<?php
/**
 * WooCommerce Product Categories Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Woo_Categories extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'woo-categories';
    }
    
    public function get_title() {
        return __('Product Categories', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa-th-large';
    }
    
    public function get_category() {
        return 'content';
    }
    
    public function get_keywords() {
        return ['woocommerce', 'categories', 'shop', 'product categories'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('section_content', [
            'label' => __('Categories', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'grid',
            'options' => [
                'grid' => __('Grid', 'probuilder'),
                'list' => __('List', 'probuilder'),
            ],
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'default' => '4',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
        ]);
        
        $this->add_control('show_image', [
            'label' => __('Show Image', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_count', [
            'label' => __('Show Product Count', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('hide_empty', [
            'label' => __('Hide Empty Categories', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('image_height', [
            'label' => __('Image Height', 'probuilder'),
            'type' => 'slider',
            'default' => 200,
            'range' => [
                'px' => ['min' => 100, 'max' => 400, 'step' => 10],
            ],
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('column_gap', [
            'label' => __('Column Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('row_gap', [
            'label' => __('Row Gap', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'range' => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => ['min' => 0, 'max' => 50, 'step' => 1],
            ],
        ]);
        
        $this->add_control('card_bg_color', [
            'label' => __('Card Background', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#344047',
        ]);
        
        $this->add_control('count_color', [
            'label' => __('Count Color', 'probuilder'),
            'type' => 'color',
            'default' => '#6b7280',
        ]);
        
        $this->add_control('hover_effect', [
            'label' => __('Hover Effect', 'probuilder'),
            'type' => 'select',
            'default' => 'lift',
            'options' => [
                'none' => __('None', 'probuilder'),
                'lift' => __('Lift Up', 'probuilder'),
                'scale' => __('Scale', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS
        $this->render_custom_css();
        
        // Get wrapper classes and attributes
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        if (!class_exists('WooCommerce')) {
            echo '<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">';
            echo '<i class="dashicons dashicons-warning" style="font-size: 48px;"></i>';
            echo '<p style="margin: 10px 0 0; font-weight: 600;">WooCommerce is not installed or activated.</p>';
            echo '</div>';
            return;
        }
        
        $hide_empty = $this->get_settings('hide_empty');
        $hide_empty = ($hide_empty === 'no') ? false : true;
        
        $args = [
            'taxonomy' => 'product_cat',
            'hide_empty' => $hide_empty,
            'orderby' => 'name',
            'order' => 'ASC',
        ];
        
        $categories = get_terms($args);
        
        if (is_wp_error($categories) || empty($categories)) {
            echo '<div style="padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #cbd5e1; border-radius: 8px; color: #6b7280;">';
            echo '<i class="dashicons dashicons-category" style="font-size: 48px; opacity: 0.3;"></i>';
            echo '<p style="margin: 10px 0 0; font-weight: 600;">No categories found</p>';
            echo '</div>';
            return;
        }
        
        $columns = intval($this->get_settings('columns', 4));
        $column_gap = intval($this->get_settings('column_gap', 20));
        $row_gap = intval($this->get_settings('row_gap', 20));
        $border_radius = intval($this->get_settings('border_radius', 8));
        $card_bg = $this->get_settings('card_bg_color', '#ffffff');
        $title_color = $this->get_settings('title_color', '#344047');
        $count_color = $this->get_settings('count_color', '#6b7280');
        $image_height = intval($this->get_settings('image_height', 200));
        $show_image = $this->get_settings('show_image');
        $show_image = ($show_image === 'no') ? false : true;
        $show_count = $this->get_settings('show_count');
        $show_count = ($show_count === 'no') ? false : true;
        $hover_effect = $this->get_settings('hover_effect', 'lift');
        
        $hover_style = '';
        if ($hover_effect === 'lift') {
            $hover_style = '.probuilder-woo-categories .category-item:hover { transform: translateY(-5px); }';
        } elseif ($hover_effect === 'scale') {
            $hover_style = '.probuilder-woo-categories .category-item:hover { transform: scale(1.05); }';
        }
        
        echo '<style>
            .probuilder-woo-categories .category-item {
                transition: all 0.3s ease;
            }
            ' . $hover_style . '
            .probuilder-woo-categories .category-item a {
                text-decoration: none;
            }
            .probuilder-woo-categories .category-item a:hover {
                opacity: 0.8;
            }
        </style>';
        
        $grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: ' . esc_attr($row_gap) . 'px ' . esc_attr($column_gap) . 'px;';
        if ($inline_styles) $grid_style .= ' ' . $inline_styles;
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-woo-categories" ' . $wrapper_attributes . ' style="' . esc_attr($grid_style) . '">';
        
        foreach ($categories as $category) {
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
            $category_link = get_term_link($category);
            
            echo '<div class="category-item" style="text-align: center; padding: 20px; background: ' . esc_attr($card_bg) . '; border-radius: ' . esc_attr($border_radius) . 'px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
            
            if ($show_image) {
                if ($image) {
                    echo '<a href="' . esc_url($category_link) . '">';
                    echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" style="width: 100%; height: ' . esc_attr($image_height) . 'px; object-fit: cover; border-radius: 4px; margin-bottom: 15px; display: block;">';
                    echo '</a>';
                } else {
                    // Placeholder with category initial
                    $initial = strtoupper(substr($category->name, 0, 1));
                    $colors = ['#92003b', '#667eea', '#4facfe', '#764ba2', '#f093fb'];
                    $color = $colors[abs(crc32($category->name)) % count($colors)];
                    echo '<a href="' . esc_url($category_link) . '">';
                    echo '<div style="width: 100%; height: ' . esc_attr($image_height) . 'px; background: ' . $color . '; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; font-weight: 700;">' . $initial . '</div>';
                    echo '</a>';
                }
            }
            
            echo '<h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 600; line-height: 1.4;"><a href="' . esc_url($category_link) . '" style="color: ' . esc_attr($title_color) . ';">' . esc_html($category->name) . '</a></h3>';
            
            if ($show_count) {
                echo '<p style="margin: 0; font-size: 14px; color: ' . esc_attr($count_color) . ';">' . sprintf(_n('%s product', '%s products', $category->count, 'probuilder'), $category->count) . '</p>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
    }
}

