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
        ]);
        
        $this->add_control('images', [
            'label' => __('Add Images', 'probuilder'),
            'type' => 'gallery',
            'default' => [
                ['url' => 'https://via.placeholder.com/800x600/FF6B6B/ffffff?text=Slide+1'],
                ['url' => 'https://via.placeholder.com/800x600/4ECDC4/ffffff?text=Slide+2'],
                ['url' => 'https://via.placeholder.com/800x600/45B7D1/ffffff?text=Slide+3'],
            ],
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

