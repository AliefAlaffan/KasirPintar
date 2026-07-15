<x-kasir-layout title="Riwayat Tutup Kasir">

    <div class="max-w-3xl mx-auto">
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke POS
        </a>

        <h2 class="font-display font-bold text-xl text-slate-800 mb-4">Riwayat Tutup Kasir</h2>

        @if ($closures->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Periode</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Tunai Sistem</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Uang Fisik</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Selisih</th>
                                <th class="px-5 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($closures as $closure)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-4 text-slate-600">{{ $closure->period_end->format('d M Y, H:i') }}</td>
                                    <td class="px-5 py-4 text-slate-500">Rp {{ number_format($closure->system_cash_total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-slate-500">Rp {{ number_format($closure->physical_cash, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4">
                                        @php $bal = (float) $closure->difference === 0.0; @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $bal ? 'bg-slate-100 text-slate-500' : ($closure->difference > 0 ? 'bg-blue-50 text-blue-600' : 'bg-rose-50 text-rose-600') }}">
                                            {{ $bal ? 'Pas' : ($closure->difference > 0 ? '+' : '') . number_format($closure->difference, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('kasir.cash-closure.show', $closure->id) }}" class="text-brand-600 text-sm font-medium hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-slate-100">{{ $closures->links() }}</div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                <p class="text-sm text-slate-400">Belum ada riwayat tutup kasir.</p>
            </div>
        @endif
    </div>
</x-kasir-layout>