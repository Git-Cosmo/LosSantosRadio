document.addEventListener('DOMContentLoaded', function() {
const onAirRoute = document.querySelector('[data-djs-on-air-route]')?.dataset.djsOnAirRoute || '/djs/on-air';
fetch(onAirRoute)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('on-air-status');
                if (data.on_air) {
                    container.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(63, 185, 80, 0.1); border: 1px solid var(--color-success); border-radius: 8px;">
                            <span class="badge badge-live">LIVE</span>
                            <div>
                                <p style="font-weight: 600;">${data.dj.stage_name}</p>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted);">${data.dj.show_name || 'Live Show'}</p>
                            </div>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div style="text-align: center; padding: 0.5rem;">
                            <p style="font-weight: 500; margin-bottom: 0.25rem;">
                                <i class="fas fa-robot" style="color: var(--color-accent);"></i> AutoDJ is playing
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                                No live DJ at the moment
                            </p>
                        </div>
                    `;
                }
            })
            .catch(() => {
                document.getElementById('on-air-status').innerHTML = `
                    <p style="color: var(--color-text-muted); text-align: center;">
                        AutoDJ is playing
                    </p>
                `;
            });
});
