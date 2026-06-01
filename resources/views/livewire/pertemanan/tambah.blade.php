<div>
    <x-modal wire:model="show" title="Tambah Teman" description="Masukkan email pengguna lain untuk mengirim permintaan pertemanan.">
        <form action="{{ route('pertemanan.kirim') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Teman</label>
                <input type="email" id="email" name="email" required
                    placeholder="contoh@email.com"
                    class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$toggle('show')">
                    Batal
                </flux:button>
                <button type="submit"
                    class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg text-sm px-5 py-2.5">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </x-modal>
</div>
