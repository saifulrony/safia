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
    
    public function get_category() {
        return 'content';
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
            'default' => 'yes',
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_price', [
            'label' => __('Show Price', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_cart_button', [
            'label' => __('Show Add to Cart', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_rating', [
            'label' => __('Show Rating', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_badge', [
            'label' => __('Show Sale Badge', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('orderby', [
            'label' => __('Order By', 'probuilder'),
            'type' => 'select',
            'default' => 'date',
            'options' => [
                'date' => __('Date', 'probuilder'),
                'title' => __('Title', 'probuilder'),
                'price' => __('Price', 'probuilder'),
                'popularity' => __('Popularity', 'probuilder'),
                'rating' => __('Rating', 'probuilder'),
            ],
        ]);
        
        $this->add_control('order', [
            'label' => __('Order', 'probuilder'),
            'type' => 'select',
            'default' => 'DESC',
            'options' => [
                'DESC' => __('Descending', 'probuilder'),
                'ASC' => __('Ascending', 'probuilder'),
            ],
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
            'range' => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('row_gap', [
            'label' => __('Row Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 30,
            'range' => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('product_border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => ['min' => 0, 'max' => 50, 'step' => 1],
            ],
        ]);
        
        $this->add_control('card_bg_color', [
            'label' => __('Card Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#344047',
        ]);
        
        $this->add_control('price_color', [
            'label' => __('Price Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('button_bg_color', [
            'label' => __('Button Background', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('button_text_color', [
            'label' => __('Button Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        if (!class_exists('WooCommerce')) {
            echo '<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">';
            echo '<i class="dashicons dashicons-warning" style="font-size: 48px;"></i>';
            echo '<p style="margin: 10px 0 0; font-weight: 600;">' . __('WooCommerce is not installed or activated.', 'probuilder') . '</p>';
            echo '<p style="margin: 5px 0 0; font-size: 13px;">Please install and activate WooCommerce to use this widget.</p>';
            echo '</div>';
            return;
        }
        
        $query_type = $this->get_settings('query_type', 'recent');
        $per_page = intval($this->get_settings('products_per_page', 8));
        $orderby = $this->get_settings('orderby', 'date');
        $order = $this->get_settings('order', 'DESC');
        
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $per_page,
            'post_status' => 'publish',
        ];
        
        // Query modifications based on type
        switch ($query_type) {
            case 'featured':
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                    ]
                ];
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
                
            case 'sale':
                $sale_ids = wc_get_product_ids_on_sale();
                if (empty($sale_ids)) {
                    $sale_ids = [0]; // No products on sale
                }
                $args['post__in'] = $sale_ids;
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
                
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = $order; // Respect user's order choice
                break;
                
            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = $order; // Respect user's order choice
                break;
                
            default: // recent
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
        }
        
        $products = new WP_Query($args);
        
        $columns = intval($this->get_settings('columns', 4));
        $column_gap = intval($this->get_settings('column_gap', 20));
        $row_gap = intval($this->get_settings('row_gap', 30));
        
        // Generate unique ID for this widget instance
        $widget_id = 'pb-products-' . uniqid();
        
        // Wrapper style (applies padding, margin, background, border, etc.)
        $wrapper_style = $inline_styles;
        
        // Grid style (ONLY grid-specific properties, NO padding/margin/background)
        $grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($row_gap) . 'px ' . esc_attr($column_gap) . 'px; width: 100%;';
        
        // Add inline CSS to ensure consistent styling
        echo '<style>
            #' . $widget_id . ' {
                box-sizing: border-box;
            }
            #' . $widget_id . ' .probuilder-products-grid {
                box-sizing: border-box;
                width: 100%;
            }
            #' . $widget_id . ' .probuilder-product-card * {
                box-sizing: border-box;
            }
            #' . $widget_id . ' .product-title a:hover {
                opacity: 0.8;
            }
            #' . $widget_id . ' .button:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }
        </style>';
        
        // Outer wrapper with padding/margin/etc from style tab
        echo '<div id="' . esc_attr($widget_id) . '" class="' . esc_attr($wrapper_classes) . ' probuilder-woo-products-wrapper" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
        // Inner grid container with ONLY grid properties
        echo '<div class="probuilder-products-grid" style="' . esc_attr($grid_style) . '">';
        
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                $product = wc_get_product(get_the_ID());
                
                if ($product) {
                    $this->render_product_card($product);
                }
            }
        } else {
            echo '<div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #cbd5e1; border-radius: 8px; color: #6b7280;">';
            echo '<i class="dashicons dashicons-cart" style="font-size: 48px; opacity: 0.3;"></i>';
            echo '<p style="margin: 10px 0 0; font-weight: 600;">No products found</p>';
            echo '<p style="margin: 5px 0 0; font-size: 13px;">Try changing the query type or add some products to your store.</p>';
            echo '</div>';
        }
        
        wp_reset_postdata();
        
        echo '</div>'; // .probuilder-products-grid
        echo '</div>'; // .probuilder-woo-products-wrapper
    }
    
    private function render_product_card($product) {
        $border_radius = $this->get_settings('product_border_radius', 8);
        $card_bg = $this->get_settings('card_bg_color', '#ffffff');
        $title_color = $this->get_settings('title_color', '#344047');
        $price_color = $this->get_settings('price_color', '#92003b');
        $btn_bg = $this->get_settings('button_bg_color', '#92003b');
        $btn_text = $this->get_settings('button_text_color', '#ffffff');
        
        // Get visibility settings with proper defaults
        $show_image = $this->get_settings('show_image');
        $show_image = ($show_image === 'no') ? false : true; // Default to true
        
        $show_title = $this->get_settings('show_title');
        $show_title = ($show_title === 'no') ? false : true;
        
        $show_price = $this->get_settings('show_price');
        $show_price = ($show_price === 'no') ? false : true;
        
        $show_rating = $this->get_settings('show_rating');
        $show_rating = ($show_rating === 'no') ? false : true;
        
        $show_badge = $this->get_settings('show_badge');
        $show_badge = ($show_badge === 'no') ? false : true;
        
        $show_cart = $this->get_settings('show_cart_button');
        $show_cart = ($show_cart === 'no') ? false : true;
        
        echo '<div class="probuilder-product-card" style="border-radius: ' . esc_attr($border_radius) . 'px; overflow: hidden; background: ' . esc_attr($card_bg) . '; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">';
        
        // Image
        if ($show_image) {
            echo '<div class="product-image" style="position: relative; background: #f8f9fa;">';
            echo '<a href="' . esc_url($product->get_permalink()) . '" style="display: block;">';
            echo $product->get_image('medium', ['style' => 'width: 100%; height: auto; display: block;']);
            echo '</a>';
            
            // Sale badge
            if ($show_badge && $product->is_on_sale()) {
                echo '<span class="sale-badge" style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Sale</span>';
            }
            
            echo '</div>';
        }
        
        echo '<div class="product-content" style="padding: 20px;">';
        
        // Title
        if ($show_title) {
            echo '<h3 class="product-title" style="margin: 0 0 10px; font-size: 16px; font-weight: 600; line-height: 1.4; color: ' . esc_attr($title_color) . ';"><a href="' . esc_url($product->get_permalink()) . '" style="color: inherit; text-decoration: none;">' . esc_html($product->get_name()) . '</a></h3>';
        }
        
        // Rating
        if ($show_rating && $product->get_average_rating() > 0) {
            echo '<div class="product-rating" style="margin-bottom: 10px; color: #fbbf24; font-size: 14px;">';
            $rating = round($product->get_average_rating());
            for ($i = 0; $i < 5; $i++) {
                echo $i < $rating ? '★' : '☆';
            }
            echo '</div>';
        }
        
        // Price - Clean display without verbose text
        if ($show_price) {
            echo '<div class="product-price" style="margin-bottom: 15px; font-size: 18px; font-weight: 700; color: ' . esc_attr($price_color) . '; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif;">';
            if ($product->is_on_sale()) {
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                echo '<span style="text-decoration: line-through; opacity: 0.6; margin-right: 8px; font-size: 16px;">' . wc_price($regular_price) . '</span>';
                echo '<span>' . wc_price($sale_price) . '</span>';
            } else {
                echo wc_price($product->get_price());
            }
            echo '</div>';
        }
        
        // Add to Cart
        if ($show_cart) {
            echo '<a href="' . esc_url($product->get_permalink()) . '" class="button product_type_simple add_to_cart_button" style="background: ' . esc_attr($btn_bg) . '; color: ' . esc_attr($btn_text) . '; padding: 12px 24px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif;">' . esc_html__('View Product', 'probuilder') . '</a>';
        }
        
        echo '</div>'; // .product-content
        echo '</div>'; // .probuilder-product-card
    }
}

