<?php
/**
 * Contact Form Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Contact_Form extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'contact-form';
        $this->title = __('Contact Form', 'probuilder');
        $this->icon = 'fa fa-envelope';
        $this->category = 'content';
        $this->keywords = ['contact', 'form', 'email'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_form', [
            'label' => __('Form', 'probuilder'),
        ]);
        
        $this->add_control('form_title', [
            'label' => __('Form Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Get in Touch', 'probuilder'),
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Send Message', 'probuilder'),
        ]);
        
        $this->add_control('button_color', [
            'label' => __('Button Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
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
        
        $form_title = $this->get_settings('form_title', 'Get in Touch');
        $button_text = $this->get_settings('button_text', 'Send Message');
        $button_color = $this->get_settings('button_color', '#92003b');
        
        $style = 'padding: 30px; background: #fff; border: 1px solid #e5e5e5; border-radius: 8px;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-contact-form" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        
        if ($form_title) {
            echo '<h3 style="margin: 0 0 25px 0; font-size: 24px; color: #333;">' . esc_html($form_title) . '</h3>';
        }
        
        echo '<form onsubmit="alert(\'Form submitted! Connect this to your email service.\'); return false;" style="display: flex; flex-direction: column; gap: 15px;">';
        
        echo '<input type="text" placeholder="Your Name" required style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">';
        
        echo '<input type="email" placeholder="Your Email" required style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">';
        
        echo '<input type="text" placeholder="Subject" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">';
        
        echo '<textarea placeholder="Your Message" rows="5" required style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>';
        
        $button_style = 'background: ' . esc_attr($button_color) . '; color: #fff; padding: 14px 30px; ';
        $button_style .= 'border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 15px; transition: all 0.3s;';
        
        echo '<button type="submit" style="' . $button_style . '" onmouseover="this.style.opacity=\'0.9\';" onmouseout="this.style.opacity=\'1\';">' . esc_html($button_text) . '</button>';
        
        echo '</form>';
        echo '</div>';
    }
}

