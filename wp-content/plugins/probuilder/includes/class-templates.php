<?php
/**
 * Templates Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Templates {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Register template post type
        add_action('init', [$this, 'register_template_post_type']);
    }
    
    /**
     * Register template post type
     */
    public function register_template_post_type() {
        register_post_type('probuilder_template', [
            'labels' => [
                'name' => __('ProBuilder Templates', 'probuilder'),
                'singular_name' => __('Template', 'probuilder'),
            ],
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'capability_type' => 'page',
            'supports' => ['title'],
        ]);
    }
    
    /**
     * Get templates list
     */
    public function get_templates_list() {
        $templates = [];
        
        // Get saved templates
        $query = new WP_Query([
            'post_type' => 'probuilder_template',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $templates[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'data' => get_post_meta(get_the_ID(), '_probuilder_data', true),
                ];
            }
            wp_reset_postdata();
        }
        
        // Add predefined templates
        $templates = array_merge($templates, $this->get_predefined_templates());
        
        return $templates;
    }
    
    /**
     * Get predefined templates
     */
    private function get_predefined_templates() {
        return [
            [
                'id' => 'blank',
                'title' => __('Blank Page', 'probuilder'),
                'data' => [],
            ],
        ];
    }
    
    /**
     * Templates admin page
     */
    public function templates_page() {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('ProBuilder Templates', 'probuilder') . '</h1>';
        echo '<p>' . esc_html__('Manage your saved templates here.', 'probuilder') . '</p>';
        
        $templates = $this->get_templates_list();
        
        if (empty($templates)) {
            echo '<p>' . esc_html__('No templates found. Create your first template in the editor!', 'probuilder') . '</p>';
        } else {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>' . esc_html__('Template Name', 'probuilder') . '</th><th>' . esc_html__('Actions', 'probuilder') . '</th></tr></thead>';
            echo '<tbody>';
            
            foreach ($templates as $template) {
                if (is_numeric($template['id'])) {
                    echo '<tr>';
                    echo '<td>' . esc_html($template['title']) . '</td>';
                    echo '<td><a href="' . get_delete_post_link($template['id'], '', true) . '" class="button">' . esc_html__('Delete', 'probuilder') . '</a></td>';
                    echo '</tr>';
                }
            }
            
            echo '</tbody></table>';
        }
        
        echo '</div>';
    }
}

