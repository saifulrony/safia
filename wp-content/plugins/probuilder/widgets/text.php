<?php
/**
 * Text Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Text extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'text';
        $this->title = __('Text Editor', 'probuilder');
        $this->icon = 'fa fa-align-left';
        $this->category = 'basic';
        $this->keywords = ['text', 'editor', 'paragraph'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('text', [
            'label' => __('Text', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 16,
            'range' => [
                'px' => ['min' => 10, 'max' => 50],
            ],
        ]);
        
        $this->add_control('line_height', [
            'label' => __('Line Height', 'probuilder'),
            'type' => 'slider',
            'default' => 1.6,
            'range' => [
                'px' => ['min' => 1, 'max' => 3, 'step' => 0.1],
            ],
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Text Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
                'justify' => __('Justify', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Text Path Section
        $this->start_controls_section('section_text_path', [
            'label' => __('Text Path & Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('enable_text_path', [
            'label' => __('Enable Text Path', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Curve text along a path (works best with single line text)', 'probuilder'),
        ]);
        
        $this->add_control('path_type', [
            'label' => __('Path Type', 'probuilder'),
            'type' => 'select',
            'default' => 'curve',
            'options' => [
                'curve' => __('Curve (Arc)', 'probuilder'),
                'wave' => __('Wave', 'probuilder'),
                'circle' => __('Circle', 'probuilder'),
            ],
        ]);
        
        $this->add_control('curve_amount', [
            'label' => __('Curve Amount', 'probuilder'),
            'type' => 'slider',
            'default' => 50,
            'range' => [
                'px' => ['min' => -100, 'max' => 100, 'step' => 5],
            ],
            'description' => __('Positive = curve up, Negative = curve down', 'probuilder'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $text = $this->get_settings('text', '');
        $color = $this->get_settings('text_color', '#666666');
        $font_size = $this->get_settings('font_size', 16);
        $line_height = $this->get_settings('line_height', 1.6);
        $text_align = $this->get_settings('text_align', 'left');
        $enable_text_path = $this->get_settings('enable_text_path', 'no') === 'yes';
        $path_type = $this->get_settings('path_type', 'curve');
        $curve_amount = $this->get_settings('curve_amount', 50);
        
        if ($enable_text_path) {
            // Generate unique ID
            $id = 'text-path-' . uniqid();
            
            // Use only first line for text path
            $text_lines = explode("\n", $text);
            $curved_text = trim($text_lines[0]);
            
            // Calculate SVG height based on curve
            $svg_height = $font_size * 3;
            $text_length = strlen($curved_text) * $font_size * 0.6;
            
            // Generate SVG path based on type
            if ($path_type === 'curve') {
                $control_y = $svg_height / 2 - $curve_amount;
                $path_d = "M 0,{$svg_height} Q " . ($text_length / 2) . ",{$control_y} {$text_length},{$svg_height}";
            } elseif ($path_type === 'wave') {
                $wave_height = abs($curve_amount);
                $path_d = "M 0," . ($svg_height / 2) . " Q " . ($text_length * 0.25) . "," . ($svg_height / 2 - $wave_height) . " " . ($text_length * 0.5) . "," . ($svg_height / 2) . " T {$text_length}," . ($svg_height / 2);
            } else { // circle
                $radius = $text_length / 2;
                $path_d = "M 0,{$svg_height} A {$radius},{$radius} 0 0,1 {$text_length},{$svg_height}";
            }
            
            echo '<div class="probuilder-text-path-wrapper" style="text-align: ' . esc_attr($text_align) . ';">';
            echo '<svg width="100%" height="' . $svg_height . '" viewBox="0 0 ' . $text_length . ' ' . $svg_height . '" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">';
            echo '<defs>';
            echo '<path id="' . esc_attr($id) . '" d="' . esc_attr($path_d) . '" fill="transparent"/>';
            echo '</defs>';
            echo '<text style="fill: ' . esc_attr($color) . '; font-size: ' . esc_attr($font_size) . 'px; font-family: inherit;">';
            echo '<textPath href="#' . esc_attr($id) . '" startOffset="50%" text-anchor="middle">';
            echo esc_html($curved_text);
            echo '</textPath>';
            echo '</text>';
            echo '</svg>';
            
            // Show remaining lines as regular text
            if (count($text_lines) > 1) {
                array_shift($text_lines);
                $remaining_text = implode("\n", $text_lines);
                $style = 'color: ' . esc_attr($color) . '; ';
                $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
                $style .= 'line-height: ' . esc_attr($line_height) . '; ';
                $style .= 'text-align: ' . esc_attr($text_align) . '; ';
                $style .= 'margin-top: 10px;';
                echo '<div style="' . $style . '">' . wp_kses_post(wpautop($remaining_text)) . '</div>';
            }
            echo '</div>';
        } else {
            // Regular text
            $style = 'color: ' . esc_attr($color) . '; ';
            $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
            $style .= 'line-height: ' . esc_attr($line_height) . '; ';
            $style .= 'text-align: ' . esc_attr($text_align) . ';';
            
            echo '<div class="probuilder-text" style="' . $style . '">';
            echo wp_kses_post(wpautop($text));
            echo '</div>';
        }
    }
}

