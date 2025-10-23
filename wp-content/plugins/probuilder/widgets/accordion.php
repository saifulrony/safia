<?php
/**
 * Accordion Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Accordion extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'accordion';
        $this->title = __('Accordion', 'probuilder');
        $this->icon = 'fa fa-bars-staggered';
        $this->category = 'advanced';
        $this->keywords = ['accordion', 'collapse', 'toggle'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_accordion', [
            'label' => __('Accordion', 'probuilder'),
        ]);
        
        $this->add_control('items', [
            'label' => __('Accordion Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'title' => __('Accordion #1', 'probuilder'),
                    'content' => __('Accordion content goes here.', 'probuilder'),
                ],
                [
                    'title' => __('Accordion #2', 'probuilder'),
                    'content' => __('Accordion content goes here.', 'probuilder'),
                ],
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Accordion Title', 'probuilder'),
                ],
                [
                    'name' => 'content',
                    'label' => __('Content', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Accordion content', 'probuilder'),
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('title_bg_color', [
            'label' => __('Title Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f5f5f5',
        ]);
        
        $this->add_control('title_text_color', [
            'label' => __('Title Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('content_bg_color', [
            'label' => __('Content Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $items = $this->get_settings('items', []);
        $title_bg = $this->get_settings('title_bg_color', '#f5f5f5');
        $title_text = $this->get_settings('title_text_color', '#333333');
        $content_bg = $this->get_settings('content_bg_color', '#ffffff');
        
        if (empty($items)) {
            return;
        }
        
        $id = 'probuilder-accordion-' . uniqid();
        
        echo '<div class="probuilder-accordion" id="' . esc_attr($id) . '">';
        
        foreach ($items as $index => $item) {
            $item_id = $id . '-' . $index;
            
            // Title
            $title_style = 'background-color: ' . esc_attr($title_bg) . '; ';
            $title_style .= 'color: ' . esc_attr($title_text) . '; ';
            $title_style .= 'padding: 15px 20px; cursor: pointer; border: 1px solid #ddd; margin-bottom: 0; position: relative;';
            
            echo '<div class="probuilder-accordion-item">';
            echo '<div class="probuilder-accordion-title" data-item="' . esc_attr($item_id) . '" style="' . $title_style . '">';
            echo '<span>' . esc_html($item['title']) . '</span>';
            echo '<span class="probuilder-accordion-icon" style="position: absolute; right: 20px;">+</span>';
            echo '</div>';
            
            // Content
            $content_style = 'background-color: ' . esc_attr($content_bg) . '; ';
            $content_style .= 'padding: 20px; border: 1px solid #ddd; border-top: none; display: none;';
            
            echo '<div class="probuilder-accordion-content" data-item="' . esc_attr($item_id) . '" style="' . $content_style . '">';
            echo wp_kses_post(wpautop($item['content']));
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

