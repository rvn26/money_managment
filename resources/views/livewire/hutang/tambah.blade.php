<div>
    <x-modal wire:model="show" title="Hutang Baru" description="Catat hutang ke teman yang sudah terhubung di aplikasi, atau ke nama bebas.">
        <form action="{{ route('hutang.store') }}" method="POST" class="flex flex-col gap-4" x-data="{ pakaiTeman: false }">
            @csrf

            <div class="flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-700 p-2">
                <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" x-model="pakaiTeman" class="rounded border-gray-300">
                    Hutang ke teman di aplikasi
                </label>
            </div>

            <div x-show="pakaiTeman" x-cloak>
                <label for="id_teman" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Teman</label>
                <select id="id_teman" name="id_teman" :disabled="!pakaiTeman"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">— Pilih teman —</option>
                    @foreach ($temanList as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->email }})</option>
                    @endforeach
                </select>
                @if ($temanList->isEmpty())
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Belum ada teman terhubung. Tambahkan teman lebih dulu di menu Teman.
                    </p>
                @endif
                @error('id_teman')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
                
            </div>

            <div x-show="!pakaiTeman">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                <input type="text" id="nama" name="nama" :disabled="pakaiTeman"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('nama')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                <input type="text" id="jumlah" name="jumlah" data-autonumeric required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('jumlah')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_pinjaman" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pinjaman</label>
                <input type="date" id="tanggal_pinjaman" name="tanggal_pinjaman" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('tanggal_pinjaman')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">— Pilih metode —</option>
                    <option value="Cash">Cash</option>
                    <option value="Qris">Qris</option>
                    <option value="Bank">Bank</option>
                    <option value="Dana">Dana</option>
                    <option value="Gopay">Gopay</option>
                </select>
                @error('metode_pembayaran')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                <textarea id="catatan" name="catatan" rows="2"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                @error('catatan')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$toggle('show')">
                    Batal
                </flux:button>
                <button type="submit"
                    class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg text-sm px-5 py-2.5">
                    Tambah Hutang
                </button>
            </div>
        </form>
    </x-modal>
</div>
