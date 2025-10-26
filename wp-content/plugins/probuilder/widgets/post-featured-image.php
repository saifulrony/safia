<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Post_Featured_Image_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'post-featured-image'; }
    public function get_title() { return __('Post Featured Image', 'probuilder'); }
    public function get_icon() { return 'fa fa-camera'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('size', ['label' => 'Image Size', 'type' => 'select', 'options' => ['thumbnail' => 'Thumbnail', 'medium' => 'Medium', 'large' => 'Large', 'full' => 'Full'], 'default' => 'full']);
        $this->add_control('link', ['label' => 'Link to Post', 'type' => 'switcher', 'default' => false]);
        $this->start_style_tab();
        $this->add_control('border_radius', ['label' => 'Border Radius', 'type' => 'slider', 'default' => 0, 'min' => 0, 'max' => 50]);
        $this->end_style_tab();
    }
    protected function render() {
        $s = $this->get_settings();
        if (!has_post_thumbnail()) { echo '<p>No featured image</p>'; return; }
        $img = get_the_post_thumbnail(null, $s['size'], ['style' => 'border-radius:' . $s['border_radius'] . 'px;width:100%;height:auto']);
        echo $s['link'] ? '<a href="' . get_permalink() . '">' . $img . '</a>' : $img;
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Featured_Image_Widget());

