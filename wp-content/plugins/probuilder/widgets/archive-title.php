<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Archive_Title_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'archive-title'; }
    public function get_title() { return __('Archive Title', 'probuilder'); }
    public function get_icon() { return 'fa fa-archive'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('tag', ['label' => 'HTML Tag', 'type' => 'select', 'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3'], 'default' => 'h1']);
        $this->start_style_tab();
        $this->add_control('color', ['label' => 'Color', 'type' => 'color', 'default' => '#333']);
        $this->end_style_tab();
    }
    protected function render() {
        if (!is_archive()) { echo '<p>View on archive page</p>'; return; }
        $s = $this->get_settings();
        echo '<' . $s['tag'] . ' class="pb-archive-title" style="color:' . $s['color'] . '">' . get_the_archive_title() . '</' . $s['tag'] . '>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Archive_Title_Widget());

