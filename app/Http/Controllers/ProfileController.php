<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function user(Request $request)
    {
        return $request->user()->load(['notifications']);
    }

    public function update_profile(Request $request)
    {
        $user = $request->user();
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        if ($request->hasFile('avatar')) {
            if (Storage::exists($user->getRawOriginal('avatar') ?? ''))
                Storage::delete($user->getRawOriginal('avatar') ?? '');
            $validate['avatar'] = $request->file('avatar')->store('avatars');
        }
        $user->update($validate);
        return response()->json('Profile berhasil diupdate');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required|current_password',
            'password' => 'required|min:6|confirmed'
        ]);
        $request->user()->update(['password' => bcrypt($request->password)]);
        return response()->json('Katasandi berhasil diupdate');
    }

    public function read_all_notification(Request $request)
    {
        $request->user()->notifications()->update(['read_at' => now()]);
        return response()->json($request->user()->notifications);
    }

    public function read_notification(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);
        $notification->markAsRead();
        return response()->json($notification);
    }
}
