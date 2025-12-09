{{-- Quick Stats Widget Component --}}
<div class="quick-stats-widget" x-data="quickStats()" x-init="init()">
    <div class="stats-header">
        <h3 class="stats-title">
            <i class="fas fa-chart-line"></i>
            Live Stats
        </h3>
        <button @click="refresh()" class="stats-refresh-btn" :disabled="isRefreshing">
            <i class="fas fa-sync-alt" :class="{ 'fa-spin': isRefreshing }"></i>
        </button>
    </div>

    <div class="stats-grid">
        {{-- Current Listeners --}}
        <div class="stat-item stat-primary">
            <div class="stat-icon">
                <i class="fas fa-headphones"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" x-text="stats.currentListeners"></div>
                <div class="stat-label">Listening Now</div>
            </div>
        </div>

        {{-- Peak Listeners --}}
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" x-text="stats.peakListeners"></div>
                <div class="stat-label">Peak Today</div>
            </div>
        </div>

        {{-- Songs Played --}}
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-music"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" x-text="stats.songsPlayed"></div>
                <div class="stat-label">Songs Played</div>
            </div>
        </div>

        {{-- Stream Status --}}
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-broadcast-tower"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value" x-text="stats.isLive ? 'LIVE' : 'AutoDJ'"></div>
                <div class="stat-label">Stream Status</div>
            </div>
        </div>
    </div>
</div>

<script>
    function quickStats() {
        return {
            stats: {
                currentListeners: 0,
                peakListeners: 0,
                songsPlayed: 0,
                isLive: false
            },
            isRefreshing: false,

            init() {
                this.fetchStats();
                // Update every 30 seconds
                setInterval(() => this.fetchStats(), 30000);
            },

            async fetchStats() {
                try {
                    const response = await fetch('/api/radio/stats');
                    const data = await response.json();
                    
                    if (data.success) {
                        this.stats = {
                            currentListeners: data.data.currentListeners || 0,
                            peakListeners: data.data.peakListeners || 0,
                            songsPlayed: data.data.songsPlayed || 0,
                            isLive: data.data.isLive || false
                        };
                    }
                } catch (err) {
                    console.error('Failed to fetch stats:', err);
                }
            },

            async refresh() {
                this.isRefreshing = true;
                await this.fetchStats();
                setTimeout(() => {
                    this.isRefreshing = false;
                }, 500);
            }
        };
    }
</script>
