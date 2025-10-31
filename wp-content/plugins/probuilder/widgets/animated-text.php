<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Animated_Text_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'animated-text'; }
    public function get_title() { return __('Animated Text', 'probuilder'); }
    public function get_icon() { return 'fa fa-magic'; }
    public function get_categories() { return ['advanced']; }
    protected function register_controls() {
        $this->add_control('text', ['label' => 'Text', 'type' => 'text', 'default' => 'Animated Text']);
        $this->add_control('animation', ['label' => 'Animation', 'type' => 'select', 'options' => ['typing' => 'Typing', 'wave' => 'Wave', 'glitch' => 'Glitch', 'neon' => 'Neon'], 'default' => 'typing']);
        $this->start_style_tab();
        $this->add_control('color', ['label' => 'Color', 'type' => 'color', 'default' => '#0073aa']);
        $this->add_control('size', ['label' => 'Size', 'type' => 'slider', 'default' => 36, 'min' => 16, 'max' => 100]);
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
        
        $id = 'anim-text-' . uniqid();
        $style = 'color:' . $s['color'] . ';font-size:' . $s['size'] . 'px;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<h2 id="' . $id . '" class="' . esc_attr($wrapper_classes) . ' pb-animated-text anim-' . $s['animation'] . '" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">' . esc_html($s['text']) . '</h2>';
        echo '<style>.anim-typing{overflow:hidden;border-right:3px solid;white-space:nowrap;animation:typing 3s steps(40) infinite,blink 0.75s step-end infinite}@keyframes typing{from{width:0}to{width:100%}}@keyframes blink{50%{border-color:transparent}}.anim-wave{animation:wave 2s ease-in-out infinite}@keyframes wave{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}.anim-glitch{animation:glitch 1s infinite}@keyframes glitch{0%,100%{transform:translate(0)}25%{transform:translate(-2px,2px)}75%{transform:translate(2px,-2px)}}.anim-neon{text-shadow:0 0 10px currentColor,0 0 20px currentColor,0 0 30px currentColor;animation:neon 1.5s ease-in-out infinite}@keyframes neon{0%,100%{opacity:1}50%{opacity:0.8}}</style>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Animated_Text_Widget());

