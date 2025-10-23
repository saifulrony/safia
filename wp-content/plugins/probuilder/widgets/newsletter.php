<?php
/**
 * Newsletter Signup Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Newsletter extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'newsletter';
        $this->title = __('Newsletter', 'probuilder');
        $this->icon = 'fa fa-envelope-open-text';
        $this->category = 'content';
        $this->keywords = ['newsletter', 'subscribe', 'email'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Subscribe to Our Newsletter', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Get the latest updates and offers.', 'probuilder'),
        ]);
        
        $this->add_control('placeholder', [
            'label' => __('Email Placeholder', 'probuilder'),
            'type' => 'text',
            'default' => __('Enter your email', 'probuilder'),
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Subscribe', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'inline',
            'options' => [
                'inline' => __('Inline', 'probuilder'),
                'stacked' => __('Stacked', 'probuilder'),
            ],
        ]);
        
        $this->add_control('button_color', [
            'label' => __('Button Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'Subscribe to Our Newsletter');
        $description = $this->get_settings('description', '');
        $placeholder = $this->get_settings('placeholder', 'Enter your email');
        $button_text = $this->get_settings('button_text', 'Subscribe');
        $layout = $this->get_settings('layout', 'inline');
        $button_color = $this->get_settings('button_color', '#92003b');
        
        $form_style = $layout === 'inline' ? 'display: flex; gap: 10px;' : 'display: flex; flex-direction: column; gap: 10px;';
        
        echo '<div class="probuilder-newsletter" style="padding: 40px; background: #f9f9f9; border-radius: 8px; text-align: center;">';
        
        // Title
        echo '<h3 style="margin: 0 0 10px 0; font-size: 24px; color: #333;">' . esc_html($title) . '</h3>';
        
        // Description
        if ($description) {
            echo '<p style="margin: 0 0 25px 0; color: #666; font-size: 15px;">' . esc_html($description) . '</p>';
        }
        
        // Form
        echo '<form class="newsletter-form" style="' . $form_style . ' max-width: 500px; margin: 0 auto;" onsubmit="alert(\'Newsletter subscription functionality would be connected to your email service\'); return false;">';
        
        echo '<input type="email" placeholder="' . esc_attr($placeholder) . '" required style="flex: 1; padding: 14px 20px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">';
        
        $button_style = 'background: ' . esc_attr($button_color) . '; color: #fff; padding: 14px 35px; ';
        $button_style .= 'border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s;';
        
        echo '<button type="submit" style="' . $button_style . '" onmouseover="this.style.opacity=\'0.9\';" onmouseout="this.style.opacity=\'1\';">' . esc_html($button_text) . '</button>';
        
        echo '</form>';
        
        echo '</div>';
    }
}

