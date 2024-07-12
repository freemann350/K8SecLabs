<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDefinitionConfigRequest;
use App\Http\Requests\UpdateDefinitionInfoRequest;
use App\Models\UserDefinition;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DefinitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Definition;
use App\Models\Category;
use App\Models\User;

class DefinitionController extends Controller
{
    public function index()
    {
        try {
            $definitions = Definition::where('user_id', Auth::id())->get();
            $userDefinitions = UserDefinition::where('user_id', Auth::id())->get();
            return view('definitions.index', compact('definitions','userDefinitions'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function catalog()
    {
        try {
            $userId = Auth::id();
            $definitions = Definition::whereDoesntHave('userDefinitions', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    
            return view('definitions.catalog', compact('definitions'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            if ($categories->count() == 0) {
                $errormsg = $this->createError('403','Forbidden', 'There are no categories in order to create a definition');
                return redirect()->back()->withInput()->with('error_msg', $errormsg);
            }
    
            $users = User::all();
    
            return view('definitions.create', compact('categories', 'users'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function store(DefinitionRequest $request)
    {
        try {
            $formData = $request->validated();

            $fileName = $formData['name'] . '.json';
            $filePath = 'definitions/' . $fileName;

            $definition = Definition::create([
                'name' => $formData['name'],
                'user_id' => Auth::id(),
                'category_id' => $formData['category'],
                'path' => $filePath,
                'description' => $formData['description'],
                'private' => $formData['private'] == 1 ? 1 : 0,
                'tags' => $formData['tags'],
            ]);
    
            $file = $formData['definition'];
            Storage::put($filePath, file_get_contents($file));
            
            //ASSOCIATES THE NEWLY CREATED DEFINITION TO THE USER SO THEY CAN USE IT
            UserDefinition::create([
                'user_id' => Auth::id(),
                'definition_id' => $definition->id
            ]);
    
            return redirect()->route('Definitions.index')->with('success-msg', 'Definition created successfully.');
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function show($id)
    {
        try {
            $definition = Definition::findOrfail($id);
            $definition->description = str_replace('{*ENV_IPS*}','<code>{*ENV_IPS*}</code>',$definition->description);
    
            $json = Storage::get($definition->path);
            $tags = explode(',', $definition->tags);
            
            $variables = $this->extractVariables($json);
            $variables = array_unique($variables);
    
            return view('definitions.show', compact('definition', 'json', 'tags', 'variables'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function edit($id)
    {
        try {
            $definition = Definition::findOrfail($id);
            $categories = Category::all();
            
            if ($categories->count() == 0) {
                $errormsg = $this->createError('403','Forbidden', 'There are no categories in order to edit this definition');
                return redirect()->back()->withInput()->with('error_msg', $errormsg);
            }
    
            $users = User::all();
            return view('definitions.edit', compact('definition', 'categories', 'users'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function update(UpdateDefinitionInfoRequest $request, $id)
    {
        try {
            //UPDATES THE DEFINITION'S FILENAME BASED ON THE NEW INPUTTED NAME
            $formData = $request->validated();
            $definition = Definition::findOrfail($id);
            $fileName = $formData['name'] . '.json';
            $filePath = 'definitions/' . $fileName;

            $definition->update([
                'name' => $formData['name'],
                'category_id' => $formData['category'],
                'description' => $formData['description'],
                'private' => $formData['private'] == 1 ? 1 : 0,
                'path' => $filePath,
                'tags' => $formData['tags'],
            ]);

            Storage::move($definition->path, $filePath);

            return redirect()->route('Definitions.index')->with('success-msg', 'Definition updated successfully.');
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function updateDefinition(UpdateDefinitionConfigRequest $request, $id)
    {
        try {
            $formData = $request->validated();

            $definition = Definition::findOrfail($id);
    
            $file = $formData['definition'];
            $fileName = $definition->name . '.json';
            $filePath = 'definitions/' . $fileName;
            
            $definition->update([
                'path' => $filePath
            ]);
    
            Storage::put($filePath, file_get_contents($file));
    
            return redirect()->route('Definitions.index')->with('success-msg', "Definition '$definition->name' updated successfully.");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function addDefinition($id) 
    {
        try {
            $user = Auth::id();
            $definition = Definition::findOrFail($id);
            
            if ($definition->user_id != Auth::id() && $definition->private == 1) {
                $errormsg = $this->createError('403','Forbidden',"The definition '$definition->name' is private");
                return redirect()->back()->withInput()->with('error_msg', $errormsg);
            }
    
            $exists = UserDefinition::where("user_id",$user)->where("definition_id",$definition->id)->exists();
            if ($exists) {
                $errormsg = $this->createError('405','Method Not Allowed',"You already have the Definition '$definition->name'");
                return redirect()->back()->withInput()->with('error_msg', $errormsg);
            }
    
            UserDefinition::create([
                'user_id' => Auth::id(),
                'definition_id' => $id,
            ]);
    
            return redirect()->route('Definitions.index')->with('success-msg', "Definition '$definition->name' added successfully.");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function removeDefinition($id)
    {
        try {
            $userDefinition = UserDefinition::findOrFail($id);
            $definition = Definition::findOrFail($userDefinition->definition_id,'name');
            
            $userDefinition->delete();
    
            return redirect()->route('Definitions.index')->with('success-msg', "Definition '$definition->name' was removed from your definitions successfully.");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function destroy($id)
    {
        try {
            $definition = Definition::findOrFail($id);
            $name = $definition->name;
        
            if (Storage::exists($definition->path)) {
                Storage::delete($definition->path);
            }
            
            UserDefinition::where('definition_id', $id)->delete();
            $definition->delete();

            return redirect()->route('Definitions.index')->with('success-msg', "Definition '$name' deleted successfully.");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function download($id)
    {
        try {
            $definition = Definition::findOrFail($id);
            $filePath = storage_path("app/$definition->path");
            return response()->download($filePath);
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    function extractVariables($jsonString)
    {
        preg_match_all('/\{\*([^\}]*)\*\}/', $jsonString, $matches);
        return $matches[1]; // Return only the captured values
    }

    private function createError($code, $status, $message) {
        $errormsg['code']= $code;
        $errormsg['status']= $status;
        $errormsg['message']= $message;

        return $errormsg;
    }
}
