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
        $picture = $request->file('picture')->store('pictures');
        Product::create([
            'category_id' => $request->get('category_id'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'amount' => $request->get('amount'),
            'picture' => $picture
        ]);
        return response()->json("product berhasil di tambahkan");
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = [
            'category_id' => $request->get('category_id'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'amount' => $request->get('amount'),
        ];

        if ($request->hasFile('picture')) {
            if (Storage::exists($product->getRawOriginal('picture'))) {
                Storage::delete($product->getRawOriginal('picture'));
            }
            $data['picture'] = $request->file('picture')->store('pictures');
        }

        $product->update($data);

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
