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
        $s = $this->get_settings();
        echo '<style>' . wp_strip_all_tags($s['css_code']) . '</style>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Custom_CSS_Widget());

