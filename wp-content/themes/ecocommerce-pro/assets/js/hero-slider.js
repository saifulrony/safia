/**
 * Hero Slider JavaScript
 * Handles automatic sliding, navigation controls, and dot indicators
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.querySelector('.hero-slider');
        if (!slider) return; // Exit if no slider found
        
        const slides = slider.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');
        const dots = document.querySelectorAll('.dot');
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        let autoplayInterval;
        
        // Show specific slide
        function showSlide(index) {
            // Remove active class from all slides and dots
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            dots.forEach(dot => {
                dot.classList.remove('active');
            });
            
            // Ensure index is within bounds
            if (index >= totalSlides) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = totalSlides - 1;
            } else {
                currentSlide = index;
            }
            
            // Add active class to current slide and dot
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }
        
        // Next slide
        function nextSlide() {
            showSlide(currentSlide + 1);
        }
        
        // Previous slide
        function prevSlide() {
            showSlide(currentSlide - 1);
        }
        
        // Start autoplay
        function startAutoplay() {
            autoplayInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        }
        
        // Stop autoplay
        function stopAutoplay() {
            clearInterval(autoplayInterval);
        }
        
        // Event listeners for navigation buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                prevSlide();
                stopAutoplay();
                startAutoplay(); // Restart autoplay after manual navigation
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                nextSlide();
                stopAutoplay();
                startAutoplay(); // Restart autoplay after manual navigation
            });
        }
        
        // Event listeners for dots
        dots.forEach(function(dot, index) {
            dot.addEventListener('click', function() {
                showSlide(index);
                stopAutoplay();
                startAutoplay(); // Restart autoplay after manual navigation
            });
        });
        
        // Pause autoplay on hover
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                stopAutoplay();
                startAutoplay();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                stopAutoplay();
                startAutoplay();
            }
        });
        
        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        slider.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        slider.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left - next slide
                nextSlide();
                stopAutoplay();
                startAutoplay();
            }
            if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right - previous slide
                prevSlide();
                stopAutoplay();
                startAutoplay();
            }
        }
        
        // Initialize: Show first slide and start autoplay
        showSlide(0);
        startAutoplay();
    });
})();

