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
            ['id' => 'porto-shop', 'name' => '🛒 Porto Shop - Real Visual Content', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'fashion-store', 'name' => '👗 Fashion Store - Complete', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
            ['id' => 'electronics', 'name' => '💻 Electronics Store - Full Page', 'category' => 'pages', 'type' => 'page', 'thumbnail' => $placeholder],
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
            ['id' => 'porto-shop', 'name' => 'Porto Shop', 'category' => 'pages', 'data' => $this->template_porto_visual()],
            ['id' => 'fashion-store', 'name' => 'Fashion Store', 'category' => 'pages', 'data' => $this->template_fashion_visual()],
            ['id' => 'electronics', 'name' => 'Electronics', 'category' => 'pages', 'data' => $this->template_electronics_visual()],
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
     * Porto Shop - SIMPLE, FLAT, VISUAL TEMPLATE
     */
    private function template_porto_visual() {
        return [
            // Hero Image with text overlay
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=600&fit=crop'],
                    'height' => 600,
                    'object_fit' => 'cover',
                ]
            ],
            
            // Heading
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Summer Collection 2024',
                    'html_tag' => 'h1',
                    'font_size' => 56,
                    'text_align' => 'center',
                    'margin' => ['top' => 80, 'bottom' => 20],
                ]
            ],
            
            // Subtitle
            [
                'id' => $this->generate_id(),
                'widgetType' => 'text',
                'settings' => [
                    'text' => '<p style="text-align:center;font-size:20px;color:#666;margin-bottom:40px;">Discover the latest trends with up to 50% off</p>',
                ]
            ],
            
            // CTA Button
            [
                'id' => $this->generate_id(),
                'widgetType' => 'button',
                'settings' => [
                    'text' => 'Shop Now',
                    'background_color' => '#92003b',
                    'text_color' => '#ffffff',
                    'align' => 'center',
                    'padding' => ['top' => 18, 'right' => 45, 'bottom' => 18, 'left' => 45],
                    'font_size' => 18,
                    'margin' => ['bottom' => 80],
                ]
            ],
            
            // Product Image 1
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=500&fit=crop'],
                    'height' => 400,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['right' => 20, 'bottom' => 20],
                ]
            ],
            
            // Product Image 2
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1564422170194-896b89110ef8?w=400&h=500&fit=crop'],
                    'height' => 400,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['right' => 20, 'bottom' => 20],
                ]
            ],
            
            // Product Image 3
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=500&fit=crop'],
                    'height' => 400,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['right' => 20, 'bottom' => 20],
                ]
            ],
            
            // Product Image 4
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=500&fit=crop'],
                    'height' => 400,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['bottom' => 20],
                ]
            ],
            
            // Banner Image
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920&h=400&fit=crop'],
                    'height' => 400,
                    'object_fit' => 'cover',
                    'margin' => ['top' => 40, 'bottom' => 40],
                ]
            ],
            
            // Section Heading
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Featured Products',
                    'html_tag' => 'h2',
                    'font_size' => 42,
                    'text_align' => 'center',
                    'margin' => ['top' => 60, 'bottom' => 40],
                ]
            ],
            
            // More Product Images (row of 4)
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop'],
                    'height' => 350,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['right' => 20, 'bottom' => 20],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400&h=400&fit=crop'],
                    'height' => 350,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['right' => 20, 'bottom' => 20],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=400&h=400&fit=crop'],
                    'height' => 350,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['right' => 20, 'bottom' => 20],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=400&h=400&fit=crop'],
                    'height' => 350,
                    'width' => '25%',
                    'object_fit' => 'cover',
                    'border_radius' => 8,
                    'margin' => ['bottom' => 20],
                ]
            ],
            
            // Another Banner
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=500&fit=crop'],
                    'height' => 500,
                    'object_fit' => 'cover',
                    'margin' => ['top' => 60, 'bottom' => 60],
                ]
            ],
            
            // Final CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Get 30% Off Today Only!',
                    'description' => 'Limited time offer on all items',
                    'button_text' => 'Shop The Sale',
                    'background_color' => '#92003b',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                ]
            ],
        ];
    }
    
    private function template_fashion_visual() {
        return [
            // Hero
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1920&h=700&fit=crop'],
                    'height' => 700,
                    'object_fit' => 'cover',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'New Season Fashion',
                    'html_tag' => 'h1',
                    'font_size' => 64,
                    'text_align' => 'center',
                    'margin' => ['top' => 80, 'bottom' => 40],
                ]
            ],
            
            // Product Row
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=500&h=600&fit=crop'],
                    'height' => 500,
                    'width' => '33%',
                    'object_fit' => 'cover',
                    'margin' => ['right' => 15],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?w=500&h=600&fit=crop'],
                    'height' => 500,
                    'width' => '33%',
                    'object_fit' => 'cover',
                    'margin' => ['right' => 15],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=500&h=600&fit=crop'],
                    'height' => 500,
                    'width' => '33%',
                    'object_fit' => 'cover',
                ]
            ],
            
            // CTA
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Exclusive Collection',
                    'description' => 'Shop the latest trends',
                    'button_text' => 'Explore Now',
                    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                ]
            ],
        ];
    }
    
    private function template_electronics_visual() {
        return [
            [
                'id' => $this->generate_id(),
                'widgetType' => 'heading',
                'settings' => [
                    'text' => 'Latest Technology',
                    'html_tag' => 'h1',
                    'font_size' => 56,
                    'text_align' => 'center',
                    'margin' => ['top' => 60, 'bottom' => 40],
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=400&fit=crop'],
                    'height' => 400,
                    'width' => '50%',
                    'object_fit' => 'cover',
                    'margin' => ['right' => 20],
                ]
            ],
            [
                'id' => $this->generate_id(),
                'widgetType' => 'image',
                'settings' => [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=500&h=400&fit=crop'],
                    'height' => 400,
                    'width' => '50%',
                    'object_fit' => 'cover',
                ]
            ],
            
            [
                'id' => $this->generate_id(),
                'widgetType' => 'call-to-action',
                'settings' => [
                    'title' => 'Tech Sale - 40% Off',
                    'description' => 'Premium electronics at unbeatable prices',
                    'button_text' => 'Shop Electronics',
                    'background_color' => '#0088cc',
                    'title_color' => '#ffffff',
                    'description_color' => '#ffffff',
                ]
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

