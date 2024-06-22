<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessCodeRequest;
use App\Models\Environment;
use App\Models\EnvironmentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentAccessController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $environmentAccesses = EnvironmentAccess::where('user_id', $userId)->get();

        return view('environmentAccesses.index', compact('environmentAccesses'));
    }
    
    public function show($id)
    {
        $environmentAccess = EnvironmentAccess::findOrFail($id);

        return view('environmentAccesses.show', compact('environmentAccess'));
    }

    public function code($id)
    {
        $environment = Environment::findOrFail($id);
        $environmentAccesses = EnvironmentAccess::where('environment_id',$environment->id)->get();
        
        foreach ($environmentAccesses as $environmentAccess) {
            if ($environmentAccess->user_id == Auth::user()->id) {
                return redirect()->route('EnvironmentAccesses.show',$environmentAccess->id);
            }
        }

        return view('environmentAccesses.code', ['id' => $id]);
    }

    public function join(AccessCodeRequest $request, $id)
    {
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
    }
}
