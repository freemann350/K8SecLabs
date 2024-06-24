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
        $definitionCount = $user_definitions->count();

        if ($definitionCount == 0) {
            $errormsg = $this->createError('403','Forbidden', 'You have no definitions to create start an environment');
        }

        return view('environments.create', compact('user_definitions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnvironmentRequest $request)
    {
        //TODO: VERIFY IF ENVIRONMENT IS READY (PODS HAVE IP)
        
        $formData = $request->validated();

        if (Environment::where('name',$formData['name'])->whereNull('end_date')->exists()) {
            $errormsg = $this->createError('400','Bad Request', 'The name is already under use');
            
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        $environment = Environment::create([
            'name' => $formData['name'],
            'user_definition_id' => $formData['definition'],
            'access_code' => $formData['access_code'],
            'quantity' => $formData['quantity'],
            'description' => $formData['description'],
        ]);
        
        try {
            $definitionFile = file_get_contents(storage_path('app/'.$environment->userDefinition->definition->path));
            $definition = json_decode($definitionFile, true);

            for ($i=0; $i < $formData['quantity']; $i++) { 
                $port = $formData['port']+$i;
                
                $description = $environment->userDefinition->definition->description;
                $description = str_replace('{*ENDPOINT*}','http://'. env('MASTER_NODE_IP_ACCESS') .':'. $port,$description);
                
                $environmentAccess = EnvironmentAccess::create([
                    'environment_id' => $environment->id,
                    'user_id' => null,
                    'description' => $description
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
                    $rawData = str_replace('"{*ACCESS_PORT*}"',$port,$rawData);
                    
                    $rawData = $this->transformVariables($rawData, $formData);

                    if (isset($rawData['not_ok']) && $rawData['not_ok'] == true) {
                        $environment->delete();
                        unset($rawData['not_ok']);
                        $variablesDetected = implode(', ', $rawData);
                        
                        $this->deleteNamespace($environment);

                        $errormsg = $this->createError('500','Internal Server Error', "There are variables yet not treated. Untreated variables: $variablesDetected");
                        return redirect()->back()->withInput()->with('error_msg', $errormsg);
                    }
                    
                    $status = $this->createResource(json_decode($rawData, true), $namespace);

                    if ($status != 0) {
                        $environment->delete();
                        $this->deleteNamespace($environment);
                        return redirect()->back()->withInput()->with('error_msg', $status);
                    }
                    
                }
            }
        } catch (\Exception $e) {
            $environment->delete();
            for ($i=0; $i < $formData['quantity']; $i++) { 
                $this->deleteNamespace($environment);
            }
            $errormsg = $this->createError('500','Internal Server Error', $e->getMessage());
            
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        return redirect()->route('Environments.show',$environment->id)->with('success-msg', "Environment '". $formData['name'] ."' was added with success");
    }

    public function show($id)
    {
        $environmentAccesses = EnvironmentAccess::where('environment_id', $id)->get();
        
        return view('environments.show', compact('environmentAccesses'));
    }
    
    public function destroy($id)
    {
        $environment = Environment::findOrFail($id);

        if ($environment->end_date == null) {
            $environment->update([
                'end_date' => date('Y-m-d H:i:s')
            ]);
    
            $this->deleteNamespace($environment);
        } else {
            $environment->delete();
        }

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
                'timeout' => 5,
                'verify' => false
            ]);

            $resourceEndpoint = $this->getKubernetesEndpoint($resource['kind'],$namespace);
            $response = $client->post($resourceEndpoint);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errormsg = $this->treat_error($e->getResponse()->getBody()->getContents());
            
            if ($errormsg == null) {
                $errormsg = $this->createError('500','Internal Server Error', $e->getMessage());
            }

            return $errormsg;
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error', $e->getMessage());

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
                'timeout' => 5,
                'verify' => false
            ]);

            $response = $client->post("/api/v1/namespaces");
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $environment->delete();
            $errormsg = $this->treat_error($e->getResponse()->getBody()->getContents());
            
            if ($errormsg == null) {
                $errormsg = $this->createError('500','Internal Server Error', $e->getMessage());
            }

            return $errormsg;
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error', $e->getMessage());

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
                    'timeout' => 5,
                    'verify' => false
                ]);

                $response = $client->delete("/api/v1/namespaces/" . $name);
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    private function transformVariables($definition, $data) {
        unset($data['name']);
        unset($data['definition']);
        unset($data['access_code']);
        unset($data['quantity']);
        unset($data['port']);
        unset($data['description']);

        if (isset($data['str_name'])) {
            foreach ($data['str_name'] as $key => $string) {
                $definition = str_replace('{*'. $string .'*}',$data['str_val'][$key],$definition);
            }
        }

        if (isset($data['num_name'])) {
            foreach ($data['num_name'] as $key => $num) {
                $definition = str_replace('"{*'. $num .'*}"',$data['num_val'][$key],$definition);
            }
        }

        if (isset($data['rand_name'])) {
            foreach ($data['rand_name'] as $key => $rand) {
                $randNumber = rand($data['min'][$key], $data['max'][$key]);
                $definition = str_replace('{*'. $rand .'*}',$randNumber,$definition);
            }
        }

        if (isset($data['flag_name'])) {
            foreach ($data['flag_name'] as $key => $flag) {
                $flagVal = isset($data['flag_val'][$key]) && !is_null($data['flag_val'][$key]) ? $data['flag_val'][$key] : hash('sha256',bin2hex(random_bytes(16)));
                $definition = str_replace('{*'. $flag .'*}',$flagVal,$definition);
            }
        }

        $detectedVariables = [];
        if (preg_match_all('/\{\*(.*?)\*\}/', $definition, $matches)) {
            $detectedVariables = $matches[1];
            $detectedVariables['not_ok'] = true;
            return $detectedVariables;
        }
        return $definition;
    }

    private function createError($code, $status, $message) {
        $errormsg['code']= $code;
        $errormsg['status']= $status;
        $errormsg['message']= $message;

        return $errormsg;
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
