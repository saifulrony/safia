<?php
/**
 * Role Manager
 * Control ProBuilder access and capabilities
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Role_Manager {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_role_manager_menu']);
        add_action('init', [$this, 'apply_role_restrictions']);
        add_action('wp_ajax_probuilder_save_roles', [$this, 'ajax_save_roles']);
    }
    
    /**
     * Add role manager menu
     */
    public function add_role_manager_menu() {
        add_submenu_page(
            'probuilder',
            __('Role Manager', 'probuilder'),
            __('Role Manager', 'probuilder'),
            'manage_options',
            'probuilder-role-manager',
            [$this, 'role_manager_page']
        );
    }
    
    /**
     * Role manager page
     */
    public function role_manager_page() {
        $roles = wp_roles()->get_names();
        $saved_capabilities = get_option('probuilder_role_capabilities', []);
        
        ?>
        <div class="wrap">
            <h1><?php _e('ProBuilder Role Manager', 'probuilder'); ?></h1>
            <p><?php _e('Control which roles can access ProBuilder features.', 'probuilder'); ?></p>
            
            <form method="post" id="probuilder-role-form">
                <?php wp_nonce_field('probuilder_roles'); ?>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Role', 'probuilder'); ?></th>
                            <th><?php _e('Access Editor', 'probuilder'); ?></th>
                            <th><?php _e('Manage Templates', 'probuilder'); ?></th>
                            <th><?php _e('Global Widgets', 'probuilder'); ?></th>
                            <th><?php _e('Theme Builder', 'probuilder'); ?></th>
                            <th><?php _e('Settings', 'probuilder'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role_slug => $role_name): 
                            $caps = isset($saved_capabilities[$role_slug]) ? $saved_capabilities[$role_slug] : [];
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($role_name); ?></strong></td>
                            <td>
                                <input type="checkbox" 
                                       name="capabilities[<?php echo esc_attr($role_slug); ?>][editor]" 
                                       value="1"
                                       <?php checked(isset($caps['editor']) && $caps['editor']); ?>>
                            </td>
                            <td>
                                <input type="checkbox" 
                                       name="capabilities[<?php echo esc_attr($role_slug); ?>][templates]" 
                                       value="1"
                                       <?php checked(isset($caps['templates']) && $caps['templates']); ?>>
                            </td>
                            <td>
                                <input type="checkbox" 
                                       name="capabilities[<?php echo esc_attr($role_slug); ?>][global_widgets]" 
                                       value="1"
                                       <?php checked(isset($caps['global_widgets']) && $caps['global_widgets']); ?>>
                            </td>
                            <td>
                                <input type="checkbox" 
                                       name="capabilities[<?php echo esc_attr($role_slug); ?>][theme_builder]" 
                                       value="1"
                                       <?php checked(isset($caps['theme_builder']) && $caps['theme_builder']); ?>>
                            </td>
                            <td>
                                <input type="checkbox" 
                                       name="capabilities[<?php echo esc_attr($role_slug); ?>][settings]" 
                                       value="1"
                                       <?php checked(isset($caps['settings']) && $caps['settings']); ?>>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary"><?php _e('Save Role Settings', 'probuilder'); ?></button>
                </p>
            </form>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#probuilder-role-form').on('submit', function(e) {
                e.preventDefault();
                
                $.post(ajaxurl, {
                    action: 'probuilder_save_roles',
                    nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                    capabilities: $(this).find('[name^="capabilities"]').serialize()
                }, function(response) {
                    if (response.success) {
                        alert('<?php _e('Role settings saved!', 'probuilder'); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Save roles
     */
    public function ajax_save_roles() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }
        
        parse_str($_POST['capabilities'], $capabilities);
        
        update_option('probuilder_role_capabilities', $capabilities['capabilities']);
        
        wp_send_json_success(['message' => 'Role settings saved']);
    }
    
    /**
     * Apply role restrictions
     */
    public function apply_role_restrictions() {
        if (!is_user_logged_in()) {
            return;
        }
        
        $user = wp_get_current_user();
        $saved_capabilities = get_option('probuilder_role_capabilities', []);
        
        foreach ($user->roles as $role) {
            if (isset($saved_capabilities[$role])) {
                $caps = $saved_capabilities[$role];
                
                // Apply capabilities
                if (isset($caps['editor']) && $caps['editor']) {
                    $user->add_cap('use_probuilder_editor');
                }
                
                if (isset($caps['templates']) && $caps['templates']) {
                    $user->add_cap('manage_probuilder_templates');
                }
                
                if (isset($caps['global_widgets']) && $caps['global_widgets']) {
                    $user->add_cap('manage_global_widgets');
                }
                
                if (isset($caps['theme_builder']) && $caps['theme_builder']) {
                    $user->add_cap('use_theme_builder');
                }
            }
        }
    }
    
    /**
     * Check if user has capability
     */
    public static function user_can($capability) {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user = wp_get_current_user();
        $saved_capabilities = get_option('probuilder_role_capabilities', []);
        
        foreach ($user->roles as $role) {
            if (isset($saved_capabilities[$role][$capability]) && $saved_capabilities[$role][$capability]) {
                return true;
            }
        }
        
        return current_user_can('manage_options');
    }
}

