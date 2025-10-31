<?php
/**
 * WordPress Header Widget
 * Display existing WordPress headers/menus
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_WP_Header extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'wp-header';
        $this->title = __('WP Header', 'probuilder');
        $this->icon = 'fa fa-window-maximize';
        $this->category = 'content';
        $this->keywords = ['header', 'menu', 'navigation', 'nav', 'wordpress'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_header', [
            'label' => __('Header', 'probuilder'),
            'tab' => 'content',
        ]);
        
        // Get all registered menus
        $menus = wp_get_nav_menus();
        $menu_options = ['' => __('— Select Menu —', 'probuilder')];
        foreach ($menus as $menu) {
            $menu_options[$menu->term_id] = $menu->name;
        }
        
        $this->add_control('menu_id', [
            'label' => __('Select Menu', 'probuilder'),
            'type' => 'select',
            'default' => '',
            'options' => $menu_options,
            'description' => __('Choose a WordPress menu to display as header', 'probuilder'),
        ]);
        
        $this->add_control('header_type', [
            'label' => __('Header Type', 'probuilder'),
            'type' => 'select',
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal', 'probuilder'),
                'vertical' => __('Vertical', 'probuilder'),
            ],
        ]);
        
        $this->add_control('show_logo', [
            'label' => __('Show Site Logo', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('custom_logo', [
            'label' => __('Custom Logo', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => ''],
            'description' => __('Override default site logo', 'probuilder'),
        ]);
        
        $this->add_control('logo_width', [
            'label' => __('Logo Width (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 150,
            'range' => [
                'px' => ['min' => 50, 'max' => 400, 'step' => 10],
            ],
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
            'default' => '#ffffff',
        ]);
        
        $this->add_control('menu_color', [
            'label' => __('Menu Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('menu_hover_color', [
            'label' => __('Menu Hover Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 30, 'bottom' => 20, 'left' => 30],
        ]);
        
        $this->add_control('sticky', [
            'label' => __('Sticky Header', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $menu_id = $this->get_settings('menu_id', '');
        $header_type = $this->get_settings('header_type', 'horizontal');
        $show_logo = $this->get_settings('show_logo', 'yes') === 'yes';
        $custom_logo = $this->get_settings('custom_logo', ['url' => '']);
        $logo_width = $this->get_settings('logo_width', 150);
        $bg_color = $this->get_settings('bg_color', '#ffffff');
        $menu_color = $this->get_settings('menu_color', '#333333');
        $menu_hover_color = $this->get_settings('menu_hover_color', '#92003b');
        $padding = $this->get_settings('padding', ['top' => 20, 'right' => 30, 'bottom' => 20, 'left' => 30]);
        $sticky = $this->get_settings('sticky', 'no') === 'yes';
        $box_shadow = $this->get_settings('box_shadow', 'yes') === 'yes';
        
        $id = 'wp-header-' . uniqid();
        
        // Header style
        $header_style = 'background: ' . esc_attr($bg_color) . '; ';
        $header_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px; ';
        $header_style .= 'display: flex; align-items: center; ';
        $header_style .= $header_type === 'horizontal' ? 'justify-content: space-between; flex-direction: row;' : 'flex-direction: column; gap: 20px;';
        
        if ($box_shadow) {
            $header_style .= ' box-shadow: 0 2px 10px rgba(0,0,0,0.1);';
        }
        
        if ($sticky) {
            $header_style .= ' position: sticky; top: 0; z-index: 999;';
        }
        
        if ($inline_styles) {
            $header_style .= ' ' . $inline_styles;
        }
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-wp-header" ' . $wrapper_attributes . ' id="' . esc_attr($id) . '" style="' . esc_attr($header_style) . '">';
        
        // Logo
        if ($show_logo) {
            $logo_url = !empty($custom_logo['url']) ? $custom_logo['url'] : get_site_icon_url(150);
            if (empty($logo_url)) {
                $logo_url = get_template_directory_uri() . '/screenshot.png'; // Fallback
            }
            
            echo '<div class="header-logo" style="flex-shrink: 0;">';
            echo '<a href="' . esc_url(home_url('/')) . '" style="display: inline-block;">';
            if ($logo_url) {
                echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" style="height: auto; max-width: ' . esc_attr($logo_width) . 'px; display: block;">';
            } else {
                echo '<span style="font-size: 24px; font-weight: 700; color: ' . esc_attr($menu_color) . ';">' . esc_html(get_bloginfo('name')) . '</span>';
            }
            echo '</a>';
            echo '</div>';
        }
        
        // Menu
        if (!empty($menu_id)) {
            $menu_args = [
                'menu' => $menu_id,
                'container' => 'nav',
                'container_class' => 'header-menu',
                'menu_class' => 'probuilder-header-menu',
                'fallback_cb' => false,
                'depth' => 1,
            ];
            
            echo '<div class="header-navigation" style="flex-grow: 1;">';
            wp_nav_menu($menu_args);
            echo '</div>';
            
            // Add menu styles
            echo '<style>
                #' . esc_attr($id) . ' .probuilder-header-menu {
                    list-style: none;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    ' . ($header_type === 'horizontal' ? 'flex-direction: row;' : 'flex-direction: column;') . '
                    gap: ' . ($header_type === 'horizontal' ? '30px' : '15px') . ';
                    ' . ($header_type === 'horizontal' ? 'justify-content: flex-end;' : 'align-items: center;') . '
                }
                #' . esc_attr($id) . ' .probuilder-header-menu li {
                    margin: 0;
                }
                #' . esc_attr($id) . ' .probuilder-header-menu a {
                    color: ' . esc_attr($menu_color) . ';
                    text-decoration: none;
                    font-weight: 500;
                    font-size: 15px;
                    transition: color 0.3s;
                    padding: 5px 0;
                    display: inline-block;
                }
                #' . esc_attr($id) . ' .probuilder-header-menu a:hover {
                    color: ' . esc_attr($menu_hover_color) . ';
                }
            </style>';
        } else {
            echo '<div style="color: #666; font-size: 14px; font-style: italic;">No menu selected</div>';
        }
        
        echo '</div>';
    }
}

