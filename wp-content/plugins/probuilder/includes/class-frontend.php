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
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_styles']);
    }
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_frontend_styles() {
        // Only on pages with ProBuilder data
        global $post;
        if ($post) {
            $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
            if (!empty($probuilder_data)) {
                // Add inline CSS to ensure content displays
                wp_add_inline_style('wp-block-library', '
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
        
        // Debug comment for logged-in users
        if (current_user_can('edit_posts')) {
            echo '<!-- ProBuilder Content Start - ' . count($elements) . ' element(s) -->' . "\n";
            
            // Add visible debug panel for admins
            echo '<div style="background: #344047; color: white; padding: 15px; margin-bottom: 20px; border-radius: 8px; font-family: monospace; font-size: 12px;">';
            echo '<strong style="font-size: 14px;">🔍 ProBuilder Debug (Admin Only)</strong><br>';
            echo 'Post ID: ' . get_the_ID() . '<br>';
            echo 'Post Title: ' . get_the_title() . '<br>';
            echo 'Post Slug: ' . $post->post_name . '<br>';
            echo 'Elements: ' . count($elements) . '<br>';
            echo '<details style="margin-top: 10px;"><summary style="cursor: pointer;">Show Element Details</summary>';
            echo '<div style="background: rgba(255,255,255,0.1); padding: 10px; margin-top: 10px; border-radius: 4px;">';
            foreach ($elements as $index => $element) {
                echo 'Element ' . ($index + 1) . ': ' . (isset($element['widgetType']) ? $element['widgetType'] : 'unknown');
                if (isset($element['widgetType']) && $element['widgetType'] === 'heading' && isset($element['settings']['title'])) {
                    echo ' - <span style="color: #4ade80;">"' . esc_html($element['settings']['title']) . '"</span>';
                }
                echo '<br>';
            }
            echo '</div></details>';
            echo '</div>';
        }
        
        echo '<div class="probuilder-content" style="width: 100%; display: block; visibility: visible; opacity: 1;">';
        
        foreach ($elements as $index => $element) {
            if (current_user_can('edit_posts')) {
                echo '<!-- Element ' . ($index + 1) . ': ' . (isset($element['widgetType']) ? $element['widgetType'] : 'unknown') . ' -->' . "\n";
            }
            $this->render_element($element);
        }
        
        echo '</div>';
        
        if (current_user_can('edit_posts')) {
            echo "\n" . '<!-- ProBuilder Content End -->';
            
            // Add floating debug panel for easy access
            global $post;
            echo '
            <div id="probuilder-debug-panel" style="
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #344047;
                color: white;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 99999;
                max-width: 350px;
                font-family: -apple-system, BlinkMacSystemFont, sans-serif;
                font-size: 13px;
                line-height: 1.6;
            ">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <strong style="font-size: 15px;">🔍 ProBuilder Debug</strong>
                    <button onclick="this.parentElement.parentElement.remove()" style="
                        background: rgba(255,255,255,0.2);
                        border: none;
                        color: white;
                        width: 24px;
                        height: 24px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 16px;
                    ">×</button>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                    <strong>📄 Page Info:</strong><br>
                    ID: ' . $post->ID . '<br>
                    Title: ' . esc_html($post->post_title) . '<br>
                    Slug: ' . esc_html($post->post_name) . '
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                    <strong>🎨 ProBuilder:</strong><br>
                    Elements: ' . count($elements) . '<br>
                    Status: <span style="color: #4ade80;">Active ✓</span>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="' . add_query_arg(['p' => $post->ID, 'probuilder' => 'true'], home_url('/')) . '" style="
                        flex: 1;
                        background: #22c55e;
                        color: white;
                        padding: 10px;
                        text-align: center;
                        border-radius: 6px;
                        text-decoration: none;
                        font-weight: 600;
                        font-size: 12px;
                    ">✏️ Edit</a>
                    <a href="' . admin_url('plugins.php?page=probuilder-clear-cache&post_id=' . $post->ID) . '" style="
                        background: #dc2626;
                        color: white;
                        padding: 10px 12px;
                        text-align: center;
                        border-radius: 6px;
                        text-decoration: none;
                        font-weight: 600;
                        font-size: 12px;
                    ">🗑️</a>
                </div>
                <div style="margin-top: 10px; font-size: 11px; color: rgba(255,255,255,0.7); text-align: center;">
                    Admin only • Not visible to visitors
                </div>
            </div>';
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


