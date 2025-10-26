<?php
/**
 * ProBuilder Templates Library
 * 
 * Manages pre-built templates and user templates
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
        // Only load metadata, not full template data (for performance)
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
        // Simple placeholder thumbnail (fast!)
        $placeholder = 'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="#92003b" font-size="24" font-weight="bold">ProBuilder</text></svg>');
        
        return [
            // E-COMMERCE FULL PAGE TEMPLATES
            ['id' => 'ecommerce-shop', 'name' => '🛒 E-Commerce Shop Page', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'ecommerce-product', 'name' => '📦 Product Detail Page', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'ecommerce-homepage', 'name' => '🏪 E-Commerce Homepage', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'fashion-store', 'name' => '👗 Fashion Store Homepage', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'electronics-store', 'name' => '💻 Electronics Store', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'agency-portfolio', 'name' => '🎨 Creative Agency Portfolio', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'saas-landing', 'name' => '🚀 SaaS Landing Page', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'restaurant-food', 'name' => '🍕 Restaurant & Food Ordering', 'category' => 'pages', 'thumbnail' => $placeholder],
            ['id' => 'blog-magazine', 'name' => '📰 Blog & Magazine', 'category' => 'pages', 'thumbnail' => $placeholder],
            
            // SECTION TEMPLATES
            ['id' => 'hero-1', 'name' => 'Hero Section - Modern', 'category' => 'hero', 'thumbnail' => $placeholder],
            ['id' => 'hero-2', 'name' => 'Hero Section - Gradient', 'category' => 'hero', 'thumbnail' => $placeholder],
            ['id' => 'features-grid', 'name' => 'Features Grid - 3 Columns', 'category' => 'features', 'thumbnail' => $placeholder],
            ['id' => 'pricing-table', 'name' => 'Pricing Table - 3 Plans', 'category' => 'pricing', 'thumbnail' => $placeholder],
            ['id' => 'team-section', 'name' => 'Team Section - 4 Members', 'category' => 'team', 'thumbnail' => $placeholder],
            ['id' => 'testimonials', 'name' => 'Testimonials - Carousel', 'category' => 'testimonials', 'thumbnail' => $placeholder],
            ['id' => 'cta-banner', 'name' => 'Call to Action - Banner', 'category' => 'cta', 'thumbnail' => $placeholder],
            ['id' => 'gallery-masonry', 'name' => 'Gallery - Masonry Grid', 'category' => 'gallery', 'thumbnail' => $placeholder],
            ['id' => 'stats-counter', 'name' => 'Stats Counter - 4 Columns', 'category' => 'stats', 'thumbnail' => $placeholder],
            ['id' => 'services-cards', 'name' => 'Services Cards - 3 Columns', 'category' => 'services', 'thumbnail' => $placeholder],
            ['id' => 'contact-form', 'name' => 'Contact Section with Form', 'category' => 'contact', 'thumbnail' => $placeholder],
            ['id' => 'newsletter-popup', 'name' => 'Newsletter - Subscribe Box', 'category' => 'newsletter', 'thumbnail' => $placeholder]
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
     * Get pre-built templates
     */
    public function get_prebuilt_templates() {
        return [
            // E-COMMERCE FULL PAGE TEMPLATES
            [
                'id' => 'ecommerce-shop',
                'name' => '🛒 E-Commerce Shop Page',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_ecommerce_thumbnail()),
                'data' => $this->get_ecommerce_shop_page()
            ],
            [
                'id' => 'ecommerce-product',
                'name' => '📦 Product Detail Page',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_product_thumbnail()),
                'data' => $this->get_ecommerce_product_page()
            ],
            [
                'id' => 'ecommerce-homepage',
                'name' => '🏪 E-Commerce Homepage',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_homepage_thumbnail()),
                'data' => $this->get_ecommerce_homepage()
            ],
            [
                'id' => 'fashion-store',
                'name' => '👗 Fashion Store Homepage',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_fashion_thumbnail()),
                'data' => $this->get_fashion_store_page()
            ],
            [
                'id' => 'electronics-store',
                'name' => '💻 Electronics Store',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_electronics_thumbnail()),
                'data' => $this->get_electronics_store_page()
            ],
            [
                'id' => 'agency-portfolio',
                'name' => '🎨 Creative Agency Portfolio',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_full_page_thumbnail()),
                'data' => $this->build_agency_portfolio_page()
            ],
            [
                'id' => 'saas-landing',
                'name' => '🚀 SaaS Landing Page',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_full_page_thumbnail()),
                'data' => $this->build_saas_landing_page()
            ],
            [
                'id' => 'restaurant-food',
                'name' => '🍕 Restaurant & Food Ordering',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_full_page_thumbnail()),
                'data' => $this->build_restaurant_page()
            ],
            [
                'id' => 'blog-magazine',
                'name' => '📰 Blog & Magazine',
                'category' => 'pages',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_full_page_thumbnail()),
                'data' => $this->build_blog_magazine_page()
            ],
            
            // SECTION TEMPLATES
            [
                'id' => 'hero-1',
                'name' => 'Hero Section - Modern',
                'category' => 'hero',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_hero_thumbnail()),
                'data' => $this->get_hero_template_1()
            ],
            [
                'id' => 'hero-2',
                'name' => 'Hero Section - Gradient',
                'category' => 'hero',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_hero_gradient_thumbnail()),
                'data' => $this->get_hero_template_2()
            ],
            [
                'id' => 'features-grid',
                'name' => 'Features Grid - 3 Columns',
                'category' => 'features',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_features_thumbnail()),
                'data' => $this->get_features_grid_template()
            ],
            [
                'id' => 'pricing-table',
                'name' => 'Pricing Table - 3 Plans',
                'category' => 'pricing',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_pricing_thumbnail()),
                'data' => $this->get_pricing_template()
            ],
            [
                'id' => 'team-section',
                'name' => 'Team Section - 4 Members',
                'category' => 'team',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_team_thumbnail()),
                'data' => $this->get_team_template()
            ],
            [
                'id' => 'testimonials',
                'name' => 'Testimonials - Carousel',
                'category' => 'testimonials',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_testimonials_thumbnail()),
                'data' => $this->get_testimonials_template()
            ],
            [
                'id' => 'cta-banner',
                'name' => 'Call to Action - Banner',
                'category' => 'cta',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_cta_thumbnail()),
                'data' => $this->get_cta_template()
            ],
            [
                'id' => 'gallery-masonry',
                'name' => 'Gallery - Masonry Grid',
                'category' => 'gallery',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_gallery_thumbnail()),
                'data' => $this->get_gallery_template()
            ],
            [
                'id' => 'stats-counter',
                'name' => 'Stats Counter - 4 Columns',
                'category' => 'stats',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_stats_thumbnail()),
                'data' => $this->get_stats_template()
            ],
            [
                'id' => 'services-cards',
                'name' => 'Services - Icon Cards',
                'category' => 'services',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_services_thumbnail()),
                'data' => $this->get_services_template()
            ],
            [
                'id' => 'contact-section',
                'name' => 'Contact Section - Form + Info',
                'category' => 'contact',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_contact_thumbnail()),
                'data' => $this->get_contact_template()
            ],
            [
                'id' => 'newsletter-popup',
                'name' => 'Newsletter - Subscribe Box',
                'category' => 'newsletter',
                'thumbnail' => 'data:image/svg+xml;base64,' . base64_encode($this->get_newsletter_thumbnail()),
                'data' => $this->get_newsletter_template()
            ]
        ];
    }
    
    /**
     * Hero Template 1 - Modern
     */
    private function get_hero_template_1() {
        return [
            [
                'id' => 'hero-container-1',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '2',
                    'min_height' => '500',
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'hero-heading',
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Build Amazing Websites',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => '48',
                            'font_weight' => '700'
                        ],
                        'children' => []
                    ],
                    [
                        'id' => 'hero-image',
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => ['url' => 'https://via.placeholder.com/600x400/667eea/ffffff?text=Hero+Image'],
                            'align' => 'center'
                        ],
                        'children' => []
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Hero Template 2 - Gradient
     */
    private function get_hero_template_2() {
        return [
            [
                'id' => 'hero-container-2',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'min_height' => '600',
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(to right, #fa709a 0%, #fee140 100%)',
                    'padding' => ['top' => '100', 'right' => '20', 'bottom' => '100', 'left' => '20']
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Features Grid Template
     */
    private function get_features_grid_template() {
        return [
            [
                'id' => 'features-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '3',
                    'column_gap' => '30',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20']
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Pricing Template
     */
    private function get_pricing_template() {
        return [
            [
                'id' => 'pricing-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '3',
                    'column_gap' => '30',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20'],
                    'background_color' => '#f8f9fa'
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Team Template
     */
    private function get_team_template() {
        return [
            [
                'id' => 'team-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '4',
                    'column_gap' => '30',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20']
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Testimonials Template
     */
    private function get_testimonials_template() {
        return [
            [
                'id' => 'testimonials-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20'],
                    'background_color' => '#ffffff'
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * CTA Template
     */
    private function get_cta_template() {
        return [
            [
                'id' => 'cta-container',
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Ready to Get Started?',
                    'description' => 'Join thousands of happy customers today!',
                    'button_text' => 'Start Free Trial',
                    'button_url' => ['url' => '#'],
                    'background_color' => '#92003b',
                    'text_color' => '#ffffff'
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Gallery Template
     */
    private function get_gallery_template() {
        return [
            [
                'id' => 'gallery-widget',
                'widgetType' => 'gallery',
                'settings' => [
                    'columns' => '4',
                    'gap' => '15'
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Stats Template
     */
    private function get_stats_template() {
        return [
            [
                'id' => 'stats-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '4',
                    'column_gap' => '30',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20'],
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Services Template
     */
    private function get_services_template() {
        return [
            [
                'id' => 'services-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '3',
                    'column_gap' => '30',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20']
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Contact Template
     */
    private function get_contact_template() {
        return [
            [
                'id' => 'contact-container',
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '2',
                    'column_gap' => '40',
                    'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20']
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Newsletter Template
     */
    private function get_newsletter_template() {
        return [
            [
                'id' => 'newsletter-widget',
                'widgetType' => 'newsletter',
                'settings' => [
                    'title' => 'Subscribe to Our Newsletter',
                    'description' => 'Get the latest updates and exclusive offers',
                    'layout' => 'inline',
                    'button_color' => '#92003b'
                ],
                'children' => []
            ]
        ];
    }
    
    /**
     * Get user-saved templates
     */
    public function get_user_templates() {
        $templates = get_option('probuilder_user_templates', []);
        return is_array($templates) ? $templates : [];
    }
    
    /**
     * Save current design as template
     */
    public function save_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission denied');
        }
        
        $name = sanitize_text_field($_POST['name']);
        $category = sanitize_text_field($_POST['category']);
        $data = json_decode(stripslashes($_POST['data']), true);
        
        $templates = $this->get_user_templates();
        
        $template = [
            'id' => 'user-' . time(),
            'name' => $name,
            'category' => $category,
            'data' => $data,
            'created' => current_time('mysql'),
            'thumbnail' => '' // Could generate thumbnail later
        ];
        
        $templates[] = $template;
        
        update_option('probuilder_user_templates', $templates);
        
        wp_send_json_success([
            'message' => 'Template saved successfully',
            'template' => $template
        ]);
    }
    
    /**
     * Delete user template
     */
    public function delete_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission denied');
        }
        
        $template_id = sanitize_text_field($_POST['template_id']);
        $templates = $this->get_user_templates();
        
        $templates = array_filter($templates, function($template) use ($template_id) {
            return $template['id'] !== $template_id;
        });
        
        update_option('probuilder_user_templates', array_values($templates));
        
        wp_send_json_success('Template deleted');
    }
    
    /**
     * Import template
     */
    public function import_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $template_id = sanitize_text_field($_POST['template_id']);
        $type = sanitize_text_field($_POST['type']);
        
        if ($type === 'prebuilt') {
            $templates = $this->get_prebuilt_templates();
            $template = array_filter($templates, function($t) use ($template_id) {
                return $t['id'] === $template_id;
            });
            
            if (!empty($template)) {
                $template = array_values($template)[0];
                wp_send_json_success($template['data']);
            }
        } else {
            $templates = $this->get_user_templates();
            $template = array_filter($templates, function($t) use ($template_id) {
                return $t['id'] === $template_id;
            });
            
            if (!empty($template)) {
                $template = array_values($template)[0];
                wp_send_json_success($template['data']);
            }
        }
        
        wp_send_json_error('Template not found');
    }
    
    /**
     * SVG Thumbnails for templates
     */
    private function get_hero_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#667eea"/><rect x="20" y="60" width="120" height="15" fill="#fff" opacity="0.9"/><rect x="20" y="85" width="160" height="10" fill="#fff" opacity="0.7"/><rect x="20" y="100" width="140" height="10" fill="#fff" opacity="0.7"/><rect x="20" y="125" width="80" height="30" rx="5" fill="#fff"/></svg>';
    }
    
    private function get_hero_gradient_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#fa709a;stop-opacity:1" /><stop offset="100%" style="stop-color:#fee140;stop-opacity:1" /></linearGradient></defs><rect width="300" height="200" fill="url(#grad1)"/><rect x="75" y="60" width="150" height="20" fill="#fff" opacity="0.9"/><rect x="75" y="90" width="150" height="12" fill="#fff" opacity="0.7"/><rect x="110" y="120" width="80" height="35" rx="5" fill="#fff"/></svg>';
    }
    
    private function get_features_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f8f9fa"/><rect x="10" y="20" width="85" height="160" rx="5" fill="#fff" stroke="#e6e9ec"/><circle cx="52" cy="60" r="15" fill="#92003b" opacity="0.2"/><rect x="25" y="90" width="54" height="8" fill="#333" opacity="0.3"/><rect x="107" y="20" width="85" height="160" rx="5" fill="#fff" stroke="#e6e9ec"/><circle cx="149" cy="60" r="15" fill="#92003b" opacity="0.2"/><rect x="122" y="90" width="54" height="8" fill="#333" opacity="0.3"/><rect x="204" y="20" width="85" height="160" rx="5" fill="#fff" stroke="#e6e9ec"/><circle cx="246" cy="60" r="15" fill="#92003b" opacity="0.2"/><rect x="219" y="90" width="54" height="8" fill="#333" opacity="0.3"/></svg>';
    }
    
    private function get_pricing_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f8f9fa"/><rect x="10" y="20" width="85" height="160" rx="5" fill="#fff" stroke="#e6e9ec"/><text x="52" y="50" text-anchor="middle" font-size="20" font-weight="bold" fill="#92003b">$9</text><rect x="107" y="10" width="85" height="180" rx="5" fill="#92003b"/><text x="149" y="50" text-anchor="middle" font-size="20" font-weight="bold" fill="#fff">$29</text><rect x="204" y="20" width="85" height="160" rx="5" fill="#fff" stroke="#e6e9ec"/><text x="246" y="50" text-anchor="middle" font-size="20" font-weight="bold" fill="#92003b">$99</text></svg>';
    }
    
    private function get_team_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#fff"/><circle cx="40" cy="60" r="25" fill="#92003b" opacity="0.2"/><rect x="20" y="95" width="40" height="6" fill="#333" opacity="0.3"/><circle cx="110" cy="60" r="25" fill="#92003b" opacity="0.2"/><rect x="90" y="95" width="40" height="6" fill="#333" opacity="0.3"/><circle cx="180" cy="60" r="25" fill="#92003b" opacity="0.2"/><rect x="160" y="95" width="40" height="6" fill="#333" opacity="0.3"/><circle cx="250" cy="60" r="25" fill="#92003b" opacity="0.2"/><rect x="230" y="95" width="40" height="6" fill="#333" opacity="0.3"/></svg>';
    }
    
    private function get_testimonials_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#fff"/><rect x="50" y="40" width="200" height="120" rx="10" fill="#f8f9fa" stroke="#e6e9ec"/><circle cx="150" cy="70" r="20" fill="#92003b" opacity="0.2"/><rect x="80" y="100" width="140" height="6" fill="#333" opacity="0.2"/><rect x="90" y="115" width="120" height="6" fill="#333" opacity="0.2"/><text x="150" y="145" text-anchor="middle" font-size="10" fill="#92003b">★★★★★</text></svg>';
    }
    
    private function get_cta_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#92003b"/><rect x="50" y="60" width="200" height="15" fill="#fff" opacity="0.9"/><rect x="75" y="85" width="150" height="10" fill="#fff" opacity="0.7"/><rect x="110" y="110" width="80" height="30" rx="5" fill="#fff"/></svg>';
    }
    
    private function get_gallery_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f8f9fa"/><rect x="10" y="10" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="82" y="10" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="154" y="10" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="226" y="10" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="10" y="105" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="82" y="105" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="154" y="105" width="65" height="85" fill="#92003b" opacity="0.3"/><rect x="226" y="105" width="65" height="85" fill="#92003b" opacity="0.3"/></svg>';
    }
    
    private function get_stats_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#667eea;stop-opacity:1" /><stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" /></linearGradient></defs><rect width="300" height="200" fill="url(#grad2)"/><text x="40" y="80" text-anchor="middle" font-size="30" font-weight="bold" fill="#fff">500+</text><text x="110" y="80" text-anchor="middle" font-size="30" font-weight="bold" fill="#fff">24/7</text><text x="190" y="80" text-anchor="middle" font-size="30" font-weight="bold" fill="#fff">98%</text><text x="260" y="80" text-anchor="middle" font-size="30" font-weight="bold" fill="#fff">5K+</text></svg>';
    }
    
    private function get_services_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#fff"/><rect x="15" y="30" width="80" height="140" rx="8" fill="#f8f9fa" stroke="#e6e9ec"/><circle cx="55" cy="70" r="18" fill="#92003b" opacity="0.2"/><rect x="25" y="100" width="60" height="6" fill="#333" opacity="0.3"/><rect x="110" y="30" width="80" height="140" rx="8" fill="#f8f9fa" stroke="#e6e9ec"/><circle cx="150" cy="70" r="18" fill="#92003b" opacity="0.2"/><rect x="120" y="100" width="60" height="6" fill="#333" opacity="0.3"/><rect x="205" y="30" width="80" height="140" rx="8" fill="#f8f9fa" stroke="#e6e9ec"/><circle cx="245" cy="70" r="18" fill="#92003b" opacity="0.2"/><rect x="215" y="100" width="60" height="6" fill="#333" opacity="0.3"/></svg>';
    }
    
    private function get_contact_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f8f9fa"/><rect x="15" y="30" width="120" height="140" rx="5" fill="#fff" stroke="#e6e9ec"/><rect x="25" y="45" width="100" height="8" fill="#333" opacity="0.2"/><rect x="25" y="63" width="100" height="25" fill="#f8f9fa" stroke="#d4d4d8"/><rect x="165" y="30" width="120" height="140" rx="5" fill="#fff" stroke="#e6e9ec"/><rect x="175" y="45" width="100" height="8" fill="#333" opacity="0.2"/><rect x="175" y="63" width="100" height="8" fill="#333" opacity="0.1"/><rect x="175" y="78" width="100" height="8" fill="#333" opacity="0.1"/></svg>';
    }
    
    private function get_newsletter_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#fff"/><rect x="30" y="60" width="240" height="80" rx="8" fill="#f8f9fa" stroke="#92003b" stroke-width="2"/><rect x="45" y="80" width="130" height="8" fill="#333" opacity="0.3"/><rect x="45" y="95" width="150" height="25" rx="3" fill="#fff" stroke="#d4d4d8"/><rect x="205" y="95" width="60" height="25" rx="3" fill="#92003b"/></svg>';
    }
    
    private function get_full_page_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="pageGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#92003b;stop-opacity:1" /><stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" /></linearGradient></defs><rect width="300" height="200" fill="url(#pageGrad)"/><rect x="10" y="10" width="280" height="180" rx="5" fill="#fff" opacity="0.1"/><rect x="20" y="20" width="260" height="30" fill="#fff" opacity="0.3"/><rect x="20" y="60" width="85" height="60" rx="3" fill="#fff" opacity="0.2"/><rect x="108" y="60" width="85" height="60" rx="3" fill="#fff" opacity="0.2"/><rect x="196" y="60" width="85" height="60" rx="3" fill="#fff" opacity="0.2"/><rect x="20" y="130" width="260" height="40" rx="3" fill="#fff" opacity="0.2"/><text x="150" y="35" text-anchor="middle" font-size="12" font-weight="bold" fill="#fff">COMPLETE PAGE</text></svg>';
    }
    
    /**
     * E-Commerce Full Page Template Methods
     */
    
    private function get_ecommerce_shop_page() {
        return $this->build_ecommerce_shop_page();
    }
    
    private function get_ecommerce_product_page() {
        return $this->build_ecommerce_product_page();
    }
    
    private function get_ecommerce_homepage() {
        return $this->build_ecommerce_homepage();
    }
    
    private function get_fashion_store_page() {
        return $this->build_fashion_store_page();
    }
    
    private function get_electronics_store_page() {
        return $this->build_electronics_store_page();
    }
    
    private function build_business_page() {
        $elements = [];
        
        // Hero Section
        $elements[] = [
            'id' => 'bus-hero-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40']
            ],
            'children' => [
                [
                    'id' => 'bus-hero-heading-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Grow Your Business With Us',
                        'html_tag' => 'h1',
                        'color' => '#ffffff',
                        'font_size' => '48',
                        'font_weight' => '700'
                    ]
                ],
                [
                    'id' => 'bus-hero-image-' . uniqid(),
                    'widgetType' => 'image',
                    'settings' => [
                        'image' => ['url' => 'https://via.placeholder.com/600x400/667eea/ffffff?text=Business+Hero'],
                        'align' => 'center'
                    ]
                ]
            ]
        ];
        
        // Features
        $elements[] = [
            'id' => 'bus-features-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Our Core Features',
                'html_tag' => 'h2',
                'color' => '#1e293b',
                'font_size' => '36',
                'align' => 'center',
                'margin' => ['top' => '60', 'bottom' => '40']
            ]
        ];
        
        $elements[] = [
            'id' => 'bus-features-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_color' => '#ffffff',
                'column_gap' => '30',
                'padding' => ['top' => '20', 'right' => '40', 'bottom' => '60', 'left' => '40']
            ],
            'children' => [
                [
                    'id' => 'bus-icon-1-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa fa-rocket',
                        'title' => 'Fast Performance',
                        'description' => 'Lightning-fast loading for better UX'
                    ]
                ],
                [
                    'id' => 'bus-icon-2-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa fa-shield',
                        'title' => 'Secure & Reliable',
                        'description' => 'Enterprise-grade security'
                    ]
                ],
                [
                    'id' => 'bus-icon-3-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa fa-headset',
                        'title' => '24/7 Support',
                        'description' => 'Always here to help you'
                    ]
                ]
            ]
        ];
        
        // CTA
        $elements[] = [
            'id' => 'bus-cta-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(to right, #fa709a 0%, #fee140 100%)',
                'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40']
            ],
            'children' => [
                [
                    'id' => 'bus-cta-heading-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Ready to Get Started?',
                        'html_tag' => 'h2',
                        'color' => '#ffffff',
                        'font_size' => '42',
                        'align' => 'center'
                    ]
                ],
                [
                    'id' => 'bus-cta-btn-' . uniqid(),
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => 'Start Free Trial',
                        'bg_color' => '#ffffff',
                        'text_color' => '#fa709a',
                        'align' => 'center'
                    ]
                ]
            ]
        ];
        
        return $elements;
    }
    
    private function build_agency_page() {
        return [
            [
                'id' => 'agn-hero-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(to right, #4facfe 0%, #00f2fe 100%)',
                    'padding' => ['top' => '100', 'right' => '40', 'bottom' => '100', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'agn-title-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Creative Digital Agency',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => '56',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => 'agn-subtitle-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'We craft digital experiences that matter',
                            'html_tag' => 'h3',
                            'color' => '#ffffff',
                            'font_size' => '24',
                            'align' => 'center',
                            'font_weight' => '300'
                        ]
                    ]
                ]
            ],
            
            [
                'id' => 'agn-services-title-' . uniqid(),
                'widgetType' => 'heading',
                'settings' => [
                    'title' => 'What We Do',
                    'html_tag' => 'h2',
                    'color' => '#1e293b',
                    'font_size' => '40',
                    'align' => 'center',
                    'margin' => ['top' => '60', 'bottom' => '40']
                ]
            ],
            
            [
                'id' => 'agn-services-grid-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '4',
                    'background_color' => '#ffffff',
                    'column_gap' => '25',
                    'padding' => ['top' => '20', 'right' => '40', 'bottom' => '80', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'agn-service-1-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-paint-brush',
                            'title' => 'Design',
                            'description' => 'Beautiful designs'
                        ]
                    ],
                    [
                        'id' => 'agn-service-2-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-code',
                            'title' => 'Development',
                            'description' => 'Clean code'
                        ]
                    ],
                    [
                        'id' => 'agn-service-3-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-bullhorn',
                            'title' => 'Marketing',
                            'description' => 'Smart strategies'
                        ]
                    ],
                    [
                        'id' => 'agn-service-4-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-chart-line',
                            'title' => 'Analytics',
                            'description' => 'Track growth'
                        ]
                    ]
                ]
            ]
        ];
    }
    
    private function build_portfolio_page() {
        return [
            [
                'id' => 'port-hero-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'background_color' => '#0f172a',
                    'padding' => ['top' => '120', 'right' => '40', 'bottom' => '120', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'port-title-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Portfolio Showcase',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => '64',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => 'port-subtitle-' . uniqid(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Selected works from 2020-2025',
                            'text_color' => '#94a3b8',
                            'font_size' => '20',
                            'align' => 'center'
                        ]
                    ]
                ]
            ],
            
            [
                'id' => 'port-grid-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '3',
                    'background_color' => '#ffffff',
                    'column_gap' => '30',
                    'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'port-img-1-' . uniqid(),
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => ['url' => 'https://via.placeholder.com/600x400/92003b/ffffff?text=Project+1']
                        ]
                    ],
                    [
                        'id' => 'port-img-2-' . uniqid(),
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => ['url' => 'https://via.placeholder.com/600x400/667eea/ffffff?text=Project+2']
                        ]
                    ],
                    [
                        'id' => 'port-img-3-' . uniqid(),
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => ['url' => 'https://via.placeholder.com/600x400/4facfe/ffffff?text=Project+3']
                        ]
                    ]
                ]
            ],
            
            [
                'id' => 'port-cta-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'background_color' => '#92003b',
                    'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'port-cta-heading-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Let\'s Create Something Amazing',
                            'html_tag' => 'h2',
                            'color' => '#ffffff',
                            'font_size' => '40',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => 'port-cta-btn-' . uniqid(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Get in Touch',
                            'bg_color' => '#ffffff',
                            'text_color' => '#92003b',
                            'align' => 'center'
                        ]
                    ]
                ]
            ]
        ];
    }
    
    private function build_product_page() {
        return [
            [
                'id' => 'prod-hero-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '2',
                    'background_color' => '#f8f9fa',
                    'padding' => ['top' => '80', 'right' => '60', 'bottom' => '80', 'left' => '60']
                ],
                'children' => [
                    [
                        'id' => 'prod-img-' . uniqid(),
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => ['url' => 'https://via.placeholder.com/800x600/92003b/ffffff?text=Product']
                        ]
                    ],
                    [
                        'id' => 'prod-title-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Amazing Product',
                            'html_tag' => 'h1',
                            'color' => '#1e293b',
                            'font_size' => '48'
                        ]
                    ]
                ]
            ],
            
            [
                'id' => 'prod-features-title-' . uniqid(),
                'widgetType' => 'heading',
                'settings' => [
                    'title' => 'Key Features',
                    'html_tag' => 'h2',
                    'color' => '#1e293b',
                    'font_size' => '36',
                    'align' => 'center',
                    'margin' => ['top' => '60', 'bottom' => '40']
                ]
            ],
            
            [
                'id' => 'prod-features-grid-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '3',
                    'column_gap' => '30',
                    'padding' => ['top' => '20', 'right' => '40', 'bottom' => '80', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'prod-feat-1-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-bolt',
                            'title' => 'Lightning Fast',
                            'description' => 'Optimized for speed'
                        ]
                    ],
                    [
                        'id' => 'prod-feat-2-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-mobile',
                            'title' => 'Mobile Ready',
                            'description' => 'Works on all devices'
                        ]
                    ],
                    [
                        'id' => 'prod-feat-3-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-lock',
                            'title' => 'Secure',
                            'description' => 'Built with security'
                        ]
                    ]
                ]
            ],
            
            [
                'id' => 'prod-cta-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'prod-cta-heading-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Get Yours Today',
                            'html_tag' => 'h2',
                            'color' => '#ffffff',
                            'font_size' => '42',
                            'align' => 'center'
                        ]
                    ],
                    [
                        'id' => 'prod-cta-btn-' . uniqid(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Order Now - $99.99',
                            'bg_color' => '#ffffff',
                            'text_color' => '#667eea',
                            'align' => 'center'
                        ]
                    ]
                ]
            ]
        ];
    }
    
    private function build_services_page() {
        return [
            [
                'id' => 'serv-hero-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '1',
                    'background_type' => 'gradient',
                    'background_gradient' => 'linear-gradient(to bottom, #667eea 0%, #764ba2 100%)',
                    'padding' => ['top' => '100', 'right' => '40', 'bottom' => '100', 'left' => '40']
                ],
                'children' => [
                    [
                        'id' => 'serv-title-' . uniqid(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Our Services',
                            'html_tag' => 'h1',
                            'color' => '#ffffff',
                            'font_size' => '56',
                            'align' => 'center'
                        ]
                    ]
                ]
            ],
            
            [
                'id' => 'serv-grid-' . uniqid(),
                'widgetType' => 'container',
                'settings' => [
                    'columns' => '2',
                    'background_color' => '#ffffff',
                    'column_gap' => '40',
                    'padding' => ['top' => '80', 'right' => '60', 'bottom' => '80', 'left' => '60']
                ],
                'children' => [
                    [
                        'id' => 'serv-1-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-laptop-code',
                            'title' => 'Web Development',
                            'description' => 'Custom websites and applications'
                        ]
                    ],
                    [
                        'id' => 'serv-2-' . uniqid(),
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'icon' => 'fa fa-mobile-alt',
                            'title' => 'Mobile Apps',
                            'description' => 'iOS and Android development'
                        ]
                    ]
                ]
            ]
        ];
    }
    
    /**
     * E-Commerce Template Thumbnails
     */
    
    private function get_ecommerce_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect fill="#f8f9fa" width="300" height="200"/><rect x="0" y="0" width="300" height="50" fill="#1a1a1a"/><text x="20" y="32" font-size="14" fill="#fff" font-weight="bold">SHOP</text><rect x="220" y="15" width="70" height="20" rx="3" fill="#92003b"/><rect x="10" y="60" width="85" height="120" rx="4" fill="#fff"/><rect x="10" y="145" width="85" height="25" fill="#e5e7eb"/><rect x="107" y="60" width="85" height="120" rx="4" fill="#fff"/><rect x="107" y="145" width="85" height="25" fill="#e5e7eb"/><rect x="204" y="60" width="85" height="120" rx="4" fill="#fff"/><rect x="204" y="145" width="85" height="25" fill="#e5e7eb"/></svg>';
    }
    
    private function get_product_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect fill="#ffffff" width="300" height="200"/><rect x="10" y="10" width="130" height="180" rx="8" fill="#f3f4f6"/><rect x="150" y="10" width="140" height="30" fill="#1f2937"/><rect x="150" y="50" width="100" height="15" fill="#e5e7eb"/><rect x="150" y="75" width="120" height="20" fill="#fbbf24"/><rect x="150" y="110" width="140" height="40" rx="4" fill="#92003b"/><rect x="150" y="160" width="70" height="25" fill="#10b981"/></svg>';
    }
    
    private function get_homepage_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="heroGrad"><stop offset="0%" stop-color="#92003b"/><stop offset="100%" stop-color="#667eea"/></linearGradient></defs><rect fill="#fff" width="300" height="200"/><rect y="0" width="300" height="80" fill="url(#heroGrad)"/><text x="150" y="45" text-anchor="middle" font-size="16" fill="#fff" font-weight="bold">MEGA SALE</text><rect x="10" y="90" width="85" height="100" rx="4" fill="#f3f4f6"/><rect x="107" y="90" width="85" height="100" rx="4" fill="#f3f4f6"/><rect x="204" y="90" width="85" height="100" rx="4" fill="#f3f4f6"/></svg>';
    }
    
    private function get_fashion_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect fill="#fef2f2" width="300" height="200"/><rect x="10" y="10" width="130" height="180" rx="8" fill="#fff"/><circle cx="75" cy="100" r="40" fill="#fca5a5"/><rect x="150" y="20" width="140" height="50" fill="#1f2937"/><rect x="150" y="80" width="80" height="100" rx="4" fill="#fff"/><rect x="238" y="80" width="52" height="100" rx="4" fill="#fff"/></svg>';
    }
    
    private function get_electronics_thumbnail() {
        return '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect fill="#eff6ff" width="300" height="200"/><rect x="0" y="0" width="300" height="40" fill="#1e40af"/><text x="20" y="25" font-size="12" fill="#fff" font-weight="bold">TECH STORE</text><rect x="10" y="50" width="135" height="140" rx="8" fill="#dbeafe"/><rect x="20" y="60" width="115" height="80" rx="4" fill="#3b82f6"/><rect x="155" y="50" width="135" height="140" rx="8" fill="#dbeafe"/><rect x="165" y="60" width="115" height="80" rx="4" fill="#3b82f6"/></svg>';
    }
    
    /**
     * Build E-Commerce Shop Page - THEMEFOREST LEVEL
     */
    private function build_ecommerce_shop_page() {
        $elements = [];
        
        // 1. PREMIUM TOP BAR - Sleek & Modern
        $elements[] = [
            'id' => 'topbar-premium-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(90deg, #1a1a1a 0%, #2d2d2d 100%)',
                'padding' => ['top' => 12, 'right' => 80, 'bottom' => 12, 'left' => 80]
            ],
            'children' => [
                [
                    'id' => 'topbar-promo-' . uniqid(),
                    'widgetType' => 'animated-text',
                    'settings' => [
                        'text' => '✨ FLASH SALE: 70% OFF + FREE Shipping',
                        'animation' => 'slide',
                        'color' => '#FFD700',
                        'size' => 13,
                        'font_weight' => '600'
                    ]
                ],
                [
                    'id' => 'topbar-center-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '🚚 Free Shipping Worldwide | 💳 Secure Checkout | ↩️ Easy Returns',
                        'color' => '#ffffff',
                        'font_size' => 12,
                        'align' => 'center'
                    ]
                ],
                [
                    'id' => 'topbar-lang-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '🌐 EN | USD $',
                        'color' => '#ffffff',
                        'font_size' => 12,
                        'align' => 'right'
                    ]
                ]
            ]
        ];
        
        // 2. THEMEFOREST-STYLE HEADER - Modern & Clean
        $elements[] = [
            'id' => 'header-main-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_color' => '#ffffff',
                'padding' => ['top' => 30, 'right' => 80, 'bottom' => 30, 'left' => 80],
                'box_shadow' => '0 4px 20px rgba(0,0,0,0.08)',
                'position' => 'sticky',
                'top' => 0,
                'z_index' => 1000
            ],
            'children' => [
                [
                    'id' => 'logo-container-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        [
                            'id' => 'brand-logo-' . uniqid(),
                            'widgetType' => 'heading',
                            'settings' => [
                                'title' => 'ELITÉ',
                                'html_tag' => 'h1',
                                'color' => '#1a1a1a',
                                'font_size' => 32,
                                'font_weight' => '800',
                                'letter_spacing' => '2px'
                            ]
                        ]
                    ]
                ],
                [
                    'id' => 'nav-container-' . uniqid(),
                    'widgetType' => 'menu',
                    'settings' => [
                        'layout' => 'horizontal',
                        'align' => 'center',
                        'font_size' => 15,
                        'font_weight' => '500',
                        'spacing' => 30
                    ]
                ],
                [
                    'id' => 'header-actions-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '3', 'column_gap' => 20],
                    'children' => [
                        [
                            'id' => 'search-icon-' . uniqid(),
                            'widgetType' => 'icon',
                            'settings' => [
                                'icon' => 'fa-search',
                                'size' => 20,
                                'color' => '#1a1a1a'
                            ]
                        ],
                        [
                            'id' => 'user-icon-' . uniqid(),
                            'widgetType' => 'icon',
                            'settings' => [
                                'icon' => 'fa-user',
                                'size' => 20,
                                'color' => '#1a1a1a'
                            ]
                        ],
                        [
                            'id' => 'cart-widget-' . uniqid(),
                            'widgetType' => 'woo-cart',
                            'settings' => [
                                'show_total' => true,
                                'show_count' => true,
                                'icon_size' => 22
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        // 3. THEMEFOREST-LEVEL HERO - Full Screen, Multi-Layer
        // Background Layer: Full-width parallax
        $elements[] = [
            'id' => 'hero-bg-' . uniqid(),
            'widgetType' => 'parallax-image',
            'settings' => [
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=1080',
                'height' => 900,
                'speed' => 0.3
            ]
        ];
        
        // Overlay Layer with Gradient
        $elements[] = [
            'id' => 'hero-overlay-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, rgba(99,102,241,0.9) 0%, rgba(168,85,247,0.9) 50%, rgba(236,72,153,0.9) 100%)',
                'padding' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],
                'margin' => ['top' => -900, 'right' => 0, 'bottom' => 0, 'left' => 0],
                'min_height' => 900
            ]
        ];
        
        // Hero Content - Split Layout
        $elements[] = [
            'id' => 'hero-content-main-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 80,
                'padding' => ['top' => 180, 'right' => 100, 'bottom' => 100, 'left' => 100],
                'margin' => ['top' => -900, 'right' => 0, 'bottom' => 0, 'left' => 0],
                'align_items' => 'center'
            ],
            'children' => [
                [
                    'id' => 'hero-left-content-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1', 'padding' => ['top' => 0, 'right' => 40, 'bottom' => 0, 'left' => 0]],
                    'children' => [
                        [
                            'id' => 'hero-season-badge-' . uniqid(),
                            'widgetType' => 'container',
                            'settings' => [
                                'columns' => '1',
                                'background_type' => 'gradient',
                                'background_gradient' => 'linear-gradient(135deg, #FFD700 0%, #FFA500 100%)',
                                'padding' => ['top' => 10, 'right' => 25, 'bottom' => 10, 'left' => 25],
                                'border_radius' => 50,
                                'display' => 'inline-block',
                                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 30, 'left' => 0]
                            ],
                            'children' => [
                                [
                                    'id' => 'badge-text-' . uniqid(),
                                    'widgetType' => 'text',
                                    'settings' => [
                                        'content' => '⭐ SUMMER 2025 COLLECTION',
                                        'color' => '#000000',
                                        'font_size' => 13,
                                        'font_weight' => '700',
                                        'letter_spacing' => '1px'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => 'hero-mega-title-' . uniqid(),
                            'widgetType' => 'heading',
                            'settings' => [
                                'title' => 'Redefine Your Style',
                                'html_tag' => 'h1',
                                'color' => '#ffffff',
                                'font_size' => 82,
                                'font_weight' => '900',
                                'line_height' => '1.1',
                                'text_shadow' => '0 4px 20px rgba(0,0,0,0.3)',
                                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 25, 'left' => 0]
                            ]
                        ],
                        [
                            'id' => 'hero-gradient-text-' . uniqid(),
                            'widgetType' => 'heading',
                            'settings' => [
                                'title' => 'Premium Fashion',
                                'html_tag' => 'h2',
                                'color' => 'transparent',
                                'background_clip' => 'text',
                                'background_type' => 'gradient',
                                'background_gradient' => 'linear-gradient(135deg, #FFD700 0%, #FF6B9D 100%)',
                                'font_size' => 72,
                                'font_weight' => '900',
                                'line_height' => '1.1',
                                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 35, 'left' => 0]
                            ]
                        ],
                        [
                            'id' => 'hero-tagline-' . uniqid(),
                            'widgetType' => 'text',
                            'settings' => [
                                'content' => 'Discover exclusive designer collections with up to 70% OFF. Limited edition pieces, premium quality, worldwide shipping.',
                                'color' => 'rgba(255,255,255,0.95)',
                                'font_size' => 20,
                                'line_height' => '1.7',
                                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                            ]
                        ],
                        [
                            'id' => 'hero-cta-group-' . uniqid(),
                            'widgetType' => 'container',
                            'settings' => ['columns' => '2', 'column_gap' => 20, 'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]],
                            'children' => [
                                [
                                    'id' => 'hero-btn-primary-' . uniqid(),
                                    'widgetType' => 'button',
                                    'settings' => [
                                        'text' => 'Shop Collection',
                                        'bg_color' => '#ffffff',
                                        'text_color' => '#1a1a1a',
                                        'size' => 'xlarge',
                                        'border_radius' => 8,
                                        'padding' => ['top' => 22, 'right' => 50, 'bottom' => 22, 'left' => 50],
                                        'font_weight' => '700',
                                        'box_shadow' => '0 10px 40px rgba(0,0,0,0.3)'
                                    ]
                                ],
                                [
                                    'id' => 'hero-btn-secondary-' . uniqid(),
                                    'widgetType' => 'button',
                                    'settings' => [
                                        'text' => 'Explore Trends →',
                                        'bg_color' => 'transparent',
                                        'text_color' => '#ffffff',
                                        'border_color' => '#ffffff',
                                        'border_width' => 2,
                                        'size' => 'xlarge',
                                        'border_radius' => 8,
                                        'padding' => ['top' => 20, 'right' => 48, 'bottom' => 20, 'left' => 48],
                                        'font_weight' => '600'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => 'hero-stats-' . uniqid(),
                            'widgetType' => 'container',
                            'settings' => ['columns' => '3', 'column_gap' => 40],
                            'children' => [
                                [
                                    'id' => 'stat-1-' . uniqid(),
                                    'widgetType' => 'text',
                                    'settings' => [
                                        'content' => '<strong style="font-size:28px;display:block;">50K+</strong><span style="font-size:14px;opacity:0.9;">Happy Customers</span>',
                                        'color' => '#ffffff'
                                    ]
                                ],
                                [
                                    'id' => 'stat-2-' . uniqid(),
                                    'widgetType' => 'text',
                                    'settings' => [
                                        'content' => '<strong style="font-size:28px;display:block;">100K+</strong><span style="font-size:14px;opacity:0.9;">Products Sold</span>',
                                        'color' => '#ffffff'
                                    ]
                                ],
                                [
                                    'id' => 'stat-3-' . uniqid(),
                                    'widgetType' => 'text',
                                    'settings' => [
                                        'content' => '<strong style="font-size:28px;display:block;">4.9★</strong><span style="font-size:14px;opacity:0.9;">Average Rating</span>',
                                        'color' => '#ffffff'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    'id' => 'hero-right-visuals-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1', 'position' => 'relative'],
                    'children' => [
                        [
                            'id' => 'hero-main-product-' . uniqid(),
                            'widgetType' => 'image',
                            'settings' => [
                                'url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&h=1000',
                                'border_radius' => 20,
                                'box_shadow' => '0 30px 80px rgba(0,0,0,0.4)',
                                'transform' => 'rotate(-3deg)'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        // Scroll Indicator
        $elements[] = [
            'id' => 'scroll-indicator-' . uniqid(),
            'widgetType' => 'text',
            'settings' => [
                'content' => '↓ Scroll to Explore',
                'color' => '#ffffff',
                'font_size' => 14,
                'align' => 'center',
                'margin' => ['top' => -80, 'right' => 0, 'bottom' => 0, 'left' => 0],
                'animation' => 'bounce'
            ]
        ];
        
        // 4. THEMEFOREST CATEGORY SHOWCASE - Magazine Style with Overlapping Card
        // Category Section Title (Overlapping from hero)
        $elements[] = [
            'id' => 'cat-intro-card-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#ffffff',
                'padding' => ['top' => 60, 'right' => 80, 'bottom' => 60, 'left' => 80],
                'margin' => ['top' => -150, 'right' => 100, 'bottom' => 0, 'left' => 100],
                'border_radius' => 20,
                'box_shadow' => '0 20px 60px rgba(0,0,0,0.15)',
                'z_index' => 10
            ],
            'children' => [
                [
                    'id' => 'cat-pretitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '— EXPLORE OUR COLLECTIONS',
                        'color' => '#6366f1',
                        'font_size' => 14,
                        'font_weight' => '700',
                        'letter_spacing' => '2px',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'cat-main-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Shop By Category',
                        'html_tag' => 'h2',
                        'color' => '#1a1a1a',
                        'font_size' => 52,
                        'font_weight' => '800',
                        'align' => 'center',
                        'line_height' => '1.2',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'cat-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Curated collections for every style and occasion',
                        'color' => '#64748b',
                        'font_size' => 18,
                        'align' => 'center',
                        'line_height' => '1.6'
                    ]
                ]
            ]
        ];
        
        // Category Grid - ThemeForest Style
        $elements[] = [
            'id' => 'cat-grid-container-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 100, 'right' => 80, 'bottom' => 100, 'left' => 80],
                'margin' => ['top' => -60, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ]
        ];
        
        // ASYMMETRIC Category Grid (ThemeForest Magazine Style)
        $elements[] = [
            'id' => 'cat-row-1-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 80, 'bottom' => 30, 'left' => 80],
                'background_color' => '#f8f9fa'
            ],
            'children' => [
                [
                    'id' => 'cat-large-1-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800&h=600',
                        'title' => 'WOMEN\'S FASHION',
                        'description' => 'Elegant & Timeless',
                        'button_text' => 'Shop Women',
                        'title_size' => 32,
                        'overlay_color' => 'rgba(0,0,0,0.3)',
                        'hover_effect' => 'zoom'
                    ]
                ],
                [
                    'id' => 'cat-column-right-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1', 'row_gap' => 30],
                    'children' => [
                        [
                            'id' => 'cat-small-1-' . uniqid(),
                            'widgetType' => 'image-box',
                            'settings' => [
                                'image_url' => 'https://images.unsplash.com/photo-1488161628813-04466f872be2?w=600&h=400',
                                'title' => 'ACCESSORIES',
                                'description' => 'Complete Your Look',
                                'title_size' => 24,
                                'height' => 280
                            ]
                        ],
                        [
                            'id' => 'cat-small-2-' . uniqid(),
                            'widgetType' => 'image-box',
                            'settings' => [
                                'image_url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&h=400',
                                'title' => 'SHOES',
                                'description' => 'Step In Style',
                                'title_size' => 24,
                                'height' => 280
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'cat-row-2-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 80, 'bottom' => 0, 'left' => 80],
                'background_color' => '#f8f9fa'
            ],
            'children' => [
                [
                    'id' => 'cat-medium-1-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1516762689617-e1cffcef479d?w=600&h=500',
                        'title' => 'MEN\'S FASHION',
                        'description' => 'Modern & Sharp',
                        'title_size' => 28,
                        'height' => 400
                    ]
                ],
                [
                    'id' => 'cat-medium-2-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=600&h=500',
                        'title' => 'BAGS & LUGGAGE',
                        'description' => 'Travel Ready',
                        'title_size' => 28,
                        'height' => 400
                    ]
                ],
                [
                    'id' => 'cat-medium-3-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1509319117829-c08a3630f3e9?w=600&h=500',
                        'title' => 'JEWELRY',
                        'description' => 'Shine Bright',
                        'title_size' => 28,
                        'height' => 400
                    ]
                ]
            ]
        ];
        
        // 5. FLASH SALE / Special Offers Section
        $elements[] = [
            'id' => 'flash-container-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%)',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 80, 'left' => 60],
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 60, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'flash-badge-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '🔥 FLASH SALE',
                        'color' => '#ffffff',
                        'font_size' => 18,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'flash-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Limited Time Offers - Up to 70% OFF',
                        'html_tag' => 'h2',
                        'color' => '#ffffff',
                        'font_size' => 48,
                        'font_weight' => '800',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'flash-countdown-' . uniqid(),
                    'widgetType' => 'countdown',
                    'settings' => [
                        'date' => date('Y-m-d', strtotime('+7 days')),
                        'style' => 'circle',
                        'size' => 'large',
                        'color' => '#ffffff'
                    ]
                ],
                [
                    'id' => 'flash-btn-' . uniqid(),
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => 'Shop Flash Sale →',
                        'bg_color' => '#ffffff',
                        'text_color' => '#FF6B6B',
                        'size' => 'large',
                        'align' => 'center',
                        'border_radius' => 50,
                        'margin' => ['top' => 30, 'right' => 0, 'bottom' => 0, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        // 6. FEATURED PRODUCTS - Large Grid
        $elements[] = [
            'id' => 'featured-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 40, 'left' => 60]
            ],
            'children' => [
                [
                    'id' => 'featured-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => '⭐ Featured Products',
                        'html_tag' => 'h2',
                        'color' => '#1a1a1a',
                        'font_size' => 42,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'featured-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Handpicked collection of our best-selling products',
                        'color' => '#64748b',
                        'font_size' => 18,
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        // Featured Products Grid - 12 products
        $elements[] = [
            'id' => 'woo-featured-' . uniqid(),
            'widgetType' => 'woo-products',
            'settings' => [
                'query_type' => 'featured',
                'columns' => 4,
                'products_per_page' => 12,
                'show_image' => true,
                'show_price' => true,
                'show_cart_button' => true
            ]
        ];
        
        // 7. NEW ARRIVALS Section
        $elements[] = [
            'id' => 'new-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#ffffff',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 40, 'left' => 60],
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'new-badge-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '✨ NEW',
                        'color' => '#22c55e',
                        'font_size' => 16,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 10, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'new-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Just Arrived',
                        'html_tag' => 'h2',
                        'color' => '#1a1a1a',
                        'font_size' => 42,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'new-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Fresh styles, hot trends, new possibilities',
                        'color' => '#64748b',
                        'font_size' => 18,
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'woo-new-' . uniqid(),
            'widgetType' => 'woo-products',
            'settings' => [
                'query_type' => 'recent',
                'columns' => 4,
                'products_per_page' => 8,
                'show_image' => true,
                'show_price' => true,
                'show_cart_button' => true
            ]
        ];
        
        // 8. BEST SELLERS Section
        $elements[] = [
            'id' => 'best-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 40, 'left' => 60],
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'best-badge-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '🏆 BEST SELLERS',
                        'color' => '#f59e0b',
                        'font_size' => 16,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 10, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'best-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Customer Favorites',
                        'html_tag' => 'h2',
                        'color' => '#1a1a1a',
                        'font_size' => 42,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'best-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'The products everyone is talking about',
                        'color' => '#64748b',
                        'font_size' => 18,
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'woo-best-' . uniqid(),
            'widgetType' => 'woo-products',
            'settings' => [
                'query_type' => 'best_selling',
                'columns' => 4,
                'products_per_page' => 8,
                'show_image' => true,
                'show_price' => true,
                'show_cart_button' => true
            ]
        ];
        
        // 9. STATS / ACHIEVEMENTS Section
        $elements[] = [
            'id' => 'stats-container-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 80, 'left' => 60],
                'margin' => ['top' => 80, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'stats-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Trusted by Thousands',
                        'html_tag' => 'h2',
                        'color' => '#ffffff',
                        'font_size' => 42,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 60, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'stats-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '4',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'padding' => ['top' => 0, 'right' => 60, 'bottom' => 80, 'left' => 60],
                'margin' => ['top' => -80, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'counter-1-' . uniqid(),
                    'widgetType' => 'counter',
                    'settings' => [
                        'end_value' => 50000,
                        'title' => 'Happy Customers',
                        'icon' => 'fa-users',
                        'color' => '#ffffff'
                    ]
                ],
                [
                    'id' => 'counter-2-' . uniqid(),
                    'widgetType' => 'counter',
                    'settings' => [
                        'end_value' => 10000,
                        'title' => 'Products Sold',
                        'icon' => 'fa-shopping-bag',
                        'color' => '#ffffff'
                    ]
                ],
                [
                    'id' => 'counter-3-' . uniqid(),
                    'widgetType' => 'counter',
                    'settings' => [
                        'end_value' => 15,
                        'title' => 'Years Experience',
                        'icon' => 'fa-award',
                        'color' => '#ffffff'
                    ]
                ],
                [
                    'id' => 'counter-4-' . uniqid(),
                    'widgetType' => 'counter',
                    'settings' => [
                        'end_value' => 99,
                        'suffix' => '%',
                        'title' => 'Satisfaction Rate',
                        'icon' => 'fa-heart',
                        'color' => '#ffffff'
                    ]
                ]
            ]
        ];
        
        // 10. BRAND LOGOS Section
        $elements[] = [
            'id' => 'brands-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 60, 'right' => 60, 'bottom' => 60, 'left' => 60],
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'brands-title-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'TRUSTED BY LEADING BRANDS',
                        'color' => '#64748b',
                        'font_size' => 14,
                        'font_weight' => '700',
                        'letter_spacing' => '2px',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'brands-grid-' . uniqid(),
                    'widgetType' => 'logo-grid',
                    'settings' => [
                        'columns' => 6,
                        'grayscale' => true
                    ]
                ]
            ]
        ];
        
        // 11. CUSTOMER REVIEWS
        $elements[] = [
            'id' => 'reviews-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 40, 'left' => 60]
            ],
            'children' => [
                [
                    'id' => 'reviews-badge-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '⭐⭐⭐⭐⭐ 4.9/5.0',
                        'color' => '#f59e0b',
                        'font_size' => 18,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'reviews-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'What Our Customers Say',
                        'html_tag' => 'h2',
                        'color' => '#1a1a1a',
                        'font_size' => 42,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'reviews-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Join 50,000+ happy customers who love shopping with us',
                        'color' => '#64748b',
                        'font_size' => 18,
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'reviews-widget-' . uniqid(),
            'widgetType' => 'reviews',
            'settings' => [
                'layout' => 'carousel',
                'show_rating' => true,
                'items' => [
                    ['name' => 'Jessica Smith', 'rating' => 5, 'text' => 'Amazing quality! Fast shipping and great customer service.', 'avatar' => 'https://i.pravatar.cc/150?img=1'],
                    ['name' => 'Michael Chen', 'rating' => 5, 'text' => 'Best online shopping experience. Will buy again!', 'avatar' => 'https://i.pravatar.cc/150?img=3'],
                    ['name' => 'Sarah Johnson', 'rating' => 5, 'text' => 'Love everything about this store. Highly recommend!', 'avatar' => 'https://i.pravatar.cc/150?img=5']
                ]
            ]
        ];
        
        // 12. INSTAGRAM FEED
        $elements[] = [
            'id' => 'insta-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#ffffff',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 40, 'left' => 60],
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'insta-badge-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '📸 INSTAGRAM',
                        'color' => '#E1306C',
                        'font_size' => 16,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 15, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'insta-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Follow Us @eliteshop',
                        'html_tag' => 'h2',
                        'color' => '#1a1a1a',
                        'font_size' => 42,
                        'font_weight' => '700',
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'insta-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Tag us in your photos for a chance to be featured!',
                        'color' => '#64748b',
                        'font_size' => 18,
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'instagram-feed-' . uniqid(),
            'widgetType' => 'instagram-feed',
            'settings' => [
                'username' => 'eliteshop',
                'columns' => 6,
                'limit' => 12
            ]
        ];
        
        // 13. NEWSLETTER SIGNUP - Eye-catching
        $elements[] = [
            'id' => 'newsletter-container-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'padding' => ['top' => 100, 'right' => 60, 'bottom' => 100, 'left' => 60],
                'margin' => ['top' => 80, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'newsletter-icon-' . uniqid(),
                    'widgetType' => 'icon',
                    'settings' => [
                        'icon' => 'fa-envelope',
                        'size' => 64,
                        'color' => '#ffffff',
                        'align' => 'center'
                    ]
                ],
                [
                    'id' => 'newsletter-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Get 15% OFF Your First Order!',
                        'html_tag' => 'h2',
                        'color' => '#ffffff',
                        'font_size' => 48,
                        'font_weight' => '800',
                        'align' => 'center',
                        'margin' => ['top' => 30, 'right' => 0, 'bottom' => 20, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'newsletter-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Subscribe to our newsletter and be the first to know about exclusive offers, new arrivals, and style tips!',
                        'color' => '#ffffff',
                        'font_size' => 18,
                        'align' => 'center',
                        'line_height' => '1.6',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'newsletter-form-' . uniqid(),
                    'widgetType' => 'newsletter',
                    'settings' => [
                        'style' => 'inline',
                        'button_text' => 'Subscribe & Save',
                        'placeholder' => 'Enter your email address',
                        'show_consent' => true
                    ]
                ]
            ]
        ];
        
        // Trust Badges
        $elements[] = [
            'id' => 'trust-' . uniqid(),
            'widgetType' => 'feature-list',
            'settings' => [
                'layout' => 'grid',
                'columns' => '4',
                'show_card' => 'yes',
                'icon_position' => 'top',
                'text_align' => 'center',
                'items' => [
                    ['icon' => 'fa fa-truck-fast', 'title' => 'Free Shipping', 'description' => 'On orders over $50'],
                    ['icon' => 'fa fa-shield-check', 'title' => 'Secure Payment', 'description' => '100% protected'],
                    ['icon' => 'fa fa-rotate-left', 'title' => 'Easy Returns', 'description' => '30-day policy'],
                    ['icon' => 'fa fa-headset', 'title' => '24/7 Support', 'description' => 'Always here to help']
                ]
            ]
        ];
        
        // Payment Options
        $elements[] = [
            'id' => 'payment-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Secure Payment Methods',
                'html_tag' => 'h3',
                'font_size' => 24,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 20, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'payment-buttons-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 20,
                'padding' => ['top' => 20, 'right' => 300, 'bottom' => 60, 'left' => 300],
                'background_color' => '#f8f9fa'
            ],
            'children' => [
                [
                    'id' => 'paypal-demo-' . uniqid(),
                    'widgetType' => 'paypal-button',
                    'settings' => [
                        'amount' => 100,
                        'currency' => 'USD',
                        'description' => 'Shop Purchase'
                    ]
                ],
                [
                    'id' => 'stripe-demo-' . uniqid(),
                    'widgetType' => 'stripe-button',
                    'settings' => [
                        'amount' => 10000,
                        'currency' => 'usd',
                        'description' => 'Shop Purchase'
                    ]
                ]
            ]
        ];
        
        // Back to Top
        $elements[] = [
            'id' => 'back-to-top-' . uniqid(),
            'widgetType' => 'back-to-top',
            'settings' => [
                'position' => 'bottom-right',
                'icon' => 'fa fa-arrow-up',
                'bg_color' => '#92003b'
            ]
        ];
        
        // Footer
        $elements[] = [
            'id' => 'footer-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#1a1a1a',
                'padding' => ['top' => 40, 'right' => 40, 'bottom' => 40, 'left' => 40],
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'footer-text-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => '© 2025 Elite Shop. All rights reserved. Premium fashion and accessories.',
                        'color' => '#ffffff',
                        'align' => 'center',
                        'font_size' => 14
                    ]
                ]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build E-Commerce Product Detail Page - ENHANCED
     */
    private function build_ecommerce_product_page() {
        $elements = [];
        
        // Breadcrumbs
        $elements[] = [
            'id' => 'breadcrumbs-' . uniqid(),
            'widgetType' => 'breadcrumbs',
            'settings' => [
                'separator' => '>',
                'home_text' => 'Home'
            ]
        ];
        
        // Header with Search Form
        $elements[] = [
            'id' => 'header-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_color' => '#ffffff',
                'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40],
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],
                'box_shadow' => ['x' => 0, 'y' => 2, 'blur' => 10, 'color' => 'rgba(0,0,0,0.05)']
            ],
            'children' => [
                [
                    'id' => 'logo-' . uniqid(),
                    'widgetType' => 'site-logo',
                    'settings' => ['width' => 120, 'align' => 'left']
                ],
                [
                    'id' => 'search-form-' . uniqid(),
                    'widgetType' => 'search-form',
                    'settings' => ['placeholder' => 'Search products...', 'button_text' => 'Search', 'layout' => 'inline']
                ],
                [
                    'id' => 'cart-widget-' . uniqid(),
                    'widgetType' => 'woo-cart',
                    'settings' => ['show_total' => true, 'show_count' => true]
                ]
            ]
        ];
        
        // Product Detail Section
        $elements[] = [
            'id' => 'product-detail-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 60,
                'padding' => ['top' => 60, 'right' => 80, 'bottom' => 60, 'left' => 80],
                'margin' => ['top' => 40, 'right' => 0, 'bottom' => 60, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'product-images-' . uniqid(),
                    'widgetType' => 'gallery',
                    'settings' => [
                        'columns' => '2',
                        'gap' => 15,
                        'lightbox' => 'yes',
                        'images' => [
                            ['image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop', 'caption' => 'Main view'],
                            ['image_url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&h=600&fit=crop', 'caption' => 'Side view'],
                            ['image_url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&h=600&fit=crop', 'caption' => 'Detail'],
                            ['image_url' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=600&h=600&fit=crop', 'caption' => 'Lifestyle']
                        ]
                    ]
                ],
                [
                    'id' => 'product-info-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        [
                            'id' => 'product-title-' . uniqid(),
                            'widgetType' => 'heading',
                            'settings' => ['title' => 'Premium Wireless Headphones', 'html_tag' => 'h1', 'font_size' => 32, 'color' => '#1a1a1a', 'font_weight' => '700']
                        ],
                        [
                            'id' => 'product-rating-' . uniqid(),
                            'widgetType' => 'star-rating',
                            'settings' => ['rating' => 4.8, 'show_title' => 'no', 'filled_color' => '#fbbf24', 'align' => 'left']
                        ],
                        [
                            'id' => 'product-price-' . uniqid(),
                            'widgetType' => 'heading',
                            'settings' => ['title' => '$299.00', 'html_tag' => 'h2', 'font_size' => 36, 'color' => '#92003b', 'font_weight' => '700']
                        ],
                        [
                            'id' => 'product-desc-' . uniqid(),
                            'widgetType' => 'text',
                            'settings' => ['content' => 'Experience premium sound quality with active noise cancellation, 30-hour battery life, and comfortable over-ear design. Perfect for music lovers and professionals.', 'color' => '#666', 'font_size' => 16]
                        ],
                        [
                            'id' => 'product-features-' . uniqid(),
                            'widgetType' => 'icon-list',
                            'settings' => [
                                'layout' => 'vertical',
                                'icon_color' => '#10b981',
                                'items' => [
                                    ['icon' => 'fa fa-check-circle', 'text' => 'Active Noise Cancellation'],
                                    ['icon' => 'fa fa-check-circle', 'text' => '30 Hours Battery Life'],
                                    ['icon' => 'fa fa-check-circle', 'text' => 'Premium Sound Quality'],
                                    ['icon' => 'fa fa-check-circle', 'text' => 'Bluetooth 5.0'],
                                    ['icon' => 'fa fa-check-circle', 'text' => 'Foldable Design']
                                ]
                            ]
                        ],
                        [
                            'id' => 'add-to-cart-' . uniqid(),
                            'widgetType' => 'button',
                            'settings' => ['text' => 'Add to Cart', 'bg_color' => '#92003b', 'text_color' => '#fff', 'size' => 'large', 'align' => 'left']
                        ]
                    ]
                ]
            ]
        ];
        
        // Product Reviews
        $elements[] = [
            'id' => 'product-reviews-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => ['title' => 'Customer Reviews', 'html_tag' => 'h2', 'font_size' => 32, 'align' => 'center', 'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]]
        ];
        
        $elements[] = [
            'id' => 'product-reviews-' . uniqid(),
            'widgetType' => 'reviews',
            'settings' => [
                'layout' => 'grid',
                'columns' => 3,
                'show_rating' => true,
                'items' => [
                    ['name' => 'Alex Turner', 'rating' => 5, 'text' => 'Best headphones I have ever owned! Crystal clear sound.', 'avatar' => 'https://i.pravatar.cc/150?img=12'],
                    ['name' => 'Maria Garcia', 'rating' => 5, 'text' => 'Amazing quality and comfort. Worth every penny!', 'avatar' => 'https://i.pravatar.cc/150?img=10'],
                    ['name' => 'James Wilson', 'rating' => 4, 'text' => 'Great product, fast delivery. Highly recommend!', 'avatar' => 'https://i.pravatar.cc/150?img=15']
                ]
            ]
        ];
        
        // Product Tabs - Detailed Information
        $elements[] = [
            'id' => 'product-tabs-' . uniqid(),
            'widgetType' => 'tabs',
            'settings' => [
                'tabs' => [
                    [
                        'title' => 'Description',
                        'content' => '<h3>Product Details</h3><p>Experience premium sound quality with our latest wireless headphones. Featuring advanced noise cancellation technology, 30-hour battery life, and comfort-first design. Perfect for music lovers, professionals, and travelers.</p><ul><li>High-fidelity audio drivers</li><li>Active noise cancellation</li><li>Bluetooth 5.0 connectivity</li><li>Premium materials</li><li>Comfortable ear cushions</li></ul>'
                    ],
                    [
                        'title' => 'Specifications',
                        'content' => '<table style="width:100%"><tr><th>Feature</th><th>Specification</th></tr><tr><td>Driver Size</td><td>40mm</td></tr><tr><td>Frequency</td><td>20Hz - 20kHz</td></tr><tr><td>Impedance</td><td>32 Ohms</td></tr><tr><td>Battery</td><td>30 hours</td></tr><tr><td>Charging</td><td>USB-C Fast Charge</td></tr><tr><td>Weight</td><td>250g</td></tr></table>'
                    ],
                    [
                        'title' => 'Shipping',
                        'content' => '<h4>Shipping Information</h4><p><strong>Free Standard Shipping</strong> on orders over $50</p><ul><li>Standard Delivery: 3-5 business days</li><li>Express Delivery: 1-2 business days</li><li>International Shipping Available</li><li>Track your order online</li></ul>'
                    ],
                    [
                        'title' => 'Returns',
                        'content' => '<h4>Easy 30-Day Returns</h4><p>Not satisfied? Return within 30 days for a full refund!</p><ul><li>Free return shipping</li><li>No restocking fees</li><li>Full refund guarantee</li><li>Easy return process</li></ul>'
                    ]
                ],
                'style' => 'horizontal'
            ]
        ];
        
        // Trust Badges Section
        $elements[] = [
            'id' => 'product-trust-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '4',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 40, 'right' => 60, 'bottom' => 40, 'left' => 60],
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 60, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'trust-1-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-shield-check',
                        'title' => '2-Year Warranty',
                        'description' => 'Full coverage included',
                        'icon_color' => '#22c55e'
                    ]
                ],
                [
                    'id' => 'trust-2-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-truck-fast',
                        'title' => 'Free Shipping',
                        'description' => 'On orders over $50',
                        'icon_color' => '#3b82f6'
                    ]
                ],
                [
                    'id' => 'trust-3-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-rotate-left',
                        'title' => 'Easy Returns',
                        'description' => '30-day money back',
                        'icon_color' => '#f59e0b'
                    ]
                ],
                [
                    'id' => 'trust-4-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-lock',
                        'title' => 'Secure Payment',
                        'description' => '100% protected',
                        'icon_color' => '#8b5cf6'
                    ]
                ]
            ]
        ];
        
        // Product Video Section
        $elements[] = [
            'id' => 'video-section-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => '🎥 See It In Action',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'product-video-' . uniqid(),
            'widgetType' => 'video',
            'settings' => [
                'source' => 'youtube',
                'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'aspect_ratio' => '16:9',
                'autoplay' => false
            ]
        ];
        
        // Product Features Highlight
        $elements[] = [
            'id' => 'features-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'column_gap' => 40,
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 80, 'left' => 60],
                'background_color' => '#ffffff'
            ],
            'children' => [
                [
                    'id' => 'feature-1-' . uniqid(),
                    'widgetType' => 'info-box',
                    'settings' => [
                        'icon' => 'fa-headphones',
                        'title' => 'Premium Audio',
                        'description' => 'Studio-quality sound with deep bass and crystal-clear highs. Experience music like never before.',
                        'icon_bg_color' => '#667eea',
                        'style' => 'modern'
                    ]
                ],
                [
                    'id' => 'feature-2-' . uniqid(),
                    'widgetType' => 'info-box',
                    'settings' => [
                        'icon' => 'fa-battery-full',
                        'title' => 'Long Battery Life',
                        'description' => 'Up to 30 hours of continuous playback on a single charge. Quick charge gives 3 hours in 10 minutes.',
                        'icon_bg_color' => '#f093fb',
                        'style' => 'modern'
                    ]
                ],
                [
                    'id' => 'feature-3-' . uniqid(),
                    'widgetType' => 'info-box',
                    'settings' => [
                        'icon' => 'fa-microphone',
                        'title' => 'Crystal Clear Calls',
                        'description' => 'Advanced microphone technology ensures perfect call quality even in noisy environments.',
                        'icon_bg_color' => '#4ade80',
                        'style' => 'modern'
                    ]
                ]
            ]
        ];
        
        // FAQ Section
        $elements[] = [
            'id' => 'faq-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Frequently Asked Questions',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'product-faq-' . uniqid(),
            'widgetType' => 'faq',
            'settings' => [
                'items' => [
                    ['question' => 'Are these headphones compatible with all devices?', 'answer' => 'Yes! Our headphones work with any Bluetooth-enabled device including smartphones, tablets, laptops, and smart TVs.'],
                    ['question' => 'How long does the battery last?', 'answer' => 'Up to 30 hours of continuous playback. With ANC off, you can get up to 40 hours of battery life.'],
                    ['question' => 'Do they come with a warranty?', 'answer' => 'Yes, all our products come with a 2-year manufacturer warranty covering defects and malfunctions.'],
                    ['question' => 'Can I use them while charging?', 'answer' => 'Yes, you can use the headphones while charging via the included USB-C cable.'],
                    ['question' => 'What is your return policy?', 'answer' => 'We offer a 30-day money-back guarantee. If you\'re not satisfied, return for a full refund - no questions asked.']
                ]
            ]
        ];
        
        // Image Comparison - Product Quality
        $elements[] = [
            'id' => 'comparison-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => ['title' => 'Before & After: Active Noise Cancellation', 'html_tag' => 'h3', 'font_size' => 32, 'align' => 'center', 'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]]
        ];
        
        $elements[] = [
            'id' => 'sound-comparison-' . uniqid(),
            'widgetType' => 'image-comparison',
            'settings' => [
                'before_image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&h=600',
                'after_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600',
                'before_label' => 'Noisy Environment',
                'after_label' => 'With ANC ON',
                'position' => 50
            ]
        ];
        
        // Payment Options
        $elements[] = [
            'id' => 'payment-section-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Secure Payment Options',
                'html_tag' => 'h3',
                'font_size' => 28,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 30, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'payment-options-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 20,
                'padding' => ['top' => 20, 'right' => 200, 'bottom' => 60, 'left' => 200]
            ],
            'children' => [
                [
                    'id' => 'paypal-btn-' . uniqid(),
                    'widgetType' => 'paypal-button',
                    'settings' => [
                        'amount' => 299,
                        'currency' => 'USD',
                        'description' => 'Premium Wireless Headphones'
                    ]
                ],
                [
                    'id' => 'stripe-btn-' . uniqid(),
                    'widgetType' => 'stripe-button',
                    'settings' => [
                        'amount' => 299,
                        'currency' => 'USD',
                        'description' => 'Premium Wireless Headphones'
                    ]
                ]
            ]
        ];
        
        // Size Guide / Compatibility
        $elements[] = [
            'id' => 'compatibility-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Device Compatibility',
                'html_tag' => 'h3',
                'font_size' => 28,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'compatibility-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '5',
                'padding' => ['top' => 20, 'right' => 60, 'bottom' => 60, 'left' => 60]
            ],
            'children' => [
                [
                    'id' => 'compat-1-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-mobile',
                        'title' => 'Smartphones',
                        'icon_color' => '#22c55e',
                        'icon_size' => 36
                    ]
                ],
                [
                    'id' => 'compat-2-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-tablet',
                        'title' => 'Tablets',
                        'icon_color' => '#3b82f6',
                        'icon_size' => 36
                    ]
                ],
                [
                    'id' => 'compat-3-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-laptop',
                        'title' => 'Laptops',
                        'icon_color' => '#8b5cf6',
                        'icon_size' => 36
                    ]
                ],
                [
                    'id' => 'compat-4-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-tv',
                        'title' => 'Smart TV',
                        'icon_color' => '#f59e0b',
                        'icon_size' => 36
                    ]
                ],
                [
                    'id' => 'compat-5-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa-gamepad',
                        'title' => 'Gaming',
                        'icon_color' => '#ec4899',
                        'icon_size' => 36
                    ]
                ]
            ]
        ];
        
        // Related Products
        $elements[] = [
            'id' => 'related-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 80, 'right' => 60, 'bottom' => 40, 'left' => 60],
                'margin' => ['top' => 80, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'related-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'You May Also Like',
                        'html_tag' => 'h2',
                        'font_size' => 36,
                        'align' => 'center',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]
                    ]
                ]
            ]
        ];
        
        $elements[] = [
            'id' => 'related-woo-products-' . uniqid(),
            'widgetType' => 'woo-products',
            'settings' => [
                'query_type' => 'recent',
                'columns' => 4,
                'products_per_page' => 8,
                'show_image' => true,
                'show_price' => true,
                'show_cart_button' => true
            ]
        ];
        
        // Share Buttons
        $elements[] = [
            'id' => 'share-product-' . uniqid(),
            'widgetType' => 'share-buttons',
            'settings' => [
                'platforms' => ['facebook', 'twitter', 'pinterest', 'whatsapp'],
                'style' => 'rounded',
                'align' => 'center'
            ]
        ];
        
        // Back to Top
        $elements[] = [
            'id' => 'back-to-top-product-' . uniqid(),
            'widgetType' => 'back-to-top',
            'settings' => [
                'position' => 'bottom-right',
                'icon' => 'fa fa-arrow-up',
                'bg_color' => '#92003b'
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build E-Commerce Homepage - ENHANCED
     */
    private function build_ecommerce_homepage() {
        $elements = [];
        
        // Notification Bar
        $elements[] = [
            'id' => 'notification-bar-' . uniqid(),
            'widgetType' => 'notification',
            'settings' => [
                'message' => '🎉 MEGA SALE! Up to 70% OFF Everything + Free Shipping!',
                'type' => 'warning',
                'dismissible' => true,
                'position' => 'top'
            ]
        ];
        
        // Top Bar
        $elements[] = [
            'id' => 'topbar-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'background_color' => '#92003b',
                'padding' => ['top' => 10, 'right' => 40, 'bottom' => 10, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'promo-' . uniqid(),
                    'widgetType' => 'animated-text',
                    'settings' => ['text' => 'Summer Sale - Up to 50% OFF!', 'animation' => 'wave', 'color' => '#ffffff', 'size' => 14]
                ],
                [
                    'id' => 'contact-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => ['content' => '📞 +1 (555) 123-4567', 'color' => '#ffffff', 'align' => 'right', 'font_size' => 13]
                ]
            ]
        ];
        
        // Header with Navigation
        $elements[] = [
            'id' => 'header-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_color' => '#ffffff',
                'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40],
                'box_shadow' => ['x' => 0, 'y' => 2, 'blur' => 10, 'color' => 'rgba(0,0,0,0.05)']
            ],
            'children' => [
                [
                    'id' => 'logo-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => ['title' => 'ELITE SHOP', 'html_tag' => 'h1', 'color' => '#1a1a1a', 'font_size' => 28, 'font_weight' => '800']
                ],
                [
                    'id' => 'nav-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => ['content' => 'New Arrivals | Men | Women | Kids | Sale', 'align' => 'center', 'color' => '#333', 'font_size' => 14]
                ],
                [
                    'id' => 'header-actions-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => ['content' => '🔍 | 👤 | 🛒', 'align' => 'right', 'font_size' => 18]
                ]
            ]
        ];
        
        // Hero Carousel
        $elements[] = [
            'id' => 'hero-carousel-' . uniqid(),
            'widgetType' => 'carousel',
            'settings' => [
                'height' => 600,
                'autoplay' => 'yes',
                'autoplay_speed' => 5000,
                'images' => [
                    ['image_url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&h=600&fit=crop', 'caption' => 'Summer Collection 2025 - Up to 50% OFF'],
                    ['image_url' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&h=600&fit=crop', 'caption' => 'New Arrivals - Shop the Latest Trends'],
                    ['image_url' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1600&h=600&fit=crop', 'caption' => 'Premium Quality - Free Shipping Over $50']
                ]
            ]
        ];
        
        // Category Boxes
        $elements[] = [
            'id' => 'categories-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'column_gap' => 30,
                'padding' => ['top' => 60, 'right' => 40, 'bottom' => 60, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'cat1-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?w=600&h=400&fit=crop',
                        'title' => 'Women\'s Fashion',
                        'description' => 'Explore the latest trends',
                        'button_text' => 'Shop Women',
                        'show_button' => 'yes',
                        'text_align' => 'center',
                        'button_bg_color' => '#92003b'
                    ]
                ],
                [
                    'id' => 'cat2-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=600&h=400&fit=crop',
                        'title' => 'Men\'s Fashion',
                        'description' => 'Stylish and modern',
                        'button_text' => 'Shop Men',
                        'show_button' => 'yes',
                        'text_align' => 'center',
                        'button_bg_color' => '#92003b'
                    ]
                ],
                [
                    'id' => 'cat3-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://images.unsplash.com/photo-1622290291468-a28f7a7dc6a8?w=600&h=400&fit=crop',
                        'title' => 'Accessories',
                        'description' => 'Complete your look',
                        'button_text' => 'Shop Now',
                        'show_button' => 'yes',
                        'text_align' => 'center',
                        'button_bg_color' => '#92003b'
                    ]
                ]
            ]
        ];
        
        // Featured Products
        $elements[] = [
            'id' => 'featured-heading-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => ['title' => 'Trending Now', 'html_tag' => 'h2', 'font_size' => 36, 'align' => 'center', 'color' => '#1a1a1a', 'margin' => ['top' => 0, 'right' => 0, 'bottom' => 50, 'left' => 0]]
        ];
        
        $elements[] = [
            'id' => 'featured-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '4',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 40, 'bottom' => 60, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'fprod1-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=500&fit=crop', 'title' => 'Classic Dress', 'description' => '$89.00', 'button_text' => 'Add to Cart', 'text_align' => 'center']
                ],
                [
                    'id' => 'fprod2-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=500&fit=crop', 'title' => 'Designer Handbag', 'description' => '$249.00', 'button_text' => 'Add to Cart', 'text_align' => 'center']
                ],
                [
                    'id' => 'fprod3-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&h=500&fit=crop', 'title' => 'Stylish Sneakers', 'description' => '$129.00', 'button_text' => 'Add to Cart', 'text_align' => 'center']
                ],
                [
                    'id' => 'fprod4-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=500&fit=crop', 'title' => 'Luxury Watch', 'description' => '$449.00', 'button_text' => 'Add to Cart', 'text_align' => 'center']
                ]
            ]
        ];
        
        // Trust Section
        $elements[] = [
            'id' => 'trust-section-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 60, 'right' => 40, 'bottom' => 60, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'trust-features-' . uniqid(),
                    'widgetType' => 'feature-list',
                    'settings' => [
                        'layout' => 'grid',
                        'columns' => '4',
                        'show_card' => 'yes',
                        'icon_position' => 'top',
                        'text_align' => 'center',
                        'items' => [
                            ['icon' => 'fa fa-truck-fast', 'title' => 'Free Shipping', 'description' => 'Free shipping worldwide on orders over $50'],
                            ['icon' => 'fa fa-shield-halved', 'title' => 'Secure Payment', 'description' => '100% secure payment with SSL encryption'],
                            ['icon' => 'fa fa-rotate-left', 'title' => '30-Day Return', 'description' => 'Easy returns within 30 days of purchase'],
                            ['icon' => 'fa fa-headset', 'title' => '24/7 Support', 'description' => 'Dedicated customer support team always ready']
                        ]
                    ]
                ]
            ]
        ];
        
        // Newsletter
        $elements[] = [
            'id' => 'newsletter-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #92003b 0%, #667eea 100%)',
                'padding' => ['top' => 80, 'right' => 40, 'bottom' => 80, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'newsletter-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => ['title' => 'Subscribe to Our Newsletter', 'html_tag' => 'h2', 'font_size' => 32, 'color' => '#fff', 'align' => 'center']
                ],
                [
                    'id' => 'newsletter-text-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => ['content' => 'Get exclusive deals, new arrivals, and fashion tips delivered to your inbox.', 'color' => '#fff', 'align' => 'center', 'font_size' => 16]
                ],
                [
                    'id' => 'newsletter-btn-' . uniqid(),
                    'widgetType' => 'button',
                    'settings' => ['text' => 'Subscribe Now', 'bg_color' => '#ffffff', 'text_color' => '#92003b', 'size' => 'large', 'align' => 'center']
                ]
            ]
        ];
        
        // Footer
        $elements[] = [
            'id' => 'footer-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '4',
                'background_color' => '#1a1a1a',
                'padding' => ['top' => 60, 'right' => 40, 'bottom' => 40, 'left' => 40],
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'footer-about-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        ['id' => 'f-title1-' . uniqid(), 'widgetType' => 'heading', 'settings' => ['title' => 'About Us', 'html_tag' => 'h3', 'color' => '#fff', 'font_size' => 18]],
                        ['id' => 'f-text1-' . uniqid(), 'widgetType' => 'text', 'settings' => ['content' => 'Premium fashion and lifestyle products since 2010. Quality you can trust.', 'color' => '#999', 'font_size' => 14]]
                    ]
                ],
                [
                    'id' => 'footer-shop-' . uniqid(),
                    'widgetType' => 'icon-list',
                    'settings' => [
                        'text_color' => '#999',
                        'icon_color' => '#92003b',
                        'icon_size' => 14,
                        'text_size' => 14,
                        'items' => [
                            ['icon' => 'fa fa-angle-right', 'text' => 'New Arrivals'],
                            ['icon' => 'fa fa-angle-right', 'text' => 'Best Sellers'],
                            ['icon' => 'fa fa-angle-right', 'text' => 'Sale Items'],
                            ['icon' => 'fa fa-angle-right', 'text' => 'Gift Cards']
                        ]
                    ]
                ],
                [
                    'id' => 'footer-help-' . uniqid(),
                    'widgetType' => 'icon-list',
                    'settings' => [
                        'text_color' => '#999',
                        'icon_color' => '#92003b',
                        'icon_size' => 14,
                        'text_size' => 14,
                        'items' => [
                            ['icon' => 'fa fa-angle-right', 'text' => 'Shipping Info'],
                            ['icon' => 'fa fa-angle-right', 'text' => 'Returns'],
                            ['icon' => 'fa fa-angle-right', 'text' => 'Size Guide'],
                            ['icon' => 'fa fa-angle-right', 'text' => 'FAQs']
                        ]
                    ]
                ],
                [
                    'id' => 'footer-payment-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        ['id' => 'payment-title-' . uniqid(), 'widgetType' => 'heading', 'settings' => ['title' => 'We Accept', 'html_tag' => 'h3', 'color' => '#fff', 'font_size' => 18]],
                        ['id' => 'payment-text-' . uniqid(), 'widgetType' => 'text', 'settings' => ['content' => '💳 Visa | MasterCard | PayPal | Apple Pay', 'color' => '#999', 'font_size' => 14]]
                    ]
                ]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build Fashion Store Page
     */
    private function build_fashion_store_page() {
        $elements = [];
        
        // Luxury Header
        $elements[] = [
            'id' => 'luxury-header-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#000000',
                'padding' => ['top' => 30, 'right' => 40, 'bottom' => 30, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'brand-name-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => ['title' => 'MAISON DE MODE', 'html_tag' => 'h1', 'color' => '#ffffff', 'font_size' => 32, 'align' => 'center', 'font_weight' => '300', 'letter_spacing' => '5px']
                ]
            ]
        ];
        
        // Full-Width Hero
        $elements[] = [
            'id' => 'fashion-hero-' . uniqid(),
            'widgetType' => 'image-box',
            'settings' => [
                'image_url' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1600&h=600&fit=crop',
                'title' => 'Autumn/Winter 2025',
                'description' => 'Timeless elegance meets contemporary design',
                'button_text' => 'Explore Collection',
                'show_button' => 'yes',
                'text_align' => 'center',
                'title_color' => '#ffffff',
                'description_color' => '#ffffff',
                'title_size' => 48,
                'button_bg_color' => '#ffffff',
                'button_text_color' => '#000000'
            ]
        ];
        
        // Featured Collections
        $elements[] = [
            'id' => 'collections-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => ['title' => 'FEATURED COLLECTIONS', 'html_tag' => 'h2', 'font_size' => 28, 'align' => 'center', 'color' => '#000', 'letter_spacing' => '3px', 'margin' => ['top' => 60, 'right' => 0, 'bottom' => 50, 'left' => 0]]
        ];
        
        $elements[] = [
            'id' => 'collections-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => ['columns' => '3', 'column_gap' => 20, 'padding' => ['top' => 0, 'right' => 40, 'bottom' => 80, 'left' => 40]],
            'children' => [
                ['id' => 'col1-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=500&h=700&fit=crop', 'title' => 'Evening Wear', 'description' => 'From $299', 'text_align' => 'center']],
                ['id' => 'col2-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=500&h=700&fit=crop', 'title' => 'Casual Chic', 'description' => 'From $79', 'text_align' => 'center']],
                ['id' => 'col3-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1564859228273-274232fdb516?w=500&h=700&fit=crop', 'title' => 'Accessories', 'description' => 'From $49', 'text_align' => 'center']]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build Electronics Store Page
     */
    private function build_electronics_store_page() {
        $elements = [];
        
        // Tech Header
        $elements[] = [
            'id' => 'tech-header-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_color' => '#0f172a',
                'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40]
            ],
            'children' => [
                ['id' => 'tech-logo-' . uniqid(), 'widgetType' => 'heading', 'settings' => ['title' => '⚡ TECH STORE', 'html_tag' => 'h2', 'color' => '#fff', 'font_size' => 24, 'font_weight' => '700']],
                ['id' => 'tech-nav-' . uniqid(), 'widgetType' => 'text', 'settings' => ['content' => 'Laptops | Phones | Tablets | Audio | Gaming', 'color' => '#cbd5e1', 'align' => 'center', 'font_size' => 14]],
                ['id' => 'tech-cart-' . uniqid(), 'widgetType' => 'button', 'settings' => ['text' => '🛒 Cart', 'bg_color' => '#3b82f6', 'align' => 'right']]
            ]
        ];
        
        // Hero Banner
        $elements[] = [
            'id' => 'tech-hero-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #1e40af 0%, #7c3aed 100%)',
                'padding' => ['top' => 100, 'right' => 60, 'bottom' => 100, 'left' => 60]
            ],
            'children' => [
                [
                    'id' => 'tech-hero-content-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        ['id' => 'tech-hero-title-' . uniqid(), 'widgetType' => 'heading', 'settings' => ['title' => 'Latest Tech Gadgets', 'html_tag' => 'h1', 'color' => '#fff', 'font_size' => 48, 'font_weight' => '800']],
                        ['id' => 'tech-hero-text-' . uniqid(), 'widgetType' => 'text', 'settings' => ['content' => 'Discover cutting-edge technology at unbeatable prices. From smartphones to laptops, we have everything you need.', 'color' => '#e0e7ff', 'font_size' => 18]],
                        ['id' => 'tech-hero-btn-' . uniqid(), 'widgetType' => 'button', 'settings' => ['text' => 'Browse Products', 'bg_color' => '#fff', 'text_color' => '#1e40af', 'size' => 'large', 'align' => 'left']]
                    ]
                ],
                ['id' => 'tech-hero-img-' . uniqid(), 'widgetType' => 'image', 'settings' => ['url' => 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=800&h=600&fit=crop']]
            ]
        ];
        
        // Product Showcase
        $elements[] = [
            'id' => 'showcase-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => ['title' => 'Best Sellers', 'html_tag' => 'h2', 'font_size' => 36, 'align' => 'center', 'margin' => ['top' => 60, 'right' => 0, 'bottom' => 50, 'left' => 0]]
        ];
        
        $elements[] = [
            'id' => 'tech-products-' . uniqid(),
            'widgetType' => 'container',
            'settings' => ['columns' => '4', 'column_gap' => 30, 'padding' => ['top' => 0, 'right' => 40, 'bottom' => 80, 'left' => 40]],
            'children' => [
                ['id' => 'tech-prod1-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400&h=400&fit=crop', 'title' => 'Gaming Laptop', 'description' => '$1,499.00', 'button_text' => 'Buy Now', 'text_align' => 'center']],
                ['id' => 'tech-prod2-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', 'title' => 'Wireless Headphones', 'description' => '$299.00', 'button_text' => 'Buy Now', 'text_align' => 'center']],
                ['id' => 'tech-prod3-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&h=400&fit=crop', 'title' => 'Smart Watch Pro', 'description' => '$399.00', 'button_text' => 'Buy Now', 'text_align' => 'center']],
                ['id' => 'tech-prod4-' . uniqid(), 'widgetType' => 'image-box', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop', 'title' => '4K Drone', 'description' => '$799.00', 'button_text' => 'Buy Now', 'text_align' => 'center']]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build Agency Portfolio Page - COMPREHENSIVE with ALL NEW WIDGETS
     */
    private function build_agency_portfolio_page() {
        $elements = [];
        
        // Animated Header with Notification Bar
        $elements[] = [
            'id' => 'notification-' . uniqid(),
            'widgetType' => 'notification',
            'settings' => [
                'message' => '🎉 New Portfolio Projects Added! Check Out Our Latest Work',
                'type' => 'info',
                'dismissible' => true,
                'position' => 'top'
            ]
        ];
        
        // Parallax Hero Section
        $elements[] = [
            'id' => 'parallax-hero-' . uniqid(),
            'widgetType' => 'parallax-image',
            'settings' => [
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1600&h=800&fit=crop',
                'height' => 600,
                'speed' => 0.5
            ]
        ];
        
        // Animated Text Title
        $elements[] = [
            'id' => 'animated-title-' . uniqid(),
            'widgetType' => 'animated-text',
            'settings' => [
                'text' => 'Creative Digital Agency',
                'animation' => 'neon',
                'color' => '#667eea',
                'size' => 64
            ]
        ];
        
        // Navigation Menu
        $elements[] = [
            'id' => 'main-nav-' . uniqid(),
            'widgetType' => 'menu',
            'settings' => [
                'layout' => 'horizontal',
                'align' => 'center'
            ]
        ];
        
        // Portfolio Grid Widget
        $elements[] = [
            'id' => 'portfolio-grid-' . uniqid(),
            'widgetType' => 'portfolio',
            'settings' => [
                'columns' => '3',
                'items' => [
                    ['title' => 'Brand Identity', 'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400', 'category' => 'Branding'],
                    ['title' => 'Web Design', 'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=600&h=400', 'category' => 'Design'],
                    ['title' => 'Mobile App', 'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400', 'category' => 'Development'],
                    ['title' => 'Marketing', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400', 'category' => 'Marketing'],
                    ['title' => 'Photography', 'image' => 'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?w=600&h=400', 'category' => 'Creative'],
                    ['title' => 'Illustration', 'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&h=400', 'category' => 'Art']
                ]
            ]
        ];
        
        // Progress Tracker - Skills Section
        $elements[] = [
            'id' => 'skills-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Our Expertise',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'skills-progress-' . uniqid(),
            'widgetType' => 'progress-tracker',
            'settings' => [
                'items' => [
                    ['label' => 'Web Design', 'percentage' => 95, 'color' => '#667eea'],
                    ['label' => 'Development', 'percentage' => 90, 'color' => '#4facfe'],
                    ['label' => 'Branding', 'percentage' => 85, 'color' => '#fa709a'],
                    ['label' => 'Marketing', 'percentage' => 88, 'color' => '#fee140']
                ]
            ]
        ];
        
        // Image Comparison - Before/After
        $elements[] = [
            'id' => 'comparison-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Website Transformation',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'before-after-' . uniqid(),
            'widgetType' => 'image-comparison',
            'settings' => [
                'before_image' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?w=800&h=600',
                'after_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600',
                'position' => 50
            ]
        ];
        
        // Reviews Widget
        $elements[] = [
            'id' => 'reviews-section-' . uniqid(),
            'widgetType' => 'reviews',
            'settings' => [
                'layout' => 'carousel',
                'show_rating' => true,
                'items' => [
                    ['name' => 'John Smith', 'rating' => 5, 'text' => 'Outstanding creative work! The team delivered beyond expectations.', 'avatar' => 'https://i.pravatar.cc/150?img=1'],
                    ['name' => 'Sarah Johnson', 'rating' => 5, 'text' => 'Professional, creative, and delivered on time. Highly recommended!', 'avatar' => 'https://i.pravatar.cc/150?img=2'],
                    ['name' => 'Mike Chen', 'rating' => 5, 'text' => 'Best agency we have worked with. Amazing results!', 'avatar' => 'https://i.pravatar.cc/150?img=3']
                ]
            ]
        ];
        
        // Team Section with Hotspot
        $elements[] = [
            'id' => 'team-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Meet Our Team',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'team-hotspot-' . uniqid(),
            'widgetType' => 'hotspot',
            'settings' => [
                'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&h=600',
                'hotspots' => [
                    ['x' => 25, 'y' => 50, 'title' => 'John Doe', 'description' => 'Creative Director'],
                    ['x' => 50, 'y' => 50, 'title' => 'Jane Smith', 'description' => 'Lead Designer'],
                    ['x' => 75, 'y' => 50, 'title' => 'Mike Johnson', 'description' => 'Senior Developer']
                ]
            ]
        ];
        
        // Price List
        $elements[] = [
            'id' => 'pricing-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Our Services & Pricing',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'price-list-' . uniqid(),
            'widgetType' => 'price-list',
            'settings' => [
                'items' => [
                    ['title' => 'Brand Identity Package', 'price' => '$2,500', 'description' => 'Logo, Brand Guidelines, Business Cards'],
                    ['title' => 'Website Design', 'price' => '$5,000', 'description' => 'Custom Design, Responsive, SEO Optimized'],
                    ['title' => 'E-Commerce Solution', 'price' => '$8,500', 'description' => 'Full Online Store Setup, Payment Integration'],
                    ['title' => 'Digital Marketing', 'price' => '$1,500/mo', 'description' => 'SEO, Social Media, Content Marketing']
                ]
            ]
        ];
        
        // Social Media Integration
        $elements[] = [
            'id' => 'social-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Follow Our Journey',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'instagram-feed-' . uniqid(),
            'widgetType' => 'instagram-feed',
            'settings' => [
                'username' => 'creativeagency',
                'columns' => 4,
                'limit' => 8
            ]
        ];
        
        // Twitter Embed
        $elements[] = [
            'id' => 'twitter-embed-' . uniqid(),
            'widgetType' => 'twitter-embed',
            'settings' => [
                'theme' => 'light',
                'url' => 'https://twitter.com/agency/status/123'
            ]
        ];
        
        // Share Buttons
        $elements[] = [
            'id' => 'share-buttons-' . uniqid(),
            'widgetType' => 'share-buttons',
            'settings' => [
                'platforms' => ['facebook', 'twitter', 'linkedin', 'pinterest'],
                'style' => 'rounded',
                'align' => 'center'
            ]
        ];
        
        // Calendly Booking
        $elements[] = [
            'id' => 'booking-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Schedule a Consultation',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'calendly-booking-' . uniqid(),
            'widgetType' => 'calendly',
            'settings' => [
                'type' => 'inline',
                'url' => 'your-calendly-link'
            ]
        ];
        
        // Payment Options
        $elements[] = [
            'id' => 'payment-container-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 30,
                'padding' => ['top' => 60, 'right' => 40, 'bottom' => 60, 'left' => 40],
                'background_color' => '#f8f9fa'
            ],
            'children' => [
                [
                    'id' => 'paypal-btn-' . uniqid(),
                    'widgetType' => 'paypal-button',
                    'settings' => [
                        'amount' => 2500,
                        'currency' => 'USD',
                        'description' => 'Brand Identity Package'
                    ]
                ],
                [
                    'id' => 'stripe-btn-' . uniqid(),
                    'widgetType' => 'stripe-button',
                    'settings' => [
                        'amount' => 250000,
                        'currency' => 'usd',
                        'description' => 'Website Design Package'
                    ]
                ]
            ]
        ];
        
        // Back to Top Button
        $elements[] = [
            'id' => 'back-to-top-' . uniqid(),
            'widgetType' => 'back-to-top',
            'settings' => [
                'position' => 'bottom-right',
                'icon' => 'fa fa-arrow-up',
                'bg_color' => '#667eea'
            ]
        ];
        
        // Reading Progress Bar
        $elements[] = [
            'id' => 'reading-progress-' . uniqid(),
            'widgetType' => 'reading-progress',
            'settings' => [
                'position' => 'top',
                'height' => 4,
                'color' => '#667eea'
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build SaaS Landing Page - with Advanced Widgets
     */
    private function build_saas_landing_page() {
        $elements = [];
        
        // Hero with Animated Text
        $elements[] = [
            'id' => 'saas-hero-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'padding' => ['top' => 100, 'right' => 40, 'bottom' => 100, 'left' => 40],
                'text_align' => 'center'
            ],
            'children' => [
                [
                    'id' => 'saas-animated-title-' . uniqid(),
                    'widgetType' => 'animated-text',
                    'settings' => [
                        'text' => 'Grow Your Business Faster',
                        'animation' => 'typing',
                        'color' => '#ffffff',
                        'size' => 56
                    ]
                ],
                [
                    'id' => 'saas-subtitle-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'All-in-one platform for modern businesses. Start your free trial today!',
                        'color' => '#ffffff',
                        'font_size' => 20,
                        'align' => 'center'
                    ]
                ]
            ]
        ];
        
        // Sticky Video Demo
        $elements[] = [
            'id' => 'video-demo-' . uniqid(),
            'widgetType' => 'sticky-video',
            'settings' => [
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'position' => 'bottom-right'
            ]
        ];
        
        // Features with Icon Boxes
        $elements[] = [
            'id' => 'features-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Powerful Features',
                'html_tag' => 'h2',
                'font_size' => 40,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 50, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'features-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 40, 'bottom' => 80, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'feature1-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa fa-rocket',
                        'title' => 'Lightning Fast',
                        'description' => 'Optimized for speed and performance'
                    ]
                ],
                [
                    'id' => 'feature2-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa fa-shield',
                        'title' => 'Secure & Reliable',
                        'description' => 'Enterprise-grade security'
                    ]
                ],
                [
                    'id' => 'feature3-' . uniqid(),
                    'widgetType' => 'icon-box',
                    'settings' => [
                        'icon' => 'fa fa-chart-line',
                        'title' => 'Analytics Dashboard',
                        'description' => 'Real-time insights and reporting'
                    ]
                ]
            ]
        ];
        
        // Code Highlight - API Example
        $elements[] = [
            'id' => 'api-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Developer Friendly API',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'code-example-' . uniqid(),
            'widgetType' => 'code-highlight',
            'settings' => [
                'code' => 'const api = new SaaSAPI({\n  apiKey: "your-api-key",\n  endpoint: "https://api.example.com"\n});\n\nawait api.users.create({\n  name: "John Doe",\n  email: "john@example.com"\n});',
                'language' => 'javascript',
                'theme' => 'dark'
            ]
        ];
        
        // Pricing Tables
        $elements[] = [
            'id' => 'pricing-section-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Simple, Transparent Pricing',
                'html_tag' => 'h2',
                'font_size' => 40,
                'align' => 'center',
                'margin' => ['top' => 80, 'right' => 0, 'bottom' => 50, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'pricing-grid-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 40, 'bottom' => 80, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'pricing-box1-' . uniqid(),
                    'widgetType' => 'pricing-table',
                    'settings' => [
                        'title' => 'Starter',
                        'price' => '$29',
                        'period' => 'per month',
                        'features' => ['10 Users', '10GB Storage', 'Basic Support'],
                        'button_text' => 'Get Started'
                    ]
                ],
                [
                    'id' => 'pricing-box2-' . uniqid(),
                    'widgetType' => 'pricing-table',
                    'settings' => [
                        'title' => 'Professional',
                        'price' => '$79',
                        'period' => 'per month',
                        'features' => ['50 Users', '100GB Storage', 'Priority Support', 'Advanced Analytics'],
                        'button_text' => 'Get Started',
                        'highlighted' => true
                    ]
                ],
                [
                    'id' => 'pricing-box3-' . uniqid(),
                    'widgetType' => 'pricing-table',
                    'settings' => [
                        'title' => 'Enterprise',
                        'price' => '$199',
                        'period' => 'per month',
                        'features' => ['Unlimited Users', 'Unlimited Storage', '24/7 Support', 'Custom Integration'],
                        'button_text' => 'Contact Sales'
                    ]
                ]
            ]
        ];
        
        // Reviews/Testimonials
        $elements[] = [
            'id' => 'testimonials-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Loved by Thousands of Businesses',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'testimonials-reviews-' . uniqid(),
            'widgetType' => 'reviews',
            'settings' => [
                'layout' => 'grid',
                'columns' => 3,
                'show_rating' => true
            ]
        ];
        
        // Newsletter Signup
        $elements[] = [
            'id' => 'newsletter-cta-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(to right, #4facfe 0%, #00f2fe 100%)',
                'padding' => ['top' => 80, 'right' => 40, 'bottom' => 80, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'newsletter-title-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Stay Updated',
                        'html_tag' => 'h2',
                        'font_size' => 36,
                        'color' => '#ffffff',
                        'align' => 'center'
                    ]
                ],
                [
                    'id' => 'newsletter-form-' . uniqid(),
                    'widgetType' => 'newsletter',
                    'settings' => [
                        'layout' => 'inline',
                        'button_color' => '#ffffff',
                        'button_text_color' => '#4facfe'
                    ]
                ]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build Restaurant & Food Page - with WooCommerce Widgets
     */
    private function build_restaurant_page() {
        $elements = [];
        
        // Hero Banner with Parallax
        $elements[] = [
            'id' => 'restaurant-parallax-' . uniqid(),
            'widgetType' => 'parallax-image',
            'settings' => [
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600&h=800',
                'height' => 500,
                'speed' => 0.3
            ]
        ];
        
        // Restaurant Title
        $elements[] = [
            'id' => 'restaurant-title-' . uniqid(),
            'widgetType' => 'animated-text',
            'settings' => [
                'text' => 'Gourmet Restaurant',
                'animation' => 'neon',
                'color' => '#fbbf24',
                'size' => 52
            ]
        ];
        
        // Navigation Menu
        $elements[] = [
            'id' => 'restaurant-menu-' . uniqid(),
            'widgetType' => 'menu',
            'settings' => [
                'layout' => 'horizontal',
                'align' => 'center'
            ]
        ];
        
        // WooCommerce Products Grid
        $elements[] = [
            'id' => 'menu-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Our Menu',
                'html_tag' => 'h2',
                'font_size' => 40,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'woo-products-' . uniqid(),
            'widgetType' => 'woo-products',
            'settings' => [
                'columns' => 4,
                'products_count' => 8,
                'orderby' => 'popularity'
            ]
        ];
        
        // WooCommerce Categories
        $elements[] = [
            'id' => 'categories-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Browse by Category',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'woo-categories-' . uniqid(),
            'widgetType' => 'woo-categories',
            'settings' => [
                'columns' => 4,
                'show_count' => true
            ]
        ];
        
        // WooCommerce Cart Widget
        $elements[] = [
            'id' => 'mini-cart-' . uniqid(),
            'widgetType' => 'woo-cart',
            'settings' => [
                'show_total' => true,
                'show_count' => true
            ]
        ];
        
        // Reviews Section
        $elements[] = [
            'id' => 'food-reviews-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'What Our Guests Say',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 80, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'food-reviews-' . uniqid(),
            'widgetType' => 'reviews',
            'settings' => [
                'layout' => 'carousel',
                'show_rating' => true,
                'items' => [
                    ['name' => 'Emma Wilson', 'rating' => 5, 'text' => 'Best food I have ever tasted! The atmosphere is amazing.', 'avatar' => 'https://i.pravatar.cc/150?img=5'],
                    ['name' => 'David Brown', 'rating' => 5, 'text' => 'Excellent service and delicious dishes. Highly recommend!', 'avatar' => 'https://i.pravatar.cc/150?img=7'],
                    ['name' => 'Lisa Anderson', 'rating' => 5, 'text' => 'Perfect for special occasions. The chef is a true artist!', 'avatar' => 'https://i.pravatar.cc/150?img=9']
                ]
            ]
        ];
        
        // Instagram Feed
        $elements[] = [
            'id' => 'food-instagram-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Follow Us on Instagram',
                'html_tag' => 'h2',
                'font_size' => 32,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 30, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'food-instagram-' . uniqid(),
            'widgetType' => 'instagram-feed',
            'settings' => [
                'username' => 'gourmetrestaurant',
                'columns' => 4,
                'limit' => 8
            ]
        ];
        
        // Facebook Page Embed
        $elements[] = [
            'id' => 'facebook-page-' . uniqid(),
            'widgetType' => 'facebook-embed',
            'settings' => [
                'type' => 'page',
                'url' => 'https://www.facebook.com/gourmetrestaurant'
            ]
        ];
        
        // Reservation with Calendly
        $elements[] = [
            'id' => 'reservation-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Reserve Your Table',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 80, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'reservation-calendly-' . uniqid(),
            'widgetType' => 'calendly',
            'settings' => [
                'type' => 'inline',
                'url' => 'restaurant-booking-link'
            ]
        ];
        
        // Location with Google Maps (using Hotspot as placeholder)
        $elements[] = [
            'id' => 'location-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Visit Us',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build Blog & Magazine Page - with Post Widgets
     */
    private function build_blog_magazine_page() {
        $elements = [];
        
        // Magazine Header with Site Logo
        $elements[] = [
            'id' => 'site-logo-' . uniqid(),
            'widgetType' => 'site-logo',
            'settings' => [
                'width' => 150,
                'align' => 'center'
            ]
        ];
        
        // Navigation Menu
        $elements[] = [
            'id' => 'blog-nav-' . uniqid(),
            'widgetType' => 'menu',
            'settings' => [
                'layout' => 'horizontal',
                'align' => 'center'
            ]
        ];
        
        // Search Form
        $elements[] = [
            'id' => 'search-form-' . uniqid(),
            'widgetType' => 'search-form',
            'settings' => [
                'placeholder' => 'Search articles...',
                'button_text' => 'Search',
                'layout' => 'inline'
            ]
        ];
        
        // Featured Post with Post Featured Image
        $elements[] = [
            'id' => 'featured-post-container-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'column_gap' => 40,
                'padding' => ['top' => 60, 'right' => 40, 'bottom' => 60, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'featured-image-' . uniqid(),
                    'widgetType' => 'post-featured-image',
                    'settings' => [
                        'size' => 'large',
                        'link' => true,
                        'border_radius' => 8
                    ]
                ],
                [
                    'id' => 'featured-content-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        [
                            'id' => 'post-title-' . uniqid(),
                            'widgetType' => 'post-title',
                            'settings' => [
                                'tag' => 'h1',
                                'font_size' => 42,
                                'link' => true
                            ]
                        ],
                        [
                            'id' => 'post-meta-' . uniqid(),
                            'widgetType' => 'container',
                            'settings' => ['columns' => '3'],
                            'children' => [
                                [
                                    'id' => 'post-author-' . uniqid(),
                                    'widgetType' => 'post-author',
                                    'settings' => [
                                        'show_avatar' => true,
                                        'link' => true
                                    ]
                                ],
                                [
                                    'id' => 'post-date-' . uniqid(),
                                    'widgetType' => 'post-date',
                                    'settings' => [
                                        'format' => 'F j, Y',
                                        'icon' => 'fa fa-calendar'
                                    ]
                                ],
                                [
                                    'id' => 'post-comments-' . uniqid(),
                                    'widgetType' => 'post-comments',
                                    'settings' => [
                                        'show_count' => true,
                                        'icon' => 'fa fa-comments'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => 'post-excerpt-' . uniqid(),
                            'widgetType' => 'post-excerpt',
                            'settings' => [
                                'length' => 200
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        // Reading Progress Bar
        $elements[] = [
            'id' => 'blog-reading-progress-' . uniqid(),
            'widgetType' => 'reading-progress',
            'settings' => [
                'position' => 'top',
                'height' => 3,
                'color' => '#92003b'
            ]
        ];
        
        // Recent Posts Grid
        $elements[] = [
            'id' => 'recent-posts-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Latest Articles',
                'html_tag' => 'h2',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'recent-posts-' . uniqid(),
            'widgetType' => 'recent-posts',
            'settings' => [
                'posts_per_page' => 6,
                'columns' => 3,
                'show_image' => true,
                'show_date' => true,
                'show_excerpt' => true
            ]
        ];
        
        // Category List
        $elements[] = [
            'id' => 'categories-section-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Browse Categories',
                'html_tag' => 'h2',
                'font_size' => 32,
                'align' => 'center',
                'margin' => ['top' => 60, 'right' => 0, 'bottom' => 30, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'category-list-' . uniqid(),
            'widgetType' => 'category-list',
            'settings' => [
                'show_count' => true,
                'hierarchical' => true,
                'columns' => 4
            ]
        ];
        
        // Tag Cloud
        $elements[] = [
            'id' => 'tags-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Popular Tags',
                'html_tag' => 'h3',
                'font_size' => 24,
                'align' => 'center',
                'margin' => ['top' => 40, 'right' => 0, 'bottom' => 20, 'left' => 0]
            ]
        ];
        
        $elements[] = [
            'id' => 'tag-cloud-' . uniqid(),
            'widgetType' => 'tag-cloud',
            'settings' => [
                'smallest' => 12,
                'largest' => 24,
                'number' => 30,
                'align' => 'center'
            ]
        ];
        
        // Archive Title
        $elements[] = [
            'id' => 'archive-title-' . uniqid(),
            'widgetType' => 'archive-title',
            'settings' => [
                'show_prefix' => true,
                'tag' => 'h1',
                'align' => 'center'
            ]
        ];
        
        // Author Box
        $elements[] = [
            'id' => 'author-box-' . uniqid(),
            'widgetType' => 'author-box',
            'settings' => [
                'show_avatar' => true,
                'avatar_size' => 100,
                'show_bio' => true,
                'show_social' => true
            ]
        ];
        
        // Post Navigation
        $elements[] = [
            'id' => 'post-navigation-' . uniqid(),
            'widgetType' => 'post-navigation',
            'settings' => [
                'show_title' => true,
                'show_thumbnail' => true
            ]
        ];
        
        // Breadcrumbs
        $elements[] = [
            'id' => 'breadcrumbs-' . uniqid(),
            'widgetType' => 'breadcrumbs',
            'settings' => [
                'separator' => '/',
                'home_text' => 'Home'
            ]
        ];
        
        // Table of Contents (for long articles)
        $elements[] = [
            'id' => 'toc-' . uniqid(),
            'widgetType' => 'table-of-contents',
            'settings' => [
                'title' => 'Table of Contents',
                'hierarchical' => true,
                'collapsible' => true
            ]
        ];
        
        // Share Buttons
        $elements[] = [
            'id' => 'blog-share-buttons-' . uniqid(),
            'widgetType' => 'share-buttons',
            'settings' => [
                'platforms' => ['facebook', 'twitter', 'linkedin', 'reddit', 'pinterest'],
                'style' => 'icons',
                'align' => 'center'
            ]
        ];
        
        // Newsletter Signup
        $elements[] = [
            'id' => 'blog-newsletter-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '1',
                'background_color' => '#f8f9fa',
                'padding' => ['top' => 60, 'right' => 40, 'bottom' => 60, 'left' => 40],
                'text_align' => 'center'
            ],
            'children' => [
                [
                    'id' => 'newsletter-heading-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Subscribe to Our Newsletter',
                        'html_tag' => 'h3',
                        'font_size' => 28
                    ]
                ],
                [
                    'id' => 'newsletter-widget-' . uniqid(),
                    'widgetType' => 'newsletter',
                    'settings' => [
                        'layout' => 'inline',
                        'placeholder' => 'Enter your email...',
                        'button_text' => 'Subscribe'
                    ]
                ]
            ]
        ];
        
        // Scroll Snap Section
        $elements[] = [
            'id' => 'scroll-snap-' . uniqid(),
            'widgetType' => 'scroll-snap',
            'settings' => [
                'sections' => ['section1', 'section2', 'section3']
            ]
        ];
        
        // Back to Top
        $elements[] = [
            'id' => 'blog-back-to-top-' . uniqid(),
            'widgetType' => 'back-to-top',
            'settings' => [
                'position' => 'bottom-right',
                'icon' => 'fa fa-arrow-up',
                'bg_color' => '#92003b'
            ]
        ];
        
        return $elements;
    }
}

// Initialize
ProBuilder_Templates_Library::instance();


