<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Twitter_Embed_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'twitter-embed'; }
    public function get_title() { return __('Twitter/X Embed', 'probuilder'); }
    public function get_icon() { return 'fa fa-twitter'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('url', ['label' => 'Tweet URL', 'type' => 'url', 'default' => '']);
        $this->add_control('theme', ['label' => 'Theme', 'type' => 'select', 'options' => ['light' => 'Light', 'dark' => 'Dark'], 'default' => 'light']);
    }
    protected function render() {
        $s = $this->get_settings();
        echo '<div class="pb-twitter-embed"><blockquote class="twitter-tweet" data-theme="' . $s['theme'] . '"><a href="' . esc_url($s['url']) . '"></a></blockquote></div>';
        echo '<script async src="https://platform.twitter.com/widgets.js"></script>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Twitter_Embed_Widget());

