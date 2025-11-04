<?php
/**
 * Star Rating Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Star_Rating extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'star-rating';
        $this->title = __('Star Rating', 'probuilder');
        $this->icon = 'fa fa-star';
        $this->category = 'content';
        $this->keywords = ['star', 'rating', 'review', 'testimonial'];
    }
    
    protected function register_controls() {
        // RATING
        $this->start_controls_section('section_rating', [
            'label' => __('Rating', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('rating', [
            'label' => __('Rating', 'probuilder'),
            'type' => 'slider',
            'default' => 5,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1
                ]
            ],
        ]);
        
        $this->add_control('max_stars', [
            'label' => __('Maximum Stars', 'probuilder'),
            'type' => 'select',
            'default' => '5',
            'options' => [
                '5' => '5 Stars',
                '10' => '10 Stars',
            ],
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Excellent Service!', 'probuilder'),
        ]);
        
        $this->add_control('show_number', [
            'label' => __('Show Number', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('number_position', [
            'label' => __('Number Position', 'probuilder'),
            'type' => 'select',
            'default' => 'after',
            'options' => [
                'before' => __('Before', 'probuilder'),
                'after' => __('After', 'probuilder'),
                'bottom' => __('Bottom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('show_text', [
            'label' => __('Show Text Label', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('rating_text', [
            'label' => __('Rating Text', 'probuilder'),
            'type' => 'text',
            'default' => __('out of 5', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // STAR STYLE
        $this->start_controls_section('section_star_style', [
            'label' => __('Star Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('star_style', [
            'label' => __('Star Style', 'probuilder'),
            'type' => 'select',
            'default' => 'solid',
            'options' => [
                'solid' => __('Solid', 'probuilder'),
                'regular' => __('Regular (Outline)', 'probuilder'),
            ],
        ]);
        
        $this->add_control('star_size', [
            'label' => __('Star Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 24,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 60,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('star_spacing', [
            'label' => __('Star Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 3,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('filled_color', [
            'label' => __('Filled Star Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffa500',
        ]);
        
        $this->add_control('empty_color', [
            'label' => __('Empty Star Color', 'probuilder'),
            'type' => 'color',
            'default' => '#d4d4d4',
        ]);
        
        $this->end_controls_section();
        
        // TEXT STYLE
        $this->start_controls_section('section_text_style', [
            'label' => __('Text Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('title_size', [
            'label' => __('Title Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 18,
            'range' => [
                'px' => [
                    'min' => 12,
                    'max' => 36,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('number_color', [
            'label' => __('Number Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('number_size', [
            'label' => __('Number Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 14,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 24,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Label Color', 'probuilder'),
            'type' => 'color',
            'default' => '#999999',
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('rating_layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'vertical',
            'options' => [
                'vertical' => __('Vertical', 'probuilder'),
                'horizontal' => __('Horizontal', 'probuilder'),
            ],
        ]);
        
        $this->add_control('item_spacing', [
            'label' => __('Item Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 10,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 1
                ]
            ]
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
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $settings = $this->get_settings();
        $id = 'star-rating-' . uniqid();
        
        $rating = floatval($settings['rating'] ?? 5);
        $max_stars = intval($settings['max_stars'] ?? '5');
        $show_title = ($settings['show_title'] ?? 'yes') !== 'no';
        $title = $settings['title'] ?? 'Excellent Service!';
        $show_number = ($settings['show_number'] ?? 'yes') !== 'no';
        $number_position = $settings['number_position'] ?? 'after';
        $show_text = ($settings['show_text'] ?? 'no') === 'yes';
        $rating_text = $settings['rating_text'] ?? 'out of 5';
        
        $star_style = $settings['star_style'] ?? 'solid';
        $star_size = $settings['star_size'] ?? 24;
        $star_spacing = $settings['star_spacing'] ?? 3;
        $filled_color = $settings['filled_color'] ?? '#ffa500';
        $empty_color = $settings['empty_color'] ?? '#d4d4d4';
        
        $title_color = $settings['title_color'] ?? '#333333';
        $title_size = $settings['title_size'] ?? 18;
        $number_color = $settings['number_color'] ?? '#666666';
        $number_size = $settings['number_size'] ?? 14;
        $text_color = $settings['text_color'] ?? '#999999';
        $align = $settings['align'] ?? 'left';
        
        $layout = $settings['rating_layout'] ?? 'vertical';
        $item_spacing = $settings['item_spacing'] ?? 10;
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        
        // Container styles
        $container_style = 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; ';
        
        if ($layout === 'horizontal') {
            $container_style .= 'display: flex; align-items: center; gap: ' . $item_spacing . 'px; ';
            if ($align === 'center') {
                $container_style .= 'justify-content: center; ';
            } elseif ($align === 'right') {
                $container_style .= 'justify-content: flex-end; ';
            }
        } else {
            $container_style .= 'text-align: ' . $align . '; ';
        }
        
        // Stars wrapper style
        $stars_wrapper_style = 'display: inline-flex; align-items: center; gap: ' . $star_spacing . 'px; ';
        if ($number_position !== 'bottom') {
            $stars_wrapper_style .= 'margin-right: ' . ($number_position === 'after' ? $item_spacing : 0) . 'px; ';
            $stars_wrapper_style .= 'margin-left: ' . ($number_position === 'before' ? $item_spacing : 0) . 'px; ';
        }
        
        $star_icon_solid = 'fa-star';
        $star_icon_regular = 'fa-star';
        $star_icon_half = 'fa-star-half-stroke';
        
        ?>
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-star-rating" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" style="<?php echo esc_attr($container_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <?php if ($show_title && $title): ?>
            <div class="rating-title" style="font-size: <?php echo esc_attr($title_size); ?>px; font-weight: 600; color: <?php echo esc_attr($title_color); ?>; margin-bottom: <?php echo $layout === 'vertical' ? $item_spacing : 0; ?>px;">
                <?php echo esc_html($title); ?>
            </div>
            <?php endif; ?>
            
            <div class="rating-content" style="display: inline-flex; align-items: center; <?php echo $number_position === 'bottom' ? 'flex-direction: column; gap: ' . $item_spacing . 'px;' : ''; ?>">
                
                <?php if ($show_number && $number_position === 'before'): ?>
                <div class="rating-number" style="font-size: <?php echo esc_attr($number_size); ?>px; color: <?php echo esc_attr($number_color); ?>; font-weight: 600; margin-right: <?php echo $item_spacing; ?>px;">
                    <?php echo number_format($rating, 1); ?><?php if ($show_text): ?> <span style="color: <?php echo esc_attr($text_color); ?>; font-weight: 400;"><?php echo esc_html($rating_text); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="stars-wrapper" style="<?php echo $stars_wrapper_style; ?>">
                    <?php 
                    for ($i = 1; $i <= $max_stars; $i++) {
                        $star_class = 'fa ';
                        $star_color = $empty_color;
                        
                        if ($i <= floor($rating)) {
                            // Full star
                            $star_class .= $star_icon_solid;
                            $star_color = $filled_color;
                        } elseif ($i - 0.5 <= $rating) {
                            // Half star
                            $star_class .= $star_icon_half;
                            $star_color = $filled_color;
                        } else {
                            // Empty star
                            $star_class .= ($star_style === 'regular' ? 'fa-regular ' : '') . $star_icon_regular;
                            $star_color = $empty_color;
                        }
                        
                        echo '<i class="' . esc_attr($star_class) . '" style="font-size: ' . esc_attr($star_size) . 'px; color: ' . esc_attr($star_color) . ';"></i>';
                    }
                    ?>
                </div>
                
                <?php if ($show_number && $number_position === 'after'): ?>
                <div class="rating-number" style="font-size: <?php echo esc_attr($number_size); ?>px; color: <?php echo esc_attr($number_color); ?>; font-weight: 600; margin-left: <?php echo $item_spacing; ?>px;">
                    <?php echo number_format($rating, 1); ?><?php if ($show_text): ?> <span style="color: <?php echo esc_attr($text_color); ?>; font-weight: 400;"><?php echo esc_html($rating_text); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($show_number && $number_position === 'bottom'): ?>
                <div class="rating-number" style="font-size: <?php echo esc_attr($number_size); ?>px; color: <?php echo esc_attr($number_color); ?>; font-weight: 600;">
                    <?php echo number_format($rating, 1); ?> / <?php echo $max_stars; ?><?php if ($show_text): ?> <span style="color: <?php echo esc_attr($text_color); ?>; font-weight: 400;"><?php echo esc_html($rating_text); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
