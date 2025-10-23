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
        $this->start_controls_section('section_pricing', [
            'label' => __('Pricing', 'probuilder'),
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
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'Basic Plan');
        $currency = $this->get_settings('currency', '$');
        $price = $this->get_settings('price', '29');
        $period = $this->get_settings('period', 'per month');
        $features = $this->get_settings('features', []);
        $button_text = $this->get_settings('button_text', 'Get Started');
        $button_link = $this->get_settings('button_link', '#');
        $featured = $this->get_settings('featured', 'no');
        
        $box_style = 'border: 2px solid ' . ($featured === 'yes' ? '#0073aa' : '#e5e5e5') . '; ';
        $box_style .= 'padding: 40px 30px; text-align: center; background: #ffffff; position: relative;';
        
        echo '<div class="probuilder-pricing-table" style="' . $box_style . '">';
        
        if ($featured === 'yes') {
            echo '<div class="probuilder-pricing-badge" style="position: absolute; top: 20px; right: 20px; background: #0073aa; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold;">POPULAR</div>';
        }
        
        // Title
        echo '<h3 style="font-size: 24px; margin: 0 0 20px 0;">' . esc_html($title) . '</h3>';
        
        // Price
        echo '<div class="probuilder-pricing-price" style="margin-bottom: 30px;">';
        echo '<span style="font-size: 24px; vertical-align: top;">' . esc_html($currency) . '</span>';
        echo '<span style="font-size: 60px; font-weight: bold; line-height: 1;">' . esc_html($price) . '</span>';
        echo '<div style="color: #666; font-size: 14px; margin-top: 5px;">' . esc_html($period) . '</div>';
        echo '</div>';
        
        // Features
        echo '<ul class="probuilder-pricing-features" style="list-style: none; margin: 0 0 30px 0; padding: 0;">';
        foreach ($features as $feature) {
            echo '<li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;">' . esc_html($feature['text']) . '</li>';
        }
        echo '</ul>';
        
        // Button
        $button_style = 'background: ' . ($featured === 'yes' ? '#0073aa' : '#333333') . '; ';
        $button_style .= 'color: white; padding: 15px 40px; text-decoration: none; display: inline-block; border-radius: 5px; font-weight: bold;';
        
        echo '<a href="' . esc_url($button_link) . '" class="probuilder-pricing-button" style="' . $button_style . '">' . esc_html($button_text) . '</a>';
        
        echo '</div>';
    }
}

