@props([
    'title' => '',
    'description' => '',
])

<div x-data="{ show: @entangle($attributes->wire('model')->value) }">
    <div x-show="show" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-[60] flex items-center justify-center p-4">

        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
             class="w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-center dark:bg-gray-800 dark:border-gray-700"
             @click.outside="show = false">

            <div class="mb-4 flex justify-center text-amber-500">
                {{ $icon ?? '' }}
                @if (trim($icon ?? '') === '')
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                @endif
            </div>

            @if ($title)
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
            @endif
            @if ($description)
                <p class="mb-6 text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif

            <div class="flex flex-col gap-3">
                {{ $actions ?? $slot }}
            </div>
        </div>
    </div>
</div>
