<x-dashboard-layout title="Performa Kasir">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Performa Kasir</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
        </div>
    </div>

    <x-report-tabs active="cashiers" :from="$from" :to="$to" />

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