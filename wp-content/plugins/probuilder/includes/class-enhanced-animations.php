<?php
/**
 * Enhanced Animations System
 * Hover, exit, and repeat animations
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Enhanced_Animations {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_footer', [$this, 'render_animation_css']);
    }
    
    /**
     * Get entrance animations
     */
    public static function get_entrance_animations() {
        return [
            'none' => __('None', 'probuilder'),
            'fadeIn' => __('Fade In', 'probuilder'),
            'fadeInDown' => __('Fade In Down', 'probuilder'),
            'fadeInUp' => __('Fade In Up', 'probuilder'),
            'fadeInLeft' => __('Fade In Left', 'probuilder'),
            'fadeInRight' => __('Fade In Right', 'probuilder'),
            'slideInDown' => __('Slide In Down', 'probuilder'),
            'slideInUp' => __('Slide In Up', 'probuilder'),
            'slideInLeft' => __('Slide In Left', 'probuilder'),
            'slideInRight' => __('Slide In Right', 'probuilder'),
            'zoomIn' => __('Zoom In', 'probuilder'),
            'zoomInDown' => __('Zoom In Down', 'probuilder'),
            'zoomInUp' => __('Zoom In Up', 'probuilder'),
            'bounceIn' => __('Bounce In', 'probuilder'),
            'bounceInDown' => __('Bounce In Down', 'probuilder'),
            'bounceInUp' => __('Bounce In Up', 'probuilder'),
            'rotateIn' => __('Rotate In', 'probuilder'),
            'flipInX' => __('Flip In X', 'probuilder'),
            'flipInY' => __('Flip In Y', 'probuilder'),
            'lightSpeedIn' => __('Light Speed In', 'probuilder'),
            'rollIn' => __('Roll In', 'probuilder'),
        ];
    }
    
    /**
     * Get hover animations
     */
    public static function get_hover_animations() {
        return [
            'none' => __('None', 'probuilder'),
            'grow' => __('Grow', 'probuilder'),
            'shrink' => __('Shrink', 'probuilder'),
            'pulse' => __('Pulse', 'probuilder'),
            'pulse-grow' => __('Pulse Grow', 'probuilder'),
            'pulse-shrink' => __('Pulse Shrink', 'probuilder'),
            'push' => __('Push', 'probuilder'),
            'pop' => __('Pop', 'probuilder'),
            'bounce-in' => __('Bounce In', 'probuilder'),
            'bounce-out' => __('Bounce Out', 'probuilder'),
            'rotate' => __('Rotate', 'probuilder'),
            'rotate-ccw' => __('Rotate Counter-Clockwise', 'probuilder'),
            'grow-rotate' => __('Grow Rotate', 'probuilder'),
            'float' => __('Float', 'probuilder'),
            'sink' => __('Sink', 'probuilder'),
            'bob' => __('Bob', 'probuilder'),
            'hang' => __('Hang', 'probuilder'),
            'skew' => __('Skew', 'probuilder'),
            'skew-forward' => __('Skew Forward', 'probuilder'),
            'skew-backward' => __('Skew Backward', 'probuilder'),
            'wobble-horizontal' => __('Wobble Horizontal', 'probuilder'),
            'wobble-vertical' => __('Wobble Vertical', 'probuilder'),
            'wobble-to-bottom-right' => __('Wobble To Bottom Right', 'probuilder'),
            'wobble-to-top-right' => __('Wobble To Top Right', 'probuilder'),
            'wobble-top' => __('Wobble Top', 'probuilder'),
            'wobble-bottom' => __('Wobble Bottom', 'probuilder'),
            'wobble-skew' => __('Wobble Skew', 'probuilder'),
            'buzz' => __('Buzz', 'probuilder'),
            'buzz-out' => __('Buzz Out', 'probuilder'),
            'forward' => __('Forward', 'probuilder'),
            'backward' => __('Backward', 'probuilder'),
        ];
    }
    
    /**
     * Get exit animations
     */
    public static function get_exit_animations() {
        return [
            'none' => __('None', 'probuilder'),
            'fadeOut' => __('Fade Out', 'probuilder'),
            'fadeOutDown' => __('Fade Out Down', 'probuilder'),
            'fadeOutUp' => __('Fade Out Up', 'probuilder'),
            'fadeOutLeft' => __('Fade Out Left', 'probuilder'),
            'fadeOutRight' => __('Fade Out Right', 'probuilder'),
            'slideOutDown' => __('Slide Out Down', 'probuilder'),
            'slideOutUp' => __('Slide Out Up', 'probuilder'),
            'slideOutLeft' => __('Slide Out Left', 'probuilder'),
            'slideOutRight' => __('Slide Out Right', 'probuilder'),
            'zoomOut' => __('Zoom Out', 'probuilder'),
            'zoomOutDown' => __('Zoom Out Down', 'probuilder'),
            'zoomOutUp' => __('Zoom Out Up', 'probuilder'),
            'bounceOut' => __('Bounce Out', 'probuilder'),
            'bounceOutDown' => __('Bounce Out Down', 'probuilder'),
            'bounceOutUp' => __('Bounce Out Up', 'probuilder'),
            'rotateOut' => __('Rotate Out', 'probuilder'),
            'flipOutX' => __('Flip Out X', 'probuilder'),
            'flipOutY' => __('Flip Out Y', 'probuilder'),
            'lightSpeedOut' => __('Light Speed Out', 'probuilder'),
            'rollOut' => __('Roll Out', 'probuilder'),
        ];
    }
    
    /**
     * Get animation controls
     */
    public static function get_controls() {
        return [
            // Entrance Animation
            'animation_entrance' => [
                'label' => __('Entrance Animation', 'probuilder'),
                'type' => 'select',
                'default' => 'none',
                'options' => self::get_entrance_animations(),
                'tab' => 'advanced'
            ],
            'animation_duration' => [
                'label' => __('Animation Duration', 'probuilder'),
                'type' => 'slider',
                'default' => 1000,
                'min' => 100,
                'max' => 5000,
                'step' => 100,
                'unit' => 'ms',
                'condition' => ['animation_entrance' => ['!=', 'none']],
                'tab' => 'advanced'
            ],
            'animation_delay' => [
                'label' => __('Animation Delay', 'probuilder'),
                'type' => 'slider',
                'default' => 0,
                'min' => 0,
                'max' => 5000,
                'step' => 100,
                'unit' => 'ms',
                'condition' => ['animation_entrance' => ['!=', 'none']],
                'tab' => 'advanced'
            ],
            
            // Hover Animation
            'animation_hover' => [
                'label' => __('Hover Animation', 'probuilder'),
                'type' => 'select',
                'default' => 'none',
                'options' => self::get_hover_animations(),
                'tab' => 'advanced'
            ],
            'animation_hover_duration' => [
                'label' => __('Hover Duration', 'probuilder'),
                'type' => 'slider',
                'default' => 300,
                'min' => 100,
                'max' => 2000,
                'step' => 100,
                'unit' => 'ms',
                'condition' => ['animation_hover' => ['!=', 'none']],
                'tab' => 'advanced'
            ],
            
            // Exit Animation
            'animation_exit' => [
                'label' => __('Exit Animation', 'probuilder'),
                'type' => 'select',
                'default' => 'none',
                'options' => self::get_exit_animations(),
                'tab' => 'advanced'
            ],
            'animation_exit_trigger' => [
                'label' => __('Exit Trigger', 'probuilder'),
                'type' => 'select',
                'default' => 'scroll_out',
                'options' => [
                    'scroll_out' => __('On Scroll Out', 'probuilder'),
                    'click' => __('On Click', 'probuilder'),
                    'timer' => __('After Timer', 'probuilder'),
                ],
                'condition' => ['animation_exit' => ['!=', 'none']],
                'tab' => 'advanced'
            ],
            
            // Repeat
            'animation_repeat' => [
                'label' => __('Repeat Animation', 'probuilder'),
                'type' => 'switcher',
                'default' => false,
                'condition' => ['animation_entrance' => ['!=', 'none']],
                'tab' => 'advanced'
            ],
            'animation_repeat_count' => [
                'label' => __('Repeat Count', 'probuilder'),
                'type' => 'select',
                'default' => 'infinite',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '5' => '5',
                    '10' => '10',
                    'infinite' => __('Infinite', 'probuilder'),
                ],
                'condition' => [
                    'animation_entrance' => ['!=', 'none'],
                    'animation_repeat' => true
                ],
                'tab' => 'advanced'
            ],
            'animation_repeat_delay' => [
                'label' => __('Delay Between Repeats', 'probuilder'),
                'type' => 'slider',
                'default' => 1000,
                'min' => 0,
                'max' => 10000,
                'step' => 100,
                'unit' => 'ms',
                'condition' => [
                    'animation_entrance' => ['!=', 'none'],
                    'animation_repeat' => true
                ],
                'tab' => 'advanced'
            ],
        ];
    }
    
    /**
     * Render animation CSS
     */
    public function render_animation_css() {
        ?>
        <style id="probuilder-animations">
        /* Hover Animations */
        .pb-hover-grow { transition: all 0.3s ease; }
        .pb-hover-grow:hover { transform: scale(1.1); }
        
        .pb-hover-shrink { transition: all 0.3s ease; }
        .pb-hover-shrink:hover { transform: scale(0.9); }
        
        .pb-hover-pulse { transition: all 0.3s ease; }
        .pb-hover-pulse:hover { animation: pb-pulse 1s infinite; }
        
        .pb-hover-rotate { transition: all 0.3s ease; }
        .pb-hover-rotate:hover { transform: rotate(4deg); }
        
        .pb-hover-rotate-ccw { transition: all 0.3s ease; }
        .pb-hover-rotate-ccw:hover { transform: rotate(-4deg); }
        
        .pb-hover-float { transition: all 0.3s ease; }
        .pb-hover-float:hover { transform: translateY(-10px); }
        
        .pb-hover-sink { transition: all 0.3s ease; }
        .pb-hover-sink:hover { transform: translateY(10px); }
        
        .pb-hover-buzz { transition: all 0.3s ease; }
        .pb-hover-buzz:hover { animation: pb-buzz 0.15s linear infinite; }
        
        @keyframes pb-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes pb-buzz {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        
        /* Entrance Animations */
        .pb-animated { animation-fill-mode: both; }
        
        /* Exit on scroll support */
        .pb-exit-animation { opacity: 1; }
        .pb-exit-animation.exiting { animation-fill-mode: forwards; }
        </style>
        <?php
    }
    
    /**
     * Apply animations to element
     */
    public static function apply($settings) {
        $classes = [];
        $styles = [];
        $data_attrs = [];
        
        // Entrance animation
        if (isset($settings['animation_entrance']) && $settings['animation_entrance'] !== 'none') {
            $classes[] = 'pb-animated';
            $classes[] = 'animate__' . $settings['animation_entrance'];
            
            if (isset($settings['animation_duration'])) {
                $styles[] = 'animation-duration: ' . $settings['animation_duration'] . 'ms';
            }
            
            if (isset($settings['animation_delay'])) {
                $styles[] = 'animation-delay: ' . $settings['animation_delay'] . 'ms';
            }
            
            // Repeat
            if (isset($settings['animation_repeat']) && $settings['animation_repeat']) {
                $count = $settings['animation_repeat_count'] ?? 'infinite';
                $styles[] = 'animation-iteration-count: ' . $count;
                
                if (isset($settings['animation_repeat_delay'])) {
                    // This requires JS handling for delay between iterations
                    $data_attrs['repeat-delay'] = $settings['animation_repeat_delay'];
                }
            }
        }
        
        // Hover animation
        if (isset($settings['animation_hover']) && $settings['animation_hover'] !== 'none') {
            $classes[] = 'pb-hover-' . $settings['animation_hover'];
        }
        
        // Exit animation
        if (isset($settings['animation_exit']) && $settings['animation_exit'] !== 'none') {
            $classes[] = 'pb-exit-animation';
            $data_attrs['exit-animation'] = $settings['animation_exit'];
            $data_attrs['exit-trigger'] = $settings['animation_exit_trigger'] ?? 'scroll_out';
        }
        
        $output = '';
        
        if (!empty($classes)) {
            $output .= ' class="' . esc_attr(implode(' ', $classes)) . '"';
        }
        
        if (!empty($styles)) {
            $output .= ' style="' . esc_attr(implode('; ', $styles)) . '"';
        }
        
        foreach ($data_attrs as $key => $value) {
            $output .= ' data-' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
        
        return $output;
    }
}

