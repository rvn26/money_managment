<div>
    @if ($show)
        @if ($kategori && count($kategori) > 0)
            <div class="fixed overflow-y-auto inset-0 z-50 flex items-center justify-center" x-data="{ open: false }" x-init="$nextTick(() => open = true)">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    wire:click="$toggle('show')"></div>

                <!-- Modal -->
                <div class="relative w-full max-w-3xl mx-4 p-4 bg-white rounded-2xl shadow-xl sm:p-6 md:p-8 dark:bg-zinc-900"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200 delay-50"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Tambah Tagihan
                        </h3>
                        <button type="button" wire:click="$toggle('show')"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="{{ route('simpan.tagihan') }}" method="POST">
                        @csrf
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                <select id="name" name="id_kategori"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Pilih Kategori</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                    @error('id_kategori')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </select>
                            </div>
                            <div>
                                <label for="nama"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                <input type="text" name="nama" id="nama"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="masukan nama" required="">
                                @error('nama')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="nominal"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nominal</label>
                                <input type="text" name="nominal" id="nominal" data-autonumeric
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Nominal Tagihan" required="">
                                @error('nominal')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="metode_pembayaran"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metode
                                    Pembayaran</label>
                                <select id="metode_pembayaran" name="metode_pembayaran"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Pilih Pembayaran</option>
                                    <option value="Qris">Qris</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Dana">Dana</option>
                                    <option value="Gopay">Gopay</option>
                                    <option value="Cash">Cash</option>
                                </select>
                                @error('metode_pembayaran')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <div class="grid gap-4 mb-4 sm:grid-cols-3">
                                    <div>
                                        <label for="status"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                        <select id="status" name="status"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option selected="">Select status</option>
                                            <option value="belum_dibayar">Belum Dibayar</option>
                                            <option value="lunas">Lunas</option>
                                            {{-- <option value="terlambat">Paid</option> --}}
                                        </select>
                                        @error('status')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="pengulangan"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pengulangan</label>
                                        <select id="pengulangan" name="pengulangan"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option selected="">Select pengulangan</option>
                                            <option value="sekali_bayar">Sekali Bayar</option>
                                            <option value="bulanan">Bulanan</option>
                                            <option value="tahunan">Tahunan</option>
                                        </select>
                                        @error('pengulangan')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="jatuh_tempo"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jatuh
                                            Tempo</label>
                                        <input type="date" name="jatuh_tempo" id="jatuh_tempo"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="jatuh_tempo Tagihan" required="">
                                        @error('jatuh_tempo')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                                <textarea id="description" rows="4" name="catatan"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Deskripsi pengeluaran"></textarea>
                                @error('catatan')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit"
                            class="text-white inline-flex items-center bg-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary-700 dark:focus:ring-primary">
                            <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Simpan
                        </button>
                    </form>
                </div>

            </div>
        @else
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div
                    class="w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-center dark:bg-gray-800 dark:border-gray-700">
                    <div class="mb-4 flex justify-center text-amber-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Kategori Belum Tersedia</h3>
                    <p class="mb-6 text-gray-500 dark:text-gray-400">Wah, sepertinya brankasmu belum punya label
                        kategori. Buat kategori tagihan dulu agar uangmu tercatat rapi!</p>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('kategori.tagihan') }}"
                            class="text-white bg-primary hover:bg-primary-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                            Buat Kategori Sekarang
                        </a>
                        <button type="button" wire:click="$toggle('show')"
                            class="text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>
        @endif

    @endif
</div>
