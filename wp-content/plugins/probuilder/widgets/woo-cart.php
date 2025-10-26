<?php
/**
 * WooCommerce Cart Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Woo_Cart extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'woo-cart';
    }
    
    public function get_title() {
        return __('Cart', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa-shopping-bag';
    }
    
    public function get_categories() {
        return ['woocommerce'];
    }
    
    public function get_keywords() {
        return ['woocommerce', 'cart', 'shop', 'checkout'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Cart', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('show_icon', [
            'label' => __('Show Icon', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_count', [
            'label' => __('Show Item Count', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_total', [
            'label' => __('Show Total', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('icon_size', [
            'label' => __('Icon Size', 'probuilder'),
            'type' => 'slider',
            'default' => 24,
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#344047',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->settings;
        if (!class_exists('WooCommerce')) {
            echo '<div class="probuilder-woo-notice">';
            echo '<p>' . __('WooCommerce is not installed.', 'probuilder') . '</p>';
            echo '</div>';
            return;
        }
        
        $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        $cart_total = WC()->cart ? WC()->cart->get_cart_total() : '$0.00';
        $cart_url = wc_get_cart_url();
        
        $icon_size = $settings['icon_size'] ?? 24;
        $icon_color = $settings['icon_color'] ?? '#344047';
        $badge_bg = $settings['badge_bg_color'] ?? '#e74c3c';
        $badge_text = $settings['badge_text_color'] ?? '#ffffff';
        
        echo '<div class="probuilder-woo-cart" style="display: inline-flex; align-items: center; gap: 10px;">';
        echo '<a href="' . esc_url($cart_url) . '" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit;">';
        
        // Icon with badge
        if ($settings['show_icon'] ?? true) {
            echo '<div style="position: relative;">';
            echo '<i class="' . esc_attr($settings['icon'] ?? 'fa fa-shopping-cart') . '" style="font-size: ' . esc_attr($icon_size) . 'px; color: ' . esc_attr($icon_color) . ';"></i>';
            
            if (($settings['show_count'] ?? true) && $cart_count > 0) {
                echo '<span style="position: absolute; top: -8px; right: -8px; background: ' . esc_attr($badge_bg) . '; color: ' . esc_attr($badge_text) . '; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">' . esc_html($cart_count) . '</span>';
            }
            
            echo '</div>';
        }
        
        // Total
        if ($settings['show_total'] ?? true) {
            echo '<span style="font-weight: 600;">' . $cart_total . '</span>';
        }
        
        echo '</a>';
        echo '</div>';
    }
}

