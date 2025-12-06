/**
 * Live Clock Alpine.js Component
 * Displays a real-time clock with 12/24 hour format toggle
 */
export function liveClock() {
    return {
        time: '',
        interval: null,
        format: localStorage.getItem('clockFormat') || '24',
        _onStorage: null,

        init() {
            this.updateTime();
            this.interval = setInterval(() => this.updateTime(), 1000);
            // Listen for clock format changes via storage event (for cross-tab sync)
            this._onStorage = (e) => {
                if (e.key === 'clockFormat') {
                    this.format = e.newValue || '24';
                    this.updateTime();
                }
            };
            window.addEventListener('storage', this._onStorage);
        },

        destroy() {
            // Cleanup function to prevent memory leaks
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            if (this._onStorage) {
                window.removeEventListener('storage', this._onStorage);
                this._onStorage = null;
            }
        },

        toggleFormat() {
            this.format = this.format === '24' ? '12' : '24';
            localStorage.setItem('clockFormat', this.format);
            this.updateTime();
        },

        updateTime() {
            const now = new Date();

            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');

            if (this.format === '12') {
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // 0 should be 12
                this.time = `${hours}:${minutes}:${seconds} ${ampm}`;
            } else {
                this.time = `${hours.toString().padStart(2, '0')}:${minutes}:${seconds}`;
            }
        },

        getFormatLabel() {
            return this.format === '24' ? '24H' : '12H';
        }
    };
}

// Make available globally for Alpine.js
window.liveClock = liveClock;
