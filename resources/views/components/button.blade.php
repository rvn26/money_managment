@props([
    'icon' => null,
    'iconClass' => 'size-4',
])

@php
    $baseClasses = 'bg-primary rounded-2xl h-9 px-3 text-white font-semibold md:text-sm text-xs shadow-md whitespace-nowrap inline-flex items-center gap-2 leading-none cursor-pointer transition duration-150 ease-in-out hover:brightness-95 hover:shadow-lg active:translate-y-px focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/30';
@endphp

<button {{ $attributes->class($baseClasses) }}>
    @if ($icon)
        <flux:icon :name="$icon" :class="$iconClass" />
    @endif
    <span>{{ $slot }}</span>
</button>
