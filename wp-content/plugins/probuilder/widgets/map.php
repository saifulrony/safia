<?php
/**
 * Map Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Map extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'map';
        $this->title = __('Google Map', 'probuilder');
        $this->icon = 'fa fa-map';
        $this->category = 'content';
        $this->keywords = ['map', 'google', 'location'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_map', [
            'label' => __('Map', 'probuilder'),
        ]);
        
        $this->add_control('address', [
            'label' => __('Address', 'probuilder'),
            'type' => 'text',
            'default' => 'New York, NY, USA',
        ]);
        
        $this->add_control('zoom', [
            'label' => __('Zoom', 'probuilder'),
            'type' => 'slider',
            'default' => 12,
            'range' => [
                'px' => ['min' => 1, 'max' => 20],
            ],
        ]);
        
        $this->add_control('height', [
            'label' => __('Height', 'probuilder'),
            'type' => 'slider',
            'default' => 400,
            'range' => [
                'px' => ['min' => 200, 'max' => 800],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $address = $this->get_settings('address', 'New York, NY, USA');
        $zoom = $this->get_settings('zoom', 12);
        $height = $this->get_settings('height', 400);
        
        $encoded_address = urlencode($address);
        
        echo '<div class="probuilder-map" style="width: 100%; height: ' . esc_attr($height) . 'px;">';
        echo '<iframe width="100%" height="100%" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=' . esc_attr($encoded_address) . '&zoom=' . esc_attr($zoom) . '" allowfullscreen></iframe>';
        echo '</div>';
        
        // Note: In production, you would need a Google Maps API key
        echo '<div style="font-size: 12px; color: #999; margin-top: 10px;"><em>' . esc_html__('Note: Google Maps API key required for production use.', 'probuilder') . '</em></div>';
    }
}

