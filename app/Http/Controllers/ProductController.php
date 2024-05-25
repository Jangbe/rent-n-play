<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->with('category');
        if (request()->filled('category')) {
            $products->whereHas('category', fn ($q) => $q->where('id', request('category')));
        }
        return DataTables::of($products)->toJson();
    }

    public function carts(Request $request)
    {
        return response()->json(Product::with('category')->whereIn('id', $request->id)->get());
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:200',
            'description' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
            'picture' => 'required|file|image'
        ]);
        $validate['picture'] = $request->file('picture')->store('pictures');
        Product::create($validate);
        return response()->json("product berhasil di tambahkan");
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validate = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:200',
            'description' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if ($request->hasFile('picture')) {
            if (Storage::exists($product->getRawOriginal('picture'))) {
                Storage::delete($product->getRawOriginal('picture'));
            }
            $validate['picture'] = $request->file('picture')->store('pictures');
        }

        $product->update($validate);

        return response()->json("data product berhasil di ubah");
    }

    public function destroy($id)
    {
        $product = Product::findorFail($id);
        if (Storage::exists($product->getRawOriginal('picture'))) {
            Storage::delete($product->getRawOriginal('picture'));
        }
        $product->delete();
        return response()->json('telah di hapus');
    }
}
