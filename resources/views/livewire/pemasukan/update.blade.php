<div>
    @if ($show)
        <div class="fixed overflow-y-auto inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div
                class="w-full max-w-3xl p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Pemasukan
                    </h3>
                    <button type="button" wire:click="$toggle('show')"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="defaultModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('edit.pemasukan', ['id' => $id]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        {{-- <div class="sm:col-span-2"> --}}
                        <div>
                            <label for="tanggal"
                                class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Tanggal Pemasukan" required=""
                                value="{{ $pemasukan->tanggal->timezone('Asia/Jakarta')->format('Y-m-d') }}">
                            @error('tanggal')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>


                        <div>
                            <label for="total"
                                class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                            <input type="number" name="total" id="total"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Total Pemasukan" required="" value="{{ $pemasukan->total }}">
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
                                    <option selected="" value="{{ $pemasukan->jenis }}">{{ $pemasukan->jenis }}
                                    </option>
                                    @foreach ($kategori as $k)
                                        @if (strtolower($pemasukan->jenis) != strtolower($k))
                                            <option value="{{ strtolower($k) }}">{{ $k }}</option>
                                        @endif
                                    @endforeach

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
                                    <option selected="" value="{{ $pemasukan->metode_pembayaran }}">
                                        {{ $pemasukan->metode_pembayaran }}</option>
                                    @foreach ($pembayaran as $p)
                                        @if (strtolower($pemasukan->metode_pembayaran) != strtolower($p))
                                            <option value="{{ $p }}">{{ $p }}</option>
                                        @endif
                                    @endforeach
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
                                    <option selected="" value="{{ $pemasukan->status }}">{{ $pemasukan->status }}
                                    </option>
                                    <option value="{{ $pemasukan->status == 'panding' ? 'panding' : 'lunas' }}">
                                        {{ $pemasukan->status == 'panding' ? 'Lunas' : 'Pending' }}</option>
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
                            placeholder="Deskripsi Pemasukan" value="{{ $pemasukan->deskripsi }}">{{ $pemasukan->deskripsi }}</textarea>
                        @error('deskripsi')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- </div> --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="text-white inline-flex items-center bg-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary-700 dark:focus:ring-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Simpan Edit
                        </button>
                    </div>
                </form>

            </div>

        </div>
    @endif
</div>
