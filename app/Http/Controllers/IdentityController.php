<?php

namespace App\Http\Controllers;

use App\Models\identity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IdentityController extends Controller
{
    public function index()
    {
        $identities = Identity::all();
        return response()->json($identities);
    }

    public function store(Request $request)
    {
        $ettachment = $request->file('ettachment')->store('identities');
        Identity::create([
            'user_id' => $request->get('user_id'),
            'type' => $request->get('type'),
            'identity_number' => $request->get('identity_number'),
            'ettachment' => $ettachment
        ]);
        return response()->json("Identity berhasil di tambahkan");
    }

    public function update(Request $request, $id)
    {
        $identity = Identity::findOrFail($id);

        $data = [
            'user_id' => $request->get('user_id'),
            'type' => $request->get('type'),
            'identity_number' => $request->get('identity_number'),
        ];
        if ($request->hasFile('ettachment')) {
            if (Storage::exists($identity->ettachment)) {
                Storage::delete($identity->ettachment);
            }
            $data['ettachment'] = $request->file('ettachment')->store('identities');
        }

        $identity->update($data);

        return response()->json("data identities berhasil di ubah");
    }

    public function destroy($id)
    {
        $identity = Identity::findOrFail($id);
        if (Storage::exists($identity->ettachment)) {
            Storage::delete($identity->ettachment);
        }
        $identity->delete();
        return response()->json('telah di hapus');
    }
}
