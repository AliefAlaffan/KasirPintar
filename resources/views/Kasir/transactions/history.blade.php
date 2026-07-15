<x-kasir-layout title="Riwayat Transaksi Saya">

    <div class="max-w-4xl mx-auto" x-data="{ voidingId: null, voidingInvoice: '' }">
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 mb-4 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke POS
        </a>

        <h2 class="font-display font-bold text-xl text-slate-800 mb-4">Riwayat Transaksi Saya</h2>

        @if (session('success'))
            <div class="mb-4 p-3.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3.5 bg-rose-50 text-rose-700 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        @if ($transactions->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Invoice</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Waktu</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Metode</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($transactions as $trx)
                                <tr class="hover:bg-slate-50/60 transition {{ $trx->is_voided ? 'opacity-50' : '' }}">
                                    <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $trx->invoice_number }}</td>
                                    <td class="px-5 py-4 text-slate-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4 font-medium text-slate-700">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 uppercase text-xs text-slate-500">{{ $trx->payment_method }}</td>
                                    <td class="px-5 py-4">
                                        @if ($trx->is_voided)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">Dibatalkan</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">Berhasil</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right space-x-2">
                                        <a href="{{ route('kasir.transactions.receipt', $trx->id) }}" target="_blank"
                                           class="text-brand-600 text-sm font-medium hover:underline">Struk</a>
                                        @if (!$trx->is_voided)
                                            <button @click="voidingId = {{ $trx->id }}; voidingInvoice = '{{ $trx->invoice_number }}'"
                                                    class="text-rose-500 text-sm font-medium hover:underline">Batalkan</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-slate-100">{{ $transactions->links() }}</div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                <p class="text-sm text-slate-400">Belum ada transaksi yang Anda proses.</p>
            </div>
        @endif

        {{-- Modal Void Transaksi --}}
        <div x-show="voidingId" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div @click.outside="voidingId = null" x-show="voidingId"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                <div class="w-11 h-11 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 mb-1">Batalkan transaksi <span x-text="voidingInvoice"></span>?</h3>
                <p class="text-sm text-slate-400 mb-4">Stok produk akan dikembalikan otomatis. Wajib isi alasan pembatalan.</p>

                <form :action="'/kasir/transactions/' + voidingId + '/void'" method="POST">
                    @csrf
                    <textarea name="reason" rows="3" placeholder="Contoh: salah input jumlah, pembeli batal beli, dll"
                              class="w-full rounded-xl border-slate-200 mb-4 text-sm focus:ring-2 focus:ring-rose-400 focus:border-rose-400" required></textarea>
                    <div class="flex gap-2">
                        <button type="button" @click="voidingId = null"
                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-medium text-sm rounded-xl hover:bg-rose-700 transition">Ya, Batalkan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-kasir-layout>