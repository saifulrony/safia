<?php
/**
 * Social Icons Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Social_Icons extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'social-icons';
        $this->title = __('Social Icons', 'probuilder');
        $this->icon = 'fa fa-share-nodes';
        $this->category = 'content';
        $this->keywords = ['social', 'icons', 'facebook', 'twitter', 'instagram'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_social', [
            'label' => __('Social Icons', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('social_items', [
            'label' => __('Social Links', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                ['platform' => 'facebook', 'url' => '#', 'icon' => 'fab fa-facebook-f', 'color' => '#3b5998'],
                ['platform' => 'twitter', 'url' => '#', 'icon' => 'fab fa-twitter', 'color' => '#1da1f2'],
                ['platform' => 'instagram', 'url' => '#', 'icon' => 'fab fa-instagram', 'color' => '#E4405F'],
                ['platform' => 'linkedin', 'url' => '#', 'icon' => 'fab fa-linkedin-in', 'color' => '#0077b5'],
            ],
            'fields' => [
                [
                    'name' => 'platform',
                    'label' => __('Platform', 'probuilder'),
                    'type' => 'text',
                    'default' => 'facebook',
                ],
                [
                    'name' => 'url',
                    'label' => __('URL', 'probuilder'),
                    'type' => 'url',
                    'default' => '#',
                ],
                [
                    'name' => 'icon',
                    'label' => __('Icon', 'probuilder'),
                    'type' => 'icon',
                    'default' => 'fab fa-facebook-f',
                ],
                [
                    'name' => 'color',
                    'label' => __('Custom Color', 'probuilder'),
                    'type' => 'color',
                    'default' => '#3b5998',
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('icon_style', [
            'label' => __('Icon Style', 'probuilder'),
            'type' => 'select',
            'default' => 'circle',
            'options' => [
                'circle' => __('Circle', 'probuilder'),
                'square' => __('Square', 'probuilder'),
                'rounded' => __('Rounded Square', 'probuilder'),
                'minimal' => __('Minimal (No Background)', 'probuilder'),
            ],
        ]);
        
        $this->add_control('color_style', [
            'label' => __('Color Style', 'probuilder'),
            'type' => 'select',
            'default' => 'brand',
            'options' => [
                'brand' => __('Brand Colors', 'probuilder'),
                'custom' => __('Custom Color', 'probuilder'),
                'monochrome' => __('Monochrome', 'probuilder'),
            ],
        ]);
        
        $this->add_control('icon_size', [
            'label' => __('Icon Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 10, 'max' => 60],
            ],
        ]);
        
        $this->add_control('icon_box_size', [
            'label' => __('Box Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 45,
            'range' => [
                'px' => ['min' => 30, 'max' => 100],
            ],
        ]);
        
        $this->add_control('icon_spacing', [
            'label' => __('Icon Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 10,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('icon_bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('icon_hover_color', [
            'label' => __('Hover Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('icon_hover_bg_color', [
            'label' => __('Hover Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
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
        // Render custom CSS if any
        $this->render_custom_css();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $items = $this->get_settings('social_items', []);
        $icon_size = $this->get_settings('icon_size', 20);
        $spacing = $this->get_settings('icon_spacing', 10);
        $color = $this->get_settings('icon_color', '#ffffff');
        $bg_color = $this->get_settings('icon_bg_color', '#333333');
        $align = $this->get_settings('align', 'center');
        
        if (empty($items)) {
            return;
        }
        
        $wrapper_style = 'text-align: ' . esc_attr($align) . ';';
        if ($inline_styles) {
            $wrapper_style .= ' ' . $inline_styles;
        }
        
        $icon_wrapper_style = 'display: inline-block; width: ' . ($icon_size + 20) . 'px; height: ' . ($icon_size + 20) . 'px; ';
        $icon_wrapper_style .= 'background: ' . esc_attr($bg_color) . '; color: ' . esc_attr($color) . '; ';
        $icon_wrapper_style .= 'border-radius: 50%; margin: 0 ' . esc_attr($spacing) . 'px; ';
        $icon_wrapper_style .= 'display: inline-flex; align-items: center; justify-content: center; ';
        $icon_wrapper_style .= 'font-size: ' . esc_attr($icon_size) . 'px; transition: all 0.3s;';
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-social-icons" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
        foreach ($items as $item) {
            echo '<a href="' . esc_url($item['url']) . '" target="_blank" class="probuilder-social-icon" style="' . $icon_wrapper_style . '" onmouseover="this.style.transform=\'translateY(-5px)\'; this.style.opacity=\'0.8\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.opacity=\'1\';">';
            echo '<i class="' . esc_attr($item['icon']) . '"></i>';
            echo '</a>';
        }
        
        echo '</div>';
    }
}

