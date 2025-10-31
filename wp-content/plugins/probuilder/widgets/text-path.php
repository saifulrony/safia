<?php
/**
 * Advanced Text Path / Curved Text Widget
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Text_Path_Widget extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'text-path';
    }
    
    public function get_title() {
        return __('Curved Text', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa fa-bezier-curve';
    }
    
    public function get_categories() {
        return ['advanced'];
    }
    
    public function get_keywords() {
        return ['text', 'curve', 'path', 'arc', 'circular', 'wave'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('text', [
            'label' => __('Text', 'probuilder'),
            'type' => 'textarea',
            'default' => 'Beautiful Curved Text',
            'placeholder' => 'Enter your text here'
        ]);
        
        $this->end_controls_section();
        
        // Path Settings
        $this->start_controls_section('section_path', [
            'label' => __('Path Settings', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('path_type', [
            'label' => __('Path Type', 'probuilder'),
            'type' => 'select',
            'default' => 'arc',
            'options' => [
                'arc' => __('Arc (Upward)', 'probuilder'),
                'arc-down' => __('Arc (Downward)', 'probuilder'),
                'wave' => __('Wave', 'probuilder'),
                'circle' => __('Circle', 'probuilder'),
                'spiral' => __('Spiral', 'probuilder'),
                's-curve' => __('S-Curve', 'probuilder'),
                'zigzag' => __('Zigzag', 'probuilder'),
                'custom' => __('Custom Bezier', 'probuilder')
            ]
        ]);
        
        $this->add_control('curve_amount', [
            'label' => __('Curve Amount', 'probuilder'),
            'type' => 'slider',
            'default' => 30,
            'min' => -200,
            'max' => 200,
            'step' => 1,
            'description' => 'Negative values curve downward, positive curve upward'
        ]);
        
        $this->add_control('path_width', [
            'label' => __('Path Width', 'probuilder'),
            'type' => 'slider',
            'default' => 500,
            'min' => 200,
            'max' => 1200,
            'step' => 10
        ]);
        
        $this->add_control('path_height', [
            'label' => __('Path Height', 'probuilder'),
            'type' => 'slider',
            'default' => 200,
            'min' => 50,
            'max' => 600,
            'step' => 10
        ]);
        
        $this->add_control('reverse_path', [
            'label' => __('Reverse Path', 'probuilder'),
            'type' => 'switcher',
            'default' => false,
            'description' => 'Flip the text direction along the path'
        ]);
        
        $this->add_control('start_offset', [
            'label' => __('Start Offset (%)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'description' => 'Position text along the path'
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('section_style', [
            'label' => __('Typography', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#1a1a1a'
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 32,
            'min' => 12,
            'max' => 120,
            'step' => 1
        ]);
        
        $this->add_control('font_weight', [
            'label' => __('Font Weight', 'probuilder'),
            'type' => 'select',
            'default' => '600',
            'options' => [
                '300' => '300 (Light)',
                '400' => '400 (Normal)',
                '500' => '500 (Medium)',
                '600' => '600 (Semi Bold)',
                '700' => '700 (Bold)',
                '800' => '800 (Extra Bold)',
                '900' => '900 (Black)'
            ]
        ]);
        
        $this->add_control('letter_spacing', [
            'label' => __('Letter Spacing', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'min' => -5,
            'max' => 20,
            'step' => 0.5
        ]);
        
        $this->add_control('text_transform', [
            'label' => __('Text Transform', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => 'None',
                'uppercase' => 'UPPERCASE',
                'lowercase' => 'lowercase',
                'capitalize' => 'Capitalize'
            ]
        ]);
        
        $this->end_controls_section();
        
        // Effects Tab
        $this->start_controls_section('section_effects', [
            'label' => __('Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('text_stroke', [
            'label' => __('Text Stroke Width', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'min' => 0,
            'max' => 5,
            'step' => 0.5
        ]);
        
        $this->add_control('stroke_color', [
            'label' => __('Stroke Color', 'probuilder'),
            'type' => 'color',
            'default' => '#000000'
        ]);
        
        $this->add_control('text_shadow', [
            'label' => __('Text Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => false
        ]);
        
        $this->add_control('shadow_color', [
            'label' => __('Shadow Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.3)'
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->settings;
        
        $text = isset($settings['text']) ? $settings['text'] : 'Beautiful Curved Text';
        $path_type = isset($settings['path_type']) ? $settings['path_type'] : 'arc';
        $curve_amount = isset($settings['curve_amount']) ? $settings['curve_amount'] : 30;
        $width = isset($settings['path_width']) ? $settings['path_width'] : 500;
        $height = isset($settings['path_height']) ? $settings['path_height'] : 200;
        $reverse = isset($settings['reverse_path']) ? $settings['reverse_path'] : false;
        $start_offset = isset($settings['start_offset']) ? $settings['start_offset'] : 0;
        
        $color = isset($settings['text_color']) ? $settings['text_color'] : '#1a1a1a';
        $font_size = isset($settings['font_size']) ? $settings['font_size'] : 32;
        $font_weight = isset($settings['font_weight']) ? $settings['font_weight'] : '600';
        $letter_spacing = isset($settings['letter_spacing']) ? $settings['letter_spacing'] : 0;
        $text_transform = isset($settings['text_transform']) ? $settings['text_transform'] : 'none';
        
        $stroke_width = isset($settings['text_stroke']) ? $settings['text_stroke'] : 0;
        $stroke_color = isset($settings['stroke_color']) ? $settings['stroke_color'] : '#000000';
        $has_shadow = isset($settings['text_shadow']) ? $settings['text_shadow'] : false;
        $shadow_color = isset($settings['shadow_color']) ? $settings['shadow_color'] : 'rgba(0,0,0,0.3)';
        
        // Generate SVG path based on type
        $path_d = $this->generate_path($path_type, $width, $height, $curve_amount);
        
        // Unique ID for this path
        $path_id = 'text-path-' . uniqid();
        
        // Text transform CSS
        $transform_style = '';
        switch ($text_transform) {
            case 'uppercase':
                $text = strtoupper($text);
                break;
            case 'lowercase':
                $text = strtolower($text);
                break;
            case 'capitalize':
                $text = ucwords($text);
                break;
        }
        
        // Build SVG filters for shadow
        $filters = '';
        $filter_attr = '';
        if ($has_shadow) {
            $filters = '<defs>
                <filter id="text-shadow-' . $path_id . '" x="-50%" y="-50%" width="200%" height="200%">
                    <feGaussianBlur in="SourceAlpha" stdDeviation="3"/>
                    <feOffset dx="2" dy="2" result="offsetblur"/>
                    <feFlood flood-color="' . esc_attr($shadow_color) . '"/>
                    <feComposite in2="offsetblur" operator="in"/>
                    <feMerge>
                        <feMergeNode/>
                        <feMergeNode in="SourceGraphic"/>
                    </feMerge>
                </filter>
            </defs>';
            $filter_attr = 'filter="url(#text-shadow-' . $path_id . ')"';
        }
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-text-path" style="width: 100%; max-width: ' . $width . 'px; margin: 0 auto;">';
        echo '<svg viewBox="0 0 ' . $width . ' ' . $height . '" style="width: 100%; height: auto;" xmlns="http://www.w3.org/2000/svg">';
        echo $filters;
        echo '<path id="' . $path_id . '" d="' . $path_d . '" fill="transparent" stroke="none"/>';
        echo '<text ' . $filter_attr . ' fill="' . esc_attr($color) . '" font-size="' . esc_attr($font_size) . '" font-weight="' . esc_attr($font_weight) . '" letter-spacing="' . esc_attr($letter_spacing) . '" ';
        
        if ($stroke_width > 0) {
            echo 'stroke="' . esc_attr($stroke_color) . '" stroke-width="' . esc_attr($stroke_width) . '" ';
        }
        
        echo 'text-anchor="middle">';
        echo '<textPath href="#' . $path_id . '" startOffset="' . esc_attr($start_offset) . '%">';
        echo esc_html($text);
        echo '</textPath>';
        echo '</text>';
        echo '</svg>';
        echo '</div>';
    }
    
    /**
     * Generate SVG path based on type and settings
     */
    private function generate_path($type, $width, $height, $curve_amount) {
        $centerX = $width / 2;
        $centerY = $height / 2;
        $startX = 50;
        $endX = $width - 50;
        
        switch ($type) {
            case 'arc':
                // Simple arc - curves upward
                $controlY = $centerY - $curve_amount;
                return "M {$startX},{$centerY} Q {$centerX},{$controlY} {$endX},{$centerY}";
                
            case 'arc-down':
                // Arc downward
                $controlY = $centerY + $curve_amount;
                return "M {$startX},{$centerY} Q {$centerX},{$controlY} {$endX},{$centerY}";
                
            case 'wave':
                // Sine wave
                $waveHeight = abs($curve_amount);
                $step = $width / 4;
                return "M {$startX},{$centerY} Q " . ($startX + $step) . "," . ($centerY - $waveHeight) . " " . ($startX + $step * 2) . ",{$centerY} Q " . ($startX + $step * 3) . "," . ($centerY + $waveHeight) . " {$endX},{$centerY}";
                
            case 'circle':
                // Full circle
                $radius = min($width, $height) / 2 - 50;
                return "M " . ($centerX - $radius) . ",{$centerY} A {$radius},{$radius} 0 1,1 " . ($centerX + $radius) . ",{$centerY}";
                
            case 'spiral':
                // Spiral path
                $radius = min($width, $height) / 3;
                return "M {$centerX},{$centerY} m -{$radius},0 a {$radius},{$radius} 0 1,0 " . ($radius * 2) . ",0 a {$radius},{$radius} 0 1,0 -" . ($radius * 2) . ",0";
                
            case 's-curve':
                // S-shaped curve
                $control1Y = $centerY - $curve_amount;
                $control2Y = $centerY + $curve_amount;
                return "M {$startX},{$centerY} C " . ($width * 0.3) . ",{$control1Y} " . ($width * 0.7) . ",{$control2Y} {$endX},{$centerY}";
                
            case 'zigzag':
                // Zigzag pattern
                $zigHeight = abs($curve_amount);
                $step = $width / 6;
                $path = "M {$startX},{$centerY}";
                for ($i = 1; $i <= 6; $i++) {
                    $x = $startX + ($step * $i);
                    $y = ($i % 2 == 0) ? $centerY - $zigHeight : $centerY + $zigHeight;
                    $path .= " L {$x},{$y}";
                }
                return $path;
                
            case 'custom':
                // Custom bezier curve - fully customizable
                $control1X = $width * 0.25;
                $control1Y = $centerY - $curve_amount;
                $control2X = $width * 0.75;
                $control2Y = $centerY + ($curve_amount / 2);
                return "M {$startX},{$centerY} C {$control1X},{$control1Y} {$control2X},{$control2Y} {$endX},{$centerY}";
                
            default:
                return "M {$startX},{$centerY} Q {$centerX}," . ($centerY - $curve_amount) . " {$endX},{$centerY}";
        }
    }
}

