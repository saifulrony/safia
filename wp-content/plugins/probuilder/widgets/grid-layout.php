<?php
/**
 * Grid Layout Widget - Complex grid patterns with resizable cells
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Grid_Layout extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'grid-layout';
        $this->title = __('Grid Layout', 'probuilder');
        $this->icon = 'fa fa-th';
        $this->category = 'layout';
        $this->keywords = ['grid', 'layout', 'masonry', 'columns', 'rows'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_grid', [
            'label' => __('Grid Layout', 'probuilder'),
        ]);
        
        $this->add_control('grid_pattern', [
            'label' => __('Choose Grid Pattern', 'probuilder'),
            'type' => 'select',
            'default' => 'pattern-1',
            'options' => [
                'pattern-1' => __('Magazine Hero', 'probuilder'),
                'pattern-2' => __('Featured Post', 'probuilder'),
                'pattern-3' => __('Pinterest Masonry', 'probuilder'),
                'pattern-4' => __('Dashboard', 'probuilder'),
                'pattern-5' => __('Portfolio Showcase', 'probuilder'),
                'pattern-6' => __('Product Grid', 'probuilder'),
                'pattern-7' => __('Asymmetric Modern', 'probuilder'),
                'pattern-8' => __('Split Screen', 'probuilder'),
                'pattern-9' => __('Blog Magazine', 'probuilder'),
                'pattern-10' => __('Creative Complex', 'probuilder'),
            ],
            'description' => __('Select from 10+ professional grid layouts', 'probuilder'),
        ]);
        
        
        $this->add_control('gap', [
            'label' => __('Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->add_control('enable_resize', [
            'label' => __('Enable Resize Borders', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
            'description' => __('Allow users to resize grid cells by dragging borders', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('min_height', [
            'label' => __('Min Cell Height', 'probuilder'),
            'type' => 'slider',
            'default' => 150,
            'range' => [
                'px' => ['min' => 50, 'max' => 500],
            ],
        ]);
        
        $this->add_control('background_color', [
            'label' => __('Cell Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f8f9fa',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Cell Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ddd',
        ]);
        
        $this->add_control('border_width', [
            'label' => __('Cell Border Width', 'probuilder'),
            'type' => 'slider',
            'default' => 1,
            'range' => [
                'px' => ['min' => 0, 'max' => 10],
            ],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Cell Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->end_controls_section();
        
        // Spacing Section
        $this->start_controls_section('section_spacing', [
            'label' => __('Spacing', 'probuilder'),
            'tab' => 'style'
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
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $settings = $this->settings;
        $pattern = $this->get_settings('grid_pattern', 'pattern-1');
        $gap = $this->get_settings('gap', 20);
        $min_height = $this->get_settings('min_height', 150);
        $bg_color = $this->get_settings('background_color', '#f8f9fa');
        $border_color = $this->get_settings('border_color', '#ddd');
        $border_width = $this->get_settings('border_width', 1);
        $border_radius = $this->get_settings('border_radius', 8);
        $enable_resize = $this->get_settings('enable_resize', true);
        
        // Get spacing settings
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20]);
        $margin = $this->get_settings('margin', ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0]);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        $grid_id = 'probuilder-grid-' . uniqid();
        
        // Get grid template based on pattern
        $grid_template = $this->get_grid_template($pattern);
        
        ?>
        <style>
            #<?php echo $grid_id; ?> {
                display: grid;
                grid-template-columns: <?php echo esc_attr($grid_template['columns']); ?>;
                grid-template-rows: <?php echo esc_attr($grid_template['rows']); ?>;
                gap: <?php echo esc_attr($gap); ?>px;
                width: 100%;
                position: relative;
                padding: <?php echo esc_attr($padding['top']); ?>px <?php echo esc_attr($padding['right']); ?>px <?php echo esc_attr($padding['bottom']); ?>px <?php echo esc_attr($padding['left']); ?>px;
                margin: <?php echo esc_attr($margin['top']); ?>px <?php echo esc_attr($margin['right']); ?>px <?php echo esc_attr($margin['bottom']); ?>px <?php echo esc_attr($margin['left']); ?>px;
            }
            
            #<?php echo $grid_id; ?> .grid-cell {
                min-height: <?php echo esc_attr($min_height); ?>px;
                background: <?php echo esc_attr($bg_color); ?>;
                border: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                border-radius: <?php echo esc_attr($border_radius); ?>px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s;
                position: relative;
                overflow: hidden;
            }
            
            /* Add padding only for empty cells (editor placeholders) */
            #<?php echo $grid_id; ?> .grid-cell:not(:has(.probuilder-widget)) {
                padding: 20px;
            }
            
            #<?php echo $grid_id; ?> .grid-cell:hover {
                background: rgba(0,124,186,0.05);
                border-color: #007cba;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            
            <?php if ($enable_resize): ?>
            /* Grid cell resize handles - Show on hover only */
            #<?php echo $grid_id; ?> .grid-resize-handle {
                position: absolute;
                background: #007cba;
                opacity: 0;
                transition: all 0.2s;
                z-index: 999;
                pointer-events: auto;
            }
            
            #<?php echo $grid_id; ?> .grid-cell:hover .grid-resize-handle {
                opacity: 0.7;
            }
            
            #<?php echo $grid_id; ?> .grid-resize-handle:hover {
                opacity: 1 !important;
                background: #005a87;
                transform: scale(1.2);
            }
            
            #<?php echo $grid_id; ?> .grid-resize-handle-top {
                top: -<?php echo floor($gap / 2); ?>px;
                left: 0;
                width: 100%;
                height: 6px;
                cursor: row-resize;
            }
            
            #<?php echo $grid_id; ?> .grid-resize-handle-left {
                top: 0;
                left: -<?php echo floor($gap / 2); ?>px;
                width: 6px;
                height: 100%;
                cursor: col-resize;
            }
            
            #<?php echo $grid_id; ?> .grid-resize-handle-right {
                top: 0;
                right: -<?php echo floor($gap / 2); ?>px;
                width: 6px;
                height: 100%;
                cursor: col-resize;
            }
            
            #<?php echo $grid_id; ?> .grid-resize-handle-bottom {
                bottom: -<?php echo floor($gap / 2); ?>px;
                left: 0;
                width: 100%;
                height: 6px;
                cursor: row-resize;
            }
            
            #<?php echo $grid_id; ?> .grid-resize-handle-corner {
                bottom: -<?php echo floor($gap / 2); ?>px;
                right: -<?php echo floor($gap / 2); ?>px;
                width: 16px;
                height: 16px;
                cursor: nwse-resize;
                border-radius: 3px;
                background: #ffffff !important;
                border: 2px solid #007cba;
            }
            <?php endif; ?>
            
            <?php foreach ($grid_template['areas'] as $index => $area): ?>
            #<?php echo $grid_id; ?> .grid-cell-<?php echo $index + 1; ?> {
                grid-area: <?php echo esc_attr($area); ?>;
            }
            <?php endforeach; ?>
            
            #<?php echo $grid_id; ?> .grid-cell-content {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }
            
            #<?php echo $grid_id; ?> .grid-cell-toolbar {
                position: absolute;
                top: 8px;
                right: 8px;
                display: flex;
                gap: 4px;
                opacity: 0;
                transition: opacity 0.2s;
            }
            
            #<?php echo $grid_id; ?> .grid-cell:hover .grid-cell-toolbar {
                opacity: 1;
            }
            
            #<?php echo $grid_id; ?> .grid-cell-toolbar button {
                background: #007cba;
                color: white;
                border: none;
                border-radius: 3px;
                padding: 4px 8px;
                cursor: pointer;
                font-size: 11px;
                transition: background 0.2s;
            }
            
            #<?php echo $grid_id; ?> .grid-cell-toolbar button:hover {
                background: #005a87;
            }
        </style>
        
        <div id="<?php echo $grid_id; ?>" class="<?php echo esc_attr($wrapper_classes); ?> probuilder-grid-layout" <?php echo $wrapper_attributes; ?> style="<?php echo esc_attr($inline_styles); ?>" data-resizable="<?php echo $enable_resize ? '1' : '0'; ?>">
            <?php 
            // Always render in editor context (AJAX calls don't have $_GET)
            // We'll hide handles with CSS on frontend instead
            $is_editor = !is_admin() || (isset($_GET['probuilder']) && $_GET['probuilder'] === 'true') || (defined('DOING_AJAX') && DOING_AJAX);
            for ($i = 0; $i < count($grid_template['areas']); $i++): 
            ?>
                <div class="grid-cell grid-cell-<?php echo $i + 1; ?>" data-cell-index="<?php echo $i; ?>" data-original-area="<?php echo esc_attr($grid_template['areas'][$i]); ?>">
                    <?php if ($enable_resize): ?>
                    <!-- Resize handles - always render, hide with CSS on frontend -->
                    <div class="grid-resize-handle grid-resize-handle-top" data-cell-index="<?php echo $i; ?>" data-direction="top" data-editor-only="true"></div>
                    <div class="grid-resize-handle grid-resize-handle-left" data-cell-index="<?php echo $i; ?>" data-direction="left" data-editor-only="true"></div>
                    <div class="grid-resize-handle grid-resize-handle-right" data-cell-index="<?php echo $i; ?>" data-direction="right" data-editor-only="true"></div>
                    <div class="grid-resize-handle grid-resize-handle-bottom" data-cell-index="<?php echo $i; ?>" data-direction="bottom" data-editor-only="true"></div>
                    <div class="grid-resize-handle grid-resize-handle-corner" data-cell-index="<?php echo $i; ?>" data-direction="both" data-editor-only="true"></div>
                    <?php endif; ?>
                    
                    <?php if ($is_editor): ?>
                    <div class="grid-cell-toolbar">
                        <button class="add-content-btn" title="Add Content">
                            <i class="dashicons dashicons-plus" style="font-size: 12px; width: 12px; height: 12px;"></i>
                        </button>
                        <button class="settings-btn" title="Cell Settings">
                            <i class="dashicons dashicons-admin-generic" style="font-size: 12px; width: 12px; height: 12px;"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="grid-cell-content">
                        <?php 
                        // Render child widget if exists
                        $children = $this->get_settings('_children', []);
                        if (!empty($children) && isset($children[$i])) {
                            $child = $children[$i];
                            // Render child widget
                            $child_widget = ProBuilder_Widgets_Manager::instance()->get_widget($child['widgetType']);
                            if ($child_widget) {
                                // Pass nested children too
                                $child_settings = $child['settings'] ?? [];
                                $child_settings['_children'] = $child['children'] ?? [];
                                $child_widget->render_widget($child_settings);
                            }
                        } else {
                            // Show placeholder ONLY in ProBuilder editor, not on frontend
                            if (isset($_GET['probuilder']) && $_GET['probuilder'] === 'true') {
                                ?>
                                <i class="dashicons dashicons-welcome-add-page" style="font-size: 32px; opacity: 0.3; color: #999;"></i>
                                <div style="font-size: 12px; margin-top: 8px; color: #999;">Cell <?php echo $i + 1; ?></div>
                                <div style="font-size: 11px; margin-top: 4px; color: #bbb;">Drop widgets here</div>
                                <?php
                            }
                            // On frontend: show nothing (empty cell)
                        }
                        ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        
        <?php if (!$is_editor): ?>
        <style>
            /* Hide editor-only elements on frontend */
            #<?php echo $grid_id; ?> .grid-cell-toolbar,
            #<?php echo $grid_id; ?> .resize-handle,
            #<?php echo $grid_id; ?> [data-editor-only="true"] {
                display: none !important;
                opacity: 0 !important;
                pointer-events: none !important;
            }
        </style>
        <?php endif; ?>
        
        <?php 
        // Resize functionality is handled by editor.js - no inline JS needed
        // The handles are already rendered and will be automatically initialized
        ?>
        <?php
    }
    
    /**
     * Get grid template based on pattern
     */
    private function get_grid_template($pattern) {
        $templates = [
            // Magazine Style Layouts
            'pattern-1' => [
                'name' => 'Magazine Hero',
                'columns' => 'repeat(4, 1fr)',
                'rows' => 'repeat(4, 150px)',
                'areas' => [
                    '1 / 1 / 3 / 3',  // Large left
                    '1 / 3 / 2 / 5',  // Top right
                    '2 / 3 / 3 / 4',  // Mid right 1
                    '2 / 4 / 3 / 5',  // Mid right 2
                    '3 / 1 / 5 / 2',  // Bottom left 1
                    '3 / 2 / 5 / 3',  // Bottom left 2
                    '3 / 3 / 5 / 5',  // Bottom right
                ],
            ],
            
            'pattern-2' => [
                'name' => 'Featured Post',
                'columns' => 'repeat(6, 1fr)',
                'rows' => 'repeat(3, 200px)',
                'areas' => [
                    '1 / 1 / 3 / 4',  // Large featured
                    '1 / 4 / 2 / 7',  // Top right
                    '2 / 4 / 3 / 5',  // Bottom right 1
                    '2 / 5 / 3 / 6',  // Bottom right 2
                    '2 / 6 / 3 / 7',  // Bottom right 3
                    '3 / 1 / 4 / 3',  // Bottom left
                    '3 / 3 / 4 / 5',  // Bottom center
                    '3 / 5 / 4 / 7',  // Bottom right
                ],
            ],
            
            // Masonry Layouts
            'pattern-3' => [
                'name' => 'Pinterest Masonry',
                'columns' => 'repeat(4, 1fr)',
                'rows' => 'repeat(5, 120px)',
                'areas' => [
                    '1 / 1 / 3 / 2',  // Tall 1
                    '1 / 2 / 2 / 3',  // Short 1
                    '1 / 3 / 3 / 4',  // Tall 2
                    '1 / 4 / 2 / 5',  // Short 2
                    '2 / 2 / 4 / 3',  // Tall 3
                    '2 / 4 / 3 / 5',  // Short 3
                    '3 / 1 / 4 / 2',  // Short 4
                    '3 / 3 / 5 / 4',  // Tall 4
                    '3 / 4 / 5 / 5',  // Tall 5
                    '4 / 1 / 6 / 2',  // Tall 6
                    '4 / 2 / 5 / 3',  // Short 5
                    '5 / 3 / 6 / 5',  // Wide bottom
                ],
            ],
            
            // Dashboard Layouts
            'pattern-4' => [
                'name' => 'Dashboard',
                'columns' => 'repeat(12, 1fr)',
                'rows' => 'repeat(4, 150px)',
                'areas' => [
                    '1 / 1 / 2 / 4',  // Card 1
                    '1 / 4 / 2 / 7',  // Card 2
                    '1 / 7 / 2 / 10', // Card 3
                    '1 / 10 / 2 / 13', // Card 4
                    '2 / 1 / 4 / 9',  // Large chart
                    '2 / 9 / 4 / 13', // Side panel
                    '4 / 1 / 5 / 7',  // Bottom left
                    '4 / 7 / 5 / 13', // Bottom right
                ],
            ],
            
            // Portfolio Layouts
            'pattern-5' => [
                'name' => 'Portfolio Showcase',
                'columns' => 'repeat(5, 1fr)',
                'rows' => 'repeat(3, 180px)',
                'areas' => [
                    '1 / 1 / 3 / 3',  // Large feature
                    '1 / 3 / 2 / 4',  // Top 1
                    '1 / 4 / 2 / 5',  // Top 2
                    '1 / 5 / 2 / 6',  // Top 3
                    '2 / 3 / 3 / 6',  // Wide bottom
                    '3 / 1 / 4 / 2',  // Bottom 1
                    '3 / 2 / 4 / 3',  // Bottom 2
                    '3 / 3 / 4 / 4',  // Bottom 3
                    '3 / 4 / 4 / 5',  // Bottom 4
                    '3 / 5 / 4 / 6',  // Bottom 5
                ],
            ],
            
            // E-commerce Layouts
            'pattern-6' => [
                'name' => 'Product Grid',
                'columns' => 'repeat(4, 1fr)',
                'rows' => 'repeat(4, 180px)',
                'areas' => [
                    '1 / 1 / 3 / 3',  // Featured product
                    '1 / 3 / 2 / 4',  // Product 1
                    '1 / 4 / 2 / 5',  // Product 2
                    '2 / 3 / 3 / 4',  // Product 3
                    '2 / 4 / 3 / 5',  // Product 4
                    '3 / 1 / 5 / 2',  // Sidebar ad
                    '3 / 2 / 4 / 3',  // Product 5
                    '3 / 3 / 4 / 4',  // Product 6
                    '3 / 4 / 4 / 5',  // Product 7
                    '4 / 2 / 5 / 5',  // Wide banner
                ],
            ],
            
            // Asymmetric Layouts
            'pattern-7' => [
                'name' => 'Asymmetric Modern',
                'columns' => 'repeat(6, 1fr)',
                'rows' => 'repeat(4, 150px)',
                'areas' => [
                    '1 / 1 / 2 / 3',  // Top left
                    '1 / 3 / 3 / 5',  // Large center
                    '1 / 5 / 2 / 7',  // Top right
                    '2 / 1 / 3 / 2',  // Mid left 1
                    '2 / 2 / 3 / 3',  // Mid left 2
                    '2 / 5 / 4 / 7',  // Tall right
                    '3 / 1 / 5 / 3',  // Bottom left
                    '3 / 3 / 4 / 5',  // Bottom center
                    '4 / 3 / 5 / 7',  // Bottom right
                ],
            ],
            
            'pattern-8' => [
                'name' => 'Split Screen',
                'columns' => 'repeat(2, 1fr)',
                'rows' => 'repeat(6, 120px)',
                'areas' => [
                    '1 / 1 / 4 / 2',  // Left large
                    '1 / 2 / 2 / 3',  // Right top
                    '2 / 2 / 3 / 3',  // Right mid 1
                    '3 / 2 / 4 / 3',  // Right mid 2
                    '4 / 1 / 5 / 2',  // Left mid
                    '4 / 2 / 5 / 3',  // Right mid 3
                    '5 / 1 / 7 / 2',  // Left bottom
                    '5 / 2 / 7 / 3',  // Right bottom
                ],
            ],
            
            // Blog Layouts
            'pattern-9' => [
                'name' => 'Blog Magazine',
                'columns' => 'repeat(8, 1fr)',
                'rows' => 'repeat(5, 140px)',
                'areas' => [
                    '1 / 1 / 3 / 5',  // Hero post
                    '1 / 5 / 2 / 9',  // Top featured
                    '2 / 5 / 3 / 7',  // Side 1
                    '2 / 7 / 3 / 9',  // Side 2
                    '3 / 1 / 4 / 3',  // Post 1
                    '3 / 3 / 4 / 5',  // Post 2
                    '3 / 5 / 4 / 7',  // Post 3
                    '3 / 7 / 4 / 9',  // Post 4
                    '4 / 1 / 6 / 4',  // Large post
                    '4 / 4 / 5 / 6',  // Small 1
                    '4 / 6 / 5 / 8',  // Small 2
                    '4 / 8 / 5 / 9',  // Small 3
                    '5 / 4 / 6 / 9',  // Bottom wide
                ],
            ],
            
            // Complex Patterns
            'pattern-10' => [
                'name' => 'Creative Complex',
                'columns' => 'repeat(10, 1fr)',
                'rows' => 'repeat(6, 120px)',
                'areas' => [
                    '1 / 1 / 3 / 4',   // Box 1
                    '1 / 4 / 2 / 6',   // Box 2
                    '1 / 6 / 3 / 8',   // Box 3
                    '1 / 8 / 2 / 11',  // Box 4
                    '2 / 4 / 3 / 6',   // Box 5
                    '2 / 8 / 4 / 11',  // Box 6
                    '3 / 1 / 5 / 3',   // Box 7
                    '3 / 3 / 4 / 5',   // Box 8
                    '3 / 5 / 5 / 8',   // Box 9
                    '4 / 3 / 5 / 5',   // Box 10
                    '4 / 8 / 6 / 10',  // Box 11
                    '5 / 1 / 7 / 3',   // Box 12
                    '5 / 3 / 6 / 6',   // Box 13
                    '5 / 10 / 7 / 11', // Box 14
                    '6 / 3 / 7 / 5',   // Box 15
                    '6 / 5 / 7 / 8',   // Box 16
                    '6 / 8 / 7 / 10',  // Box 17
                ],
            ],
        ];
        
        return isset($templates[$pattern]) ? $templates[$pattern] : $templates['pattern-1'];
    }
}

