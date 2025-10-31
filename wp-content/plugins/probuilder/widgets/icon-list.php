<?php
/**
 * Icon List Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Icon_List extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'icon-list';
        $this->title = __('Icon List', 'probuilder');
        $this->icon = 'fa fa-list';
        $this->category = 'content';
        $this->keywords = ['icon', 'list', 'features', 'check'];
    }
    
    protected function register_controls() {
        // LIST ITEMS
        $this->start_controls_section('section_list', [
            'label' => __('List Items', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('items', [
            'label' => __('List Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'text' => __('Professional Design', 'probuilder'),
                    'icon' => 'fa fa-check-circle',
                    'link' => '',
                ],
                [
                    'text' => __('Fast Performance', 'probuilder'),
                    'icon' => 'fa fa-check-circle',
                    'link' => '',
                ],
                [
                    'text' => __('Responsive Layout', 'probuilder'),
                    'icon' => 'fa fa-check-circle',
                    'link' => '',
                ],
                [
                    'text' => __('SEO Optimized', 'probuilder'),
                    'icon' => 'fa fa-check-circle',
                    'link' => '',
                ],
            ],
            'fields' => [
                [
                    'name' => 'text',
                    'label' => __('Text', 'probuilder'),
                    'type' => 'text',
                    'default' => __('List Item', 'probuilder'),
                ],
                [
                    'name' => 'icon',
                    'label' => __('Icon', 'probuilder'),
                    'type' => 'text',
                    'default' => 'fa fa-check-circle',
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
            'default' => 'vertical',
            'options' => [
                'vertical' => __('Vertical', 'probuilder'),
                'horizontal' => __('Horizontal', 'probuilder'),
                'grid' => __('Grid', 'probuilder'),
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '2',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);
        
        $this->add_control('item_spacing', [
            'label' => __('Item Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 15,
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
        
        $this->add_control('icon_size', [
            'label' => __('Icon Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 50,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('icon_spacing', [
            'label' => __('Icon Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 12,
            'range' => [
                'px' => [
                    'min' => 5,
                    'max' => 30,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('icon_style', [
            'label' => __('Icon Style', 'probuilder'),
            'type' => 'select',
            'default' => 'default',
            'options' => [
                'default' => __('Default', 'probuilder'),
                'framed' => __('Framed', 'probuilder'),
                'filled' => __('Filled Circle', 'probuilder'),
            ],
        ]);
        
        $this->add_control('icon_bg_color', [
            'label' => __('Icon Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f0f0f0',
        ]);
        
        $this->end_controls_section();
        
        // TEXT STYLE
        $this->start_controls_section('section_text_style', [
            'label' => __('Text Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('text_size', [
            'label' => __('Text Size (px)', 'probuilder'),
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
        
        $this->add_control('text_weight', [
            'label' => __('Text Weight', 'probuilder'),
            'type' => 'select',
            'default' => '400',
            'options' => [
                '300' => __('Light', 'probuilder'),
                '400' => __('Normal', 'probuilder'),
                '500' => __('Medium', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
            ],
        ]);
        
        $this->add_control('hover_color', [
            'label' => __('Hover Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('divider', [
            'label' => __('Show Divider', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('divider_style', [
            'label' => __('Divider Style', 'probuilder'),
            'type' => 'select',
            'default' => 'solid',
            'options' => [
                'solid' => __('Solid', 'probuilder'),
                'dashed' => __('Dashed', 'probuilder'),
                'dotted' => __('Dotted', 'probuilder'),
            ],
        ]);
        
        $this->add_control('divider_color', [
            'label' => __('Divider Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e5e5e5',
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
        
        $settings = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $id = 'icon-list-' . uniqid();
        
        $items = $settings['items'] ?? [];
        $layout = $settings['layout'] ?? 'vertical';
        $columns = $settings['columns'] ?? '2';
        $item_spacing = $settings['item_spacing'] ?? 15;
        
        $icon_color = $settings['icon_color'] ?? '#92003b';
        $icon_size = $settings['icon_size'] ?? 20;
        $icon_spacing = $settings['icon_spacing'] ?? 12;
        $icon_style = $settings['icon_style'] ?? 'default';
        $icon_bg = $settings['icon_bg_color'] ?? '#f0f0f0';
        
        $text_color = $settings['text_color'] ?? '#333333';
        $text_size = $settings['text_size'] ?? 16;
        $text_weight = $settings['text_weight'] ?? '400';
        $hover_color = $settings['hover_color'] ?? '#92003b';
        
        $show_divider = ($settings['divider'] ?? 'no') === 'yes';
        $divider_style = $settings['divider_style'] ?? 'solid';
        $divider_color = $settings['divider_color'] ?? '#e5e5e5';
        
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        
        if (empty($items)) {
            return;
        }
        
        // Container styles
        $container_style = 'list-style: none; margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; padding: 0; ';
        
        if ($layout === 'grid') {
            $container_style .= 'display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: ' . $item_spacing . 'px;';
        } elseif ($layout === 'horizontal') {
            $container_style .= 'display: flex; flex-wrap: wrap; gap: ' . $item_spacing . 'px ' . ($item_spacing * 2) . 'px;';
        } else {
            $container_style .= 'display: flex; flex-direction: column;';
        }
        
        // Icon wrapper styles
        $icon_wrapper_style = 'display: inline-flex; align-items: center; justify-content: center; ';
        $icon_wrapper_style .= 'color: ' . $icon_color . '; ';
        $icon_wrapper_style .= 'font-size: ' . $icon_size . 'px; ';
        $icon_wrapper_style .= 'margin-right: ' . $icon_spacing . 'px; ';
        $icon_wrapper_style .= 'flex-shrink: 0; ';
        
        if ($icon_style === 'filled' || $icon_style === 'framed') {
            $icon_wrapper_style .= 'width: ' . ($icon_size + 20) . 'px; ';
            $icon_wrapper_style .= 'height: ' . ($icon_size + 20) . 'px; ';
            $icon_wrapper_style .= 'border-radius: 50%; ';
            
            if ($icon_style === 'filled') {
                $icon_wrapper_style .= 'background: ' . $icon_bg . '; ';
            } else {
                $icon_wrapper_style .= 'border: 2px solid ' . $icon_color . '; ';
            }
        }
        
        ?>
        <ul class="<?php echo esc_attr($wrapper_classes); ?> probuilder-icon-list" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" style="<?php echo esc_attr($container_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <?php foreach ($items as $index => $item): 
                $item_style = 'display: flex; align-items: center; ';
                if ($layout === 'vertical') {
                    $item_style .= 'margin-bottom: ' . ($index < count($items) - 1 ? $item_spacing : 0) . 'px; ';
                    if ($show_divider && $index < count($items) - 1) {
                        $item_style .= 'padding-bottom: ' . $item_spacing . 'px; ';
                        $item_style .= 'border-bottom: 1px ' . $divider_style . ' ' . $divider_color . '; ';
                    }
                }
                $item_style .= 'transition: all 0.3s ease;';
                
                $text_style = 'color: ' . $text_color . '; ';
                $text_style .= 'font-size: ' . $text_size . 'px; ';
                $text_style .= 'font-weight: ' . $text_weight . '; ';
                $text_style .= 'margin: 0; line-height: 1.5; ';
                $text_style .= 'transition: color 0.3s ease;';
                
                $has_link = !empty($item['link']);
            ?>
            <li class="icon-list-item" style="<?php echo $item_style; ?>" data-hover-color="<?php echo esc_attr($hover_color); ?>">
                <?php if ($has_link): ?>
                <a href="<?php echo esc_url($item['link']); ?>" style="display: flex; align-items: center; text-decoration: none; color: inherit; flex: 1;">
                <?php endif; ?>
                
                <span class="icon-wrapper" style="<?php echo $icon_wrapper_style; ?>">
                    <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                </span>
                
                <span class="item-text" style="<?php echo $text_style; ?>">
                    <?php echo esc_html($item['text']); ?>
                </span>
                
                <?php if ($has_link): ?>
                </a>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <style>
            #<?php echo esc_attr($id); ?> .icon-list-item:hover .item-text {
                color: <?php echo esc_attr($hover_color); ?>;
            }
            
            #<?php echo esc_attr($id); ?> .icon-list-item a:hover .icon-wrapper {
                color: <?php echo esc_attr($hover_color); ?>;
                <?php if ($icon_style === 'framed'): ?>
                border-color: <?php echo esc_attr($hover_color); ?>;
                <?php endif; ?>
            }
        </style>
        <?php
    }
}
