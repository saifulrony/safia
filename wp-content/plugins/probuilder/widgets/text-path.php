<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Text_Path_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'text-path'; }
    public function get_title() { return __('Text Path', 'probuilder'); }
    public function get_icon() { return 'fa fa-bezier-curve'; }
    public function get_categories() { return ['advanced']; }
    protected function register_controls() {
        $this->add_control('text', ['label' => 'Text', 'type' => 'text', 'default' => 'Curved Text']);
        $this->add_control('path', ['label' => 'Path', 'type' => 'select', 'options' => ['curve' => 'Curve', 'wave' => 'Wave', 'circle' => 'Circle'], 'default' => 'curve']);
        $this->start_style_tab();
        $this->add_control('color', ['label' => 'Color', 'type' => 'color', 'default' => '#333']);
        $this->add_control('size', ['label' => 'Size', 'type' => 'slider', 'default' => 24, 'min' => 12, 'max' => 100]);
        $this->end_style_tab();
    }
    protected function render() {
        $s = $this->get_settings();
        echo '<div class="pb-text-path"><svg viewBox="0 0 500 100"><path id="curve' . uniqid() . '" d="M 50,50 Q 250,20 450,50" fill="transparent"/><text fill="' . $s['color'] . '" font-size="' . $s['size'] . '"><textPath href="#curve' . uniqid() . '">' . esc_html($s['text']) . '</textPath></text></svg></div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Text_Path_Widget());

