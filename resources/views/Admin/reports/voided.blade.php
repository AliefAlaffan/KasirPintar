<x-dashboard-layout title="Riwayat Void Transaksi">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Riwayat Void Transaksi</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
        </div>
    </div>

    <x-report-tabs active="voided" :from="$from" :to="$to" />

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Waktu Dibatalkan</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($voidedTransactions as $trx)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $trx->invoice_number }}</td>
                            <td class="px-6 py-3.5 text-slate-700">{{ $trx->user->name }}</td>
                            <td class="px-6 py-3.5 text-slate-500">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $trx->voided_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $trx->void_reason }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Tidak ada transaksi yang dibatalkan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>