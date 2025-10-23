<?php
/**
 * Icon Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Icon_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'icon-box';
        $this->title = __('Icon Box', 'probuilder');
        $this->icon = 'fa fa-icons';
        $this->category = 'content';
        $this->keywords = ['icon', 'box', 'feature'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_icon', [
            'label' => __('Icon', 'probuilder'),
        ]);
        
        $this->add_control('icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'icon',
            'default' => 'fa fa-star',
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('icon_size', [
            'label' => __('Icon Size', 'probuilder'),
            'type' => 'slider',
            'default' => 48,
            'range' => [
                'px' => ['min' => 20, 'max' => 100],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Icon Box Title', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is an icon box description.', 'probuilder'),
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Text Alignment', 'probuilder'),
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
        $icon = $this->get_settings('icon', 'fa fa-star');
        $icon_color = $this->get_settings('icon_color', '#0073aa');
        $icon_size = $this->get_settings('icon_size', 48);
        $title = $this->get_settings('title', 'Icon Box Title');
        $description = $this->get_settings('description', '');
        $align = $this->get_settings('text_align', 'center');
        
        $box_style = 'text-align: ' . esc_attr($align) . '; padding: 30px;';
        $icon_style = 'color: ' . esc_attr($icon_color) . '; font-size: ' . esc_attr($icon_size) . 'px; margin-bottom: 20px;';
        
        echo '<div class="probuilder-icon-box" style="' . $box_style . '">';
        
        if ($icon) {
            echo '<div class="probuilder-icon-box-icon" style="' . $icon_style . '">';
            echo '<i class="' . esc_attr($icon) . '"></i>';
            echo '</div>';
        }
        
        echo '<div class="probuilder-icon-box-content">';
        echo '<h3 style="margin: 0 0 15px 0; font-size: 24px;">' . esc_html($title) . '</h3>';
        echo '<p style="margin: 0; color: #666; line-height: 1.6;">' . esc_html($description) . '</p>';
        echo '</div>';
        
        echo '</div>';
    }
}

