<?php
/**
 * ProBuilder Templates Library - SIMPLE VERSION WITH REAL VISUAL CONTENT
 * No complex nesting - just beautiful, working templates with real images and text
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Templates_Library {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_probuilder_get_templates', [$this, 'get_templates']);
        add_action('wp_ajax_probuilder_get_template_data', [$this, 'ajax_get_template_data']);
        add_action('wp_ajax_probuilder_save_template', [$this, 'save_template']);
        add_action('wp_ajax_probuilder_delete_template', [$this, 'delete_template']);
        add_action('wp_ajax_probuilder_import_template', [$this, 'import_template']);
    }
    
    public function ajax_get_template_data() {
        if (!isset($_POST['template_id'])) {
            wp_send_json_error(['message' => 'Template ID required']);
        }
        
        $template_id = sanitize_text_field($_POST['template_id']);
        $data = $this->get_template_data($template_id);
        
        if ($data) {
            wp_send_json_success(['data' => $data]);
        } else {
            wp_send_json_error(['message' => 'Template not found']);
        }
    }
    
    public function get_templates() {
        $prebuilt = $this->get_prebuilt_templates_meta();
        $user = $this->get_user_templates();
        
        $templates = [
            'prebuilt' => $prebuilt,
            'user' => $user
        ];
        
        wp_send_json_success($templates);
    }
    
    public function get_prebuilt_templates_meta() {
        $placeholder = $this->create_svg_placeholder('ProBuilder', '#92003b');
        
        return [
            ['id' => 'clothing-store-home', 'name' => '👔 Clothing Store - Full Homepage', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'woodmart-home', 'name' => '🌳 WoodMart Home - Full Featured', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'porto-shop', 'name' => '🛒 Porto Shop - Real Visual Content', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'fashion-store', 'name' => '👗 Fashion Store - Complete', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'electronics', 'name' => '💻 Electronics Store - Full Page', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'modern-shop', 'name' => '✨ Modern Shop - Inspired Design', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
        ];
    }
    
    public function get_template_data($template_id) {
        $all_templates = $this->get_prebuilt_templates();
        
        foreach ($all_templates as $template) {
            if ($template['id'] === $template_id) {
                return $template['data'];
            }
        }
        
        return null;
    }
    
    public function get_prebuilt_templates() {
        return [
            ['id' => 'clothing-store-home', 'name' => 'Clothing Store Home', 'category' => 'pages', 'data' => $this->template_clothing_store_home()],
            ['id' => 'woodmart-home', 'name' => 'WoodMart Home', 'category' => 'pages', 'data' => $this->template_woodmart_home()],
            ['id' => 'porto-shop', 'name' => 'Porto Shop', 'category' => 'pages', 'data' => $this->template_porto_visual()],
            ['id' => 'fashion-store', 'name' => 'Fashion Store', 'category' => 'pages', 'data' => $this->template_fashion_visual()],
            ['id' => 'electronics', 'name' => 'Electronics', 'category' => 'pages', 'data' => $this->template_electronics_visual()],
            ['id' => 'modern-shop', 'name' => 'Modern Shop', 'category' => 'pages', 'data' => $this->template_modern_shop()],
        ];
    }
    
    private function create_svg_placeholder($text, $color = '#92003b') {
        $svg = '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="' . $color . '" font-size="20" font-weight="bold">' . $text . '</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    private function generate_id() {
        return 'el_' . uniqid();
    }
    
    /**
     * CLOTHING STORE HOME - FULL PAGE MODERN DESIGN
     * Complete homepage for clothing/fashion stores
     */
    private function template_clothing_store_home() {
        return [
            // === HERO SLIDER - FULL WIDTH ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'slider',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0',
                    '_margin' => '0',
                    'slides' => [
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1920&h=800&fit=crop',
                            'background_overlay' => 'rgba(0,0,0,0.2)',
                            'title' => 'New Season Collection',
                            'title_size' => '64px',
                            'title_color' => '#ffffff',
                            'title_weight' => '700',
                            'description' => 'Discover the latest trends in fashion',
                            'description_size' => '24px',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#1f2937',
                            'button_text_color' => '#ffffff',
                            'content_position' => 'center center',
                            'alignment' => 'center',
                        ],
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=800&fit=crop',
                            'background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'Women\'s Fashion',
                            'title_size' => '64px',
                            'title_color' => '#ffffff',
                            'title_weight' => '700',
                            'description' => 'Elegant styles for every occasion',
                            'description_size' => '24px',
                            'description_color' => '#ffffff',
                            'button_text' => 'Explore Collection',
                            'button_link' => '/shop',
                            'button_bg_color' => '#db2777',
                            'button_text_color' => '#ffffff',
                            'content_position' => 'center center',
                            'alignment' => 'center',
                        ],
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=1920&h=800&fit=crop',
                            'background_overlay' => 'rgba(0,0,0,0.25)',
                            'title' => 'Men\'s Collection',
                            'title_size' => '64px',
                            'title_color' => '#ffffff',
                            'title_weight' => '700',
                            'description' => 'Style meets comfort',
                            'description_size' => '24px',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Men',
                            'button_link' => '/shop',
                            'button_bg_color' => '#2563eb',
                            'button_text_color' => '#ffffff',
                            'content_position' => 'center center',
                            'alignment' => 'center',
                        ],
                    ],
                    '_height' => '700px',
                    'autoplay' => 'yes',
                    'autoplay_speed' => '5000',
                    'show_arrows' => 'yes',
                    'show_dots' => 'yes',
                    'animation' => 'fade',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === CATEGORY BANNERS - 3 COLUMNS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '0 20px',
                    'columns' => '3',
                    'gap' => '30',
                ],
                'children' => [
                    // Women's Collection
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=500&h=700&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'Women\'s Wear',
                            'title_size' => '32px',
                            'title_color' => '#ffffff',
                            'title_weight' => '700',
                            'description' => 'Dresses, Tops & More',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Women',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#1f2937',
                            'alignment' => 'center',
                            '_min_height' => '500px',
                            '_padding' => '40px',
                        ],
                        'children' => []
                    ],
                    // Men's Collection
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=500&h=700&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'Men\'s Wear',
                            'title_size' => '32px',
                            'title_color' => '#ffffff',
                            'title_weight' => '700',
                            'description' => 'Shirts, Pants & Accessories',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Men',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#1f2937',
                            'alignment' => 'center',
                            '_min_height' => '500px',
                            '_padding' => '40px',
                        ],
                        'children' => []
                    ],
                    // Kids Collection
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=500&h=700&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'Kids Collection',
                            'title_size' => '32px',
                            'title_color' => '#ffffff',
                            'title_weight' => '700',
                            'description' => 'Fun & Comfortable',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Kids',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#1f2937',
                            'alignment' => 'center',
                            '_min_height' => '500px',
                            '_padding' => '40px',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === SECTION HEADING ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Featured Products',
                    'tag' => 'h2',
                    'size' => '48px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '20px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'text',
                'settings' => [
                    'text' => 'Shop our handpicked collection of trending styles',
                    'size' => '18px',
                    'color' => '#6b7280',
                    'alignment' => 'center',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '50px'],
                'children' => []
            ],
            
            // === FEATURED PRODUCTS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '0 20px',
                    'query_type' => 'featured',
                    'products_per_page' => '8',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'quick_actions_style' => 'on-hover',
                    'actions_position' => 'top-right',
                    'column_gap' => '30',
                    'row_gap' => '40',
                    'image_ratio' => '3:4',
                    'hover_effect' => 'zoom-in',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === PROMOTIONAL BANNER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '0 20px',
                    '_background_type' => 'gradient',
                    '_background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'title' => 'Limited Time Offer',
                    'title_size' => '52px',
                    'title_color' => '#ffffff',
                    'title_weight' => '700',
                    'description' => 'Get 30% off on all items. Use code: FASHION30',
                    'description_size' => '20px',
                    'description_color' => '#ffffff',
                    'button_text' => 'Shop Sale',
                    'button_link' => '/shop',
                    'button_bg_color' => '#ffffff',
                    'button_text_color' => '#667eea',
                    'alignment' => 'center',
                    '_min_height' => '350px',
                    '_border_radius' => '20px',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === NEW ARRIVALS SECTION ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'New Arrivals',
                    'tag' => 'h2',
                    'size' => '48px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '50px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '0 20px',
                    'query_type' => 'recent',
                    'products_per_page' => '8',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'quick_actions_style' => 'on-hover',
                    'actions_position' => 'top-right',
                    'column_gap' => '30',
                    'row_gap' => '40',
                    'image_ratio' => '3:4',
                    'hover_effect' => 'zoom-in',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === SHOP BY CATEGORY ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Shop by Category',
                    'tag' => 'h2',
                    'size' => '48px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '50px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-categories',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '0 20px',
                    'columns' => '4',
                    'number' => '8',
                    'show_image' => 'yes',
                    'show_count' => 'yes',
                    'show_description' => 'yes',
                    'image_height' => '300px',
                    'column_gap' => '30',
                    'row_gap' => '30',
                    'hover_effect' => 'zoom',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === FEATURES SECTION ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '60px 20px',
                    '_background_color' => '#f9fafb',
                    'columns' => '4',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fas fa-shipping-fast',
                            'icon_size' => '48px',
                            'icon_color' => '#667eea',
                            'title' => 'Free Shipping',
                            'title_size' => '20px',
                            'title_color' => '#1f2937',
                            'description' => 'On orders over $50',
                            'description_color' => '#6b7280',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fas fa-sync-alt',
                            'icon_size' => '48px',
                            'icon_color' => '#667eea',
                            'title' => 'Easy Returns',
                            'title_size' => '20px',
                            'title_color' => '#1f2937',
                            'description' => '30-day return policy',
                            'description_color' => '#6b7280',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fas fa-lock',
                            'icon_size' => '48px',
                            'icon_color' => '#667eea',
                            'title' => 'Secure Payment',
                            'title_size' => '20px',
                            'title_color' => '#1f2937',
                            'description' => '100% secure checkout',
                            'description_color' => '#6b7280',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fas fa-headset',
                            'icon_size' => '48px',
                            'icon_color' => '#667eea',
                            'title' => '24/7 Support',
                            'title_size' => '20px',
                            'title_color' => '#1f2937',
                            'description' => 'Dedicated customer service',
                            'description_color' => '#6b7280',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === TESTIMONIALS SECTION ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'What Our Customers Say',
                    'tag' => 'h2',
                    'size' => '48px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '50px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '0 20px',
                    'columns' => '3',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'testimonial',
                        'settings' => [
                            '_background_color' => '#ffffff',
                            '_border_width' => '1px',
                            '_border_color' => '#e5e7eb',
                            '_border_radius' => '10px',
                            '_padding' => '30px',
                            'content' => 'Amazing quality and fast shipping! The clothes fit perfectly and the fabric is top-notch.',
                            'name' => 'Sarah Johnson',
                            'title' => 'Verified Customer',
                            'rating' => '5',
                            'show_rating' => 'yes',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'testimonial',
                        'settings' => [
                            '_background_color' => '#ffffff',
                            '_border_width' => '1px',
                            '_border_color' => '#e5e7eb',
                            '_border_radius' => '10px',
                            '_padding' => '30px',
                            'content' => 'Love the variety and styles! Found exactly what I was looking for. Will definitely shop again.',
                            'name' => 'Michael Chen',
                            'title' => 'Verified Customer',
                            'rating' => '5',
                            'show_rating' => 'yes',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'testimonial',
                        'settings' => [
                            '_background_color' => '#ffffff',
                            '_border_width' => '1px',
                            '_border_color' => '#e5e7eb',
                            '_border_radius' => '10px',
                            '_padding' => '30px',
                            'content' => 'Excellent customer service and the return process was so easy. Highly recommend!',
                            'name' => 'Emma Davis',
                            'title' => 'Verified Customer',
                            'rating' => '5',
                            'show_rating' => 'yes',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '80px'],
                'children' => []
            ],
            
            // === NEWSLETTER SECTION ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    '_width' => '100%',
                    '_background_color' => '#1f2937',
                    '_padding' => '80px 20px',
                    'title' => 'Join Our Newsletter',
                    'title_size' => '42px',
                    'title_color' => '#ffffff',
                    'title_weight' => '700',
                    'description' => 'Subscribe to get special offers, free giveaways, and exclusive deals.',
                    'description_size' => '18px',
                    'description_color' => '#d1d5db',
                    'button_text' => 'Subscribe Now',
                    'button_link' => '#',
                    'button_bg_color' => '#667eea',
                    'button_text_color' => '#ffffff',
                    'alignment' => 'center',
                ],
                'children' => []
            ],
            
            // === FINAL SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
        ];
    }
    
    /**
     * WoodMart Home - COMPLETE E-COMMERCE HOMEPAGE
     * Inspired by WoodMart theme with all features
     */
    private function template_woodmart_home() {
        return [
            // === HERO BANNER GRID (2 columns) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 20px',
                    'columns' => '2',
                    'gap' => '20',
                ],
                'children' => [
                    // Left: Large product banner
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1610792516307-ea5acd9c3b00?w=800&h=600&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.1)',
                            'title' => 'Samsung Galaxy Flip5',
                            'title_size' => '36px',
                            'title_color' => '#1f2937',
                            'description' => 'Experience the future of smartphones',
                            'description_color' => '#6b7280',
                            'button_text' => 'Buy Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#667eea',
                            'button_text_color' => '#ffffff',
                            'alignment' => 'left',
                            '_min_height' => '500px',
                            '_padding' => '60px',
                        ],
                        'children' => []
                    ],
                    // Right: Special offer banner
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=600&fit=crop',
                            '_background_overlay' => 'rgba(255,255,255,0.85)',
                            'title' => 'Special Offer',
                            'title_size' => '28px',
                            'title_color' => '#dc2626',
                            'description' => 'Washing Machine - $799',
                            'description_size' => '20px',
                            'description_color' => '#1f2937',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#dc2626',
                            'button_text_color' => '#ffffff',
                            'alignment' => 'center',
                            '_min_height' => '500px',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '40px'],
                'children' => []
            ],
            
            // === BEST PICK OF THE WEEK (4 products) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Best pick of the week',
                    'tag' => 'h2',
                    'size' => '32px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '30px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 20px',
                    'query_type' => 'featured',
                    'products_per_page' => '4',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'column_gap' => '20',
                    'row_gap' => '30',
                    'image_ratio' => '1:1',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '50px'],
                'children' => []
            ],
            
            // === FEATURES BAR (5 features) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '30px 20px',
                    '_background_color' => '#f8f9fa',
                    'columns' => '5',
                    'gap' => '20',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-truck-fast',
                            'icon_size' => '40px',
                            'icon_color' => '#667eea',
                            'title' => 'Fast, Free Shipping',
                            'title_size' => '16px',
                            'description' => 'On orders over $50',
                            'description_size' => '13px',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-calendar',
                            'icon_size' => '40px',
                            'icon_color' => '#10b981',
                            'title' => 'Next Day Delivery',
                            'title_size' => '16px',
                            'description' => 'Available',
                            'description_size' => '13px',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-rotate-left',
                            'icon_size' => '40px',
                            'icon_color' => '#f59e0b',
                            'title' => '60-Day Free Returns',
                            'title_size' => '16px',
                            'description' => 'Easy returns',
                            'description_size' => '13px',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-headset',
                            'icon_size' => '40px',
                            'icon_color' => '#3b82f6',
                            'title' => 'Expert Customer Service',
                            'title_size' => '16px',
                            'description' => '24/7 support',
                            'description_size' => '13px',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-star',
                            'icon_size' => '40px',
                            'icon_color' => '#ec4899',
                            'title' => 'Exclusive Brands',
                            'title_size' => '16px',
                            'description' => 'Top quality',
                            'description_size' => '13px',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === FEATURED PRODUCTS WITH TABS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Featured products',
                    'tag' => 'h2',
                    'size' => '36px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '30px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 20px',
                    'enable_tabs' => 'yes',
                    'tabs_style' => 'minimal',
                    'tabs' => [
                        ['label' => 'Popular products', 'query_type' => 'featured'],
                        ['label' => 'Most-viewed products', 'query_type' => 'recent'],
                        ['label' => 'Top selling', 'query_type' => 'best_selling'],
                    ],
                    'products_per_page' => '8',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'quick_actions_style' => 'on-hover',
                    'actions_position' => 'top-right',
                    'column_gap' => '20',
                    'row_gap' => '30',
                    'image_ratio' => '1:1',
                    'hover_effect' => 'lift',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === SHOP BY CATEGORIES ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Shop by categories',
                    'tag' => 'h2',
                    'size' => '32px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '30px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-categories',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 20px',
                    'columns' => '8',
                    'show_count' => 'yes',
                    'show_image' => 'yes',
                    'image_height' => '150px',
                    'orderby' => 'count',
                    'order' => 'DESC',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === LARGE PROMO BANNERS (3 banners) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 20px',
                    'columns' => '3',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1434494878577-86c23bcb06b9?w=600&h=700&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.2)',
                            'title' => 'WATCH',
                            'title_size' => '20px',
                            'title_color' => '#ffffff',
                            'description' => 'Next level adventure',
                            'description_size' => '28px',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#000000',
                            'alignment' => 'center',
                            '_min_height' => '450px',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&h=700&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'FURNITURE',
                            'title_size' => '20px',
                            'title_color' => '#ffffff',
                            'description' => 'Hearth loft series',
                            'description_size' => '28px',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#000000',
                            'alignment' => 'center',
                            '_min_height' => '450px',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=600&h=700&fit=crop',
                            '_background_overlay' => 'rgba(102, 126, 234, 0.15)',
                            'title' => 'DYSON',
                            'title_size' => '20px',
                            'title_color' => '#1f2937',
                            'description' => 'Supersonic Hair Dryer',
                            'description_size' => '24px',
                            'description_color' => '#1f2937',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#667eea',
                            'button_text_color' => '#ffffff',
                            'alignment' => 'center',
                            '_min_height' => '450px',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === MORE RECOMMENDED PRODUCTS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'More recommended products',
                    'tag' => 'h2',
                    'size' => '36px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                    '_padding' => '0 20px',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '30px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 20px',
                    'query_type' => 'recent',
                    'products_per_page' => '12',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'quick_actions_style' => 'on-hover',
                    'actions_position' => 'top-right',
                    'column_gap' => '20',
                    'row_gap' => '30',
                    'image_ratio' => '1:1',
                    'hover_effect' => 'zoom-in',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
        ];
    }
    
    /**
     * Porto Shop - PROFESSIONAL TEMPLATE WITH PROPER WIDGET USAGE
     * Uses: Slider, WooCommerce Products, Categories, Icon Box, Testimonials, CTA, etc.
     */
    private function template_porto_visual() {
        return [
            // === SECTION 1: HERO SLIDER (Dynamic) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'slider',
                'settings' => [
                    'slides' => [
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=700&fit=crop',
                            'title' => 'Summer Collection 2024',
                            'description' => 'Discover the latest trends',
                            'button_text' => 'Shop Now',
                            'button_link' => '#',
                        ],
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=700&fit=crop',
                            'title' => 'New Arrivals',
                            'description' => 'Fresh styles just landed',
                            'button_text' => 'Explore',
                            'button_link' => '#',
                        ],
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920&h=700&fit=crop',
                            'title' => 'Flash Sale - 50% Off',
                            'description' => 'Limited time offer',
                            'button_text' => 'Get Deal',
                            'button_link' => '#',
                        ],
                    ],
                    'height' => 700,
                    'autoplay' => 'yes',
                    'autoplay_speed' => 5000,
                    'navigation' => 'arrows',
                    'pagination' => 'dots',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 80,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Summer Collection 2024',
                    'html_tag' => 'h1',
                    'font_size' => 64,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'color' => '#1a1a1a',
                    'margin' => ['bottom' => 20],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'text',
                'settings' => [
                    'text' => '<p style="text-align:center;font-size:22px;color:#666;line-height:1.6;">Discover the latest trends with up to 50% off on selected items</p>',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 40,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'button',
                'settings' => [
                    'text' => 'Shop The Collection',
                    'background_color' => '#92003b',
                    'text_color' => '#ffffff',
                    'align' => 'center',
                    'padding' => ['top' => 18, 'right' => 45, 'bottom' => 18, 'left' => 45],
                    'font_size' => 18,
                    'font_weight' => 600,
                    'border_radius' => 50,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 100,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'divider',
                'settings' => [
                    'style' => 'solid',
                    'color' => '#e5e7eb',
                    'width' => 80,
                    'margin' => ['top' => 0, 'bottom' => 0],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 80,
                ]
            ],
            
            // === SECTION 2: WOOCOMMERCE CATEGORIES (Dynamic) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Shop by Category',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'margin' => ['bottom' => 50],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-categories',
                'settings' => [
                    'columns' => 4,
                    'number' => 8,
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => 'no',
                    'show_image' => 'yes',
                    'show_count' => 'yes',
                    'image_height' => 300,
                    'border_radius' => 8,
                    'spacing' => 20,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 60,
                ]
            ],
            
            // === SECTION 3: PROMO BANNER (Call to Action) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Limited Time Offer',
                    'description' => 'Get up to 60% off on selected items. Don\'t miss out!',
                    'button_text' => 'Shop Sale',
                    'button_link' => '#',
                    'background_image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920&h=500&fit=crop',
                    'background_overlay' => 'yes',
                    'overlay_color' => 'rgba(0,0,0,0.4)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'min_height' => 400,
                    'text_align' => 'center',
                    'border_radius' => 12,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 100,
                ]
            ],
            
            // === SECTION 4: FEATURES ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'icon-box',
                'settings' => [
                    'icon' => 'fa fa-shipping-fast',
                    'title' => 'FREE SHIPPING',
                    'description' => 'On orders over $50',
                    'icon_size' => 48,
                    'icon_color' => '#92003b',
                    'text_align' => 'center',
                    'width' => '25%',
                    'margin' => ['right' => 15, 'bottom' => 40],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'icon-box',
                'settings' => [
                    'icon' => 'fa fa-undo',
                    'title' => 'EASY RETURNS',
                    'description' => '30-day guarantee',
                    'icon_size' => 48,
                    'icon_color' => '#92003b',
                    'text_align' => 'center',
                    'width' => '25%',
                    'margin' => ['right' => 15, 'bottom' => 40],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'icon-box',
                'settings' => [
                    'icon' => 'fa fa-lock',
                    'title' => 'SECURE PAYMENT',
                    'description' => '100% secure checkout',
                    'icon_size' => 48,
                    'icon_color' => '#92003b',
                    'text_align' => 'center',
                    'width' => '25%',
                    'margin' => ['right' => 15, 'bottom' => 40],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'icon-box',
                'settings' => [
                    'icon' => 'fa fa-headset',
                    'title' => '24/7 SUPPORT',
                    'description' => 'Always here to help',
                    'icon_size' => 48,
                    'icon_color' => '#92003b',
                    'text_align' => 'center',
                    'width' => '25%',
                    'margin' => ['bottom' => 40],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 60,
                ]
            ],
            
            // === SECTION 5: FEATURED PRODUCTS (WooCommerce - Dynamic) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Featured Products',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'margin' => ['bottom' => 60],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    'query_type' => 'featured',
                    'columns' => 4,
                    'rows' => 2,
                    'orderby' => 'popularity',
                    'order' => 'DESC',
                    'show_rating' => 'yes',
                    'show_price' => 'yes',
                    'show_add_to_cart' => 'yes',
                    'image_height' => 300,
                    'border_radius' => 8,
                    'spacing' => 20,
                    'hover_effect' => 'zoom',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 100,
                ]
            ],
            
            // === SECTION 6: BEST SELLERS (WooCommerce - Dynamic) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Best Sellers',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'margin' => ['bottom' => 60],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    'query_type' => 'best_selling',
                    'columns' => 5,
                    'rows' => 1,
                    'show_rating' => 'yes',
                    'show_price' => 'yes',
                    'show_add_to_cart' => 'yes',
                    'image_height' => 280,
                    'border_radius' => 8,
                    'spacing' => 15,
                    'hover_effect' => 'zoom',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 80,
                ]
            ],
            
            // === SECTION 6B: FLASH SALE BANNER WITH COUNTDOWN ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Flash Sale - Up to 70% OFF',
                    'description' => 'Limited time offer on all categories',
                    'button_text' => 'Shop Now',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 100,
                ]
            ],
            
            // === SECTION 7: NEW ARRIVALS (WooCommerce - Dynamic) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'New Arrivals',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'margin' => ['bottom' => 60],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    'query_type' => 'recent',
                    'columns' => 3,
                    'rows' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'show_rating' => 'yes',
                    'show_price' => 'yes',
                    'show_add_to_cart' => 'yes',
                    'image_height' => 400,
                    'border_radius' => 8,
                    'spacing' => 20,
                    'hover_effect' => 'slide-up',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 100,
                ]
            ],
            
            // === SECTION 8: LIFESTYLE BANNER (Image with overlay) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=600&fit=crop'],
                    'height' => 600,
                    'object_fit' => 'cover',
                    'border_radius' => 12,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 120,
                ]
            ],
            
            // === SECTION 9: TESTIMONIALS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'What Our Customers Say',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'margin' => ['bottom' => 60],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'testimonial',
                'settings' => [
                    'content' => 'Absolutely love my purchase! The quality is outstanding and shipping was super fast. Will definitely shop here again!',
                    'author_name' => 'Sarah Johnson',
                    'author_title' => 'Verified Customer',
                    'rating' => 5,
                    'width' => '33.33%',
                    'margin' => ['right' => 10, 'bottom' => 30],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'testimonial',
                'settings' => [
                    'content' => 'Best online shopping experience ever! Great products, amazing customer service, and fast delivery.',
                    'author_name' => 'Michael Chen',
                    'author_title' => 'Happy Shopper',
                    'rating' => 5,
                    'width' => '33.33%',
                    'margin' => ['right' => 10, 'bottom' => 30],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'testimonial',
                'settings' => [
                    'content' => 'Incredible selection and prices! The quality exceeded my expectations. Highly recommend!',
                    'author_name' => 'Emily Davis',
                    'author_title' => 'Loyal Customer',
                    'rating' => 5,
                    'width' => '33.33%',
                    'margin' => ['bottom' => 30],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => [
                    'height' => 100,
                ]
            ],
            
            // === SECTION 10: FINAL CTA ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Join Our Newsletter',
                    'description' => 'Subscribe and get 15% off your first order',
                    'button_text' => 'Subscribe Now',
                    'background_color' => '#1a1a1a',
                    'title_color' => '#ffffff',
                    'description_color' => '#cccccc',
                    'padding' => ['top' => 100, 'bottom' => 100],
                ]
            ],
        ];
    }
    
    private function template_fashion_visual() {
        return [
            // Hero Slider
            [
                'id' => $this->generate_id(),
                'widgetType' => 'slider',
                'settings' => [
                    'slides' => [
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=700&fit=crop',
                            'title' => 'New Season Fashion',
                            'description' => 'Trendy styles for 2024',
                            'button_text' => 'Shop Women',
                            'button_link' => '#',
                        ],
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?w=1920&h=700&fit=crop',
                            'title' => 'Men\'s Collection',
                            'description' => 'Bold and sophisticated',
                            'button_text' => 'Shop Men',
                            'button_link' => '#',
                        ],
                    ],
                    'height' => 700,
                    'autoplay' => 'yes',
                    'autoplay_speed' => 6000,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['height' => 80]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Trending Now',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'font_weight' => 700,
                    'margin' => ['bottom' => 60],
                ]
            ],
            
            // WooCommerce Products
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    'query_type' => 'featured',
                    'columns' => 3,
                    'rows' => 2,
                    'show_rating' => 'yes',
                    'show_price' => 'yes',
                    'show_add_to_cart' => 'yes',
                    'image_height' => 450,
                    'hover_effect' => 'zoom',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['height' => 100]
            ],
            
            // CTA Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Exclusive Fashion Collection',
                    'description' => 'Shop the latest trends and express your unique style',
                    'button_text' => 'Explore Collection',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 100, 'bottom' => 100],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['height' => 80]
            ],
            
            // Categories
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Shop by Style',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'margin' => ['bottom' => 50],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-categories',
                'settings' => [
                    'columns' => 3,
                    'number' => 6,
                    'show_image' => 'yes',
                    'show_count' => 'yes',
                    'image_height' => 350,
                ]
            ],
        ];
    }
    
    private function template_electronics_visual() {
        return [
            // Hero
            [
                'id' => $this->generate_id(),
                'widgetType' => 'slider',
                'settings' => [
                    'slides' => [
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1920&h=650&fit=crop',
                            'title' => 'Latest Tech Gadgets',
                            'description' => 'Innovation at your fingertips',
                            'button_text' => 'Shop Now',
                            'button_link' => '#',
                        ],
                    ],
                    'height' => 650,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['height' => 80]
            ],
            
            // Featured Categories
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Browse by Category',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'margin' => ['bottom' => 50],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-categories',
                'settings' => [
                    'columns' => 4,
                    'number' => 4,
                    'show_image' => 'yes',
                    'show_count' => 'yes',
                    'image_height' => 250,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['height' => 80]
            ],
            
            // Bestsellers
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Top Selling Products',
                    'html_tag' => 'h2',
                    'font_size' => 48,
                    'text_align' => 'center',
                    'margin' => ['bottom' => 60],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    'query_type' => 'best_selling',
                    'columns' => 4,
                    'rows' => 2,
                    'show_rating' => 'yes',
                    'show_price' => 'yes',
                    'show_add_to_cart' => 'yes',
                    'image_height' => 320,
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['height' => 100]
            ],
            
            // Sale CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Mega Tech Sale - Up to 40% Off',
                    'description' => 'Premium electronics at unbeatable prices. Limited stock available!',
                    'button_text' => 'Shop Electronics',
                    'background_color' => '#0088cc',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 100, 'bottom' => 100],
                ]
            ],
        ];
    }
    
    /**
     * Modern Shop - Inspired from user's design reference
     * Features: Hero slider, tabbed products, banners, features
     */
    private function template_modern_shop() {
        return [
            // === HERO SLIDER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'slider',
                'settings' => [
                    '_width' => '100%',
                    '_height' => '600px',
                    'slider_style' => 'modern',
                    'slider_height' => '600px',
                    'autoplay' => 'yes',
                    'autoplay_speed' => '5000',
                    'show_arrows' => 'yes',
                    'show_dots' => 'yes',
                    'dot_position' => 'bottom-center',
                    'content_animation' => 'fade-up',
                    'slides' => [
                        [
                            'background_type' => 'image',
                            'background_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=600&fit=crop',
                            'overlay_type' => 'gradient',
                            'overlay_gradient_start' => 'rgba(0,0,0,0.3)',
                            'overlay_gradient_end' => 'rgba(0,0,0,0.1)',
                            'title' => 'New Season Collection',
                            'title_size' => '56px',
                            'title_color' => '#ffffff',
                            'description' => 'Discover our latest arrivals with up to 50% off',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#92003b',
                            'content_position' => 'center-left',
                        ],
                        [
                            'background_type' => 'image',
                            'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=600&fit=crop',
                            'overlay_type' => 'gradient',
                            'overlay_gradient_start' => 'rgba(146,0,59,0.4)',
                            'overlay_gradient_end' => 'rgba(146,0,59,0.1)',
                            'title' => 'Exclusive Deals',
                            'title_size' => '56px',
                            'title_color' => '#ffffff',
                            'description' => 'Limited time offers on premium products',
                            'description_color' => '#ffffff',
                            'button_text' => 'View Deals',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#92003b',
                            'content_position' => 'center-center',
                        ],
                        [
                            'background_type' => 'image',
                            'background_image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1920&h=600&fit=crop',
                            'overlay_type' => 'gradient',
                            'overlay_gradient_start' => 'rgba(0,0,0,0.4)',
                            'overlay_gradient_end' => 'rgba(0,0,0,0.2)',
                            'title' => 'Free Shipping',
                            'title_size' => '56px',
                            'title_color' => '#ffffff',
                            'description' => 'On orders over $50 - Fast delivery worldwide',
                            'description_color' => '#ffffff',
                            'button_text' => 'Start Shopping',
                            'button_link' => '/shop',
                            'button_bg_color' => '#10b981',
                            'content_position' => 'center-right',
                        ],
                    ],
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === FEATURED PRODUCTS WITH TABS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'enable_tabs' => 'yes',
                    'tabs_style' => 'modern',
                    'tabs' => [
                        ['label' => 'Featured Products', 'query_type' => 'featured'],
                        ['label' => 'New Arrivals', 'query_type' => 'recent'],
                        ['label' => 'On Sale', 'query_type' => 'sale'],
                        ['label' => 'Best Sellers', 'query_type' => 'best_selling'],
                    ],
                    'products_per_page' => '8',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'show_compare' => 'yes',
                    'quick_actions_style' => 'on-hover',
                    'actions_position' => 'top-right',
                    'column_gap' => '20',
                    'row_gap' => '30',
                    'product_border_radius' => '8',
                    'hover_effect' => 'zoom-in',
                    'card_shadow' => 'small',
                    'card_hover_shadow' => 'medium',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === PROMO BANNERS SECTION (3 BANNERS) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'columns' => '3',
                    'gap' => '30',
                    'min_height' => '300px',
                ],
                'children' => [
                    // Banner 1
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=400&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'Smart Watches',
                            'title_size' => '28px',
                            'title_color' => '#ffffff',
                            'description' => 'Starting at $199',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Watches',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#000000',
                            'alignment' => 'center',
                            '_min_height' => '300px',
                        ],
                        'children' => []
                    ],
                    // Banner 2
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&h=400&fit=crop',
                            '_background_overlay' => 'rgba(146,0,59,0.4)',
                            'title' => 'Sunglasses',
                            'title_size' => '28px',
                            'title_color' => '#ffffff',
                            'description' => 'Summer Collection',
                            'description_color' => '#ffffff',
                            'button_text' => 'Explore',
                            'button_link' => '/shop',
                            'button_bg_color' => '#ffffff',
                            'button_text_color' => '#92003b',
                            'alignment' => 'center',
                            '_min_height' => '300px',
                        ],
                        'children' => []
                    ],
                    // Banner 3
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=600&h=400&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.3)',
                            'title' => 'Sneakers',
                            'title_size' => '28px',
                            'title_color' => '#ffffff',
                            'description' => 'New Arrivals',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Now',
                            'button_link' => '/shop',
                            'button_bg_color' => '#10b981',
                            'button_text_color' => '#ffffff',
                            'alignment' => 'center',
                            '_min_height' => '300px',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === FEATURES BAR (4 ICON BOXES) ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '40px',
                    '_background_color' => '#f8f9fa',
                    'columns' => '4',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-truck',
                            'icon_size' => '48px',
                            'icon_color' => '#92003b',
                            'title' => 'Free Shipping',
                            'title_size' => '18px',
                            'description' => 'On orders over $50',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-shield',
                            'icon_size' => '48px',
                            'icon_color' => '#10b981',
                            'title' => 'Secure Payment',
                            'title_size' => '18px',
                            'description' => '100% protected transactions',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-rotate-left',
                            'icon_size' => '48px',
                            'icon_color' => '#3b82f6',
                            'title' => 'Easy Returns',
                            'title_size' => '18px',
                            'description' => '30-day return policy',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa-headset',
                            'icon_size' => '48px',
                            'icon_color' => '#f59e0b',
                            'title' => '24/7 Support',
                            'title_size' => '18px',
                            'description' => 'Dedicated support team',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === PRODUCT CATEGORIES ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-categories',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'columns' => '6',
                    'show_count' => 'yes',
                    'show_image' => 'yes',
                    'image_height' => '200px',
                    'orderby' => 'count',
                    'order' => 'DESC',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === DUAL PROMO BANNERS ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'columns' => '2',
                    'gap' => '30',
                    'min_height' => '400px',
                ],
                'children' => [
                    // Large Banner Left
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'call-to-action',
                        'settings' => [
                            '_background_type' => 'image',
                            '_background_image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=800&h=500&fit=crop',
                            '_background_overlay' => 'rgba(0,0,0,0.25)',
                            'title' => 'Summer Sale',
                            'title_size' => '42px',
                            'title_color' => '#ffffff',
                            'description' => 'Up to 60% OFF on selected items',
                            'description_size' => '18px',
                            'description_color' => '#ffffff',
                            'button_text' => 'Shop Sale',
                            'button_link' => '/shop',
                            'button_bg_color' => '#fbbf24',
                            'button_text_color' => '#000000',
                            'alignment' => 'left',
                            '_min_height' => '400px',
                            '_padding' => '60px',
                        ],
                        'children' => []
                    ],
                    // Stacked Banners Right
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'container',
                        'settings' => [
                            'columns' => '1',
                            'gap' => '30',
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'call-to-action',
                                'settings' => [
                                    '_background_type' => 'image',
                                    '_background_image' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&h=185&fit=crop',
                                    '_background_overlay' => 'rgba(146,0,59,0.3)',
                                    'title' => 'Accessories',
                                    'title_size' => '24px',
                                    'title_color' => '#ffffff',
                                    'description' => 'Complete your look',
                                    'description_color' => '#ffffff',
                                    'button_text' => 'Browse',
                                    'button_link' => '/shop',
                                    'button_bg_color' => '#ffffff',
                                    'button_text_color' => '#92003b',
                                    'alignment' => 'center',
                                    '_min_height' => '185px',
                                ],
                                'children' => []
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'call-to-action',
                                'settings' => [
                                    '_background_type' => 'image',
                                    '_background_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=185&fit=crop',
                                    '_background_overlay' => 'rgba(0,0,0,0.3)',
                                    'title' => 'Premium Collection',
                                    'title_size' => '24px',
                                    'title_color' => '#ffffff',
                                    'description' => 'Luxury items for you',
                                    'description_color' => '#ffffff',
                                    'button_text' => 'Discover',
                                    'button_link' => '/shop',
                                    'button_bg_color' => '#10b981',
                                    'button_text_color' => '#ffffff',
                                    'alignment' => 'center',
                                    '_min_height' => '185px',
                                ],
                                'children' => []
                            ],
                        ],
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === BEST SELLERS SECTION ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'text' => 'Best Selling Products',
                    'tag' => 'h2',
                    'size' => '36px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '30px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'woo-products',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'enable_tabs' => 'no',
                    'query_type' => 'best_selling',
                    'products_per_page' => '8',
                    'columns' => '4',
                    'show_image' => 'yes',
                    'show_title' => 'yes',
                    'show_price' => 'yes',
                    'show_cart_button' => 'yes',
                    'show_rating' => 'yes',
                    'show_badge' => 'yes',
                    'show_quick_view' => 'yes',
                    'show_wishlist' => 'yes',
                    'show_compare' => 'yes',
                    'quick_actions_style' => 'on-hover',
                    'actions_position' => 'top-right',
                    'column_gap' => '20',
                    'row_gap' => '30',
                    'hover_effect' => 'lift',
                ],
                'children' => []
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === TESTIMONIALS SECTION ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'text' => 'What Our Customers Say',
                    'tag' => 'h2',
                    'size' => '36px',
                    'color' => '#1f2937',
                    'alignment' => 'center',
                    'weight' => '700',
                ],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '30px'],
                'children' => []
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '0 40px 0 40px',
                    'columns' => '3',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'testimonial',
                        'settings' => [
                            'author_name' => 'Sarah Johnson',
                            'author_title' => 'Verified Customer',
                            'author_image' => 'https://i.pravatar.cc/150?img=1',
                            'testimonial' => 'Amazing products and fast shipping! The quality exceeded my expectations. Highly recommend this store.',
                            'rating' => '5',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'testimonial',
                        'settings' => [
                            'author_name' => 'Mike Chen',
                            'author_title' => 'Regular Customer',
                            'author_image' => 'https://i.pravatar.cc/150?img=12',
                            'testimonial' => 'Great customer service and excellent product selection. I\'ve been shopping here for years and never disappointed.',
                            'rating' => '5',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'testimonial',
                        'settings' => [
                            'author_name' => 'Emma Davis',
                            'author_title' => 'Happy Shopper',
                            'author_image' => 'https://i.pravatar.cc/150?img=5',
                            'testimonial' => 'Love the variety and quality! The quick view feature is so convenient. Makes shopping a breeze.',
                            'rating' => '5',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                ],
            ],
            
            // === SPACER ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'spacer',
                'settings' => ['_height' => '60px'],
                'children' => []
            ],
            
            // === NEWSLETTER CTA ===
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '80px 40px 80px 40px',
                    '_background_type' => 'gradient',
                    '_background_gradient_type' => 'linear',
                    '_background_gradient_angle' => '135',
                    '_background_gradient_color_1' => '#667eea',
                    '_background_gradient_color_2' => '#764ba2',
                    'title' => 'Subscribe to Our Newsletter',
                    'title_size' => '42px',
                    'title_color' => '#ffffff',
                    'description' => 'Get exclusive deals, new arrivals, and insider-only discounts',
                    'description_size' => '18px',
                    'description_color' => '#ffffff',
                    'button_text' => 'Subscribe Now',
                    'button_link' => '#',
                    'button_bg_color' => '#ffffff',
                    'button_text_color' => '#667eea',
                    'button_size' => 'large',
                    'alignment' => 'center',
                ],
                'children' => []
            ],
        ];
    }
    
    public function get_user_templates() {
        return [];
    }
    
    public function save_template() {
        wp_send_json_success(['message' => 'Template saved']);
    }
    
    public function delete_template() {
        wp_send_json_success(['message' => 'Template deleted']);
    }
    
    public function import_template() {
        wp_send_json_success(['message' => 'Template imported']);
    }
}

