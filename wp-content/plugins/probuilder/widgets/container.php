<?php
/**
 * Container Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Container extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'container';
        $this->title = __('Container', 'probuilder');
        $this->icon = 'fa fa-square';
        $this->category = 'layout';
        $this->keywords = ['container', 'section', 'column', 'row'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'probuilder'),
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'boxed',
            'options' => [
                'boxed' => __('Boxed', 'probuilder'),
                'full-width' => __('Full Width', 'probuilder'),
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns (Desktop)', 'probuilder'),
            'type' => 'select',
            'default' => '1',
            'options' => [
                '1' => __('1 Column', 'probuilder'),
                '2' => __('2 Columns', 'probuilder'),
                '3' => __('3 Columns', 'probuilder'),
                '4' => __('4 Columns', 'probuilder'),
                '5' => __('5 Columns', 'probuilder'),
                '6' => __('6 Columns', 'probuilder'),
                '7' => __('7 Columns', 'probuilder'),
                '8' => __('8 Columns', 'probuilder'),
                '9' => __('9 Columns', 'probuilder'),
                '10' => __('10 Columns', 'probuilder'),
                '11' => __('11 Columns', 'probuilder'),
                '12' => __('12 Columns', 'probuilder'),
            ],
            'description' => __('Number of columns on desktop (> 1024px)', 'probuilder'),
        ]);
        
        $this->add_control('columns_tablet', [
            'label' => __('Columns (Tablet)', 'probuilder'),
            'type' => 'select',
            'default' => '2',
            'options' => [
                '1' => __('1 Column', 'probuilder'),
                '2' => __('2 Columns', 'probuilder'),
                '3' => __('3 Columns', 'probuilder'),
                '4' => __('4 Columns', 'probuilder'),
                '5' => __('5 Columns', 'probuilder'),
                '6' => __('6 Columns', 'probuilder'),
            ],
            'description' => __('Number of columns on tablet (768px - 1024px)', 'probuilder'),
        ]);
        
        $this->add_control('columns_mobile', [
            'label' => __('Columns (Mobile)', 'probuilder'),
            'type' => 'select',
            'default' => '1',
            'options' => [
                '1' => __('1 Column', 'probuilder'),
                '2' => __('2 Columns', 'probuilder'),
            ],
            'description' => __('Number of columns on mobile (< 768px)', 'probuilder'),
        ]);
        
        $this->add_control('column_gap', [
            'label' => __('Column Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->add_control('content_width', [
            'label' => __('Content Width', 'probuilder'),
            'type' => 'slider',
            'default' => 1140,
            'range' => [
                'px' => ['min' => 500, 'max' => 1920],
            ],
        ]);
        
        $this->add_control('min_height', [
            'label' => __('Min Height', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => ['min' => 0, 'max' => 1000],
            ],
        ]);
        
        $this->add_control('content_position', [
            'label' => __('Content Position', 'probuilder'),
            'type' => 'select',
            'default' => 'top',
            'options' => [
                'top' => __('Top', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'bottom' => __('Bottom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('enable_rows', [
            'label' => __('Enable Multiple Rows', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'description' => __('Allow adding multiple rows to this container', 'probuilder'),
        ]);
        
        $this->add_control('rows', [
            'label' => __('Rows', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'row_columns' => '2',
                    'row_columns_tablet' => '2',
                    'row_columns_mobile' => '1',
                    'row_gap' => 20,
                ],
            ],
            'fields' => [
                [
                    'name' => 'row_columns',
                    'label' => __('Columns (Desktop)', 'probuilder'),
                    'type' => 'select',
                    'default' => '2',
                    'options' => [
                        '1' => __('1 Column', 'probuilder'),
                        '2' => __('2 Columns', 'probuilder'),
                        '3' => __('3 Columns', 'probuilder'),
                        '4' => __('4 Columns', 'probuilder'),
                        '5' => __('5 Columns', 'probuilder'),
                        '6' => __('6 Columns', 'probuilder'),
                    ],
                ],
                [
                    'name' => 'row_columns_tablet',
                    'label' => __('Columns (Tablet)', 'probuilder'),
                    'type' => 'select',
                    'default' => '2',
                    'options' => [
                        '1' => __('1 Column', 'probuilder'),
                        '2' => __('2 Columns', 'probuilder'),
                        '3' => __('3 Columns', 'probuilder'),
                        '4' => __('4 Columns', 'probuilder'),
                    ],
                ],
                [
                    'name' => 'row_columns_mobile',
                    'label' => __('Columns (Mobile)', 'probuilder'),
                    'type' => 'select',
                    'default' => '1',
                    'options' => [
                        '1' => __('1 Column', 'probuilder'),
                        '2' => __('2 Columns', 'probuilder'),
                    ],
                ],
                [
                    'name' => 'row_gap',
                    'label' => __('Row Gap', 'probuilder'),
                    'type' => 'slider',
                    'default' => 20,
                    'range' => [
                        'px' => ['min' => 0, 'max' => 100],
                    ],
                ],
            ],
            'condition' => [
                'enable_rows' => 'yes',
            ],
            'title_field' => 'Row {{_index}}',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_background', [
            'label' => __('Background', 'probuilder'),
        ]);
        
        $this->add_control('background_type', [
            'label' => __('Background Type', 'probuilder'),
            'type' => 'select',
            'default' => 'color',
            'options' => [
                'color' => __('Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'image' => __('Image', 'probuilder'),
            ],
        ]);
        
        $this->add_control('background_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('background_gradient', [
            'label' => __('Gradient', 'probuilder'),
            'type' => 'text',
            'default' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        ]);
        
        $this->add_control('background_image', [
            'label' => __('Background Image', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => ''],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_spacing', [
            'label' => __('Spacing', 'probuilder'),
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20],
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_border', [
            'label' => __('Border', 'probuilder'),
        ]);
        
        $this->add_control('border', [
            'label' => __('Border', 'probuilder'),
            'type' => 'border',
            'default' => ['width' => 0, 'style' => 'solid', 'color' => '#000000'],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'box-shadow',
            'default' => ['x' => 0, 'y' => 0, 'blur' => 0, 'color' => 'rgba(0,0,0,0)'],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings();
        $layout = $this->get_settings('layout', 'boxed');
        $columns = $this->get_settings('columns', '1');
        $columns_tablet = $this->get_settings('columns_tablet', '2');
        $columns_mobile = $this->get_settings('columns_mobile', '1');
        $column_gap = $this->get_settings('column_gap', 20);
        $content_width = $this->get_settings('content_width', 1140);
        $min_height = $this->get_settings('min_height', 100);
        $content_position = $this->get_settings('content_position', 'top');
        $enable_rows = $this->get_settings('enable_rows', 'no');
        $rows = $this->get_settings('rows', []);
        
        // Generate unique ID for this container
        $container_id = 'probuilder-container-' . uniqid();
        
        // Background
        $bg_type = $this->get_settings('background_type', 'color');
        $bg_color = $this->get_settings('background_color', '#ffffff');
        $bg_gradient = $this->get_settings('background_gradient', '');
        $bg_image = $this->get_settings('background_image', ['url' => '']);
        
        // Spacing
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20]);
        $margin = $this->get_settings('margin', ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0]);
        
        // Border
        $border = $this->get_settings('border', ['width' => 0, 'style' => 'solid', 'color' => '#000000']);
        $border_radius = $this->get_settings('border_radius', 0);
        $box_shadow = $this->get_settings('box_shadow', ['x' => 0, 'y' => 0, 'blur' => 0, 'color' => 'rgba(0,0,0,0)']);
        
        // Build container style
        $style = '';
        
        // Background
        if ($bg_type === 'color') {
            $style .= 'background-color: ' . esc_attr($bg_color) . ';';
        } elseif ($bg_type === 'gradient' && $bg_gradient) {
            $style .= 'background: ' . esc_attr($bg_gradient) . ';';
        } elseif ($bg_type === 'image' && !empty($bg_image['url'])) {
            $style .= 'background-image: url(' . esc_url($bg_image['url']) . ');';
            $style .= 'background-size: cover;';
            $style .= 'background-position: center;';
        }
        
        // Dimensions
        $style .= 'min-height: ' . esc_attr($min_height) . 'px;';
        
        // Spacing
        $style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px;';
        $style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px;';
        
        // Border
        if ($border['width'] > 0) {
            $style .= 'border: ' . esc_attr($border['width']) . 'px ' . esc_attr($border['style']) . ' ' . esc_attr($border['color']) . ';';
        }
        $style .= 'border-radius: ' . esc_attr($border_radius) . 'px;';
        
        // Box Shadow
        if ($box_shadow['blur'] > 0) {
            $style .= 'box-shadow: ' . esc_attr($box_shadow['x']) . 'px ' . esc_attr($box_shadow['y']) . 'px ' . esc_attr($box_shadow['blur']) . 'px ' . esc_attr($box_shadow['color']) . ';';
        }
        
        // Layout
        if ($layout === 'boxed') {
            $style .= 'max-width: ' . esc_attr($content_width) . 'px; margin-left: auto; margin-right: auto;';
        }
        
        // Content Position
        if ($content_position === 'center') {
            $style .= 'display: flex; align-items: center;';
        } elseif ($content_position === 'bottom') {
            $style .= 'display: flex; align-items: flex-end;';
        }
        
        // Output responsive CSS
        ?>
        <style>
            #<?php echo $container_id; ?> .probuilder-container-row {
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }
            
            #<?php echo $container_id; ?> .probuilder-container-row:last-child {
                margin-bottom: 0;
            }
            
            #<?php echo $container_id; ?> .probuilder-container-columns {
                display: grid;
                width: 100%;
            }
            
            <?php if ($enable_rows === 'yes' && !empty($rows)): ?>
                <?php foreach ($rows as $index => $row): ?>
                    #<?php echo $container_id; ?> .probuilder-row-<?php echo $index; ?> .probuilder-container-columns {
                        grid-template-columns: repeat(<?php echo esc_attr($row['row_columns']); ?>, 1fr);
                        gap: <?php echo esc_attr($row['row_gap']); ?>px;
                    }
                    
                    /* Tablet (768px - 1024px) */
                    @media (max-width: 1024px) {
                        #<?php echo $container_id; ?> .probuilder-row-<?php echo $index; ?> .probuilder-container-columns {
                            grid-template-columns: repeat(<?php echo esc_attr($row['row_columns_tablet']); ?>, 1fr);
                        }
                    }
                    
                    /* Mobile (< 768px) */
                    @media (max-width: 767px) {
                        #<?php echo $container_id; ?> .probuilder-row-<?php echo $index; ?> .probuilder-container-columns {
                            grid-template-columns: repeat(<?php echo esc_attr($row['row_columns_mobile']); ?>, 1fr);
                        }
                    }
                <?php endforeach; ?>
            <?php else: ?>
                #<?php echo $container_id; ?> .probuilder-container-columns {
                    grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr);
                    gap: <?php echo esc_attr($column_gap); ?>px;
                }
                
                /* Tablet (768px - 1024px) */
                @media (max-width: 1024px) {
                    #<?php echo $container_id; ?> .probuilder-container-columns {
                        grid-template-columns: repeat(<?php echo esc_attr($columns_tablet); ?>, 1fr);
                    }
                }
                
                /* Mobile (< 768px) */
                @media (max-width: 767px) {
                    #<?php echo $container_id; ?> .probuilder-container-columns {
                        grid-template-columns: repeat(<?php echo esc_attr($columns_mobile); ?>, 1fr);
                    }
                }
            <?php endif; ?>
        </style>
        <?php
        
        echo '<div id="' . esc_attr($container_id) . '" class="probuilder-container probuilder-container-' . esc_attr($layout) . '" style="' . $style . '" data-columns="' . esc_attr($columns) . '" data-columns-tablet="' . esc_attr($columns_tablet) . '" data-columns-mobile="' . esc_attr($columns_mobile) . '" data-enable-rows="' . esc_attr($enable_rows) . '">';
        
        if ($enable_rows === 'yes' && !empty($rows)) {
            // Render multiple rows
            foreach ($rows as $index => $row) {
                echo '<div class="probuilder-container-row probuilder-row-' . $index . '">';
                echo '<div class="probuilder-container-columns">';
                
                // Render children for this row
                $children = $this->get_settings('_children', []);
                $row_children = isset($children[$index]) ? $children[$index] : [];
                
                if (!empty($row_children)) {
                    foreach ($row_children as $child) {
                        echo '<div class="probuilder-column">';
                        ProBuilder_Frontend::instance()->render_element($child);
                        echo '</div>';
                    }
                } else {
                    // Show empty column placeholders
                    for ($i = 0; $i < $row['row_columns']; $i++) {
                        echo '<div class="probuilder-column" style="min-height: 50px; border: 1px dashed #ddd; padding: 10px; text-align: center; color: #999;">Column ' . ($i + 1) . '</div>';
                    }
                }
                
                echo '</div>';
                echo '</div>';
            }
        } else {
            // Render single row (original behavior)
            echo '<div class="probuilder-container-row probuilder-row-0">';
            echo '<div class="probuilder-container-columns">';
            
            // Render children in columns
            $children = $this->get_settings('_children', []);
            if (!empty($children)) {
                foreach ($children as $child) {
                    echo '<div class="probuilder-column">';
                    ProBuilder_Frontend::instance()->render_element($child);
                    echo '</div>';
                }
            } else {
                // Show empty column placeholders in editor
                for ($i = 0; $i < $columns; $i++) {
                    echo '<div class="probuilder-column" style="min-height: 50px; border: 1px dashed #ddd; padding: 10px; text-align: center; color: #999;">Column ' . ($i + 1) . '</div>';
                }
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

