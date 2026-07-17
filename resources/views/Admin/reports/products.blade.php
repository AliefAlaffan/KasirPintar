<x-dashboard-layout title="Produk Terlaris">

    <div class="flex items-start gap-3 mb-6">
        <a href="{{ route('reports.index') }}"
           class="mt-1 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Laporan</p>
            <h2 class="font-display font-bold text-2xl text-slate-800">Produk Terlaris</h2>
        </div>
    </div>

    <x-report-filter-bar :from="$from" :to="$to" export-type="products" />

    @php
        $totalUnitTerjual = $bestSelling->sum('total_sold');
        $totalPendapatan = $bestSelling->sum('total_revenue');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Produk Terjual</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">{{ count($bestSelling) }} produk</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Unit Terjual</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">{{ number_format($totalUnitTerjual) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Pendapatan</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Peringkat</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Terjual</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($bestSelling as $index => $item)
                        @php $rankColors = ['bg-amber-400 text-white', 'bg-slate-300 text-white', 'bg-amber-700 text-white']; @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-3.5">
                                <span class="w-6 h-6 rounded-full {{ $rankColors[$index] ?? 'bg-slate-100 text-slate-500' }} text-xs font-bold flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-slate-700">{{ $item->name }}</td>
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $item->sku }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $item->total_sold }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada data penjualan di periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>