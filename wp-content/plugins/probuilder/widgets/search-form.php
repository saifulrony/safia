<?php
/**
 * Search Form Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Search_Form_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'search-form';
        $this->title = __('Search Form', 'probuilder');
        $this->icon = 'fa fa-search';
        $this->category = 'wordpress';
        $this->keywords = ['search', 'find', 'form'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('placeholder', [
            'label' => __('Placeholder Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Search...', 'probuilder'),
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Search', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('button_color', [
            'label' => __('Button Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $placeholder = $this->get_settings('placeholder', 'Search...');
        $button_text = $this->get_settings('button_text', 'Search');
        $button_color = $this->get_settings('button_color', '#0073aa');
        
        echo '<form role="search" method="get" action="' . esc_url(home_url('/')) . '" style="display:flex;gap:0;max-width:600px">';
        echo '<input type="search" name="s" placeholder="' . esc_attr($placeholder) . '" style="flex:1;padding:12px 15px;border:1px solid #ddd;border-radius:4px 0 0 4px;font-size:16px;outline:none" />';
        echo '<button type="submit" style="background:' . esc_attr($button_color) . ';color:#fff;border:none;padding:12px 24px;border-radius:0 4px 4px 0;cursor:pointer;font-weight:600;font-size:16px">' . esc_html($button_text) . '</button>';
        echo '</form>';
    }
}

