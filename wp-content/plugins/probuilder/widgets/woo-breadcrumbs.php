<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Woo_Breadcrumbs_Widget extends ProBuilder_Base_Widget {
    public function __construct() {
        $this->name = 'woo-breadcrumbs';
        $this->title = __('Product Breadcrumbs', 'probuilder');
        $this->icon = 'fa fa-angle-double-right';
        $this->category = 'woocommerce';
    }
    protected function register_controls() {
        $this->start_controls_section('section_content', ['label' => __('Content', 'probuilder')]);
        $this->add_control('separator', ['label' => 'Separator', 'type' => 'text', 'default' => '/']);
        $this->end_controls_section();
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        if (!class_exists('WooCommerce')) { echo '<p style="padding:20px;background:#f5f5f5">WooCommerce not installed</p>'; return; }
        $sep = $this->get_settings('separator', '/');
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $nav_style = 'font-size:14px;color:#666;';
        if ($inline_styles) $nav_style .= ' ' . $inline_styles;
        $wrap_before = '<nav class="' . esc_attr($wrapper_classes) . ' pb-woo-breadcrumbs" ' . $wrapper_attributes . ' style="' . esc_attr($nav_style) . '"><ol style="display:flex;list-style:none;padding:0;margin:0;gap:8px">';
        woocommerce_breadcrumb(['delimiter' => ' <span style="color:#999;margin:0 8px">' . esc_html($sep) . '</span> ', 'wrap_before' => $wrap_before, 'wrap_after' => '</ol></nav>', 'before' => '<li>', 'after' => '</li>']);
    }
}
