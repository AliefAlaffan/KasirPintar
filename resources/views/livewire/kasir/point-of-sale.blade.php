<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- KOLOM KIRI: Pencarian & Grid Produk --}}
    <div class="lg:col-span-3 space-y-4">

        <div class="bg-white rounded-xl shadow p-4">
            <input type="text" wire:model="search" wire:keydown.enter="scanBarcode"
                   placeholder="Scan barcode atau cari nama produk, lalu tekan Enter..."
                   class="w-full rounded-lg border-gray-300 text-sm" autofocus>

            <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                <button wire:click="$set('categoryFilter', null)"
                        class="px-3 py-1.5 rounded-full text-xs whitespace-nowrap
                        {{ !$categoryFilter ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                    Semua
                </button>
                @foreach ($categories as $cat)
                    <button wire:click="$set('categoryFilter', {{ $cat->id }})"
                            class="px-3 py-1.5 rounded-full text-xs whitespace-nowrap
                            {{ $categoryFilter == $cat->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse ($products as $product)
                <button wire:click="addToCart({{ $product->id }})"
                        class="bg-white rounded-xl shadow p-3 text-left hover:ring-2 hover:ring-blue-400 transition">
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/80' }}"
                         class="w-full h-20 object-cover rounded-lg mb-2">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->stock }} {{ $product->unit }} tersisa</p>
                    <p class="text-sm font-bold text-blue-600 mt-1">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                </button>
            @empty
                <p class="col-span-full text-center text-gray-400 py-8">Produk tidak ditemukan.</p>
            @endforelse
        </div>
    </div>

    {{-- KOLOM KANAN: Keranjang & Checkout --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow p-4 sticky top-4">
            <h3 class="font-semibold text-gray-800 mb-3">Keranjang Belanja</h3>

            @if ($errorMessage)
                <div class="p-2 bg-red-100 text-red-700 rounded-lg text-xs mb-3">{{ $errorMessage }}</div>
            @endif

            @if ($successMessage)
                <div class="p-2 bg-green-100 text-green-700 rounded-lg text-xs mb-3 flex justify-between items-center">
                    <span>{{ $successMessage }}</span>
                    @if ($lastTransactionId)
                        <a href="{{ route('kasir.transactions.receipt', $lastTransactionId) }}" target="_blank"
                           class="underline font-medium">Cetak Struk</a>
                    @endif
                </div>
            @endif

            <div class="space-y-2 max-h-80 overflow-y-auto mb-3">
                @forelse ($cart as $item)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }} / {{ $item['unit'] }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="decrementQty({{ $item['id'] }})"
                                    class="w-6 h-6 rounded-full bg-gray-100 text-sm">-</button>
                            <span class="text-sm w-6 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQty({{ $item['id'] }})"
                                    class="w-6 h-6 rounded-full bg-gray-100 text-sm">+</button>
                            <button wire:click="removeFromCart({{ $item['id'] }})"
                                    class="text-red-500 text-xs ml-1">✕</button>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">Keranjang kosong. Klik produk atau scan barcode.</p>
                @endforelse
            </div>

            <div class="border-t pt-3 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Diskon (Rp)</span>
                    <input type="number" wire:model.live="discount" min="0"
                           class="w-28 rounded-lg border-gray-300 text-sm text-right">
                </div>

                <div class="flex justify-between text-base font-bold text-gray-800 pt-1">
                    <span>Total</span>
                    <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t mt-3 pt-3 space-y-2">
                <select wire:model="paymentMethod" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="cash">Tunai</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Kartu Debit</option>
                </select>

                <input type="number" wire:model.live="paidAmount" placeholder="Jumlah dibayar"
                       class="w-full rounded-lg border-gray-300 text-sm">

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Kembalian</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($this->change, 0, ',', '.') }}</span>
                </div>

                <button wire:click="checkout" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="checkout">Proses Pembayaran</span>
                    <span wire:loading wire:target="checkout">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</div>