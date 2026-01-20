<div>
    @if (session('message'))
        @livewire('component.notif-success')
    @elseif($errors->any())
        @livewire('component.notif-error')
    @endif
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
                                @if ($filter == '7_hari' || $filter == '30_hari')
                                    {{ str_replace('_', ' ', $filter) }} terakhir
                                @elseif($filter == 'bulan_ini' || $filter == 'tahun_ini' || $filter == 'hari_ini')
                                    1 {{ str_replace('_ini', ' ', $filter) }} terakhir
                                @endif

                            </span>
                            <p class="text-xs flex items-center gap-1">
                                @if ($selisih > 0)
                                    <span class="p-1 bg-green-200 rounded-lg text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="size-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                        </svg>
                                    </span>
                                @else
                                    <span class="p-1 bg-red-200 rounded-lg text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                                        </svg>

                                    </span>
                                @endif

                                Rp. {{ number_format($selisih, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>
                    <div id="isiapa?"
                        class="border  border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-zinc-800">
                        <div class="p-3 h-full flex flex-col gap-3">
                            @if ($batasHarian)
                                @php
                                    $warnaBar = $persentase >= 90 ? 'bg-red-500' : 'bg-third';
                                @endphp
                                <div
                                    class="flex items-center justify-between bg-gray-50 p-2 shadow-md dark:bg-zinc-900 rounded-xl border border-mustard/20 h-full">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-mustard rounded-lg shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-charcoal"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <a wire:click.prevent="tampilsetbatas" class="cursor-pointer ">
                                            <div>
                                                <p
                                                    class="text-[10px] font-bold text-neutral-400 dark:text-accent-content uppercase leading-none">
                                                    Aman
                                                    Hari Ini</p>
                                                <p class="text-md font-black text-charcoal dark:text-white">Rp.
                                                    {{ number_format($batasHarian->batas, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                    <div>

                                        <div class="w-12 h-1 flex bg-neutral-200 rounded-full overflow-hidden">
                                            <div class="{{ $warnaBar }} h-full" style="width: {{ $persentase }}%">
                                            </div>
                                        </div>
                                        <p class="text-[9px] text-neutral-500 mt-1 font-medium italic">Sudah terpakai
                                            <br>
                                            Rp. {{ number_format($totalTerpakai, 0, ',', '.') }}
                                        </p>
                                    </div>

                                </div>
                            @else
                                <a wire:click.prevent="tampilsetbatas" class="cursor-pointer group">
                                    <div
                                        class="flex items-center gap-3 bg-neutral-100 dark:bg-neutral-800 border-2 border-dashed border-neutral-300 dark:border-neutral-700 p-2 rounded-xl h-full hover:border-mustard transition-colors">
                                        <div
                                            class="p-1.5 bg-neutral-200 dark:bg-neutral-700 rounded-lg group-hover:bg-mustard transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-4 text-neutral-500 group-hover:text-charcoal" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-neutral-400 uppercase leading-none">
                                                Batas Belum Set</p>
                                            <p class="text-xs font-medium text-neutral-500">Klik untuk mengatur</p>
                                        </div>
                                    </div>
                                </a>
                            @endif


                            {{-- Filter --}}
                            <div class="grid grid-cols-2 gap-2">
                                <button wire:click.prevent="laporan"
                                    class="flex items-center justify-center gap-2 py-2 rounded-xl bg-primary text-white hover:bg-neutral-800 transition shadow-sm dark:bg-accent-content dark:text-accent-foreground dark:hover:bg-amber-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-[12px] font-bold uppercase">Laporan</span>
                                </button>

                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-neutral-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                    </div>

                                    <select wire:model.live="filter"
                                        class="block w-full pl-9 pr-3 py-2 text-[10px] font-bold uppercase bg-transparent border border-neutral-200 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-mustard/50 hover:bg-neutral-50 dark:text-white dark:hover:bg-zinc-700 transition cursor-pointer">
                                        <option value="bulan_ini">Filter</option>
                                        <option value="hari_ini">hari ini</option>
                                        <option value="7_hari">7 hari</option>
                                        <option value="30_hari">30 hari</option>
                                        <option value="bulan_ini">Bulan ini</option>
                                        <option value="tahun_ini">Tahun ini</option>

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
                            <div class="p-1.5 bg-green-200 rounded-lg">
                                <a wire:click.prevent="TambahPemasukan" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-green-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <p class="font-bold text-lg mt-2 text-neutral-800 dark:text-white">Rp.
                            {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        @if ($persentasePemasukan >= 0)
                            <p class="text-[10px] text-green-600 font-medium mt-1">
                                +{{ number_format($persentasePemasukan, 1) }}% dari
                                @if ($filter == '7_hari' || $filter == '30_hari')
                                    {{ str_replace('_', ' ', $filter) }}
                                @elseif($filter == 'bulan_ini' || $filter == 'tahun_ini' || $filter == 'hari_ini')
                                    {{ str_replace('_ini', ' ', $filter) }}
                                @endif
                                lalu
                            </p>
                        @else
                            <p class="text-[10px] text-red-600 font-medium mt-1">
                                {{ number_format($persentasePemasukan, 1) }}% dari
                                @if ($filter == '7_hari' || $filter == '30_hari')
                                    {{ str_replace('_', ' ', $filter) }}
                                @elseif($filter == 'bulan_ini' || $filter == 'tahun_ini' || $filter == 'hari_ini')
                                    {{ str_replace('_ini', ' ', $filter) }}
                                @endif
                                lalu
                            </p>
                        @endif
                    </div>
                    <div
                        class="border p-3 border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-zinc-800">
                        <div class="flex justify-between items-start">
                            <h1 class=" font-medium">Pengeluaran</h1>
                            <div class="p-1.5 bg-red-200 rounded-lg">
                                <a wire:click.prevent="TambahPengeluaran" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-red-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <p class="font-bold text-lg mt-2 text-neutral-800 dark:text-white">Rp.
                            {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        @if ($persentasePengeluaran > 0)
                            {{-- Pengeluaran Naik --}}
                            <p class="text-[10px] text-red-600 font-medium mt-1">
                                +{{ number_format($persentasePengeluaran, 1) }}% dari
                                @if ($filter == '7_hari' || $filter == '30_hari')
                                    {{ str_replace('_', ' ', $filter) }}
                                @elseif($filter == 'bulan_ini' || $filter == 'tahun_ini' || $filter == 'hari_ini')
                                    {{ str_replace('_ini', ' ', $filter) }}
                                @endif
                                lalu
                            </p>
                        @elseif ($persentasePengeluaran < 0)
                            {{-- Pengeluaran Turun --}}
                            <p class="text-[10px] text-green-600 font-medium mt-1">
                                {{ number_format($persentasePengeluaran, 1) }}% dari
                                @if ($filter == '7_hari' || $filter == '30_hari')
                                    {{ str_replace('_', ' ', $filter) }}
                                @elseif($filter == 'bulan_ini' || $filter == 'tahun_ini' || $filter == 'hari_ini')
                                    {{ str_replace('_ini', ' ', $filter) }}
                                @endif
                                lalu
                            </p>
                        @else
                            {{-- Tetap / Tidak ada data --}}
                            <p class="text-[10px] text-gray-500 font-medium mt-1">
                                0% dari periode lalu
                            </p>
                        @endif
                    </div>
                    <div
                        class="border p-3 border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-zinc-800">
                        <div class="flex justify-between items-start">
                            <h1 class=" font-medium">Tagihan</h1>
                            <div class="p-1.5 bg-secondary/50 rounded-lg">
                                <a wire:click.prevent="TambahTagihan" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-third" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <p class="font-bold text-lg mt-2 text-neutral-800 dark:text-white">Rp.
                            {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-yellow-600 font-medium mt-1">{{ $tagihanBelumBayar }} Tagihan belum
                            dibayar</p>
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
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="p-2 flex flex-col gap-2 col-span-2 flex-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                {{-- <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
                @livewire('component.balance-cart')
            </div>
            <div
                class="p-2 flex flex-col gap-2 flex-1 col-span-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @livewire('component.kategori-cart')
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @livewire('component.tabel-transaksi-terbaru')
        </div>
    </div>
</div>
