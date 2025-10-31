<?php
/**
 * Price List Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Price_List_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'price-list';
        $this->title = __('Price List', 'probuilder');
        $this->icon = 'fa fa-list-alt';
        $this->category = 'content';
        $this->keywords = ['price', 'list', 'menu'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('items', [
            'label' => __('Price Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                ['title' => 'Service 1', 'price' => '$50', 'description' => 'Description here'],
                ['title' => 'Service 2', 'price' => '$100', 'description' => 'Description here'],
            ],
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'default' => 'Service'],
                ['name' => 'price', 'label' => 'Price', 'type' => 'text', 'default' => '$50'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'default' => ''],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $items = $this->get_settings('items', []);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-price-list">';
        
        foreach ($items as $item) {
            echo '<div style="display:flex;justify-content:space-between;align-items:flex-start;padding:20px 0;border-bottom:1px solid #eee' . ($inline_styles ? ' ' . $inline_styles : '') . '">';
            echo '<div style="flex:1">';
            echo '<h4 style="margin:0 0 8px;font-size:18px;font-weight:600">' . esc_html($item['title']) . '</h4>';
            if (!empty($item['description'])) {
                echo '<p style="margin:0;color:#666;font-size:14px">' . esc_html($item['description']) . '</p>';
            }
            echo '</div>';
            echo '<div style="font-size:20px;font-weight:700;color:#0073aa;margin-left:20px">' . esc_html($item['price']) . '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

