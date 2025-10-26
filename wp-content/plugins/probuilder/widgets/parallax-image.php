<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Parallax_Image_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'parallax-image'; }
    public function get_title() { return __('Parallax Image', 'probuilder'); }
    public function get_icon() { return 'fa fa-mountain'; }
    public function get_categories() { return ['advanced']; }
    protected function register_controls() {
        $this->add_control('image', ['label' => 'Image', 'type' => 'media', 'default' => '']);
        $this->add_control('height', ['label' => 'Height', 'type' => 'slider', 'default' => 400, 'min' => 200, 'max' => 1000]);
        $this->add_control('speed', ['label' => 'Parallax Speed', 'type' => 'slider', 'default' => 0.5, 'min' => 0.1, 'max' => 2, 'step' => 0.1]);
    }
    protected function render() {
        $s = $this->get_settings();
        $id = 'parallax-' . uniqid();
        echo '<div id="' . $id . '" class="pb-parallax" style="height:' . $s['height'] . 'px;overflow:hidden;position:relative"><div class="pb-parallax-bg" style="background-image:url(' . esc_url($s['image']) . ');background-size:cover;background-position:center;height:150%;width:100%;position:absolute"></div></div>';
        echo '<script>window.addEventListener("scroll",function(){var e=document.getElementById("' . $id . '"),r=e.getBoundingClientRect(),o=r.top,s=window.innerHeight;if(o<s&&o>-r.height){var t=(s-o)/(s+r.height)*100*' . $s['speed'] . ';e.querySelector(".pb-parallax-bg").style.transform="translateY("+t+"px)"}});</script>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Parallax_Image_Widget());

