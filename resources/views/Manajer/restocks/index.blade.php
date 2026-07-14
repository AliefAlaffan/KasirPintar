<x-dashboard-layout title="Riwayat Restock">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Riwayat Restock</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ $restocks->total() }} transaksi barang masuk tercatat</p>
        </div>
        <a href="{{ route('manajer.restocks.create') }}"
           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 hover:-translate-y-0.5 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Input Restock
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nomor invoice, lalu tekan Enter..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition shrink-0">Cari</button>
            @if (request('search'))
                <a href="{{ route('manajer.restocks.index') }}" class="px-4 py-2.5 bg-slate-50 text-slate-500 text-sm font-medium rounded-xl hover:bg-slate-100 transition shrink-0">Reset</a>
            @endif
        </form>
    </div>

    @if ($restocks->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Invoice</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Supplier</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Diinput oleh</th>
                            <th class="px-5 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($restocks as $restock)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-4">
                                    <span class="font-mono text-xs text-slate-500 bg-slate-50 px-2 py-1 rounded-md">{{ $restock->invoice_number }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-display font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($restock->supplier->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-slate-700">{{ $restock->supplier->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-500">{{ \Carbon\Carbon::parse($restock->restock_date)->format('d M Y') }}</td>
                                <td class="px-5 py-4 font-medium text-slate-800">Rp {{ number_format($restock->total_cost, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $restock->user->name }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('manajer.restocks.show', $restock->id) }}"
                                       class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium hover:underline">
                                        Detail
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">{{ $restocks->links() }}</div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
            <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <p class="font-display font-semibold text-slate-700">
                @if(request('search')) Tidak ada invoice "{{ request('search') }}" @else Belum ada riwayat restock @endif
            </p>
            <p class="text-sm text-slate-400 mt-1 mb-5">Catat barang masuk pertama dari supplier Anda.</p>
            <a href="{{ route('manajer.restocks.create') }}"
               class="inline-block px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition-all">
                + Input Restock
            </a>
        </div>
    @endif
</x-dashboard-layout>