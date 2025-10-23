<?php
/**
 * Icon List Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Icon_List extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'icon-list';
        $this->title = __('Icon List', 'probuilder');
        $this->icon = 'fa fa-list';
        $this->category = 'content';
        $this->keywords = ['icon', 'list', 'features'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_list', [
            'label' => __('List', 'probuilder'),
        ]);
        
        $this->add_control('items', [
            'label' => __('List Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'text' => __('List Item #1', 'probuilder'),
                    'icon' => 'fa fa-check',
                ],
                [
                    'text' => __('List Item #2', 'probuilder'),
                    'icon' => 'fa fa-check',
                ],
                [
                    'text' => __('List Item #3', 'probuilder'),
                    'icon' => 'fa fa-check',
                ],
            ],
            'fields' => [
                [
                    'name' => 'text',
                    'label' => __('Text', 'probuilder'),
                    'type' => 'text',
                    'default' => __('List Item', 'probuilder'),
                ],
                [
                    'name' => 'icon',
                    'label' => __('Icon', 'probuilder'),
                    'type' => 'icon',
                    'default' => 'fa fa-check',
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $items = $this->get_settings('items', []);
        $icon_color = $this->get_settings('icon_color', '#0073aa');
        $text_color = $this->get_settings('text_color', '#333333');
        
        if (empty($items)) {
            return;
        }
        
        echo '<ul class="probuilder-icon-list" style="list-style: none; margin: 0; padding: 0;">';
        
        foreach ($items as $item) {
            $item_style = 'margin-bottom: 15px; display: flex; align-items: center;';
            $icon_style = 'color: ' . esc_attr($icon_color) . '; margin-right: 10px; font-size: 18px;';
            $text_style = 'color: ' . esc_attr($text_color) . '; margin: 0;';
            
            echo '<li style="' . $item_style . '">';
            echo '<i class="' . esc_attr($item['icon']) . '" style="' . $icon_style . '"></i>';
            echo '<span style="' . $text_style . '">' . esc_html($item['text']) . '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
    }
}

