<?php
/**
 * Portfolio Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Portfolio_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'portfolio';
        $this->title = __('Portfolio', 'probuilder');
        $this->icon = 'fa fa-images';
        $this->category = 'content';
        $this->keywords = ['portfolio', 'gallery', 'work'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('items', [
            'label' => __('Portfolio Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                ['title' => 'Project 1', 'image' => '', 'category' => 'Design'],
                ['title' => 'Project 2', 'image' => '', 'category' => 'Development'],
            ],
            'fields' => [
                ['name' => 'image', 'label' => 'Image', 'type' => 'media', 'default' => ''],
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'default' => 'Project'],
                ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'default' => ''],
                ['name' => 'link', 'label' => 'Link', 'type' => 'url', 'default' => '#'],
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'options' => ['2' => '2', '3' => '3', '4' => '4'],
            'default' => '3',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $items = $this->get_settings('items', []);
        $columns = $this->get_settings('columns', 3);
        
        if (empty($items)) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">Add portfolio items in settings</p>';
            return;
        }
        
        echo '<div style="display:grid;grid-template-columns:repeat(' . $columns . ',1fr);gap:20px">';
        
        foreach ($items as $item) {
            echo '<div style="position:relative;overflow:hidden;border-radius:8px;cursor:pointer" onmouseover="this.querySelector(\'.overlay\').style.opacity=\'1\'" onmouseout="this.querySelector(\'.overlay\').style.opacity=\'0\'">';
            
            if (!empty($item['image'])) {
                echo '<img src="' . esc_url($item['image']) . '" alt="' . esc_attr($item['title']) . '" style="width:100%;height:250px;object-fit:cover;display:block">';
            } else {
                echo '<div style="width:100%;height:250px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999">No Image</div>';
            }
            
            echo '<div class="overlay" style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;flex-direction:column;color:#fff;opacity:0;transition:opacity 0.3s">';
            echo '<h3 style="margin:0 0 8px;font-size:24px;color:#fff">' . esc_html($item['title']) . '</h3>';
            if (!empty($item['category'])) {
                echo '<p style="margin:0;color:#ccc;font-size:14px">' . esc_html($item['category']) . '</p>';
            }
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }
}

