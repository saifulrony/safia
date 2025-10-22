<?php
/**
 * EcoCommerce Pro - Page Builder Support
 * 
 * Adds compatibility for popular page builders:
 * - Elementor (Most Popular)
 * - Gutenberg (WordPress Block Editor)
 * - Beaver Builder
 * - Divi Builder
 * - WPBakery Page Builder
 * 
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/* ==========================================================================
   ELEMENTOR SUPPORT (Most Popular - Recommended)
   ========================================================================== */

/**
 * Add Elementor Support
 */
function ecocommerce_pro_elementor_support() {
    // Add theme support for Elementor
    add_theme_support('elementor');
    
    // Add support for Elementor Pro features
    add_theme_support('elementor-pro');
    
    // Register Elementor locations
    if (function_exists('elementor_theme_do_location')) {
        // Register header location
        add_theme_support('elementor-location-header');
        
        // Register footer location
        add_theme_support('elementor-location-footer');
        
        // Register single post location
        add_theme_support('elementor-location-single');
        
        // Register archive location
        add_theme_support('elementor-location-archive');
    }
}
add_action('after_setup_theme', 'ecocommerce_pro_elementor_support');

/**
 * Elementor Custom Breakpoints
 */
function ecocommerce_pro_elementor_breakpoints($breakpoints) {
    $breakpoints['mobile'] = 768;
    $breakpoints['tablet'] = 1024;
    return $breakpoints;
}
add_filter('elementor/breakpoints/get_breakpoints', 'ecocommerce_pro_elementor_breakpoints');

/**
 * Elementor Full Width Page Template
 */
function ecocommerce_pro_elementor_canvas_template($template) {
    if (is_page() && get_post_meta(get_the_ID(), '_wp_page_template', true) === 'elementor_canvas') {
        $template = locate_template('page-templates/elementor-canvas.php');
        if (!$template) {
            $template = ABSPATH . 'wp-content/themes/ecocommerce-pro/page-templates/elementor-canvas.php';
        }
    }
    return $template;
}
add_filter('template_include', 'ecocommerce_pro_elementor_canvas_template', 999);

/**
 * Register Elementor Widget Categories
 */
function ecocommerce_pro_elementor_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'ecocommerce-pro-widgets',
        array(
            'title' => __('EcoCommerce Pro', 'ecocommerce-pro'),
            'icon' => 'fa fa-shopping-cart',
        )
    );
}
add_action('elementor/elements/categories_registered', 'ecocommerce_pro_elementor_widget_categories');

/* ==========================================================================
   GUTENBERG (WordPress Block Editor) SUPPORT
   ========================================================================== */

/**
 * Gutenberg Block Editor Support
 */
function ecocommerce_pro_gutenberg_support() {
    // Add support for wide and full aligned images
    add_theme_support('align-wide');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for custom line height
    add_theme_support('custom-line-height');
    
    // Add support for custom units
    add_theme_support('custom-units');
    
    // Add support for custom spacing
    add_theme_support('custom-spacing');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Enqueue editor styles
    add_editor_style('assets/css/editor-style.css');
    
    // Add support for block templates
    add_theme_support('block-templates');
    
    // Add support for block template parts
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'ecocommerce_pro_gutenberg_support');

/**
 * Gutenberg Color Palette
 */
function ecocommerce_pro_gutenberg_color_palette() {
    $styling_options = get_option('ecocommerce_pro_styling_options', array());
    
    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('Primary', 'ecocommerce-pro'),
            'slug' => 'primary',
            'color' => $styling_options['primary_color'] ?? '#2563eb',
        ),
        array(
            'name' => __('Secondary', 'ecocommerce-pro'),
            'slug' => 'secondary',
            'color' => $styling_options['secondary_color'] ?? '#10b981',
        ),
        array(
            'name' => __('Accent', 'ecocommerce-pro'),
            'slug' => 'accent',
            'color' => $styling_options['accent_color'] ?? '#f59e0b',
        ),
        array(
            'name' => __('Text', 'ecocommerce-pro'),
            'slug' => 'text',
            'color' => $styling_options['text_color'] ?? '#333333',
        ),
        array(
            'name' => __('Heading', 'ecocommerce-pro'),
            'slug' => 'heading',
            'color' => $styling_options['heading_color'] ?? '#111111',
        ),
        array(
            'name' => __('White', 'ecocommerce-pro'),
            'slug' => 'white',
            'color' => '#ffffff',
        ),
        array(
            'name' => __('Black', 'ecocommerce-pro'),
            'slug' => 'black',
            'color' => '#000000',
        ),
    ));
}
add_action('after_setup_theme', 'ecocommerce_pro_gutenberg_color_palette');

/**
 * Gutenberg Font Sizes
 */
function ecocommerce_pro_gutenberg_font_sizes() {
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'ecocommerce-pro'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => __('Normal', 'ecocommerce-pro'),
            'size' => 16,
            'slug' => 'normal'
        ),
        array(
            'name' => __('Medium', 'ecocommerce-pro'),
            'size' => 20,
            'slug' => 'medium'
        ),
        array(
            'name' => __('Large', 'ecocommerce-pro'),
            'size' => 24,
            'slug' => 'large'
        ),
        array(
            'name' => __('Extra Large', 'ecocommerce-pro'),
            'size' => 32,
            'slug' => 'extra-large'
        ),
    ));
}
add_action('after_setup_theme', 'ecocommerce_pro_gutenberg_font_sizes');

/* ==========================================================================
   BEAVER BUILDER SUPPORT
   ========================================================================== */

/**
 * Beaver Builder Support
 */
function ecocommerce_pro_beaver_builder_support() {
    // Add Beaver Builder theme support
    add_theme_support('fl-builder');
    
    // Enable Beaver Builder on all post types
    add_theme_support('fl-builder-all-post-types');
}
add_action('after_setup_theme', 'ecocommerce_pro_beaver_builder_support');

/**
 * Beaver Builder Full Width Template
 */
function ecocommerce_pro_beaver_builder_template_class($classes) {
    if (class_exists('FLBuilderModel') && FLBuilderModel::is_builder_enabled()) {
        $classes[] = 'fl-builder';
        
        // Check for full width template
        $template = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if ($template === 'tpl-fullwidth.php' || $template === 'page-templates/full-width.php') {
            $classes[] = 'fl-builder-full-width';
        }
    }
    return $classes;
}
add_filter('body_class', 'ecocommerce_pro_beaver_builder_template_class');

/* ==========================================================================
   DIVI BUILDER SUPPORT
   ========================================================================== */

/**
 * Divi Builder Support
 */
function ecocommerce_pro_divi_support() {
    // Enable Divi Builder
    if (class_exists('ET_Builder_Plugin')) {
        // Add Divi compatibility class
        add_filter('body_class', function($classes) {
            $classes[] = 'et-builder-support';
            return $classes;
        });
    }
}
add_action('after_setup_theme', 'ecocommerce_pro_divi_support');

/* ==========================================================================
   WPBAKERY PAGE BUILDER SUPPORT
   ========================================================================== */

/**
 * WPBakery Page Builder Support
 */
function ecocommerce_pro_wpbakery_support() {
    if (class_exists('Vc_Manager')) {
        // Add WPBakery compatibility
        add_filter('body_class', function($classes) {
            if (get_post_meta(get_the_ID(), '_wpb_vc_js_status', true) === 'true') {
                $classes[] = 'vc_row-has-fill wpb-js-composer';
            }
            return $classes;
        });
    }
}
add_action('after_setup_theme', 'ecocommerce_pro_wpbakery_support');

/* ==========================================================================
   FULL WIDTH PAGE TEMPLATE SUPPORT
   ========================================================================== */

/**
 * Detect if current page uses a page builder
 */
function ecocommerce_pro_is_page_builder_active() {
    if (!is_singular()) {
        return false;
    }
    
    $post_id = get_the_ID();
    
    // Check Elementor
    if (class_exists('\Elementor\Plugin')) {
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)) {
            return true;
        }
    }
    
    // Check Beaver Builder
    if (class_exists('FLBuilderModel')) {
        if (FLBuilderModel::is_builder_enabled($post_id)) {
            return true;
        }
    }
    
    // Check Divi
    if (function_exists('et_pb_is_pagebuilder_used')) {
        if (et_pb_is_pagebuilder_used($post_id)) {
            return true;
        }
    }
    
    // Check WPBakery
    if (get_post_meta($post_id, '_wpb_vc_js_status', true) === 'true') {
        return true;
    }
    
    // Check Gutenberg blocks
    if (has_blocks($post_id)) {
        return true;
    }
    
    return false;
}

/**
 * Add body class for page builder pages
 */
function ecocommerce_pro_page_builder_body_class($classes) {
    if (ecocommerce_pro_is_page_builder_active()) {
        $classes[] = 'page-builder-active';
        $classes[] = 'full-width-content';
    }
    
    return $classes;
}
add_filter('body_class', 'ecocommerce_pro_page_builder_body_class');

/**
 * Remove sidebar for page builder pages
 */
function ecocommerce_pro_page_builder_remove_sidebar($display) {
    if (ecocommerce_pro_is_page_builder_active()) {
        return false;
    }
    return $display;
}
add_filter('is_active_sidebar', 'ecocommerce_pro_page_builder_remove_sidebar');

/* ==========================================================================
   REGISTER PAGE TEMPLATES
   ========================================================================== */

/**
 * Register page templates for builders
 */
function ecocommerce_pro_register_page_templates($templates) {
    $templates['page-templates/full-width.php'] = __('Full Width (Page Builders)', 'ecocommerce-pro');
    $templates['page-templates/elementor-canvas.php'] = __('Elementor Canvas', 'ecocommerce-pro');
    $templates['page-templates/blank.php'] = __('Blank Template (Builders)', 'ecocommerce-pro');
    
    return $templates;
}
add_filter('theme_page_templates', 'ecocommerce_pro_register_page_templates');

/* ==========================================================================
   CUSTOM CSS FOR PAGE BUILDERS
   ========================================================================== */

/**
 * Add custom CSS for page builder compatibility
 */
function ecocommerce_pro_page_builder_css() {
    ?>
    <style id="ecocommerce-page-builder-css">
        /* Full Width Template */
        .page-builder-active .site-content,
        .full-width-content .site-content {
            max-width: 100%;
            padding: 0;
        }
        
        .page-builder-active .content-area,
        .full-width-content .content-area {
            width: 100%;
            max-width: 100%;
        }
        
        /* Elementor Compatibility */
        .elementor-page .site-content {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }
        
        .elementor-default .site-main {
            padding: 0;
        }
        
        /* Beaver Builder Compatibility */
        .fl-builder .site-content,
        .fl-builder-full-width .site-content {
            max-width: 100%;
            padding: 0;
        }
        
        /* Divi Compatibility */
        .et_pb_section {
            padding: 0;
        }
        
        /* WPBakery Compatibility */
        .vc_row-has-fill {
            margin-left: 0;
            margin-right: 0;
        }
        
        /* Gutenberg Full Width */
        .alignfull {
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            max-width: 100vw;
            width: 100vw;
        }
        
        .alignwide {
            margin-left: calc(25% - 25vw);
            margin-right: calc(25% - 25vw);
            max-width: 50vw;
            width: auto;
        }
    </style>
    <?php
}
add_action('wp_head', 'ecocommerce_pro_page_builder_css', 100);

/* ==========================================================================
   ENQUEUE BUILDER-SPECIFIC STYLES
   ========================================================================== */

/**
 * Enqueue page builder styles
 */
function ecocommerce_pro_enqueue_builder_styles() {
    // Elementor compatibility styles
    if (defined('ELEMENTOR_VERSION')) {
        wp_enqueue_style(
            'ecocommerce-pro-elementor',
            get_template_directory_uri() . '/assets/css/elementor-compatibility.css',
            array(),
            '1.0.0'
        );
    }
    
    // Beaver Builder compatibility styles
    if (class_exists('FLBuilder')) {
        wp_enqueue_style(
            'ecocommerce-pro-beaver',
            get_template_directory_uri() . '/assets/css/beaver-compatibility.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'ecocommerce_pro_enqueue_builder_styles', 20);

/* ==========================================================================
   ADMIN NOTICE FOR PAGE BUILDERS
   ========================================================================== */

/**
 * Show admin notice about supported page builders
 */
function ecocommerce_pro_page_builder_notice() {
    $screen = get_current_screen();
    
    if ($screen && $screen->id === 'themes') {
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <strong><?php _e('EcoCommerce Pro - Page Builder Support', 'ecocommerce-pro'); ?></strong><br>
                <?php _e('This theme supports the following page builders:', 'ecocommerce-pro'); ?>
            </p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><strong>Elementor</strong> <?php echo defined('ELEMENTOR_VERSION') ? '<span style="color: green;">✓ Installed</span>' : '<a href="' . admin_url('plugin-install.php?s=elementor&tab=search') . '">Install Now</a>'; ?></li>
                <li><strong>Gutenberg</strong> (WordPress Block Editor) <span style="color: green;">✓ Built-in</span></li>
                <li><strong>Beaver Builder</strong> <?php echo class_exists('FLBuilder') ? '<span style="color: green;">✓ Installed</span>' : '<a href="' . admin_url('plugin-install.php?s=beaver+builder&tab=search') . '">Install Now</a>'; ?></li>
                <li><strong>Divi Builder</strong> - Compatible</li>
                <li><strong>WPBakery</strong> - Compatible</li>
            </ul>
        </div>
        <?php
    }
}
add_action('admin_notices', 'ecocommerce_pro_page_builder_notice');

/* ==========================================================================
   THEME OPTIONS - PAGE BUILDER SETTINGS
   ========================================================================== */

/**
 * Add Page Builder Settings to Theme Options
 */
function ecocommerce_pro_add_builder_settings_menu() {
    add_submenu_page(
        'ecocommerce-pro-options',
        __('Page Builder Settings', 'ecocommerce-pro'),
        __('Page Builders', 'ecocommerce-pro'),
        'manage_options',
        'ecocommerce-pro-builders',
        'ecocommerce_pro_render_builders_page'
    );
}
add_action('admin_menu', 'ecocommerce_pro_add_builder_settings_menu', 20);

/**
 * AJAX Handler - Install Elementor
 */
function ecocommerce_pro_ajax_install_elementor() {
    // Security check
    check_ajax_referer('ecocommerce_install_elementor', 'nonce');
    
    if (!current_user_can('install_plugins')) {
        wp_send_json_error(array('message' => 'You do not have permission to install plugins.'));
    }
    
    // Include required files
    if (!function_exists('plugins_api')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
    }
    if (!class_exists('Plugin_Upgrader')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    }
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/misc.php');
    
    // Get plugin info
    $api = plugins_api('plugin_information', array(
        'slug' => 'elementor',
        'fields' => array('sections' => false)
    ));
    
    if (is_wp_error($api)) {
        wp_send_json_error(array('message' => 'Could not fetch plugin information: ' . $api->get_error_message()));
    }
    
    // Install plugin
    $skin = new WP_Ajax_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader($skin);
    $result = $upgrader->install($api->download_link);
    
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => 'Installation failed: ' . $result->get_error_message()));
    }
    
    // Activate plugin
    $activate = activate_plugin('elementor/elementor.php');
    
    if (is_wp_error($activate)) {
        wp_send_json_error(array('message' => 'Activation failed: ' . $activate->get_error_message()));
    }
    
    wp_send_json_success(array(
        'message' => 'Elementor installed and activated successfully!',
        'version' => defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'Latest'
    ));
}
add_action('wp_ajax_ecocommerce_install_elementor', 'ecocommerce_pro_ajax_install_elementor');

/**
 * Render Page Builder Settings Page
 */
function ecocommerce_pro_render_builders_page() {
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="ecocommerce-admin-container">
            <div class="ecocommerce-admin-main">
                <div class="ecocommerce-card">
                    <h2>🎨 Supported Page Builders</h2>
                    <p>EcoCommerce Pro is fully compatible with the following page builders. Install any of them to create stunning pages with drag-and-drop!</p>
                    
                    <table class="widefat" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th>Page Builder</th>
                                <th>Status</th>
                                <th>Recommended For</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Elementor</strong> ⭐ Most Popular</td>
                                <td>
                                    <?php if (defined('ELEMENTOR_VERSION')): ?>
                                        <span style="color: green; font-weight: bold;">✓ Installed (v<?php echo ELEMENTOR_VERSION; ?>)</span>
                                    <?php else: ?>
                                        <span style="color: orange;">Not Installed</span>
                                    <?php endif; ?>
                                </td>
                                <td>Beginners & Professionals</td>
                                <td>
                                    <?php if (!defined('ELEMENTOR_VERSION')): ?>
                                        <button type="button" id="install-elementor-btn" class="button button-primary">
                                            <span class="dashicons dashicons-download"></span> Install Elementor
                                        </button>
                                        <span id="elementor-install-status" style="margin-left: 10px;"></span>
                                    <?php else: ?>
                                        <span style="color: green; font-weight: 600;">✓ Ready to use (v<?php echo ELEMENTOR_VERSION; ?>)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td><strong>Gutenberg</strong> (WordPress Blocks)</td>
                                <td><span style="color: green; font-weight: bold;">✓ Built-in</span></td>
                                <td>WordPress Native</td>
                                <td><span style="color: green;">✓ Always available</span></td>
                            </tr>
                            
                            <tr>
                                <td><strong>Beaver Builder</strong></td>
                                <td>
                                    <?php if (class_exists('FLBuilder')): ?>
                                        <span style="color: green; font-weight: bold;">✓ Installed</span>
                                    <?php else: ?>
                                        <span style="color: orange;">Not Installed</span>
                                    <?php endif; ?>
                                </td>
                                <td>Professional Sites</td>
                                <td>
                                    <?php if (!class_exists('FLBuilder')): ?>
                                        <a href="<?php echo admin_url('plugin-install.php?s=beaver+builder&tab=search&type=term'); ?>" class="button">Install Beaver Builder</a>
                                    <?php else: ?>
                                        <span style="color: green;">✓ Ready to use</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td><strong>Divi Builder</strong></td>
                                <td>
                                    <?php if (class_exists('ET_Builder_Plugin')): ?>
                                        <span style="color: green; font-weight: bold;">✓ Installed</span>
                                    <?php else: ?>
                                        <span style="color: orange;">Not Installed</span>
                                    <?php endif; ?>
                                </td>
                                <td>Visual Design</td>
                                <td>
                                    <?php if (!class_exists('ET_Builder_Plugin')): ?>
                                        <a href="https://www.elegantthemes.com/gallery/divi/" target="_blank" class="button">Get Divi Builder</a>
                                    <?php else: ?>
                                        <span style="color: green;">✓ Ready to use</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td><strong>WPBakery Page Builder</strong></td>
                                <td>
                                    <?php if (class_exists('Vc_Manager')): ?>
                                        <span style="color: green; font-weight: bold;">✓ Installed</span>
                                    <?php else: ?>
                                        <span style="color: orange;">Not Installed</span>
                                    <?php endif; ?>
                                </td>
                                <td>Legacy Projects</td>
                                <td>
                                    <?php if (!class_exists('Vc_Manager')): ?>
                                        <a href="https://wpbakery.com/" target="_blank" class="button">Get WPBakery</a>
                                    <?php else: ?>
                                        <span style="color: green;">✓ Ready to use</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="ecocommerce-card">
                    <h2>📋 Page Builder Features</h2>
                    
                    <h3>✅ Fully Supported Features:</h3>
                    <ul class="builder-features">
                        <li>✓ <strong>Full Width Templates</strong> - Stretch content edge-to-edge</li>
                        <li>✓ <strong>Canvas/Blank Templates</strong> - No header/footer for landing pages</li>
                        <li>✓ <strong>Custom Breakpoints</strong> - Responsive design at any screen size</li>
                        <li>✓ <strong>Theme Colors Integration</strong> - Your theme colors available in builders</li>
                        <li>✓ <strong>Custom Fonts Support</strong> - Typography settings work with builders</li>
                        <li>✓ <strong>Widget Areas</strong> - All widget areas accessible</li>
                        <li>✓ <strong>WooCommerce Integration</strong> - Product layouts work perfectly</li>
                        <li>✓ <strong>Header/Footer Builder</strong> - Build custom headers (Elementor Pro)</li>
                    </ul>
                </div>
                
                <div class="ecocommerce-card">
                    <h2>🚀 Recommended: Elementor</h2>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                        <div>
                            <h4>Why Elementor?</h4>
                            <ul>
                                <li>✓ Most popular (5M+ active installs)</li>
                                <li>✓ Drag-and-drop interface</li>
                                <li>✓ 100+ widgets included</li>
                                <li>✓ Live front-end editing</li>
                                <li>✓ Mobile responsive editing</li>
                                <li>✓ Free version very powerful</li>
                                <li>✓ Great documentation</li>
                                <li>✓ Regular updates</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4>Elementor Pro Features:</h4>
                            <ul>
                                <li>✓ Theme Builder (header/footer)</li>
                                <li>✓ WooCommerce Builder</li>
                                <li>✓ Popup Builder</li>
                                <li>✓ Form Builder</li>
                                <li>✓ Advanced widgets (300+)</li>
                                <li>✓ Dynamic content</li>
                                <li>✓ Global widgets</li>
                                <li>✓ Custom CSS per element</li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if (!defined('ELEMENTOR_VERSION')): ?>
                        <p style="margin-top: 20px;">
                            <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>" class="button button-primary button-hero">
                                Install Elementor Now (Free)
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="ecocommerce-admin-sidebar">
                <div class="ecocommerce-card">
                    <h3>💡 Quick Comparison</h3>
                    
                    <h4>🥇 Elementor</h4>
                    <p><strong>Best for:</strong> Everyone<br>
                    <strong>Difficulty:</strong> ⭐⭐ Easy<br>
                    <strong>Price:</strong> Free + Pro<br>
                    <strong>Rating:</strong> ⭐⭐⭐⭐⭐</p>
                    
                    <h4>🥈 Gutenberg</h4>
                    <p><strong>Best for:</strong> WordPress purists<br>
                    <strong>Difficulty:</strong> ⭐ Very Easy<br>
                    <strong>Price:</strong> Free (built-in)<br>
                    <strong>Rating:</strong> ⭐⭐⭐⭐</p>
                    
                    <h4>🥉 Beaver Builder</h4>
                    <p><strong>Best for:</strong> Developers<br>
                    <strong>Difficulty:</strong> ⭐⭐⭐ Medium<br>
                    <strong>Price:</strong> Premium<br>
                    <strong>Rating:</strong> ⭐⭐⭐⭐⭐</p>
                </div>
                
                <div class="ecocommerce-card">
                    <h3>📚 Resources</h3>
                    <ul>
                        <li><a href="https://elementor.com/academy/" target="_blank">Elementor Academy</a></li>
                        <li><a href="https://wordpress.org/support/article/wordpress-editor/" target="_blank">Gutenberg Guide</a></li>
                        <li><a href="https://www.wpbeaverbuilder.com/kb/" target="_blank">Beaver Builder Docs</a></li>
                    </ul>
                </div>
                
                <div class="ecocommerce-card">
                    <h3>🎯 Getting Started</h3>
                    <ol style="line-height: 1.8;">
                        <li>Choose a page builder</li>
                        <li>Install & activate it</li>
                        <li>Create a new page</li>
                        <li>Click "Edit with [Builder]"</li>
                        <li>Start designing!</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <style>
        .builder-features {
            list-style: none;
            padding: 0;
        }
        
        .builder-features li {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #2563eb;
        }
        
        .widefat td, .widefat th {
            padding: 12px;
        }
        </style>
        
        <?php if (!defined('ELEMENTOR_VERSION')): ?>
        <script>
        jQuery(document).ready(function($) {
            $('#install-elementor-btn').on('click', function() {
                var $btn = $(this);
                var $status = $('#elementor-install-status');
                
                // Disable button
                $btn.prop('disabled', true);
                $btn.html('<span class="dashicons dashicons-update dashicons-spin"></span> Installing...');
                $status.html('<span style="color: #f59e0b;">⏳ Please wait...</span>');
                
                // Install via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ecocommerce_install_elementor',
                        nonce: '<?php echo wp_create_nonce('ecocommerce_install_elementor'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $status.html('<span style="color: #10b981; font-weight: 600;">✓ ' + response.data.message + '</span>');
                            $btn.html('<span class="dashicons dashicons-yes"></span> Installed!');
                            
                            // Show success message
                            var successMsg = $('<div class="notice notice-success" style="margin: 20px 0; padding: 15px;"><p><strong>✓ Success!</strong> Elementor has been installed and activated. <a href="' + '<?php echo admin_url('post-new.php?post_type=page'); ?>' + '">Create a new page</a> to start building!</p></div>');
                            $('.ecocommerce-card').first().prepend(successMsg);
                            
                            // Reload page after 3 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        } else {
                            $status.html('<span style="color: #ef4444;">✗ ' + response.data.message + '</span>');
                            $btn.prop('disabled', false).html('<span class="dashicons dashicons-download"></span> Try Again');
                        }
                    },
                    error: function(xhr, status, error) {
                        $status.html('<span style="color: #ef4444;">✗ Installation failed. Please try manual installation.</span>');
                        $btn.prop('disabled', false).html('<span class="dashicons dashicons-download"></span> Try Again');
                        
                        // Show manual installation link
                        var errorMsg = $('<div class="notice notice-error" style="margin: 20px 0; padding: 15px;"><p><strong>Installation failed.</strong> Please try <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>">manual installation</a>.</p></div>');
                        $('.ecocommerce-card').first().prepend(errorMsg);
                    }
                });
            });
        });
        </script>
        <?php endif; ?>
    </div>
    <?php
}

