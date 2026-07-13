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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

        <!-- Styles -->
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
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-slate-50 font-sans" x-data="{ sidebarOpen: false }">

            {{-- ============ SIDEBAR (satu file, menu menyesuaikan role) ============ --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-40 w-64 bg-[#1e1b3a] transform transition-transform duration-200 flex flex-col">

                <div class="flex items-center gap-3 px-6 h-20 shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-brand-gradient flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="font-display font-bold text-white text-lg tracking-tight">KasirPintar</span>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

                    @php
                        $navItem = function (string $route, string $label, string $iconPath, string $routePattern = null) {
                            $pattern = $routePattern ?? $route;
                            $active = request()->routeIs($pattern);
                            $classes = $active
                                ? 'bg-white text-[#1e1b3a] shadow-sm'
                                : 'text-slate-300 hover:bg-white/5 hover:text-white';
                            return compact('route', 'label', 'iconPath', 'classes');
                        };
                    @endphp

                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                       {{ request()->routeIs('dashboard') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    {{-- ===== MENU KHUSUS ADMIN ===== --}}
                    @if (auth()->user()->isAdmin())
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-5">Master Data</p>

                        <a href="{{ route('admin.categories.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('admin.categories.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Kategori
                        </a>

                        <a href="{{ route('admin.suppliers.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('admin.suppliers.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Supplier
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('admin.products.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Produk
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('admin.users.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                            </svg>
                            Manajemen User
                        </a>
                    @endif

                    {{-- ===== MENU KHUSUS MANAJER ===== --}}
                    @if (auth()->user()->isManajer())
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-5">Stok Barang</p>

                        <a href="{{ route('manajer.restocks.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('manajer.restocks.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Restock
                        </a>

                        <a href="{{ route('manajer.stock-opnames.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('manajer.stock-opnames.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Stock Opname
                        </a>
                    @endif

                    {{-- ===== MENU LAPORAN — dipakai bareng Admin & Manajer ===== --}}
                    @if (auth()->user()->isAdmin() || auth()->user()->isManajer())
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-5">Laporan</p>

                        <a href="{{ route('reports.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                           {{ request()->routeIs('reports.*') ? 'bg-white text-[#1e1b3a] shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h0a2 2 0 012 2v6m-4 0h4m-4 0H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2m-4 0v3m0 0h2m-2 0H10" />
                            </svg>
                            Laporan Penjualan
                        </a>
                    @endif
                </nav>

                {{-- User Info Bawah — label role otomatis menyesuaikan --}}
                <div class="p-3 border-t border-white/10">
                    <div class="flex items-center gap-3 px-3 py-2.5">
                        <div class="w-9 h-9 rounded-full bg-brand-gradient flex items-center justify-center text-white font-semibold text-sm shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">
                                {{ match(auth()->user()->role) {
                                    'admin' => 'Administrator',
                                    'manajer' => 'Manajer Operasional',
                                    'kasir' => 'Kasir',
                                    default => ucfirst(auth()->user()->role),
                                } }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button title="Keluar" class="text-slate-400 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

            {{-- ============ KONTEN UTAMA (sama untuk semua role) ============ --}}
            <div class="lg:pl-64">
                <header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-slate-200 h-16 flex items-center px-4 sm:px-6 gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="font-display font-bold text-slate-800 text-lg leading-tight">{{ $title ?? 'Dashboard' }}</h1>
                    <div class="ms-auto flex items-center gap-3">
                        <span class="hidden sm:block text-xs text-slate-400 font-mono" x-data="{ time: '' }"
                              x-init="time = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })">
                            <span x-text="time"></span>
                        </span>
                    </div>
                </header>

                <main class="p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

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