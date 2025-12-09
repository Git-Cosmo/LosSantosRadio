<x-layouts.app title="Analytics Dashboard">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <div class="analytics-dashboard">
        <div class="analytics-header">
            <h2 class="analytics-title">
                <i class="fas fa-chart-line"></i> Listener Analytics
            </h2>
            <div class="timeframe-selector">
                <a href="{{ route('analytics') }}?timeframe=today"
                   class="btn {{ $timeframe === 'today' ? 'btn-primary' : 'btn-secondary' }}">Today</a>
                <a href="{{ route('analytics') }}?timeframe=week"
                   class="btn {{ $timeframe === 'week' ? 'btn-primary' : 'btn-secondary' }}">Week</a>
                <a href="{{ route('analytics') }}?timeframe=month"
                   class="btn {{ $timeframe === 'month' ? 'btn-primary' : 'btn-secondary' }}">Month</a>
                <a href="{{ route('analytics') }}?timeframe=year"
                   class="btn {{ $timeframe === 'year' ? 'btn-primary' : 'btn-secondary' }}">Year</a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--color-accent), #7c3aed);">
                    <i class="fas fa-music"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($requestStats['total'] ?? 0) }}</div>
                    <div class="stat-label">Total Requests</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($requestStats['played'] ?? 0) }}</div>
                    <div class="stat-label">Played</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($requestStats['pending'] ?? 0) }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $requestStats['success_rate'] ?? 0 }}%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>

        <div class="analytics-grid">
            <!-- Daily Requests Chart -->
            <div class="card analytics-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Daily Requests
                    </h3>
                </div>
                <div class="card-body">
                    <div id="dailyRequestsChart"></div>
                </div>
            </div>

            <!-- Top Requested Songs -->
            <div class="card analytics-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-fire"></i> Top Requested Songs
                    </h3>
                </div>
                <div class="card-body">
                    @if(count($topSongs) > 0)
                        <div class="top-list">
                            @foreach($topSongs as $index => $song)
                                <div class="top-item">
                                    <span class="top-rank">#{{ $index + 1 }}</span>
                                    <div class="top-info">
                                        <div class="top-title">{{ $song['title'] }}</div>
                                        <div class="top-subtitle">{{ $song['artist'] }}</div>
                                    </div>
                                    <span class="top-count">{{ $song['count'] }} requests</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No data available</p>
                    @endif
                </div>
            </div>

            <!-- Top Requesters -->
            <div class="card analytics-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Top Requesters
                    </h3>
                </div>
                <div class="card-body">
                    @if(count($topRequesters) > 0)
                        <div class="top-list">
                            @foreach($topRequesters as $index => $requester)
                                <div class="top-item">
                                    <span class="top-rank">#{{ $index + 1 }}</span>
                                    <div class="top-info">
                                        @if($requester['user'])
                                            <img src="{{ $requester['user']['avatar'] }}" alt="{{ $requester['user']['name'] }}" class="requester-avatar">
                                            <span class="top-title">{{ $requester['user']['name'] }}</span>
                                        @else
                                            <span class="top-title">Unknown User</span>
                                        @endif
                                    </div>
                                    <span class="top-count">{{ $requester['count'] }} requests</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    

    

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.54.1/dist/apexcharts.min.js" integrity="sha384-RIvv8kW3pN2ELOLfhVHsQWQI3pbMNEYCkLOOkdYhlhbKNxjx8FU2n7T1XNXN2kPL" crossorigin="anonymous"></script>
    <script>
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
    </script>
    @endpush
</x-layouts.app>
