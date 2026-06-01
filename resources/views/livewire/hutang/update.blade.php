<div>
    @if ($show && $hutang)
        <div class="fixed overflow-y-auto inset-0 z-50 flex items-center justify-center" x-data="{ open: false }" x-init="$nextTick(() => open = true)">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="$toggle('show')"></div>

            <div class="relative w-full max-w-2xl mx-4 p-4 bg-white rounded-2xl shadow-xl sm:p-6 md:p-8 dark:bg-gray-800"
                x-show="open"
                x-transition:enter="transition ease-out duration-200 delay-50"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Hutang
                    </h3>
                    <button type="button" wire:click="$toggle('show')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                <form action="{{ route('hutang.update', ['id' => $id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hutang Kepada</label>
                            <div class="bg-gray-50 dark:bg-neutral-700 rounded-lg p-2.5 text-sm text-gray-700 dark:text-neutral-200">
                                @if ($hutang->teman)
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium">{{ $hutang->teman->name }}</span>
                                        <span class="text-[10px] uppercase bg-blue-100 text-blue-700 dark:bg-blue-800/30 dark:text-blue-400 rounded px-1.5 py-0.5">Teman</span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-neutral-400">{{ $hutang->teman->email }}</span>
                                @else
                                    {{ $hutang->nama }}
                                @endif
                            </div>
                        </div>

                        <div>
                            <label for="jumlah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                            <input type="text" id="jumlah" name="jumlah" data-autonumeric required
                                value="{{ $hutang->jumlah }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('jumlah')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_pinjaman" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pinjaman</label>
                            <input type="date" id="tanggal_pinjaman" name="tanggal_pinjaman" required
                                value="{{ \Carbon\Carbon::parse($hutang->tanggal_pinjaman)->format('Y-m-d') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('tanggal_pinjaman')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="metode_pembayaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metode Pembayaran</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach ($pembayaranOptions as $p)
                                    <option value="{{ $p }}" @selected($hutang->metode_pembayaran === $p)>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select id="status" name="status" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach ($statusOptions as $s)
                                    <option value="{{ $s }}" @selected($hutang->status === $s)>{{ str_replace('_', ' ', $s) }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="catatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan</label>
                            <textarea id="catatan" name="catatan" rows="2"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $hutang->catatan }}</textarea>
                            @error('catatan')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <flux:button type="button" variant="ghost" wire:click="$toggle('show')">
                            Batal
                        </flux:button>
                        <button type="submit"
                            class="text-white inline-flex items-center bg-primary hover:bg-primary font-medium rounded-lg text-sm px-5 py-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
