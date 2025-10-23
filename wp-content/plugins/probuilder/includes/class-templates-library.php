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
}

// Initialize
ProBuilder_Templates_Library::instance();

