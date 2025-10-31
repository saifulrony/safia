<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Scroll_Snap_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'scroll-snap'; }
    public function get_title() { return __('Scroll Snap Section', 'probuilder'); }
    public function get_icon() { return 'fa fa-layer-group'; }
    public function get_categories() { return ['layout']; }
    protected function register_controls() {
        $this->add_control('sections', ['label' => 'Sections', 'type' => 'repeater', 'default' => [['bg' => '#f5f5f5'], ['bg' => '#e5e5e5']], 'fields' => [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'default' => 'Section'],
            ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'default' => 'Section content'],
            ['name' => 'bg', 'label' => 'Background', 'type' => 'color', 'default' => '#ffffff'],
        ]]);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-scroll-snap-container" style="scroll-snap-type:y mandatory;overflow-y:scroll;height:100vh' . ($inline_styles ? ' ' . $inline_styles : '') . '">';
        foreach ($s['sections'] as $sec) {
            echo '<section class="pb-snap-section" style="scroll-snap-align:start;height:100vh;display:flex;align-items:center;justify-content:center;background:' . $sec['bg'] . '"><div><h2>' . esc_html($sec['title']) . '</h2><p>' . esc_html($sec['content']) . '</p></div></section>';
        }
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Scroll_Snap_Widget());

