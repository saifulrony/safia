<?php
/**
 * Button Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Button extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'button';
        $this->title = __('Button', 'probuilder');
        $this->icon = 'fa fa-square-check';
        $this->category = 'basic';
        $this->keywords = ['button', 'link', 'cta'];
    }
    
    protected function register_controls() {
        // CONTENT TAB
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('text', [
            'label' => __('Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Click Here', 'probuilder'),
        ]);
        
        $this->add_control('link', [
            'label' => __('Link', 'probuilder'),
            'type' => 'url',
            'default' => '#',
        ]);
        
        $this->add_control('link_target', [
            'label' => __('Open in New Tab', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'icon',
            'default' => '',
            'placeholder' => 'fa fa-arrow-right',
        ]);
        
        $this->add_control('icon_position', [
            'label' => __('Icon Position', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('icon_spacing', [
            'label' => __('Icon Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => ['min' => 0, 'max' => 30],
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Button Style
        $this->start_controls_section('section_button_style', [
            'label' => __('Button Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('button_type', [
            'label' => __('Type', 'probuilder'),
            'type' => 'select',
            'default' => 'solid',
            'options' => [
                'solid' => __('Solid', 'probuilder'),
                'outline' => __('Outline', 'probuilder'),
                'ghost' => __('Ghost', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
            ],
        ]);
        
        $this->add_control('size_preset', [
            'label' => __('Size Preset', 'probuilder'),
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => __('Small', 'probuilder'),
                'medium' => __('Medium', 'probuilder'),
                'large' => __('Large', 'probuilder'),
                'xl' => __('Extra Large', 'probuilder'),
                'custom' => __('Custom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('width_type', [
            'label' => __('Width', 'probuilder'),
            'type' => 'select',
            'default' => 'auto',
            'options' => [
                'auto' => __('Auto', 'probuilder'),
                'full' => __('Full Width', 'probuilder'),
                'custom' => __('Custom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('custom_width', [
            'label' => __('Custom Width (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 200,
            'range' => [
                'px' => ['min' => 50, 'max' => 800],
            ],
        ]);
        
        $this->add_control('align', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
                'justify' => __('Justify (Full Width)', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Colors
        $this->start_controls_section('section_colors', [
            'label' => __('Colors', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('gradient_color', [
            'label' => __('Gradient Color 2', 'probuilder'),
            'type' => 'color',
            'default' => '#005a87',
        ]);
        
        $this->add_control('gradient_angle', [
            'label' => __('Gradient Angle', 'probuilder'),
            'type' => 'slider',
            'default' => 135,
            'range' => [
                'px' => ['min' => 0, 'max' => 360],
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Hover Effects
        $this->start_controls_section('section_hover', [
            'label' => __('Hover Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('hover_bg_color', [
            'label' => __('Hover Background', 'probuilder'),
            'type' => 'color',
            'default' => '#005a87',
        ]);
        
        $this->add_control('hover_text_color', [
            'label' => __('Hover Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('hover_animation', [
            'label' => __('Hover Animation', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'grow' => __('Grow', 'probuilder'),
                'shrink' => __('Shrink', 'probuilder'),
                'lift' => __('Lift (Shadow)', 'probuilder'),
                'float' => __('Float Up', 'probuilder'),
                'pulse' => __('Pulse', 'probuilder'),
                'bounce' => __('Bounce', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Typography
        $this->start_controls_section('section_typography', [
            'label' => __('Typography', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 16,
            'range' => [
                'px' => ['min' => 10, 'max' => 36],
            ],
        ]);
        
        $this->add_control('font_weight', [
            'label' => __('Font Weight', 'probuilder'),
            'type' => 'select',
            'default' => '500',
            'options' => [
                '300' => __('Light (300)', 'probuilder'),
                '400' => __('Normal (400)', 'probuilder'),
                '500' => __('Medium (500)', 'probuilder'),
                '600' => __('Semi Bold (600)', 'probuilder'),
                '700' => __('Bold (700)', 'probuilder'),
                '800' => __('Extra Bold (800)', 'probuilder'),
            ],
        ]);
        
        $this->add_control('text_transform', [
            'label' => __('Text Transform', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'uppercase' => __('UPPERCASE', 'probuilder'),
                'lowercase' => __('lowercase', 'probuilder'),
                'capitalize' => __('Capitalize', 'probuilder'),
            ],
        ]);
        
        $this->add_control('letter_spacing', [
            'label' => __('Letter Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => ['min' => -2, 'max' => 10, 'step' => 0.1],
            ],
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Border
        $this->start_controls_section('section_border', [
            'label' => __('Border', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('border_width', [
            'label' => __('Border Width (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => ['min' => 0, 'max' => 10],
            ],
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 3,
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
        ]);
        
        $this->add_control('hover_border_color', [
            'label' => __('Hover Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#005a87',
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Shadow
        $this->start_controls_section('section_shadow', [
            'label' => __('Shadow', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'box-shadow',
            'default' => ['x' => 0, 'y' => 2, 'blur' => 8, 'color' => 'rgba(0,0,0,0.1)'],
        ]);
        
        $this->add_control('hover_box_shadow', [
            'label' => __('Hover Shadow', 'probuilder'),
            'type' => 'box-shadow',
            'default' => ['x' => 0, 'y' => 4, 'blur' => 16, 'color' => 'rgba(0,0,0,0.2)'],
        ]);
        
        $this->end_controls_section();
        
        // Note: Spacing section is automatically added by base class
        // But we keep button-specific padding in the controls for the button itself
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        // Content settings
        $text = $this->get_settings('text', 'Click Here');
        $link = $this->get_settings('link', '#');
        $link_target = $this->get_settings('link_target', 'no') === 'yes' ? '_blank' : '_self';
        $icon = $this->get_settings('icon', '');
        $icon_position = $this->get_settings('icon_position', 'left');
        $icon_spacing = $this->get_settings('icon_spacing', 8);
        
        // Style settings
        $button_type = $this->get_settings('button_type', 'solid');
        $size_preset = $this->get_settings('size_preset', 'medium');
        $width_type = $this->get_settings('width_type', 'auto');
        $custom_width = $this->get_settings('custom_width', 200);
        $align = $this->get_settings('align', 'left');
        
        // Color settings
        $bg_color = $this->get_settings('bg_color', '#0073aa');
        $text_color = $this->get_settings('text_color', '#ffffff');
        $gradient_color = $this->get_settings('gradient_color', '#005a87');
        $gradient_angle = $this->get_settings('gradient_angle', 135);
        
        // Hover settings
        $hover_bg_color = $this->get_settings('hover_bg_color', '#005a87');
        $hover_text_color = $this->get_settings('hover_text_color', '#ffffff');
        $hover_animation = $this->get_settings('hover_animation', 'none');
        
        // Typography settings
        $font_size = $this->get_settings('font_size', 16);
        $font_weight = $this->get_settings('font_weight', '500');
        $text_transform = $this->get_settings('text_transform', 'none');
        $letter_spacing = $this->get_settings('letter_spacing', 0);
        
        // Border settings
        $border_width = $this->get_settings('border_width', 0);
        $border_color = $this->get_settings('border_color', '#0073aa');
        $border_radius = $this->get_settings('border_radius', 3);
        $hover_border_color = $this->get_settings('hover_border_color', '#005a87');
        
        // Shadow settings
        $box_shadow = $this->get_settings('box_shadow', ['x' => 0, 'y' => 2, 'blur' => 8, 'color' => 'rgba(0,0,0,0.1)']);
        $hover_box_shadow = $this->get_settings('hover_box_shadow', ['x' => 0, 'y' => 4, 'blur' => 16, 'color' => 'rgba(0,0,0,0.2)']);
        
        // Spacing settings - using default button padding
        $padding = ['top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24];
        
        // Apply size preset
        if ($size_preset !== 'custom') {
            $presets = [
                'small' => ['font' => 14, 'padding' => ['top' => 8, 'right' => 16, 'bottom' => 8, 'left' => 16]],
                'medium' => ['font' => 16, 'padding' => ['top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24]],
                'large' => ['font' => 18, 'padding' => ['top' => 16, 'right' => 32, 'bottom' => 16, 'left' => 32]],
                'xl' => ['font' => 22, 'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40]],
            ];
            if (isset($presets[$size_preset])) {
                $font_size = $presets[$size_preset]['font'];
                $padding = $presets[$size_preset]['padding'];
            }
        }
        
        // Generate unique ID for hover styles
        $button_id = 'probuilder-button-' . uniqid();
        
        // Build styles
        $button_style = '';
        
        // Background
        if ($button_type === 'gradient') {
            $button_style .= 'background: linear-gradient(' . $gradient_angle . 'deg, ' . esc_attr($bg_color) . ', ' . esc_attr($gradient_color) . '); ';
        } elseif ($button_type === 'outline' || $button_type === 'ghost') {
            $button_style .= 'background: transparent; ';
        } else {
            $button_style .= 'background-color: ' . esc_attr($bg_color) . '; ';
        }
        
        // Text color
        $button_style .= 'color: ' . esc_attr($text_color) . '; ';
        
        // Typography
        $button_style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
        $button_style .= 'font-weight: ' . esc_attr($font_weight) . '; ';
        $button_style .= 'text-transform: ' . esc_attr($text_transform) . '; ';
        $button_style .= 'letter-spacing: ' . esc_attr($letter_spacing) . 'px; ';
        
        // Padding (internal button padding)
        $button_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        
        // Border
        if ($border_width > 0 || $button_type === 'outline') {
            $final_border_width = $button_type === 'outline' ? max(2, $border_width) : $border_width;
            $button_style .= 'border: ' . esc_attr($final_border_width) . 'px solid ' . esc_attr($border_color) . '; ';
        } else {
            $button_style .= 'border: none; ';
        }
        
        $button_style .= 'border-radius: ' . esc_attr($border_radius) . 'px; ';
        
        // Box shadow
        if ($box_shadow && $box_shadow['blur'] > 0) {
            $button_style .= 'box-shadow: ' . esc_attr($box_shadow['x']) . 'px ' . esc_attr($box_shadow['y']) . 'px ' . esc_attr($box_shadow['blur']) . 'px ' . esc_attr($box_shadow['color']) . '; ';
        }
        
        // Width
        if ($width_type === 'full' || $align === 'justify') {
            $button_style .= 'width: 100%; display: block; text-align: center; ';
        } elseif ($width_type === 'custom') {
            $button_style .= 'width: ' . esc_attr($custom_width) . 'px; display: inline-block; text-align: center; ';
        } else {
            $button_style .= 'display: inline-block; ';
        }
        
        $button_style .= 'text-decoration: none; transition: all 0.3s ease; cursor: pointer;';
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        // Wrapper style
        $wrapper_style = 'text-align: ' . esc_attr($align === 'justify' ? 'center' : $align) . ';';
        if ($inline_styles) {
            $wrapper_style .= ' ' . $inline_styles;
        }
        
        // Hover styles
        $hover_bg = $button_type === 'gradient' 
            ? 'linear-gradient(' . $gradient_angle . 'deg, ' . esc_attr($hover_bg_color) . ', ' . esc_attr($gradient_color) . ')'
            : esc_attr($hover_bg_color);
        
        $hover_shadow = $hover_box_shadow && $hover_box_shadow['blur'] > 0
            ? esc_attr($hover_box_shadow['x']) . 'px ' . esc_attr($hover_box_shadow['y']) . 'px ' . esc_attr($hover_box_shadow['blur']) . 'px ' . esc_attr($hover_box_shadow['color'])
            : 'none';
        
        ?>
        <style>
            #<?php echo $button_id; ?> {
                position: relative;
                overflow: hidden;
            }
            
            #<?php echo $button_id; ?>:hover {
                <?php if ($button_type !== 'ghost'): ?>
                background: <?php echo $hover_bg; ?> !important;
                <?php endif; ?>
                color: <?php echo esc_attr($hover_text_color); ?> !important;
                <?php if ($border_width > 0 || $button_type === 'outline'): ?>
                border-color: <?php echo esc_attr($hover_border_color); ?> !important;
                <?php endif; ?>
                box-shadow: <?php echo $hover_shadow; ?> !important;
                
                <?php if ($hover_animation === 'grow'): ?>
                transform: scale(1.05);
                <?php elseif ($hover_animation === 'shrink'): ?>
                transform: scale(0.95);
                <?php elseif ($hover_animation === 'lift'): ?>
                transform: translateY(-3px);
                <?php elseif ($hover_animation === 'float'): ?>
                transform: translateY(-5px);
                <?php elseif ($hover_animation === 'pulse'): ?>
                animation: buttonPulse 0.6s ease;
                <?php elseif ($hover_animation === 'bounce'): ?>
                animation: buttonBounce 0.6s ease;
                <?php endif; ?>
            }
            
            <?php if ($button_type === 'ghost'): ?>
            #<?php echo $button_id; ?>:hover {
                background: <?php echo esc_attr($bg_color); ?> !important;
                opacity: 0.1;
            }
            <?php endif; ?>
            
            @keyframes buttonPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }
            
            @keyframes buttonBounce {
                0%, 100% { transform: translateY(0); }
                25% { transform: translateY(-8px); }
                50% { transform: translateY(0); }
                75% { transform: translateY(-4px); }
            }
            
            #<?php echo $button_id; ?> i {
                margin-left: <?php echo $icon_position === 'left' ? '0' : esc_attr($icon_spacing); ?>px;
                margin-right: <?php echo $icon_position === 'left' ? esc_attr($icon_spacing) : '0'; ?>px;
                transition: transform 0.3s ease;
            }
            
            #<?php echo $button_id; ?>:hover i {
                transform: translateX(<?php echo $icon_position === 'left' ? '-3px' : '3px'; ?>);
            }
        </style>
        
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-button-wrapper" <?php echo $wrapper_attributes; ?> style="<?php echo $wrapper_style; ?>">
            <a href="<?php echo esc_url($link); ?>" 
               id="<?php echo $button_id; ?>"
               class="probuilder-button" 
               style="<?php echo $button_style; ?>"
               target="<?php echo esc_attr($link_target); ?>"
               rel="<?php echo $link_target === '_blank' ? 'noopener noreferrer' : ''; ?>">
                
                <?php if ($icon && $icon_position === 'left'): ?>
                    <i class="<?php echo esc_attr($icon); ?>"></i>
                <?php endif; ?>
                
                <span><?php echo esc_html($text); ?></span>
                
                <?php if ($icon && $icon_position === 'right'): ?>
                    <i class="<?php echo esc_attr($icon); ?>"></i>
                <?php endif; ?>
                
            </a>
        </div>
        <?php
    }
}

