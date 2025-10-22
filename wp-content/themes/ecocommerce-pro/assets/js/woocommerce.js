/**
 * EcoCommerce Pro - WooCommerce JavaScript
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initProductGallery();
        initProductTabs();
        initCartFunctionality();
        initCheckoutProcess();
        initProductFilters();
        initProductSearch();
        initWishlist();
        initCompare();
        initProductQuickView();
    });

    /**
     * Enhanced Product Gallery
     */
    function initProductGallery() {
        const mainImage = $('.product-main-image img');
        const thumbnails = $('.thumbnail-item img');
        const galleryContainer = $('.product-gallery');

        if (thumbnails.length) {
            // Set first thumbnail as active
            thumbnails.first().parent().addClass('active');

            // Thumbnail click handler
            thumbnails.on('click', function() {
                const newSrc = $(this).attr('src');
                const newAlt = $(this).attr('alt');
                
                mainImage.attr('src', newSrc);
                mainImage.attr('alt', newAlt);
                
                thumbnails.parent().removeClass('active');
                $(this).parent().addClass('active');
            });

            // Touch/swipe support for mobile
            if (galleryContainer.length) {
                let startX = 0;
                let endX = 0;

                galleryContainer.on('touchstart', function(e) {
                    startX = e.originalEvent.touches[0].clientX;
                });

                galleryContainer.on('touchend', function(e) {
                    endX = e.originalEvent.changedTouches[0].clientX;
                    handleSwipe();
                });

                function handleSwipe() {
                    const threshold = 50;
                    const diff = startX - endX;

                    if (Math.abs(diff) > threshold) {
                        const activeThumbnail = $('.thumbnail-item.active');
                        
                        if (diff > 0) {
                            // Swipe left - next image
                            const nextThumbnail = activeThumbnail.next();
                            if (nextThumbnail.length) {
                                nextThumbnail.find('img').trigger('click');
                            }
                        } else {
                            // Swipe right - previous image
                            const prevThumbnail = activeThumbnail.prev();
                            if (prevThumbnail.length) {
                                prevThumbnail.find('img').trigger('click');
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Product Tabs Enhancement
     */
    function initProductTabs() {
        const tabs = $('.woocommerce-tabs ul.tabs li');
        const tabPanels = $('.woocommerce-tabs .panel');

        tabs.on('click', function(e) {
            e.preventDefault();
            
            const targetTab = $(this).find('a').attr('href');
            
            // Update active tab
            tabs.removeClass('active');
            $(this).addClass('active');
            
            // Show/hide tab panels
            tabPanels.hide();
            $(targetTab).fadeIn(300);
        });

        // Initialize first tab as active
        if (tabs.length) {
            tabs.first().addClass('active');
            tabPanels.hide();
            tabPanels.first().show();
        }
    }

    /**
     * Enhanced Cart Functionality
     */
    function initCartFunctionality() {
        // Quantity buttons
        $('.quantity').each(function() {
            const quantityContainer = $(this);
            const input = quantityContainer.find('input');
            const value = parseInt(input.val()) || 1;
            const min = parseInt(input.attr('min')) || 1;
            const max = parseInt(input.attr('max')) || 999;

            // Add quantity buttons
            const decreaseBtn = $('<button type="button" class="qty-btn qty-decrease">-</button>');
            const increaseBtn = $('<button type="button" class="qty-btn qty-increase">+</button>');
            
            input.before(decreaseBtn);
            input.after(increaseBtn);

            // Decrease quantity
            decreaseBtn.on('click', function() {
                const currentValue = parseInt(input.val()) || min;
                if (currentValue > min) {
                    input.val(currentValue - 1).trigger('change');
                }
            });

            // Increase quantity
            increaseBtn.on('click', function() {
                const currentValue = parseInt(input.val()) || min;
                if (currentValue < max) {
                    input.val(currentValue + 1).trigger('change');
                }
            });

            // Validate input
            input.on('change', function() {
                let value = parseInt($(this).val()) || min;
                if (value < min) value = min;
                if (value > max) value = max;
                $(this).val(value);
            });
        });

        // Remove item confirmation
        $('.product-remove .remove').on('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        });

        // Cart item quantity update
        $('.cart .quantity input').on('change', function() {
            const input = $(this);
            const cartItemKey = input.attr('name').replace('cart[', '').replace('][qty]', '');
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_update_cart',
                    cart_item_key: cartItemKey,
                    quantity: input.val(),
                    nonce: wc_add_to_cart_params.update_cart_nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });
    }

    /**
     * Checkout Process Enhancement
     */
    function initCheckoutProcess() {
        const checkoutForm = $('.checkout');
        const paymentMethods = $('.payment_methods input[type="radio"]');
        const paymentDivs = $('.payment_box');

        // Payment method selection
        paymentMethods.on('change', function() {
            const selectedMethod = $(this).val();
            
            paymentDivs.slideUp(300);
            $(`.payment_box[data-method="${selectedMethod}"]`).slideDown(300);
        });

        // Form validation
        checkoutForm.on('submit', function(e) {
            let isValid = true;
            const requiredFields = $(this).find('[required]');
            
            requiredFields.each(function() {
                const field = $(this);
                if (!field.val().trim()) {
                    isValid = false;
                    field.addClass('error');
                    if (!field.next('.error-message').length) {
                        field.after('<div class="error-message">This field is required.</div>');
                    }
                } else {
                    field.removeClass('error');
                    field.next('.error-message').remove();
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.error').first().offset().top - 100
                }, 500);
            }
        });

        // Real-time validation
        $('.checkout input, .checkout select').on('blur', function() {
            const field = $(this);
            const value = field.val().trim();
            
            if (field.attr('required') && !value) {
                field.addClass('error');
                if (!field.next('.error-message').length) {
                    field.after('<div class="error-message">This field is required.</div>');
                }
            } else {
                field.removeClass('error');
                field.next('.error-message').remove();
            }
        });
    }

    /**
     * Product Filters
     */
    function initProductFilters() {
        const filterForm = $('.woocommerce-ordering');
        const priceFilter = $('.price-filter');
        const attributeFilters = $('.attribute-filter');

        // Price filter
        if (priceFilter.length) {
            const priceSlider = priceFilter.find('.price-slider');
            const priceInputs = priceFilter.find('.price-inputs input');
            
            priceInputs.on('change', function() {
                const minPrice = priceInputs.first().val();
                const maxPrice = priceInputs.last().val();
                
                filterProducts({
                    min_price: minPrice,
                    max_price: maxPrice
                });
            });
        }

        // Attribute filters
        attributeFilters.on('change', function() {
            const filters = {};
            
            attributeFilters.each(function() {
                const filter = $(this);
                const attribute = filter.data('attribute');
                const values = filter.find('input:checked').map(function() {
                    return $(this).val();
                }).get();
                
                if (values.length) {
                    filters[attribute] = values;
                }
            });
            
            filterProducts(filters);
        });

        function filterProducts(filters) {
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_products',
                    filters: filters,
                    nonce: wc_add_to_cart_params.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.woocommerce ul.products').html(response.data.products);
                        $('.woocommerce-result-count').html(response.data.result_count);
                        $('.woocommerce-pagination').html(response.data.pagination);
                    }
                }
            });
        }
    }

    /**
     * Product Search Enhancement
     */
    function initProductSearch() {
        const searchInput = $('.woocommerce-product-search input');
        const searchResults = $('<div class="search-results"></div>');
        
        if (searchInput.length) {
            searchInput.after(searchResults);
            
            let searchTimeout;
            
            searchInput.on('input', function() {
                const query = $(this).val().trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length >= 3) {
                    searchTimeout = setTimeout(() => {
                        searchProducts(query);
                    }, 300);
                } else {
                    searchResults.hide();
                }
            });
            
            function searchProducts(query) {
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'product_search',
                        query: query,
                        nonce: wc_add_to_cart_params.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            searchResults.html(response.data.results).show();
                        }
                    }
                });
            }
            
            // Hide results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.woocommerce-product-search').length) {
                    searchResults.hide();
                }
            });
        }
    }

    /**
     * Wishlist Functionality
     */
    function initWishlist() {
        $('.wishlist-btn').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'toggle_wishlist',
                    product_id: productId,
                    nonce: wc_add_to_cart_params.nonce
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
     * Product Comparison
     */
    function initCompare() {
        $('.compare-btn').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'add_to_compare',
                    product_id: productId,
                    nonce: wc_add_to_cart_params.nonce
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
     * Product Quick View
     */
    function initProductQuickView() {
        $('.quick-view-btn').on('click', function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'product_quick_view',
                    product_id: productId,
                    nonce: wc_add_to_cart_params.nonce
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
     * Show Notification
     */
    function showNotification(message, type = 'info') {
        const notification = $(`<div class="woocommerce-notification woocommerce-notification-${type}">${message}</div>`);
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
     * Add to Cart Animation
     */
    function initAddToCartAnimation() {
        $('.single_add_to_cart_button').on('click', function() {
            const button = $(this);
            const originalText = button.text();
            
            button.text('Adding...').prop('disabled', true);
            
            setTimeout(() => {
                button.text(originalText).prop('disabled', false);
            }, 2000);
        });
    }

    /**
     * Initialize add to cart animation
     */
    initAddToCartAnimation();

    /**
     * Product Image Zoom
     */
    function initProductImageZoom() {
        const productImage = $('.product-main-image img');
        
        if (productImage.length) {
            productImage.on('click', function() {
                const src = $(this).attr('src');
                const alt = $(this).attr('alt');
                
                const zoomModal = $(`
                    <div class="image-zoom-modal">
                        <div class="zoom-overlay"></div>
                        <div class="zoom-content">
                            <button class="zoom-close">&times;</button>
                            <img src="${src}" alt="${alt}">
                        </div>
                    </div>
                `);
                
                $('body').append(zoomModal);
                zoomModal.addClass('active');
                
                zoomModal.on('click', function(e) {
                    if (e.target === this || $(e.target).hasClass('zoom-close')) {
                        zoomModal.removeClass('active');
                        setTimeout(() => {
                            zoomModal.remove();
                        }, 300);
                    }
                });
            });
        }
    }

    /**
     * Initialize product image zoom
     */
    initProductImageZoom();

})(jQuery);
