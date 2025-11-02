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
        // Use priority 9999 to ensure ProBuilder runs AFTER Elementor (which uses priority 9)
        add_filter('the_content', [$this, 'render_content'], 9999);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_styles']);
        
        // Disable Elementor for ProBuilder pages
        add_action('template_redirect', [$this, 'disable_elementor_for_probuilder'], 1);
    }
    
    /**
     * Disable Elementor rendering for ProBuilder pages
     */
    public function disable_elementor_for_probuilder() {
        global $post;
        
        if (!is_singular() || !$post) {
            return;
        }
        
        // Check if this page uses ProBuilder
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        
        if (!empty($probuilder_data)) {
            // This is a ProBuilder page - disable Elementor
            if (class_exists('\Elementor\Plugin')) {
                remove_action('template_redirect', [\Elementor\Plugin::instance()->frontend, 'init'], 0);
                add_filter('elementor/frontend/builder_content_data', '__return_null');
            }
            
            // Remove Elementor edit mode flag if it exists
            if (get_post_meta($post->ID, '_elementor_edit_mode', true) === 'builder') {
                delete_post_meta($post->ID, '_elementor_edit_mode');
            }
        }
    }
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_frontend_styles() {
        // Enqueue frontend CSS for all pages
        wp_enqueue_style('probuilder-frontend', PROBUILDER_URL . 'assets/css/frontend.css', [], PROBUILDER_VERSION);
        
        // Only on pages with ProBuilder data
        global $post;
        if ($post) {
            $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
            if (!empty($probuilder_data)) {
                // Add inline CSS to ensure content displays
                wp_add_inline_style('probuilder-frontend', '
                    .probuilder-content {
                        display: block !important;
                        visibility: visible !important;
                        opacity: 1 !important;
                        width: 100% !important;
                        clear: both !important;
                    }
                    .probuilder-element {
                        display: block !important;
                        visibility: visible !important;
                    }
                    /* Responsive visibility */
                    @media (min-width: 1025px) {
                        .probuilder-hide-desktop { display: none !important; }
                    }
                    @media (min-width: 768px) and (max-width: 1024px) {
                        .probuilder-hide-tablet { display: none !important; }
                    }
                    @media (max-width: 767px) {
                        .probuilder-hide-mobile { display: none !important; }
                    }
                ');
            }
        }
    }
    
    /**
     * Render ProBuilder content with caching
     */
    public function render_content($content) {
        global $post;
        
        if (!is_singular() || !$post) {
            return $content;
        }
        
        // ALWAYS get fresh data - no cache (to prevent showing old content)
        wp_cache_delete($post->ID, 'post_meta');
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        
        // Debug logging (only for logged-in users)
        if (current_user_can('edit_posts')) {
            error_log('=== ProBuilder Frontend Render ===');
            error_log('Post ID: ' . $post->ID);
            error_log('Post Title: ' . $post->post_title);
            error_log('Post Slug: ' . $post->post_name);
            error_log('Data exists: ' . (!empty($probuilder_data) ? 'YES' : 'NO'));
            if (!empty($probuilder_data)) {
                error_log('Data type: ' . gettype($probuilder_data));
                if (is_string($probuilder_data)) {
                    error_log('Data length: ' . strlen($probuilder_data));
                    error_log('First 200 chars: ' . substr($probuilder_data, 0, 200));
                } elseif (is_array($probuilder_data)) {
                    error_log('Array count: ' . count($probuilder_data));
                    if (count($probuilder_data) > 0) {
                        error_log('First element type: ' . (isset($probuilder_data[0]['widgetType']) ? $probuilder_data[0]['widgetType'] : 'unknown'));
                    }
                }
            }
        }
        
        if (empty($probuilder_data)) {
            // No ProBuilder data, return original content
            return $content;
        }
        
        // Parse data if it's a JSON string
        if (is_string($probuilder_data)) {
            $decoded = json_decode($probuilder_data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $probuilder_data = $decoded;
            }
        }
        
        // Ensure data is an array
        if (!is_array($probuilder_data)) {
            error_log('ProBuilder Frontend - ERROR: Data is not an array after parsing');
            return $content;
        }
        
        // If array is empty, return original content
        if (count($probuilder_data) === 0) {
            error_log('ProBuilder Frontend - Data array is empty');
            return $content;
        }
        
        // Render ProBuilder elements
        $output = $this->render_elements($probuilder_data);
        
        // Debug output
        if (current_user_can('edit_posts')) {
            error_log('ProBuilder Frontend - Rendered output length: ' . strlen($output));
        }
        
        // Return ProBuilder content (replacing original content)
        return $output;
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
        
        echo '<div class="probuilder-content" style="width: 100%; display: block; visibility: visible; opacity: 1;">';
        
        foreach ($elements as $index => $element) {
            $this->render_element($element);
        }
        
        echo '</div>';
        
        if (current_user_can('edit_posts')) {
            // Add simple floating edit button
            global $post;
            echo '<a href="' . add_query_arg(['p' => $post->ID, 'probuilder' => 'true'], home_url('/')) . '" style="
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #92003b;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 99999;
                font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            ">✏️ Edit with ProBuilder</a>';
        }
        
        return ob_get_clean();
    }
    
    /**
     * Render single element
     */
    public function render_element($element) {
        $widget_name = isset($element['widgetType']) ? $element['widgetType'] : '';
        $settings = isset($element['settings']) ? $element['settings'] : [];
        $children = isset($element['children']) ? $element['children'] : [];
        
        if (empty($widget_name)) {
            if (current_user_can('edit_posts')) {
                echo '<div style="padding: 20px; background: #fee; border: 2px solid #dc2626; border-radius: 8px; margin: 10px 0;">';
                echo '<strong>⚠️ Error:</strong> Element has no widget type!';
                echo '</div>';
            }
            return;
        }
        
        $widget = ProBuilder_Widgets_Manager::instance()->get_widget($widget_name);
        
        if (!$widget) {
            if (current_user_can('edit_posts')) {
                echo '<div style="padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; margin: 10px 0;">';
                echo '<strong>⚠️ Widget Not Found:</strong> ' . esc_html($widget_name);
                echo '<br><small>This widget is not registered or the file is missing.</small>';
                echo '</div>';
            }
            error_log('ProBuilder Frontend - Widget not found: ' . $widget_name);
            return;
        }
        
        $element_id = isset($element['id']) ? $element['id'] : uniqid('pb-');
        
        echo '<div id="' . esc_attr($element_id) . '" class="probuilder-element probuilder-widget-' . esc_attr($widget_name) . '" style="display: block; visibility: visible;">';
        
        // Pass children to widget for container-type widgets
        $settings['_children'] = $children;
        
        try {
            // Render widget with error catching
            ob_start();
            $widget->render_widget($settings);
            $widget_output = ob_get_clean();
            
            if (empty(trim($widget_output))) {
                if (current_user_can('edit_posts')) {
                    echo '<div style="padding: 15px; background: #e6f3ff; border: 2px dashed #667eea; border-radius: 6px; color: #004085;">';
                    echo '<strong>ℹ️ Empty Output:</strong> Widget "' . esc_html($widget_name) . '" rendered but produced no output.';
                    echo '</div>';
                }
            } else {
                echo $widget_output;
            }
        } catch (Exception $e) {
            if (current_user_can('edit_posts')) {
                echo '<div style="padding: 20px; background: #fee; border: 2px solid #dc2626; border-radius: 8px;">';
                echo '<strong>❌ Rendering Error:</strong> ' . esc_html($e->getMessage());
                echo '<br><small>Widget: ' . esc_html($widget_name) . '</small>';
                echo '</div>';
            }
            error_log('ProBuilder Frontend - Rendering error for ' . $widget_name . ': ' . $e->getMessage());
        }
        
        echo '</div>';
    }
}


