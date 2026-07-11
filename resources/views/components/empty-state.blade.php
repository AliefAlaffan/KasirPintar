@props(['title' => 'Belum ada data', 'description' => '', 'icon' => null])

<div class="text-center py-12">
    <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
    </div>
    <p class="text-gray-600 font-medium">{{ $title }}</p>
    @if ($description)
        <p class="text-gray-400 text-sm mt-1">{{ $description }}</p>
    @endif
</div>