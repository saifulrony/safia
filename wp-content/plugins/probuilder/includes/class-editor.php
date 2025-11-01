<?php
/**
 * Editor Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Editor {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'init_editor']);
        add_action('template_redirect', [$this, 'maybe_load_editor']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_editor_scripts'], 999);
    }
    
    /**
     * Initialize editor
     */
    public function init_editor() {
        if (!$this->is_editor_active()) {
            return;
        }
        
        // Remove admin bar
        add_filter('show_admin_bar', '__return_false');
    }
    
    /**
     * Maybe load editor
     */
    public function maybe_load_editor() {
        if (!$this->is_editor_active()) {
            return;
        }
        
        // Setup query
        global $wp_query;
        
        // Get post ID
        $post_id = 0;
        if (isset($_GET['p'])) {
            $post_id = intval($_GET['p']);
        } elseif (isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
        }
        
        if ($post_id > 0) {
            // Setup the post
            $wp_query->query_vars['p'] = $post_id;
            $wp_query->is_single = true;
            $wp_query->is_singular = true;
            
            // Load the post
            $post = get_post($post_id);
            if ($post) {
                $GLOBALS['post'] = $post;
                setup_postdata($post);
            }
        }
        
        // Change template
        add_filter('template_include', [$this, 'editor_template'], 99999);
    }
    
    /**
     * Check if editor is active
     */
    public function is_editor_active() {
        if (!isset($_GET['probuilder']) || $_GET['probuilder'] !== 'true') {
            return false;
        }
        
        // Get post ID from various sources
        $post_id = 0;
        
        // Try from query var 'p'
        if (isset($_GET['p'])) {
            $post_id = intval($_GET['p']);
        }
        
        // Try from global $post
        if (!$post_id) {
            global $post;
            if ($post && isset($post->ID)) {
                $post_id = $post->ID;
            }
        }
        
        // Try from get_the_ID()
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        // Try from query var 'post'
        if (!$post_id && isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
        }
        
        return $post_id > 0;
    }
    
    /**
     * Editor template
     */
    public function editor_template($template) {
        return PROBUILDER_PATH . 'templates/editor.php';
    }
    
    /**
     * Enqueue editor scripts
     */
    public function enqueue_editor_scripts() {
        if (!$this->is_editor_active()) {
            return;
        }
        
        // Remove all other styles and scripts
        global $wp_styles, $wp_scripts;
        
        // Get current post ID properly
        $post_id = get_the_ID();
        if (!$post_id && isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
        }
        // Also support ?p=ID when opening editor from frontend
        if (!$post_id && isset($_GET['p'])) {
            $post_id = intval($_GET['p']);
        }
        
        // Dequeue unnecessary scripts that might conflict
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style');
        
        // Enqueue our styles - Use plugin version for caching
        wp_enqueue_style('probuilder-editor', PROBUILDER_URL . 'assets/css/editor.css', [], PROBUILDER_VERSION);
        wp_enqueue_style('probuilder-sidebar-toggle', PROBUILDER_URL . 'assets/css/sidebar-toggle.css', ['probuilder-editor'], PROBUILDER_VERSION);
        wp_enqueue_style('probuilder-container-columns', PROBUILDER_URL . 'assets/css/container-column-selector.css', ['probuilder-editor'], PROBUILDER_VERSION);
        wp_enqueue_style('probuilder-templates', PROBUILDER_URL . 'assets/css/templates.css', ['probuilder-editor'], PROBUILDER_VERSION);
        wp_enqueue_style('probuilder-navigator', PROBUILDER_URL . 'assets/css/navigator.css', ['probuilder-editor'], PROBUILDER_VERSION);
        wp_enqueue_style('probuilder-history-panel', PROBUILDER_URL . 'assets/css/history-panel.css', ['probuilder-editor'], PROBUILDER_VERSION);
        wp_enqueue_style('probuilder-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
        wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', [], '4.1.1');
        
        // Enqueue scripts - Use plugin version for caching
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('probuilder-editor-js', PROBUILDER_URL . 'assets/js/editor.js', ['jquery', 'jquery-ui-sortable'], PROBUILDER_VERSION, true);
        wp_enqueue_script('probuilder-templates-js', PROBUILDER_URL . 'assets/js/templates.js', ['jquery', 'probuilder-editor-js'], PROBUILDER_VERSION, true);
        wp_enqueue_script('probuilder-navigator-js', PROBUILDER_URL . 'assets/js/navigator.js', ['jquery', 'probuilder-editor-js'], PROBUILDER_VERSION, true);
        wp_enqueue_script('probuilder-history-js', PROBUILDER_URL . 'assets/js/history-panel.js', ['jquery', 'probuilder-editor-js'], PROBUILDER_VERSION, true);
        
        // Get saved ProBuilder data for this page
        $saved_elements = get_post_meta($post_id, '_probuilder_data', true);
        
        // Ensure it's an array
        if (!is_array($saved_elements)) {
            if (is_string($saved_elements)) {
                $decoded = json_decode($saved_elements, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $saved_elements = $decoded;
                } else {
                    $saved_elements = [];
                }
            } else {
                $saved_elements = [];
            }
        }
        
        // Localize script with all data INCLUDING saved elements
        wp_localize_script('probuilder-editor-js', 'ProBuilderEditor', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('probuilder-editor'),
            'post_id' => $post_id,
            'home_url' => home_url(),
            'site_url' => get_site_url(),
            'plugin_url' => PROBUILDER_URL,
            'widgets' => ProBuilder_Widgets_Manager::instance()->get_widgets_config(),
            'templates' => ProBuilder_Templates::instance()->get_templates_list(),
            'savedElements' => $saved_elements, // CRITICAL: Load saved elements!
            'i18n' => [
                'save' => __('Save', 'probuilder'),
                'preview' => __('Preview', 'probuilder'),
                'exit' => __('Exit', 'probuilder'),
                'add_element' => __('Add Element', 'probuilder'),
                'settings' => __('Settings', 'probuilder'),
                'style' => __('Style', 'probuilder'),
                'advanced' => __('Advanced', 'probuilder'),
            ],
            'debug' => true, // Enable debug mode
        ]);
    }
}

