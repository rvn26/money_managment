<div>
    @if ($show)
        <div class="fixed overflow-y-auto inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div
                class="w-[80%] md:w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-zinc-900 dark:border-zinc-700">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Set Batas Harian
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
                <form
                    action="{{ $batasHarian ? route('edit.batas', ['id' => $batasHarian->id]) : route('simpan.batas') }}"
                    method="POST">
                    @csrf
                    @if ($batasHarian)
                        @method('PUT')
                    @endif
                    <div class="mb-4">
                        <div>
                            <label for="nama"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Masukan Batas
                                Harian</label>
                            <input type="text" name="batas" id="nama" data-autonumeric
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="set batas harian anda" required="" value="{{ $batasHarian ? $batasHarian->batas : '' }}">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="text-white inline-flex items-center bg-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary-700 dark:focus:ring-primary">
                            <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    @endif
</div>
