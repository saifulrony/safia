<?php
/**
 * Lottie Animation Widget - Fixed Version
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Lottie_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'lottie';
        $this->title = __('Lottie Animation', 'probuilder');
        $this->icon = 'fa fa-film';
        $this->category = 'advanced';
        $this->keywords = ['lottie', 'animation', 'json'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('animation_url', [
            'label' => __('Animation URL', 'probuilder'),
            'type' => 'url',
            'default' => 'https://assets3.lottiefiles.com/packages/lf20_UJNc2t.json',
            'description' => 'Get free animations from lottiefiles.com',
        ]);
        
        $this->add_control('loop', [
            'label' => __('Loop', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('autoplay', [
            'label' => __('Autoplay', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('width', [
            'label' => __('Width', 'probuilder'),
            'type' => 'slider',
            'default' => 300,
            'range' => ['px' => ['min' => 100, 'max' => 800]],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $url = $this->get_settings('animation_url', '');
        $loop = $this->get_settings('loop', true);
        $autoplay = $this->get_settings('autoplay', true);
        $width = $this->get_settings('width', 300);
        
        if (empty($url)) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">Please add Lottie animation URL</p>';
            return;
        }
        
        $animation_id = 'lottie-' . uniqid();
        
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $style = 'text-align:center;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-lottie" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '"><div id="' . $animation_id . '" style="width:' . $width . 'px;max-width:100%;margin:0 auto"></div></div>';
        
        // Enqueue Lottie
        wp_enqueue_script('lottie-player', 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js', [], '5.12.2', true);
        
        // Initialize
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lottie !== "undefined") {
                lottie.loadAnimation({
                    container: document.getElementById("' . esc_js($animation_id) . '"),
                    renderer: "svg",
                    loop: ' . ($loop ? 'true' : 'false') . ',
                    autoplay: ' . ($autoplay ? 'true' : 'false') . ',
                    path: "' . esc_js($url) . '"
                });
            }
        });
        </script>';
    }
}
