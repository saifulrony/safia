<?php
/**
 * Image Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Image extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'image';
        $this->title = __('Image', 'probuilder');
        $this->icon = 'fa fa-image';
        $this->category = 'basic';
        $this->keywords = ['image', 'photo', 'picture'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('image', [
            'label' => __('Choose Image', 'probuilder'),
            'type' => 'media',
            'default' => [
                'url' => 'https://via.placeholder.com/800x600',
            ],
        ]);
        
        $this->add_control('link', [
            'label' => __('Link', 'probuilder'),
            'type' => 'url',
            'default' => '',
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
        
        $this->add_control('width', [
            'label' => __('Width', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $image = $this->get_settings('image', ['url' => 'https://via.placeholder.com/800x600']);
        $link = $this->get_settings('link', '');
        $align = $this->get_settings('align', 'center');
        $width = $this->get_settings('width', 100);
        
        $wrapper_style = 'text-align: ' . esc_attr($align) . ';';
        $img_style = 'max-width: ' . esc_attr($width) . '%; height: auto;';
        
        echo '<div class="probuilder-image" style="' . $wrapper_style . '">';
        
        if ($link) {
            echo '<a href="' . esc_url($link) . '">';
        }
        
        echo '<img src="' . esc_url($image['url']) . '" alt="" style="' . $img_style . '">';
        
        if ($link) {
            echo '</a>';
        }
        
        echo '</div>';
    }
}

