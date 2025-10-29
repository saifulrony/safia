<?php
/**
 * Cache Class
 * Handles caching of element data, rendered output, and assets for better performance
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Cache {
    
    private static $instance = null;
    
    /**
     * Cache group names
     */
    const CACHE_GROUP = 'probuilder';
    const ELEMENT_CACHE_GROUP = 'probuilder_elements';
    const RENDER_CACHE_GROUP = 'probuilder_render';
    const ASSET_CACHE_GROUP = 'probuilder_assets';
    
    /**
     * Cache expiration times (in seconds)
     */
    const ELEMENT_CACHE_EXPIRATION = 3600; // 1 hour
    const RENDER_CACHE_EXPIRATION = 1800;  // 30 minutes
    const ASSET_CACHE_EXPIRATION = 86400;  // 24 hours
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Clear cache on post save
        add_action('save_post', [$this, 'clear_post_cache']);
        add_action('probuilder_save_page', [$this, 'clear_post_cache']);
        
        // Clear cache on plugin update
        add_action('upgrader_process_complete', [$this, 'clear_all_cache'], 10, 2);
        
        // Add cache management page
        add_action('admin_menu', [$this, 'add_cache_settings'], 20);
        
        // Clear cache AJAX
        add_action('wp_ajax_probuilder_clear_cache', [$this, 'ajax_clear_cache']);
    }
    
    /**
     * Get cached element data
     */
    public function get_element_data($post_id) {
        if (!$this->is_cache_enabled()) {
            return false;
        }
        
        $cache_key = $this->get_element_cache_key($post_id);
        return wp_cache_get($cache_key, self::ELEMENT_CACHE_GROUP);
    }
    
    /**
     * Set element data cache
     */
    public function set_element_data($post_id, $data) {
        if (!$this->is_cache_enabled()) {
            return false;
        }
        
        $cache_key = $this->get_element_cache_key($post_id);
        return wp_cache_set($cache_key, $data, self::ELEMENT_CACHE_GROUP, self::ELEMENT_CACHE_EXPIRATION);
    }
    
    /**
     * Get cached rendered output
     */
    public function get_rendered_output($post_id) {
        if (!$this->is_cache_enabled()) {
            return false;
        }
        
        $cache_key = $this->get_render_cache_key($post_id);
        return wp_cache_get($cache_key, self::RENDER_CACHE_GROUP);
    }
    
    /**
     * Set rendered output cache
     */
    public function set_rendered_output($post_id, $output) {
        if (!$this->is_cache_enabled()) {
            return false;
        }
        
        $cache_key = $this->get_render_cache_key($post_id);
        return wp_cache_set($cache_key, $output, self::RENDER_CACHE_GROUP, self::RENDER_CACHE_EXPIRATION);
    }
    
    /**
     * Get cached asset list
     */
    public function get_asset_list($post_id) {
        if (!$this->is_cache_enabled()) {
            return false;
        }
        
        $cache_key = $this->get_asset_cache_key($post_id);
        return wp_cache_get($cache_key, self::ASSET_CACHE_GROUP);
    }
    
    /**
     * Set asset list cache
     */
    public function set_asset_list($post_id, $assets) {
        if (!$this->is_cache_enabled()) {
            return false;
        }
        
        $cache_key = $this->get_asset_cache_key($post_id);
        return wp_cache_set($cache_key, $assets, self::ASSET_CACHE_GROUP, self::ASSET_CACHE_EXPIRATION);
    }
    
    /**
     * Get element cache key
     */
    private function get_element_cache_key($post_id) {
        return 'element_data_' . $post_id;
    }
    
    /**
     * Get render cache key
     */
    private function get_render_cache_key($post_id) {
        return 'render_output_' . $post_id;
    }
    
    /**
     * Get asset cache key
     */
    private function get_asset_cache_key($post_id) {
        return 'asset_list_' . $post_id;
    }
    
    /**
     * Clear post-specific cache
     */
    public function clear_post_cache($post_id) {
        if (wp_is_post_revision($post_id)) {
            return;
        }
        
        // Clear element data cache
        $element_key = $this->get_element_cache_key($post_id);
        wp_cache_delete($element_key, self::ELEMENT_CACHE_GROUP);
        
        // Clear render cache
        $render_key = $this->get_render_cache_key($post_id);
        wp_cache_delete($render_key, self::RENDER_CACHE_GROUP);
        
        // Clear asset cache
        $asset_key = $this->get_asset_cache_key($post_id);
        wp_cache_delete($asset_key, self::ASSET_CACHE_GROUP);
        
        // Clear transients
        delete_transient('probuilder_data_' . $post_id);
        delete_transient('probuilder_render_' . $post_id);
    }
    
    /**
     * Clear all ProBuilder cache
     */
    public function clear_all_cache() {
        // Clear object cache groups
        wp_cache_flush_group(self::ELEMENT_CACHE_GROUP);
        wp_cache_flush_group(self::RENDER_CACHE_GROUP);
        wp_cache_flush_group(self::ASSET_CACHE_GROUP);
        
        // Clear all ProBuilder transients
        global $wpdb;
        $wpdb->query(
            "DELETE FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_probuilder_%' 
            OR option_name LIKE '_transient_timeout_probuilder_%'"
        );
        
        // Clear cached widget data
        wp_cache_delete('probuilder_widgets', self::CACHE_GROUP);
        
        // Clear template cache
        wp_cache_delete('probuilder_templates', self::CACHE_GROUP);
    }
    
    /**
     * Check if cache is enabled
     */
    public function is_cache_enabled() {
        return apply_filters('probuilder_cache_enabled', true);
    }
    
    /**
     * Add cache settings submenu
     */
    public function add_cache_settings() {
        add_submenu_page(
            'probuilder',
            __('Cache Settings', 'probuilder'),
            __('Cache', 'probuilder'),
            'manage_options',
            'probuilder-cache',
            [$this, 'cache_settings_page']
        );
    }
    
    /**
     * Cache settings page
     */
    public function cache_settings_page() {
        if (isset($_POST['probuilder_clear_cache'])) {
            check_admin_referer('probuilder_clear_cache');
            $this->clear_all_cache();
            echo '<div class="notice notice-success"><p>' . esc_html__('Cache cleared successfully!', 'probuilder') . '</p></div>';
        }
        
        // Get cache statistics
        $stats = $this->get_cache_statistics();
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('ProBuilder Cache Settings', 'probuilder') . '</h1>';
        
        // Cache statistics
        echo '<div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">';
        echo '<h2>' . esc_html__('Cache Statistics', 'probuilder') . '</h2>';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th>' . esc_html__('Element Cache Entries', 'probuilder') . '</th>';
        echo '<td>' . esc_html($stats['element_count']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . esc_html__('Render Cache Entries', 'probuilder') . '</th>';
        echo '<td>' . esc_html($stats['render_count']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . esc_html__('Cache Status', 'probuilder') . '</th>';
        echo '<td>' . ($this->is_cache_enabled() ? '<span style="color: green;">✓ ' . esc_html__('Enabled', 'probuilder') . '</span>' : '<span style="color: red;">✗ ' . esc_html__('Disabled', 'probuilder') . '</span>') . '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';
        
        // Cache management
        echo '<div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">';
        echo '<h2>' . esc_html__('Cache Management', 'probuilder') . '</h2>';
        echo '<p>' . esc_html__('Clear all ProBuilder cached data to free up memory and ensure fresh content.', 'probuilder') . '</p>';
        echo '<form method="post">';
        wp_nonce_field('probuilder_clear_cache');
        echo '<button type="submit" name="probuilder_clear_cache" class="button button-primary">' . esc_html__('Clear All Cache', 'probuilder') . '</button>';
        echo '</form>';
        echo '</div>';
        
        // Cache configuration
        echo '<div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">';
        echo '<h2>' . esc_html__('Cache Configuration', 'probuilder') . '</h2>';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th>' . esc_html__('Element Cache Duration', 'probuilder') . '</th>';
        echo '<td>' . esc_html(self::ELEMENT_CACHE_EXPIRATION / 60) . ' ' . esc_html__('minutes', 'probuilder') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . esc_html__('Render Cache Duration', 'probuilder') . '</th>';
        echo '<td>' . esc_html(self::RENDER_CACHE_EXPIRATION / 60) . ' ' . esc_html__('minutes', 'probuilder') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . esc_html__('Asset Cache Duration', 'probuilder') . '</th>';
        echo '<td>' . esc_html(self::ASSET_CACHE_EXPIRATION / 3600) . ' ' . esc_html__('hours', 'probuilder') . '</td>';
        echo '</tr>';
        echo '</table>';
        echo '<p class="description">' . esc_html__('Cache durations can be modified using filters in your theme or plugin.', 'probuilder') . '</p>';
        echo '</div>';
        
        echo '</div>';
    }
    
    /**
     * Get cache statistics
     */
    private function get_cache_statistics() {
        global $wpdb;
        
        // Count transients
        $element_count = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_probuilder_data_%'"
        );
        
        $render_count = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_probuilder_render_%'"
        );
        
        return [
            'element_count' => $element_count ?: 0,
            'render_count' => $render_count ?: 0,
        ];
    }
    
    /**
     * AJAX: Clear cache
     */
    public function ajax_clear_cache() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $this->clear_all_cache();
        
        wp_send_json_success(['message' => __('Cache cleared successfully!', 'probuilder')]);
    }
    
    /**
     * Get cached data with fallback to transients
     */
    public function get($key, $group = self::CACHE_GROUP) {
        // Try object cache first
        $data = wp_cache_get($key, $group);
        
        if ($data === false) {
            // Fallback to transients
            $data = get_transient($group . '_' . $key);
        }
        
        return $data;
    }
    
    /**
     * Set cached data with fallback to transients
     */
    public function set($key, $data, $group = self::CACHE_GROUP, $expiration = 3600) {
        // Set in object cache
        wp_cache_set($key, $data, $group, $expiration);
        
        // Also set as transient for persistence
        set_transient($group . '_' . $key, $data, $expiration);
        
        return true;
    }
    
    /**
     * Delete cached data
     */
    public function delete($key, $group = self::CACHE_GROUP) {
        // Delete from object cache
        wp_cache_delete($key, $group);
        
        // Delete transient
        delete_transient($group . '_' . $key);
        
        return true;
    }
}

