<?php
/**
 * EcoCommerce Pro Theme Options Page - Part 2
 * Footer, Styling, and Helper Functions
 *
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Footer Options Page
 */
function ecocommerce_pro_render_footer_page() {
    $options = get_option('ecocommerce_pro_footer_options', ecocommerce_pro_get_default_footer_options());
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('ecocommerce_pro_footer'); ?>
            
            <div class="ecocommerce-card">
                <h2><?php _e('Footer Layout', 'ecocommerce-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Footer Columns', 'ecocommerce-pro'); ?></th>
                        <td>
                            <select name="ecocommerce_pro_footer_options[columns]">
                                <option value="1" <?php selected($options['columns'] ?? '4', '1'); ?>><?php _e('1 Column', 'ecocommerce-pro'); ?></option>
                                <option value="2" <?php selected($options['columns'] ?? '4', '2'); ?>><?php _e('2 Columns', 'ecocommerce-pro'); ?></option>
                                <option value="3" <?php selected($options['columns'] ?? '4', '3'); ?>><?php _e('3 Columns', 'ecocommerce-pro'); ?></option>
                                <option value="4" <?php selected($options['columns'] ?? '4', '4'); ?>><?php _e('4 Columns', 'ecocommerce-pro'); ?></option>
                            </select>
                            <p class="description"><?php _e('Choose number of widget columns in footer', 'ecocommerce-pro'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Show Footer Widgets', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_footer_options[show_widgets]" value="1" <?php checked(!empty($options['show_widgets'])); ?> />
                                <?php _e('Display footer widget areas', 'ecocommerce-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Show Social Icons', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_footer_options[show_social]" value="1" <?php checked(!empty($options['show_social'])); ?> />
                                <?php _e('Display social media icons in footer', 'ecocommerce-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Show Payment Icons', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_footer_options[show_payment]" value="1" <?php checked(!empty($options['show_payment'])); ?> />
                                <?php _e('Display payment method icons', 'ecocommerce-pro'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="ecocommerce-card">
                <h2><?php _e('Footer Bottom Bar', 'ecocommerce-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Copyright Text', 'ecocommerce-pro'); ?></th>
                        <td>
                            <textarea name="ecocommerce_pro_footer_options[copyright]" rows="3" class="large-text" placeholder="© [year] [site_title]. All rights reserved."><?php echo esc_textarea($options['copyright'] ?? ''); ?></textarea>
                            <p class="description"><?php _e('Use [year] for current year, [site_title] for site name', 'ecocommerce-pro'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Footer Credit', 'ecocommerce-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="ecocommerce_pro_footer_options[show_credit]" value="1" <?php checked(!empty($options['show_credit'])); ?> />
                                <?php _e('Show theme credit link', 'ecocommerce-pro'); ?>
                            </label>
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
 * Render Styling Options Page
 */
function ecocommerce_pro_render_styling_page() {
    $options = get_option('ecocommerce_pro_styling_options', ecocommerce_pro_get_default_styling_options());
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('ecocommerce_pro_styling'); ?>
            
            <div class="ecocommerce-admin-container">
                <div class="ecocommerce-admin-main">
                    <div class="ecocommerce-card">
                        <h2><?php _e('Color Scheme', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Primary Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[primary_color]" value="<?php echo esc_attr($options['primary_color'] ?? '#4CAF50'); ?>" class="color-picker" data-default-color="#4CAF50" />
                                    <p class="description"><?php _e('Main theme color (buttons, links, etc.)', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Secondary Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[secondary_color]" value="<?php echo esc_attr($options['secondary_color'] ?? '#2196F3'); ?>" class="color-picker" data-default-color="#2196F3" />
                                    <p class="description"><?php _e('Secondary accent color', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Accent Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[accent_color]" value="<?php echo esc_attr($options['accent_color'] ?? '#FF9800'); ?>" class="color-picker" data-default-color="#FF9800" />
                                    <p class="description"><?php _e('Accent color for highlights', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Text Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[text_color]" value="<?php echo esc_attr($options['text_color'] ?? '#333333'); ?>" class="color-picker" data-default-color="#333333" />
                                    <p class="description"><?php _e('Main text color', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Heading Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[heading_color]" value="<?php echo esc_attr($options['heading_color'] ?? '#111111'); ?>" class="color-picker" data-default-color="#111111" />
                                    <p class="description"><?php _e('Color for headings (h1-h6)', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Link Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[link_color]" value="<?php echo esc_attr($options['link_color'] ?? '#4CAF50'); ?>" class="color-picker" data-default-color="#4CAF50" />
                                    <p class="description"><?php _e('Default link color', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Link Hover Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[link_hover_color]" value="<?php echo esc_attr($options['link_hover_color'] ?? '#388E3C'); ?>" class="color-picker" data-default-color="#388E3C" />
                                    <p class="description"><?php _e('Link color on hover', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Background Color', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="text" name="ecocommerce_pro_styling_options[background_color]" value="<?php echo esc_attr($options['background_color'] ?? '#FFFFFF'); ?>" class="color-picker" data-default-color="#FFFFFF" />
                                    <p class="description"><?php _e('Site background color', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h2><?php _e('Typography', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Body Font Family', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <select name="ecocommerce_pro_styling_options[body_font]">
                                        <option value="Inter" <?php selected($options['body_font'] ?? 'Inter', 'Inter'); ?>>Inter (Default)</option>
                                        <option value="Arial" <?php selected($options['body_font'] ?? 'Inter', 'Arial'); ?>>Arial</option>
                                        <option value="Georgia" <?php selected($options['body_font'] ?? 'Inter', 'Georgia'); ?>>Georgia</option>
                                        <option value="Helvetica" <?php selected($options['body_font'] ?? 'Inter', 'Helvetica'); ?>>Helvetica</option>
                                        <option value="Roboto" <?php selected($options['body_font'] ?? 'Inter', 'Roboto'); ?>>Roboto</option>
                                        <option value="Open Sans" <?php selected($options['body_font'] ?? 'Inter', 'Open Sans'); ?>>Open Sans</option>
                                        <option value="Lato" <?php selected($options['body_font'] ?? 'Inter', 'Lato'); ?>>Lato</option>
                                        <option value="Montserrat" <?php selected($options['body_font'] ?? 'Inter', 'Montserrat'); ?>>Montserrat</option>
                                        <option value="Poppins" <?php selected($options['body_font'] ?? 'Inter', 'Poppins'); ?>>Poppins</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Heading Font Family', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <select name="ecocommerce_pro_styling_options[heading_font]">
                                        <option value="Inter" <?php selected($options['heading_font'] ?? 'Inter', 'Inter'); ?>>Inter (Default)</option>
                                        <option value="Arial" <?php selected($options['heading_font'] ?? 'Inter', 'Arial'); ?>>Arial</option>
                                        <option value="Georgia" <?php selected($options['heading_font'] ?? 'Inter', 'Georgia'); ?>>Georgia</option>
                                        <option value="Helvetica" <?php selected($options['heading_font'] ?? 'Inter', 'Helvetica'); ?>>Helvetica</option>
                                        <option value="Roboto" <?php selected($options['heading_font'] ?? 'Inter', 'Roboto'); ?>>Roboto</option>
                                        <option value="Open Sans" <?php selected($options['heading_font'] ?? 'Inter', 'Open Sans'); ?>>Open Sans</option>
                                        <option value="Lato" <?php selected($options['heading_font'] ?? 'Inter', 'Lato'); ?>>Lato</option>
                                        <option value="Montserrat" <?php selected($options['heading_font'] ?? 'Inter', 'Montserrat'); ?>>Montserrat</option>
                                        <option value="Poppins" <?php selected($options['heading_font'] ?? 'Inter', 'Poppins'); ?>>Poppins</option>
                                        <option value="Playfair Display" <?php selected($options['heading_font'] ?? 'Inter', 'Playfair Display'); ?>>Playfair Display</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Body Font Size', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="number" name="ecocommerce_pro_styling_options[body_font_size]" value="<?php echo esc_attr($options['body_font_size'] ?? '16'); ?>" min="12" max="24" /> px
                                    <p class="description"><?php _e('Base font size (12-24px)', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Line Height', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <input type="number" name="ecocommerce_pro_styling_options[line_height]" value="<?php echo esc_attr($options['line_height'] ?? '1.6'); ?>" min="1" max="3" step="0.1" />
                                    <p class="description"><?php _e('Text line height (1.0-3.0)', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h2><?php _e('Button Styles', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Button Style', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <select name="ecocommerce_pro_styling_options[button_style]">
                                        <option value="default" <?php selected($options['button_style'] ?? 'default', 'default'); ?>><?php _e('Default', 'ecocommerce-pro'); ?></option>
                                        <option value="rounded" <?php selected($options['button_style'] ?? 'default', 'rounded'); ?>><?php _e('Rounded', 'ecocommerce-pro'); ?></option>
                                        <option value="square" <?php selected($options['button_style'] ?? 'default', 'square'); ?>><?php _e('Square', 'ecocommerce-pro'); ?></option>
                                        <option value="pill" <?php selected($options['button_style'] ?? 'default', 'pill'); ?>><?php _e('Pill Shape', 'ecocommerce-pro'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php _e('Button Hover Effect', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <select name="ecocommerce_pro_styling_options[button_hover]">
                                        <option value="darken" <?php selected($options['button_hover'] ?? 'darken', 'darken'); ?>><?php _e('Darken', 'ecocommerce-pro'); ?></option>
                                        <option value="lighten" <?php selected($options['button_hover'] ?? 'darken', 'lighten'); ?>><?php _e('Lighten', 'ecocommerce-pro'); ?></option>
                                        <option value="scale" <?php selected($options['button_hover'] ?? 'darken', 'scale'); ?>><?php _e('Scale Up', 'ecocommerce-pro'); ?></option>
                                        <option value="shadow" <?php selected($options['button_hover'] ?? 'darken', 'shadow'); ?>><?php _e('Add Shadow', 'ecocommerce-pro'); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h2><?php _e('Custom CSS', 'ecocommerce-pro'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Additional CSS', 'ecocommerce-pro'); ?></th>
                                <td>
                                    <textarea name="ecocommerce_pro_styling_options[custom_css]" rows="10" class="large-text code" placeholder="/* Your custom CSS here */"><?php echo esc_textarea($options['custom_css'] ?? ''); ?></textarea>
                                    <p class="description"><?php _e('Add custom CSS code to further customize your site appearance', 'ecocommerce-pro'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="ecocommerce-admin-sidebar">
                    <div class="ecocommerce-card">
                        <h3><?php _e('Color Presets', 'ecocommerce-pro'); ?></h3>
                        <p><?php _e('Quick color scheme presets:', 'ecocommerce-pro'); ?></p>
                        <button type="button" class="button preset-button" data-preset="eco-green"><?php _e('Eco Green', 'ecocommerce-pro'); ?></button>
                        <button type="button" class="button preset-button" data-preset="ocean-blue"><?php _e('Ocean Blue', 'ecocommerce-pro'); ?></button>
                        <button type="button" class="button preset-button" data-preset="sunset-orange"><?php _e('Sunset Orange', 'ecocommerce-pro'); ?></button>
                        <button type="button" class="button preset-button" data-preset="royal-purple"><?php _e('Royal Purple', 'ecocommerce-pro'); ?></button>
                    </div>
                    
                    <div class="ecocommerce-card">
                        <h3><?php _e('Reset Styles', 'ecocommerce-pro'); ?></h3>
                        <p><?php _e('Reset all styling options to default values.', 'ecocommerce-pro'); ?></p>
                        <button type="button" class="button button-secondary reset-styles-button"><?php _e('Reset to Defaults', 'ecocommerce-pro'); ?></button>
                    </div>
                </div>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Get default options
 */
function ecocommerce_pro_get_default_general_options() {
    return array(
        'logo' => '',
        'favicon' => '',
        'site_layout' => 'boxed',
        'preloader' => false,
        'preloader_enable' => false,
        'preloader_type' => 'spinner',
        'preloader_color' => '#667eea',
        'preloader_bg' => '#ffffff',
        'preloader_size' => '60',
        'preloader_speed' => 'normal',
        'preloader_custom_image' => '',
        'back_to_top' => true,
        'smooth_scroll' => true,
        'copyright_text' => '© [year] [site_title]. All rights reserved.',
        'ga_code' => '',
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'linkedin' => '',
        'youtube' => '',
        'pinterest' => '',
    );
}

function ecocommerce_pro_get_default_header_options() {
    return array(
        'style' => 'default',
        'sticky' => true,
        'show_search' => true,
        'show_cart' => true,
        'show_social' => false,
        'height' => '80',
        'topbar_enable' => false,
        'topbar_text' => '',
        'phone' => '',
        'email' => '',
    );
}

function ecocommerce_pro_get_default_homepage_options() {
    return array(
        'hero_enable' => true,
        'hero_title' => 'Welcome to Our Store',
        'hero_subtitle' => 'Discover amazing eco-friendly products',
        'hero_button_text' => 'Shop Now',
        'hero_button_url' => '',
        'hero_bg' => '',
        'featured_enable' => true,
        'featured_title' => 'Featured Products',
        'featured_count' => '8',
        'cta_enable' => true,
        'cta_title' => 'Start Shopping Today',
        'cta_description' => 'Join thousands of happy customers',
        'cta_button_text' => 'Get Started',
        'cta_button_url' => '',
    );
}

function ecocommerce_pro_get_default_footer_options() {
    return array(
        'columns' => '4',
        'show_widgets' => true,
        'show_social' => true,
        'show_payment' => true,
        'copyright' => '© [year] [site_title]. All rights reserved.',
        'show_credit' => true,
    );
}

function ecocommerce_pro_get_default_styling_options() {
    return array(
        'primary_color' => '#4CAF50',
        'secondary_color' => '#2196F3',
        'accent_color' => '#FF9800',
        'text_color' => '#333333',
        'heading_color' => '#111111',
        'link_color' => '#4CAF50',
        'link_hover_color' => '#388E3C',
        'background_color' => '#FFFFFF',
        'body_font' => 'Inter',
        'heading_font' => 'Inter',
        'body_font_size' => '16',
        'line_height' => '1.6',
        'button_style' => 'default',
        'button_hover' => 'darken',
        'custom_css' => '',
    );
}

/**
 * Sanitization functions
 */
function ecocommerce_pro_sanitize_general_options($input) {
    $sanitized = array();
    
    if (isset($input['logo'])) {
        $sanitized['logo'] = esc_url_raw($input['logo']);
    }
    
    if (isset($input['favicon'])) {
        $sanitized['favicon'] = esc_url_raw($input['favicon']);
    }
    
    if (isset($input['site_layout'])) {
        $sanitized['site_layout'] = sanitize_text_field($input['site_layout']);
    }
    
    $sanitized['preloader'] = isset($input['preloader']) ? true : false;
    $sanitized['back_to_top'] = isset($input['back_to_top']) ? true : false;
    $sanitized['smooth_scroll'] = isset($input['smooth_scroll']) ? true : false;
    
    if (isset($input['copyright_text'])) {
        $sanitized['copyright_text'] = sanitize_textarea_field($input['copyright_text']);
    }
    
    if (isset($input['ga_code'])) {
        $sanitized['ga_code'] = sanitize_text_field($input['ga_code']);
    }
    
    // Sanitize social links
    $social_fields = array('facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'pinterest');
    foreach ($social_fields as $field) {
        if (isset($input[$field])) {
            $sanitized[$field] = esc_url_raw($input[$field]);
        }
    }
    
    return $sanitized;
}

function ecocommerce_pro_sanitize_header_options($input) {
    $sanitized = array();
    
    if (isset($input['style'])) {
        $sanitized['style'] = sanitize_text_field($input['style']);
    }
    
    $sanitized['sticky'] = isset($input['sticky']) ? true : false;
    $sanitized['show_search'] = isset($input['show_search']) ? true : false;
    $sanitized['show_cart'] = isset($input['show_cart']) ? true : false;
    $sanitized['show_social'] = isset($input['show_social']) ? true : false;
    
    if (isset($input['height'])) {
        $sanitized['height'] = absint($input['height']);
    }
    
    $sanitized['topbar_enable'] = isset($input['topbar_enable']) ? true : false;
    
    if (isset($input['topbar_text'])) {
        $sanitized['topbar_text'] = sanitize_text_field($input['topbar_text']);
    }
    
    if (isset($input['phone'])) {
        $sanitized['phone'] = sanitize_text_field($input['phone']);
    }
    
    if (isset($input['email'])) {
        $sanitized['email'] = sanitize_email($input['email']);
    }
    
    return $sanitized;
}

function ecocommerce_pro_sanitize_homepage_options($input) {
    $sanitized = array();
    
    $sanitized['hero_enable'] = isset($input['hero_enable']) ? true : false;
    
    if (isset($input['hero_title'])) {
        $sanitized['hero_title'] = sanitize_text_field($input['hero_title']);
    }
    
    if (isset($input['hero_subtitle'])) {
        $sanitized['hero_subtitle'] = sanitize_textarea_field($input['hero_subtitle']);
    }
    
    if (isset($input['hero_button_text'])) {
        $sanitized['hero_button_text'] = sanitize_text_field($input['hero_button_text']);
    }
    
    if (isset($input['hero_button_url'])) {
        $sanitized['hero_button_url'] = esc_url_raw($input['hero_button_url']);
    }
    
    if (isset($input['hero_bg'])) {
        $sanitized['hero_bg'] = esc_url_raw($input['hero_bg']);
    }
    
    $sanitized['featured_enable'] = isset($input['featured_enable']) ? true : false;
    
    if (isset($input['featured_title'])) {
        $sanitized['featured_title'] = sanitize_text_field($input['featured_title']);
    }
    
    if (isset($input['featured_count'])) {
        $sanitized['featured_count'] = absint($input['featured_count']);
    }
    
    $sanitized['cta_enable'] = isset($input['cta_enable']) ? true : false;
    
    if (isset($input['cta_title'])) {
        $sanitized['cta_title'] = sanitize_text_field($input['cta_title']);
    }
    
    if (isset($input['cta_description'])) {
        $sanitized['cta_description'] = sanitize_textarea_field($input['cta_description']);
    }
    
    if (isset($input['cta_button_text'])) {
        $sanitized['cta_button_text'] = sanitize_text_field($input['cta_button_text']);
    }
    
    if (isset($input['cta_button_url'])) {
        $sanitized['cta_button_url'] = esc_url_raw($input['cta_button_url']);
    }
    
    return $sanitized;
}

function ecocommerce_pro_sanitize_footer_options($input) {
    $sanitized = array();
    
    if (isset($input['columns'])) {
        $sanitized['columns'] = absint($input['columns']);
    }
    
    $sanitized['show_widgets'] = isset($input['show_widgets']) ? true : false;
    $sanitized['show_social'] = isset($input['show_social']) ? true : false;
    $sanitized['show_payment'] = isset($input['show_payment']) ? true : false;
    
    if (isset($input['copyright'])) {
        $sanitized['copyright'] = sanitize_textarea_field($input['copyright']);
    }
    
    $sanitized['show_credit'] = isset($input['show_credit']) ? true : false;
    
    return $sanitized;
}

function ecocommerce_pro_sanitize_styling_options($input) {
    $sanitized = array();
    
    // Sanitize colors
    $color_fields = array('primary_color', 'secondary_color', 'accent_color', 'text_color', 'heading_color', 'link_color', 'link_hover_color', 'background_color');
    foreach ($color_fields as $field) {
        if (isset($input[$field])) {
            $sanitized[$field] = sanitize_hex_color($input[$field]);
        }
    }
    
    // Sanitize fonts
    if (isset($input['body_font'])) {
        $sanitized['body_font'] = sanitize_text_field($input['body_font']);
    }
    
    if (isset($input['heading_font'])) {
        $sanitized['heading_font'] = sanitize_text_field($input['heading_font']);
    }
    
    if (isset($input['body_font_size'])) {
        $sanitized['body_font_size'] = absint($input['body_font_size']);
    }
    
    if (isset($input['line_height'])) {
        $sanitized['line_height'] = floatval($input['line_height']);
    }
    
    if (isset($input['button_style'])) {
        $sanitized['button_style'] = sanitize_text_field($input['button_style']);
    }
    
    if (isset($input['button_hover'])) {
        $sanitized['button_hover'] = sanitize_text_field($input['button_hover']);
    }
    
    if (isset($input['custom_css'])) {
        $sanitized['custom_css'] = wp_strip_all_tags($input['custom_css']);
    }
    
    return $sanitized;
}

/**
 * Get default cart options
 */
function ecocommerce_pro_get_default_cart_options() {
    return array(
        // Header
        'header_bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'header_text_color' => '#ffffff',
        'header_font_size' => '14',
        'header_padding' => '20',
        
        // Table Body
        'row_bg' => '#ffffff',
        'row_hover_bg' => '#f8f9fa',
        'border_color' => '#f0f0f0',
        'text_color' => '#1f2937',
        'cell_padding' => '24',
        'border_radius' => '12',
        
        // Thumbnail
        'thumbnail_size' => '100',
        'thumbnail_radius' => '10',
        'thumbnail_shadow' => true,
        'thumbnail_hover_effect' => true,
        
        // Remove Button
        'remove_bg' => '#fee',
        'remove_color' => '#e74c3c',
        'remove_hover_bg' => '#e74c3c',
        'remove_hover_color' => '#ffffff',
        'remove_size' => '36',
        'remove_radius' => '8',
        
        // Quantity
        'quantity_width' => '60',
        'quantity_border' => '#e0e0e0',
        'quantity_border_width' => '2',
        'quantity_radius' => '8',
        
        // Cart Totals
        'totals_bg' => '#ffffff',
        'totals_border' => '#f0f0f0',
        'totals_title_color' => '#1f2937',
        'totals_amount_color' => '#2563eb',
        'totals_padding' => '32',
        'totals_radius' => '12',
        
        // Checkout Button
        'checkout_bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'checkout_color' => '#ffffff',
        'checkout_font_size' => '18',
        'checkout_font_weight' => '700',
        'checkout_padding' => '18',
        'checkout_radius' => '10',
        'checkout_uppercase' => true,
        'checkout_shadow' => true,
    );
}

/**
 * Sanitize cart options
 */
function ecocommerce_pro_sanitize_cart_options($input) {
    $sanitized = array();
    
    // Text fields
    $text_fields = array(
        'header_bg', 'header_text_color', 'row_bg', 'row_hover_bg',
        'border_color', 'text_color', 'remove_bg', 'remove_color',
        'remove_hover_bg', 'remove_hover_color', 'quantity_border',
        'totals_bg', 'totals_border', 'totals_title_color', 'totals_amount_color',
        'checkout_bg', 'checkout_color', 'checkout_font_weight'
    );
    
    foreach ($text_fields as $field) {
        if (isset($input[$field])) {
            $sanitized[$field] = sanitize_text_field($input[$field]);
        }
    }
    
    // Number fields
    $number_fields = array(
        'header_font_size', 'header_padding', 'cell_padding', 'border_radius',
        'thumbnail_size', 'thumbnail_radius', 'remove_size', 'remove_radius',
        'quantity_width', 'quantity_border_width', 'quantity_radius',
        'totals_padding', 'totals_radius', 'checkout_font_size', 'checkout_padding', 'checkout_radius'
    );
    
    foreach ($number_fields as $field) {
        if (isset($input[$field])) {
            $sanitized[$field] = absint($input[$field]);
        }
    }
    
    // Boolean fields
    $boolean_fields = array(
        'thumbnail_shadow', 'thumbnail_hover_effect', 'checkout_uppercase', 'checkout_shadow'
    );
    
    foreach ($boolean_fields as $field) {
        $sanitized[$field] = !empty($input[$field]);
    }
    
    return $sanitized;
}

