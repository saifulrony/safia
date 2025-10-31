<?php
/**
 * Share Buttons Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Share_Buttons_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'share-buttons';
        $this->title = __('Share Buttons', 'probuilder');
        $this->icon = 'fa fa-share-alt';
        $this->category = 'content';
        $this->keywords = ['share', 'social', 'facebook', 'twitter'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('networks', [
            'label' => __('Networks', 'probuilder'),
            'type' => 'checkbox',
            'options' => [
                'facebook' => 'Facebook',
                'twitter' => 'Twitter',
                'linkedin' => 'LinkedIn',
                'pinterest' => 'Pinterest',
            ],
            'default' => ['facebook', 'twitter', 'linkedin'],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $networks = $this->get_settings('networks', ['facebook', 'twitter']);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $url = get_permalink();
        $title = get_the_title();
        
        $share_urls = [
            'facebook' => ['url' => 'https://www.facebook.com/sharer.php?u=' . urlencode($url), 'color' => '#1877f2', 'icon' => 'fa fa-facebook-f'],
            'twitter' => ['url' => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title), 'color' => '#1da1f2', 'icon' => 'fa fa-twitter'],
            'linkedin' => ['url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url), 'color' => '#0077b5', 'icon' => 'fa fa-linkedin-in'],
            'pinterest' => ['url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($url), 'color' => '#bd081c', 'icon' => 'fa fa-pinterest-p'],
        ];
        
        $style = 'display:flex;gap:10px;flex-wrap:wrap;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-share-buttons" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        
        foreach ($networks as $network) {
            if (isset($share_urls[$network])) {
                $data = $share_urls[$network];
                echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener" style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;background:' . $data['color'] . ';color:#fff;border-radius:50%;text-decoration:none;transition:transform 0.3s" onmouseover="this.style.transform=\'scale(1.1)\'" onmouseout="this.style.transform=\'scale(1)\'">';
                echo '<i class="' . $data['icon'] . '"></i>';
                echo '</a>';
            }
        }
        
        echo '</div>';
    }
}

