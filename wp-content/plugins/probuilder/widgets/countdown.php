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
        $this->start_controls_section('section_countdown', [
            'label' => __('Countdown', 'probuilder'),
        ]);
        
        $this->add_control('target_date', [
            'label' => __('Target Date', 'probuilder'),
            'type' => 'text',
            'default' => date('Y-m-d H:i:s', strtotime('+30 days')),
        ]);
        
        $this->add_control('show_labels', [
            'label' => __('Show Labels', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('digit_color', [
            'label' => __('Digit Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('label_color', [
            'label' => __('Label Color', 'probuilder'),
            'type' => 'color',
            'default' => '#999999',
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
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $target_date = $this->get_settings('target_date', date('Y-m-d H:i:s', strtotime('+30 days')));
        $show_labels = $this->get_settings('show_labels', 'yes');
        $digit_color = $this->get_settings('digit_color', '#333333');
        $label_color = $this->get_settings('label_color', '#999999');
        $align = $this->get_settings('align', 'center');
        
        $id = 'countdown-' . uniqid();
        
        $wrapper_style = 'text-align: ' . esc_attr($align) . '; display: flex; justify-content: ' . esc_attr($align) . '; gap: 20px; flex-wrap: wrap;';
        $box_style = 'text-align: center; min-width: 80px;';
        $digit_style = 'font-size: 48px; font-weight: bold; color: ' . esc_attr($digit_color) . '; line-height: 1;';
        $label_style = 'font-size: 14px; color: ' . esc_attr($label_color) . '; margin-top: 5px;';
        
        echo '<div class="probuilder-countdown" id="' . esc_attr($id) . '" data-target="' . esc_attr($target_date) . '" style="' . $wrapper_style . '">';
        
        echo '<div class="countdown-box" style="' . $box_style . '">';
        echo '<div class="countdown-days" style="' . $digit_style . '">00</div>';
        if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Days', 'probuilder') . '</div>';
        echo '</div>';
        
        echo '<div class="countdown-box" style="' . $box_style . '">';
        echo '<div class="countdown-hours" style="' . $digit_style . '">00</div>';
        if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Hours', 'probuilder') . '</div>';
        echo '</div>';
        
        echo '<div class="countdown-box" style="' . $box_style . '">';
        echo '<div class="countdown-minutes" style="' . $digit_style . '">00</div>';
        if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Minutes', 'probuilder') . '</div>';
        echo '</div>';
        
        echo '<div class="countdown-box" style="' . $box_style . '">';
        echo '<div class="countdown-seconds" style="' . $digit_style . '">00</div>';
        if ($show_labels === 'yes') echo '<div style="' . $label_style . '">' . __('Seconds', 'probuilder') . '</div>';
        echo '</div>';
        
        echo '</div>';
    }
}

