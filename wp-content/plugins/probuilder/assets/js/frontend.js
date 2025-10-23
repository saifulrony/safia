/**
 * ProBuilder Frontend JavaScript
 */

(function($) {
    'use strict';
    
    const ProBuilderFrontend = {
        
        /**
         * Initialize
         */
        init: function() {
            this.initTabs();
            this.initAccordion();
            this.initCarousel();
            this.initCounter();
            this.initCountdown();
            this.initAnimatedHeadline();
        },
        
        /**
         * Initialize tabs
         */
        initTabs: function() {
            $('.probuilder-tab-title').on('click', function() {
                const $parent = $(this).closest('.probuilder-tabs');
                const tab = $(this).data('tab');
                
                // Update nav
                $parent.find('.probuilder-tab-title').removeClass('active');
                $(this).addClass('active');
                
                // Update content
                $parent.find('.probuilder-tab-content').hide().removeClass('active');
                $parent.find(`.probuilder-tab-content[data-tab="${tab}"]`).show().addClass('active');
            });
        },
        
        /**
         * Initialize accordion
         */
        initAccordion: function() {
            $('.probuilder-accordion-title').on('click', function() {
                const $parent = $(this).closest('.probuilder-accordion-item');
                const $content = $parent.find('.probuilder-accordion-content');
                const $icon = $(this).find('.probuilder-accordion-icon');
                
                // Toggle content
                $content.slideToggle(300);
                
                // Toggle icon
                if ($content.is(':visible')) {
                    $icon.text('−');
                } else {
                    $icon.text('+');
                }
            });
        },
        
        /**
         * Initialize carousel
         */
        initCarousel: function() {
            $('.probuilder-carousel').each(function() {
                const $carousel = $(this);
                const $track = $carousel.find('.probuilder-carousel-track');
                const $slides = $track.find('.probuilder-carousel-slide');
                const autoplay = $carousel.data('autoplay') === 'yes';
                const speed = parseInt($carousel.data('speed')) || 3000;
                
                let currentSlide = 0;
                const totalSlides = $slides.length;
                
                if (totalSlides <= 1) return;
                
                function goToSlide(index) {
                    if (index < 0) index = totalSlides - 1;
                    if (index >= totalSlides) index = 0;
                    
                    currentSlide = index;
                    $track.css('transform', `translateX(-${currentSlide * 100}%)`);
                }
                
                // Next button
                $carousel.find('.probuilder-carousel-next').on('click', function() {
                    goToSlide(currentSlide + 1);
                });
                
                // Previous button
                $carousel.find('.probuilder-carousel-prev').on('click', function() {
                    goToSlide(currentSlide - 1);
                });
                
                // Autoplay
                if (autoplay) {
                    setInterval(function() {
                        goToSlide(currentSlide + 1);
                    }, speed);
                }
            });
        },
        
        /**
         * Initialize counter
         */
        initCounter: function() {
            $('.probuilder-counter').each(function() {
                const $counter = $(this);
                const $value = $counter.find('.counter-value');
                const start = parseInt($counter.data('start')) || 0;
                const end = parseInt($counter.data('end')) || 100;
                const duration = parseInt($counter.data('duration')) || 2000;
                
                let hasAnimated = false;
                
                // Check if element is in viewport
                function isInViewport() {
                    const rect = $counter[0].getBoundingClientRect();
                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );
                }
                
                // Animate counter
                function animateCounter() {
                    if (hasAnimated) return;
                    hasAnimated = true;
                    
                    const startTime = Date.now();
                    const endTime = startTime + duration;
                    
                    function updateCounter() {
                        const now = Date.now();
                        const remaining = Math.max(endTime - now, 0);
                        const progress = 1 - (remaining / duration);
                        const current = Math.round(start + (end - start) * progress);
                        
                        $value.text(current);
                        
                        if (remaining > 0) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            $value.text(end);
                        }
                    }
                    
                    updateCounter();
                }
                
                // Check on scroll
                $(window).on('scroll resize', function() {
                    if (isInViewport()) {
                        animateCounter();
                    }
                });
                
                // Check on load
                if (isInViewport()) {
                    animateCounter();
                }
            });
        },
        
        /**
         * Initialize countdown
         */
        initCountdown: function() {
            $('.probuilder-countdown').each(function() {
                const $countdown = $(this);
                const targetDate = new Date($countdown.data('target')).getTime();
                
                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = targetDate - now;
                    
                    if (distance < 0) {
                        $countdown.find('.countdown-days').text('00');
                        $countdown.find('.countdown-hours').text('00');
                        $countdown.find('.countdown-minutes').text('00');
                        $countdown.find('.countdown-seconds').text('00');
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    $countdown.find('.countdown-days').text(String(days).padStart(2, '0'));
                    $countdown.find('.countdown-hours').text(String(hours).padStart(2, '0'));
                    $countdown.find('.countdown-minutes').text(String(minutes).padStart(2, '0'));
                    $countdown.find('.countdown-seconds').text(String(seconds).padStart(2, '0'));
                }
                
                updateCountdown();
                setInterval(updateCountdown, 1000);
            });
        },
        
        /**
         * Initialize animated headline
         */
        initAnimatedHeadline: function() {
            $('.animated-words').each(function() {
                const $element = $(this);
                const words = $element.data('words');
                
                if (!words || words.length === 0) return;
                
                let currentIndex = 0;
                
                setInterval(function() {
                    currentIndex = (currentIndex + 1) % words.length;
                    $element.fadeOut(300, function() {
                        $(this).text(words[currentIndex]).fadeIn(300);
                    });
                }, 3000);
            });
        }
        
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        ProBuilderFrontend.init();
    });
    
    // Re-initialize on AJAX complete (for dynamic content)
    $(document).ajaxComplete(function() {
        ProBuilderFrontend.init();
    });
    
})(jQuery);

