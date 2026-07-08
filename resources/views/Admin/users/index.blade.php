<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen User</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama..." class="rounded-lg border-gray-300 text-sm">
                        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Cari</button>
                    </form>

                    <button data-modal-target="modal-add-user" data-modal-toggle="modal-add-user"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        + Tambah User
                    </button>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 capitalize">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada user lain.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah User --}}
    <div id="modal-add-user" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Tambah User Baru</h3>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="name" placeholder="Nama lengkap" class="w-full rounded-lg border-gray-300" required>
                <input type="email" name="email" placeholder="Email" class="w-full rounded-lg border-gray-300" required>
                <input type="password" name="password" placeholder="Password (min. 8 karakter)" class="w-full rounded-lg border-gray-300" required>
                <select name="role" class="w-full rounded-lg border-gray-300" required>
                    <option value="">Pilih role</option>
                    <option value="manajer">Manajer Operasional</option>
                    <option value="kasir">Kasir</option>
                </select>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-hide="modal-add-user" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>