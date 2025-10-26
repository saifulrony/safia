<?php
/**
 * History Panel Class
 * Visual undo/redo timeline with state management
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_History_Panel {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_probuilder_get_history', [$this, 'ajax_get_history']);
        add_action('wp_ajax_probuilder_save_history_state', [$this, 'ajax_save_history_state']);
        add_action('wp_ajax_probuilder_restore_state', [$this, 'ajax_restore_state']);
        add_action('wp_ajax_probuilder_clear_history', [$this, 'ajax_clear_history']);
    }
    
    /**
     * Get history for post
     */
    public function ajax_get_history() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $history = get_post_meta($post_id, '_probuilder_history', true);
        
        if (!$history) {
            $history = [
                'states' => [],
                'current_index' => -1
            ];
        }
        
        wp_send_json_success($history);
    }
    
    /**
     * Save history state
     */
    public function ajax_save_history_state() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $state = json_decode(stripslashes($_POST['state']), true);
        $action_label = sanitize_text_field($_POST['action_label']);
        
        $history = get_post_meta($post_id, '_probuilder_history', true);
        
        if (!$history) {
            $history = [
                'states' => [],
                'current_index' => -1
            ];
        }
        
        // Remove states after current index (when making new changes after undo)
        if ($history['current_index'] < count($history['states']) - 1) {
            $history['states'] = array_slice($history['states'], 0, $history['current_index'] + 1);
        }
        
        // Add new state
        $history['states'][] = [
            'data' => $state,
            'action' => $action_label,
            'timestamp' => current_time('timestamp'),
            'preview' => $this->generate_preview($state)
        ];
        
        $history['current_index']++;
        
        // Limit history to last 50 states
        if (count($history['states']) > 50) {
            $history['states'] = array_slice($history['states'], -50);
            $history['current_index'] = 49;
        }
        
        update_post_meta($post_id, '_probuilder_history', $history);
        
        wp_send_json_success([
            'index' => $history['current_index'],
            'total' => count($history['states'])
        ]);
    }
    
    /**
     * Restore state
     */
    public function ajax_restore_state() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $index = intval($_POST['index']);
        
        $history = get_post_meta($post_id, '_probuilder_history', true);
        
        if (!$history || !isset($history['states'][$index])) {
            wp_send_json_error(['message' => 'State not found']);
            return;
        }
        
        $state = $history['states'][$index]['data'];
        $history['current_index'] = $index;
        
        // Update post meta
        update_post_meta($post_id, '_probuilder_data', $state);
        update_post_meta($post_id, '_probuilder_history', $history);
        
        wp_send_json_success([
            'state' => $state,
            'index' => $index
        ]);
    }
    
    /**
     * Clear history
     */
    public function ajax_clear_history() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        
        delete_post_meta($post_id, '_probuilder_history');
        
        wp_send_json_success(['message' => 'History cleared']);
    }
    
    /**
     * Generate preview thumbnail for state
     */
    private function generate_preview($state) {
        // Generate simple text preview
        $preview = [];
        
        if (is_array($state)) {
            foreach ($state as $element) {
                if (isset($element['type'])) {
                    $preview[] = $element['type'];
                }
            }
        }
        
        return implode(', ', array_slice($preview, 0, 3));
    }
}

