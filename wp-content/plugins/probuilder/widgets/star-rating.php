<?php
/**
 * Star Rating Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Star_Rating extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'star-rating';
        $this->title = __('Star Rating', 'probuilder');
        $this->icon = 'fa fa-star';
        $this->category = 'content';
        $this->keywords = ['star', 'rating', 'review'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_rating', [
            'label' => __('Rating', 'probuilder'),
        ]);
        
        $this->add_control('rating', [
            'label' => __('Rating', 'probuilder'),
            'type' => 'slider',
            'default' => 5,
            'range' => [
                'px' => ['min' => 0, 'max' => 5, 'step' => 0.5],
            ],
        ]);
        
        $this->add_control('scale', [
            'label' => __('Scale', 'probuilder'),
            'type' => 'select',
            'default' => '5',
            'options' => [
                '5' => __('0-5', 'probuilder'),
                '10' => __('0-10', 'probuilder'),
            ],
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Excellent!', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('star_size', [
            'label' => __('Star Size', 'probuilder'),
            'type' => 'slider',
            'default' => 24,
            'range' => [
                'px' => ['min' => 10, 'max' => 60],
            ],
        ]);
        
        $this->add_control('star_color', [
            'label' => __('Star Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffa500',
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
        $rating = $this->get_settings('rating', 5);
        $scale = $this->get_settings('scale', '5');
        $title = $this->get_settings('title', 'Excellent!');
        $star_size = $this->get_settings('star_size', 24);
        $star_color = $this->get_settings('star_color', '#ffa500');
        $align = $this->get_settings('align', 'center');
        
        $max_stars = $scale === '10' ? 10 : 5;
        
        echo '<div class="probuilder-star-rating" style="text-align: ' . esc_attr($align) . ';">';
        
        // Title
        if ($title) {
            echo '<div style="font-size: 18px; font-weight: 600; margin-bottom: 10px; color: #333;">' . esc_html($title) . '</div>';
        }
        
        // Stars
        echo '<div class="stars" style="font-size: ' . esc_attr($star_size) . 'px; color: ' . esc_attr($star_color) . ';">';
        
        for ($i = 1; $i <= $max_stars; $i++) {
            if ($i <= floor($rating)) {
                echo '<i class="fa fa-star"></i> ';
            } elseif ($i - 0.5 <= $rating) {
                echo '<i class="fa fa-star-half-stroke"></i> ';
            } else {
                echo '<i class="fa fa-star" style="opacity: 0.3;"></i> ';
            }
        }
        
        echo '</div>';
        
        // Rating number
        echo '<div style="margin-top: 8px; font-size: 14px; color: #666;">' . esc_html($rating) . ' / ' . esc_html($max_stars) . '</div>';
        
        echo '</div>';
    }
}

