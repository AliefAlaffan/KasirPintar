<x-kasir-layout title="Tutup Kasir">

    <div class="max-w-2xl mx-auto" x-data="{ physicalCash: '', get diff() { return (parseFloat(this.physicalCash) || 0) - {{ $summary['system_cash_total'] }}; } }">

        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke POS
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <div class="bg-brand-gradient p-6 sm:p-8">
                <h2 class="font-display font-bold text-white text-xl">Tutup Kasir</h2>
                <p class="text-white/70 text-sm mt-1">
                    Periode: {{ $summary['period_start']->format('d M Y, H:i') }} — {{ $summary['period_end']->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="p-6 sm:p-8">

                {{-- Ringkasan sistem --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-emerald-50 rounded-xl p-4">
                        <p class="text-xs text-emerald-600 font-medium mb-1">Tunai (Sistem)</p>
                        <p class="font-display font-bold text-emerald-700">Rp {{ number_format($summary['system_cash_total'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <p class="text-xs text-blue-600 font-medium mb-1">QRIS</p>
                        <p class="font-display font-bold text-blue-700">Rp {{ number_format($summary['system_qris_total'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-violet-50 rounded-xl p-4">
                        <p class="text-xs text-violet-600 font-medium mb-1">Debit</p>
                        <p class="font-display font-bold text-violet-700">Rp {{ number_format($summary['system_debit_total'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mb-6">
                    {{ $summary['transaction_count'] }} transaksi tercatat pada periode ini.
                    Hanya <strong>tunai</strong> yang perlu dicocokkan dengan uang fisik — QRIS & Debit ditampilkan sebagai info saja.
                </p>

                @if (session('success'))
                    <div class="mb-5 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm">{{ session('success') }}</div>
                @endif

                <form action="{{ route('kasir.cash-closure.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-slate-600">Jumlah Uang Fisik di Laci (Rp)</label>
                        <input type="number" name="physical_cash" x-model="physicalCash"
                               placeholder="Hitung dan masukkan jumlah uang tunai sebenarnya"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-3 text-lg font-display font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>

                    {{-- Selisih live preview --}}
                    <div class="p-4 rounded-xl transition-colors"
                         :class="diff === 0 ? 'bg-slate-50' : (diff > 0 ? 'bg-blue-50' : 'bg-rose-50')">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium" :class="diff === 0 ? 'text-slate-500' : (diff > 0 ? 'text-blue-600' : 'text-rose-600')">
                                <span x-show="diff === 0">Sesuai / Pas</span>
                                <span x-show="diff > 0">Kelebihan Kas</span>
                                <span x-show="diff < 0">Kekurangan Kas</span>
                            </span>
                            <span class="font-display font-bold text-lg"
                                  :class="diff === 0 ? 'text-slate-700' : (diff > 0 ? 'text-blue-700' : 'text-rose-700')"
                                  x-text="'Rp ' + Math.abs(diff).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Catatan (opsional)</label>
                        <textarea name="note" rows="2" placeholder="Contoh: ada uang robek, kembalian kurang ke pembeli, dll"
                                  class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-brand-gradient text-white font-semibold text-sm rounded-xl hover:shadow-lg transition">
                        Konfirmasi Tutup Kasir
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('kasir.cash-closure.history') }}"
           class="block text-center text-sm text-slate-400 hover:text-slate-600 mt-4 transition">
            Lihat riwayat tutup kasir sebelumnya →
        </a>
    </div>
</x-kasir-layout>