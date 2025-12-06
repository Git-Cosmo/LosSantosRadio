/**
 * UI Helpers Module
 * Contains utility functions for UI interactions
 */

/**
 * Mobile menu toggle
 */
export function toggleMobileMenu() {
    const navLinks = document.getElementById('nav-links');
    const icon = document.getElementById('mobile-menu-icon');
    if (navLinks) navLinks.classList.toggle('mobile-open');
    if (icon) {
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    }
}

/**
 * Create scroll to top button with throttling
 */
export function createScrollToTop() {
    // Prevent duplicate scroll indicators
    if (document.querySelector('.scroll-indicator')) return;

    const scrollBtn = document.createElement('div');
    scrollBtn.className = 'scroll-indicator';
    scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
    document.body.appendChild(scrollBtn);

    let ticking = false;
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        lastScrollY = window.scrollY;

        if (!ticking) {
            window.requestAnimationFrame(() => {
                if (lastScrollY > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}

/**
 * Add entrance animations to cards using CSS classes
 */
export function addEntranceAnimations() {
    // Respect user's motion preferences
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const cards = document.querySelectorAll('.card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('card-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    cards.forEach((card, index) => {
        card.classList.add('card-entrance');
        card.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(card);
    });
}

/**
 * Global toast notification helper
 * Uses toastr if available, falls back silently in production
 * @param {string} type - Type of toast: 'success', 'error', 'warning', 'info'
 * @param {string} message - Message to display
 */
export function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        switch(type) {
            case 'success':
                toastr.success(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'info':
                toastr.info(message);
                break;
            default:
                toastr.info(message);
        }
    }
    // Silent fallback - toastr not available, notification is skipped
    // This prevents console noise in production when toastr isn't loaded
}

// Make functions available globally
window.toggleMobileMenu = toggleMobileMenu;
window.createScrollToTop = createScrollToTop;
window.addEntranceAnimations = addEntranceAnimations;
window.showToast = showToast;
