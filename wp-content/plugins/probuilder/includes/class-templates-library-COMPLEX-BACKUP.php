<?php
/**
 * ProBuilder Templates Library - Professional Edition
 * 
 * Modern templates inspired by Porto, WoodMart, Flatsome, and Avada
 * Completely rewritten for professional e-commerce designs
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
    
    /**
     * AJAX handler to get single template data
     */
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
    
    /**
     * Get all templates (pre-built + user templates)
     */
    public function get_templates() {
        $prebuilt = $this->get_prebuilt_templates_meta();
        $user = $this->get_user_templates();
        
        $templates = [
            'prebuilt' => $prebuilt,
            'user' => $user
        ];
        
        wp_send_json_success($templates);
    }
    
    /**
     * Get template metadata only (without full data - for faster loading)
     */
    public function get_prebuilt_templates_meta() {
        $placeholder = $this->create_svg_placeholder('ProBuilder', '#92003b');
        
        return [
            // ========== FULL PAGE TEMPLATES ==========
            ['id' => 'porto-shop', 'name' => '🛒 Modern Shop (Porto Style)', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'woodmart-fashion', 'name' => '👗 Fashion Store (WoodMart)', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'flatsome-electronics', 'name' => '💻 Electronics Store (Flatsome)', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'avada-product', 'name' => '📦 Product Page (Avada)', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'modern-homepage', 'name' => '🏪 Modern E-Commerce Home', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'landing-saas', 'name' => '🚀 SaaS Landing Page', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            
            // ========== HERO SECTIONS ==========
            ['id' => 'hero-modern', 'name' => 'Hero - Modern with Image', 'category' => 'hero', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'hero-split', 'name' => 'Hero - Split Screen', 'category' => 'hero', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'hero-video', 'name' => 'Hero - Video Background', 'category' => 'hero', 'type' => 'section', 'thumbnail' => $placeholder],
            
            // ========== PRODUCT SECTIONS ==========
            ['id' => 'products-grid', 'name' => 'Products - Grid Layout', 'category' => 'products', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'products-carousel', 'name' => 'Products - Carousel Slider', 'category' => 'products', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'products-featured', 'name' => 'Featured Products Banner', 'category' => 'products', 'type' => 'section', 'thumbnail' => $placeholder],
            
            // ========== FEATURE SECTIONS ==========
            ['id' => 'features-icons', 'name' => 'Features - Icon Grid', 'category' => 'features', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'features-cards', 'name' => 'Features - Modern Cards', 'category' => 'features', 'type' => 'section', 'thumbnail' => $placeholder],
            
            // ========== OTHER SECTIONS ==========
            ['id' => 'testimonials-modern', 'name' => 'Testimonials - Carousel', 'category' => 'testimonials', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'cta-gradient', 'name' => 'CTA - Gradient Banner', 'category' => 'cta', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'team-grid', 'name' => 'Team - Grid Layout', 'category' => 'team', 'type' => 'section', 'thumbnail' => $placeholder],
            ['id' => 'pricing-modern', 'name' => 'Pricing - 3 Plans', 'category' => 'pricing', 'type' => 'section', 'thumbnail' => $placeholder],
        ];
    }
    
    /**
     * Get single template data by ID
     */
    public function get_template_data($template_id) {
        $all_templates = $this->get_prebuilt_templates();
        
        foreach ($all_templates as $template) {
            if ($template['id'] === $template_id) {
                return $template['data'];
            }
        }
        
        return null;
    }
    
    /**
     * Get pre-built templates with full data
     */
    public function get_prebuilt_templates() {
        return [
            // FULL PAGE TEMPLATES
            ['id' => 'porto-shop', 'name' => '🛒 Modern Shop (Porto Style)', 'category' => 'pages', 'data' => $this->template_porto_shop()],
            ['id' => 'woodmart-fashion', 'name' => '👗 Fashion Store (WoodMart)', 'category' => 'pages', 'data' => $this->template_woodmart_fashion()],
            ['id' => 'flatsome-electronics', 'name' => '💻 Electronics Store (Flatsome)', 'category' => 'pages', 'data' => $this->template_flatsome_electronics()],
            ['id' => 'avada-product', 'name' => '📦 Product Page (Avada)', 'category' => 'pages', 'data' => $this->template_avada_product()],
            ['id' => 'modern-homepage', 'name' => '🏪 Modern E-Commerce Home', 'category' => 'pages', 'data' => $this->template_modern_homepage()],
            ['id' => 'landing-saas', 'name' => '🚀 SaaS Landing Page', 'category' => 'pages', 'data' => $this->template_saas_landing()],
            
            // HERO SECTIONS
            ['id' => 'hero-modern', 'name' => 'Hero - Modern', 'category' => 'hero', 'data' => $this->section_hero_modern()],
            ['id' => 'hero-split', 'name' => 'Hero - Split Screen', 'category' => 'hero', 'data' => $this->section_hero_split()],
            ['id' => 'hero-video', 'name' => 'Hero - Video Background', 'category' => 'hero', 'data' => $this->section_hero_video()],
            
            // PRODUCT SECTIONS
            ['id' => 'products-grid', 'name' => 'Products - Grid', 'category' => 'products', 'data' => $this->section_products_grid()],
            ['id' => 'products-carousel', 'name' => 'Products - Carousel', 'category' => 'products', 'data' => $this->section_products_carousel()],
            ['id' => 'products-featured', 'name' => 'Featured Products', 'category' => 'products', 'data' => $this->section_products_featured()],
            
            // FEATURE SECTIONS
            ['id' => 'features-icons', 'name' => 'Features - Icons', 'category' => 'features', 'data' => $this->section_features_icons()],
            ['id' => 'features-cards', 'name' => 'Features - Cards', 'category' => 'features', 'data' => $this->section_features_cards()],
            
            // OTHER SECTIONS
            ['id' => 'testimonials-modern', 'name' => 'Testimonials', 'category' => 'testimonials', 'data' => $this->section_testimonials()],
            ['id' => 'cta-gradient', 'name' => 'CTA Banner', 'category' => 'cta', 'data' => $this->section_cta()],
            ['id' => 'team-grid', 'name' => 'Team Grid', 'category' => 'team', 'data' => $this->section_team_grid()],
            ['id' => 'pricing-modern', 'name' => 'Pricing Table', 'category' => 'pricing', 'data' => $this->section_pricing()],
        ];
    }
    
    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    
    private function create_svg_placeholder($text, $color = '#92003b') {
        $svg = '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="' . $color . '" font-size="20" font-weight="bold">' . $text . '</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    private function generate_id() {
        return 'el_' . uniqid();
    }
    
    // ============================================
    // FULL PAGE TEMPLATES
    // ============================================
    
    /**
     * Porto Shop 2 Style - Based on portotheme.com/wordpress/porto/shop2/
     */
    private function template_porto_shop() {
        return [
            // 1. Top Promo Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_color' => '#0088cc',
                    'padding' => ['top' => 15, 'bottom' => 15],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => '<p style="text-align:center;margin:0;color:#fff;font-size:14px;">Get Up to <strong>40% OFF</strong> New-Season Styles • FREE RETURNS • STANDARD SHIPPING ORDERS $99+</p>',
                        ]
                    ],
                ]
            ],
            
            // 2. Hero Slider - Large Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'slider',
                'settings' => [
                    'height' => 600,
                    'autoplay' => 'yes',
                    'slides' => [
                        [
                            'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=800&fit=crop',
                            'title' => 'Find the Boundaries. Push Through!',
                            'button_text' => 'Shop Now',
                        ]
                    ],
                ]
            ],
            
            // 3. Three Promo Banners (Summer Sale, Great Deals, New Arrivals)
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 60, 'bottom' => 60],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 25,
                        ],
                        'children' => [
                            // Summer Sale Banner
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&h=400&fit=crop',
                                    'background_overlay' => 'rgba(146, 0, 59, 0.6)',
                                    'padding' => ['top' => 60, 'bottom' => 60],
                                    'border_radius' => 8,
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                                            'text' => 'Summer Sale',
                                            'html_tag' => 'h3',
                            'color' => '#ffffff',
                                            'font_size' => 24,
                            'text_align' => 'center',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:48px;font-weight:700;margin:10px 0;">30% OFF</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:16px;">Starting At <strong style="font-size:20px;">$19.99</strong></p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'Get Yours!',
                                            'background_color' => '#ffffff',
                                            'text_color' => '#92003b',
                                            'align' => 'center',
                                            'padding' => ['top' => 12, 'right' => 30, 'bottom' => 12, 'left' => 30],
                                        ]
                                    ],
                                ]
                            ],
                            // Great Deals Banner
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=600&h=400&fit=crop',
                                    'background_overlay' => 'rgba(0, 136, 204, 0.6)',
                                    'padding' => ['top' => 60, 'bottom' => 60],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'heading',
                                        'settings' => [
                                            'text' => 'GREAT DEALS',
                                            'html_tag' => 'h3',
                            'color' => '#ffffff',
                                            'font_size' => 24,
                            'text_align' => 'center',
                        ]
                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:16px;">Starting At <strong style="font-size:20px;">$29.99</strong></p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:14px;font-weight:700;">OVER 200 PRODUCTS</p>',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                                            'text' => 'Get Yours!',
                            'background_color' => '#ffffff',
                                            'text_color' => '#0088cc',
                            'align' => 'center',
                                            'padding' => ['top' => 12, 'right' => 30, 'bottom' => 12, 'left' => 30],
                        ]
                    ],
                ]
            ],
                            // New Arrivals Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&h=400&fit=crop',
                                    'background_overlay' => 'rgba(106, 13, 173, 0.6)',
                                    'padding' => ['top' => 60, 'bottom' => 60],
                                    'border_radius' => 8,
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                                            'text' => 'NEW ARRIVALS',
                                            'html_tag' => 'h3',
                                            'color' => '#ffffff',
                                            'font_size' => 24,
                            'text_align' => 'center',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:16px;">Starting At <strong style="font-size:20px;">$29.99</strong></p>',
                        ]
                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:14px;font-weight:700;">UP TO 70% OFF</p>',
                ]
            ],
            [
                'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                'settings' => [
                                            'text' => 'Get Yours!',
                                            'background_color' => '#ffffff',
                                            'text_color' => '#6a0dad',
                                            'align' => 'center',
                                            'padding' => ['top' => 12, 'right' => 30, 'bottom' => 12, 'left' => 30],
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 4. Features Bar (Free Shipping, Money Back, 24/7 Support)
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 40, 'bottom' => 40],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-shipping-fast',
                                    'title' => 'FREE SHIPPING & RETURN',
                                    'description' => 'Free shipping on all orders over $99',
                                    'icon_size' => 48,
                                    'icon_color' => '#0088cc',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-money-bill-wave',
                                    'title' => 'MONEY BACK GUARANTEE',
                                    'description' => '100% money back guarantee',
                                    'icon_size' => 48,
                                    'icon_color' => '#0088cc',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-headset',
                                    'title' => 'ONLINE SUPPORT 24/7',
                                    'description' => 'Always dedicated team',
                                    'icon_size' => 48,
                                    'icon_color' => '#0088cc',
                                    'text_align' => 'center',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 5. Porto Watches Banner + Electronic Deals (2 Column)
                            [
                                'id' => $this->generate_id(),
                'widgetType' => 'container',
                                'settings' => [
                    'padding' => ['top' => 40, 'bottom' => 40],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 2,
                            'gap' => 30,
                        ],
                        'children' => [
                            // Porto Watches
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=400&fit=crop',
                                    'background_overlay' => 'rgba(0, 0, 0, 0.4)',
                                    'padding' => ['top' => 80, 'bottom' => 80],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:32px;font-weight:700;margin:0;">Porto Watches</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:64px;font-weight:700;line-height:1;margin:10px 0;">20% <span style="font-size:48px;">30%</span> Off</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'Shop Now',
                                            'background_color' => '#0088cc',
                                            'text_color' => '#ffffff',
                                            'align' => 'center',
                                            'padding' => ['top' => 15, 'right' => 35, 'bottom' => 15, 'left' => 35],
                                        ]
                                    ],
                                ]
                            ],
                            // Electronic Deals
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_color' => '#1a1a1a',
                                    'padding' => ['top' => 80, 'bottom' => 80],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#0088cc;font-size:18px;font-weight:700;margin:0;">ELECTRONIC DEALS</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:20px;margin:15px 0;">Exclusive COUPON</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:36px;font-weight:700;margin:10px 0;">UP TO <span style="font-size:48px;color:#0088cc;">$100</span> OFF</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'Get Yours!',
                                            'background_color' => '#0088cc',
                                            'text_color' => '#ffffff',
                                            'align' => 'center',
                                            'padding' => ['top' => 15, 'right' => 35, 'bottom' => 15, 'left' => 35],
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 6. Flash Sale + Exclusive Collection (2 More Banners)
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 40, 'bottom' => 40],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 2,
                            'gap' => 30,
                        ],
                        'children' => [
                            // Flash Sale Sunglasses
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800&h=400&fit=crop',
                                    'background_overlay' => 'rgba(255, 200, 0, 0.3)',
                                    'padding' => ['top' => 80, 'bottom' => 80],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#000;font-size:18px;font-weight:700;">FLASH SALE</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#000;font-size:28px;font-weight:700;margin:10px 0;">TOP BRANDS SUMMER SUNGLASSES</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#000;font-size:18px;">STARTING AT <strong style="font-size:24px;">$19.99</strong></p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'View Sale',
                                            'background_color' => '#000000',
                                            'text_color' => '#ffffff',
                                            'align' => 'center',
                                            'padding' => ['top' => 15, 'right' => 35, 'bottom' => 15, 'left' => 35],
                                        ]
                                    ],
                                ]
                            ],
                            // Amazing Collection
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                    'padding' => ['top' => 80, 'bottom' => 80],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:24px;font-weight:700;">AMAZING</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:48px;font-weight:700;margin:10px 0;">Collection</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:16px;">Check our discounts</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'SHOP NOW!',
                                            'background_color' => '#ffffff',
                                            'text_color' => '#764ba2',
                                            'align' => 'center',
                                            'padding' => ['top' => 15, 'right' => 35, 'bottom' => 15, 'left' => 35],
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 7. Trending Fashion + Side Banners
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 60, 'bottom' => 60],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 25,
                        ],
                        'children' => [
                            // Left Banner
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=600&fit=crop',
                                    'background_overlay' => 'rgba(106, 13, 173, 0.5)',
                                    'padding' => ['top' => 100, 'bottom' => 100],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:32px;font-weight:700;">TRENDING</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:28px;font-weight:700;margin:10px 0;">Fashion Sales</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:18px;">STARTING AT $99</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'BUY NOW!',
                                            'background_color' => '#ffffff',
                                            'text_color' => '#6a0dad',
                                            'align' => 'center',
                                            'padding' => ['top' => 12, 'right' => 30, 'bottom' => 12, 'left' => 30],
                                        ]
                                    ],
                                ]
                            ],
                            // Middle Banner - Exclusive Shoes
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [
                                    'background_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=600&fit=crop',
                                    'background_overlay' => 'rgba(146, 0, 59, 0.5)',
                                    'padding' => ['top' => 100, 'bottom' => 100],
                                    'border_radius' => 8,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:36px;font-weight:700;">Exclusive Shoes</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:56px;font-weight:700;line-height:1;margin:15px 0;">50% OFF</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:18px;">STARTING AT $99</p>',
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'text',
                                        'settings' => [
                                            'text' => '<p style="text-align:center;color:#fff;font-size:14px;margin-bottom:20px;">Check our discounts</p>',
                                        ]
                                    ],
                                ]
                            ],
                            // Right Side - More Banners
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'container',
                                'settings' => [],
                                'children' => [
                                    // More Than 20 Brands
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'container',
                                        'settings' => [
                                            'background_color' => '#000000',
                                            'padding' => ['top' => 40, 'bottom' => 40],
                                            'border_radius' => 8,
                                            'margin' => ['bottom' => 20],
                                        ],
                                        'children' => [
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'text',
                                                'settings' => [
                                                    'text' => '<p style="text-align:center;color:#fff;font-size:28px;font-weight:700;">More Than<br>20 Brands</p>',
                                                ]
                                            ],
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'text',
                                                'settings' => [
                                                    'text' => '<p style="text-align:center;color:#0088cc;font-size:36px;font-weight:700;">UP TO $100 OFF</p>',
                                                ]
                                            ],
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'button',
                                                'settings' => [
                                                    'text' => 'CHECK THIS SALE!',
                                                    'background_color' => '#0088cc',
                                                    'text_color' => '#ffffff',
                                                    'align' => 'center',
                                                    'padding' => ['top' => 12, 'right' => 25, 'bottom' => 12, 'left' => 25],
                                                    'font_size' => 13,
                                                ]
                                            ],
                                        ]
                                    ],
                                    // Handbags
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'container',
                                        'settings' => [
                                            'background_image' => 'https://images.unsplash.com/photo-1564422170194-896b89110ef8?w=400&h=250&fit=crop',
                                            'background_overlay' => 'rgba(0, 0, 0, 0.4)',
                                            'padding' => ['top' => 40, 'bottom' => 40],
                                            'border_radius' => 8,
                                            'margin' => ['bottom' => 20],
                                        ],
                                        'children' => [
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'text',
                                                'settings' => [
                                                    'text' => '<p style="text-align:center;color:#fff;font-size:24px;font-weight:700;margin:0;">Handbags</p>',
                                                ]
                                            ],
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'text',
                                                'settings' => [
                                                    'text' => '<p style="text-align:center;color:#fff;font-size:16px;">STARTING AT $99</p>',
                                                ]
                                            ],
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'button',
                                                'settings' => [
                                                    'text' => 'Shop Now',
                                                    'background_color' => '#92003b',
                                                    'text_color' => '#ffffff',
                                                    'align' => 'center',
                                                    'padding' => ['top' => 10, 'right' => 25, 'bottom' => 10, 'left' => 25],
                                                    'font_size' => 13,
                                                ]
                                            ],
                                        ]
                                    ],
                                    // Deal Promos
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'container',
                                        'settings' => [
                                            'background_gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                            'padding' => ['top' => 40, 'bottom' => 40],
                                            'border_radius' => 8,
                                        ],
                                        'children' => [
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'text',
                                                'settings' => [
                                                    'text' => '<p style="text-align:center;color:#fff;font-size:24px;font-weight:700;margin:0;">DEAL PROMOS</p>',
                                                ]
                                            ],
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'text',
                                                'settings' => [
                                                    'text' => '<p style="text-align:center;color:#fff;font-size:16px;">STARTING AT $99</p>',
                                                ]
                                            ],
                                            [
                                                'id' => $this->generate_id(),
                                                'widgetType' => 'button',
                                                'settings' => [
                                                    'text' => 'BUY NOW!',
                                                    'background_color' => '#ffffff',
                                                    'text_color' => '#f5576c',
                                                    'align' => 'center',
                                                    'padding' => ['top' => 10, 'right' => 25, 'bottom' => 10, 'left' => 25],
                                                    'font_size' => 13,
                                                ]
                                            ],
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 8. Featured Products Section
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Featured Products',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'font_weight' => 700,
                            'text_align' => 'center',
                            'color' => '#1e293b',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 5,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=300&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1564422170194-896b89110ef8?w=300&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=300&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 5,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 9. HUGE SALE Banner (Porto Style)
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'HUGE SALE - 70% OFF',
                    'description' => 'Limited time offer on selected items',
                    'button_text' => 'Shop The Sale',
                    'background_gradient' => 'linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 60, 'bottom' => 60],
                ]
            ],
        ];
    }
    
    /**
     * WoodMart Style Fashion Store - COMPLETE FULL PAGE
     */
    private function template_woodmart_fashion() {
        return [
            // 1. Full-width Hero Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_type' => 'image',
                    'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920',
                    'padding' => ['top' => 180, 'bottom' => 180],
                    'min_height' => 600,
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'New Season',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => 72,
                            'font_weight' => 300,
                            'text_transform' => 'uppercase',
                            'letter_spacing' => 5,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 20],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Fall Collection 2024',
                            'color' => '#ffffff',
                            'font_size' => 28,
                            'font_weight' => 600,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 40],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Explore Collection',
                            'background_color' => '#000000',
                            'text_color' => '#ffffff',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'font_weight' => 500,
                            'letter_spacing' => 2,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
            
            // 2. Category Grid with REAL IMAGES
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    'columns' => 3,
                    'gap' => 20,
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                                'widgetType' => 'image',
                        'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&h=700&fit=crop'],
                                    'height' => 450,
                                    'object_fit' => 'cover',
                                    'border_radius' => 0,
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                                'widgetType' => 'image',
                        'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?w=600&h=700&fit=crop'],
                                    'height' => 450,
                                    'object_fit' => 'cover',
                                    'border_radius' => 0,
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                                'widgetType' => 'image',
                        'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&h=700&fit=crop'],
                                    'height' => 450,
                                    'object_fit' => 'cover',
                                    'border_radius' => 0,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 3. Featured Collection with REAL IMAGES
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Featured Collection',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 25,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1467043237213-65f2da53396f?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1550614000-4895a10e1bfd?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 4. Trending Now with REAL IMAGES
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Trending Now',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1581044777550-4cfa60707c03?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1571455786673-9d9d6c194f90?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1589156288859-f0cb0d82b065?w=400&h=500&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 5. Lookbook Section
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#000000',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Editor\'s Pick',
                            'html_tag' => 'h2',
                            'color' => '#ffffff',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 2,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800'],
                                    'height' => 500,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=800'],
                                    'height' => 500,
                                    'object_fit' => 'cover',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 6. Sale Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Up to 50% Off - Limited Time',
                    'description' => 'Don\'t miss out on our biggest sale of the year',
                    'button_text' => 'Shop Sale',
                    'background_color' => '#92003b',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ]
            ],
            
            // 7. New Arrivals with REAL IMAGES
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'New Arrivals',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1542219550-37153d387c27?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1620799139507-2a76f79a2f4d?w=400&h=500&fit=crop'],
                                    'height' => 400,
                                    'object_fit' => 'cover',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 8. Features
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 60, 'bottom' => 60],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-truck',
                                    'title' => 'Free Shipping',
                                    'description' => 'On orders $100+',
                                    'icon_size' => 40,
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-undo',
                                    'title' => 'Easy Returns',
                                    'description' => '30-day policy',
                                    'icon_size' => 40,
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-certificate',
                                    'title' => 'Authentic',
                                    'description' => '100% genuine',
                                    'icon_size' => 40,
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-heart',
                                    'title' => 'Quality',
                                    'description' => 'Premium materials',
                                    'icon_size' => 40,
                                    'text_align' => 'center',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Flatsome Style Electronics Store - COMPLETE FULL PAGE
     */
    private function template_flatsome_electronics() {
        return [
            // 1. Modern Hero with Features
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_gradient' => 'linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%)',
                    'padding' => ['top' => 120, 'bottom' => 120],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Latest Technology',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => 64,
                            'font_weight' => 700,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 20],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Premium electronics at unbeatable prices',
                            'color' => '#ffffff',
                            'font_size' => 22,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 40],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Shop Electronics',
                            'background_color' => '#00bcd4',
                            'text_color' => '#ffffff',
                            'padding' => ['top' => 20, 'right' => 45, 'bottom' => 20, 'left' => 45],
                            'border_radius' => 5,
                            'font_size' => 18,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
            
            // 2. Product Categories
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Browse by Category',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=400&h=300&fit=crop'],
                                    'height' => 250,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=300&fit=crop'],
                                    'height' => 250,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1593642532842-98d0fd5ebc1a?w=400&h=300&fit=crop'],
                                    'height' => 250,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=300&fit=crop'],
                                    'height' => 250,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 3. Best Sellers with REAL ELECTRONICS
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f5f5f5',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Best Sellers',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1588423771073-b8903fbb85b5?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1563770660941-20978e870e26?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1609081219090-a6d81d3085bf?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 4. New Releases with REAL ELECTRONICS
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'New Releases',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1491933382434-500287f9b54b?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1585249805528-f28e3a66c5ed?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1586904371766-749f5dbeeaa4?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1589492477829-5e65395b66cc?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1612198188060-c7c2a3b66eae?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 5. Special Offers Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Tech Sale - Up to 40% Off',
                    'description' => 'Get the latest gadgets at amazing prices',
                    'button_text' => 'Shop Now',
                    'background_gradient' => 'linear-gradient(135deg, #00bcd4 0%, #0097a7 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ]
            ],
            
            // 6. Top Rated Products
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f5f5f5',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Top Rated Products',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=400&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1572635196243-4dd75fbdbd7f?w=400&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1590658165737-15a047b7a725?w=400&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1625825140283-4b85f70c40a4?w=400&h=400&fit=crop'],
                                    'height' => 350,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 7. Trust Badges
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-shield-alt',
                                    'title' => '2 Year Warranty',
                                    'description' => 'Extended protection',
                                    'icon_size' => 48,
                                    'icon_color' => '#00bcd4',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-truck',
                                    'title' => 'Free Shipping',
                                    'description' => 'On all orders',
                                    'icon_size' => 48,
                                    'icon_color' => '#00bcd4',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-credit-card',
                                    'title' => 'Secure Payment',
                                    'description' => 'SSL encrypted',
                                    'icon_size' => 48,
                                    'icon_color' => '#00bcd4',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-headphones',
                                    'title' => 'Expert Support',
                                    'description' => '24/7 available',
                                    'icon_size' => 48,
                                    'icon_color' => '#00bcd4',
                                    'text_align' => 'center',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 8. Newsletter CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Stay Updated with Tech News',
                    'description' => 'Subscribe to get notifications about new products and exclusive deals',
                    'button_text' => 'Subscribe Now',
                    'background_gradient' => 'linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ]
            ],
        ];
    }
    
    /**
     * Avada Style Product Page
     */
    private function template_avada_product() {
        return [
            // Product Header
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 40, 'bottom' => 40],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'breadcrumbs',
                        'settings' => []
                    ],
                ]
            ],
            
            // Product Details (2 columns)
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    'columns' => 2,
                    'gap' => 60,
                    'padding' => ['top' => 40, 'bottom' => 60],
                ],
                'children' => [
                    // Left: Product Images
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'container',
                        'settings' => [],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                        'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=600&fit=crop'],
                                    'height' => 500,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'grid-layout',
                                'settings' => [
                                    'columns' => 3,
                                    'gap' => 10,
                                ],
                                'children' => [
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'image',
                                        'settings' => [
                                            'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&h=200&fit=crop'],
                                            'height' => 120,
                                            'object_fit' => 'cover',
                                            'border_radius' => 5,
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'image',
                                        'settings' => [
                                            'image' => ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=200&h=200&fit=crop'],
                                            'height' => 120,
                                            'object_fit' => 'cover',
                                            'border_radius' => 5,
                                        ]
                                    ],
                                    [
                                        'id' => $this->generate_id(),
                                        'widgetType' => 'image',
                                        'settings' => [
                                            'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=200&h=200&fit=crop'],
                                            'height' => 120,
                                            'object_fit' => 'cover',
                                            'border_radius' => 5,
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                    // Right: Product Info
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'container',
                        'settings' => [],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'heading',
                                'settings' => [
                                    'text' => 'Premium Product Name',
                                    'html_tag' => 'h1',
                                    'font_size' => 36,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'star-rating',
                                'settings' => [
                                    'rating' => 4.5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'text',
                                'settings' => [
                                    'text' => '<p style="font-size: 32px; color: #92003b; font-weight: 700;">$299.00</p>',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'text',
                                'settings' => [
                                    'text' => 'High-quality product description goes here. This product features premium materials and exceptional craftsmanship.',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'woo-add-to-cart',
                                'settings' => [
                                    'button_text' => 'Add to Cart',
                                    'show_quantity' => 'yes',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'woo-meta',
                                'settings' => [
                                    'show_sku' => 'yes',
                                    'show_category' => 'yes',
                                    'show_tags' => 'yes',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // Product Tabs
            [
                'id' => $this->generate_id(),
                'widgetType' => 'tabs',
                'settings' => [
                    'tabs' => [
                        ['title' => 'Description', 'content' => 'Detailed product description...'],
                        ['title' => 'Specifications', 'content' => 'Product specifications...'],
                        ['title' => 'Reviews', 'content' => 'Customer reviews...'],
                    ],
                ]
            ],
            
            // Related Products
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'You May Also Like',
                            'html_tag' => 'h2',
                            'font_size' => 32,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 40],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Modern E-Commerce Homepage - COMPLETE FULL PAGE
     */
    private function template_modern_homepage() {
        return [
            // 1. Hero Section
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'padding' => ['top' => 120, 'bottom' => 120],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Welcome to Our Store',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => 64,
                            'font_weight' => 700,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 20],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Find everything you need at amazing prices',
                            'color' => '#ffffff',
                            'font_size' => 22,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 40],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Start Shopping',
                            'background_color' => '#ffffff',
                            'text_color' => '#f5576c',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'border_radius' => 50,
                            'font_size' => 18,
                            'font_weight' => 600,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
            
            // 2. Featured Categories
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Shop by Category',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 10,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 10,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 10,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 10,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 3. Best Sellers with REAL IMAGES
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Best Selling Products',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 4. Sale Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Limited Time Offer - 30% Off',
                    'description' => 'Use code SAVE30 at checkout',
                    'button_text' => 'Shop Now',
                    'background_color' => '#92003b',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ]
            ],
            
            // 5. New Arrivals
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'New Arrivals',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1532453288672-3a27e9be9efd?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 6. Features
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-truck',
                                    'title' => 'Free Shipping',
                                    'description' => 'On orders $50+',
                                    'icon_size' => 48,
                                    'icon_color' => '#f5576c',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-undo',
                                    'title' => 'Easy Returns',
                                    'description' => '30-day guarantee',
                                    'icon_size' => 48,
                                    'icon_color' => '#f5576c',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-shield-alt',
                                    'title' => 'Secure Checkout',
                                    'description' => '100% safe',
                                    'icon_size' => 48,
                                    'icon_color' => '#f5576c',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-headset',
                                    'title' => '24/7 Support',
                                    'description' => 'Always here',
                                    'icon_size' => 48,
                                    'icon_color' => '#f5576c',
                                    'text_align' => 'center',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 7. Testimonials
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'What Customers Say',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'carousel',
                        'settings' => [
                            'autoplay' => 'yes',
                            'loop' => 'yes',
                            'items_per_slide' => 3,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Amazing quality products and excellent customer service!',
                                    'author_name' => 'David Wilson',
                                    'rating' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Fast shipping and great prices. Highly recommended!',
                                    'author_name' => 'Lisa Anderson',
                                    'rating' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Best shopping experience ever. Will shop again!',
                                    'author_name' => 'Robert Taylor',
                                    'rating' => 5,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 8. Newsletter CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Join Our Newsletter',
                    'description' => 'Get exclusive offers and updates delivered to your inbox',
                    'button_text' => 'Subscribe',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ]
            ],
        ];
    }
    
    /**
     * SaaS Landing Page - COMPLETE FULL PAGE
     */
    private function template_saas_landing() {
        return [
            // 1. Hero
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_color' => '#0f172a',
                    'padding' => ['top' => 140, 'bottom' => 140],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Build Amazing Websites',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => 64,
                            'font_weight' => 700,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 20],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'The most powerful page builder for WordPress',
                            'color' => '#94a3b8',
                            'font_size' => 22,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 40],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Get Started Free',
                            'background_color' => '#3b82f6',
                            'text_color' => '#ffffff',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'border_radius' => 8,
                            'font_size' => 18,
                            'font_weight' => 600,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
            
            // 2. Features
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 100, 'bottom' => 100],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Everything You Need',
                            'html_tag' => 'h2',
                            'font_size' => 48,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 40,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-rocket',
                                    'title' => 'Lightning Fast',
                                    'description' => 'Optimized for speed and performance',
                                    'icon_size' => 64,
                                    'icon_color' => '#3b82f6',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-shield-alt',
                                    'title' => 'Secure',
                                    'description' => 'Bank-level security for your data',
                                    'icon_size' => 64,
                                    'icon_color' => '#3b82f6',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-mobile-alt',
                                    'title' => 'Mobile Ready',
                                    'description' => 'Responsive on all devices',
                                    'icon_size' => 64,
                                    'icon_color' => '#3b82f6',
                                    'text_align' => 'center',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 3. How It Works
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 100, 'bottom' => 100],
                    'background_color' => '#f8fafc',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'How It Works',
                            'html_tag' => 'h2',
                            'font_size' => 48,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 40,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-user-plus',
                                    'title' => 'Sign Up',
                                    'description' => 'Create your free account in seconds',
                                    'icon_size' => 64,
                                    'icon_color' => '#3b82f6',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-magic',
                                    'title' => 'Build',
                                    'description' => 'Drag and drop to create your site',
                                    'icon_size' => 64,
                                    'icon_color' => '#3b82f6',
                                    'text_align' => 'center',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-globe',
                                    'title' => 'Launch',
                                    'description' => 'Publish and go live instantly',
                                    'icon_size' => 64,
                                    'icon_color' => '#3b82f6',
                                    'text_align' => 'center',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 4. Testimonials
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 100, 'bottom' => 100],
                    'background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Trusted by Thousands',
                            'html_tag' => 'h2',
                            'font_size' => 48,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'carousel',
                        'settings' => [
                            'autoplay' => 'yes',
                            'loop' => 'yes',
                            'items_per_slide' => 3,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Best page builder I\'ve ever used. Made my workflow so much faster!',
                                    'author_name' => 'Alex Thompson',
                                    'author_title' => 'Web Designer',
                                    'rating' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Incredible features and support. Highly recommend!',
                                    'author_name' => 'Maria Garcia',
                                    'author_title' => 'Developer',
                                    'rating' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Saves me hours every week. Worth every penny!',
                                    'author_name' => 'James Brown',
                                    'author_title' => 'Agency Owner',
                                    'rating' => 5,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 5. Pricing
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 100, 'bottom' => 100],
                    'background_color' => '#f8fafc',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Simple Pricing',
                            'html_tag' => 'h2',
                            'font_size' => 48,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'pricing-table',
                                'settings' => [
                                    'title' => 'Starter',
                                    'price' => '$29',
                                    'period' => 'per month',
                                    'features' => ['5 Websites', '10GB Storage', 'Email Support'],
                                    'button_text' => 'Get Started',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'pricing-table',
                                'settings' => [
                                    'title' => 'Pro',
                                    'price' => '$79',
                                    'period' => 'per month',
                                    'features' => ['Unlimited Websites', '50GB Storage', 'Priority Support', 'Advanced Features'],
                                    'button_text' => 'Get Started',
                                    'featured' => 'yes',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'pricing-table',
                                'settings' => [
                                    'title' => 'Enterprise',
                                    'price' => '$199',
                                    'period' => 'per month',
                                    'features' => ['Everything in Pro', 'Dedicated Support', 'Custom Development'],
                                    'button_text' => 'Contact Sales',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // 6. CTA Section
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Ready to Get Started?',
                    'description' => 'Join thousands of happy customers and start building today',
                    'button_text' => 'Start Free Trial',
                    'background_gradient' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                    'padding' => ['top' => 100, 'bottom' => 100],
                ]
            ],
        ];
    }
    
    // ============================================
    // SECTION TEMPLATES
    // ============================================
    
    /**
     * Modern Hero Section
     */
    private function section_hero_modern() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'padding' => ['top' => 100, 'bottom' => 100],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Your Amazing Headline',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => 56,
                            'font_weight' => 700,
                            'text_align' => 'center',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'A compelling sub-headline that describes your value proposition',
                            'color' => '#ffffff',
                            'font_size' => 20,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 30],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Get Started',
                            'background_color' => '#ffffff',
                            'text_color' => '#764ba2',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'border_radius' => 50,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Split Screen Hero
     */
    private function section_hero_split() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    'columns' => 2,
                    'gap' => 0,
                    'min_height' => 600,
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'container',
                        'settings' => [
                            'background_color' => '#0f172a',
                            'padding' => ['top' => 80, 'right' => 60, 'bottom' => 80, 'left' => 60],
                            'vertical_align' => 'center',
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'heading',
                                'settings' => [
                                    'text' => 'Split Hero Section',
                                    'html_tag' => 'h1',
                                    'color' => '#ffffff',
                                    'font_size' => 48,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'text',
                                'settings' => [
                                    'text' => 'Modern design with split layout for maximum impact',
                                    'color' => '#94a3b8',
                                    'font_size' => 18,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'button',
                                'settings' => [
                                    'text' => 'Learn More',
                                    'background_color' => '#3b82f6',
                                    'text_color' => '#ffffff',
                                ]
                            ],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => ['url' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800'],
                            'object_fit' => 'cover',
                            'height' => 600,
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Products Grid Section
     */
    private function section_products_grid() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Featured Products',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Features with Icons Section
     */
    private function section_features_icons() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Why Choose Us',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 40,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-bolt',
                                    'title' => 'Fast Performance',
                                    'description' => 'Lightning-fast loading speeds',
                                    'icon_size' => 64,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-shield-alt',
                                    'title' => 'Secure & Safe',
                                    'description' => 'Bank-level security',
                                    'icon_size' => 64,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-headset',
                                    'title' => '24/7 Support',
                                    'description' => 'Always here to help you',
                                    'icon_size' => 64,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Testimonials Section
     */
    private function section_testimonials() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'What Our Customers Say',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'carousel',
                        'settings' => [
                            'autoplay' => 'yes',
                            'loop' => 'yes',
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Excellent service and great products! Highly recommended.',
                                    'author_name' => 'John Doe',
                                    'author_title' => 'CEO, Company Inc',
                                    'rating' => 5,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'testimonial',
                                'settings' => [
                                    'content' => 'Best purchase I\'ve made this year. Quality is outstanding!',
                                    'author_name' => 'Jane Smith',
                                    'author_title' => 'Designer',
                                    'rating' => 5,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Call to Action Section
     */
    private function section_cta() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Ready to Get Started?',
                    'description' => 'Join thousands of happy customers today',
                    'button_text' => 'Get Started Now',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                ]
            ],
        ];
    }
    
    /**
     * Pricing Table Section
     */
    private function section_pricing() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Choose Your Plan',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 3,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'pricing-table',
                                'settings' => [
                                    'title' => 'Basic',
                                    'price' => '$29',
                                    'period' => 'per month',
                                    'features' => ['Feature 1', 'Feature 2', 'Feature 3'],
                                    'button_text' => 'Get Started',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'pricing-table',
                                'settings' => [
                                    'title' => 'Pro',
                                    'price' => '$79',
                                    'period' => 'per month',
                                    'features' => ['Everything in Basic', 'Feature 4', 'Feature 5', 'Priority Support'],
                                    'button_text' => 'Get Started',
                                    'featured' => 'yes',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'pricing-table',
                                'settings' => [
                                    'title' => 'Enterprise',
                                    'price' => '$199',
                                    'period' => 'per month',
                                    'features' => ['Everything in Pro', 'Feature 6', 'Feature 7', 'Dedicated Support'],
                                    'button_text' => 'Contact Sales',
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Hero Video Background Section
     */
    private function section_hero_video() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_color' => '#000000',
                    'padding' => ['top' => 120, 'bottom' => 120],
                    'min_height' => 600,
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Welcome to the Future',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => 64,
                            'font_weight' => 700,
                            'text_align' => 'center',
                            'text_shadow' => '0 4px 20px rgba(0,0,0,0.5)',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Experience innovation like never before',
                            'color' => '#ffffff',
                            'font_size' => 24,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 30],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Watch Video',
                            'background_color' => 'transparent',
                            'text_color' => '#ffffff',
                            'border_width' => 2,
                            'border_color' => '#ffffff',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'border_radius' => 50,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Products Carousel Section
     */
    private function section_products_carousel() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Trending Products',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1506152983158-b4a74a01c721?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Featured Products Banner Section
     */
    private function section_products_featured() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Featured Products',
                            'html_tag' => 'h2',
                            'color' => '#ffffff',
                            'font_size' => 48,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 40],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 20,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'image',
                                'settings' => [
                                    'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400&h=400&fit=crop'],
                                    'height' => 300,
                                    'object_fit' => 'cover',
                                    'border_radius' => 8,
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Features Modern Cards Section
     */
    private function section_features_cards() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                    'background_color' => '#f8f9fa',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Our Features',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-palette',
                                    'title' => 'Beautiful Design',
                                    'description' => 'Stunning visual elements',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                    'background_color' => '#ffffff',
                                    'padding' => ['top' => 30, 'bottom' => 30],
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-cog',
                                    'title' => 'Easy Customization',
                                    'description' => 'Flexible and powerful',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                    'background_color' => '#ffffff',
                                    'padding' => ['top' => 30, 'bottom' => 30],
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-rocket',
                                    'title' => 'Fast Loading',
                                    'description' => 'Optimized performance',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                    'background_color' => '#ffffff',
                                    'padding' => ['top' => 30, 'bottom' => 30],
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-mobile-alt',
                                    'title' => 'Fully Responsive',
                                    'description' => 'Works on all devices',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                    'background_color' => '#ffffff',
                                    'padding' => ['top' => 30, 'bottom' => 30],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Team Grid Section
     */
    private function section_team_grid() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 80],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Meet Our Team',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 60],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'grid-layout',
                        'settings' => [
                            'columns' => 4,
                            'gap' => 30,
                        ],
                        'children' => [
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'team-member',
                                'settings' => [
                                    'name' => 'John Doe',
                                    'position' => 'CEO & Founder',
                                    'image' => ['url' => 'https://i.pravatar.cc/300?img=12'],
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'team-member',
                                'settings' => [
                                    'name' => 'Jane Smith',
                                    'position' => 'Chief Designer',
                                    'image' => ['url' => 'https://i.pravatar.cc/300?img=5'],
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'team-member',
                                'settings' => [
                                    'name' => 'Mike Johnson',
                                    'position' => 'Lead Developer',
                                    'image' => ['url' => 'https://i.pravatar.cc/300?img=33'],
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'team-member',
                                'settings' => [
                                    'name' => 'Sarah Williams',
                                    'position' => 'Marketing Director',
                                    'image' => ['url' => 'https://i.pravatar.cc/300?img=9'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];
    }
    
    // ============================================
    // USER TEMPLATES
    // ============================================
    
    /**
     * Get user-saved templates
     */
    public function get_user_templates() {
        $templates = [];
        
        $args = [
            'post_type' => 'probuilder_template',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                
                $templates[] = [
                    'id' => $post_id,
                    'name' => get_the_title(),
                    'category' => 'user',
                    'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
                    'data' => get_post_meta($post_id, '_probuilder_data', true),
                    'type' => 'user',
                ];
            }
            wp_reset_postdata();
        }
        
        return $templates;
    }
    
    /**
     * Save template (placeholder)
     */
    public function save_template() {
        wp_send_json_success(['message' => 'Template saved']);
    }
    
    /**
     * Delete template (placeholder)
     */
    public function delete_template() {
        wp_send_json_success(['message' => 'Template deleted']);
    }
    
    /**
     * Import template (placeholder)
     */
    public function import_template() {
        wp_send_json_success(['message' => 'Template imported']);
    }
}

