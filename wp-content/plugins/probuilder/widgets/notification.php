<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Notification_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'notification'; }
    public function get_title() { return __('Notification Bar', 'probuilder'); }
    public function get_icon() { return 'fa fa-bell'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('message', ['label' => 'Message', 'type' => 'textarea', 'default' => 'Important announcement!']);
        $this->add_control('type', ['label' => 'Type', 'type' => 'select', 'options' => ['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'error' => 'Error'], 'default' => 'info']);
        $this->add_control('dismissible', ['label' => 'Dismissible', 'type' => 'switcher', 'default' => true]);
        $this->add_control('position', ['label' => 'Position', 'type' => 'select', 'options' => ['top' => 'Top', 'bottom' => 'Bottom'], 'default' => 'top']);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $colors = ['info' => '#2196f3', 'success' => '#4caf50', 'warning' => '#ff9800', 'error' => '#f44336'];
        $id = 'notif-' . uniqid();
        $style = 'position:fixed;' . $s['position'] . ':0;left:0;right:0;background:' . $colors[$s['type']] . ';color:#fff;padding:15px;text-align:center;z-index:99999;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div id="' . $id . '" class="' . esc_attr($wrapper_classes) . ' pb-notification type-' . $s['type'] . '" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        echo esc_html($s['message']);
        if ($s['dismissible']) echo '<button onclick="document.getElementById(\'' . $id . '\').style.display=\'none\'" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:24px;cursor:pointer">×</button>';
        echo '</div>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Notification_Widget());

