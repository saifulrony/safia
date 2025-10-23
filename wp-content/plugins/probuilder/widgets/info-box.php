<?php
/**
 * Info Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Info_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'info-box';
        $this->title = __('Info Box', 'probuilder');
        $this->icon = 'fa fa-circle-info';
        $this->category = 'content';
        $this->keywords = ['info', 'box', 'notice', 'tip'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('number', [
            'label' => __('Number/Icon Text', 'probuilder'),
            'type' => 'text',
            'default' => '01',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Step One', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is a description of the first step.', 'probuilder'),
        ]);
        
        $this->add_control('accent_color', [
            'label' => __('Accent Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $number = $this->get_settings('number', '01');
        $title = $this->get_settings('title', 'Step One');
        $description = $this->get_settings('description', '');
        $accent_color = $this->get_settings('accent_color', '#92003b');
        
        echo '<div class="probuilder-info-box" style="display: flex; gap: 20px; padding: 25px; background: #fff; border: 1px solid #e5e5e5; border-radius: 8px;">';
        
        // Number circle
        echo '<div class="info-number" style="flex-shrink: 0; width: 70px; height: 70px; background: ' . esc_attr($accent_color) . '; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: bold;">';
        echo esc_html($number);
        echo '</div>';
        
        // Content
        echo '<div class="info-content" style="flex: 1;">';
        echo '<h3 style="margin: 0 0 10px 0; font-size: 20px; color: #333;">' . esc_html($title) . '</h3>';
        echo '<p style="margin: 0; color: #666; line-height: 1.6; font-size: 14px;">' . esc_html($description) . '</p>';
        echo '</div>';
        
        echo '</div>';
    }
}

