<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Supplier</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari supplier..."
                               class="rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">Cari</button>
                    </form>

                    <button type="button" data-modal-target="modal-add" data-modal-toggle="modal-add"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        + Tambah Supplier
                    </button>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nama Supplier</th>
                            <th class="px-4 py-3">Telepon</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $supplier->name }}</td>
                                <td class="px-4 py-3">{{ $supplier->phone }}</td>
                                <td class="px-4 py-3">{{ $supplier->email }}</td>
                                <td class="px-4 py-3">{{ $supplier->address }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button data-modal-target="edit-{{ $supplier->id }}" data-modal-toggle="edit-{{ $supplier->id }}"
                                            class="text-blue-600 hover:underline">Edit</button>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal Edit per baris --}}
                            <div id="edit-{{ $supplier->id }}" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">
                                    <h3 class="text-lg font-semibold mb-4">Edit Supplier</h3>
                                    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $supplier->name }}"
                                               class="w-full rounded-lg border-gray-300 mb-4">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" data-modal-hide="edit-{{ $supplier->id }}"
                                                    class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada supplier.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $suppliers->links() }}</div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modal-add" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Tambah Supplier</h3>
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama supplier"
                       class="w-full rounded-lg border-gray-300 mb-4" required>
                <div class="flex justify-end gap-2">
                    <button type="button" data-modal-hide="modal-add" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>