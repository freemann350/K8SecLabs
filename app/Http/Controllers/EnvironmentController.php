<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessCodeRequest;
use App\Http\Requests\EnvironmentRequest;
use App\Models\Definition;
use App\Models\Environment;
use App\Models\EnvironmentAccess;
use App\Models\UserDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $environmentId = Environment::create([
            'name' => $formData['name'],
            'user_definition_id' => $formData['definition'],
            'access_code' => $formData['access_code'],
            'quantity' => $formData['quantity'],
            'description' => $formData['description'],
        ]);

        for ($i=0; $i < $formData['quantity']; $i++) { 
            EnvironmentAccess::create([
                'environment_id' => $environmentId->id,
                'user_id' => null,
                'description' => $environmentId->userDefinition->definition->description
            ]);
        }
        
        return redirect()->route('Environments.index')->with('success-msg', 'Environment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $environments = EnvironmentAccess::where('environment_id', $id)->get();

        return view('environments.show', compact('environments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function join(AccessCodeRequest $request, $id)
    {
        $environment = Environment::findOrFail($id);
        $formData = $request->validated();
        
        if ($formData['access_code'] == $environment->access_code) {
            return redirect()->route('Environments.joinShow',$id)->with("check",true);
        } else {
            $errormsg['message'] = 'Could not join specified environment: wrong access code';
            $errormsg['code'] = "401";
            $errormsg['status'] = "Unauthorized";
            return redirect()->back()->with('error_msg', $errormsg);
        }
    }
    
    public function joinShow(Request $request, $id)
    {
        if (!session('check'))
            return redirect()->route('Dashboard');
        $environment = Environment::findOrFail($id);
        $environments = EnvironmentAccess::where('environment_id',$environment->id)->get();
        
        foreach ($environments as $environment) {
            if ($environment->user_id == Auth::user()->id) {
                return view('environments.userAccess',compact('environment'));
            }
        }
        return view('environments.access', compact('environments'));
    }

    public function access($id)
    {
        $environment = EnvironmentAccess::findOrFail($id);
        
        if ($environment->user_id == null) {
            $environment->update([
                "user_id" => Auth::user()->id
            ]);
            return view('environments.userAccess', compact('environment'));
        } else {
            $errormsg['message'] = 'A user is already using this environment';
            $errormsg['code'] = "401";
            $errormsg['status'] = "Unauthorized";
            return redirect()->back()->withInput()->with('error_msg', $errormsg)->with("check",true);
        }
    }

    public function userAccess($id)
    {
        $environment = EnvironmentAccess::findOrFail($id);
        
        return view('environments.userAccess',compact('environment'));
    }
    
    public function destroy($id)
    {
        $environment = Environment::findOrFail($id);

        $environment->update([
            'end_date' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('Environments.index')->with('success-msg', 'Environment ended successfully.');
    }
}
