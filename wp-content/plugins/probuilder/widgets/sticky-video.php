<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Sticky_Video_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'sticky-video'; }
    public function get_title() { return __('Sticky Video', 'probuilder'); }
    public function get_icon() { return 'fa fa-video'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('video_url', ['label' => 'Video URL', 'type' => 'url', 'default' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->add_control('sticky_position', ['label' => 'Sticky Position', 'type' => 'select', 'options' => ['bottom-right' => 'Bottom Right', 'bottom-left' => 'Bottom Left', 'top-right' => 'Top Right', 'top-left' => 'Top Left'], 'default' => 'bottom-right']);
        $this->add_control('enable_minimize', ['label' => 'Enable Minimize', 'type' => 'switcher', 'default' => true]);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $video_id = $this->extract_video_id($s['video_url']);
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-sticky-video" data-position="' . $s['sticky_position'] . '"><iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe></div>';
        echo '<style>.pb-sticky-video{position:fixed;bottom:20px;right:20px;width:400px;max-width:90vw;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,0.3)}.pb-sticky-video iframe{width:100%;height:225px}</style>';
    }
    private function extract_video_id($url) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches);
        return $matches[1] ?? '';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Sticky_Video_Widget());

