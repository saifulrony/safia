<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Sidebar_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'sidebar'; }
    public function get_title() { return __('Sidebar', 'probuilder'); }
    public function get_icon() { return 'fa fa-columns'; }
    public function get_categories() { return ['wordpress']; }
    
    protected function register_controls() {
        $this->add_control('sidebar', ['label' => __('Choose Sidebar', 'probuilder'), 'type' => 'select', 'options' => $this->get_sidebars(), 'default' => 'sidebar-1']);
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        if (is_active_sidebar($s['sidebar'])) {
            echo '<div class="' . esc_attr($wrapper_classes) . ' pb-sidebar-widget">';
            dynamic_sidebar($s['sidebar']);
            echo '</div>';
        } else {
            echo '<p>No sidebar found</p>';
        }
    }
    
    private function get_sidebars() {
        global $wp_registered_sidebars;
        $options = [];
        foreach ($wp_registered_sidebars as $sidebar) {
            $options[$sidebar['id']] = $sidebar['name'];
        }
        return $options;
    }
}

