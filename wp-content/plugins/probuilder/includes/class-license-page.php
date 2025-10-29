<?php
/**
 * ProBuilder License Activation Page
 * 
 * @package ProBuilder
 * @subpackage Admin
 * @since 3.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_License_Page {
    
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
        add_submenu_page(
            'probuilder',
            __('License Activation', 'probuilder'),
            __('License', 'probuilder'),
            'manage_options',
            'probuilder-license',
            [$this, 'render_license_page']
        );
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook) {
        if ($hook !== 'probuilder_page_probuilder-license') {
            return;
        }
        
        wp_enqueue_style('probuilder-license', PROBUILDER_URL . 'assets/css/license.css', [], PROBUILDER_VERSION);
        wp_enqueue_script('probuilder-license', PROBUILDER_URL . 'assets/js/license.js', ['jquery'], PROBUILDER_VERSION, true);
        
        wp_localize_script('probuilder-license', 'probuilderLicense', [
            'nonce' => wp_create_nonce('probuilder_license_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }
    
    /**
     * Render license page
     */
    public function render_license_page() {
        $license_manager = ProBuilder_License_Manager::instance();
        $company_info = $license_manager->get_company_info();
        $license_data = $license_manager->get_license_data();
        $is_active = $license_manager->is_license_active();
        $install_hash = $license_manager->get_install_hash();
        
        ?>
        <div class="wrap probuilder-license-wrap">
            <h1><?php _e('ProBuilder License Activation', 'probuilder'); ?></h1>
            
            <div class="probuilder-license-container">
                
                <!-- Company Branding -->
                <div class="license-header">
                    <div class="company-logo">
                        <h2><?php echo esc_html($company_info['name']); ?></h2>
                        <p><?php echo esc_url($company_info['website']); ?></p>
                    </div>
                    <div class="product-info">
                        <strong><?php _e('Product:', 'probuilder'); ?></strong> ProBuilder - Safia Edition<br>
                        <strong><?php _e('Version:', 'probuilder'); ?></strong> <?php echo PROBUILDER_VERSION; ?><br>
                        <strong><?php _e('Product ID:', 'probuilder'); ?></strong> <?php echo esc_html($company_info['product_id']); ?>
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
                                <?php _e('License Active', 'probuilder'); ?>
                            <?php else: ?>
                                <?php _e('License Not Activated', 'probuilder'); ?>
                            <?php endif; ?>
                        </h3>
                        <?php if ($is_active && !empty($license_data)): ?>
                            <p>
                                <strong><?php _e('Licensed to:', 'probuilder'); ?></strong> 
                                <?php echo esc_html($license_data['customer_name'] ?? 'N/A'); ?><br>
                                <strong><?php _e('License Type:', 'probuilder'); ?></strong> 
                                <?php echo esc_html($license_data['license_type'] ?? 'Standard'); ?><br>
                                <strong><?php _e('Expires:', 'probuilder'); ?></strong> 
                                <?php echo esc_html($license_data['expires'] ?? 'Never'); ?>
                            </p>
                        <?php else: ?>
                            <p><?php _e('Please enter your license key to activate ProBuilder and receive updates.', 'probuilder'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- License Activation Form -->
                <?php if (!$is_active): ?>
                <div class="license-form-box">
                    <h3><?php _e('Activate Your License', 'probuilder'); ?></h3>
                    <form id="probuilder-license-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="license_key"><?php _e('License Key', 'probuilder'); ?></label>
                                </th>
                                <td>
                                    <input type="text" 
                                           id="license_key" 
                                           name="license_key" 
                                           class="regular-text" 
                                           placeholder="XXXX-XXXX-XXXX-XXXX"
                                           required>
                                    <p class="description">
                                        <?php _e('Enter the license key you received after purchase.', 'probuilder'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label><?php _e('Installation ID', 'probuilder'); ?></label>
                                </th>
                                <td>
                                    <code style="padding: 5px 10px; background: #f5f5f5; display: inline-block;">
                                        <?php echo esc_html($install_hash); ?>
                                    </code>
                                    <p class="description">
                                        <?php _e('This unique ID identifies your installation.', 'probuilder'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary button-hero">
                                <?php _e('Activate License', 'probuilder'); ?>
                            </button>
                        </p>
                        
                        <div id="license-message" style="margin-top: 20px;"></div>
                    </form>
                </div>
                <?php else: ?>
                <!-- Deactivation Form -->
                <div class="license-form-box">
                    <h3><?php _e('License Management', 'probuilder'); ?></h3>
                    <p><?php _e('To move this license to another site, please deactivate it here first.', 'probuilder'); ?></p>
                    <form id="probuilder-deactivate-form">
                        <p class="submit">
                            <button type="submit" class="button button-secondary">
                                <?php _e('Deactivate License', 'probuilder'); ?>
                            </button>
                        </p>
                        <div id="license-message" style="margin-top: 20px;"></div>
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- Security Information -->
                <div class="license-security-box">
                    <h3><span class="dashicons dashicons-shield"></span> <?php _e('Security Features', 'probuilder'); ?></h3>
                    <ul>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Code Integrity Verification', 'probuilder'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Encrypted License Validation', 'probuilder'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Domain-Locked Activation', 'probuilder'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Anti-Tampering Protection', 'probuilder'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span> <?php _e('Automatic Update Verification', 'probuilder'); ?></li>
                    </ul>
                </div>
                
                <!-- Support Information -->
                <div class="license-support-box">
                    <h3><?php _e('Need Help?', 'probuilder'); ?></h3>
                    <p><?php _e('If you need assistance with license activation:', 'probuilder'); ?></p>
                    <ul>
                        <li><strong><?php _e('Email:', 'probuilder'); ?></strong> support@<?php echo esc_html(parse_url($company_info['website'], PHP_URL_HOST)); ?></li>
                        <li><strong><?php _e('Website:', 'probuilder'); ?></strong> <a href="<?php echo esc_url($company_info['website']); ?>" target="_blank"><?php echo esc_html($company_info['website']); ?></a></li>
                    </ul>
                </div>
                
                <!-- Copyright Notice -->
                <div class="license-copyright">
                    <p>
                        &copy; <?php echo date('Y'); ?> <?php echo esc_html($company_info['name']); ?>. <?php _e('All rights reserved.', 'probuilder'); ?><br>
                        <?php _e('This software is licensed, not sold. Unauthorized copying, distribution, or modification is prohibited.', 'probuilder'); ?>
                    </p>
                </div>
                
            </div>
        </div>
        <?php
    }
}

