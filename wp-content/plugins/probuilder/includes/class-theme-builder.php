<?php
/**
 * Theme Builder System
 * Build custom headers, footers, archives, and single templates
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Theme_Builder {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'register_template_post_type']);
        add_action('admin_menu', [$this, 'add_theme_builder_menu']);
        add_action('template_redirect', [$this, 'apply_templates']);
        add_action('wp_ajax_probuilder_save_theme_template', [$this, 'ajax_save_template']);
        add_action('wp_ajax_probuilder_toggle_template', [$this, 'ajax_toggle_template']);
        
        // Redirect to ProBuilder when creating new theme template
        add_action('load-post-new.php', [$this, 'redirect_new_template_to_probuilder']);
    }
    
    /**
     * Register template post type
     */
    public function register_template_post_type() {
        register_post_type('pb_theme_template', [
            'labels' => [
                'name' => __('Theme Templates', 'probuilder'),
                'singular_name' => __('Theme Template', 'probuilder'),
                'add_new' => __('Add New Template', 'probuilder'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'probuilder',
            'supports' => ['title'],
            'capability_type' => 'page',
        ]);
    }
    
    /**
     * Add theme builder menu
     */
    public function add_theme_builder_menu() {
        add_submenu_page(
            'probuilder',
            __('Theme Builder', 'probuilder'),
            __('Theme Builder', 'probuilder'),
            'edit_pages',
            'probuilder-theme-builder',
            [$this, 'theme_builder_page']
        );
    }
    
    /**
     * Redirect new theme template to ProBuilder
     */
    public function redirect_new_template_to_probuilder() {
        if (!is_admin()) return;
        
        $post_type = isset($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';
        if ($post_type !== 'pb_theme_template') return;
        
        if (!current_user_can('edit_pages')) return;
        
        $template_type = isset($_GET['template_type']) ? sanitize_key($_GET['template_type']) : 'header';
        
        // Create draft template
        $post_id = wp_insert_post([
            'post_type'   => 'pb_theme_template',
            'post_status' => 'draft',
            'post_title'  => ucfirst($template_type) . ' Template',
            'post_author' => get_current_user_id(),
        ]);
        
        if (is_wp_error($post_id) || !$post_id) return;
        
        // Set template type
        update_post_meta($post_id, '_template_type', $template_type);
        update_post_meta($post_id, '_template_enabled', '0');
        
        // Redirect to ProBuilder editor
        $probuilder_url = add_query_arg([
            'p' => $post_id,
            'probuilder' => 'true',
        ], home_url('/'));
        
        wp_safe_redirect($probuilder_url);
        exit;
    }
    
    /**
     * Theme builder admin page
     */
    public function theme_builder_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Theme Builder', 'probuilder'); ?></h1>
            <p><?php _e('Design custom headers, footers, and templates with ProBuilder', 'probuilder'); ?></p>
            
            <div class="probuilder-theme-builder-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 30px;">
                
                <!-- Header Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-align-center" style="font-size: 48px; color: #92003b;"></i>
                    <h2 style="margin: 20px 0 10px;"><?php _e('Header', 'probuilder'); ?></h2>
                    <p style="color: #666; font-size: 14px;"><?php _e('Build custom site header', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=header'); ?>" class="button button-primary" style="margin-top: 10px;"><?php _e('+ Create Header', 'probuilder'); ?></a>
                </div>
                
                <!-- Footer Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-align-full-width" style="font-size: 48px; color: #92003b;"></i>
                    <h2 style="margin: 20px 0 10px;"><?php _e('Footer', 'probuilder'); ?></h2>
                    <p style="color: #666; font-size: 14px;"><?php _e('Build custom site footer', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=footer'); ?>" class="button button-primary" style="margin-top: 10px;"><?php _e('+ Create Footer', 'probuilder'); ?></a>
                </div>
                
                <!-- Single Post Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-media-document" style="font-size: 48px; color: #92003b;"></i>
                    <h2 style="margin: 20px 0 10px;"><?php _e('Single Post', 'probuilder'); ?></h2>
                    <p style="color: #666; font-size: 14px;"><?php _e('Build custom single post template', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=single'); ?>" class="button button-primary" style="margin-top: 10px;"><?php _e('+ Create Template', 'probuilder'); ?></a>
                </div>
                
            </div>
            
            <h2 style="margin-top: 40px;"><?php _e('Your Templates', 'probuilder'); ?></h2>
            <?php
            $templates = get_posts([
                'post_type' => 'pb_theme_template',
                'posts_per_page' => -1,
                'post_status' => ['publish', 'draft'],
                'orderby' => 'date',
                'order' => 'DESC'
            ]);
            
            if ($templates) {
                echo '<table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">';
                echo '<thead><tr>';
                echo '<th style="width: 35%;">Title</th>';
                echo '<th style="width: 15%;">Type</th>';
                echo '<th style="width: 15%;">Status</th>';
                echo '<th style="width: 35%;">Actions</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                
                foreach ($templates as $template) {
                    $type = get_post_meta($template->ID, '_template_type', true) ?: 'unknown';
                    $enabled = get_post_meta($template->ID, '_template_enabled', true);
                    $is_active = ($enabled === '1' || $enabled === 1);
                    
                    echo '<tr>';
                    echo '<td><strong>' . esc_html($template->post_title) . '</strong></td>';
                    echo '<td><span class="dashicons dashicons-' . $this->get_type_icon($type) . '"></span> ' . esc_html(ucfirst($type)) . '</td>';
                    
                    echo '<td>';
                    if ($is_active) {
                        echo '<span style="display: inline-flex; align-items: center; gap: 5px; color: #22c55e; font-weight: 600;"><span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Active</span>';
                    } else {
                        echo '<span style="display: inline-flex; align-items: center; gap: 5px; color: #9ca3af;"><span style="width: 8px; height: 8px; background: #9ca3af; border-radius: 50%; display: inline-block;"></span> Inactive</span>';
                    }
                    echo '</td>';
                    
                    echo '<td style="display: flex; gap: 8px; align-items: center;">';
                    echo '<a href="' . add_query_arg(['p' => $template->ID, 'probuilder' => 'true'], home_url('/')) . '" class="button button-primary button-small"><i class="dashicons dashicons-edit" style="vertical-align: middle;"></i> Edit with ProBuilder</a> ';
                    
                    // Toggle activation button
                    $toggle_text = $is_active ? __('Deactivate', 'probuilder') : __('Activate', 'probuilder');
                    $toggle_class = $is_active ? 'button-secondary' : 'button-primary';
                    echo '<button class="button ' . $toggle_class . ' button-small probuilder-toggle-template" data-template-id="' . $template->ID . '" data-active="' . ($is_active ? '1' : '0') . '">' . esc_html($toggle_text) . '</button>';
                    
                    echo '<a href="' . get_delete_post_link($template->ID) . '" class="button button-small button-link-delete" style="color: #dc2626;">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<div style="background: #f8f9fa; padding: 40px; text-align: center; border-radius: 8px; margin-top: 20px;">';
                echo '<i class="dashicons dashicons-admin-page" style="font-size: 64px; color: #cbd5e1;"></i>';
                echo '<p style="color: #71717a; margin-top: 15px;">' . __('No templates created yet. Click the buttons above to create your first template!', 'probuilder') . '</p>';
                echo '</div>';
            }
            ?>
            
            <script>
            jQuery(document).ready(function($) {
                $('.probuilder-toggle-template').on('click', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const templateId = $btn.data('template-id');
                    const currentlyActive = $btn.data('active') === 1;
                    
                    $btn.prop('disabled', true).text('Processing...');
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'probuilder_toggle_template',
                            template_id: templateId,
                            enable: currentlyActive ? '0' : '1',
                            nonce: '<?php echo wp_create_nonce('probuilder-toggle-template'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                alert('Error: ' + (response.data?.message || 'Unknown error'));
                                $btn.prop('disabled', false);
                            }
                        },
                        error: function() {
                            alert('Error toggling template');
                            $btn.prop('disabled', false);
                        }
                    });
                });
            });
            </script>
        </div>
        <?php
    }
    
    /**
     * Get icon for template type
     */
    private function get_type_icon($type) {
        $icons = [
            'header' => 'align-center',
            'footer' => 'align-full-width',
            'single' => 'media-document',
            'archive' => 'grid-view',
            'product' => 'products',
            '404' => 'warning'
        ];
        return $icons[$type] ?? 'admin-page';
    }
    
    /**
     * Apply templates
     */
    public function apply_templates() {
        // Skip in ProBuilder editor mode
        if (isset($_GET['probuilder']) && $_GET['probuilder'] === 'true') {
            return;
        }
        
        // Get active templates
        $templates = get_posts([
            'post_type' => 'pb_theme_template',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_template_enabled',
                    'value' => '1'
                ]
            ]
        ]);
        
        foreach ($templates as $template) {
            $type = get_post_meta($template->ID, '_template_type', true);
            
            switch ($type) {
                case 'header':
                    add_action('get_header', [$this, 'override_header'], 99);
                    break;
                case 'footer':
                    add_action('get_footer', [$this, 'override_footer'], 99);
                    break;
                case 'single':
                    if (is_single()) {
                        add_filter('template_include', [$this, 'override_single_template']);
                    }
                    break;
                case 'archive':
                    if (is_archive()) {
                        add_filter('template_include', [$this, 'override_archive_template']);
                    }
                    break;
                case '404':
                    if (is_404()) {
                        add_filter('template_include', [$this, 'override_404_template']);
                    }
                    break;
            }
        }
    }
    
    /**
     * Override header
     */
    public function override_header($name) {
        $template = $this->get_active_template('header');
        if ($template) {
            // Don't break the page - just render our custom header
            echo '<header id="probuilder-custom-header" class="probuilder-custom-header" style="width: 100%; display: block;">';
            $this->render_template($template);
            echo '</header>';
            
            // Hide theme's default header with CSS
            echo '<style>#masthead, .site-header:not(#probuilder-custom-header) { display: none !important; }</style>';
        }
    }
    
    /**
     * Override footer
     */
    public function override_footer($name) {
        $template = $this->get_active_template('footer');
        if ($template) {
            // Don't break the page - just render our custom footer
            echo '<footer id="probuilder-custom-footer" class="probuilder-custom-footer" style="width: 100%; display: block;">';
            $this->render_template($template);
            echo '</footer>';
            
            // Hide theme's default footer with CSS
            echo '<style>#colophon, .site-footer:not(#probuilder-custom-footer) { display: none !important; }</style>';
        }
    }
    
    /**
     * Get active template by type
     */
    private function get_active_template($type) {
        $templates = get_posts([
            'post_type' => 'pb_theme_template',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_template_type',
                    'value' => $type
                ],
                [
                    'key' => '_template_enabled',
                    'value' => '1'
                ]
            ]
        ]);
        
        return $templates ? $templates[0] : null;
    }
    
    /**
     * Render template
     */
    private function render_template($template) {
        $data = get_post_meta($template->ID, '_probuilder_data', true);
        
        if ($data && is_array($data)) {
            foreach ($data as $element) {
                ProBuilder_Frontend::instance()->render_element($element);
            }
        }
    }
    
    /**
     * Save template
     */
    public function ajax_save_template() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $template_id = intval($_POST['template_id']);
        $type = sanitize_text_field($_POST['type']);
        $data = json_decode(stripslashes($_POST['data']), true);
        
        update_post_meta($template_id, '_template_type', $type);
        update_post_meta($template_id, '_probuilder_data', $data);
        update_post_meta($template_id, '_template_enabled', '1');
        
        wp_send_json_success(['message' => 'Template saved']);
    }
    
    /**
     * Toggle template activation
     */
    public function ajax_toggle_template() {
        check_ajax_referer('probuilder-toggle-template', 'nonce');
        
        if (!current_user_can('edit_pages')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;
        $enable = isset($_POST['enable']) ? sanitize_text_field($_POST['enable']) : '0';
        
        if (!$template_id) {
            wp_send_json_error(['message' => __('Invalid template ID', 'probuilder')]);
        }
        
        $template = get_post($template_id);
        if (!$template || $template->post_type !== 'pb_theme_template') {
            wp_send_json_error(['message' => __('Template not found', 'probuilder')]);
        }
        
        $type = get_post_meta($template_id, '_template_type', true);
        
        // If enabling, disable other templates of the same type
        if ($enable === '1') {
            $other_templates = get_posts([
                'post_type' => 'pb_theme_template',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'exclude' => [$template_id],
                'meta_query' => [
                    [
                        'key' => '_template_type',
                        'value' => $type
                    ]
                ]
            ]);
            
            foreach ($other_templates as $other) {
                update_post_meta($other->ID, '_template_enabled', '0');
            }
        }
        
        // Update this template
        update_post_meta($template_id, '_template_enabled', $enable);
        
        // Ensure template is published if activating
        if ($enable === '1' && $template->post_status !== 'publish') {
            wp_update_post([
                'ID' => $template_id,
                'post_status' => 'publish'
            ]);
        }
        
        wp_send_json_success([
            'message' => $enable === '1' ? __('Template activated', 'probuilder') : __('Template deactivated', 'probuilder')
        ]);
    }
}

