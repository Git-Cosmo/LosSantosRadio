/**
 * New Year Theme Overlay
 * Adds celebratory fireworks and party effects to the site
 */

(function() {
    'use strict';

    const NewYearTheme = {
        canvas: null,
        ctx: null,
        particles: [],
        animationFrame: null,
        fireworkInterval: null,
        FIREWORK_INTERVAL_MS: 2000, // Interval between fireworks in milliseconds

        init() {
            console.log('🎉 Initializing New Year Theme...');
            this.createCanvas();
            this.addPartyElements();
            this.startFireworks();
            this.addPartyColors();
        },

        createCanvas() {
            this.canvas = document.createElement('canvas');
            this.canvas.id = 'newyear-canvas';
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

        createFirework(x, y) {
            const colors = ['#ef4444', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
            const particleCount = 50;
            const color = colors[Math.floor(Math.random() * colors.length)];

            for (let i = 0; i < particleCount; i++) {
                const angle = (Math.PI * 2 * i) / particleCount;
                const velocity = Math.random() * 3 + 2;
                
                this.particles.push({
                    x: x,
                    y: y,
                    vx: Math.cos(angle) * velocity,
                    vy: Math.sin(angle) * velocity,
                    life: 1,
                    color: color,
                    size: Math.random() * 3 + 1
                });
            }
        },

        startFireworks() {
            this.animate();
            
            // Launch fireworks periodically
            this.fireworkInterval = setInterval(() => {
                // Limit total particles to prevent memory issues
                if (this.particles.length > 500) {
                    return;
                }
                const x = Math.random() * this.canvas.width;
                const y = Math.random() * (this.canvas.height * 0.5);
                this.createFirework(x, y);
            }, this.FIREWORK_INTERVAL_MS);
        },

        animate() {
            // Clear canvas completely for clean frame
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            
            // Use trail effect more intentionally
            this.ctx.fillStyle = 'rgba(0, 0, 0, 0.15)';
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

            // Update and draw particles
            for (let i = this.particles.length - 1; i >= 0; i--) {
                const p = this.particles[i];
                
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.1; // Gravity
                p.life -= 0.01;

                if (p.life <= 0) {
                    this.particles.splice(i, 1);
                    continue;
                }

                this.ctx.beginPath();
                this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                this.ctx.fillStyle = p.color;
                this.ctx.globalAlpha = p.life;
                this.ctx.fill();
                this.ctx.globalAlpha = 1;
            }

            this.animationFrame = requestAnimationFrame(() => this.animate());
        },

        addPartyElements() {
            const style = document.createElement('style');
            style.id = 'newyear-theme-style';
            style.textContent = `
                /* New Year Theme Styles */
                @keyframes fadeIn {
                    from { opacity: 0; transform: scale(0.9); }
                    to { opacity: 1; transform: scale(1); }
                }

                @keyframes confetti-fall {
                    0% { transform: translateY(-100vh) rotate(0deg); }
                    100% { transform: translateY(100vh) rotate(360deg); }
                }

                @keyframes pulse-glow {
                    0%, 100% { 
                        box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
                    }
                    50% { 
                        box-shadow: 0 0 40px rgba(139, 92, 246, 0.8);
                    }
                }

                @keyframes rainbow {
                    0% { filter: hue-rotate(0deg); }
                    100% { filter: hue-rotate(360deg); }
                }

                .confetti {
                    position: fixed;
                    width: 10px;
                    height: 10px;
                    pointer-events: none;
                    z-index: 9998;
                    animation: confetti-fall 5s linear infinite;
                }

                body.newyear-theme {
                    --party-purple: #8b5cf6;
                    --party-pink: #ec4899;
                    --party-gold: #f59e0b;
                }

                body.newyear-theme .enhanced-audio-player {
                    animation: pulse-glow 3s ease-in-out infinite;
                }

                body.newyear-theme .status-badge.live {
                    background: linear-gradient(135deg, var(--party-purple), var(--party-pink));
                    animation: rainbow 5s linear infinite;
                }

                body.newyear-theme .play-btn {
                    animation: pulse-glow 2s ease-in-out infinite;
                }

                .party-banner {
                    position: fixed;
                    top: 60px;
                    left: 0;
                    right: 0;
                    text-align: center;
                    padding: 0.5rem;
                    background: linear-gradient(135deg, #8b5cf6, #ec4899);
                    color: white;
                    font-weight: bold;
                    z-index: 9997;
                    animation: pulse-glow 2s ease-in-out infinite, fadeIn 0.5s ease-in;
                    font-size: 1.125rem;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                }

                @media (max-width: 768px) {
                    .party-banner {
                        font-size: 0.875rem;
                        padding: 0.375rem;
                    }
                }
            `;
            document.head.appendChild(style);
            document.body.classList.add('newyear-theme');

            // Add confetti
            for (let i = 0; i < 30; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                const colors = ['#ef4444', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                confetti.style.animationDuration = (Math.random() * 3 + 4) + 's';
                document.body.appendChild(confetti);
            }

            // Add party banner
            const banner = document.createElement('div');
            banner.className = 'party-banner';
            banner.innerHTML = '🎉 Happy New Year! Join the celebration! 🎊';
            document.body.appendChild(banner);
        },

        addPartyColors() {
            // Add rainbow effect to key elements
            const accentElements = document.querySelectorAll('.btn-primary');
            accentElements.forEach(el => {
                el.style.background = 'linear-gradient(135deg, #8b5cf6, #ec4899)';
            });
        },

        destroy() {
            console.log('🎉 Removing New Year Theme...');
            
            // Cancel animations
            if (this.animationFrame) {
                cancelAnimationFrame(this.animationFrame);
            }
            if (this.fireworkInterval) {
                clearInterval(this.fireworkInterval);
            }

            // Remove canvas
            if (this.canvas) {
                this.canvas.remove();
            }

            // Remove style
            const style = document.getElementById('newyear-theme-style');
            if (style) style.remove();

            // Remove confetti and banner
            document.querySelectorAll('.confetti, .party-banner').forEach(el => el.remove());
            document.body.classList.remove('newyear-theme');
        }
    };

    // Initialize theme
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => NewYearTheme.init());
    } else {
        NewYearTheme.init();
    }

    // Expose for cleanup
    window.NewYearTheme = NewYearTheme;
})();
