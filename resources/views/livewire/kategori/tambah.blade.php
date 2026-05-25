<div>
    @if($show)
        <div class="fixed overflow-y-auto inset-0 z-50 flex items-center justify-center" x-data="{ open: false }" x-init="$nextTick(() => open = true)">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="$toggle('show')"></div>

            <!-- Modal -->
            <div class="relative w-full max-w-xl mx-4 p-4 bg-white rounded-2xl shadow-xl sm:p-6 md:p-8 dark:bg-gray-800"
                x-show="open"
                x-transition:enter="transition ease-out duration-200 delay-50"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                       Tambah Kategori Pengeluaran
                    </h3>
                    <button type="button" wire:click="$toggle('show')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('simpan.kategori') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="nama"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                            <input id="nama" name="nama"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Nama kategori pengeluaran">
                        </div>
                        <div class="sm:col-span-2" x-data="{ openEmoji: false, openColor: false, selectedEmoji: '', selectedColor: '' }">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ikon Kategori</label>
                            <input type="hidden" name="emoji" :value="selectedEmoji">
                            <input type="hidden" name="warna" :value="selectedColor">

                            <!-- Preview & Pickers -->
                            <div class="flex items-center gap-3">
                                <!-- Emoji Preview with BG Color -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                                    :style="selectedColor ? 'background-color: ' + selectedColor + '20' : 'background-color: #f3f4f6'">
                                    <span x-text="selectedEmoji || '?'"></span>
                                </div>

                                <!-- Emoji Picker Button -->
                                <div class="relative flex-1">
                                    <button type="button" @click="openEmoji = !openEmoji; openColor = false"
                                        class="flex items-center gap-2 p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                        <span x-show="selectedEmoji" x-text="selectedEmoji" class="text-lg"></span>
                                        <span x-show="!selectedEmoji" class="text-gray-400">Pilih emoji</span>
                                    </button>
                                    <div x-show="openEmoji" @click.away="openEmoji = false" x-transition
                                        class="absolute z-50 mt-1 p-3 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600 max-h-48 overflow-y-auto w-full">
                                        <div class="grid grid-cols-8 gap-1">
                                            @php
                                                $emojis = ['🍔','🍕','🍜','🍲','☕','🍺','🛒','🏠','💡','💧','📱','🚗','⛽','🚌','✈️','🏥','💊','🎓','📚','👕','👟','💇','🎬','🎮','🎵','💪','🏋️','⚽','🎁','💍','🐶','🐱','💰','💳','📊','🏦','🔧','🛠️','🧹','🪴','🛋️','📦','🎂','🍰','🥗','🥤','🍳','🧃'];
                                            @endphp
                                            @foreach($emojis as $emoji)
                                                <button type="button"
                                                    @click="selectedEmoji = '{{ $emoji }}'; openEmoji = false"
                                                    class="text-xl p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer text-center">
                                                    {{ $emoji }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500 text-center">Butuh lebih banyak emoji? Gunakan mobile app Kepitink</p>
                                    </div>
                                </div>

                                <!-- Color Picker Button -->
                                <div class="relative flex-1">
                                    <button type="button" @click="openColor = !openColor; openEmoji = false"
                                        class="flex items-center gap-2 p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                        <span x-show="selectedColor" class="w-5 h-5 rounded-full border border-gray-300" :style="'background-color: ' + selectedColor + '40'"></span>
                                        <span x-show="selectedColor" class="text-xs" x-text="selectedColor"></span>
                                        <span x-show="!selectedColor" class="text-gray-400">Pilih warna</span>
                                    </button>
                                    <div x-show="openColor" @click.away="openColor = false" x-transition
                                        class="absolute z-50 mt-1 p-3 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600 w-full">
                                        <div class="grid grid-cols-6 gap-2">
                                            @php
                                                $colors = [
                                                    '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16', '#22c55e',
                                                    '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1',
                                                    '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#78716c',
                                                ];
                                            @endphp
                                            @foreach($colors as $color)
                                                <button type="button"
                                                    @click="selectedColor = '{{ $color }}'; openColor = false"
                                                    class="w-8 h-8 rounded-full border-2 cursor-pointer transition-transform hover:scale-110"
                                                    :class="selectedColor === '{{ $color }}' ? 'border-gray-900 dark:border-white scale-110' : 'border-transparent'"
                                                    style="background-color: {{ $color }}40">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                            <textarea id="description" rows="4" name="deskripsi"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Deskripsi dari kategori ini"></textarea>
                        </div>
                    </div>
                    <flux:button type="submit" variant="primary" icon="plus" class="w-full">
                        Simpan
                    </flux:button>
                </form>
            </div>
        </div>
    @endif
</div>
