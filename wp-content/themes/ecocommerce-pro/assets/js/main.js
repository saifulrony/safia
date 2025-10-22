/**
 * EcoCommerce Pro - Main JavaScript
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initMobileMenu();
        initSearchToggle();
        initStickyHeader();
        initSmoothScroll();
        initLazyLoading();
        initProductGallery();
        initCartUpdates();
        initAnimations();
        initScrollToTop();
    });
    
    /**
     * Sticky Header
     */
    function initStickyHeader() {
        const $header = $('.site-header');
        const $body = $('body');
        
        // Only run if sticky header is enabled
        if (!$body.hasClass('sticky-header')) {
            return;
        }
        
        let lastScroll = 0;
        const headerHeight = $header.outerHeight();
        
        $(window).on('scroll', function() {
            const currentScroll = $(this).scrollTop();
            
            if (currentScroll > headerHeight) {
                $header.addClass('is-sticky');
                
                // For transparent headers, add scrolled class
                if ($('.header-template-transparent').length) {
                    $header.addClass('scrolled');
                }
            } else {
                $header.removeClass('is-sticky');
                
                if ($('.header-template-transparent').length) {
                    $header.removeClass('scrolled');
                }
            }
            
            lastScroll = currentScroll;
        });
    }

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const navMenu = $('.nav-menu');

        menuToggle.on('click', function() {
            $(this).toggleClass('active');
            navMenu.toggleClass('active');
            $('body').toggleClass('menu-open');
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length) {
                menuToggle.removeClass('active');
                navMenu.removeClass('active');
                $('body').removeClass('menu-open');
            }
        });

        // Close menu when clicking on a link
        navMenu.find('a').on('click', function() {
            menuToggle.removeClass('active');
            navMenu.removeClass('active');
            $('body').removeClass('menu-open');
        });
    }

    /**
     * Search Toggle
     */
    function initSearchToggle() {
        const searchToggle = $('.search-toggle');
        const searchContainer = $('.search-form-container');

        searchToggle.on('click', function() {
            searchContainer.toggleClass('active');
            if (searchContainer.hasClass('active')) {
                searchContainer.find('.search-field').focus();
            }
        });

        // Close search when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.header-search').length) {
                searchContainer.removeClass('active');
            }
        });

        // Close search on escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) { // Escape key
                searchContainer.removeClass('active');
            }
        });
    }

    /**
     * Smooth Scroll for anchor links
     */
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                    return false;
                }
            }
        });
    }

    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Product Gallery
     */
    function initProductGallery() {
        const mainImage = $('.product-main-image img');
        const thumbnails = $('.thumbnail-item img');

        thumbnails.on('click', function() {
            const newSrc = $(this).attr('src');
            mainImage.attr('src', newSrc);
            thumbnails.removeClass('active');
            $(this).addClass('active');
        });
    }

    /**
     * Cart Updates
     */
    function initCartUpdates() {
        // Update cart count when adding/removing products
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            // Update cart count
            if (fragments['.cart-count']) {
                $('.cart-count').html(fragments['.cart-count']);
            }
            
            // Show success message
            showNotification('Product added to cart!', 'success');
        });

        // Update cart when quantity changes
        $(document.body).on('updated_cart_totals', function() {
            // Refresh cart totals
            location.reload();
        });
    }

    /**
     * Animations on Scroll
     */
    function initAnimations() {
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.post-item, .product-item, .section-title').forEach(el => {
                animationObserver.observe(el);
            });
        }
    }

    /**
     * Scroll to Top Button
     */
    function initScrollToTop() {
        const backToTopBtn = $('.back-to-top, #back-to-top');
        
        // If no button exists, create one
        if (backToTopBtn.length === 0) {
            const scrollButton = $('<button class="back-to-top" aria-label="Scroll to top"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg></button>');
            $('body').append(scrollButton);
            backToTopBtn = scrollButton;
        }

        // Show/hide button based on scroll position
        $(window).on('scroll', function() {
            const scrollTop = $(this).scrollTop();
            if (scrollTop > 300) {
                $('.back-to-top').addClass('visible').fadeIn(300);
            } else {
                $('.back-to-top').removeClass('visible').fadeOut(300);
            }
        });

        // Scroll to top when clicked
        $(document).on('click', '.back-to-top', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 600, 'swing');
            return false;
        });
    }

    /**
     * Show Notification
     */
    function showNotification(message, type = 'info') {
        const notification = $(`<div class="notification notification-${type}">${message}</div>`);
        $('body').append(notification);
        
        setTimeout(() => {
            notification.addClass('show');
        }, 100);
        
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Form Validation
     */
    function initFormValidation() {
        $('form').on('submit', function(e) {
            const form = $(this);
            let isValid = true;
            
            // Remove previous error messages
            form.find('.error-message').remove();
            form.find('.error').removeClass('error');
            
            // Validate required fields
            form.find('[required]').each(function() {
                const field = $(this);
                const value = field.val().trim();
                
                if (!value) {
                    isValid = false;
                    field.addClass('error');
                    field.after(`<div class="error-message">This field is required.</div>`);
                }
            });
            
            // Validate email fields
            form.find('input[type="email"]').each(function() {
                const field = $(this);
                const value = field.val().trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (value && !emailRegex.test(value)) {
                    isValid = false;
                    field.addClass('error');
                    field.after(`<div class="error-message">Please enter a valid email address.</div>`);
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please correct the errors above.', 'error');
            }
        });
    }

    /**
     * Initialize form validation
     */
    initFormValidation();

    /**
     * Product Quick View
     */
    function initProductQuickView() {
        $('.product-item').on('click', '.quick-view-btn', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            
            // Load product quick view
            $.ajax({
                url: ecocommerce_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'product_quick_view',
                    product_id: productId,
                    nonce: ecocommerce_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('body').append(response.data.html);
                        $('.quick-view-modal').addClass('active');
                    }
                }
            });
        });

        // Close quick view
        $(document).on('click', '.quick-view-close, .quick-view-overlay', function() {
            $('.quick-view-modal').removeClass('active');
            setTimeout(() => {
                $('.quick-view-modal').remove();
            }, 300);
        });
    }

    /**
     * Initialize product quick view
     */
    initProductQuickView();

    /**
     * Wishlist functionality
     */
    function initWishlist() {
        $('.wishlist-btn').on('click', function(e) {
            e.preventDefault();
            const button = $(this);
            const productId = button.data('product-id');
            
            $.ajax({
                url: ecocommerce_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'toggle_wishlist',
                    product_id: productId,
                    nonce: ecocommerce_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        button.toggleClass('active');
                        showNotification(response.data.message, 'success');
                    }
                }
            });
        });
    }

    /**
     * Initialize wishlist
     */
    initWishlist();

    /**
     * Product Comparison
     */
    function initProductComparison() {
        $('.compare-btn').on('click', function(e) {
            e.preventDefault();
            const button = $(this);
            const productId = button.data('product-id');
            
            $.ajax({
                url: ecocommerce_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'add_to_compare',
                    product_id: productId,
                    nonce: ecocommerce_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        button.toggleClass('active');
                        showNotification(response.data.message, 'success');
                    }
                }
            });
        });
    }

    /**
     * Initialize product comparison
     */
    initProductComparison();

    /**
     * Newsletter Signup
     */
    function initNewsletterSignup() {
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const email = form.find('input[type="email"]').val();
            
            $.ajax({
                url: ecocommerce_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'newsletter_signup',
                    email: email,
                    nonce: ecocommerce_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.data.message, 'success');
                        form[0].reset();
                    } else {
                        showNotification(response.data.message, 'error');
                    }
                }
            });
        });
    }

    /**
     * Initialize newsletter signup
     */
    initNewsletterSignup();

})(jQuery);
