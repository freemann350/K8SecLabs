<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return view('categories.index', ['categories' => $categories]);
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $formData = $request->validated();

            $category = Category::create($request->all());

            return redirect()->route('Categories.index')->with('success-msg', "Category '$category->name' created successfully.");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('categories.edit', compact('category'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $formData = $request->validated();  

            $category = Category::findOrFail($id);
            $category->update($request->all());
    
            return redirect()->route('Categories.index')->with('success-msg', "Category '$category->name' updated successfully.");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            $category->delete();
            return redirect()->route('Categories.index')->with('success-msg', "Category '$category->name' was deleted successfully");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    private function createError($code, $status, $message) {
        $errormsg['code']= $code;
        $errormsg['status']= $status;
        $errormsg['message']= $message;

        return $errormsg;
    }
}
