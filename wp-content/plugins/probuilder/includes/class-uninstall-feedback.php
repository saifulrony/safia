<?php
/**
 * Uninstall Feedback System
 * Collects feedback when users deactivate the plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Uninstall_Feedback {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Enqueue scripts on plugins page
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // AJAX handler to save feedback
        add_action('wp_ajax_probuilder_deactivate_feedback', [$this, 'save_feedback']);
        
        // Add admin menu to view feedback
        add_action('admin_menu', [$this, 'add_feedback_menu'], 100);
        
        // Create feedback table on plugin activation
        register_activation_hook(PROBUILDER_FILE, [$this, 'create_feedback_table']);
    }
    
    /**
     * Create database table for feedback
     */
    public function create_feedback_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'probuilder_feedback';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            reason varchar(255) NOT NULL,
            details text,
            site_url varchar(255),
            wp_version varchar(50),
            php_version varchar(50),
            plugin_version varchar(50),
            pages_created int(11) DEFAULT 0,
            user_email varchar(255),
            user_id bigint(20),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Enqueue scripts for feedback modal
     */
    public function enqueue_scripts($hook) {
        // Only on plugins page
        if ($hook !== 'plugins.php') {
            return;
        }
        
        // Enqueue styles
        wp_add_inline_style('wp-admin', $this->get_modal_styles());
        
        // Enqueue script
        wp_add_inline_script('jquery', $this->get_modal_script());
    }
    
    /**
     * Get modal styles
     */
    private function get_modal_styles() {
        return "
        /* ProBuilder Deactivation Feedback Modal */
        #probuilder-feedback-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999999;
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        #probuilder-feedback-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            z-index: 1000000;
            animation: slideIn 0.3s;
        }
        
        @keyframes slideIn {
            from { transform: translate(-50%, -60%); opacity: 0; }
            to { transform: translate(-50%, -50%); opacity: 1; }
        }
        
        .probuilder-feedback-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
        }
        
        .probuilder-feedback-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .probuilder-feedback-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .probuilder-feedback-body {
            padding: 30px;
        }
        
        .probuilder-feedback-reasons {
            margin-bottom: 20px;
        }
        
        .probuilder-feedback-reason {
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }
        
        .probuilder-feedback-reason:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        
        .probuilder-feedback-reason input[type='radio'] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            vertical-align: middle;
            cursor: pointer;
        }
        
        .probuilder-feedback-reason label {
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #344047;
            vertical-align: middle;
        }
        
        .probuilder-feedback-reason.selected {
            border-color: #667eea;
            background: #f0f4ff;
        }
        
        .probuilder-feedback-details {
            margin-top: 20px;
            display: none;
        }
        
        .probuilder-feedback-details.show {
            display: block;
        }
        
        .probuilder-feedback-details label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #344047;
            font-size: 14px;
        }
        
        .probuilder-feedback-details textarea {
            width: 100%;
            min-height: 100px;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
        }
        
        .probuilder-feedback-details textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .probuilder-feedback-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 12px 12px;
        }
        
        .probuilder-feedback-btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .probuilder-feedback-btn-skip {
            background: transparent;
            color: #6b7280;
        }
        
        .probuilder-feedback-btn-skip:hover {
            color: #344047;
        }
        
        .probuilder-feedback-btn-submit {
            background: #667eea;
            color: white;
        }
        
        .probuilder-feedback-btn-submit:hover {
            background: #5568d3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .probuilder-feedback-btn-submit:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .probuilder-feedback-loading {
            display: none;
            text-align: center;
            padding: 30px;
        }
        
        .probuilder-feedback-loading.show {
            display: block;
        }
        
        .probuilder-feedback-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        ";
    }
    
    /**
     * Get modal script
     */
    private function get_modal_script() {
        $nonce = wp_create_nonce('probuilder_deactivate_feedback');
        $ajax_url = admin_url('admin-ajax.php');
        
        return "
        jQuery(document).ready(function($) {
            // ProBuilder deactivation feedback
            var probuilderDeactivateLink = '';
            
            // Intercept deactivate link click
            $(document).on('click', 'tr[data-slug=\"probuilder\"] .deactivate a', function(e) {
                e.preventDefault();
                probuilderDeactivateLink = $(this).attr('href');
                showProBuilderFeedbackModal();
            });
            
            // Show feedback modal
            function showProBuilderFeedbackModal() {
                if ($('#probuilder-feedback-overlay').length === 0) {
                    createProBuilderFeedbackModal();
                }
                $('#probuilder-feedback-overlay').fadeIn(300);
            }
            
            // Create modal HTML
            function createProBuilderFeedbackModal() {
                var modalHTML = `
                    <div id=\"probuilder-feedback-overlay\">
                        <div id=\"probuilder-feedback-modal\">
                            <div class=\"probuilder-feedback-header\">
                                <h2>Quick Feedback</h2>
                                <p>We'd love to know why you're deactivating ProBuilder</p>
                            </div>
                            
                            <div class=\"probuilder-feedback-body\">
                                <div class=\"probuilder-feedback-reasons\">
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-1\" value=\"not-working\">
                                        <label for=\"reason-1\">It's not working / I found bugs</label>
                                    </div>
                                    
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-2\" value=\"missing-features\">
                                        <label for=\"reason-2\">Missing features I need</label>
                                    </div>
                                    
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-3\" value=\"too-complex\">
                                        <label for=\"reason-3\">Too complex / Hard to use</label>
                                    </div>
                                    
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-4\" value=\"found-better\">
                                        <label for=\"reason-4\">Found a better plugin</label>
                                    </div>
                                    
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-5\" value=\"temporary\">
                                        <label for=\"reason-5\">Temporary deactivation / Testing</label>
                                    </div>
                                    
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-6\" value=\"no-longer-needed\">
                                        <label for=\"reason-6\">No longer need it</label>
                                    </div>
                                    
                                    <div class=\"probuilder-feedback-reason\">
                                        <input type=\"radio\" name=\"probuilder_reason\" id=\"reason-7\" value=\"other\">
                                        <label for=\"reason-7\">Other reason</label>
                                    </div>
                                </div>
                                
                                <div class=\"probuilder-feedback-details\">
                                    <label>Please tell us more (optional):</label>
                                    <textarea id=\"probuilder-feedback-text\" placeholder=\"Your feedback helps us improve ProBuilder...\"></textarea>
                                </div>
                            </div>
                            
                            <div class=\"probuilder-feedback-footer\">
                                <button class=\"probuilder-feedback-btn probuilder-feedback-btn-skip\" id=\"probuilder-feedback-skip\">
                                    Skip & Deactivate
                                </button>
                                <button class=\"probuilder-feedback-btn probuilder-feedback-btn-submit\" id=\"probuilder-feedback-submit\" disabled>
                                    Submit & Deactivate
                                </button>
                            </div>
                            
                            <div class=\"probuilder-feedback-loading\">
                                <div class=\"probuilder-feedback-spinner\"></div>
                                <p>Sending feedback...</p>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(modalHTML);
                
                // Event listeners
                $('.probuilder-feedback-reason input').on('change', function() {
                    $('.probuilder-feedback-reason').removeClass('selected');
                    $(this).closest('.probuilder-feedback-reason').addClass('selected');
                    $('#probuilder-feedback-submit').prop('disabled', false);
                    $('.probuilder-feedback-details').addClass('show');
                });
                
                $('.probuilder-feedback-reason').on('click', function() {
                    $(this).find('input').prop('checked', true).trigger('change');
                });
                
                $('#probuilder-feedback-skip').on('click', function() {
                    closeModalAndDeactivate();
                });
                
                $('#probuilder-feedback-submit').on('click', function() {
                    submitFeedback();
                });
                
                // Close on overlay click
                $('#probuilder-feedback-overlay').on('click', function(e) {
                    if (e.target.id === 'probuilder-feedback-overlay') {
                        closeModalAndDeactivate();
                    }
                });
            }
            
            // Submit feedback
            function submitFeedback() {
                var reason = $('input[name=\"probuilder_reason\"]:checked').val();
                var details = $('#probuilder-feedback-text').val();
                
                if (!reason) {
                    closeModalAndDeactivate();
                    return;
                }
                
                // Show loading
                $('.probuilder-feedback-body, .probuilder-feedback-footer').hide();
                $('.probuilder-feedback-loading').addClass('show');
                
                // Send via AJAX
                $.ajax({
                    url: '" . $ajax_url . "',
                    type: 'POST',
                    data: {
                        action: 'probuilder_deactivate_feedback',
                        nonce: '" . $nonce . "',
                        reason: reason,
                        details: details
                    },
                    success: function(response) {
                        setTimeout(function() {
                            closeModalAndDeactivate();
                        }, 500);
                    },
                    error: function() {
                        // Even if AJAX fails, still deactivate
                        closeModalAndDeactivate();
                    }
                });
            }
            
            // Close modal and deactivate
            function closeModalAndDeactivate() {
                $('#probuilder-feedback-overlay').fadeOut(200, function() {
                    $(this).remove();
                    if (probuilderDeactivateLink) {
                        window.location.href = probuilderDeactivateLink;
                    }
                });
            }
        });
        ";
    }
    
    /**
     * Save feedback via AJAX
     */
    public function save_feedback() {
        check_ajax_referer('probuilder_deactivate_feedback', 'nonce');
        
        if (!current_user_can('activate_plugins')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';
        $details = isset($_POST['details']) ? sanitize_textarea_field($_POST['details']) : '';
        
        if (empty($reason)) {
            wp_send_json_error(['message' => 'Reason is required']);
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'probuilder_feedback';
        
        // Count ProBuilder pages
        $pages_created = $wpdb->get_var("
            SELECT COUNT(DISTINCT post_id) 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_probuilder_data'
        ");
        
        // Get current user
        $current_user = wp_get_current_user();
        
        // Insert feedback
        $inserted = $wpdb->insert(
            $table_name,
            [
                'reason' => $reason,
                'details' => $details,
                'site_url' => get_site_url(),
                'wp_version' => get_bloginfo('version'),
                'php_version' => PHP_VERSION,
                'plugin_version' => PROBUILDER_VERSION,
                'pages_created' => intval($pages_created),
                'user_email' => $current_user->user_email,
                'user_id' => $current_user->ID,
                'created_at' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s']
        );
        
        if ($inserted) {
            // Optional: Send email notification to developer
            $this->send_notification_email($reason, $details, $pages_created);
            
            wp_send_json_success(['message' => 'Feedback saved']);
        } else {
            wp_send_json_error(['message' => 'Failed to save feedback']);
        }
    }
    
    /**
     * Send email notification to developer
     */
    private function send_notification_email($reason, $details, $pages_created) {
        // Change this to your email
        $developer_email = get_option('admin_email'); // or hardcode your email
        
        $subject = '[ProBuilder] Deactivation Feedback - ' . ucwords(str_replace('-', ' ', $reason));
        
        $message = "New deactivation feedback received:\n\n";
        $message .= "Reason: " . ucwords(str_replace('-', ' ', $reason)) . "\n";
        $message .= "Details: " . ($details ?: 'No additional details') . "\n\n";
        $message .= "Site Info:\n";
        $message .= "- URL: " . get_site_url() . "\n";
        $message .= "- WordPress: " . get_bloginfo('version') . "\n";
        $message .= "- PHP: " . PHP_VERSION . "\n";
        $message .= "- ProBuilder: " . PROBUILDER_VERSION . "\n";
        $message .= "- Pages Created: " . $pages_created . "\n";
        $message .= "- Time: " . current_time('mysql') . "\n";
        
        wp_mail($developer_email, $subject, $message);
    }
    
    /**
     * Add admin menu to view feedback
     */
    public function add_feedback_menu() {
        add_submenu_page(
            'probuilder-settings',
            'Deactivation Feedback',
            'Feedback',
            'manage_options',
            'probuilder-feedback',
            [$this, 'render_feedback_page']
        );
    }
    
    /**
     * Render feedback page
     */
    public function render_feedback_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'probuilder_feedback';
        
        // Get all feedback
        $feedback_items = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        
        // Get statistics
        $total_feedback = count($feedback_items);
        $reason_counts = $wpdb->get_results("
            SELECT reason, COUNT(*) as count 
            FROM $table_name 
            GROUP BY reason 
            ORDER BY count DESC
        ");
        
        include PROBUILDER_PATH . 'templates/admin-feedback.php';
    }
}

// Initialize
ProBuilder_Uninstall_Feedback::instance();

