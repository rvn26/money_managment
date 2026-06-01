<div>
    @if (session('message'))
        @livewire('component.notif-success')
    @elseif($errors->any())
        @livewire('component.notif-error')
    @endif
     <h1 class="text-2xl font-bold">Pemasukan</h1>
        <p class="text-sm text-gray-500 dark:text-neutral-400">
            Daftar pemasukan km selama periode tertentu yang tercatat.
        </p>
    <div class="py-3 pt-5 flex flex-wrap justify-between gap-2">
        <div class="flex flex-wrap items-center gap-2 flex-1 min-w-0">
            <div class="relative min-w-0 max-w-45 sm:max-w-xs">
                <label for="hs-table-search" class="sr-only">Search</label>
                <input wire:model.live="cari" type="text" name="hs-table-search" id="hs-table-search"
                    class="py-1.5 sm:py-2 px-3 ps-9 block w-full border border-gray-300 shadow-sm rounded-lg sm:text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                    placeholder="Cari...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
            </div>
            <x-filter-periode :periode="$periode" :bulan-custom="$bulanCustom" :label="$this->labelPeriode" />
        </div>
        <div>
            <div class="flex-shrink-0">
                <button wire:click="tampilTambah"
                    class="bg-primary rounded-2xl py-2 px-3 text-white font-semibold text-sm shadow-md">
                    + Tambah
                </button>
            </div>
        </div>
    </div>
    <div class="flex flex-col pt-3">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div
                    class="border border-gray-200 rounded-lg shadow-xs overflow-hidden dark:border-neutral-700 dark:shadow-gray-900">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    jenis</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    total</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Pembayaran</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Deskripsi</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Tanggal</th>
                                <th scope="col"
                                    class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-neutral-700">

                            @forelse ($transaksi as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200 cursor-pointer"
                                        wire:click="edit('{{ $item->id }}')">
                                        {{ $transaksi->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 cursor-pointer" wire:click="edit('{{ $item->id }}')">
                                        @php
                                            $emojiMap = [
                                                'gaji' => '💰',
                                                'bonus' => '🎁',
                                                'penjualan' => '🛒',
                                                'investasi' => '📈',
                                                'lain-lain' => '📦',
                                            ];
                                            $colorMap = [
                                                'gaji' => '#22c55e',
                                                'bonus' => '#f59e0b',
                                                'penjualan' => '#3b82f6',
                                                'investasi' => '#8b5cf6',
                                                'lain-lain' => '#78716c',
                                            ];
                                            $emoji = $emojiMap[strtolower($item->jenis)] ?? '📦';
                                            $color = $colorMap[strtolower($item->jenis)] ?? '#78716c';
                                        @endphp
                                        <div class="inline-flex items-center gap-2">
                                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                                                style="background-color: {{ $color }}20">
                                                {{ $emoji }}
                                            </span>
                                            <span class="text-sm font-medium">{{ ucwords($item->jenis) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 cursor-pointer" wire:click="edit('{{ $item->id }}')">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 cursor-pointer" wire:click="edit('{{ $item->id }}')">
                                        <span
                                            class="inline-flex items-center gap-x-1.5 py-0.5 px-3  rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">{{ $item->metode_pembayaran }}</span>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 cursor-pointer" wire:click="edit('{{ $item->id }}')">
                                        @if ($item->status == 'lunas')
                                            <span
                                                class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">{{ $item->status }}</span>
                                        @elseif($item->status == 'pending')
                                            <span
                                                class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 cursor-pointer" wire:click="edit('{{ $item->id }}')">
                                        {{ $item->deskripsi }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 cursor-pointer" wire:click="edit('{{ $item->id }}')">
                                        {{ $item->tanggal->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a wire:click="edit('{{ $item->id }}')"
                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                        <a wire:click="hapus('{{ $item->id }}')"
                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:text-red-400 dark:focus:text-red-400"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                             @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-neutral-400">
                                        Belum ada catatan pemasukan.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="px-4 py-4 border-t border-gray-200 dark:bg-neutral-900 dark:border-neutral-700">
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
