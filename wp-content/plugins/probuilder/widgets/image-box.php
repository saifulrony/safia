<?php
/**
 * Image Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Image_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'image-box';
        $this->title = __('Image Box', 'probuilder');
        $this->icon = 'fa fa-image-portrait';
        $this->category = 'content';
        $this->keywords = ['image', 'box', 'card'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_image', [
            'label' => __('Image', 'probuilder'),
        ]);
        
        $this->add_control('image', [
            'label' => __('Choose Image', 'probuilder'),
            'type' => 'media',
            'default' => [
                'url' => 'https://via.placeholder.com/400x300',
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Image Box Title', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is an image box description.', 'probuilder'),
        ]);
        
        $this->add_control('link', [
            'label' => __('Link', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
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
        $image = $this->get_settings('image', ['url' => 'https://via.placeholder.com/400x300']);
        $title = $this->get_settings('title', 'Image Box Title');
        $description = $this->get_settings('description', '');
        $link = $this->get_settings('link', '');
        $align = $this->get_settings('text_align', 'center');
        
        $box_style = 'text-align: ' . esc_attr($align) . '; border: 1px solid #e5e5e5; padding: 0; overflow: hidden;';
        
        echo '<div class="probuilder-image-box" style="' . $box_style . '">';
        
        if ($link) {
            echo '<a href="' . esc_url($link) . '" style="text-decoration: none; color: inherit;">';
        }
        
        echo '<div class="probuilder-image-box-img" style="margin: 0; line-height: 0;">';
        echo '<img src="' . esc_url($image['url']) . '" alt="" style="width: 100%; height: auto;">';
        echo '</div>';
        
        echo '<div class="probuilder-image-box-content" style="padding: 20px;">';
        echo '<h3 style="margin: 0 0 10px 0; font-size: 24px;">' . esc_html($title) . '</h3>';
        echo '<p style="margin: 0; color: #666;">' . esc_html($description) . '</p>';
        echo '</div>';
        
        if ($link) {
            echo '</a>';
        }
        
        echo '</div>';
    }
}

