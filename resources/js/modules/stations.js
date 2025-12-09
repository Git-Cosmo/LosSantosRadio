document.addEventListener('DOMContentLoaded', function() {
let refreshErrorCount = 0;
        const maxRetries = 3;

        // Auto-refresh station data every 30 seconds
        function refreshStations() {
            fetch('/api/stations/now-playing')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshErrorCount = 0;
                        hideRefreshError();
                        const totalListeners = data.data.reduce((sum, station) => sum + (station.listeners || 0), 0);
                        const listenerCount = document.getElementById('listener-count');
                        if (listenerCount) {
                            listenerCount.textContent = totalListeners;
                        }
                    } else {
                        handleRefreshError();
                    }
                })
                .catch(error => {
                    console.error('Failed to refresh stations:', error);
                    handleRefreshError();
                });
        }

        function handleRefreshError() {
            refreshErrorCount++;
            if (refreshErrorCount >= maxRetries) {
                showRefreshError();
            }
        }

        function showRefreshError() {
            const errorEl = document.getElementById('refresh-error');
            if (errorEl) {
                errorEl.style.display = 'block';
            }
        }

        function hideRefreshError() {
            const errorEl = document.getElementById('refresh-error');
            if (errorEl) {
                errorEl.style.display = 'none';
            }
        }

        // Refresh every 30 seconds
        setInterval(refreshStations, 30000);

        function playStation(stationId) {
            // This could be extended to play specific stations
            // For now, redirect to home page
            window.location.href = '/';
        }
});
