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
        
        // Page settings
        add_action('wp_ajax_probuilder_get_page_settings', [$this, 'get_page_settings']);
        add_action('wp_ajax_probuilder_save_page_settings', [$this, 'save_page_settings']);
        
        // Get element preview
        add_action('wp_ajax_probuilder_get_element_preview', [$this, 'get_element_preview']);
        
        // Upload image
        add_action('wp_ajax_probuilder_upload_image', [$this, 'upload_image']);
    }
    
    /**
     * Get page settings
     */
    public function get_page_settings() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        $post = get_post($post_id);
        
        if (!$post) {
            wp_send_json_error(['message' => __('Post not found', 'probuilder')]);
        }
        
        wp_send_json_success([
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'permalink' => get_permalink($post_id),
            'site_url' => home_url()
        ]);
    }
    
    /**
     * Save page settings (title and slug)
     */
    public function save_page_settings() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $slug = isset($_POST['slug']) ? sanitize_title($_POST['slug']) : '';
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        if (empty($title)) {
            wp_send_json_error(['message' => __('Title cannot be empty', 'probuilder')]);
        }
        
        if (empty($slug)) {
            wp_send_json_error(['message' => __('URL cannot be empty', 'probuilder')]);
        }
        
        // Store original slug to check if it was changed
        $original_slug = $slug;
        
        // Check if slug already exists (for different post)
        $existing_post = get_page_by_path($slug, OBJECT, get_post_type($post_id));
        if ($existing_post && $existing_post->ID != $post_id) {
            // Slug exists, make it unique
            $slug = wp_unique_post_slug($slug, $post_id, get_post_status($post_id), get_post_type($post_id), 0);
            error_log("ProBuilder: Duplicate slug detected. Changed '$original_slug' to '$slug' for Post #$post_id");
        }
        
        // Update post
        $result = wp_update_post([
            'ID' => $post_id,
            'post_title' => $title,
            'post_name' => $slug
        ], true);
        
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }
        
        // Flush rewrite rules to ensure URL changes take effect
        flush_rewrite_rules(false);
        
        // Clear post cache
        clean_post_cache($post_id);
        
        // Prepare success message
        $message = __('Page settings updated successfully!', 'probuilder');
        if ($original_slug !== $slug) {
            $message .= ' ' . __('Note: URL was changed to avoid duplicate.', 'probuilder');
        }
        
        wp_send_json_success([
            'message' => $message,
            'title' => $title,
            'slug' => $slug,
            'original_slug' => $original_slug,
            'slug_changed' => ($original_slug !== $slug),
            'permalink' => get_permalink($post_id)
        ]);
    }
    
    /**
     * Save page
     */
    public function save_page() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $elements = isset($_POST['elements']) ? $_POST['elements'] : '[]';
        
        // Debug logging (only when WP_DEBUG is enabled)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ProBuilder Save - POST keys: ' . implode(', ', array_keys($_POST)));
            error_log('ProBuilder Save - Elements raw length: ' . (is_string($elements) ? strlen($elements) : 'not a string'));
        }
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        // Ensure post exists and is editable
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(['message' => __('Post not found', 'probuilder')]);
        }
        
        // Parse and validate elements
        if (is_string($elements)) {
            // CRITICAL FIX: WordPress adds slashes to POST data, we need to remove them!
            // This prevents JSON decode errors when WordPress magic quotes are enabled
            $elements = stripslashes($elements);
            
            $decoded = json_decode($elements, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $elements = $decoded;
            } else {
                // If JSON decode fails, log error (only in debug mode)
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('ProBuilder Save - JSON decode failed: ' . json_last_error_msg());
                }
                $elements = [];
            }
        }
        
        // Ensure elements is an array
        if (!is_array($elements)) {
            $elements = [];
        }
        
        // Debug logging (only when WP_DEBUG is enabled)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ProBuilder Save - Post ID: ' . $post_id);
            error_log('ProBuilder Save - Elements count: ' . count($elements));
        }
        
        // If this content was created as a blog post by mistake, convert it to a Page
        if ($post->post_type !== 'page') {
            wp_update_post([
                'ID' => $post_id,
                'post_type' => 'page',
            ]);
            // Refresh $post after type change
            $post = get_post($post_id);
        }

        // Save ProBuilder data (save as array, WordPress will serialize it)
        $updated = update_post_meta($post_id, '_probuilder_data', $elements);
        update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
        
        // Ensure post has a unique slug before publishing
        $current_slug = $post->post_name;
        if (empty($current_slug) || $current_slug === 'auto-draft') {
            // Generate slug from title
            $current_slug = sanitize_title($post->post_title);
            if (empty($current_slug)) {
                $current_slug = 'page-' . $post_id;
            }
        }
        
        // Check for duplicate slugs and make unique if needed
        $existing_post = get_page_by_path($current_slug, OBJECT, $post->post_type);
        if ($existing_post && $existing_post->ID != $post_id) {
            $unique_slug = wp_unique_post_slug($current_slug, $post_id, 'publish', $post->post_type, 0);
            error_log("ProBuilder Save - Duplicate slug detected. Changed '$current_slug' to '$unique_slug' for Post #$post_id");
            $current_slug = $unique_slug;
        }
        
        // Ensure post status is published (not draft)
        if ($post->post_status === 'auto-draft' || $post->post_status === 'draft') {
            wp_update_post([
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_name' => $current_slug,
                'post_modified' => current_time('mysql')
            ]);
            error_log('ProBuilder Save - Post status changed to publish');
        } else {
            // Just update modified date and ensure slug is set
            wp_update_post([
                'ID' => $post_id, 
                'post_name' => $current_slug,
                'post_modified' => current_time('mysql')
            ]);
        }
        
        // Do not flush rewrite rules on every save; it's expensive and unnecessary
        // Slug/permalink changes are already handled in save_page_settings()
        
        // Clear any cache
        clean_post_cache($post_id);
        
        // Get permalink for response
        $permalink = get_permalink($post_id);
        
        error_log('ProBuilder Save - Meta updated: ' . ($updated ? 'YES' : 'NO (same data)'));
        error_log('ProBuilder Save - Permalink: ' . $permalink);
        
        wp_send_json_success([
            'message' => __('Page saved successfully!', 'probuilder'),
            'permalink' => $permalink,
            'element_count' => count($elements)
        ]);
    }
    
    /**
     * Get element preview
     */
    public function get_element_preview() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
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
        check_ajax_referer('probuilder-editor', 'nonce');
        
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

