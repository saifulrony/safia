<?php
/**
 * Toggle Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Toggle extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'toggle';
        $this->title = __('Toggle', 'probuilder');
        $this->icon = 'fa fa-toggle-on';
        $this->category = 'advanced';
        $this->keywords = ['toggle', 'switch', 'collapse', 'expand'];
    }
    
    protected function register_controls() {
        // TOGGLE ITEMS
        $this->start_controls_section('section_toggle', [
            'label' => __('Toggle Items', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('items', [
            'label' => __('Toggle Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'title' => __('What are the system requirements?', 'probuilder'),
                    'content' => __('Our system works on all modern browsers and requires a stable internet connection.', 'probuilder'),
                ],
                [
                    'title' => __('How do I get started?', 'probuilder'),
                    'content' => __('Simply sign up for an account and follow our quick start guide.', 'probuilder'),
                ],
                [
                    'title' => __('Is there customer support?', 'probuilder'),
                    'content' => __('Yes! We offer 24/7 customer support via email and live chat.', 'probuilder'),
                ],
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => __('Title', 'probuilder'),
                    'type' => 'text',
                    'default' => __('Toggle Title', 'probuilder'),
                ],
                [
                    'name' => 'content',
                    'label' => __('Content', 'probuilder'),
                    'type' => 'textarea',
                    'default' => __('Toggle content', 'probuilder'),
                ],
                [
                    'name' => 'default_open',
                    'label' => __('Open by Default', 'probuilder'),
                    'type' => 'switcher',
                    'default' => 'no',
                ],
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('toggle_style', [
            'label' => __('Toggle Style', 'probuilder'),
            'type' => 'select',
            'default' => 'switch',
            'options' => [
                'switch' => __('Switch Style', 'probuilder'),
                'simple' => __('Simple', 'probuilder'),
                'bordered' => __('Bordered', 'probuilder'),
            ],
        ]);
        
        $this->add_control('title_bg_color', [
            'label' => __('Title Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f8f9fa',
        ]);
        
        $this->add_control('title_text_color', [
            'label' => __('Title Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('toggle_icon_color', [
            'label' => __('Toggle Icon Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('title_font_size', [
            'label' => __('Title Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 16,
            'range' => [
                'px' => [
                    'min' => 12,
                    'max' => 28,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('content_bg_color', [
            'label' => __('Content Background', 'probuilder'),
            'type' => 'color',
            'default' => '#f9f9f9',
        ]);
        
        $this->add_control('content_text_color', [
            'label' => __('Content Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 4,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1
                ]
            ]
        ]);
        
        $this->add_control('item_spacing', [
            'label' => __('Item Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 10,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 5
                ]
            ]
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED WITH ANIMATIONS
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]
        ]);
        
        $this->add_control('entrance_animation', [
            'label' => __('Entrance Animation', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'fadeIn' => __('Fade In', 'probuilder'),
                'fadeInUp' => __('Fade In Up', 'probuilder'),
                'slideInLeft' => __('Slide In Left', 'probuilder'),
                'slideInRight' => __('Slide In Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('animation_duration', [
            'label' => __('Animation Duration (ms)', 'probuilder'),
            'type' => 'slider',
            'default' => 600,
            'range' => [
                'px' => [
                    'min' => 100,
                    'max' => 2000,
                    'step' => 100
                ]
            ]
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
        $id = 'toggle-' . uniqid();
        
        $items = $settings['items'] ?? [];
        $toggle_style = $settings['toggle_style'] ?? 'switch';
        $title_bg = $settings['title_bg_color'] ?? '#f8f9fa';
        $title_color = $settings['title_text_color'] ?? '#333333';
        $icon_color = $settings['toggle_icon_color'] ?? '#92003b';
        $title_size = $settings['title_font_size'] ?? 16;
        $content_bg = $settings['content_bg_color'] ?? '#f9f9f9';
        $content_color = $settings['content_text_color'] ?? '#666666';
        $border_radius = $settings['border_radius'] ?? 4;
        $item_spacing = $settings['item_spacing'] ?? 10;
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        $animation = $settings['entrance_animation'] ?? 'none';
        $anim_duration = $settings['animation_duration'] ?? 600;
        
        if (empty($items)) {
            return;
        }
        
        $container_style = 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; ';
        
        if ($animation !== 'none') {
            $container_style .= 'opacity: 0; animation: ' . $animation . ' ' . ($anim_duration / 1000) . 's ease forwards;';
        }
        
        ?>
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-toggle" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" style="<?php echo esc_attr($container_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <?php foreach ($items as $index => $item): 
                $item_id = $id . '-' . $index;
                $is_open = ($item['default_open'] ?? 'no') === 'yes';
            ?>
            <div class="toggle-item" style="margin-bottom: <?php echo $item_spacing; ?>px;">
                <div class="toggle-title" data-item="<?php echo esc_attr($item_id); ?>" style="
                    background: <?php echo esc_attr($title_bg); ?>;
                    color: <?php echo esc_attr($title_color); ?>;
                    font-size: <?php echo esc_attr($title_size); ?>px;
                    padding: 15px 20px;
                    cursor: pointer;
                    border-radius: <?php echo esc_attr($border_radius); ?>px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    <?php if ($toggle_style === 'bordered'): ?>
                    border: 2px solid <?php echo esc_attr($icon_color); ?>;
                    <?php elseif ($toggle_style === 'simple'): ?>
                    border-bottom: 2px solid #e5e5e5;
                    background: transparent;
                    border-radius: 0;
                    <?php endif; ?>
                " onclick="
                    var content = this.nextElementSibling;
                    var icon = this.querySelector('.toggle-icon');
                    var switchToggle = this.querySelector('.switch-toggle');
                    if(content.style.display === 'none' || !content.style.display) {
                        content.style.display = 'block';
                        content.style.maxHeight = content.scrollHeight + 'px';
                        if (switchToggle) {
                            switchToggle.style.background = '<?php echo esc_js($icon_color); ?>';
                            switchToggle.querySelector('.switch-thumb').style.left = '22px';
                        }
                        if (icon) icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.style.maxHeight = '0';
                        setTimeout(function() { content.style.display = 'none'; }, 300);
                        if (switchToggle) {
                            switchToggle.style.background = '#cbd5e1';
                            switchToggle.querySelector('.switch-thumb').style.left = '2px';
                        }
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                ">
                    <span style="flex: 1;"><?php echo esc_html($item['title']); ?></span>
                    
                    <?php if ($toggle_style === 'switch'): ?>
                    <span class="switch-toggle" style="
                        position: relative;
                        width: 44px;
                        height: 24px;
                        background: <?php echo $is_open ? esc_attr($icon_color) : '#cbd5e1'; ?>;
                        border-radius: 12px;
                        transition: background 0.3s;
                        display: inline-block;
                        margin-left: 15px;
                    ">
                        <span class="switch-thumb" style="
                            position: absolute;
                            top: 2px;
                            left: <?php echo $is_open ? '22px' : '2px'; ?>;
                            width: 20px;
                            height: 20px;
                            background: #ffffff;
                            border-radius: 10px;
                            transition: left 0.3s;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        "></span>
                    </span>
                    <?php else: ?>
                    <span class="toggle-icon" style="transition: transform 0.3s; font-size: 18px; color: <?php echo esc_attr($icon_color); ?>;">▼</span>
                    <?php endif; ?>
                </div>
                
                <div class="toggle-content" style="
                    display: <?php echo $is_open ? 'block' : 'none'; ?>;
                    max-height: <?php echo $is_open ? '1000px' : '0'; ?>;
                    overflow: hidden;
                    transition: max-height 0.3s ease;
                    background: <?php echo esc_attr($content_bg); ?>;
                    color: <?php echo esc_attr($content_color); ?>;
                    padding: <?php echo $is_open ? '15px 20px' : '0 20px'; ?>;
                    margin-top: 5px;
                    border-radius: <?php echo esc_attr($border_radius); ?>px;
                ">
                    <p style="margin: 0; line-height: 1.6;"><?php echo esc_html($item['content']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
