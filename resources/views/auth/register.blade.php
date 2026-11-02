<x-guest-layout>
    {{-- Banner Informasi jika datang dari login Google --}}
    @if(session('info'))
        <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl text-xs text-amber-400 text-center">
            {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ showPassword: false, showConfirmPassword: false }">
        @csrf

        <h2 class="text-lg font-bold text-white text-center mb-1">Registrasi Akun Baru</h2>
        <p class="text-xs text-slate-400 text-center mb-5">Lengkapi data untuk memulai tabungan bersama</p>

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
            <input id="name" 
                   class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500" 
                   type="text" 
                   name="name" 
                   value="{{ old('name', session('google_data.name', '')) }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="Nama Lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-xs font-semibold text-slate-300 mb-1">Username Unik</label>
            <input id="username" 
                   class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500" 
                   type="text" 
                   name="username" 
                   value="{{ old('username') }}" 
                   placeholder="username_unik" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
            <input id="email" 
                   class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500 {{ session('google_data.email') ? 'opacity-75 cursor-not-allowed' : '' }}" 
                   type="email" 
                   name="email" 
                   value="{{ old('email', session('google_data.email', '')) }}" 
                   required 
                   autocomplete="username" 
                   placeholder="nama@domain.com" 
                   {{ session('google_data.email') ? 'readonly' : '' }} />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 mb-1">Password</label>
            <div class="relative">
                <input id="password" 
                       class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 pr-10 focus:ring-emerald-500 focus:border-emerald-500" 
                       :type="showPassword ? 'text' : 'password'" 
                       name="password" 
                       required 
                       autocomplete="new-password" 
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1">Konfirmasi Password</label>
            <div class="relative">
                <input id="password_confirmation" 
                       class="w-full text-sm bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 pr-10 focus:ring-emerald-500 focus:border-emerald-500" 
                       :type="showConfirmPassword ? 'text' : 'password'" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password" 
                       placeholder="••••••••" />
                <button type="button" 
                        @click="showConfirmPassword = !showConfirmPassword" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-200 focus:outline-none" 
                        tabindex="-1">
                    <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.682-.813c4.478 0 8.268 2.943 9.542 7a9.978 9.978 0 01-2.163 3.568m-4.586 1.834a3 3 0 01-4.243-4.243M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-rose-400" />
        </div>

        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-md transition">
            Daftar Sekarang
        </button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-400">
        Sudah memiliki akun? 
        <a href="{{ route('login') }}" class="font-semibold text-emerald-400 hover:text-emerald-300 transition">Masuk di Sini</a>
    </p>
</x-guest-layout>