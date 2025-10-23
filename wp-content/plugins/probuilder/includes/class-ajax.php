<?php
/**
 * AJAX Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Ajax {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Save page data
        add_action('wp_ajax_probuilder_save_page', [$this, 'save_page']);
        
        // Get element preview
        add_action('wp_ajax_probuilder_get_element_preview', [$this, 'get_element_preview']);
        
        // Upload image
        add_action('wp_ajax_probuilder_upload_image', [$this, 'upload_image']);
    }
    
    /**
     * Save page
     */
    public function save_page() {
        check_ajax_referer('probuilder_editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $elements = isset($_POST['elements']) ? $_POST['elements'] : [];
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        // Save ProBuilder data
        update_post_meta($post_id, '_probuilder_data', $elements);
        update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
        
        // Update post modified date
        wp_update_post(['ID' => $post_id, 'post_modified' => current_time('mysql')]);
        
        wp_send_json_success(['message' => __('Page saved successfully!', 'probuilder')]);
    }
    
    /**
     * Get element preview
     */
    public function get_element_preview() {
        check_ajax_referer('probuilder_editor', 'nonce');
        
        $widget_name = isset($_POST['widget_name']) ? sanitize_text_field($_POST['widget_name']) : '';
        $settings = isset($_POST['settings']) ? $_POST['settings'] : [];
        
        $widget = ProBuilder_Widgets_Manager::instance()->get_widget($widget_name);
        
        if (!$widget) {
            wp_send_json_error(['message' => __('Widget not found', 'probuilder')]);
        }
        
        ob_start();
        $widget->render_widget($settings);
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    /**
     * Upload image
     */
    public function upload_image() {
        check_ajax_referer('probuilder_editor', 'nonce');
        
        if (!current_user_can('upload_files')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        if (!isset($_FILES['file'])) {
            wp_send_json_error(['message' => __('No file uploaded', 'probuilder')]);
        }
        
        $file = $_FILES['file'];
        
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        
        $attachment_id = media_handle_upload('file', 0);
        
        if (is_wp_error($attachment_id)) {
            wp_send_json_error(['message' => $attachment_id->get_error_message()]);
        }
        
        $image_url = wp_get_attachment_url($attachment_id);
        
        wp_send_json_success([
            'id' => $attachment_id,
            'url' => $image_url
        ]);
    }
}

