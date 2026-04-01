<div>
    @if ($show)
        <div class="fixed inset-0 bg-black/50 z-[60] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full shadow-xl">
                <h3 class="text-lg font-bold mb-4 dark:text-white">Pindai Struk</h3>

                <p class="text-gray-600 dark:text-gray-400 mb-4">Silakan upload foto struk belanja Anda.</p>
                <form action="{{ route('scan.receipt') }}" method="POST" enctype="multipart/form-data" class="space-y-4">

                    <input type="file" wire:model="file"
                        class="mb-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />

                    @if ($file)
                        @if (!$isScanned)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                                <div class="flex justify-center bg-gray-100 dark:bg-gray-900 rounded-lg p-2">
                                    <img src="{{ $file->temporaryUrl() }}" alt="Preview"
                                        class="max-w-full max-h-[400px] w-auto h-auto rounded-lg shadow-md object-contain">
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Hasil Scan:</p>
                                <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-4 space-y-2">
                                    <div><strong>Toko:</strong> {{ $scanResult['toko'] }}</div>
                                    <div><strong>Tanggal:</strong> {{ $scanResult['tanggal'] }}</div>
                                    <div><strong>Items:</strong></div>
                                    <ul class="list-disc list-inside ml-4">
                                        @foreach($scanResult['items'] as $item)
                                            <li>{{ $item['nama'] }} - Rp {{ number_format($item['harga']) }}</li>
                                        @endforeach
                                    </ul>
                                    <div><strong>Total:</strong> Rp {{ number_format($scanResult['total']) }}</div>
                                </div>
                            </div>
                        @endif
                    @endif


                    <div class="flex justify-end gap-2">
                        <button wire:click="$set('show', false)" type="button"
                            class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                        <button class="px-4 py-2 bg-primary text-white rounded-lg">Mulai Scan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
