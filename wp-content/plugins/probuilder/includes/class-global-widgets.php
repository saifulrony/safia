<?php
/**
 * Global Widgets System
 * Reusable widget instances across pages
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Global_Widgets {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'register_global_widget_post_type']);
        add_action('wp_ajax_probuilder_save_global_widget', [$this, 'ajax_save_global_widget']);
        add_action('wp_ajax_probuilder_get_global_widgets', [$this, 'ajax_get_global_widgets']);
        add_action('wp_ajax_probuilder_link_to_global', [$this, 'ajax_link_to_global']);
        add_action('wp_ajax_probuilder_unlink_from_global', [$this, 'ajax_unlink_from_global']);
    }
    
    /**
     * Register global widget post type
     */
    public function register_global_widget_post_type() {
        register_post_type('pb_global_widget', [
            'labels' => [
                'name' => __('Global Widgets', 'probuilder'),
                'singular_name' => __('Global Widget', 'probuilder'),
                'add_new' => __('Add New Global Widget', 'probuilder'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'probuilder',
            'supports' => ['title'],
            'capability_type' => 'page',
        ]);
    }
    
    /**
     * Save global widget
     */
    public function ajax_save_global_widget() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $widget_id = intval($_POST['widget_id']);
        $data = json_decode(stripslashes($_POST['data']), true);
        $title = sanitize_text_field($_POST['title']);
        
        if ($widget_id > 0) {
            // Update existing
            wp_update_post([
                'ID' => $widget_id,
                'post_title' => $title
            ]);
        } else {
            // Create new
            $widget_id = wp_insert_post([
                'post_type' => 'pb_global_widget',
                'post_title' => $title,
                'post_status' => 'publish'
            ]);
        }
        
        update_post_meta($widget_id, '_probuilder_widget_data', $data);
        
        // Update all instances
        $this->update_all_instances($widget_id, $data);
        
        wp_send_json_success([
            'id' => $widget_id,
            'message' => __('Global widget saved', 'probuilder')
        ]);
    }
    
    /**
     * Get global widgets
     */
    public function ajax_get_global_widgets() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $widgets = get_posts([
            'post_type' => 'pb_global_widget',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        $widget_list = [];
        foreach ($widgets as $widget) {
            $widget_list[] = [
                'id' => $widget->ID,
                'title' => $widget->post_title,
                'data' => get_post_meta($widget->ID, '_probuilder_widget_data', true),
                'usage_count' => $this->get_usage_count($widget->ID)
            ];
        }
        
        wp_send_json_success($widget_list);
    }
    
    /**
     * Link element to global widget
     */
    public function ajax_link_to_global() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $element_id = sanitize_text_field($_POST['element_id']);
        $global_widget_id = intval($_POST['global_widget_id']);
        
        // Save reference
        $references = get_post_meta($global_widget_id, '_probuilder_widget_references', true) ?: [];
        $references[] = [
            'post_id' => $post_id,
            'element_id' => $element_id
        ];
        update_post_meta($global_widget_id, '_probuilder_widget_references', $references);
        
        wp_send_json_success(['message' => 'Linked to global widget']);
    }
    
    /**
     * Unlink element from global widget
     */
    public function ajax_unlink_from_global() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $element_id = sanitize_text_field($_POST['element_id']);
        $global_widget_id = intval($_POST['global_widget_id']);
        
        // Remove reference
        $references = get_post_meta($global_widget_id, '_probuilder_widget_references', true) ?: [];
        $references = array_filter($references, function($ref) use ($post_id, $element_id) {
            return !($ref['post_id'] == $post_id && $ref['element_id'] == $element_id);
        });
        update_post_meta($global_widget_id, '_probuilder_widget_references', $references);
        
        wp_send_json_success(['message' => 'Unlinked from global widget']);
    }
    
    /**
     * Update all instances of a global widget
     */
    private function update_all_instances($widget_id, $data) {
        $references = get_post_meta($widget_id, '_probuilder_widget_references', true) ?: [];
        
        foreach ($references as $ref) {
            $post_data = get_post_meta($ref['post_id'], '_probuilder_data', true);
            
            if ($post_data && is_array($post_data)) {
                $post_data = $this->update_element_in_tree($post_data, $ref['element_id'], $data);
                update_post_meta($ref['post_id'], '_probuilder_data', $post_data);
            }
        }
    }
    
    /**
     * Update element in tree recursively
     */
    private function update_element_in_tree($elements, $element_id, $new_data) {
        foreach ($elements as &$element) {
            if ($element['id'] === $element_id) {
                $element['settings'] = $new_data['settings'];
            }
            
            if (isset($element['children']) && is_array($element['children'])) {
                $element['children'] = $this->update_element_in_tree($element['children'], $element_id, $new_data);
            }
        }
        
        return $elements;
    }
    
    /**
     * Get usage count for a global widget
     */
    private function get_usage_count($widget_id) {
        $references = get_post_meta($widget_id, '_probuilder_widget_references', true);
        return is_array($references) ? count($references) : 0;
    }
}

