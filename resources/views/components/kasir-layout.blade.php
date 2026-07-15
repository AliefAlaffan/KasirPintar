@props(['title' => null])

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - KasirPintar' : 'KasirPintar' }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        <style>
            [x-cloak] { display: none !important; }
            #page-loader {
                position: fixed; top: 0; left: 0; height: 3px; width: 0%;
                background: linear-gradient(90deg, #4f46e5, #7c3aed);
                z-index: 100; transition: width 0.3s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <x-banner />

        {{-- ============ TOPBAR RAMPING ============ --}}
        <header class="sticky top-0 z-30 bg-[#1e1b3a] h-16 flex items-center px-4 sm:px-6 gap-4">
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-brand-gradient flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="font-display font-bold text-white tracking-tight hidden sm:block">KasirPintar</span>
            </div>

            <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

            <div class="hidden sm:block">
                <p class="text-xs text-slate-400 leading-none">Kasir bertugas</p>
                <p class="text-sm font-medium text-white leading-tight">{{ auth()->user()->name }}</p>
            </div>

            <div class="ms-auto flex items-center gap-4">
                <a href="{{ route('kasir.transactions.history') }}"
                   class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h0a2 2 0 012 2v6m-4 0h4m-4 0H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2m-4 0v3m0 0h2m-2 0H10" />
                    </svg>
                    Riwayat
                </a>

                <a href="{{ route('kasir.cash-closure.create') }}"
                   class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Tutup Kasir
                </a>

                <div class="text-right hidden sm:block" x-data="{ time: '' }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID'), 1000)">
                    <p class="text-xs text-slate-400 leading-none">Waktu</p>
                    <p class="text-sm font-mono font-medium text-white leading-tight" x-text="time"></p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button title="Keluar" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ============ KONTEN POS (full-width) ============ --}}
        <main class="p-4 sm:p-6">
            {{ $slot }}
        </main>

        @stack('modals')

        @livewireScripts
        <x-toast />
        <div id="page-loader"></div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const loader = document.getElementById('page-loader');
                document.querySelectorAll('a[href]:not([target="_blank"])').forEach(link => {
                    link.addEventListener('click', () => { loader.style.width = '80%'; });
                });
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', () => { loader.style.width = '80%'; });
                });
            });
        </script>
    </body>
</html>