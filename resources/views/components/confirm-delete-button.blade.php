@props(['action', 'label' => 'Hapus', 'confirmText' => 'Yakin ingin menghapus data ini?'])

<div x-data="{ open: false }" class="inline">
    <button type="button" @click="open = true" class="text-red-600 hover:underline text-sm">
        {{ $label }}
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm" @click.outside="open = false">
            <h3 class="font-semibold text-gray-800 mb-2">Konfirmasi</h3>
            <p class="text-sm text-gray-500 mb-4">{{ $confirmText }}</p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</button>
                <form action="{{ $action }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>