<?php
/**
 * Archives Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Archives extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'archives';
        $this->title = __('Archives', 'probuilder');
        $this->icon = 'fa fa-archive';
        $this->category = 'wordpress';
        $this->keywords = ['archives', 'archive', 'date', 'posts', 'wordpress'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_archives', [
            'label' => __('Archives', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Archives', 'probuilder'),
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('type', [
            'label' => __('Archive Type', 'probuilder'),
            'type' => 'select',
            'default' => 'monthly',
            'options' => [
                'monthly' => __('Monthly', 'probuilder'),
                'yearly' => __('Yearly', 'probuilder'),
                'daily' => __('Daily', 'probuilder'),
                'weekly' => __('Weekly', 'probuilder'),
                'postbypost' => __('Post by Post', 'probuilder'),
            ],
        ]);
        
        $this->add_control('format', [
            'label' => __('Display Format', 'probuilder'),
            'type' => 'select',
            'default' => 'html',
            'options' => [
                'html' => __('HTML (List)', 'probuilder'),
                'option' => __('Dropdown', 'probuilder'),
                'custom' => __('Custom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('limit', [
            'label' => __('Limit', 'probuilder'),
            'type' => 'number',
            'default' => '',
            'placeholder' => 'All',
            'description' => __('Limit the number of archives to display (leave empty for all)', 'probuilder'),
        ]);
        
        $this->add_control('show_post_count', [
            'label' => __('Show Post Count', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('order', [
            'label' => __('Order', 'probuilder'),
            'type' => 'select',
            'default' => 'DESC',
            'options' => [
                'DESC' => __('Descending (Newest First)', 'probuilder'),
                'ASC' => __('Ascending (Oldest First)', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('title_size', [
            'label' => __('Title Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 24,
            'range' => [
                'px' => ['min' => 12, 'max' => 48, 'step' => 1],
            ],
        ]);
        
        $this->add_control('link_color', [
            'label' => __('Link Color', 'probuilder'),
            'type' => 'color',
            'default' => '#0073aa',
        ]);
        
        $this->add_control('link_hover_color', [
            'label' => __('Link Hover Color', 'probuilder'),
            'type' => 'color',
            'default' => '#005177',
        ]);
        
        $this->add_control('count_color', [
            'label' => __('Count Color', 'probuilder'),
            'type' => 'color',
            'default' => '#999999',
        ]);
        
        $this->add_control('text_size', [
            'label' => __('Text Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 15,
            'range' => [
                'px' => ['min' => 10, 'max' => 24, 'step' => 1],
            ],
        ]);
        
        $this->add_control('list_style', [
            'label' => __('List Style', 'probuilder'),
            'type' => 'select',
            'default' => 'default',
            'options' => [
                'default' => __('Default', 'probuilder'),
                'none' => __('None', 'probuilder'),
                'disc' => __('Disc', 'probuilder'),
                'circle' => __('Circle', 'probuilder'),
                'square' => __('Square', 'probuilder'),
            ],
        ]);
        
        $this->add_control('item_spacing', [
            'label' => __('Item Spacing (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 10,
            'range' => [
                'px' => ['min' => 0, 'max' => 30, 'step' => 1],
            ],
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
        
        $title = $this->get_settings('title', 'Archives');
        $show_title = $this->get_settings('show_title', 'yes');
        $type = $this->get_settings('type', 'monthly');
        $format = $this->get_settings('format', 'html');
        $limit = $this->get_settings('limit', '');
        $show_post_count = $this->get_settings('show_post_count', 'yes');
        $order = $this->get_settings('order', 'DESC');
        $title_color = $this->get_settings('title_color', '#333333');
        $title_size = $this->get_settings('title_size', 24);
        $link_color = $this->get_settings('link_color', '#0073aa');
        $link_hover_color = $this->get_settings('link_hover_color', '#005177');
        $count_color = $this->get_settings('count_color', '#999999');
        $text_size = $this->get_settings('text_size', 15);
        $list_style = $this->get_settings('list_style', 'default');
        $item_spacing = $this->get_settings('item_spacing', 10);
        
        $wrapper_style = 'padding: 20px;';
        if ($inline_styles) $wrapper_style .= ' ' . $inline_styles;
        
        $unique_id = 'archives-' . uniqid();
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-archives" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
        if ($show_title === 'yes' && !empty($title)) {
            echo '<h3 style="margin: 0 0 20px 0; font-size: ' . esc_attr($title_size) . 'px; color: ' . esc_attr($title_color) . '; font-weight: 600;">' . esc_html($title) . '</h3>';
        }
        
        // Custom styles for this widget
        echo '<style>';
        echo '.probuilder-archives#' . esc_attr($unique_id) . ' ul { margin: 0; padding: 0; }';
        if ($list_style !== 'default') {
            echo '.probuilder-archives#' . esc_attr($unique_id) . ' ul { list-style-type: ' . esc_attr($list_style) . '; }';
            if ($list_style !== 'none') {
                echo '.probuilder-archives#' . esc_attr($unique_id) . ' ul { padding-left: 20px; }';
            }
        }
        echo '.probuilder-archives#' . esc_attr($unique_id) . ' li { margin-bottom: ' . esc_attr($item_spacing) . 'px; font-size: ' . esc_attr($text_size) . 'px; }';
        echo '.probuilder-archives#' . esc_attr($unique_id) . ' a { color: ' . esc_attr($link_color) . '; text-decoration: none; transition: color 0.3s; }';
        echo '.probuilder-archives#' . esc_attr($unique_id) . ' a:hover { color: ' . esc_attr($link_hover_color) . '; }';
        echo '.probuilder-archives#' . esc_attr($unique_id) . ' .post-count { color: ' . esc_attr($count_color) . '; margin-left: 5px; }';
        echo '.probuilder-archives#' . esc_attr($unique_id) . ' select { width: 100%; padding: 10px; font-size: ' . esc_attr($text_size) . 'px; border: 1px solid #ddd; border-radius: 4px; }';
        echo '</style>';
        
        echo '<div id="' . esc_attr($unique_id) . '" class="probuilder-archives">';
        
        // Build args for wp_get_archives
        $args = [
            'type' => $type,
            'format' => $format,
            'show_post_count' => $show_post_count === 'yes' ? 1 : 0,
            'order' => $order,
            'echo' => 1,
        ];
        
        if (!empty($limit)) {
            $args['limit'] = intval($limit);
        }
        
        // Handle dropdown format
        if ($format === 'option') {
            echo '<label class="screen-reader-text" for="' . esc_attr($unique_id) . '-dropdown">' . esc_html($title) . '</label>';
            echo '<select id="' . esc_attr($unique_id) . '-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">';
            echo '<option value="">' . esc_html__('Select Month', 'probuilder') . '</option>';
            
            $args['format'] = 'option';
            wp_get_archives($args);
            
            echo '</select>';
        } else {
            // HTML list format
            wp_get_archives($args);
        }
        
        echo '</div>';
        echo '</div>';
    }
}

// Register widget
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Widget_Archives());

