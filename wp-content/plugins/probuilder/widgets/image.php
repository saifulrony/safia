<?php
/**
 * Image Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Image extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'image';
        $this->title = __('Image', 'probuilder');
        $this->icon = 'fa fa-image';
        $this->category = 'basic';
        $this->keywords = ['image', 'photo', 'picture', 'img'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Image', 'probuilder'),
        ]);
        
        $this->add_control('image', [
            'label' => __('Choose Image', 'probuilder'),
            'type' => 'media',
            'default' => [
                'url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            ],
        ]);
        
        $this->add_control('image_size', [
            'label' => __('Image Size', 'probuilder'),
            'type' => 'select',
            'default' => 'full',
            'options' => [
                'thumbnail' => __('Thumbnail (150x150)', 'probuilder'),
                'medium' => __('Medium (300x300)', 'probuilder'),
                'large' => __('Large (1024x1024)', 'probuilder'),
                'full' => __('Full Size', 'probuilder'),
            ],
        ]);
        
        $this->add_control('alt_text', [
            'label' => __('Alt Text', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => __('Enter alternative text', 'probuilder'),
            'description' => __('Important for SEO and accessibility', 'probuilder'),
        ]);
        
        $this->add_control('caption', [
            'label' => __('Caption', 'probuilder'),
            'type' => 'textarea',
            'default' => '',
            'placeholder' => __('Enter image caption', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Link Section
        $this->start_controls_section('section_link', [
            'label' => __('Link', 'probuilder'),
        ]);
        
        $this->add_control('link_to', [
            'label' => __('Link To', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'custom' => __('Custom URL', 'probuilder'),
                'lightbox' => __('Lightbox', 'probuilder'),
            ],
        ]);
        
        $this->add_control('link', [
            'label' => __('Link URL', 'probuilder'),
            'type' => 'url',
            'default' => '',
            'placeholder' => 'https://example.com',
        ]);
        
        $this->add_control('open_in_new_tab', [
            'label' => __('Open in New Tab', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->end_controls_section();
        
        // Style - Image Section
        $this->start_controls_section('section_style_image', [
            'label' => __('Image', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('width', [
            'label' => __('Width', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'min' => 0,
                'max' => 100,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('max_width', [
            'label' => __('Max Width (px)', 'probuilder'),
            'type' => 'number',
            'default' => '',
            'placeholder' => 'auto',
            'tab' => 'style',
        ]);
        
        $this->add_control('height', [
            'label' => __('Height (px)', 'probuilder'),
            'type' => 'number',
            'default' => '',
            'placeholder' => 'auto',
            'tab' => 'style',
        ]);
        
        $this->add_control('object_fit', [
            'label' => __('Object Fit', 'probuilder'),
            'type' => 'select',
            'default' => 'cover',
            'options' => [
                'fill' => __('Fill', 'probuilder'),
                'cover' => __('Cover', 'probuilder'),
                'contain' => __('Contain', 'probuilder'),
                'none' => __('None', 'probuilder'),
                'scale-down' => __('Scale Down', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'center',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Style - Border Section
        $this->start_controls_section('section_style_border', [
            'label' => __('Border', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => 0,
                'max' => 200,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('border_width', [
            'label' => __('Border Width (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => 0,
                'max' => 20,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#000000',
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Style - Effects Section
        $this->start_controls_section('section_style_effects', [
            'label' => __('Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('hover_animation', [
            'label' => __('Hover Animation', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'zoom-in' => __('Zoom In', 'probuilder'),
                'zoom-out' => __('Zoom Out', 'probuilder'),
                'slide' => __('Slide', 'probuilder'),
                'rotate' => __('Rotate', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('css_filters_brightness', [
            'label' => __('Brightness', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'min' => 0,
                'max' => 200,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('css_filters_contrast', [
            'label' => __('Contrast', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'min' => 0,
                'max' => 200,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('css_filters_saturate', [
            'label' => __('Saturation', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'min' => 0,
                'max' => 200,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('css_filters_blur', [
            'label' => __('Blur (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => 0,
                'max' => 10,
                'step' => 0.1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('css_filters_hue', [
            'label' => __('Hue Rotate (deg)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => 0,
                'max' => 360,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Style - Box Shadow Section
        $this->start_controls_section('section_style_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_horizontal', [
            'label' => __('Horizontal (px)', 'probuilder'),
            'type' => 'number',
            'default' => 0,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_vertical', [
            'label' => __('Vertical (px)', 'probuilder'),
            'type' => 'number',
            'default' => 5,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_blur', [
            'label' => __('Blur (px)', 'probuilder'),
            'type' => 'number',
            'default' => 15,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_spread', [
            'label' => __('Spread (px)', 'probuilder'),
            'type' => 'number',
            'default' => 0,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_color', [
            'label' => __('Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.2)',
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        // Get settings
        $image = $this->get_settings('image', ['url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop']);
        $alt_text = $this->get_settings('alt_text', '');
        $caption = $this->get_settings('caption', '');
        $link_to = $this->get_settings('link_to', 'none');
        $link = $this->get_settings('link', '');
        $open_in_new_tab = $this->get_settings('open_in_new_tab', 'no');
        $align = $this->get_settings('align', 'center');
        $width = $this->get_settings('width', 100);
        $max_width = $this->get_settings('max_width', '');
        $height = $this->get_settings('height', '');
        $object_fit = $this->get_settings('object_fit', 'cover');
        $border_radius = $this->get_settings('border_radius', 0);
        $border_width = $this->get_settings('border_width', 0);
        $border_color = $this->get_settings('border_color', '#000000');
        $hover_animation = $this->get_settings('hover_animation', 'none');
        
        // CSS Filters
        $brightness = $this->get_settings('css_filters_brightness', 100);
        $contrast = $this->get_settings('css_filters_contrast', 100);
        $saturate = $this->get_settings('css_filters_saturate', 100);
        $blur = $this->get_settings('css_filters_blur', 0);
        $hue = $this->get_settings('css_filters_hue', 0);
        
        // Box Shadow
        $box_shadow = $this->get_settings('box_shadow', 'no');
        $shadow_h = $this->get_settings('box_shadow_horizontal', 0);
        $shadow_v = $this->get_settings('box_shadow_vertical', 5);
        $shadow_blur = $this->get_settings('box_shadow_blur', 15);
        $shadow_spread = $this->get_settings('box_shadow_spread', 0);
        $shadow_color = $this->get_settings('box_shadow_color', 'rgba(0,0,0,0.2)');
        
        // Build wrapper styles
        $wrapper_style = 'text-align: ' . esc_attr($align) . ';';
        
        // Build image styles
        $img_styles = [];
        $img_styles[] = 'width: ' . esc_attr($width) . '%';
        
        if (!empty($max_width)) {
            $img_styles[] = 'max-width: ' . esc_attr($max_width) . 'px';
        }
        
        if (!empty($height)) {
            $img_styles[] = 'height: ' . esc_attr($height) . 'px';
        } else {
            $img_styles[] = 'height: auto';
        }
        
        $img_styles[] = 'object-fit: ' . esc_attr($object_fit);
        
        if ($border_radius > 0) {
            $img_styles[] = 'border-radius: ' . esc_attr($border_radius) . 'px';
        }
        
        if ($border_width > 0) {
            $img_styles[] = 'border: ' . esc_attr($border_width) . 'px solid ' . esc_attr($border_color);
        }
        
        // CSS Filters
        $filters = [];
        if ($brightness != 100) {
            $filters[] = 'brightness(' . ($brightness / 100) . ')';
        }
        if ($contrast != 100) {
            $filters[] = 'contrast(' . ($contrast / 100) . ')';
        }
        if ($saturate != 100) {
            $filters[] = 'saturate(' . ($saturate / 100) . ')';
        }
        if ($blur > 0) {
            $filters[] = 'blur(' . $blur . 'px)';
        }
        if ($hue > 0) {
            $filters[] = 'hue-rotate(' . $hue . 'deg)';
        }
        if (!empty($filters)) {
            $img_styles[] = 'filter: ' . implode(' ', $filters);
        }
        
        // Box Shadow
        if ($box_shadow === 'yes') {
            $img_styles[] = 'box-shadow: ' . $shadow_h . 'px ' . $shadow_v . 'px ' . $shadow_blur . 'px ' . $shadow_spread . 'px ' . $shadow_color;
        }
        
        // Hover animation class
        $hover_class = $hover_animation !== 'none' ? 'probuilder-image-hover-' . $hover_animation : '';
        
        // Start output
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-image-wrapper" ' . $wrapper_attributes . ' style="' . $wrapper_style . '">';
        
        // Link wrapper
        $link_url = '';
        $link_target = '';
        
        if ($link_to === 'custom' && !empty($link)) {
            $link_url = $link;
            $link_target = $open_in_new_tab === 'yes' ? '_blank' : '_self';
        } elseif ($link_to === 'lightbox') {
            $link_url = $image['url'];
            $link_target = '_blank';
        }
        
        if (!empty($link_url)) {
            echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">';
        }
        
        // Image
        echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($alt_text) . '" class="' . esc_attr($hover_class) . '" style="' . implode('; ', $img_styles) . ';">';
        
        if (!empty($link_url)) {
            echo '</a>';
        }
        
        // Caption
        if (!empty($caption)) {
            echo '<div class="probuilder-image-caption" style="margin-top: 10px; font-size: 14px; color: #666; font-style: italic;">' . esc_html($caption) . '</div>';
        }
        
        echo '</div>';
        
        // Add hover animation styles
        if ($hover_animation !== 'none') {
            echo '<style>
                .probuilder-image-hover-zoom-in {
                    transition: transform 0.3s ease;
                }
                .probuilder-image-hover-zoom-in:hover {
                    transform: scale(1.1);
                }
                .probuilder-image-hover-zoom-out {
                    transition: transform 0.3s ease;
                }
                .probuilder-image-hover-zoom-out:hover {
                    transform: scale(0.9);
                }
                .probuilder-image-hover-slide {
                    transition: transform 0.3s ease;
                }
                .probuilder-image-hover-slide:hover {
                    transform: translateX(10px);
                }
                .probuilder-image-hover-rotate {
                    transition: transform 0.3s ease;
                }
                .probuilder-image-hover-rotate:hover {
                    transform: rotate(5deg);
                }
            </style>';
        }
    }
}

