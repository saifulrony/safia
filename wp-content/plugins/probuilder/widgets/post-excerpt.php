<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Post_Excerpt_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'post-excerpt'; }
    public function get_title() { return __('Post Excerpt', 'probuilder'); }
    public function get_icon() { return 'fa fa-file-alt'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('length', ['label' => 'Excerpt Length (words)', 'type' => 'number', 'default' => 55, 'min' => 10, 'max' => 200]);
        $this->add_control('show_more', ['label' => 'Show Read More', 'type' => 'switcher', 'default' => true]);
        $this->add_control('more_text', ['label' => 'Read More Text', 'type' => 'text', 'default' => 'Read More']);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $excerpt = wp_trim_words(get_the_excerpt(), $s['length']);
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-post-excerpt">' . $excerpt;
        if ($s['show_more']) echo ' <a href="' . get_permalink() . '">' . esc_html($s['more_text']) . '</a>';
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Excerpt_Widget());

