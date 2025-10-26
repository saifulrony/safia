<?php
/**
 * Loop Builder Widget - Fixed Version
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Loop_Builder_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'loop-builder';
        $this->title = __('Loop Builder', 'probuilder');
        $this->icon = 'fa fa-sync-alt';
        $this->category = 'advanced';
        $this->keywords = ['loop', 'query', 'dynamic', 'posts'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_query', [
            'label' => __('Query', 'probuilder'),
        ]);
        
        $this->add_control('post_type', [
            'label' => __('Post Type', 'probuilder'),
            'type' => 'select',
            'options' => [
                'post' => __('Posts', 'probuilder'),
                'page' => __('Pages', 'probuilder'),
                'product' => __('Products', 'probuilder'),
            ],
            'default' => 'post',
        ]);
        
        $this->add_control('posts_per_page', [
            'label' => __('Posts Per Page', 'probuilder'),
            'type' => 'number',
            'default' => 6,
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'default' => '3',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $post_type = $this->get_settings('post_type', 'post');
        $posts_per_page = $this->get_settings('posts_per_page', 6);
        $columns = $this->get_settings('columns', 3);
        
        $query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
        ]);
        
        if (!$query->have_posts()) {
            echo '<p>No posts found</p>';
            return;
        }
        
        $gap = 20;
        echo '<div style="display:grid;grid-template-columns:repeat(' . esc_attr($columns) . ',1fr);gap:' . $gap . 'px">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            echo '<div style="border:1px solid #eee;border-radius:8px;overflow:hidden;transition:box-shadow 0.3s">';
            
            if (has_post_thumbnail()) {
                echo '<a href="' . get_permalink() . '">';
                echo get_the_post_thumbnail(null, 'medium', ['style' => 'width:100%;height:auto;display:block']);
                echo '</a>';
            }
            
            echo '<div style="padding:20px">';
            echo '<h3 style="margin:0 0 10px;font-size:18px"><a href="' . get_permalink() . '" style="color:#333;text-decoration:none">' . get_the_title() . '</a></h3>';
            echo '<p style="color:#666;font-size:14px;line-height:1.6">' . wp_trim_words(get_the_excerpt(), 20) . '</p>';
            echo '<a href="' . get_permalink() . '" style="color:#0073aa;text-decoration:none;font-weight:600">Read More →</a>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
        
        wp_reset_postdata();
    }
}
