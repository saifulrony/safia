<?php
/**
 * Cart Options Output
 * Generates dynamic CSS based on cart customization options
 *
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output cart custom styles
 */
function ecocommerce_pro_cart_custom_styles() {
    // Only output on cart or checkout pages
    if (!is_cart() && !is_checkout() && !is_admin()) {
        return;
    }
    
    $options = get_option('ecocommerce_pro_cart_options', ecocommerce_pro_get_default_cart_options());
    
    // Start building CSS
    $css = '<style id="ecocommerce-cart-custom-styles">';
    
    // Cart Table Header
    if (!empty($options['header_bg'])) {
        $css .= '.woocommerce table.cart thead { background: ' . esc_attr($options['header_bg']) . ' !important; }';
    }
    
    if (!empty($options['header_text_color'])) {
        $css .= '.woocommerce table.cart th { color: ' . esc_attr($options['header_text_color']) . ' !important; }';
    }
    
    if (!empty($options['header_font_size'])) {
        $css .= '.woocommerce table.cart th { font-size: ' . absint($options['header_font_size']) . 'px !important; }';
    }
    
    if (!empty($options['header_padding'])) {
        $css .= '.woocommerce table.cart th { padding: ' . absint($options['header_padding']) . 'px 24px !important; }';
    }
    
    // Cart Table Body
    if (!empty($options['row_bg'])) {
        $css .= '.woocommerce table.cart tbody tr { background: ' . esc_attr($options['row_bg']) . ' !important; }';
    }
    
    if (!empty($options['row_hover_bg'])) {
        $css .= '.woocommerce table.cart tbody tr:hover { background: ' . esc_attr($options['row_hover_bg']) . ' !important; }';
    }
    
    if (!empty($options['border_color'])) {
        $css .= '.woocommerce table.cart tbody tr { border-bottom-color: ' . esc_attr($options['border_color']) . ' !important; }';
    }
    
    if (!empty($options['text_color'])) {
        $css .= '.woocommerce table.cart td { color: ' . esc_attr($options['text_color']) . ' !important; }';
    }
    
    if (!empty($options['cell_padding'])) {
        $css .= '.woocommerce table.cart td { padding: ' . absint($options['cell_padding']) . 'px !important; }';
    }
    
    if (!empty($options['border_radius'])) {
        $css .= '.woocommerce-cart-form { border-radius: ' . absint($options['border_radius']) . 'px !important; }';
    }
    
    // Product Thumbnail
    if (!empty($options['thumbnail_size'])) {
        $size = absint($options['thumbnail_size']);
        $css .= '.woocommerce table.cart .product-thumbnail img { width: ' . $size . 'px !important; height: ' . $size . 'px !important; }';
    }
    
    if (!empty($options['thumbnail_radius'])) {
        $css .= '.woocommerce table.cart .product-thumbnail img { border-radius: ' . absint($options['thumbnail_radius']) . 'px !important; }';
    }
    
    if (empty($options['thumbnail_shadow'])) {
        $css .= '.woocommerce table.cart .product-thumbnail img { box-shadow: none !important; }';
    }
    
    if (empty($options['thumbnail_hover_effect'])) {
        $css .= '.woocommerce table.cart .product-thumbnail img:hover { transform: none !important; }';
    }
    
    // Remove Button
    if (!empty($options['remove_bg'])) {
        $css .= '.woocommerce table.cart .product-remove .remove { background: ' . esc_attr($options['remove_bg']) . ' !important; }';
    }
    
    if (!empty($options['remove_color'])) {
        $css .= '.woocommerce table.cart .product-remove .remove { color: ' . esc_attr($options['remove_color']) . ' !important; }';
    }
    
    if (!empty($options['remove_hover_bg'])) {
        $css .= '.woocommerce table.cart .product-remove .remove:hover { background: ' . esc_attr($options['remove_hover_bg']) . ' !important; }';
    }
    
    if (!empty($options['remove_hover_color'])) {
        $css .= '.woocommerce table.cart .product-remove .remove:hover { color: ' . esc_attr($options['remove_hover_color']) . ' !important; }';
    }
    
    if (!empty($options['remove_size'])) {
        $size = absint($options['remove_size']);
        $css .= '.woocommerce table.cart .product-remove .remove { width: ' . $size . 'px !important; height: ' . $size . 'px !important; }';
    }
    
    if (!empty($options['remove_radius'])) {
        $css .= '.woocommerce table.cart .product-remove .remove { border-radius: ' . absint($options['remove_radius']) . 'px !important; }';
    }
    
    // Quantity Input
    if (!empty($options['quantity_width'])) {
        $css .= '.woocommerce table.cart .quantity input { width: ' . absint($options['quantity_width']) . 'px !important; }';
    }
    
    if (!empty($options['quantity_border'])) {
        $css .= '.woocommerce table.cart .quantity { border-color: ' . esc_attr($options['quantity_border']) . ' !important; }';
    }
    
    if (!empty($options['quantity_border_width'])) {
        $css .= '.woocommerce table.cart .quantity { border-width: ' . absint($options['quantity_border_width']) . 'px !important; }';
    }
    
    if (!empty($options['quantity_radius'])) {
        $css .= '.woocommerce table.cart .quantity { border-radius: ' . absint($options['quantity_radius']) . 'px !important; }';
    }
    
    // Cart Totals
    if (!empty($options['totals_bg'])) {
        $css .= '.woocommerce .cart_totals { background: ' . esc_attr($options['totals_bg']) . ' !important; }';
    }
    
    if (!empty($options['totals_border'])) {
        $css .= '.woocommerce .cart_totals { border-color: ' . esc_attr($options['totals_border']) . ' !important; }';
    }
    
    if (!empty($options['totals_title_color'])) {
        $css .= '.woocommerce .cart_totals h2 { color: ' . esc_attr($options['totals_title_color']) . ' !important; }';
    }
    
    if (!empty($options['totals_amount_color'])) {
        $css .= '.woocommerce .cart_totals table .order-total td { color: ' . esc_attr($options['totals_amount_color']) . ' !important; }';
    }
    
    if (!empty($options['totals_padding'])) {
        $css .= '.woocommerce .cart_totals { padding: ' . absint($options['totals_padding']) . 'px !important; }';
    }
    
    if (!empty($options['totals_radius'])) {
        $css .= '.woocommerce .cart_totals { border-radius: ' . absint($options['totals_radius']) . 'px !important; }';
    }
    
    // Checkout Button
    if (!empty($options['checkout_bg'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { background: ' . esc_attr($options['checkout_bg']) . ' !important; }';
    }
    
    if (!empty($options['checkout_color'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { color: ' . esc_attr($options['checkout_color']) . ' !important; }';
    }
    
    if (!empty($options['checkout_font_size'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { font-size: ' . absint($options['checkout_font_size']) . 'px !important; }';
    }
    
    if (!empty($options['checkout_font_weight'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { font-weight: ' . esc_attr($options['checkout_font_weight']) . ' !important; }';
    }
    
    if (!empty($options['checkout_padding'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { padding: ' . absint($options['checkout_padding']) . 'px 32px !important; }';
    }
    
    if (!empty($options['checkout_radius'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { border-radius: ' . absint($options['checkout_radius']) . 'px !important; }';
    }
    
    if (!empty($options['checkout_uppercase'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { text-transform: uppercase !important; }';
    } else {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { text-transform: none !important; }';
    }
    
    if (empty($options['checkout_shadow'])) {
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button { box-shadow: none !important; }';
        $css .= '.woocommerce .wc-proceed-to-checkout .checkout-button:hover { box-shadow: none !important; }';
    }
    
    $css .= '</style>';
    
    echo $css;
}
add_action('wp_head', 'ecocommerce_pro_cart_custom_styles');
add_action('admin_head', 'ecocommerce_pro_cart_custom_styles');

