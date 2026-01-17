<div>
    <div class="w-full bg-neutral-primary-soft border border-default rounded-base shadow-xs p-4 md:p-6">
        <div class="flex justify-between mb-4 md:mb-6">
            <h1 class="font-bold text-xl">Transaksi</h1>
        </div>
        <div id="line-chart"></div>
    </div>
    @push('scripts')
        <script>
            // Get the CSS variable --color-brand and convert it to hex for ApexCharts
            const getBrandColor = () => {
                // Get the computed style of the document's root element
                const computedStyle = getComputedStyle(document.documentElement);

                // Get the value of the --color-brand CSS variable
                return computedStyle.getPropertyValue('--color-fg-brand').trim() || "#FFB800";
            };

            const getBrandSecondaryColor = () => {
                const computedStyle = getComputedStyle(document.documentElement);
                return computedStyle.getPropertyValue('--color-fg-brand-subtle').trim() || "#F26119";
            };

            const brandColor = getBrandColor();
            const brandSecondaryColor = getBrandSecondaryColor();

            const options = {
                chart: {
                    height: "100%",
                    maxWidth: "100%",
                    type: "line",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: {
                        enabled: false,
                    },
                    toolbar: {
                        show: false,
                    },
                },
                tooltip: {
                    enabled: true,
                    x: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: 6,
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: -26
                    },
                },
                series: [{
                        name: "Clicks",
                        data: [6500, 6418],
                        color: brandColor,
                    },
                    {
                        name: "CPC",
                        data: [6456, 6356],
                        color: brandSecondaryColor,
                    },
                ],
                legend: {
                    show: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'],
                    labels: {
                        show: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                            cssClass: 'text-xs font-normal fill-body'
                        }
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: true,
                    labels: {
                        show: true,
                        style: {
                            colors: '#9ca3af',
                            fontSize: '12px',
                        },
                        // Formatter agar angka besar (jutaan) lebih rapi
                        formatter: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + "jt";
                            if (value >= 1000) return (value / 1000) + "rb";
                            return value;
                        }
                    },
                },
            }

            if (document.getElementById("line-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("line-chart"), options);
                chart.render();
            }
        </script>
    @endpush
</div>
