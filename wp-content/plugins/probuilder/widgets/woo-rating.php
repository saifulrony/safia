<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Woo_Rating_Widget extends ProBuilder_Base_Widget {
    public function __construct() {
        $this->name = 'woo-rating';
        $this->title = __('Product Rating', 'probuilder');
        $this->icon = 'fa fa-star';
        $this->category = 'woocommerce';
    }
    protected function register_controls() {
        $this->start_controls_section('section_content', ['label' => __('Content', 'probuilder')]);
        $this->add_control('show_count', ['label' => 'Show Review Count', 'type' => 'switcher', 'default' => true]);
        $this->end_controls_section();
    }
    protected function render() {
        if (!class_exists('WooCommerce')) { echo '<p>WooCommerce not installed</p>'; return; }
        $product = wc_get_product(get_the_ID());
        if (!$product) { echo '<p>View on product page</p>'; return; }
        $rating = $product->get_average_rating();
        $count = $product->get_review_count();
        if (!$rating) { echo '<p style="color:#999">No ratings yet</p>'; return; }
        echo '<div style="display:flex;align-items:center;gap:10px">';
        echo '<div style="color:#ffc107;font-size:18px">';
        for ($i = 1; $i <= 5; $i++) echo $i <= round($rating) ? '★' : '☆';
        echo '</div>';
        if ($this->get_settings('show_count', true)) echo '<span style="color:#666;font-size:14px">(' . $count . ' reviews)</span>';
        echo '</div>';
    }
}
