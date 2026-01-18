<div>
    <div class="w-full bg-neutral-primary-soft border border-default rounded-base shadow-xs p-4 md:p-6">
        <div class="flex justify-between mb-2 md:mb-2">
            <div class="grid gap-4 grid-cols-2">
               <h1 class="font-bold text-xl">transakasi</h1>
            </div>
        </div>
        <div wire:ignore>
            <div id="chart" ></div>
        </div>
    </div>
    <script>
        function destroyChart() {
            if (window.pendapatanChart && typeof window.pendapatanChart.destroy === 'function') {
                window.pendapatanChart.destroy();
                window.pendapatanChart = null;
            }
        }

        function initPendapatanChart(incomingData = null) {
            const el = document.querySelector('#chart');
            if (!el) return;

            if (el.offsetWidth === 0) {
                requestAnimationFrame(() => initPendapatanChart(incomingData));
                return;
            }

            destroyChart();

            // DATA DUMMY: Simulasi data 7 hari terakhir
            const dummyData = {
                categories: [
                    new Date().setDate(new Date().getDate() - 3),
                    new Date().setDate(new Date().getDate() - 2),
                    new Date().setDate(new Date().getDate() - 1),
                    new Date().getTime()
                ],
                pengeluaran: [1200000, 1500000, 900000, 2100000],
                pemasukan: [800000, 1100000, 1400000, 1200000]
            };

            // Gunakan incomingData jika ada (dari Livewire), jika tidak gunakan dummyData
            const data = incomingData || dummyData;

            window.pendapatanChart = new ApexCharts(el, {
                // Menggunakan warna dari palet Anda (Mustard & Tangerine atau Green dari kode awal)
                // Di sini saya sesuaikan dengan kode awal Anda: Hijau Tua & Hijau Muda
                colors: ['#FFB800', '#F26119'],
                series: [{
                        name: 'pengeluaran',
                        data: data.pengeluaran
                    },
                    {
                        name: 'pemasukan',
                        data: data.pemasukan
                    }
                ],
                chart: {
                    toolbar: {
                        show: false
                    },
                    type: 'area',
                    height: 250,
                    width: '100%',
                    animations: {
                        enabled: true
                    },
                    redrawOnParentResize: true
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 5
                },
                xaxis: {
                    type: 'datetime',
                    categories: data.categories,
                    labels: {
                        style: {
                            colors: '#9ca3af'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af'
                        },
                        formatter: function(value) {
                            return "Rp " + (value / 1000000).toFixed(1) + "jt";
                        }
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy'
                    },
                    y: {
                        formatter: function(value) {
                            return "Rp " + value.toLocaleString('id-ID');
                        }
                    }
                }
            });

            window.pendapatanChart.render();
        }

        document.addEventListener('livewire:navigated', () => initPendapatanChart());
        window.addEventListener('update-chart', (event) => initPendapatanChart(event.detail.data));
        document.addEventListener('livewire:navigating', () => destroyChart());
    </script>

</div>
