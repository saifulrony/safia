<?php
/**
 * Navigator Panel Class
 * Element tree view with drag & drop reordering
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Navigator {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_probuilder_get_navigator_tree', [$this, 'ajax_get_navigator_tree']);
        add_action('wp_ajax_probuilder_reorder_elements', [$this, 'ajax_reorder_elements']);
    }
    
    /**
     * Get navigator tree structure
     */
    public function ajax_get_navigator_tree() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $elements = get_post_meta($post_id, '_probuilder_data', true);
        
        if (!$elements) {
            $elements = [];
        }
        
        $tree = $this->build_tree_structure($elements);
        
        wp_send_json_success([
            'tree' => $tree,
            'count' => count($elements)
        ]);
    }
    
    /**
     * Build hierarchical tree structure
     */
    private function build_tree_structure($elements) {
        $tree = [];
        
        foreach ($elements as $element) {
            $tree_item = [
                'id' => $element['id'],
                'type' => $element['type'],
                'icon' => $this->get_element_icon($element['type']),
                'label' => $this->get_element_label($element),
                'visible' => !isset($element['hidden']) || !$element['hidden'],
                'locked' => isset($element['locked']) && $element['locked'],
                'children' => []
            ];
            
            // Add children for containers
            if (in_array($element['type'], ['container', 'section', 'column'])) {
                if (isset($element['children']) && is_array($element['children'])) {
                    $tree_item['children'] = $this->build_tree_structure($element['children']);
                }
            }
            
            $tree[] = $tree_item;
        }
        
        return $tree;
    }
    
    /**
     * Get element icon
     */
    private function get_element_icon($type) {
        $icons = [
            'container' => 'fa-layer-group',
            'section' => 'fa-square',
            'column' => 'fa-columns',
            'heading' => 'fa-heading',
            'text' => 'fa-paragraph',
            'button' => 'fa-mouse-pointer',
            'image' => 'fa-image',
            'video' => 'fa-video',
            'icon-box' => 'fa-star',
            'testimonial' => 'fa-quote-right',
            'slider' => 'fa-sliders-h',
            'gallery' => 'fa-images',
            'form' => 'fa-wpforms'
        ];
        
        return isset($icons[$type]) ? $icons[$type] : 'fa-cube';
    }
    
    /**
     * Get element label
     */
    private function get_element_label($element) {
        // Try to get a meaningful label from element content
        if (isset($element['settings']['title']) && !empty($element['settings']['title'])) {
            return strip_tags($element['settings']['title']);
        }
        
        if (isset($element['settings']['text']) && !empty($element['settings']['text'])) {
            $text = strip_tags($element['settings']['text']);
            return strlen($text) > 30 ? substr($text, 0, 30) . '...' : $text;
        }
        
        // Return widget name
        return ucfirst(str_replace(['_', '-'], ' ', $element['type']));
    }
    
    /**
     * Reorder elements via drag & drop
     */
    public function ajax_reorder_elements() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $order = json_decode(stripslashes($_POST['order']), true);
        
        // Update element order
        $elements = get_post_meta($post_id, '_probuilder_data', true);
        if (!$elements) {
            $elements = [];
        }
        
        $reordered = $this->apply_order($elements, $order);
        
        update_post_meta($post_id, '_probuilder_data', $reordered);
        
        wp_send_json_success([
            'message' => 'Elements reordered successfully'
        ]);
    }
    
    /**
     * Apply new order to elements
     */
    private function apply_order($elements, $order) {
        $reordered = [];
        
        foreach ($order as $item) {
            foreach ($elements as $element) {
                if ($element['id'] === $item['id']) {
                    $reordered[] = $element;
                    break;
                }
            }
        }
        
        return $reordered;
    }
}

