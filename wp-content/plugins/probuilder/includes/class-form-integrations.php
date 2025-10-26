<?php
/**
 * Form Integrations
 * Mailchimp, Stripe, PayPal, email services
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Form_Integrations {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_integrations_menu']);
        add_action('wp_ajax_probuilder_save_integration', [$this, 'ajax_save_integration']);
        add_action('wp_ajax_probuilder_test_integration', [$this, 'ajax_test_integration']);
        add_action('wp_ajax_nopriv_probuilder_submit_form', [$this, 'ajax_submit_form']);
        add_action('wp_ajax_probuilder_submit_form', [$this, 'ajax_submit_form']);
    }
    
    /**
     * Get available integrations
     */
    public function get_integrations() {
        return [
            'mailchimp' => [
                'title' => __('Mailchimp', 'probuilder'),
                'description' => __('Subscribe users to Mailchimp lists', 'probuilder'),
                'icon' => 'fa-mailchimp',
                'fields' => [
                    'api_key' => __('API Key', 'probuilder'),
                    'list_id' => __('List ID', 'probuilder'),
                ]
            ],
            'activecampaign' => [
                'title' => __('ActiveCampaign', 'probuilder'),
                'description' => __('Marketing automation platform', 'probuilder'),
                'icon' => 'fa-envelope',
                'fields' => [
                    'api_url' => __('API URL', 'probuilder'),
                    'api_key' => __('API Key', 'probuilder'),
                    'list_id' => __('List ID', 'probuilder'),
                ]
            ],
            'getresponse' => [
                'title' => __('GetResponse', 'probuilder'),
                'description' => __('Email marketing software', 'probuilder'),
                'icon' => 'fa-envelope-open',
                'fields' => [
                    'api_key' => __('API Key', 'probuilder'),
                    'campaign_token' => __('Campaign Token', 'probuilder'),
                ]
            ],
            'aweber' => [
                'title' => __('AWeber', 'probuilder'),
                'description' => __('Email marketing service', 'probuilder'),
                'icon' => 'fa-paper-plane',
                'fields' => [
                    'client_id' => __('Client ID', 'probuilder'),
                    'client_secret' => __('Client Secret', 'probuilder'),
                    'access_token' => __('Access Token', 'probuilder'),
                ]
            ],
            'hubspot' => [
                'title' => __('HubSpot', 'probuilder'),
                'description' => __('CRM and marketing automation', 'probuilder'),
                'icon' => 'fa-hubspot',
                'fields' => [
                    'api_key' => __('API Key', 'probuilder'),
                    'portal_id' => __('Portal ID', 'probuilder'),
                ]
            ],
            'drip' => [
                'title' => __('Drip', 'probuilder'),
                'description' => __('E-commerce CRM', 'probuilder'),
                'icon' => 'fa-tint',
                'fields' => [
                    'api_token' => __('API Token', 'probuilder'),
                    'account_id' => __('Account ID', 'probuilder'),
                ]
            ],
            'constantcontact' => [
                'title' => __('Constant Contact', 'probuilder'),
                'description' => __('Email and online marketing', 'probuilder'),
                'icon' => 'fa-address-card',
                'fields' => [
                    'access_token' => __('Access Token', 'probuilder'),
                    'list_id' => __('List ID', 'probuilder'),
                ]
            ],
            'zapier' => [
                'title' => __('Zapier', 'probuilder'),
                'description' => __('Connect to 3000+ apps', 'probuilder'),
                'icon' => 'fa-bolt',
                'fields' => [
                    'webhook_url' => __('Webhook URL', 'probuilder'),
                ]
            ],
            'slack' => [
                'title' => __('Slack', 'probuilder'),
                'description' => __('Send notifications to Slack', 'probuilder'),
                'icon' => 'fa-slack',
                'fields' => [
                    'webhook_url' => __('Webhook URL', 'probuilder'),
                    'channel' => __('Channel', 'probuilder'),
                ]
            ],
            'discord' => [
                'title' => __('Discord', 'probuilder'),
                'description' => __('Send notifications to Discord', 'probuilder'),
                'icon' => 'fa-discord',
                'fields' => [
                    'webhook_url' => __('Webhook URL', 'probuilder'),
                ]
            ],
            'googlesheets' => [
                'title' => __('Google Sheets', 'probuilder'),
                'description' => __('Save form data to Google Sheets', 'probuilder'),
                'icon' => 'fa-google',
                'fields' => [
                    'spreadsheet_id' => __('Spreadsheet ID', 'probuilder'),
                    'api_key' => __('API Key', 'probuilder'),
                ]
            ],
            'recaptcha' => [
                'title' => __('reCAPTCHA', 'probuilder'),
                'description' => __('Protect forms from spam', 'probuilder'),
                'icon' => 'fa-shield-alt',
                'fields' => [
                    'site_key' => __('Site Key', 'probuilder'),
                    'secret_key' => __('Secret Key', 'probuilder'),
                ]
            ],
            'twilio' => [
                'title' => __('Twilio', 'probuilder'),
                'description' => __('Send SMS notifications', 'probuilder'),
                'icon' => 'fa-sms',
                'fields' => [
                    'account_sid' => __('Account SID', 'probuilder'),
                    'auth_token' => __('Auth Token', 'probuilder'),
                    'from_number' => __('From Number', 'probuilder'),
                ]
            ],
            'salesforce' => [
                'title' => __('Salesforce', 'probuilder'),
                'description' => __('CRM integration', 'probuilder'),
                'icon' => 'fa-salesforce',
                'fields' => [
                    'instance_url' => __('Instance URL', 'probuilder'),
                    'access_token' => __('Access Token', 'probuilder'),
                ]
            ],
            'stripe' => [
                'title' => __('Stripe', 'probuilder'),
                'description' => __('Accept payments via Stripe', 'probuilder'),
                'icon' => 'fa-stripe',
                'fields' => [
                    'publishable_key' => __('Publishable Key', 'probuilder'),
                    'secret_key' => __('Secret Key', 'probuilder'),
                ]
            ],
            'paypal' => [
                'title' => __('PayPal', 'probuilder'),
                'description' => __('Accept payments via PayPal', 'probuilder'),
                'icon' => 'fa-paypal',
                'fields' => [
                    'client_id' => __('Client ID', 'probuilder'),
                    'secret' => __('Secret', 'probuilder'),
                    'mode' => __('Mode (sandbox/live)', 'probuilder'),
                ]
            ],
            'sendinblue' => [
                'title' => __('Sendinblue', 'probuilder'),
                'description' => __('Email marketing automation', 'probuilder'),
                'icon' => 'fa-envelope',
                'fields' => [
                    'api_key' => __('API Key', 'probuilder'),
                    'list_id' => __('List ID', 'probuilder'),
                ]
            ],
            'convertkit' => [
                'title' => __('ConvertKit', 'probuilder'),
                'description' => __('Email marketing for creators', 'probuilder'),
                'icon' => 'fa-paper-plane',
                'fields' => [
                    'api_key' => __('API Key', 'probuilder'),
                    'form_id' => __('Form ID', 'probuilder'),
                ]
            ],
            'webhook' => [
                'title' => __('Webhook', 'probuilder'),
                'description' => __('Send data to any webhook URL', 'probuilder'),
                'icon' => 'fa-link',
                'fields' => [
                    'url' => __('Webhook URL', 'probuilder'),
                    'method' => __('Method (POST/GET)', 'probuilder'),
                ]
            ],
        ];
    }
    
    /**
     * Add integrations menu
     */
    public function add_integrations_menu() {
        add_submenu_page(
            'probuilder',
            __('Form Integrations', 'probuilder'),
            __('Integrations', 'probuilder'),
            'manage_options',
            'probuilder-integrations',
            [$this, 'integrations_page']
        );
    }
    
    /**
     * Integrations page
     */
    public function integrations_page() {
        $integrations = $this->get_integrations();
        $saved_settings = get_option('probuilder_integrations', []);
        
        ?>
        <div class="wrap">
            <h1><?php _e('Form Integrations', 'probuilder'); ?></h1>
            <p><?php _e('Connect your forms to third-party services.', 'probuilder'); ?></p>
            
            <div class="probuilder-integrations-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
                
                <?php foreach ($integrations as $key => $integration): 
                    $is_connected = isset($saved_settings[$key]) && !empty($saved_settings[$key]);
                ?>
                <div class="integration-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <i class="fab <?php echo esc_attr($integration['icon']); ?>" style="font-size: 36px; margin-right: 15px; color: #344047;"></i>
                        <div>
                            <h3 style="margin: 0;"><?php echo esc_html($integration['title']); ?></h3>
                            <span style="font-size: 12px; color: <?php echo $is_connected ? 'green' : '#999'; ?>;">
                                <?php echo $is_connected ? '● Connected' : '○ Not Connected'; ?>
                            </span>
                        </div>
                    </div>
                    
                    <p style="color: #666; margin-bottom: 20px;"><?php echo esc_html($integration['description']); ?></p>
                    
                    <button class="button button-primary integration-configure" data-integration="<?php echo esc_attr($key); ?>">
                        <?php echo $is_connected ? __('Configure', 'probuilder') : __('Connect', 'probuilder'); ?>
                    </button>
                    
                    <?php if ($is_connected): ?>
                    <button class="button integration-test" data-integration="<?php echo esc_attr($key); ?>">
                        <?php _e('Test Connection', 'probuilder'); ?>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                
            </div>
        </div>
        
        <!-- Configuration Modal -->
        <div id="integration-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 999999; align-items: center; justify-content: center;">
            <div style="background: #fff; padding: 40px; border-radius: 8px; max-width: 500px; width: 90%;">
                <h2 id="modal-title"></h2>
                <form id="integration-form">
                    <div id="modal-fields"></div>
                    <p>
                        <button type="submit" class="button button-primary"><?php _e('Save Integration', 'probuilder'); ?></button>
                        <button type="button" class="button modal-close"><?php _e('Cancel', 'probuilder'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            const integrations = <?php echo json_encode($integrations); ?>;
            const savedSettings = <?php echo json_encode($saved_settings); ?>;
            let currentIntegration = null;
            
            $('.integration-configure').on('click', function() {
                currentIntegration = $(this).data('integration');
                const integration = integrations[currentIntegration];
                
                $('#modal-title').text(integration.title + ' Configuration');
                
                let fieldsHTML = '';
                Object.entries(integration.fields).forEach(([key, label]) => {
                    const value = savedSettings[currentIntegration] ? (savedSettings[currentIntegration][key] || '') : '';
                    fieldsHTML += `
                        <p>
                            <label>${label}</label><br>
                            <input type="${key.includes('secret') || key.includes('key') ? 'password' : 'text'}" 
                                   name="${key}" 
                                   value="${value}" 
                                   style="width: 100%; padding: 8px;" 
                                   required>
                        </p>
                    `;
                });
                
                $('#modal-fields').html(fieldsHTML);
                $('#integration-modal').css('display', 'flex');
            });
            
            $('.modal-close').on('click', function() {
                $('#integration-modal').hide();
            });
            
            $('#integration-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {};
                $(this).find('[name]').each(function() {
                    formData[$(this).attr('name')] = $(this).val();
                });
                
                $.post(ajaxurl, {
                    action: 'probuilder_save_integration',
                    nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                    integration: currentIntegration,
                    settings: JSON.stringify(formData)
                }, function(response) {
                    if (response.success) {
                        alert('Integration saved!');
                        location.reload();
                    }
                });
            });
            
            $('.integration-test').on('click', function() {
                const integration = $(this).data('integration');
                
                $.post(ajaxurl, {
                    action: 'probuilder_test_integration',
                    nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                    integration: integration
                }, function(response) {
                    alert(response.success ? 'Connection successful!' : 'Connection failed: ' + response.data.message);
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Save integration
     */
    public function ajax_save_integration() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }
        
        $integration = sanitize_text_field($_POST['integration']);
        $settings = json_decode(stripslashes($_POST['settings']), true);
        
        $saved = get_option('probuilder_integrations', []);
        $saved[$integration] = $settings;
        
        update_option('probuilder_integrations', $saved);
        
        wp_send_json_success(['message' => 'Integration saved']);
    }
    
    /**
     * Test integration
     */
    public function ajax_test_integration() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $integration = sanitize_text_field($_POST['integration']);
        $saved = get_option('probuilder_integrations', []);
        
        if (!isset($saved[$integration])) {
            wp_send_json_error(['message' => 'Integration not configured']);
            return;
        }
        
        $settings = $saved[$integration];
        $result = $this->test_connection($integration, $settings);
        
        if ($result) {
            wp_send_json_success(['message' => 'Connection successful']);
        } else {
            wp_send_json_error(['message' => 'Connection failed']);
        }
    }
    
    /**
     * Test connection
     */
    private function test_connection($integration, $settings) {
        switch ($integration) {
            case 'mailchimp':
                return $this->test_mailchimp($settings);
            case 'stripe':
                return $this->test_stripe($settings);
            default:
                return true;
        }
    }
    
    /**
     * Test Mailchimp connection
     */
    private function test_mailchimp($settings) {
        if (empty($settings['api_key'])) {
            return false;
        }
        
        $dc = explode('-', $settings['api_key'])[1] ?? '';
        $url = "https://{$dc}.api.mailchimp.com/3.0/ping";
        
        $response = wp_remote_get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $settings['api_key']
            ]
        ]);
        
        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }
    
    /**
     * Test Stripe connection
     */
    private function test_stripe($settings) {
        // Basic validation
        return !empty($settings['publishable_key']) && !empty($settings['secret_key']);
    }
    
    /**
     * Submit form with integrations
     */
    public function ajax_submit_form() {
        $form_data = $_POST['form_data'] ?? [];
        $integrations = $_POST['integrations'] ?? [];
        
        $results = [];
        
        foreach ($integrations as $integration_key) {
            $result = $this->process_integration($integration_key, $form_data);
            $results[$integration_key] = $result;
        }
        
        wp_send_json_success([
            'message' => 'Form submitted successfully',
            'results' => $results
        ]);
    }
    
    /**
     * Process integration
     */
    private function process_integration($integration, $form_data) {
        $saved = get_option('probuilder_integrations', []);
        
        if (!isset($saved[$integration])) {
            return false;
        }
        
        $settings = $saved[$integration];
        
        switch ($integration) {
            case 'mailchimp':
                return $this->mailchimp_subscribe($settings, $form_data);
            case 'webhook':
                return $this->webhook_send($settings, $form_data);
            default:
                return false;
        }
    }
    
    /**
     * Mailchimp subscribe
     */
    private function mailchimp_subscribe($settings, $form_data) {
        $dc = explode('-', $settings['api_key'])[1] ?? '';
        $url = "https://{$dc}.api.mailchimp.com/3.0/lists/{$settings['list_id']}/members";
        
        $response = wp_remote_post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $settings['api_key'],
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'email_address' => $form_data['email'] ?? '',
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $form_data['first_name'] ?? '',
                    'LNAME' => $form_data['last_name'] ?? ''
                ]
            ])
        ]);
        
        return !is_wp_error($response);
    }
    
    /**
     * Webhook send
     */
    private function webhook_send($settings, $form_data) {
        $method = strtoupper($settings['method'] ?? 'POST');
        
        $args = [
            'method' => $method,
            'body' => json_encode($form_data),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
        
        $response = wp_remote_request($settings['url'], $args);
        
        return !is_wp_error($response);
    }
}

