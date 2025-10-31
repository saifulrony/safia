<?php
/**
 * WooCommerce Product Categories Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Woo_Categories extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'woo-categories';
    }
    
    public function get_title() {
        return __('Product Categories', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa-th-large';
    }
    
    public function get_categories() {
        return ['woocommerce'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Categories', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '4',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4'],
        ]);
        
        $this->add_control('show_count', [
            'label' => __('Show Product Count', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_image', [
            'label' => __('Show Image', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->settings;
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $args = [
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ];
        
        $categories = get_terms($args);
        $columns = $settings['columns'] ?? 4;
        
        $style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: 20px;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-woo-categories" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        
        foreach ($categories as $category) {
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);
            
            echo '<div class="category-item" style="text-align: center; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
            
            if (($settings['show_image'] ?? true) && $image) {
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 15px;">';
            }
            
            echo '<h3><a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a></h3>';
            
            if ($settings['show_count'] ?? true) {
                echo '<p>' . sprintf(_n('%s product', '%s products', $category->count, 'probuilder'), $category->count) . '</p>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
    }
}

