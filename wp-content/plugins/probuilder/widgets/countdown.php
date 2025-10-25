<?php
/**
 * Countdown Timer Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Countdown extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'countdown';
        $this->title = __('Countdown Timer', 'probuilder');
        $this->icon = 'fa fa-clock';
        $this->category = 'content';
        $this->keywords = ['countdown', 'timer', 'clock'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_countdown', [
            'label' => __('Countdown', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('target_date', [
            'label' => __('Target Date', 'probuilder'),
            'type' => 'text',
            'default' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'description' => __('Format: YYYY-MM-DD HH:MM:SS (e.g., 2025-12-31 23:59:59)', 'probuilder'),
        ]);
        
        $this->add_control('show_days', [
            'label' => __('Show Days', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_hours', [
            'label' => __('Show Hours', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_minutes', [
            'label' => __('Show Minutes', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_seconds', [
            'label' => __('Show Seconds', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_labels', [
            'label' => __('Show Labels', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('expire_message', [
            'label' => __('Expiry Message', 'probuilder'),
            'type' => 'text',
            'default' => __('The countdown has ended!', 'probuilder'),
            'description' => __('Message to show when countdown ends', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout Style', 'probuilder'),
            'type' => 'select',
            'default' => 'boxes',
            'options' => [
                'boxes' => __('Boxes', 'probuilder'),
                'inline' => __('Inline', 'probuilder'),
                'circles' => __('Circles', 'probuilder'),
            ],
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'center',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('digit_size', [
            'label' => __('Digit Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 48,
            'range' => [
                'px' => [
                    'min' => 24,
                    'max' => 100,
                    'step' => 2
                ]
            ],
        ]);
        
        $this->add_control('label_size', [
            'label' => __('Label Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 14,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 24,
                    'step' => 1
                ]
            ],
        ]);
        
        $this->add_control('digit_color', [
            'label' => __('Digit Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('label_color', [
            'label' => __('Label Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('box_bg_color', [
            'label' => __('Box Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('box_border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ],
        ]);
        
        $this->add_control('separator_show', [
            'label' => __('Show Separator', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('separator_text', [
            'label' => __('Separator Text', 'probuilder'),
            'type' => 'text',
            'default' => ':',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $target_date = $this->get_settings('target_date', date('Y-m-d H:i:s', strtotime('+30 days')));
        $show_days = $this->get_settings('show_days', 'yes');
        $show_hours = $this->get_settings('show_hours', 'yes');
        $show_minutes = $this->get_settings('show_minutes', 'yes');
        $show_seconds = $this->get_settings('show_seconds', 'yes');
        $show_labels = $this->get_settings('show_labels', 'yes');
        $expire_message = $this->get_settings('expire_message', 'The countdown has ended!');
        
        // Style settings
        $layout = $this->get_settings('layout', 'boxes');
        $align = $this->get_settings('align', 'center');
        $digit_size = $this->get_settings('digit_size', 48);
        $label_size = $this->get_settings('label_size', 14);
        $digit_color = $this->get_settings('digit_color', '#ffffff');
        $label_color = $this->get_settings('label_color', '#ffffff');
        $box_bg_color = $this->get_settings('box_bg_color', '#92003b');
        $box_border_radius = $this->get_settings('box_border_radius', 8);
        $separator_show = $this->get_settings('separator_show', 'no');
        $separator_text = $this->get_settings('separator_text', ':');
        
        $id = 'countdown-' . uniqid();
        
        // Alignment mapping
        $justify_map = [
            'left' => 'flex-start',
            'center' => 'center',
            'right' => 'flex-end'
        ];
        
        // Wrapper style
        $wrapper_style = 'display: flex; justify-content: ' . esc_attr($justify_map[$align]) . '; align-items: center; gap: 15px; flex-wrap: wrap;';
        
        // Box style based on layout
        if ($layout === 'boxes') {
            $box_style = 'background: ' . esc_attr($box_bg_color) . '; padding: 20px 15px; text-align: center; min-width: 90px; border-radius: ' . esc_attr($box_border_radius) . 'px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
        } elseif ($layout === 'circles') {
            $circle_size = max($digit_size + 40, 100);
            $box_style = 'background: ' . esc_attr($box_bg_color) . '; width: ' . $circle_size . 'px; height: ' . $circle_size . 'px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1);';
        } else {
            // Inline
            $box_style = 'text-align: center;';
        }
        
        $digit_style = 'font-size: ' . esc_attr($digit_size) . 'px; font-weight: bold; color: ' . esc_attr($digit_color) . '; line-height: 1;';
        $label_style = 'font-size: ' . esc_attr($label_size) . 'px; color: ' . esc_attr($label_color) . '; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;';
        $separator_style = 'font-size: ' . esc_attr($digit_size) . 'px; font-weight: bold; color: ' . esc_attr($digit_color) . ';';
        
        echo '<div class="probuilder-countdown" id="' . esc_attr($id) . '" data-target="' . esc_attr($target_date) . '" data-expire-message="' . esc_attr($expire_message) . '" style="' . $wrapper_style . '">';
        
        $first_item = true;
        
        // Days
        if ($show_days === 'yes') {
            if (!$first_item && $separator_show === 'yes' && $layout === 'inline') {
                echo '<div class="countdown-separator" style="' . $separator_style . '">' . esc_html($separator_text) . '</div>';
            }
            echo '<div class="countdown-box countdown-days-box" style="' . $box_style . '">';
            echo '<div class="countdown-days" style="' . $digit_style . '">00</div>';
            if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Days', 'probuilder') . '</div>';
            echo '</div>';
            $first_item = false;
        }
        
        // Hours
        if ($show_hours === 'yes') {
            if (!$first_item && $separator_show === 'yes' && $layout === 'inline') {
                echo '<div class="countdown-separator" style="' . $separator_style . '">' . esc_html($separator_text) . '</div>';
            }
            echo '<div class="countdown-box countdown-hours-box" style="' . $box_style . '">';
            echo '<div class="countdown-hours" style="' . $digit_style . '">00</div>';
            if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Hours', 'probuilder') . '</div>';
            echo '</div>';
            $first_item = false;
        }
        
        // Minutes
        if ($show_minutes === 'yes') {
            if (!$first_item && $separator_show === 'yes' && $layout === 'inline') {
                echo '<div class="countdown-separator" style="' . $separator_style . '">' . esc_html($separator_text) . '</div>';
            }
            echo '<div class="countdown-box countdown-minutes-box" style="' . $box_style . '">';
            echo '<div class="countdown-minutes" style="' . $digit_style . '">00</div>';
            if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Minutes', 'probuilder') . '</div>';
            echo '</div>';
            $first_item = false;
        }
        
        // Seconds
        if ($show_seconds === 'yes') {
            if (!$first_item && $separator_show === 'yes' && $layout === 'inline') {
                echo '<div class="countdown-separator" style="' . $separator_style . '">' . esc_html($separator_text) . '</div>';
            }
            echo '<div class="countdown-box countdown-seconds-box" style="' . $box_style . '">';
            echo '<div class="countdown-seconds" style="' . $digit_style . '">00</div>';
            if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Seconds', 'probuilder') . '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // JavaScript to handle countdown
        ?>
        <script>
        (function() {
            var countdown = document.getElementById('<?php echo esc_js($id); ?>');
            if (!countdown) return;
            
            var targetDate = new Date(countdown.getAttribute('data-target')).getTime();
            var expireMessage = countdown.getAttribute('data-expire-message');
            
            function updateCountdown() {
                var now = new Date().getTime();
                var distance = targetDate - now;
                
                if (distance < 0) {
                    countdown.innerHTML = '<div style="text-align: center; font-size: 24px; font-weight: 600; color: <?php echo esc_js($digit_color); ?>; padding: 20px;">' + expireMessage + '</div>';
                    return;
                }
                
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                var daysEl = countdown.querySelector('.countdown-days');
                var hoursEl = countdown.querySelector('.countdown-hours');
                var minutesEl = countdown.querySelector('.countdown-minutes');
                var secondsEl = countdown.querySelector('.countdown-seconds');
                
                if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
                if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
        </script>
        <?php
    }
}

