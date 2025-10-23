<?php
/**
 * Blockquote Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Blockquote extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'blockquote';
        $this->title = __('Blockquote', 'probuilder');
        $this->icon = 'fa fa-quote-right';
        $this->category = 'basic';
        $this->keywords = ['blockquote', 'quote', 'citation'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_quote', [
            'label' => __('Quote', 'probuilder'),
        ]);
        
        $this->add_control('quote_text', [
            'label' => __('Quote Text', 'probuilder'),
            'type' => 'textarea',
            'default' => __('The only way to do great work is to love what you do.', 'probuilder'),
        ]);
        
        $this->add_control('author', [
            'label' => __('Author', 'probuilder'),
            'type' => 'text',
            'default' => __('Steve Jobs', 'probuilder'),
        ]);
        
        $this->add_control('author_title', [
            'label' => __('Author Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Apple Co-founder', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('quote_style', [
            'label' => __('Style', 'probuilder'),
            'type' => 'select',
            'default' => 'border',
            'options' => [
                'border' => __('Border Left', 'probuilder'),
                'box' => __('Box', 'probuilder'),
                'minimal' => __('Minimal', 'probuilder'),
            ],
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border/Accent Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $quote = $this->get_settings('quote_text', '');
        $author = $this->get_settings('author', '');
        $author_title = $this->get_settings('author_title', '');
        $style_type = $this->get_settings('quote_style', 'border');
        $border_color = $this->get_settings('border_color', '#92003b');
        
        $box_style = '';
        
        if ($style_type === 'border') {
            $box_style = 'border-left: 4px solid ' . esc_attr($border_color) . '; padding-left: 30px; font-style: italic;';
        } elseif ($style_type === 'box') {
            $box_style = 'border: 2px solid ' . esc_attr($border_color) . '; padding: 30px; background: #f9f9f9; border-radius: 8px;';
        } else {
            $box_style = 'font-style: italic; padding: 20px 0;';
        }
        
        echo '<blockquote class="probuilder-blockquote" style="' . $box_style . ' margin: 20px 0;">';
        
        // Quote icon
        echo '<div style="font-size: 40px; color: ' . esc_attr($border_color) . '; opacity: 0.3; margin-bottom: 15px;">';
        echo '<i class="fa fa-quote-left"></i>';
        echo '</div>';
        
        // Quote text
        echo '<p style="font-size: 20px; line-height: 1.6; margin: 0 0 20px 0; color: #333;">' . esc_html($quote) . '</p>';
        
        // Author
        if ($author) {
            echo '<footer style="font-style: normal;">';
            echo '<cite style="font-weight: 600; color: #92003b; font-style: normal;">' . esc_html($author) . '</cite>';
            if ($author_title) {
                echo '<span style="color: #999; font-size: 14px;"> — ' . esc_html($author_title) . '</span>';
            }
            echo '</footer>';
        }
        
        echo '</blockquote>';
    }
}

