<?php
/**
 * Alert/Notice Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Alert extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'alert';
        $this->title = __('Alert Box', 'probuilder');
        $this->icon = 'fa fa-circle-exclamation';
        $this->category = 'basic';
        $this->keywords = ['alert', 'notice', 'warning', 'info'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_alert', [
            'label' => __('Alert', 'probuilder'),
        ]);
        
        $this->add_control('alert_type', [
            'label' => __('Type', 'probuilder'),
            'type' => 'select',
            'default' => 'info',
            'options' => [
                'info' => __('Info', 'probuilder'),
                'success' => __('Success', 'probuilder'),
                'warning' => __('Warning', 'probuilder'),
                'error' => __('Error', 'probuilder'),
            ],
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Information', 'probuilder'),
        ]);
        
        $this->add_control('message', [
            'label' => __('Message', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is an alert message.', 'probuilder'),
        ]);
        
        $this->add_control('dismissible', [
            'label' => __('Dismissible', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $type = $this->get_settings('alert_type', 'info');
        $title = $this->get_settings('title', 'Information');
        $message = $this->get_settings('message', '');
        $dismissible = $this->get_settings('dismissible', 'yes');
        
        $colors = [
            'info' => ['bg' => '#e3f2fd', 'border' => '#2196f3', 'text' => '#0d47a1', 'icon' => 'fa-circle-info'],
            'success' => ['bg' => '#e8f5e9', 'border' => '#4caf50', 'text' => '#1b5e20', 'icon' => 'fa-circle-check'],
            'warning' => ['bg' => '#fff3e0', 'border' => '#ff9800', 'text' => '#e65100', 'icon' => 'fa-triangle-exclamation'],
            'error' => ['bg' => '#ffebee', 'border' => '#f44336', 'text' => '#b71c1c', 'icon' => 'fa-circle-xmark'],
        ];
        
        $color_scheme = $colors[$type];
        
        $box_style = 'background: ' . $color_scheme['bg'] . '; ';
        $box_style .= 'border-left: 4px solid ' . $color_scheme['border'] . '; ';
        $box_style .= 'color: ' . $color_scheme['text'] . '; ';
        $box_style .= 'padding: 20px; border-radius: 4px; position: relative; margin: 20px 0;';
        
        echo '<div class="probuilder-alert probuilder-alert-' . esc_attr($type) . '" style="' . $box_style . '">';
        
        echo '<div style="display: flex; align-items: flex-start; gap: 15px;">';
        
        // Icon
        echo '<div style="font-size: 24px;"><i class="fa ' . esc_attr($color_scheme['icon']) . '"></i></div>';
        
        // Content
        echo '<div style="flex: 1;">';
        echo '<h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">' . esc_html($title) . '</h4>';
        if ($message) {
            echo '<p style="margin: 0; font-size: 14px; line-height: 1.5;">' . esc_html($message) . '</p>';
        }
        echo '</div>';
        
        // Close button
        if ($dismissible === 'yes') {
            echo '<button onclick="this.closest(\'.probuilder-alert\').style.display=\'none\';" style="background: transparent; border: none; color: inherit; cursor: pointer; font-size: 20px; padding: 0; opacity: 0.7;" onmouseover="this.style.opacity=\'1\';" onmouseout="this.style.opacity=\'0.7\';">';
            echo '<i class="fa fa-times"></i>';
            echo '</button>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

