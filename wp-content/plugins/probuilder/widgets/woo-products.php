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
        
        $this->add_control('enable_tabs', [
            'label' => __('Enable Tabs', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Show tabs for different product categories (Featured, Recent, Sale, etc.)', 'probuilder'),
        ]);
        
        $this->add_control('tabs_style', [
            'label' => __('Tabs Style', 'probuilder'),
            'type' => 'select',
            'default' => 'modern',
            'options' => [
                'modern' => __('Modern', 'probuilder'),
                'minimal' => __('Minimal', 'probuilder'),
                'underline' => __('Underline', 'probuilder'),
                'pills' => __('Pills', 'probuilder'),
            ],
            'condition' => ['enable_tabs' => 'yes'],
        ]);
        
        $this->add_control('tabs', [
            'label' => __('Tabs', 'probuilder'),
            'type' => 'repeater',
            'fields' => [
                [
                    'name' => 'label',
                    'label' => __('Tab Label', 'probuilder'),
                    'type' => 'text',
                    'default' => 'Featured',
                ],
                [
                    'name' => 'query_type',
                    'label' => __('Query Type', 'probuilder'),
                    'type' => 'select',
                    'options' => [
                        'recent' => __('Recent Products', 'probuilder'),
                        'featured' => __('Featured Products', 'probuilder'),
                        'sale' => __('Sale Products', 'probuilder'),
                        'best_selling' => __('Best Selling', 'probuilder'),
                        'top_rated' => __('Top Rated', 'probuilder'),
                    ],
                    'default' => 'featured',
                ],
            ],
            'default' => [
                ['label' => 'Featured', 'query_type' => 'featured'],
                ['label' => 'Recent', 'query_type' => 'recent'],
                ['label' => 'Sale', 'query_type' => 'sale'],
                ['label' => 'Best Selling', 'query_type' => 'best_selling'],
            ],
            'condition' => ['enable_tabs' => 'yes'],
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
            'condition' => ['enable_tabs' => 'no'],
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
        
        // Image & Hover Effects Section
        $this->start_controls_section('section_image_effects', [
            'label' => __('Image & Hover Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('image_ratio', [
            'label' => __('Image Aspect Ratio', 'probuilder'),
            'type' => 'select',
            'default' => '1:1',
            'options' => [
                '1:1' => __('Square (1:1)', 'probuilder'),
                '4:3' => __('Standard (4:3)', 'probuilder'),
                '16:9' => __('Wide (16:9)', 'probuilder'),
                '3:4' => __('Portrait (3:4)', 'probuilder'),
                'custom' => __('Custom Height', 'probuilder'),
            ],
        ]);
        
        $this->add_control('image_height', [
            'label' => __('Custom Image Height (px)', 'probuilder'),
            'type' => 'number',
            'default' => 300,
            'condition' => ['image_ratio' => 'custom'],
        ]);
        
        $this->add_control('image_fit', [
            'label' => __('Image Fit', 'probuilder'),
            'type' => 'select',
            'default' => 'cover',
            'options' => [
                'cover' => __('Cover', 'probuilder'),
                'contain' => __('Contain', 'probuilder'),
                'fill' => __('Fill', 'probuilder'),
            ],
        ]);
        
        $this->add_control('hover_effect', [
            'label' => __('Hover Effect', 'probuilder'),
            'type' => 'select',
            'default' => 'zoom',
            'options' => [
                'none' => __('None', 'probuilder'),
                'zoom' => __('Zoom In', 'probuilder'),
                'zoom-out' => __('Zoom Out', 'probuilder'),
                'slide' => __('Slide', 'probuilder'),
                'rotate' => __('Rotate', 'probuilder'),
                'blur' => __('Blur', 'probuilder'),
                'grayscale' => __('Grayscale to Color', 'probuilder'),
                'opacity' => __('Fade', 'probuilder'),
            ],
        ]);
        
        $this->add_control('show_second_image', [
            'label' => __('Show Second Image on Hover', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Show product gallery second image on hover', 'probuilder'),
        ]);
        
        $this->add_control('image_overlay', [
            'label' => __('Image Overlay on Hover', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('overlay_color', [
            'label' => __('Overlay Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.3)',
            'condition' => ['image_overlay' => 'yes'],
        ]);
        
        $this->add_control('card_shadow', [
            'label' => __('Card Shadow', 'probuilder'),
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'none' => __('None', 'probuilder'),
                'small' => __('Small', 'probuilder'),
                'medium' => __('Medium', 'probuilder'),
                'large' => __('Large', 'probuilder'),
            ],
        ]);
        
        $this->add_control('card_hover_shadow', [
            'label' => __('Hover Shadow', 'probuilder'),
            'type' => 'select',
            'default' => 'large',
            'options' => [
                'none' => __('None', 'probuilder'),
                'small' => __('Small', 'probuilder'),
                'medium' => __('Medium', 'probuilder'),
                'large' => __('Large', 'probuilder'),
                'xl' => __('Extra Large', 'probuilder'),
            ],
        ]);
        
        $this->add_control('card_hover_lift', [
            'label' => __('Lift Card on Hover', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Slightly lift card on hover', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Badge & Labels Section
        $this->start_controls_section('section_badges', [
            'label' => __('Badges & Labels', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('badge_style', [
            'label' => __('Badge Style', 'probuilder'),
            'type' => 'select',
            'default' => 'modern',
            'options' => [
                'modern' => __('Modern Rounded', 'probuilder'),
                'minimal' => __('Minimal', 'probuilder'),
                'bold' => __('Bold', 'probuilder'),
                'outline' => __('Outline', 'probuilder'),
            ],
        ]);
        
        $this->add_control('badge_position', [
            'label' => __('Badge Position', 'probuilder'),
            'type' => 'select',
            'default' => 'top-left',
            'options' => [
                'top-left' => __('Top Left', 'probuilder'),
                'top-right' => __('Top Right', 'probuilder'),
                'bottom-left' => __('Bottom Left', 'probuilder'),
                'bottom-right' => __('Bottom Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('sale_badge_color', [
            'label' => __('Sale Badge Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e74c3c',
        ]);
        
        $this->add_control('featured_badge_color', [
            'label' => __('Featured Badge Color', 'probuilder'),
            'type' => 'color',
            'default' => '#f39c12',
        ]);
        
        $this->add_control('new_badge', [
            'label' => __('Show "New" Badge', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Show badge for products less than 30 days old', 'probuilder'),
        ]);
        
        $this->add_control('new_badge_days', [
            'label' => __('New Badge Duration (days)', 'probuilder'),
            'type' => 'number',
            'default' => 30,
            'condition' => ['new_badge' => 'yes'],
        ]);
        
        $this->add_control('out_of_stock_badge', [
            'label' => __('Show "Out of Stock" Badge', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        // Quick Actions Section
        $this->start_controls_section('section_quick_actions', [
            'label' => __('Quick Actions', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('show_quick_view', [
            'label' => __('Show Quick View', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Show quick view button (opens product in popup)', 'probuilder'),
        ]);
        
        $this->add_control('show_wishlist', [
            'label' => __('Show Wishlist', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Show add to wishlist button', 'probuilder'),
        ]);
        
        $this->add_control('show_compare', [
            'label' => __('Show Compare', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Show add to compare button', 'probuilder'),
        ]);
        
        $this->add_control('show_select_options', [
            'label' => __('Show Select Options', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Show "Select Options" for variable products (size, color, etc.)', 'probuilder'),
        ]);
        
        $this->add_control('show_view_details', [
            'label' => __('Show View Details', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Show "View Details" button to go to product page', 'probuilder'),
        ]);
        
        $this->add_control('show_add_to_cart_icon', [
            'label' => __('Show Add to Cart Icon', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Show shopping cart icon button', 'probuilder'),
        ]);
        
        $this->add_control('quick_actions_style', [
            'label' => __('Quick Actions Style', 'probuilder'),
            'type' => 'select',
            'default' => 'on-hover',
            'options' => [
                'always-visible' => __('Always Visible', 'probuilder'),
                'on-hover' => __('Show on Hover', 'probuilder'),
                'icon-only' => __('Icons Only', 'probuilder'),
                'text-only' => __('Text Only', 'probuilder'),
            ],
        ]);
        
        $this->add_control('actions_position', [
            'label' => __('Actions Position', 'probuilder'),
            'type' => 'select',
            'default' => 'top-right',
            'options' => [
                'top-right' => __('Top Right Corner', 'probuilder'),
                'top-left' => __('Top Left Corner', 'probuilder'),
                'bottom-right' => __('Bottom Right Corner', 'probuilder'),
                'overlay' => __('Center Overlay', 'probuilder'),
                'below-image' => __('Below Image', 'probuilder'),
            ],
            'condition' => ['quick_actions_style' => ['on-hover', 'always-visible']],
        ]);
        
        $this->end_controls_section();
        
        // Typography Section
        $this->start_controls_section('section_typography', [
            'label' => __('Typography', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('title_font_size', [
            'label' => __('Title Font Size (px)', 'probuilder'),
            'type' => 'number',
            'default' => 16,
        ]);
        
        $this->add_control('title_font_weight', [
            'label' => __('Title Font Weight', 'probuilder'),
            'type' => 'select',
            'default' => '600',
            'options' => [
                '400' => __('Normal', 'probuilder'),
                '500' => __('Medium', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
            ],
        ]);
        
        $this->add_control('price_font_size', [
            'label' => __('Price Font Size (px)', 'probuilder'),
            'type' => 'number',
            'default' => 18,
        ]);
        
        $this->add_control('price_font_weight', [
            'label' => __('Price Font Weight', 'probuilder'),
            'type' => 'select',
            'default' => '700',
            'options' => [
                '400' => __('Normal', 'probuilder'),
                '500' => __('Medium', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Pagination Section
        $this->start_controls_section('section_pagination', [
            'label' => __('Pagination', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('enable_pagination', [
            'label' => __('Enable Pagination', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('pagination_type', [
            'label' => __('Pagination Type', 'probuilder'),
            'type' => 'select',
            'default' => 'numbers',
            'options' => [
                'numbers' => __('Numbers', 'probuilder'),
                'prev-next' => __('Prev/Next Only', 'probuilder'),
                'load-more' => __('Load More Button', 'probuilder'),
                'infinite' => __('Infinite Scroll', 'probuilder'),
            ],
            'condition' => ['enable_pagination' => 'yes'],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Enqueue WooCommerce add-to-cart scripts
        if (class_exists('WooCommerce')) {
            wp_enqueue_script('wc-add-to-cart');
            wp_enqueue_script('wc-cart-fragments');
        }
        
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
        
        $enable_tabs = $this->get_settings('enable_tabs', 'no');
        $tabs = $this->get_settings('tabs', []);
        $tabs_style = $this->get_settings('tabs_style', 'modern');
        $query_type = $this->get_settings('query_type', 'recent');
        $per_page = intval($this->get_settings('products_per_page', 8));
        $orderby = $this->get_settings('orderby', 'date');
        $order = $this->get_settings('order', 'DESC');
        
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
            /* Tabs Styles */
            #' . $widget_id . ' .pb-products-tabs {
                display: flex;
                gap: 10px;
                margin-bottom: 30px;
                border-bottom: 2px solid #e5e7eb;
                padding-bottom: 0;
            }
            #' . $widget_id . ' .pb-products-tabs.modern {
                border-bottom: 2px solid #e5e7eb;
            }
            #' . $widget_id . ' .pb-products-tabs.minimal {
                border-bottom: none;
                gap: 20px;
            }
            #' . $widget_id . ' .pb-products-tabs.underline {
                border-bottom: 2px solid #e5e7eb;
            }
            #' . $widget_id . ' .pb-products-tabs.pills {
                border-bottom: none;
            }
            #' . $widget_id . ' .pb-tab-button {
                padding: 12px 24px;
                background: transparent;
                border: none;
                cursor: pointer;
                font-size: 15px;
                font-weight: 600;
                color: #6b7280;
                transition: all 0.3s;
                position: relative;
                border-bottom: 2px solid transparent;
                margin-bottom: -2px;
            }
            #' . $widget_id . ' .pb-tab-button:hover {
                color: #92003b;
            }
            #' . $widget_id . ' .pb-tab-button.active {
                color: #92003b;
                border-bottom-color: #92003b;
            }
            #' . $widget_id . ' .pb-products-tabs.pills .pb-tab-button {
                border-radius: 25px;
                border-bottom: none;
                background: #f3f4f6;
            }
            #' . $widget_id . ' .pb-products-tabs.pills .pb-tab-button.active {
                background: #92003b;
                color: #ffffff;
            }
            #' . $widget_id . ' .pb-tab-content {
                display: none;
            }
            #' . $widget_id . ' .pb-tab-content.active {
                display: block;
            }
            /* Product Card Actions */
            #' . $widget_id . ' .product-actions {
                position: absolute;
                display: flex;
                flex-direction: column;
                gap: 8px;
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 20;
            }
            #' . $widget_id . ' .probuilder-product-card:hover .product-actions {
                opacity: 1;
            }
            #' . $widget_id . ' .product-actions.always-visible {
                opacity: 1;
            }
            #' . $widget_id . ' .product-actions.overlay {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                flex-direction: row;
            }
            #' . $widget_id . ' .product-actions.top-right {
                top: 12px;
                right: 12px;
                flex-direction: column;
            }
            #' . $widget_id . ' .product-actions.top-left {
                top: 12px;
                left: 12px;
                flex-direction: column;
            }
            #' . $widget_id . ' .product-actions.bottom-right {
                bottom: 12px;
                right: 12px;
                flex-direction: column;
            }
            #' . $widget_id . ' .product-actions.below-image {
                position: relative;
                top: auto;
                left: auto;
                transform: none;
                justify-content: center;
                padding: 10px;
                background: rgba(255,255,255,0.95);
                flex-direction: row;
                opacity: 1;
            }
            #' . $widget_id . ' .product-action-btn {
                width: 40px;
                height: 40px;
                min-width: 40px;
                min-height: 40px;
                border-radius: 50%;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                color: #374151;
                text-decoration: none;
                font-size: 16px;
                padding: 0;
                margin: 0;
            }
            #' . $widget_id . ' .product-action-btn .dashicons {
                color: #374151;
                transition: color 0.3s;
            }
            #' . $widget_id . ' .product-action-btn:hover {
                background: #92003b;
                border-color: #92003b;
                transform: scale(1.1);
                box-shadow: 0 4px 12px rgba(146,0,59,0.3);
            }
            #' . $widget_id . ' .product-action-btn:hover .dashicons {
                color: #ffffff;
            }
            #' . $widget_id . ' .product-action-btn.in-wishlist,
            #' . $widget_id . ' .product-action-btn.in-compare {
                background: #92003b;
                border-color: #92003b;
            }
            #' . $widget_id . ' .product-action-btn.in-wishlist .dashicons,
            #' . $widget_id . ' .product-action-btn.in-compare .dashicons {
                color: #ffffff;
            }
            #' . $widget_id . ' .product-image {
                position: relative;
            }
        </style>';
        
        // Outer wrapper with padding/margin/etc from style tab
        echo '<div id="' . esc_attr($widget_id) . '" class="' . esc_attr($wrapper_classes) . ' probuilder-woo-products-wrapper" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
        // Tabs if enabled
        if ($enable_tabs === 'yes' && !empty($tabs)) {
            echo '<div class="pb-products-tabs ' . esc_attr($tabs_style) . '">';
            foreach ($tabs as $index => $tab) {
                $tab_id = 'tab-' . $index;
                $is_active = $index === 0 ? 'active' : '';
                $label = isset($tab['label']) ? $tab['label'] : 'Tab ' . ($index + 1);
                echo '<button class="pb-tab-button ' . esc_attr($is_active) . '" data-tab="' . esc_attr($tab_id) . '" data-query="' . esc_attr($tab['query_type']) . '">' . esc_html($label) . '</button>';
            }
            echo '</div>';
            
            // Tab content containers
            foreach ($tabs as $index => $tab) {
                $tab_id = 'tab-' . $index;
                $is_active = $index === 0 ? 'active' : '';
                $query_type = isset($tab['query_type']) ? $tab['query_type'] : 'recent';
                
                echo '<div class="pb-tab-content ' . esc_attr($is_active) . '" data-tab-id="' . esc_attr($tab_id) . '">';
                $this->render_products_grid($query_type, $per_page, $orderby, $order, $columns, $column_gap, $row_gap, $widget_id);
                echo '</div>';
            }
        } else {
            // Single query without tabs
            $this->render_products_grid($query_type, $per_page, $orderby, $order, $columns, $column_gap, $row_gap, $widget_id);
        }
        
        // Add tabs JavaScript
        if ($enable_tabs === 'yes' && !empty($tabs)) {
            echo '<script>
                (function() {
                    const widgetEl = document.getElementById("' . $widget_id . '");
                    if (!widgetEl) return;
                    
                    const tabButtons = widgetEl.querySelectorAll(".pb-tab-button");
                    const tabContents = widgetEl.querySelectorAll(".pb-tab-content");
                    
                    tabButtons.forEach(button => {
                        button.addEventListener("click", function() {
                            const tabId = this.getAttribute("data-tab");
                            
                            // Remove active class from all
                            tabButtons.forEach(btn => btn.classList.remove("active"));
                            tabContents.forEach(content => content.classList.remove("active"));
                            
                            // Add active to clicked
                            this.classList.add("active");
                            const targetContent = widgetEl.querySelector(`[data-tab-id="${tabId}"]`);
                            if (targetContent) {
                                targetContent.classList.add("active");
                                
                                // Load products if not already loaded
                                if (!targetContent.dataset.loaded) {
                                    const queryType = this.getAttribute("data-query");
                                    loadProductsTab("' . $widget_id . '", tabId, queryType);
                                    targetContent.dataset.loaded = "true";
                                }
                            }
                        });
                    });
                    
                    function loadProductsTab(widgetId, tabId, queryType) {
                        // AJAX load products for this tab
                        const contentEl = document.querySelector(`#${widgetId} [data-tab-id="${tabId}"]`);
                        if (contentEl && !contentEl.dataset.loaded) {
                            // This would be an AJAX call in production
                            // For now, products are already rendered
                        }
                    }
                })();
            </script>';
        }
        
        // Add Quick View Modal & JavaScript to footer (outside widget wrapper)
        add_action('wp_footer', [$this, 'render_quick_view_modal'], 999);
        add_action('wp_footer', [$this, 'render_action_scripts'], 999);
        
        echo '</div>'; // .probuilder-woo-products-wrapper
    }
    
    /**
     * Render Quick View Modal
     */
    public function render_quick_view_modal() {
        // Only render once
        static $rendered = false;
        if ($rendered) return;
        $rendered = true;
        ?>
        <!-- Quick View Modal -->
        <div id="pb-quick-view-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.85); z-index: 99999999; align-items: center; justify-content: center;">
            <div style="position: relative; background: white; max-width: 900px; width: 90%; max-height: 90vh; overflow-y: auto; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.4); z-index: 99999999;">
                <button onclick="pbCloseQuickView()" style="position: absolute; top: 15px; right: 15px; background: #f3f4f6; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 24px; line-height: 1; z-index: 10; transition: all 0.3s;" onmouseover="this.style.background='#92003b'; this.style.color='white'" onmouseout="this.style.background='#f3f4f6'; this.style.color='#000'">×</button>
                <div id="pb-quick-view-content" style="padding: 30px;">
                    <div style="text-align: center; padding: 60px 20px;">
                        <div class="spinner" style="border: 4px solid #f3f4f6; border-top: 4px solid #92003b; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                        <p style="margin-top: 20px; color: #6b7280;">Loading product...</p>
                    </div>
                </div>
            </div>
        </div>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            #pb-quick-view-modal {
                z-index: 99999999 !important;
            }
            #pb-quick-view-modal.active {
                display: flex !important;
                z-index: 99999999 !important;
            }
            #pb-quick-view-modal > div {
                z-index: 99999999 !important;
                position: relative;
            }
            /* Ensure notifications appear above modal */
            .pb-notification,
            .pb-cart-notification {
                z-index: 999999999 !important;
            }
        </style>
        <?php
    }
    
    /**
     * Render Action Scripts
     */
    public function render_action_scripts() {
        // Only render once
        static $rendered = false;
        if ($rendered) return;
        $rendered = true;
        ?>
        <script type="text/javascript">
        // Ensure jQuery is loaded
        (function($) {
            'use strict';
            
        // Quick View Function (Global scope for onclick)
        window.pbQuickView = function(productId) {
            const modal = document.getElementById('pb-quick-view-modal');
            const content = document.getElementById('pb-quick-view-content');
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Show modal
            modal.classList.add('active');
            modal.style.display = 'flex';
            
            // Load product via AJAX
            fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=pb_quick_view&product_id=' + productId)
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = '<div style="padding: 40px; text-align: center;"><p style="color: #ef4444;">Failed to load product. Please try again.</p></div>';
                });
        }
        
        // Close Quick View (Global scope for onclick)
        window.pbCloseQuickView = function() {
            const modal = document.getElementById('pb-quick-view-modal');
            modal.classList.remove('active');
            modal.style.display = 'none';
            
            // Restore body scroll
            document.body.style.overflow = '';
        };
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.pbCloseQuickView();
            }
        });
        
        // Close on backdrop click
        const qvModal = document.getElementById('pb-quick-view-modal');
        if (qvModal) {
            qvModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.pbCloseQuickView();
                }
            });
        }
        
        // Add to Wishlist Function (Global scope for onclick)
        window.pbAddToWishlist = function(productId, button) {
            // Check if already in wishlist
            if (button.classList.contains('in-wishlist')) {
                window.pbShowNotification('Already in wishlist!', 'info');
                return;
            }
            
            // Visual feedback
            button.style.transform = 'scale(1.2)';
            button.style.background = '#92003b';
            button.querySelector('.dashicons').style.color = 'white';
            
            // Get or create wishlist
            let wishlist = JSON.parse(localStorage.getItem('pb_wishlist') || '[]');
            
            if (!wishlist.includes(productId)) {
                wishlist.push(productId);
                localStorage.setItem('pb_wishlist', JSON.stringify(wishlist));
                
                button.classList.add('in-wishlist');
                window.pbShowNotification('Added to wishlist!', 'success');
                
                // Update wishlist count if exists
                const counter = document.querySelector('.pb-wishlist-count');
                if (counter) {
                    counter.textContent = wishlist.length;
                }
            }
            
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 300);
        }
        
        // Add to Compare Function (Global scope for onclick)
        window.pbAddToCompare = function(productId, button) {
            // Check if already in compare
            if (button.classList.contains('in-compare')) {
                window.pbShowNotification('Already in compare list!', 'info');
                return;
            }
            
            // Visual feedback
            button.style.transform = 'scale(1.2)';
            button.style.background = '#92003b';
            button.querySelector('.dashicons').style.color = 'white';
            
            // Get or create compare list
            let compare = JSON.parse(localStorage.getItem('pb_compare') || '[]');
            
            if (!compare.includes(productId)) {
                compare.push(productId);
                localStorage.setItem('pb_compare', JSON.stringify(compare));
                
                button.classList.add('in-compare');
                window.pbShowNotification('Added to compare!', 'success');
                
                // Update compare count if exists
                const counter = document.querySelector('.pb-compare-count');
                if (counter) {
                    counter.textContent = compare.length;
                }
            }
            
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 300);
        }
        
        // Show Notification (Global scope)
        window.pbShowNotification = function(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = 'pb-notification';
            notification.textContent = message;
            
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                info: '#3b82f6'
            };
            
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${colors[type]};
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                z-index: 999999999;
                font-weight: 600;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Override WooCommerce's add to cart to prevent redirect and show notification
        jQuery(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
            console.log('✅ Product added to cart event triggered!');
            
            // Get product ID from button
            const productId = button.data('product_id');
            
            // Change button to "View Cart"
            button.removeClass('loading').addClass('added');
            button.html('✓ View Cart');
            button.attr('href', '<?php echo esc_url(wc_get_cart_url()); ?>');
            
            // Show custom notification
            window.pbShowAddedToCartNotification(productId);
        });
        
        // Show "Added to Cart" notification with View Cart button (Global scope)
        window.pbShowAddedToCartNotification = function(productId) {
            const notification = document.createElement('div');
            notification.className = 'pb-notification pb-cart-notification';
            
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span class="dashicons dashicons-yes-alt" style="font-size: 24px; color: white;"></span>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">Added to Cart!</div>
                        <div style="font-size: 13px; opacity: 0.9;">Product successfully added to your cart</div>
                    </div>
                </div>
                <div style="margin-top: 12px; display: flex; gap: 8px;">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" style="background: white; color: #10b981; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-block; transition: all 0.3s;">View Cart</a>
                    <button onclick="this.closest('.pb-cart-notification').remove()" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s;">Continue Shopping</button>
                </div>
            `;
            
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #10b981;
                color: white;
                padding: 20px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(16, 185, 129, 0.4);
                z-index: 999999999;
                min-width: 350px;
                max-width: 400px;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 8 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 8000);
        }
        
        // Check wishlist/compare on page load
        document.addEventListener('DOMContentLoaded', function() {
            const wishlist = JSON.parse(localStorage.getItem('pb_wishlist') || '[]');
            const compare = JSON.parse(localStorage.getItem('pb_compare') || '[]');
            
            wishlist.forEach(id => {
                const btn = document.querySelector('.pb-wishlist[data-product-id="' + id + '"]');
                if (btn) {
                    btn.classList.add('in-wishlist');
                    btn.style.background = '#92003b';
                    btn.querySelector('.dashicons').style.color = 'white';
                }
            });
            
            compare.forEach(id => {
                const btn = document.querySelector('.pb-compare[data-product-id="' + id + '"]');
                if (btn) {
                    btn.classList.add('in-compare');
                    btn.style.background = '#92003b';
                    btn.querySelector('.dashicons').style.color = 'white';
                }
            });
        });
        
        })(jQuery); // End jQuery wrapper
        </script>
        <style>
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
            
            /* Add to Cart Button States */
            .ajax_add_to_cart.loading {
                opacity: 0.7;
                pointer-events: none;
            }
            
            .ajax_add_to_cart.added {
                background: #10b981 !important;
            }
            
            /* Cart Notification Hover Effects */
            .pb-cart-notification a:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
            
            .pb-cart-notification button:hover {
                background: rgba(255,255,255,0.3) !important;
            }
        </style>
        <?php
    }
    
    /**
     * Render products grid
     */
    private function render_products_grid($query_type, $per_page, $orderby, $order, $columns, $column_gap, $row_gap, $widget_id) {
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
                $args['order'] = $order;
                break;
                
            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = $order;
                break;
                
            default: // recent
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
        }
        
        $products = new WP_Query($args);
        
        // Grid style
        $grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($row_gap) . 'px ' . esc_attr($column_gap) . 'px; width: 100%;';
        
        // Inner grid container
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
        
        // Get action settings
        $show_quick_view = $this->get_settings('show_quick_view', 'yes');
        $show_wishlist = $this->get_settings('show_wishlist', 'yes');
        $show_compare = $this->get_settings('show_compare', 'yes');
        $show_select_options = $this->get_settings('show_select_options', 'yes');
        $show_view_details = $this->get_settings('show_view_details', 'yes');
        $show_add_to_cart_icon = $this->get_settings('show_add_to_cart_icon', 'yes');
        $actions_style = $this->get_settings('quick_actions_style', 'on-hover');
        $actions_position = $this->get_settings('actions_position', 'top-right');
        
        // Check if product is variable
        $is_variable = $product->is_type('variable');
        
        echo '<div class="probuilder-product-card" style="border-radius: ' . esc_attr($border_radius) . 'px; overflow: hidden; background: ' . esc_attr($card_bg) . '; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s; position: relative;">';
        
        // Image
        if ($show_image) {
            $image_ratio = $this->get_settings('image_ratio', '1:1');
            $image_height = $this->get_settings('image_height', 300);
            $image_fit = $this->get_settings('image_fit', 'cover');
            
            // Calculate padding-top based on aspect ratio
            $ratio_map = [
                '1:1' => '100%',
                '4:3' => '75%',
                '3:4' => '133.33%',
                '16:9' => '56.25%',
                'custom' => $image_height . 'px',
            ];
            
            $padding_top = isset($ratio_map[$image_ratio]) ? $ratio_map[$image_ratio] : '100%';
            
            // Use aspect ratio technique for consistent image heights
            $image_container_style = 'position: relative; background: #f8f9fa; overflow: hidden;';
            if ($image_ratio !== 'custom') {
                $image_container_style .= ' padding-top: ' . $padding_top . ';';
            } else {
                $image_container_style .= ' height: ' . $image_height . 'px;';
            }
            
            echo '<div class="product-image" style="' . esc_attr($image_container_style) . '">';
            echo '<a href="' . esc_url($product->get_permalink()) . '" style="display: block; position: ' . ($image_ratio !== 'custom' ? 'absolute' : 'relative') . '; top: 0; left: 0; width: 100%; height: 100%;">';
            echo $product->get_image('medium', ['style' => 'width: 100%; height: 100%; object-fit: ' . esc_attr($image_fit) . '; display: block; transition: transform 0.3s;']);
            echo '</a>';
            
            // Sale badge
            if ($show_badge && $product->is_on_sale()) {
                $badge_style = $this->get_settings('badge_style', 'modern');
                $badge_position = $this->get_settings('badge_position', 'top-right');
                
                $badge_classes = 'sale-badge badge-' . esc_attr($badge_style);
                $badge_pos_style = '';
                
                switch ($badge_position) {
                    case 'top-left':
                        $badge_pos_style = 'top: 10px; left: 10px;';
                        break;
                    case 'top-right':
                        $badge_pos_style = 'top: 10px; right: 10px;';
                        break;
                    case 'bottom-left':
                        $badge_pos_style = 'bottom: 10px; left: 10px;';
                        break;
                    case 'bottom-right':
                        $badge_pos_style = 'bottom: 10px; right: 10px;';
                        break;
                }
                
                $badge_bg = $this->get_settings('sale_badge_color', '#e74c3c');
                
                echo '<span class="' . esc_attr($badge_classes) . '" style="position: absolute; ' . $badge_pos_style . ' background: ' . esc_attr($badge_bg) . '; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; z-index: 10;">Sale</span>';
            }
            
            // Product Actions (Quick View, Wishlist, Compare, etc.)
            $has_actions = ($show_quick_view === 'yes' || $show_wishlist === 'yes' || $show_compare === 'yes' || 
                           ($show_select_options === 'yes' && $is_variable) || $show_view_details === 'yes' || $show_add_to_cart_icon === 'yes');
            
            if ($has_actions) {
                $action_classes = 'product-actions';
                if ($actions_style === 'always-visible') {
                    $action_classes .= ' always-visible';
                }
                $action_classes .= ' ' . esc_attr($actions_position);
                
                echo '<div class="' . esc_attr($action_classes) . '" style="z-index: 20;">';
                
                // Quick View
                if ($show_quick_view === 'yes') {
                    echo '<button type="button" class="product-action-btn pb-quick-view" title="' . esc_attr__('Quick View', 'probuilder') . '" data-product-id="' . esc_attr($product->get_id()) . '" onclick="pbQuickView(' . esc_attr($product->get_id()) . ')">';
                    echo '<span class="dashicons dashicons-visibility"></span>';
                    echo '</button>';
                }
                
                // Wishlist
                if ($show_wishlist === 'yes') {
                    echo '<button type="button" class="product-action-btn pb-wishlist" title="' . esc_attr__('Add to Wishlist', 'probuilder') . '" data-product-id="' . esc_attr($product->get_id()) . '" onclick="pbAddToWishlist(' . esc_attr($product->get_id()) . ', this)">';
                    echo '<span class="dashicons dashicons-heart"></span>';
                    echo '</button>';
                }
                
                // Compare
                if ($show_compare === 'yes') {
                    echo '<button type="button" class="product-action-btn pb-compare" title="' . esc_attr__('Add to Compare', 'probuilder') . '" data-product-id="' . esc_attr($product->get_id()) . '" onclick="pbAddToCompare(' . esc_attr($product->get_id()) . ', this)">';
                    echo '<span class="dashicons dashicons-arrow-left-right"></span>';
                    echo '</button>';
                }
                
                // Select Options (for variable products)
                if ($show_select_options === 'yes' && $is_variable) {
                    echo '<a href="' . esc_url($product->get_permalink()) . '" class="product-action-btn pb-select-options" title="' . esc_attr__('Select Options', 'probuilder') . '">';
                    echo '<span class="dashicons dashicons-cart"></span>';
                    echo '</a>';
                }
                
                // Add to Cart Icon
                if ($show_add_to_cart_icon === 'yes' && !$is_variable) {
                    $add_to_cart_url = $product->add_to_cart_url();
                    echo '<a href="' . esc_url($add_to_cart_url) . '" class="product-action-btn pb-add-to-cart ajax_add_to_cart" title="' . esc_attr__('Add to Cart', 'probuilder') . '" data-product-id="' . esc_attr($product->get_id()) . '" data-product-type="' . esc_attr($product->get_type()) . '">';
                    echo '<span class="dashicons dashicons-cart"></span>';
                    echo '</a>';
                }
                
                // View Details
                if ($show_view_details === 'yes') {
                    echo '<a href="' . esc_url($product->get_permalink()) . '" class="product-action-btn pb-view-details" title="' . esc_attr__('View Details', 'probuilder') . '">';
                    echo '<span class="dashicons dashicons-arrow-right-alt"></span>';
                    echo '</a>';
                }
                
                echo '</div>'; // .product-actions
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
        
        // Add to Cart / Select Options
        if ($show_cart) {
            $button_text = __('View Product', 'probuilder');
            $button_class = 'button product_type_simple add_to_cart_button';
            $button_url = $product->get_permalink();
            
            if ($is_variable) {
                $button_text = __('Select Options', 'probuilder');
                $button_class = 'button product_type_variable';
                // Variable product - use link
                echo '<a href="' . esc_url($button_url) . '" class="' . esc_attr($button_class) . '" style="background: ' . esc_attr($btn_bg) . '; color: ' . esc_attr($btn_text) . '; padding: 12px 24px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif; transition: all 0.3s;">' . esc_html($button_text) . '</a>';
            } elseif ($product->is_in_stock()) {
                $button_text = __('Add to Cart', 'probuilder');
                $button_class = 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart pb_add_to_cart';
                // Simple product - use link with special class
                echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="' . esc_attr($button_class) . '" style="background: ' . esc_attr($btn_bg) . '; color: ' . esc_attr($btn_text) . '; padding: 12px 24px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif; transition: all 0.3s; cursor: pointer; width: 100%;" data-product_id="' . esc_attr($product->get_id()) . '" data-product_sku="' . esc_attr($product->get_sku()) . '" data-quantity="1" rel="nofollow">' . esc_html($button_text) . '</a>';
            } else {
                // Out of stock - use link
                echo '<a href="' . esc_url($button_url) . '" class="' . esc_attr($button_class) . '" style="background: ' . esc_attr($btn_bg) . '; color: ' . esc_attr($btn_text) . '; padding: 12px 24px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif; transition: all 0.3s;">' . esc_html($button_text) . '</a>';
            }
        }
        
        echo '</div>'; // .product-content
        echo '</div>'; // .probuilder-product-card
    }
}

