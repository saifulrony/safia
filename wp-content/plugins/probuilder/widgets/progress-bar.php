<?php
/**
 * Progress Bar Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Progress_Bar extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'progress-bar';
        $this->title = __('Progress Bar', 'probuilder');
        $this->icon = 'fa fa-chart-simple';
        $this->category = 'content';
        $this->keywords = ['progress', 'bar', 'skill', 'percentage'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_progress', [
            'label' => __('Progress Bar', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('My Skill', 'probuilder'),
        ]);
        
        $this->add_control('percentage', [
            'label' => __('Percentage', 'probuilder'),
            'type' => 'slider',
            'default' => 75,
            'range' => [
                'px' => ['min' => 0, 'max' => 100],
            ],
        ]);
        
        $this->add_control('show_percentage', [
            'label' => __('Show Percentage', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('inner_text', [
            'label' => __('Inner Text', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'description' => __('Optional text inside the progress bar', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('bar_style', [
            'label' => __('Bar Style', 'probuilder'),
            'type' => 'select',
            'default' => 'solid',
            'options' => [
                'solid' => __('Solid', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'striped' => __('Striped', 'probuilder'),
                'animated' => __('Animated Stripes', 'probuilder'),
            ],
        ]);
        
        $this->add_control('bar_color', [
            'label' => __('Bar Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('bar_gradient', [
            'label' => __('Bar Gradient', 'probuilder'),
            'type' => 'text',
            'default' => 'linear-gradient(90deg, #92003b 0%, #c44569 100%)',
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e5e7eb',
        ]);
        
        $this->add_control('height', [
            'label' => __('Height (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 30,
            'range' => [
                'px' => ['min' => 10, 'max' => 60],
            ],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 15,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('percentage_color', [
            'label' => __('Percentage Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('inner_text_color', [
            'label' => __('Inner Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->end_controls_section();
        
        // Animation Section
        $this->start_controls_section('section_animation', [
            'label' => __('Animation', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('enable_fill_animation', [
            'label' => __('Enable Fill Animation', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
            'description' => __('Animate progress bar from 0 to target percentage', 'probuilder'),
        ]);
        
        $this->add_control('animation_duration', [
            'label' => __('Animation Duration (seconds)', 'probuilder'),
            'type' => 'slider',
            'default' => 1.5,
            'range' => [
                'px' => ['min' => 0.5, 'max' => 5, 'step' => 0.1],
            ],
        ]);
        
        $this->add_control('animation_delay', [
            'label' => __('Animation Delay (seconds)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => ['min' => 0, 'max' => 3, 'step' => 0.1],
            ],
        ]);
        
        $this->add_control('animation_easing', [
            'label' => __('Animation Easing', 'probuilder'),
            'type' => 'select',
            'default' => 'ease-out',
            'options' => [
                'linear' => __('Linear', 'probuilder'),
                'ease' => __('Ease', 'probuilder'),
                'ease-in' => __('Ease In', 'probuilder'),
                'ease-out' => __('Ease Out', 'probuilder'),
                'ease-in-out' => __('Ease In Out', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'My Skill');
        $percentage = $this->get_settings('percentage', 75);
        $show_percentage = $this->get_settings('show_percentage', 'yes');
        $inner_text = $this->get_settings('inner_text', '');
        $bar_style_type = $this->get_settings('bar_style', 'solid');
        $bar_color = $this->get_settings('bar_color', '#92003b');
        $bar_gradient = $this->get_settings('bar_gradient', 'linear-gradient(90deg, #92003b 0%, #c44569 100%)');
        $bg_color = $this->get_settings('bg_color', '#e5e7eb');
        $height = $this->get_settings('height', 30);
        $border_radius = $this->get_settings('border_radius', 15);
        $title_color = $this->get_settings('title_color', '#333333');
        $percentage_color = $this->get_settings('percentage_color', '#333333');
        $inner_text_color = $this->get_settings('inner_text_color', '#ffffff');
        $enable_fill_animation = $this->get_settings('enable_fill_animation', 'yes') === 'yes';
        $animation_duration = $this->get_settings('animation_duration', 1.5);
        $animation_delay = $this->get_settings('animation_delay', 0);
        $animation_easing = $this->get_settings('animation_easing', 'ease-out');
        
        $id = 'progress-' . uniqid();
        
        echo '<div class="probuilder-progress-bar" style="margin-bottom: 20px;">';
        
        // Title and Percentage
        echo '<div class="probuilder-progress-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
        echo '<span style="font-weight: 600; font-size: 15px; color: ' . esc_attr($title_color) . ';">' . esc_html($title) . '</span>';
        if ($show_percentage === 'yes') {
            echo '<span class="progress-percentage-display" id="percentage-' . esc_attr($id) . '" style="font-weight: 700; font-size: 15px; color: ' . esc_attr($percentage_color) . ';">' . ($enable_fill_animation ? '0' : esc_html($percentage)) . '%</span>';
        }
        echo '</div>';
        
        // Progress bar background
        $bg_style = 'position: relative; background-color: ' . esc_attr($bg_color) . '; height: ' . esc_attr($height) . 'px; border-radius: ' . esc_attr($border_radius) . 'px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);';
        
        echo '<div class="probuilder-progress-bg" style="' . $bg_style . '">';
        
        // Progress bar fill
        $initial_width = $enable_fill_animation ? '0' : $percentage;
        $bar_fill_style = 'height: 100%; width: ' . esc_attr($initial_width) . '%; position: relative; display: flex; align-items: center; padding: 0 15px; box-sizing: border-box;';
        
        if (!$enable_fill_animation) {
            $bar_fill_style .= ' transition: width 1.5s ease;';
        }
        
        // Apply bar style
        if ($bar_style_type === 'solid') {
            $bar_fill_style .= ' background-color: ' . esc_attr($bar_color) . ';';
        } elseif ($bar_style_type === 'gradient') {
            $bar_fill_style .= ' background: ' . esc_attr($bar_gradient) . ';';
        } elseif ($bar_style_type === 'striped') {
            $bar_fill_style .= ' background: ' . esc_attr($bar_color) . ';';
            $bar_fill_style .= ' background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);';
            $bar_fill_style .= ' background-size: 20px 20px;';
        } elseif ($bar_style_type === 'animated') {
            $bar_fill_style .= ' background: ' . esc_attr($bar_color) . ';';
            $bar_fill_style .= ' background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);';
            $bar_fill_style .= ' background-size: 20px 20px;';
            $bar_fill_style .= ' animation: progress-stripes 1s linear infinite;';
        }
        
        echo '<div class="probuilder-progress-fill" id="' . esc_attr($id) . '" style="' . $bar_fill_style . '">';
        
        // Inner text
        if (!empty($inner_text)) {
            echo '<span style="font-size: 13px; font-weight: 600; color: ' . esc_attr($inner_text_color) . '; white-space: nowrap;">' . esc_html($inner_text) . '</span>';
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Add animation CSS for animated stripes
        if ($bar_style_type === 'animated') {
            echo '<style>
                @keyframes progress-stripes {
                    0% { background-position: 0 0; }
                    100% { background-position: 20px 0; }
                }
            </style>';
        }
        
        // Add filling animation JavaScript
        if ($enable_fill_animation) {
            ?>
            <script>
            (function() {
                var progressBar = document.getElementById('<?php echo esc_js($id); ?>');
                var percentageDisplay = document.getElementById('percentage-<?php echo esc_js($id); ?>');
                
                if (!progressBar) return;
                
                var targetPercentage = <?php echo esc_js($percentage); ?>;
                var duration = <?php echo esc_js($animation_duration * 1000); ?>; // Convert to ms
                var delay = <?php echo esc_js($animation_delay * 1000); ?>; // Convert to ms
                var easing = '<?php echo esc_js($animation_easing); ?>';
                
                // Wait for element to be visible
                setTimeout(function() {
                    // Set transition
                    progressBar.style.transition = 'width ' + (duration / 1000) + 's ' + easing;
                    
                    // Animate width
                    setTimeout(function() {
                        progressBar.style.width = targetPercentage + '%';
                    }, 50);
                    
                    // Animate percentage number
                    if (percentageDisplay) {
                        var startTime = Date.now();
                        var animateNumber = function() {
                            var elapsed = Date.now() - startTime;
                            var progress = Math.min(elapsed / duration, 1);
                            
                            // Apply easing
                            var easedProgress = progress;
                            if (easing === 'ease-in') {
                                easedProgress = progress * progress;
                            } else if (easing === 'ease-out') {
                                easedProgress = progress * (2 - progress);
                            } else if (easing === 'ease-in-out') {
                                easedProgress = progress < 0.5 ? 2 * progress * progress : 1 - Math.pow(-2 * progress + 2, 2) / 2;
                            }
                            
                            var currentPercentage = Math.floor(easedProgress * targetPercentage);
                            percentageDisplay.textContent = currentPercentage + '%';
                            
                            if (progress < 1) {
                                requestAnimationFrame(animateNumber);
                            } else {
                                percentageDisplay.textContent = targetPercentage + '%';
                            }
                        };
                        
                        setTimeout(animateNumber, 50);
                    }
                }, delay);
            })();
            </script>
            <?php
        }
    }
}

