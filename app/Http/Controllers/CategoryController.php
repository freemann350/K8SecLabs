<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();
        return view('categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:50',
            'training_type' => 'required|in:R,B,U',
        ]);

        Category::create($request->all());
        return redirect()->route('Categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $category_id)
    {
        $request->validate([
            'name' => 'required|max:50|unique:categories,name,' . $category_id . ',category_id',
            'training_type' => 'required|in:R,B,U',
        ]);        

        $category = Category::findOrFail($category_id);
        $category->update($request->all());

        return redirect()->route('Categories.index')->with('success', 'Category updated successfully.');
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();
        return redirect()->route('Categories.index')->with('success-msg', "$name was deleted successfully");
    }
}
