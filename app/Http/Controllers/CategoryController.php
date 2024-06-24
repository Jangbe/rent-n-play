<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create([
            'name' => $request->get('name')
        ]);
        return response()->json('Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate(['name' => 'required']);
        $category->update([
            'name' => $request->get('name')
        ]);

        return response()->json('Kategori berhasil diubah');
    }

    public function destroy($id)
    {
        $category = Category::findorFail($id);
        $category->delete();
        return response()->json('Kategori berhasil dihapus');
    }
}
