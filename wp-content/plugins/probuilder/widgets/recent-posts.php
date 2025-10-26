<?php
/**
 * Recent Posts Widget
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Recent_Posts_Widget extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'recent-posts';
    }
    
    public function get_title() {
        return __('Recent Posts', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa fa-newspaper';
    }
    
    public function get_categories() {
        return ['wordpress'];
    }
    
    public function get_keywords() {
        return ['posts', 'recent', 'blog', 'latest'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('posts_count', [
            'label' => __('Number of Posts', 'probuilder'),
            'type' => 'number',
            'default' => 5,
            'min' => 1,
            'max' => 20
        ]);
        
        $this->add_control('show_image', [
            'label' => __('Show Featured Image', 'probuilder'),
            'type' => 'switcher',
            'default' => true
        ]);
        
        $this->add_control('image_size', [
            'label' => __('Image Size', 'probuilder'),
            'type' => 'select',
            'default' => 'thumbnail',
            'options' => [
                'thumbnail' => 'Thumbnail',
                'medium' => 'Medium',
                'large' => 'Large'
            ]
        ]);
        
        $this->add_control('show_date', [
            'label' => __('Show Date', 'probuilder'),
            'type' => 'switcher',
            'default' => true
        ]);
        
        $this->add_control('show_excerpt', [
            'label' => __('Show Excerpt', 'probuilder'),
            'type' => 'switcher',
            'default' => false
        ]);
        
        $this->add_control('excerpt_length', [
            'label' => __('Excerpt Length', 'probuilder'),
            'type' => 'number',
            'default' => 15,
            'min' => 5,
            'max' => 100
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'probuilder'),
            'type' => 'color',
            'default' => '#1a1a1a'
        ]);
        
        $this->add_control('date_color', [
            'label' => __('Date Color', 'probuilder'),
            'type' => 'color',
            'default' => '#999999'
        ]);
        
        $this->add_control('gap', [
            'label' => __('Gap Between Posts', 'probuilder'),
            'type' => 'slider',
            'default' => 20,
            'min' => 0,
            'max' => 50
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->settings;
        
        // Get posts
        $args = [
            'post_type' => 'post',
            'posts_per_page' => isset($settings['posts_count']) ? $settings['posts_count'] : 5,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        
        $recent_posts = new WP_Query($args);
        
        if (!$recent_posts->have_posts()) {
            echo '<p>' . __('No posts found.', 'probuilder') . '</p>';
            return;
        }
        
        $show_image = isset($settings['show_image']) ? $settings['show_image'] : true;
        $show_date = isset($settings['show_date']) ? $settings['show_date'] : true;
        $show_excerpt = isset($settings['show_excerpt']) ? $settings['show_excerpt'] : false;
        $image_size = isset($settings['image_size']) ? $settings['image_size'] : 'thumbnail';
        $excerpt_length = isset($settings['excerpt_length']) ? $settings['excerpt_length'] : 15;
        $title_color = isset($settings['title_color']) ? $settings['title_color'] : '#1a1a1a';
        $date_color = isset($settings['date_color']) ? $settings['date_color'] : '#999999';
        $gap = isset($settings['gap']) ? $settings['gap'] : 20;
        
        echo '<div class="probuilder-recent-posts">';
        
        while ($recent_posts->have_posts()) {
            $recent_posts->the_post();
            
            echo '<div class="probuilder-recent-post-item" style="display: flex; gap: 15px; margin-bottom: ' . $gap . 'px; align-items: flex-start;">';
            
            // Featured Image
            if ($show_image && has_post_thumbnail()) {
                echo '<div class="post-thumbnail" style="flex-shrink: 0;">';
                echo get_the_post_thumbnail(get_the_ID(), $image_size, [
                    'style' => 'width: 80px; height: 80px; object-fit: cover; border-radius: 6px; display: block;'
                ]);
                echo '</div>';
            }
            
            echo '<div class="post-content" style="flex: 1;">';
            
            // Title
            echo '<h4 class="post-title" style="margin: 0 0 8px 0; font-size: 16px; line-height: 1.4;">';
            echo '<a href="' . esc_url(get_permalink()) . '" style="color: ' . esc_attr($title_color) . '; text-decoration: none; font-weight: 600;">';
            echo esc_html(get_the_title());
            echo '</a>';
            echo '</h4>';
            
            // Date
            if ($show_date) {
                echo '<div class="post-date" style="font-size: 13px; color: ' . esc_attr($date_color) . '; margin-bottom: 8px;">';
                echo '<i class="fa fa-calendar" style="margin-right: 5px;"></i>';
                echo get_the_date();
                echo '</div>';
            }
            
            // Excerpt
            if ($show_excerpt) {
                $excerpt = wp_trim_words(get_the_excerpt(), $excerpt_length, '...');
                echo '<div class="post-excerpt" style="font-size: 14px; color: #666; line-height: 1.5;">';
                echo esc_html($excerpt);
                echo '</div>';
            }
            
            echo '</div>'; // .post-content
            echo '</div>'; // .probuilder-recent-post-item
        }
        
        wp_reset_postdata();
        
        echo '</div>'; // .probuilder-recent-posts
    }
}

