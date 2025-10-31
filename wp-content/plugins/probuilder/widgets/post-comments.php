<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Post_Comments_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'post-comments'; }
    public function get_title() { return __('Post Comments', 'probuilder'); }
    public function get_icon() { return 'fa fa-comments'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('show_form', ['label' => 'Show Comment Form', 'type' => 'switcher', 'default' => true]);
        $this->add_control('show_count', ['label' => 'Show Comment Count', 'type' => 'switcher', 'default' => true]);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        if (!is_single() && !is_page()) { echo '<p>View on a post/page</p>'; return; }
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-post-comments">';
        if ($s['show_count']) echo '<h3>' . get_comments_number() . ' Comments</h3>';
        comments_template();
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Comments_Widget());

