<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Calendly_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'calendly'; }
    public function get_title() { return __('Calendly Booking', 'probuilder'); }
    public function get_icon() { return 'fa fa-calendar-check'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('url', ['label' => 'Calendly URL', 'type' => 'url', 'default' => 'https://calendly.com/your-link']);
        $this->add_control('height', ['label' => 'Height', 'type' => 'slider', 'default' => 630, 'min' => 400, 'max' => 1000]);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-calendly-widget"><iframe src="' . esc_url($s['url']) . '" width="100%" height="' . $s['height'] . '" frameborder="0"></iframe></div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Calendly_Widget());

