<?php
/**
 * Table of Contents Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Table_Of_Contents_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'table-of-contents';
        $this->title = __('Table of Contents', 'probuilder');
        $this->icon = 'fa fa-list-ol';
        $this->category = 'wordpress';
        $this->keywords = ['toc', 'table', 'contents'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Table of Contents', 'probuilder'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        if (!is_single() && !is_page()) {
            echo '<p style="padding:20px;background:#f5f5f5">View on a post or page</p>';
            return;
        }
        
        $title = $this->get_settings('title', 'Table of Contents');
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $content = get_the_content();
        
        // Extract headings
        preg_match_all('/<h([2-6])>(.*?)<\/h[2-6]>/i', $content, $matches, PREG_SET_ORDER);
        
        if (empty($matches)) {
            echo '<p style="padding:20px;background:#f5f5f5">No headings found in content</p>';
            return;
        }
        
        $style = 'background:#f9f9f9;padding:20px;border-radius:8px;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-toc" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        if ($title) {
            echo '<h4 style="margin:0 0 15px;font-size:18px">' . esc_html($title) . '</h4>';
        }
        echo '<ol style="margin:0;padding-left:25px;line-height:2">';
        
        foreach ($matches as $match) {
            $text = strip_tags($match[2]);
            $id = sanitize_title($text);
            echo '<li><a href="#' . $id . '" style="color:#0073aa;text-decoration:none">' . esc_html($text) . '</a></li>';
        }
        
        echo '</ol></div>';
    }
}

