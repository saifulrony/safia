<?php
/**
 * Icon Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Icon_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'icon';
        $this->title = __('Icon', 'probuilder');
        $this->icon = 'fa fa-star';
        $this->category = 'basic';
        $this->keywords = ['icon', 'symbol'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'icon',
            'default' => 'fa fa-star',
        ]);
        
        $this->add_control('link', [
            'label' => __('Link', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('size', [
            'label' => __('Size', 'probuilder'),
            'type' => 'slider',
            'default' => 50,
            'range' => ['px' => ['min' => 20, 'max' => 200]],
        ]);
        
        $this->add_control('color', [
            'label' => __('Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
            'default' => 'center',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $icon = $this->get_settings('icon', 'fa fa-star');
        $size = $this->get_settings('size', 50);
        $color = $this->get_settings('color', '#0073aa');
        $align = $this->get_settings('align', 'center');
        $link = $this->get_settings('link', '');
        
        $icon_html = '<i class="' . esc_attr($icon) . '" style="font-size:' . $size . 'px;color:' . $color . ';display:inline-block;transition:transform 0.3s" onmouseover="this.style.transform=\'scale(1.1)\'" onmouseout="this.style.transform=\'scale(1)\'"></i>';
        
        echo '<div style="text-align:' . $align . '">';
        
        if ($link) {
            echo '<a href="' . esc_url($link) . '" style="display:inline-block">' . $icon_html . '</a>';
        } else {
            echo $icon_html;
        }
        
        echo '</div>';
    }
}

