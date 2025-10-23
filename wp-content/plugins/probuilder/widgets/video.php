<?php
/**
 * Video Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Video extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'video';
        $this->title = __('Video', 'probuilder');
        $this->icon = 'fa fa-video';
        $this->category = 'content';
        $this->keywords = ['video', 'youtube', 'vimeo', 'embed'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_video', [
            'label' => __('Video', 'probuilder'),
        ]);
        
        $this->add_control('video_type', [
            'label' => __('Video Type', 'probuilder'),
            'type' => 'select',
            'default' => 'youtube',
            'options' => [
                'youtube' => __('YouTube', 'probuilder'),
                'vimeo' => __('Vimeo', 'probuilder'),
                'self' => __('Self Hosted', 'probuilder'),
            ],
        ]);
        
        $this->add_control('youtube_url', [
            'label' => __('YouTube URL', 'probuilder'),
            'type' => 'text',
            'default' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
        
        $this->add_control('vimeo_url', [
            'label' => __('Vimeo URL', 'probuilder'),
            'type' => 'text',
            'default' => '',
        ]);
        
        $this->add_control('self_url', [
            'label' => __('Video URL', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->add_control('aspect_ratio', [
            'label' => __('Aspect Ratio', 'probuilder'),
            'type' => 'select',
            'default' => '16:9',
            'options' => [
                '16:9' => '16:9',
                '4:3' => '4:3',
                '21:9' => '21:9',
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $video_type = $this->get_settings('video_type', 'youtube');
        $youtube_url = $this->get_settings('youtube_url', '');
        $vimeo_url = $this->get_settings('vimeo_url', '');
        $self_url = $this->get_settings('self_url', '');
        $aspect_ratio = $this->get_settings('aspect_ratio', '16:9');
        
        $padding_bottom = '56.25%'; // 16:9
        if ($aspect_ratio === '4:3') {
            $padding_bottom = '75%';
        } elseif ($aspect_ratio === '21:9') {
            $padding_bottom = '42.85%';
        }
        
        echo '<div class="probuilder-video">';
        echo '<div class="probuilder-video-wrapper" style="position: relative; padding-bottom: ' . esc_attr($padding_bottom) . '; height: 0; overflow: hidden;">';
        
        if ($video_type === 'youtube' && $youtube_url) {
            // Extract video ID
            preg_match('/[?&]v=([^&]+)/', $youtube_url, $matches);
            $video_id = isset($matches[1]) ? $matches[1] : '';
            
            if ($video_id) {
                echo '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            }
        } elseif ($video_type === 'vimeo' && $vimeo_url) {
            // Extract video ID
            preg_match('/vimeo\.com\/(\d+)/', $vimeo_url, $matches);
            $video_id = isset($matches[1]) ? $matches[1] : '';
            
            if ($video_id) {
                echo '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
            }
        } elseif ($video_type === 'self' && $self_url) {
            echo '<video style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" controls>';
            echo '<source src="' . esc_url($self_url) . '" type="video/mp4">';
            echo __('Your browser does not support the video tag.', 'probuilder');
            echo '</video>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

