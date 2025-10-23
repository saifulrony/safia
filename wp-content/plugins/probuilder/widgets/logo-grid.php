<?php
/**
 * Logo Grid Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Logo_Grid extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'logo-grid';
        $this->title = __('Logo Grid', 'probuilder');
        $this->icon = 'fa fa-grip';
        $this->category = 'content';
        $this->keywords = ['logo', 'grid', 'clients', 'partners'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_logos', [
            'label' => __('Logos', 'probuilder'),
        ]);
        
        $this->add_control('logos', [
            'label' => __('Add Logos', 'probuilder'),
            'type' => 'gallery',
            'default' => [
                ['url' => 'https://via.placeholder.com/200x100/f0f0f0/333?text=Logo+1'],
                ['url' => 'https://via.placeholder.com/200x100/f0f0f0/333?text=Logo+2'],
                ['url' => 'https://via.placeholder.com/200x100/f0f0f0/333?text=Logo+3'],
                ['url' => 'https://via.placeholder.com/200x100/f0f0f0/333?text=Logo+4'],
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '4',
            'options' => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
        ]);
        
        $this->add_control('grayscale', [
            'label' => __('Grayscale Effect', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $logos = $this->get_settings('logos', []);
        $columns = $this->get_settings('columns', '4');
        $grayscale = $this->get_settings('grayscale', 'yes');
        
        if (empty($logos)) {
            return;
        }
        
        $grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: 30px; align-items: center;';
        
        echo '<div class="probuilder-logo-grid" style="' . $grid_style . ' padding: 40px 20px;">';
        
        foreach ($logos as $logo) {
            $img_style = 'max-width: 100%; height: auto; ';
            
            if ($grayscale === 'yes') {
                $img_style .= 'filter: grayscale(100%); opacity: 0.7; transition: all 0.3s;';
            }
            
            echo '<div class="logo-item" style="text-align: center;">';
            echo '<img src="' . esc_url($logo['url']) . '" alt="Logo" style="' . $img_style . '" onmouseover="this.style.filter=\'grayscale(0%)\'; this.style.opacity=\'1\';" onmouseout="this.style.filter=\'grayscale(100%)\'; this.style.opacity=\'0.7\';">';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

