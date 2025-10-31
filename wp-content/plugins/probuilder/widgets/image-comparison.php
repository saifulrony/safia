<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Image_Comparison_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'image-comparison'; }
    public function get_title() { return __('Image Comparison Slider', 'probuilder'); }
    public function get_icon() { return 'fa fa-adjust'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('before_image', ['label' => 'Before Image', 'type' => 'media', 'default' => '']);
        $this->add_control('after_image', ['label' => 'After Image', 'type' => 'media', 'default' => '']);
        $this->add_control('position', ['label' => 'Initial Position (%)', 'type' => 'slider', 'default' => 50, 'min' => 0, 'max' => 100]);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $id = 'compare-' . uniqid();
        $style = 'position:relative;overflow:hidden;user-select:none;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div id="' . $id . '" class="' . esc_attr($wrapper_classes) . ' pb-image-comparison" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '"><img src="' . esc_url($s['before_image']) . '" style="width:100%;display:block"><div class="after-img" style="position:absolute;top:0;left:0;height:100%;width:' . $s['position'] . '%;overflow:hidden"><img src="' . esc_url($s['after_image']) . '" style="width:100vw;max-width:none"></div><div class="slider" style="position:absolute;top:0;left:' . $s['position'] . '%;width:4px;height:100%;background:#fff;cursor:ew-resize;box-shadow:0 0 10px rgba(0,0,0,0.5)"></div></div>';
        echo '<script>var c=document.getElementById("' . $id . '"),s=c.querySelector(".slider"),a=c.querySelector(".after-img");s.addEventListener("mousedown",function(e){var m=function(ev){var p=Math.max(0,Math.min(100,(ev.clientX-c.offsetLeft)/c.offsetWidth*100));s.style.left=p+"%";a.style.width=p+"%"};document.addEventListener("mousemove",m);document.addEventListener("mouseup",function(){document.removeEventListener("mousemove",m)},{once:true})});</script>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Image_Comparison_Widget());

