<?php
/**
 * Author Box Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Author_Box_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'author-box';
        $this->title = __('Author Box', 'probuilder');
        $this->icon = 'fa fa-user-circle';
        $this->category = 'wordpress';
        $this->keywords = ['author', 'bio', 'profile'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('show_avatar', [
            'label' => __('Show Avatar', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('avatar_size', [
            'label' => __('Avatar Size', 'probuilder'),
            'type' => 'slider',
            'default' => 80,
            'range' => ['px' => ['min' => 32, 'max' => 150]],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        if (!is_single()) {
            echo '<p style="padding:20px;background:#f5f5f5">View on a single post</p>';
            return;
        }
        
        $author_id = get_the_author_meta('ID');
        $show_avatar = $this->get_settings('show_avatar', true);
        $avatar_size = $this->get_settings('avatar_size', 80);
        
        echo '<div style="background:#f9f9f9;border:1px solid #eee;padding:30px;border-radius:8px;display:flex;gap:20px;align-items:center">';
        
        if ($show_avatar) {
            echo '<div style="flex-shrink:0">' . get_avatar($author_id, $avatar_size, '', '', ['style' => 'border-radius:50%']) . '</div>';
        }
        
        echo '<div style="flex:1">';
        echo '<h3 style="margin:0 0 10px;font-size:24px"><a href="' . get_author_posts_url($author_id) . '" style="color:#333;text-decoration:none">' . get_the_author() . '</a></h3>';
        
        $bio = get_the_author_meta('description');
        if ($bio) {
            echo '<p style="margin:0 0 15px;color:#666;line-height:1.6">' . esc_html($bio) . '</p>';
        }
        
        echo '<a href="' . get_author_posts_url($author_id) . '" style="color:#0073aa;text-decoration:none;font-weight:600">View All Posts →</a>';
        echo '</div>';
        echo '</div>';
    }
}

