<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDefinitionConfigRequest;
use App\Http\Requests\UpdateDefinitionInfoRequest;
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
        return view('definitions.index', compact('definitions'));
    }

    public function catalog()
    {
        $definitions = Definition::all();
        return view('definitions.catalog', compact('definitions'));
    }

    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        return view('definitions.create', compact('categories', 'users'));
    }

    public function store(DefinitionRequest $request)
    {
        $formData = $request->validated();

        $definitionId = Definition::create([
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

        $definitionId->path = $filePath;
        $definitionId->save();

        return redirect()->route('Definitions.index')->with('success-msg', 'Definition created successfully.');
    }

    public function show($id)
    {
        $definition = Definition::findOrfail($id);

        $json = Storage::get($definition->path);
        $tags = explode(',', $definition->tags);
        
        $variables = $this->extractVariables($json);

        return view('definitions.show', compact('definition', 'json', 'tags', 'variables'));
    }

    public function edit($id)
    {
        $definition = Definition::findOrfail($id);
        $categories = Category::all();
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
}
