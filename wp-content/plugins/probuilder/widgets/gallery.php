<?php
/**
 * Gallery Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Gallery extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'gallery';
        $this->title = __('Gallery', 'probuilder');
        $this->icon = 'fa fa-table-cells';
        $this->category = 'content';
        $this->keywords = ['gallery', 'images', 'grid', 'lightbox'];
    }
    
    protected function register_controls() {
        // IMAGES
        $this->start_controls_section('section_gallery', [
            'label' => __('Gallery Images', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('images', [
            'label' => __('Gallery Images', 'probuilder'),
            'type' => 'repeater',
            'default' => [
                [
                    'image_url' => 'https://via.placeholder.com/600x400/FF6B6B/ffffff?text=1',
                    'caption' => 'Beautiful Image 1',
                ],
                [
                    'image_url' => 'https://via.placeholder.com/600x400/4ECDC4/ffffff?text=2',
                    'caption' => 'Beautiful Image 2',
                ],
                [
                    'image_url' => 'https://via.placeholder.com/600x400/45B7D1/ffffff?text=3',
                    'caption' => 'Beautiful Image 3',
                ],
                [
                    'image_url' => 'https://via.placeholder.com/600x400/96CEB4/ffffff?text=4',
                    'caption' => 'Beautiful Image 4',
                ],
                [
                    'image_url' => 'https://via.placeholder.com/600x400/FFEAA7/ffffff?text=5',
                    'caption' => 'Beautiful Image 5',
                ],
                [
                    'image_url' => 'https://via.placeholder.com/600x400/6C5CE7/ffffff?text=6',
                    'caption' => 'Beautiful Image 6',
                ],
            ],
            'fields' => [
                [
                    'name' => 'image_url',
                    'label' => __('Image URL', 'probuilder'),
                    'type' => 'text',
                    'default' => 'https://via.placeholder.com/600x400',
                ],
                [
                    'name' => 'caption',
                    'label' => __('Caption', 'probuilder'),
                    'type' => 'text',
                    'default' => '',
                ],
            ],
        ]);
        
        $this->add_control('lightbox', [
            'label' => __('Enable Lightbox', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Click images to view in lightbox', 'probuilder'),
        ]);
        
        $this->add_control('show_caption', [
            'label' => __('Show Caption', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
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
            'default' => '3',
            'options' => [
                '1' => '1',
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
            'default' => 15,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 5
                ]
            ],
        ]);
        
        $this->add_control('image_ratio', [
            'label' => __('Image Ratio', 'probuilder'),
            'type' => 'select',
            'default' => 'original',
            'options' => [
                'original' => __('Original', 'probuilder'),
                '1:1' => __('1:1 (Square)', 'probuilder'),
                '4:3' => __('4:3', 'probuilder'),
                '16:9' => __('16:9', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
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
            'label' => __('Border Radius (px)', 'probuilder'),
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
        
        $this->add_control('caption_bg_color', [
            'label' => __('Caption Background', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.7)',
        ]);
        
        $this->add_control('caption_text_color', [
            'label' => __('Caption Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
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
                'fadeInDown' => __('Fade In Down', 'probuilder'),
                'fadeInLeft' => __('Fade In Left', 'probuilder'),
                'fadeInRight' => __('Fade In Right', 'probuilder'),
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
        
        $this->add_control('animation_delay', [
            'label' => __('Animation Delay (ms)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 3000,
                    'step' => 100
                ]
            ]
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings();
        $id = 'gallery-' . uniqid();
        
        $images = $settings['images'] ?? [];
        $lightbox = ($settings['lightbox'] ?? 'yes') !== 'no';
        $show_caption = ($settings['show_caption'] ?? 'yes') !== 'no';
        $columns = $settings['columns'] ?? '3';
        $gap = $settings['gap'] ?? 15;
        $image_ratio = $settings['image_ratio'] ?? 'original';
        $hover_effect = $settings['hover_effect'] ?? 'zoom';
        $border_radius = $settings['border_radius'] ?? 8;
        $caption_bg = $settings['caption_bg_color'] ?? 'rgba(0,0,0,0.7)';
        $caption_color = $settings['caption_text_color'] ?? '#ffffff';
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        $animation = $settings['entrance_animation'] ?? 'none';
        $anim_duration = $settings['animation_duration'] ?? 600;
        $anim_delay = $settings['animation_delay'] ?? 0;
        
        if (empty($images)) {
            return;
        }
        
        $grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($gap) . 'px; ';
        $grid_style .= 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px;';
        
        if ($animation !== 'none') {
            $grid_style .= ' opacity: 0; animation: ' . $animation . ' ' . ($anim_duration / 1000) . 's ease forwards ' . ($anim_delay / 1000) . 's;';
        }
        
        ?>
        <div class="probuilder-gallery" id="<?php echo esc_attr($id); ?>" style="<?php echo $grid_style; ?>" data-lightbox="<?php echo $lightbox ? 'true' : 'false'; ?>">
            <?php foreach ($images as $index => $image): 
                $item_style = 'position: relative; overflow: hidden; border-radius: ' . $border_radius . 'px; line-height: 0; transition: all 0.3s ease;';
                
                if ($image_ratio !== 'original') {
                    $item_style .= ' aspect-ratio: ' . str_replace(':', '/', $image_ratio) . ';';
                }
            ?>
            <div class="gallery-item" data-index="<?php echo $index; ?>" style="<?php echo $item_style; ?>" data-hover="<?php echo esc_attr($hover_effect); ?>">
                <img src="<?php echo esc_url($image['image_url']); ?>" alt="<?php echo esc_attr($image['caption'] ?? ''); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease; cursor: <?php echo $lightbox ? 'pointer' : 'default'; ?>;">
                
                <?php if ($show_caption && !empty($image['caption'])): ?>
                <div class="gallery-caption" style="
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background: <?php echo esc_attr($caption_bg); ?>;
                    color: <?php echo esc_attr($caption_color); ?>;
                    padding: 10px 15px;
                    font-size: 14px;
                    transform: translateY(100%);
                    transition: transform 0.3s ease;
                ">
                    <?php echo esc_html($image['caption']); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($lightbox): ?>
        <script>
        jQuery(document).ready(function($) {
            $('#<?php echo esc_js($id); ?> .gallery-item img').on('click', function() {
                const index = $(this).closest('.gallery-item').data('index');
                const images = <?php echo json_encode(array_column($images, 'image_url')); ?>;
                const captions = <?php echo json_encode(array_column($images, 'caption')); ?>;
                
                // Create lightbox
                const lightbox = $('<div class="probuilder-lightbox" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 999999; display: flex; align-items: center; justify-content: center; flex-direction: column;"></div>');
                
                const img = $('<img>').attr('src', images[index]).css({
                    'max-width': '90%',
                    'max-height': '80vh',
                    'box-shadow': '0 10px 50px rgba(0,0,0,0.5)'
                });
                
                const caption = $('<div>').text(captions[index]).css({
                    'color': '#ffffff',
                    'margin-top': '20px',
                    'font-size': '16px'
                });
                
                const close = $('<div>').html('×').css({
                    'position': 'absolute',
                    'top': '20px',
                    'right': '30px',
                    'color': '#ffffff',
                    'font-size': '40px',
                    'cursor': 'pointer'
                }).on('click', function() {
                    lightbox.fadeOut(300, function() { $(this).remove(); });
                });
                
                lightbox.append(close, img, caption);
                $('body').append(lightbox);
                lightbox.hide().fadeIn(300);
                
                // Close on background click
                lightbox.on('click', function(e) {
                    if (e.target === this) {
                        $(this).fadeOut(300, function() { $(this).remove(); });
                    }
                });
            });
            
            // Hover effects
            $('#<?php echo esc_js($id); ?> .gallery-item').on('mouseenter', function() {
                const effect = $(this).data('hover');
                const $img = $(this).find('img');
                const $caption = $(this).find('.gallery-caption');
                
                if (effect === 'zoom') {
                    $img.css('transform', 'scale(1.1)');
                } else if (effect === 'lift') {
                    $(this).css('transform', 'translateY(-5px)');
                } else if (effect === 'overlay') {
                    $img.css('opacity', '0.7');
                }
                
                $caption.css('transform', 'translateY(0)');
            }).on('mouseleave', function() {
                const $img = $(this).find('img');
                const $caption = $(this).find('.gallery-caption');
                
                $img.css({'transform': 'scale(1)', 'opacity': '1'});
                $(this).css('transform', 'translateY(0)');
                $caption.css('transform', 'translateY(100%)');
            });
        });
        </script>
        <?php endif; ?>
        <?php
    }
}
