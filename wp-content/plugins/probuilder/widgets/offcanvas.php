<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Offcanvas_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'offcanvas'; }
    public function get_title() { return __('Offcanvas Menu', 'probuilder'); }
    public function get_icon() { return 'fa fa-bars'; }
    public function get_categories() { return ['advanced']; }
    protected function register_controls() {
        $this->add_control('position', ['label' => 'Position', 'type' => 'select', 'options' => ['left' => 'Left', 'right' => 'Right'], 'default' => 'right']);
        $this->add_control('trigger_text', ['label' => 'Trigger Text', 'type' => 'text', 'default' => '☰ Menu']);
        $this->add_control('content', ['label' => 'Content', 'type' => 'textarea', 'default' => 'Offcanvas content here']);
        $this->start_style_tab();
        $this->add_control('panel_bg', ['label' => 'Panel Background', 'type' => 'color', 'default' => '#ffffff']);
        $this->add_control('panel_width', ['label' => 'Panel Width', 'type' => 'slider', 'default' => 300, 'min' => 200, 'max' => 600]);
        $this->end_style_tab();
    }
    protected function render() {
        $s = $this->get_settings();
        $id = 'offcanvas-' . uniqid();
        echo '<button class="pb-offcanvas-trigger" onclick="document.getElementById(\'' . $id . '\').classList.add(\'open\')">' . esc_html($s['trigger_text']) . '</button>';
        echo '<div id="' . $id . '" class="pb-offcanvas pb-pos-' . $s['position'] . '" style="width:' . $s['panel_width'] . 'px;background:' . $s['panel_bg'] . '"><button class="pb-close" onclick="this.parentElement.classList.remove(\'open\')">×</button><div class="pb-offcanvas-content">' . wpautop($s['content']) . '</div></div>';
        echo '<div class="pb-offcanvas-overlay" onclick="document.getElementById(\'' . $id . '\').classList.remove(\'open\')"></div>';
        echo '<style>.pb-offcanvas{position:fixed;top:0;height:100vh;transform:translateX(-100%);transition:0.3s;z-index:99999;padding:20px;overflow-y:auto}.pb-offcanvas.pb-pos-right{left:auto;right:0;transform:translateX(100%)}.pb-offcanvas.open{transform:translateX(0)}.pb-offcanvas-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.5);opacity:0;pointer-events:none;transition:0.3s;z-index:99998}.pb-offcanvas.open~.pb-offcanvas-overlay{opacity:1;pointer-events:all}.pb-close{position:absolute;top:10px;right:10px;background:none;border:none;font-size:30px;cursor:pointer}</style>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Offcanvas_Widget());

