<x-admin-layout title="Kelola Kategori">

    <div x-data="{ showAddModal: false, editingId: null, deletingId: null, deletingName: '' }">

        {{-- ============ HEADER ============ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-display font-bold text-xl text-slate-800">Kelola Kategori</h2>
                <p class="text-sm text-slate-400 mt-0.5">{{ $categories->total() }} kategori terdaftar untuk pengelompokan produk</p>
            </div>

            <button @click="showAddModal = true"
                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 hover:-translate-y-0.5 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kategori
            </button>
        </div>

        {{-- ============ SEARCH (diperbaiki: Enter + tombol submit eksplisit) ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
            <form method="GET" class="relative flex gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama kategori, lalu tekan Enter..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <button type="submit"
                        class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition shrink-0">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-4 py-2.5 bg-slate-50 text-slate-500 text-sm font-medium rounded-xl hover:bg-slate-100 transition shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- ============ GRID KATEGORI ============ --}}
        @if ($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $palettes = [
                        ['bg-indigo-50', 'text-indigo-600'],
                        ['bg-emerald-50', 'text-emerald-600'],
                        ['bg-amber-50', 'text-amber-600'],
                        ['bg-rose-50', 'text-rose-600'],
                        ['bg-blue-50', 'text-blue-600'],
                        ['bg-violet-50', 'text-violet-600'],
                    ];
                @endphp
                @foreach ($categories as $index => $category)
                    @php $palette = $palettes[$index % count($palettes)]; @endphp
                    <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl {{ $palette[0] }} {{ $palette[1] }} flex items-center justify-center font-display font-bold text-lg">
                                {{ strtoupper(substr($category->name, 0, 1)) }}
                            </div>

                            {{-- Dropdown aksi — @click.outside sekarang di WRAPPER, bukan di tombol --}}
                            <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                                <button @click="open = !open"
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-50 hover:text-slate-600 flex items-center justify-center transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition x-cloak
                                     class="absolute right-0 top-9 w-36 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-10">
                                    <button type="button"
                                            @click="editingId = {{ $category->id }}; open = false"
                                            class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button"
                                            @click="deletingId = {{ $category->id }}; deletingName = '{{ addslashes($category->name) }}'; open = false"
                                            class="w-full text-left px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="font-display font-semibold text-slate-800">{{ $category->name }}</h3>
                        <p class="text-sm text-slate-400 mt-1">{{ $category->products_count }} produk</p>
                    </div>

                    {{-- Modal Edit khusus kategori ini — SEKARANG DI LUAR dropdown, tidak akan ikut ke-hide --}}
                    <div x-show="editingId === {{ $category->id }}" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
                        <div @click.outside="editingId = null" x-show="editingId === {{ $category->id }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="font-display font-bold text-slate-800 text-lg mb-4">Edit Kategori</h3>
                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                @csrf @method('PUT')
                                <label class="text-sm font-medium text-slate-600">Nama Kategori</label>
                                <input type="text" name="name" value="{{ $category->name }}"
                                       class="w-full rounded-xl border-slate-200 mt-1.5 mb-5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                <div class="flex gap-2">
                                    <button type="button" @click="editingId = null"
                                            class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                    <button type="submit"
                                            class="flex-1 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Hapus khusus kategori ini — juga di luar dropdown --}}
                    <div x-show="deletingId === {{ $category->id }}" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
                        <div @click.outside="deletingId = null" x-show="deletingId === {{ $category->id }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                            <div class="w-11 h-11 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-slate-800 mb-1">Hapus "{{ $category->name }}"?</h3>
                            <p class="text-sm text-slate-400 mb-5">Kategori dengan produk terkait tidak bisa dihapus. Tindakan ini tidak bisa dibatalkan.</p>
                            <div class="flex gap-2">
                                <button type="button" @click="deletingId = null"
                                        class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 bg-rose-600 text-white font-medium text-sm rounded-xl hover:bg-rose-700 transition">Ya, Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $categories->links() }}</div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-slate-700">
                    @if(request('search'))
                        Tidak ada kategori bernama "{{ request('search') }}"
                    @else
                        Belum ada kategori
                    @endif
                </p>
                <p class="text-sm text-slate-400 mt-1 mb-5">Buat kategori pertama untuk mulai mengelompokkan produk Anda.</p>
                <button @click="showAddModal = true"
                        class="px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition-all">
                    + Tambah Kategori
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
                <h3 class="font-display font-bold text-slate-800 text-lg mb-4">Tambah Kategori Baru</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <label class="text-sm font-medium text-slate-600">Nama Kategori</label>
                    <input type="text" name="name" placeholder="Contoh: Minuman, Snack, Alat Tulis"
                           class="w-full rounded-xl border-slate-200 mt-1.5 mb-5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required autofocus>
                    <div class="flex gap-2">
                        <button type="button" @click="showAddModal = false"
                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>