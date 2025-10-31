<?php
/**
 * Plugin Name: ProBuilder - Safia Edition
 * Plugin URI: https://safia.com/probuilder
 * Description: Professional Elementor-style drag & drop page builder with 90+ premium widgets, advanced controls, and responsive design modes. Build stunning pages visually! Licensed by Safia Technologies.
 * Version: 3.0.0
 * Author: Safia Technologies
 * Author URI: https://safia.com
 * License: Proprietary License - Safia Technologies
 * License URI: https://safia.com/license
 * Text Domain: probuilder
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * 
 * ProBuilder - Safia Edition
 * Copyright (c) 2025 Safia Technologies. All rights reserved.
 * 
 * This plugin is licensed, not sold. Unauthorized copying, distribution,
 * modification, or use is strictly prohibited and violates copyright law.
 * 
 * SECURITY FEATURES:
 * - License Key Activation Required
 * - Code Integrity Verification
 * - Domain-Locked Licensing
 * - Anti-Tampering Protection
 * - Encrypted Communication
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Plugin version
define('PROBUILDER_VERSION', '1.0.0');
define('PROBUILDER_PATH', plugin_dir_path(__FILE__));
define('PROBUILDER_URL', plugin_dir_url(__FILE__));
define('PROBUILDER_FILE', __FILE__);

/**
 * Main ProBuilder Class
 */
final class ProBuilder {
    
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Security & License (Load First!)
        require_once PROBUILDER_PATH . 'includes/class-license-manager.php';
        require_once PROBUILDER_PATH . 'includes/class-license-page.php';
        
        // Core classes
        require_once PROBUILDER_PATH . 'includes/class-error-handler.php';
        require_once PROBUILDER_PATH . 'includes/class-backup-manager.php';
        require_once PROBUILDER_PATH . 'includes/class-cache.php';
        require_once PROBUILDER_PATH . 'includes/class-assets-optimizer.php';
        require_once PROBUILDER_PATH . 'includes/class-base-widget.php';
        require_once PROBUILDER_PATH . 'includes/class-widgets-manager.php';
        require_once PROBUILDER_PATH . 'includes/class-editor.php';
        require_once PROBUILDER_PATH . 'includes/class-frontend.php';
        require_once PROBUILDER_PATH . 'includes/class-ajax.php';
        require_once PROBUILDER_PATH . 'includes/class-templates.php';
        require_once PROBUILDER_PATH . 'includes/class-templates-library.php';
        require_once PROBUILDER_PATH . 'includes/class-theme-integration.php';
        require_once PROBUILDER_PATH . 'includes/class-uninstall-feedback.php';
        
        // Advanced Features
        require_once PROBUILDER_PATH . 'includes/class-navigator.php';
        require_once PROBUILDER_PATH . 'includes/class-history-panel.php';
        require_once PROBUILDER_PATH . 'includes/class-global-styles.php';
        require_once PROBUILDER_PATH . 'includes/class-popup-builder.php';
        require_once PROBUILDER_PATH . 'includes/class-theme-builder.php';
        require_once PROBUILDER_PATH . 'includes/class-dynamic-content.php';
        require_once PROBUILDER_PATH . 'includes/class-global-widgets.php';
        require_once PROBUILDER_PATH . 'includes/class-role-manager.php';
        require_once PROBUILDER_PATH . 'includes/class-form-integrations.php';
        require_once PROBUILDER_PATH . 'includes/class-shape-dividers.php';
        require_once PROBUILDER_PATH . 'includes/class-motion-effects.php';
        require_once PROBUILDER_PATH . 'includes/class-custom-fonts.php';
        require_once PROBUILDER_PATH . 'includes/class-custom-breakpoints.php';
        require_once PROBUILDER_PATH . 'includes/class-enhanced-animations.php';
        
        // Auto-load all widget files
        $this->load_widgets();
    }
    
    /**
     * Auto-load all widget files from widgets directory
     */
    private function load_widgets() {
        $widgets_dir = PROBUILDER_PATH . 'widgets/';
        
        if (!is_dir($widgets_dir)) {
            error_log('ProBuilder: Widgets directory not found: ' . $widgets_dir);
            return;
        }
        
        // Get all PHP files in widgets directory
        $widget_files = glob($widgets_dir . '*.php');
        
        if (empty($widget_files)) {
            error_log('ProBuilder: No widget files found in ' . $widgets_dir);
            return;
        }
        
        $loaded_count = 0;
        $failed_widgets = [];
        
        foreach ($widget_files as $widget_file) {
            try {
                require_once $widget_file;
                $loaded_count++;
            } catch (Exception $e) {
                $failed_widgets[] = basename($widget_file) . ': ' . $e->getMessage();
                error_log('ProBuilder: Failed to load widget ' . basename($widget_file) . ': ' . $e->getMessage());
            }
        }
        
        error_log("ProBuilder: Loaded $loaded_count widget files");
        
        if (!empty($failed_widgets)) {
            error_log('ProBuilder: Failed widgets: ' . implode(', ', $failed_widgets));
        }
    }
    
    /**
     * Old includes method kept for reference - now using auto-loader
     */
    private function includes_old() {
        // Layout Widgets
        require_once PROBUILDER_PATH . 'widgets/container.php';
        require_once PROBUILDER_PATH . 'widgets/flexbox.php';
        require_once PROBUILDER_PATH . 'widgets/grid-layout.php';
        
        // Basic Widgets
        require_once PROBUILDER_PATH . 'widgets/heading.php';
        require_once PROBUILDER_PATH . 'widgets/text.php';
        require_once PROBUILDER_PATH . 'widgets/button.php';
        require_once PROBUILDER_PATH . 'widgets/image.php';
        require_once PROBUILDER_PATH . 'widgets/divider.php';
        require_once PROBUILDER_PATH . 'widgets/spacer.php';
        require_once PROBUILDER_PATH . 'widgets/alert.php';
        require_once PROBUILDER_PATH . 'widgets/blockquote.php';
        
        // Advanced Widgets
        require_once PROBUILDER_PATH . 'widgets/tabs.php';
        require_once PROBUILDER_PATH . 'widgets/accordion.php';
        require_once PROBUILDER_PATH . 'widgets/carousel.php';
        require_once PROBUILDER_PATH . 'widgets/gallery.php';
        require_once PROBUILDER_PATH . 'widgets/toggle.php';
        require_once PROBUILDER_PATH . 'widgets/flip-box.php';
        require_once PROBUILDER_PATH . 'widgets/before-after.php';
        require_once PROBUILDER_PATH . 'widgets/animated-headline.php';
        
        // Content Widgets
        require_once PROBUILDER_PATH . 'widgets/image-box.php';
        require_once PROBUILDER_PATH . 'widgets/icon-box.php';
        require_once PROBUILDER_PATH . 'widgets/info-box.php';
        require_once PROBUILDER_PATH . 'widgets/icon-list.php';
        require_once PROBUILDER_PATH . 'widgets/feature-list.php';
        require_once PROBUILDER_PATH . 'widgets/progress-bar.php';
        require_once PROBUILDER_PATH . 'widgets/testimonial.php';
        require_once PROBUILDER_PATH . 'widgets/counter.php';
        require_once PROBUILDER_PATH . 'widgets/star-rating.php';
        require_once PROBUILDER_PATH . 'widgets/pricing-table.php';
        require_once PROBUILDER_PATH . 'widgets/team-member.php';
        require_once PROBUILDER_PATH . 'widgets/call-to-action.php';
        require_once PROBUILDER_PATH . 'widgets/social-icons.php';
        require_once PROBUILDER_PATH . 'widgets/countdown.php';
        require_once PROBUILDER_PATH . 'widgets/newsletter.php';
        require_once PROBUILDER_PATH . 'widgets/contact-form.php';
        require_once PROBUILDER_PATH . 'widgets/logo-grid.php';
        require_once PROBUILDER_PATH . 'widgets/video.php';
        require_once PROBUILDER_PATH . 'widgets/map.php';
        require_once PROBUILDER_PATH . 'widgets/html-code.php';
        require_once PROBUILDER_PATH . 'widgets/shortcode.php';
        require_once PROBUILDER_PATH . 'widgets/wp-header.php';
        require_once PROBUILDER_PATH . 'widgets/wp-sidebar.php';
        require_once PROBUILDER_PATH . 'widgets/wp-footer.php';
        require_once PROBUILDER_PATH . 'widgets/form-builder.php';
        require_once PROBUILDER_PATH . 'widgets/slider.php';
        require_once PROBUILDER_PATH . 'widgets/blog-posts.php';
        require_once PROBUILDER_PATH . 'widgets/faq.php';
        require_once PROBUILDER_PATH . 'widgets/timeline.php';
        require_once PROBUILDER_PATH . 'widgets/portfolio.php';
        require_once PROBUILDER_PATH . 'widgets/reviews.php';
        require_once PROBUILDER_PATH . 'widgets/hotspot.php';
        require_once PROBUILDER_PATH . 'widgets/loop-builder.php';
        
        // WordPress Integration Widgets
        require_once PROBUILDER_PATH . 'widgets/menu.php';
        require_once PROBUILDER_PATH . 'widgets/search-form.php';
        require_once PROBUILDER_PATH . 'widgets/breadcrumbs.php';
        require_once PROBUILDER_PATH . 'widgets/author-box.php';
        require_once PROBUILDER_PATH . 'widgets/post-navigation.php';
        require_once PROBUILDER_PATH . 'widgets/share-buttons.php';
        require_once PROBUILDER_PATH . 'widgets/price-list.php';
        require_once PROBUILDER_PATH . 'widgets/login.php';
        require_once PROBUILDER_PATH . 'widgets/sitemap.php';
        require_once PROBUILDER_PATH . 'widgets/table-of-contents.php';
        require_once PROBUILDER_PATH . 'widgets/icon.php';
        require_once PROBUILDER_PATH . 'widgets/category-list.php';
        require_once PROBUILDER_PATH . 'widgets/tag-cloud.php';
        require_once PROBUILDER_PATH . 'widgets/archive-title.php';
        require_once PROBUILDER_PATH . 'widgets/site-logo.php';
        require_once PROBUILDER_PATH . 'widgets/recent-posts.php';
        require_once PROBUILDER_PATH . 'widgets/post-excerpt.php';
        require_once PROBUILDER_PATH . 'widgets/post-title.php';
        require_once PROBUILDER_PATH . 'widgets/post-featured-image.php';
        require_once PROBUILDER_PATH . 'widgets/post-date.php';
        require_once PROBUILDER_PATH . 'widgets/post-author.php';
        require_once PROBUILDER_PATH . 'widgets/post-comments.php';
        
        // Advanced/Media Widgets
        require_once PROBUILDER_PATH . 'widgets/lottie.php';
        require_once PROBUILDER_PATH . 'widgets/mega-menu.php';
        require_once PROBUILDER_PATH . 'widgets/audio.php';
        require_once PROBUILDER_PATH . 'widgets/progress-tracker.php';
        require_once PROBUILDER_PATH . 'widgets/table.php';
        require_once PROBUILDER_PATH . 'widgets/anchor.php';
        require_once PROBUILDER_PATH . 'widgets/google-maps.php';
        require_once PROBUILDER_PATH . 'widgets/reading-progress.php';
        require_once PROBUILDER_PATH . 'widgets/code-highlight.php';
        require_once PROBUILDER_PATH . 'widgets/back-to-top.php';
        require_once PROBUILDER_PATH . 'widgets/sidebar.php';
        require_once PROBUILDER_PATH . 'widgets/text-path.php';
        require_once PROBUILDER_PATH . 'widgets/scroll-snap.php';
        require_once PROBUILDER_PATH . 'widgets/sticky-video.php';
        require_once PROBUILDER_PATH . 'widgets/offcanvas.php';
        require_once PROBUILDER_PATH . 'widgets/paypal-button.php';
        require_once PROBUILDER_PATH . 'widgets/stripe-button.php';
        require_once PROBUILDER_PATH . 'widgets/custom-css.php';
        require_once PROBUILDER_PATH . 'widgets/facebook-embed.php';
        require_once PROBUILDER_PATH . 'widgets/twitter-embed.php';
        require_once PROBUILDER_PATH . 'widgets/instagram-feed.php';
        require_once PROBUILDER_PATH . 'widgets/animated-text.php';
        require_once PROBUILDER_PATH . 'widgets/notification.php';
        require_once PROBUILDER_PATH . 'widgets/image-comparison.php';
        require_once PROBUILDER_PATH . 'widgets/parallax-image.php';
        require_once PROBUILDER_PATH . 'widgets/calendly.php';
        
        // WooCommerce Widgets
        if (class_exists('WooCommerce')) {
            require_once PROBUILDER_PATH . 'widgets/woo-products.php';
            require_once PROBUILDER_PATH . 'widgets/woo-cart.php';
            require_once PROBUILDER_PATH . 'widgets/woo-categories.php';
            require_once PROBUILDER_PATH . 'widgets/woo-reviews.php';
            require_once PROBUILDER_PATH . 'widgets/woo-add-to-cart.php';
            require_once PROBUILDER_PATH . 'widgets/woo-related.php';
            require_once PROBUILDER_PATH . 'widgets/woo-breadcrumbs.php';
            require_once PROBUILDER_PATH . 'widgets/woo-rating.php';
            require_once PROBUILDER_PATH . 'widgets/woo-meta.php';
        }
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', [$this, 'init']);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_scripts']);
        
        // Auto-fix permalinks on admin load (once per day)
        add_action('admin_init', [$this, 'auto_check_permalinks']);
        
        // Add edit with ProBuilder button
        add_filter('page_row_actions', [$this, 'add_edit_button'], 10, 2);
        add_filter('post_row_actions', [$this, 'add_edit_button'], 10, 2);
    }
    
    /**
     * Auto-check and fix permalink structure
     * Runs once per day to ensure pretty URLs work
     */
    public function auto_check_permalinks() {
        // Only check once per day to avoid performance impact
        $last_check = get_option('probuilder_permalink_check', 0);
        if (time() - $last_check < 86400) {
            return; // Checked within last 24 hours
        }
        
        // Update check time
        update_option('probuilder_permalink_check', time());
        
        $current_structure = get_option('permalink_structure');
        
        // If using Plain permalinks or index.php structure, auto-fix
        if (empty($current_structure) || strpos($current_structure, 'index.php') !== false) {
            // Set to Post name structure
            update_option('permalink_structure', '/%postname%/');
            
            // Flush rewrite rules
            flush_rewrite_rules(false);
            
            // Create/update .htaccess
            $this->ensure_htaccess();
        }
    }
    
    /**
     * Ensure .htaccess exists and has proper rewrite rules
     */
    private function ensure_htaccess() {
        $htaccess_file = ABSPATH . '.htaccess';
        
        // Only create if it doesn't exist
        if (!file_exists($htaccess_file)) {
            $htaccess_content = "# BEGIN WordPress\n";
            $htaccess_content .= "<IfModule mod_rewrite.c>\n";
            $htaccess_content .= "RewriteEngine On\n";
            $htaccess_content .= "RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]\n";
            $htaccess_content .= "RewriteBase /\n";
            $htaccess_content .= "RewriteRule ^index\.php$ - [L]\n";
            $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
            $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
            $htaccess_content .= "RewriteRule . /index.php [L]\n";
            $htaccess_content .= "</IfModule>\n";
            $htaccess_content .= "# END WordPress\n";
            
            @file_put_contents($htaccess_file, $htaccess_content);
            @chmod($htaccess_file, 0644);
        }
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize license manager (FIRST!)
        ProBuilder_License_Manager::instance();
        ProBuilder_License_Page::instance();
        
        // Initialize error handler
        ProBuilder_Error_Handler::instance();
        
        // Initialize backup manager
        ProBuilder_Backup_Manager::instance();
        
        // Initialize cache system
        ProBuilder_Cache::instance();
        
        // Initialize assets optimizer
        ProBuilder_Assets_Optimizer::instance();
        
        // Initialize widgets manager
        ProBuilder_Widgets_Manager::instance();
        
        // Initialize editor
        ProBuilder_Editor::instance();
        
        // Initialize frontend
        ProBuilder_Frontend::instance();
        
        // Initialize AJAX
        ProBuilder_Ajax::instance();
        
        // Initialize templates
        ProBuilder_Templates::instance();
        ProBuilder_Templates_Library::instance();
        
        // Initialize theme integration
        ProBuilder_Theme_Integration::instance();
        
        // Initialize advanced features
        ProBuilder_Navigator::instance();
        ProBuilder_History_Panel::instance();
        ProBuilder_Global_Styles::instance();
        ProBuilder_Popup_Builder::instance();
        ProBuilder_Theme_Builder::instance();
        ProBuilder_Dynamic_Content::instance();
        ProBuilder_Global_Widgets::instance();
        ProBuilder_Role_Manager::instance();
        ProBuilder_Form_Integrations::instance();
        ProBuilder_Shape_Dividers::instance();
        ProBuilder_Motion_Effects::instance();
        ProBuilder_Custom_Fonts::instance();
        ProBuilder_Custom_Breakpoints::instance();
        ProBuilder_Enhanced_Animations::instance();
        
        // Load text domain
        load_plugin_textdomain('probuilder', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Add admin menu
     */
    public function admin_menu() {
        add_menu_page(
            __('ProBuilder', 'probuilder'),
            __('ProBuilder', 'probuilder'),
            'edit_pages',
            'probuilder',
            [$this, 'admin_page'],
            'dashicons-layout',
            30
        );
        
        add_submenu_page(
            'probuilder',
            __('Templates', 'probuilder'),
            __('Templates', 'probuilder'),
            'edit_pages',
            'probuilder-templates',
            [ProBuilder_Templates::instance(), 'templates_page']
        );
        
        add_submenu_page(
            'probuilder',
            __('Settings', 'probuilder'),
            __('Settings', 'probuilder'),
            'manage_options',
            'probuilder-settings',
            [$this, 'settings_page']
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        $widgets_manager = ProBuilder_Widgets_Manager::instance();
        $widgets = $widgets_manager->get_widgets();
        $widgets_by_category = [
            'layout' => [],
            'basic' => [],
            'advanced' => [],
            'content' => []
        ];
        
        foreach ($widgets as $widget) {
            $category = $widget->get_category();
            if (!isset($widgets_by_category[$category])) {
                $widgets_by_category[$category] = [];
            }
            $widgets_by_category[$category][] = $widget;
        }
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('ProBuilder - Page Builder', 'probuilder') . '</h1>';
        
        // Widget Status Panel
        echo '<div class="notice notice-info" style="margin: 20px 0; padding: 15px;">';
        echo '<h3 style="margin-top: 0;"><span class="dashicons dashicons-info"></span> Widgets Status</h3>';
        echo '<p><strong>' . count($widgets) . ' widgets loaded successfully!</strong></p>';
        echo '<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-top: 15px;">';
        
        foreach ($widgets_by_category as $category => $cat_widgets) {
            $icon_map = [
                'layout' => 'dashicons-editor-table',
                'basic' => 'dashicons-editor-paragraph',
                'advanced' => 'dashicons-admin-settings',
                'content' => 'dashicons-admin-page'
            ];
            $icon = isset($icon_map[$category]) ? $icon_map[$category] : 'dashicons-admin-generic';
            
            echo '<div style="background: #fff; padding: 15px; border-left: 4px solid #007cba; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
            echo '<div style="display: flex; align-items: center; margin-bottom: 10px;">';
            echo '<span class="dashicons ' . $icon . '" style="font-size: 24px; margin-right: 8px; color: #007cba;"></span>';
            echo '<strong style="text-transform: capitalize;">' . esc_html($category) . '</strong>';
            echo '</div>';
            echo '<div style="font-size: 24px; font-weight: bold; color: #007cba;">' . count($cat_widgets) . '</div>';
            echo '<div style="font-size: 12px; color: #666; margin-top: 5px;">';
            foreach ($cat_widgets as $widget) {
                echo '<div style="padding: 2px 0;">• ' . esc_html($widget->get_title()) . '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        
        echo '<div class="probuilder-admin-welcome">';
        echo '<div class="probuilder-welcome-panel">';
        echo '<h2>' . esc_html__('Welcome to ProBuilder!', 'probuilder') . '</h2>';
        echo '<p>' . esc_html__('Create stunning pages with our powerful drag & drop builder.', 'probuilder') . '</p>';
        echo '<div class="probuilder-features">';
        echo '<div class="feature"><span class="dashicons dashicons-yes"></span> ' . count($widgets) . '+ Premium Widgets</div>';
        echo '<div class="feature"><span class="dashicons dashicons-yes"></span> ' . esc_html__('Drag & Drop Interface', 'probuilder') . '</div>';
        echo '<div class="feature"><span class="dashicons dashicons-yes"></span> ' . esc_html__('Fully Responsive', 'probuilder') . '</div>';
        echo '<div class="feature"><span class="dashicons dashicons-yes"></span> ' . esc_html__('Grid Layout with Resizable Borders', 'probuilder') . '</div>';
        echo '</div>';
        echo '<a href="' . admin_url('post-new.php?post_type=page&probuilder=true') . '" class="button button-primary button-hero">' . esc_html__('Create New Page', 'probuilder') . '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        if (isset($_POST['probuilder_save_settings'])) {
            check_admin_referer('probuilder_settings');
            
            $post_types = isset($_POST['probuilder_post_types']) ? array_map('sanitize_text_field', $_POST['probuilder_post_types']) : [];
            update_option('probuilder_post_types', $post_types);
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Settings saved!', 'probuilder') . '</p></div>';
        }
        
        $enabled_post_types = get_option('probuilder_post_types', ['page', 'post']);
        $available_post_types = get_post_types(['public' => true], 'objects');
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('ProBuilder Settings', 'probuilder') . '</h1>';
        echo '<form method="post">';
        wp_nonce_field('probuilder_settings');
        echo '<table class="form-table">';
        echo '<tr><th scope="row">' . esc_html__('Enable ProBuilder For', 'probuilder') . '</th><td>';
        
        foreach ($available_post_types as $post_type) {
            if ($post_type->name !== 'attachment') {
                $checked = in_array($post_type->name, $enabled_post_types) ? 'checked' : '';
                echo '<label><input type="checkbox" name="probuilder_post_types[]" value="' . esc_attr($post_type->name) . '" ' . $checked . '> ' . esc_html($post_type->label) . '</label><br>';
            }
        }
        
        echo '</td></tr>';
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="probuilder_save_settings" class="button button-primary" value="' . esc_attr__('Save Settings', 'probuilder') . '"></p>';
        echo '</form>';
        echo '</div>';
    }
    
    /**
     * Enqueue admin scripts
     */
    public function admin_scripts($hook) {
        // Editor styles
        wp_enqueue_style('probuilder-admin', PROBUILDER_URL . 'assets/css/admin.css', [], PROBUILDER_VERSION);
    }
    
    /**
     * Enqueue frontend scripts with optimization
     */
    public function frontend_scripts() {
        // Check if minified versions exist, use them if available
        $css_file = file_exists(PROBUILDER_PATH . 'assets/css/frontend.min.css') 
            ? 'assets/css/frontend.min.css' 
            : 'assets/css/frontend.css';
        
        $js_file = file_exists(PROBUILDER_PATH . 'assets/js/frontend.min.js')
            ? 'assets/js/frontend.min.js'
            : 'assets/js/frontend.js';
        
        wp_enqueue_style('probuilder-frontend', PROBUILDER_URL . $css_file, [], PROBUILDER_VERSION);
        wp_enqueue_script('probuilder-frontend', PROBUILDER_URL . $js_file, ['jquery'], PROBUILDER_VERSION, true);
        
        // Add inline optimization settings
        wp_localize_script('probuilder-frontend', 'probuilderOptimization', [
            'cacheEnabled' => ProBuilder_Cache::instance()->is_cache_enabled(),
            'lazyLoad' => apply_filters('probuilder_lazy_load', true),
        ]);
    }
    
    /**
     * Add edit with ProBuilder button
     */
    public function add_edit_button($actions, $post) {
        $enabled_post_types = get_option('probuilder_post_types', ['page', 'post']);
        
        if (in_array($post->post_type, $enabled_post_types)) {
            // Use frontend URL with probuilder parameter (not admin)
            $probuilder_url = add_query_arg([
                'p' => $post->ID,
                'probuilder' => 'true',
                'post_type' => $post->post_type,
            ], home_url('/'));
            
            $actions['probuilder'] = sprintf(
                '<a href="%s" style="color: #92003b; font-weight: 600;">%s</a>',
                esc_url($probuilder_url),
                __('Edit with ProBuilder', 'probuilder')
            );
        }
        
        return $actions;
    }
}

/**
 * Plugin Activation Hook
 * Automatically configure WordPress for ProBuilder
 */
function probuilder_activate() {
    // 1. Set permalink structure to Post name (pretty URLs without index.php)
    $current_structure = get_option('permalink_structure');
    
    // Only change if it's Plain or includes index.php
    if (empty($current_structure) || strpos($current_structure, 'index.php') !== false) {
        update_option('permalink_structure', '/%postname%/');
    }
    
    // 2. Flush rewrite rules to apply new structure
    flush_rewrite_rules(true);
    
    // 3. Create .htaccess if it doesn't exist
    $htaccess_file = ABSPATH . '.htaccess';
    if (!file_exists($htaccess_file)) {
        $htaccess_content = "# BEGIN WordPress\n";
        $htaccess_content .= "<IfModule mod_rewrite.c>\n";
        $htaccess_content .= "RewriteEngine On\n";
        $htaccess_content .= "RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]\n";
        $htaccess_content .= "RewriteBase /\n";
        $htaccess_content .= "RewriteRule ^index\.php$ - [L]\n";
        $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
        $htaccess_content .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
        $htaccess_content .= "RewriteRule . /index.php [L]\n";
        $htaccess_content .= "</IfModule>\n";
        $htaccess_content .= "# END WordPress\n";
        
        @file_put_contents($htaccess_file, $htaccess_content);
        @chmod($htaccess_file, 0644);
    }
    
    // 4. Enable pages and posts for ProBuilder by default
    $enabled_types = get_option('probuilder_post_types', []);
    if (empty($enabled_types)) {
        update_option('probuilder_post_types', ['page', 'post']);
    }
    
    // 5. Set activation flag for first-time setup notice
    update_option('probuilder_activated', true);
    update_option('probuilder_activation_time', time());
    
    // 6. Create feedback table
    if (class_exists('ProBuilder_Uninstall_Feedback')) {
        ProBuilder_Uninstall_Feedback::instance()->create_feedback_table();
    }
}

/**
 * Plugin Deactivation Hook
 */
function probuilder_deactivate() {
    // Don't flush rewrite rules on deactivation
    // This allows pages to still work if plugin is temporarily disabled
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'probuilder_activate');
register_deactivation_hook(__FILE__, 'probuilder_deactivate');

/**
 * Initialize ProBuilder
 */
function probuilder() {
    return ProBuilder::instance();
}

// Start the plugin
probuilder();

