<?php
/**
 * Heading Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Heading extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'heading';
        $this->title = __('Heading', 'probuilder');
        $this->icon = 'fa fa-heading';
        $this->category = 'basic';
        $this->keywords = ['heading', 'title', 'h1', 'h2', 'h3'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('This is a heading', 'probuilder'),
        ]);
        
        $this->add_control('html_tag', [
            'label' => __('HTML Tag', 'probuilder'),
            'type' => 'select',
            'default' => 'h2',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'p' => 'p',
            ],
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
                'justify' => __('Justify', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 32,
            'range' => [
                'px' => ['min' => 10, 'max' => 100],
            ],
        ]);
        
        $this->add_control('font_weight', [
            'label' => __('Font Weight', 'probuilder'),
            'type' => 'select',
            'default' => '600',
            'options' => [
                '300' => __('Light', 'probuilder'),
                '400' => __('Normal', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
                '900' => __('Black', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'This is a heading');
        $tag = $this->get_settings('html_tag', 'h2');
        $align = $this->get_settings('align', 'left');
        $color = $this->get_settings('color', '#333333');
        $font_size = $this->get_settings('font_size', 32);
        $font_weight = $this->get_settings('font_weight', '600');
        
        $style = 'text-align: ' . esc_attr($align) . '; ';
        $style .= 'color: ' . esc_attr($color) . '; ';
        $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
        $style .= 'font-weight: ' . esc_attr($font_weight) . ';';
        
        printf(
            '<%1$s class="probuilder-heading" style="%2$s">%3$s</%1$s>',
            esc_attr($tag),
            $style,
            esc_html($title)
        );
    }
}

