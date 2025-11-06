<?php
/**
 * ProBuilder Custom Parts - Headers, Footers, Sliders
 * Dedicated builder for each part type
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Custom_Parts {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_shortcodes']);
        add_action('admin_menu', [$this, 'add_admin_menus'], 20);
        add_filter('post_row_actions', [$this, 'add_edit_button'], 10, 2);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        add_filter('manage_pb_header_posts_columns', [$this, 'custom_columns_header']);
        add_filter('manage_pb_footer_posts_columns', [$this, 'custom_columns_footer']);
        add_filter('manage_pb_slider_posts_columns', [$this, 'custom_columns_slider']);
        add_filter('manage_pb_sidebar_posts_columns', [$this, 'custom_columns_sidebar']);
        add_action('manage_pb_header_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        add_action('manage_pb_footer_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        add_action('manage_pb_slider_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        add_action('manage_pb_sidebar_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        
        // Prevent direct access to custom parts - redirect to 404
        add_action('template_redirect', [$this, 'prevent_direct_access']);
        
        // Exclude from navigation menus
        add_filter('get_pages', [$this, 'exclude_from_pages'], 10, 2);
        add_filter('nav_menu_meta_box_object', [$this, 'exclude_from_nav_menu'], 10, 1);
        
        // Apply active headers/footers to site
        add_action('get_header', [$this, 'replace_theme_header']);
        add_action('get_footer', [$this, 'replace_theme_footer']);
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Flush rewrite rules on first activation
        $flush_option = 'probuilder_parts_flushed';
        if (!get_option($flush_option)) {
            add_action('init', function() use ($flush_option) {
                flush_rewrite_rules();
                update_option($flush_option, '1');
            }, 999);
        }
        
        // Headers
        register_post_type('pb_header', [
            'labels' => [
                'name' => __('Headers', 'probuilder'),
                'singular_name' => __('Header', 'probuilder'),
                'add_new' => __('Add New Header', 'probuilder'),
                'add_new_item' => __('Add New Header', 'probuilder'),
                'edit_item' => __('Edit Header', 'probuilder'),
                'new_item' => __('New Header', 'probuilder'),
                'view_item' => __('View Header', 'probuilder'),
                'search_items' => __('Search Headers', 'probuilder'),
                'not_found' => __('No headers found', 'probuilder'),
                'all_items' => __('All Headers', 'probuilder'),
            ],
            'public' => false, // Not public - prevents appearing in pages list
            'publicly_queryable' => false, // Not accessible via URL
            'show_ui' => true, // Show in admin
            'show_in_menu' => false, // Hide from main menu, show in submenu
            'show_in_nav_menus' => false, // IMPORTANT: Hide from nav menus
            'query_var' => false,
            'rewrite' => false, // No permalinks
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-align-center',
            'supports' => ['title', 'revisions'],
            'exclude_from_search' => true, // Exclude from search
        ]);
        
        // Footers
        register_post_type('pb_footer', [
            'labels' => [
                'name' => __('Footers', 'probuilder'),
                'singular_name' => __('Footer', 'probuilder'),
                'add_new' => __('Add New Footer', 'probuilder'),
                'add_new_item' => __('Add New Footer', 'probuilder'),
                'edit_item' => __('Edit Footer', 'probuilder'),
                'new_item' => __('New Footer', 'probuilder'),
                'view_item' => __('View Footer', 'probuilder'),
                'search_items' => __('Search Footers', 'probuilder'),
                'not_found' => __('No footers found', 'probuilder'),
                'all_items' => __('All Footers', 'probuilder'),
            ],
            'public' => false, // Not public - prevents appearing in pages list
            'publicly_queryable' => false, // Not accessible via URL
            'show_ui' => true, // Show in admin
            'show_in_menu' => false, // Hide from main menu, show in submenu
            'show_in_nav_menus' => false, // IMPORTANT: Hide from nav menus
            'query_var' => false,
            'rewrite' => false, // No permalinks
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-align-full-width',
            'supports' => ['title', 'revisions'],
            'exclude_from_search' => true, // Exclude from search
        ]);
        
        // Sliders
        register_post_type('pb_slider', [
            'labels' => [
                'name' => __('Sliders', 'probuilder'),
                'singular_name' => __('Slider', 'probuilder'),
                'add_new' => __('Add New Slider', 'probuilder'),
                'add_new_item' => __('Add New Slider', 'probuilder'),
                'edit_item' => __('Edit Slider', 'probuilder'),
                'new_item' => __('New Slider', 'probuilder'),
                'view_item' => __('View Slider', 'probuilder'),
                'search_items' => __('Search Sliders', 'probuilder'),
                'not_found' => __('No sliders found', 'probuilder'),
                'all_items' => __('All Sliders', 'probuilder'),
            ],
            'public' => false, // Not public - prevents appearing in pages list
            'publicly_queryable' => false, // Not accessible via URL
            'show_ui' => true, // Show in admin
            'show_in_menu' => false, // Hide from main menu, show in submenu
            'show_in_nav_menus' => false, // IMPORTANT: Hide from nav menus
            'query_var' => false,
            'rewrite' => false, // No permalinks
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title', 'revisions'],
            'exclude_from_search' => true, // Exclude from search
        ]);
        
        // Sidebars
        register_post_type('pb_sidebar', [
            'labels' => [
                'name' => __('Sidebars', 'probuilder'),
                'singular_name' => __('Sidebar', 'probuilder'),
                'add_new' => __('Add New Sidebar', 'probuilder'),
                'add_new_item' => __('Add New Sidebar', 'probuilder'),
                'edit_item' => __('Edit Sidebar', 'probuilder'),
                'new_item' => __('New Sidebar', 'probuilder'),
                'view_item' => __('View Sidebar', 'probuilder'),
                'search_items' => __('Search Sidebars', 'probuilder'),
                'not_found' => __('No sidebars found', 'probuilder'),
                'all_items' => __('All Sidebars', 'probuilder'),
            ],
            'public' => false, // Not public - prevents appearing in pages list
            'publicly_queryable' => false, // Not accessible via URL
            'show_ui' => true, // Show in admin
            'show_in_menu' => false, // Hide from main menu, show in submenu
            'show_in_nav_menus' => false, // IMPORTANT: Hide from nav menus
            'query_var' => false,
            'rewrite' => false, // No permalinks
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-columns',
            'supports' => ['title', 'revisions'],
            'exclude_from_search' => true, // Exclude from search
        ]);
    }
    
    /**
     * Add admin menus
     */
    public function add_admin_menus() {
        // Main ProBuilder menu
        add_menu_page(
            __('ProBuilder', 'probuilder'),
            __('ProBuilder', 'probuilder'),
            'edit_pages',
            'probuilder-parts',
            [$this, 'render_dashboard'],
            'dashicons-layout',
            26
        );
        
        // Headers submenu
        add_submenu_page(
            'probuilder-parts',
            __('Headers', 'probuilder'),
            __('📌 Headers', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=pb_header'
        );
        
        // Footers submenu
        add_submenu_page(
            'probuilder-parts',
            __('Footers', 'probuilder'),
            __('📎 Footers', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=pb_footer'
        );
        
        // Sliders submenu
        add_submenu_page(
            'probuilder-parts',
            __('Sliders', 'probuilder'),
            __('🎬 Sliders', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=pb_slider'
        );
        
        // Sidebars submenu
        add_submenu_page(
            'probuilder-parts',
            __('Sidebars', 'probuilder'),
            __('📋 Sidebars', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=pb_sidebar'
        );
        
        // Template Parts submenu
        add_submenu_page(
            'probuilder-parts',
            __('Template Parts', 'probuilder'),
            __('📦 Template Parts', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=probuilder_part'
        );
    }
    
    /**
     * Render dashboard page
     */
    public function render_dashboard() {
        ?>
        <div class="wrap" style="max-width: 1400px;">
            <h1 style="font-size: 32px; margin-bottom: 30px;">
                <span class="dashicons dashicons-layout" style="font-size: 32px; vertical-align: middle;"></span>
                <?php _e('ProBuilder - Build Your Site', 'probuilder'); ?>
            </h1>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 30px;">
                
                <!-- Headers Card -->
                <div class="probuilder-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; font-size: 120px; opacity: 0.1;">📌</div>
                    <div style="position: relative; z-index: 1;">
                        <h2 style="margin: 0 0 15px; font-size: 24px; color: white;">
                            <span class="dashicons dashicons-align-center" style="font-size: 28px; vertical-align: middle; margin-right: 8px;"></span>
                            <?php _e('Headers', 'probuilder'); ?>
                        </h2>
                        <p style="margin: 0 0 20px; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                            <?php _e('Create custom headers with logo, navigation, search, cart, and more. Apply to entire site or specific pages.', 'probuilder'); ?>
                        </p>
                        <div style="display: flex; gap: 10px;">
                            <a href="<?php echo admin_url('post-new.php?post_type=pb_header'); ?>" class="button button-primary button-hero" style="background: white; color: #667eea; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-weight: 600;">
                                <?php _e('+ Create Header', 'probuilder'); ?>
                            </a>
                            <a href="<?php echo admin_url('edit.php?post_type=pb_header'); ?>" class="button button-hero" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                                <?php _e('View All', 'probuilder'); ?>
                            </a>
                        </div>
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                            <small style="opacity: 0.8;">
                                <?php
                                $header_count = wp_count_posts('pb_header');
                                printf(__('%d headers created', 'probuilder'), $header_count->publish + $header_count->draft);
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Footers Card -->
                <div class="probuilder-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; font-size: 120px; opacity: 0.1;">📎</div>
                    <div style="position: relative; z-index: 1;">
                        <h2 style="margin: 0 0 15px; font-size: 24px; color: white;">
                            <span class="dashicons dashicons-align-full-width" style="font-size: 28px; vertical-align: middle; margin-right: 8px;"></span>
                            <?php _e('Footers', 'probuilder'); ?>
                        </h2>
                        <p style="margin: 0 0 20px; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                            <?php _e('Design footers with columns, social icons, newsletter forms, links, and copyright. Different footers for different pages.', 'probuilder'); ?>
                        </p>
                        <div style="display: flex; gap: 10px;">
                            <a href="<?php echo admin_url('post-new.php?post_type=pb_footer'); ?>" class="button button-primary button-hero" style="background: white; color: #f5576c; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-weight: 600;">
                                <?php _e('+ Create Footer', 'probuilder'); ?>
                            </a>
                            <a href="<?php echo admin_url('edit.php?post_type=pb_footer'); ?>" class="button button-hero" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                                <?php _e('View All', 'probuilder'); ?>
                            </a>
                        </div>
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                            <small style="opacity: 0.8;">
                                <?php
                                $footer_count = wp_count_posts('pb_footer');
                                printf(__('%d footers created', 'probuilder'), $footer_count->publish + $footer_count->draft);
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Sliders Card -->
                <div class="probuilder-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; font-size: 120px; opacity: 0.1;">🎬</div>
                    <div style="position: relative; z-index: 1;">
                        <h2 style="margin: 0 0 15px; font-size: 24px; color: white;">
                            <span class="dashicons dashicons-images-alt2" style="font-size: 28px; vertical-align: middle; margin-right: 8px;"></span>
                            <?php _e('Sliders', 'probuilder'); ?>
                        </h2>
                        <p style="margin: 0 0 20px; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                            <?php _e('Build hero sliders with multiple slides. Add images, text, buttons, animations. Use anywhere with shortcode.', 'probuilder'); ?>
                        </p>
                        <div style="display: flex; gap: 10px;">
                            <a href="<?php echo admin_url('post-new.php?post_type=pb_slider'); ?>" class="button button-primary button-hero" style="background: white; color: #4facfe; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-weight: 600;">
                                <?php _e('+ Create Slider', 'probuilder'); ?>
                            </a>
                            <a href="<?php echo admin_url('edit.php?post_type=pb_slider'); ?>" class="button button-hero" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                                <?php _e('View All', 'probuilder'); ?>
                            </a>
                        </div>
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                            <small style="opacity: 0.8;">
                                <?php
                                $slider_count = wp_count_posts('pb_slider');
                                printf(__('%d sliders created', 'probuilder'), $slider_count->publish + $slider_count->draft);
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebars Card -->
                <div class="probuilder-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; font-size: 120px; opacity: 0.1;">📋</div>
                    <div style="position: relative; z-index: 1;">
                        <h2 style="margin: 0 0 15px; font-size: 24px; color: white;">
                            <span class="dashicons dashicons-columns" style="font-size: 28px; vertical-align: middle; margin-right: 8px;"></span>
                            <?php _e('Sidebars', 'probuilder'); ?>
                        </h2>
                        <p style="margin: 0 0 20px; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                            <?php _e('Create custom sidebars with widgets, ads, recent posts, categories, and more. Use in any page or template.', 'probuilder'); ?>
                        </p>
                        <div style="display: flex; gap: 10px;">
                            <a href="<?php echo admin_url('post-new.php?post_type=pb_sidebar'); ?>" class="button button-primary button-hero" style="background: white; color: #fa709a; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-weight: 600;">
                                <?php _e('+ Create Sidebar', 'probuilder'); ?>
                            </a>
                            <a href="<?php echo admin_url('edit.php?post_type=pb_sidebar'); ?>" class="button button-hero" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                                <?php _e('View All', 'probuilder'); ?>
                            </a>
                        </div>
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                            <small style="opacity: 0.8;">
                                <?php
                                $sidebar_count = wp_count_posts('pb_sidebar');
                                printf(__('%d sidebars created', 'probuilder'), $sidebar_count->publish + $sidebar_count->draft);
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Features Overview -->
            <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                <h2 style="margin: 0 0 20px; font-size: 20px;">
                    <span class="dashicons dashicons-star-filled" style="color: #fbbf24; font-size: 24px; vertical-align: middle;"></span>
                    <?php _e('What You Can Build', 'probuilder'); ?>
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <h3 style="margin: 0 0 10px; font-size: 16px; color: #667eea;">📌 Headers</h3>
                        <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #6b7280; line-height: 1.8;">
                            <li>Logo + Navigation</li>
                            <li>Search Bar</li>
                            <li>Shopping Cart</li>
                            <li>User Account Menu</li>
                            <li>Sticky Headers</li>
                            <li>Transparent Headers</li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 10px; font-size: 16px; color: #f5576c;">📎 Footers</h3>
                        <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #6b7280; line-height: 1.8;">
                            <li>Multi-Column Layouts</li>
                            <li>Social Media Icons</li>
                            <li>Newsletter Forms</li>
                            <li>Copyright Text</li>
                            <li>Payment Icons</li>
                            <li>Back to Top Button</li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 10px; font-size: 16px; color: #4facfe;">🎬 Sliders</h3>
                        <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #6b7280; line-height: 1.8;">
                            <li>Hero Sliders</li>
                            <li>Full-Screen Sliders</li>
                            <li>Product Carousels</li>
                            <li>Testimonial Sliders</li>
                            <li>Image Galleries</li>
                            <li>Video Sliders</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Quick Tips -->
            <div style="margin-top: 20px; background: #f0f9ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <h3 style="margin: 0 0 10px; font-size: 16px; color: #1e40af;">
                    <span class="dashicons dashicons-lightbulb" style="font-size: 18px; vertical-align: middle;"></span>
                    <?php _e('Quick Tips', 'probuilder'); ?>
                </h3>
                <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #1e40af; line-height: 1.8;">
                    <li>After creating, click <strong>"Edit with ProBuilder"</strong> to design with drag & drop</li>
                    <li>Use headers/footers site-wide or assign to specific pages</li>
                    <li>Insert sliders anywhere using shortcode: <code>[pb_slider id="123"]</code></li>
                    <li>All parts are fully responsive and mobile-optimized</li>
                    <li>You can duplicate existing parts to create variations</li>
                </ul>
            </div>
        </div>
        
        <style>
            .probuilder-card:hover {
                transform: translateY(-5px);
                transition: transform 0.3s ease;
            }
            .probuilder-card .button:hover {
                transform: scale(1.05);
                transition: transform 0.2s ease;
            }
        </style>
        <?php
    }
    
    /**
     * Add edit with ProBuilder button
     */
    public function add_edit_button($actions, $post) {
        $post_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];
        
        if (in_array($post->post_type, $post_types)) {
            // Remove "View" link - these are elements, not pages
            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);
            
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
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        $post_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'probuilder_part_info',
                __('ProBuilder Info', 'probuilder'),
                [$this, 'render_info_meta_box'],
                $post_type,
                'side',
                'high'
            );
            
            // Add activation meta box for headers and footers
            if ($post_type === 'pb_header' || $post_type === 'pb_footer') {
                add_meta_box(
                    'probuilder_part_activation',
                    __('Site-Wide Activation', 'probuilder'),
                    [$this, 'render_activation_meta_box'],
                    $post_type,
                    'side',
                    'high'
                );
            }
        }
    }
    
    /**
     * Render info meta box
     */
    public function render_info_meta_box($post) {
        $part_type = str_replace('pb_', '', $post->post_type);
        ?>
        <div style="padding: 10px 0;">
            <p style="margin: 0 0 15px; font-size: 13px; line-height: 1.6;">
                <?php
                if ($part_type === 'header') {
                    _e('Create a custom header with navigation, logo, search, cart, and more.', 'probuilder');
                } elseif ($part_type === 'footer') {
                    _e('Design a footer with columns, social icons, forms, and links.', 'probuilder');
                } else {
                    _e('Build a slider with multiple slides, images, text, and animations.', 'probuilder');
                }
                ?>
            </p>
            
            <?php if ($post->post_status === 'publish'): ?>
                <div style="padding: 12px; background: #f0f9ff; border-radius: 6px; margin-bottom: 15px;">
                    <strong style="display: block; margin-bottom: 8px; font-size: 12px; color: #1e40af;">
                        <?php _e('Shortcode:', 'probuilder'); ?>
                    </strong>
                    <input type="text" value="[<?php echo $part_type; ?> id=&quot;<?php echo $post->ID; ?>&quot;]" readonly 
                           style="width: 100%; padding: 8px; font-family: monospace; font-size: 11px; background: white; border: 1px solid #cbd5e1; border-radius: 4px;"
                           onclick="this.select();">
                    <small style="display: block; margin-top: 6px; color: #64748b; font-size: 11px;">
                        <?php _e('Click to select, then copy', 'probuilder'); ?>
                    </small>
                </div>
            <?php endif; ?>
            
            <div style="padding: 12px; background: #fef3c7; border-radius: 6px; border-left: 3px solid #f59e0b;">
                <strong style="display: block; margin-bottom: 8px; font-size: 12px; color: #92400e;">
                    <span class="dashicons dashicons-info" style="font-size: 14px; vertical-align: middle;"></span>
                    <?php _e('How to Use', 'probuilder'); ?>
                </strong>
                <ol style="margin: 0; padding-left: 20px; font-size: 11px; line-height: 1.6; color: #92400e;">
                    <li><?php _e('Click "Publish" to save', 'probuilder'); ?></li>
                    <li><?php _e('Click "Edit with ProBuilder"', 'probuilder'); ?></li>
                    <li><?php _e('Design with drag & drop', 'probuilder'); ?></li>
                    <li><?php _e('Save and use anywhere!', 'probuilder'); ?></li>
                </ol>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    /**
     * Render activation meta box
     */
    public function render_activation_meta_box($post) {
        $part_type = str_replace('pb_', '', $post->post_type);
        $is_active = get_post_meta($post->ID, '_probuilder_active_' . $part_type, true);
        $active_id = get_option('probuilder_active_' . $part_type, 0);
        
        wp_nonce_field('probuilder_activation_' . $post->ID, 'probuilder_activation_nonce');
        ?>
        <div style="padding: 10px 0;">
            <?php if ($active_id && $active_id != $post->ID): ?>
                <div style="padding: 12px; background: #fef3c7; border-left: 4px solid #f59e0b; margin-bottom: 15px; border-radius: 4px;">
                    <p style="margin: 0; font-size: 12px; color: #92400e; line-height: 1.5;">
                        ⚠️ <strong>Another <?php echo $part_type; ?> is currently active</strong> (ID: <?php echo $active_id; ?>).<br>
                        Activating this will deactivate the other one.
                    </p>
                </div>
            <?php endif; ?>
            
            <label style="display: flex; align-items: center; cursor: pointer; padding: 12px; background: #f9fafb; border-radius: 6px; border: 2px solid <?php echo $is_active ? '#10b981' : '#e5e7eb'; ?>;">
                <input type="checkbox" 
                       name="probuilder_activate_<?php echo $part_type; ?>" 
                       value="1" 
                       <?php checked($is_active, '1'); ?>
                       style="margin-right: 10px; width: 18px; height: 18px;">
                <span style="font-weight: 600; color: <?php echo $is_active ? '#065f46' : '#1f2937'; ?>;">
                    <?php if ($is_active): ?>
                        ✅ Active on All Pages
                    <?php else: ?>
                        Set as Active <?php echo ucfirst($part_type); ?>
                    <?php endif; ?>
                </span>
            </label>
            
            <p style="margin: 12px 0 0; font-size: 12px; color: #6b7280; line-height: 1.6;">
                <?php if ($part_type === 'header'): ?>
                    When active, this header will automatically appear at the top of all pages, replacing your theme's default header.
                <?php else: ?>
                    When active, this footer will automatically appear at the bottom of all pages, replacing your theme's default footer.
                <?php endif; ?>
            </p>
            
            <?php if ($is_active): ?>
                <div style="margin-top: 15px; padding: 12px; background: #d1fae5; border-radius: 6px;">
                    <p style="margin: 0; font-size: 12px; color: #065f46;">
                        <strong>✅ Currently Active!</strong><br>
                        This <?php echo $part_type; ?> is being displayed site-wide.
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['probuilder_activation_nonce']) || 
            !wp_verify_nonce($_POST['probuilder_activation_nonce'], 'probuilder_activation_' . $post_id)) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $post = get_post($post_id);
        if (!$post) {
            return;
        }
        
        $part_type = str_replace('pb_', '', $post->post_type);
        
        // Only for headers and footers
        if (!in_array($post->post_type, ['pb_header', 'pb_footer'])) {
            return;
        }
        
        // Check if activation checkbox was checked
        $activate = isset($_POST['probuilder_activate_' . $part_type]) && $_POST['probuilder_activate_' . $part_type] === '1';
        
        if ($activate) {
            // Get currently active item
            $current_active = get_option('probuilder_active_' . $part_type, 0);
            
            // Deactivate current active item
            if ($current_active && $current_active != $post_id) {
                update_post_meta($current_active, '_probuilder_active_' . $part_type, '');
            }
            
            // Activate this item
            update_post_meta($post_id, '_probuilder_active_' . $part_type, '1');
            update_option('probuilder_active_' . $part_type, $post_id);
        } else {
            // Deactivate this item
            update_post_meta($post_id, '_probuilder_active_' . $part_type, '');
            
            // If this was the active one, clear the option
            if (get_option('probuilder_active_' . $part_type) == $post_id) {
                delete_option('probuilder_active_' . $part_type);
            }
        }
    }
    
    /**
     * Replace theme header with ProBuilder header
     */
    public function replace_theme_header() {
        // Skip in admin or ProBuilder editor
        if (is_admin() || (isset($_GET['probuilder']) && $_GET['probuilder'] === 'true')) {
            return;
        }
        
        $active_header_id = get_option('probuilder_active_header', 0);
        
        if (!$active_header_id) {
            return;
        }
        
        // Get the header content
        $header_content = do_shortcode('[header id="' . $active_header_id . '"]');
        
        if (!empty($header_content)) {
            // Output the ProBuilder header
            echo $header_content;
            
            // Prevent theme header from loading
            remove_all_actions('wp_head');
            add_action('wp_head', 'wp_enqueue_scripts', 1);
            add_action('wp_head', 'wp_print_styles', 8);
            add_action('wp_head', 'wp_print_head_scripts', 9);
        }
    }
    
    /**
     * Replace theme footer with ProBuilder footer
     */
    public function replace_theme_footer() {
        // Skip in admin or ProBuilder editor
        if (is_admin() || (isset($_GET['probuilder']) && $_GET['probuilder'] === 'true')) {
            return;
        }
        
        $active_footer_id = get_option('probuilder_active_footer', 0);
        
        if (!$active_footer_id) {
            return;
        }
        
        // Get the footer content
        $footer_content = do_shortcode('[footer id="' . $active_footer_id . '"]');
        
        if (!empty($footer_content)) {
            // Output the ProBuilder footer
            echo $footer_content;
            
            // Prevent theme footer widgets from loading
            remove_all_actions('wp_footer');
            add_action('wp_footer', 'wp_print_footer_scripts', 20);
        }
    }
    
    /**
     * Custom columns
     */
    public function custom_columns_header($columns) {
        return $this->custom_columns($columns, 'header');
    }
    
    public function custom_columns_footer($columns) {
        return $this->custom_columns($columns, 'footer');
    }
    
    public function custom_columns_slider($columns) {
        return $this->custom_columns($columns, 'slider');
    }
    
    public function custom_columns_sidebar($columns) {
        return $this->custom_columns($columns, 'sidebar');
    }
    
    private function custom_columns($columns, $type) {
        $new_columns = [];
        
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            
            if ($key === 'title') {
                // Add Active column for headers and footers
                if ($type === 'header' || $type === 'footer') {
                    $new_columns['active_status'] = __('Status', 'probuilder');
                }
                $new_columns['shortcode'] = __('Shortcode', 'probuilder');
                $new_columns['preview'] = __('Preview', 'probuilder');
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Custom column content
     */
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'active_status':
                $post_type = get_post_type($post_id);
                $type = str_replace('pb_', '', $post_type);
                $is_active = get_post_meta($post_id, '_probuilder_active_' . $type, true);
                $active_id = get_option('probuilder_active_' . $type, 0);
                
                if ($is_active && $active_id == $post_id) {
                    echo '<span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 11px; font-weight: 600;">✅ ACTIVE</span>';
                } else {
                    echo '<span style="color: #9ca3af; font-size: 11px;">Inactive</span>';
                }
                break;
            
            case 'shortcode':
                $post_type = get_post_type($post_id);
                $type = str_replace('pb_', '', $post_type);
                echo '<code style="padding: 4px 8px; background: #f1f5f9; border-radius: 4px; font-size: 11px;">[' . $type . ' id="' . $post_id . '"]</code>';
                echo '<button class="button button-small" style="margin-left: 8px;" onclick="
                    const code = \'[' . $type . ' id=&quot;' . $post_id . '&quot;]\';
                    navigator.clipboard.writeText(code);
                    this.textContent = \'✓ Copied!\';
                    setTimeout(() => this.textContent = \'Copy\', 2000);
                ">Copy</button>';
                break;
                
            case 'preview':
                $probuilder_url = add_query_arg([
                    'p' => $post_id,
                    'preview' => 'true',
                ], home_url('/'));
                echo '<a href="' . esc_url($probuilder_url) . '" target="_blank" class="button button-small">';
                echo '<span class="dashicons dashicons-visibility" style="font-size: 14px; vertical-align: middle;"></span> ';
                echo __('Preview', 'probuilder');
                echo '</a>';
                break;
        }
    }
    
    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('header', [$this, 'render_header_shortcode']);
        add_shortcode('pb_header', [$this, 'render_header_shortcode']);
        add_shortcode('footer', [$this, 'render_footer_shortcode']);
        add_shortcode('pb_footer', [$this, 'render_footer_shortcode']);
        add_shortcode('slider', [$this, 'render_slider_shortcode']);
        add_shortcode('pb_slider', [$this, 'render_slider_shortcode']);
        add_shortcode('sidebar', [$this, 'render_sidebar_shortcode']);
        add_shortcode('pb_sidebar', [$this, 'render_sidebar_shortcode']);
    }
    
    /**
     * Render header shortcode
     */
    public function render_header_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => 0,
        ], $atts);
        
        $header_id = intval($atts['id']);
        if (!$header_id) {
            return '<p style="color: red;">' . __('Invalid header ID', 'probuilder') . '</p>';
        }
        
        return $this->render_part($header_id, 'pb_header');
    }
    
    /**
     * Render footer shortcode
     */
    public function render_footer_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => 0,
        ], $atts);
        
        $footer_id = intval($atts['id']);
        if (!$footer_id) {
            return '<p style="color: red;">' . __('Invalid footer ID', 'probuilder') . '</p>';
        }
        
        return $this->render_part($footer_id, 'pb_footer');
    }
    
    /**
     * Render slider shortcode
     */
    public function render_slider_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => 0,
        ], $atts);
        
        $slider_id = intval($atts['id']);
        if (!$slider_id) {
            return '<p style="color: red;">' . __('Invalid slider ID', 'probuilder') . '</p>';
        }
        
        return $this->render_part($slider_id, 'pb_slider');
    }
    
    /**
     * Render sidebar shortcode
     */
    public function render_sidebar_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => 0,
        ], $atts);
        
        $sidebar_id = intval($atts['id']);
        if (!$sidebar_id) {
            return '<p style="color: red;">' . __('Invalid sidebar ID', 'probuilder') . '</p>';
        }
        
        return $this->render_part($sidebar_id, 'pb_sidebar');
    }
    
    /**
     * Exclude custom parts from get_pages() results
     * Prevents them from appearing in page lists and menus
     */
    public function exclude_from_pages($pages, $args) {
        if (empty($pages)) {
            return $pages;
        }
        
        $custom_part_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];
        
        // Filter out custom parts
        $filtered_pages = array_filter($pages, function($page) use ($custom_part_types) {
            return !in_array($page->post_type, $custom_part_types);
        });
        
        return array_values($filtered_pages);
    }
    
    /**
     * Exclude custom parts from navigation menu meta boxes
     * Hides them from "Add menu items" panel
     */
    public function exclude_from_nav_menu($post_type_obj) {
        $custom_part_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];
        
        if (is_object($post_type_obj) && in_array($post_type_obj->name, $custom_part_types)) {
            // Return null to exclude from nav menu meta box
            return null;
        }
        
        return $post_type_obj;
    }
    
    /**
     * Prevent direct access to custom parts
     * These are elements only, not standalone pages
     */
    public function prevent_direct_access() {
        // Check if we're viewing a single post
        if (!is_singular()) {
            return;
        }
        
        global $post;
        
        // Check if it's one of our custom part types
        $custom_part_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];
        
        if (in_array($post->post_type, $custom_part_types)) {
            // Redirect to 404 - these are elements, not pages
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            
            // Show a friendly message for logged-in users
            if (current_user_can('edit_posts')) {
                wp_die(
                    '<div style="max-width: 600px; margin: 100px auto; padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); text-align: center; font-family: -apple-system, BlinkMacSystemFont, sans-serif;">' .
                    '<div style="font-size: 64px; margin-bottom: 20px;">📌</div>' .
                    '<h1 style="color: #1f2937; margin-bottom: 10px;">This is an Element, Not a Page</h1>' .
                    '<p style="color: #6b7280; font-size: 16px; line-height: 1.6; margin-bottom: 30px;">' .
                    'Headers, footers, sliders, and sidebars cannot be accessed directly via URL.<br>' .
                    'They are reusable elements that you insert into pages using shortcodes.' .
                    '</p>' .
                    '<div style="background: #f3f4f6; padding: 20px; border-radius: 6px; margin-bottom: 30px;">' .
                    '<strong style="color: #1f2937;">How to use this element:</strong><br>' .
                    '<code style="background: white; padding: 8px 16px; border-radius: 4px; display: inline-block; margin-top: 10px; color: #667eea; font-size: 14px;">' .
                    '[' . str_replace('pb_', '', $post->post_type) . ' id="' . $post->ID . '"]' .
                    '</code>' .
                    '</div>' .
                    '<a href="' . admin_url('edit.php?post_type=' . $post->post_type) . '" style="display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 600;">Go to ' . ucfirst(str_replace('pb_', '', $post->post_type)) . 's</a>' .
                    '</div>',
                    'Element Not Accessible',
                    ['response' => 404]
                );
            }
            
            // For non-logged-in users, show standard 404
            get_template_part(404);
            exit;
        }
    }
    
    /**
     * Render a part (header/footer/slider/sidebar)
     */
    private function render_part($part_id, $expected_type = '') {
        $post = get_post($part_id);
        
        if (!$post || ($expected_type && $post->post_type !== $expected_type)) {
            return '<p style="color: red;">' . __('Part not found', 'probuilder') . '</p>';
        }
        
        // Check if published
        if ($post->post_status !== 'publish') {
            if (current_user_can('edit_posts')) {
                return '<p style="background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;">' . 
                       sprintf(__('This %s is not published yet. Only you can see this message.', 'probuilder'), str_replace('pb_', '', $post->post_type)) . 
                       '</p>';
            }
            return '';
        }
        
        // Get ProBuilder data
        $probuilder_data = get_post_meta($part_id, '_probuilder_data', true);
        
        if (empty($probuilder_data)) {
            if (current_user_can('edit_posts')) {
                $edit_url = add_query_arg([
                    'p' => $part_id,
                    'probuilder' => 'true',
                    'post_type' => $post->post_type,
                ], home_url('/'));
                
                return '<div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">' . 
                       '<strong>' . __('No content yet!', 'probuilder') . '</strong><br>' .
                       sprintf(__('This %s has no content. ', 'probuilder'), str_replace('pb_', '', $post->post_type)) . 
                       '<a href="' . esc_url($edit_url) . '" style="color: #92003b; font-weight: 600;">' . __('Edit with ProBuilder', 'probuilder') . '</a>' .
                       '</div>';
            }
            return '';
        }
        
        // Render ProBuilder content
        ob_start();
        
        $part_class = 'probuilder-' . str_replace('pb_', '', $post->post_type);
        echo '<div class="' . esc_attr($part_class) . '" data-part-id="' . esc_attr($part_id) . '">';
        
        // Use ProBuilder frontend to render
        if (class_exists('ProBuilder_Frontend')) {
            $frontend = ProBuilder_Frontend::instance();
            foreach ($probuilder_data as $element) {
                $frontend->render_element($element);
            }
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }
    
    /**
     * Get part data
     */
    public static function get_part_data($part_id) {
        $data = get_post_meta($part_id, '_probuilder_data', true);
        return $data ?: [];
    }
}

// Initialize
ProBuilder_Custom_Parts::instance();

