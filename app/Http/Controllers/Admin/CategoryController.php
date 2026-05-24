<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')->orderBy('name', 'asc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name', 'is_active' => 'required|boolean']);
        Category::create($request->all());
        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan!']);
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,'.$category->id, 'is_active' => 'required|boolean']);
        $category->update($request->all());
        return response()->json(['success' => true, 'message' => 'Kategori diperbarui!']);
    }

    public function destroy(Category $category)
    {
        if ($category->menus()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Kategori tidak bisa dihapus karena masih memiliki menu!'], 400);
        }
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Kategori dihapus!']);
    }
}