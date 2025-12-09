/**
 * Christmas Theme Overlay
 * Adds festive snow effects and Christmas decorations to the site
 */

(function() {
    'use strict';

    const ChristmasTheme = {
        snowflakes: [],
        canvas: null,
        ctx: null,
        animationFrame: null,

        init() {
            console.log('🎄 Initializing Christmas Theme...');
            this.injectStyles();
            this.createCanvas();
            this.addFestiveElements();
            this.startSnowfall();
            this.addChristmasColors();
        },

        injectStyles() {
            const style = document.createElement('style');
            style.id = 'christmas-theme-styles';
            style.textContent = `
                body.christmas-theme .btn-primary.christmas-accent,
                body.christmas-theme .control-btn.play-btn.christmas-accent {
                    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
                }
            `;
            document.head.appendChild(style);
        },

        createCanvas() {
            this.canvas = document.createElement('canvas');
            this.canvas.id = 'christmas-snow-canvas';
            this.canvas.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 9999;
            `;
            document.body.appendChild(this.canvas);
            this.ctx = this.canvas.getContext('2d');
            this.resizeCanvas();
            window.addEventListener('resize', () => this.resizeCanvas());
        },

        resizeCanvas() {
            this.canvas.width = window.innerWidth;
            this.canvas.height = window.innerHeight;
        },

        startSnowfall() {
            // Create snowflakes - reduce count on mobile for better performance
            const baseCount = window.innerWidth < 768 ? 50 : 100;
            const snowflakeCount = Math.min(baseCount, Math.floor(window.innerWidth / 10));
            for (let i = 0; i < snowflakeCount; i++) {
                this.snowflakes.push({
                    x: Math.random() * this.canvas.width,
                    y: Math.random() * this.canvas.height,
                    radius: Math.random() * 3 + 1,
                    speed: Math.random() * 1 + 0.5,
                    drift: Math.random() * 0.5 - 0.25,
                    opacity: Math.random() * 0.6 + 0.4
                });
            }

            this.animate();
        },

        animate() {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            // Draw and update snowflakes
            this.snowflakes.forEach(flake => {
                this.ctx.beginPath();
                this.ctx.arc(flake.x, flake.y, flake.radius, 0, Math.PI * 2);
                this.ctx.fillStyle = `rgba(255, 255, 255, ${flake.opacity})`;
                this.ctx.fill();

                // Update position
                flake.y += flake.speed;
                flake.x += flake.drift;

                // Reset snowflake if it falls off screen
                if (flake.y > this.canvas.height) {
                    flake.y = -10;
                    flake.x = Math.random() * this.canvas.width;
                }

                if (flake.x > this.canvas.width) {
                    flake.x = 0;
                } else if (flake.x < 0) {
                    flake.x = this.canvas.width;
                }
            });

            this.animationFrame = requestAnimationFrame(() => this.animate());
        },

        addFestiveElements() {
            // Add Christmas decorations to header
            const style = document.createElement('style');
            style.id = 'christmas-theme-style';
            style.textContent = `
                /* Christmas Theme Styles */
                .christmas-decoration {
                    position: fixed;
                    pointer-events: none;
                    z-index: 9998;
                    font-size: 24px;
                    animation: swing 3s ease-in-out infinite, fadeIn 0.5s ease-in;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: scale(0.8); }
                    to { opacity: 1; transform: scale(1); }
                }

                @keyframes swing {
                    0%, 100% { transform: rotate(-5deg); }
                    50% { transform: rotate(5deg); }
                }

                @keyframes twinkle {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }

                .christmas-lights {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 40px;
                    pointer-events: none;
                    z-index: 9998;
                    display: flex;
                    justify-content: space-around;
                    align-items: flex-start;
                    padding: 5px 0;
                    animation: fadeIn 0.5s ease-in;
                }

                .christmas-light {
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    animation: twinkle 2s ease-in-out infinite;
                    box-shadow: 0 0 10px currentColor, 0 0 20px currentColor;
                }

                .christmas-light:nth-child(odd) {
                    animation-delay: 1s;
                }

                /* Festive accent colors */
                body.christmas-theme {
                    --christmas-red: #ef4444;
                    --christmas-green: #10b981;
                    --christmas-gold: #f59e0b;
                }

                body.christmas-theme .enhanced-audio-player {
                    border: 2px solid var(--christmas-red);
                    box-shadow: 0 10px 40px rgba(239, 68, 68, 0.3);
                }

                body.christmas-theme .status-badge.live {
                    background: linear-gradient(135deg, var(--christmas-red), var(--christmas-green));
                }
            `;
            document.head.appendChild(style);
            document.body.classList.add('christmas-theme');

            // Add string lights
            const lights = document.createElement('div');
            lights.className = 'christmas-lights';
            lights.innerHTML = Array(30).fill(0).map((_, i) => {
                const colors = ['#ef4444', '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6'];
                const color = colors[i % colors.length];
                return `<div class="christmas-light" style="background: ${color}; color: ${color};"></div>`;
            }).join('');
            document.body.appendChild(lights);

            // Add corner decorations - positioned to avoid header/footer overlap
            const decorations = [
                { emoji: '🎄', top: '100px', left: '30px' },
                { emoji: '⭐', top: '100px', right: '30px' },
                { emoji: '🎁', bottom: '120px', left: '30px' },
                { emoji: '🔔', bottom: '120px', right: '30px' }
            ];

            decorations.forEach(dec => {
                const el = document.createElement('div');
                el.className = 'christmas-decoration';
                el.textContent = dec.emoji;
                Object.assign(el.style, dec);
                document.body.appendChild(el);
            });
        },

        addChristmasColors() {
            // Add Christmas theme class to body for CSS-based styling
            document.body.classList.add('christmas-theme');
            
            // Add subtle Christmas color theme to buttons and accents
            const accentElements = document.querySelectorAll('.btn-primary, .control-btn.play-btn');
            accentElements.forEach(el => {
                el.classList.add('christmas-accent');
            });
        },

        destroy() {
            console.log('🎄 Removing Christmas Theme...');
            
            // Cancel animation
            if (this.animationFrame) {
                cancelAnimationFrame(this.animationFrame);
            }

            // Remove canvas
            if (this.canvas) {
                this.canvas.remove();
            }

            // Remove styles
            const style = document.getElementById('christmas-theme-styles');
            if (style) style.remove();
            
            const oldStyle = document.getElementById('christmas-theme-style');
            if (oldStyle) oldStyle.remove();

            // Remove decorations
            document.querySelectorAll('.christmas-decoration, .christmas-lights').forEach(el => el.remove());
            
            // Remove Christmas classes
            document.body.classList.remove('christmas-theme');
            document.querySelectorAll('.christmas-accent').forEach(el => {
                el.classList.remove('christmas-accent');
            });
        }
    };

    // Initialize theme
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => ChristmasTheme.init());
    } else {
        ChristmasTheme.init();
    }

    // Expose for cleanup
    window.ChristmasTheme = ChristmasTheme;
})();
