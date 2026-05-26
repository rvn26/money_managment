<div>
    {{-- @if ($show) --}}
    <x-modal wire:model="show" title="Hutang Baru">
        <p class="mb-4 text-sm text-gray-500 dark:text-gray-300">
            Tambahkan hutang baru untuk membantu Anda melacak utang dan pembayaran dengan lebih mudah. Dengan fitur ini,
            Anda dapat mencatat detail hutang, tanggal jatuh tempo, dan status pembayaran untuk memastikan keuangan Anda
            tetap teratur.
        </p>
        <form action="{{ route('hutang.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                <input type="text" id="nama" wire:model.defer="nama" name="nama" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @error('nama')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                <input type="number" id="jumlah" name="jumlah" wire:model.defer="jumlah" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @error('jumlah')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="tanggal_pinjaman"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pinjaman</label>
                <input type="date" id="tanggal_pinjaman" name="tanggal_pinjaman" wire:model.defer="tanggal_pinjaman" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @error('tanggal_pinjaman')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="keterangan"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                <textarea id="keterangan" name="catatan" wire:model.defer="keterangan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                @error('keterangan')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>



            <button type="submit"
                class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-dark dark:hover:bg-primary">
                Tambah Hutang
            </button>
            {{-- <input type="file" name="receipt" accept="image/*" required
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @if ($errorMessage)
                    <p class="text-sm text-red-500">{{ $errorMessage }}</p>
                @endif --}}
            {{-- <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600"
                    :disabled="submitting || !canSubmit">
                    {{ submitting ? 'Memproses...' : 'Scan Sekarang' }}
                </button> --}}
        </form>
    </x-modal>
    {{-- @endif --}}
</div>
