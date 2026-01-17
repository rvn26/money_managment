<div>
    @if ($show)
        <div class="fixed overflow-y-auto inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div
                class="w-full max-w-3xl p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-zinc-900 dark:border-zinc-700">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Add Product
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
                <form action="{{ route('edit.tagihan', ['id' => $id]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <select id="name" name="id_kategori"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option selected="" value="{{ $tagihan->kategori }}">
                                    {{ $tagihan->kategori_tagihan->nama }}</option>
                                @foreach ($kategori as $item)
                                    @if ($tagihan->kategori != $item->id)
                                        {{-- <option value="">hehehe</option> --}}
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endif
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
                                placeholder="masukan nama" required="" value="{{ $tagihan->nama }}">
                            @error('nama')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="nominal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nominal</label>
                            <input type="number" name="nominal" id="nominal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Nominal Tagihan" required="" value="{{ $tagihan->nominal }}">
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
                                <option selected="" value="{{ $tagihan->metode_pembayaran }}">
                                    {{ $tagihan->metode_pembayaran }}</option>
                                @foreach ($pembayaran as $p)
                                    @if ($tagihan->metode_pembayaran != $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endif
                                @endforeach
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
                                        <option selected="" value="{{ $tagihan->status }}">{{ str_replace('_', ' ',$tagihan->status) }}
                                        </option>
                                        @foreach ($status as $s)
                                            @if (strtolower(str_replace('_', ' ', $s)) != strtolower(str_replace('_', ' ', $tagihan->pengulangan)))
                                                <option value="{{ strtolower($s) }}">{{ str_replace('_', ' ', $s) }}
                                                </option>
                                            @endif
                                        @endforeach
                                        {{-- <option value="lunas">Lunas</option> --}}
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
                                        <option selected="" value="{{ $tagihan->pengulangan }}">
                                            {{ str_replace('_', ' ', $tagihan->pengulangan) }}</option>
                                        @foreach ($pengulangan as $p)
                                            @if (strtolower(str_replace('_', ' ', $p)) != strtolower(str_replace('_', ' ', $tagihan->pengulangan)))
                                                <option value="{{ strtolower($p) }}">{{ str_replace('_', ' ', $p) }}
                                                </option>
                                            @endif
                                        @endforeach
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
                                        placeholder="jatuh_tempo Tagihan" required=""
                                        value="{{ $tagihan->jatuh_tempo->timezone('Asia/Jakarta')->format('Y-m-d') }}">
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
                                value="{{ $tagihan->catatan }}" placeholder="Deskripsi pengeluaran">{{ $tagihan->catatan }}</textarea>
                            @error('catatan')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
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
