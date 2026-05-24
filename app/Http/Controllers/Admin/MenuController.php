<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        // Panggil menu beserta relasi kategorinya
        $menus = Menu::with('category')->orderBy('name', 'asc')->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:menus,name',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'required|boolean'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($data);
        return response()->json(['success' => true, 'message' => 'Menu berhasil ditambahkan!']);
    }

    public function edit(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:menus,name,'.$menu->id,
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'required|boolean'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image) { Storage::disk('public')->delete($menu->image); }
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);
        return response()->json(['success' => true, 'message' => 'Menu berhasil diperbarui!']);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) { Storage::disk('public')->delete($menu->image); }
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus!']);
    }

    public function toggleAvailable(Menu $menu)
    {
        $menu->update(['is_available' => !$menu->is_available]);
        $status = $menu->is_available ? 'Tersedia' : 'Kosong';
        return response()->json([
            'success' => true,
            'message' => "Status menu \"$menu->name\" berhasil diubah ke $status!",
            'is_available' => $menu->is_available
        ]);
    }
}