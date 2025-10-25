<?php
/**
 * Image Carousel Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Carousel extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'carousel';
        $this->title = __('Image Carousel', 'probuilder');
        $this->icon = 'fa fa-images';
        $this->category = 'content';
        $this->keywords = ['carousel', 'slider', 'images'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_images', [
            'label' => __('Images', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('images', [
            'label' => __('Carousel Images', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'image_url' => 'https://via.placeholder.com/1200x600/92003b/ffffff?text=Slide+1',
                    'caption' => 'First Slide'
                ],
                [
                    'image_url' => 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Slide+2',
                    'caption' => 'Second Slide'
                ],
                [
                    'image_url' => 'https://via.placeholder.com/1200x600/4facfe/ffffff?text=Slide+3',
                    'caption' => 'Third Slide'
                ],
            ],
            'fields' => [
                [
                    'name' => 'image_url',
                    'label' => __('Image URL', 'probuilder'),
                    'type' => 'text',
                    'default' => 'https://via.placeholder.com/1200x600',
                ],
                [
                    'name' => 'caption',
                    'label' => __('Caption', 'probuilder'),
                    'type' => 'text',
                    'default' => '',
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('autoplay', [
            'label' => __('Autoplay', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('autoplay_speed', [
            'label' => __('Autoplay Speed (ms)', 'probuilder'),
            'type' => 'number',
            'default' => 3000,
        ]);
        
        $this->add_control('show_arrows', [
            'label' => __('Show Arrows', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_dots', [
            'label' => __('Show Dots', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('height', [
            'label' => __('Height (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 400,
            'range' => [
                'px' => [
                    'min' => 200,
                    'max' => 800,
                    'step' => 10
                ]
            ]
        ]);
        
        $this->add_control('arrows_color', [
            'label' => __('Arrows Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('dots_color', [
            'label' => __('Dots Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $images = $this->get_settings('images', []);
        $autoplay = $this->get_settings('autoplay', 'yes');
        $autoplay_speed = $this->get_settings('autoplay_speed', 3000);
        
        if (empty($images)) {
            return;
        }
        
        $id = 'probuilder-carousel-' . uniqid();
        
        echo '<div class="probuilder-carousel" id="' . esc_attr($id) . '" data-autoplay="' . esc_attr($autoplay) . '" data-speed="' . esc_attr($autoplay_speed) . '" style="position: relative; overflow: hidden;">';
        
        echo '<div class="probuilder-carousel-track" style="display: flex; transition: transform 0.5s ease;">';
        foreach ($images as $image) {
            echo '<div class="probuilder-carousel-slide" style="flex: 0 0 100%; width: 100%;">';
            echo '<img src="' . esc_url($image['url']) . '" alt="" style="width: 100%; height: auto; display: block;">';
            echo '</div>';
        }
        echo '</div>';
        
        // Navigation
        echo '<button class="probuilder-carousel-prev" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 10px 15px; cursor: pointer; font-size: 20px; z-index: 10;">‹</button>';
        echo '<button class="probuilder-carousel-next" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 10px 15px; cursor: pointer; font-size: 20px; z-index: 10;">›</button>';
        
        echo '</div>';
    }
}

