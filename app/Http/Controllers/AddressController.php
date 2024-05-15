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
        Address::create([
            'user_id' => $request->get('user_id', $request->user()->id),
            'type' => $request->get('type'),
            'street_name' => $request->get('street_name'),
            'contact_name' => $request->get('contact_name'),
            'contact_phone' => $request->get('contact_phone'),
            'detail' => $request->get('detail'),
        ]);
        return response()->json("Address berhasil di tambahkan");
    }

    public function show($user_id)
    {
        $addresses = Address::where('user_id', $user_id)->get();
        return response()->json($addresses);
    }

    public function update(Request $request, $id)
    {
        $Address = Address::find($id);

        if (!$Address) {
            return response()->json("Data Address tidak ada", 404);
        }

        $Address->update([
            'user_id' => $request->get('user_id'),
            'type' => $request->get('type'),
            'street_name' => $request->get('street_name'),
            'contact_name' => $request->get('contact_name'),
            'contact_phone' => $request->get('contact_phone'),
            'detail' => $request->get('detail'),
        ]);

        return response()->json("data Address berhasil di ubah");
    }

    public function destroy($id)
    {
        $Address = Address::findorFail($id);
        $Address->delete();
        return response()->json('telah di hapus');
    }
}
