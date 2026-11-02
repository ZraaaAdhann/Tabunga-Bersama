<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tabungan Bersama') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-950 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">
        <div class="w-full sm:max-w-md">
            <!-- Brand Logo Header -->
            <div class="flex justify-center mb-6">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-700 via-emerald-600 to-teal-500 shadow-lg shadow-emerald-900/30 flex items-center justify-center text-white border border-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-white text-xl tracking-tight leading-none">
                            Tabungan<span class="text-emerald-400">Bersama</span>
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase mt-1">Joint Savings Management</span>
                    </div>
                </a>
            </div>

            <!-- Card Form Box -->
            <div class="w-full bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl text-slate-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>