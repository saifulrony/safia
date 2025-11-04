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
        // Content Section
        $this->start_controls_section('section_video', [
            'label' => __('Video', 'probuilder'),
            'tab' => 'content',
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
            'default' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
            'description' => __('Enter full YouTube URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)', 'probuilder'),
            'placeholder' => 'https://www.youtube.com/watch?v=...',
        ]);
        
        $this->add_control('vimeo_url', [
            'label' => __('Vimeo URL', 'probuilder'),
            'type' => 'text',
            'default' => '',
            'description' => __('Enter full Vimeo URL (e.g., https://vimeo.com/123456789)', 'probuilder'),
            'placeholder' => 'https://vimeo.com/...',
        ]);
        
        $this->add_control('self_url', [
            'label' => __('Video URL', 'probuilder'),
            'type' => 'url',
            'default' => '',
            'description' => __('Upload video to Media Library and paste URL', 'probuilder'),
        ]);
        
        $this->add_control('aspect_ratio', [
            'label' => __('Aspect Ratio', 'probuilder'),
            'type' => 'select',
            'default' => '16:9',
            'options' => [
                '16:9' => '16:9 (Widescreen)',
                '4:3' => '4:3 (Standard)',
                '21:9' => '21:9 (Ultra Wide)',
                '1:1' => '1:1 (Square)',
                '9:16' => '9:16 (Vertical/Stories)',
            ],
        ]);
        
        $this->add_control('autoplay', [
            'label' => __('Autoplay', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('mute', [
            'label' => __('Mute', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('loop', [
            'label' => __('Loop', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('controls', [
            'label' => __('Show Controls', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 8,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1
                ]
            ],
        ]);
        
        $this->add_control('box_shadow', [
            'label' => __('Box Shadow', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
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
        
        $video_type = $this->get_settings('video_type', 'youtube');
        $youtube_url = $this->get_settings('youtube_url', 'https://www.youtube.com/watch?v=ScMzIvxBSi4');
        $vimeo_url = $this->get_settings('vimeo_url', '');
        $self_url = $this->get_settings('self_url', '');
        $aspect_ratio = $this->get_settings('aspect_ratio', '16:9');
        $autoplay = $this->get_settings('autoplay', 'no') === 'yes';
        $mute = $this->get_settings('mute', 'no') === 'yes';
        $loop = $this->get_settings('loop', 'no') === 'yes';
        $controls = $this->get_settings('controls', 'yes') === 'yes';
        $border_radius = $this->get_settings('border_radius', 8);
        $box_shadow = $this->get_settings('box_shadow', 'yes') === 'yes';
        
        // Calculate padding based on aspect ratio
        $padding_map = [
            '16:9' => '56.25%',
            '4:3' => '75%',
            '21:9' => '42.85%',
            '1:1' => '100%',
            '9:16' => '177.78%',
        ];
        $padding_bottom = $padding_map[$aspect_ratio] ?? '56.25%';
        
        // Build wrapper style
        $wrapper_style = 'position: relative; padding-bottom: ' . esc_attr($padding_bottom) . '; height: 0; overflow: hidden; border-radius: ' . esc_attr($border_radius) . 'px;';
        if ($box_shadow) {
            $wrapper_style .= ' box-shadow: 0 4px 20px rgba(0,0,0,0.15);';
        }
        
        // Build video parameters
        $autoplay_param = $autoplay ? '1' : '0';
        $mute_param = $mute ? '1' : '0';
        $loop_param = $loop ? '1' : '0';
        $controls_param = $controls ? '1' : '0';
        
        if ($inline_styles) {
            $wrapper_style .= ' ' . $inline_styles;
        }
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-video" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '">';
        echo '<div class="probuilder-video-wrapper" style="' . esc_attr($wrapper_style) . '">';
        
        if ($video_type === 'youtube' && $youtube_url) {
            // Extract video ID from various YouTube URL formats
            $video_id = '';
            if (preg_match('/[?&]v=([^&]+)/', $youtube_url, $matches)) {
                $video_id = $matches[1];
            } elseif (preg_match('/youtu\.be\/([^?]+)/', $youtube_url, $matches)) {
                $video_id = $matches[1];
            } elseif (preg_match('/embed\/([^?]+)/', $youtube_url, $matches)) {
                $video_id = $matches[1];
            }
            
            if ($video_id) {
                $youtube_params = "autoplay={$autoplay_param}&mute={$mute_param}&loop={$loop_param}&controls={$controls_param}";
                if ($loop) {
                    $youtube_params .= "&playlist={$video_id}";
                }
                echo '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?' . $youtube_params . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
            } else {
                echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>';
            }
        } elseif ($video_type === 'vimeo' && $vimeo_url) {
            // Extract video ID from various Vimeo URL formats
            $video_id = '';
            if (preg_match('/vimeo\.com\/(\d+)/', $vimeo_url, $matches)) {
                $video_id = $matches[1];
            } elseif (preg_match('/player\.vimeo\.com\/video\/(\d+)/', $vimeo_url, $matches)) {
                $video_id = $matches[1];
            }
            
            if ($video_id) {
                $vimeo_params = "autoplay={$autoplay_param}&muted={$mute_param}&loop={$loop_param}&controls={$controls_param}";
                echo '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?' . $vimeo_params . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
            } else {
                echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>';
            }
        } elseif ($video_type === 'self' && $self_url) {
            $video_attrs = 'style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"';
            if ($autoplay) $video_attrs .= ' autoplay';
            if ($mute) $video_attrs .= ' muted';
            if ($loop) $video_attrs .= ' loop';
            if ($controls) $video_attrs .= ' controls';
            
            echo '<video ' . $video_attrs . '>';
            echo '<source src="' . esc_url($self_url) . '" type="video/mp4">';
            echo __('Your browser does not support the video tag.', 'probuilder');
            echo '</video>';
        } else {
            // No video URL provided - show placeholder
            echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">';
            echo '<i class="fa fa-play-circle" style="font-size: 72px; margin-bottom: 15px; opacity: 0.9;"></i>';
            echo '<div style="font-size: 18px; font-weight: 600;">Video Placeholder</div>';
            echo '<div style="font-size: 13px; margin-top: 8px; opacity: 0.8;">Enter a video URL to display</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

