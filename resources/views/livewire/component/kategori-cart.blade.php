<div>
    <div class="w-full bg-neutral-primary-soft border border-default rounded-base shadow-xs p-4 md:p-6">
        <div class="flex justify-center w-full text-center mb-2">
            <h1 class="font-bold text-xl text-charcoal">Pengeluaran per Kategori</h1>
        </div>
        <div wire:ignore>
            <div id="category-pie-chart" class=""></div>
        </div>
    </div>

    <script>
        function destroyChart1() {
            if (window.categoryChart && typeof window.categoryChart.destroy === 'function') {
                window.categoryChart.destroy();
                window.categoryChart = null;
            }
        }

        // Fungsi untuk menghasilkan warna dinamis jika kategori bertambah banyak
        function generateDynamicColors(count) {
            // Palet Utama Anda
            const basePalette = ['#191919', '#FFB800', '#F26119', '#A3B18A', '#375534'];

            if (count <= basePalette.length) {
                return basePalette.slice(0, count);
            }

            // Jika kategori > palet, tambahkan variasi warna berdasarkan palet utama
            let finalColors = [...basePalette];
            for (let i = basePalette.length; i < count; i++) {
                // Menghasilkan variasi warna (HSL) agar tetap harmonis
                const hue = (i * 137.5) % 360; // Golden angle untuk distribusi warna merata
                finalColors.push(`hsl(${hue}, 60%, 50%)`);
            }
            return finalColors;
        }

        function initCategoryChart(incomingData = null) {
            const el = document.querySelector('#category-pie-chart');
            if (!el) return;

            destroyChart1();

            // DATA DUMMY: Pengeluaran berdasarkan kategori
            // const dummyData = {
            //     labels: ['Makanan', 'Transportasi', 'Belanja'],
            //     series: [1200000, 450000, 850000] // Nominal Rp
            // };

            const data = incomingData || @json($categoryChartData ?? ['labels' => [], 'series' => []]);

            window.categoryChart = new ApexCharts(el, {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%',
                    fontFamily: 'Instrument Sans, sans-serif',
                },
                // Menggunakan palet warna Anda: Charcoal, Mustard, Tangerine + tambahan warna harmonis
                colors: generateDynamicColors(data.labels.length),
                labels: data.labels,
                series: data.series,
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '14px',
                                    fontFamily: 'Instrument Sans, sans-serif',
                                    fontWeight: 500,
                                    color: '#9ca3af',
                                    formatter: function(w) {
                                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        // Logika dinamis: Juta vs Ribu
                                        if (total >= 1000000) {
                                            return "Rp " + (total / 1000000).toFixed(1).replace('.0', '') +
                                                "jt";
                                        } else if (total >= 1000) {
                                            return "Rp " + (total / 1000).toFixed(0) + "rb";
                                        } else {
                                            return "Rp " + total;
                                        }
                                    }
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px', // Ukuran angka lebih besar
                                    fontFamily: 'Instrument Sans, sans-serif',
                                    fontWeight: 700, // Lebih tebal (bold)
                                    color: '#111827', // Warna Charcoal (text-gray-900)
                                    offsetY: 5,
                                    // Jarak vertikal angka dari label "Total"
                                },
                                name: {
                                    show: true,
                                    fontSize: '12px',
                                    fontWeight: 400,
                                    color: '#6b7280',
                                    offsetY: -10 // Menaikkan posisi label "Total" agar tidak menempel angka
                                },
                            }
                        }
                    }
                },

                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + "%";
                    }
                },
                legend: {
                    position: 'bottom',
                    fontFamily: 'inherit',
                    labels: {
                        colors: '#9ca3af'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return "Rp " + value.toLocaleString('id-ID');
                        }
                    }
                },
                stroke: {
                    show: false
                },
                noData: {
                    text: 'Belum Ada Riwayat Pengeluaran',
                    align: 'center',
                    verticalAlign: 'middle',
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        color: '#9ca3af', // Abu-abu lembut (text-gray-400)
                        fontSize: '16px',
                        fontFamily: 'Instrument Sans, sans-serif'
                    }
                }
            });

           
            window.categoryChart.render();
        }

        document.addEventListener('livewire:navigated', () => initCategoryChart());
        window.addEventListener('update-category-chart', (event) => initCategoryChart(event.detail.data));
        document.addEventListener('livewire:navigating', () => destroyChart1());
    </script>
</div>
