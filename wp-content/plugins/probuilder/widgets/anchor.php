<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Anchor_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'anchor'; }
    public function get_title() { return __('Anchor/Menu Anchor', 'probuilder'); }
    public function get_icon() { return 'fa fa-anchor'; }
    public function get_categories() { return ['basic']; }
    
    protected function register_controls() {
        $this->add_control('anchor_id', ['label' => __('Anchor ID', 'probuilder'), 'type' => 'text', 'default' => 'section-1', 'placeholder' => 'my-anchor']);
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        echo '<div id="' . esc_attr($s['anchor_id']) . '" class="' . esc_attr($wrapper_classes) . ' pb-anchor" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '"></div>';
    }
}

