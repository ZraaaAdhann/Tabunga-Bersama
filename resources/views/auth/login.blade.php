<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-400" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPassword: false }">
        @csrf

        <h2 class="text-lg font-bold text-white text-center mb-1">Masuk ke Akun</h2>
        <p class="text-xs text-slate-400 text-center mb-5">Akses dashboard tabungan bersama Anda</p>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
            <input id="email" class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@domain.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-xs font-semibold text-slate-300">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-emerald-400 hover:text-emerald-300 transition" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" 
                       class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 pr-10 focus:ring-emerald-500 focus:border-emerald-500" 
                       :type="showPassword ? 'text' : 'password'" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••" />
                <button type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-200 focus:outline-none" 
                        tabindex="-1">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.682-.813c4.478 0 8.268 2.943 9.542 7a9.978 9.978 0 01-2.163 3.568m-4.586 1.834a3 3 0 01-4.243-4.243M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded bg-slate-950 border-slate-800 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
            <label for="remember_me" class="ms-2 text-xs text-slate-400">Ingat Akun Saya</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-md transition">
            Masuk
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-800"></div></div>
        <div class="relative flex justify-center text-xs"><span class="bg-slate-900 px-3 text-slate-500">atau</span></div>
    </div>

    <!-- Google OAuth Button -->
    <a href="{{ route('auth.google') }}" class="w-full py-2.5 px-4 border border-slate-800 rounded-xl font-semibold text-xs text-slate-200 bg-slate-950 hover:bg-slate-800 flex items-center justify-center gap-2 shadow-sm transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Masuk Menggunakan Google</span>
    </a>

    <!-- Register Link -->
    <p class="mt-6 text-center text-xs text-slate-400">
        Belum memiliki akun? 
        <a href="{{ route('register') }}" class="font-semibold text-emerald-400 hover:text-emerald-300 transition">Daftar Akun Baru</a>
    </p>
</x-guest-layout>