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
        $s = $this->get_settings();
        echo '<' . $s['tag'] . ' class="pb-post-title" style="color:' . $s['color'] . ';font-size:' . $s['size'] . 'px">' . get_the_title() . '</' . $s['tag'] . '>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Title_Widget());

