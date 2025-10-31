<?php
/**
 * Pricing Table Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Pricing_Table extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'pricing-table';
        $this->title = __('Pricing Table', 'probuilder');
        $this->icon = 'fa fa-table';
        $this->category = 'content';
        $this->keywords = ['pricing', 'table', 'price'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_pricing', [
            'label' => __('Pricing', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Basic Plan', 'probuilder'),
        ]);
        
        $this->add_control('currency', [
            'label' => __('Currency', 'probuilder'),
            'type' => 'text',
            'default' => '$',
        ]);
        
        $this->add_control('price', [
            'label' => __('Price', 'probuilder'),
            'type' => 'text',
            'default' => '29',
        ]);
        
        $this->add_control('period', [
            'label' => __('Period', 'probuilder'),
            'type' => 'text',
            'default' => __('per month', 'probuilder'),
        ]);
        
        $this->add_control('features', [
            'label' => __('Features', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                ['text' => __('Feature 1', 'probuilder')],
                ['text' => __('Feature 2', 'probuilder')],
                ['text' => __('Feature 3', 'probuilder')],
            ],
            'fields' => [
                [
                    'name' => 'text',
                    'label' => __('Text', 'probuilder'),
                    'type' => 'text',
                ],
            ],
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Get Started', 'probuilder'),
        ]);
        
        $this->add_control('button_link', [
            'label' => __('Button Link', 'probuilder'),
            'type' => 'url',
            'default' => '#',
        ]);
        
        $this->add_control('featured', [
            'label' => __('Featured', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e5e5e5',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('price_color', [
            'label' => __('Price Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('button_bg_color', [
            'label' => __('Button Background', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('button_text_color', [
            'label' => __('Button Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('featured_color', [
            'label' => __('Featured Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
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
        $title = $this->get_settings('title', 'Basic Plan');
        $currency = $this->get_settings('currency', '$');
        $price = $this->get_settings('price', '29');
        $period = $this->get_settings('period', 'per month');
        $features = $this->get_settings('features', []);
        $button_text = $this->get_settings('button_text', 'Get Started');
        $button_link = $this->get_settings('button_link', '#');
        $featured = $this->get_settings('featured', 'no');
        
        // Style settings
        $border_color = $this->get_settings('border_color', '#e5e5e5');
        $title_color = $this->get_settings('title_color', '#333333');
        $price_color = $this->get_settings('price_color', '#333333');
        $button_bg_color = $this->get_settings('button_bg_color', '#333333');
        $button_text_color = $this->get_settings('button_text_color', '#ffffff');
        $featured_color = $this->get_settings('featured_color', '#0073aa');
        
        // Apply featured color if featured is enabled
        $active_border_color = ($featured === 'yes') ? $featured_color : $border_color;
        $active_button_bg = ($featured === 'yes') ? $featured_color : $button_bg_color;
        
        $box_style = 'border: 2px solid ' . esc_attr($active_border_color) . '; ';
        $box_style .= 'padding: 40px 30px; text-align: center; background: #ffffff; position: relative; border-radius: 8px;';
        
        if ($inline_styles) $box_style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-pricing-table" ' . $wrapper_attributes . ' style="' . esc_attr($box_style) . '">';
        
        if ($featured === 'yes') {
            echo '<div class="probuilder-pricing-badge" style="position: absolute; top: 20px; right: 20px; background: ' . esc_attr($featured_color) . '; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold;">POPULAR</div>';
        }
        
        // Title
        echo '<h3 style="font-size: 24px; margin: 0 0 20px 0; font-weight: 600; color: ' . esc_attr($title_color) . ';">' . esc_html($title) . '</h3>';
        
        // Price
        echo '<div class="probuilder-pricing-price" style="margin-bottom: 30px; color: ' . esc_attr($price_color) . ';">';
        echo '<span style="font-size: 24px; vertical-align: top; font-weight: 600;">' . esc_html($currency) . '</span>';
        echo '<span style="font-size: 60px; font-weight: bold; line-height: 1;">' . esc_html($price) . '</span>';
        echo '<div style="color: #666; font-size: 14px; margin-top: 5px;">' . esc_html($period) . '</div>';
        echo '</div>';
        
        // Features
        echo '<ul class="probuilder-pricing-features" style="list-style: none; margin: 0 0 30px 0; padding: 0; text-align: left;">';
        foreach ($features as $feature) {
            if (!empty($feature['text'])) {
                echo '<li style="padding: 10px 0; padding-left: 25px; border-bottom: 1px solid #f0f0f0; color: #555; position: relative;">';
                echo '<span class="dashicons dashicons-yes" style="position: absolute; left: 0; top: 10px; color: ' . esc_attr($featured === 'yes' ? $featured_color : '#0073aa') . '; font-size: 18px;"></span>';
                echo esc_html($feature['text']);
                echo '</li>';
            }
        }
        echo '</ul>';
        
        // Button
        $button_style = 'background: ' . esc_attr($active_button_bg) . '; ';
        $button_style .= 'color: ' . esc_attr($button_text_color) . '; ';
        $button_style .= 'padding: 15px 40px; text-decoration: none; display: inline-block; border-radius: 5px; font-weight: bold; transition: all 0.3s;';
        
        echo '<a href="' . esc_url($button_link) . '" class="probuilder-pricing-button" style="' . $button_style . '">' . esc_html($button_text) . '</a>';
        
        echo '</div>';
    }
}

