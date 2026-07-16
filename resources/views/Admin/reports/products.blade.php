<x-dashboard-layout title="Produk Terlaris">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Produk Terlaris</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
        </div>
    </div>

    <x-report-tabs active="products" :from="$from" :to="$to" />

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="space-y-4">
            @forelse ($bestSelling as $index => $item)
                @php
                    $maxSold = $bestSelling->max('total_sold') ?: 1;
                    $ratio = ($item->total_sold / $maxSold) * 100;
                    $rankColors = ['bg-amber-400', 'bg-slate-300', 'bg-amber-700'];
                @endphp
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full {{ $rankColors[$index] ?? 'bg-slate-100' }} {{ $index < 3 ? 'text-white' : 'text-slate-500' }} text-xs font-bold flex items-center justify-center shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-sm font-medium text-slate-700 truncate pr-2">{{ $item->name }}</p>
                            <span class="text-xs font-semibold text-slate-500 shrink-0">{{ $item->total_sold }} terjual · Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
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
</x-dashboard-layout>