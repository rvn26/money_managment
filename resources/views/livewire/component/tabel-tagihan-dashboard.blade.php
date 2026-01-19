<div>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div
                    class="border border-gray-200 rounded-lg divide-y divide-gray-200 dark:border-neutral-700 dark:divide-neutral-700">
                    <div class=" flex justify-between py-3 px-4">
                        <div class="relative max-w-xs">
                            <h1 class="font-bold text-xl">Tagihan Terdekat</h1>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('tagihan') }}"
                                class="bg-primary rounded-2xl py-2 px-2.5 text-white font-medium  text-xs shadow-md whitespace-nowrap">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        @if ($tagihanterdekat->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-700">
                                    <tr>

                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Nama</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Nominal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Tanggal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    {{-- {{ dd($tagihanterdekat) }} --}}
                                    @foreach ($tagihanterdekat as $item)
                                        <tr>

                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                {{ $item->nama }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                {{ $item->jatuh_tempo->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                @if ($item->jatuh_tempo->endOfDay()->timezone('Asia/Jakarta')->isPast() && $item->status == 'terlambat')
                                                    <span
                                                        class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">{{ $item->status }}
                                                    </span>
                                                @else
                                                    @if ($item->status == 'lunas')
                                                        <span
                                                            class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">{{ $item->status }}
                                                        </span>
                                                    @elseif($item->status == 'belum_dibayar')
                                                        <span
                                                            class="inline-flex items-center gap-x-1.5 py-0.5 px-3 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500">{{ $item->status }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    {{-- @empty --}}
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <div class="p-10 text-center">
                                <svg class="mx-auto size-12 text-gray-300 dark:text-neutral-600"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <p class="mt-4 text-sm text-neutral-500 dark:text-neutral-400 font-medium">
                                    Tidak
                                    ada tagihan yang perlu dibayar saat ini.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
