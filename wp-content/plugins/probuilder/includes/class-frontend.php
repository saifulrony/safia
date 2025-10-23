<?php
/**
 * Frontend Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Frontend {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_filter('the_content', [$this, 'render_content'], 999);
    }
    
    /**
     * Render ProBuilder content
     */
    public function render_content($content) {
        global $post;
        
        if (!is_singular() || !$post) {
            return $content;
        }
        
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        
        if (empty($probuilder_data)) {
            return $content;
        }
        
        return $this->render_elements($probuilder_data);
    }
    
    /**
     * Render elements
     */
    public function render_elements($elements) {
        if (!is_array($elements)) {
            $elements = json_decode($elements, true);
        }
        
        if (empty($elements)) {
            return '';
        }
        
        ob_start();
        
        echo '<div class="probuilder-content">';
        
        foreach ($elements as $element) {
            $this->render_element($element);
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }
    
    /**
     * Render single element
     */
    public function render_element($element) {
        $widget_name = isset($element['widgetType']) ? $element['widgetType'] : '';
        $settings = isset($element['settings']) ? $element['settings'] : [];
        $children = isset($element['children']) ? $element['children'] : [];
        
        $widget = ProBuilder_Widgets_Manager::instance()->get_widget($widget_name);
        
        if (!$widget) {
            return;
        }
        
        $element_id = isset($element['id']) ? $element['id'] : uniqid('pb-');
        
        echo '<div id="' . esc_attr($element_id) . '" class="probuilder-element probuilder-widget-' . esc_attr($widget_name) . '">';
        
        // Pass children to widget for container-type widgets
        $settings['_children'] = $children;
        
        // Render widget
        $widget->render_widget($settings);
        
        echo '</div>';
    }
}

