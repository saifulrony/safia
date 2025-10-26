<?php
/**
 * Shape Dividers Library
 * Decorative section dividers
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Shape_Dividers {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_probuilder_get_shape_dividers', [$this, 'ajax_get_shape_dividers']);
    }
    
    /**
     * Get available shape dividers
     */
    public function get_shapes() {
        return [
            'waves' => [
                'title' => __('Waves', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"/></svg>'
            ],
            'curve' => [
                'title' => __('Curve', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z"/></svg>'
            ],
            'triangle' => [
                'title' => __('Triangle', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 598.97 114.72 1200 0z"/></svg>'
            ],
            'asymmetric' => [
                'title' => __('Asymmetric', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 892.25 114.72 1200 0z"/></svg>'
            ],
            'tilt' => [
                'title' => __('Tilt', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 120L0 16.48 0 0 1200 0 1200 120z"/></svg>'
            ],
            'mountains' => [
                'title' => __('Mountains', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"/></svg>'
            ],
            'arrow' => [
                'title' => __('Arrow', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M649.97 0L550.03 0 599.91 54.12 649.97 0z"/></svg>'
            ],
            'split' => [
                'title' => __('Split', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z"/></svg>'
            ],
            'zigzag' => [
                'title' => __('Zigzag', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V6c0,21.6,291,111.46,741,110.26,445.39,3.6,459-88.3,459-110.26V0Z"/></svg>'
            ],
            'book' => [
                'title' => __('Book', 'probuilder'),
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z" transform="rotate(180 600 60)"/></svg>'
            ],
        ];
    }
    
    /**
     * AJAX: Get shape dividers
     */
    public function ajax_get_shape_dividers() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        wp_send_json_success($this->get_shapes());
    }
    
    /**
     * Render shape divider
     */
    public static function render($shape, $position = 'top', $color = '#ffffff', $height = 100, $flip = false, $invert = false) {
        $shapes = self::instance()->get_shapes();
        
        if (!isset($shapes[$shape])) {
            return '';
        }
        
        $svg = $shapes[$shape]['svg'];
        
        // Apply color
        $svg = str_replace('<path', '<path fill="' . esc_attr($color) . '"', $svg);
        
        // Apply transforms
        $transform = '';
        if ($flip) {
            $transform .= ' scaleX(-1)';
        }
        if ($invert) {
            $transform .= ' scaleY(-1)';
        }
        
        if ($transform) {
            $svg = str_replace('<svg', '<svg style="transform:' . $transform . '"', $svg);
        }
        
        $style = sprintf(
            'position: absolute; %s: 0; left: 0; width: 100%%; height: %dpx; z-index: 1; pointer-events: none;',
            $position,
            $height
        );
        
        return sprintf('<div class="probuilder-shape-divider probuilder-shape-%s" style="%s">%s</div>', 
            esc_attr($shape), 
            $style, 
            $svg
        );
    }
}

