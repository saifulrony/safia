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
        $height = $this->get_settings('height', 50);
        
        echo '<div class="probuilder-spacer" style="height: ' . esc_attr($height) . 'px;"></div>';
    }
}

