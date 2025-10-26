<?php
/**
 * Backup Manager
 * Backup and restore page content
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Backup_Manager {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('save_post', [$this, 'auto_backup'], 10, 3);
        add_action('admin_menu', [$this, 'add_backup_menu']);
        add_action('wp_ajax_probuilder_restore_backup', [$this, 'ajax_restore_backup']);
        add_action('wp_ajax_probuilder_delete_backup', [$this, 'ajax_delete_backup']);
    }
    
    /**
     * Auto backup on save
     */
    public function auto_backup($post_id, $post, $update) {
        // Only backup if it's an update (not new post)
        if (!$update) {
            return;
        }
        
        // Check if it's a ProBuilder page
        $is_probuilder = get_post_meta($post_id, '_probuilder_edit_mode', true);
        if (!$is_probuilder) {
            return;
        }
        
        // Create backup
        $this->create_backup($post_id);
    }
    
    /**
     * Create backup
     */
    public function create_backup($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            return false;
        }
        
        $backup_data = [
            'content' => $post->post_content,
            'meta' => get_post_meta($post_id),
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
        ];
        
        $backups = get_option('probuilder_backups_' . $post_id, []);
        
        // Add new backup
        array_unshift($backups, $backup_data);
        
        // Keep only last 10 backups
        $backups = array_slice($backups, 0, 10);
        
        update_option('probuilder_backups_' . $post_id, $backups);
        
        return true;
    }
    
    /**
     * Get backups for a post
     */
    public function get_backups($post_id) {
        return get_option('probuilder_backups_' . $post_id, []);
    }
    
    /**
     * Restore backup
     */
    public function restore_backup($post_id, $backup_index) {
        $backups = $this->get_backups($post_id);
        
        if (!isset($backups[$backup_index])) {
            return false;
        }
        
        $backup = $backups[$backup_index];
        
        // Update post content
        wp_update_post([
            'ID' => $post_id,
            'post_content' => $backup['content'],
        ]);
        
        // Restore meta data
        if (isset($backup['meta'])) {
            foreach ($backup['meta'] as $key => $values) {
                delete_post_meta($post_id, $key);
                foreach ($values as $value) {
                    add_post_meta($post_id, $key, maybe_unserialize($value));
                }
            }
        }
        
        return true;
    }
    
    /**
     * Delete backup
     */
    public function delete_backup($post_id, $backup_index) {
        $backups = $this->get_backups($post_id);
        
        if (isset($backups[$backup_index])) {
            unset($backups[$backup_index]);
            $backups = array_values($backups);
            update_option('probuilder_backups_' . $post_id, $backups);
            return true;
        }
        
        return false;
    }
    
    /**
     * Add backup menu
     */
    public function add_backup_menu() {
        add_submenu_page(
            'probuilder',
            __('Backups', 'probuilder'),
            __('Backups', 'probuilder'),
            'edit_pages',
            'probuilder-backups',
            [$this, 'backups_page']
        );
    }
    
    /**
     * Backups page
     */
    public function backups_page() {
        $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
        
        echo '<div class="wrap">';
        echo '<h1>' . __('ProBuilder Backups', 'probuilder') . '</h1>';
        
        if ($post_id) {
            $this->show_post_backups($post_id);
        } else {
            $this->show_backup_list();
        }
        
        echo '</div>';
    }
    
    /**
     * Show backup list
     */
    private function show_backup_list() {
        global $wpdb;
        
        // Get all posts with ProBuilder content
        $posts = $wpdb->get_results("
            SELECT p.ID, p.post_title 
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE pm.meta_key = '_probuilder_edit_mode'
            AND p.post_status = 'publish'
            ORDER BY p.post_modified DESC
            LIMIT 50
        ");
        
        if (empty($posts)) {
            echo '<p>' . __('No ProBuilder pages found', 'probuilder') . '</p>';
            return;
        }
        
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>' . __('Page', 'probuilder') . '</th>';
        echo '<th>' . __('Backups', 'probuilder') . '</th>';
        echo '<th>' . __('Last Modified', 'probuilder') . '</th>';
        echo '<th>' . __('Actions', 'probuilder') . '</th>';
        echo '</tr></thead><tbody>';
        
        foreach ($posts as $post) {
            $backups = $this->get_backups($post->ID);
            $backup_count = count($backups);
            
            echo '<tr>';
            echo '<td><strong>' . esc_html($post->post_title) . '</strong></td>';
            echo '<td>' . sprintf(_n('%s backup', '%s backups', $backup_count, 'probuilder'), $backup_count) . '</td>';
            echo '<td>' . get_the_modified_date('', $post->ID) . '</td>';
            echo '<td><a href="?page=probuilder-backups&post_id=' . $post->ID . '" class="button">' . __('View Backups', 'probuilder') . '</a></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
    
    /**
     * Show post backups
     */
    private function show_post_backups($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            echo '<p>' . __('Post not found', 'probuilder') . '</p>';
            return;
        }
        
        echo '<p><a href="?page=probuilder-backups" class="button">« ' . __('Back to List', 'probuilder') . '</a></p>';
        echo '<h2>' . sprintf(__('Backups for: %s', 'probuilder'), esc_html($post->post_title)) . '</h2>';
        
        $backups = $this->get_backups($post_id);
        
        if (empty($backups)) {
            echo '<p>' . __('No backups found', 'probuilder') . '</p>';
            return;
        }
        
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>' . __('#', 'probuilder') . '</th>';
        echo '<th>' . __('Date', 'probuilder') . '</th>';
        echo '<th>' . __('User', 'probuilder') . '</th>';
        echo '<th>' . __('Actions', 'probuilder') . '</th>';
        echo '</tr></thead><tbody>';
        
        foreach ($backups as $index => $backup) {
            $user = get_user_by('ID', $backup['user_id']);
            $user_name = $user ? $user->display_name : __('Unknown', 'probuilder');
            
            echo '<tr>';
            echo '<td>' . ($index + 1) . '</td>';
            echo '<td>' . $backup['timestamp'] . '</td>';
            echo '<td>' . esc_html($user_name) . '</td>';
            echo '<td>';
            echo '<button class="button pb-restore-backup" data-post-id="' . $post_id . '" data-index="' . $index . '">' . __('Restore', 'probuilder') . '</button> ';
            echo '<button class="button pb-delete-backup" data-post-id="' . $post_id . '" data-index="' . $index . '">' . __('Delete', 'probuilder') . '</button>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        
        // Add JS
        echo '<script>
        jQuery(document).ready(function($) {
            $(".pb-restore-backup").click(function() {
                if (!confirm("' . __('Are you sure you want to restore this backup?', 'probuilder') . '")) return;
                
                var postId = $(this).data("post-id");
                var index = $(this).data("index");
                
                $.post(ajaxurl, {
                    action: "probuilder_restore_backup",
                    post_id: postId,
                    index: index,
                    _wpnonce: "' . wp_create_nonce('pb_restore_backup') . '"
                }, function(response) {
                    if (response.success) {
                        alert("' . __('Backup restored successfully!', 'probuilder') . '");
                        location.reload();
                    } else {
                        alert("' . __('Error restoring backup', 'probuilder') . '");
                    }
                });
            });
            
            $(".pb-delete-backup").click(function() {
                if (!confirm("' . __('Are you sure you want to delete this backup?', 'probuilder') . '")) return;
                
                var postId = $(this).data("post-id");
                var index = $(this).data("index");
                
                $.post(ajaxurl, {
                    action: "probuilder_delete_backup",
                    post_id: postId,
                    index: index,
                    _wpnonce: "' . wp_create_nonce('pb_delete_backup') . '"
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert("' . __('Error deleting backup', 'probuilder') . '");
                    }
                });
            });
        });
        </script>';
    }
    
    /**
     * AJAX: Restore backup
     */
    public function ajax_restore_backup() {
        check_ajax_referer('pb_restore_backup');
        
        $post_id = intval($_POST['post_id']);
        $index = intval($_POST['index']);
        
        if (!current_user_can('edit_post', $post_id)) {
            wp_send_json_error('Unauthorized');
        }
        
        $result = $this->restore_backup($post_id, $index);
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Restore failed');
        }
    }
    
    /**
     * AJAX: Delete backup
     */
    public function ajax_delete_backup() {
        check_ajax_referer('pb_delete_backup');
        
        $post_id = intval($_POST['post_id']);
        $index = intval($_POST['index']);
        
        if (!current_user_can('edit_post', $post_id)) {
            wp_send_json_error('Unauthorized');
        }
        
        $result = $this->delete_backup($post_id, $index);
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Delete failed');
        }
    }
}

