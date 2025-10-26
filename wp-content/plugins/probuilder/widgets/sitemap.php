<?php
/**
 * Sitemap Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Sitemap_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'sitemap';
        $this->title = __('Sitemap', 'probuilder');
        $this->icon = 'fa fa-sitemap';
        $this->category = 'wordpress';
        $this->keywords = ['sitemap', 'site', 'structure'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('show_pages', [
            'label' => __('Show Pages', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_posts', [
            'label' => __('Show Posts', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('columns', [
            'label' => __('Columns', 'probuilder'),
            'type' => 'select',
            'options' => ['2' => '2', '3' => '3', '4' => '4'],
            'default' => '3',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $show_pages = $this->get_settings('show_pages', true);
        $show_posts = $this->get_settings('show_posts', true);
        $columns = $this->get_settings('columns', 3);
        
        echo '<div style="display:grid;grid-template-columns:repeat(' . $columns . ',1fr);gap:30px">';
        
        if ($show_pages) {
            echo '<div>';
            echo '<h3 style="margin:0 0 15px;font-size:20px;color:#333">Pages</h3>';
            echo '<ul style="list-style:none;padding:0;margin:0">';
            wp_list_pages(['title_li' => '', 'echo' => true]);
            echo '</ul>';
            echo '</div>';
        }
        
        if ($show_posts) {
            echo '<div>';
            echo '<h3 style="margin:0 0 15px;font-size:20px;color:#333">Recent Posts</h3>';
            echo '<ul style="list-style:none;padding:0;margin:0">';
            wp_get_archives(['type' => 'postbypost', 'limit' => 20, 'format' => 'html', 'echo' => true]);
            echo '</ul>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '<style>.pb-sitemap ul li{padding:5px 0}.pb-sitemap ul li a{color:#0073aa;text-decoration:none}.pb-sitemap ul li a:hover{text-decoration:underline}</style>';
    }
}

