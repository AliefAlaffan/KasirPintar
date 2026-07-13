<x-dashboard-layout title="Manajemen User">

    <div x-data="{ showAddModal: false }">

        {{-- ============ HEADER ============ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-display font-bold text-xl text-slate-800">Manajemen User</h2>
                <p class="text-sm text-slate-400 mt-0.5">{{ $users->total() }} akun Manajer & Kasir terdaftar</p>
            </div>

            <button @click="showAddModal = true"
                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 hover:-translate-y-0.5 transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </button>
        </div>

        {{-- ============ SEARCH ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
            <form method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama user, lalu tekan Enter..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition shrink-0">Cari</button>
                @if (request('search'))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-50 text-slate-500 text-sm font-medium rounded-xl hover:bg-slate-100 transition shrink-0">Reset</a>
                @endif
            </form>
        </div>

        {{-- ============ TABEL USER ============ --}}
        @if ($users->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">User</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Role</th>
                                <th class="px-5 py-3.5 font-medium text-slate-400 text-xs uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @php
                                $roleBadge = [
                                    'admin'   => ['bg-violet-50', 'text-violet-600', 'Admin'],
                                    'manajer' => ['bg-blue-50', 'text-blue-600', 'Manajer'],
                                    'kasir'   => ['bg-emerald-50', 'text-emerald-600', 'Kasir'],
                                ];
                                $rolePalette = [
                                    'admin'   => ['bg-violet-100', 'text-violet-600'],
                                    'manajer' => ['bg-blue-100', 'text-blue-600'],
                                    'kasir'   => ['bg-emerald-100', 'text-emerald-600'],
                                ];
                            @endphp
                            @foreach ($users as $user)
                                @php $rb = $roleBadge[$user->role] ?? ['bg-slate-100', 'text-slate-600', ucfirst($user->role)]; @endphp
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full {{ $rolePalette[$user->role][0] ?? 'bg-slate-100' }} {{ $rolePalette[$user->role][1] ?? 'text-slate-600' }} flex items-center justify-center font-display font-bold shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $rb[0] }} {{ $rb[1] }}">
                                            {{ $rb[2] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        {{-- Toggle Switch --}}
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST"
                                              x-data="{ active: {{ $user->is_active ? 'true' : 'false' }} }"
                                              @submit="active = !active">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors duration-200"
                                                    :class="active ? 'bg-emerald-500' : 'bg-slate-200'">
                                                <span class="inline-block w-4 h-4 transform bg-white rounded-full shadow transition-transform duration-200"
                                                      :class="active ? 'translate-x-6' : 'translate-x-1'"></span>
                                            </button>
                                            <span class="text-xs ml-2 align-middle font-medium" :class="active ? 'text-emerald-600' : 'text-slate-400'"
                                                  x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div x-data="{
                                                open: false, top: 0, left: 0,
                                                toggle() {
                                                    this.open = !this.open;
                                                    if (this.open) {
                                                        const r = this.$refs.btn.getBoundingClientRect();
                                                        this.top = r.bottom + window.scrollY + 6;
                                                        this.left = r.right + window.scrollX - 160;
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
                                                    <button type="button"
                                                            @click="open = false; document.getElementById('edit-user-{{ $user->id }}').classList.remove('hidden')"
                                                            class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit User
                                                    </button>
                                                    <button type="button"
                                                            @click="open = false; document.getElementById('delete-user-{{ $user->id }}').classList.remove('hidden')"
                                                            class="w-full text-left px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus User
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                
                                {{-- Modal Edit User --}}
                                <div id="edit-user-{{ $user->id }}" x-data="{ showPassword: false }" x-cloak
                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
                                    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
                                        <h3 class="font-display font-bold text-slate-800 text-lg mb-4">Edit User</h3>
                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
                                            @csrf @method('PUT')

                                            <div>
                                                <label class="text-sm font-medium text-slate-600">Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ $user->name }}"
                                                    class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium text-slate-600">Email</label>
                                                <input type="email" name="email" value="{{ $user->email }}"
                                                    class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium text-slate-600">Password Baru</label>
                                                <div class="relative mt-1.5">
                                                    <input :type="showPassword ? 'text' : 'password'" name="password"
                                                        placeholder="Kosongkan jika tidak ingin diganti"
                                                        class="w-full rounded-xl border-slate-200 pr-10 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                                    <button type="button" @click="showPassword = !showPassword"
                                                            class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter kalau ingin diganti.</p>
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium text-slate-600">Role</label>
                                                <select name="role" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                                    <option value="manajer" @selected($user->role === 'manajer')>Manajer Operasional</option>
                                                    <option value="kasir" @selected($user->role === 'kasir')>Kasir</option>
                                                </select>
                                            </div>

                                            <div class="flex gap-2 pt-1">
                                                <button type="button" onclick="document.getElementById('edit-user-{{ $user->id }}').classList.add('hidden')"
                                                        class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                                <button type="submit"
                                                        class="flex-1 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Hapus per-user --}}
                                <div id="delete-user-{{ $user->id }}" x-cloak
                                     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
                                    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                                        <div class="w-11 h-11 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-display font-bold text-slate-800 mb-1">Hapus akun "{{ $user->name }}"?</h3>
                                        <p class="text-sm text-slate-400 mb-5">User ini tidak akan bisa login lagi setelah dihapus. Tindakan ini tidak bisa dibatalkan.</p>
                                        <div class="flex gap-2">
                                            <button type="button" onclick="document.getElementById('delete-user-{{ $user->id }}').classList.add('hidden')"
                                                    class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-1">
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
                <div class="px-5 py-4 border-t border-slate-100">{{ $users->links() }}</div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                    </svg>
                </div>
                <p class="font-display font-semibold text-slate-700">
                    @if(request('search')) Tidak ada user bernama "{{ request('search') }}" @else Belum ada user lain @endif
                </p>
                <p class="text-sm text-slate-400 mt-1 mb-5">Tambahkan akun Manajer atau Kasir untuk mulai mengoperasikan toko.</p>
                <button @click="showAddModal = true"
                        class="px-5 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition-all">
                    + Tambah User
                </button>
            </div>
        @endif

        {{-- ============ MODAL TAMBAH USER ============ --}}
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
                <h3 class="font-display font-bold text-slate-800 text-lg mb-4">Tambah User Baru</h3>
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-slate-600">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Email</label>
                        <input type="email" name="email" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Role</label>
                        <select name="role" class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">Pilih role</option>
                            <option value="manajer">Manajer Operasional</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="button" @click="showAddModal = false"
                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 font-medium text-sm rounded-xl hover:bg-slate-100 transition">Batal</button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-brand-gradient text-white font-medium text-sm rounded-xl hover:shadow-lg transition">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>