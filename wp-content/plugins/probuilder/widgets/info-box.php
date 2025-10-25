<?php
/**
 * Info Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Info_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'info-box';
        $this->title = __('Info Box', 'probuilder');
        $this->icon = 'fa fa-circle-info';
        $this->category = 'content';
        $this->keywords = ['info', 'box', 'notice', 'tip'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('icon_type', [
            'label' => __('Icon Type', 'probuilder'),
            'type' => 'select',
            'default' => 'number',
            'options' => [
                'number' => __('Number', 'probuilder'),
                'icon' => __('Icon', 'probuilder'),
            ],
        ]);
        
        $this->add_control('number', [
            'label' => __('Number', 'probuilder'),
            'type' => 'text',
            'default' => '01',
        ]);
        
        $this->add_control('icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'icon',
            'default' => 'fa fa-check-circle',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Step One', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is a description of the first step.', 'probuilder'),
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => __('Learn More', 'probuilder'),
        ]);
        
        $this->add_control('button_link', [
            'label' => __('Button Link', 'probuilder'),
            'type' => 'url',
            'default' => '#',
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal (Icon Left)', 'probuilder'),
                'vertical' => __('Vertical (Icon Top)', 'probuilder'),
            ],
        ]);
        
        $this->add_control('icon_style', [
            'label' => __('Icon Style', 'probuilder'),
            'type' => 'select',
            'default' => 'circle',
            'options' => [
                'circle' => __('Circle', 'probuilder'),
                'square' => __('Square', 'probuilder'),
                'rounded' => __('Rounded Square', 'probuilder'),
            ],
        ]);
        
        $this->add_control('icon_size', [
            'label' => __('Icon Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 70,
            'range' => [
                'px' => ['min' => 40, 'max' => 150, 'step' => 5],
            ],
        ]);
        
        $this->add_control('accent_color', [
            'label' => __('Accent Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('description_color', [
            'label' => __('Description Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e5e5e5',
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => ['min' => 0, 'max' => 30, 'step' => 1],
            ],
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('hover_effect', [
            'label' => __('Hover Effect', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $icon_type = $this->get_settings('icon_type', 'number');
        $number = $this->get_settings('number', '01');
        $icon = $this->get_settings('icon', 'fa fa-check-circle');
        $title = $this->get_settings('title', 'Step One');
        $description = $this->get_settings('description', '');
        $button_text = $this->get_settings('button_text', '');
        $button_link = $this->get_settings('button_link', '#');
        
        // Style settings
        $layout = $this->get_settings('layout', 'horizontal');
        $icon_style = $this->get_settings('icon_style', 'circle');
        $icon_size = $this->get_settings('icon_size', 70);
        $accent_color = $this->get_settings('accent_color', '#92003b');
        $bg_color = $this->get_settings('bg_color', '#ffffff');
        $title_color = $this->get_settings('title_color', '#333333');
        $description_color = $this->get_settings('description_color', '#666666');
        $border_color = $this->get_settings('border_color', '#e5e5e5');
        $border_radius = $this->get_settings('border_radius', 8);
        $box_shadow = $this->get_settings('box_shadow', 'no') === 'yes';
        $hover_effect = $this->get_settings('hover_effect', 'yes') === 'yes';
        
        $id = 'info-box-' . uniqid();
        
        // Container style
        $container_style = 'padding: 25px; background: ' . esc_attr($bg_color) . '; border: 1px solid ' . esc_attr($border_color) . '; border-radius: ' . esc_attr($border_radius) . 'px; ';
        
        if ($layout === 'horizontal') {
            $container_style .= 'display: flex; gap: 20px; align-items: flex-start;';
        } else {
            $container_style .= 'display: flex; flex-direction: column; align-items: center; text-align: center; gap: 20px;';
        }
        
        if ($box_shadow) {
            $container_style .= ' box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
        }
        
        if ($hover_effect) {
            $container_style .= ' transition: all 0.3s ease;';
        }
        
        // Icon/Number border radius
        $icon_border_radius = '50%'; // Circle default
        if ($icon_style === 'square') {
            $icon_border_radius = '0';
        } elseif ($icon_style === 'rounded') {
            $icon_border_radius = '12px';
        }
        
        // Icon/Number style
        $icon_container_style = 'flex-shrink: 0; width: ' . esc_attr($icon_size) . 'px; height: ' . esc_attr($icon_size) . 'px; ';
        $icon_container_style .= 'background: ' . esc_attr($accent_color) . '; color: #fff; ';
        $icon_container_style .= 'border-radius: ' . $icon_border_radius . '; ';
        $icon_container_style .= 'display: flex; align-items: center; justify-content: center; ';
        $icon_container_style .= 'font-size: ' . ($icon_size * 0.4) . 'px; font-weight: bold;';
        
        echo '<div class="probuilder-info-box" id="' . esc_attr($id) . '" style="' . $container_style . '">';
        
        // Icon/Number
        echo '<div class="info-icon" style="' . $icon_container_style . '">';
        if ($icon_type === 'icon') {
            echo '<i class="' . esc_attr($icon) . '"></i>';
        } else {
            echo esc_html($number);
        }
        echo '</div>';
        
        // Content
        $content_style = 'flex: 1;';
        if ($layout === 'vertical') {
            $content_style .= ' text-align: center;';
        }
        
        echo '<div class="info-content" style="' . $content_style . '">';
        echo '<h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: ' . esc_attr($title_color) . ';">' . esc_html($title) . '</h3>';
        
        if (!empty($description)) {
            echo '<p style="margin: 0 0 15px 0; color: ' . esc_attr($description_color) . '; line-height: 1.6; font-size: 14px;">' . esc_html($description) . '</p>';
        }
        
        // Button
        if (!empty($button_text)) {
            $button_style = 'background: ' . esc_attr($accent_color) . '; color: #fff; padding: 10px 24px; ';
            $button_style .= 'border: none; border-radius: 4px; text-decoration: none; display: inline-block; ';
            $button_style .= 'font-weight: 600; font-size: 14px; transition: all 0.3s; cursor: pointer;';
            
            echo '<a href="' . esc_url($button_link) . '" style="' . $button_style . '" onmouseover="this.style.opacity=\'0.9\'; this.style.transform=\'translateY(-2px)\';" onmouseout="this.style.opacity=\'1\'; this.style.transform=\'translateY(0)\';">' . esc_html($button_text) . '</a>';
        }
        
        echo '</div>';
        echo '</div>';
        
        // Add hover effect
        if ($hover_effect) {
            echo '<script>
                (function() {
                    var infoBox = document.getElementById("' . esc_js($id) . '");
                    if (infoBox) {
                        infoBox.addEventListener("mouseenter", function() {
                            this.style.transform = "translateY(-5px)";
                            this.style.boxShadow = "0 8px 25px rgba(0,0,0,0.15)";
                        });
                        infoBox.addEventListener("mouseleave", function() {
                            this.style.transform = "translateY(0)";
                            this.style.boxShadow = "' . ($box_shadow ? '0 4px 15px rgba(0,0,0,0.1)' : 'none') . '";
                        });
                    }
                })();
            </script>';
        }
    }
}

