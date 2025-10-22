<?php
/**
 * WooCommerce customizations for EcoCommerce Pro theme
 *
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce theme support
 */
function ecocommerce_pro_woocommerce_theme_support() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'gallery_thumbnail_image_width' => 100,
        'single_image_width' => 600,
    ));
    
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'ecocommerce_pro_woocommerce_theme_support');

/**
 * Remove default WooCommerce styles
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Change number of products per row to 3
 */
function ecocommerce_pro_loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'ecocommerce_pro_loop_columns');

/**
 * Change number of products per page
 */
function ecocommerce_pro_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'ecocommerce_pro_products_per_page');

/**
 * Remove WooCommerce breadcrumbs (we'll add our own)
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Remove default WooCommerce sidebar
 */
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Add custom breadcrumbs
 */
function ecocommerce_pro_woocommerce_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb(array(
            'delimiter'   => ' / ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x('Home', 'breadcrumb', 'ecocommerce-pro'),
        ));
    }
}

/**
 * Customize product gallery
 */
function ecocommerce_pro_product_gallery() {
    // Remove default gallery
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    
    // Add custom gallery
    add_action('woocommerce_before_single_product_summary', 'ecocommerce_pro_custom_product_gallery', 20);
}
add_action('init', 'ecocommerce_pro_product_gallery');

/**
 * Custom product gallery function
 */
function ecocommerce_pro_custom_product_gallery() {
    global $product;
    
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    
    if ($main_image_id || $attachment_ids) {
        echo '<div class="product-gallery">';
        
        // Main image
        if ($main_image_id) {
            echo '<div class="product-main-image">';
            echo wp_get_attachment_image($main_image_id, 'woocommerce_single', false, array('class' => 'main-image'));
            echo '</div>';
        }
        
        // Thumbnail images
        if ($attachment_ids) {
            echo '<div class="product-thumbnails">';
            foreach ($attachment_ids as $attachment_id) {
                echo '<div class="thumbnail-item">';
                echo wp_get_attachment_image($attachment_id, 'woocommerce_gallery_thumbnail', false, array('class' => 'thumbnail-image'));
                echo '</div>';
            }
            echo '</div>';
        }
        
        echo '</div>';
    }
}

/**
 * Customize single product page layout
 */
function ecocommerce_pro_single_product_layout() {
    // Remove default actions
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    
    // Add custom layout
    add_action('woocommerce_single_product_summary', 'ecocommerce_pro_single_product_title', 5);
    add_action('woocommerce_single_product_summary', 'ecocommerce_pro_single_product_price', 10);
    add_action('woocommerce_single_product_summary', 'ecocommerce_pro_single_product_excerpt', 20);
    add_action('woocommerce_single_product_summary', 'ecocommerce_pro_single_product_add_to_cart', 30);
    add_action('woocommerce_single_product_summary', 'ecocommerce_pro_single_product_meta', 40);
}
add_action('init', 'ecocommerce_pro_single_product_layout');

/**
 * Custom single product title
 */
function ecocommerce_pro_single_product_title() {
    echo '<h1 class="product_title entry-title">' . get_the_title() . '</h1>';
}

/**
 * Custom single product price
 */
function ecocommerce_pro_single_product_price() {
    global $product;
    echo '<div class="product-price">';
    echo $product->get_price_html();
    echo '</div>';
}

/**
 * Custom single product excerpt
 */
function ecocommerce_pro_single_product_excerpt() {
    global $product;
    if ($product->get_short_description()) {
        echo '<div class="product-short-description">';
        echo wp_kses_post($product->get_short_description());
        echo '</div>';
    }
}

/**
 * Custom single product add to cart
 */
function ecocommerce_pro_single_product_add_to_cart() {
    woocommerce_template_single_add_to_cart();
}

/**
 * Custom single product meta
 */
function ecocommerce_pro_single_product_meta() {
    echo '<div class="product-meta">';
    woocommerce_template_single_meta();
    echo '</div>';
}

/**
 * Customize shop page layout
 */
function ecocommerce_pro_shop_layout() {
    // Add custom shop header
    add_action('woocommerce_before_shop_loop', 'ecocommerce_pro_shop_header', 5);
    
    // Remove default shop title
    remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
    remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
}
add_action('init', 'ecocommerce_pro_shop_layout');

/**
 * Custom shop header
 */
function ecocommerce_pro_shop_header() {
    echo '<div class="shop-header">';
    echo '<h1 class="shop-title">' . woocommerce_page_title(false) . '</h1>';
    echo '<div class="shop-description">';
    echo woocommerce_product_archive_description();
    echo '</div>';
    echo '</div>';
}

/**
 * Customize product loop
 */
function ecocommerce_pro_product_loop() {
    // Remove default product actions
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
    // Add custom product loop
    add_action('woocommerce_before_shop_loop_item', 'ecocommerce_pro_product_loop_start', 5);
    add_action('woocommerce_after_shop_loop_item', 'ecocommerce_pro_product_loop_end', 15);
}
add_action('init', 'ecocommerce_pro_product_loop');

/**
 * Custom product loop start
 */
function ecocommerce_pro_product_loop_start() {
    echo '<div class="product-item">';
    echo '<div class="product-image">';
    echo '<a href="' . get_permalink() . '">';
    woocommerce_template_loop_product_thumbnail();
    echo '</a>';
    echo '</div>';
    echo '<div class="product-content">';
    echo '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
    woocommerce_template_loop_price();
    woocommerce_template_loop_rating();
    echo '<div class="product-actions">';
    woocommerce_template_loop_add_to_cart();
    echo '</div>';
    echo '</div>';
}

/**
 * Custom product loop end
 */
function ecocommerce_pro_product_loop_end() {
    echo '</div>';
}

/**
 * Customize cart page
 */
function ecocommerce_pro_cart_customizations() {
    // Add custom cart styles
    add_action('wp_head', 'ecocommerce_pro_cart_styles');
}
add_action('init', 'ecocommerce_pro_cart_customizations');

/**
 * Custom cart styles
 */
function ecocommerce_pro_cart_styles() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    if (is_cart() || is_checkout()) {
        echo '<style>
            .woocommerce-cart-form {
                margin-bottom: 2rem;
            }
            .cart-totals {
                background: #f8f9fa;
                padding: 2rem;
                border-radius: 8px;
            }
            .checkout-button {
                width: 100%;
                padding: 15px;
                font-size: 16px;
                font-weight: 600;
            }
        </style>';
    }
}

/**
 * Customize checkout page
 */
function ecocommerce_pro_checkout_customizations() {
    // Add custom checkout styles
    add_action('wp_head', 'ecocommerce_pro_checkout_styles');
}
add_action('init', 'ecocommerce_pro_checkout_customizations');

/**
 * Custom checkout styles
 */
function ecocommerce_pro_checkout_styles() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    if (is_checkout()) {
        echo '<style>
            .woocommerce-checkout-review-order-table {
                background: #f8f9fa;
                padding: 2rem;
                border-radius: 8px;
                margin-bottom: 2rem;
            }
            .place-order {
                text-align: center;
            }
            #place_order {
                width: 100%;
                padding: 15px;
                font-size: 16px;
                font-weight: 600;
            }
        </style>';
    }
}

/**
 * Add custom WooCommerce body classes
 */
function ecocommerce_pro_woocommerce_body_classes($classes) {
    if (!class_exists('WooCommerce')) {
        return $classes;
    }
    if (is_woocommerce()) {
        $classes[] = 'woocommerce-page';
    }
    if (is_shop()) {
        $classes[] = 'shop-page';
    }
    if (is_product()) {
        $classes[] = 'single-product-page';
    }
    if (is_cart()) {
        $classes[] = 'cart-page';
    }
    if (is_checkout()) {
        $classes[] = 'checkout-page';
    }
    if (is_account_page()) {
        $classes[] = 'my-account-page';
    }
    return $classes;
}
add_filter('body_class', 'ecocommerce_pro_woocommerce_body_classes');

/**
 * Customize WooCommerce pagination
 */
function ecocommerce_pro_woocommerce_pagination_args($args) {
    $args['prev_text'] = __('Previous', 'ecocommerce-pro');
    $args['next_text'] = __('Next', 'ecocommerce-pro');
    return $args;
}
add_filter('woocommerce_pagination_args', 'ecocommerce_pro_woocommerce_pagination_args');

/**
 * Add custom WooCommerce scripts
 */
function ecocommerce_pro_woocommerce_scripts() {
    // Only run if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        wp_enqueue_script('ecocommerce-pro-woocommerce', get_template_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'ecocommerce_pro_woocommerce_scripts');
