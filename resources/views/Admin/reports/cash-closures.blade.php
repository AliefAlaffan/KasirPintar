<x-dashboard-layout title="Laporan Kas Kasir">

    <div class="flex items-start gap-3 mb-6">
        <a href="{{ route('reports.index') }}"
           class="mt-1 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Laporan</p>
            <h2 class="font-display font-bold text-2xl text-slate-800">Laporan Kas</h2>
        </div>
    </div>

    <x-report-filter-bar :from="$from" :to="$to" export-type="cash-closures" />

    @php
        $totalLebih = $cashClosures->where('difference', '>', 0)->count();
        $totalKurang = $cashClosures->where('difference', '<', 0)->count();
        $totalPas = $cashClosures->where('difference', 0)->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Laporan Pas</p>
            <p class="text-xl font-display font-bold text-slate-700 mt-1">{{ $totalPas }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Kelebihan Kas</p>
            <p class="text-xl font-display font-bold text-blue-600 mt-1">{{ $totalLebih }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Kekurangan Kas</p>
            <p class="text-xl font-display font-bold text-rose-600 mt-1">{{ $totalKurang }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Waktu Lapor</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Tunai Sistem</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Uang Fisik</th>
                        <th class="px-6 py-3 font-medium text-slate-400 text-xs uppercase tracking-wider">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($cashClosures as $closure)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-3.5 text-slate-700">{{ $closure->user->name }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ $closure->period_end->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3.5 text-slate-500">Rp {{ number_format($closure->system_cash_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 text-slate-500">Rp {{ number_format($closure->physical_cash, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                @php $bal = (float) $closure->difference === 0.0; @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $bal ? 'bg-slate-100 text-slate-500' : ($closure->difference > 0 ? 'bg-blue-50 text-blue-600' : 'bg-rose-50 text-rose-600') }}">
                                    {{ $bal ? 'Pas' : ($closure->difference > 0 ? '+' : '') . number_format($closure->difference, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada laporan kas pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>