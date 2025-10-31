<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Woo_Meta_Widget extends ProBuilder_Base_Widget {
    public function __construct() {
        $this->name = 'woo-meta';
        $this->title = __('Product Meta', 'probuilder');
        $this->icon = 'fa fa-info-circle';
        $this->category = 'woocommerce';
    }
    protected function register_controls() {
        $this->start_controls_section('section_content', ['label' => __('Content', 'probuilder')]);
        $this->add_control('show_sku', ['label' => 'Show SKU', 'type' => 'switcher', 'default' => true]);
        $this->add_control('show_categories', ['label' => 'Show Categories', 'type' => 'switcher', 'default' => true]);
        $this->end_controls_section();
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        if (!class_exists('WooCommerce')) { echo '<p>WooCommerce not installed</p>'; return; }
        $product = wc_get_product(get_the_ID());
        if (!$product) { echo '<p>View on product page</p>'; return; }
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $style = 'font-size:14px;color:#666;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-woo-meta" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        if ($this->get_settings('show_sku', true) && $product->get_sku()) {
            echo '<div style="margin-bottom:8px"><strong style="color:#999">SKU:</strong> ' . esc_html($product->get_sku()) . '</div>';
        }
        if ($this->get_settings('show_categories', true)) {
            $categories = wc_get_product_category_list(get_the_ID(), ', ');
            if ($categories) echo '<div><strong style="color:#999">Categories:</strong> ' . $categories . '</div>';
        }
        echo '</div>';
    }
}
