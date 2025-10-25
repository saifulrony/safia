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
        add_action('wp_ajax_probuilder_save_template', [$this, 'save_template']);
        add_action('wp_ajax_probuilder_delete_template', [$this, 'delete_template']);
        add_action('wp_ajax_probuilder_import_template', [$this, 'import_template']);
    }
    
    /**
     * Get all templates (pre-built + user templates)
     */
    public function get_templates() {
        $templates = [
            'prebuilt' => $this->get_prebuilt_templates(),
            'user' => $this->get_user_templates()
        ];
        
        wp_send_json_success($templates);
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
     * Build E-Commerce Shop Page
     */
    private function build_ecommerce_shop_page() {
        $elements = [];
        
        // Header with navigation
        $elements[] = [
            'id' => 'header-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '3',
                'background_color' => '#1a1a1a',
                'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40],
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'logo-' . uniqid(),
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'ELITE SHOP',
                        'html_tag' => 'h2',
                        'color' => '#ffffff',
                        'font_size' => 24,
                        'font_weight' => '700',
                        'margin' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0]
                    ]
                ],
                [
                    'id' => 'nav-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => [
                        'content' => 'Home | Shop | About | Contact',
                        'color' => '#ffffff',
                        'align' => 'center',
                        'font_size' => 14
                    ]
                ],
                [
                    'id' => 'cart-' . uniqid(),
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => '🛒 Cart (0)',
                        'bg_color' => '#92003b',
                        'text_color' => '#ffffff',
                        'align' => 'right',
                        'size' => 'small'
                    ]
                ]
            ]
        ];
        
        // Hero Banner
        $elements[] = [
            'id' => 'hero-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '2',
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #92003b 0%, #667eea 100%)',
                'padding' => ['top' => 80, 'right' => 40, 'bottom' => 80, 'left' => 40],
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'hero-content-' . uniqid(),
                    'widgetType' => 'container',
                    'settings' => ['columns' => '1'],
                    'children' => [
                        [
                            'id' => 'hero-title-' . uniqid(),
                            'widgetType' => 'heading',
                            'settings' => [
                                'title' => 'Summer Collection 2025',
                                'html_tag' => 'h1',
                                'color' => '#ffffff',
                                'font_size' => 48,
                                'font_weight' => '700',
                                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]
                            ]
                        ],
                        [
                            'id' => 'hero-text-' . uniqid(),
                            'widgetType' => 'text',
                            'settings' => [
                                'content' => 'Discover our latest arrivals with up to 50% off. Limited time offer on premium fashion and accessories.',
                                'color' => '#ffffff',
                                'font_size' => 18,
                                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 30, 'left' => 0]
                            ]
                        ],
                        [
                            'id' => 'hero-btn-' . uniqid(),
                            'widgetType' => 'button',
                            'settings' => [
                                'text' => 'Shop Now →',
                                'bg_color' => '#ffffff',
                                'text_color' => '#92003b',
                                'size' => 'large',
                                'align' => 'left'
                            ]
                        ]
                    ]
                ],
                [
                    'id' => 'hero-image-' . uniqid(),
                    'widgetType' => 'image',
                    'settings' => [
                        'url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80',
                        'align' => 'center'
                    ]
                ]
            ]
        ];
        
        // Featured Products Section
        $elements[] = [
            'id' => 'featured-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => [
                'title' => 'Featured Products',
                'html_tag' => 'h2',
                'color' => '#1a1a1a',
                'font_size' => 36,
                'align' => 'center',
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ]
        ];
        
        // Product Grid
        $elements[] = [
            'id' => 'products-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '4',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 40, 'bottom' => 60, 'left' => 40],
                'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]
            ],
            'children' => [
                [
                    'id' => 'product1-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://via.placeholder.com/400x400/FF6B6B/ffffff?text=Watch',
                        'title' => 'Premium Watch',
                        'description' => '$299.00',
                        'button_text' => 'Add to Cart',
                        'show_button' => 'yes',
                        'text_align' => 'center'
                    ]
                ],
                [
                    'id' => 'product2-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://via.placeholder.com/400x400/4ECDC4/ffffff?text=Sunglasses',
                        'title' => 'Designer Sunglasses',
                        'description' => '$149.00',
                        'button_text' => 'Add to Cart',
                        'show_button' => 'yes',
                        'text_align' => 'center'
                    ]
                ],
                [
                    'id' => 'product3-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://via.placeholder.com/400x400/45B7D1/ffffff?text=Sneakers',
                        'title' => 'Leather Sneakers',
                        'description' => '$189.00',
                        'button_text' => 'Add to Cart',
                        'show_button' => 'yes',
                        'text_align' => 'center'
                    ]
                ],
                [
                    'id' => 'product4-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => [
                        'image_url' => 'https://via.placeholder.com/400x400/96CEB4/ffffff?text=Backpack',
                        'title' => 'Designer Backpack',
                        'description' => '$129.00',
                        'button_text' => 'Add to Cart',
                        'show_button' => 'yes',
                        'text_align' => 'center'
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
                'show_card' => 'no',
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
     * Build E-Commerce Product Detail Page
     */
    private function build_ecommerce_product_page() {
        $elements = [];
        
        // Header
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
                    'widgetType' => 'heading',
                    'settings' => ['title' => 'ELITE SHOP', 'html_tag' => 'h2', 'color' => '#1a1a1a', 'font_size' => 24, 'font_weight' => '700']
                ],
                [
                    'id' => 'search-' . uniqid(),
                    'widgetType' => 'text',
                    'settings' => ['content' => 'Search products...', 'align' => 'center', 'color' => '#666']
                ],
                [
                    'id' => 'cart-' . uniqid(),
                    'widgetType' => 'button',
                    'settings' => ['text' => '🛒 Cart', 'bg_color' => '#92003b', 'text_color' => '#fff', 'align' => 'right']
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
        
        // Related Products
        $elements[] = [
            'id' => 'related-title-' . uniqid(),
            'widgetType' => 'heading',
            'settings' => ['title' => 'You May Also Like', 'html_tag' => 'h2', 'font_size' => 32, 'align' => 'center', 'margin' => ['top' => 0, 'right' => 0, 'bottom' => 40, 'left' => 0]]
        ];
        
        $elements[] = [
            'id' => 'related-products-' . uniqid(),
            'widgetType' => 'container',
            'settings' => [
                'columns' => '4',
                'column_gap' => 30,
                'padding' => ['top' => 0, 'right' => 40, 'bottom' => 60, 'left' => 40]
            ],
            'children' => [
                [
                    'id' => 'rel-product1-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', 'title' => 'Wireless Earbuds', 'description' => '$99.00', 'button_text' => 'View', 'text_align' => 'center']
                ],
                [
                    'id' => 'rel-product2-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=400&h=400&fit=crop', 'title' => 'Smart Speaker', 'description' => '$149.00', 'button_text' => 'View', 'text_align' => 'center']
                ],
                [
                    'id' => 'rel-product3-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&h=400&fit=crop', 'title' => 'Smart Watch', 'description' => '$349.00', 'button_text' => 'View', 'text_align' => 'center']
                ],
                [
                    'id' => 'rel-product4-' . uniqid(),
                    'widgetType' => 'image-box',
                    'settings' => ['image_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400&h=400&fit=crop', 'title' => 'Gaming Laptop', 'description' => '$1,299.00', 'button_text' => 'View', 'text_align' => 'center']
                ]
            ]
        ];
        
        return $elements;
    }
    
    /**
     * Build E-Commerce Homepage
     */
    private function build_ecommerce_homepage() {
        $elements = [];
        
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
                    'widgetType' => 'text',
                    'settings' => ['content' => '🎉 Summer Sale - Up to 50% OFF on all items!', 'color' => '#ffffff', 'font_size' => 13]
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
}

// Initialize
ProBuilder_Templates_Library::instance();

