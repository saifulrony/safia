<?php
/**
 * ProBuilder Template Parts System
 * Create custom Slides, Headers, Footers, and more
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Template_Parts {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        add_filter('manage_probuilder_part_posts_columns', [$this, 'custom_columns']);
        add_action('manage_probuilder_part_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
        add_action('admin_menu', [$this, 'add_admin_menu'], 100);
        add_action('wp_ajax_probuilder_get_template_parts', [$this, 'ajax_get_template_parts']);
    }
    
    /**
     * Register custom post type
     */
    public function register_post_type() {
        $labels = [
            'name' => __('ProBuilder Parts', 'probuilder'),
            'singular_name' => __('ProBuilder Part', 'probuilder'),
            'add_new' => __('Add New', 'probuilder'),
            'add_new_item' => __('Add New Part', 'probuilder'),
            'edit_item' => __('Edit Part', 'probuilder'),
            'new_item' => __('New Part', 'probuilder'),
            'view_item' => __('View Part', 'probuilder'),
            'search_items' => __('Search Parts', 'probuilder'),
            'not_found' => __('No parts found', 'probuilder'),
            'not_found_in_trash' => __('No parts found in Trash', 'probuilder'),
            'all_items' => __('All Parts', 'probuilder'),
            'menu_name' => __('Template Parts', 'probuilder'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false, // We'll add custom menu
            'query_var' => true,
            'rewrite' => ['slug' => 'probuilder-part'],
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-layout',
            'supports' => ['title', 'editor', 'revisions'],
        ];
        
        register_post_type('probuilder_part', $args);
    }
    
    /**
     * Add custom admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=page',
            __('Template Parts', 'probuilder'),
            __('Template Parts', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=probuilder_part'
        );
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'probuilder_part_settings',
            __('Part Settings', 'probuilder'),
            [$this, 'render_settings_meta_box'],
            'probuilder_part',
            'side',
            'high'
        );
    }
    
    /**
     * Render settings meta box
     */
    public function render_settings_meta_box($post) {
        wp_nonce_field('probuilder_part_settings', 'probuilder_part_nonce');
        
        $part_type = get_post_meta($post->ID, '_probuilder_part_type', true) ?: 'slide';
        $part_category = get_post_meta($post->ID, '_probuilder_part_category', true) ?: 'general';
        ?>
        <div class="probuilder-part-settings">
            <p>
                <label for="probuilder_part_type" style="font-weight: 600; display: block; margin-bottom: 8px;">
                    <?php _e('Part Type', 'probuilder'); ?>
                </label>
                <select name="probuilder_part_type" id="probuilder_part_type" style="width: 100%;">
                    <option value="slide" <?php selected($part_type, 'slide'); ?>>
                        🎬 <?php _e('Slider Slide', 'probuilder'); ?>
                    </option>
                    <option value="header" <?php selected($part_type, 'header'); ?>>
                        📌 <?php _e('Header', 'probuilder'); ?>
                    </option>
                    <option value="footer" <?php selected($part_type, 'footer'); ?>>
                        📎 <?php _e('Footer', 'probuilder'); ?>
                    </option>
                    <option value="section" <?php selected($part_type, 'section'); ?>>
                        📦 <?php _e('Content Section', 'probuilder'); ?>
                    </option>
                    <option value="popup" <?php selected($part_type, 'popup'); ?>>
                        🔔 <?php _e('Popup', 'probuilder'); ?>
                    </option>
                </select>
            </p>
            
            <p>
                <label for="probuilder_part_category" style="font-weight: 600; display: block; margin-bottom: 8px;">
                    <?php _e('Category', 'probuilder'); ?>
                </label>
                <select name="probuilder_part_category" id="probuilder_part_category" style="width: 100%;">
                    <option value="general" <?php selected($part_category, 'general'); ?>>
                        <?php _e('General', 'probuilder'); ?>
                    </option>
                    <option value="ecommerce" <?php selected($part_category, 'ecommerce'); ?>>
                        <?php _e('E-commerce', 'probuilder'); ?>
                    </option>
                    <option value="hero" <?php selected($part_category, 'hero'); ?>>
                        <?php _e('Hero Section', 'probuilder'); ?>
                    </option>
                    <option value="testimonial" <?php selected($part_category, 'testimonial'); ?>>
                        <?php _e('Testimonials', 'probuilder'); ?>
                    </option>
                    <option value="cta" <?php selected($part_category, 'cta'); ?>>
                        <?php _e('Call to Action', 'probuilder'); ?>
                    </option>
                    <option value="features" <?php selected($part_category, 'features'); ?>>
                        <?php _e('Features', 'probuilder'); ?>
                    </option>
                </select>
            </p>
            
            <div style="margin-top: 15px; padding: 12px; background: #f0f6fc; border-left: 3px solid #0066cc; font-size: 12px; line-height: 1.5;">
                <strong>💡 Quick Tip:</strong><br>
                After saving, click "Edit with ProBuilder" to design your part using drag & drop!
            </div>
        </div>
        
        <style>
            .probuilder-part-settings select {
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 13px;
            }
            .probuilder-part-settings select:focus {
                border-color: #0066cc;
                outline: none;
                box-shadow: 0 0 0 1px #0066cc;
            }
        </style>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['probuilder_part_nonce']) || !wp_verify_nonce($_POST['probuilder_part_nonce'], 'probuilder_part_settings')) {
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
        
        // Save part type
        if (isset($_POST['probuilder_part_type'])) {
            update_post_meta($post_id, '_probuilder_part_type', sanitize_text_field($_POST['probuilder_part_type']));
        }
        
        // Save part category
        if (isset($_POST['probuilder_part_category'])) {
            update_post_meta($post_id, '_probuilder_part_category', sanitize_text_field($_POST['probuilder_part_category']));
        }
    }
    
    /**
     * Custom admin columns
     */
    public function custom_columns($columns) {
        $new_columns = [];
        
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            
            if ($key === 'title') {
                $new_columns['part_type'] = __('Type', 'probuilder');
                $new_columns['part_category'] = __('Category', 'probuilder');
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Custom column content
     */
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'part_type':
                $part_type = get_post_meta($post_id, '_probuilder_part_type', true) ?: 'slide';
                $types = [
                    'slide' => '🎬 Slider Slide',
                    'header' => '📌 Header',
                    'footer' => '📎 Footer',
                    'section' => '📦 Section',
                    'popup' => '🔔 Popup',
                ];
                echo '<strong>' . ($types[$part_type] ?? $part_type) . '</strong>';
                break;
                
            case 'part_category':
                $category = get_post_meta($post_id, '_probuilder_part_category', true) ?: 'general';
                echo '<span style="display: inline-block; padding: 3px 8px; background: #e0e5ff; color: #0066cc; border-radius: 3px; font-size: 11px; font-weight: 600;">' . ucfirst($category) . '</span>';
                break;
        }
    }
    
    /**
     * AJAX: Get template parts for selection
     */
    public function ajax_get_template_parts() {
        $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
        
        $args = [
            'post_type' => 'probuilder_part',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ];
        
        if ($type) {
            $args['meta_query'] = [
                [
                    'key' => '_probuilder_part_type',
                    'value' => $type,
                    'compare' => '='
                ]
            ];
        }
        
        $query = new WP_Query($args);
        $parts = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                
                $parts[] = [
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'type' => get_post_meta($post_id, '_probuilder_part_type', true),
                    'category' => get_post_meta($post_id, '_probuilder_part_category', true),
                    'edit_url' => admin_url('post.php?post=' . $post_id . '&action=edit'),
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success($parts);
    }
    
    /**
     * Get part data by ID
     */
    public static function get_part_data($part_id) {
        $data = get_post_meta($part_id, '_probuilder_data', true);
        return $data ?: [];
    }
    
    /**
     * Render part by ID
     */
    public static function render_part($part_id, $echo = true) {
        $data = self::get_part_data($part_id);
        
        if (empty($data)) {
            return '';
        }
        
        ob_start();
        
        $frontend = ProBuilder_Frontend::instance();
        foreach ($data as $element) {
            $frontend->render_element($element);
        }
        
        $output = ob_get_clean();
        
        if ($echo) {
            echo $output;
        }
        
        return $output;
    }
}

// Initialize
ProBuilder_Template_Parts::instance();

