<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Reading_Progress_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'reading-progress'; }
    public function get_title() { return __('Reading Progress Bar', 'probuilder'); }
    public function get_icon() { return 'fa fa-chart-line'; }
    public function get_categories() { return ['content']; }
    
    protected function register_controls() {
        $this->add_control('position', ['label' => __('Position', 'probuilder'), 'type' => 'select', 'options' => ['top' => 'Top', 'bottom' => 'Bottom'], 'default' => 'top']);
        $this->add_control('height', ['label' => __('Height', 'probuilder'), 'type' => 'slider', 'default' => 4, 'min' => 1, 'max' => 20]);
        
        $this->start_style_tab();
        $this->add_control('color', ['label' => __('Color', 'probuilder'), 'type' => 'color', 'default' => '#0073aa']);
        $this->add_control('background', ['label' => __('Background', 'probuilder'), 'type' => 'color', 'default' => '#eeeeee']);
        $this->end_style_tab();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $id = 'reading-progress-' . uniqid();
        $style = 'position:fixed;' . $s['position'] . ':0;left:0;width:0%;height:' . $s['height'] . 'px;background:' . $s['color'] . ';z-index:9999;transition:width 0.3s;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div id="' . $id . '" class="' . esc_attr($wrapper_classes) . ' pb-reading-progress" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '"></div>';
        echo '<script>window.addEventListener("scroll",function(){var s=document.documentElement.scrollTop,h=document.documentElement.scrollHeight-document.documentElement.clientHeight,p=(s/h)*100;document.getElementById("' . $id . '").style.width=p+"%"});</script>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Reading_Progress_Widget());

