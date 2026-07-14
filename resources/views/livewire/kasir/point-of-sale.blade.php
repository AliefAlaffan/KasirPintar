<div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

    {{-- ============ KOLOM KIRI: Pencarian & Grid Produk ============ --}}
    <div class="lg:col-span-3 space-y-4">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="relative">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" wire:model="search" wire:keydown.enter="scanBarcode"
                       placeholder="Scan barcode atau cari nama produk, lalu tekan Enter..."
                       class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" autofocus>
            </div>

            <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                <button wire:click="$set('categoryFilter', null)"
                        class="px-3.5 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition
                        {{ !$categoryFilter ? 'bg-brand-gradient text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
                    Semua
                </button>
                @foreach ($categories as $cat)
                    <button wire:click="$set('categoryFilter', {{ $cat->id }})"
                            class="px-3.5 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition
                            {{ $categoryFilter == $cat->id ? 'bg-brand-gradient text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse ($products as $product)
                <button wire:click="addToCart({{ $product->id }})"
                        class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-3 text-left hover:shadow-md hover:-translate-y-0.5 hover:border-brand-200 transition-all">
                    <div class="relative">
                        <img src="{{ $product->image ? Storage::url($product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=EEF2FF&color=4F46E5&size=128' }}"
                             class="w-full h-20 object-cover rounded-xl mb-2">
                        <span class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-sm font-medium text-slate-800 truncate leading-tight">{{ $product->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $product->stock }} {{ $product->unit }} tersisa</p>
                    <p class="text-sm font-display font-bold text-brand-600 mt-1">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                </button>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-400">Produk tidak ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============ KOLOM KANAN: Keranjang & Checkout ============ --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 lg:sticky lg:top-20 overflow-hidden">

            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-display font-bold text-slate-800">Keranjang Belanja</h3>
                <span class="text-xs font-semibold bg-brand-50 text-brand-600 px-2.5 py-1 rounded-full">
                    {{ count($cart) }} item
                </span>
            </div>

            @if ($errorMessage)
                <div class="mx-4 mt-3 p-3 bg-rose-50 text-rose-600 rounded-xl text-xs font-medium flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    {{ $errorMessage }}
                </div>
            @endif

            {{-- ============ MODAL DETAIL TRANSAKSI (muncul setelah checkout berhasil) ============ --}}
            @if ($lastTransactionId && !empty($lastReceipt))
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
                    x-data="{ show: false }" x-init="show = true" x-show="show" x-transition.opacity>
                    <div x-show="show"
                        x-transition:enter="transition ease-out duration-250"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden max-h-[90vh] flex flex-col">

                        {{-- Header sukses --}}
                        <div class="bg-emerald-500 p-6 text-center shrink-0">
                            <div class="mx-auto w-14 h-14 rounded-full bg-white/20 flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-white text-lg">Pembayaran Berhasil!</h3>
                            <p class="text-emerald-50 text-sm font-mono mt-1">{{ $lastReceipt['invoice_number'] }}</p>
                        </div>

                        {{-- Detail transaksi --}}
                        <div class="p-5 overflow-y-auto">
                            <p class="text-xs text-slate-400 mb-2">{{ $lastReceipt['created_at'] }}</p>

                            <div class="space-y-2 mb-4 pb-4 border-b border-slate-100">
                                @foreach ($lastReceipt['items'] as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">{{ $item['name'] }} <span class="text-slate-400">×{{ $item['quantity'] }}</span></span>
                                        <span class="font-medium text-slate-700">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-1.5 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Subtotal</span>
                                    <span class="text-slate-700">Rp {{ number_format($lastReceipt['subtotal'], 0, ',', '.') }}</span>
                                </div>
                                @if ($lastReceipt['discount'] > 0)
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Diskon</span>
                                        <span class="text-rose-500">- Rp {{ number_format($lastReceipt['discount'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between font-display font-bold text-slate-800 text-base pt-1">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($lastReceipt['total'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-slate-100 mt-2">
                                    <span class="text-slate-500">Dibayar ({{ strtoupper($lastReceipt['payment_method']) }})</span>
                                    <span class="text-slate-700">Rp {{ number_format($lastReceipt['paid_amount'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Kembalian</span>
                                    <span class="font-bold text-emerald-600">Rp {{ number_format($lastReceipt['change_amount'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="p-5 pt-0 flex gap-2 shrink-0">
                            <button wire:click="closeReceipt"
                                    class="flex-1 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">
                                Selesai
                            </button>
                            <a href="{{ route('kasir.transactions.receipt', $lastTransactionId) }}" target="_blank"
                            wire:click="closeReceipt"
                            class="flex-1 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition text-center">
                                Cetak Struk
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 space-y-2 max-h-72 overflow-y-auto">
                @forelse ($cart as $item)
                    <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-slate-400">Rp {{ number_format($item['price'], 0, ',', '.') }} / {{ $item['unit'] }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button wire:click="decrementQty({{ $item['id'] }})"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">−</button>
                            <span class="text-sm font-semibold w-7 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQty({{ $item['id'] }})"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">+</button>
                            <button wire:click="removeFromCart({{ $item['id'] }})"
                                    class="w-7 h-7 rounded-lg text-rose-400 hover:bg-rose-50 hover:text-rose-600 flex items-center justify-center transition ml-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="mx-auto w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-400">Keranjang kosong.</p>
                        <p class="text-xs text-slate-300 mt-0.5">Klik produk atau scan barcode.</p>
                    </div>
                @endforelse
            </div>

            <div class="p-4 bg-slate-50/60 border-t border-slate-100 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="font-medium text-slate-700">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Diskon (Rp)</span>
                    <input type="number" wire:model.live="discount" min="0"
                           class="w-28 rounded-lg border-slate-200 text-sm text-right py-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                    <span class="font-display font-semibold text-slate-800">Total</span>
                    <span class="font-display font-bold text-xl text-brand-600">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>

                {{-- Pilihan Metode Pembayaran --}}
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <button wire:click="$set('paymentMethod', 'cash')"
                            class="py-2.5 rounded-xl text-xs font-semibold transition flex flex-col items-center gap-1
                            {{ $paymentMethod === 'cash' ? 'bg-brand-gradient text-white' : 'bg-white border border-slate-200 text-slate-500' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Tunai
                    </button>
                    <button wire:click="$set('paymentMethod', 'qris')"
                            class="py-2.5 rounded-xl text-xs font-semibold transition flex flex-col items-center gap-1
                            {{ $paymentMethod === 'qris' ? 'bg-brand-gradient text-white' : 'bg-white border border-slate-200 text-slate-500' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        QRIS
                    </button>
                    <button wire:click="$set('paymentMethod', 'debit')"
                            class="py-2.5 rounded-xl text-xs font-semibold transition flex flex-col items-center gap-1
                            {{ $paymentMethod === 'debit' ? 'bg-brand-gradient text-white' : 'bg-white border border-slate-200 text-slate-500' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Debit
                    </button>
                </div>

                @if ($paymentMethod === 'cash')
                    {{-- ===== TUNAI: nominal cepat + input manual ===== --}}
                    <div class="grid grid-cols-4 gap-2">
                        <button wire:click="setExactAmount"
                                class="py-2 rounded-xl text-xs font-semibold border transition
                                {{ (float) $paidAmount === (float) $this->total ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                            Uang Pas
                        </button>
                        @foreach ($this->quickAmounts as $amount)
                            <button wire:click="setQuickAmount({{ $amount }})"
                                    class="py-2 rounded-xl text-xs font-semibold border transition
                                    {{ (float) $paidAmount === (float) $amount ? 'bg-brand-50 border-brand-300 text-brand-700' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                                {{ $amount >= 1000000 ? number_format($amount / 1000000, 1) . 'jt' : number_format($amount / 1000, 0) . 'rb' }}
                            </button>
                        @endforeach
                    </div>

                    <input type="number" wire:model.live="paidAmount" placeholder="atau ketik jumlah manual"
                        class="w-full rounded-xl border-slate-200 text-sm py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">

                    <div class="flex justify-between text-sm px-1">
                        <span class="text-slate-500">Kembalian</span>
                        <span class="font-bold text-emerald-600">Rp {{ number_format($this->change, 0, ',', '.') }}</span>
                    </div>
                @else
                    {{-- ===== QRIS / DEBIT: dibayar pas otomatis, tidak ada kembalian ===== --}}
                    <div class="p-3.5 bg-blue-50 rounded-xl flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-blue-700">Dibayar pas via {{ strtoupper($paymentMethod) }}</p>
                            <p class="text-xs text-blue-500">Rp {{ number_format($this->total, 0, ',', '.') }} — tidak ada kembalian</p>
                        </div>
                    </div>
                @endif

                <button wire:click="checkout" wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-brand-gradient text-white rounded-xl text-sm font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="checkout">Proses Pembayaran</span>
                    <span wire:loading wire:target="checkout" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>