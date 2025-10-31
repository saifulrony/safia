<?php
/**
 * Image Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Image_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'image-box';
        $this->title = __('Image Box', 'probuilder');
        $this->icon = 'fa fa-image-portrait';
        $this->category = 'content';
        $this->keywords = ['image', 'box', 'card', 'photo'];
    }
    
    protected function register_controls() {
        // IMAGE
        $this->start_controls_section('section_image', [
            'label' => __('Image', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('image_url', [
            'label' => __('Image URL', 'probuilder'),
            'type' => 'text',
            'default' => 'https://via.placeholder.com/600x400/92003b/ffffff?text=Image+Box',
        ]);
        
        $this->add_control('image_position', [
            'label' => __('Image Position', 'probuilder'),
            'type' => 'select',
            'default' => 'top',
            'options' => [
                'top' => __('Top', 'probuilder'),
                'left' => __('Left', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('image_size', [
            'label' => __('Image Size', 'probuilder'),
            'type' => 'select',
            'default' => 'full',
            'options' => [
                'full' => __('Full Width', 'probuilder'),
                'custom' => __('Custom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('custom_image_width', [
            'label' => __('Custom Width (%)', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 100,
                    'step' => 5
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        // CONTENT
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Beautiful Image Box', 'probuilder'),
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Add a stunning image with text to create an engaging content block that captures attention.', 'probuilder'),
        ]);
        
        $this->add_control('show_button', [
            'label' => __('Show Button', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Learn More', 'probuilder'),
        ]);
        
        $this->add_control('button_link', [
            'label' => __('Button Link', 'probuilder'),
            'type' => 'text',
            'default' => '#',
        ]);
        
        $this->add_control('link_full_box', [
            'label' => __('Link Full Box', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Make the entire box clickable', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // STYLE
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Text Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('title_size', [
            'label' => __('Title Font Size', 'probuilder'),
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
        
        $this->add_control('description_color', [
            'label' => __('Description Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('description_size', [
            'label' => __('Description Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 16,
            'range' => [
                'px' => [
                    'min' => 12,
                    'max' => 24,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('hover_effect', [
            'label' => __('Hover Effect', 'probuilder'),
            'type' => 'select',
            'default' => 'zoom',
            'options' => [
                'none' => __('None', 'probuilder'),
                'zoom' => __('Zoom', 'probuilder'),
                'lift' => __('Lift', 'probuilder'),
                'overlay' => __('Overlay', 'probuilder'),
            ],
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
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        // BUTTON STYLE
        $this->start_controls_section('section_button_style', [
            'label' => __('Button Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('button_bg_color', [
            'label' => __('Button Background', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('button_text_color', [
            'label' => __('Button Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('button_size', [
            'label' => __('Button Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 14,
            'range' => [
                'px' => [
                    'min' => 12,
                    'max' => 24,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('padding', [
            'label' => __('Content Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 25, 'right' => 25, 'bottom' => 25, 'left' => 25]
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
        $id = 'image-box-' . uniqid();
        
        // Get settings
        $image_url = $settings['image_url'] ?? 'https://via.placeholder.com/600x400';
        $image_position = $settings['image_position'] ?? 'top';
        $image_size = $settings['image_size'] ?? 'full';
        $custom_width = $settings['custom_image_width'] ?? 100;
        
        $title = $settings['title'] ?? 'Beautiful Image Box';
        $description = $settings['description'] ?? '';
        $show_button = ($settings['show_button'] ?? 'yes') !== 'no';
        $button_text = $settings['button_text'] ?? 'Learn More';
        $button_link = $settings['button_link'] ?? '#';
        $link_full_box = ($settings['link_full_box'] ?? 'no') === 'yes';
        
        $text_align = $settings['text_align'] ?? 'left';
        $title_color = $settings['title_color'] ?? '#333333';
        $title_size = $settings['title_size'] ?? 24;
        $desc_color = $settings['description_color'] ?? '#666666';
        $desc_size = $settings['description_size'] ?? 16;
        $bg_color = $settings['bg_color'] ?? '#ffffff';
        $hover_effect = $settings['hover_effect'] ?? 'zoom';
        $border_radius = $settings['border_radius'] ?? 8;
        $box_shadow = ($settings['box_shadow'] ?? 'yes') !== 'no';
        
        $button_bg = $settings['button_bg_color'] ?? '#92003b';
        $button_color = $settings['button_text_color'] ?? '#ffffff';
        $button_size = $settings['button_size'] ?? 14;
        
        $padding = $settings['padding'] ?? ['top' => 25, 'right' => 25, 'bottom' => 25, 'left' => 25];
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        
        // Build styles
        $box_style = 'background: ' . $bg_color . '; ';
        $box_style .= 'border-radius: ' . $border_radius . 'px; ';
        $box_style .= 'overflow: hidden; ';
        $box_style .= 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; ';
        if ($box_shadow) {
            $box_style .= 'box-shadow: 0 4px 15px rgba(0,0,0,0.1); ';
        }
        $box_style .= 'transition: all 0.3s ease; ';
        
        if ($image_position === 'left' || $image_position === 'right') {
            $box_style .= 'display: flex; align-items: center; ';
            if ($image_position === 'right') {
                $box_style .= 'flex-direction: row-reverse; ';
            }
        }
        
        $content_style = 'padding: ' . $padding['top'] . 'px ' . $padding['right'] . 'px ' . $padding['bottom'] . 'px ' . $padding['left'] . 'px; ';
        $content_style .= 'text-align: ' . $text_align . '; ';
        if ($image_position === 'left' || $image_position === 'right') {
            $content_style .= 'flex: 1; ';
        }
        
        $image_style = 'width: ' . ($image_size === 'custom' ? $custom_width . '%' : '100%') . '; ';
        $image_style .= 'height: auto; display: block; transition: transform 0.3s ease;';
        
        ?>
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-image-box" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" style="<?php echo esc_attr($box_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>" data-hover="<?php echo esc_attr($hover_effect); ?>">
            <?php if ($link_full_box && $button_link): ?>
            <a href="<?php echo esc_url($button_link); ?>" style="text-decoration: none; color: inherit; display: contents;">
            <?php endif; ?>
            
            <div class="image-box-image" style="margin: 0; line-height: 0; <?php echo ($image_position === 'left' || $image_position === 'right') ? 'flex-shrink: 0;' : ''; ?>">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" style="<?php echo $image_style; ?>">
            </div>
            
            <div class="image-box-content" style="<?php echo $content_style; ?>">
                <h3 style="margin: 0 0 12px 0; font-size: <?php echo esc_attr($title_size); ?>px; color: <?php echo esc_attr($title_color); ?>; font-weight: 600;">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($description): ?>
                <p style="margin: 0 0 20px 0; font-size: <?php echo esc_attr($desc_size); ?>px; color: <?php echo esc_attr($desc_color); ?>; line-height: 1.6;">
                    <?php echo esc_html($description); ?>
                </p>
                <?php endif; ?>
                
                <?php if ($show_button && $button_text && !$link_full_box): ?>
                <a href="<?php echo esc_url($button_link); ?>" style="display: inline-block; background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_color); ?>; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: <?php echo esc_attr($button_size); ?>px; transition: all 0.3s;">
                    <?php echo esc_html($button_text); ?>
                </a>
                <?php endif; ?>
            </div>
            
            <?php if ($link_full_box): ?>
            </a>
            <?php endif; ?>
        </div>
        
        <style>
            #<?php echo esc_attr($id); ?>:hover {
                <?php if ($hover_effect === 'lift'): ?>
                transform: translateY(-10px);
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                <?php endif; ?>
            }
            
            #<?php echo esc_attr($id); ?>:hover img {
                <?php if ($hover_effect === 'zoom'): ?>
                transform: scale(1.1);
                <?php endif; ?>
            }
        </style>
        <?php
    }
}
