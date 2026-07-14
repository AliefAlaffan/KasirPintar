<x-dashboard-layout title="Input Restock">

    <a href="{{ route('manajer.restocks.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke riwayat restock
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8" x-data="restockForm()">
        <h2 class="font-display font-bold text-xl text-slate-800 mb-1">Input Restock Barang</h2>
        <p class="text-sm text-slate-400 mb-6">Catat barang masuk dari supplier — stok produk akan otomatis bertambah.</p>

        <form action="{{ route('manajer.restocks.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-sm font-medium text-slate-600">Supplier</label>
                    <select name="supplier_id" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                        <option value="">Pilih supplier</option>
                        @foreach ($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600">Tanggal Restock</label>
                    <input type="date" name="restock_date" value="{{ date('Y-m-d') }}"
                           class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                </div>
            </div>

            <div class="flex items-center justify-between mb-3">
                <h3 class="font-display font-semibold text-slate-700">Daftar Barang Masuk</h3>
                <button type="button" @click="addItem"
                        class="flex items-center gap-1.5 text-sm text-brand-600 font-medium hover:underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Baris
                </button>
            </div>

            <div class="rounded-xl border border-slate-100 overflow-hidden mb-2">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-left">
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider w-[40%]">Produk</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Harga Modal</th>
                            <th class="px-4 py-2.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Subtotal</th>
                            <th class="px-4 py-2.5 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-2.5">
                                    <select :name="'items['+index+'][product_id]'" x-model="item.product_id"
                                            @change="fillCostPrice(index, $event)"
                                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                        <option value="">Pilih produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-cost="{{ $product->cost_price }}">
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2.5">
                                    <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity"
                                           min="1" class="w-20 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                </td>
                                <td class="px-4 py-2.5">
                                    <input type="number" :name="'items['+index+'][cost_price]'" x-model.number="item.cost_price"
                                           min="0" class="w-28 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                </td>
                                <td class="px-4 py-2.5 font-medium text-slate-600" x-text="formatRupiah(item.quantity * item.cost_price)"></td>
                                <td class="px-4 py-2.5 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mb-6">
                <div class="bg-brand-50 rounded-xl px-5 py-3 text-right">
                    <p class="text-xs text-brand-600 font-medium">Total Restock</p>
                    <p class="font-display font-bold text-lg text-brand-700" x-text="formatRupiah(grandTotal())"></p>
                </div>
            </div>

            <div class="mb-6">
                <label class="text-sm font-medium text-slate-600">Catatan (opsional)</label>
                <textarea name="note" rows="2" placeholder="Contoh: pembayaran cash, ada 1 dus rusak, dll"
                          class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <a href="{{ route('manajer.restocks.index') }}"
                   class="px-5 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</a>
                <button type="submit"
                        class="px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Restock</button>
            </div>
        </form>
    </div>

    <script>
        function restockForm() {
            return {
                items: [{ product_id: '', quantity: 1, cost_price: 0 }],
                addItem() { this.items.push({ product_id: '', quantity: 1, cost_price: 0 }); },
                removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); },
                fillCostPrice(index, event) {
                    const option = event.target.options[event.target.selectedIndex];
                    this.items[index].cost_price = parseFloat(option.dataset.cost || 0);
                },
                grandTotal() {
                    return this.items.reduce((sum, i) => sum + ((i.quantity || 0) * (i.cost_price || 0)), 0);
                },
                formatRupiah(value) { return 'Rp ' + (value || 0).toLocaleString('id-ID'); }
            }
        }
    </script>
</x-dashboard-layout>