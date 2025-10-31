<?php
/**
 * Accordion Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Accordion extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'accordion';
        $this->title = __('Accordion', 'probuilder');
        $this->icon = 'fa fa-bars-staggered';
        $this->category = 'advanced';
        $this->keywords = ['accordion', 'collapse', 'toggle'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_accordion', [
            'label' => __('Accordion Items', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('items', [
            'label' => __('Accordion Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'title' => __('What is ProBuilder?', 'probuilder'),
                    'content' => __('ProBuilder is a powerful page builder that allows you to create stunning websites with drag-and-drop functionality.', 'probuilder'),
                ],
                [
                    'title' => __('How do I use it?', 'probuilder'),
                    'content' => __('Simply drag widgets from the left panel onto your canvas and customize them using the settings panel on the right.', 'probuilder'),
                ],
                [
                    'title' => __('Is it responsive?', 'probuilder'),
                    'content' => __('Yes! ProBuilder creates fully responsive designs that work perfectly on all devices.', 'probuilder'),
                ],
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Accordion Title', 'probuilder'),
                ],
                [
                    'name' => 'content',
                    'label' => __('Content', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Accordion content', 'probuilder'),
                ],
            ],
        ]);
        
        $this->add_control('allow_multiple', [
            'label' => __('Allow Multiple Open', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Allow multiple accordion items to be open at the same time', 'probuilder'),
        ]);
        
        $this->add_control('default_open', [
            'label' => __('Default Open Item', 'probuilder'),
            'type' => 'number',
            'default' => 1,
            'min' => 0,
            'description' => __('Which item should be open by default (0 = none, 1 = first, 2 = second, etc.)', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('title_bg_color', [
            'label' => __('Title Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f8f9fa',
        ]);
        
        $this->add_control('title_text_color', [
            'label' => __('Title Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('active_bg_color', [
            'label' => __('Active Background', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('active_text_color', [
            'label' => __('Active Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('content_bg_color', [
            'label' => __('Content Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('content_text_color', [
            'label' => __('Content Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e6e9ec',
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 4,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $items = $this->get_settings('items', []);
        $allow_multiple = $this->get_settings('allow_multiple', 'no');
        $default_open = $this->get_settings('default_open', 1);
        $title_bg = $this->get_settings('title_bg_color', '#f8f9fa');
        $title_text = $this->get_settings('title_text_color', '#333333');
        $active_bg = $this->get_settings('active_bg_color', '#92003b');
        $active_text = $this->get_settings('active_text_color', '#ffffff');
        $content_bg = $this->get_settings('content_bg_color', '#ffffff');
        $content_text = $this->get_settings('content_text_color', '#666666');
        $border_color = $this->get_settings('border_color', '#e6e9ec');
        $border_radius = $this->get_settings('border_radius', 4);
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]);
        $margin = $this->get_settings('margin', ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        if (empty($items)) {
            return;
        }
        
        $id = 'probuilder-accordion-' . uniqid();
        $container_style = 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px;';
        if ($inline_styles) {
            $container_style .= ' ' . $inline_styles;
        }
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-accordion" ' . $wrapper_attributes . ' id="' . esc_attr($id) . '" style="' . esc_attr($container_style) . '" data-allow-multiple="' . esc_attr($allow_multiple) . '">';
        
        foreach ($items as $index => $item) {
            $item_id = $id . '-' . $index;
            $is_open = ($default_open > 0 && $index + 1 == $default_open);
            
            // Title
            $title_style = 'background-color: ' . esc_attr($is_open ? $active_bg : $title_bg) . '; ';
            $title_style .= 'color: ' . esc_attr($is_open ? $active_text : $title_text) . '; ';
            $title_style .= 'padding: 15px 20px; cursor: pointer; border: 1px solid ' . esc_attr($border_color) . '; margin-bottom: 0; position: relative; ';
            $title_style .= 'border-radius: ' . esc_attr($border_radius) . 'px; transition: all 0.3s ease;';
            
            echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-accordion-item" ' . $wrapper_attributes . '  style="margin-bottom: 10px;">';
            echo '<div class="probuilder-accordion-title" data-item="' . esc_attr($item_id) . '" style="' . $title_style . '">';
            echo '<span style="font-weight: 600;">' . esc_html($item['title']) . '</span>';
            echo '<span class="probuilder-accordion-icon" style="position: absolute; right: 20px; font-size: 18px; transition: all 0.3s;">' . ($is_open ? '−' : '+') . '</span>';
            echo '</div>';
            
            // Content
            $content_style = 'background-color: ' . esc_attr($content_bg) . '; ';
            $content_style .= 'color: ' . esc_attr($content_text) . '; ';
            $content_style .= 'padding: ' . ($is_open ? '15px 20px' : '0 20px') . '; ';
            $content_style .= 'border: 1px solid ' . esc_attr($border_color) . '; border-top: none; ';
            $content_style .= 'max-height: ' . ($is_open ? '500px' : '0') . '; ';
            $content_style .= 'overflow: hidden; transition: all 0.3s ease; opacity: ' . ($is_open ? '1' : '0') . ';';
            
            echo '<div class="probuilder-accordion-content" data-item="' . esc_attr($item_id) . '" style="' . $content_style . '">';
            echo wp_kses_post(wpautop($item['content']));
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Add JavaScript for interactivity
        echo '<script>
        jQuery(document).ready(function($) {
            $("#' . esc_js($id) . ' .probuilder-accordion-title").on("click", function(e) {
                e.stopPropagation();
                const $header = $(this);
                const $item = $header.closest(".probuilder-accordion-item");
                const $content = $item.find(".probuilder-accordion-content");
                const $icon = $header.find(".probuilder-accordion-icon");
                const $accordion = $("#' . esc_js($id) . '");
                const allowMultiple = $accordion.data("allow-multiple") === "yes";
                
                const activeBg = "' . esc_js($active_bg) . '";
                const activeColor = "' . esc_js($active_text) . '";
                const inactiveBg = "' . esc_js($title_bg) . '";
                const inactiveColor = "' . esc_js($title_text) . '";
                
                const isCurrentlyOpen = $content.css("max-height") !== "0px";
                
                if (!allowMultiple && !isCurrentlyOpen) {
                    // Close all other items
                    $accordion.find(".probuilder-accordion-content").css({
                        "max-height": "0",
                        "padding": "0 20px",
                        "opacity": "0"
                    });
                    $accordion.find(".probuilder-accordion-icon").text("+");
                    $accordion.find(".probuilder-accordion-title").css({
                        "background": inactiveBg,
                        "color": inactiveColor
                    });
                }
                
                if (isCurrentlyOpen) {
                    // Close this item
                    $content.css({
                        "max-height": "0",
                        "padding": "0 20px",
                        "opacity": "0"
                    });
                    $icon.text("+");
                    $header.css({
                        "background": inactiveBg,
                        "color": inactiveColor
                    });
                } else {
                    // Open this item
                    $content.css({
                        "max-height": "500px",
                        "padding": "15px 20px",
                        "opacity": "1"
                    });
                    $icon.text("−");
                    $header.css({
                        "background": activeBg,
                        "color": activeColor
                    });
                }
            });
        });
        </script>';
    }
}

