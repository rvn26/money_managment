<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
                class="p-2 flex flex-col gap-2 flex-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                {{-- <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-4 rounded-xl gradient-sunset text-white shadow-sm">
                        <h1 class="font-bold text-lg">Total Saldo</h1>
                        <p class="font-bold text-2xl mt-3">Rp. {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-4">
                            <span class="font-semibold text-[10px] bg-white text-primary rounded-full px-3 py-1">
                                7 hari terakhir
                            </span>
                            <p class="text-xs flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="size-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                                Rp. {{ number_format($selisih, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>
                    <div
                        class="hidden sm:block border border-dashed border-neutral-300 rounded-xl bg-neutral-50 dark:bg-zinc-800/50">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="border p-3 border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-zinc-800">
                        <div class="flex justify-between items-center mb-2">
                            <h1 class=" font-medium">Pemasukan</h1>
                            <div class="p-1.5 bg-secondary rounded-lg">
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <p class="font-bold text-lg mt-2 text-neutral-800">Rp. 8.500.000</p>
                        <p class="text-[10px] text-green-600 font-medium mt-1">+12% dari bln lalu</p>
                    </div>
                    <div class="border p-3  border-neutral-200 rounded-md">
                        <div class="flex justify-between items-start">
                            <h1 class=" font-medium">Pengeluaran</h1>
                            <div class="p-1.5 bg-secondary rounded-lg">
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <p class="font-bold text-lg mt-2 text-neutral-800">Rp. 8.500.000</p>
                        <p class="text-[10px] text-green-600 font-medium mt-1">+12% dari bln lalu</p>
                    </div>
                    <div class="border p-3  border-neutral-200 rounded-md">
                        <div class="flex justify-between items-start">
                            <h1 class=" font-medium">Tagihan</h1>
                            <div class="p-1.5 bg-secondary rounded-lg">
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <p class="font-bold text-lg mt-2 text-neutral-800">Rp. 8.500.000</p>
                        <p class="text-[10px] text-green-600 font-medium mt-1">+12% dari bln lalu</p>
                    </div>
                </div>

            </div>
            <div
                class="relative aspect-auto p-2 overflow-hidden  shadow-md  rounded-xl border border-neutral-200 dark:border-neutral-700">
                {{-- <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
                @livewire('component.tabel-tagihan-dashboard')
            </div>
        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div
                class="p-2 flex flex-col gap-2 flex-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                {{-- <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
                @livewire('component.balance-cart')
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div
                            class="border border-gray-200 rounded-lg shadow-xs overflow-hidden dark:border-neutral-700 dark:shadow-gray-900">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                            Name</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                            Age</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">
                                            Address</th>
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
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            45</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            New York No. 1 Lake Park</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <button type="button"
                                                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Delete</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            Jim Green</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            27</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            London No. 1 Lake Park</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <button type="button"
                                                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Delete</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            Joe Black</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            31</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            Sidney No. 1 Lake Park</td>
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
    </div>
</div>
