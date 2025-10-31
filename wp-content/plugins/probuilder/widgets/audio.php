<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Audio_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'audio'; }
    public function get_title() { return __('Audio Player', 'probuilder'); }
    public function get_icon() { return 'fa fa-music'; }
    public function get_categories() { return ['basic']; }
    
    protected function register_controls() {
        $this->add_control('audio_source', ['label' => __('Audio Source', 'probuilder'), 'type' => 'select', 'options' => ['url' => 'URL', 'upload' => 'Upload'], 'default' => 'upload']);
        $this->add_control('audio_url', ['label' => __('Audio URL', 'probuilder'), 'type' => 'url', 'condition' => ['audio_source' => 'url']]);
        $this->add_control('audio_file', ['label' => __('Upload Audio', 'probuilder'), 'type' => 'media', 'condition' => ['audio_source' => 'upload']]);
        $this->add_control('autoplay', ['label' => __('Autoplay', 'probuilder'), 'type' => 'switcher', 'default' => false]);
        $this->add_control('loop', ['label' => __('Loop', 'probuilder'), 'type' => 'switcher', 'default' => false]);
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $src = $s['audio_source'] === 'url' ? $s['audio_url'] : $s['audio_file'];
        $style = 'width:100%;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-audio" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '"><audio controls' . ($s['autoplay'] ? ' autoplay' : '') . ($s['loop'] ? ' loop' : '') . ' style="' . esc_attr($style) . '"><source src="' . esc_url($src) . '"></audio></div>';
    }
}

