<?php
/**
 * Button Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Button extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'button';
        $this->title = __('Button', 'probuilder');
        $this->icon = 'fa fa-square-check';
        $this->category = 'basic';
        $this->keywords = ['button', 'link', 'cta'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('text', [
            'label' => __('Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Click Here', 'probuilder'),
        ]);
        
        $this->add_control('link', [
            'label' => __('Link', 'probuilder'),
            'type' => 'url',
            'default' => '#',
        ]);
        
        $this->add_control('icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'icon',
            'default' => '',
        ]);
        
        $this->add_control('icon_position', [
            'label' => __('Icon Position', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
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
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 3,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $text = $this->get_settings('text', 'Click Here');
        $link = $this->get_settings('link', '#');
        $icon = $this->get_settings('icon', '');
        $icon_position = $this->get_settings('icon_position', 'left');
        $align = $this->get_settings('align', 'left');
        $bg_color = $this->get_settings('bg_color', '#0073aa');
        $text_color = $this->get_settings('text_color', '#ffffff');
        $padding = $this->get_settings('padding', ['top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24]);
        $border_radius = $this->get_settings('border_radius', 3);
        
        $style = 'background-color: ' . esc_attr($bg_color) . '; ';
        $style .= 'color: ' . esc_attr($text_color) . '; ';
        $style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        $style .= 'border-radius: ' . esc_attr($border_radius) . 'px; ';
        $style .= 'text-decoration: none; display: inline-block;';
        
        $wrapper_style = 'text-align: ' . esc_attr($align) . ';';
        
        echo '<div class="probuilder-button-wrapper" style="' . $wrapper_style . '">';
        echo '<a href="' . esc_url($link) . '" class="probuilder-button" style="' . $style . '">';
        
        if ($icon && $icon_position === 'left') {
            echo '<i class="' . esc_attr($icon) . '"></i> ';
        }
        
        echo esc_html($text);
        
        if ($icon && $icon_position === 'right') {
            echo ' <i class="' . esc_attr($icon) . '"></i>';
        }
        
        echo '</a>';
        echo '</div>';
    }
}

