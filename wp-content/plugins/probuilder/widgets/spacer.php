<?php
/**
 * Spacer Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Spacer extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'spacer';
        $this->title = __('Spacer', 'probuilder');
        $this->icon = 'fa fa-arrows-up-down';
        $this->category = 'basic';
        $this->keywords = ['spacer', 'space', 'gap'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_spacer', [
            'label' => __('Spacer', 'probuilder'),
        ]);
        
        $this->add_control('height', [
            'label' => __('Height', 'probuilder'),
            'type' => 'slider',
            'default' => 50,
            'range' => [
                'px' => ['min' => 0, 'max' => 500],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $height = $this->get_settings('height', 50);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $style = 'height: ' . esc_attr($height) . 'px;';
        if ($inline_styles) {
            $style .= ' ' . $inline_styles;
        }
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-spacer" ' . $wrapper_attributes . ' style="' . $style . '"></div>';
    }
}

