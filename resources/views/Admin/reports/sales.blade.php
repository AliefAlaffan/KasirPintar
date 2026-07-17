<x-dashboard-layout title="Laporan Penjualan">

    <div class="flex items-start gap-3 mb-6">
        <a href="{{ route('reports.index') }}"
           class="mt-1 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Laporan</p>
            <h2 class="font-display font-bold text-2xl text-slate-800">Penjualan</h2>
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <a href="{{ route('reports.index') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-800 text-sm font-medium mb-4 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Pusat Laporan
        </a>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $from }}"
                       class="w-full rounded-xl border-slate-200 text-sm mt-1 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
            <div>
                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $to }}"
                       class="w-full rounded-xl border-slate-200 text-sm mt-1 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition">
                Terapkan
            </button>
            <div class="flex gap-2 ms-auto">
                <a href="{{ route('reports.export.pdf', ['from' => $from, 'to' => $to]) }}"
                   class="flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 text-rose-600 text-sm font-medium rounded-xl hover:bg-rose-100 transition">PDF</a>
                <a href="{{ route('reports.export.excel', ['from' => $from, 'to' => $to]) }}"
                   class="flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 text-emerald-600 text-sm font-medium rounded-xl hover:bg-emerald-100 transition">Excel</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div x-data="animatedCounter({{ $summary['total_revenue'] }})" x-init="start()" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-brand-gradient"></div>
            <div class="p-5">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Pendapatan</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
            </div>
        </div>
        <div x-data="animatedCounter({{ $summary['total_cost'] }})" x-init="start()" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-rose-500"></div>
            <div class="p-5">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Modal (HPP)</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
            </div>
        </div>
        <div x-data="animatedCounter({{ $summary['total_profit'] }})" x-init="start()" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-emerald-500"></div>
            <div class="p-5">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Laba Bersih</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp <span x-text="display.toLocaleString('id-ID')"></span></p>
            </div>
        </div>
        <div x-data="animatedCounter({{ $summary['total_transactions'] }})" x-init="start()" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-1 bg-amber-500"></div>
            <div class="p-5">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Jumlah Transaksi</p>
                <p class="text-xl font-display font-bold text-slate-800 mt-1"><span x-text="Math.round(display)"></span></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-display font-bold text-slate-800 mb-4">Tren Pendapatan Harian</h3>
        <div class="relative h-72">
            <canvas id="dailyTrendChart"></canvas>
        </div>
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
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trend.map(d => d.date),
                datasets: [{ label: 'Pendapatan', data: trend.map(d => d.revenue), backgroundColor: '#4f46e5', borderRadius: 6, maxBarThickness: 36 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1e293b', padding: 10, cornerRadius: 8, callbacks: { label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8' } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8', callback: (v) => 'Rp ' + (v / 1000) + 'rb' } }
                }
            }
        });
    </script>
</x-dashboard-layout>