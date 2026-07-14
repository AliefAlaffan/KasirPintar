<x-dashboard-layout title="Detail Stock Opname">

    <a href="{{ route('manajer.stock-opnames.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke riwayat opname
    </a>

    <div class="max-w-3xl" x-data="{ confirming: false }">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">

            <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-6 border-b border-slate-100">
                <div>
                    <p class="text-xs text-slate-400">Tanggal Opname</p>
                    <p class="font-display font-bold text-slate-800 text-lg">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold
                        {{ $opname->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $opname->status === 'completed' ? '✓ Selesai & Diterapkan' : 'Menunggu Konfirmasi' }}
                    </span>

                    @if ($opname->status === 'draft')
                        <button type="button" @click="confirming = true"
                                class="px-4 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition">
                            Konfirmasi & Terapkan
                        </button>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="mb-5 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm">{{ session('success') }}</div>
            @endif

            <h3 class="font-display font-semibold text-slate-700 mb-3">Hasil Pencocokan Stok</h3>
            <div class="rounded-xl border border-slate-100 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Produk</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Stok Sistem</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Stok Fisik</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider text-right">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($opname->details as $detail)
                            <tr>
                                <td class="px-4 py-3 text-slate-700">{{ $detail->product->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $detail->system_stock }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $detail->physical_stock }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if ($detail->difference == 0)
                                        <span class="text-slate-300 text-xs">Sesuai</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $detail->difference < 0 ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-blue-600' }}">
                                            {{ $detail->difference > 0 ? '+' : '' }}{{ $detail->difference }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($opname->note)
                <div class="mt-5 p-4 bg-amber-50 rounded-xl">
                    <p class="text-xs font-medium text-amber-700 mb-1">Catatan</p>
                    <p class="text-sm text-amber-800">{{ $opname->note }}</p>
                </div>
            @endif
        </div>

        {{-- Modal Konfirmasi --}}
        <div x-show="confirming" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div @click.outside="confirming = false" x-show="confirming"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                <div class="w-11 h-11 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 mb-1">Terapkan hasil opname ini?</h3>
                <p class="text-sm text-slate-400 mb-5">Stok sistem akan disesuaikan mengikuti hasil hitung fisik. Tindakan ini tidak bisa dibatalkan setelah diterapkan.</p>
                <div class="flex gap-2">
                    <button type="button" @click="confirming = false"
                            class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                    <form action="{{ route('manajer.stock-opnames.complete', $opname->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-emerald-600 text-white font-medium text-sm rounded-xl hover:bg-emerald-700 transition">Ya, Terapkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>