<?php
/**
 * EcoCommerce Pro Customizer
 *
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ecocommerce_pro_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'ecocommerce_pro_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'ecocommerce_pro_customize_partial_blogdescription',
        ));
    }

    // Add theme options panel
    $wp_customize->add_panel('ecocommerce_pro_theme_options', array(
        'title'    => __('Theme Options', 'ecocommerce-pro'),
        'priority' => 30,
    ));

    // Colors section
    $wp_customize->add_section('ecocommerce_pro_colors', array(
        'title'    => __('Colors', 'ecocommerce-pro'),
        'panel'    => 'ecocommerce_pro_theme_options',
        'priority' => 10,
    ));

    // Primary color
    $wp_customize->add_setting('ecocommerce_pro_primary_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ecocommerce_pro_primary_color', array(
        'label'    => __('Primary Color', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_colors',
        'settings' => 'ecocommerce_pro_primary_color',
    )));

    // Secondary color
    $wp_customize->add_setting('ecocommerce_pro_secondary_color', array(
        'default'           => '#28a745',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ecocommerce_pro_secondary_color', array(
        'label'    => __('Secondary Color', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_colors',
        'settings' => 'ecocommerce_pro_secondary_color',
    )));

    // Header background color
    $wp_customize->add_setting('ecocommerce_pro_header_bg', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ecocommerce_pro_header_bg', array(
        'label'    => __('Header Background Color', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_colors',
        'settings' => 'ecocommerce_pro_header_bg',
    )));

    // Footer background color
    $wp_customize->add_setting('ecocommerce_pro_footer_bg', array(
        'default'           => '#2c3e50',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ecocommerce_pro_footer_bg', array(
        'label'    => __('Footer Background Color', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_colors',
        'settings' => 'ecocommerce_pro_footer_bg',
    )));

    // Layout section
    $wp_customize->add_section('ecocommerce_pro_layout', array(
        'title'    => __('Layout', 'ecocommerce-pro'),
        'panel'    => 'ecocommerce_pro_theme_options',
        'priority' => 20,
    ));

    // Header layout
    $wp_customize->add_setting('ecocommerce_pro_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'ecocommerce_pro_sanitize_select',
    ));

    $wp_customize->add_control('ecocommerce_pro_header_layout', array(
        'label'    => __('Header Layout', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_layout',
        'type'     => 'select',
        'choices'  => array(
            'default' => __('Default', 'ecocommerce-pro'),
            'centered' => __('Centered', 'ecocommerce-pro'),
            'minimal' => __('Minimal', 'ecocommerce-pro'),
        ),
    ));

    // Footer layout
    $wp_customize->add_setting('ecocommerce_pro_footer_layout', array(
        'default'           => '4-columns',
        'sanitize_callback' => 'ecocommerce_pro_sanitize_select',
    ));

    $wp_customize->add_control('ecocommerce_pro_footer_layout', array(
        'label'    => __('Footer Layout', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_layout',
        'type'     => 'select',
        'choices'  => array(
            '2-columns' => __('2 Columns', 'ecocommerce-pro'),
            '3-columns' => __('3 Columns', 'ecocommerce-pro'),
            '4-columns' => __('4 Columns', 'ecocommerce-pro'),
        ),
    ));

    // Social media section
    $wp_customize->add_section('ecocommerce_pro_social', array(
        'title'    => __('Social Media', 'ecocommerce-pro'),
        'panel'    => 'ecocommerce_pro_theme_options',
        'priority' => 30,
    ));

    // Facebook URL
    $wp_customize->add_setting('ecocommerce_pro_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ecocommerce_pro_facebook', array(
        'label'    => __('Facebook URL', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_social',
        'type'     => 'url',
    ));

    // Twitter URL
    $wp_customize->add_setting('ecocommerce_pro_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ecocommerce_pro_twitter', array(
        'label'    => __('Twitter URL', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_social',
        'type'     => 'url',
    ));

    // Instagram URL
    $wp_customize->add_setting('ecocommerce_pro_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ecocommerce_pro_instagram', array(
        'label'    => __('Instagram URL', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_social',
        'type'     => 'url',
    ));

    // YouTube URL
    $wp_customize->add_setting('ecocommerce_pro_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ecocommerce_pro_youtube', array(
        'label'    => __('YouTube URL', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_social',
        'type'     => 'url',
    ));

    // LinkedIn URL
    $wp_customize->add_setting('ecocommerce_pro_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ecocommerce_pro_linkedin', array(
        'label'    => __('LinkedIn URL', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_social',
        'type'     => 'url',
    ));

    // Typography section
    $wp_customize->add_section('ecocommerce_pro_typography', array(
        'title'    => __('Typography', 'ecocommerce-pro'),
        'panel'    => 'ecocommerce_pro_theme_options',
        'priority' => 40,
    ));

    // Body font size
    $wp_customize->add_setting('ecocommerce_pro_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('ecocommerce_pro_body_font_size', array(
        'label'    => __('Body Font Size (px)', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_typography',
        'type'     => 'number',
        'input_attrs' => array(
            'min' => 12,
            'max' => 24,
        ),
    ));

    // Headings font family
    $wp_customize->add_setting('ecocommerce_pro_headings_font', array(
        'default'           => 'default',
        'sanitize_callback' => 'ecocommerce_pro_sanitize_select',
    ));

    $wp_customize->add_control('ecocommerce_pro_headings_font', array(
        'label'    => __('Headings Font Family', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_typography',
        'type'     => 'select',
        'choices'  => array(
            'default' => __('Default (System Font)', 'ecocommerce-pro'),
            'serif' => __('Serif', 'ecocommerce-pro'),
            'sans-serif' => __('Sans Serif', 'ecocommerce-pro'),
            'monospace' => __('Monospace', 'ecocommerce-pro'),
        ),
    ));

    // Advanced section
    $wp_customize->add_section('ecocommerce_pro_advanced', array(
        'title'    => __('Advanced', 'ecocommerce-pro'),
        'panel'    => 'ecocommerce_pro_theme_options',
        'priority' => 50,
    ));

    // Custom CSS
    $wp_customize->add_setting('ecocommerce_pro_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('ecocommerce_pro_custom_css', array(
        'label'    => __('Custom CSS', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_advanced',
        'type'     => 'textarea',
    ));

    // Custom JavaScript
    $wp_customize->add_setting('ecocommerce_pro_custom_js', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('ecocommerce_pro_custom_js', array(
        'label'    => __('Custom JavaScript', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_advanced',
        'type'     => 'textarea',
    ));

    // Meta keywords
    $wp_customize->add_setting('ecocommerce_pro_meta_keywords', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('ecocommerce_pro_meta_keywords', array(
        'label'    => __('Meta Keywords', 'ecocommerce-pro'),
        'section'  => 'ecocommerce_pro_advanced',
        'type'     => 'text',
    ));

    // WooCommerce section
    if (class_exists('WooCommerce')) {
        $wp_customize->add_section('ecocommerce_pro_woocommerce', array(
            'title'    => __('WooCommerce', 'ecocommerce-pro'),
            'panel'    => 'ecocommerce_pro_theme_options',
            'priority' => 60,
        ));

        // Products per page
        $wp_customize->add_setting('ecocommerce_pro_products_per_page', array(
            'default'           => '12',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('ecocommerce_pro_products_per_page', array(
            'label'    => __('Products Per Page', 'ecocommerce-pro'),
            'section'  => 'ecocommerce_pro_woocommerce',
            'type'     => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 50,
            ),
        ));

        // Products per row
        $wp_customize->add_setting('ecocommerce_pro_products_per_row', array(
            'default'           => '3',
            'sanitize_callback' => 'ecocommerce_pro_sanitize_select',
        ));

        $wp_customize->add_control('ecocommerce_pro_products_per_row', array(
            'label'    => __('Products Per Row', 'ecocommerce-pro'),
            'section'  => 'ecocommerce_pro_woocommerce',
            'type'     => 'select',
            'choices'  => array(
                '2' => __('2 Products', 'ecocommerce-pro'),
                '3' => __('3 Products', 'ecocommerce-pro'),
                '4' => __('4 Products', 'ecocommerce-pro'),
            ),
        ));

        // Show product ratings
        $wp_customize->add_setting('ecocommerce_pro_show_ratings', array(
            'default'           => true,
            'sanitize_callback' => 'ecocommerce_pro_sanitize_checkbox',
        ));

        $wp_customize->add_control('ecocommerce_pro_show_ratings', array(
            'label'    => __('Show Product Ratings', 'ecocommerce-pro'),
            'section'  => 'ecocommerce_pro_woocommerce',
            'type'     => 'checkbox',
        ));
    }
}
add_action('customize_register', 'ecocommerce_pro_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function ecocommerce_pro_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function ecocommerce_pro_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ecocommerce_pro_customize_preview_js() {
    wp_enqueue_script('ecocommerce-pro-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), '1.0.0', true);
}
add_action('customize_preview_init', 'ecocommerce_pro_customize_preview_js');

/**
 * Sanitize checkbox values
 */
function ecocommerce_pro_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select values
 */
function ecocommerce_pro_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize textarea values
 */
function ecocommerce_pro_sanitize_textarea($input) {
    return wp_kses_post($input);
}

/**
 * Sanitize URL values
 */
function ecocommerce_pro_sanitize_url($input) {
    return esc_url_raw($input);
}
