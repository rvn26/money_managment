<div>
    @livewire('Kategori.tambah')
    @if (session('message'))
        @livewire('component.notif-success')
    @elseif($errors->any())
        @livewire('component.notif-error')
    @endif
    <div>
        <h1 class="text-2xl font-bold ">Kategori Pengeluaran</h1>
    </div>
    <div class="py-3 pt-5 flex justify-between">
        <div class="relative flex-1 min-w-0 max-w-45 sm:max-w-xs">
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
        <div>
            <div class="flex-shrink-0">
                <button wire:click="tambahTampil"
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
                                    Nama</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Deskripsi</th>
                                <th scope="col"
                                    class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-neutral-700">
                            @forelse ($kategori as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        <div class="flex items-center gap-3">
                                            @if($item->emoji)
                                                <span class="w-10 h-10 rounded-full flex items-center justify-center text-lg"
                                                    style="background-color: {{ $item->warna ? $item->warna . '20' : '#f3f4f6' }}">
                                                    {{ $item->emoji }}
                                                </span>
                                            @else
                                                <span class="w-10 h-10 rounded-full flex items-center justify-center text-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500">
                                                    ?
                                                </span>
                                            @endif
                                            <span class="font-medium">{{ $item->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ $item->deskripsi }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
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
                                        Belum ada catatan kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
