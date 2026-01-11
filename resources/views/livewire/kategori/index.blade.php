<div>
    @livewire('Kategori.tambah')
    <div>
        <h1 class="text-2xl font-bold ">Kategori Pengeluaran</h1>
    </div>
    <div class="py-3 pt-5 flex justify-between">
        <div class="w-xs relative">
            <label for="hs-table-search" class="sr-only">Search</label>
            <input type="text" name="hs-table-search" id="hs-table-search"
                class="py-1.5 sm:py-2 px-3 ps-9 block w-full border border-gray-200 shadow-xs rounded-lg sm:text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                placeholder="Search for items">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
        </div>
        <div>
            <button wire:click="tambahTampil" class="bg-primary rounded-2xl py-2 px-3 text-white font-semibold text-sm shadow-md">
                Tambah Kategori
            </button>
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
                                    Nama</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Deskripsi</th>
                                <th scope="col"
                                    class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                    John Brown</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">45
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">New
                                    York No. 1 Lake Park</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <button type="button"
                                        class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>