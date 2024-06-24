<?php

namespace App\Http\Controllers;

use App\Models\Identity;
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
        $attachment = $request->file('attachment')->store('identities');
        Identity::create([
            'user_id' => $request->get('user_id', $request->user()->id),
            'type' => $request->get('type'),
            'identity_number' => $request->get('identity_number'),
            'attachment' => $attachment
        ]);
        return response()->json("Identity berhasil di tambahkan");
    }

    public function show($user_id)
    {
        $identities = Identity::where('user_id', $user_id)->get();
        return response()->json($identities);
    }

    public function update(Request $request, $id)
    {
        $identity = Identity::findOrFail($id);

        $data = [
            'user_id' => $request->get('user_id'),
            'type' => $request->get('type'),
            'identity_number' => $request->get('identity_number'),
        ];
        if ($request->hasFile('attachment')) {
            if (Storage::exists($identity->attachment)) {
                Storage::delete($identity->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('identities');
        }

        $identity->update($data);

        return response()->json("data identities berhasil di ubah");
    }

    public function destroy($id)
    {
        $identity = Identity::findOrFail($id);
        if (Storage::exists($identity->attachment)) {
            Storage::delete($identity->attachment);
        }
        $identity->delete();
        return response()->json('telah di hapus');
    }
}
