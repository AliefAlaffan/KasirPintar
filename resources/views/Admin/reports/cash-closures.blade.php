<x-dashboard-layout title="Laporan Kas Kasir">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Laporan Kas Kasir</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
        </div>
    </div>

    <x-report-tabs active="cash" :from="$from" :to="$to" />

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