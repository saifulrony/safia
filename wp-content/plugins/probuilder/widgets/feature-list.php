<?php
/**
 * Feature List Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Feature_List extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'feature-list';
        $this->title = __('Feature List', 'probuilder');
        $this->icon = 'fa fa-list-check';
        $this->category = 'content';
        $this->keywords = ['feature', 'list', 'check', 'benefits'];
    }
    
    protected function register_controls() {
        // FEATURES
        $this->start_controls_section('section_features', [
            'label' => __('Features', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('items', [
            'label' => __('Feature Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'title' => __('24/7 Customer Support', 'probuilder'),
                    'description' => __('Get help whenever you need it', 'probuilder'),
                    'icon' => 'fa fa-headset',
                ],
                [
                    'title' => __('Free Updates Forever', 'probuilder'),
                    'description' => __('Always get the latest features', 'probuilder'),
                    'icon' => 'fa fa-rocket',
                ],
                [
                    'title' => __('Money Back Guarantee', 'probuilder'),
                    'description' => __('30-day refund policy', 'probuilder'),
                    'icon' => 'fa fa-shield-halved',
                ],
            ],
            'fields' => [
                [
                    'name' => 'icon',
                    'label' => __('Icon', 'probuilder'),
                    'type' => 'text',
                    'default' => 'fa fa-check-circle',
                ],
                [
                    'name' => 'title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Feature Title', 'probuilder'),
                ],
                [
                    'name' => 'description',
                    'label' => __('Description', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Feature description', 'probuilder'),
                ],
                [
                    'name' => 'link',
                    'label' => __('Link', 'probuilder'),
                    'type' => 'text',
                    'default' => '',
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        // LAYOUT
        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'grid',
            'options' => [
                'list' => __('List', 'probuilder'),
                'grid' => __('Grid', 'probuilder'),
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '3',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);
        
        $this->add_control('icon_position', [
            'label' => __('Icon Position', 'probuilder'),
            'type' => 'select',
            'default' => 'top',
            'options' => [
                'top' => __('Top', 'probuilder'),
                'left' => __('Left', 'probuilder'),
            ],
        ]);
        
        $this->add_control('item_spacing', [
            'label' => __('Item Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 5
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        // ICON STYLE
        $this->start_controls_section('section_icon_style', [
            'label' => __('Icon Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('icon_bg_color', [
            'label' => __('Icon Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f8f9fa',
        ]);
        
        $this->add_control('icon_size', [
            'label' => __('Icon Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 40,
            'range' => [
                'px' => [
                    'min' => 20,
                    'max' => 80,
                    'step' => 5
                ]
            ]
        ]);
        
        $this->add_control('icon_style', [
            'label' => __('Icon Style', 'probuilder'),
            'type' => 'select',
            'default' => 'filled',
            'options' => [
                'default' => __('Default', 'probuilder'),
                'filled' => __('Filled Circle', 'probuilder'),
                'outlined' => __('Outlined Circle', 'probuilder'),
                'square' => __('Square', 'probuilder'),
            ],
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
                    'min' => 14,
                    'max' => 32,
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
            'label' => __('Description Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 14,
            'range' => [
                'px' => [
                    'min' => 12,
                    'max' => 20,
                    'step' => 1
                ]
            ]
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
        
        $this->end_controls_section();
        
        // CARD STYLE
        $this->start_controls_section('section_card_style', [
            'label' => __('Card Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('show_card', [
            'label' => __('Show Card Background', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('card_bg_color', [
            'label' => __('Card Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('card_border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('card_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('hover_effect', [
            'label' => __('Hover Effect', 'probuilder'),
            'type' => 'select',
            'default' => 'lift',
            'options' => [
                'none' => __('None', 'probuilder'),
                'lift' => __('Lift', 'probuilder'),
                'grow' => __('Grow', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('padding', [
            'label' => __('Card Padding', 'probuilder'),
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
                // Render custom CSS if any
        $this->render_custom_css();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        $settings = $this->get_settings();
        $id = 'feature-list-' . uniqid();
        
        $items = $settings['items'] ?? [];
        $layout = $settings['layout'] ?? 'grid';
        $columns = $settings['columns'] ?? '3';
        $icon_position = $settings['icon_position'] ?? 'top';
        $item_spacing = $settings['item_spacing'] ?? 20;
        
        $icon_color = $settings['icon_color'] ?? '#92003b';
        $icon_bg = $settings['icon_bg_color'] ?? '#f8f9fa';
        $icon_size = $settings['icon_size'] ?? 40;
        $icon_style = $settings['icon_style'] ?? 'filled';
        
        $title_color = $settings['title_color'] ?? '#333333';
        $title_size = $settings['title_size'] ?? 18;
        $desc_color = $settings['description_color'] ?? '#666666';
        $desc_size = $settings['description_size'] ?? 14;
        $text_align = $settings['text_align'] ?? 'left';
        
        $show_card = ($settings['show_card'] ?? 'yes') !== 'no';
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        $card_radius = $settings['card_border_radius'] ?? 8;
        $card_shadow = ($settings['card_shadow'] ?? 'yes') !== 'no';
        $hover_effect = $settings['hover_effect'] ?? 'lift';
        
        $padding = $settings['padding'] ?? ['top' => 25, 'right' => 25, 'bottom' => 25, 'left' => 25];
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        
        if (empty($items)) {
            return;
        }
        
        // Container styles
        $container_style = 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; ';
        
        if ($layout === 'grid') {
            $container_style .= 'display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: ' . $item_spacing . 'px;';
        } else {
            $container_style .= 'display: flex; flex-direction: column; gap: ' . $item_spacing . 'px;';
        }
        
        // Icon wrapper styles
        $icon_wrapper_style = 'display: flex; align-items: center; justify-content: center; ';
        $icon_wrapper_style .= 'color: ' . $icon_color . '; ';
        $icon_wrapper_style .= 'font-size: ' . $icon_size . 'px; ';
        $icon_wrapper_style .= 'flex-shrink: 0; ';
        
        if ($icon_style !== 'default') {
            $icon_wrapper_style .= 'width: ' . ($icon_size + 30) . 'px; ';
            $icon_wrapper_style .= 'height: ' . ($icon_size + 30) . 'px; ';
            
            if ($icon_style === 'filled' || $icon_style === 'outlined') {
                $icon_wrapper_style .= 'border-radius: 50%; ';
            } else {
                $icon_wrapper_style .= 'border-radius: 8px; ';
            }
            
            if ($icon_style === 'filled' || $icon_style === 'square') {
                $icon_wrapper_style .= 'background: ' . $icon_bg . '; ';
            } else {
                $icon_wrapper_style .= 'border: 2px solid ' . $icon_color . '; ';
            }
        }
        
        if ($icon_position === 'top') {
            $icon_wrapper_style .= 'margin-bottom: 15px; ';
        } else {
            $icon_wrapper_style .= 'margin-right: 15px; ';
        }
        
        ?>
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-feature-list" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" style="<?php echo esc_attr($container_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <?php foreach ($items as $item): 
                $item_style = 'transition: all 0.3s ease; ';
                
                if ($show_card) {
                    $item_style .= 'background: ' . $card_bg . '; ';
                    $item_style .= 'border-radius: ' . $card_radius . 'px; ';
                    $item_style .= 'padding: ' . $padding['top'] . 'px ' . $padding['right'] . 'px ' . $padding['bottom'] . 'px ' . $padding['left'] . 'px; ';
                    if ($card_shadow) {
                        $item_style .= 'box-shadow: 0 2px 10px rgba(0,0,0,0.08); ';
                    }
                }
                
                if ($icon_position === 'top') {
                    $item_style .= 'display: flex; flex-direction: column; ';
                    if ($text_align === 'center') {
                        $item_style .= 'align-items: center; ';
                    }
                } else {
                    $item_style .= 'display: flex; align-items: flex-start; ';
                }
                
                $has_link = !empty($item['link']);
            ?>
            <div class="feature-item" style="<?php echo $item_style; ?>" data-hover="<?php echo esc_attr($hover_effect); ?>">
                <?php if ($has_link): ?>
                <a href="<?php echo esc_url($item['link']); ?>" style="text-decoration: none; color: inherit; display: flex; <?php echo $icon_position === 'top' ? 'flex-direction: column; align-items: ' . ($text_align === 'center' ? 'center' : 'flex-start') . ';' : 'align-items: flex-start;'; ?>">
                <?php endif; ?>
                
                <div class="icon-wrapper" style="<?php echo $icon_wrapper_style; ?>">
                    <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                </div>
                
                <div class="feature-content" style="text-align: <?php echo esc_attr($text_align); ?>;">
                    <h4 style="margin: 0 0 8px 0; font-size: <?php echo esc_attr($title_size); ?>px; color: <?php echo esc_attr($title_color); ?>; font-weight: 600;">
                        <?php echo esc_html($item['title']); ?>
                    </h4>
                    
                    <?php if (!empty($item['description'])): ?>
                    <p style="margin: 0; font-size: <?php echo esc_attr($desc_size); ?>px; color: <?php echo esc_attr($desc_color); ?>; line-height: 1.6;">
                        <?php echo esc_html($item['description']); ?>
                    </p>
                    <?php endif; ?>
                </div>
                
                <?php if ($has_link): ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <style>
            #<?php echo esc_attr($id); ?> .feature-item[data-hover="lift"]:hover {
                transform: translateY(-5px);
                <?php if ($card_shadow): ?>
                box-shadow: 0 8px 20px rgba(0,0,0,0.12);
                <?php endif; ?>
            }
            
            #<?php echo esc_attr($id); ?> .feature-item[data-hover="grow"]:hover {
                transform: scale(1.05);
            }
        </style>
        <?php
    }
}
