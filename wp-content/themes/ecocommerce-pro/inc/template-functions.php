<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function ecocommerce_pro_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add custom body classes for different page types
    if (is_front_page()) {
        $classes[] = 'homepage';
    }

    if (is_page_template('page-fullwidth.php')) {
        $classes[] = 'full-width-page';
    }

    return $classes;
}
add_filter('body_class', 'ecocommerce_pro_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function ecocommerce_pro_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'ecocommerce_pro_pingback_header');

/**
 * Customize the excerpt more link
 */
function ecocommerce_pro_excerpt_more($more) {
    if (!is_admin()) {
        $more = sprintf(
            '... <a class="read-more-link" href="%1$s">%2$s</a>',
            get_permalink(),
            __('Read More', 'ecocommerce-pro')
        );
    }
    return $more;
}
add_filter('excerpt_more', 'ecocommerce_pro_excerpt_more');

/**
 * Customize the excerpt length
 */
function ecocommerce_pro_excerpt_length($length) {
    if (is_admin()) {
        return $length;
    }
    
    if (is_home() || is_archive()) {
        return 25;
    }
    
    return 30;
}
add_filter('excerpt_length', 'ecocommerce_pro_excerpt_length');

/**
 * REMOVED: Old custom CSS function
 * Now handled by inc/theme-output.php which uses theme options instead of customizer
 * This prevents duplicate function declaration error
 */

/**
 * Add custom JavaScript for theme functionality
 */
function ecocommerce_pro_custom_js() {
    $custom_js = get_theme_mod('ecocommerce_pro_custom_js');
    if ($custom_js) {
        echo '<script type="text/javascript">' . $custom_js . '</script>';
    }
}
add_action('wp_footer', 'ecocommerce_pro_custom_js');

/**
 * Add schema markup for better SEO
 */
function ecocommerce_pro_schema_markup() {
    if (is_singular('product') && class_exists('WooCommerce')) {
        global $product;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => $product->get_short_description() ?: $product->get_description(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
            'sku' => $product->get_sku(),
            'brand' => array(
                '@type' => 'Brand',
                'name' => get_bloginfo('name')
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink()
            )
        );
        
        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_rating_count()
            );
        }
        
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
    
    if (is_singular('post')) {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'image' => has_post_thumbnail() ? wp_get_attachment_image_url(get_post_thumbnail_id(), 'full') : '',
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : ''
                )
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c')
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'ecocommerce_pro_schema_markup');

/**
 * Add custom meta tags
 */
function ecocommerce_pro_meta_tags() {
    if (is_front_page()) {
        $description = get_bloginfo('description');
        $keywords = get_theme_mod('ecocommerce_pro_meta_keywords');
        
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">';
        }
        
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">';
        }
    }
}
add_action('wp_head', 'ecocommerce_pro_meta_tags');

/**
 * Optimize WordPress performance
 */
/**
 * REMOVED: Old performance optimizations
 * Now handled by inc/theme-output.php
 */

/**
 * Add custom post types support
 */
function ecocommerce_pro_add_post_type_support() {
    add_post_type_support('page', 'excerpt');
    add_post_type_support('page', 'thumbnail');
}
add_action('init', 'ecocommerce_pro_add_post_type_support');

/**
 * Customize the login page
 */
function ecocommerce_pro_login_styles() {
    $logo_url = get_theme_mod('custom_logo');
    if ($logo_url) {
        $logo = wp_get_attachment_image_url($logo_url, 'full');
        ?>
        <style type="text/css">
            .login h1 a {
                background-image: url(<?php echo esc_url($logo); ?>);
                background-size: contain;
                width: 100%;
                height: 100px;
            }
        </style>
        <?php
    }
}
add_action('login_head', 'ecocommerce_pro_login_styles');

/**
 * Change login logo URL
 */
function ecocommerce_pro_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'ecocommerce_pro_login_logo_url');

/**
 * Change login logo title
 */
function ecocommerce_pro_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'ecocommerce_pro_login_logo_title');

/**
 * Add custom admin styles
 */
function ecocommerce_pro_admin_styles() {
    echo '<style type="text/css">
        .wp-admin #wpbody-content .metabox-holder {
            padding-top: 20px;
        }
    </style>';
}
add_action('admin_head', 'ecocommerce_pro_admin_styles');

/**
 * Add theme support for custom post types
 */
function ecocommerce_pro_custom_post_types() {
    // Add support for custom post types if needed
    // This can be extended based on requirements
}
add_action('init', 'ecocommerce_pro_custom_post_types');
