<?php
/**
 * Feature List Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Feature_List extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'feature-list';
        $this->title = __('Feature List', 'probuilder');
        $this->icon = 'fa fa-list-check';
        $this->category = 'content';
        $this->keywords = ['feature', 'list', 'check'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_features', [
            'label' => __('Features', 'probuilder'),
        ]);
        
        $this->add_control('items', [
            'label' => __('Feature Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                ['text' => __('24/7 Customer Support', 'probuilder'), 'icon' => 'fa fa-check-circle'],
                ['text' => __('Free Updates Forever', 'probuilder'), 'icon' => 'fa fa-check-circle'],
                ['text' => __('Money Back Guarantee', 'probuilder'), 'icon' => 'fa fa-check-circle'],
            ],
            'fields' => [
                [
                    'name' => 'text',
                    'label' => __('Text', 'probuilder'),
                    'type' => 'text',
                ],
                [
                    'name' => 'icon',
                    'label' => __('Icon', 'probuilder'),
                    'type' => 'icon',
                    'default' => 'fa fa-check-circle',
                ],
            ],
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'list',
            'options' => [
                'list' => __('List', 'probuilder'),
                'grid' => __('Grid', 'probuilder'),
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '2',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#22c55e',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $items = $this->get_settings('items', []);
        $layout = $this->get_settings('layout', 'list');
        $columns = $this->get_settings('columns', '2');
        $icon_color = $this->get_settings('icon_color', '#22c55e');
        
        if (empty($items)) {
            return;
        }
        
        $container_style = $layout === 'grid' 
            ? 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: 20px;'
            : 'display: flex; flex-direction: column; gap: 15px;';
        
        echo '<div class="probuilder-feature-list" style="' . $container_style . ' padding: 20px;">';
        
        foreach ($items as $item) {
            $item_style = 'display: flex; align-items: center; gap: 12px; padding: 12px; background: #f9f9f9; border-radius: 6px;';
            
            echo '<div class="feature-item" style="' . $item_style . '">';
            echo '<i class="' . esc_attr($item['icon']) . '" style="font-size: 20px; color: ' . esc_attr($icon_color) . ';"></i>';
            echo '<span style="font-size: 15px; color: #333; font-weight: 500;">' . esc_html($item['text']) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

