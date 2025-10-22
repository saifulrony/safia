<?php
/**
 * EcoCommerce Pro functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function ecocommerce_pro_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Set default thumbnail sizes
    set_post_thumbnail_size(800, 600, true);
    add_image_size('ecocommerce-featured', 1200, 800, true);
    add_image_size('ecocommerce-product', 400, 400, true);
    add_image_size('ecocommerce-thumbnail', 150, 150, true);

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'ecocommerce-pro'),
        'footer'  => esc_html__('Footer Menu', 'ecocommerce-pro'),
    ));

    // Switch default core markup for search form, comment form, and comments
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('ecocommerce_pro_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo.
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for wide alignment
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom spacing
    add_theme_support('custom-spacing');
}
add_action('after_setup_theme', 'ecocommerce_pro_setup');

/**
 * Default menu fallback
 */
function ecocommerce_pro_default_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . home_url('/') . '">Home</a></li>';
    echo '<li><a href="' . home_url('/shop/') . '">Shop</a></li>';
    echo '<li><a href="' . home_url('/cart/') . '">Cart</a></li>';
    echo '<li><a href="' . home_url('/checkout/') . '">Checkout</a></li>';
    echo '<li><a href="' . home_url('/my-account/') . '">My Account</a></li>';
    echo '<li><a href="' . home_url('/contact/') . '">Contact</a></li>';
    echo '</ul>';
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function ecocommerce_pro_content_width() {
    $GLOBALS['content_width'] = apply_filters('ecocommerce_pro_content_width', 1200);
}
add_action('after_setup_theme', 'ecocommerce_pro_content_width', 0);

/**
 * Register widget areas.
 */
function ecocommerce_pro_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'ecocommerce-pro'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'ecocommerce-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Shop Sidebar', 'ecocommerce-pro'),
        'id'            => 'shop-sidebar',
        'description'   => esc_html__('Add widgets here for the shop page.', 'ecocommerce-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 1', 'ecocommerce-pro'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here.', 'ecocommerce-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 2', 'ecocommerce-pro'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here.', 'ecocommerce-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 3', 'ecocommerce-pro'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here.', 'ecocommerce-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 4', 'ecocommerce-pro'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here.', 'ecocommerce-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ecocommerce_pro_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function ecocommerce_pro_scripts() {
    wp_enqueue_style('ecocommerce-pro-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue Google Fonts
    wp_enqueue_style('ecocommerce-pro-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', array(), null);
    
    // Enqueue main theme stylesheet
    wp_enqueue_style('ecocommerce-pro-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Enqueue header templates CSS
    wp_enqueue_style('ecocommerce-pro-header-templates', get_template_directory_uri() . '/assets/css/header-templates.css', array('ecocommerce-pro-main'), '1.0.0');
    
    // Enqueue homepage stylesheet for front page
    if (is_front_page() || is_home()) {
        wp_enqueue_style('ecocommerce-pro-homepage', get_template_directory_uri() . '/assets/css/homepage.css', array('ecocommerce-pro-main'), '1.0.0');
    }
    
    // Enqueue world-class premium Elementor styles
    wp_enqueue_style('ecocommerce-pro-premium-elementor', get_template_directory_uri() . '/assets/css/premium-elementor.css', array('ecocommerce-pro-main'), '1.0.0');
    
    // Enqueue Porto-style header
    wp_enqueue_style('ecocommerce-pro-porto-header', get_template_directory_uri() . '/assets/css/porto-header.css', array('ecocommerce-pro-main'), '1.0.0');
    
    // Enqueue main JavaScript
    wp_enqueue_script('ecocommerce-pro-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Enqueue hero slider JavaScript
    wp_enqueue_script('ecocommerce-pro-hero-slider', get_template_directory_uri() . '/assets/js/hero-slider.js', array(), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('ecocommerce-pro-main', 'ecocommerce_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ecocommerce_nonce'),
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'ecocommerce_pro_scripts');

/**
 * WooCommerce Support
 */
function ecocommerce_pro_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'gallery_thumbnail_image_width' => 100,
        'single_image_width' => 600,
    ));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'ecocommerce_pro_woocommerce_setup');

/**
 * Remove default WooCommerce styles
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Custom WooCommerce styles
 */
function ecocommerce_pro_woocommerce_styles() {
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('ecocommerce-pro-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'ecocommerce_pro_woocommerce_styles');

// Excerpt functions moved to inc/template-functions.php to avoid duplication

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * WooCommerce customizations.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Theme Options Panel.
 */
require get_template_directory() . '/inc/theme-options.php';
require get_template_directory() . '/inc/theme-options-part2.php';
require get_template_directory() . '/inc/demo-import.php';
require get_template_directory() . '/inc/demo-import-complete.php';
require get_template_directory() . '/inc/demo-import-page.php';

/**
 * Page Builder Support.
 */
require get_template_directory() . '/inc/page-builder-support.php';

/**
 * Admin UI Helpers.
 */
require get_template_directory() . '/inc/admin-helpers.php';

/**
 * Compact All Options Page.
 */
require get_template_directory() . '/inc/all-options-compact.php';

/**
 * Header Templates.
 */
require get_template_directory() . '/inc/header-templates.php';

/**
 * Theme Output Handler - Makes all options actually work!
 */
require get_template_directory() . '/inc/theme-output.php';

/**
 * Cart Options Output - Applies cart customizations
 */
require get_template_directory() . '/inc/cart-output.php';

/**
 * Preloader Output - Displays page preloader
 */
require get_template_directory() . '/inc/preloader-output.php';

/**
 * Security enhancements
 */
function ecocommerce_pro_security_headers() {
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove Windows Live Writer manifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'ecocommerce_pro_security_headers');

// Performance optimizations function moved to inc/template-functions.php to avoid duplication

// Body classes function moved to inc/template-functions.php to avoid duplication

/**
 * Theme activation hook
 */
function ecocommerce_pro_theme_activation() {
    // Set default options
    set_theme_mod('ecocommerce_pro_primary_color', '#0073aa');
    set_theme_mod('ecocommerce_pro_secondary_color', '#28a745');
    set_theme_mod('ecocommerce_pro_header_layout', 'default');
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ecocommerce_pro_theme_activation');

/**
 * Theme deactivation hook
 */
function ecocommerce_pro_theme_deactivation() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'ecocommerce_pro_theme_deactivation');
