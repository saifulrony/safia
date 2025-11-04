<?php
/**
 * WordPress Sidebar Widget
 * Display existing WordPress sidebars
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_WP_Sidebar extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'wp-sidebar';
        $this->title = __('WP Sidebar', 'probuilder');
        $this->icon = 'fa fa-sidebar';
        $this->category = 'content';
        $this->keywords = ['sidebar', 'widget', 'area', 'wordpress'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_sidebar', [
            'label' => __('Sidebar', 'probuilder'),
            'tab' => 'content',
        ]);
        
        // Get all registered sidebars
        global $wp_registered_sidebars;
        $sidebar_options = ['' => __('— Select Sidebar —', 'probuilder')];
        
        if (!empty($wp_registered_sidebars)) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebar_options[$sidebar['id']] = $sidebar['name'];
            }
        }
        
        $this->add_control('sidebar_id', [
            'label' => __('Select Sidebar', 'probuilder'),
            'type' => 'select',
            'default' => '',
            'options' => $sidebar_options,
            'description' => __('Choose a WordPress sidebar/widget area to display', 'probuilder'),
        ]);
        
        $this->add_control('sidebar_info', [
            'type' => 'raw_html',
            'raw' => '<div style="padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3; margin-top: 10px; border-radius: 3px;">
                <strong style="display: block; margin-bottom: 5px; color: #1976d2;">📌 About Sidebars</strong>
                <p style="margin: 5px 0 0 0; color: #555; font-size: 13px; line-height: 1.6;">
                    Sidebars are widget areas registered by your theme. Common sidebars include:<br>
                    • Primary Sidebar<br>
                    • Footer Widget Area<br>
                    • Shop Sidebar<br>
                    <br>
                    You can add widgets to them in <strong>Appearance → Widgets</strong>
                </p>
            </div>',
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('bg_color', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20],
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => ['min' => 0, 'max' => 30, 'step' => 1],
            ],
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
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
        
        $sidebar_id = $this->get_settings('sidebar_id', '');
        $bg_color = $this->get_settings('bg_color', '');
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20]);
        $margin = $this->get_settings('margin', ['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0]);
        $border_radius = $this->get_settings('border_radius', 0);
        $box_shadow = $this->get_settings('box_shadow', 'no') === 'yes';
        
        $wrapper_style = '';
        if ($bg_color) {
            $wrapper_style .= 'background: ' . esc_attr($bg_color) . '; ';
        }
        $wrapper_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        $wrapper_style .= 'margin: ' . esc_attr($margin['top']) . 'px ' . esc_attr($margin['right']) . 'px ' . esc_attr($margin['bottom']) . 'px ' . esc_attr($margin['left']) . 'px; ';
        
        if ($border_radius > 0) {
            $wrapper_style .= 'border-radius: ' . esc_attr($border_radius) . 'px; ';
        }
        
        if ($box_shadow) {
            $wrapper_style .= 'box-shadow: 0 4px 15px rgba(0,0,0,0.1); ';
        }
        
        if ($inline_styles) {
            $wrapper_style .= ' ' . $inline_styles;
        }
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-wp-sidebar-wrapper" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
        if (!empty($sidebar_id) && is_active_sidebar($sidebar_id)) {
            dynamic_sidebar($sidebar_id);
        } elseif (!empty($sidebar_id)) {
            echo '<div style="padding: 30px; background: #fff3cd; border: 2px dashed #ffc107; border-radius: 8px; text-align: center; color: #856404;">';
            echo '<i class="fa fa-exclamation-triangle" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>';
            echo '<div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">Sidebar Has No Widgets</div>';
            echo '<div style="font-size: 13px;">Add widgets to this sidebar in <strong>Appearance → Widgets</strong></div>';
            echo '</div>';
        } else {
            echo '<div style="padding: 30px; background: #e3f2fd; border: 2px dashed #2196f3; border-radius: 8px; text-align: center; color: #1976d2;">';
            echo '<i class="fa fa-sidebar" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>';
            echo '<div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">No Sidebar Selected</div>';
            echo '<div style="font-size: 13px;">Select a sidebar from the widget settings</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

