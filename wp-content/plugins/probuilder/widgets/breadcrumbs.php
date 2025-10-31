<?php
/**
 * Breadcrumbs Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Breadcrumbs_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'breadcrumbs';
        $this->title = __('Breadcrumbs', 'probuilder');
        $this->icon = 'fa fa-ellipsis-h';
        $this->category = 'wordpress';
        $this->keywords = ['breadcrumbs', 'navigation', 'seo'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('home_text', [
            'label' => __('Home Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Home', 'probuilder'),
        ]);
        
        $this->add_control('separator', [
            'label' => __('Separator', 'probuilder'),
            'type' => 'text',
            'default' => '/',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $home_text = $this->get_settings('home_text', 'Home');
        $separator = $this->get_settings('separator', '/');
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        $breadcrumbs = [];
        $breadcrumbs[] = '<a href="' . home_url('/') . '" style="color:#0073aa;text-decoration:none">' . esc_html($home_text) . '</a>';
        
        if (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                $breadcrumbs[] = '<a href="' . get_category_link($categories[0]) . '" style="color:#0073aa;text-decoration:none">' . $categories[0]->name . '</a>';
            }
            $breadcrumbs[] = '<span style="color:#666">' . get_the_title() . '</span>';
        } elseif (is_page()) {
            $breadcrumbs[] = '<span style="color:#666">' . get_the_title() . '</span>';
        } elseif (is_category()) {
            $breadcrumbs[] = '<span style="color:#666">' . single_cat_title('', false) . '</span>';
        }
        
        $style = 'font-size:14px;color:#666;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<nav class="' . esc_attr($wrapper_classes) . ' pb-breadcrumbs" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        echo implode(' <span style="color:#999;margin:0 8px">' . esc_html($separator) . '</span> ', $breadcrumbs);
        echo '</nav>';
    }
}

