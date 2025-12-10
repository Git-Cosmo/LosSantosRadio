// Media Show Page - Interactive Features
// Handles tabs, image gallery, rating, and favorite functionality

document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    initRating();
    initImageGallery();
    initFavorite();
});

/**
 * Initialize tabbed interface
 */
function initTabs() {
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('[data-tab-content]');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            button.classList.add('active');
            const targetContent = document.querySelector(`[data-tab-content="${tabName}"]`);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
}

/**
 * Initialize rating functionality
 */
function initRating() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('rating-input');
    const ratingForm = document.getElementById('rating-form');

    if (!stars.length) return;

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            setRating(rating);
        });

        star.addEventListener('mouseenter', function() {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });
    });

    // Reset highlight on mouse leave
    const ratingContainer = document.querySelector('.rating-stars-container');
    if (ratingContainer) {
        ratingContainer.addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value || 0;
            highlightStars(currentRating);
        });
    }

    // Form submission
    if (ratingForm) {
        ratingForm.addEventListener('submit', async function(e) {
            if (!ratingInput.value) {
                e.preventDefault();
                showToast('Please select a rating', 'error');
            }
        });
    }
}

/**
 * Set rating value
 */
function setRating(rating) {
    const ratingInput = document.getElementById('rating-input');
    if (ratingInput) {
        ratingInput.value = rating;
        highlightStars(rating);
    }
}

/**
 * Highlight stars up to rating
 */
function highlightStars(rating) {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        const starRating = star.getAttribute('data-rating');
        const icon = star.querySelector('i');
        
        if (starRating <= rating) {
            icon.classList.remove('far');
            icon.classList.add('fas', 'text-yellow-500');
        } else {
            icon.classList.remove('fas', 'text-yellow-500');
            icon.classList.add('far', 'text-gray-300');
        }
    });
}

/**
 * Initialize image gallery/lightbox
 */
function initImageGallery() {
    const galleryImages = document.querySelectorAll('[data-gallery-image]');
    
    galleryImages.forEach(image => {
        image.addEventListener('click', function() {
            const imageUrl = this.getAttribute('data-gallery-image');
            openLightbox(imageUrl);
        });
    });
}

/**
 * Open image lightbox
 */
function openLightbox(imageUrl) {
    // Create lightbox if it doesn't exist
    let lightbox = document.getElementById('image-lightbox');
    
    if (!lightbox) {
        lightbox = document.createElement('div');
        lightbox.id = 'image-lightbox';
        lightbox.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4';
        lightbox.style.display = 'none';
        
        // Create close button
        const closeBtn = document.createElement('button');
        closeBtn.className = 'lightbox-close-btn absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition-colors z-10';
        closeBtn.setAttribute('aria-label', 'Close lightbox');
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.addEventListener('click', closeLightbox);
        
        // Create image element
        const img = document.createElement('img');
        img.id = 'lightbox-image';
        img.alt = 'Full size image';
        img.className = 'max-w-full max-h-full object-contain';
        
        lightbox.appendChild(closeBtn);
        lightbox.appendChild(img);
        document.body.appendChild(lightbox);
        
        // Close on click outside
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }
    
    const img = lightbox.querySelector('#lightbox-image');
    img.src = imageUrl;
    lightbox.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Close image lightbox
 */
window.closeLightbox = function() {
    const lightbox = document.getElementById('image-lightbox');
    if (lightbox) {
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
    }
};

/**
 * Initialize favorite toggle
 */
function initFavorite() {
    const favoriteBtn = document.getElementById('favorite-btn');
    
    if (!favoriteBtn) return;
    
    favoriteBtn.addEventListener('click', async function() {
        const url = this.getAttribute('data-url');
        const icon = this.querySelector('#favorite-icon');
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                // Toggle icon
                if (data.favorited) {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-500');
                    this.classList.add('text-red-500');
                    this.classList.remove('text-gray-400');
                    showToast('Added to favorites', 'success');
                } else {
                    icon.classList.remove('fas', 'text-red-500');
                    icon.classList.add('far');
                    this.classList.remove('text-red-500');
                    this.classList.add('text-gray-400');
                    showToast('Removed from favorites', 'info');
                }
            } else {
                showToast('Failed to update favorite', 'error');
            }
        } catch (error) {
            console.error('Favorite toggle error:', error);
            showToast('An error occurred', 'error');
        }
    });
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Create icon element
    const icon = document.createElement('i');
    const iconType = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    icon.className = `fas fa-${iconType}`;
    
    // Create message span and set textContent for safety
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    
    // Append icon and message to toast
    toast.appendChild(icon);
    toast.appendChild(messageSpan);
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
