<div>
    @if ($hasKategori)
        <x-modal wire:model="show" title="Pindai Struk" description="Upload foto struk belanja kamu">
            <form action="{{ route('scan.receipt') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
                x-data="scanForm()" @submit.prevent="submit">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                        Gambar Struk
                    </label>
                    <div wire:ignore>
                        <input name="receipt" type="file" wire:model="file" accept="image/*"
                            @change="hasFile = $event.target.files.length > 0" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                           file:mr-3 file:py-2 file:px-4
                                           file:rounded-2xl file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-primary file:text-white
                                           hover:file:opacity-90 file:cursor-pointer
                                           file:shadow-md
                                           border border-gray-200 dark:border-gray-700
                                           rounded-xl bg-gray-50 dark:bg-gray-900
                                           cursor-pointer" />
                    </div>
                    @error('receipt')
                        <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="file" class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                        <div class="w-3 h-3 rounded-full border-2 border-primary border-t-transparent animate-spin"></div>
                        Mengunggah...
                    </div>
                </div>

                @if ($file)
                    @if (!$isScanned)
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Preview</p>
                            <div
                                class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex justify-center p-2">
                                <img src="{{ $file->temporaryUrl() }}" alt="Preview"
                                    class="max-h-[320px] w-auto rounded-lg object-contain shadow">
                            </div>
                        </div>
                    @else
                        <div
                            class="rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden text-sm">
                            <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900 font-semibold text-gray-700 dark:text-gray-300">
                                Hasil Scan
                            </div>
                            <div class="px-4 py-2 flex justify-between">
                                <span class="text-gray-500">Toko</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $scanResult['toko'] }}</span>
                            </div>
                            <div class="px-4 py-2 flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $scanResult['tanggal'] }}</span>
                            </div>
                            <div class="px-4 py-3">
                                <p class="text-gray-500 mb-1">Items</p>
                                <ul class="space-y-1">
                                    @foreach($scanResult['items'] as $item)
                                        <li class="flex justify-between text-gray-800 dark:text-gray-200">
                                            <span>{{ $item['nama'] }}</span>
                                            <span class="font-medium">Rp {{ number_format($item['harga']) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="px-4 py-2 flex justify-between bg-gray-50 dark:bg-gray-900 font-semibold">
                                <span class="text-gray-700 dark:text-gray-300">Total</span>
                                <span class="text-gray-900 dark:text-white">Rp {{ number_format($scanResult['total']) }}</span>
                            </div>
                        </div>
                    @endif
                @endif

                <template x-if="errorMessage">
                    <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                        <span x-text="errorMessage"></span>
                    </div>
                </template>

                {{-- Actions --}}
                <div class="flex justify-end gap-2 pt-1">
                    <button wire:click="$set('show', false)" type="button"
                        class="py-2 px-4 rounded-2xl text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" :disabled="submitting || !canSubmit"
                        class="bg-primary rounded-2xl py-2 px-4 text-white font-semibold text-sm shadow-md inline-flex items-center gap-2"
                        :class="(submitting || !canSubmit) ? 'opacity-40 cursor-not-allowed' : ''">
                        <span x-show="!submitting">Mulai Scan</span>
                        <span x-show="submitting" class="inline-flex items-center gap-2">
                            <span
                                class="w-3 h-3 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </x-modal>
    @else
        <x-warning-modal wire:model="show" title="Kategori Belum Tersedia"
            description="Wah, sepertinya brankasmu belum punya label kategori. Buat kategori pengeluaran dulu agar uangmu tercatat rapi!">
            <a href="{{ route('kategori') }}"
                class="text-white bg-primary hover:bg-primary-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                Buat Kategori Sekarang
            </a>
            <button type="button" wire:click="$toggle('show')"
                class="text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                Nanti Saja
            </button>
        </x-warning-modal>
    @endif
</div>

<script>
    function scanForm() {
        return {
            submitting: false,
            errorMessage: '',
            hasFile: false,
            init() {
                this.$watch('$wire.show', value => {
                    if (!value) {
                        const input = this.$el.querySelector('input[name="receipt"]');
                        if (input) {
                            input.value = '';
                        }
                        this.hasFile = false;
                        this.errorMessage = '';
                        this.submitting = false;
                    }
                });
            },
            get canSubmit() {
                return this.hasFile;
            },
            async submit(event) {
                if (this.submitting || !this.canSubmit) return;
                this.submitting = true;
                this.errorMessage = '';

                const form = event.target;
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: formData,
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        this.errorMessage = payload.error || 'Gagal scan struk';
                        this.submitting = false;
                        return;
                    }

                    if (!payload || payload.success !== true || !Array.isArray(payload.data)) {
                        this.errorMessage = payload.error || 'Gagal scan struk';
                        this.submitting = false;
                        return;
                    }

                    localStorage.setItem('scan_items', JSON.stringify(payload.data));
                    window.location.href = '{{ route('pengeluaran.hasil-scan') }}';
                } catch (error) {
                    this.errorMessage = 'Terjadi kesalahan sistem';
                    this.submitting = false;
                }
            },
        };
    }
</script>