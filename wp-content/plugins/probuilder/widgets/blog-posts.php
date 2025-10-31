<?php
/**
 * Blog Posts Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Blog_Posts extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'blog-posts';
    }
    
    public function get_title() {
        return 'Blog Posts';
    }
    
    public function get_icon() {
        return 'fa fa-newspaper';
    }
    
    public function get_category() {
        return 'content';
    }
    
    public function get_keywords() {
        return ['blog', 'posts', 'articles', 'news', 'content'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('content_section', [
            'label' => 'Blog Posts Settings',
            'tab' => 'content'
        ]);
        
        $this->add_control('posts_per_page', [
            'label' => 'Number of Posts',
            'type' => 'slider',
            'range' => ['px' => ['min' => 1, 'max' => 12]],
            'default' => ['size' => 6]
        ]);
        
        $this->add_control('post_layout', [
            'label' => 'Layout',
            'type' => 'select',
            'options' => [
                'grid' => 'Grid',
                'list' => 'List',
                'masonry' => 'Masonry'
            ],
            'default' => 'grid'
        ]);
        
        $this->add_control('columns', [
            'label' => 'Columns',
            'type' => 'select',
            'options' => [
                '1' => '1 Column',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns'
            ],
            'default' => '3',
            'condition' => ['post_layout' => 'grid']
        ]);
        
        $this->add_control('show_image', [
            'label' => 'Show Featured Image',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('show_title', [
            'label' => 'Show Title',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('show_excerpt', [
            'label' => 'Show Excerpt',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('excerpt_length', [
            'label' => 'Excerpt Length',
            'type' => 'slider',
            'range' => ['px' => ['min' => 10, 'max' => 200]],
            'default' => ['size' => 100],
            'condition' => ['show_excerpt' => 'yes']
        ]);
        
        $this->add_control('show_meta', [
            'label' => 'Show Meta (Date, Author)',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('show_read_more', [
            'label' => 'Show Read More Button',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('read_more_text', [
            'label' => 'Read More Text',
            'type' => 'text',
            'default' => 'Read More',
            'condition' => ['show_read_more' => 'yes']
        ]);
        
        $this->add_control('category_filter', [
            'label' => 'Filter by Category',
            'type' => 'select',
            'options' => $this->get_categories_options(),
            'default' => ''
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('style_section', [
            'label' => 'Blog Style',
            'tab' => 'style'
        ]);
        
        $this->add_control('card_bg_color', [
            'label' => 'Card Background',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('card_border_radius', [
            'label' => 'Card Border Radius',
            'type' => 'slider',
            'range' => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 8]
        ]);
        
        $this->add_control('card_box_shadow', [
            'label' => 'Card Box Shadow',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => 'color',
            'default' => '#1e293b'
        ]);
        
        $this->add_control('excerpt_color', [
            'label' => 'Excerpt Color',
            'type' => 'color',
            'default' => '#64748b'
        ]);
        
        $this->add_control('meta_color', [
            'label' => 'Meta Color',
            'type' => 'color',
            'default' => '#94a3b8'
        ]);
        
        $this->add_control('read_more_bg_color', [
            'label' => 'Read More Background',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('read_more_text_color', [
            'label' => 'Read More Text Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->end_controls_section();
    }
    
    private function get_categories_options() {
        $categories = get_categories(['hide_empty' => false]);
        $options = ['' => 'All Categories'];
        
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }
        
        return $options;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page']['size'],
            'post_status' => 'publish'
        ];
        
        if (!empty($settings['category_filter'])) {
            $args['cat'] = $settings['category_filter'];
        }
        
        $posts = get_posts($args);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        if (empty($posts)) {
            $style = 'text-align: center; padding: 40px; color: #64748b;';
            if ($inline_styles) $style .= ' ' . $inline_styles;
            echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-blog-posts" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
            echo '<p>No posts found. Create some blog posts to display them here.</p>';
            echo '</div>';
            return;
        }
        
        $container_class = $wrapper_classes . ' probuilder-blog-posts probuilder-blog-' . esc_attr($settings['post_layout']);
        $grid_columns = $settings['post_layout'] === 'grid' ? $settings['columns'] : '1';
        
        $grid_style = 'display: grid; grid-template-columns: repeat(' . esc_attr($grid_columns) . ', 1fr); gap: 30px;';
        if ($inline_styles) $grid_style .= ' ' . $inline_styles;
        
        echo '<div class="' . esc_attr($container_class) . '" ' . $wrapper_attributes . ' style="' . esc_attr($grid_style) . '">';
        
        foreach ($posts as $post) {
            $this->render_post_card($post, $settings);
        }
        
        echo '</div>';
    }
    
    private function render_post_card($post, $settings) {
        $card_style = '';
        $card_style .= 'background-color: ' . esc_attr($settings['card_bg_color']) . ';';
        $card_style .= 'border-radius: ' . esc_attr($settings['card_border_radius']['size']) . 'px;';
        $card_style .= 'overflow: hidden;';
        
        if ($settings['card_box_shadow'] === 'yes') {
            $card_style .= 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);';
        }
        
        echo '<article class="probuilder-blog-post" style="' . $card_style . '">';
        
        // Featured Image
        if ($settings['show_image'] === 'yes') {
            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'large');
            if (!$thumbnail_url) {
                $thumbnail_url = 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800';
            }
            
            echo '<div class="probuilder-post-image" style="position: relative; height: 200px; background-image: url(' . esc_url($thumbnail_url) . '); background-size: cover; background-position: center;">';
            echo '<a href="' . esc_url(get_permalink($post->ID)) . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: block;"></a>';
            echo '</div>';
        }
        
        echo '<div class="probuilder-post-content" style="padding: 25px;">';
        
        // Meta Information
        if ($settings['show_meta'] === 'yes') {
            echo '<div class="probuilder-post-meta" style="margin-bottom: 15px; font-size: 14px; color: ' . esc_attr($settings['meta_color']) . ';">';
            echo '<span>' . esc_html(get_the_date('F j, Y', $post->ID)) . '</span>';
            echo ' • <span>' . esc_html(get_the_author_meta('display_name', $post->post_author)) . '</span>';
            echo '</div>';
        }
        
        // Title
        if ($settings['show_title'] === 'yes') {
            echo '<h3 class="probuilder-post-title" style="margin: 0 0 15px 0; font-size: 20px; line-height: 1.4;">';
            echo '<a href="' . esc_url(get_permalink($post->ID)) . '" style="color: ' . esc_attr($settings['title_color']) . '; text-decoration: none;">' . esc_html($post->post_title) . '</a>';
            echo '</h3>';
        }
        
        // Excerpt
        if ($settings['show_excerpt'] === 'yes') {
            $excerpt = $post->post_excerpt;
            if (empty($excerpt)) {
                $excerpt = wp_trim_words($post->post_content, $settings['excerpt_length']['size']);
            }
            
            echo '<div class="probuilder-post-excerpt" style="color: ' . esc_attr($settings['excerpt_color']) . '; line-height: 1.6; margin-bottom: 20px;">';
            echo esc_html($excerpt);
            echo '</div>';
        }
        
        // Read More Button
        if ($settings['show_read_more'] === 'yes') {
            echo '<a href="' . esc_url(get_permalink($post->ID)) . '" class="probuilder-read-more" style="display: inline-block; background-color: ' . esc_attr($settings['read_more_bg_color']) . '; color: ' . esc_attr($settings['read_more_text_color']) . '; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px; transition: all 0.3s ease;">' . esc_html($settings['read_more_text']) . '</a>';
        }
        
        echo '</div>';
        echo '</article>';
    }
}
