<div class="analytics-dashboard">
    <div class="analytics-header">
        <h2 class="analytics-title">
            <i class="fas fa-chart-line"></i> Listener Analytics
        </h2>
        <div class="timeframe-selector">
            <button 
                wire:click="setTimeframe('today')"
                class="btn {{ $timeframe === 'today' ? 'btn-primary' : 'btn-secondary' }}"
            >Today</button>
            <button 
                wire:click="setTimeframe('week')"
                class="btn {{ $timeframe === 'week' ? 'btn-primary' : 'btn-secondary' }}"
            >Week</button>
            <button 
                wire:click="setTimeframe('month')"
                class="btn {{ $timeframe === 'month' ? 'btn-primary' : 'btn-secondary' }}"
            >Month</button>
            <button 
                wire:click="setTimeframe('year')"
                class="btn {{ $timeframe === 'year' ? 'btn-primary' : 'btn-secondary' }}"
            >Year</button>
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
                <div id="dailyRequestsChart" wire:ignore></div>
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

    <style>
        .analytics-dashboard {
            margin: 1.5rem 0;
        }

        .analytics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .analytics-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--color-text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .analytics-title i {
            color: var(--color-accent);
        }

        .timeframe-selector {
            display: flex;
            gap: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 0.25rem;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .analytics-card {
            min-height: 350px;
        }

        .top-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .top-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background-color: var(--color-bg-tertiary);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .top-item:hover {
            background-color: var(--color-bg-hover);
            transform: translateX(4px);
        }

        .top-rank {
            font-weight: 700;
            color: var(--color-accent);
            min-width: 30px;
        }

        .top-item:first-child .top-rank {
            color: #fbbf24;
        }

        .top-item:nth-child(2) .top-rank {
            color: #9ca3af;
        }

        .top-item:nth-child(3) .top-rank {
            color: #cd7f32;
        }

        .top-info {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .top-title {
            font-weight: 500;
            color: var(--color-text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .top-subtitle {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
        }

        .top-count {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            white-space: nowrap;
        }

        .requester-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .text-muted {
            color: var(--color-text-muted);
            text-align: center;
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .analytics-header {
                flex-direction: column;
                align-items: stretch;
            }

            .timeframe-selector {
                flex-wrap: wrap;
            }

            .analytics-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@4"></script>
    <script>
        document.addEventListener('livewire:init', function() {
            let chart = null;

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
                    if (chart) {
                        chart.destroy();
                    }
                    chart = new ApexCharts(chartElement, options);
                    chart.render();
                }
            }

            // Initial render
            const initialData = @json($dailyRequests);
            if (initialData && initialData.length > 0) {
                setTimeout(() => renderChart(initialData), 100);
            }

            // Update chart when Livewire updates
            Livewire.hook('morph.updated', ({ component, toEl }) => {
                const data = component.snapshot.data.dailyRequests;
                if (data && data.length > 0) {
                    renderChart(data);
                }
            });
        });
    </script>
    @endpush
</div>
