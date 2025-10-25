<?php
/**
 * Logo Grid Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Logo_Grid extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'logo-grid';
        $this->title = __('Logo Grid', 'probuilder');
        $this->icon = 'fa fa-grip';
        $this->category = 'content';
        $this->keywords = ['logo', 'grid', 'clients', 'partners', 'brands'];
    }
    
    protected function register_controls() {
        // LOGOS
        $this->start_controls_section('section_logos', [
            'label' => __('Logos', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('logos', [
            'label' => __('Logo Items', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'logo_url' => 'https://logo.clearbit.com/google.com',
                    'name' => 'Google',
                    'link' => 'https://google.com',
                ],
                [
                    'logo_url' => 'https://logo.clearbit.com/microsoft.com',
                    'name' => 'Microsoft',
                    'link' => 'https://microsoft.com',
                ],
                [
                    'logo_url' => 'https://logo.clearbit.com/apple.com',
                    'name' => 'Apple',
                    'link' => 'https://apple.com',
                ],
                [
                    'logo_url' => 'https://logo.clearbit.com/amazon.com',
                    'name' => 'Amazon',
                    'link' => 'https://amazon.com',
                ],
                [
                    'logo_url' => 'https://logo.clearbit.com/facebook.com',
                    'name' => 'Meta',
                    'link' => 'https://facebook.com',
                ],
                [
                    'logo_url' => 'https://logo.clearbit.com/netflix.com',
                    'name' => 'Netflix',
                    'link' => 'https://netflix.com',
                ],
            ],
            'fields' => [
                [
                    'name' => 'logo_url',
                    'label' => __('Logo URL', 'probuilder'),
                    'type' => 'text',
                    'default' => 'https://via.placeholder.com/200x100',
                ],
                [
                    'name' => 'name',
                    'label' => __('Brand Name', 'probuilder'),
                    'type' => 'text',
                    'default' => 'Brand',
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
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '4',
            'options' => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
        ]);
        
        $this->add_control('gap', [
            'label' => __('Gap (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 30,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 80,
                    'step' => 5
                ]
            ]
        ]);
        
        $this->add_control('vertical_align', [
            'label' => __('Vertical Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'center',
            'options' => [
                'start' => __('Top', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'end' => __('Bottom', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('grayscale', [
            'label' => __('Grayscale Effect', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Logos appear in grayscale and colorize on hover', 'probuilder'),
        ]);
        
        $this->add_control('opacity', [
            'label' => __('Logo Opacity', 'probuilder'),
            'type' => 'slider',
            'default' => 0.7,
            'range' => [
                'px' => [
                    'min' => 0.1,
                    'max' => 1,
                    'step' => 0.1
                ]
            ]
        ]);
        
        $this->add_control('hover_opacity', [
            'label' => __('Hover Opacity', 'probuilder'),
            'type' => 'slider',
            'default' => 1,
            'range' => [
                'px' => [
                    'min' => 0.1,
                    'max' => 1,
                    'step' => 0.1
                ]
            ]
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => 'transparent',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Logo Padding (px)', 'probuilder'),
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
        
        $this->add_control('border', [
            'label' => __('Show Border', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e5e5e5',
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
                'zoomIn' => __('Zoom In', 'probuilder'),
                'bounceIn' => __('Bounce In', 'probuilder'),
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
        $settings = $this->get_settings();
        $id = 'logo-grid-' . uniqid();
        
        $logos = $settings['logos'] ?? [];
        $columns = $settings['columns'] ?? '4';
        $gap = $settings['gap'] ?? 30;
        $vertical_align = $settings['vertical_align'] ?? 'center';
        $grayscale = ($settings['grayscale'] ?? 'yes') === 'yes';
        $opacity = $settings['opacity'] ?? 0.7;
        $hover_opacity = $settings['hover_opacity'] ?? 1;
        $bg_color = $settings['bg_color'] ?? 'transparent';
        $logo_padding = $settings['padding'] ?? 20;
        $show_border = ($settings['border'] ?? 'no') === 'yes';
        $border_color = $settings['border_color'] ?? '#e5e5e5';
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        $animation = $settings['entrance_animation'] ?? 'none';
        $anim_duration = $settings['animation_duration'] ?? 600;
        
        if (empty($logos)) {
            return;
        }
        
        $grid_style = 'display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: ' . $gap . 'px; align-items: ' . $vertical_align . '; ';
        $grid_style .= 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px; ';
        
        if ($animation !== 'none') {
            $grid_style .= 'opacity: 0; animation: ' . $animation . ' ' . ($anim_duration / 1000) . 's ease forwards;';
        }
        
        ?>
        <div class="probuilder-logo-grid" id="<?php echo esc_attr($id); ?>" style="<?php echo $grid_style; ?>">
            <?php foreach ($logos as $logo): 
                $item_style = 'text-align: center; padding: ' . $logo_padding . 'px; background: ' . $bg_color . '; ';
                $item_style .= 'transition: all 0.3s ease; ';
                
                if ($show_border) {
                    $item_style .= 'border: 1px solid ' . $border_color . '; border-radius: 8px; ';
                }
                
                $img_style = 'max-width: 100%; height: auto; display: block; margin: 0 auto; transition: all 0.3s ease; ';
                
                if ($grayscale) {
                    $img_style .= 'filter: grayscale(100%); ';
                }
                
                $img_style .= 'opacity: ' . $opacity . ';';
                
                $has_link = !empty($logo['link']);
            ?>
            <div class="logo-item" style="<?php echo $item_style; ?>" data-hover-opacity="<?php echo esc_attr($hover_opacity); ?>">
                <?php if ($has_link): ?>
                <a href="<?php echo esc_url($logo['link']); ?>" target="_blank" rel="noopener" style="display: block; text-decoration: none;">
                <?php endif; ?>
                
                <img src="<?php echo esc_url($logo['logo_url']); ?>" alt="<?php echo esc_attr($logo['name']); ?>" title="<?php echo esc_attr($logo['name']); ?>" style="<?php echo $img_style; ?>">
                
                <?php if ($has_link): ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#<?php echo esc_js($id); ?> .logo-item').on('mouseenter', function() {
                const hoverOpacity = $(this).data('hover-opacity');
                $(this).find('img').css({
                    'filter': 'grayscale(0%)',
                    'opacity': hoverOpacity,
                    'transform': 'scale(1.05)'
                });
            }).on('mouseleave', function() {
                $(this).find('img').css({
                    'filter': '<?php echo $grayscale ? 'grayscale(100%)' : 'grayscale(0%)'; ?>',
                    'opacity': '<?php echo $opacity; ?>',
                    'transform': 'scale(1)'
                });
            });
        });
        </script>
        <?php
    }
}
