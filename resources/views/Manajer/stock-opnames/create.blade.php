<x-dashboard-layout title="Stock Opname Baru">

    <a href="{{ route('manajer.stock-opnames.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke riwayat opname
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8" x-data="{ search: '' }">
        <h2 class="font-display font-bold text-xl text-slate-800 mb-1">Stock Opname Baru</h2>
        <p class="text-sm text-slate-400 mb-6">Isi jumlah stok fisik hasil hitung langsung di toko. Produk yang stoknya sudah sesuai bisa dibiarkan apa adanya.</p>

        <form action="{{ route('manajer.stock-opnames.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="text-sm font-medium text-slate-600">Tanggal Opname</label>
                <input type="date" name="opname_date" value="{{ date('Y-m-d') }}"
                       class="w-full sm:w-64 rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            </div>

            <div class="relative mb-3">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="search" placeholder="Cari produk untuk mempersempit daftar di bawah..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="rounded-xl border border-slate-100 overflow-hidden mb-6">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Produk</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Stok Sistem</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Stok Fisik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 max-h-96">
                        @foreach ($products as $index => $product)
                            <tr x-show="search === '' || '{{ strtolower($product->name) }}'.includes(search.toLowerCase())">
                                <td class="px-4 py-2.5 text-slate-700">
                                    {{ $product->name }}
                                    <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                                </td>
                                <td class="px-4 py-2.5 text-slate-500">{{ $product->stock }} {{ $product->unit }}</td>
                                <td class="px-4 py-2.5">
                                    <input type="number" name="items[{{ $index }}][physical_stock]"
                                           value="{{ $product->stock }}" min="0"
                                           class="w-24 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-6">
                <label class="text-sm font-medium text-slate-600">Catatan (opsional)</label>
                <textarea name="note" rows="2" placeholder="Contoh: ada kerusakan kemasan, dus basah, dll"
                          class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <a href="{{ route('manajer.stock-opnames.index') }}"
                   class="px-5 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</a>
                <button type="submit"
                        class="px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Draft</button>
            </div>
        </form>
    </div>
</x-dashboard-layout>