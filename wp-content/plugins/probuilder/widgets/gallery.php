<?php
/**
 * Gallery Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Gallery extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'gallery';
        $this->title = __('Gallery', 'probuilder');
        $this->icon = 'fa fa-table-cells';
        $this->category = 'content';
        $this->keywords = ['gallery', 'images', 'grid'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_gallery', [
            'label' => __('Gallery', 'probuilder'),
        ]);
        
        $this->add_control('images', [
            'label' => __('Add Images', 'probuilder'),
            'type' => 'gallery',
            'default' => [
                ['url' => 'https://via.placeholder.com/400x300/FF6B6B/ffffff?text=1'],
                ['url' => 'https://via.placeholder.com/400x300/4ECDC4/ffffff?text=2'],
                ['url' => 'https://via.placeholder.com/400x300/45B7D1/ffffff?text=3'],
                ['url' => 'https://via.placeholder.com/400x300/96CEB4/ffffff?text=4'],
                ['url' => 'https://via.placeholder.com/400x300/FFEAA7/ffffff?text=5'],
                ['url' => 'https://via.placeholder.com/400x300/DFE6E9/ffffff?text=6'],
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '3',
            'options' => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
        ]);
        
        $this->add_control('gap', [
            'label' => __('Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 10,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $images = $this->get_settings('images', []);
        $columns = $this->get_settings('columns', '3');
        $gap = $this->get_settings('gap', 10);
        
        if (empty($images)) {
            return;
        }
        
        $gallery_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($gap) . 'px;';
        
        echo '<div class="probuilder-gallery" style="' . $gallery_style . '">';
        
        foreach ($images as $image) {
            echo '<div class="probuilder-gallery-item" style="overflow: hidden; line-height: 0;">';
            echo '<img src="' . esc_url($image['url']) . '" alt="" style="width: 100%; height: auto; display: block; transition: transform 0.3s;" onmouseover="this.style.transform=\'scale(1.1)\'" onmouseout="this.style.transform=\'scale(1)\'">';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

