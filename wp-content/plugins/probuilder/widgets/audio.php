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
        $s = $this->get_settings();
        $src = $s['audio_source'] === 'url' ? $s['audio_url'] : $s['audio_file'];
        echo '<audio controls' . ($s['autoplay'] ? ' autoplay' : '') . ($s['loop'] ? ' loop' : '') . ' style="width:100%"><source src="' . esc_url($src) . '"></audio>';
    }
}

