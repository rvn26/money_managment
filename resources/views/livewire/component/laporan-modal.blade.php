<div>
    @if ($show)
        <div class="fixed overflow-y-auto inset-0 z-50 flex items-center justify-center p-4"
            x-data="{ open: false }" x-init="$nextTick(() => open = true)">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                wire:click="tutup"></div>

            <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl dark:bg-zinc-900"
                x-show="open"
                x-transition:enter="transition ease-out duration-200 delay-50"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <form id="formLaporan" method="POST" action="{{ route('laporan.csv') }}"
                    class="flex flex-col">
                    @csrf

                    <div class="flex justify-between items-center px-6 py-4 border-b dark:border-zinc-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cetak Laporan Keuangan</h3>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">
                                Pilih bagian yang akan dimasukkan dan rentang tanggal laporan.
                            </p>
                        </div>
                        <button type="button" wire:click="tutup"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>

                    <div class="px-6 py-4 space-y-5">
                        {{-- Bagian yang dicetak --}}
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">Bagian Laporan</p>
                            <div class="grid sm:grid-cols-3 gap-2">
                                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition
                                    {{ in_array('pemasukan', $sections) ? 'border-primary bg-primary/5 dark:bg-primary/10' : 'border-gray-200 dark:border-zinc-700' }}">
                                    <input type="checkbox" name="sections[]" value="pemasukan"
                                        wire:model.live="sections"
                                        class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <flux:icon.document-arrow-down class="size-4 text-gray-500 dark:text-zinc-300" />
                                    <span class="text-sm text-gray-800 dark:text-zinc-200">Pemasukan</span>
                                </label>
                                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition
                                    {{ in_array('pengeluaran', $sections) ? 'border-primary bg-primary/5 dark:bg-primary/10' : 'border-gray-200 dark:border-zinc-700' }}">
                                    <input type="checkbox" name="sections[]" value="pengeluaran"
                                        wire:model.live="sections"
                                        class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <flux:icon.document-arrow-up class="size-4 text-gray-500 dark:text-zinc-300" />
                                    <span class="text-sm text-gray-800 dark:text-zinc-200">Pengeluaran</span>
                                </label>
                                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition
                                    {{ in_array('hutang', $sections) ? 'border-primary bg-primary/5 dark:bg-primary/10' : 'border-gray-200 dark:border-zinc-700' }}">
                                    <input type="checkbox" name="sections[]" value="hutang"
                                        wire:model.live="sections"
                                        class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <flux:icon.banknotes class="size-4 text-gray-500 dark:text-zinc-300" />
                                    <span class="text-sm text-gray-800 dark:text-zinc-200">Hutang</span>
                                </label>
                            </div>
                            @error('sections')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rentang periode --}}
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">Periode Laporan</p>
                            <div class="grid sm:grid-cols-3 gap-2">
                                @foreach ([
                                    'bulan_ini' => 'Bulan Ini',
                                    'setahun' => '1 Tahun',
                                    'custom' => 'Custom',
                                ] as $value => $label)
                                    <label class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition text-sm
                                        {{ $periode === $value ? 'border-primary bg-primary/5 dark:bg-primary/10 text-primary' : 'border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300' }}">
                                        <input type="radio" name="periode" value="{{ $value }}"
                                            wire:model.live="periode" class="hidden">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>

                            @if ($periode === 'custom')
                                <div class="mt-3 grid sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs text-gray-600 dark:text-zinc-400 mb-1">Dari Tanggal</label>
                                        <input type="date" name="tanggal_dari" wire:model.live="tanggalDari"
                                            class="block w-full text-sm rounded-lg border border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white p-2">
                                        @error('tanggal_dari')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 dark:text-zinc-400 mb-1">Sampai Tanggal</label>
                                        <input type="date" name="tanggal_sampai" wire:model.live="tanggalSampai"
                                            class="block w-full text-sm rounded-lg border border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white p-2">
                                        @error('tanggal_sampai')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if ($periode !== 'custom')
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggalDari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggalSampai }}">
                            @endif
                        </div>

                        @error('periode')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="px-6 py-4 border-t dark:border-zinc-700 flex flex-wrap items-center justify-end gap-2">
                        <flux:button type="button" variant="ghost" wire:click="tutup">Batal</flux:button>
                        <button type="submit" name="format" value="csv"
                            formaction="{{ route('laporan.csv') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium">
                            <flux:icon.table-cells class="size-4" />
                            Spreadsheet
                        </button>
                        <button type="submit" name="format" value="pdf"
                            formaction="{{ route('laporan.pdf') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium">
                            <flux:icon.document class="size-4" />
                            PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
