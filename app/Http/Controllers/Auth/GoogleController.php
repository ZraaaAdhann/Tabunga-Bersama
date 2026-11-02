<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah akun sudah terdaftar di database
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            // Jika pengguna SUDAH terdaftar sebelumnya
            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                Auth::login($user);
                return redirect()->route('dashboard');
            }

            // Jika pengguna BELUM terdaftar, simpan data Google ke session dan arahkan ke halaman Register
            session([
                'google_data' => [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]
            ]);

            return redirect()->route('register')->with('info', 'Silakan lengkapi password dan data pendaftaran Anda.');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['google' => 'Gagal melakukan otentikasi via Google.']);
        }
    }
}