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
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'Ready to Get Started?');
        $description = $this->get_settings('description', '');
        $button_text = $this->get_settings('button_text', 'Get Started Now');
        $button_link = $this->get_settings('button_link', '#');
        $bg_color = $this->get_settings('bg_color', '#92003b');
        $text_color = $this->get_settings('text_color', '#ffffff');
        
        $box_style = 'background: ' . esc_attr($bg_color) . '; color: ' . esc_attr($text_color) . '; ';
        $box_style .= 'padding: 60px 40px; text-align: center; border-radius: 8px;';
        
        echo '<div class="probuilder-cta" style="' . $box_style . '">';
        
        echo '<h2 style="margin: 0 0 15px 0; font-size: 36px; color: inherit;">' . esc_html($title) . '</h2>';
        
        if ($description) {
            echo '<p style="margin: 0 0 30px 0; font-size: 18px; opacity: 0.9;">' . esc_html($description) . '</p>';
        }
        
        $button_style = 'background: #ffffff; color: ' . esc_attr($bg_color) . '; ';
        $button_style .= 'padding: 15px 40px; text-decoration: none; display: inline-block; ';
        $button_style .= 'border-radius: 4px; font-weight: 600; font-size: 16px; transition: all 0.3s;';
        
        echo '<a href="' . esc_url($button_link) . '" class="cta-button" style="' . $button_style . '" onmouseover="this.style.transform=\'scale(1.05)\';" onmouseout="this.style.transform=\'scale(1)\';">' . esc_html($button_text) . '</a>';
        
        echo '</div>';
    }
}

