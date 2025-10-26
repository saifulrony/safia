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
        ]);
        
        $this->add_control('menu', [
            'label' => __('Select Menu', 'probuilder'),
            'type' => 'select',
            'options' => $this->get_available_menus(),
            'default' => '',
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'options' => [
                'horizontal' => __('Horizontal', 'probuilder'),
                'vertical' => __('Vertical', 'probuilder'),
            ],
            'default' => 'horizontal',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('hover_color', [
            'label' => __('Hover Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $menu_id = $this->get_settings('menu', '');
        $layout = $this->get_settings('layout', 'horizontal');
        $text_color = $this->get_settings('text_color', '#333333');
        $hover_color = $this->get_settings('hover_color', '#0073aa');
        
        if (empty($menu_id)) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">Please select a menu from settings</p>';
            return;
        }
        
        $flex_direction = $layout === 'horizontal' ? 'row' : 'column';
        $menu_class = 'pb-menu-' . uniqid();
        
        echo '<style>
        .' . $menu_class . '{display:flex;flex-direction:' . $flex_direction . ';gap:20px;list-style:none;padding:0;margin:0}
        .' . $menu_class . ' a{color:' . $text_color . ';text-decoration:none;padding:10px 15px;display:block;transition:color 0.3s}
        .' . $menu_class . ' a:hover{color:' . $hover_color . '}
        .' . $menu_class . ' .sub-menu{list-style:none;padding:10px 0;margin:0}
        </style>';
        
        wp_nav_menu([
            'menu' => $menu_id,
            'container' => 'nav',
            'menu_class' => $menu_class,
            'fallback_cb' => false,
        ]);
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

