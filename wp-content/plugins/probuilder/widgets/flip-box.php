<?php
/**
 * Flip Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Flip_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'flip-box';
        $this->title = __('Flip Box', 'probuilder');
        $this->icon = 'fa fa-clone';
        $this->category = 'advanced';
        $this->keywords = ['flip', 'box', 'card', 'hover', '3d'];
    }
    
    protected function register_controls() {
        // FRONT SIDE - CONTENT
        $this->start_controls_section('section_front_content', [
            'label' => __('Front Side - Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('front_icon_type', [
            'label' => __('Icon/Image', 'probuilder'),
            'type' => 'select',
            'default' => 'icon',
            'options' => [
                'icon' => __('Icon', 'probuilder'),
                'image' => __('Image', 'probuilder'),
                'none' => __('None', 'probuilder'),
            ],
        ]);
        
        $this->add_control('front_icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'text',
            'default' => 'fa fa-star',
        ]);
        
        $this->add_control('front_image', [
            'label' => __('Image URL', 'probuilder'),
            'type' => 'text',
            'default' => 'https://via.placeholder.com/100x100/92003b/ffffff?text=Icon',
        ]);
        
        $this->add_control('front_title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Amazing Feature', 'probuilder'),
        ]);
        
        $this->add_control('front_description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Hover to see more', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // BACK SIDE - CONTENT
        $this->start_controls_section('section_back_content', [
            'label' => __('Back Side - Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('back_icon_type', [
            'label' => __('Icon/Image', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'icon' => __('Icon', 'probuilder'),
                'image' => __('Image', 'probuilder'),
                'none' => __('None', 'probuilder'),
            ],
        ]);
        
        $this->add_control('back_icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'text',
            'default' => 'fa fa-check-circle',
        ]);
        
        $this->add_control('back_image', [
            'label' => __('Image URL', 'probuilder'),
            'type' => 'text',
            'default' => 'https://via.placeholder.com/100x100/333333/ffffff?text=Icon',
        ]);
        
        $this->add_control('back_title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Discover More', 'probuilder'),
        ]);
        
        $this->add_control('back_description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is an amazing feature that will help you achieve your goals. Learn more about what we can do for you.', 'probuilder'),
        ]);
        
        $this->add_control('show_button', [
            'label' => __('Show Button', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('back_button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Learn More', 'probuilder'),
        ]);
        
        $this->add_control('back_button_link', [
            'label' => __('Button Link', 'probuilder'),
            'type' => 'text',
            'default' => '#',
        ]);
        
        $this->end_controls_section();
        
        // SETTINGS
        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('flip_effect', [
            'label' => __('Flip Effect', 'probuilder'),
            'type' => 'select',
            'default' => 'flip-horizontal',
            'options' => [
                'flip-horizontal' => __('Flip Horizontal', 'probuilder'),
                'flip-vertical' => __('Flip Vertical', 'probuilder'),
                'zoom-in' => __('Zoom In', 'probuilder'),
                'zoom-out' => __('Zoom Out', 'probuilder'),
                'fade' => __('Fade', 'probuilder'),
            ],
        ]);
        
        $this->add_control('flip_duration', [
            'label' => __('Animation Duration (seconds)', 'probuilder'),
            'type' => 'slider',
            'default' => 0.6,
            'range' => [
                'px' => [
                    'min' => 0.1,
                    'max' => 2,
                    'step' => 0.1
                ]
            ]
        ]);
        
        $this->add_control('box_height', [
            'label' => __('Box Height (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 300,
            'range' => [
                'px' => [
                    'min' => 200,
                    'max' => 600,
                    'step' => 10
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        // FRONT STYLE
        $this->start_controls_section('section_front_style', [
            'label' => __('Front Side - Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('front_bg_type', [
            'label' => __('Background Type', 'probuilder'),
            'type' => 'select',
            'default' => 'color',
            'options' => [
                'color' => __('Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'image' => __('Image', 'probuilder'),
            ],
        ]);
        
        $this->add_control('front_bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('front_bg_gradient', [
            'label' => __('Background Gradient', 'probuilder'),
            'type' => 'text',
            'default' => 'linear-gradient(135deg, #92003b 0%, #667eea 100%)',
        ]);
        
        $this->add_control('front_bg_image', [
            'label' => __('Background Image URL', 'probuilder'),
            'type' => 'text',
            'default' => '',
        ]);
        
        $this->add_control('front_text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('front_icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('front_icon_size', [
            'label' => __('Icon Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 60,
            'range' => [
                'px' => [
                    'min' => 20,
                    'max' => 120,
                    'step' => 5
                ]
            ]
        ]);
        
        $this->add_control('front_title_size', [
            'label' => __('Title Font Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 24,
            'range' => [
                'px' => [
                    'min' => 14,
                    'max' => 48,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        // BACK STYLE
        $this->start_controls_section('section_back_style', [
            'label' => __('Back Side - Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('back_bg_type', [
            'label' => __('Background Type', 'probuilder'),
            'type' => 'select',
            'default' => 'color',
            'options' => [
                'color' => __('Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'image' => __('Image', 'probuilder'),
            ],
        ]);
        
        $this->add_control('back_bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('back_bg_gradient', [
            'label' => __('Background Gradient', 'probuilder'),
            'type' => 'text',
            'default' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        ]);
        
        $this->add_control('back_bg_image', [
            'label' => __('Background Image URL', 'probuilder'),
            'type' => 'text',
            'default' => '',
        ]);
        
        $this->add_control('back_text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('back_button_bg', [
            'label' => __('Button Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('back_button_color', [
            'label' => __('Button Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 30, 'right' => 30, 'bottom' => 30, 'left' => 30]
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings();
        
        $id = 'flip-box-' . uniqid();
        
        // Get all settings with defaults
        $front_icon_type = $settings['front_icon_type'] ?? 'icon';
        $front_icon = $settings['front_icon'] ?? 'fa fa-star';
        $front_image = $settings['front_image'] ?? '';
        $front_title = $settings['front_title'] ?? 'Amazing Feature';
        $front_desc = $settings['front_description'] ?? '';
        
        $back_icon_type = $settings['back_icon_type'] ?? 'none';
        $back_icon = $settings['back_icon'] ?? 'fa fa-check-circle';
        $back_image = $settings['back_image'] ?? '';
        $back_title = $settings['back_title'] ?? 'Discover More';
        $back_desc = $settings['back_description'] ?? '';
        $show_button = ($settings['show_button'] ?? 'yes') !== 'no';
        $button_text = $settings['back_button_text'] ?? 'Learn More';
        $button_link = $settings['back_button_link'] ?? '#';
        
        $flip_effect = $settings['flip_effect'] ?? 'flip-horizontal';
        $flip_duration = $settings['flip_duration'] ?? 0.6;
        $box_height = $settings['box_height'] ?? 300;
        
        $front_bg_type = $settings['front_bg_type'] ?? 'color';
        $front_bg_color = $settings['front_bg_color'] ?? '#92003b';
        $front_bg_gradient = $settings['front_bg_gradient'] ?? '';
        $front_bg_image = $settings['front_bg_image'] ?? '';
        $front_text_color = $settings['front_text_color'] ?? '#ffffff';
        $front_icon_color = $settings['front_icon_color'] ?? '#ffffff';
        $front_icon_size = $settings['front_icon_size'] ?? 60;
        $front_title_size = $settings['front_title_size'] ?? 24;
        
        $back_bg_type = $settings['back_bg_type'] ?? 'color';
        $back_bg_color = $settings['back_bg_color'] ?? '#333333';
        $back_bg_gradient = $settings['back_bg_gradient'] ?? '';
        $back_bg_image = $settings['back_bg_image'] ?? '';
        $back_text_color = $settings['back_text_color'] ?? '#ffffff';
        $back_button_bg = $settings['back_button_bg'] ?? '#ffffff';
        $back_button_color = $settings['back_button_color'] ?? '#333333';
        
        $border_radius = $settings['border_radius'] ?? 8;
        $padding = $settings['padding'] ?? ['top' => 30, 'right' => 30, 'bottom' => 30, 'left' => 30];
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        
        // Generate front background
        $front_bg = '';
        if ($front_bg_type === 'gradient') {
            $front_bg = 'background: ' . $front_bg_gradient . ';';
        } elseif ($front_bg_type === 'image' && $front_bg_image) {
            $front_bg = 'background: url(' . esc_url($front_bg_image) . ') center/cover;';
        } else {
            $front_bg = 'background: ' . $front_bg_color . ';';
        }
        
        // Generate back background
        $back_bg = '';
        if ($back_bg_type === 'gradient') {
            $back_bg = 'background: ' . $back_bg_gradient . ';';
        } elseif ($back_bg_type === 'image' && $back_bg_image) {
            $back_bg = 'background: url(' . esc_url($back_bg_image) . ') center/cover;';
        } else {
            $back_bg = 'background: ' . $back_bg_color . ';';
        }
        
        $container_style = 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; perspective: 1000px; height: ' . $box_height . 'px;';
        $inner_style = 'position: relative; width: 100%; height: 100%; transition: transform ' . $flip_duration . 's; transform-style: preserve-3d;';
        $padding_style = 'padding: ' . $padding['top'] . 'px ' . $padding['right'] . 'px ' . $padding['bottom'] . 'px ' . $padding['left'] . 'px;';
        
        ?>
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-flip-box" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" data-effect="<?php echo esc_attr($flip_effect); ?>" style="<?php echo esc_attr($container_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <div class="flip-box-inner" style="<?php echo $inner_style; ?>">
                
                <!-- Front -->
                <div class="flip-box-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; <?php echo $front_bg; ?> color: <?php echo esc_attr($front_text_color); ?>; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: <?php echo esc_attr($border_radius); ?>px; <?php echo $padding_style; ?>">
                    
                    <?php if ($front_icon_type === 'icon' && $front_icon): ?>
                        <i class="<?php echo esc_attr($front_icon); ?>" style="font-size: <?php echo esc_attr($front_icon_size); ?>px; color: <?php echo esc_attr($front_icon_color); ?>; margin-bottom: 20px;"></i>
                    <?php elseif ($front_icon_type === 'image' && $front_image): ?>
                        <img src="<?php echo esc_url($front_image); ?>" alt="" style="width: <?php echo esc_attr($front_icon_size); ?>px; height: <?php echo esc_attr($front_icon_size); ?>px; margin-bottom: 20px; border-radius: 50%;">
                    <?php endif; ?>
                    
                    <h3 style="margin: 0 0 10px 0; font-size: <?php echo esc_attr($front_title_size); ?>px; font-weight: 600;"><?php echo esc_html($front_title); ?></h3>
                    
                    <?php if ($front_desc): ?>
                        <p style="margin: 0; font-size: 14px; opacity: 0.9;"><?php echo esc_html($front_desc); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Back -->
                <div class="flip-box-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; <?php echo $back_bg; ?> color: <?php echo esc_attr($back_text_color); ?>; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: <?php echo esc_attr($border_radius); ?>px; <?php echo $padding_style; ?> transform: rotateY(180deg);">
                    
                    <?php if ($back_icon_type === 'icon' && $back_icon): ?>
                        <i class="<?php echo esc_attr($back_icon); ?>" style="font-size: 40px; margin-bottom: 15px;"></i>
                    <?php elseif ($back_icon_type === 'image' && $back_image): ?>
                        <img src="<?php echo esc_url($back_image); ?>" alt="" style="width: 60px; height: 60px; margin-bottom: 15px; border-radius: 50%;">
                    <?php endif; ?>
                    
                    <h3 style="margin: 0 0 15px 0; font-size: 22px; font-weight: 600;"><?php echo esc_html($back_title); ?></h3>
                    
                    <?php if ($back_desc): ?>
                        <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; text-align: center;"><?php echo esc_html($back_desc); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($show_button && $button_text): ?>
                        <a href="<?php echo esc_url($button_link); ?>" style="background: <?php echo esc_attr($back_button_bg); ?>; color: <?php echo esc_attr($back_button_color); ?>; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: 600; transition: all 0.3s;"><?php echo esc_html($button_text); ?></a>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#<?php echo esc_js($id); ?>').on('mouseenter', function() {
                const effect = $(this).data('effect');
                const $inner = $(this).find('.flip-box-inner');
                
                switch(effect) {
                    case 'flip-horizontal':
                        $inner.css('transform', 'rotateY(180deg)');
                        break;
                    case 'flip-vertical':
                        $inner.css('transform', 'rotateX(180deg)');
                        break;
                    case 'zoom-in':
                        $inner.find('.flip-box-front').css({'opacity': '0', 'transform': 'scale(1.2)'});
                        $inner.find('.flip-box-back').css({'opacity': '1', 'transform': 'scale(1) rotateY(0deg)'});
                        break;
                    case 'zoom-out':
                        $inner.find('.flip-box-front').css({'opacity': '0', 'transform': 'scale(0.8)'});
                        $inner.find('.flip-box-back').css({'opacity': '1', 'transform': 'scale(1) rotateY(0deg)'});
                        break;
                    case 'fade':
                        $inner.find('.flip-box-front').css('opacity', '0');
                        $inner.find('.flip-box-back').css({'opacity': '1', 'transform': 'rotateY(0deg)'});
                        break;
                }
            }).on('mouseleave', function() {
                const $inner = $(this).find('.flip-box-inner');
                $inner.css('transform', '');
                $inner.find('.flip-box-front').css({'opacity': '1', 'transform': ''});
                $inner.find('.flip-box-back').css({'opacity': '0', 'transform': 'rotateY(180deg)'});
            });
        });
        </script>
        <?php
    }
}
