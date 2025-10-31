<?php
/**
 * WooCommerce Product Reviews Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Woo_Reviews_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'woo-reviews';
        $this->title = __('Product Reviews', 'probuilder');
        $this->icon = 'fa fa-comments';
        $this->category = 'woocommerce';
        $this->keywords = ['woocommerce', 'reviews', 'ratings'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('reviews_count', [
            'label' => __('Reviews to Show', 'probuilder'),
            'type' => 'number',
            'default' => 5,
        ]);
        
        $this->add_control('show_rating', [
            'label' => __('Show Rating Stars', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        if (!class_exists('WooCommerce')) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">WooCommerce is not installed</p>';
            return;
        }
        
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        
        if (!$product) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">Please view on a product page</p>';
            return;
        }
        
        $count = $this->get_settings('reviews_count', 5);
        $show_rating = $this->get_settings('show_rating', true);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        $reviews = get_comments([
            'post_id' => $product_id,
            'status' => 'approve',
            'number' => $count,
        ]);
        
        if (empty($reviews)) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">No reviews yet</p>';
            return;
        }
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-woo-reviews">';
        
        foreach ($reviews as $review) {
            $rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
            
            echo '<div style="border:1px solid #eee;padding:20px;margin-bottom:15px;border-radius:8px' . ($inline_styles ? ' ' . $inline_styles : '') . '">';
            echo '<div style="display:flex;align-items:center;gap:15px;margin-bottom:10px">';
            echo get_avatar($review, 50, '', '', ['style' => 'border-radius:50%']);
            echo '<div>';
            echo '<strong>' . esc_html($review->comment_author) . '</strong><br>';
            
            if ($show_rating && $rating) {
                echo '<div style="color:#ffc107">';
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $rating ? '★' : '☆';
                }
                echo '</div>';
            }
            
            echo '</div></div>';
            echo '<div style="color:#666;line-height:1.6">' . wpautop($review->comment_content) . '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

if (class_exists('WooCommerce')) {
}
