<?php
/**
 * Slider Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Slider extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'slider';
        $this->title = __('Image Slider', 'probuilder');
        $this->icon = 'fa fa-sliders';
        $this->category = 'content';
        $this->keywords = ['slider', 'carousel', 'images', 'gallery', 'slideshow'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('content_section', [
            'label' => 'Slider Content',
            'tab' => 'content'
        ]);
        
        $this->add_control('slides', [
            'label' => 'Slides',
            'type' => 'repeater',
            'fields' => [
                [
                    'name' => 'image',
                    'label' => 'Image',
                    'type' => 'media',
                    'default' => ['url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200']
                ],
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'default' => 'Slide Title'
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'default' => 'Slide description goes here...'
                ],
                [
                    'name' => 'button_text',
                    'label' => 'Button Text',
                    'type' => 'text',
                    'default' => 'Learn More'
                ],
                [
                    'name' => 'button_link',
                    'label' => 'Button Link',
                    'type' => 'url',
                    'default' => '#'
                ],
                [
                    'name' => 'content_position',
                    'label' => 'Content Position',
                    'type' => 'select',
                    'options' => [
                        'left' => 'Left',
                        'center' => 'Center',
                        'right' => 'Right'
                    ],
                    'default' => 'center'
                ]
            ],
            'default' => [
                [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200'],
                    'title' => 'Welcome to Our Website',
                    'description' => 'Discover amazing products and services that will transform your business.',
                    'button_text' => 'Get Started',
                    'button_link' => '#',
                    'content_position' => 'center'
                ],
                [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1200'],
                    'title' => 'Quality Products',
                    'description' => 'We offer the highest quality products with exceptional customer service.',
                    'button_text' => 'Shop Now',
                    'button_link' => '#',
                    'content_position' => 'center'
                ],
                [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200'],
                    'title' => 'Expert Support',
                    'description' => 'Our team of experts is here to help you every step of the way.',
                    'button_text' => 'Contact Us',
                    'button_link' => '#',
                    'content_position' => 'center'
                ]
            ]
        ]);
        
        $this->add_control('slider_style', [
            'label' => __('Slider Style', 'probuilder'),
            'type' => 'select',
            'default' => 'classic',
            'options' => [
                'classic' => __('Classic', 'probuilder'),
                'modern' => __('Modern Fade', 'probuilder'),
                'ken-burns' => __('Ken Burns (Zoom)', 'probuilder'),
                'parallax' => __('Parallax', 'probuilder'),
                'full-screen' => __('Full Screen (100vh - hides content below!)', 'probuilder'),
                'boxed' => __('Boxed', 'probuilder'),
            ],
            'description' => __('WARNING: Full Screen takes entire viewport height!', 'probuilder'),
        ]);
        
        $this->add_control('slider_height', [
            'label' => 'Slider Height',
            'type' => 'slider',
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'default' => ['size' => 500]
        ]);
        
        $this->add_control('transition_effect', [
            'label' => __('Transition Effect', 'probuilder'),
            'type' => 'select',
            'default' => 'fade',
            'options' => [
                'fade' => __('Fade', 'probuilder'),
                'slide' => __('Slide', 'probuilder'),
                'zoom' => __('Zoom In', 'probuilder'),
                'zoom-out' => __('Zoom Out', 'probuilder'),
                'flip' => __('3D Flip', 'probuilder'),
                'cube' => __('3D Cube', 'probuilder'),
            ],
        ]);
        
        $this->add_control('transition_speed', [
            'label' => __('Transition Speed (ms)', 'probuilder'),
            'type' => 'number',
            'default' => 500,
        ]);
        
        $this->add_control('autoplay', [
            'label' => 'Autoplay',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('autoplay_speed', [
            'label' => 'Autoplay Speed (seconds)',
            'type' => 'slider',
            'range' => ['px' => ['min' => 1, 'max' => 15]],
            'default' => ['size' => 5],
            'condition' => ['autoplay' => 'yes']
        ]);
        
        $this->add_control('pause_on_hover', [
            'label' => __('Pause on Hover', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('infinite_loop', [
            'label' => __('Infinite Loop', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_arrows', [
            'label' => 'Show Navigation Arrows',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('show_dots', [
            'label' => 'Show Dots Navigation',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('show_progress_bar', [
            'label' => __('Show Progress Bar', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('show_fraction', [
            'label' => __('Show Fraction (1/5)', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->end_controls_section();
        
        // Navigation Style Section
        $this->start_controls_section('section_navigation', [
            'label' => __('Navigation Style', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('arrow_style', [
            'label' => __('Arrow Style', 'probuilder'),
            'type' => 'select',
            'default' => 'circle',
            'options' => [
                'circle' => __('Circle', 'probuilder'),
                'square' => __('Square', 'probuilder'),
                'rounded' => __('Rounded Square', 'probuilder'),
                'minimal' => __('Minimal', 'probuilder'),
                'chevron' => __('Chevron Only', 'probuilder'),
            ],
        ]);
        
        $this->add_control('arrow_position', [
            'label' => __('Arrow Position', 'probuilder'),
            'type' => 'select',
            'default' => 'inside',
            'options' => [
                'inside' => __('Inside', 'probuilder'),
                'outside' => __('Outside', 'probuilder'),
                'center-edge' => __('Center Edge', 'probuilder'),
            ],
        ]);
        
        $this->add_control('arrow_size', [
            'label' => __('Arrow Size', 'probuilder'),
            'type' => 'slider',
            'default' => 50,
            'range' => [
                'px' => ['min' => 30, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('dot_style', [
            'label' => __('Dot Style', 'probuilder'),
            'type' => 'select',
            'default' => 'circle',
            'options' => [
                'circle' => __('Circle', 'probuilder'),
                'square' => __('Square', 'probuilder'),
                'line' => __('Line', 'probuilder'),
                'dash' => __('Dash', 'probuilder'),
            ],
        ]);
        
        $this->add_control('dot_size', [
            'label' => __('Dot Size', 'probuilder'),
            'type' => 'slider',
            'default' => 12,
            'range' => [
                'px' => ['min' => 6, 'max' => 24, 'step' => 2],
            ],
        ]);
        
        $this->add_control('dot_position', [
            'label' => __('Dot Position', 'probuilder'),
            'type' => 'select',
            'default' => 'bottom-center',
            'options' => [
                'bottom-center' => __('Bottom Center', 'probuilder'),
                'bottom-left' => __('Bottom Left', 'probuilder'),
                'bottom-right' => __('Bottom Right', 'probuilder'),
                'top-center' => __('Top Center', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Content Animation Section
        $this->start_controls_section('section_animation', [
            'label' => __('Content Animation', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('content_animation', [
            'label' => __('Content Animation', 'probuilder'),
            'type' => 'select',
            'default' => 'fade-up',
            'options' => [
                'none' => __('None', 'probuilder'),
                'fade-up' => __('Fade Up', 'probuilder'),
                'fade-down' => __('Fade Down', 'probuilder'),
                'fade-left' => __('Fade Left', 'probuilder'),
                'fade-right' => __('Fade Right', 'probuilder'),
                'zoom-in' => __('Zoom In', 'probuilder'),
                'zoom-out' => __('Zoom Out', 'probuilder'),
            ],
        ]);
        
        $this->add_control('animation_delay', [
            'label' => __('Animation Delay (ms)', 'probuilder'),
            'type' => 'number',
            'default' => 200,
        ]);
        
        $this->add_control('stagger_animation', [
            'label' => __('Stagger Elements', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Animate title, description, and button separately', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Overlay Style Section
        $this->start_controls_section('section_overlay', [
            'label' => __('Overlay', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('overlay_type', [
            'label' => __('Overlay Type', 'probuilder'),
            'type' => 'select',
            'default' => 'color',
            'options' => [
                'none' => __('None', 'probuilder'),
                'color' => __('Solid Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
            ],
        ]);
        
        $this->add_control('overlay_color', [
            'label' => 'Overlay Color',
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.4)'
        ]);
        
        $this->add_control('overlay_gradient_start', [
            'label' => __('Gradient Start', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.6)',
        ]);
        
        $this->add_control('overlay_gradient_end', [
            'label' => __('Gradient End', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.2)',
        ]);
        
        $this->end_controls_section();
        
        // Typography Section
        $this->start_controls_section('section_typography', [
            'label' => __('Typography', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('title_size', [
            'label' => __('Title Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 48,
            'range' => [
                'px' => ['min' => 24, 'max' => 80, 'step' => 2],
            ],
        ]);
        
        $this->add_control('title_weight', [
            'label' => __('Title Weight', 'probuilder'),
            'type' => 'select',
            'default' => '700',
            'options' => [
                '300' => __('Light', 'probuilder'),
                '400' => __('Normal', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
                '900' => __('Black', 'probuilder'),
            ],
        ]);
        
        $this->add_control('title_shadow', [
            'label' => __('Title Text Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('description_color', [
            'label' => 'Description Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('description_size', [
            'label' => __('Description Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 18,
            'range' => [
                'px' => ['min' => 12, 'max' => 32, 'step' => 1],
            ],
        ]);
        
        $this->add_control('content_max_width', [
            'label' => __('Content Max Width (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 600,
            'range' => [
                'px' => ['min' => 400, 'max' => 1200, 'step' => 50],
            ],
        ]);
        
        $this->add_control('content_bg_enable', [
            'label' => __('Content Background', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('content_bg_color', [
            'label' => __('Content BG Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(255,255,255,0.1)',
        ]);
        
        $this->add_control('content_bg_blur', [
            'label' => __('Background Blur', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Glass morphism effect', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Button Style Section
        $this->start_controls_section('section_button_style', [
            'label' => __('Button Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('button_bg_color', [
            'label' => 'Button Background',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('button_text_color', [
            'label' => 'Button Text Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('button_hover_bg', [
            'label' => __('Button Hover BG', 'probuilder'),
            'type' => 'color',
            'default' => '#7a0031',
        ]);
        
        $this->add_control('button_size', [
            'label' => __('Button Size', 'probuilder'),
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => __('Small', 'probuilder'),
                'medium' => __('Medium', 'probuilder'),
                'large' => __('Large', 'probuilder'),
            ],
        ]);
        
        $this->add_control('button_border_radius', [
            'label' => __('Button Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 5,
            'range' => [
                'px' => ['min' => 0, 'max' => 50, 'step' => 1],
            ],
        ]);
        
        $this->add_control('button_shadow', [
            'label' => __('Button Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        // Navigation Colors Section
        $this->start_controls_section('section_navigation_colors', [
            'label' => __('Navigation Colors', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('arrow_color', [
            'label' => 'Arrow Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('arrow_bg_color', [
            'label' => __('Arrow Background', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.5)',
        ]);
        
        $this->add_control('arrow_hover_bg', [
            'label' => __('Arrow Hover BG', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(146,0,59,0.9)',
        ]);
        
        $this->add_control('dot_color', [
            'label' => 'Dot Color',
            'type' => 'color',
            'default' => 'rgba(255,255,255,0.5)'
        ]);
        
        $this->add_control('active_dot_color', [
            'label' => 'Active Dot Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('progress_bar_color', [
            'label' => __('Progress Bar Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('fraction_color', [
            'label' => __('Fraction Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        // Get all settings
        $slides = $this->get_settings('slides', []);
        $slider_style_type = $this->get_settings('slider_style', 'classic');
        $slider_height = $this->get_settings('slider_height', ['size' => 500]);
        $transition_effect = $this->get_settings('transition_effect', 'fade');
        $transition_speed = $this->get_settings('transition_speed', 500);
        $autoplay = $this->get_settings('autoplay', 'yes');
        $autoplay_speed = $this->get_settings('autoplay_speed', ['size' => 5]);
        $pause_on_hover = $this->get_settings('pause_on_hover', 'yes');
        $infinite_loop = $this->get_settings('infinite_loop', 'yes');
        $show_arrows = $this->get_settings('show_arrows', 'yes');
        $show_dots = $this->get_settings('show_dots', 'yes');
        $show_progress_bar = $this->get_settings('show_progress_bar', 'no');
        $show_fraction = $this->get_settings('show_fraction', 'no');
        
        // Navigation settings
        $arrow_style = $this->get_settings('arrow_style', 'circle');
        $arrow_position = $this->get_settings('arrow_position', 'inside');
        $arrow_size = $this->get_settings('arrow_size', 50);
        $dot_style = $this->get_settings('dot_style', 'circle');
        $dot_size = $this->get_settings('dot_size', 12);
        $dot_position = $this->get_settings('dot_position', 'bottom-center');
        
        // Animation settings
        $content_animation = $this->get_settings('content_animation', 'fade-up');
        $animation_delay = $this->get_settings('animation_delay', 200);
        $stagger_animation = $this->get_settings('stagger_animation', 'yes');
        
        // Overlay settings
        $overlay_type = $this->get_settings('overlay_type', 'color');
        $overlay_color = $this->get_settings('overlay_color', 'rgba(0,0,0,0.4)');
        $overlay_gradient_start = $this->get_settings('overlay_gradient_start', 'rgba(0,0,0,0.6)');
        $overlay_gradient_end = $this->get_settings('overlay_gradient_end', 'rgba(0,0,0,0.2)');
        
        // Typography settings
        $title_color = $this->get_settings('title_color', '#ffffff');
        $title_size = $this->get_settings('title_size', 48);
        $title_weight = $this->get_settings('title_weight', '700');
        $title_shadow = $this->get_settings('title_shadow', 'yes');
        $description_color = $this->get_settings('description_color', '#ffffff');
        $description_size = $this->get_settings('description_size', 18);
        $content_max_width = $this->get_settings('content_max_width', 600);
        $content_bg_enable = $this->get_settings('content_bg_enable', 'no');
        $content_bg_color = $this->get_settings('content_bg_color', 'rgba(255,255,255,0.1)');
        $content_bg_blur = $this->get_settings('content_bg_blur', 'no');
        
        // Button settings
        $button_bg_color = $this->get_settings('button_bg_color', '#92003b');
        $button_text_color = $this->get_settings('button_text_color', '#ffffff');
        $button_hover_bg = $this->get_settings('button_hover_bg', '#7a0031');
        $button_size = $this->get_settings('button_size', 'medium');
        $button_border_radius = $this->get_settings('button_border_radius', 5);
        $button_shadow = $this->get_settings('button_shadow', 'yes');
        
        // Navigation colors
        $arrow_color = $this->get_settings('arrow_color', '#ffffff');
        $arrow_bg_color = $this->get_settings('arrow_bg_color', 'rgba(0,0,0,0.5)');
        $arrow_hover_bg = $this->get_settings('arrow_hover_bg', 'rgba(146,0,59,0.9)');
        $dot_color = $this->get_settings('dot_color', 'rgba(255,255,255,0.5)');
        $active_dot_color = $this->get_settings('active_dot_color', '#ffffff');
        $progress_bar_color = $this->get_settings('progress_bar_color', '#92003b');
        $fraction_color = $this->get_settings('fraction_color', '#ffffff');
        
        if (empty($slides)) {
            return;
        }
        
        $slider_id = 'probuilder-slider-' . uniqid();
        $height_value = is_array($slider_height) ? ($slider_height['size'] ?? 500) : $slider_height;
        $autoplay_speed_value = is_array($autoplay_speed) ? ($autoplay_speed['size'] ?? 5) : $autoplay_speed;
        
        // Determine slider wrapper style based on slider type
        $is_full_screen = $slider_style_type === 'full-screen';
        $is_boxed = $slider_style_type === 'boxed';
        
        $slider_style = 'position: relative; overflow: hidden; display: block; width: 100%;';
        if ($is_full_screen) {
            $slider_style .= ' height: 100vh; margin: 0;';
        } else {
            $slider_style .= ' height: ' . esc_attr($height_value) . 'px; margin-bottom: 20px;';
        }
        if (!$is_boxed) {
            $slider_style .= ' border-radius: 8px;';
        } else {
            $slider_style .= ' border-radius: 16px; max-width: 1200px; margin-left: auto; margin-right: auto;';
        }
        if ($inline_styles) $slider_style .= ' ' . $inline_styles;
        
        // Add custom styles for this slider
        ?>
        <style>
            #<?php echo $slider_id; ?> {
                position: relative !important;
                z-index: 1;
                clear: both;
                margin-bottom: 0;
            }
            
            /* Prevent slider from covering other content */
            #<?php echo $slider_id; ?>.probuilder-slider-full-screen {
                position: relative !important;
                /* NOT fixed - allow content below to be visible */
            }
            
            #<?php echo $slider_id; ?> .probuilder-slide {
                position: absolute !important;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            
            #<?php echo $slider_id; ?> .probuilder-slider-button:hover {
                background-color: <?php echo esc_attr($button_hover_bg); ?> !important;
                transform: translateY(-2px);
                <?php if ($button_shadow === 'yes'): ?>
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                <?php endif; ?>
            }
            
            #<?php echo $slider_id; ?> .probuilder-slider-arrow:hover {
                background-color: <?php echo esc_attr($arrow_hover_bg); ?> !important;
            }
            
            <?php if ($slider_style_type === 'ken-burns'): ?>
            #<?php echo $slider_id; ?> .probuilder-slide.active {
                animation: kenBurns-<?php echo $slider_id; ?> 10s ease-in-out infinite alternate;
            }
            @keyframes kenBurns-<?php echo $slider_id; ?> {
                0% { transform: scale(1); }
                100% { transform: scale(1.1); }
            }
            <?php endif; ?>
        </style>
        <?php
        
        echo '<div id="' . esc_attr($slider_id) . '" class="' . esc_attr($wrapper_classes) . ' probuilder-slider probuilder-slider-' . esc_attr($slider_style_type) . '" ' . $wrapper_attributes . ' style="' . esc_attr($slider_style) . '" data-transition="' . esc_attr($transition_effect) . '" data-speed="' . esc_attr($transition_speed) . '">';
        
        foreach ($slides as $index => $slide) {
            $active_class = $index === 0 ? 'active' : '';
            $content_position = isset($slide['content_position']) ? $slide['content_position'] : 'center';
            $content_align = $content_position === 'left' ? 'flex-start' : ($content_position === 'right' ? 'flex-end' : 'center');
            $image_url = is_array($slide['image']) ? ($slide['image']['url'] ?? '') : $slide['image'];
            
            // Determine overlay style
            $overlay_style = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%;';
            if ($overlay_type === 'gradient') {
                $overlay_style .= ' background: linear-gradient(135deg, ' . esc_attr($overlay_gradient_start) . ', ' . esc_attr($overlay_gradient_end) . ');';
            } elseif ($overlay_type === 'color') {
                $overlay_style .= ' background-color: ' . esc_attr($overlay_color) . ';';
            }
            
            // Slide background style
            $slide_bg_style = 'background-image: url(' . esc_url($image_url) . '); background-size: cover; background-position: center;';
            if ($slider_style_type === 'parallax') {
                $slide_bg_style .= ' background-attachment: fixed;';
            }
            
            echo '<div class="probuilder-slide ' . esc_attr($active_class) . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; ' . $slide_bg_style . ' display: flex; align-items: center; justify-content: ' . esc_attr($content_align) . '; opacity: ' . ($index === 0 ? '1' : '0') . '; transition: all ' . intval($transition_speed) . 'ms ease;">';
            
            if ($overlay_type !== 'none') {
                echo '<div class="probuilder-slide-overlay" style="' . $overlay_style . '"></div>';
            }
            
            // Content container style
            $content_container_style = 'position: relative; z-index: 2; text-align: ' . esc_attr($content_position) . '; max-width: ' . esc_attr($content_max_width) . 'px; padding: 40px;';
            if ($content_bg_enable === 'yes') {
                $content_container_style .= ' background-color: ' . esc_attr($content_bg_color) . '; border-radius: 12px;';
                if ($content_bg_blur === 'yes') {
                    $content_container_style .= ' backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);';
                }
            }
            
            echo '<div class="probuilder-slide-content" style="' . $content_container_style . '" data-animation="' . esc_attr($content_animation) . '">';
            
            // Button size padding
            $button_padding = $button_size === 'small' ? '10px 20px' : ($button_size === 'large' ? '18px 40px' : '15px 30px');
            $button_font_size = $button_size === 'small' ? '14px' : ($button_size === 'large' ? '18px' : '16px');
            
            if (!empty($slide['title'])) {
                $title_style = 'color: ' . esc_attr($title_color) . '; font-size: ' . esc_attr($title_size) . 'px; font-weight: ' . esc_attr($title_weight) . '; margin: 0 0 20px 0; line-height: 1.2;';
                if ($title_shadow === 'yes') {
                    $title_style .= ' text-shadow: 0 2px 10px rgba(0,0,0,0.3);';
                }
                echo '<h2 class="probuilder-slide-title" style="' . $title_style . '">' . esc_html($slide['title']) . '</h2>';
            }
            
            if (!empty($slide['description'])) {
                echo '<p class="probuilder-slide-description" style="color: ' . esc_attr($description_color) . '; font-size: ' . esc_attr($description_size) . 'px; margin: 0 0 30px 0; line-height: 1.6;">' . esc_html($slide['description']) . '</p>';
            }
            
            if (!empty($slide['button_text'])) {
                $button_style = 'display: inline-block; background-color: ' . esc_attr($button_bg_color) . '; color: ' . esc_attr($button_text_color) . '; padding: ' . $button_padding . '; text-decoration: none; border-radius: ' . esc_attr($button_border_radius) . 'px; font-weight: 600; font-size: ' . $button_font_size . '; transition: all 0.3s ease;';
                if ($button_shadow === 'yes') {
                    $button_style .= ' box-shadow: 0 4px 10px rgba(0,0,0,0.2);';
                }
                echo '<a href="' . esc_url($slide['button_link']) . '" class="probuilder-slide-button probuilder-slider-button" style="' . $button_style . '">' . esc_html($slide['button_text']) . '</a>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        // Navigation Arrows
        if ($show_arrows === 'yes') {
            // Arrow style
            $arrow_border_radius = $arrow_style === 'circle' ? '50%' : ($arrow_style === 'rounded' ? '8px' : ($arrow_style === 'square' ? '0' : '50%'));
            $arrow_background = $arrow_style === 'chevron' || $arrow_style === 'minimal' ? 'transparent' : $arrow_bg_color;
            $arrow_padding = ($arrow_size / 3) . 'px';
            $arrow_font_size = ($arrow_size / 2) . 'px';
            
            // Arrow positioning
            $arrow_left_pos = $arrow_position === 'outside' ? '-' . ($arrow_size + 10) . 'px' : ($arrow_position === 'center-edge' ? '0' : '20px');
            $arrow_right_pos = $arrow_position === 'outside' ? '-' . ($arrow_size + 10) . 'px' : ($arrow_position === 'center-edge' ? '0' : '20px');
            
            $arrow_base_style = 'position: absolute; top: 50%; transform: translateY(-50%); background: ' . esc_attr($arrow_background) . '; border: none; color: ' . esc_attr($arrow_color) . '; font-size: ' . $arrow_font_size . '; padding: ' . $arrow_padding . '; border-radius: ' . $arrow_border_radius . '; cursor: pointer; z-index: 3; transition: all 0.3s ease;';
            
            echo '<button class="probuilder-slider-prev probuilder-slider-arrow" style="' . $arrow_base_style . ' left: ' . $arrow_left_pos . ';">‹</button>';
            echo '<button class="probuilder-slider-next probuilder-slider-arrow" style="' . $arrow_base_style . ' right: ' . $arrow_right_pos . ';">›</button>';
        }
        
        // Dots Navigation
        if ($show_dots === 'yes') {
            // Dot position
            $dot_bottom = strpos($dot_position, 'bottom') !== false ? '20px' : 'auto';
            $dot_top = strpos($dot_position, 'top') !== false ? '20px' : 'auto';
            $dot_left = $dot_position === 'bottom-left' ? '20px' : '50%';
            $dot_transform = $dot_position === 'bottom-left' ? 'none' : 'translateX(-50%)';
            $dot_right = $dot_position === 'bottom-right' ? '20px' : 'auto';
            if ($dot_position === 'bottom-right') {
                $dot_left = 'auto';
                $dot_transform = 'none';
            }
            
            echo '<div class="probuilder-slider-dots" style="position: absolute; bottom: ' . $dot_bottom . '; top: ' . $dot_top . '; left: ' . $dot_left . '; right: ' . $dot_right . '; transform: ' . $dot_transform . '; display: flex; gap: 10px; z-index: 3;">';
            foreach ($slides as $index => $slide) {
                $active_class = $index === 0 ? 'active' : '';
                $dot_bg_color = $index === 0 ? $active_dot_color : $dot_color;
                
                // Dot shape
                $dot_border_radius = $dot_style === 'circle' ? '50%' : ($dot_style === 'square' ? '0' : '2px');
                $dot_width = $dot_style === 'line' ? ($dot_size * 3) . 'px' : ($dot_style === 'dash' ? ($dot_size * 2) . 'px' : $dot_size . 'px');
                $dot_height = $dot_style === 'line' ? ($dot_size / 2) . 'px' : ($dot_style === 'dash' ? ($dot_size / 2) . 'px' : $dot_size . 'px');
                
                echo '<button class="probuilder-slider-dot ' . esc_attr($active_class) . '" data-slide="' . esc_attr($index) . '" style="width: ' . $dot_width . '; height: ' . $dot_height . '; border-radius: ' . $dot_border_radius . '; border: none; background-color: ' . esc_attr($dot_bg_color) . '; cursor: pointer; transition: all 0.3s ease;"></button>';
            }
            echo '</div>';
        }
        
        // Progress Bar
        if ($show_progress_bar === 'yes') {
            echo '<div class="probuilder-slider-progress" style="position: absolute; bottom: 0; left: 0; width: 0%; height: 4px; background-color: ' . esc_attr($progress_bar_color) . '; z-index: 4; transition: width linear;"></div>';
        }
        
        // Fraction Counter
        if ($show_fraction === 'yes') {
            echo '<div class="probuilder-slider-fraction" style="position: absolute; top: 20px; right: 20px; color: ' . esc_attr($fraction_color) . '; font-size: 14px; font-weight: 600; z-index: 3; background: rgba(0,0,0,0.3); padding: 8px 16px; border-radius: 20px;"><span class="current">1</span> / <span class="total">' . count($slides) . '</span></div>';
        }
        
        echo '</div>';
        
        // Add JavaScript for slider functionality
        ?>
        <script>
        (function() {
            function initSlider() {
                const slider = document.getElementById("<?php echo esc_js($slider_id); ?>");
                if (!slider) return;
                
                const slides = slider.querySelectorAll(".probuilder-slide");
                const dots = slider.querySelectorAll(".probuilder-slider-dot");
                const prevBtn = slider.querySelector(".probuilder-slider-prev");
                const nextBtn = slider.querySelector(".probuilder-slider-next");
                const progressBar = slider.querySelector(".probuilder-slider-progress");
                const fractionCurrent = slider.querySelector(".probuilder-slider-fraction .current");
                const transitionEffect = slider.dataset.transition || 'fade';
                const transitionSpeed = parseInt(slider.dataset.speed) || 500;
                
                let currentSlide = 0;
                const totalSlides = slides.length;
                const autoplay = <?php echo $autoplay === 'yes' ? 'true' : 'false'; ?>;
                const autoplaySpeed = <?php echo esc_js($autoplay_speed_value); ?> * 1000;
                const pauseOnHover = <?php echo $pause_on_hover === 'yes' ? 'true' : 'false'; ?>;
                const infiniteLoop = <?php echo $infinite_loop === 'yes' ? 'true' : 'false'; ?>;
                const contentAnimation = "<?php echo esc_js($content_animation); ?>";
                const staggerAnimation = <?php echo $stagger_animation === 'yes' ? 'true' : 'false'; ?>;
                const animationDelay = <?php echo intval($animation_delay); ?>;
                
                let autoplayInterval;
                let progressInterval;
                
                function applyTransition(currentSlide, nextSlide, direction) {
                    switch(transitionEffect) {
                        case 'slide':
                            currentSlide.style.transform = direction === 'next' ? 'translateX(-100%)' : 'translateX(100%)';
                            nextSlide.style.transform = 'translateX(0)';
                            nextSlide.style.opacity = '1';
                            setTimeout(() => {
                                currentSlide.style.transform = 'translateX(0)';
                                currentSlide.style.opacity = '0';
                            }, transitionSpeed);
                            break;
                            
                        case 'zoom':
                            currentSlide.style.transform = 'scale(1.2)';
                            currentSlide.style.opacity = '0';
                            nextSlide.style.transform = 'scale(1)';
                            nextSlide.style.opacity = '1';
                            break;
                            
                        case 'zoom-out':
                            currentSlide.style.transform = 'scale(0.8)';
                            currentSlide.style.opacity = '0';
                            nextSlide.style.transform = 'scale(1)';
                            nextSlide.style.opacity = '1';
                            break;
                            
                        case 'flip':
                            currentSlide.style.transform = 'rotateY(90deg)';
                            currentSlide.style.opacity = '0';
                            nextSlide.style.transform = 'rotateY(0)';
                            nextSlide.style.opacity = '1';
                            break;
                            
                        case 'cube':
                            currentSlide.style.transform = 'rotateY(-90deg) translateZ(-200px)';
                            currentSlide.style.opacity = '0';
                            nextSlide.style.transform = 'rotateY(0) translateZ(0)';
                            nextSlide.style.opacity = '1';
                            break;
                            
                        default: // fade
                            currentSlide.style.opacity = '0';
                            nextSlide.style.opacity = '1';
                    }
                }
                
                function animateContent(slideContent) {
                    const title = slideContent.querySelector('.probuilder-slide-title');
                    const description = slideContent.querySelector('.probuilder-slide-description');
                    const button = slideContent.querySelector('.probuilder-slide-button');
                    const elements = [title, description, button].filter(el => el);
                    
                    if (contentAnimation === 'none') return;
                    
                    elements.forEach((el, i) => {
                        if (!el) return;
                        const delay = staggerAnimation ? i * 150 : 0;
                        
                        el.style.opacity = '0';
                        el.style.transition = `all ${transitionSpeed}ms ease`;
                        
                        setTimeout(() => {
                            el.style.opacity = '1';
                            
                            switch(contentAnimation) {
                                case 'fade-up':
                                    el.style.transform = 'translateY(0)';
                                    break;
                                case 'fade-down':
                                    el.style.transform = 'translateY(0)';
                                    break;
                                case 'fade-left':
                                    el.style.transform = 'translateX(0)';
                                    break;
                                case 'fade-right':
                                    el.style.transform = 'translateX(0)';
                                    break;
                                case 'zoom-in':
                                    el.style.transform = 'scale(1)';
                                    break;
                                case 'zoom-out':
                                    el.style.transform = 'scale(1)';
                                    break;
                            }
                        }, animationDelay + delay);
                    });
                }
                
                function showSlide(index) {
                    const oldSlide = slides[currentSlide];
                    const newSlide = slides[index];
                    const direction = index > currentSlide ? 'next' : 'prev';
                    
                    applyTransition(oldSlide, newSlide, direction);
                    
                    // Animate content
                    const slideContent = newSlide.querySelector('.probuilder-slide-content');
                    if (slideContent) {
                        setTimeout(() => animateContent(slideContent), 100);
                    }
                    
                    // Update dots
                    dots.forEach((dot, i) => {
                        dot.classList.toggle("active", i === index);
                        dot.style.backgroundColor = i === index ? "<?php echo esc_js($active_dot_color); ?>" : "<?php echo esc_js($dot_color); ?>";
                    });
                    
                    // Update fraction
                    if (fractionCurrent) {
                        fractionCurrent.textContent = index + 1;
                    }
                    
                    currentSlide = index;
                }
            
                
                function nextSlide() {
                    let next = currentSlide + 1;
                    if (next >= totalSlides) {
                        next = infiniteLoop ? 0 : currentSlide;
                    }
                    if (next !== currentSlide) showSlide(next);
                }
                
                function prevSlide() {
                    let prev = currentSlide - 1;
                    if (prev < 0) {
                        prev = infiniteLoop ? totalSlides - 1 : 0;
                    }
                    if (prev !== currentSlide) showSlide(prev);
                }
                
                function startProgressBar() {
                    if (!progressBar) return;
                    let progress = 0;
                    const increment = 100 / (autoplaySpeed / 50);
                    
                    progressInterval = setInterval(() => {
                        progress += increment;
                        if (progress >= 100) {
                            progress = 0;
                        }
                        progressBar.style.width = progress + '%';
                    }, 50);
                }
                
                function resetProgressBar() {
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }
                    if (progressInterval) {
                        clearInterval(progressInterval);
                    }
                }
                
                function startAutoplay() {
                    if (autoplay) {
                        autoplayInterval = setInterval(nextSlide, autoplaySpeed);
                        startProgressBar();
                    }
                }
                
                function stopAutoplay() {
                    if (autoplayInterval) {
                        clearInterval(autoplayInterval);
                    }
                    resetProgressBar();
                }
                
                function restartAutoplay() {
                    stopAutoplay();
                    startAutoplay();
                }
                
                // Event listeners
                if (nextBtn) {
                    nextBtn.addEventListener("click", function() {
                        nextSlide();
                        restartAutoplay();
                    });
                }
                
                if (prevBtn) {
                    prevBtn.addEventListener("click", function() {
                        prevSlide();
                        restartAutoplay();
                    });
                }
                
                dots.forEach((dot, index) => {
                    dot.addEventListener("click", function() {
                        showSlide(index);
                        restartAutoplay();
                    });
                });
                
                // Pause autoplay on hover
                if (pauseOnHover) {
                    slider.addEventListener("mouseenter", stopAutoplay);
                    slider.addEventListener("mouseleave", startAutoplay);
                }
                
                // Keyboard navigation
                document.addEventListener("keydown", function(e) {
                    if (e.key === "ArrowLeft") prevSlide();
                    if (e.key === "ArrowRight") nextSlide();
                });
                
                // Swipe support for mobile
                let touchStartX = 0;
                let touchEndX = 0;
                
                slider.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                });
                
                slider.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                });
                
                function handleSwipe() {
                    if (touchEndX < touchStartX - 50) nextSlide();
                    if (touchEndX > touchStartX + 50) prevSlide();
                }
                
                // Initialize first slide animation
                const firstSlideContent = slides[0].querySelector('.probuilder-slide-content');
                if (firstSlideContent) {
                    setTimeout(() => animateContent(firstSlideContent), 100);
                }
                
                // Start autoplay
                startAutoplay();
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSlider);
            } else {
                initSlider();
            }
        })();
        </script>
        <?php
    }
}
