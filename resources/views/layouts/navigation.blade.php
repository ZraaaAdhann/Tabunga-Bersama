<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 text-white sticky top-0 z-50">
    <!-- Main Navbar Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-bold text-lg tracking-tight">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shadow-sm shadow-emerald-500/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-semibold">Tabungan<span class="text-emerald-400">Bersama</span></span>
                </a>
            </div>

            <!-- Profile & Menu di Desktop (Layar Komputer) -->
            <div class="hidden md:flex md:items-center md:gap-4">
                @auth
                    <div class="flex items-center gap-3 bg-gray-800/70 border border-gray-700/60 px-3 py-1.5 rounded-full">
                        <img class="w-7 h-7 rounded-full object-cover border border-emerald-500/40" 
                             src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" 
                             alt="{{ auth()->user()->name }}">
                        <span class="text-sm font-medium text-gray-200">{{ auth()->user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-red-400 hover:text-red-300 bg-red-500/10 border border-red-500/20 px-3 py-1.5 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

            <!-- Tombol Hamburger di HP -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/80 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'inline-flex': open, 'hidden': ! open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Dropdown Menu Mobile Drawer (Tampil Saat Hamburger Diklik di HP) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-gray-800 bg-gray-900/95 backdrop-blur-lg px-4 pt-3 pb-5 transition-all duration-200 ease-in-out">
        @auth
            <!-- Profil User Card di Mobile -->
            <div class="flex items-center gap-3 p-3 mb-3 bg-gray-800/60 border border-gray-700/70 rounded-xl">
                <img class="w-10 h-10 rounded-full object-cover border-2 border-emerald-500/50 shadow-sm" 
                     src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" 
                     alt="{{ auth()->user()->name }}">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Action Links -->
            <div class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 text-sm font-medium rounded-lg text-emerald-400 bg-emerald-500/10 border border-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard Tabungan
                </a>

                <form method="POST" action="{{ route('logout') }}" class="pt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3.5 py-2.5 text-sm font-medium rounded-lg text-red-400 bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>