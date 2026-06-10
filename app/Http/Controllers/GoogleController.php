<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    protected function getGoogleProvider()
    {
        $provider = Socialite::driver('google');
        // Bypass SSL local issue on Windows/Laragon
        if (app()->environment('local')) {
            $provider->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }

        return $provider;
    }

    public function redirectToGoogle()
    {
        return $this->getGoogleProvider()->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = $this->getGoogleProvider()->stateless()->user();

        $finduser = User::where('email', $user->email)->first();

        if ($finduser) {
            // Update google_id if it's not set
            if (! $finduser->google_id) {
                $finduser->update(['google_id' => $user->id]);
            }
            // Jika akun sudah ada tapi belum diverifikasi emailnya, verifikasi sekarang karena login pakai Google
            if (! $finduser->hasVerifiedEmail()) {
                $finduser->markEmailAsVerified();
            }
            Auth::login($finduser);
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(24)),
            ]);

            $newUser->markEmailAsVerified();

            Auth::login($newUser);
        }

        return redirect()->intended('dashboard');
    }
}
