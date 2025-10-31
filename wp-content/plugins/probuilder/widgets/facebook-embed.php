<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Facebook_Embed_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'facebook-embed'; }
    public function get_title() { return __('Facebook Embed', 'probuilder'); }
    public function get_icon() { return 'fa fa-facebook'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('url', ['label' => 'Facebook Post/Page URL', 'type' => 'url', 'default' => '']);
        $this->add_control('type', ['label' => 'Type', 'type' => 'select', 'options' => ['post' => 'Post', 'page' => 'Page', 'video' => 'Video'], 'default' => 'post']);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-facebook-embed"><iframe src="https://www.facebook.com/plugins/' . $s['type'] . '.php?href=' . urlencode($s['url']) . '&width=500" width="500" height="600" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true"></iframe></div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Facebook_Embed_Widget());

