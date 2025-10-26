<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Post_Date_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'post-date'; }
    public function get_title() { return __('Post Date', 'probuilder'); }
    public function get_icon() { return 'fa fa-calendar-alt'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('format', ['label' => 'Date Format', 'type' => 'select', 'options' => ['F j, Y' => 'January 1, 2025', 'Y-m-d' => '2025-01-01', 'm/d/Y' => '01/01/2025', 'd/m/Y' => '01/01/2025'], 'default' => 'F j, Y']);
        $this->add_control('show_icon', ['label' => 'Show Icon', 'type' => 'switcher', 'default' => true]);
        $this->start_style_tab();
        $this->add_control('color', ['label' => 'Color', 'type' => 'color', 'default' => '#666']);
        $this->end_style_tab();
    }
    protected function render() {
        $s = $this->get_settings();
        echo '<div class="pb-post-date" style="color:' . $s['color'] . '">';
        if ($s['show_icon']) echo '<i class="pb-icon-calendar"></i> ';
        echo get_the_date($s['format']);
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Post_Date_Widget());

