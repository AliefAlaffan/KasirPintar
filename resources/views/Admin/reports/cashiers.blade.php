<x-dashboard-layout title="Performa Kasir">

    <div class="flex items-start gap-3 mb-6">
        <a href="{{ route('reports.index') }}"
           class="mt-1 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Laporan</p>
            <h2 class="font-display font-bold text-2xl text-slate-800">Performa Kasir</h2>
        </div>
    </div>

    <x-report-filter-bar :from="$from" :to="$to" export-type="cashiers" />

    @php
        $totalTransaksi = $cashierPerformance->sum('total_transactions');
        $totalPendapatan = $cashierPerformance->sum('total_revenue');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Kasir Aktif</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">{{ count($cashierPerformance) }} orang</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Transaksi</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">{{ number_format($totalTransaksi) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Pendapatan</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($cashierPerformance as $cp)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-brand-gradient flex items-center justify-center text-white font-display font-bold shrink-0">
                    {{ strtoupper(substr($cp->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-medium text-slate-800 truncate">{{ $cp->name }}</p>
                    <p class="text-xs text-slate-400">{{ $cp->total_transactions }} transaksi</p>
                    <p class="text-sm font-semibold text-brand-600 mt-0.5">Rp {{ number_format($cp->total_revenue, 0, ',', '.') }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                <p class="text-sm text-slate-400">Belum ada data kasir di periode ini.</p>
            </div>
        @endforelse
    </div>
</x-dashboard-layout>