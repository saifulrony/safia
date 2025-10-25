<?php
/**
 * Google Map Widget
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
        $this->keywords = ['map', 'google', 'location', 'address'];
    }
    
    protected function register_controls() {
        // LOCATION
        $this->start_controls_section('section_location', [
            'label' => __('Location', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('address', [
            'label' => __('Address', 'probuilder'),
            'type' => 'text',
            'default' => 'Times Square, New York, NY, USA',
            'description' => __('Enter address or location name. Google Maps will find it automatically!', 'probuilder'),
        ]);
        
        $this->add_control('latitude', [
            'label' => __('Latitude (Optional)', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => '40.7580',
            'description' => __('Leave empty to use address above, or enter for precise location', 'probuilder'),
        ]);
        
        $this->add_control('longitude', [
            'label' => __('Longitude (Optional)', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => '-73.9855',
            'description' => __('Leave empty to use address above, or enter for precise location', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // MAP SETTINGS
        $this->start_controls_section('section_settings', [
            'label' => __('Map Settings', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('zoom', [
            'label' => __('Zoom Level', 'probuilder'),
            'type' => 'slider',
            'default' => 12,
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 20,
                    'step' => 1
                ]
            ],
            'description' => __('1 = World, 20 = Building', 'probuilder'),
        ]);
        
        $this->add_control('map_type', [
            'label' => __('Map Type', 'probuilder'),
            'type' => 'select',
            'default' => 'roadmap',
            'options' => [
                'roadmap' => __('Roadmap', 'probuilder'),
                'satellite' => __('Satellite', 'probuilder'),
                'hybrid' => __('Hybrid', 'probuilder'),
                'terrain' => __('Terrain', 'probuilder'),
            ],
        ]);
        
        $this->add_control('show_marker', [
            'label' => __('Show Marker', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('marker_title', [
            'label' => __('Marker Title', 'probuilder'),
            'type' => 'text',
            'default' => 'Our Location',
        ]);
        
        $this->end_controls_section();
        
        // STYLE
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('height', [
            'label' => __('Height (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 400,
            'range' => [
                'px' => [
                    'min' => 200,
                    'max' => 800,
                    'step' => 10
                ]
            ],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ],
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]
        ]);
        
        $this->add_control('entrance_animation', [
            'label' => __('Entrance Animation', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'fadeIn' => __('Fade In', 'probuilder'),
                'fadeInUp' => __('Fade In Up', 'probuilder'),
                'zoomIn' => __('Zoom In', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $id = 'map-' . uniqid();
        
        $address = $this->get_settings('address', 'Times Square, New York, NY, USA');
        $latitude = $this->get_settings('latitude', '');
        $longitude = $this->get_settings('longitude', '');
        $zoom = $this->get_settings('zoom', 12);
        $map_type = $this->get_settings('map_type', 'roadmap');
        $show_marker = $this->get_settings('show_marker', 'yes');
        $marker_title = $this->get_settings('marker_title', 'Our Location');
        $height = $this->get_settings('height', 400);
        $border_radius = $this->get_settings('border_radius', 8);
        $margin = $this->get_settings('margin', ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]);
        $animation = $this->get_settings('entrance_animation', 'none');
        
        $map_style = 'width: 100%; height: ' . $height . 'px; border-radius: ' . $border_radius . 'px; overflow: hidden; ';
        $map_style .= 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; ';
        $map_style .= 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
        
        if ($animation !== 'none') {
            $map_style .= ' animation: ' . $animation . ' 0.6s ease forwards;';
        }
        
        // Smart detection: Use address OR coordinates
        // Priority: If coordinates provided, use them. Otherwise use address.
        $has_coords = !empty($latitude) && !empty($longitude);
        
        if ($has_coords) {
            // User provided coordinates - use them for precision
            $google_maps_url = 'https://maps.google.com/maps?q=' . urlencode($latitude . ',' . $longitude) . '&t=m&z=' . $zoom . '&output=embed&iwloc=near';
        } else {
            // Use address - Google Maps will geocode it automatically
            $google_maps_url = 'https://maps.google.com/maps?q=' . urlencode($address) . '&t=m&z=' . $zoom . '&output=embed&iwloc=near';
        }
        
        // Alternative: OpenStreetMap (fallback)
        $osm_url = 'https://www.openstreetmap.org/export/embed.html?bbox=' . ($longitude - 0.01) . ',' . ($latitude - 0.01) . ',' . ($longitude + 0.01) . ',' . ($latitude + 0.01) . '&layer=mapnik&marker=' . $latitude . ',' . $longitude;
        
        // Use Google Maps by default
        $map_url = $google_maps_url;
        
        ?>
        <div class="probuilder-map-widget" id="<?php echo esc_attr($id); ?>" style="<?php echo $map_style; ?>">
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                style="border:0; display: block;" 
                src="<?php echo esc_url($map_url); ?>" 
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>
        
        <div style="
            font-size: 12px; 
            color: #64748b; 
            margin-top: 8px; 
            display: flex; 
            align-items: center; 
            gap: 5px;
            margin-left: <?php echo $margin['left']; ?>px;
        ">
            <i class="fa fa-map-marker" style="color: #92003b;"></i>
            <span><?php echo esc_html($address); ?></span>
        </div>
        
        <script>
        // Fallback handler if map doesn't load
        (function() {
            var mapIframe = document.querySelector('#<?php echo esc_js($id); ?> iframe');
            if (mapIframe) {
                mapIframe.addEventListener('error', function() {
                    // If Google Maps fails, try OpenStreetMap
                    mapIframe.src = '<?php echo esc_js($osm_url); ?>';
                });
            }
        })();
        </script>
        <?php
    }
}
