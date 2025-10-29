<?php
/**
 * Templates Class - Enhanced with Categories, Import/Export
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
        // Register template post type and taxonomy
        add_action('init', [$this, 'register_template_post_type']);
        add_action('init', [$this, 'register_template_taxonomy']);
        
        // Add AJAX handlers for template operations
        add_action('wp_ajax_probuilder_save_template', [$this, 'ajax_save_template']);
        add_action('wp_ajax_probuilder_delete_template', [$this, 'ajax_delete_template']);
        add_action('wp_ajax_probuilder_export_template', [$this, 'ajax_export_template']);
        add_action('wp_ajax_probuilder_import_template', [$this, 'ajax_import_template']);
        add_action('wp_ajax_probuilder_duplicate_template', [$this, 'ajax_duplicate_template']);
    }
    
    /**
     * Register template post type
     */
    public function register_template_post_type() {
        register_post_type('probuilder_template', [
            'labels' => [
                'name' => __('ProBuilder Templates', 'probuilder'),
                'singular_name' => __('Template', 'probuilder'),
                'add_new' => __('Add New Template', 'probuilder'),
                'add_new_item' => __('Add New Template', 'probuilder'),
                'edit_item' => __('Edit Template', 'probuilder'),
                'view_item' => __('View Template', 'probuilder'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'capability_type' => 'page',
            'supports' => ['title', 'thumbnail'],
            'has_archive' => false,
        ]);
    }
    
    /**
     * Register template taxonomy for categories
     */
    public function register_template_taxonomy() {
        register_taxonomy('probuilder_template_category', 'probuilder_template', [
            'labels' => [
                'name' => __('Template Categories', 'probuilder'),
                'singular_name' => __('Template Category', 'probuilder'),
                'add_new_item' => __('Add New Category', 'probuilder'),
            ],
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_admin_column' => true,
        ]);
        
        // Add default categories if they don't exist
        $this->create_default_categories();
    }
    
    /**
     * Create default template categories
     */
    private function create_default_categories() {
        $categories = [
            'landing-pages' => __('Landing Pages', 'probuilder'),
            'headers' => __('Headers', 'probuilder'),
            'footers' => __('Footers', 'probuilder'),
            'content-blocks' => __('Content Blocks', 'probuilder'),
            'forms' => __('Forms', 'probuilder'),
            'pricing' => __('Pricing Tables', 'probuilder'),
            'testimonials' => __('Testimonials', 'probuilder'),
        ];
        
        foreach ($categories as $slug => $name) {
            if (!term_exists($slug, 'probuilder_template_category')) {
                wp_insert_term($name, 'probuilder_template_category', ['slug' => $slug]);
            }
        }
    }
    
    /**
     * Get templates list with category filter
     */
    public function get_templates_list($category = '') {
        $templates = [];
        
        $args = [
            'post_type' => 'probuilder_template',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];
        
        if (!empty($category)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'probuilder_template_category',
                    'field' => 'slug',
                    'terms' => $category,
                ],
            ];
        }
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $categories = wp_get_post_terms($post_id, 'probuilder_template_category', ['fields' => 'names']);
                
                $templates[] = [
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'data' => get_post_meta($post_id, '_probuilder_data', true),
                    'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
                    'categories' => $categories,
                    'author' => get_the_author(),
                    'date' => get_the_date(),
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
                'thumbnail' => '',
                'categories' => [],
                'type' => 'predefined',
            ],
        ];
    }
    
    /**
     * AJAX: Save template
     */
    public function ajax_save_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $template_name = isset($_POST['template_name']) ? sanitize_text_field($_POST['template_name']) : '';
        $template_data = isset($_POST['template_data']) ? $_POST['template_data'] : [];
        $template_category = isset($_POST['template_category']) ? sanitize_text_field($_POST['template_category']) : '';
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;
        
        if (empty($template_name)) {
            wp_send_json_error(['message' => __('Template name is required', 'probuilder')]);
        }
        
        $post_data = [
            'post_title' => $template_name,
            'post_type' => 'probuilder_template',
            'post_status' => 'publish',
        ];
        
        if ($template_id) {
            // Update existing template
            $post_data['ID'] = $template_id;
            $template_id = wp_update_post($post_data);
        } else {
            // Create new template
            $template_id = wp_insert_post($post_data);
        }
        
        if (is_wp_error($template_id)) {
            wp_send_json_error(['message' => $template_id->get_error_message()]);
        }
        
        // Save template data
        update_post_meta($template_id, '_probuilder_data', $template_data);
        
        // Set template category
        if (!empty($template_category)) {
            wp_set_object_terms($template_id, $template_category, 'probuilder_template_category');
        }
        
        wp_send_json_success([
            'message' => __('Template saved successfully!', 'probuilder'),
            'template_id' => $template_id,
        ]);
    }
    
    /**
     * AJAX: Delete template
     */
    public function ajax_delete_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('delete_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;
        
        if (!$template_id) {
            wp_send_json_error(['message' => __('Invalid template ID', 'probuilder')]);
        }
        
        $result = wp_delete_post($template_id, true);
        
        if (!$result) {
            wp_send_json_error(['message' => __('Failed to delete template', 'probuilder')]);
        }
        
        wp_send_json_success(['message' => __('Template deleted successfully!', 'probuilder')]);
    }
    
    /**
     * AJAX: Export template
     */
    public function ajax_export_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;
        
        if (!$template_id) {
            wp_send_json_error(['message' => __('Invalid template ID', 'probuilder')]);
        }
        
        $template_data = get_post_meta($template_id, '_probuilder_data', true);
        $template_title = get_the_title($template_id);
        $template_categories = wp_get_post_terms($template_id, 'probuilder_template_category', ['fields' => 'slugs']);
        
        $export_data = [
            'version' => PROBUILDER_VERSION,
            'title' => $template_title,
            'categories' => $template_categories,
            'data' => $template_data,
            'exported_at' => current_time('mysql'),
        ];
        
        wp_send_json_success([
            'export_data' => $export_data,
            'filename' => sanitize_file_name($template_title) . '-template.json',
        ]);
    }
    
    /**
     * AJAX: Import template
     */
    public function ajax_import_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $import_data = isset($_POST['import_data']) ? $_POST['import_data'] : '';
        
        if (empty($import_data)) {
            wp_send_json_error(['message' => __('No import data provided', 'probuilder')]);
        }
        
        // Decode JSON if string
        if (is_string($import_data)) {
            $import_data = json_decode($import_data, true);
        }
        
        if (!isset($import_data['title']) || !isset($import_data['data'])) {
            wp_send_json_error(['message' => __('Invalid template data', 'probuilder')]);
        }
        
        // Create new template
        $template_id = wp_insert_post([
            'post_title' => $import_data['title'] . ' (Imported)',
            'post_type' => 'probuilder_template',
            'post_status' => 'publish',
        ]);
        
        if (is_wp_error($template_id)) {
            wp_send_json_error(['message' => $template_id->get_error_message()]);
        }
        
        // Save template data
        update_post_meta($template_id, '_probuilder_data', $import_data['data']);
        
        // Set categories if available
        if (isset($import_data['categories']) && !empty($import_data['categories'])) {
            wp_set_object_terms($template_id, $import_data['categories'], 'probuilder_template_category');
        }
        
        wp_send_json_success([
            'message' => __('Template imported successfully!', 'probuilder'),
            'template_id' => $template_id,
        ]);
    }
    
    /**
     * AJAX: Duplicate template
     */
    public function ajax_duplicate_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;
        
        if (!$template_id) {
            wp_send_json_error(['message' => __('Invalid template ID', 'probuilder')]);
        }
        
        $original_post = get_post($template_id);
        
        if (!$original_post) {
            wp_send_json_error(['message' => __('Template not found', 'probuilder')]);
        }
        
        // Create duplicate
        $new_template_id = wp_insert_post([
            'post_title' => $original_post->post_title . ' (Copy)',
            'post_type' => 'probuilder_template',
            'post_status' => 'publish',
        ]);
        
        if (is_wp_error($new_template_id)) {
            wp_send_json_error(['message' => $new_template_id->get_error_message()]);
        }
        
        // Copy template data
        $template_data = get_post_meta($template_id, '_probuilder_data', true);
        update_post_meta($new_template_id, '_probuilder_data', $template_data);
        
        // Copy categories
        $categories = wp_get_object_terms($template_id, 'probuilder_template_category', ['fields' => 'slugs']);
        if (!empty($categories)) {
            wp_set_object_terms($new_template_id, $categories, 'probuilder_template_category');
        }
        
        wp_send_json_success([
            'message' => __('Template duplicated successfully!', 'probuilder'),
            'template_id' => $new_template_id,
        ]);
    }
    
    /**
     * Templates admin page - Enhanced with Categories and Import/Export
     */
    public function templates_page() {
        // Handle import
        if (isset($_POST['probuilder_import_template']) && isset($_FILES['template_file'])) {
            check_admin_referer('probuilder_import_template');
            $this->handle_template_import();
        }
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('ProBuilder Templates', 'probuilder') . '</h1>';
        
        // Import form
        echo '<div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">';
        echo '<h2>' . esc_html__('Import Template', 'probuilder') . '</h2>';
        echo '<form method="post" enctype="multipart/form-data">';
        wp_nonce_field('probuilder_import_template');
        echo '<input type="file" name="template_file" accept=".json" required>';
        echo ' <button type="submit" name="probuilder_import_template" class="button button-primary">' . esc_html__('Import Template', 'probuilder') . '</button>';
        echo '</form>';
        echo '</div>';
        
        // Get categories
        $categories = get_terms([
            'taxonomy' => 'probuilder_template_category',
            'hide_empty' => false,
        ]);
        
        // Category filter
        $current_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        
        echo '<div class="subsubsub">';
        echo '<a href="' . admin_url('admin.php?page=probuilder-templates') . '" ' . (empty($current_category) ? 'class="current"' : '') . '>' . esc_html__('All', 'probuilder') . '</a>';
        
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $active = $current_category === $category->slug ? 'class="current"' : '';
                echo ' | <a href="' . admin_url('admin.php?page=probuilder-templates&category=' . $category->slug) . '" ' . $active . '>' . esc_html($category->name) . ' (' . $category->count . ')</a>';
            }
        }
        echo '</div>';
        
        echo '<br style="clear: both;">';
        
        $templates = $this->get_templates_list($current_category);
        
        if (empty($templates)) {
            echo '<p>' . esc_html__('No templates found. Create your first template in the editor!', 'probuilder') . '</p>';
        } else {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>';
            echo '<th>' . esc_html__('Template Name', 'probuilder') . '</th>';
            echo '<th>' . esc_html__('Category', 'probuilder') . '</th>';
            echo '<th>' . esc_html__('Author', 'probuilder') . '</th>';
            echo '<th>' . esc_html__('Date', 'probuilder') . '</th>';
            echo '<th>' . esc_html__('Actions', 'probuilder') . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            
            foreach ($templates as $template) {
                if (is_numeric($template['id'])) {
                    echo '<tr>';
                    echo '<td><strong>' . esc_html($template['title']) . '</strong></td>';
                    echo '<td>' . (isset($template['categories']) ? implode(', ', $template['categories']) : '-') . '</td>';
                    echo '<td>' . (isset($template['author']) ? esc_html($template['author']) : '-') . '</td>';
                    echo '<td>' . (isset($template['date']) ? esc_html($template['date']) : '-') . '</td>';
                    echo '<td>';
                    echo '<a href="' . admin_url('post.php?post=' . $template['id'] . '&action=edit') . '" class="button button-small">' . esc_html__('Edit', 'probuilder') . '</a> ';
                    echo '<button class="button button-small probuilder-export-template" data-template-id="' . esc_attr($template['id']) . '">' . esc_html__('Export', 'probuilder') . '</button> ';
                    echo '<a href="' . get_delete_post_link($template['id'], '', true) . '" class="button button-small" onclick="return confirm(\'' . esc_js__('Are you sure?', 'probuilder') . '\')">' . esc_html__('Delete', 'probuilder') . '</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            
            echo '</tbody></table>';
        }
        
        echo '</div>';
        
        // Add export functionality JavaScript
        echo '<script>
        jQuery(document).ready(function($) {
            $(".probuilder-export-template").on("click", function() {
                var templateId = $(this).data("template-id");
                
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "probuilder_export_template",
                        template_id: templateId,
                        nonce: "' . wp_create_nonce('probuilder_editor') . '"
                    },
                    success: function(response) {
                        if (response.success) {
                            var dataStr = JSON.stringify(response.data.export_data, null, 2);
                            var dataUri = "data:application/json;charset=utf-8," + encodeURIComponent(dataStr);
                            var downloadLink = document.createElement("a");
                            downloadLink.setAttribute("href", dataUri);
                            downloadLink.setAttribute("download", response.data.filename);
                            downloadLink.click();
                        } else {
                            alert(response.data.message);
                        }
                    }
                });
            });
        });
        </script>';
    }
    
    /**
     * Handle template file import
     */
    private function handle_template_import() {
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied', 'probuilder'));
        }
        
        $file = $_FILES['template_file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo '<div class="notice notice-error"><p>' . esc_html__('File upload error', 'probuilder') . '</p></div>';
            return;
        }
        
        $file_content = file_get_contents($file['tmp_name']);
        $import_data = json_decode($file_content, true);
        
        if (!$import_data || !isset($import_data['title']) || !isset($import_data['data'])) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Invalid template file', 'probuilder') . '</p></div>';
            return;
        }
        
        // Create template
        $template_id = wp_insert_post([
            'post_title' => $import_data['title'] . ' (Imported)',
            'post_type' => 'probuilder_template',
            'post_status' => 'publish',
        ]);
        
        if (is_wp_error($template_id)) {
            echo '<div class="notice notice-error"><p>' . $template_id->get_error_message() . '</p></div>';
            return;
        }
        
        // Save data
        update_post_meta($template_id, '_probuilder_data', $import_data['data']);
        
        // Set categories
        if (isset($import_data['categories']) && !empty($import_data['categories'])) {
            wp_set_object_terms($template_id, $import_data['categories'], 'probuilder_template_category');
        }
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Template imported successfully!', 'probuilder') . '</p></div>';
    }
}

