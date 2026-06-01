@props(['periode' => 'bulan_ini', 'bulanCustom' => null, 'label' => null])

<div class="flex flex-wrap items-center gap-2">
    <div class="relative">
        <select wire:model.live="periode"
            class="appearance-none py-1.5 sm:py-2 ps-3 pe-9 text-xs sm:text-sm border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
            <option value="semua">Semua</option>
            <option value="bulan_ini">Bulan Ini</option>
            <option value="minggu_ini">Minggu Ini</option>
            <option value="custom">Pilih Bulan</option>
        </select>
        <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-2.5">
            <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </div>

    @if ($periode === 'custom')
        <input type="month" wire:model.live="bulanCustom"
            class="px-3 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300">
    @endif

    @if ($label)
        <span class="text-xs text-gray-500 dark:text-neutral-400">
            {{ $label }}
        </span>
    @endif
</div>
