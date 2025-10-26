<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Post_Author_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'post-author'; }
    public function get_title() { return __('Post Author', 'probuilder'); }
    public function get_icon() { return 'fa fa-user'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('show_avatar', ['label' => 'Show Avatar', 'type' => 'switcher', 'default' => true]);
        $this->add_control('avatar_size', ['label' => 'Avatar Size', 'type' => 'slider', 'default' => 32, 'min' => 16, 'max' => 128]);
        $this->add_control('link', ['label' => 'Link to Author Page', 'type' => 'switcher', 'default' => true]);
    }
    protected function render() {
        $s = $this->get_settings();
        echo '<div class="pb-post-author" style="display:flex;align-items:center;gap:10px">';
        if ($s['show_avatar']) echo get_avatar(get_the_author_meta('ID'), $s['avatar_size'], '', '', ['style' => 'border-radius:50%']);
        if ($s['link']) echo '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_the_author() . '</a>';
        else echo get_the_author();
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Author_Widget());

