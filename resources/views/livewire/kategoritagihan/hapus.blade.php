<div>
    @if ($show)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="p-4 w-full max-w-md">
                <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">

                    <button type="button" wire:click="$toggle('show')"
                        class="text-gray-400 absolute right-2 top-2 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="deleteModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z">
                            </path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>


                    <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z">
                        </path>
                    </svg>

                    <p class="mb-4 text-gray-500 dark:text-gray-300">
                        Apakah kamu yakin ingin menghapus ini?
                    </p>

                    <div class="flex justify-center items-center space-x-4">
                        <button wire:click="$toggle('show')" type="button"
                            class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            batal
                        </button>
                        <form action="{{ route('hapus.kategori.tagihan', ['id' => $id]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit"
                                class="py-2 px-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 dark:bg-red-500 dark:hover:bg-red-600">
                                iya, yakin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
