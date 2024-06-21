<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessCodeRequest;
use App\Http\Requests\EnvironmentRequest;
use App\Models\Definition;
use App\Models\Environment;
use App\Models\EnvironmentAccess;
use App\Models\UserDefinition;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentController extends Controller
{
    private $endpoint;
    private $token;

    public function __construct()
    {
        $this->endpoint = env('MASTER_NODE_IP','127.0.0.1:6443');
        $this->token = "Bearer " . env('MASTER_NODE_TOKEN');
    }
    
    public function index()
    {
        $userId = Auth::user()->id;
        $environments = Environment::whereHas('userDefinition', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return view('environments.index', compact('environments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userId = Auth::user()->id;
        $user_definitions = UserDefinition::where('user_id', $userId)->get();

        return view('environments.create', compact('user_definitions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnvironmentRequest $request)
    {
        //TODO: VERIFY IF USER DEFINITION BELONGS TO LOGGED USER
        //TODO: VERIFY IF ENVIRONMENT IS READY (PODS HAVE IP)
        $formData = $request->validated();

        if (Environment::where('name',$formData['name'])->whereNull('end_date')->exists()) {
            $errormsg['code']= '400';
            $errormsg['status']= 'Bad Request';
            $errormsg['message']= 'The name is already under use';
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        $environment = Environment::create([
            'name' => $formData['name'],
            'user_definition_id' => $formData['definition'],
            'access_code' => $formData['access_code'],
            'quantity' => $formData['quantity'],
            'description' => $formData['description'],
        ]);
        
        $definitionFile = file_get_contents(storage_path('app/'.$environment->userDefinition->definition->path));
        $definition = json_decode($definitionFile, true);

        for ($i=0; $i < $formData['quantity']; $i++) { 
            $environmentAccess = EnvironmentAccess::create([
                'environment_id' => $environment->id,
                'user_id' => null,
                'description' => $environment->userDefinition->definition->description
            ]);

            $status = $this->createNamespace($environment,$environmentAccess,$i);
            
            if ($status != 0) {
                $environment->delete();
                $this->deleteNamespace($environment);
                return redirect()->back()->withInput()->with('error_msg', $status);
            }
            
            foreach ($definition['items'] as $resource) {
                $namespace = $environment->name .'-' . $environment->id . '-env-' . $i+1;
                
                $rawData = json_encode($resource);
                $rawData = str_replace('{*NAMESPACE*}',$namespace,$rawData);
                $rawData = str_replace('"{*ACCESS_PORT*}"',$formData['port']+$i,$rawData);
                $status = $this->createResource(json_decode($rawData, true), $namespace);

                if ($status != 0) {
                    $environment->delete();
                    $this->deleteNamespace($environment);
                    return redirect()->back()->withInput()->with('error_msg', $status);
                }
            }
        }

        return redirect()->route('Environments.show',$environment->id)->with('success-msg', "Namespace '". $formData['name'] ."' was added with success");
    }

    public function show($id)
    {
        $environmentAccesses = EnvironmentAccess::where('environment_id', $id)->get();
        
        return view('environments.show', compact('environmentAccesses'));
    }
    
    public function destroy($id)
    {
        $environment = Environment::findOrFail($id);

        $environment->update([
            'end_date' => date('Y-m-d H:i:s')
        ]);

        $this->deleteNamespace($environment);

        return redirect()->route('Environments.index')->with('success-msg', 'Environment ended successfully.');
    }

    private function createResource($resource,$namespace) {
        try {
            $client = new Client([
                'base_uri' => $this->endpoint,
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($resource),
                'verify' => false
            ]);

            $resourceEndpoint = $this->getKubernetesEndpoint($resource['kind'],$namespace);
            $response = $client->post($resourceEndpoint);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errormsg = $this->treat_error($e->getResponse()->getBody()->getContents());
            
            if ($errormsg == null) {
                $errormsg['code']= '500';
                $errormsg['status']= 'Internal Server Error';
                $errormsg['message']= $e->getMessage();
            }

            return $errormsg;
        } catch (\Exception $e) {
            $errormsg['code']= '500';
            $errormsg['status']= 'Internal Server Error';
            $errormsg['message']= $e->getMessage();

            return $errormsg;
        }
        return 0;
    }
    
    private function createNamespace($environment, $environmentAccess,$scenarioNumber) {
        $namespace = [];
        $namespace['apiVersion'] = "v1";
        $namespace['kind'] = "Namespace";
        $namespace['metadata']['name'] = $environment->name .'-' . $environment->id . '-env-' . $scenarioNumber+1;
        $namespace['metadata']['labels']['definition'] = $environment->userDefinition->definition->name;
        $namespace['metadata']['labels']['environment-id'] = ''. $environment->userDefinition->id .'';
        $namespace['metadata']['labels']['environment-access-id'] = ''. $environmentAccess->id .'';
        $namespace['metadata']['labels']['environment-number'] = ''. $scenarioNumber+1 .'';
        $namespace['spec']['finalizers'] = ['kubernetes'];

        $jsonData = json_encode($namespace);

        try {
            $client = new Client([
                'base_uri' => $this->endpoint,
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept' => 'application/json',
                ],
                'body' => $jsonData,
                'verify' => false
            ]);

            $response = $client->post("/api/v1/namespaces");
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $environment->delete();
            $errormsg = $this->treat_error($e->getResponse()->getBody()->getContents());
            
            if ($errormsg == null) {
                $errormsg['code']= '500';
                $errormsg['status']= 'Internal Server Error';
                $errormsg['message']= $e->getMessage();
            }

            return $errormsg;
        } catch (\Exception $e) {
            $errormsg['code']= '500';
            $errormsg['status']= 'Internal Server Error';
            $errormsg['message']= $e->getMessage();

            return $errormsg;
        }
        return 0;
    }

    private function getKubernetesEndpoint($resourceKind, $namespace = null) {
        switch ($resourceKind) {
            // Core API (v1)
            case 'Service':
                return "/api/v1/namespaces/{$namespace}/services";
            case 'ConfigMap':
                return "/api/v1/namespaces/{$namespace}/configmaps";
            case 'Secret':
                return "/api/v1/namespaces/{$namespace}/secrets";
            case 'PersistentVolume':
                return "/api/v1/persistentvolumes";
            case 'PersistentVolumeClaim':
                return "/api/v1/namespaces/{$namespace}/persistentvolumeclaims";
            case 'Event':
                return "/api/v1/namespaces/{$namespace}/events";
            case 'Endpoint':
                return "/api/v1/namespaces/{$namespace}/endpoints";
            case 'ReplicationController':
                return "/api/v1/namespaces/{$namespace}/replicationcontrollers";
            case 'ServiceAccount':
                return "/api/v1/namespaces/{$namespace}/serviceaccounts";
            case 'ResourceQuota':
                return "/api/v1/namespaces/{$namespace}/resourcequotas";
            case 'LimitRange':
                return "/api/v1/namespaces/{$namespace}/limitranges";
            case 'PodTemplate':
                return "/api/v1/namespaces/{$namespace}/podtemplates";
            
            // Apps API (apps/v1)
            case 'Deployment':
                return "/apis/apps/v1/namespaces/{$namespace}/deployments";
            case 'StatefulSet':
                return "/apis/apps/v1/namespaces/{$namespace}/statefulsets";
            case 'DaemonSet':
                return "/apis/apps/v1/namespaces/{$namespace}/daemonsets";
            case 'ReplicaSet':
                return "/apis/apps/v1/namespaces/{$namespace}/replicasets";
    
            // Batch API (batch/v1)
            case 'Job':
                return "/apis/batch/v1/namespaces/{$namespace}/jobs";
            case 'CronJob':
                return "/apis/batch/v1/namespaces/{$namespace}/cronjobs";
    
            // Autoscaling API (autoscaling/v1, autoscaling/v2)
            case 'HorizontalPodAutoscaler':
                return "/apis/autoscaling/v1/namespaces/{$namespace}/horizontalpodautoscalers";
    
            // Networking API (networking.k8s.io/v1)
            case 'Ingress':
                return "/apis/networking.k8s.io/v1/namespaces/{$namespace}/ingresses";
            case 'NetworkPolicy':
                return "/apis/networking.k8s.io/v1/namespaces/{$namespace}/networkpolicies";
    
            // Policy API (policy/v1)
            case 'PodDisruptionBudget':
                return "/apis/policy/v1/namespaces/{$namespace}/poddisruptionbudgets";
            
            default:
                return -1;
        }
    }

    private function deleteNamespace(Environment $environment) {
        for ($i=0; $i < $environment->quantity; $i++) { 
            try {
                $name= $environment->name .'-'. $environment->id .'-env-'. $i+1;
                $client = new Client([
                    'base_uri' => $this->endpoint,
                    'headers' => [
                        'Authorization' => $this->token,
                    ],
                    'verify' => false
                ]);

                $response = $client->delete("/api/v1/namespaces/" . $name);
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    private function treat_error($errorMessage) 
    {
        $error = null;

        $jsonData = json_decode($errorMessage, true);

        if (isset($jsonData['message']))
            $error['message'] = $jsonData['message'];
        if (isset($jsonData['status']))
            $error['status'] = $jsonData['status'];
        if (isset($jsonData['code']))
            $error['code'] = $jsonData['code'];
        
        return $error;
    }
}
