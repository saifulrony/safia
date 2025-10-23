<?php
/**
 * Toggle Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Toggle extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'toggle';
        $this->title = __('Toggle', 'probuilder');
        $this->icon = 'fa fa-toggle-on';
        $this->category = 'advanced';
        $this->keywords = ['toggle', 'switch', 'collapse'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_toggle', [
            'label' => __('Toggle', 'probuilder'),
        ]);
        
        $this->add_control('items', [
            'label' => __('Toggle Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'title' => __('Toggle #1', 'probuilder'),
                    'content' => __('Toggle content goes here.', 'probuilder'),
                ],
                [
                    'title' => __('Toggle #2', 'probuilder'),
                    'content' => __('Toggle content goes here.', 'probuilder'),
                ],
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Toggle Title', 'probuilder'),
                ],
                [
                    'name' => 'content',
                    'label' => __('Content', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Toggle content', 'probuilder'),
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('title_bg', [
            'label' => __('Title Background', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $items = $this->get_settings('items', []);
        $title_bg = $this->get_settings('title_bg', '#92003b');
        $title_color = $this->get_settings('title_color', '#ffffff');
        
        if (empty($items)) {
            return;
        }
        
        $id = 'toggle-' . uniqid();
        
        echo '<div class="probuilder-toggle" id="' . esc_attr($id) . '">';
        
        foreach ($items as $index => $item) {
            $item_id = $id . '-' . $index;
            
            $title_style = 'background: ' . esc_attr($title_bg) . '; color: ' . esc_attr($title_color) . '; ';
            $title_style .= 'padding: 15px 20px; cursor: pointer; border-radius: 4px; margin-bottom: 5px; ';
            $title_style .= 'display: flex; justify-content: space-between; align-items: center; font-weight: 600;';
            
            echo '<div class="toggle-item" style="margin-bottom: 10px;">';
            
            echo '<div class="toggle-title" data-item="' . esc_attr($item_id) . '" style="' . $title_style . '" onclick="';
            echo 'var content = this.nextElementSibling; ';
            echo 'var icon = this.querySelector(\'.toggle-icon\'); ';
            echo 'if(content.style.display === \'none\' || !content.style.display) { ';
            echo 'content.style.display = \'block\'; icon.style.transform = \'rotate(180deg)\'; ';
            echo '} else { content.style.display = \'none\'; icon.style.transform = \'rotate(0deg)\'; }">';
            echo '<span>' . esc_html($item['title']) . '</span>';
            echo '<span class="toggle-icon" style="transition: transform 0.3s;">▼</span>';
            echo '</div>';
            
            echo '<div class="toggle-content" style="display: none; padding: 20px; background: #f9f9f9; border-radius: 4px;">';
            echo '<p style="margin: 0; color: #666; line-height: 1.6;">' . esc_html($item['content']) . '</p>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }
}

