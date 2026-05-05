<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::withCount('posts')->latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100|unique:categories,name',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Categories::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created.');
    }

    public function destroy(Categories $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}