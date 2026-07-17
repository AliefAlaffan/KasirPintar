<x-dashboard-layout title="Detail Transaksi">

    <div class="flex items-start gap-3 mb-6">
        <a href="{{ route('reports.index') }}"
           class="mt-1 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Laporan</p>
            <h2 class="font-display font-bold text-2xl text-slate-800">Detail Transaksi</h2>
        </div>
    </div>

    <x-report-filter-bar :from="$from" :to="$to" export-type="transactions" />

    @php
        $totalValid = $transactions->where('is_voided', false)->count();
        $totalVoid = $transactions->where('is_voided', true)->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Transaksi</p>
            <p class="text-xl font-display font-bold text-slate-800 mt-1">{{ count($transactions) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Berhasil</p>
            <p class="text-xl font-display font-bold text-emerald-600 mt-1">{{ $totalValid }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Dibatalkan (Void)</p>
            <p class="text-xl font-display font-bold text-rose-600 mt-1">{{ $totalVoid }}</p>
        </div>
    </div>

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