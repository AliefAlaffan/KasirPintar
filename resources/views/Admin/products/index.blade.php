<x-admin-layout title="Kelola Produk">

    {{-- ============ HEADER ============ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="font-display font-bold text-xl text-slate-800">Kelola Produk</h2>
            <p class="text-sm text-slate-400 mt-0.5">{{ $products->total() }} produk terdaftar di katalog toko</p>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 hover:-translate-y-0.5 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- ============ FILTER & SEARCH ============ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama produk atau SKU..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
            <select name="category_id" onchange="this.form.submit()"
                    class="rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition shrink-0">Cari</button>
            @if (request('search') || request('category_id'))
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2.5 bg-slate-50 text-slate-500 text-sm font-medium rounded-xl hover:bg-slate-100 transition shrink-0 text-center">Reset</a>
            @endif
        </form>
    </div>

    {{-- ============ TABEL PRODUK ============ --}}
    @if ($products->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Produk</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">SKU</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Harga Jual</th>
                            <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Stok</th>
                            <th class="px-5 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($products as $product)
                            @php
                                $ratio = $product->min_stock > 0 ? min(100, ($product->stock / $product->min_stock) * 100) : 100;
                                $isLow = $product->stock <= $product->min_stock;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->image ? Storage::url($product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=EEF2FF&color=4F46E5' }}"
                                             class="w-11 h-11 rounded-xl object-cover shrink-0 border border-slate-100">
                                        <span class="font-medium text-slate-800">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-slate-500 bg-slate-50 px-2 py-1 rounded-md">{{ $product->sku }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $product->category->name }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-700">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="w-28">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-semibold {{ $isLow ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ $product->stock }} {{ $product->unit }}
                                            </span>
                                        </div>
                                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $isLow ? 'bg-gradient-to-r from-rose-500 to-amber-500' : 'bg-emerald-500' }}"
                                                 style="width: {{ min(100, $ratio) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    {{-- Dropdown aksi — sekarang pakai x-teleport supaya tidak ter-clip oleh overflow-x-auto pada tabel --}}
                                    <div x-data="{
                                            open: false,
                                            top: 0,
                                            left: 0,
                                            toggle() {
                                                this.open = !this.open;
                                                if (this.open) {
                                                    const rect = this.$refs.btn.getBoundingClientRect();
                                                    this.top = rect.bottom + window.scrollY + 6;
                                                    this.left = rect.right + window.scrollX - 160;
                                                }
                                            }
                                        }" class="relative inline-block">
                                        <button x-ref="btn" @click="toggle()"
                                                class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <template x-teleport="body">
                                            <div x-show="open" @click.outside="open = false" x-transition x-cloak
                                                :style="`position: absolute; top: ${top}px; left: ${left}px;`"
                                                class="w-40 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">

                                                {{-- ISI MENU (Edit/Hapus) TETAP SAMA SEPERTI SEBELUMNYA, cukup dipindah ke sini --}}
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Produk
                                                </a>
                                                <button type="button"
                                                        onclick="document.getElementById('delete-modal-{{ $product->id }}').classList.remove('hidden')"
                                                        class="w-full text-left px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Hapus per-produk (state lokal via id, tidak bentrok antar baris) --}}
                            <div id="delete-modal-{{ $product->id }}" x-data="{}" x-cloak
                                 class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
                                <div @click.outside="document.getElementById('delete-modal-{{ $product->id }}').classList.add('hidden')"
                                     class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                                    <div class="w-11 h-11 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-display font-bold text-slate-800 mb-1">Hapus "{{ $product->name }}"?</h3>
                                    <p class="text-sm text-slate-400 mb-5">Gambar produk dan riwayat terkait tidak bisa dikembalikan setelah dihapus.</p>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="document.getElementById('delete-modal-{{ $product->id }}').classList.add('hidden')"
                                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="flex-1">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-full px-4 py-2.5 bg-rose-600 text-white font-medium text-sm rounded-xl hover:bg-rose-700 transition">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">{{ $products->links() }}</div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
            <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <p class="font-display font-semibold text-slate-700">
                @if(request('search') || request('category_id'))
                    Tidak ada produk yang cocok dengan filter ini
                @else
                    Belum ada produk
                @endif
            </p>
            <p class="text-sm text-slate-400 mt-1 mb-5">Tambahkan produk pertama untuk mulai berjualan.</p>
            <a href="{{ route('admin.products.create') }}"
               class="inline-block px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition-all">
                + Tambah Produk
            </a>
        </div>
    @endif
</x-admin-layout>