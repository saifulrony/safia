<?php
/**
 * WooCommerce Add to Cart Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Woo_Add_To_Cart_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'woo-add-to-cart';
        $this->title = __('Add to Cart', 'probuilder');
        $this->icon = 'fa fa-cart-plus';
        $this->category = 'woocommerce';
        $this->keywords = ['woocommerce', 'cart', 'buy'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Add to Cart', 'probuilder'),
        ]);
        
        $this->add_control('show_quantity', [
            'label' => __('Show Quantity Selector', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        if (!class_exists('WooCommerce')) {
            echo '<p style="padding:20px;background:#f5f5f5">WooCommerce not installed</p>';
            return;
        }
        
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        
        if (!$product) {
            echo '<p style="padding:20px;background:#f5f5f5">View on a product page</p>';
            return;
        }
        
        $button_text = $this->get_settings('button_text', 'Add to Cart');
        $show_qty = $this->get_settings('show_quantity', true);
        
        echo '<div class="pb-woo-add-to-cart">';
        echo '<form action="' . esc_url($product->add_to_cart_url()) . '" method="post" style="display:flex;gap:10px;align-items:center">';
        
        if ($show_qty && !$product->is_sold_individually()) {
            woocommerce_quantity_input([
                'min_value' => 1,
                'max_value' => $product->get_max_purchase_quantity(),
            ], $product);
        }
        
        echo '<button type="submit" name="add-to-cart" value="' . esc_attr($product_id) . '" style="background:#0073aa;color:#fff;border:none;padding:12px 24px;border-radius:4px;cursor:pointer;font-weight:600">';
        echo esc_html($button_text);
        echo '</button>';
        
        echo '</form></div>';
    }
}

if (class_exists('WooCommerce')) {
}
