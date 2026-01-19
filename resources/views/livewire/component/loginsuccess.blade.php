<div>
    @if ($show)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md transform transition-all">
                <div class="relative p-6 text-center bg-white rounded-2xl shadow-xl dark:bg-zinc-900 sm:p-8">

                    <button type="button" wire:click="$toggle('show')"
                        class="text-gray-400 absolute right-4 top-4 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-full text-sm p-1.5 dark:hover:bg-zinc-800 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div
                        class="w-20 h-20 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">
                        Login Berhasil!
                    </h3>
                    <p class="mb-6 text-gray-500 dark:text-zinc-400 leading-relaxed">
                        Selamat datang kembali, <span
                            class="font-bold text-gray-800 dark:text-white">{{ $userName }}</span>!
                        Anda telah berhasil masuk. Selamat mengelola keuangan hari ini.
                    </p>

                    <div class="flex justify-center">
                        <button wire:click="$toggle('show')" type="button"
                            class="w-full py-2.5 px-5 text-sm font-semibold text-white bg-primary rounded-xl transition-all shadow-md shadow-primary/50 dark:shadow-none">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
