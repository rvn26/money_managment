<div>
    @if (session('message'))
        @livewire('component.notif-success')
    @elseif($errors->any() || session('error'))
        @livewire('component.notif-error')
    @endif

    <div>
        <h1 class="text-2xl font-bold">Hutang Saya</h1>
        <p class="text-sm text-gray-500 dark:text-neutral-400">
            Daftar hutang yang dicatat teman dengan kamu sebagai yang berhutang.
        </p>
    </div>

    <div class="mt-4 rounded-xl border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4 inline-block">
        <p class="text-xs text-gray-500 dark:text-neutral-400">Total hutang aktif</p>
        <p class="text-xl font-semibold text-red-600 dark:text-red-400">
            Rp {{ number_format($totalAktif, 0, ',', '.') }}
        </p>
    </div>

    <div class="py-3 pt-5">
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative min-w-0 max-w-45 sm:max-w-xs">
                <label for="hs-table-search" class="sr-only">Cari</label>
                <input wire:model.live="cari" type="text" id="hs-table-search"
                    class="py-1.5 sm:py-2 px-3 ps-9 block w-full border border-gray-300 shadow-sm rounded-lg sm:text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500"
                    placeholder="Cari nama teman...">
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
    </div>

    <div class="flex flex-col pt-3">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="border border-gray-200 rounded-lg shadow-xs overflow-hidden dark:border-neutral-700 dark:shadow-gray-900">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-700">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Berhutang Kepada</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Jumlah</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Pembayaran</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Status</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Catatan</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-neutral-700">
                            @forelse ($hutang as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        {{ $hutang->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ $item->user->name }}</span>
                                            <span class="text-xs text-gray-500 dark:text-neutral-400">{{ $item->user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">
                                            {{ $item->metode_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        @php
                                            $statusColor = match ($item->status) {
                                                'lunas' => 'bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500',
                                                'belum_lunas' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500',
                                                'terlambat' => 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ \Illuminate\Support\Str::limit($item->catatan, 30) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjaman)->translatedFormat('l, d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-neutral-400">
                                        Belum ada catatan hutang dari teman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="px-4 py-4 ">
            {{ $hutang->links() }}
        </div>
    </div>
</div>
