<?php
/**
 * Theme Integration Class
 * Provides better integration with WordPress themes and template parts
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Theme_Integration {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Register template parts
        add_action('init', [$this, 'register_template_parts']);
        
        // Add theme support hooks
        add_action('after_setup_theme', [$this, 'add_theme_support']);
        
        // Template redirects
        add_filter('template_include', [$this, 'template_redirect'], 999);
        
        // Enqueue theme-specific styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_theme_styles']);
        
        // Add body classes
        add_filter('body_class', [$this, 'add_body_classes']);
        
        // AJAX handlers for template parts
        add_action('wp_ajax_probuilder_save_template_part', [$this, 'ajax_save_template_part']);
        add_action('wp_ajax_probuilder_get_template_parts', [$this, 'ajax_get_template_parts']);
    }
    
    /**
     * Register custom template parts post type
     */
    public function register_template_parts() {
        register_post_type('probuilder_part', [
            'labels' => [
                'name' => __('Template Parts', 'probuilder'),
                'singular_name' => __('Template Part', 'probuilder'),
                'add_new' => __('Add New Template Part', 'probuilder'),
                'add_new_item' => __('Add New Template Part', 'probuilder'),
                'edit_item' => __('Edit Template Part', 'probuilder'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'probuilder',
            'capability_type' => 'page',
            'supports' => ['title'],
        ]);
        
        // Register template part types taxonomy
        register_taxonomy('probuilder_part_type', 'probuilder_part', [
            'labels' => [
                'name' => __('Template Part Types', 'probuilder'),
                'singular_name' => __('Template Part Type', 'probuilder'),
            ],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
        ]);
        
        // Create default template part types
        $this->create_default_part_types();
    }
    
    /**
     * Create default template part types
     */
    private function create_default_part_types() {
        $types = [
            'header' => __('Header', 'probuilder'),
            'footer' => __('Footer', 'probuilder'),
            'sidebar' => __('Sidebar', 'probuilder'),
            'popup' => __('Popup', 'probuilder'),
            'archive' => __('Archive', 'probuilder'),
            'single' => __('Single Post', 'probuilder'),
            'page' => __('Page', 'probuilder'),
            'block' => __('Content Block', 'probuilder'),
        ];
        
        foreach ($types as $slug => $name) {
            if (!term_exists($slug, 'probuilder_part_type')) {
                wp_insert_term($name, 'probuilder_part_type', ['slug' => $slug]);
            }
        }
    }
    
    /**
     * Add theme support for ProBuilder features
     */
    public function add_theme_support() {
        // Add custom template support
        add_theme_support('probuilder');
        add_theme_support('probuilder-library');
        add_theme_support('probuilder-theme-style');
    }
    
    /**
     * Template redirect for full-width ProBuilder pages
     */
    public function template_redirect($template) {
        global $post;
        
        if (!is_singular() || !$post) {
            return $template;
        }
        
        // Check if ProBuilder is active for this page
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        $use_canvas = get_post_meta($post->ID, '_probuilder_canvas_mode', true);
        
        if (!empty($probuilder_data) && $use_canvas === 'yes') {
            // Use canvas template (full-width, no theme elements)
            $canvas_template = PROBUILDER_PATH . 'templates/canvas.php';
            if (file_exists($canvas_template)) {
                return $canvas_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Enqueue theme-specific styles
     */
    public function enqueue_theme_styles() {
        global $post;
        
        if (is_singular() && $post) {
            $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
            
            if (!empty($probuilder_data)) {
                // Add ProBuilder theme compatibility styles
                wp_add_inline_style('probuilder-frontend', $this->get_theme_compatibility_css());
            }
        }
    }
    
    /**
     * Get theme compatibility CSS
     */
    private function get_theme_compatibility_css() {
        return '
            .probuilder-content {
                max-width: 100%;
                width: 100%;
            }
            .probuilder-canvas-mode {
                margin: 0 !important;
                padding: 0 !important;
            }
            .probuilder-canvas-mode .site-header,
            .probuilder-canvas-mode .site-footer,
            .probuilder-canvas-mode .site-sidebar {
                display: none !important;
            }
        ';
    }
    
    /**
     * Add body classes for ProBuilder pages
     */
    public function add_body_classes($classes) {
        global $post;
        
        if (is_singular() && $post) {
            $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
            $use_canvas = get_post_meta($post->ID, '_probuilder_canvas_mode', true);
            
            if (!empty($probuilder_data)) {
                $classes[] = 'probuilder-page';
                
                if ($use_canvas === 'yes') {
                    $classes[] = 'probuilder-canvas-mode';
                }
            }
        }
        
        return $classes;
    }
    
    /**
     * Get template part by type
     */
    public function get_template_part($type, $default = '') {
        $args = [
            'post_type' => 'probuilder_part',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'tax_query' => [
                [
                    'taxonomy' => 'probuilder_part_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ],
        ];
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            $query->the_post();
            $part_id = get_the_ID();
            $part_data = get_post_meta($part_id, '_probuilder_data', true);
            wp_reset_postdata();
            
            if (!empty($part_data)) {
                return $part_data;
            }
        }
        
        return $default;
    }
    
    /**
     * Render template part
     */
    public function render_template_part($type) {
        $part_data = $this->get_template_part($type);
        
        if (!empty($part_data)) {
            $frontend = ProBuilder_Frontend::instance();
            echo $frontend->render_elements($part_data);
        }
    }
    
    /**
     * AJAX: Save template part
     */
    public function ajax_save_template_part() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $part_name = isset($_POST['part_name']) ? sanitize_text_field($_POST['part_name']) : '';
        $part_type = isset($_POST['part_type']) ? sanitize_text_field($_POST['part_type']) : '';
        $part_data = isset($_POST['part_data']) ? $_POST['part_data'] : [];
        $part_id = isset($_POST['part_id']) ? intval($_POST['part_id']) : 0;
        
        if (empty($part_name) || empty($part_type)) {
            wp_send_json_error(['message' => __('Part name and type are required', 'probuilder')]);
        }
        
        $post_data = [
            'post_title' => $part_name,
            'post_type' => 'probuilder_part',
            'post_status' => 'publish',
        ];
        
        if ($part_id) {
            $post_data['ID'] = $part_id;
            $part_id = wp_update_post($post_data);
        } else {
            $part_id = wp_insert_post($post_data);
        }
        
        if (is_wp_error($part_id)) {
            wp_send_json_error(['message' => $part_id->get_error_message()]);
        }
        
        // Save part data
        update_post_meta($part_id, '_probuilder_data', $part_data);
        
        // Set part type
        wp_set_object_terms($part_id, $part_type, 'probuilder_part_type');
        
        wp_send_json_success([
            'message' => __('Template part saved successfully!', 'probuilder'),
            'part_id' => $part_id,
        ]);
    }
    
    /**
     * AJAX: Get template parts list
     */
    public function ajax_get_template_parts() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $part_type = isset($_POST['part_type']) ? sanitize_text_field($_POST['part_type']) : '';
        
        $args = [
            'post_type' => 'probuilder_part',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];
        
        if (!empty($part_type)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'probuilder_part_type',
                    'field' => 'slug',
                    'terms' => $part_type,
                ],
            ];
        }
        
        $query = new WP_Query($args);
        $parts = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $types = wp_get_post_terms($post_id, 'probuilder_part_type', ['fields' => 'slugs']);
                
                $parts[] = [
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'type' => !empty($types) ? $types[0] : '',
                    'data' => get_post_meta($post_id, '_probuilder_data', true),
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success(['parts' => $parts]);
    }
    
    /**
     * Get available header templates
     */
    public function get_header_templates() {
        return $this->get_templates_by_type('header');
    }
    
    /**
     * Get available footer templates
     */
    public function get_footer_templates() {
        return $this->get_templates_by_type('footer');
    }
    
    /**
     * Get templates by type
     */
    private function get_templates_by_type($type) {
        $args = [
            'post_type' => 'probuilder_part',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => [
                [
                    'taxonomy' => 'probuilder_part_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ],
        ];
        
        $query = new WP_Query($args);
        $templates = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $templates[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                ];
            }
            wp_reset_postdata();
        }
        
        return $templates;
    }
}

