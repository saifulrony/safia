<?php
/**
 * Navigation Menu Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Menu_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'menu';
        $this->title = __('Navigation Menu', 'probuilder');
        $this->icon = 'fa fa-list-ul';
        $this->category = 'wordpress';
        $this->keywords = ['menu', 'navigation', 'nav'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('menu', [
            'label' => __('Select Menu', 'probuilder'),
            'type' => 'select',
            'options' => $this->get_available_menus(),
            'default' => '',
            'description' => __('Choose a WordPress menu created in Appearance → Menus', 'probuilder'),
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'options' => [
                'horizontal' => __('Horizontal (Row)', 'probuilder'),
                'vertical' => __('Vertical (Column)', 'probuilder'),
            ],
            'default' => 'horizontal',
        ]);
        
        $this->add_control('alignment', [
            'label' => __('Alignment', 'probuilder'),
            'type' => 'select',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
            'default' => 'left',
        ]);
        
        $this->add_control('spacing', [
            'label' => __('Items Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 5, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('hover_color', [
            'label' => __('Hover Color', 'probuilder'),
            'type' => 'color',
            'default' => '#667eea',
        ]);
        
        $this->add_control('active_color', [
            'label' => __('Active Color', 'probuilder'),
            'type' => 'color',
            'default' => '#667eea',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 16,
            'range' => [
                'px' => ['min' => 12, 'max' => 32, 'step' => 1],
            ],
        ]);
        
        $this->add_control('font_weight', [
            'label' => __('Font Weight', 'probuilder'),
            'type' => 'select',
            'options' => [
                '300' => __('Light', 'probuilder'),
                '400' => __('Normal', 'probuilder'),
                '500' => __('Medium', 'probuilder'),
                '600' => __('Semi Bold', 'probuilder'),
                '700' => __('Bold', 'probuilder'),
            ],
            'default' => '500',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $menu_id = $this->get_settings('menu', '');
        $layout = $this->get_settings('layout', 'horizontal');
        $alignment = $this->get_settings('alignment', 'left');
        $spacing = $this->get_settings('spacing', 20);
        $text_color = $this->get_settings('text_color', '#333333');
        $hover_color = $this->get_settings('hover_color', '#667eea');
        $active_color = $this->get_settings('active_color', '#667eea');
        $font_size = $this->get_settings('font_size', 16);
        $font_weight = $this->get_settings('font_weight', '500');
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        if (empty($menu_id)) {
            if (current_user_can('edit_posts')) {
                echo '<div style="padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107; text-align: center; border-radius: 6px;">';
                echo '<p style="margin: 0 0 10px; font-weight: 600; color: #856404;">⚠️ No Menu Selected</p>';
                echo '<p style="margin: 0; font-size: 14px; color: #856404;">Click this widget and select a menu from the settings panel (right side) →</p>';
                echo '<p style="margin: 10px 0 0; font-size: 13px; color: #856404;"><a href="' . admin_url('nav-menus.php') . '" target="_blank" style="color: #667eea;">Create a menu in WordPress</a></p>';
                echo '</div>';
            }
            return;
        }
        
        $flex_direction = $layout === 'horizontal' ? 'row' : 'column';
        $justify_content = 'flex-start';
        if ($alignment === 'center') {
            $justify_content = 'center';
        } elseif ($alignment === 'right') {
            $justify_content = 'flex-end';
        }
        
        $menu_class = 'pb-menu-' . uniqid();
        
        echo '<style>
        .' . $menu_class . ' {
            display: flex;
            flex-direction: ' . esc_attr($flex_direction) . ';
            justify-content: ' . esc_attr($justify_content) . ';
            gap: ' . esc_attr($spacing) . 'px;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }
        .' . $menu_class . ' li {
            margin: 0;
        }
        .' . $menu_class . ' a {
            color: ' . esc_attr($text_color) . ';
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: ' . esc_attr($font_size) . 'px;
            font-weight: ' . esc_attr($font_weight) . ';
        }
        .' . $menu_class . ' a:hover {
            color: ' . esc_attr($hover_color) . ';
        }
        .' . $menu_class . ' .current-menu-item > a,
        .' . $menu_class . ' .current_page_item > a {
            color: ' . esc_attr($active_color) . ';
            font-weight: 600;
        }
        .' . $menu_class . ' .sub-menu {
            list-style: none;
            padding: 10px 0 0 20px;
            margin: 0;
        }
        @media (max-width: 768px) {
            .' . $menu_class . ' {
                flex-direction: column;
                align-items: ' . ($alignment === 'right' ? 'flex-end' : ($alignment === 'center' ? 'center' : 'flex-start')) . ';
            }
        }
        </style>';
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-menu-wrapper" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '">';
        wp_nav_menu([
            'menu' => $menu_id,
            'container' => 'nav',
            'menu_class' => $menu_class,
            'fallback_cb' => false,
        ]);
        echo '</div>';
    }
    
    private function get_available_menus() {
        $menus = wp_get_nav_menus();
        $options = ['' => __('Select Menu', 'probuilder')];
        foreach ($menus as $menu) {
            $options[$menu->term_id] = $menu->name;
        }
        return $options;
    }
}

