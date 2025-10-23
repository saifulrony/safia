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
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('text', [
            'label' => __('Text', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size', 'probuilder'),
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
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $text = $this->get_settings('text', '');
        $color = $this->get_settings('text_color', '#666666');
        $font_size = $this->get_settings('font_size', 16);
        $line_height = $this->get_settings('line_height', 1.6);
        
        $style = 'color: ' . esc_attr($color) . '; ';
        $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
        $style .= 'line-height: ' . esc_attr($line_height) . ';';
        
        echo '<div class="probuilder-text" style="' . $style . '">';
        echo wp_kses_post(wpautop($text));
        echo '</div>';
    }
}

