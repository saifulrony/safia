<?php
/**
 * WordPress Footer Widget
 * Display existing WordPress footer areas
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_WP_Footer extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'wp-footer';
        $this->title = __('WP Footer', 'probuilder');
        $this->icon = 'fa fa-window-minimize';
        $this->category = 'content';
        $this->keywords = ['footer', 'widget', 'bottom', 'wordpress'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_footer', [
            'label' => __('Footer', 'probuilder'),
            'tab' => 'content',
        ]);
        
        // Get all registered sidebars (footers are usually sidebars too)
        global $wp_registered_sidebars;
        $footer_options = ['' => __('— Select Footer Area —', 'probuilder')];
        
        if (!empty($wp_registered_sidebars)) {
            foreach ($wp_registered_sidebars as $sidebar) {
                // Usually footer sidebars contain 'footer' in their ID or name
                $footer_options[$sidebar['id']] = $sidebar['name'];
            }
        }
        
        $this->add_control('footer_id', [
            'label' => __('Select Footer Area', 'probuilder'),
            'type' => 'select',
            'default' => '',
            'options' => $footer_options,
            'description' => __('Choose a WordPress widget area to display as footer', 'probuilder'),
        ]);
        
        $this->add_control('footer_layout', [
            'label' => __('Footer Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'columns',
            'options' => [
                'columns' => __('Columns', 'probuilder'),
                'stacked' => __('Stacked', 'probuilder'),
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Number of Columns', 'probuilder'),
            'type' => 'select',
            'default' => '3',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);
        
        $this->add_control('show_copyright', [
            'label' => __('Show Copyright', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('copyright_text', [
            'label' => __('Copyright Text', 'probuilder'),
            'type' => 'text',
            'default' => '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
        ]);
        
        $this->add_control('footer_info', [
            'type' => 'raw_html',
            'raw' => '<div style="padding: 15px; background: #f3e5f5; border-left: 4px solid #9c27b0; margin-top: 10px; border-radius: 3px;">
                <strong style="display: block; margin-bottom: 5px; color: #7b1fa2;">📌 About Footer Areas</strong>
                <p style="margin: 5px 0 0 0; color: #555; font-size: 13px; line-height: 1.6;">
                    Footer widget areas are registered by your theme. You can add widgets to them in <strong>Appearance → Widgets</strong><br><br>
                    This widget displays the footer content from your theme.
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
            'default' => '#1f2937',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#e5e7eb',
        ]);
        
        $this->add_control('link_color', [
            'label' => __('Link Color', 'probuilder'),
            'type' => 'color',
            'default' => '#93c5fd',
        ]);
        
        $this->add_control('link_hover_color', [
            'label' => __('Link Hover Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('padding', [
            'label' => __('Padding', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 60, 'right' => 30, 'bottom' => 30, 'left' => 30],
        ]);
        
        $this->add_control('copyright_bg', [
            'label' => __('Copyright Background', 'probuilder'),
            'type' => 'color',
            'default' => '#111827',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $footer_id = $this->get_settings('footer_id', '');
        $footer_layout = $this->get_settings('footer_layout', 'columns');
        $columns = $this->get_settings('columns', '3');
        $show_copyright = $this->get_settings('show_copyright', 'yes') === 'yes';
        $copyright_text = $this->get_settings('copyright_text', '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
        $bg_color = $this->get_settings('bg_color', '#1f2937');
        $text_color = $this->get_settings('text_color', '#e5e7eb');
        $link_color = $this->get_settings('link_color', '#93c5fd');
        $link_hover_color = $this->get_settings('link_hover_color', '#ffffff');
        $padding = $this->get_settings('padding', ['top' => 60, 'right' => 30, 'bottom' => 30, 'left' => 30]);
        $copyright_bg = $this->get_settings('copyright_bg', '#111827');
        
        $id = 'wp-footer-' . uniqid();
        
        // Footer style
        $footer_style = 'background: ' . esc_attr($bg_color) . '; ';
        $footer_style .= 'color: ' . esc_attr($text_color) . '; ';
        $footer_style .= 'padding: ' . esc_attr($padding['top']) . 'px ' . esc_attr($padding['right']) . 'px ' . esc_attr($padding['bottom']) . 'px ' . esc_attr($padding['left']) . 'px;';
        
        echo '<div class="probuilder-wp-footer" id="' . esc_attr($id) . '" style="' . $footer_style . '">';
        
        if (!empty($footer_id) && is_active_sidebar($footer_id)) {
            $content_style = $footer_layout === 'columns' ? 
                'display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: 30px;' : 
                'display: flex; flex-direction: column; gap: 20px;';
            
            echo '<div class="footer-content" style="' . $content_style . '">';
            dynamic_sidebar($footer_id);
            echo '</div>';
            
            // Add footer widget styles
            echo '<style>
                #' . esc_attr($id) . ' .widget {
                    color: ' . esc_attr($text_color) . ';
                }
                #' . esc_attr($id) . ' .widget-title,
                #' . esc_attr($id) . ' .widgettitle {
                    color: ' . esc_attr($text_color) . ';
                    font-size: 18px;
                    font-weight: 600;
                    margin-bottom: 15px;
                }
                #' . esc_attr($id) . ' a {
                    color: ' . esc_attr($link_color) . ';
                    text-decoration: none;
                    transition: color 0.3s;
                }
                #' . esc_attr($id) . ' a:hover {
                    color: ' . esc_attr($link_hover_color) . ';
                }
                #' . esc_attr($id) . ' ul {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }
                #' . esc_attr($id) . ' li {
                    padding: 5px 0;
                }
            </style>';
        } elseif (!empty($footer_id)) {
            echo '<div style="padding: 30px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3); border-radius: 8px; text-align: center;">';
            echo '<i class="fa fa-exclamation-triangle" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>';
            echo '<div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">Footer Area Has No Widgets</div>';
            echo '<div style="font-size: 13px; opacity: 0.9;">Add widgets to this area in <strong>Appearance → Widgets</strong></div>';
            echo '</div>';
        } else {
            echo '<div style="padding: 30px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3); border-radius: 8px; text-align: center;">';
            echo '<i class="fa fa-window-minimize" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>';
            echo '<div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">No Footer Area Selected</div>';
            echo '<div style="font-size: 13px; opacity: 0.9;">Select a footer widget area from the settings</div>';
            echo '</div>';
        }
        
        // Copyright section
        if ($show_copyright) {
            $copyright_style = 'background: ' . esc_attr($copyright_bg) . '; ';
            $copyright_style .= 'color: ' . esc_attr($text_color) . '; ';
            $copyright_style .= 'text-align: center; padding: 20px; margin-top: 30px; font-size: 14px;';
            
            echo '<div class="footer-copyright" style="' . $copyright_style . '">';
            echo esc_html($copyright_text);
            echo '</div>';
        }
        
        echo '</div>';
    }
}

