<div>
    @if ($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-data="{ open: false }" x-init="$nextTick(() => open = true)">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="$toggle('show')"></div>

            <!-- Modal -->
            <div class="relative w-full max-w-sm mx-4 bg-white rounded-2xl shadow-xl dark:bg-gray-800"
                x-show="open"
                x-transition:enter="transition ease-out duration-200 delay-50"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="p-6">
                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                            <flux:icon.exclamation-triangle class="size-7 text-red-500 dark:text-red-400" />
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Hapus Tagihan?</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Data tagihan ini akan dihapus permanen dan tidak bisa dikembalikan.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <flux:button wire:click="$toggle('show')" variant="ghost" class="flex-1">
                            Batal
                        </flux:button>
                        <form action="{{ route('hapus.tagihan', ['id' => $id]) }}" method="POST" class="flex-1">
                            @method('DELETE')
                            @csrf
                            <flux:button type="submit" variant="danger" class="w-full" icon="trash">
                                Hapus
                            </flux:button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
