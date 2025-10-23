<?php
/**
 * Counter Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Counter extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'counter';
        $this->title = __('Counter', 'probuilder');
        $this->icon = 'fa fa-calculator';
        $this->category = 'content';
        $this->keywords = ['counter', 'number', 'stats'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_counter', [
            'label' => __('Counter', 'probuilder'),
        ]);
        
        $this->add_control('starting_number', [
            'label' => __('Starting Number', 'probuilder'),
            'type' => 'number',
            'default' => 0,
        ]);
        
        $this->add_control('ending_number', [
            'label' => __('Ending Number', 'probuilder'),
            'type' => 'number',
            'default' => 1000,
        ]);
        
        $this->add_control('prefix', [
            'label' => __('Prefix', 'probuilder'),
            'type' => 'text',
            'default' => '',
        ]);
        
        $this->add_control('suffix', [
            'label' => __('Suffix', 'probuilder'),
            'type' => 'text',
            'default' => '+',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Happy Clients', 'probuilder'),
        ]);
        
        $this->add_control('duration', [
            'label' => __('Animation Duration (ms)', 'probuilder'),
            'type' => 'number',
            'default' => 2000,
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('number_color', [
            'label' => __('Number Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'center',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $starting = $this->get_settings('starting_number', 0);
        $ending = $this->get_settings('ending_number', 1000);
        $prefix = $this->get_settings('prefix', '');
        $suffix = $this->get_settings('suffix', '+');
        $title = $this->get_settings('title', 'Happy Clients');
        $duration = $this->get_settings('duration', 2000);
        $number_color = $this->get_settings('number_color', '#0073aa');
        $title_color = $this->get_settings('title_color', '#333333');
        $align = $this->get_settings('text_align', 'center');
        
        $id = 'probuilder-counter-' . uniqid();
        
        $box_style = 'text-align: ' . esc_attr($align) . '; padding: 20px;';
        $number_style = 'font-size: 48px; font-weight: bold; color: ' . esc_attr($number_color) . '; margin-bottom: 10px;';
        $title_style = 'font-size: 18px; color: ' . esc_attr($title_color) . ';';
        
        echo '<div class="probuilder-counter" id="' . esc_attr($id) . '" style="' . $box_style . '" data-start="' . esc_attr($starting) . '" data-end="' . esc_attr($ending) . '" data-duration="' . esc_attr($duration) . '">';
        echo '<div class="probuilder-counter-number" style="' . $number_style . '">';
        echo esc_html($prefix) . '<span class="counter-value">' . esc_html($ending) . '</span>' . esc_html($suffix);
        echo '</div>';
        echo '<div class="probuilder-counter-title" style="' . $title_style . '">' . esc_html($title) . '</div>';
        echo '</div>';
    }
}

