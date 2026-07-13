<x-dashboard-layout title="Kelola Supplier">

    <div x-data="{ showAddModal: false, editingId: null, deletingId: null }">

        {{-- ============ HEADER ============ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-display font-bold text-xl text-slate-800">Kelola Supplier</h2>
                <p class="text-sm text-slate-400 mt-0.5">{{ $suppliers->total() }} supplier terdaftar sebagai mitra pemasok barang</p>
            </div>

            <button @click="showAddModal = true"
                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 hover:-translate-y-0.5 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Supplier
            </button>
        </div>

        {{-- ============ SEARCH ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
            <form method="GET" class="relative flex gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama supplier, lalu tekan Enter..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <button type="submit"
                        class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition shrink-0">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.suppliers.index') }}"
                       class="px-4 py-2.5 bg-slate-50 text-slate-500 text-sm font-medium rounded-xl hover:bg-slate-100 transition shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- ============ TABEL SUPPLIER ============ --}}
        @if ($suppliers->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Supplier</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Kontak</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Alamat</th>
                                <th class="px-5 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @php
                                $palettes = [
                                    ['bg-indigo-50', 'text-indigo-600'], ['bg-emerald-50', 'text-emerald-600'],
                                    ['bg-amber-50', 'text-amber-600'], ['bg-rose-50', 'text-rose-600'],
                                    ['bg-blue-50', 'text-blue-600'], ['bg-violet-50', 'text-violet-600'],
                                ];
                            @endphp
                            @foreach ($suppliers as $index => $supplier)
                                @php $palette = $palettes[$index % count($palettes)]; @endphp
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl {{ $palette[0] }} {{ $palette[1] }} flex items-center justify-center font-display font-bold shrink-0">
                                                {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-slate-800">{{ $supplier->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="space-y-0.5">
                                            @if ($supplier->phone)
                                                <p class="text-slate-600 flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    {{ $supplier->phone }}
                                                </p>
                                            @endif
                                            @if ($supplier->email)
                                                <p class="text-slate-400 text-xs flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $supplier->email }}
                                                </p>
                                            @endif
                                            @if (!$supplier->phone && !$supplier->email)
                                                <span class="text-slate-300 text-xs italic">Belum ada kontak</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 max-w-xs truncate">
                                        {{ $supplier->address ?: '—' }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        {{-- Dropdown aksi Supplier — pakai x-teleport, dan tetap kompatibel dengan editingId/deletingId di scope terluar --}}
                                        <div x-data="{
                                                open: false,
                                                top: 0,
                                                left: 0,
                                                toggle() {
                                                    this.open = !this.open;
                                                    if (this.open) {
                                                        const rect = this.$refs.btn.getBoundingClientRect();
                                                        this.top = rect.bottom + window.scrollY + 6;
                                                        this.left = rect.right + window.scrollX - 144;
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
                                                    class="w-36 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">

                                                    {{-- Perhatikan: editingId/deletingId ini tetap merujuk ke scope Alpine terluar (x-data di pembungkus halaman),
                                                        karena x-teleport memindahkan lokasi DOM tapi Alpine tetap mewarisi scope dari tempat definisi asal --}}
                                                    <button type="button" @click="editingId = {{ $supplier->id }}; open = false"
                                                            class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <button type="button" @click="deletingId = {{ $supplier->id }}; open = false"
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

                                {{-- Modal Edit — di luar dropdown supaya tidak ikut ke-hide --}}
                                <div x-show="editingId === {{ $supplier->id }}" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
                                    <div @click.outside="editingId = null" x-show="editingId === {{ $supplier->id }}"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
                                        <h3 class="font-display font-bold text-slate-800 text-lg mb-4">Edit Supplier</h3>
                                        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" class="space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="text-sm font-medium text-slate-600">Nama Supplier</label>
                                                <input type="text" name="name" value="{{ $supplier->name }}"
                                                       class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-sm font-medium text-slate-600">Telepon</label>
                                                    <input type="text" name="phone" value="{{ $supplier->phone }}"
                                                           class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-slate-600">Email</label>
                                                    <input type="email" name="email" value="{{ $supplier->email }}"
                                                           class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-slate-600">Alamat</label>
                                                <textarea name="address" rows="2"
                                                          class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">{{ $supplier->address }}</textarea>
                                            </div>
                                            <div class="flex gap-2 pt-1">
                                                <button type="button" @click="editingId = null"
                                                        class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                                <button type="submit"
                                                        class="flex-1 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Hapus --}}
                                <div x-show="deletingId === {{ $supplier->id }}" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
                                    <div @click.outside="deletingId = null" x-show="deletingId === {{ $supplier->id }}"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                                        <div class="w-11 h-11 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-display font-bold text-slate-800 mb-1">Hapus "{{ $supplier->name }}"?</h3>
                                        <p class="text-sm text-slate-400 mb-5">Produk yang masih terhubung ke supplier ini tidak akan terhapus, hanya tautannya yang dilepas.</p>
                                        <div class="flex gap-2">
                                            <button type="button" @click="deletingId = null"
                                                    class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="flex-1">
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
                <div class="px-5 py-4 border-t border-slate-100">{{ $suppliers->links() }}</div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-slate-700">
                    @if(request('search'))
                        Tidak ada supplier bernama "{{ request('search') }}"
                    @else
                        Belum ada supplier
                    @endif
                </p>
                <p class="text-sm text-slate-400 mt-1 mb-5">Tambahkan supplier untuk mulai mencatat sumber pasokan produk Anda.</p>
                <button @click="showAddModal = true"
                        class="px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition-all">
                    + Tambah Supplier
                </button>
            </div>
        @endif

        {{-- ============ MODAL TAMBAH ============ --}}
        <div x-show="showAddModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div @click.outside="showAddModal = false" x-show="showAddModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
                <div class="w-11 h-11 rounded-xl bg-brand-gradient flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-lg mb-4">Tambah Supplier Baru</h3>
                <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-slate-600">Nama Supplier</label>
                        <input type="text" name="name" placeholder="Contoh: CV Sumber Jaya"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required autofocus>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium text-slate-600">Telepon</label>
                            <input type="text" name="phone" placeholder="08xx-xxxx-xxxx"
                                   class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-600">Email</label>
                            <input type="email" name="email" placeholder="opsional"
                                   class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Alamat</label>
                        <textarea name="address" rows="2" placeholder="Opsional"
                                  class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="button" @click="showAddModal = false"
                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>