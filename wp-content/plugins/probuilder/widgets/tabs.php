<?php
/**
 * Tabs Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Tabs extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'tabs';
        $this->title = __('Tabs', 'probuilder');
        $this->icon = 'fa fa-folder';
        $this->category = 'advanced';
        $this->keywords = ['tabs', 'toggle', 'switch'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_tabs', [
            'label' => __('Tabs', 'probuilder'),
        ]);
        
        $this->add_control('tab_orientation', [
            'label' => __('Tab Orientation', 'probuilder'),
            'type' => 'select',
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal (Top)', 'probuilder'),
                'vertical' => __('Vertical (Left)', 'probuilder'),
            ],
        ]);
        
        $this->add_control('tab_alignment', [
            'label' => __('Tab Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
                'justified' => __('Justified (Equal Width)', 'probuilder'),
            ],
            'condition' => [
                'tab_orientation' => 'horizontal'
            ]
        ]);
        
        $this->add_control('vertical_tab_width', [
            'label' => __('Tab Width (%)', 'probuilder'),
            'type' => 'slider',
            'default' => 25,
            'range' => [
                'px' => ['min' => 15, 'max' => 50],
            ],
            'condition' => [
                'tab_orientation' => 'vertical'
            ]
        ]);
        
        $this->add_control('tabs', [
            'label' => __('Tabs Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'tab_title' => __('Tab #1', 'probuilder'),
                    'tab_icon' => 'fa fa-home',
                    'tab_content' => __('Tab content goes here.', 'probuilder'),
                ],
                [
                    'tab_title' => __('Tab #2', 'probuilder'),
                    'tab_icon' => 'fa fa-star',
                    'tab_content' => __('Tab content goes here.', 'probuilder'),
                ],
                [
                    'tab_title' => __('Tab #3', 'probuilder'),
                    'tab_icon' => 'fa fa-heart',
                    'tab_content' => __('Tab content goes here.', 'probuilder'),
                ],
            ],
            'fields' => [
                [
                    'name' => 'tab_title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Tab Title', 'probuilder'),
                ],
                [
                    'name' => 'tab_icon',
                    'label' => __('Icon (Optional)', 'probuilder'),
                    'type' => 'text',
                    'default' => 'fa fa-star',
                    'placeholder' => 'fa fa-star',
                ],
                [
                    'name' => 'tab_content',
                    'label' => __('Content', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Tab content', 'probuilder'),
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('tab_bg_color', [
            'label' => __('Tab Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f5f5f5',
        ]);
        
        $this->add_control('tab_active_bg_color', [
            'label' => __('Active Tab Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('tab_text_color', [
            'label' => __('Tab Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('tab_active_text_color', [
            'label' => __('Active Tab Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#007cba',
        ]);
        
        $this->add_control('tab_border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ddd',
        ]);
        
        $this->add_control('tab_border_width', [
            'label' => __('Border Width', 'probuilder'),
            'type' => 'slider',
            'default' => 1,
            'range' => [
                'px' => ['min' => 0, 'max' => 10],
            ],
        ]);
        
        $this->add_control('tab_border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 4,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->add_control('tab_padding', [
            'label' => __('Tab Padding', 'probuilder'),
            'type' => 'slider',
            'default' => 15,
            'range' => [
                'px' => ['min' => 5, 'max' => 50],
            ],
        ]);
        
        $this->add_control('content_padding', [
            'label' => __('Content Padding', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $tabs = $this->get_settings('tabs', []);
        $orientation = $this->get_settings('tab_orientation', 'horizontal');
        $alignment = $this->get_settings('tab_alignment', 'left');
        $vertical_width = $this->get_settings('vertical_tab_width', 25);
        
        $tab_bg = $this->get_settings('tab_bg_color', '#f5f5f5');
        $tab_active_bg = $this->get_settings('tab_active_bg_color', '#ffffff');
        $tab_text = $this->get_settings('tab_text_color', '#333333');
        $tab_active_text = $this->get_settings('tab_active_text_color', '#007cba');
        $border_color = $this->get_settings('tab_border_color', '#ddd');
        $border_width = $this->get_settings('tab_border_width', 1);
        $border_radius = $this->get_settings('tab_border_radius', 4);
        $tab_padding = $this->get_settings('tab_padding', 15);
        $content_padding = $this->get_settings('content_padding', 20);
        
        if (empty($tabs)) {
            return;
        }
        
        $id = 'probuilder-tabs-' . uniqid();
        $wrapper_class = 'probuilder-tabs probuilder-tabs-' . esc_attr($orientation);
        
        ?>
        <style>
            #<?php echo $id; ?> {
                display: <?php echo $orientation === 'vertical' ? 'flex' : 'block'; ?>;
                gap: 0;
            }
            
            #<?php echo $id; ?> .probuilder-tabs-nav {
                <?php if ($orientation === 'vertical'): ?>
                    width: <?php echo esc_attr($vertical_width); ?>%;
                    flex-shrink: 0;
                    border-right: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                <?php else: ?>
                    display: flex;
                    <?php if ($alignment === 'center'): ?>
                        justify-content: center;
                    <?php elseif ($alignment === 'right'): ?>
                        justify-content: flex-end;
                    <?php elseif ($alignment === 'justified'): ?>
                        justify-content: space-between;
                    <?php else: ?>
                        justify-content: flex-start;
                    <?php endif; ?>
                    border-bottom: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                <?php endif; ?>
            }
            
            #<?php echo $id; ?> .probuilder-tab-title {
                padding: <?php echo esc_attr($tab_padding); ?>px <?php echo esc_attr($tab_padding * 1.5); ?>px;
                cursor: pointer;
                transition: all 0.3s ease;
                border: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                background: <?php echo esc_attr($tab_bg); ?>;
                color: <?php echo esc_attr($tab_text); ?>;
                user-select: none;
                
                <?php if ($orientation === 'vertical'): ?>
                    border-right: none;
                    border-bottom: none;
                    margin-bottom: -<?php echo esc_attr($border_width); ?>px;
                    text-align: left;
                <?php else: ?>
                    display: inline-block;
                    border-bottom: none;
                    margin-right: -<?php echo esc_attr($border_width); ?>px;
                    border-top-left-radius: <?php echo esc_attr($border_radius); ?>px;
                    border-top-right-radius: <?php echo esc_attr($border_radius); ?>px;
                    <?php if ($alignment === 'justified'): ?>
                        flex: 1;
                        text-align: center;
                    <?php endif; ?>
                <?php endif; ?>
            }
            
            #<?php echo $id; ?> .probuilder-tab-title.active {
                background: <?php echo esc_attr($tab_active_bg); ?>;
                color: <?php echo esc_attr($tab_active_text); ?>;
                font-weight: 600;
                
                <?php if ($orientation === 'vertical'): ?>
                    border-right-color: transparent;
                    position: relative;
                    margin-right: -<?php echo esc_attr($border_width); ?>px;
                <?php else: ?>
                    border-bottom-color: transparent;
                    position: relative;
                    margin-bottom: -<?php echo esc_attr($border_width); ?>px;
                <?php endif; ?>
            }
            
            #<?php echo $id; ?> .probuilder-tab-title:hover:not(.active) {
                background: rgba(0,124,186,0.05);
                color: <?php echo esc_attr($tab_active_text); ?>;
            }
            
            #<?php echo $id; ?> .probuilder-tab-title i {
                margin-right: 8px;
            }
            
            #<?php echo $id; ?> .probuilder-tabs-content {
                <?php if ($orientation === 'vertical'): ?>
                    flex: 1;
                <?php endif; ?>
                border: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                padding: <?php echo esc_attr($content_padding); ?>px;
                background: <?php echo esc_attr($tab_active_bg); ?>;
                
                <?php if ($orientation === 'horizontal'): ?>
                    border-top: none;
                    border-bottom-left-radius: <?php echo esc_attr($border_radius); ?>px;
                    border-bottom-right-radius: <?php echo esc_attr($border_radius); ?>px;
                <?php else: ?>
                    border-left: none;
                    border-radius: 0 <?php echo esc_attr($border_radius); ?>px <?php echo esc_attr($border_radius); ?>px 0;
                <?php endif; ?>
            }
            
            #<?php echo $id; ?> .probuilder-tab-content {
                display: none;
                animation: fadeIn 0.3s ease;
            }
            
            #<?php echo $id; ?> .probuilder-tab-content.active {
                display: block;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        </style>
        
        <div class="<?php echo esc_attr($wrapper_class); ?>" id="<?php echo esc_attr($id); ?>">
            <!-- Tab Navigation -->
            <div class="probuilder-tabs-nav">
                <?php foreach ($tabs as $index => $tab): ?>
                    <div class="probuilder-tab-title <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-tab="<?php echo esc_attr($index); ?>">
                        <?php if (!empty($tab['tab_icon'])): ?>
                            <i class="<?php echo esc_attr($tab['tab_icon']); ?>"></i>
                        <?php endif; ?>
                        <?php echo esc_html($tab['tab_title']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Tab Content -->
            <div class="probuilder-tabs-content">
                <?php foreach ($tabs as $index => $tab): ?>
                    <div class="probuilder-tab-content <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-tab="<?php echo esc_attr($index); ?>">
                        <?php echo wp_kses_post(wpautop($tab['tab_content'])); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script>
        (function($) {
            $(document).ready(function() {
                $('#<?php echo $id; ?> .probuilder-tab-title').on('click', function() {
                    var tabIndex = $(this).data('tab');
                    var $container = $(this).closest('.probuilder-tabs');
                    
                    // Update tab titles
                    $container.find('.probuilder-tab-title').removeClass('active');
                    $(this).addClass('active');
                    
                    // Update tab content
                    $container.find('.probuilder-tab-content').removeClass('active');
                    $container.find('.probuilder-tab-content[data-tab="' + tabIndex + '"]').addClass('active');
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}

