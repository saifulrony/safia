<?php
/**
 * Safia Theme License Manager
 * 
 * Handles theme license activation, validation, and security
 * 
 * @package Safia
 * @subpackage Security
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Safia_Theme_License_Manager {
    
    private static $instance = null;
    
    private $license_key_option = 'safia_theme_license_key';
    private $license_status_option = 'safia_theme_license_status';
    private $license_data_option = 'safia_theme_license_data';
    private $activation_date_option = 'safia_theme_activation_date';
    private $install_hash_option = 'safia_theme_install_hash';
    
    // Your license server URL
    private $license_server = 'https://yourdomain.com/license-api/';
    
    // Unique product identifier
    private $product_id = 'safia-theme-pro';
    
    // Company information
    private $company_name = 'Safia Technologies';
    private $company_website = 'https://safia.com';
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
        $this->verify_integrity();
        $this->check_license_status();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('after_switch_theme', [$this, 'on_theme_activation']);
        add_action('admin_init', [$this, 'validate_license_activation']);
        add_action('admin_notices', [$this, 'license_notices']);
        add_action('wp_ajax_safia_activate_license', [$this, 'ajax_activate_license']);
        add_action('wp_ajax_safia_deactivate_license', [$this, 'ajax_deactivate_license']);
        
        // Check license status daily
        add_action('safia_daily_license_check', [$this, 'check_license_status']);
        if (!wp_next_scheduled('safia_daily_license_check')) {
            wp_schedule_event(time(), 'daily', 'safia_daily_license_check');
        }
    }
    
    /**
     * On theme activation
     */
    public function on_theme_activation() {
        // Generate unique installation hash
        $install_hash = $this->generate_install_hash();
        update_option($this->install_hash_option, $install_hash);
        
        // Record activation date
        update_option($this->activation_date_option, current_time('mysql'));
        
        // Verify file integrity
        $this->verify_integrity();
    }
    
    /**
     * Generate unique installation hash
     */
    private function generate_install_hash() {
        $site_url = get_site_url();
        $admin_email = get_option('admin_email');
        $install_time = time();
        
        return hash('sha256', $site_url . $admin_email . $install_time . $this->product_id);
    }
    
    /**
     * Get current installation hash
     */
    public function get_install_hash() {
        $hash = get_option($this->install_hash_option);
        if (!$hash) {
            $hash = $this->generate_install_hash();
            update_option($this->install_hash_option, $hash);
        }
        return $hash;
    }
    
    /**
     * Verify code integrity
     */
    public function verify_integrity() {
        $theme_dir = get_template_directory();
        
        $critical_files = [
            $theme_dir . '/functions.php',
            $theme_dir . '/inc/class-theme-license-manager.php',
            $theme_dir . '/style.css',
        ];
        
        $integrity_check = get_option('safia_theme_integrity_hash', '');
        $current_hash = $this->calculate_files_hash($critical_files);
        
        if (empty($integrity_check)) {
            update_option('safia_theme_integrity_hash', $current_hash);
        } elseif ($integrity_check !== $current_hash) {
            $this->log_security_event('integrity_check_failed', 'Critical theme files have been modified');
        }
        
        return true;
    }
    
    /**
     * Calculate hash of files
     */
    private function calculate_files_hash($files) {
        $combined = '';
        foreach ($files as $file) {
            if (file_exists($file)) {
                $combined .= md5_file($file);
            }
        }
        return hash('sha256', $combined);
    }
    
    /**
     * Check license status
     */
    public function check_license_status() {
        $license_key = get_option($this->license_key_option);
        
        if (empty($license_key)) {
            update_option($this->license_status_option, 'invalid');
            return false;
        }
        
        $response = $this->call_license_server('check_license', [
            'license_key' => $license_key,
            'product_id' => $this->product_id,
            'domain' => get_site_url(),
            'install_hash' => $this->get_install_hash(),
        ]);
        
        if ($response && isset($response['status'])) {
            update_option($this->license_status_option, $response['status']);
            update_option($this->license_data_option, $response);
            return $response['status'] === 'active';
        }
        
        return false;
    }
    
    /**
     * Is license active
     */
    public function is_license_active() {
        $status = get_option($this->license_status_option, 'invalid');
        return $status === 'active';
    }
    
    /**
     * Get license data
     */
    public function get_license_data() {
        return get_option($this->license_data_option, []);
    }
    
    /**
     * Activate license
     */
    public function activate_license($license_key) {
        $response = $this->call_license_server('activate_license', [
            'license_key' => $license_key,
            'product_id' => $this->product_id,
            'domain' => get_site_url(),
            'install_hash' => $this->get_install_hash(),
            'site_data' => $this->get_site_data(),
        ]);
        
        if ($response && isset($response['status']) && $response['status'] === 'active') {
            update_option($this->license_key_option, $license_key);
            update_option($this->license_status_option, 'active');
            update_option($this->license_data_option, $response);
            
            $this->log_security_event('license_activated', 'License key activated successfully');
            
            return ['success' => true, 'message' => 'License activated successfully!'];
        }
        
        $error_message = isset($response['message']) ? $response['message'] : 'Failed to activate license';
        return ['success' => false, 'message' => $error_message];
    }
    
    /**
     * Deactivate license
     */
    public function deactivate_license() {
        $license_key = get_option($this->license_key_option);
        
        $response = $this->call_license_server('deactivate_license', [
            'license_key' => $license_key,
            'product_id' => $this->product_id,
            'domain' => get_site_url(),
            'install_hash' => $this->get_install_hash(),
        ]);
        
        delete_option($this->license_key_option);
        update_option($this->license_status_option, 'deactivated');
        
        $this->log_security_event('license_deactivated', 'License key deactivated');
        
        return ['success' => true, 'message' => 'License deactivated successfully'];
    }
    
    /**
     * Call license server
     */
    private function call_license_server($action, $data = []) {
        $data['timestamp'] = time();
        $data['signature'] = $this->generate_signature($data);
        
        $response = wp_remote_post($this->license_server . $action, [
            'body' => $data,
            'timeout' => 15,
            'headers' => [
                'X-Product-ID' => $this->product_id,
                'X-Company' => $this->company_name,
            ],
        ]);
        
        if (is_wp_error($response)) {
            $this->log_security_event('license_server_error', $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
    
    /**
     * Generate security signature
     */
    private function generate_signature($data) {
        $secret = 'safia_theme_' . wp_salt('auth');
        ksort($data);
        $string = implode('|', $data);
        return hash_hmac('sha256', $string, $secret);
    }
    
    /**
     * Get site data
     */
    private function get_site_data() {
        global $wp_version;
        
        return [
            'wp_version' => $wp_version,
            'php_version' => PHP_VERSION,
            'theme_version' => wp_get_theme()->get('Version'),
            'site_url' => get_site_url(),
            'admin_email' => get_option('admin_email'),
            'site_name' => get_bloginfo('name'),
        ];
    }
    
    /**
     * Validate license activation
     */
    public function validate_license_activation() {
        if (!$this->is_license_active()) {
            return false;
        }
        return true;
    }
    
    /**
     * Admin notices
     */
    public function license_notices() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $status = get_option($this->license_status_option, 'invalid');
        
        if ($status !== 'active') {
            $screen = get_current_screen();
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong>Safia Theme License Required:</strong> 
                    Please activate your license to access all features and receive updates.
                    <a href="<?php echo admin_url('themes.php?page=safia-license'); ?>" class="button button-primary" style="margin-left: 10px;">
                        Activate License
                    </a>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * AJAX: Activate license
     */
    public function ajax_activate_license() {
        check_ajax_referer('safia_license_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        
        $license_key = sanitize_text_field($_POST['license_key']);
        
        if (empty($license_key)) {
            wp_send_json_error(['message' => 'Please enter a license key']);
        }
        
        $result = $this->activate_license($license_key);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX: Deactivate license
     */
    public function ajax_deactivate_license() {
        check_ajax_referer('safia_license_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        
        $result = $this->deactivate_license();
        wp_send_json_success($result);
    }
    
    /**
     * Log security event
     */
    private function log_security_event($event_type, $message) {
        $log_entry = [
            'timestamp' => current_time('mysql'),
            'event' => $event_type,
            'message' => $message,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_id' => get_current_user_id(),
        ];
        
        $logs = get_option('safia_theme_security_logs', []);
        $logs[] = $log_entry;
        
        if (count($logs) > 100) {
            $logs = array_slice($logs, -100);
        }
        
        update_option('safia_theme_security_logs', $logs);
    }
    
    /**
     * Get company information
     */
    public function get_company_info() {
        return [
            'name' => $this->company_name,
            'website' => $this->company_website,
            'product_id' => $this->product_id,
        ];
    }
}

