<?php
/**
 * Post Navigation Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Post_Navigation_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'post-navigation';
        $this->title = __('Post Navigation', 'probuilder');
        $this->icon = 'fa fa-arrows-alt-h';
        $this->category = 'wordpress';
        $this->keywords = ['navigation', 'prev', 'next'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('show_thumbnails', [
            'label' => __('Show Thumbnails', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        if (!is_single()) {
            echo '<p style="padding:20px;background:#f5f5f5">View on a single post</p>';
            return;
        }
        
        $prev = get_previous_post();
        $next = get_next_post();
        $show_thumbs = $this->get_settings('show_thumbnails', true);
        
        echo '<div style="display:flex;gap:20px;justify-content:space-between">';
        
        if ($prev) {
            echo '<div style="flex:1;background:#f9f9f9;border-radius:8px;overflow:hidden">';
            echo '<a href="' . get_permalink($prev) . '" style="display:flex;gap:15px;padding:20px;text-decoration:none;color:#333;align-items:center">';
            if ($show_thumbs && has_post_thumbnail($prev)) {
                echo get_the_post_thumbnail($prev, 'thumbnail', ['style' => 'width:60px;height:60px;object-fit:cover;border-radius:4px']);
            }
            echo '<div><div style="color:#0073aa;font-size:12px;margin-bottom:5px">← Previous</div><h4 style="margin:0;font-size:16px">' . get_the_title($prev) . '</h4></div>';
            echo '</a></div>';
        }
        
        if ($next) {
            echo '<div style="flex:1;background:#f9f9f9;border-radius:8px;overflow:hidden">';
            echo '<a href="' . get_permalink($next) . '" style="display:flex;gap:15px;padding:20px;text-decoration:none;color:#333;align-items:center;flex-direction:row-reverse;text-align:right">';
            if ($show_thumbs && has_post_thumbnail($next)) {
                echo get_the_post_thumbnail($next, 'thumbnail', ['style' => 'width:60px;height:60px;object-fit:cover;border-radius:4px']);
            }
            echo '<div><div style="color:#0073aa;font-size:12px;margin-bottom:5px">Next →</div><h4 style="margin:0;font-size:16px">' . get_the_title($next) . '</h4></div>';
            echo '</a></div>';
        }
        
        echo '</div>';
    }
}

