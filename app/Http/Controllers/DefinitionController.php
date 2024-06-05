<?php

namespace App\Http\Controllers;

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
        $definitions = Definition::where('user_id', Auth::user()->user_id)->get();
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
            'user_id' => Auth::user()->user_id,
            'category_id' => $formData['category'],
            'path' => 0,
            'description' => $formData['description'],
            'private' => $formData['private'] == 1 ? true : false,
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
        $definition = Definition::find($id);
        return view('definitions.show', compact('definition'));
    }

    public function edit($id)
    {
        $definition = Definition::find($id);
        $categories = Category::all();
        $users = User::all();
        return view('Definitions.edit', compact('definition', 'categories', 'users'));
    }

    public function update(DefinitionRequest $request, $id)
    {
        $formData = $request->validated();
        
        $definition = Definition::find($id);
        $definition->update($request->all());

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
}
