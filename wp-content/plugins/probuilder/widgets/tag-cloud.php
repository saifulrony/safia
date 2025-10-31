<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Tag_Cloud_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'tag-cloud'; }
    public function get_title() { return __('Tag Cloud', 'probuilder'); }
    public function get_icon() { return 'fa fa-tags'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('limit', ['label' => 'Number of Tags', 'type' => 'number', 'default' => 20]);
        $this->start_style_tab();
        $this->add_control('color', ['label' => 'Tag Color', 'type' => 'color', 'default' => '#0073aa']);
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
        
        $tags = get_tags(['number' => $s['limit'], 'orderby' => 'count', 'order' => 'DESC']);
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-tag-cloud">';
        foreach ($tags as $tag) {
            $size = 12 + ($tag->count / 2);
            echo '<a href="' . get_tag_link($tag) . '" style="color:' . $s['color'] . ';font-size:' . min($size, 24) . 'px;margin:5px;display:inline-block">' . $tag->name . '</a>';
        }
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Tag_Cloud_Widget());

