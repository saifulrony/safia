<?php
/**
 * Timeline Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Timeline extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'timeline';
    }
    
    public function get_title() {
        return 'Timeline';
    }
    
    public function get_icon() {
        return 'fa fa-history';
    }
    
    public function get_category() {
        return 'content';
    }
    
    public function get_keywords() {
        return ['timeline', 'history', 'process', 'steps', 'events'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('content_section', [
            'label' => 'Timeline Content',
            'tab' => 'content'
        ]);
        
        $this->add_control('timeline_title', [
            'label' => 'Timeline Title',
            'type' => 'text',
            'default' => 'Our Journey',
            'placeholder' => 'Enter timeline title'
        ]);
        
        $this->add_control('timeline_description', [
            'label' => 'Timeline Description',
            'type' => 'textarea',
            'default' => 'Follow our journey from the beginning to where we are today.',
            'placeholder' => 'Enter timeline description'
        ]);
        
        $this->add_control('timeline_items', [
            'label' => 'Timeline Items',
            'type' => 'repeater',
            'fields' => [
                [
                    'name' => 'date',
                    'label' => 'Date',
                    'type' => 'text',
                    'default' => '2020'
                ],
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'default' => 'Company Founded'
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'default' => 'We started our journey with a vision to revolutionize the industry.'
                ],
                [
                    'name' => 'icon',
                    'label' => 'Icon',
                    'type' => 'text',
                    'default' => 'fa fa-rocket',
                    'placeholder' => 'FontAwesome icon class'
                ],
                [
                    'name' => 'image',
                    'label' => 'Image (optional)',
                    'type' => 'media',
                    'default' => ['url' => '']
                ]
            ],
            'default' => [
                [
                    'date' => '2020',
                    'title' => 'Company Founded',
                    'description' => 'We started our journey with a vision to revolutionize the industry and provide innovative solutions.',
                    'icon' => 'fa fa-rocket'
                ],
                [
                    'date' => '2021',
                    'title' => 'First Product Launch',
                    'description' => 'Launched our flagship product that quickly gained recognition in the market.',
                    'icon' => 'fa fa-star'
                ],
                [
                    'date' => '2022',
                    'title' => 'International Expansion',
                    'description' => 'Expanded our operations to serve customers across multiple countries.',
                    'icon' => 'fa fa-globe'
                ],
                [
                    'date' => '2023',
                    'title' => 'Award Recognition',
                    'description' => 'Received industry awards for innovation and customer satisfaction.',
                    'icon' => 'fa fa-trophy'
                ],
                [
                    'date' => '2024',
                    'title' => 'Future Vision',
                    'description' => 'Continuing to innovate and expand our services to meet evolving customer needs.',
                    'icon' => 'fa fa-lightbulb'
                ]
            ]
        ]);
        
        $this->add_control('layout', [
            'label' => 'Layout',
            'type' => 'select',
            'options' => [
                'vertical' => 'Vertical',
                'horizontal' => 'Horizontal'
            ],
            'default' => 'vertical'
        ]);
        
        $this->add_control('show_connector', [
            'label' => 'Show Connector Line',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('style_section', [
            'label' => 'Timeline Style',
            'tab' => 'style'
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => 'color',
            'default' => '#1e293b'
        ]);
        
        $this->add_control('description_color', [
            'label' => 'Description Color',
            'type' => 'color',
            'default' => '#64748b'
        ]);
        
        $this->add_control('item_bg_color', [
            'label' => 'Item Background',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('item_border_color', [
            'label' => 'Item Border Color',
            'type' => 'color',
            'default' => '#e1e5e9'
        ]);
        
        $this->add_control('date_color', [
            'label' => 'Date Color',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('icon_bg_color', [
            'label' => 'Icon Background',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('connector_color', [
            'label' => 'Connector Color',
            'type' => 'color',
            'default' => '#e1e5e9',
            'condition' => ['show_connector' => 'yes']
        ]);
        
        $this->add_control('border_radius', [
            'label' => 'Border Radius',
            'type' => 'slider',
            'range' => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 8]
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $timeline_id = 'probuilder-timeline-' . $this->get_id();
        
        echo '<div id="' . esc_attr($timeline_id) . '" class="probuilder-timeline probuilder-timeline-' . esc_attr($settings['layout']) . '">';
        
        // Title and Description
        if (!empty($settings['timeline_title'])) {
            echo '<h2 class="probuilder-timeline-title" style="color: ' . esc_attr($settings['title_color']) . '; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">' . esc_html($settings['timeline_title']) . '</h2>';
        }
        
        if (!empty($settings['timeline_description'])) {
            echo '<p class="probuilder-timeline-description" style="color: ' . esc_attr($settings['description_color']) . '; font-size: 16px; text-align: center; margin: 0 0 50px 0; max-width: 600px; margin-left: auto; margin-right: auto;">' . esc_html($settings['timeline_description']) . '</p>';
        }
        
        // Timeline Items
        if ($settings['layout'] === 'vertical') {
            echo '<div class="probuilder-timeline-vertical" style="position: relative; max-width: 800px; margin: 0 auto;">';
            
            if ($settings['show_connector'] === 'yes') {
                echo '<div class="probuilder-timeline-line" style="position: absolute; left: 30px; top: 0; bottom: 0; width: 2px; background-color: ' . esc_attr($settings['connector_color']) . ';"></div>';
            }
            
            foreach ($settings['timeline_items'] as $index => $item) {
                $this->render_vertical_item($item, $index, $settings);
            }
            
            echo '</div>';
        } else {
            echo '<div class="probuilder-timeline-horizontal" style="display: flex; overflow-x: auto; gap: 30px; padding: 20px 0;">';
            
            foreach ($settings['timeline_items'] as $index => $item) {
                $this->render_horizontal_item($item, $index, $settings);
            }
            
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    private function render_vertical_item($item, $index, $settings) {
        $item_style = '';
        $item_style .= 'background-color: ' . esc_attr($settings['item_bg_color']) . ';';
        $item_style .= 'border: 1px solid ' . esc_attr($settings['item_border_color']) . ';';
        $item_style .= 'border-radius: ' . esc_attr($settings['border_radius']['size']) . 'px;';
        $item_style .= 'padding: 25px;';
        $item_style .= 'margin-left: 80px;';
        $item_style .= 'margin-bottom: 30px;';
        $item_style .= 'position: relative;';
        $item_style .= 'box-shadow: 0 2px 10px rgba(0,0,0,0.1);';
        
        echo '<div class="probuilder-timeline-item" style="' . $item_style . '">';
        
        // Icon
        echo '<div class="probuilder-timeline-icon" style="position: absolute; left: -60px; top: 25px; width: 40px; height: 40px; background-color: ' . esc_attr($settings['icon_bg_color']) . '; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;">';
        echo '<i class="' . esc_attr($item['icon']) . '" style="color: ' . esc_attr($settings['icon_color']) . '; font-size: 16px;"></i>';
        echo '</div>';
        
        // Date
        if (!empty($item['date'])) {
            echo '<div class="probuilder-timeline-date" style="color: ' . esc_attr($settings['date_color']) . '; font-size: 14px; font-weight: 600; margin-bottom: 10px;">' . esc_html($item['date']) . '</div>';
        }
        
        // Title
        if (!empty($item['title'])) {
            echo '<h3 class="probuilder-timeline-item-title" style="color: ' . esc_attr($settings['title_color']) . '; font-size: 20px; font-weight: 600; margin: 0 0 15px 0;">' . esc_html($item['title']) . '</h3>';
        }
        
        // Description
        if (!empty($item['description'])) {
            echo '<p class="probuilder-timeline-item-description" style="color: ' . esc_attr($settings['description_color']) . '; margin: 0; line-height: 1.6;">' . esc_html($item['description']) . '</p>';
        }
        
        // Image
        if (!empty($item['image']['url'])) {
            echo '<div class="probuilder-timeline-image" style="margin-top: 15px;">';
            echo '<img src="' . esc_url($item['image']['url']) . '" alt="' . esc_attr($item['title']) . '" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">';
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    private function render_horizontal_item($item, $index, $settings) {
        $item_style = '';
        $item_style .= 'background-color: ' . esc_attr($settings['item_bg_color']) . ';';
        $item_style .= 'border: 1px solid ' . esc_attr($settings['item_border_color']) . ';';
        $item_style .= 'border-radius: ' . esc_attr($settings['border_radius']['size']) . 'px;';
        $item_style .= 'padding: 25px;';
        $item_style .= 'min-width: 300px;';
        $item_style .= 'flex-shrink: 0;';
        $item_style .= 'box-shadow: 0 2px 10px rgba(0,0,0,0.1);';
        
        echo '<div class="probuilder-timeline-item-horizontal" style="' . $item_style . '">';
        
        // Icon
        echo '<div class="probuilder-timeline-icon" style="width: 50px; height: 50px; background-color: ' . esc_attr($settings['icon_bg_color']) . '; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">';
        echo '<i class="' . esc_attr($item['icon']) . '" style="color: ' . esc_attr($settings['icon_color']) . '; font-size: 20px;"></i>';
        echo '</div>';
        
        // Date
        if (!empty($item['date'])) {
            echo '<div class="probuilder-timeline-date" style="color: ' . esc_attr($settings['date_color']) . '; font-size: 14px; font-weight: 600; margin-bottom: 10px; text-align: center;">' . esc_html($item['date']) . '</div>';
        }
        
        // Title
        if (!empty($item['title'])) {
            echo '<h3 class="probuilder-timeline-item-title" style="color: ' . esc_attr($settings['title_color']) . '; font-size: 18px; font-weight: 600; margin: 0 0 15px 0; text-align: center;">' . esc_html($item['title']) . '</h3>';
        }
        
        // Description
        if (!empty($item['description'])) {
            echo '<p class="probuilder-timeline-item-description" style="color: ' . esc_attr($settings['description_color']) . '; margin: 0; line-height: 1.6; text-align: center;">' . esc_html($item['description']) . '</p>';
        }
        
        echo '</div>';
    }
}
