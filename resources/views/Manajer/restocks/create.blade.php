<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Restock Barang</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6"
                 x-data="restockForm()">
                <form action="{{ route('manajer.restocks.store') }}" method="POST" @submit="beforeSubmit">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" class="w-full rounded-lg border-gray-300 mt-1" required>
                                <option value="">Pilih supplier</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Tanggal Restock</label>
                            <input type="date" name="restock_date" value="{{ date('Y-m-d') }}"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                        </div>
                    </div>

                    <h3 class="font-semibold text-gray-800 mb-2">Daftar Barang Masuk</h3>

                    <div class="space-y-2 mb-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-2 items-center">
                                <div class="col-span-5">
                                    <select :name="'items['+index+'][product_id]'" x-model="item.product_id"
                                            @change="fillCostPrice(index)"
                                            class="w-full rounded-lg border-gray-300 text-sm" required>
                                        <option value="">Pilih produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-cost="{{ $product->cost_price }}">
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity"
                                           placeholder="Qty" min="1" class="w-full rounded-lg border-gray-300 text-sm" required>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" :name="'items['+index+'][cost_price]'" x-model.number="item.cost_price"
                                           placeholder="Harga modal" min="0" class="w-full rounded-lg border-gray-300 text-sm" required>
                                </div>
                                <div class="col-span-1 text-sm text-gray-600" x-text="formatRupiah(item.quantity * item.cost_price)"></div>
                                <div class="col-span-1 text-right">
                                    <button type="button" @click="removeItem(index)" class="text-red-500 text-sm">✕</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addItem"
                            class="text-sm text-blue-600 hover:underline mb-4">+ Tambah Baris Barang</button>

                    <div class="text-right font-semibold text-gray-800 mb-4">
                        Total: <span x-text="formatRupiah(grandTotal())"></span>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Catatan (opsional)</label>
                        <textarea name="note" class="w-full rounded-lg border-gray-300 mt-1" rows="2"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('manajer.restocks.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan Restock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function restockForm() {
            return {
                items: [{ product_id: '', quantity: 1, cost_price: 0 }],
                addItem() {
                    this.items.push({ product_id: '', quantity: 1, cost_price: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) this.items.splice(index, 1);
                },
                fillCostPrice(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    this.items[index].cost_price = parseFloat(option.dataset.cost || 0);
                },
                grandTotal() {
                    return this.items.reduce((sum, i) => sum + (i.quantity * i.cost_price || 0), 0);
                },
                formatRupiah(value) {
                    return 'Rp ' + (value || 0).toLocaleString('id-ID');
                }
            }
        }
    </script>
</x-app-layout>