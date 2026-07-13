<x-dashboard-layout title="Laporan Penjualan">

    {{-- ============ HEADER + FILTER TANGGAL ============ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <form method="GET" class="flex flex-col lg:flex-row lg:items-end gap-4">
            <div>
                <h2 class="font-display font-bold text-xl text-slate-800">Laporan Penjualan</h2>
                <p class="text-sm text-slate-400 mt-0.5">Ringkasan performa penjualan toko Anda</p>
            </div>

            <div class="flex flex-wrap items-end gap-3 lg:ms-auto">
                <div>
                    <label class="text-xs font-medium text-slate-500">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ $from }}"
                           class="w-full rounded-xl border-slate-200 text-sm mt-1 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="text-xs font-medium text-slate-500">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to }}"
                           class="w-full rounded-xl border-slate-200 text-sm mt-1 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition">
                    Tampilkan
                </button>

                <div class="flex gap-2 border-l border-slate-100 pl-3">
                    <a href="{{ route('reports.export.pdf', ['from' => $from, 'to' => $to]) }}"
                       class="flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 text-rose-600 text-sm font-medium rounded-xl hover:bg-rose-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v10a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('reports.export.excel', ['from' => $from, 'to' => $to]) }}"
                       class="flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 text-emerald-600 text-sm font-medium rounded-xl hover:bg-emerald-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v10a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                </div>
            </div>
        </form>
        <p class="text-xs text-slate-400 mt-3">
            Menampilkan data <span class="font-medium text-slate-600">{{ \Carbon\Carbon::parse($from)->format('d M Y') }}</span>
            sampai <span class="font-medium text-slate-600">{{ \Carbon\Carbon::parse($to)->format('d M Y') }}</span>
        </p>
    </div>

    {{-- ============ KARTU RINGKASAN (Animated) ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div x-data="animatedCounter({{ $summary['total_revenue'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-brand-gradient"></div>
            <div class="p-5">
                <p class="text-sm text-slate-400 font-medium">Total Pendapatan</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
            </div>
        </div>
        <div x-data="animatedCounter({{ $summary['total_cost'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-rose-500"></div>
            <div class="p-5">
                <p class="text-sm text-slate-400 font-medium">Total Modal (HPP)</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
            </div>
        </div>
        <div x-data="animatedCounter({{ $summary['total_profit'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-emerald-500"></div>
            <div class="p-5">
                <p class="text-sm text-slate-400 font-medium">Laba Bersih</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
            </div>
        </div>
        <div x-data="animatedCounter({{ $summary['total_transactions'] }})" x-init="start()"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-amber-500"></div>
            <div class="p-5">
                <p class="text-sm text-slate-400 font-medium">Jumlah Transaksi</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1"><span x-text="Math.round(display)"></span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- ============ GRAFIK TREN ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-display font-bold text-slate-800 mb-4">Tren Pendapatan Harian</h3>
            <div class="relative h-64">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>

        {{-- ============ PRODUK TERLARIS (Ranking Visual) ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-display font-bold text-slate-800 mb-4">Produk Terlaris</h3>
            <div class="space-y-3">
                @forelse ($bestSelling as $index => $item)
                    @php
                        $maxSold = $bestSelling->max('total_sold') ?: 1;
                        $ratio = ($item->total_sold / $maxSold) * 100;
                        $rankColors = ['bg-amber-400', 'bg-slate-300', 'bg-amber-700'];
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full {{ $rankColors[$index] ?? 'bg-slate-100' }} {{ $index < 3 ? 'text-white' : 'text-slate-500' }} text-xs font-bold flex items-center justify-center shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-sm font-medium text-slate-700 truncate pr-2">{{ $item->name }}</p>
                                <span class="text-xs font-semibold text-slate-500 shrink-0">{{ $item->total_sold }} terjual</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-gradient rounded-full transition-all duration-700" style="width: {{ $ratio }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada data penjualan di periode ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============ PERFORMA KASIR ============ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <h3 class="font-display font-bold text-slate-800 mb-4">Performa Kasir</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($cashierPerformance as $cp)
                <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50">
                    <div class="w-10 h-10 rounded-full bg-brand-gradient flex items-center justify-center text-white font-display font-bold shrink-0">
                        {{ strtoupper(substr($cp->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $cp->name }}</p>
                        <p class="text-xs text-slate-400">{{ $cp->total_transactions }} transaksi · Rp {{ number_format($cp->total_revenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400 col-span-full text-center py-4">Belum ada data kasir di periode ini.</p>
            @endforelse
        </div>
    </div>

    {{-- ============ DETAIL TRANSAKSI ============ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 pb-0">
            <h3 class="font-display font-bold text-slate-800">Detail Transaksi</h3>
        </div>
        <div class="overflow-x-auto mt-4">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">No. Invoice</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($transactions as $trx)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $trx->invoice_number }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3.5 text-slate-700">{{ $trx->user->name }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 uppercase">{{ $trx->payment_method }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Tidak ada transaksi di rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="h-2"></div>
    </div>

    <script>
        function animatedCounter(target) {
            return {
                display: 0,
                start() {
                    const duration = 900;
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

        const trend = @json($dailyTrend);
        const ctx = document.getElementById('dailyTrendChart');
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trend.map(d => d.date),
                datasets: [{
                    label: 'Pendapatan',
                    data: trend.map(d => d.revenue),
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    maxBarThickness: 36,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8' } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8', callback: (v) => 'Rp ' + (v / 1000) + 'rb' }
                    }
                }
            }
        });
    </script>
</x-dashboard-layout>