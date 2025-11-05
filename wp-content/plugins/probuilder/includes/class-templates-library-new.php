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
            
            // SECTIONS
            ['id' => 'hero-modern', 'name' => 'Hero - Modern', 'category' => 'hero', 'data' => $this->section_hero_modern()],
            ['id' => 'hero-split', 'name' => 'Hero - Split Screen', 'category' => 'hero', 'data' => $this->section_hero_split()],
            ['id' => 'products-grid', 'name' => 'Products - Grid', 'category' => 'products', 'data' => $this->section_products_grid()],
            ['id' => 'features-icons', 'name' => 'Features - Icons', 'category' => 'features', 'data' => $this->section_features_icons()],
            ['id' => 'testimonials-modern', 'name' => 'Testimonials', 'category' => 'testimonials', 'data' => $this->section_testimonials()],
            ['id' => 'cta-gradient', 'name' => 'CTA Banner', 'category' => 'cta', 'data' => $this->section_cta()],
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
     * Porto Style Modern Shop Homepage
     */
    private function template_porto_shop() {
        return [
            // Hero Section with CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'padding' => ['top' => 100, 'bottom' => 100],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Summer Collection 2024',
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
                            'text' => 'Discover the latest trends in fashion with up to 50% off',
                            'color' => '#ffffff',
                            'font_size' => 20,
                            'text_align' => 'center',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Shop Now',
                            'button_type' => 'solid',
                            'background_color' => '#ffffff',
                            'text_color' => '#764ba2',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'border_radius' => 50,
                            'font_size' => 18,
                            'font_weight' => 600,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
            
            // Featured Products Grid
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
                            'text' => 'Featured Products',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'font_weight' => 700,
                            'text_align' => 'center',
                            'color' => '#1e293b',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'woo-products',
                        'settings' => [
                            'posts_per_page' => 8,
                            'columns' => 4,
                            'orderby' => 'popularity',
                        ]
                    ],
                ]
            ],
            
            // Banner CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Get 20% Off Your First Order',
                    'description' => 'Sign up for our newsletter and receive exclusive deals',
                    'button_text' => 'Subscribe Now',
                    'background_color' => '#92003b',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                ]
            ],
            
            // Features Section
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
                                    'icon' => 'fa fa-shipping-fast',
                                    'title' => 'Free Shipping',
                                    'description' => 'On orders over $50',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-undo',
                                    'title' => 'Easy Returns',
                                    'description' => '30-day return policy',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-lock',
                                    'title' => 'Secure Payment',
                                    'description' => '100% secure checkout',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                            [
                                'id' => $this->generate_id(),
                                'widgetType' => 'icon-box',
                                'settings' => [
                                    'icon' => 'fa fa-headset',
                                    'title' => '24/7 Support',
                                    'description' => 'Always here to help',
                                    'icon_size' => 48,
                                    'icon_color' => '#92003b',
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }
    
    /**
     * WoodMart Style Fashion Store
     */
    private function template_woodmart_fashion() {
        return [
            // Full-width Hero Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_type' => 'image',
                    'background_image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920',
                    'padding' => ['top' => 150, 'bottom' => 150],
                    'content_width' => 'full',
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
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Fall Collection 2024',
                            'color' => '#ffffff',
                            'font_size' => 24,
                            'font_weight' => 600,
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Explore Collection',
                            'background_color' => '#000000',
                            'text_color' => '#ffffff',
                            'padding' => ['top' => 15, 'right' => 35, 'bottom' => 15, 'left' => 35],
                            'font_weight' => 500,
                            'letter_spacing' => 2,
                        ]
                    ],
                ]
            ],
            
            // Category Grid
            [
                'id' => $this->generate_id(),
                'widgetType' => 'grid-layout',
                'settings' => [
                    'columns' => 3,
                    'gap' => 20,
                    'padding' => ['top' => 60, 'bottom' => 60],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'image-box',
                        'settings' => [
                            'image_url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600',
                            'title' => 'Women',
                            'description' => 'Shop Now',
                            'overlay' => 'yes',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'image-box',
                        'settings' => [
                            'image_url' => 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?w=600',
                            'title' => 'Men',
                            'description' => 'Shop Now',
                            'overlay' => 'yes',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'image-box',
                        'settings' => [
                            'image_url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600',
                            'title' => 'Accessories',
                            'description' => 'Shop Now',
                            'overlay' => 'yes',
                        ]
                    ],
                ]
            ],
            
            // Trending Products
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
                            'text' => 'Trending Now',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'woo-products',
                        'settings' => [
                            'posts_per_page' => 6,
                            'columns' => 3,
                            'orderby' => 'date',
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Flatsome Style Electronics Store
     */
    private function template_flatsome_electronics() {
        return [
            // Modern Hero with Features
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_gradient' => 'linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%)',
                    'padding' => ['top' => 100, 'bottom' => 100],
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
                            'margin' => ['bottom' => 30],
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
            
            // Product Categories
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
                            'text' => 'Browse by Category',
                            'html_tag' => 'h2',
                            'font_size' => 38,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'woo-categories',
                        'settings' => [
                            'columns' => 4,
                            'show_count' => 'yes',
                        ]
                    ],
                ]
            ],
            
            // Featured Products
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 60, 'bottom' => 80],
                    'background_color' => '#f5f5f5',
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Best Sellers',
                            'html_tag' => 'h2',
                            'font_size' => 38,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'woo-products',
                        'settings' => [
                            'posts_per_page' => 8,
                            'columns' => 4,
                            'orderby' => 'popularity',
                        ]
                    ],
                ]
            ],
            
            // Trust Badges
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 50, 'bottom' => 50],
                ],
                'children' => [
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'feature-list',
                        'settings' => [
                            'items' => [
                                ['icon' => 'fa fa-shield-alt', 'title' => '2 Year Warranty', 'description' => 'Extended protection'],
                                ['icon' => 'fa fa-truck', 'title' => 'Free Shipping', 'description' => 'On all orders'],
                                ['icon' => 'fa fa-credit-card', 'title' => 'Secure Payment', 'description' => 'SSL encrypted'],
                                ['icon' => 'fa fa-headphones', 'title' => 'Expert Support', 'description' => '24/7 available'],
                            ],
                            'layout' => 'horizontal',
                        ]
                    ],
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
                        'widgetType' => 'gallery',
                        'settings' => [
                            'columns' => 1,
                            'gap' => 15,
                            'lightbox' => 'yes',
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
                        'widgetType' => 'woo-related',
                        'settings' => [
                            'posts_per_page' => 4,
                            'columns' => 4,
                        ]
                    ],
                ]
            ],
        ];
    }
    
    /**
     * Modern E-Commerce Homepage
     */
    private function template_modern_homepage() {
        return [
            // Hero Section
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
                            'font_size' => 58,
                            'font_weight' => 700,
                            'text_align' => 'center',
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Find everything you need at amazing prices',
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
                            'text' => 'Start Shopping',
                            'background_color' => '#ffffff',
                            'text_color' => '#f5576c',
                            'padding' => ['top' => 18, 'right' => 40, 'bottom' => 18, 'left' => 40],
                            'border_radius' => 50,
                            'font_size' => 16,
                            'font_weight' => 600,
                            'align' => 'center',
                        ]
                    ],
                ]
            ],
            
            // Featured Categories
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
                            'text' => 'Shop by Category',
                            'html_tag' => 'h2',
                            'font_size' => 42,
                            'text_align' => 'center',
                            'margin' => ['bottom' => 50],
                        ]
                    ],
                    [
                        'id' => $this->generate_id(),
                        'widgetType' => 'woo-categories',
                        'settings' => [
                            'columns' => 4,
                        ]
                    ],
                ]
            ],
            
            // Featured Products
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 60, 'bottom' => 80],
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
                        'widgetType' => 'woo-products',
                        'settings' => [
                            'posts_per_page' => 8,
                            'columns' => 4,
                        ]
                    ],
                ]
            ],
            
            // CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Join Our Newsletter',
                    'description' => 'Get exclusive offers and updates delivered to your inbox',
                    'button_text' => 'Subscribe',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                ]
            ],
        ];
    }
    
    /**
     * SaaS Landing Page
     */
    private function template_saas_landing() {
        return [
            // Hero
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'background_color' => '#0f172a',
                    'padding' => ['top' => 120, 'bottom' => 120],
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
            
            // Features
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 100, 'bottom' => 100],
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
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            
            // Pricing
            [
                'id' => $this->generate_id(),
                'widgetType' => 'container',
                'settings' => [
                    'padding' => ['top' => 80, 'bottom' => 100],
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
                        'widgetType' => 'woo-products',
                        'settings' => [
                            'posts_per_page' => 8,
                            'columns' => 4,
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

