@props(['title' => '', 'description' => ''])

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
             class="bg-white dark:bg-gray-800 p-6 rounded-xl max-w-md w-full shadow-2xl space-y-4"
             @click.outside="show = false">

               
                <div class="flex items-center justify-between">
                    <div>
                        @if($title)
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
                        @endif
                        @if($description)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $description }}</p>
                        @endif
                    </div>
                    <button @click="show = false" type="button"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                
                {{ $slot }}
        </div>
    </div>
</div>
