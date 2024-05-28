<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Definition;
use App\Models\Category;
use App\Models\User;

class DefinitionController extends Controller
{
    public function index()
    {
        $definitions = Definition::all();
        return view('definitions.index', compact('definitions'));
    }

    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        return view('definitions.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'path' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
            'private' => 'required',
            'description' => 'nullable',
            'checksum' => 'nullable',
            'tags' => 'nullable',
        ]);

        Definition::create($request->all());

        return redirect()->route('definitions.index')->with('success-msg', 'Definition created successfully.');
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
        return view('definitions.edit', compact('definition', 'categories', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'path' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
            'private' => 'required',
            'description' => 'nullable',
            'checksum' => 'nullable',
            'tags' => 'nullable',
        ]);

        $definition = Definition::find($id);
        $definition->update($request->all());

        return redirect()->route('definitions.index')->with('success-msg', 'Definition updated successfully.');
    }

    public function destroy($id)
    {
        $definition = Definition::find($id);
        $definition->delete();

        return redirect()->route('definitions.index')->with('success-msg', 'Definition deleted successfully.');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $definitions = Definition::where('name', 'LIKE', "%{$keyword}%")->get();

        return view('definitions.index', compact('definitions'));
    }
}
