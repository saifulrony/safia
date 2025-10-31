<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Site_Logo_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'site-logo'; }
    public function get_title() { return __('Site Logo', 'probuilder'); }
    public function get_icon() { return 'fa fa-image'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('logo', ['label' => 'Custom Logo', 'type' => 'media', 'default' => '']);
        $this->add_control('link', ['label' => 'Link to Homepage', 'type' => 'switcher', 'default' => true]);
        $this->start_style_tab();
        $this->add_control('width', ['label' => 'Width', 'type' => 'slider', 'default' => 150, 'min' => 50, 'max' => 500]);
        $this->end_style_tab();
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $logo = !empty($s['logo']) ? $s['logo'] : get_theme_mod('custom_logo');
        if (!$logo) $logo = get_bloginfo('name');
        $img_style = 'width:' . $s['width'] . 'px;height:auto;';
        $img = is_numeric($logo) ? wp_get_attachment_image($logo, 'full', false, ['style' => $img_style]) : '<span>' . $logo . '</span>';
        $output = $s['link'] ? '<a href="' . home_url() . '">' . $img . '</a>' : $img;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-site-logo" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '">' . $output . '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Site_Logo_Widget());

