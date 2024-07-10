<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessCodeRequest;
use App\Models\Environment;
use App\Models\EnvironmentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class EnvironmentAccessController extends Controller
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
        try {
            $userId = Auth::user()->id;
            $environmentAccesses = EnvironmentAccess::where('user_id', $userId)->get();
    
            return view('environmentAccesses.index', compact('environmentAccesses'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }
    
    public function show($id)
    {
        try {
            $environmentAccess = EnvironmentAccess::findOrFail($id);

            if ($environmentAccess->user_id == Auth::id()) {
                $environmentAccess->update([
                    'last_access' => date('Y-m-d H:i:s')
                ]);
            }
    
            preg_match_all('/\{\*ENV_IPS\*\}/', $environmentAccess->description, $matches);
            
            $client = new Client([
                'base_uri' => $this->endpoint,
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept' => 'application/json',
                ],
                'timeout' => 5,
                'verify' => false
            ]);
    
            $response = $client->get("/api/v1/namespaces/".$environmentAccess->environment->name."-". $environmentAccess->environment->id ."-env-". $environmentAccess->id ."/pods");
            $jsonData = json_decode($response->getBody(), true);
    
            $info = [];
            foreach($jsonData['items'] as $item) {
                if (isset($item['metadata']['labels']['show-data']) && $item['metadata']['labels']['show-data'] == "false")
                    continue;
                    
                    $data["name"] = $item['metadata']['name'];
                    $data["ip"] = isset($item['status']['podIP']) ? $item['status']['podIP'] : "Not ready";
                    $data["status"] = isset($item['status']['phase']) && $item['status']['phase'] == "Running" ? "Ready" : "Not Ready";
                    array_push($info,$data);
            }
    
            $status = "R";
            foreach ($info as $state) {
                if ($state['status'] == "Not Ready") {
                    $status = "NR";
                    break;
                }
            }
    
            if (!empty($matches[0])) {
                $html = "
                <h3>Environment IPs</h3>
                <table class=\"table table-striped text-center\" id=\"dt1\">\n
                    <thead>\n
                        <tr>\n
                            <th class=\"text-center\">Name</th>\n
                            <th class=\"text-center\">IP</th>\n
                            <th class=\"text-center\">Status</th>\n
                        </tr>\n
                    </thead>\n
                    <tbody>\n
                ";
    
                foreach ($info as $data) {
                    $html .= "
                        <tr>\n
                        <td>{$data['name']}</td>\n
                        <td>{$data['ip']}</td>\n";
                    $html .= $data['status'] == "Ready" ? "<td><label class=\"badge badge-success\"> Ready </label></td>\n" : "<td><label class=\"badge badge-danger\"> Not Ready </label></td>\n";
                    $html .= "</tr>\n";
                }
    
                $html .= "
                    </tbody>\n
                </table>
                ";
    
                if ($environmentAccess->environment->end_date == null) {
                    $environmentAccess->description = str_replace('{*ENV_IPS*}',$html,$environmentAccess->description);
                } else {
                    $environmentAccess->description = str_replace('{*ENV_IPS*}','',$environmentAccess->description);
                }
            }
            return view('environmentAccesses.show', compact('environmentAccess','status'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function code($id)
    {
        try {
            $environment = Environment::findOrFail($id);
            $environmentAccesses = EnvironmentAccess::where('environment_id',$environment->id)->get();
            
            foreach ($environmentAccesses as $environmentAccess) {
                if ($environmentAccess->user_id == Auth::user()->id) {
                    return redirect()->route('EnvironmentAccesses.show',$environmentAccess->id);
                }
            }
    
            return view('environmentAccesses.code', ['id' => $id]);
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function join(AccessCodeRequest $request, $id)
    {
        try {
            $environment = Environment::findOrFail($id);
            $formData = $request->validated();
            
            if ($formData['access_code'] == $environment->access_code) {
                $environmentAccesses = EnvironmentAccess::where("environment_id",$id)->get();
                $remainingAccesses = $environmentAccesses->where("user_id", null)->count();
    
                if ($remainingAccesses > 0) {
                    foreach ($environmentAccesses as $environmentAccess) {
                        if ($environmentAccess->user_id == null) {
                            $environmentAccess->update([
                                'user_id' => Auth::user()->id
                            ]);
    
                            return redirect()->route('EnvironmentAccesses.show',$environmentAccess->id);
                        }
    
                        if ($environmentAccess->user_id == Auth::user()->id)
                            return redirect()->route('EnvironmentAccesses.show',$environmentAccess->id);
                    }
                } else {
                    $errormsg['message'] = 'Could not join specified environment: there are no more environments remaining';
                    $errormsg['code'] = "404";
                    $errormsg['status'] = "Not Found";
                    return redirect()->back()->with('error_msg', $errormsg);
                }
            } else {
                $errormsg['message'] = 'Could not join specified environment: wrong access code';
                $errormsg['code'] = "401";
                $errormsg['status'] = "Unauthorized";
                return redirect()->back()->with('error_msg', $errormsg);
            }
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    private function createError($code, $status, $message) {
        $errormsg['code']= $code;
        $errormsg['status']= $status;
        $errormsg['message']= $message;

        return $errormsg;
    }
}
