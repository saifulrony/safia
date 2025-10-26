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
     * Theme builder admin page
     */
    public function theme_builder_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Theme Builder', 'probuilder'); ?></h1>
            
            <div class="probuilder-theme-builder-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 30px;">
                
                <!-- Header Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-align-center" style="font-size: 48px; color: #344047;"></i>
                    <h2><?php _e('Header', 'probuilder'); ?></h2>
                    <p><?php _e('Build custom site header', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=header'); ?>" class="button button-primary"><?php _e('Create Header', 'probuilder'); ?></a>
                </div>
                
                <!-- Footer Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-align-full-width" style="font-size: 48px; color: #344047;"></i>
                    <h2><?php _e('Footer', 'probuilder'); ?></h2>
                    <p><?php _e('Build custom site footer', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=footer'); ?>" class="button button-primary"><?php _e('Create Footer', 'probuilder'); ?></a>
                </div>
                
                <!-- Single Post Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-media-document" style="font-size: 48px; color: #344047;"></i>
                    <h2><?php _e('Single Post', 'probuilder'); ?></h2>
                    <p><?php _e('Build custom single post template', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=single'); ?>" class="button button-primary"><?php _e('Create Template', 'probuilder'); ?></a>
                </div>
                
                <!-- Archive Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-grid-view" style="font-size: 48px; color: #344047;"></i>
                    <h2><?php _e('Archive', 'probuilder'); ?></h2>
                    <p><?php _e('Build custom archive template', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=archive'); ?>" class="button button-primary"><?php _e('Create Template', 'probuilder'); ?></a>
                </div>
                
                <!-- Product Single Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-products" style="font-size: 48px; color: #344047;"></i>
                    <h2><?php _e('Single Product', 'probuilder'); ?></h2>
                    <p><?php _e('Build custom product template', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=product'); ?>" class="button button-primary"><?php _e('Create Template', 'probuilder'); ?></a>
                </div>
                
                <!-- 404 Template -->
                <div class="template-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <i class="dashicons dashicons-warning" style="font-size: 48px; color: #344047;"></i>
                    <h2><?php _e('404 Page', 'probuilder'); ?></h2>
                    <p><?php _e('Build custom 404 page', 'probuilder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=pb_theme_template&template_type=404'); ?>" class="button button-primary"><?php _e('Create Template', 'probuilder'); ?></a>
                </div>
                
            </div>
            
            <h2 style="margin-top: 40px;"><?php _e('Active Templates', 'probuilder'); ?></h2>
            <?php
            $templates = get_posts([
                'post_type' => 'pb_theme_template',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            ]);
            
            if ($templates) {
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($templates as $template) {
                    $type = get_post_meta($template->ID, '_template_type', true);
                    $enabled = get_post_meta($template->ID, '_template_enabled', true);
                    
                    echo '<tr>';
                    echo '<td>' . esc_html($template->post_title) . '</td>';
                    echo '<td>' . esc_html(ucfirst($type)) . '</td>';
                    echo '<td>' . ($enabled ? '<span style="color: green;">●</span> Active' : '<span style="color: gray;">●</span> Inactive') . '</td>';
                    echo '<td>';
                    echo '<a href="' . admin_url('post.php?post=' . $template->ID . '&action=edit') . '" class="button button-small">Edit</a> ';
                    echo '<a href="' . add_query_arg(['p' => $template->ID, 'probuilder' => 'true'], home_url('/')) . '" class="button button-small">Edit with ProBuilder</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<p>' . __('No templates created yet.', 'probuilder') . '</p>';
            }
            ?>
        </div>
        <?php
    }
    
    /**
     * Apply templates
     */
    public function apply_templates() {
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
                    add_action('get_header', [$this, 'override_header']);
                    break;
                case 'footer':
                    add_action('get_footer', [$this, 'override_footer']);
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
    public function override_header() {
        $template = $this->get_active_template('header');
        if ($template) {
            $this->render_template($template);
            return null;
        }
    }
    
    /**
     * Override footer
     */
    public function override_footer() {
        $template = $this->get_active_template('footer');
        if ($template) {
            $this->render_template($template);
            return null;
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
}

