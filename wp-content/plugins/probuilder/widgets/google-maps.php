<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Google_Maps_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'google-maps'; }
    public function get_title() { return __('Google Maps', 'probuilder'); }
    public function get_icon() { return 'fa fa-map-marked-alt'; }
    public function get_categories() { return ['content']; }
    
    protected function register_controls() {
        $this->add_control('api_key', ['label' => __('Google Maps API Key', 'probuilder'), 'type' => 'text', 'default' => '']);
        $this->add_control('address', ['label' => __('Address', 'probuilder'), 'type' => 'textarea', 'default' => 'New York, NY']);
        $this->add_control('zoom', ['label' => __('Zoom Level', 'probuilder'), 'type' => 'slider', 'default' => 12, 'min' => 1, 'max' => 20]);
        $this->add_control('height', ['label' => __('Height', 'probuilder'), 'type' => 'slider', 'default' => 400, 'min' => 200, 'max' => 1000]);
    }
    
    protected function render() {
        $this->render_custom_css();
        $s = $this->get_settings();
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $map_id = 'map-' . uniqid();
        $style = 'width:100%;height:' . $s['height'] . 'px;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div id="' . $map_id . '" class="' . esc_attr($wrapper_classes) . ' pb-google-maps" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '"></div>';
        if (!empty($s['api_key'])) {
            wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $s['api_key']);
            echo '<script>
            function initMap' . $map_id . '() {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({address: "' . esc_js($s['address']) . '"}, function(results, status) {
                    if (status === "OK") {
                        var map = new google.maps.Map(document.getElementById("' . $map_id . '"), {
                            zoom: ' . $s['zoom'] . ',
                            center: results[0].geometry.location
                        });
                        new google.maps.Marker({position: results[0].geometry.location, map: map});
                    }
                });
            }
            if (typeof google !== "undefined") initMap' . $map_id . '();
            </script>';
        } else {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">Please add Google Maps API key in widget settings</p>';
        }
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Google_Maps_Widget());

