<?php
/**
 * Error Handler and Logging System
 * Production-ready error handling
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Error_Handler {
    
    private static $instance = null;
    private $error_log_file;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->error_log_file = WP_CONTENT_DIR . '/probuilder-errors.log';
        
        // Set up error handling
        add_action('admin_init', [$this, 'init_error_handling']);
        add_action('admin_menu', [$this, 'add_error_log_menu']);
        add_action('wp_ajax_probuilder_clear_errors', [$this, 'ajax_clear_errors']);
    }
    
    /**
     * Initialize error handling
     */
    public function init_error_handling() {
        // Register custom error handler
        set_error_handler([$this, 'custom_error_handler'], E_ALL);
        register_shutdown_function([$this, 'handle_fatal_error']);
    }
    
    /**
     * Custom error handler
     */
    public function custom_error_handler($errno, $errstr, $errfile, $errline) {
        // Only log ProBuilder errors
        if (strpos($errfile, 'probuilder') === false) {
            return false;
        }
        
        $error_types = [
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_NOTICE => 'NOTICE',
            E_USER_ERROR => 'USER_ERROR',
            E_USER_WARNING => 'USER_WARNING',
            E_USER_NOTICE => 'USER_NOTICE',
        ];
        
        $type = isset($error_types[$errno]) ? $error_types[$errno] : 'UNKNOWN';
        
        $this->log_error($type, $errstr, $errfile, $errline);
        
        return true;
    }
    
    /**
     * Handle fatal errors
     */
    public function handle_fatal_error() {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            // Only log if it's from ProBuilder
            if (strpos($error['file'], 'probuilder') !== false) {
                $this->log_error('FATAL', $error['message'], $error['file'], $error['line']);
            }
        }
    }
    
    /**
     * Log error to file
     */
    public function log_error($type, $message, $file, $line, $context = []) {
        $log_entry = sprintf(
            "[%s] [%s] %s in %s on line %d\n",
            date('Y-m-d H:i:s'),
            $type,
            $message,
            $file,
            $line
        );
        
        if (!empty($context)) {
            $log_entry .= "Context: " . print_r($context, true) . "\n";
        }
        
        $log_entry .= "---\n";
        
        file_put_contents($this->error_log_file, $log_entry, FILE_APPEND);
    }
    
    /**
     * Get recent errors
     */
    public function get_recent_errors($limit = 50) {
        if (!file_exists($this->error_log_file)) {
            return [];
        }
        
        $content = file_get_contents($this->error_log_file);
        $entries = explode("---\n", $content);
        $entries = array_filter($entries);
        
        return array_slice(array_reverse($entries), 0, $limit);
    }
    
    /**
     * Clear error log
     */
    public function clear_errors() {
        if (file_exists($this->error_log_file)) {
            unlink($this->error_log_file);
        }
    }
    
    /**
     * Add error log menu
     */
    public function add_error_log_menu() {
        add_submenu_page(
            'probuilder',
            __('Error Log', 'probuilder'),
            __('Error Log', 'probuilder'),
            'manage_options',
            'probuilder-errors',
            [$this, 'error_log_page']
        );
    }
    
    /**
     * Error log page
     */
    public function error_log_page() {
        $errors = $this->get_recent_errors();
        
        echo '<div class="wrap">';
        echo '<h1>' . __('ProBuilder Error Log', 'probuilder') . '</h1>';
        
        if (!empty($errors)) {
            echo '<p><button class="button" id="pb-clear-errors">' . __('Clear All Errors', 'probuilder') . '</button></p>';
            echo '<div class="pb-error-log">';
            
            foreach ($errors as $error) {
                $class = 'notice';
                if (strpos($error, 'FATAL') !== false || strpos($error, 'ERROR') !== false) {
                    $class .= ' notice-error';
                } elseif (strpos($error, 'WARNING') !== false) {
                    $class .= ' notice-warning';
                } else {
                    $class .= ' notice-info';
                }
                
                echo '<div class="' . $class . '"><pre>' . esc_html($error) . '</pre></div>';
            }
            
            echo '</div>';
        } else {
            echo '<div class="notice notice-success"><p>' . __('No errors logged! Everything is running smoothly.', 'probuilder') . '</p></div>';
        }
        
        echo '</div>';
        
        // Add JS for clear button
        echo '<script>
        jQuery(document).ready(function($) {
            $("#pb-clear-errors").click(function() {
                if (confirm("' . __('Are you sure you want to clear all errors?', 'probuilder') . '")) {
                    $.post(ajaxurl, {
                        action: "probuilder_clear_errors",
                        _wpnonce: "' . wp_create_nonce('pb_clear_errors') . '"
                    }, function() {
                        location.reload();
                    });
                }
            });
        });
        </script>';
    }
    
    /**
     * AJAX: Clear errors
     */
    public function ajax_clear_errors() {
        check_ajax_referer('pb_clear_errors');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $this->clear_errors();
        wp_send_json_success();
    }
    
    /**
     * Try-catch wrapper for safe execution
     */
    public static function safe_execute($callback, $default = null) {
        try {
            return call_user_func($callback);
        } catch (Exception $e) {
            self::instance()->log_error(
                'EXCEPTION',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                ['trace' => $e->getTraceAsString()]
            );
            return $default;
        }
    }
}

