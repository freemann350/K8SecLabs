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
        $definitions = Definition::where('user_id', Auth::user()->id)->get();
        $userDefinitions = UserDefinition::where('user_id', Auth::user()->id)->get();
        return view('definitions.index', compact('definitions','userDefinitions'));
    }

    public function catalog()
    {
        $userId = Auth::id();
        $definitions = Definition::whereDoesntHave('userDefinitions', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return view('definitions.catalog', compact('definitions'));
    }

    public function create()
    {
        $categories = Category::all();
        if ($categories->count() == 0) {
            $errormsg = $this->createError('403','Forbidden', 'There are no categories in order to create a definition');
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        $users = User::all();


        return view('definitions.create', compact('categories', 'users'));
    }

    public function store(DefinitionRequest $request)
    {
        $formData = $request->validated();

        $definition = Definition::create([
            'name' => $formData['name'],
            'user_id' => Auth::user()->id,
            'category_id' => $formData['category'],
            'path' => 0,
            'description' => $formData['description'],
            'private' => $formData['private'] == 1 ? 1 : 0,
            'tags' => $formData['tags'],
        ]);

        $file = $formData['definition'];
        $fileName = $formData['name'] . '.json';
        $filePath = 'definitions/' . $fileName;
        Storage::put($filePath, file_get_contents($file));

        $definition->path = $filePath;
        $definition->save();

        UserDefinition::create([
            'user_id' => Auth::id(),
            'definition_id' => $definition->id
        ]);

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition created successfully.');
    }

    public function show($id)
    {
        $definition = Definition::findOrfail($id);

        $json = Storage::get($definition->path);
        $tags = explode(',', $definition->tags);
        
        $variables = $this->extractVariables($json);
        $variables = array_unique($variables);

        return view('definitions.show', compact('definition', 'json', 'tags', 'variables'));
    }

    public function edit($id)
    {
        $definition = Definition::findOrfail($id);
        $categories = Category::all();
        
        if ($categories->count() == 0) {
            $errormsg = $this->createError('403','Forbidden', 'There are no categories in order to edit this definition');
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        $users = User::all();
        return view('definitions.edit', compact('definition', 'categories', 'users'));
    }

    public function update(UpdateDefinitionInfoRequest $request, $id)
    {
        $formData = $request->validated();
        $definition = Definition::findOrfail($id);
        $fileName = $formData['name'] . '.json';
        $filePath = 'definitions/' . $fileName;
        Storage::move($definition->path, $filePath);

        $definition->update([
            'name' => $formData['name'],
            'category_id' => $formData['category'],
            'description' => $formData['description'],
            'private' => $formData['private'] == 1 ? 1 : 0,
            'tags' => $formData['tags'],
        ]);
        
        $definition->update([
            'path' => $filePath
        ]);

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition updated successfully.');
    }

    public function updateDefinition(UpdateDefinitionConfigRequest $request, $id)
    {
        $formData = $request->validated();

        $definition = Definition::findOrfail($id);

        $file = $formData['definition'];
        $fileName = $definition->name . '.json';
        $filePath = 'definitions/' . $fileName;
        Storage::put($filePath, file_get_contents($file));
        
        $definition->update([
            'path' => $filePath
        ]);

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition updated successfully.');
    }

    public function addDefinition($id) 
    {
        //ToDO: VALIDATE IF DEFINITION IS PRIVATE
        
        $user = Auth::user()->id;
        $definition = Definition::findOrFail($id);
        
        if ($definition->user_id != Auth::id() && $definition->private == 1) {
            $errormsg = $this->createError('403','Forbidden','The definition "'.$definition->name.'" is private');
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        $exists = UserDefinition::where("user_id",$user)->where("definition_id",$definition->id)->exists();
        if ($exists) {
            $errormsg = $this->createError('405','Method Not Allowed','You already have the Definition "'.$definition->name.'"');
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }

        UserDefinition::create([
            'user_id' => Auth::user()->id,
            'definition_id' => $id,
        ]);

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition "'.$definition->name.'" added sucessfully updated successfully.');
    }
    public function removeDefinition($id)
    {
        $userDefinition = UserDefinition::findOrFail($id);
        $definition = Definition::findOrFail($userDefinition->definition_id);
        
        $userDefinition->delete();

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition "'.$definition->name .'" was removed from your definitions successfully.');
    }

    public function destroy($id)
    {
        $definition = Definition::findOrFail($id);
        
        if (Storage::exists($definition->path)) {
            Storage::delete($definition->path);
        }

        $definition->delete();

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition deleted successfully.');
    }

    public function download($id)
    {
        $definition = Definition::findOrFail($id);
        $filePath = storage_path("app/$definition->path");
        return response()->download($filePath);
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
