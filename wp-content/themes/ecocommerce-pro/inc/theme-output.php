<?php
/**
 * EcoCommerce Pro - Theme Output Handler
 * This file generates CSS and JS based on theme options
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output custom CSS based on theme options
 */
function ecocommerce_pro_custom_css() {
    $styling_options = get_option('ecocommerce_pro_styling_options', array());
    $general_options = get_option('ecocommerce_pro_general_options', array());
    $header_options = get_option('ecocommerce_pro_header_options', array());
    
    ob_start();
    ?>
    <style id="ecocommerce-pro-custom-css">
    /* ==========================================================================
       Custom Theme Options CSS
       Generated dynamically from Theme Options
       ========================================================================== */
    
    /* Colors */
    <?php if (!empty($styling_options['primary_color'])) : ?>
    :root {
        --primary-color: <?php echo esc_attr($styling_options['primary_color']); ?>;
        --primary-rgb: <?php echo ecocommerce_pro_hex_to_rgb($styling_options['primary_color']); ?>;
    }
    
    .button,
    .btn-primary,
    input[type="submit"],
    button[type="submit"],
    .wp-block-button__link,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button,
    .woocommerce #respond input#submit {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .button:hover,
    .btn-primary:hover,
    input[type="submit"]:hover,
    button[type="submit"]:hover {
        background-color: rgba(var(--primary-rgb), 0.9);
    }
    
    a,
    .site-title a:hover,
    .main-navigation a:hover,
    .main-navigation a.current {
        color: var(--primary-color);
    }
    
    .woocommerce .star-rating span,
    .woocommerce-page .star-rating span {
        color: var(--primary-color);
    }
    
    .header-action-btn:hover {
        background-color: var(--primary-color);
        color: white;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['secondary_color'])) : ?>
    :root {
        --secondary-color: <?php echo esc_attr($styling_options['secondary_color']); ?>;
    }
    
    .btn-secondary,
    .button-secondary {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['text_color'])) : ?>
    body,
    .site-content {
        color: <?php echo esc_attr($styling_options['text_color']); ?>;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['heading_color'])) : ?>
    h1, h2, h3, h4, h5, h6,
    .site-title,
    .entry-title {
        color: <?php echo esc_attr($styling_options['heading_color']); ?>;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['link_color'])) : ?>
    a {
        color: <?php echo esc_attr($styling_options['link_color']); ?>;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['link_hover_color'])) : ?>
    a:hover {
        color: <?php echo esc_attr($styling_options['link_hover_color']); ?>;
    }
    <?php endif; ?>
    
    /* Typography */
    <?php if (!empty($styling_options['body_font'])) : ?>
    body {
        font-family: <?php echo esc_attr($styling_options['body_font']); ?>, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['heading_font'])) : ?>
    h1, h2, h3, h4, h5, h6,
    .site-title {
        font-family: <?php echo esc_attr($styling_options['heading_font']); ?>, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['body_font_size'])) : ?>
    body {
        font-size: <?php echo esc_attr($styling_options['body_font_size']); ?>px;
    }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['h1_size'])) : ?>
    h1, .h1 { font-size: <?php echo esc_attr($styling_options['h1_size']); ?>px; }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['h2_size'])) : ?>
    h2, .h2 { font-size: <?php echo esc_attr($styling_options['h2_size']); ?>px; }
    <?php endif; ?>
    
    <?php if (!empty($styling_options['h3_size'])) : ?>
    h3, .h3 { font-size: <?php echo esc_attr($styling_options['h3_size']); ?>px; }
    <?php endif; ?>
    
    /* Header */
    <?php if (!empty($header_options['height'])) : ?>
    .site-header {
        min-height: <?php echo esc_attr($header_options['height']); ?>px;
    }
    
    .site-header .header-content {
        padding: <?php echo esc_attr($header_options['height'] / 4); ?>px 0;
    }
    <?php endif; ?>
    
    /* Layout */
    <?php 
    $layout_type = $general_options['layout_type'] ?? 'full-width';
    if ($layout_type === 'boxed') : 
    ?>
    .site {
        max-width: 1400px;
        margin: 0 auto;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }
    <?php endif; ?>
    
    <?php 
    $container_width = $general_options['container_width'] ?? 1200;
    ?>
    .container {
        max-width: <?php echo esc_attr($container_width); ?>px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 20px;
        padding-right: 20px;
    }
    
    /* Custom CSS */
    <?php 
    if (!empty($general_options['custom_css'])) {
        echo wp_strip_all_tags($general_options['custom_css']);
    }
    ?>
    </style>
    <?php
    echo ob_get_clean();
}
add_action('wp_head', 'ecocommerce_pro_custom_css', 99);

/**
 * Convert hex color to RGB
 */
function ecocommerce_pro_hex_to_rgb($hex) {
    $hex = ltrim($hex, '#');
    
    if (strlen($hex) === 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    
    return "$r, $g, $b";
}

/**
 * Add custom fonts from Google Fonts
 */
function ecocommerce_pro_custom_fonts() {
    $styling_options = get_option('ecocommerce_pro_styling_options', array());
    
    $fonts = array();
    
    if (!empty($styling_options['body_font']) && $styling_options['body_font'] !== 'inherit') {
        $fonts[] = $styling_options['body_font'];
    }
    
    if (!empty($styling_options['heading_font']) && $styling_options['heading_font'] !== 'inherit') {
        $fonts[] = $styling_options['heading_font'];
    }
    
    if (!empty($fonts)) {
        $fonts = array_unique($fonts);
        $font_families = array();
        
        foreach ($fonts as $font) {
            $font_families[] = str_replace(' ', '+', $font) . ':300,400,500,600,700';
        }
        
        $font_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $font_families) . '&display=swap';
        
        wp_enqueue_style('ecocommerce-pro-custom-fonts', $font_url, array(), null);
    }
}
add_action('wp_enqueue_scripts', 'ecocommerce_pro_custom_fonts', 5);

/**
 * Body classes based on options
 */
function ecocommerce_pro_custom_body_classes($classes) {
    $general_options = get_option('ecocommerce_pro_general_options', array());
    $header_options = get_option('ecocommerce_pro_header_options', array());
    
    // Layout type
    $layout_type = $general_options['layout_type'] ?? 'full-width';
    $classes[] = 'layout-' . $layout_type;
    
    // Sidebar position
    $sidebar_position = $general_options['sidebar_position'] ?? 'right';
    $classes[] = 'sidebar-' . $sidebar_position;
    
    // Header template
    $header_template = $header_options['template'] ?? 'default';
    $classes[] = 'header-template-' . $header_template;
    
    // Sticky header
    if (!empty($header_options['sticky'])) {
        $classes[] = 'sticky-header';
    }
    
    // Transparent header on home
    if (is_front_page() && !empty($header_options['transparent_home'])) {
        $classes[] = 'transparent-header-home';
    }
    
    return $classes;
}
add_filter('body_class', 'ecocommerce_pro_custom_body_classes');

/**
 * Add SEO meta tags
 */
function ecocommerce_pro_seo_meta_tags() {
    $general_options = get_option('ecocommerce_pro_general_options', array());
    
    // Open Graph tags
    if (!empty($general_options['og_image'])) {
        echo '<meta property="og:image" content="' . esc_url($general_options['og_image']) . '">' . "\n";
    }
    
    if (!empty($general_options['og_description'])) {
        echo '<meta property="og:description" content="' . esc_attr($general_options['og_description']) . '">' . "\n";
    }
    
    // Twitter Card
    if (!empty($general_options['twitter_card'])) {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    }
    
    // Site verification
    if (!empty($general_options['google_verification'])) {
        echo '<meta name="google-site-verification" content="' . esc_attr($general_options['google_verification']) . '">' . "\n";
    }
}
add_action('wp_head', 'ecocommerce_pro_seo_meta_tags');

/**
 * Add custom scripts to footer
 */
function ecocommerce_pro_custom_scripts() {
    $general_options = get_option('ecocommerce_pro_general_options', array());
    
    if (!empty($general_options['custom_js'])) {
        echo '<script>' . wp_kses_post($general_options['custom_js']) . '</script>';
    }
    
    // Google Analytics
    if (!empty($general_options['google_analytics'])) {
        ?>
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($general_options['google_analytics']); ?>"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($general_options['google_analytics']); ?>');
        </script>
        <?php
    }
}
add_action('wp_footer', 'ecocommerce_pro_custom_scripts', 99);

/**
 * Performance optimizations
 */
function ecocommerce_pro_performance_optimizations() {
    $general_options = get_option('ecocommerce_pro_general_options', array());
    
    // Lazy load images
    if (!empty($general_options['lazy_load_images'])) {
        add_filter('wp_get_attachment_image_attributes', 'ecocommerce_pro_add_lazy_load', 10, 2);
    }
    
    // Defer JavaScript
    if (!empty($general_options['defer_js'])) {
        add_filter('script_loader_tag', 'ecocommerce_pro_defer_scripts', 10, 2);
    }
    
    // Remove query strings
    if (!empty($general_options['remove_query_strings'])) {
        add_filter('script_loader_src', 'ecocommerce_pro_remove_query_strings', 10, 2);
        add_filter('style_loader_src', 'ecocommerce_pro_remove_query_strings', 10, 2);
    }
}
add_action('init', 'ecocommerce_pro_performance_optimizations');

/**
 * Add lazy loading to images
 */
function ecocommerce_pro_add_lazy_load($attr, $attachment) {
    $attr['loading'] = 'lazy';
    return $attr;
}

/**
 * Defer JavaScript loading
 */
function ecocommerce_pro_defer_scripts($tag, $handle) {
    $defer_scripts = array('ecocommerce-pro-main', 'jquery');
    
    if (in_array($handle, $defer_scripts)) {
        return $tag;
    }
    
    return str_replace(' src', ' defer src', $tag);
}

/**
 * Remove query strings from static resources
 */
function ecocommerce_pro_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

