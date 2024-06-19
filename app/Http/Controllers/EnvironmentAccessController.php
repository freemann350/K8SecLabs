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
        $environments = EnvironmentAccess::where('environment_id',$environment->id)->get();
        
        foreach ($environments as $environment) {
            if ($environment->user_id == Auth::user()->id) {
                return view('dashboard.show',compact('environment'));
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
            foreach ($environmentAccesses as $environmentAccess) {
                if ($environmentAccess->user_id == null) {
                    $environmentAccess->update([
                        'user_id' => Auth::user()->id
                    ]);

                    return redirect()->route('EnvironmentAccesses.show',$environmentAccess->id);
                }
            }
        } else {
            $errormsg['message'] = 'Could not join specified environment: wrong access code';
            $errormsg['code'] = "401";
            $errormsg['status'] = "Unauthorized";
            return redirect()->back()->with('error_msg', $errormsg);
        }
    }
}
