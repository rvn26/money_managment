<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div
                class="p-2 flex flex-col gap-2 flex-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                {{-- <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
                <div class="grid grid-cols-2 gap-2 h-full">
                    <div class="p-3 border border-neutral-200 rounded-md bg-secondary">
                        <h1 class="font-bold text-lg">Total Saldo</h1>
                        <p class="font-bold text-2xl mt-3">Rp. {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                        <p class="font-semibold text-xs mt-3 bg-white rounded-2xl px-3 w-fit text-center">7 hari trakhir
                        </p>
                        <p class=" text-sm mt-3 flex gap-2">kenaikan Rp. {{ number_format($selisih, 0, ',', '.') }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                            </svg>
                        </p>

                    </div>
                    <div class="border border-neutral-200 rounded-md">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 h-full">
                    <div class="border border-neutral-200 rounded-md"></div>
                    <div class="border border-neutral-200 rounded-md"></div>
                    <div class="border border-neutral-200 rounded-md"></div>
                </div>

            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</div>
