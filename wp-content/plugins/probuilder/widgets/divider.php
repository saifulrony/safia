<?php
/**
 * Divider Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Divider extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'divider';
        $this->title = __('Divider', 'probuilder');
        $this->icon = 'fa fa-minus';
        $this->category = 'basic';
        $this->keywords = ['divider', 'line', 'separator'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('style', [
            'label' => __('Style', 'probuilder'),
            'type' => 'select',
            'default' => 'solid',
            'options' => [
                'solid' => __('Solid', 'probuilder'),
                'dashed' => __('Dashed', 'probuilder'),
                'dotted' => __('Dotted', 'probuilder'),
            ],
        ]);
        
        $this->add_control('width', [
            'label' => __('Width', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->add_control('height', [
            'label' => __('Height', 'probuilder'),
            'type' => 'slider',
            'default' => 1,
            'range' => [
                'px' => ['min' => 1, 'max' => 10],
            ],
        ]);
        
        $this->add_control('color', [
            'label' => __('Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ddd',
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'center',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $style_type = $this->get_settings('style', 'solid');
        $width = $this->get_settings('width', 100);
        $height = $this->get_settings('height', 1);
        $color = $this->get_settings('color', '#ddd');
        $align = $this->get_settings('align', 'center');
        
        $margin = 'margin: 0 auto;';
        if ($align === 'left') {
            $margin = 'margin-left: 0; margin-right: auto;';
        } elseif ($align === 'right') {
            $margin = 'margin-left: auto; margin-right: 0;';
        }
        
        $style = 'width: ' . esc_attr($width) . '%; ';
        $style .= 'height: ' . esc_attr($height) . 'px; ';
        $style .= 'border-top: ' . esc_attr($height) . 'px ' . esc_attr($style_type) . ' ' . esc_attr($color) . '; ';
        $style .= $margin;
        
        echo '<div class="probuilder-divider" style="' . $style . '"></div>';
    }
}

