@props(['title' => 'Belum ada data', 'description' => '', 'action' => '', 'actionText' => ''])

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
    </svg>
    <h3 class="mt-4 text-sm font-medium text-gray-900">{{ $title }}</h3>
    @if ($description)
        <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
    @endif
    @if ($action)
        <div class="mt-6">
            <a href="{{ $action }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
