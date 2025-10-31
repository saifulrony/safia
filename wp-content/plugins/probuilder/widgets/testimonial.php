<?php
/**
 * Testimonial Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Testimonial extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'testimonial';
        $this->title = __('Testimonial', 'probuilder');
        $this->icon = 'fa fa-quote-left';
        $this->category = 'content';
        $this->keywords = ['testimonial', 'review', 'quote'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_testimonial', [
            'label' => __('Testimonial', 'probuilder'),
        ]);
        
        $this->add_control('content', [
            'label' => __('Content', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is an amazing product! I highly recommend it to everyone.', 'probuilder'),
        ]);
        
        $this->add_control('image', [
            'label' => __('Image', 'probuilder'),
            'type' => 'media',
            'default' => [
                'url' => 'https://via.placeholder.com/100x100',
            ],
        ]);
        
        $this->add_control('name', [
            'label' => __('Name', 'probuilder'),
            'type' => 'text',
            'default' => __('John Doe', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('CEO, Company', 'probuilder'),
        ]);
        
        $this->add_control('rating', [
            'label' => __('Rating', 'probuilder'),
            'type' => 'slider',
            'default' => 5,
            'range' => [
                'px' => ['min' => 0, 'max' => 5],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
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
        // Render custom CSS if any
        $this->render_custom_css();
        
        $content = $this->get_settings('content', '');
        $image = $this->get_settings('image', ['url' => 'https://via.placeholder.com/100x100']);
        $name = $this->get_settings('name', 'John Doe');
        $title = $this->get_settings('title', 'CEO, Company');
        $rating = $this->get_settings('rating', 5);
        $align = $this->get_settings('align', 'center');
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        $box_style = 'text-align: ' . esc_attr($align) . '; padding: 30px; border: 1px solid #e5e5e5; background: #f9f9f9;';
        
        if ($inline_styles) {
            $box_style .= ' ' . $inline_styles;
        }
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-testimonial" ' . $wrapper_attributes . ' style="' . esc_attr($box_style) . '">';
        
        // Quote icon
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-testimonial-icon" style="font-size: 48px; color: #0073aa; opacity: 0.3; margin-bottom: 20px;">';
        echo '<i class="fa fa-quote-left"></i>';
        echo '</div>';
        
        // Content
        echo '<div class="probuilder-testimonial-content" style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; font-style: italic;">';
        echo '<p>' . esc_html($content) . '</p>';
        echo '</div>';
        
        // Rating
        if ($rating > 0) {
            echo '<div class="probuilder-testimonial-rating" style="margin-bottom: 20px; color: #ffa500;">';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo '<i class="fa fa-star"></i> ';
                } else {
                    echo '<i class="fa fa-star" style="opacity: 0.3;"></i> ';
                }
            }
            echo '</div>';
        }
        
        // Author
        echo '<div class="probuilder-testimonial-author" style="display: flex; align-items: center; justify-content: ' . esc_attr($align) . ';">';
        
        if ($image['url']) {
            echo '<img src="' . esc_url($image['url']) . '" alt="" style="width: 60px; height: 60px; border-radius: 50%; margin-right: 15px;">';
        }
        
        echo '<div class="probuilder-testimonial-author-info" style="text-align: left;">';
        echo '<div class="probuilder-testimonial-name" style="font-weight: 600; margin-bottom: 5px;">' . esc_html($name) . '</div>';
        echo '<div class="probuilder-testimonial-title" style="color: #666; font-size: 14px;">' . esc_html($title) . '</div>';
        echo '</div>';
        
        echo '</div>';
        
        echo '</div>';
    }
}

