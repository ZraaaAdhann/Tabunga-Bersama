<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        // Ambil data google dari session jika ada
        $googleData = session('google_data', []);

        return view('auth.register', compact('googleData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $googleData = session('google_data');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'google_id' => $googleData['google_id'] ?? null,
            'avatar' => $googleData['avatar'] ?? null,
        ]);

        // Hapus session google setelah berhasil terdaftar
        session()->forget('google_data');

        // CATATAN: Auth::login($user); sengaja DIHAPUS agar user harus login manual setelah registrasi.

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.');
    }
}