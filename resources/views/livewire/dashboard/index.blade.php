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
                    <div id="isiapa?" class=" border border-neutral-300 rounded-xl bg-neutral-50 dark:bg-zinc-800/50">
                        <div class="p-3 h-full flex flex-col gap-3">
                            <div
                                class="flex items-center justify-between bg-milk/30 p-2 rounded-xl border border-mustard/20 h-full">
                                <div class="flex items-center gap-2">
                                    <div class="p-1.5 bg-mustard rounded-lg shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-charcoal"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-neutral-400 uppercase leading-none">Aman
                                            Hari Ini</p>
                                        <p class="text-md font-black text-charcoal dark:text-white">Rp. 150.000</p>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-12 h-1 flex bg-neutral-200 rounded-full overflow-hidden">
                                        <div class="bg-third h-full" style="width: 65%"></div>
                                    </div>
                                    <p class="text-[9px] text-neutral-500 mt-1 font-medium italic">Sudah terpakai <br> Rp. 60.000</p>
                                </div>

                            </div>

                            {{-- Filter --}}
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    class="flex items-center justify-center gap-2 py-2 rounded-xl bg-primary text-white hover:bg-neutral-800 transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase">Laporan</span>
                                </button>

                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-neutral-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                    </div>

                                    <select
                                        class="block w-full pl-9 pr-3 py-2 text-[10px] font-bold uppercase bg-transparent border border-neutral-200 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-mustard/50 hover:bg-neutral-50 dark:text-white dark:hover:bg-zinc-700 transition cursor-pointer">
                                        <option value="">Semua Filter</option>
                                        <option value="pemasukan">Pemasukan</option>
                                        <option value="pengeluaran">Pengeluaran</option>
                                        <option value="tagihan">Tagihan</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div
                        class="border p-3 border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-zinc-800">
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
                class="p-2 flex flex-col gap-2 flex-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @livewire('component.kategori-cart')
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
