
    <div>
        @if (session('message'))
            @livewire('component.notif-success')
        @elseif($errors->any() || session('error'))
            @livewire('component.notif-error')
        @endif

        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold">Hasil Scan Struk</h1>
                <p class="text-sm text-gray-500">Periksa dan edit sebelum simpan semua.</p>
            </div>
            <a href="{{ route('pengeluaran') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>

        <div class="mt-6" x-data="scanItems()" x-init="init()">
            <form method="POST" action="{{ route('pengeluaran.hasil-scan.simpan') }}" @submit="prepareSubmit">
                @csrf

                <input type="hidden" name="items" :value="payload">

                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600">Tanggal</label>
                        <input type="date" x-model="defaultDate"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm" />
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600">Metode Pembayaran</label>
                        <select x-model="paymentMethod"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
                            <option value="Qris">Qris</option>
                            <option value="Bank">Bank</option>
                            <option value="Dana">Dana</option>
                            <option value="Gopay">Gopay</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama (Tujuan)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(row, index) in items" :key="index">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700" x-text="index + 1"></td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="row.tujuan"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" x-model.number="row.qty" min="1"
                                            x-on:input="row.total = row.qty ? Math.round(row.unit_price * row.qty) : 0"
                                            class="w-20 rounded-lg border border-gray-200 px-2 py-2 text-sm" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" x-model.number="row.total" min="0"
                                            x-on:input="row.unit_price = row.qty ? (row.total / row.qty) : row.total"
                                            class="w-28 rounded-lg border border-gray-200 px-2 py-2 text-sm" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <select x-model="row.kategori_nama"
                                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
                                            <option value="">Pilih Kategori</option>
                                            <template x-for="kategori in categories" :key="kategori.id">
                                                <option :value="kategori.nama" x-text="kategori.nama"
                                                    :selected="kategori.nama === row.kategori_nama"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="row.description"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <select x-model="row.status"
                                            class="rounded-lg border border-gray-200 px-3 py-2 text-xs">
                                            <option value="draft">Draft</option>
                                            <option value="approved">Approved</option>
                                            <option value="paid">Paid</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" x-on:click="items.splice(index, 1)"
                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="items.length === 0">
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Tidak ada item hasil scan. Silakan scan ulang struk.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-end gap-3">
                    <x-button type="submit">Simpan Semua</x-button>
                </div>
            </form>
        </div>

        <script>
            function scanItems() {
                return {
                    items: [],
                    categories: @json(collect($categories)->map(fn($k) => ['id' => $k['id'], 'nama' => $k['nama']])),
                    defaultDate: @json($defaultDate),
                    paymentMethod: 'Cash',
                    payload: '',
                    init() {
                        const rawText = localStorage.getItem('scan_items');
                        if (!rawText) {
                            window.location.href = '{{ route('pengeluaran') }}';
                            return;
                        }

                        const rawItems = JSON.parse(rawText || '[]');
                        if (!Array.isArray(rawItems) || rawItems.length === 0) {
                            window.location.href = '{{ route('pengeluaran') }}';
                            return;
                        }

                        this.items = rawItems.map((row) => {
                            const matchedCategory = this.categories.find((cat) => {
                                return this.normalizeName(cat.nama) === this.normalizeName(row.category);
                            });

                            return {
                                tujuan: row.item || '',
                                qty: Number(row.qty || 1),
                                total: Number(row.price || 0),
                                unit_price: Number(row.qty || 1) ? (Number(row.price || 0) / Number(row.qty || 1)) : Number(row.price || 0),
                                id_kategori: '',
                                kategori_nama: matchedCategory ? matchedCategory.nama : (row.category || ''),
                                tanggal_pengeluaran: this.defaultDate,
                                description: row.item || '',
                                status: 'approved',
                                metode_pembayaran: this.paymentMethod,
                            };
                        });
                    },
                    normalizeName(value) {
                        return String(value || '')
                            .trim()
                            .toLowerCase()
                            .replace(/\s+/g, ' ');
                    },
                    matchCategory(categoryKey) {
                        if (!categoryKey) return '';
                        const match = this.categories.find((cat) => {
                            return this.normalizeName(cat.nama) === this.normalizeName(categoryKey);
                        });
                        return match ? Number(match.id) : '';
                    },
                    prepareSubmit() {
                        const mapped = this.items.map((row) => ({
                            ...row,
                            id_kategori: this.matchCategory(row.kategori_nama),
                            tanggal_pengeluaran: this.defaultDate,
                            description: row.description || row.tujuan,
                            status: row.status || 'approved',
                            metode_pembayaran: this.paymentMethod,
                        }));
                        this.items = mapped;
                        this.payload = JSON.stringify(this.items);
                    },
                };
            }
        </script>
    </div>
