<?php
/**
 * Motion Effects System
 * Parallax, mouse tracking, scroll effects
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Motion_Effects {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_footer', [$this, 'render_motion_js']);
    }
    
    /**
     * Enqueue motion effects scripts
     */
    public function enqueue_scripts() {
        if (!$this->has_motion_effects()) {
            return;
        }
        
        wp_enqueue_script('probuilder-motion', PROBUILDER_URL . 'assets/js/motion-effects.js', ['jquery'], PROBUILDER_VERSION, true);
    }
    
    /**
     * Check if page has motion effects
     */
    private function has_motion_effects() {
        if (!is_singular()) {
            return false;
        }
        
        $data = get_post_meta(get_the_ID(), '_probuilder_data', true);
        
        if (!$data || !is_array($data)) {
            return false;
        }
        
        foreach ($data as $element) {
            if (isset($element['settings']['motion_effects_enabled'])) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get motion effect controls
     */
    public static function get_controls() {
        return [
            'motion_effects_enabled' => [
                'label' => __('Enable Motion Effects', 'probuilder'),
                'type' => 'switcher',
                'default' => false,
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling' => [
                'label' => __('Scrolling Effects', 'probuilder'),
                'type' => 'section',
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling_translate_y' => [
                'label' => __('Vertical Scroll', 'probuilder'),
                'type' => 'slider',
                'default' => 0,
                'min' => -200,
                'max' => 200,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling_translate_x' => [
                'label' => __('Horizontal Scroll', 'probuilder'),
                'type' => 'slider',
                'default' => 0,
                'min' => -200,
                'max' => 200,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling_opacity' => [
                'label' => __('Transparency', 'probuilder'),
                'type' => 'slider',
                'default' => 0,
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling_blur' => [
                'label' => __('Blur', 'probuilder'),
                'type' => 'slider',
                'default' => 0,
                'min' => 0,
                'max' => 15,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling_rotate' => [
                'label' => __('Rotate', 'probuilder'),
                'type' => 'slider',
                'default' => 0,
                'min' => -360,
                'max' => 360,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_scrolling_scale' => [
                'label' => __('Scale', 'probuilder'),
                'type' => 'slider',
                'default' => 1,
                'min' => 0,
                'max' => 2,
                'step' => 0.1,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_mouse' => [
                'label' => __('Mouse Effects', 'probuilder'),
                'type' => 'section',
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_mouse_track' => [
                'label' => __('Mouse Track', 'probuilder'),
                'type' => 'switcher',
                'default' => false,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
            'motion_fx_mouse_direction' => [
                'label' => __('Direction', 'probuilder'),
                'type' => 'select',
                'default' => 'opposite',
                'options' => [
                    'opposite' => __('Opposite', 'probuilder'),
                    'direct' => __('Direct', 'probuilder'),
                ],
                'condition' => [
                    'motion_effects_enabled' => true,
                    'motion_fx_mouse_track' => true
                ],
                'tab' => 'advanced'
            ],
            'motion_fx_mouse_speed' => [
                'label' => __('Speed', 'probuilder'),
                'type' => 'slider',
                'default' => 1,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'condition' => [
                    'motion_effects_enabled' => true,
                    'motion_fx_mouse_track' => true
                ],
                'tab' => 'advanced'
            ],
            'motion_fx_mouse_tilt' => [
                'label' => __('3D Tilt', 'probuilder'),
                'type' => 'switcher',
                'default' => false,
                'condition' => ['motion_effects_enabled' => true],
                'tab' => 'advanced'
            ],
        ];
    }
    
    /**
     * Render motion effects JavaScript
     */
    public function render_motion_js() {
        if (!$this->has_motion_effects()) {
            return;
        }
        
        ?>
        <script>
        (function($) {
            'use strict';
            
            // Parallax scrolling effects
            function initScrollEffects() {
                const elements = document.querySelectorAll('[data-motion-fx="scroll"]');
                
                if (elements.length === 0) return;
                
                function updateScroll() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const windowHeight = window.innerHeight;
                    
                    elements.forEach(element => {
                        const rect = element.getBoundingClientRect();
                        const elementTop = rect.top + scrollTop;
                        const elementHeight = element.offsetHeight;
                        
                        // Calculate progress (0-1)
                        const progress = Math.max(0, Math.min(1, 
                            (scrollTop + windowHeight - elementTop) / (windowHeight + elementHeight)
                        ));
                        
                        // Get settings
                        const settings = JSON.parse(element.getAttribute('data-motion-settings') || '{}');
                        
                        let transform = '';
                        let filters = '';
                        let opacity = 1;
                        
                        // Translate Y
                        if (settings.translateY) {
                            const y = settings.translateY * progress;
                            transform += `translateY(${y}px) `;
                        }
                        
                        // Translate X
                        if (settings.translateX) {
                            const x = settings.translateX * progress;
                            transform += `translateX(${x}px) `;
                        }
                        
                        // Rotate
                        if (settings.rotate) {
                            const rotation = settings.rotate * progress;
                            transform += `rotate(${rotation}deg) `;
                        }
                        
                        // Scale
                        if (settings.scale && settings.scale !== 1) {
                            const scale = 1 + (settings.scale - 1) * progress;
                            transform += `scale(${scale}) `;
                        }
                        
                        // Opacity
                        if (settings.opacity) {
                            opacity = 1 - settings.opacity * progress;
                        }
                        
                        // Blur
                        if (settings.blur) {
                            const blur = settings.blur * progress;
                            filters += `blur(${blur}px) `;
                        }
                        
                        // Apply
                        element.style.transform = transform;
                        element.style.filter = filters;
                        element.style.opacity = opacity;
                    });
                }
                
                window.addEventListener('scroll', updateScroll, { passive: true });
                updateScroll();
            }
            
            // Mouse tracking effects
            function initMouseEffects() {
                const elements = document.querySelectorAll('[data-motion-fx="mouse"]');
                
                if (elements.length === 0) return;
                
                document.addEventListener('mousemove', function(e) {
                    const mouseX = e.clientX;
                    const mouseY = e.clientY;
                    const centerX = window.innerWidth / 2;
                    const centerY = window.innerHeight / 2;
                    
                    elements.forEach(element => {
                        const settings = JSON.parse(element.getAttribute('data-motion-settings') || '{}');
                        const speed = settings.speed || 1;
                        const direction = settings.direction === 'direct' ? 1 : -1;
                        
                        const deltaX = (mouseX - centerX) / centerX * 50 * speed * direction;
                        const deltaY = (mouseY - centerY) / centerY * 50 * speed * direction;
                        
                        let transform = `translate(${deltaX}px, ${deltaY}px)`;
                        
                        // 3D Tilt
                        if (settings.tilt) {
                            const tiltX = (mouseY - centerY) / centerY * 10 * direction;
                            const tiltY = (mouseX - centerX) / centerX * 10 * -direction;
                            transform += ` perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
                        }
                        
                        element.style.transform = transform;
                        element.style.transition = 'transform 0.1s ease-out';
                    });
                });
            }
            
            // Initialize on page load
            $(document).ready(function() {
                initScrollEffects();
                initMouseEffects();
            });
            
        })(jQuery);
        </script>
        <?php
    }
    
    /**
     * Apply motion effects to element
     */
    public static function apply($element_id, $settings) {
        if (!isset($settings['motion_effects_enabled']) || !$settings['motion_effects_enabled']) {
            return '';
        }
        
        $data = [
            'translateY' => $settings['motion_fx_scrolling_translate_y'] ?? 0,
            'translateX' => $settings['motion_fx_scrolling_translate_x'] ?? 0,
            'opacity' => $settings['motion_fx_scrolling_opacity'] ?? 0,
            'blur' => $settings['motion_fx_scrolling_blur'] ?? 0,
            'rotate' => $settings['motion_fx_scrolling_rotate'] ?? 0,
            'scale' => $settings['motion_fx_scrolling_scale'] ?? 1,
            'speed' => $settings['motion_fx_mouse_speed'] ?? 1,
            'direction' => $settings['motion_fx_mouse_direction'] ?? 'opposite',
            'tilt' => $settings['motion_fx_mouse_tilt'] ?? false,
        ];
        
        $type = ($settings['motion_fx_mouse_track'] ?? false) ? 'mouse' : 'scroll';
        
        return sprintf(
            'data-motion-fx="%s" data-motion-settings=\'%s\'',
            esc_attr($type),
            esc_attr(json_encode($data))
        );
    }
}

