<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Custom_CSS_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'custom-css'; }
    public function get_title() { return __('Custom CSS', 'probuilder'); }
    public function get_icon() { return 'fa fa-css3-alt'; }
    public function get_categories() { return ['advanced']; }
    protected function register_controls() {
        $this->add_control('css_code', ['label' => 'CSS Code', 'type' => 'textarea', 'default' => '.my-class { color: red; }']);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-custom-css" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '"><style>' . wp_strip_all_tags($s['css_code']) . '</style></div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Custom_CSS_Widget());

