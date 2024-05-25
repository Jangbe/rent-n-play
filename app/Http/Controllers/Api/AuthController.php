<?php

namespace App\Http\Controllers\Api;

use App\Events\DashboardEvent;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|string|max:225|unique:users,email,',
            'password' => 'required|string|max:225|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        broadcast(new DashboardEvent());

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau katasandi salah'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'login succes',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        if (!User::where('email', $request->email)->first()) return response()->json(['message' => 'User email tidak terdaftar'], 404);
        $email = $request->input("email");
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        // Send OTP by email
        Mail::to($email)->send(new OtpMail($otp, $request->email));
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => bcrypt($otp),
            'created_at' => now()
        ]);
        return response()->json(["message" => "Kode OTP berhasil dikirim"]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Katasandi berhasil direset'], 200)
            : response()->json(['message' => 'Gagal untuk mereset katasandi'], 400);
    }
}
