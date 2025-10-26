<?php
/**
 * Image Hotspot Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Hotspot_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'hotspot';
        $this->title = __('Image Hotspot', 'probuilder');
        $this->icon = 'fa fa-map-marker-alt';
        $this->category = 'content';
        $this->keywords = ['hotspot', 'image', 'interactive'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('image', [
            'label' => __('Image', 'probuilder'),
            'type' => 'media',
            'default' => '',
        ]);
        
        $this->add_control('hotspots', [
            'label' => __('Hotspots', 'probuilder'),
            'type' => 'repeater',
            'default' => [],
            'fields' => [
                ['name' => 'x_position', 'label' => 'X Position (%)', 'type' => 'slider', 'default' => 50, 'range' => ['%' => ['min' => 0, 'max' => 100]]],
                ['name' => 'y_position', 'label' => 'Y Position (%)', 'type' => 'slider', 'default' => 50, 'range' => ['%' => ['min' => 0, 'max' => 100]]],
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'default' => 'Hotspot'],
                ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'default' => 'Info'],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $image = $this->get_settings('image', '');
        $hotspots = $this->get_settings('hotspots', []);
        
        if (empty($image)) {
            echo '<p style="padding:20px;background:#f5f5f5">Please select an image</p>';
            return;
        }
        
        echo '<div style="position:relative;display:inline-block;max-width:100%">';
        echo '<img src="' . esc_url($image) . '" alt="Hotspot Image" style="width:100%;height:auto;display:block">';
        
        foreach ($hotspots as $index => $hotspot) {
            $hotspot_id = 'hotspot-' . uniqid() . '-' . $index;
            echo '<div style="position:absolute;left:' . $hotspot['x_position'] . '%;top:' . $hotspot['y_position'] . '%;transform:translate(-50%,-50%);cursor:pointer" onmouseover="document.getElementById(\'' . $hotspot_id . '\').style.display=\'block\'" onmouseout="document.getElementById(\'' . $hotspot_id . '\').style.display=\'none\'">';
            echo '<span style="display:block;width:20px;height:20px;background:#0073aa;border-radius:50%;animation:pulse 2s infinite"></span>';
            echo '<div id="' . $hotspot_id . '" style="display:none;position:absolute;bottom:100%;left:50%;transform:translateX(-50%);background:#fff;padding:15px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.2);min-width:200px;margin-bottom:10px;z-index:100">';
            if (!empty($hotspot['title'])) echo '<h5 style="margin:0 0 8px;font-size:16px">' . esc_html($hotspot['title']) . '</h5>';
            if (!empty($hotspot['content'])) echo '<p style="margin:0;font-size:14px;color:#666">' . esc_html($hotspot['content']) . '</p>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '<style>@keyframes pulse{0%,100%{transform:scale(1);opacity:0.8}50%{transform:scale(1.5);opacity:0.4}}</style>';
    }
}

