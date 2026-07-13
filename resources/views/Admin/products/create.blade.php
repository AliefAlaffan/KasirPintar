<x-dashboard-layout title="Tambah Produk">

    <div class="max-w-3xl">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar produk
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
            <h2 class="font-display font-bold text-xl text-slate-800 mb-1">Tambah Produk Baru</h2>
            <p class="text-sm text-slate-400 mb-6">Kode SKU akan digenerate otomatis begitu produk disimpan.</p>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="{ preview: null }" class="space-y-5">
                @csrf

                {{-- Upload Gambar dengan Preview --}}
                <div class="flex items-center gap-4">
                    <label class="relative w-24 h-24 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center cursor-pointer hover:border-brand-400 transition shrink-0 overflow-hidden bg-slate-50">
                        <template x-if="!preview">
                            <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <img :src="preview" x-show="preview" x-cloak class="w-full h-full object-cover">
                        <input type="file" name="image" accept="image/*" class="hidden"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Foto Produk</p>
                        <p class="text-xs text-slate-400">Opsional. Klik kotak untuk upload (JPG/PNG, maks 2MB).</p>
                        @error('image') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-600">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Kategori</label>
                        <select name="category_id" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Supplier (opsional)</label>
                        <select name="supplier_id" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="">Tanpa supplier</option>
                            @foreach ($suppliers as $sup)
                                <option value="{{ $sup->id }}" @selected(old('supplier_id') == $sup->id)>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Harga Modal (Rp)</label>
                        <input type="number" name="cost_price" value="{{ old('cost_price') }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                        @error('cost_price') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Harga Jual (Rp)</label>
                        <input type="number" name="sell_price" value="{{ old('sell_price') }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                        @error('sell_price') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Stok Awal</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Stok Minimum</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Satuan</label>
                        <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" placeholder="pcs, kg, dus"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.products.index') }}"
                       class="px-5 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</a>
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>