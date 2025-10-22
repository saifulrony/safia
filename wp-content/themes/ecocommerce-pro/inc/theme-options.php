<?php
/**
 * EcoCommerce Pro Theme Options Page
 * 
 * Creates a comprehensive admin panel for theme customization
 *
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Theme Options page to admin menu
 */
function ecocommerce_pro_add_theme_options_page() {
    add_menu_page(
        __('EcoCommerce Pro Options', 'ecocommerce-pro'),
        __('Theme Options', 'ecocommerce-pro'),
        'manage_options',
        'ecocommerce-pro-options',
        'ecocommerce_pro_render_all_options_compact', // FIXED: Use compact page as default
        'dashicons-admin-customizer',
        61
    );
    
    // Main all-in-one options page (default)
    add_submenu_page(
        'ecocommerce-pro-options',
        __('All Settings', 'ecocommerce-pro'),
        __('⚙️ All Settings', 'ecocommerce-pro'),
        'manage_options',
        'ecocommerce-pro-options',
        'ecocommerce_pro_render_all_options_compact'
    );
    
    // Import Demo page
    add_submenu_page(
        'ecocommerce-pro-options',
        __('Import Demo Content', 'ecocommerce-pro'),
        __('📦 Import Demo', 'ecocommerce-pro'),
        'manage_options',
        'ecocommerce-pro-import-demo',
        'ecocommerce_pro_render_import_demo_page'
    );
    
    // Page Builders page
    add_submenu_page(
        'ecocommerce-pro-options',
        __('Install Page Builders', 'ecocommerce-pro'),
        __('🎨 Page Builders', 'ecocommerce-pro'),
        'manage_options',
        'ecocommerce-pro-builders',
        'ecocommerce_pro_render_builders_page'
    );
    
    // Backup & Restore page
    add_submenu_page(
        'ecocommerce-pro-options',
        __('Backup & Restore Settings', 'ecocommerce-pro'),
        __('💾 Backup & Restore', 'ecocommerce-pro'),
        'manage_options',
        'ecocommerce-pro-backup',
        'ecocommerce_pro_render_backup_page'
    );
}
add_action('admin_menu', 'ecocommerce_pro_add_theme_options_page');

/**
 * Register settings
 */
function ecocommerce_pro_register_settings() {
    // General Settings
    register_setting('ecocommerce_pro_general', 'ecocommerce_pro_general_options', 'ecocommerce_pro_sanitize_general_options');
    
    // Header Settings
    register_setting('ecocommerce_pro_header', 'ecocommerce_pro_header_options', 'ecocommerce_pro_sanitize_header_options');
    
    // Homepage Settings
    register_setting('ecocommerce_pro_homepage', 'ecocommerce_pro_homepage_options', 'ecocommerce_pro_sanitize_homepage_options');
    
    // Footer Settings
    register_setting('ecocommerce_pro_footer', 'ecocommerce_pro_footer_options', 'ecocommerce_pro_sanitize_footer_options');
    
    // Styling Settings
    register_setting('ecocommerce_pro_styling', 'ecocommerce_pro_styling_options', 'ecocommerce_pro_sanitize_styling_options');
    
    // Cart Settings
    register_setting('ecocommerce_pro_cart', 'ecocommerce_pro_cart_options', 'ecocommerce_pro_sanitize_cart_options');
}
add_action('admin_init', 'ecocommerce_pro_register_settings');

/**
 * Enqueue admin styles and scripts
 */
function ecocommerce_pro_admin_enqueue_scripts($hook) {
    if (strpos($hook, 'ecocommerce-pro') === false) {
        return;
    }
    
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_media();
    
    wp_enqueue_style('ecocommerce-pro-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
    
    // Load compact styles for the main options page
    if ($hook === 'toplevel_page_ecocommerce-pro-options' || strpos($hook, 'ecocommerce-pro-options') !== false) {
        wp_enqueue_style('ecocommerce-pro-admin-compact', get_template_directory_uri() . '/assets/css/admin-compact.css', array('ecocommerce-pro-admin'), '1.0.0');
        
        // Enqueue modern color picker
        wp_enqueue_script('ecocommerce-pro-modern-color-picker', get_template_directory_uri() . '/assets/js/modern-color-picker.js', array('jquery'), '1.0.0', true);
        
        // Enqueue backup & restore
        wp_enqueue_script('ecocommerce-pro-backup-restore', get_template_directory_uri() . '/assets/js/backup-restore.js', array('jquery'), '1.0.0', true);
        
        // Enqueue preloader admin
        wp_enqueue_script('ecocommerce-pro-preloader-admin', get_template_directory_uri() . '/assets/js/preloader-admin.js', array('jquery'), '1.0.0', true);
        
        // Localize script for AJAX
        wp_localize_script('ecocommerce-pro-backup-restore', 'ecocommerceBackup', array(
            'nonce' => wp_create_nonce('ecocommerce_backup_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
    
    wp_enqueue_script('ecocommerce-pro-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery', 'wp-color-picker'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'ecocommerce_pro_admin_enqueue_scripts');

/**
 * Render General Options Page
 */
function ecocommerce_pro_render_options_page() {
    $options = get_option('ecocommerce_pro_general_options', ecocommerce_pro_get_default_general_options());
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('ecocommerce_pro_general'); ?>
            
            <div class="ecocommerce-admin-container">
                <div class="ecocommerce-admin-main">
                    <div class="ecocommerce-card">
                        <h2><?php _e('General Settings', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Site Logo', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <div class="logo-upload">
                                        <input type="hidden" name="ecocommerce_pro_general_options[logo]" id="site_logo" value="<?php echo esc_attr($options['logo'] ?? ''); ?>" />
                                        <button type="button" class="button upload-logo-button"><?php _e('Upload Logo', 'ecocommerce-pro'); ?></button>
                                        <button type="button" class="button remove-logo-button"><?php _e('Remove Logo', 'ecocommerce-pro'); ?></button>
                                        <div class="logo-preview">
                                            <?php if (!empty($options['logo'])): ?>
                                                <img src="<?php echo esc_url($options['logo']); ?>" alt="Logo" style="max-width: 200px; height: auto;" />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?php _e('Upload your site logo. Recommended size: 200x60px', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Site Favicon', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <div class="favicon-upload">
                                        <input type="hidden" name="ecocommerce_pro_general_options[favicon]" id="site_favicon" value="<?php echo esc_attr($options['favicon'] ?? ''); ?>" />
                                        <button type="button" class="button upload-favicon-button"><?php _e('Upload Favicon', 'ecocommerce-pro'); ?></button>
                                        <button type="button" class="button remove-favicon-button"><?php _e('Remove Favicon', 'ecocommerce-pro'); ?></button>
                                        <div class="favicon-preview">
                                            <?php if (!empty($options['favicon'])): ?>
                                                <img src="<?php echo esc_url($options['favicon']); ?>" alt="Favicon" style="max-width: 64px; height: auto;" />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?php _e('Upload site favicon. Size: 32x32px or 64x64px', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Site Layout', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <select name="ecocommerce_pro_general_options[site_layout]">
                                        <option value="full-width" <?php selected($options['site_layout'] ?? 'boxed', 'full-width'); ?>><?php _e('Full Width', 'ecocommerce-pro'); ?></option>
                                        <option value="boxed" <?php selected($options['site_layout'] ?? 'boxed', 'boxed'); ?>><?php _e('Boxed', 'ecocommerce-pro'); ?></option>
                                    </select>
                                    <p class="description"><?php _e('Choose the site layout style', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Enable Preloader', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_general_options[preloader]" value="1" <?php checked(!empty($options['preloader'])); ?> />
                                        <?php _e('Show loading animation on page load', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Back to Top Button', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_general_options[back_to_top]" value="1" <?php checked(!empty($options['back_to_top'])); ?> />
                                        <?php _e('Enable back to top button', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Enable Smooth Scroll', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_general_options[smooth_scroll]" value="1" <?php checked(!empty($options['smooth_scroll'])); ?> />
                                        <?php _e('Enable smooth scrolling effect', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Copyright Text', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <textarea name="ecocommerce_pro_general_options[copyright_text]" rows="3" class="large-text"><?php echo esc_textarea($options['copyright_text'] ?? ''); ?></textarea>
                                    <p class="description"><?php _e('Enter copyright text for footer. Use [year] for current year and [site_title] for site name.', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Google Analytics Code', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_general_options[ga_code]" value="<?php echo esc_attr($options['ga_code'] ?? ''); ?>" class="regular-text" placeholder="UA-XXXXXXXXX-X" />
                                    <p class="description"><?php _e('Enter your Google Analytics tracking code', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h2><?php _e('Social Media Links', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Facebook URL', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="url" name="ecocommerce_pro_general_options[facebook]" value="<?php echo esc_url($options['facebook'] ?? ''); ?>" class="regular-text" placeholder="https://facebook.com/yourpage" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Twitter/X URL', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="url" name="ecocommerce_pro_general_options[twitter]" value="<?php echo esc_url($options['twitter'] ?? ''); ?>" class="regular-text" placeholder="https://twitter.com/yourusername" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Instagram URL', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="url" name="ecocommerce_pro_general_options[instagram]" value="<?php echo esc_url($options['instagram'] ?? ''); ?>" class="regular-text" placeholder="https://instagram.com/yourusername" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('LinkedIn URL', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="url" name="ecocommerce_pro_general_options[linkedin]" value="<?php echo esc_url($options['linkedin'] ?? ''); ?>" class="regular-text" placeholder="https://linkedin.com/in/yourprofile" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('YouTube URL', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="url" name="ecocommerce_pro_general_options[youtube]" value="<?php echo esc_url($options['youtube'] ?? ''); ?>" class="regular-text" placeholder="https://youtube.com/c/yourchannel" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Pinterest URL', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="url" name="ecocommerce_pro_general_options[pinterest]" value="<?php echo esc_url($options['pinterest'] ?? ''); ?>" class="regular-text" placeholder="https://pinterest.com/yourprofile" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="ecocommerce-admin-sidebar">
                    <div class="ecocommerce-card">
                        <h3><?php _e('Quick Links', 'ecocommerce-pro'); ?></h3>
                        <ul class="ecocommerce-quick-links">
                            <li><a href="<?php echo admin_url('customize.php'); ?>"><?php _e('Live Customizer', 'ecocommerce-pro'); ?></a></li>
                            <li><a href="<?php echo admin_url('widgets.php'); ?>"><?php _e('Manage Widgets', 'ecocommerce-pro'); ?></a></li>
                            <li><a href="<?php echo admin_url('nav-menus.php'); ?>"><?php _e('Manage Menus', 'ecocommerce-pro'); ?></a></li>
                        </ul>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h3><?php _e('Documentation', 'ecocommerce-pro'); ?></h3>
                        <p><?php _e('Need help? Check out the theme documentation for detailed guides and tutorials.', 'ecocommerce-pro'); ?></p>
                        <a href="#" class="button button-secondary"><?php _e('View Documentation', 'ecocommerce-pro'); ?></a>
                    </div>
                </div>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Render Header Options Page
 */
function ecocommerce_pro_render_header_page() {
    $options = get_option('ecocommerce_pro_header_options', ecocommerce_pro_get_default_header_options());
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('ecocommerce_pro_header'); ?>
            
            <div class="ecocommerce-admin-container">
                <div class="ecocommerce-admin-main">
                    <div class="ecocommerce-card">
                        <h2><?php _e('Header Layout', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Header Style', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <select name="ecocommerce_pro_header_options[style]">
                                        <option value="default" <?php selected($options['style'] ?? 'default', 'default'); ?>><?php _e('Default', 'ecocommerce-pro'); ?></option>
                                        <option value="centered" <?php selected($options['style'] ?? 'default', 'centered'); ?>><?php _e('Centered', 'ecocommerce-pro'); ?></option>
                                        <option value="minimal" <?php selected($options['style'] ?? 'default', 'minimal'); ?>><?php _e('Minimal', 'ecocommerce-pro'); ?></option>
                                        <option value="transparent" <?php selected($options['style'] ?? 'default', 'transparent'); ?>><?php _e('Transparent', 'ecocommerce-pro'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Sticky Header', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_header_options[sticky]" value="1" <?php checked(!empty($options['sticky'])); ?> />
                                        <?php _e('Make header sticky on scroll', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Show Search Bar', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_header_options[show_search]" value="1" <?php checked(!empty($options['show_search'])); ?> />
                                        <?php _e('Display search bar in header', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Show Cart Icon', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_header_options[show_cart]" value="1" <?php checked(!empty($options['show_cart'])); ?> />
                                        <?php _e('Show WooCommerce cart icon in header', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Show Social Icons', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_header_options[show_social]" value="1" <?php checked(!empty($options['show_social'])); ?> />
                                        <?php _e('Display social media icons in header', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Header Height', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="number" name="ecocommerce_pro_header_options[height]" value="<?php echo esc_attr($options['height'] ?? '80'); ?>" min="50" max="200" /> px
                                    <p class="description"><?php _e('Set header height in pixels (50-200)', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h2><?php _e('Top Bar Settings', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Enable Top Bar', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ecocommerce_pro_header_options[topbar_enable]" value="1" <?php checked(!empty($options['topbar_enable'])); ?> />
                                        <?php _e('Show top bar above header', 'ecocommerce-pro'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Top Bar Text', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_header_options[topbar_text]" value="<?php echo esc_attr($options['topbar_text'] ?? ''); ?>" class="large-text" placeholder="Welcome to our store!" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Phone Number', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_header_options[phone]" value="<?php echo esc_attr($options['phone'] ?? ''); ?>" class="regular-text" placeholder="+1 234 567 8900" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Email Address', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="email" name="ecocommerce_pro_header_options[email]" value="<?php echo esc_attr($options['email'] ?? ''); ?>" class="regular-text" placeholder="info@example.com" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="ecocommerce-admin-sidebar">
                    <div class="ecocommerce-card">
                        <h3><?php _e('Preview', 'ecocommerce-pro'); ?></h3>
                        <p><?php _e('Changes will be reflected on your live site.', 'ecocommerce-pro'); ?></p>
                        <a href="<?php echo home_url('/'); ?>" target="_blank" class="button"><?php _e('View Site', 'ecocommerce-pro'); ?></a>
                    </div>
                </div>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Render Homepage Options Page
 */
function ecocommerce_pro_render_homepage_page() {
    $options = get_option('ecocommerce_pro_homepage_options', ecocommerce_pro_get_default_homepage_options());
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('ecocommerce_pro_homepage'); ?>
            
            <div class="ecocommerce-card">
                <h2><?php _e('Hero Section', 'ecocommerce-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Enable Hero Section', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_homepage_options[hero_enable]" value="1" <?php checked(!empty($options['hero_enable'])); ?> />
                                <?php _e('Show hero section on homepage', 'ecocommerce-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Hero Title', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="text" name="ecocommerce_pro_homepage_options[hero_title]" value="<?php echo esc_attr($options['hero_title'] ?? ''); ?>" class="large-text" placeholder="Welcome to Our Store" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Hero Subtitle', 'ecocommerce-pro'); ?></th>
                        <td>
                            <textarea name="ecocommerce_pro_homepage_options[hero_subtitle]" rows="3" class="large-text" placeholder="Discover amazing products at great prices"><?php echo esc_textarea($options['hero_subtitle'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Hero Button Text', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="text" name="ecocommerce_pro_homepage_options[hero_button_text]" value="<?php echo esc_attr($options['hero_button_text'] ?? ''); ?>" class="regular-text" placeholder="Shop Now" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Hero Button URL', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="url" name="ecocommerce_pro_homepage_options[hero_button_url]" value="<?php echo esc_url($options['hero_button_url'] ?? ''); ?>" class="large-text" placeholder="https://example.com/shop" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Hero Background Image', 'ecocommerce-pro'); ?></th>
                        <td>
                            <div class="hero-bg-upload">
                                <input type="hidden" name="ecocommerce_pro_homepage_options[hero_bg]" id="hero_bg" value="<?php echo esc_attr($options['hero_bg'] ?? ''); ?>" />
                                <button type="button" class="button upload-hero-bg-button"><?php _e('Upload Image', 'ecocommerce-pro'); ?></button>
                                <button type="button" class="button remove-hero-bg-button"><?php _e('Remove Image', 'ecocommerce-pro'); ?></button>
                                <div class="hero-bg-preview">
                                    <?php if (!empty($options['hero_bg'])): ?>
                                        <img src="<?php echo esc_url($options['hero_bg']); ?>" alt="Hero Background" style="max-width: 400px; height: auto;" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="ecocommerce-card">
                <h2><?php _e('Featured Products Section', 'ecocommerce-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Enable Featured Products', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_homepage_options[featured_enable]" value="1" <?php checked(!empty($options['featured_enable'])); ?> />
                                <?php _e('Show featured products section', 'ecocommerce-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Section Title', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="text" name="ecocommerce_pro_homepage_options[featured_title]" value="<?php echo esc_attr($options['featured_title'] ?? ''); ?>" class="large-text" placeholder="Featured Products" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Number of Products', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="number" name="ecocommerce_pro_homepage_options[featured_count]" value="<?php echo esc_attr($options['featured_count'] ?? '8'); ?>" min="4" max="20" />
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="ecocommerce-card">
                <h2><?php _e('Call to Action Section', 'ecocommerce-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Enable CTA Section', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_homepage_options[cta_enable]" value="1" <?php checked(!empty($options['cta_enable'])); ?> />
                                <?php _e('Show call to action section', 'ecocommerce-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('CTA Title', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="text" name="ecocommerce_pro_homepage_options[cta_title]" value="<?php echo esc_attr($options['cta_title'] ?? ''); ?>" class="large-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('CTA Description', 'ecocommerce-pro'); ?></th>
                        <td>
                            <textarea name="ecocommerce_pro_homepage_options[cta_description]" rows="3" class="large-text"><?php echo esc_textarea($options['cta_description'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('CTA Button Text', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="text" name="ecocommerce_pro_homepage_options[cta_button_text]" value="<?php echo esc_attr($options['cta_button_text'] ?? ''); ?>" class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('CTA Button URL', 'ecocommerce-pro'); ?></th>
                        <td>
                            <input type="url" name="ecocommerce_pro_homepage_options[cta_button_url]" value="<?php echo esc_url($options['cta_button_url'] ?? ''); ?>" class="large-text" />
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * AJAX Handler: Backup all theme settings
 */
function ecocommerce_backup_settings_ajax() {
    check_ajax_referer('ecocommerce_backup_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
        return;
    }
    
    // Get all theme options
    $backup_data = array(
        'version' => '1.0',
        'timestamp' => current_time('mysql'),
        'site_url' => get_site_url(),
        'settings' => array(
            'general' => get_option('ecocommerce_pro_general_options', array()),
            'header' => get_option('ecocommerce_pro_header_options', array()),
            'homepage' => get_option('ecocommerce_pro_homepage_options', array()),
            'footer' => get_option('ecocommerce_pro_footer_options', array()),
            'styling' => get_option('ecocommerce_pro_styling_options', array()),
            'cart' => get_option('ecocommerce_pro_cart_options', array()),
        )
    );
    
    wp_send_json_success($backup_data);
}
add_action('wp_ajax_ecocommerce_backup_settings', 'ecocommerce_backup_settings_ajax');

/**
 * AJAX Handler: Restore theme settings from backup
 */
function ecocommerce_restore_settings_ajax() {
    check_ajax_referer('ecocommerce_backup_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
        return;
    }
    
    $settings_json = isset($_POST['settings']) ? stripslashes($_POST['settings']) : '';
    $backup_data = json_decode($settings_json, true);
    
    if (!$backup_data || !isset($backup_data['settings'])) {
        wp_send_json_error('Invalid backup file');
        return;
    }
    
    // Restore each option group
    if (isset($backup_data['settings']['general'])) {
        update_option('ecocommerce_pro_general_options', $backup_data['settings']['general']);
    }
    if (isset($backup_data['settings']['header'])) {
        update_option('ecocommerce_pro_header_options', $backup_data['settings']['header']);
    }
    if (isset($backup_data['settings']['homepage'])) {
        update_option('ecocommerce_pro_homepage_options', $backup_data['settings']['homepage']);
    }
    if (isset($backup_data['settings']['footer'])) {
        update_option('ecocommerce_pro_footer_options', $backup_data['settings']['footer']);
    }
    if (isset($backup_data['settings']['styling'])) {
        update_option('ecocommerce_pro_styling_options', $backup_data['settings']['styling']);
    }
    if (isset($backup_data['settings']['cart'])) {
        update_option('ecocommerce_pro_cart_options', $backup_data['settings']['cart']);
    }
    
    wp_send_json_success('Settings restored successfully');
}
add_action('wp_ajax_ecocommerce_restore_settings', 'ecocommerce_restore_settings_ajax');

/**
 * AJAX Handler: Reset all settings to defaults
 */
function ecocommerce_reset_settings_ajax() {
    check_ajax_referer('ecocommerce_backup_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
        return;
    }
    
    // Reset all options to defaults
    update_option('ecocommerce_pro_general_options', ecocommerce_pro_get_default_general_options());
    update_option('ecocommerce_pro_header_options', ecocommerce_pro_get_default_header_options());
    update_option('ecocommerce_pro_homepage_options', ecocommerce_pro_get_default_homepage_options());
    update_option('ecocommerce_pro_footer_options', ecocommerce_pro_get_default_footer_options());
    update_option('ecocommerce_pro_styling_options', ecocommerce_pro_get_default_styling_options());
    update_option('ecocommerce_pro_cart_options', ecocommerce_pro_get_default_cart_options());
    
    wp_send_json_success('All settings reset to defaults');
}
add_action('wp_ajax_ecocommerce_reset_settings', 'ecocommerce_reset_settings_ajax');

/**
 * Render Backup & Restore Page
 */
function ecocommerce_pro_render_backup_page() {
    ?>
    <div class="wrap ecocommerce-backup-restore-page">
        <h1>💾 Backup & Restore Theme Settings</h1>
        
        <div class="backup-restore-container">
            <!-- Backup Card -->
                <div class="backup-card-full">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h2>📥 Backup Settings</h2>
                        <p>Download all your theme settings as a JSON file</p>
                    </div>
                    <div class="card-body">
                        <div class="backup-info">
                            <h3>What Gets Backed Up:</h3>
                            <ul class="backup-list">
                                <li>✅ <strong>General Settings</strong> - Site layout, preloader, social links</li>
                                <li>✅ <strong>Header Options</strong> - Template, colors, menu settings</li>
                                <li>✅ <strong>Homepage Options</strong> - Hero, sections, featured content</li>
                                <li>✅ <strong>Footer Options</strong> - Layout, widgets, copyright</li>
                                <li>✅ <strong>Styling Options</strong> - Colors, typography, custom CSS</li>
                                <li>✅ <strong>Cart Options</strong> - All cart customizations</li>
                            </ul>
                            <p class="total-options">Total: <strong>140+ options</strong> will be backed up!</p>
                        </div>
                        
                        <button type="button" class="button button-primary button-hero" id="backup-settings-page">
                            <span class="dashicons dashicons-download"></span>
                            Download Backup File
                        </button>
                        
                        <p class="help-text">File will be named: <code>ecocommerce-pro-backup-[timestamp].json</code></p>
                    </div>
                </div>
                
                <!-- Restore Card -->
                <div class="backup-card-full">
                    <div class="card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <h2>📤 Restore Settings</h2>
                        <p>Upload a backup file to restore your settings</p>
                    </div>
                    <div class="card-body">
                        <div class="restore-info">
                            <h3>How It Works:</h3>
                            <ol class="restore-steps">
                                <li>Select your backup JSON file</li>
                                <li>File is validated automatically</li>
                                <li>Confirm the restoration</li>
                                <li>Settings are restored instantly</li>
                                <li>Page reloads with restored settings</li>
                            </ol>
                            
                            <div class="warning-box">
                                <span class="warning-icon">⚠️</span>
                                <strong>Warning:</strong> This will overwrite your current settings. Consider backing up first!
                            </div>
                        </div>
                        
                        <button type="button" class="button button-secondary button-hero" id="restore-settings-page">
                            <span class="dashicons dashicons-upload"></span>
                            Upload Backup File
                        </button>
                        <input type="file" id="restore-file-input" accept=".json" style="display: none;" />
                    </div>
                </div>
                
                <!-- Reset Card -->
                <div class="backup-card-full">
                    <div class="card-header" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                        <h2>🔄 Reset to Defaults</h2>
                        <p>Reset all theme settings to their original values</p>
                    </div>
                    <div class="card-body">
                        <div class="reset-info">
                            <h3>What Happens:</h3>
                            <ul class="reset-points">
                                <li>❌ All customizations will be removed</li>
                                <li>❌ Colors, layouts, and options reset</li>
                                <li>❌ Custom CSS and settings cleared</li>
                                <li>✅ Theme returns to default state</li>
                                <li>✅ Can be undone by restoring backup</li>
                            </ul>
                            
                            <div class="danger-box">
                                <span class="danger-icon">🚨</span>
                                <strong>Danger:</strong> This action cannot be undone unless you have a backup!
                            </div>
                        </div>
                        
                        <button type="button" class="button button-link-delete button-hero" id="reset-settings-page">
                            <span class="dashicons dashicons-update"></span>
                            Reset All Settings
                        </button>
                    </div>
                </div>
        </div>
    </div>
    
    <style>
        .ecocommerce-backup-restore-page {
            margin: 20px 20px 20px 0;
        }
        
        .backup-restore-container {
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-top: 30px;
        }
        
        .backup-card-full {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-header {
            padding: 24px;
            color: white;
        }
        
        .card-header h2 {
            margin: 0 0 8px 0;
            color: white;
            font-size: 20px;
        }
        
        .card-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .card-body {
            padding: 24px;
        }
        
        .backup-info h3,
        .restore-info h3,
        .reset-info h3 {
            font-size: 16px;
            margin: 0 0 16px 0;
            color: #1f2937;
        }
        
        .backup-list,
        .reset-points {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }
        
        .backup-list li,
        .reset-points li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }
        
        .backup-list li:last-child,
        .reset-points li:last-child {
            border-bottom: none;
        }
        
        .restore-steps {
            padding-left: 20px;
            margin: 0 0 20px 0;
        }
        
        .restore-steps li {
            padding: 8px 0;
            font-size: 14px;
            color: #6b7280;
        }
        
        .total-options {
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .total-options strong {
            color: #667eea;
            font-size: 18px;
        }
        
        .warning-box,
        .danger-box {
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
        
        .danger-box {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
        }
        
        .warning-icon,
        .danger-icon {
            font-size: 20px;
        }
        
        .help-text {
            margin-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }
        
        .help-text code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .button-hero .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
            margin-top: 4px;
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Same functionality as sidebar buttons
        $('#backup-settings-page').on('click', function() {
            $('#backup-all-settings').trigger('click');
        });
        
        $('#restore-settings-page').on('click', function() {
            $('#restore-settings').trigger('click');
        });
        
        $('#reset-settings-page').on('click', function() {
            $('#reset-all-settings').trigger('click');
        });
    });
    </script>
    <?php
}

/**
 * Continue to part 2...
 */
