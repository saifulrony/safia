<?php
/**
 * Custom Breakpoints System
 * Define custom responsive breakpoints
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Custom_Breakpoints {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_breakpoints_menu']);
        add_action('wp_ajax_probuilder_save_breakpoints', [$this, 'ajax_save_breakpoints']);
        add_action('wp_ajax_probuilder_get_breakpoints', [$this, 'ajax_get_breakpoints']);
        add_action('wp_head', [$this, 'output_breakpoint_css'], 1);
    }
    
    /**
     * Get default breakpoints
     */
    public function get_default_breakpoints() {
        return [
            'mobile' => [
                'label' => __('Mobile', 'probuilder'),
                'value' => 480,
                'icon' => 'fa-mobile',
                'default' => true
            ],
            'mobile_extra' => [
                'label' => __('Mobile Extra', 'probuilder'),
                'value' => 576,
                'icon' => 'fa-mobile-alt',
                'default' => false
            ],
            'tablet' => [
                'label' => __('Tablet', 'probuilder'),
                'value' => 768,
                'icon' => 'fa-tablet',
                'default' => true
            ],
            'tablet_extra' => [
                'label' => __('Tablet Extra', 'probuilder'),
                'value' => 992,
                'icon' => 'fa-tablet-alt',
                'default' => false
            ],
            'laptop' => [
                'label' => __('Laptop', 'probuilder'),
                'value' => 1024,
                'icon' => 'fa-laptop',
                'default' => true
            ],
            'desktop' => [
                'label' => __('Desktop', 'probuilder'),
                'value' => 1200,
                'icon' => 'fa-desktop',
                'default' => true
            ],
            'widescreen' => [
                'label' => __('Widescreen', 'probuilder'),
                'value' => 1600,
                'icon' => 'fa-tv',
                'default' => false
            ],
        ];
    }
    
    /**
     * Get active breakpoints
     */
    public function get_breakpoints() {
        $saved = get_option('probuilder_breakpoints', []);
        $defaults = $this->get_default_breakpoints();
        
        if (empty($saved)) {
            return $defaults;
        }
        
        return array_merge($defaults, $saved);
    }
    
    /**
     * Add breakpoints menu
     */
    public function add_breakpoints_menu() {
        add_submenu_page(
            'probuilder',
            __('Breakpoints', 'probuilder'),
            __('Breakpoints', 'probuilder'),
            'manage_options',
            'probuilder-breakpoints',
            [$this, 'breakpoints_page']
        );
    }
    
    /**
     * Breakpoints admin page
     */
    public function breakpoints_page() {
        $breakpoints = $this->get_breakpoints();
        
        ?>
        <div class="wrap">
            <h1><?php _e('Custom Breakpoints', 'probuilder'); ?></h1>
            <p><?php _e('Configure responsive breakpoints for your designs.', 'probuilder'); ?></p>
            
            <form method="post" id="breakpoints-form">
                <?php wp_nonce_field('probuilder_breakpoints'); ?>
                
                <table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th><?php _e('Breakpoint', 'probuilder'); ?></th>
                            <th><?php _e('Label', 'probuilder'); ?></th>
                            <th><?php _e('Value (px)', 'probuilder'); ?></th>
                            <th><?php _e('Enabled', 'probuilder'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($breakpoints as $key => $breakpoint): ?>
                        <tr>
                            <td>
                                <i class="fas <?php echo esc_attr($breakpoint['icon']); ?>"></i>
                                <strong><?php echo esc_html($key); ?></strong>
                            </td>
                            <td>
                                <input type="text" 
                                       name="breakpoints[<?php echo esc_attr($key); ?>][label]" 
                                       value="<?php echo esc_attr($breakpoint['label']); ?>" 
                                       style="width: 200px;">
                            </td>
                            <td>
                                <input type="number" 
                                       name="breakpoints[<?php echo esc_attr($key); ?>][value]" 
                                       value="<?php echo esc_attr($breakpoint['value']); ?>" 
                                       min="1" 
                                       max="4000" 
                                       style="width: 100px;">
                            </td>
                            <td>
                                <input type="checkbox" 
                                       name="breakpoints[<?php echo esc_attr($key); ?>][enabled]" 
                                       value="1" 
                                       <?php checked(!isset($breakpoint['default']) || $breakpoint['default']); ?>>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary"><?php _e('Save Breakpoints', 'probuilder'); ?></button>
                    <button type="button" class="button" id="reset-breakpoints"><?php _e('Reset to Defaults', 'probuilder'); ?></button>
                </p>
            </form>
            
            <div style="margin-top: 30px; padding: 20px; background: #f0f0f0; border-radius: 8px;">
                <h3><?php _e('How to Use Custom Breakpoints', 'probuilder'); ?></h3>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><?php _e('Each breakpoint defines a screen width where your design will adapt', 'probuilder'); ?></li>
                    <li><?php _e('Enable only the breakpoints you need for better editor performance', 'probuilder'); ?></li>
                    <li><?php _e('Values are in pixels and represent minimum screen widths', 'probuilder'); ?></li>
                    <li><?php _e('Changes apply to all new and existing ProBuilder pages', 'probuilder'); ?></li>
                </ul>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#breakpoints-form').on('submit', function(e) {
                e.preventDefault();
                
                $.post(ajaxurl, {
                    action: 'probuilder_save_breakpoints',
                    nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                    breakpoints: $(this).serialize()
                }, function(response) {
                    if (response.success) {
                        alert('<?php _e('Breakpoints saved!', 'probuilder'); ?>');
                    }
                });
            });
            
            $('#reset-breakpoints').on('click', function() {
                if (confirm('<?php _e('Reset all breakpoints to default values?', 'probuilder'); ?>')) {
                    $.post(ajaxurl, {
                        action: 'probuilder_save_breakpoints',
                        nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                        reset: true
                    }, function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    });
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Save breakpoints
     */
    public function ajax_save_breakpoints() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }
        
        if (isset($_POST['reset'])) {
            delete_option('probuilder_breakpoints');
            wp_send_json_success(['message' => 'Breakpoints reset']);
            return;
        }
        
        parse_str($_POST['breakpoints'], $data);
        
        update_option('probuilder_breakpoints', $data['breakpoints']);
        
        wp_send_json_success(['message' => 'Breakpoints saved']);
    }
    
    /**
     * Get breakpoints for AJAX
     */
    public function ajax_get_breakpoints() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        wp_send_json_success($this->get_breakpoints());
    }
    
    /**
     * Output breakpoint CSS variables
     */
    public function output_breakpoint_css() {
        $breakpoints = $this->get_breakpoints();
        
        echo '<style id="probuilder-breakpoints">';
        echo ':root {';
        
        foreach ($breakpoints as $key => $breakpoint) {
            if (isset($breakpoint['enabled']) && $breakpoint['enabled']) {
                echo '--pb-breakpoint-' . $key . ': ' . $breakpoint['value'] . 'px;';
            }
        }
        
        echo '}';
        echo '</style>';
    }
}

