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
        add_action('manage_pb_header_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        add_action('manage_pb_footer_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        add_action('manage_pb_slider_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
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
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'pb-header'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-align-center',
            'supports' => ['title', 'revisions'],
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
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'pb-footer'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-align-full-width',
            'supports' => ['title', 'revisions'],
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
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'pb-slider'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title', 'revisions'],
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
        $post_types = ['pb_header', 'pb_footer', 'pb_slider'];
        
        if (in_array($post->post_type, $post_types)) {
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
        $post_types = ['pb_header', 'pb_footer', 'pb_slider'];
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'probuilder_part_info',
                __('ProBuilder Info', 'probuilder'),
                [$this, 'render_info_meta_box'],
                $post_type,
                'side',
                'high'
            );
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
    public function save_meta_boxes($post_id) {
        // Placeholder for future meta data
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
    
    private function custom_columns($columns, $type) {
        $new_columns = [];
        
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            
            if ($key === 'title') {
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
     * Render a part (header/footer/slider)
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

