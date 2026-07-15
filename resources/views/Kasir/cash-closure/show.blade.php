<x-kasir-layout title="Detail Tutup Kasir">

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke POS
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8 text-center border-b border-slate-100">
                @php $isBalanced = (float) $closure->difference === 0.0; @endphp
                <div class="mx-auto w-14 h-14 rounded-full flex items-center justify-center mb-3
                    {{ $isBalanced ? 'bg-emerald-50 text-emerald-500' : ($closure->difference > 0 ? 'bg-blue-50 text-blue-500' : 'bg-rose-50 text-rose-500') }}">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="font-display font-bold text-slate-800 text-lg">Tutup Kasir Berhasil Disimpan</h2>
                <p class="text-sm text-slate-400 mt-1">{{ $closure->period_end->format('d M Y, H:i') }}</p>
            </div>

            <div class="p-6 sm:p-8 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Total Tunai (Sistem)</span>
                    <span class="font-medium text-slate-700">Rp {{ number_format($closure->system_cash_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Uang Fisik Dihitung</span>
                    <span class="font-medium text-slate-700">Rp {{ number_format($closure->physical_cash, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm pt-3 border-t border-slate-100">
                    <span class="font-semibold text-slate-700">Selisih</span>
                    <span class="font-display font-bold {{ $isBalanced ? 'text-slate-700' : ($closure->difference > 0 ? 'text-blue-600' : 'text-rose-600') }}">
                        {{ $isBalanced ? 'Pas (Rp 0)' : ($closure->difference > 0 ? '+' : '') . 'Rp ' . number_format($closure->difference, 0, ',', '.') }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                    <div class="bg-blue-50 rounded-xl p-3">
                        <p class="text-xs text-blue-600 mb-0.5">QRIS</p>
                        <p class="font-semibold text-blue-700 text-sm">Rp {{ number_format($closure->system_qris_total, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-violet-50 rounded-xl p-3">
                        <p class="text-xs text-violet-600 mb-0.5">Debit</p>
                        <p class="font-semibold text-violet-700 text-sm">Rp {{ number_format($closure->system_debit_total, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if ($closure->note)
                    <div class="p-3.5 bg-amber-50 rounded-xl">
                        <p class="text-xs font-medium text-amber-700 mb-1">Catatan</p>
                        <p class="text-sm text-amber-800">{{ $closure->note }}</p>
                    </div>
                @endif
            </div>
        </div>

        <a href="{{ route('kasir.dashboard') }}"
           class="block text-center mt-4 py-3 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">
            Kembali ke POS
        </a>
    </div>
</x-kasir-layout>