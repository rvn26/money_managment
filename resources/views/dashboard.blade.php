<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div
                class="p-2 flex flex-col gap-2 flex-1 w-full h-full relative  shadow-md overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                {{-- <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}
                <div class="grid grid-cols-2 gap-2 h-full">
                    <div class="p-3 border border-neutral-200 rounded-md bg-secondary">
                        <h1 class="font-bold text-lg">Total Saldo</h1>
                        <p class="font-bold text-2xl mt-3">Rp. 12.000.000</p>
                        <p class="font-semibold text-sm mt-3 bg-white rounded-2xl px-3 w-fit text-center">7 hari trakhir</p>
                        <p class=" text-sm mt-3 ">kenaikan Rp.5.000.000</p>
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
</x-layouts.app>