<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Post_Title_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'post-title'; }
    public function get_title() { return __('Post Title', 'probuilder'); }
    public function get_icon() { return 'fa fa-heading'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('tag', ['label' => 'HTML Tag', 'type' => 'select', 'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4'], 'default' => 'h1']);
        $this->start_style_tab();
        $this->add_control('color', ['label' => 'Color', 'type' => 'color', 'default' => '#333']);
        $this->add_control('size', ['label' => 'Size', 'type' => 'slider', 'default' => 36, 'min' => 16, 'max' => 72]);
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
        
        $style = 'color:' . $s['color'] . ';font-size:' . $s['size'] . 'px;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        
        echo '<' . $s['tag'] . ' class="' . esc_attr($wrapper_classes) . ' pb-post-title" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">' . get_the_title() . '</' . $s['tag'] . '>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Title_Widget());

