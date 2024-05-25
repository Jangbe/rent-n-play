<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'type' => 'required',
            'street_name' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'detail' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $validate['user_id'] = $request->user()->id;
        Address::create($validate);
        return response()->json("Data alamat berhasil di tambahkan");
    }

    public function show($user_id)
    {
        if ($user_id != auth()->id()) return abort(404);
        $addresses = Address::where('user_id', $user_id)->get();
        return response()->json($addresses);
    }

    public function update(Request $request, $id)
    {
        $Address = Address::findOrFail($id);

        $validate = $request->validate([
            'type' => 'required',
            'street_name' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'detail' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $validate['user_id'] = $request->user()->id;
        $Address->update($validate);

        return response()->json("Data alamat berhasil di ubah");
    }

    public function destroy($id)
    {
        $Address = Address::findOrFail($id);
        $Address->delete();
        return response()->json('Data alamat berhasil di hapus');
    }
}
