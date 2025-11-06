<?php
/**
 * Call to Action Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Call_To_Action extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'call-to-action';
        $this->title = __('Call to Action', 'probuilder');
        $this->icon = 'fa fa-bullhorn';
        $this->category = 'content';
        $this->keywords = ['cta', 'call', 'action', 'banner'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Ready to Get Started?', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Join thousands of satisfied customers today!', 'probuilder'),
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Get Started Now', 'probuilder'),
        ]);
        
        $this->add_control('button_link', [
            'label' => __('Button Link', 'probuilder'),
            'type' => 'url',
            'default' => '#',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('background_type', [
            'label' => __('Background Type', 'probuilder'),
            'type' => 'select',
            'default' => 'color',
            'options' => [
                'color' => __('Color', 'probuilder'),
                'image' => __('Image', 'probuilder'),
            ],
        ]);
        
        $this->add_control('background_image', [
            'label' => __('Background Image', 'probuilder'),
            'type' => 'url',
            'default' => '',
            'condition' => [
                'background_type' => 'image',
            ],
        ]);
        
        $this->add_control('background_overlay', [
            'label' => __('Overlay Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.3)',
            'condition' => [
                'background_type' => 'image',
            ],
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('title_size', [
            'label' => __('Title Size', 'probuilder'),
            'type' => 'text',
            'default' => '36px',
        ]);
        
        $this->add_control('description_color', [
            'label' => __('Description Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('description_size', [
            'label' => __('Description Size', 'probuilder'),
            'type' => 'text',
            'default' => '18px',
        ]);
        
        $this->add_control('button_bg_color', [
            'label' => __('Button Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('button_text_color', [
            'label' => __('Button Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('alignment', [
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
        
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
        ]);
        
        $this->add_control('min_height', [
            'label' => __('Minimum Height', 'probuilder'),
            'type' => 'text',
            'default' => 'auto',
            'placeholder' => '300px',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'text',
            'default' => '60px 40px',
            'placeholder' => '60px 40px',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $title = $this->get_settings('title', 'Ready to Get Started?');
        $description = $this->get_settings('description', '');
        $button_text = $this->get_settings('button_text', 'Get Started Now');
        $button_link = $this->get_settings('button_link', '#');
        $bg_color = $this->get_settings('bg_color', '#92003b');
        $text_color = $this->get_settings('text_color', '#ffffff');
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        // Get new settings (support both formats: with and without underscore prefix)
        $bg_type = $this->get_settings('background_type', $this->get_settings('_background_type', 'color'));
        $bg_image = $this->get_settings('background_image', $this->get_settings('_background_image', ''));
        $bg_overlay = $this->get_settings('background_overlay', $this->get_settings('_background_overlay', 'rgba(0,0,0,0.3)'));
        $title_color = $this->get_settings('title_color', $text_color);
        $title_size = $this->get_settings('title_size', '36px');
        $desc_color = $this->get_settings('description_color', $text_color);
        $desc_size = $this->get_settings('description_size', '18px');
        $btn_bg = $this->get_settings('button_bg_color', '#ffffff');
        $btn_text = $this->get_settings('button_text_color', $bg_color);
        $alignment = $this->get_settings('alignment', 'center');
        $min_height = $this->get_settings('min_height', $this->get_settings('_min_height', 'auto'));
        $padding = $this->get_settings('padding', $this->get_settings('_padding', '60px 40px'));
        
        // Build background style
        $box_style = '';
        if ($bg_type === 'image' && !empty($bg_image)) {
            $box_style = 'background-image: url(' . esc_url($bg_image) . '); background-size: cover; background-position: center; ';
            $box_style .= 'background-repeat: no-repeat; ';
        } else {
            $box_style = 'background: ' . esc_attr($bg_color) . '; ';
        }
        
        $box_style .= 'color: ' . esc_attr($text_color) . '; ';
        $box_style .= 'padding: ' . esc_attr($padding) . '; ';
        $box_style .= 'text-align: ' . esc_attr($alignment) . '; ';
        $box_style .= 'border-radius: 8px; ';
        $box_style .= 'position: relative; ';
        $box_style .= 'overflow: hidden; ';
        $box_style .= 'min-height: ' . esc_attr($min_height) . '; ';
        $box_style .= 'display: flex; ';
        $box_style .= 'align-items: center; ';
        $justify_content = $alignment === 'left' ? 'flex-start' : ($alignment === 'right' ? 'flex-end' : 'center');
        $box_style .= 'justify-content: ' . $justify_content . '; ';
        
        if ($inline_styles) {
            $box_style .= ' ' . $inline_styles;
        }
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-cta" ' . $wrapper_attributes . ' style="' . esc_attr($box_style) . '">';
        
        // Background overlay (for images)
        if ($bg_type === 'image' && !empty($bg_image) && !empty($bg_overlay)) {
            echo '<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: ' . esc_attr($bg_overlay) . '; z-index: 0;"></div>';
        }
        
        // Content wrapper
        echo '<div style="position: relative; z-index: 1; max-width: 600px; width: 100%;">';
        
        echo '<h2 style="margin: 0 0 15px 0; font-size: ' . esc_attr($title_size) . '; color: ' . esc_attr($title_color) . '; font-weight: 700; line-height: 1.2;">' . esc_html($title) . '</h2>';
        
        if ($description) {
            echo '<p style="margin: 0 0 30px 0; font-size: ' . esc_attr($desc_size) . '; color: ' . esc_attr($desc_color) . '; opacity: 0.95; line-height: 1.6;">' . esc_html($description) . '</p>';
        }
        
        if ($button_text) {
            $button_style = 'background: ' . esc_attr($btn_bg) . '; ';
            $button_style .= 'color: ' . esc_attr($btn_text) . '; ';
            $button_style .= 'padding: 15px 40px; ';
            $button_style .= 'text-decoration: none; ';
            $button_style .= 'display: inline-block; ';
            $button_style .= 'border-radius: 6px; ';
            $button_style .= 'font-weight: 600; ';
            $button_style .= 'font-size: 16px; ';
            $button_style .= 'transition: all 0.3s; ';
            $button_style .= 'box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            
            echo '<a href="' . esc_url($button_link) . '" class="cta-button" style="' . $button_style . '" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 6px 20px rgba(0,0,0,0.25)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 4px 12px rgba(0,0,0,0.15)\';">' . esc_html($button_text) . '</a>';
        }
        
        echo '</div>'; // Content wrapper
        echo '</div>'; // Main container
    }
}

