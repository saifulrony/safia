<?php
/**
 * Flexbox Container Widget
 * Advanced flexible layout system
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Flexbox extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'flexbox';
        $this->title = __('Flexbox Container', 'probuilder');
        $this->icon = 'fa fa-grip-horizontal';
        $this->category = 'layout';
        $this->keywords = ['flexbox', 'flex', 'layout', 'container', 'responsive'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('direction', [
            'label' => __('Direction', 'probuilder'),
            'type' => 'select',
            'default' => 'row',
            'options' => [
                'row' => __('Row (Horizontal)', 'probuilder'),
                'row-reverse' => __('Row Reverse', 'probuilder'),
                'column' => __('Column (Vertical)', 'probuilder'),
                'column-reverse' => __('Column Reverse', 'probuilder'),
            ],
        ]);
        
        $this->add_control('justify_content', [
            'label' => __('Justify Content (Main Axis)', 'probuilder'),
            'type' => 'select',
            'default' => 'flex-start',
            'options' => [
                'flex-start' => __('Start', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'flex-end' => __('End', 'probuilder'),
                'space-between' => __('Space Between', 'probuilder'),
                'space-around' => __('Space Around', 'probuilder'),
                'space-evenly' => __('Space Evenly', 'probuilder'),
            ],
        ]);
        
        $this->add_control('align_items', [
            'label' => __('Align Items (Cross Axis)', 'probuilder'),
            'type' => 'select',
            'default' => 'stretch',
            'options' => [
                'stretch' => __('Stretch', 'probuilder'),
                'flex-start' => __('Start', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'flex-end' => __('End', 'probuilder'),
                'baseline' => __('Baseline', 'probuilder'),
            ],
        ]);
        
        $this->add_control('wrap', [
            'label' => __('Flex Wrap', 'probuilder'),
            'type' => 'select',
            'default' => 'wrap',
            'options' => [
                'nowrap' => __('No Wrap', 'probuilder'),
                'wrap' => __('Wrap', 'probuilder'),
                'wrap-reverse' => __('Wrap Reverse', 'probuilder'),
            ],
        ]);
        
        $this->add_control('gap', [
            'label' => __('Gap (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 5
                ]
            ],
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Container Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('min_height', [
            'label' => __('Min Height (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 10
                ]
            ],
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20],
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0],
        ]);
        
        $this->add_control('background_type', [
            'label' => __('Background Type', 'probuilder'),
            'type' => 'select',
            'default' => 'color',
            'options' => [
                'color' => __('Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'image' => __('Image', 'probuilder'),
            ],
        ]);
        
        $this->add_control('background_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#f8f9fa',
        ]);
        
        $this->add_control('background_gradient', [
            'label' => __('Background Gradient', 'probuilder'),
            'type' => 'text',
            'default' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'description' => __('CSS gradient (e.g., linear-gradient(135deg, #667eea 0%, #764ba2 100%))', 'probuilder'),
        ]);
        
        $this->add_control('background_image', [
            'label' => __('Background Image', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => ''],
        ]);
        
        $this->add_control('border', [
            'label' => __('Border', 'probuilder'),
            'type' => 'border',
            'default' => ['width' => 0, 'style' => 'solid', 'color' => '#000000'],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ],
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Get settings
        $direction = $this->get_settings('direction', 'row');
        $justify_content = $this->get_settings('justify_content', 'flex-start');
        $align_items = $this->get_settings('align_items', 'stretch');
        $wrap = $this->get_settings('wrap', 'wrap');
        $gap = $this->get_settings('gap', 20);
        $min_height = $this->get_settings('min_height', 100);
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20]);
        $margin = $this->get_settings('margin', ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]);
        $background_type = $this->get_settings('background_type', 'color');
        $background_color = $this->get_settings('background_color', '#f8f9fa');
        $background_gradient = $this->get_settings('background_gradient', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
        $background_image = $this->get_settings('background_image', ['url' => '']);
        $border = $this->get_settings('border', ['width' => 0, 'style' => 'solid', 'color' => '#000000']);
        $border_radius = $this->get_settings('border_radius', 0);
        $box_shadow = $this->get_settings('box_shadow', 'no') === 'yes';
        
        // Build styles
        $flex_style = 'display: flex; ';
        $flex_style .= 'flex-direction: ' . esc_attr($direction) . '; ';
        $flex_style .= 'justify-content: ' . esc_attr($justify_content) . '; ';
        $flex_style .= 'align-items: ' . esc_attr($align_items) . '; ';
        $flex_style .= 'flex-wrap: ' . esc_attr($wrap) . '; ';
        $flex_style .= 'gap: ' . esc_attr($gap) . 'px; ';
        $flex_style .= 'min-height: ' . esc_attr($min_height) . 'px; ';
        $flex_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        $flex_style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px; ';
        
        // Background
        if ($background_type === 'color') {
            $flex_style .= 'background-color: ' . esc_attr($background_color) . '; ';
        } elseif ($background_type === 'gradient') {
            $flex_style .= 'background: ' . esc_attr($background_gradient) . '; ';
        } elseif ($background_type === 'image' && !empty($background_image['url'])) {
            $flex_style .= 'background-image: url(' . esc_url($background_image['url']) . '); ';
            $flex_style .= 'background-size: cover; background-position: center; ';
        }
        
        // Border
        if ($border['width'] > 0) {
            $flex_style .= 'border: ' . esc_attr($border['width']) . 'px ' . esc_attr($border['style']) . ' ' . esc_attr($border['color']) . '; ';
        }
        
        // Border radius
        if ($border_radius > 0) {
            $flex_style .= 'border-radius: ' . esc_attr($border_radius) . 'px; ';
        }
        
        // Box shadow
        if ($box_shadow) {
            $flex_style .= 'box-shadow: 0 4px 20px rgba(0,0,0,0.1); ';
        }
        
        echo '<div class="probuilder-flexbox-container" style="' . $flex_style . '">';
        echo '<div style="padding: 30px; background: rgba(255,255,255,0.9); border: 2px dashed #cbd5e1; border-radius: 8px; text-align: center; color: #64748b; flex: 1;">';
        echo '<i class="dashicons dashicons-plus" style="font-size: 48px; opacity: 0.4; margin-bottom: 10px;"></i>';
        echo '<div style="font-size: 16px; font-weight: 600;">Flexbox Container</div>';
        echo '<div style="font-size: 13px; margin-top: 5px; opacity: 0.7;">Add widgets inside this flexible layout container</div>';
        echo '<div style="font-size: 12px; margin-top: 10px; opacity: 0.6;">Direction: ' . ucfirst($direction) . ' | Justify: ' . ucfirst(str_replace('-', ' ', $justify_content)) . '</div>';
        echo '</div>';
        echo '</div>';
    }
}

