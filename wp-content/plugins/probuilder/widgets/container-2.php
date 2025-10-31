<?php
/**
 * Container 2 Widget - Based on Grid Layout with Perfect Resize
 * This is a reliable container widget with the same working resize functionality as Grid Layout
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Container2 extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'container-2';
        $this->title = __('Container 2', 'probuilder');
        $this->icon = 'fa fa-columns';
        $this->category = 'layout';
        $this->keywords = ['container', 'layout', 'columns', 'rows', 'flexible'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_grid', [
            'label' => __('Container Layout', 'probuilder'),
        ]);
        
        $this->add_control('columns', [
            'label' => __('Number of Columns', 'probuilder'),
            'type' => 'slider',
            'default' => 2,
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 12,
                    'step' => 1,
                ],
            ],
            'description' => __('Set the number of columns in the row', 'probuilder'),
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
            'label' => __('Enable Resize Handles', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
            'description' => __('Allow resizing sections from any edge - top, bottom, left, right, corner', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('min_height', [
            'label' => __('Min Section Height', 'probuilder'),
            'type' => 'slider',
            'default' => 150,
            'range' => [
                'px' => ['min' => 50, 'max' => 500],
            ],
        ]);
        
        $this->add_control('background_color', [
            'label' => __('Section Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f8f9fa',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Section Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ddd',
        ]);
        
        $this->add_control('border_width', [
            'label' => __('Section Border Width', 'probuilder'),
            'type' => 'slider',
            'default' => 1,
            'range' => [
                'px' => ['min' => 0, 'max' => 10],
            ],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Section Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Spacing
        $this->start_controls_section('section_spacing', [
            'label' => __('Spacing', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'],
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $settings = $this->settings;
        $columns = $this->get_settings('columns', 2);
        $gap = $this->get_settings('gap', 20);
        $min_height = $this->get_settings('min_height', 150);
        $bg_color = $this->get_settings('background_color', '#f8f9fa');
        $border_color = $this->get_settings('border_color', '#ddd');
        $border_width = $this->get_settings('border_width', 1);
        $border_radius = $this->get_settings('border_radius', 8);
        $enable_resize = $this->get_settings('enable_resize', true);
        $margin = $this->get_settings('margin', ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0']);
        $padding = $this->get_settings('padding', ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0']);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        $grid_id = 'probuilder-container2-' . uniqid();
        
        // Generate grid template based on number of columns
        $grid_template = $this->get_grid_template($columns);
        
        // Build wrapper styles with margin and padding
        $wrapper_style = '';
        $wrapper_style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px; ';
        $wrapper_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px;';
        
        ?>
        <style>
            #<?php echo $grid_id; ?> {
                display: grid;
                grid-template-columns: <?php echo esc_attr($grid_template['columns']); ?>;
                grid-template-rows: <?php echo esc_attr($grid_template['rows']); ?>;
                gap: <?php echo esc_attr($gap); ?>px;
                width: 100%;
                position: relative;
            }
            
            #<?php echo $grid_id; ?> .grid-cell {
                min-height: <?php echo esc_attr($min_height); ?>px;
                background: <?php echo esc_attr($bg_color); ?>;
                border: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                border-radius: <?php echo esc_attr($border_radius); ?>px;
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s;
                position: relative;
                overflow: hidden;
            }
            
            #<?php echo $grid_id; ?> .grid-cell:hover {
                background: rgba(0,124,186,0.05);
                border-color: #007cba;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            
            <?php if ($enable_resize): ?>
            #<?php echo $grid_id; ?> .grid-cell .resize-handle {
                position: absolute;
                background: #007cba;
                opacity: 0;
                transition: opacity 0.2s;
                z-index: 10;
            }
            
            #<?php echo $grid_id; ?> .grid-cell:hover .resize-handle {
                opacity: 0.6;
            }
            
            #<?php echo $grid_id; ?> .grid-cell .resize-handle:hover {
                opacity: 1 !important;
            }
            
            #<?php echo $grid_id; ?> .grid-cell .resize-handle-right {
                top: 0;
                right: 0;
                width: 4px;
                height: 100%;
                cursor: col-resize;
            }
            
            #<?php echo $grid_id; ?> .grid-cell .resize-handle-bottom {
                bottom: 0;
                left: 0;
                width: 100%;
                height: 4px;
                cursor: row-resize;
            }
            
            #<?php echo $grid_id; ?> .grid-cell .resize-handle-corner {
                bottom: 0;
                right: 0;
                width: 12px;
                height: 12px;
                cursor: nwse-resize;
                border-radius: 0 0 <?php echo esc_attr($border_radius); ?>px 0;
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
        
        <div id="<?php echo $grid_id; ?>" class="<?php echo esc_attr($wrapper_classes); ?> probuilder-grid-layout" <?php echo $wrapper_attributes; ?> data-resizable="<?php echo $enable_resize ? '1' : '0'; ?>" style="<?php echo esc_attr($wrapper_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <?php for ($i = 0; $i < count($grid_template['areas']); $i++): ?>
                <div class="grid-cell grid-cell-<?php echo $i + 1; ?>" data-cell-index="<?php echo $i; ?>">
                    <?php if ($enable_resize): ?>
                    <div class="resize-handle resize-handle-right"></div>
                    <div class="resize-handle resize-handle-bottom"></div>
                    <div class="resize-handle resize-handle-corner"></div>
                    <?php endif; ?>
                    
                    <div class="grid-cell-toolbar">
                        <button class="add-content-btn" title="Add Content">
                            <i class="dashicons dashicons-plus" style="font-size: 12px; width: 12px; height: 12px;"></i>
                        </button>
                        <button class="settings-btn" title="Section Settings">
                            <i class="dashicons dashicons-admin-generic" style="font-size: 12px; width: 12px; height: 12px;"></i>
                        </button>
                    </div>
                    
                    <div class="grid-cell-content">
                        <i class="dashicons dashicons-welcome-add-page" style="font-size: 32px; opacity: 0.3; color: #999;"></i>
                        <div style="font-size: 12px; margin-top: 8px; color: #999;">Section <?php echo $i + 1; ?></div>
                        <div style="font-size: 11px; margin-top: 4px; color: #bbb;">Drop widgets here</div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        
        <?php if ($enable_resize): ?>
        <script>
        (function() {
            const gridId = '<?php echo $grid_id; ?>';
            const grid = document.getElementById(gridId);
            
            if (!grid) return;
            
            // Initialize resize functionality
            function initializeResize() {
                const cells = grid.querySelectorAll('.grid-cell');
                
                cells.forEach((cell, index) => {
                    const rightHandle = cell.querySelector('.resize-handle-right');
                    const bottomHandle = cell.querySelector('.resize-handle-bottom');
                    const cornerHandle = cell.querySelector('.resize-handle-corner');
                    
                    if (rightHandle) {
                        rightHandle.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            startResize(cell, 'width', e);
                        });
                    }
                    
                    if (bottomHandle) {
                        bottomHandle.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            startResize(cell, 'height', e);
                        });
                    }
                    
                    if (cornerHandle) {
                        cornerHandle.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            startResize(cell, 'both', e);
                        });
                    }
                });
            }
            
            function startResize(cell, direction, e) {
                const startX = e.clientX;
                const startY = e.clientY;
                const startWidth = cell.offsetWidth;
                const startHeight = cell.offsetHeight;
                const gridArea = window.getComputedStyle(cell).gridArea;
                
                // Parse grid-area (row-start / col-start / row-end / col-end)
                const parts = gridArea.split('/').map(p => parseInt(p.trim()));
                let [rowStart, colStart, rowEnd, colEnd] = parts;
                
                function onMouseMove(e) {
                    const deltaX = e.clientX - startX;
                    const deltaY = e.clientY - startY;
                    
                    if (direction === 'width' || direction === 'both') {
                        // Calculate new column span
                        const newColEnd = Math.max(colStart + 1, Math.round(colStart + (startWidth + deltaX) / (startWidth / (colEnd - colStart))));
                        colEnd = newColEnd;
                    }
                    
                    if (direction === 'height' || direction === 'both') {
                        // Calculate new row span
                        const newRowEnd = Math.max(rowStart + 1, Math.round(rowStart + (startHeight + deltaY) / (startHeight / (rowEnd - rowStart))));
                        rowEnd = newRowEnd;
                    }
                    
                    // Apply new grid-area
                    cell.style.gridArea = `${rowStart} / ${colStart} / ${rowEnd} / ${colEnd}`;
                    
                    // Visual feedback
                    cell.style.boxShadow = '0 0 20px rgba(0,124,186,0.3)';
                    cell.style.borderColor = '#007cba';
                }
                
                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                    
                    // Remove visual feedback
                    cell.style.boxShadow = '';
                    cell.style.borderColor = '';
                    
                    // Dispatch custom event for saving
                    const event = new CustomEvent('gridCellResized', {
                        detail: {
                            cellIndex: cell.dataset.cellIndex,
                            gridArea: cell.style.gridArea
                        }
                    });
                    grid.dispatchEvent(event);
                }
                
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            }
            
            // Initialize on load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeResize);
            } else {
                initializeResize();
            }
            
            // Make cells droppable for widgets
            const cells = grid.querySelectorAll('.grid-cell');
            cells.forEach(cell => {
                cell.addEventListener('click', function(e) {
                    if (e.target.closest('.add-content-btn')) {
                        e.preventDefault();
                        // Trigger widget panel
                        if (typeof ProBuilder !== 'undefined' && ProBuilder.openWidgetPanel) {
                            ProBuilder.openWidgetPanel(cell);
                        }
                    } else if (e.target.closest('.settings-btn')) {
                        e.preventDefault();
                        // Show cell settings
                        console.log('Section settings for section', cell.dataset.cellIndex);
                    }
                });
            });
        })();
        </script>
        <?php endif; ?>
        <?php
    }
    
    /**
     * Get grid template - Simple row with N columns
     */
    private function get_grid_template($columns) {
        // Generate areas for single row with N columns
        $areas = [];
        for ($i = 1; $i <= $columns; $i++) {
            $areas[] = "1 / {$i} / 2 / " . ($i + 1);
        }
        
        return [
            'name' => $columns . ' Columns',
            'columns' => 'repeat(' . $columns . ', 1fr)',
            'rows' => '1fr',
            'areas' => $areas,
        ];
    }
}


