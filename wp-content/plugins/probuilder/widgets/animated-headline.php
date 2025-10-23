<?php
/**
 * Animated Headline Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Animated_Headline extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'animated-headline';
        $this->title = __('Animated Headline', 'probuilder');
        $this->icon = 'fa fa-text-slash';
        $this->category = 'advanced';
        $this->keywords = ['animated', 'headline', 'text', 'typing'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('before_text', [
            'label' => __('Before Text', 'probuilder'),
            'type' => 'text',
            'default' => __('We Are', 'probuilder'),
        ]);
        
        $this->add_control('animated_text', [
            'label' => __('Animated Text (comma separated)', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Creative, Professional, Innovative, Amazing', 'probuilder'),
        ]);
        
        $this->add_control('after_text', [
            'label' => __('After Text', 'probuilder'),
            'type' => 'text',
            'default' => __('', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('animated_color', [
            'label' => __('Animated Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 42,
            'range' => [
                'px' => ['min' => 20, 'max' => 100],
            ],
        ]);
        
        $this->add_control('align', [
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
        $before = $this->get_settings('before_text', 'We Are');
        $animated = $this->get_settings('animated_text', 'Creative, Professional, Innovative');
        $after = $this->get_settings('after_text', '');
        $text_color = $this->get_settings('text_color', '#333333');
        $animated_color = $this->get_settings('animated_color', '#92003b');
        $font_size = $this->get_settings('font_size', 42);
        $align = $this->get_settings('align', 'center');
        
        $words = array_map('trim', explode(',', $animated));
        $id = 'animated-headline-' . uniqid();
        
        $style = 'text-align: ' . esc_attr($align) . '; font-size: ' . esc_attr($font_size) . 'px; ';
        $style .= 'color: ' . esc_attr($text_color) . '; font-weight: 700; line-height: 1.2;';
        
        echo '<div class="probuilder-animated-headline" id="' . esc_attr($id) . '" style="' . $style . '">';
        
        if ($before) {
            echo '<span>' . esc_html($before) . ' </span>';
        }
        
        echo '<span class="animated-words" style="color: ' . esc_attr($animated_color) . '; display: inline-block; min-width: 200px;" data-words=\'' . esc_attr(json_encode($words)) . '\'>';
        echo esc_html($words[0]);
        echo '</span>';
        
        if ($after) {
            echo ' <span>' . esc_html($after) . '</span>';
        }
        
        echo '</div>';
    }
}

