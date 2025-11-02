<?php
/**
 * Base Widget Class
 */

if (!defined('ABSPATH')) {
    exit;
}

abstract class ProBuilder_Base_Widget {
    
    protected $name;
    protected $title;
    protected $icon;
    protected $category;
    protected $keywords = [];
    
    /**
     * Get widget name
     */
    public function get_name() {
        return $this->name;
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return $this->title;
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return $this->icon;
    }
    
    /**
     * Get widget category
     */
    public function get_category() {
        return $this->category;
    }
    
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return $this->keywords;
    }
    
    /**
     * Get widget controls/settings
     */
    abstract protected function register_controls();
    
    /**
     * Render widget output
     */
    abstract protected function render();
    
    /**
     * Get controls
     */
    public function get_controls() {
        $this->register_controls();
        $this->register_common_style_controls();
        return $this->controls;
    }
    
    /**
     * Register common style controls (margin & padding)
     * Automatically added to all widgets in Style tab
     */
    protected function register_common_style_controls() {
        // Check if widget already has margin/padding controls (avoid duplicates)
        $hasPadding = isset($this->controls['padding']);
        $hasMargin = isset($this->controls['margin']);
        
        // Only add if widget doesn't already have these controls
        if (!$hasPadding || !$hasMargin) {
            // Start spacing section in Style tab
            $this->start_controls_section('section_spacing', [
                'label' => __('Spacing', 'probuilder'),
                'tab' => 'style'
            ]);
            
            // Padding - dimensions control (grouped box model)
            if (!$hasPadding) {
                $this->add_control('padding', [
                    'label' => __('Padding', 'probuilder'),
                    'type' => 'dimensions',
                    'default' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'],
                    'tab' => 'style',
                ]);
            }
            
            // Margin - dimensions control (grouped box model)
            if (!$hasMargin) {
                $this->add_control('margin', [
                    'label' => __('Margin', 'probuilder'),
                    'type' => 'dimensions',
                    'default' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'],
                    'tab' => 'style',
                ]);
            }
            
            $this->end_controls_section();
        }
        
        // Add Advanced Options Section in Style tab
        $this->start_controls_section('section_advanced_options', [
            'label' => __('Advanced Options', 'probuilder'),
            'tab' => 'style'
        ]);
        
        // Opacity (0-100%)
        $this->add_control('opacity', [
            'label' => __('Opacity', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5]
            ],
            'unit' => '%',
            'tab' => 'style',
        ]);
        
        // Z-Index
        $this->add_control('z_index', [
            'label' => __('Z-Index', 'probuilder'),
            'type' => 'number',
            'default' => '',
            'placeholder' => 'auto',
            'tab' => 'style',
        ]);
        
        // CSS Classes
        $this->add_control('css_classes', [
            'label' => __('CSS Classes', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => 'my-class another-class',
            'description' => __('Add custom CSS classes separated by space', 'probuilder'),
            'tab' => 'style',
        ]);
        
        // CSS ID
        $this->add_control('css_id', [
            'label' => __('CSS ID', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'placeholder' => 'my-element-id',
            'description' => __('Add a unique CSS ID for this element', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Add Responsive Visibility Section in Style tab (compact)
        $this->start_controls_section('section_responsive', [
            'label' => __('Responsive', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('responsive_description', [
            'type' => 'raw_html',
            'raw' => '<div style="margin-bottom: 10px; color: #666; font-size: 12px;">Hide element on specific devices:</div>',
        ]);
        
        $this->add_control('hide_desktop', [
            'label' => __('Desktop', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'label_on' => __('Hide', 'probuilder'),
            'label_off' => __('Show', 'probuilder'),
        ]);
        
        $this->add_control('hide_tablet', [
            'label' => __('Tablet', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'label_on' => __('Hide', 'probuilder'),
            'label_off' => __('Show', 'probuilder'),
        ]);
        
        $this->add_control('hide_mobile', [
            'label' => __('Mobile', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'label_on' => __('Hide', 'probuilder'),
            'label_off' => __('Show', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Add Background Section in Style tab
        $this->start_controls_section('section_background', [
            'label' => __('Background', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('background_type', [
            'label' => __('Background Type', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'color' => __('Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'image' => __('Image', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('background_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '',
            'tab' => 'style',
        ]);
        
        $this->add_control('background_gradient_start', [
            'label' => __('Gradient Start', 'probuilder'),
            'type' => 'color',
            'default' => '#667eea',
            'tab' => 'style',
        ]);
        
        $this->add_control('background_gradient_end', [
            'label' => __('Gradient End', 'probuilder'),
            'type' => 'color',
            'default' => '#764ba2',
            'tab' => 'style',
        ]);
        
        $this->add_control('background_gradient_angle', [
            'label' => __('Gradient Angle', 'probuilder'),
            'type' => 'slider',
            'default' => 135,
            'range' => [
                'min' => 0,
                'max' => 360,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('background_image', [
            'label' => __('Background Image', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => ''],
            'tab' => 'style',
        ]);
        
        $this->add_control('background_size', [
            'label' => __('Background Size', 'probuilder'),
            'type' => 'select',
            'default' => 'cover',
            'options' => [
                'auto' => __('Auto', 'probuilder'),
                'cover' => __('Cover', 'probuilder'),
                'contain' => __('Contain', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('background_position', [
            'label' => __('Background Position', 'probuilder'),
            'type' => 'select',
            'default' => 'center center',
            'options' => [
                'top left' => __('Top Left', 'probuilder'),
                'top center' => __('Top Center', 'probuilder'),
                'top right' => __('Top Right', 'probuilder'),
                'center left' => __('Center Left', 'probuilder'),
                'center center' => __('Center Center', 'probuilder'),
                'center right' => __('Center Right', 'probuilder'),
                'bottom left' => __('Bottom Left', 'probuilder'),
                'bottom center' => __('Bottom Center', 'probuilder'),
                'bottom right' => __('Bottom Right', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('background_repeat', [
            'label' => __('Background Repeat', 'probuilder'),
            'type' => 'select',
            'default' => 'no-repeat',
            'options' => [
                'no-repeat' => __('No Repeat', 'probuilder'),
                'repeat' => __('Repeat', 'probuilder'),
                'repeat-x' => __('Repeat X', 'probuilder'),
                'repeat-y' => __('Repeat Y', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Add Border Section in Style tab
        $this->start_controls_section('section_border', [
            'label' => __('Border', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('border_style', [
            'label' => __('Border Style', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'solid' => __('Solid', 'probuilder'),
                'dashed' => __('Dashed', 'probuilder'),
                'dotted' => __('Dotted', 'probuilder'),
                'double' => __('Double', 'probuilder'),
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('border_width', [
            'label' => __('Border Width', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1'],
            'tab' => 'style',
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#000000',
            'tab' => 'style',
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0'],
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_enable', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_h', [
            'label' => __('Horizontal (px)', 'probuilder'),
            'type' => 'number',
            'default' => 0,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_v', [
            'label' => __('Vertical (px)', 'probuilder'),
            'type' => 'number',
            'default' => 5,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_blur', [
            'label' => __('Blur (px)', 'probuilder'),
            'type' => 'number',
            'default' => 15,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_spread', [
            'label' => __('Spread (px)', 'probuilder'),
            'type' => 'number',
            'default' => 0,
            'tab' => 'style',
        ]);
        
        $this->add_control('box_shadow_color', [
            'label' => __('Shadow Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.2)',
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Add Transform Section in Style tab
        $this->start_controls_section('section_transform', [
            'label' => __('Transform', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('rotate', [
            'label' => __('Rotate (deg)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => -180,
                'max' => 180,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('scale', [
            'label' => __('Scale', 'probuilder'),
            'type' => 'slider',
            'default' => 100,
            'range' => [
                'px' => ['min' => 10, 'max' => 200, 'step' => 5]
            ],
            'unit' => '%',
            'tab' => 'style',
        ]);
        
        $this->add_control('skew_x', [
            'label' => __('Skew X (deg)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => -45,
                'max' => 45,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->add_control('skew_y', [
            'label' => __('Skew Y (deg)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'min' => -45,
                'max' => 45,
                'step' => 1
            ],
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
        
        // Add Custom CSS Section in Style tab
        $this->start_controls_section('section_custom_css', [
            'label' => __('Custom CSS', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('custom_css', [
            'label' => __('Custom CSS', 'probuilder'),
            'type' => 'code',
            'default' => '',
            'placeholder' => 'selector { property: value; }',
            'description' => __('Add custom CSS for this element', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->end_controls_section();
    }
    
    protected $controls = [];
    protected $current_section_tab = 'content'; // Track current tab for controls
    
    /**
     * Add control
     */
    protected function add_control($id, $args) {
        // Auto-assign tab based on current section
        if (!isset($args['tab'])) {
            $args['tab'] = $this->current_section_tab;
        }
        error_log("ProBuilder: Control '$id' assigned to tab '{$args['tab']}'");
        $this->controls[$id] = $args;
    }
    
    /**
     * Start controls section
     */
    protected function start_controls_section($id, $args) {
        // Determine tab from section name or args
        if (isset($args['tab'])) {
            $this->current_section_tab = $args['tab'];
        } else {
            // Auto-detect from section name (case insensitive)
            $id_lower = strtolower($id);
            if (strpos($id_lower, 'content') !== false) {
                $this->current_section_tab = 'content';
            } elseif (strpos($id_lower, 'style') !== false || strpos($id_lower, 'typography') !== false) {
                $this->current_section_tab = 'style';
            } else {
                $this->current_section_tab = 'content'; // Default
            }
        }
        
        error_log("ProBuilder: Section '$id' assigned to tab '{$this->current_section_tab}'");
        
        $args['tab'] = $this->current_section_tab;
        $this->controls[$id] = array_merge($args, ['type' => 'section_start']);
    }
    
    /**
     * End controls section
     */
    protected function end_controls_section() {
        // Reset to content tab
        $this->current_section_tab = 'content';
    }
    
    /**
     * Convenience method: Start style tab
     * Shorthand for starting a style controls section
     */
    protected function start_style_tab($label = null) {
        if ($label === null) {
            $label = __('Style', 'probuilder');
        }
        $this->start_controls_section('section_style_' . uniqid(), [
            'label' => $label,
            'tab' => 'style'
        ]);
    }
    
    /**
     * Convenience method: End style tab
     * Alias for end_controls_section()
     */
    protected function end_style_tab() {
        $this->end_controls_section();
    }
    
    /**
     * Convenience method: Start advanced tab
     */
    protected function start_advanced_tab($label = null) {
        if ($label === null) {
            $label = __('Advanced', 'probuilder');
        }
        $this->start_controls_section('section_style_' . uniqid(), [
            'label' => $label,
            'tab' => 'style'
        ]);
    }
    
    /**
     * Convenience method: End advanced tab
     */
    protected function end_advanced_tab() {
        $this->end_controls_section();
    }
    
    /**
     * Render widget
     */
    public function render_widget($settings = []) {
        $this->settings = $settings;
        $this->render();
    }
    
    protected $settings = [];
    
    /**
     * Get setting
     */
    protected function get_settings($key = null, $default = null) {
        if ($key === null) {
            return $this->settings;
        }
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
    
    /**
     * Get wrapper classes including custom CSS classes and responsive visibility
     */
    protected function get_wrapper_classes() {
        $classes = ['probuilder-widget', 'probuilder-widget-' . $this->name];
        
        // Add custom CSS classes
        $custom_classes = $this->get_settings('css_classes', '');
        if (!empty($custom_classes)) {
            $classes[] = esc_attr($custom_classes);
        }
        
        // Add responsive visibility classes
        if ($this->get_settings('hide_desktop') === 'yes') {
            $classes[] = 'probuilder-hide-desktop';
        }
        if ($this->get_settings('hide_tablet') === 'yes') {
            $classes[] = 'probuilder-hide-tablet';
        }
        if ($this->get_settings('hide_mobile') === 'yes') {
            $classes[] = 'probuilder-hide-mobile';
        }
        
        return implode(' ', $classes);
    }
    
    /**
     * Get wrapper attributes including ID and inline styles
     */
    protected function get_wrapper_attributes() {
        $attrs = [];
        
        // Add CSS ID
        $css_id = $this->get_settings('css_id', '');
        if (!empty($css_id)) {
            $attrs[] = 'id="' . esc_attr($css_id) . '"';
        }
        
        // Add inline styles
        $styles = $this->get_inline_styles();
        if (!empty($styles)) {
            $attrs[] = 'style="' . esc_attr($styles) . '"';
        }
        
        return implode(' ', $attrs);
    }
    
    /**
     * Get inline styles from common controls (opacity, z-index, margin, padding, background, border, transform)
     */
    protected function get_inline_styles() {
        $styles = [];
        
        // Background
        $bg_type = $this->get_settings('background_type', 'none');
        if ($bg_type !== 'none') {
            if ($bg_type === 'color') {
                $bg_color = $this->get_settings('background_color');
                if (!empty($bg_color)) {
                    $styles[] = 'background-color: ' . esc_attr($bg_color);
                }
            } elseif ($bg_type === 'gradient') {
                $start = $this->get_settings('background_gradient_start', '#667eea');
                $end = $this->get_settings('background_gradient_end', '#764ba2');
                $angle = $this->get_settings('background_gradient_angle', 135);
                $styles[] = 'background: linear-gradient(' . intval($angle) . 'deg, ' . esc_attr($start) . ', ' . esc_attr($end) . ')';
            } elseif ($bg_type === 'image') {
                $bg_image = $this->get_settings('background_image');
                if (!empty($bg_image['url'])) {
                    $styles[] = 'background-image: url(' . esc_url($bg_image['url']) . ')';
                    $styles[] = 'background-size: ' . esc_attr($this->get_settings('background_size', 'cover'));
                    $styles[] = 'background-position: ' . esc_attr($this->get_settings('background_position', 'center center'));
                    $styles[] = 'background-repeat: ' . esc_attr($this->get_settings('background_repeat', 'no-repeat'));
                }
            }
        }
        
        // Border
        $border_style = $this->get_settings('border_style', 'none');
        if ($border_style !== 'none') {
            $border_width = $this->get_settings('border_width', ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1']);
            $border_color = $this->get_settings('border_color', '#000000');
            
            if (is_array($border_width)) {
                $styles[] = 'border-style: ' . esc_attr($border_style);
                $styles[] = 'border-width: ' . esc_attr($border_width['top']) . 'px ' . esc_attr($border_width['right']) . 'px ' . esc_attr($border_width['bottom']) . 'px ' . esc_attr($border_width['left']) . 'px';
                $styles[] = 'border-color: ' . esc_attr($border_color);
            }
        }
        
        // Border Radius
        $border_radius = $this->get_settings('border_radius');
        if (!empty($border_radius) && is_array($border_radius)) {
            $has_radius = false;
            foreach ($border_radius as $value) {
                if (!empty($value) && $value !== '0') {
                    $has_radius = true;
                    break;
                }
            }
            if ($has_radius) {
                $styles[] = 'border-radius: ' . esc_attr($border_radius['top']) . 'px ' . esc_attr($border_radius['right']) . 'px ' . esc_attr($border_radius['bottom']) . 'px ' . esc_attr($border_radius['left']) . 'px';
            }
        }
        
        // Box Shadow
        $box_shadow_enable = $this->get_settings('box_shadow_enable', 'no');
        if ($box_shadow_enable === 'yes') {
            $h = $this->get_settings('box_shadow_h', 0);
            $v = $this->get_settings('box_shadow_v', 5);
            $blur = $this->get_settings('box_shadow_blur', 15);
            $spread = $this->get_settings('box_shadow_spread', 0);
            $color = $this->get_settings('box_shadow_color', 'rgba(0,0,0,0.2)');
            $styles[] = 'box-shadow: ' . intval($h) . 'px ' . intval($v) . 'px ' . intval($blur) . 'px ' . intval($spread) . 'px ' . esc_attr($color);
        }
        
        // Transform
        $transforms = [];
        $rotate = $this->get_settings('rotate', 0);
        if ($rotate != 0) {
            $transforms[] = 'rotate(' . floatval($rotate) . 'deg)';
        }
        $scale = $this->get_settings('scale', 100);
        if ($scale != 100) {
            $scale_decimal = floatval($scale) / 100;
            $transforms[] = 'scale(' . $scale_decimal . ')';
        }
        $skew_x = $this->get_settings('skew_x', 0);
        $skew_y = $this->get_settings('skew_y', 0);
        if ($skew_x != 0 || $skew_y != 0) {
            $transforms[] = 'skew(' . floatval($skew_x) . 'deg, ' . floatval($skew_y) . 'deg)';
        }
        if (!empty($transforms)) {
            $styles[] = 'transform: ' . implode(' ', $transforms);
        }
        
        // Opacity (convert percentage 0-100 to decimal 0-1)
        $opacity = $this->get_settings('opacity');
        if ($opacity !== null && $opacity !== '' && $opacity != 100) {
            $opacity_decimal = floatval($opacity) / 100;
            $styles[] = 'opacity: ' . $opacity_decimal;
        }
        
        // Z-Index
        $z_index = $this->get_settings('z_index');
        if ($z_index !== null && $z_index !== '') {
            $styles[] = 'z-index: ' . intval($z_index);
        }
        
        // Margin
        $margin = $this->get_settings('margin');
        if (!empty($margin) && is_array($margin)) {
            $margin_parts = [];
            if (!empty($margin['top']) && $margin['top'] !== '0') {
                $margin_parts[] = 'margin-top: ' . esc_attr($margin['top']) . 'px';
            }
            if (!empty($margin['right']) && $margin['right'] !== '0') {
                $margin_parts[] = 'margin-right: ' . esc_attr($margin['right']) . 'px';
            }
            if (!empty($margin['bottom']) && $margin['bottom'] !== '0') {
                $margin_parts[] = 'margin-bottom: ' . esc_attr($margin['bottom']) . 'px';
            }
            if (!empty($margin['left']) && $margin['left'] !== '0') {
                $margin_parts[] = 'margin-left: ' . esc_attr($margin['left']) . 'px';
            }
            $styles = array_merge($styles, $margin_parts);
        }
        
        // Padding
        $padding = $this->get_settings('padding');
        if (!empty($padding) && is_array($padding)) {
            $padding_parts = [];
            if (!empty($padding['top']) && $padding['top'] !== '0') {
                $padding_parts[] = 'padding-top: ' . esc_attr($padding['top']) . 'px';
            }
            if (!empty($padding['right']) && $padding['right'] !== '0') {
                $padding_parts[] = 'padding-right: ' . esc_attr($padding['right']) . 'px';
            }
            if (!empty($padding['bottom']) && $padding['bottom'] !== '0') {
                $padding_parts[] = 'padding-bottom: ' . esc_attr($padding['bottom']) . 'px';
            }
            if (!empty($padding['left']) && $padding['left'] !== '0') {
                $padding_parts[] = 'padding-left: ' . esc_attr($padding['left']) . 'px';
            }
            $styles = array_merge($styles, $padding_parts);
        }
        
        return implode('; ', $styles);
    }
    
    /**
     * Render custom CSS if provided
     */
    protected function render_custom_css() {
        $custom_css = $this->get_settings('custom_css', '');
        if (!empty($custom_css)) {
            echo '<style>' . wp_strip_all_tags($custom_css) . '</style>';
        }
    }
}

