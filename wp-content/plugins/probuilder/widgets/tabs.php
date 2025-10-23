<?php
/**
 * Tabs Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Tabs extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'tabs';
        $this->title = __('Tabs', 'probuilder');
        $this->icon = 'fa fa-folder';
        $this->category = 'advanced';
        $this->keywords = ['tabs', 'toggle', 'switch'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_tabs', [
            'label' => __('Tabs', 'probuilder'),
        ]);
        
        $this->add_control('tabs', [
            'label' => __('Tabs Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'tab_title' => __('Tab #1', 'probuilder'),
                    'tab_content' => __('Tab content goes here.', 'probuilder'),
                ],
                [
                    'tab_title' => __('Tab #2', 'probuilder'),
                    'tab_content' => __('Tab content goes here.', 'probuilder'),
                ],
            ],
            'fields' => [
                [
                    'name' => 'tab_title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Tab Title', 'probuilder'),
                ],
                [
                    'name' => 'tab_content',
                    'label' => __('Content', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Tab content', 'probuilder'),
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('tab_bg_color', [
            'label' => __('Tab Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f5f5f5',
        ]);
        
        $this->add_control('tab_active_bg_color', [
            'label' => __('Active Tab Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('tab_text_color', [
            'label' => __('Tab Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $tabs = $this->get_settings('tabs', []);
        $tab_bg = $this->get_settings('tab_bg_color', '#f5f5f5');
        $tab_active_bg = $this->get_settings('tab_active_bg_color', '#ffffff');
        $tab_text = $this->get_settings('tab_text_color', '#333333');
        
        if (empty($tabs)) {
            return;
        }
        
        $id = 'probuilder-tabs-' . uniqid();
        
        echo '<div class="probuilder-tabs" id="' . esc_attr($id) . '">';
        
        // Tab navigation
        echo '<div class="probuilder-tabs-nav">';
        foreach ($tabs as $index => $tab) {
            $active_class = $index === 0 ? 'active' : '';
            $style = 'background-color: ' . ($index === 0 ? esc_attr($tab_active_bg) : esc_attr($tab_bg)) . '; ';
            $style .= 'color: ' . esc_attr($tab_text) . '; padding: 15px 25px; cursor: pointer; display: inline-block; border: 1px solid #ddd; margin-right: -1px;';
            
            echo '<div class="probuilder-tab-title ' . esc_attr($active_class) . '" data-tab="' . esc_attr($index) . '" style="' . $style . '">';
            echo esc_html($tab['tab_title']);
            echo '</div>';
        }
        echo '</div>';
        
        // Tab content
        echo '<div class="probuilder-tabs-content" style="border: 1px solid #ddd; padding: 20px; background: ' . esc_attr($tab_active_bg) . ';">';
        foreach ($tabs as $index => $tab) {
            $active_class = $index === 0 ? 'active' : '';
            $display = $index === 0 ? 'block' : 'none';
            
            echo '<div class="probuilder-tab-content ' . esc_attr($active_class) . '" data-tab="' . esc_attr($index) . '" style="display: ' . esc_attr($display) . ';">';
            echo wp_kses_post(wpautop($tab['tab_content']));
            echo '</div>';
        }
        echo '</div>';
        
        echo '</div>';
    }
}

