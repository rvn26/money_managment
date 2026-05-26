<div>
    @if ($show)
        <div class="fixed overflow-y-auto inset-0 z-50 flex items-center justify-center" x-data="{ open: false }" x-init="$nextTick(() => open = true)">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="$toggle('show')"></div>

            <!-- Modal -->
            <div class="relative w-full max-w-3xl mx-4 p-4 bg-white rounded-2xl shadow-xl sm:p-6 md:p-8 dark:bg-gray-800"
                x-show="open"
                x-transition:enter="transition ease-out duration-200 delay-50"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Tambah Pemasukan
                    </h3>
                    <button type="button" wire:click="$toggle('show')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('simpan.pemasukan') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        {{-- <div class="sm:col-span-2"> --}}
                        <div>
                            <label for="tanggal"
                                class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Tanggal Pemasukan" required="">
                            @error('tanggal')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>


                        <div>
                            <label for="total"
                                class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                            <input type="text" name="total" id="total" data-autonumeric
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Total Pemasukan" required="">
                            @error('total')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="grid gap-4 mb-4 sm:grid-cols-3">
                            <div>
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                <select id="name" name="jenis"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Pilih Kategori</option>
                                    <option value="gaji">Gaji</option>
                                    <option value="bonus">Bonus</option>
                                    <option value="penjualan">Penjualan</option>
                                    <option value="investasi">Investasi</option>
                                    <option value="lain-lain">Lain-lain</option>
                                    @error('jenis')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </select>
                            </div>
                            <div>
                                <label for="category"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metode
                                    Pembayaran</label>
                                <select id="category" name="metode_pembayaran"
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

                            <div>
                                <label for="status"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Select status</option>
                                    <option value="pending">Pending</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                                @error('status')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-2 mb-4">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea id="description" rows="3" name="deskripsi"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Deskripsi Pemasukan"></textarea>
                        @error('deskripsi')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- </div> --}}
                    <div class="flex justify-end">
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
                    </div>
                </form>
            </div>

        </div>
    @endif
</div>
