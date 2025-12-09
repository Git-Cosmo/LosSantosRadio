document.addEventListener('DOMContentLoaded', function() {
            function renderChart(data) {
                // Cache computed styles for performance
                const rootStyles = getComputedStyle(document.documentElement);
                const textSecondary = rootStyles.getPropertyValue('--color-text-secondary').trim();
                const borderColor = rootStyles.getPropertyValue('--color-border').trim();
                const isDark = document.documentElement.classList.contains('dark');

                const options = {
                    series: [{
                        name: 'Requests',
                        data: data.map(d => d.count)
                    }],
                    chart: {
                        type: 'bar',
                        height: 250,
                        background: 'transparent',
                        toolbar: {
                            show: false
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '60%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: data.map(d => d.date),
                        labels: {
                            style: {
                                colors: textSecondary
                            },
                            rotate: -45,
                            rotateAlways: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: textSecondary
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'vertical',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#a855f7'],
                            inverseColors: false,
                            opacityFrom: 1,
                            opacityTo: 0.8,
                        }
                    },
                    colors: ['#58a6ff'],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light'
                    }
                };

                const chartElement = document.querySelector('#dailyRequestsChart');
                if (chartElement) {
                    const chart = new ApexCharts(chartElement, options);
                    chart.render();
                }
            }

            // Initial render with XSS-safe JSON encoding
            const initialData = {{ Js::from($dailyRequests) }};
            if (initialData && initialData.length > 0) {
                setTimeout(() => renderChart(initialData), 100);
            }
        });
