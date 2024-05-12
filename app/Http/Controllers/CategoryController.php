<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories= Category::all();
        return response()->json($categories);
    }

    public function store(Request $request){
        Category::create([
            'name' => $request->get('nama')
        ]);
        return response()->json('Berhasi di tambahkan');

    }

    public function update(Request $request, $id){
        $category = Category::find($id);
        ///dd($category);

        if (!$category) {
           return response()->json('Data tidak ada', 404);
        }

        $category->update([
            'name' => $request->get('nama')
        ]);

    }

    public function destroy($id){
        $category = Category::findorFail($id);
        $category->delete();
        return response()->json('telah di hapus');
    }
}
