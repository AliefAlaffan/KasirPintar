<x-admin-layout title="Dashboard">

    {{-- ============ HERO GREETING + QUICK ACTIONS ============ --}}
    <div class="relative overflow-hidden rounded-2xl bg-brand-gradient p-6 sm:p-8 mb-6">
        <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute right-24 bottom-0 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <p class="text-white/70 text-sm font-medium" x-data="{ greeting: '' }"
                   x-init="const h = new Date().getHours(); greeting = h < 11 ? 'Selamat pagi' : h < 15 ? 'Selamat siang' : h < 18 ? 'Selamat sore' : 'Selamat malam'"
                   x-text="greeting + ', {{ explode(' ', auth()->user()->name)[0] }}'"></p>
                <h2 class="font-display font-bold text-white text-2xl sm:text-3xl mt-1">Begini performa toko Anda hari ini</h2>
                <p class="text-white/60 text-sm mt-2">Terakhir diperbarui: <span x-data x-init="setInterval(() => $el.innerText = new Date().toLocaleTimeString('id-ID'), 1000)"></span></p>
            </div>

            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('admin.products.create') }}"
                   class="group flex items-center gap-2 px-4 py-2.5 bg-white text-brand-600 font-medium text-sm rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk
                </a>
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-2 px-4 py-2.5 bg-white/10 backdrop-blur text-white font-medium text-sm rounded-xl hover:bg-white/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h0a2 2 0 012 2v6m-4 0h4" />
                    </svg>
                    Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    {{-- ============ KARTU STATISTIK (Animated Counter) ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div x-data="animatedCounter({{ $summary['revenue_today'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="h-1 bg-brand-gradient"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div x-data="animatedCounter({{ $summary['revenue_month'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="h-1 bg-blue-500"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div x-data="animatedCounter({{ $summary['profit_month'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="h-1 bg-emerald-500"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Laba Bersih Bulan Ini</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <div x-data="animatedCounter({{ $summary['transactions_today'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="h-1 bg-amber-500"></div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 font-medium">Transaksi Hari Ini</p>
                    <p class="text-2xl font-display font-bold text-slate-800 mt-1"><span x-text="Math.round(display)"></span></p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ============ GRAFIK TREN (Gradient Area Chart) ============ --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-display font-bold text-slate-800">Tren Penjualan</h3>
                <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full">7 hari terakhir</span>
            </div>
            <p class="text-sm text-slate-400 mb-4">Pergerakan pendapatan harian toko Anda</p>
            <div class="relative h-64">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        {{-- ============ ALERT STOK MENIPIS (Progress Bar) ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display font-bold text-slate-800">Stok Menipis</h3>
                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 text-xs font-bold flex items-center justify-center">
                    {{ count($lowStock) }}
                </span>
            </div>

            <div class="space-y-4 max-h-72 overflow-y-auto pr-1">
                @forelse ($lowStock as $product)
                    @php
                        $ratio = $product->min_stock > 0 ? min(100, ($product->stock / $product->min_stock) * 100) : 0;
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-center mb-1.5">
                            <p class="text-sm font-medium text-slate-700 truncate pr-2">{{ $product->name }}</p>
                            <span class="text-xs font-bold text-rose-500 shrink-0">{{ $product->stock }}/{{ $product->min_stock }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-rose-500 to-amber-500 rounded-full transition-all duration-700"
                                 style="width: {{ $ratio }}%"></div>
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
                        <p class="text-xs text-slate-400 mt-1">Tidak ada produk yang perlu di-restock</p>
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
    </div>

    {{-- ============ LOG AKTIVITAS (Timeline) ============ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mt-6">
        <h3 class="font-display font-bold text-slate-800 mb-5">Aktivitas Terbaru</h3>

        <div class="relative">
            @forelse ($activities as $index => $log)
                @php
                    $iconMap = [
                        'Transaksi penjualan'   => ['bg-emerald-100', 'text-emerald-600', 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        'Restock barang'        => ['bg-blue-100', 'text-blue-600', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        'default'                => ['bg-slate-100', 'text-slate-500', 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                    $style = $iconMap[$log->action] ?? $iconMap['default'];
                @endphp
                <div class="flex gap-4 pb-6 last:pb-0 relative">
                    @if (!$loop->last)
                        <span class="absolute left-4 top-9 bottom-0 w-px bg-slate-100"></span>
                    @endif
                    <div class="w-8 h-8 rounded-full {{ $style[0] }} {{ $style[1] }} flex items-center justify-center shrink-0 z-10">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style[2] }}" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-700">
                            <span class="font-semibold">{{ $log->user->name ?? 'Sistem' }}</span>
                            <span class="text-slate-400">— {{ $log->action }}</span>
                        </p>
                        @if ($log->description)
                            <p class="text-xs text-slate-400 mt-0.5">{{ $log->description }}</p>
                        @endif
                        <p class="text-xs text-slate-300 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400 text-center py-6">Belum ada aktivitas tercatat.</p>
            @endforelse
        </div>
    </div>

    {{-- ============ SCRIPTS ============ --}}
    <script>
        function animatedCounter(target) {
            return {
                display: 0,
                start() {
                    const duration = 1000;
                    const startTime = performance.now();
                    const animate = (now) => {
                        const progress = Math.min((now - startTime) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        this.display = Math.floor(eased * target);
                        if (progress < 1) requestAnimationFrame(animate);
                        else this.display = target;
                    };
                    requestAnimationFrame(animate);
                }
            }
        }

        const trendData = @json($salesTrend);
        const ctx = document.getElementById('salesTrendChart');
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendData.map(d => d.date),
                datasets: [{
                    label: 'Pendapatan',
                    data: trendData.map(d => d.total),
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#4f46e5',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { family: 'Inter', weight: '600' },
                        bodyFont: { family: 'Inter' },
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8' } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#94a3b8',
                            callback: (v) => 'Rp ' + (v / 1000) + 'rb'
                        }
                    }
                }
            }
        });
    </script>
</x-admin-layout>