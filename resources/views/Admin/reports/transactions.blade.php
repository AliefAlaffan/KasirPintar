<x-dashboard-layout title="Detail Transaksi">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Detail Transaksi</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
        </div>
    </div>

    <x-report-tabs active="transactions" :from="$from" :to="$to" />

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">No. Invoice</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($transactions as $trx)
                        <tr class="hover:bg-slate-50/60 transition {{ $trx->is_voided ? 'opacity-50' : '' }}">
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $trx->invoice_number }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3.5 text-slate-700">{{ $trx->user->name }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 uppercase">{{ $trx->payment_method }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($trx->is_voided)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-600">Void</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">Berhasil</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">Tidak ada transaksi di rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>