<?php
/**
 * Heading Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Heading extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'heading';
        $this->title = __('Heading', 'probuilder');
        $this->icon = 'fa fa-heading';
        $this->category = 'basic';
        $this->keywords = ['heading', 'title', 'h1', 'h2', 'h3'];
    }
    
    protected function register_controls() {
        // CONTENT TAB
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('This is a heading', 'probuilder'),
        ]);
        
        $this->add_control('html_tag', [
            'label' => __('HTML Tag', 'probuilder'),
            'type' => 'select',
            'default' => 'h2',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
            ],
        ]);
        
        $this->add_control('link', [
            'label' => __('Link', 'probuilder'),
            'type' => 'url',
            'default' => ['url' => ''],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB
        $this->start_controls_section('section_style', [
            'label' => __('Typography', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 32,
            'min' => 10,
            'max' => 100,
        ]);
        
        $this->add_control('font_weight', [
            'label' => __('Font Weight', 'probuilder'),
            'type' => 'select',
            'default' => '600',
            'options' => [
                '300' => __('Light', 'probuilder'),
                '400' => __('Normal', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
                '900' => __('Black', 'probuilder'),
            ],
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('line_height', [
            'label' => __('Line Height', 'probuilder'),
            'type' => 'slider',
            'default' => 1.3,
            'min' => 0.5,
            'max' => 3,
            'step' => 0.1,
        ]);
        
        $this->end_controls_section();
        
        // TEXT PATH/EFFECTS SECTION
        $this->start_controls_section('section_text_path', [
            'label' => __('Text Path & Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('enable_text_path', [
            'label' => __('Enable Text Path', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Curve text along a path', 'probuilder'),
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
        
        // ADVANCED TAB
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '20', 'left' => '0'],
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'],
        ]);
        
        $this->add_control('css_classes', [
            'label' => __('CSS Classes', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => 'my-class another-class',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'This is a heading');
        $tag = $this->get_settings('html_tag', 'h2');
        $align = $this->get_settings('align', 'left');
        $color = $this->get_settings('color', '#333333');
        $font_size = $this->get_settings('font_size', 32);
        $font_weight = $this->get_settings('font_weight', '600');
        $line_height = $this->get_settings('line_height', 1.3);
        $enable_text_path = $this->get_settings('enable_text_path', 'no') === 'yes';
        $path_type = $this->get_settings('path_type', 'curve');
        $curve_amount = $this->get_settings('curve_amount', 50);
        $margin = $this->get_settings('margin', ['top' => '0', 'right' => '0', 'bottom' => '20', 'left' => '0']);
        $padding = $this->get_settings('padding', ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0']);
        $css_classes = $this->get_settings('css_classes', '');
        
        $wrapper_style = 'text-align: ' . esc_attr($align) . '; ';
        $wrapper_style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px; ';
        $wrapper_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        
        if ($enable_text_path) {
            // Generate unique ID
            $id = 'heading-path-' . uniqid();
            
            // Calculate SVG height based on curve
            $svg_height = $font_size * 3;
            $text_length = strlen($title) * $font_size * 0.6;
            
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
            
            echo '<div class="probuilder-heading-path-wrapper" style="' . $wrapper_style . '">';
            echo '<svg width="100%" height="' . $svg_height . '" viewBox="0 0 ' . $text_length . ' ' . $svg_height . '" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">';
            echo '<defs>';
            echo '<path id="' . esc_attr($id) . '" d="' . esc_attr($path_d) . '" fill="transparent"/>';
            echo '</defs>';
            echo '<text style="fill: ' . esc_attr($color) . '; font-size: ' . esc_attr($font_size) . 'px; font-weight: ' . esc_attr($font_weight) . '; font-family: inherit;">';
            echo '<textPath href="#' . esc_attr($id) . '" startOffset="50%" text-anchor="middle">';
            echo esc_html($title);
            echo '</textPath>';
            echo '</text>';
            echo '</svg>';
            echo '</div>';
        } else {
            // Regular text
            $style = 'color: ' . esc_attr($color) . '; ';
            $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
            $style .= 'font-weight: ' . esc_attr($font_weight) . '; ';
            $style .= 'line-height: ' . esc_attr($line_height) . '; ';
            $style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px; ';
            $style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
            $style .= 'text-align: ' . esc_attr($align) . ';';
            
            $class = 'probuilder-heading ' . esc_attr($css_classes);
            
            printf(
                '<%1$s class="%2$s" style="%3$s">%4$s</%1$s>',
                esc_attr($tag),
                trim($class),
                $style,
                esc_html($title)
            );
        }
    }
}

