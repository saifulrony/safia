<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Instagram_Feed_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'instagram-feed'; }
    public function get_title() { return __('Instagram Feed', 'probuilder'); }
    public function get_icon() { return 'fa fa-instagram'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('username', ['label' => 'Username', 'type' => 'text', 'default' => 'instagram']);
        $this->add_control('limit', ['label' => 'Photos to Show', 'type' => 'number', 'default' => 6, 'min' => 1, 'max' => 20]);
        $this->add_control('columns', ['label' => 'Columns', 'type' => 'select', 'options' => ['2' => '2', '3' => '3', '4' => '4', '6' => '6'], 'default' => '3']);
    }
    protected function render() {
        $s = $this->get_settings();
        echo '<div class="pb-instagram-feed" style="display:grid;grid-template-columns:repeat(' . $s['columns'] . ',1fr);gap:10px">';
        for ($i = 0; $i < $s['limit']; $i++) {
            echo '<div class="pb-instagram-item" style="aspect-ratio:1;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center">📷</div>';
        }
        echo '</div><p style="text-align:center;margin-top:10px;font-size:12px;color:#999">Placeholder - Connect Instagram API</p>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Instagram_Feed_Widget());

