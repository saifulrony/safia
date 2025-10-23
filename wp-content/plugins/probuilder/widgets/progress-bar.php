<?php
/**
 * Progress Bar Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Progress_Bar extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'progress-bar';
        $this->title = __('Progress Bar', 'probuilder');
        $this->icon = 'fa fa-chart-simple';
        $this->category = 'content';
        $this->keywords = ['progress', 'bar', 'skill', 'percentage'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_progress', [
            'label' => __('Progress Bar', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('My Skill', 'probuilder'),
        ]);
        
        $this->add_control('percentage', [
            'label' => __('Percentage', 'probuilder'),
            'type' => 'slider',
            'default' => 75,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->add_control('show_percentage', [
            'label' => __('Show Percentage', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('bar_color', [
            'label' => __('Bar Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e0e0e0',
        ]);
        
        $this->add_control('height', [
            'label' => __('Height', 'probuilder'),
            'type' => 'slider',
            'default' => 25,
            'range' => [
                'px' => ['min' => 10, 'max' => 50],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'My Skill');
        $percentage = $this->get_settings('percentage', 75);
        $show_percentage = $this->get_settings('show_percentage', 'yes');
        $bar_color = $this->get_settings('bar_color', '#0073aa');
        $bg_color = $this->get_settings('bg_color', '#e0e0e0');
        $height = $this->get_settings('height', 25);
        
        echo '<div class="probuilder-progress-bar" style="margin-bottom: 20px;">';
        
        // Title
        echo '<div class="probuilder-progress-title" style="display: flex; justify-content: space-between; margin-bottom: 10px;">';
        echo '<span style="font-weight: 600;">' . esc_html($title) . '</span>';
        if ($show_percentage === 'yes') {
            echo '<span style="font-weight: 600;">' . esc_html($percentage) . '%</span>';
        }
        echo '</div>';
        
        // Bar
        $bg_style = 'background-color: ' . esc_attr($bg_color) . '; height: ' . esc_attr($height) . 'px; border-radius: ' . ($height / 2) . 'px; overflow: hidden;';
        $bar_style = 'background-color: ' . esc_attr($bar_color) . '; height: 100%; width: ' . esc_attr($percentage) . '%; transition: width 1.5s ease;';
        
        echo '<div class="probuilder-progress-bg" style="' . $bg_style . '">';
        echo '<div class="probuilder-progress-fill" style="' . $bar_style . '"></div>';
        echo '</div>';
        
        echo '</div>';
    }
}

