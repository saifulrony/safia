<?php
/**
 * WooCommerce Products Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Woo_Products extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'woo-products';
    }
    
    public function get_title() {
        return __('Products', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa-shopping-cart';
    }
    
    public function get_categories() {
        return ['woocommerce'];
    }
    
    public function get_keywords() {
        return ['woocommerce', 'products', 'shop', 'store', 'ecommerce'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('section_content', [
            'label' => __('Products', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('query_type', [
            'label' => __('Query Type', 'probuilder'),
            'type' => 'select',
            'default' => 'recent',
            'options' => [
                'recent' => __('Recent Products', 'probuilder'),
                'featured' => __('Featured Products', 'probuilder'),
                'sale' => __('Sale Products', 'probuilder'),
                'best_selling' => __('Best Selling', 'probuilder'),
                'top_rated' => __('Top Rated', 'probuilder'),
            ],
        ]);
        
        $this->add_control('products_per_page', [
            'label' => __('Products Per Page', 'probuilder'),
            'type' => 'number',
            'default' => 8,
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '4',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);
        
        $this->add_control('show_image', [
            'label' => __('Show Image', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_price', [
            'label' => __('Show Price', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_cart_button', [
            'label' => __('Show Add to Cart', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('column_gap', [
            'label' => __('Column Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
        ]);
        
        $this->add_control('product_border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->settings;
        if (!class_exists('WooCommerce')) {
            echo '<div class="probuilder-woo-notice">';
            echo '<p>' . __('WooCommerce is not installed or activated.', 'probuilder') . '</p>';
            echo '</div>';
            return;
        }
        
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['products_per_page'] ?? 8,
            'orderby' => $settings['orderby'] ?? 'date',
            'order' => $settings['order'] ?? 'desc',
        ];
        
        // Query modifications based on type
        switch ($settings['query_type'] ?? 'recent') {
            case 'featured':
                $args['tax_query'][] = [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ];
                break;
            case 'sale':
                $args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());
                break;
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                break;
            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                break;
        }
        
        $products = new WP_Query($args);
        
        $columns = $settings['columns'] ?? 4;
        $column_gap = $settings['column_gap'] ?? 20;
        $row_gap = $settings['row_gap'] ?? 30;
        
        echo '<div class="probuilder-woo-products" style="display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($row_gap) . 'px ' . esc_attr($column_gap) . 'px;">';
        
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                $product = wc_get_product(get_the_ID());
                
                $this->render_product_card($product, $settings);
            }
        }
        
        wp_reset_postdata();
        
        echo '</div>';
    }
    
    private function render_product_card($product, $settings) {
        $border_radius = $settings['product_border_radius'] ?? 8;
        
        echo '<div class="probuilder-product-card" style="border-radius: ' . esc_attr($border_radius) . 'px; overflow: hidden; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">';
        
        // Image
        if ($settings['show_image'] ?? true) {
            echo '<div class="product-image" style="position: relative;">';
            echo $product->get_image('medium');
            
            // Sale badge
            if (($settings['show_badge'] ?? true) && $product->is_on_sale()) {
                echo '<span class="sale-badge" style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Sale</span>';
            }
            
            echo '</div>';
        }
        
        echo '<div class="product-content" style="padding: 20px;">';
        
        // Title
        if ($settings['show_title'] ?? true) {
            echo '<h3 class="product-title" style="margin: 0 0 10px; font-size: 18px;"><a href="' . esc_url($product->get_permalink()) . '">' . esc_html($product->get_name()) . '</a></h3>';
        }
        
        // Rating
        if (($settings['show_rating'] ?? true) && $product->get_average_rating() > 0) {
            echo '<div class="product-rating" style="margin-bottom: 10px;">';
            echo wc_get_rating_html($product->get_average_rating());
            echo '</div>';
        }
        
        // Price
        if ($settings['show_price'] ?? true) {
            echo '<div class="product-price" style="margin-bottom: 15px; font-size: 20px; font-weight: 600; color: #344047;">';
            echo $product->get_price_html();
            echo '</div>';
        }
        
        // Add to Cart
        if ($settings['show_cart_button'] ?? true) {
            woocommerce_template_loop_add_to_cart();
        }
        
        echo '</div>'; // .product-content
        echo '</div>'; // .probuilder-product-card
    }
}

