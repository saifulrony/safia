<?php
/**
 * Shortcode Widget
 * Display any WordPress shortcode within ProBuilder
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Shortcode extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'shortcode';
        $this->title = __('Shortcode', 'probuilder');
        $this->icon = 'fa fa-code';
        $this->category = 'content';
        $this->keywords = ['shortcode', 'code', 'widget', 'embed'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_shortcode', [
            'label' => __('Shortcode', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('shortcode', [
            'label' => __('Shortcode', 'probuilder'),
            'type' => 'textarea',
            'default' => '[contact-form-7 id="123"]',
            'description' => __('Enter your shortcode here. Examples: [gallery], [contact-form-7 id="123"], [woocommerce_cart], etc.', 'probuilder'),
            'placeholder' => '[your-shortcode]',
        ]);
        
        $this->add_control('shortcode_info', [
            'type' => 'raw_html',
            'raw' => '<div style="padding: 15px; background: #e8f5e9; border-left: 4px solid #4caf50; margin-top: 10px; border-radius: 3px;">
                <strong style="display: block; margin-bottom: 5px; color: #2e7d32;">Common Shortcodes:</strong>
                <ul style="margin: 5px 0 0 0; padding-left: 20px; color: #555; font-size: 13px; line-height: 1.8;">
                    <li><code>[contact-form-7]</code> - Contact Form 7</li>
                    <li><code>[gallery]</code> - WordPress Gallery</li>
                    <li><code>[woocommerce_cart]</code> - WooCommerce Cart</li>
                    <li><code>[elementor-template id="123"]</code> - Elementor Template</li>
                    <li>Any plugin shortcode from Widgets → Appearance</li>
                </ul>
            </div>',
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Container Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20],
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ],
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Text Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Advanced Section
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced',
        ]);
        
        $this->add_control('custom_class', [
            'label' => __('Custom CSS Class', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'description' => __('Add custom CSS class for additional styling', 'probuilder'),
        ]);
        
        $this->add_control('custom_id', [
            'label' => __('Custom CSS ID', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'description' => __('Add custom CSS ID', 'probuilder'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $shortcode = $this->get_settings('shortcode', '');
        $bg_color = $this->get_settings('bg_color', '');
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20]);
        $margin = $this->get_settings('margin', ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]);
        $border_radius = $this->get_settings('border_radius', 0);
        $text_align = $this->get_settings('text_align', 'left');
        $custom_class = $this->get_settings('custom_class', '');
        $custom_id = $this->get_settings('custom_id', '');
        
        // Build container style
        $container_style = 'text-align: ' . esc_attr($text_align) . '; ';
        
        if ($bg_color) {
            $container_style .= 'background: ' . esc_attr($bg_color) . '; ';
        }
        
        $container_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        $container_style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px; ';
        
        if ($border_radius) {
            $container_style .= 'border-radius: ' . esc_attr($border_radius) . 'px; ';
        }
        
        // Build container attributes
        $container_attrs = 'class="probuilder-shortcode-widget ' . esc_attr($custom_class) . '" ';
        if ($custom_id) {
            $container_attrs .= 'id="' . esc_attr($custom_id) . '" ';
        }
        $container_attrs .= 'style="' . $container_style . '"';
        
        echo '<div ' . $container_attrs . '>';
        
        if (empty(trim($shortcode))) {
            // Show placeholder if no shortcode
            echo '<div style="padding: 30px; background: #fff3cd; border: 2px dashed #ffc107; border-radius: 8px; text-align: center; color: #856404;">';
            echo '<i class="fa fa-code" style="font-size: 48px; margin-bottom: 15px; opacity: 0.6;"></i>';
            echo '<div style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">No Shortcode Entered</div>';
            echo '<div style="font-size: 14px;">Please enter a shortcode in the widget settings to display content here.</div>';
            echo '<div style="margin-top: 15px; font-size: 13px; opacity: 0.8;">Examples: [gallery], [contact-form-7 id="123"], [woocommerce_cart]</div>';
            echo '</div>';
        } else {
            // Clean and process the shortcode
            $shortcode = trim($shortcode);
            
            // Check if shortcode is valid format
            if (strpos($shortcode, '[') === 0 && strpos($shortcode, ']') !== false) {
                // Execute the shortcode
                $output = do_shortcode($shortcode);
                
                // Check if shortcode actually produced output
                if (empty(trim(strip_tags($output))) || $output === $shortcode) {
                    // Shortcode didn't execute or produced no output
                    echo '<div style="padding: 25px; background: #fff3e0; border: 2px dashed #ff9800; border-radius: 8px; text-align: center; color: #e65100;">';
                    echo '<i class="fa fa-exclamation-triangle" style="font-size: 40px; margin-bottom: 15px; opacity: 0.7;"></i>';
                    echo '<div style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">Shortcode Not Found or No Output</div>';
                    echo '<div style="font-size: 13px; margin-bottom: 10px;">The shortcode <code style="background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 3px;">' . esc_html($shortcode) . '</code> may not exist or produced no output.</div>';
                    echo '<div style="font-size: 12px; opacity: 0.8;">Make sure the plugin providing this shortcode is installed and activated.</div>';
                    echo '</div>';
                } else {
                    // Shortcode executed successfully
                    echo $output;
                }
            } else {
                // Invalid shortcode format
                echo '<div style="padding: 25px; background: #ffebee; border: 2px dashed #f44336; border-radius: 8px; text-align: center; color: #c62828;">';
                echo '<i class="fa fa-times-circle" style="font-size: 40px; margin-bottom: 15px; opacity: 0.7;"></i>';
                echo '<div style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">Invalid Shortcode Format</div>';
                echo '<div style="font-size: 13px;">Shortcodes must start with <code>[</code> and end with <code>]</code></div>';
                echo '<div style="font-size: 13px; margin-top: 10px;">You entered: <code style="background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 3px;">' . esc_html($shortcode) . '</code></div>';
                echo '</div>';
            }
        }
        
        echo '</div>';
    }
}

