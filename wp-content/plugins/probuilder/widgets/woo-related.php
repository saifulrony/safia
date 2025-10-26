<?php
/**
 * WooCommerce Related Products Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Woo_Related_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'woo-related';
        $this->title = __('Related Products', 'probuilder');
        $this->icon = 'fa fa-th-list';
        $this->category = 'woocommerce';
        $this->keywords = ['woocommerce', 'related', 'products'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('products_count', [
            'label' => __('Products to Show', 'probuilder'),
            'type' => 'number',
            'default' => 4,
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'options' => ['2' => '2', '3' => '3', '4' => '4'],
            'default' => '4',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        if (!class_exists('WooCommerce')) {
            echo '<p style="padding:20px;background:#f5f5f5">WooCommerce not installed</p>';
            return;
        }
        
        $product_id = get_the_ID();
        $count = $this->get_settings('products_count', 4);
        $columns = $this->get_settings('columns', 4);
        
        $related_ids = wc_get_related_products($product_id, $count);
        
        if (empty($related_ids)) {
            echo '<p style="padding:20px;background:#f5f5f5">No related products found</p>';
            return;
        }
        
        echo '<div style="display:grid;grid-template-columns:repeat(' . $columns . ',1fr);gap:20px">';
        
        foreach ($related_ids as $related_id) {
            $related_product = wc_get_product($related_id);
            if (!$related_product) continue;
            
            echo '<div style="border:1px solid #eee;border-radius:8px;overflow:hidden;text-align:center">';
            echo '<a href="' . get_permalink($related_id) . '">';
            echo $related_product->get_image('woocommerce_thumbnail', ['style' => 'width:100%;height:auto']);
            echo '</a>';
            echo '<div style="padding:15px">';
            echo '<h4 style="margin:0 0 10px;font-size:16px"><a href="' . get_permalink($related_id) . '" style="color:#333;text-decoration:none">' . $related_product->get_name() . '</a></h4>';
            echo '<div style="font-size:18px;font-weight:700;color:#0073aa">' . $related_product->get_price_html() . '</div>';
            echo '<a href="?add-to-cart=' . $related_id . '" style="display:inline-block;margin-top:10px;background:#0073aa;color:#fff;padding:8px 16px;border-radius:4px;text-decoration:none;font-size:14px">Add to Cart</a>';
            echo '</div></div>';
        }
        
        echo '</div>';
    }
}

if (class_exists('WooCommerce')) {
}
