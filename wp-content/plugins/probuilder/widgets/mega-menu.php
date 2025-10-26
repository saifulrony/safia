<?php
/**
 * Mega Menu Widget - Fixed Version
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Mega_Menu_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'mega-menu';
        $this->title = __('Mega Menu', 'probuilder');
        $this->icon = 'fa fa-bars';
        $this->category = 'wordpress';
        $this->keywords = ['menu', 'navigation', 'mega'];
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
    }
    
    protected function render() {
        $menu_id = $this->get_settings('menu', '');
        $layout = $this->get_settings('layout', 'horizontal');
        
        if (empty($menu_id)) {
            echo '<p style="padding:20px;background:#f5f5f5;text-align:center">Please select a menu from settings</p>';
            return;
        }
        
        $menu_style = $layout === 'horizontal' 
            ? 'display:flex;flex-direction:row;gap:20px;list-style:none;padding:0;margin:0' 
            : 'display:flex;flex-direction:column;gap:10px;list-style:none;padding:0;margin:0';
        
        echo '<div class="pb-mega-menu">';
        wp_nav_menu([
            'menu' => $menu_id,
            'container' => 'nav',
            'menu_class' => 'pb-menu',
            'items_wrap' => '<ul style="' . $menu_style . '">%3$s</ul>',
            'fallback_cb' => false,
        ]);
        echo '</div>';
        
        echo '<style>
        .pb-mega-menu a{color:#333;text-decoration:none;padding:10px 15px;display:block;transition:color 0.3s}
        .pb-mega-menu a:hover{color:#0073aa}
        .pb-mega-menu .sub-menu{position:absolute;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.1);padding:10px;display:none;list-style:none}
        .pb-mega-menu li:hover>.sub-menu{display:block}
        </style>';
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
