<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Back_To_Top_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'back-to-top'; }
    public function get_title() { return __('Back to Top Button', 'probuilder'); }
    public function get_icon() { return 'fa fa-arrow-up'; }
    public function get_categories() { return ['content']; }
    
    protected function register_controls() {
        $this->add_control('position', ['label' => __('Position', 'probuilder'), 'type' => 'select', 'options' => ['bottom-right' => 'Bottom Right', 'bottom-left' => 'Bottom Left'], 'default' => 'bottom-right']);
        $this->add_control('offset', ['label' => __('Offset', 'probuilder'), 'type' => 'slider', 'default' => 20, 'min' => 0, 'max' => 100]);
        $this->add_control('show_after', ['label' => __('Show After Scroll (px)', 'probuilder'), 'type' => 'number', 'default' => 300]);
        
        $this->start_style_tab();
        $this->add_control('button_color', ['label' => __('Button Color', 'probuilder'), 'type' => 'color', 'default' => '#0073aa']);
        $this->add_control('icon_color', ['label' => __('Icon Color', 'probuilder'), 'type' => 'color', 'default' => '#ffffff']);
        $this->add_control('size', ['label' => __('Size', 'probuilder'), 'type' => 'slider', 'default' => 50, 'min' => 30, 'max' => 100]);
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
        
        $id = 'back-to-top-' . uniqid();
        $pos = explode('-', $s['position']);
        $style = 'position:fixed;' . $pos[0] . ':' . $s['offset'] . 'px;' . $pos[1] . ':' . $s['offset'] . 'px;width:' . $s['size'] . 'px;height:' . $s['size'] . 'px;background:' . $s['button_color'] . ';color:' . $s['icon_color'] . ';border-radius:50%;display:none;align-items:center;justify-content:center;cursor:pointer;z-index:9999;box-shadow:0 2px 10px rgba(0,0,0,0.2);';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div id="' . $id . '" class="' . esc_attr($wrapper_classes) . ' pb-back-to-top" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">↑</div>';
        echo '<script>var btn=document.getElementById("' . $id . '");window.addEventListener("scroll",function(){btn.style.display=window.scrollY>' . $s['show_after'] . '?"flex":"none"});btn.addEventListener("click",function(){window.scrollTo({top:0,behavior:"smooth"})});</script>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Back_To_Top_Widget());

