<x-dashboard-layout title="Dashboard">

    {{-- ============ HERO GREETING + QUICK ACTIONS ============ --}}
    <div class="relative overflow-hidden rounded-2xl bg-brand-gradient p-6 sm:p-8 mb-6">
        <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <p class="text-white/80 text-sm font-medium" x-data="{ greeting: '' }"
                   x-init="const h = new Date().getHours(); greeting = h < 11 ? 'Selamat pagi' : h < 15 ? 'Selamat siang' : h < 18 ? 'Selamat sore' : 'Selamat malam'"
                   x-text="greeting + ', {{ explode(' ', auth()->user()->name)[0] }} 👋'"></p>
                <h2 class="font-display font-bold text-white text-2xl sm:text-3xl mt-1">Pantau stok & operasional toko di sini</h2>
            </div>

            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('manajer.restocks.create') }}"
                   class="flex items-center gap-2 px-4 py-2.5 bg-white text-brand-600 font-medium text-sm rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Restock
                </a>
                <a href="{{ route('manajer.stock-opnames.create') }}"
                   class="flex items-center gap-2 px-4 py-2.5 bg-white/10 backdrop-blur text-white font-medium text-sm rounded-xl hover:bg-white/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                    </svg>
                    Stock Opname Baru
                </a>
            </div>
        </div>
    </div>

    {{-- ============ KARTU RINGKASAN ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-rose-500 to-amber-500"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Produk Stok Menipis</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1">{{ $summary['low_stock_count'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-blue-500"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Restock Bulan Ini</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1">{{ $summary['restock_this_month'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-amber-500"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Opname Belum Selesai</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1">{{ $summary['pending_opname_count'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ============ STOK MENIPIS ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display font-bold text-slate-800">Perlu Segera Di-restock</h3>
                <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 text-xs font-bold flex items-center justify-center">
                    {{ count($lowStock) }}
                </span>
            </div>

            <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                @forelse ($lowStock as $product)
                    @php $ratio = $product->min_stock > 0 ? min(100, ($product->stock / $product->min_stock) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <p class="text-sm font-medium text-slate-700 truncate pr-2">{{ $product->name }}</p>
                            <span class="text-xs font-bold text-rose-500 shrink-0">{{ $product->stock }}/{{ $product->min_stock }} {{ $product->unit }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-rose-500 to-amber-500 rounded-full" style="width: {{ $ratio }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="mx-auto w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500 font-medium">Semua stok aman</p>
                    </div>
                @endforelse
            </div>

            @if (count($lowStock) > 0)
                <a href="{{ route('manajer.restocks.create') }}"
                   class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 text-sm font-medium rounded-xl transition">
                    Input Restock Sekarang
                </a>
            @endif
        </div>

        {{-- ============ RESTOCK TERBARU & OPNAME PENDING ============ --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-display font-bold text-slate-800 mb-4">Restock Terbaru</h3>
                <div class="space-y-3">
                    @forelse ($recentRestocks as $restock)
                        <a href="{{ route('manajer.restocks.show', $restock->id) }}"
                           class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">{{ $restock->supplier->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $restock->invoice_number }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-slate-600">Rp {{ number_format($restock->total_cost, 0, ',', '.') }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Belum ada riwayat restock.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-display font-bold text-slate-800 mb-4">Opname Menunggu Konfirmasi</h3>
                <div class="space-y-3">
                    @forelse ($pendingOpnames as $opname)
                        <a href="{{ route('manajer.stock-opnames.show', $opname->id) }}"
                           class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Draft</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Tidak ada opname yang menunggu konfirmasi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>