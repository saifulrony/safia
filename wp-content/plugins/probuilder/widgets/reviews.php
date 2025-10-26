<?php
/**
 * Reviews Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Reviews_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'reviews';
        $this->title = __('Customer Reviews', 'probuilder');
        $this->icon = 'fa fa-star-half-alt';
        $this->category = 'content';
        $this->keywords = ['reviews', 'testimonial', 'rating'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Reviews', 'probuilder'),
        ]);
        
        $this->add_control('reviews', [
            'label' => __('Reviews', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                ['name' => 'John Doe', 'rating' => 5, 'review' => 'Excellent product!'],
            ],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'default' => 'Customer Name'],
                ['name' => 'rating', 'label' => 'Rating', 'type' => 'select', 'options' => ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], 'default' => '5'],
                ['name' => 'review', 'label' => 'Review', 'type' => 'textarea', 'default' => 'Great!'],
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'options' => ['1' => '1', '2' => '2', '3' => '3'],
            'default' => '2',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $reviews = $this->get_settings('reviews', []);
        $columns = $this->get_settings('columns', 2);
        
        echo '<div style="display:grid;grid-template-columns:repeat(' . $columns . ',1fr);gap:20px">';
        
        foreach ($reviews as $review) {
            echo '<div style="background:#f9f9f9;padding:20px;border-radius:8px">';
            echo '<div style="color:#ffc107;font-size:20px;margin-bottom:10px">';
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $review['rating'] ? '★' : '☆';
            }
            echo '</div>';
            echo '<h4 style="margin:0 0 10px;font-size:18px">' . esc_html($review['name']) . '</h4>';
            echo '<p style="margin:0;color:#666;line-height:1.6">' . esc_html($review['review']) . '</p>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

