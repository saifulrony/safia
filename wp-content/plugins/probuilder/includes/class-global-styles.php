<?php
/**
 * Global Styles System
 * Site-wide colors, fonts, and design tokens
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Global_Styles {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_probuilder_get_global_styles', [$this, 'ajax_get_global_styles']);
        add_action('wp_ajax_probuilder_save_global_styles', [$this, 'ajax_save_global_styles']);
        add_action('wp_head', [$this, 'output_global_styles'], 1);
        add_action('admin_head', [$this, 'output_global_styles'], 1);
    }
    
    /**
     * Get default global styles
     */
    public function get_default_styles() {
        return [
            'layout' => [
                'content_width' => 'boxed', // 'full' or 'boxed'
                'boxed_width' => '1400px',
                'boxed_padding' => '15px'
            ],
            'colors' => [
                'primary' => '#344047',
                'secondary' => '#2c3e50',
                'accent' => '#4a5a6b',
                'text' => '#495157',
                'text_light' => '#7f8c8d',
                'heading' => '#2c3e50',
                'background' => '#ffffff',
                'background_alt' => '#f8f9fa',
                'border' => '#e1e8ed',
                'success' => '#27ae60',
                'warning' => '#f39c12',
                'error' => '#e74c3c',
                'info' => '#3498db'
            ],
            'typography' => [
                'font_family_primary' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif',
                'font_family_secondary' => 'Georgia, "Times New Roman", serif',
                'font_family_code' => '"Courier New", Courier, monospace',
                'h1_size' => '48px',
                'h1_weight' => '700',
                'h1_line_height' => '1.2',
                'h2_size' => '36px',
                'h2_weight' => '600',
                'h2_line_height' => '1.3',
                'h3_size' => '28px',
                'h3_weight' => '600',
                'h3_line_height' => '1.4',
                'h4_size' => '24px',
                'h4_weight' => '600',
                'h4_line_height' => '1.4',
                'h5_size' => '20px',
                'h5_weight' => '600',
                'h5_line_height' => '1.5',
                'h6_size' => '18px',
                'h6_weight' => '600',
                'h6_line_height' => '1.5',
                'body_size' => '16px',
                'body_weight' => '400',
                'body_line_height' => '1.6',
                'small_size' => '14px'
            ],
            'spacing' => [
                'xs' => '5px',
                'sm' => '10px',
                'md' => '20px',
                'lg' => '30px',
                'xl' => '40px',
                'xxl' => '60px'
            ],
            'borders' => [
                'radius_sm' => '4px',
                'radius_md' => '8px',
                'radius_lg' => '12px',
                'radius_xl' => '20px',
                'radius_round' => '50%',
                'width_thin' => '1px',
                'width_medium' => '2px',
                'width_thick' => '4px'
            ],
            'shadows' => [
                'sm' => '0 1px 3px rgba(0,0,0,0.12)',
                'md' => '0 4px 6px rgba(0,0,0,0.1)',
                'lg' => '0 10px 20px rgba(0,0,0,0.15)',
                'xl' => '0 20px 40px rgba(0,0,0,0.2)'
            ],
            'breakpoints' => [
                'mobile' => '480px',
                'tablet' => '768px',
                'desktop' => '1024px',
                'wide' => '1200px'
            ],
            'custom_fonts' => []
        ];
    }
    
    /**
     * Get global styles
     */
    public function get_global_styles() {
        $styles = get_option('probuilder_global_styles', []);
        
        if (empty($styles)) {
            $styles = $this->get_default_styles();
        }
        
        return $styles;
    }
    
    /**
     * AJAX: Get global styles
     */
    public function ajax_get_global_styles() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        wp_send_json_success($this->get_global_styles());
    }
    
    /**
     * AJAX: Save global styles
     */
    public function ajax_save_global_styles() {
        // Ensure we send JSON response
        header('Content-Type: application/json');
        
        try {
            // Check permissions first (before nonce)
            if (!current_user_can('edit_posts')) {
                wp_send_json_error(['message' => 'Permission denied']);
                exit;
            }
            
            // Verify nonce - don't die on failure, return error instead
            $nonce_check = check_ajax_referer('probuilder-editor', 'nonce', false);
            if (!$nonce_check) {
                // Log nonce failure details
                error_log('ProBuilder: Nonce check failed');
                error_log('Nonce sent: ' . ($_POST['nonce'] ?? 'NOT SET'));
                error_log('Action: ' . ($_POST['action'] ?? 'NOT SET'));
                
                wp_send_json_error(['message' => 'Security check failed - please refresh the page']);
                exit;
            }
            
            // Validate data
            if (!isset($_POST['styles'])) {
                wp_send_json_error(['message' => 'No styles data provided']);
                exit;
            }
            
            $new_styles = json_decode(stripslashes($_POST['styles']), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(['message' => 'Invalid JSON data: ' . json_last_error_msg()]);
                exit;
            }
            
            if (!is_array($new_styles)) {
                wp_send_json_error(['message' => 'Invalid styles format']);
                exit;
            }
            
            // Get existing styles
            $existing_styles = get_option('probuilder_global_styles', []);
            if (!is_array($existing_styles)) {
                $existing_styles = [];
            }
            
            // Merge new styles with existing (preserving other sections)
            $merged_styles = array_merge($existing_styles, $new_styles);
            
            // Deep merge for nested arrays (like layout section)
            foreach ($new_styles as $section => $values) {
                if (isset($existing_styles[$section]) && is_array($values)) {
                    $merged_styles[$section] = array_merge(
                        (array) $existing_styles[$section],
                        $values
                    );
                }
            }
            
            // Save to database
            $result = update_option('probuilder_global_styles', $merged_styles, true); // true = autoload
            
            // Clear cache
            wp_cache_delete('probuilder_global_styles', 'options');
            
            // Log for debugging
            error_log('ProBuilder: Global styles saved - ' . ($result ? 'SUCCESS' : 'UNCHANGED'));
            error_log('ProBuilder: Saved data: ' . json_encode($merged_styles['layout'] ?? []));
            
            wp_send_json_success([
                'message' => 'Global styles saved successfully',
                'styles' => $merged_styles,
                'saved' => true
            ]);
            
        } catch (Exception $e) {
            error_log('ProBuilder Global Styles Error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            wp_send_json_error(['message' => 'Server error: ' . $e->getMessage()]);
        }
        
        exit; // Ensure clean JSON response
    }
    
    /**
     * Output global styles as CSS variables
     */
    public function output_global_styles() {
        $styles = $this->get_global_styles();
        
        echo '<style id="probuilder-global-styles">';
        echo ':root {';
        
        // Layout
        if (isset($styles['layout'])) {
            foreach ($styles['layout'] as $key => $value) {
                echo '--pb-layout-' . $key . ': ' . esc_attr($value) . ';';
            }
        }
        
        // Colors
        if (isset($styles['colors'])) {
            foreach ($styles['colors'] as $key => $value) {
                echo '--pb-color-' . $key . ': ' . esc_attr($value) . ';';
            }
        }
        
        // Typography
        if (isset($styles['typography'])) {
            foreach ($styles['typography'] as $key => $value) {
                echo '--pb-' . $key . ': ' . esc_attr($value) . ';';
            }
        }
        
        // Spacing
        if (isset($styles['spacing'])) {
            foreach ($styles['spacing'] as $key => $value) {
                echo '--pb-spacing-' . $key . ': ' . esc_attr($value) . ';';
            }
        }
        
        // Borders
        if (isset($styles['borders'])) {
            foreach ($styles['borders'] as $key => $value) {
                echo '--pb-' . $key . ': ' . esc_attr($value) . ';';
            }
        }
        
        // Shadows
        if (isset($styles['shadows'])) {
            foreach ($styles['shadows'] as $key => $value) {
                echo '--pb-shadow-' . $key . ': ' . esc_attr($value) . ';';
            }
        }
        
        echo '}';
        
        // Apply layout width globally
        if (isset($styles['layout']['content_width']) && $styles['layout']['content_width'] === 'boxed') {
            $boxed_width = isset($styles['layout']['boxed_width']) ? $styles['layout']['boxed_width'] : '1400px';
            $boxed_padding = isset($styles['layout']['boxed_padding']) ? $styles['layout']['boxed_padding'] : '15px';
            
            // Base boxed layout
            echo '.probuilder-content { max-width: ' . esc_attr($boxed_width) . '; margin: 0 auto; padding: 0 ' . esc_attr($boxed_padding) . '; box-sizing: border-box; }';
            
            // Remove padding from container/grid widgets to prevent double padding
            echo '
            .probuilder-content > .probuilder-element > .probuilder-container-layout,
            .probuilder-content > .probuilder-element > .probuilder-grid-layout {
                margin-left: -' . esc_attr($boxed_padding) . ';
                margin-right: -' . esc_attr($boxed_padding) . ';
                width: calc(100% + 2 * ' . esc_attr($boxed_padding) . ');
            }
            
            /* Cells with background images should fill edge-to-edge */
            .probuilder-container-layout .container-cell,
            .probuilder-grid-layout .grid-cell {
                padding: 0 !important;
            }
            
            /* But keep padding for the content inside cells */
            .probuilder-container-layout .container-cell-content,
            .probuilder-grid-layout .grid-cell-content {
                width: 100%;
                height: 100%;
                padding: 0;
            }
            
            /* Widgets inside cells can have their own padding */
            .container-cell-content > .probuilder-widget,
            .grid-cell-content > .probuilder-widget {
                width: 100%;
                height: 100%;
            }
            ';
        } else {
            // Full width layout - no constraints
            echo '.probuilder-content { width: 100%; max-width: 100%; margin: 0; padding: 0; }';
        }
        
        echo '</style>';
    }
}

