<?php
/**
 * Safia Theme License Activation Page
 * 
 * @package Safia
 * @subpackage Admin
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Safia_Theme_License_Page {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_license_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    
    /**
     * Add license page to admin menu
     */
    public function add_license_page() {
        add_theme_page(
            __('Safia License Activation', 'safia'),
            __('License', 'safia'),
            'manage_options',
            'safia-license',
            [$this, 'render_license_page']
        );
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook) {
        if ($hook !== 'appearance_page_safia-license') {
            return;
        }
        
        wp_enqueue_style('safia-license', get_template_directory_uri() . '/assets/css/license.css', [], '2.0.0');
        wp_enqueue_script('safia-license', get_template_directory_uri() . '/assets/js/license.js', ['jquery'], '2.0.0', true);
        
        wp_localize_script('safia-license', 'safiaLicense', [
            'nonce' => wp_create_nonce('safia_license_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }
    
    /**
     * Render license page
     */
    public function render_license_page() {
        $license_manager = Safia_Theme_License_Manager::instance();
        $company_info = $license_manager->get_company_info();
        $license_data = $license_manager->get_license_data();
        $is_active = $license_manager->is_license_active();
        $install_hash = $license_manager->get_install_hash();
        $theme = wp_get_theme();
        
        ?>
        <div class="wrap safia-license-wrap">
            <h1><?php _e('Safia Theme - License Activation', 'safia'); ?></h1>
            
            <div class="safia-license-container">
                
                <!-- Company Branding -->
                <div class="license-header">
                    <div class="company-logo">
                        <h2><?php echo esc_html($company_info['name']); ?></h2>
                        <p><?php echo esc_url($company_info['website']); ?></p>
                    </div>
                    <div class="product-info">
                        <strong><?php _e('Product:', 'safia'); ?></strong> <?php echo esc_html($theme->get('Name')); ?><br>
                        <strong><?php _e('Version:', 'safia'); ?></strong> <?php echo esc_html($theme->get('Version')); ?><br>
                        <strong><?php _e('Product ID:', 'safia'); ?></strong> <?php echo esc_html($company_info['product_id']); ?>
                    </div>
                </div>
                
                <!-- License Status -->
                <div class="license-status-box <?php echo $is_active ? 'status-active' : 'status-inactive'; ?>">
                    <div class="status-icon">
                        <?php if ($is_active): ?>
                            <span class="dashicons dashicons-yes-alt"></span>
                        <?php else: ?>
                            <span class="dashicons dashicons-warning"></span>
                        <?php endif; ?>
                    </div>
                    <div class="status-content">
                        <h3>
                            <?php if ($is_active): ?>
                                <?php _e('License Active', 'safia'); ?>
                            <?php else: ?>
                                <?php _e('License Not Activated', 'safia'); ?>
                            <?php endif; ?>
                        </h3>
                        <?php if ($is_active && !empty($license_data)): ?>
                            <p>
                                <strong><?php _e('Licensed to:', 'safia'); ?></strong> 
                                <?php echo esc_html($license_data['customer_name'] ?? 'N/A'); ?><br>
                                <strong><?php _e('License Type:', 'safia'); ?></strong> 
                                <?php echo esc_html($license_data['license_type'] ?? 'Standard'); ?><br>
                                <strong><?php _e('Expires:', 'safia'); ?></strong> 
                                <?php echo esc_html($license_data['expires'] ?? 'Never'); ?>
                            </p>
                        <?php else: ?>
                            <p><?php _e('Please enter your license key to activate the Safia theme and receive updates.', 'safia'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- License Activation Form -->
                <?php if (!$is_active): ?>
                <div class="license-form-box">
                    <h3><?php _e('Activate Your License', 'safia'); ?></h3>
                    <form id="safia-license-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="license_key"><?php _e('License Key', 'safia'); ?></label>
                                </th>
                                <td>
                                    <input type="text" 
                                           id="license_key" 
                                           name="license_key" 
                                           class="regular-text" 
                                           placeholder="XXXX-XXXX-XXXX-XXXX"
                                           required>
                                    <p class="description">
                                        <?php _e('Enter the license key you received after purchase.', 'safia'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label><?php _e('Installation ID', 'safia'); ?></label>
                                </th>
                                <td>
                                    <code style="padding: 5px 10px; background: #f5f5f5; display: inline-block;">
                                        <?php echo esc_html($install_hash); ?>
                                    </code>
                                    <p class="description">
                                        <?php _e('This unique ID identifies your installation.', 'safia'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary button-hero">
                                <?php _e('Activate License', 'safia'); ?>
                            </button>
                        </p>
                        
                        <div id="license-message" style="margin-top: 20px;"></div>
                    </form>
                </div>
                <?php else: ?>
                <!-- Deactivation Form -->
                <div class="license-form-box">
                    <h3><?php _e('License Management', 'safia'); ?></h3>
                    <p><?php _e('To move this license to another site, please deactivate it here first.', 'safia'); ?></p>
                    <form id="safia-deactivate-form">
                        <p class="submit">
                            <button type="submit" class="button button-secondary">
                                <?php _e('Deactivate License', 'safia'); ?>
                            </button>
                        </p>
                        <div id="license-message" style="margin-top: 20px;"></div>
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- Security Information -->
                <div class="license-security-box">
                    <h3><span class="dashicons dashicons-shield"></span> <?php _e('Security Features', 'safia'); ?></h3>
                    <ul>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Code Integrity Verification', 'safia'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Encrypted License Validation', 'safia'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Domain-Locked Activation', 'safia'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Anti-Tampering Protection', 'safia'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Automatic Update Verification', 'safia'); ?></li>
                    </ul>
                </div>
                
                <!-- Support Information -->
                <div class="license-support-box">
                    <h3><?php _e('Need Help?', 'safia'); ?></h3>
                    <p><?php _e('If you need assistance with license activation:', 'safia'); ?></p>
                    <ul>
                        <li><strong><?php _e('Email:', 'safia'); ?></strong> support@<?php echo esc_html(parse_url($company_info['website'], PHP_URL_HOST)); ?></li>
                        <li><strong><?php _e('Website:', 'safia'); ?></strong> <a href="<?php echo esc_url($company_info['website']); ?>" target="_blank"><?php echo esc_html($company_info['website']); ?></a></li>
                    </ul>
                </div>
                
                <!-- Copyright Notice -->
                <div class="license-copyright">
                    <p>
                        &copy; <?php echo date('Y'); ?> <?php echo esc_html($company_info['name']); ?>. <?php _e('All rights reserved.', 'safia'); ?><br>
                        <?php _e('This theme is licensed, not sold. Unauthorized copying, distribution, or modification is prohibited.', 'safia'); ?>
                    </p>
                </div>
                
            </div>
        </div>
        
        <style>
        /* Inline License Page Styles */
        .safia-license-wrap {
            max-width: 1200px;
            margin: 20px auto;
        }
        
        .safia-license-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .license-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 30px;
        }
        
        .company-logo h2 {
            margin: 0 0 5px 0;
            color: #1e3a8a;
            font-size: 28px;
            font-weight: 700;
        }
        
        .license-status-box {
            display: flex;
            align-items: center;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 5px solid;
        }
        
        .license-status-box.status-active {
            background: #ecfdf5;
            border-color: #10b981;
        }
        
        .license-status-box.status-inactive {
            background: #fef3c7;
            border-color: #f59e0b;
        }
        
        .status-icon .dashicons {
            font-size: 48px;
            width: 48px;
            height: 48px;
        }
        
        .status-active .dashicons {
            color: #10b981;
        }
        
        .status-inactive .dashicons {
            color: #f59e0b;
        }
        
        .license-form-box,
        .license-security-box,
        .license-support-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .license-copyright {
            text-align: center;
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 13px;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Activate License
            $('#safia-license-form').on('submit', function(e) {
                e.preventDefault();
                
                var $button = $(this).find('button[type="submit"]');
                var $message = $('#license-message');
                var licenseKey = $('#license_key').val().trim();
                
                $button.prop('disabled', true).text('<?php _e('Activating...', 'safia'); ?>');
                
                $.ajax({
                    url: safiaLicense.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'safia_activate_license',
                        nonce: safiaLicense.nonce,
                        license_key: licenseKey
                    },
                    success: function(response) {
                        $button.prop('disabled', false).text('<?php _e('Activate License', 'safia'); ?>');
                        
                        if (response.success) {
                            $message.html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            $message.html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                        }
                    }
                });
            });
            
            // Deactivate License
            $('#safia-deactivate-form').on('submit', function(e) {
                e.preventDefault();
                
                if (!confirm('<?php _e('Are you sure you want to deactivate this license?', 'safia'); ?>')) {
                    return;
                }
                
                var $button = $(this).find('button[type="submit"]');
                var $message = $('#license-message');
                
                $button.prop('disabled', true).text('<?php _e('Deactivating...', 'safia'); ?>');
                
                $.ajax({
                    url: safiaLicense.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'safia_deactivate_license',
                        nonce: safiaLicense.nonce
                    },
                    success: function(response) {
                        $button.prop('disabled', false).text('<?php _e('Deactivate License', 'safia'); ?>');
                        
                        if (response.success) {
                            $message.html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            });
        });
        </script>
        <?php
    }
}

// Initialize
add_action('after_setup_theme', function() {
    Safia_Theme_License_Page::instance();
});

