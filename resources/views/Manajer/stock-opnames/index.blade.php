<x-dashboard-layout title="Riwayat Stock Opname">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Riwayat Stock Opname</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ $opnames->total() }} sesi pencocokan stok tercatat</p>
        </div>
        <a href="{{ route('manajer.stock-opnames.create') }}"
           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 hover:-translate-y-0.5 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Opname Baru
        </a>
    </div>

    @if ($opnames->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Tanggal Opname</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Diinput oleh</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Jumlah Item</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($opnames as $opname)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-4 font-medium text-slate-700">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $opname->user->name }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $opname->details_count ?? $opname->details->count() }} produk</td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $opname->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $opname->status === 'completed' ? 'Selesai' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('manajer.stock-opnames.show', $opname->id) }}"
                                       class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium hover:underline">
                                        Detail
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">{{ $opnames->links() }}</div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
            <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <p class="font-display font-semibold text-slate-700">Belum ada riwayat stock opname</p>
            <p class="text-sm text-slate-400 mt-1 mb-5">Lakukan pencocokan stok fisik pertama untuk menjaga akurasi data.</p>
            <a href="{{ route('manajer.stock-opnames.create') }}"
               class="inline-block px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition-all">
                + Opname Baru
            </a>
        </div>
    @endif
</x-dashboard-layout>