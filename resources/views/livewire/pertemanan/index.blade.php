<div>
    @if (session('message'))
        @livewire('component.notif-success')
    @elseif($errors->any() || session('error'))
        @livewire('component.notif-error')
    @endif

    <div>
        <h1 class="text-2xl font-bold">Teman</h1>
        <p class="text-sm text-gray-500 dark:text-neutral-400">
            Kelola daftar teman dan permintaan pertemanan kamu.
        </p>
    </div>

    <div class="py-3 pt-5 flex justify-between gap-2">
        <div class="relative flex-1 min-w-0 max-w-45 sm:max-w-xs">
            <label for="hs-table-search" class="sr-only">Cari</label>
            <input wire:model.live="cari" type="text" id="hs-table-search"
                class="py-1.5 sm:py-2 px-3 ps-9 block w-full border border-gray-300 shadow-sm rounded-lg sm:text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500"
                placeholder="Cari nama / email...">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
        </div>
        <div class="flex-shrink-0">
            <x-button wire:click="tampilTambah" icon="plus" class="mr-3">
                    Tambah
                </x-button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 dark:border-neutral-700 mt-2">
        <nav class="flex gap-2 -mb-px">
            <button type="button" wire:click="gantiTab('teman')"
                @class([
                    'py-3 px-4 inline-flex items-center gap-2 text-sm font-medium border-b-2',
                    'border-primary dark:border-gray-200 text-primary dark:text-white' => $tab === 'teman',
                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-neutral-400' => $tab !== 'teman',
                ])>
                Teman
                <span class="ms-1 text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full dark:bg-neutral-700 dark:text-neutral-300">
                    {{ $temanList->count() }}
                </span>
            </button>
            <button type="button" wire:click="gantiTab('masuk')"
                @class([
                    'py-3 px-4 inline-flex items-center gap-2 text-sm font-medium border-b-2',
                    'border-primary dark:border-gray-200 text-primary dark:text-white' => $tab === 'masuk',
                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-neutral-400' => $tab !== 'masuk',
                ])>
                Permintaan Masuk
                @if ($permintaanMasuk->count() > 0)
                    <span class="ms-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full dark:bg-red-800/30 dark:text-red-400">
                        {{ $permintaanMasuk->count() }}
                    </span>
                @endif
            </button>
            <button type="button" wire:click="gantiTab('terkirim')"
                @class([
                    'py-3 px-4 inline-flex items-center gap-2 text-sm font-medium border-b-2',
                    'border-primary dark:border-gray-200 text-primary dark:text-white' => $tab === 'terkirim',
                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-neutral-400' => $tab !== 'terkirim',
                ])>
                Terkirim
                @if ($permintaanTerkirim->count() > 0)
                    <span class="ms-1 text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full dark:bg-neutral-700 dark:text-neutral-300">
                        {{ $permintaanTerkirim->count() }}
                    </span>
                @endif
            </button>
        </nav>
    </div>

    {{-- TAB: TEMAN --}}
    @if ($tab === 'teman')
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($temanList as $item)
                @php
                    
                    $teman = $item->id_user === auth()->id() ? $item->teman : $item->user;
                    // dd($item->teman);
                @endphp
                <div class="border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center justify-between bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="size-10 shrink-0 rounded-full bg-primary/20 dark:bg-gray-100 text-primary flex items-center justify-center font-semibold uppercase">
                            {{ \Illuminate\Support\Str::of($teman->name)->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('') }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $teman->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $teman->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('pertemanan.hapus', ['id' => $item->id]) }}" method="POST"
                        onsubmit="return confirm('Hapus teman ini dari daftar?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:text-red-800 dark:text-red-500 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                            title="Hapus teman">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-10 text-sm text-gray-500 dark:text-neutral-400">
                    Belum ada teman. Klik <span class="font-semibold">Tambah Teman</span> untuk mengirim permintaan pertemanan.
                </div>
            @endforelse
        </div>
    @endif

    {{-- TAB: PERMINTAAN MASUK --}}
    @if ($tab === 'masuk')
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($permintaanMasuk as $item)
                <div class="border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center justify-between bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="size-10 shrink-0 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center font-semibold uppercase">
                            {{ \Illuminate\Support\Str::of($item->user->name)->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('') }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $item->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $item->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <form action="{{ route('pertemanan.terima', ['id' => $item->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="text-green-600 hover:text-green-800 dark:text-green-500 p-1.5 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20"
                                title="Terima permintaan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                        </form>
                        <form action="{{ route('pertemanan.hapus', ['id' => $item->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-red-800 dark:text-red-500 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                                title="Tolak permintaan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-10 text-sm text-gray-500 dark:text-neutral-400">
                    Tidak ada permintaan pertemanan masuk.
                </div>
            @endforelse
        </div>
    @endif

    {{-- TAB: TERKIRIM --}}
    @if ($tab === 'terkirim')
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($permintaanTerkirim as $item)
                <div class="border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center justify-between bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="size-10 shrink-0 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold uppercase">
                            {{ \Illuminate\Support\Str::of($item->teman->name)->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('') }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $item->teman->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $item->teman->email }}</p>
                            <span class="text-[10px] text-yellow-700 dark:text-yellow-400">Menunggu konfirmasi</span>
                        </div>
                    </div>
                    <form action="{{ route('pertemanan.hapus', ['id' => $item->id]) }}" method="POST"
                        onsubmit="return confirm('Batalkan permintaan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:text-red-800 dark:text-red-500 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                            title="Batalkan permintaan">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-10 text-sm text-gray-500 dark:text-neutral-400">
                    Belum ada permintaan terkirim.
                </div>
            @endforelse
        </div>
    @endif
</div>
