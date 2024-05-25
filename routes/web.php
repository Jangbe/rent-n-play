<?php

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('auth/{provider}', fn ($provider) => Socialite::driver($provider)->redirect());
Route::get('auth/{provider}/callback', function ($provider) {
    $providerAccount = Socialite::driver($provider)->stateless()->user();
    $data = [
        'name' => $provider,
        'uuid' => $providerAccount->id
    ];
    $socialAccount = SocialAccount::with('user')->where($data)->first();

    if (is_null($socialAccount)) {
        $user = User::create([
            'avatar' => $providerAccount->avatar,
            'name' => $providerAccount->name,
            'email' => $providerAccount->email,
            'password' => bcrypt('123456')
        ]);

        $socialAccount = $user->socialAccounts()->create($data);
    } else if (is_null($socialAccount->user->getRawOriginal('avatar'))) {
        $socialAccount->user->update(['avatar' => $providerAccount->avatar]);
    }

    $token = $socialAccount->user->createToken('auth_token')->plainTextToken;
    return view('social-account', compact('token'));
});

Route::redirect('/', 'home')->name('login');
Route::view('{any}', 'app')->where('any', '^.*');
