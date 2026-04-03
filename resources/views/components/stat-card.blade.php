@props(['title', 'value', 'icon' => '', 'color' => 'blue'])

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
        </div>
        @if ($icon)
            <div class="w-12 h-12 rounded-lg bg-{{ $color }}-100 flex items-center justify-center">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
