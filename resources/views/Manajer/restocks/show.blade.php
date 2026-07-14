<x-dashboard-layout title="Detail Restock">

    <a href="{{ route('manajer.restocks.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke riwayat restock
    </a>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            {{-- Header invoice --}}
            <div class="bg-brand-gradient p-6 sm:p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Invoice Restock</p>
                        <p class="font-mono font-bold text-white text-lg mt-1">{{ $restock->invoice_number }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400">Supplier</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $restock->supplier->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Tanggal</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ \Carbon\Carbon::parse($restock->restock_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Diinput oleh</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $restock->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Total</p>
                        <p class="text-sm font-bold text-brand-600 mt-0.5">Rp {{ number_format($restock->total_cost, 0, ',', '.') }}</p>
                    </div>
                </div>

                <h3 class="font-display font-semibold text-slate-700 mb-3">Daftar Barang</h3>
                <div class="rounded-xl border border-slate-100 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Harga Modal</th>
                                <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($restock->details as $detail)
                                <tr>
                                    <td class="px-4 py-3 text-slate-700">{{ $detail->product->name }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-3 text-slate-500">Rp {{ number_format($detail->cost_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-700 text-right">Rp {{ number_format($detail->quantity * $detail->cost_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50">
                                <td colspan="3" class="px-4 py-3 font-semibold text-slate-700 text-right">Total</td>
                                <td class="px-4 py-3 font-bold text-brand-600 text-right">Rp {{ number_format($restock->total_cost, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($restock->note)
                    <div class="mt-5 p-4 bg-amber-50 rounded-xl">
                        <p class="text-xs font-medium text-amber-700 mb-1">Catatan</p>
                        <p class="text-sm text-amber-800">{{ $restock->note }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout>