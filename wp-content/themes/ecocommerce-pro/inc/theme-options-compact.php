<?php
/**
 * EcoCommerce Pro - Compact Theme Options with Tabs
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Compact Theme Options Page with Tabs
 */
function ecocommerce_pro_render_compact_options_page() {
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
    
    // Get all options
    $general_options = get_option('ecocommerce_pro_general_options', ecocommerce_pro_get_default_general_options());
    $header_options = get_option('ecocommerce_pro_header_options', ecocommerce_pro_get_default_header_options());
    $homepage_options = get_option('ecocommerce_pro_homepage_options', ecocommerce_pro_get_default_homepage_options());
    $footer_options = get_option('ecocommerce_pro_footer_options', ecocommerce_pro_get_default_footer_options());
    $styling_options = get_option('ecocommerce_pro_styling_options', ecocommerce_pro_get_default_styling_options());
    
    ?>
    <div class="wrap ecocommerce-pro-compact">
        <h1 class="compact-main-title">⚙️ Theme Options</h1>
        
        <!-- Tab Navigation -->
        <nav class="nav-tab-wrapper compact-tabs">
            <a href="?page=ecocommerce-pro-options-compact&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">⚙️</span> General
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=branding" class="nav-tab <?php echo $active_tab === 'branding' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">🎨</span> Branding
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=layout" class="nav-tab <?php echo $active_tab === 'layout' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">📐</span> Layout
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=colors" class="nav-tab <?php echo $active_tab === 'colors' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">🎨</span> Colors
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=typography" class="nav-tab <?php echo $active_tab === 'typography' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">✍️</span> Typography
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=header" class="nav-tab <?php echo $active_tab === 'header' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">📍</span> Header
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=footer" class="nav-tab <?php echo $active_tab === 'footer' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">🦶</span> Footer
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=homepage" class="nav-tab <?php echo $active_tab === 'homepage' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">🏠</span> Homepage
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=shop" class="nav-tab <?php echo $active_tab === 'shop' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">🛍️</span> Shop
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=social" class="nav-tab <?php echo $active_tab === 'social' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">📱</span> Social
            </a>
            <a href="?page=ecocommerce-pro-options-compact&tab=advanced" class="nav-tab <?php echo $active_tab === 'advanced' ? 'nav-tab-active' : ''; ?>">
                <span class="tab-icon">⚡</span> Advanced
            </a>
        </nav>
        
        <form method="post" action="options.php" class="compact-form">
            <?php
            // Output appropriate settings fields based on active tab
            if ($active_tab === 'general') {
                settings_fields('ecocommerce_pro_general');
                ecocommerce_pro_render_general_tab($general_options);
            } elseif ($active_tab === 'branding') {
                settings_fields('ecocommerce_pro_general');
                ecocommerce_pro_render_branding_tab($general_options);
            } elseif ($active_tab === 'layout') {
                settings_fields('ecocommerce_pro_general');
                ecocommerce_pro_render_layout_tab($general_options);
            } elseif ($active_tab === 'colors') {
                settings_fields('ecocommerce_pro_styling');
                ecocommerce_pro_render_colors_tab($styling_options);
            } elseif ($active_tab === 'typography') {
                settings_fields('ecocommerce_pro_styling');
                ecocommerce_pro_render_typography_tab($styling_options);
            } elseif ($active_tab === 'header') {
                settings_fields('ecocommerce_pro_header');
                ecocommerce_pro_render_header_tab($header_options);
            } elseif ($active_tab === 'footer') {
                settings_fields('ecocommerce_pro_footer');
                ecocommerce_pro_render_footer_tab($footer_options);
            } elseif ($active_tab === 'homepage') {
                settings_fields('ecocommerce_pro_homepage');
                ecocommerce_pro_render_homepage_tab($homepage_options);
            } elseif ($active_tab === 'shop') {
                settings_fields('ecocommerce_pro_general');
                ecocommerce_pro_render_shop_tab($general_options);
            } elseif ($active_tab === 'social') {
                settings_fields('ecocommerce_pro_general');
                ecocommerce_pro_render_social_tab($general_options);
            } elseif ($active_tab === 'advanced') {
                settings_fields('ecocommerce_pro_general');
                ecocommerce_pro_render_advanced_tab($general_options);
            }
            ?>
            
            <div class="compact-submit">
                <?php submit_button('💾 Save All Changes', 'primary', 'submit', false); ?>
                <button type="button" class="button button-secondary reset-tab-btn">🔄 Reset This Tab</button>
            </div>
        </form>
    </div>
    
    <style>
    .ecocommerce-pro-compact {
        margin: -20px -20px -10px -10px;
        background: #f8f9fa;
    }
    
    .compact-main-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin: 0;
        padding: 24px 30px;
        font-size: 24px;
        font-weight: 700;
    }
    
    .compact-tabs {
        background: white;
        border-bottom: 2px solid #e5e7eb;
        padding: 0 20px;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .compact-tabs .nav-tab {
        margin: 0;
        padding: 12px 20px;
        border: none;
        border-bottom: 3px solid transparent;
        background: transparent;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    
    .compact-tabs .nav-tab:hover {
        background: #f9fafb;
        border-bottom-color: #2563eb;
    }
    
    .compact-tabs .nav-tab-active {
        border-bottom-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }
    
    .tab-icon {
        font-size: 16px;
    }
    
    .compact-form {
        padding: 30px;
        background: white;
        min-height: 400px;
    }
    
    .compact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .compact-field {
        margin-bottom: 20px;
    }
    
    .compact-field label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #374151;
        font-size: 14px;
    }
    
    .compact-field input[type="text"],
    .compact-field input[type="url"],
    .compact-field input[type="email"],
    .compact-field input[type="number"],
    .compact-field textarea,
    .compact-field select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .compact-field input:focus,
    .compact-field textarea:focus,
    .compact-field select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .compact-field small {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
    }
    
    .compact-submit {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 20px 30px;
        margin: 30px -30px -30px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        gap: 12px;
        z-index: 10;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .accordion-section {
        margin-bottom: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .accordion-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #f3f4f6 100%);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: #1f2937;
        transition: all 0.2s;
        user-select: none;
    }
    
    .accordion-header:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
    }
    
    .accordion-header.active {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
    }
    
    .accordion-icon {
        transition: transform 0.3s;
        font-size: 20px;
    }
    
    .accordion-header.active .accordion-icon {
        transform: rotate(180deg);
    }
    
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background: white;
    }
    
    .accordion-content.active {
        max-height: 2000px;
        padding: 20px;
    }
    
    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .three-column {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    
    @media (max-width: 1024px) {
        .compact-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
        }
        
        .two-column,
        .three-column {
            grid-template-columns: 1fr;
        }
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Accordion functionality
        $('.accordion-header').on('click', function() {
            var $header = $(this);
            var $content = $header.next('.accordion-content');
            var $icon = $header.find('.accordion-icon');
            
            // Toggle active class
            $header.toggleClass('active');
            $content.toggleClass('active');
        });
        
        // Open first accordion by default
        $('.accordion-section:first .accordion-header').click();
    });
    </script>
    <?php
}

/**
 * Render General Tab
 */
function ecocommerce_pro_render_general_tab($options) {
    ?>
    <div class="tab-content">
        <div class="accordion-section">
            <div class="accordion-header">
                <span>🌐 Site Information</span>
                <span class="accordion-icon">▼</span>
            </div>
            <div class="accordion-content">
                <div class="two-column">
                    <div class="compact-field">
                        <label>Site Title</label>
                        <input type="text" name="ecocommerce_pro_general_options[site_title]" value="<?php echo esc_attr($options['site_title'] ?? get_bloginfo('name')); ?>" />
                        <small>Your website name</small>
                    </div>
                    
                    <div class="compact-field">
                        <label>Site Tagline</label>
                        <input type="text" name="ecocommerce_pro_general_options[site_tagline]" value="<?php echo esc_attr($options['site_tagline'] ?? get_bloginfo('description')); ?>" />
                        <small>Short description of your site</small>
                    </div>
                </div>
                
                <div class="compact-field">
                    <label>Copyright Text</label>
                    <input type="text" name="ecocommerce_pro_general_options[copyright_text]" value="<?php echo esc_attr($options['copyright_text'] ?? ''); ?>" placeholder="© [year] [site_title]. All rights reserved." />
                    <small>Use [year] for current year, [site_title] for site name</small>
                </div>
            </div>
        </div>
        
        <div class="accordion-section">
            <div class="accordion-header">
                <span>🔧 Site Settings</span>
                <span class="accordion-icon">▼</span>
            </div>
            <div class="accordion-content">
                <div class="two-column">
                    <div class="compact-field">
                        <?php ecocommerce_pro_toggle_switch(array(
                            'name' => 'ecocommerce_pro_general_options[preloader]',
                            'checked' => !empty($options['preloader']),
                            'label' => 'Enable Preloader',
                            'description' => 'Show loading animation on page load'
                        )); ?>
                    </div>
                    
                    <div class="compact-field">
                        <?php ecocommerce_pro_toggle_switch(array(
                            'name' => 'ecocommerce_pro_general_options[back_to_top]',
                            'checked' => !empty($options['back_to_top']),
                            'label' => 'Back to Top Button',
                            'description' => 'Show scroll-to-top button'
                        )); ?>
                    </div>
                    
                    <div class="compact-field">
                        <?php ecocommerce_pro_toggle_switch(array(
                            'name' => 'ecocommerce_pro_general_options[smooth_scroll]',
                            'checked' => !empty($options['smooth_scroll']),
                            'label' => 'Smooth Scrolling',
                            'description' => 'Enable smooth scroll effect'
                        )); ?>
                    </div>
                    
                    <div class="compact-field">
                        <?php ecocommerce_pro_toggle_switch(array(
                            'name' => 'ecocommerce_pro_general_options[lazy_load]',
                            'checked' => !empty($options['lazy_load']),
                            'label' => 'Lazy Load Images',
                            'description' => 'Improve performance'
                        )); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="accordion-section">
            <div class="accordion-header">
                <span>📊 Analytics & Tracking</span>
                <span class="accordion-icon">▼</span>
            </div>
            <div class="accordion-content">
                <div class="compact-field">
                    <label>Google Analytics Code</label>
                    <input type="text" name="ecocommerce_pro_general_options[ga_code]" value="<?php echo esc_attr($options['ga_code'] ?? ''); ?>" placeholder="UA-XXXXXXXXX-X or G-XXXXXXXXXX" />
                    <small>Enter your Google Analytics tracking ID</small>
                </div>
                
                <div class="compact-field">
                    <label>Facebook Pixel ID</label>
                    <input type="text" name="ecocommerce_pro_general_options[fb_pixel]" value="<?php echo esc_attr($options['fb_pixel'] ?? ''); ?>" placeholder="XXXXXXXXXXXXXXXX" />
                    <small>Track conversions with Facebook Pixel</small>
                </div>
                
                <div class="compact-field">
                    <label>Custom Header Scripts</label>
                    <textarea name="ecocommerce_pro_general_options[header_scripts]" rows="4" placeholder="<!-- Analytics or verification codes -->"><?php echo esc_textarea($options['header_scripts'] ?? ''); ?></textarea>
                    <small>Scripts added to &lt;head&gt; section</small>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Branding Tab
 */
function ecocommerce_pro_render_branding_tab($options) {
    ?>
    <div class="tab-content">
        <div class="compact-grid">
            <div class="compact-field">
                <label>Site Logo</label>
                <div class="media-upload-compact">
                    <input type="hidden" name="ecocommerce_pro_general_options[logo]" id="site_logo" value="<?php echo esc_attr($options['logo'] ?? ''); ?>" />
                    <div class="media-preview-compact">
                        <?php if (!empty($options['logo'])): ?>
                            <img src="<?php echo esc_url($options['logo']); ?>" alt="Logo" />
                        <?php else: ?>
                            <span class="preview-placeholder">No logo</span>
                        <?php endif; ?>
                    </div>
                    <div class="media-buttons-compact">
                        <button type="button" class="button upload-logo-button">Upload Logo</button>
                        <button type="button" class="button remove-logo-button">Remove</button>
                    </div>
                </div>
                <small>Recommended: 200x60px</small>
            </div>
            
            <div class="compact-field">
                <label>Favicon</label>
                <div class="media-upload-compact">
                    <input type="hidden" name="ecocommerce_pro_general_options[favicon]" id="site_favicon" value="<?php echo esc_attr($options['favicon'] ?? ''); ?>" />
                    <div class="media-preview-compact">
                        <?php if (!empty($options['favicon'])): ?>
                            <img src="<?php echo esc_url($options['favicon']); ?>" alt="Favicon" style="max-width: 64px;" />
                        <?php else: ?>
                            <span class="preview-placeholder">No favicon</span>
                        <?php endif; ?>
                    </div>
                    <div class="media-buttons-compact">
                        <button type="button" class="button upload-favicon-button">Upload Favicon</button>
                        <button type="button" class="button remove-favicon-button">Remove</button>
                    </div>
                </div>
                <small>Size: 32x32px or 64x64px</small>
            </div>
        </div>
        
        <div class="compact-grid" style="margin-top: 30px;">
            <div class="compact-field">
                <label>Logo Width (px)</label>
                <input type="number" name="ecocommerce_pro_general_options[logo_width]" value="<?php echo esc_attr($options['logo_width'] ?? '150'); ?>" min="50" max="400" />
                <small>Logo display width (50-400px)</small>
            </div>
            
            <div class="compact-field">
                <label>Logo Position</label>
                <select name="ecocommerce_pro_general_options[logo_position]">
                    <option value="left" <?php selected($options['logo_position'] ?? 'left', 'left'); ?>>Left</option>
                    <option value="center" <?php selected($options['logo_position'] ?? 'left', 'center'); ?>>Center</option>
                    <option value="right" <?php selected($options['logo_position'] ?? 'left', 'right'); ?>>Right</option>
                </select>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Colors Tab - Compact with Live Preview
 */
function ecocommerce_pro_render_colors_tab($options) {
    ?>
    <div class="tab-content">
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px;">
            <div>
                <div class="accordion-section">
                    <div class="accordion-header">
                        <span>🎨 Primary Colors</span>
                        <span class="accordion-icon">▼</span>
                    </div>
                    <div class="accordion-content">
                        <div class="three-column">
                            <div class="compact-field">
                                <label>Primary Color</label>
                                <input type="text" name="ecocommerce_pro_styling_options[primary_color]" value="<?php echo esc_attr($options['primary_color'] ?? '#2563eb'); ?>" class="color-picker" />
                            </div>
                            
                            <div class="compact-field">
                                <label>Secondary Color</label>
                                <input type="text" name="ecocommerce_pro_styling_options[secondary_color]" value="<?php echo esc_attr($options['secondary_color'] ?? '#10b981'); ?>" class="color-picker" />
                            </div>
                            
                            <div class="compact-field">
                                <label>Accent Color</label>
                                <input type="text" name="ecocommerce_pro_styling_options[accent_color]" value="<?php echo esc_attr($options['accent_color'] ?? '#f59e0b'); ?>" class="color-picker" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-section">
                    <div class="accordion-header">
                        <span>📝 Text Colors</span>
                        <span class="accordion-icon">▼</span>
                    </div>
                    <div class="accordion-content">
                        <div class="three-column">
                            <div class="compact-field">
                                <label>Text Color</label>
                                <input type="text" name="ecocommerce_pro_styling_options[text_color]" value="<?php echo esc_attr($options['text_color'] ?? '#333333'); ?>" class="color-picker" />
                            </div>
                            
                            <div class="compact-field">
                                <label>Heading Color</label>
                                <input type="text" name="ecocommerce_pro_styling_options[heading_color]" value="<?php echo esc_attr($options['heading_color'] ?? '#111111'); ?>" class="color-picker" />
                            </div>
                            
                            <div class="compact-field">
                                <label>Link Color</label>
                                <input type="text" name="ecocommerce_pro_styling_options[link_color]" value="<?php echo esc_attr($options['link_color'] ?? '#2563eb'); ?>" class="color-picker" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-section">
                    <div class="accordion-header">
                        <span>🎨 Color Presets</span>
                        <span class="accordion-icon">▼</span>
                    </div>
                    <div class="accordion-content">
                        <div class="compact-grid">
                            <button type="button" class="preset-button-compact" data-preset="eco-green">
                                <span class="preset-swatch" style="background: linear-gradient(135deg, #4CAF50, #8BC34A);"></span>
                                Eco Green
                            </button>
                            <button type="button" class="preset-button-compact" data-preset="ocean-blue">
                                <span class="preset-swatch" style="background: linear-gradient(135deg, #2196F3, #03A9F4);"></span>
                                Ocean Blue
                            </button>
                            <button type="button" class="preset-button-compact" data-preset="sunset-orange">
                                <span class="preset-swatch" style="background: linear-gradient(135deg, #FF9800, #FF5722);"></span>
                                Sunset Orange
                            </button>
                            <button type="button" class="preset-button-compact" data-preset="royal-purple">
                                <span class="preset-swatch" style="background: linear-gradient(135deg, #9C27B0, #E91E63);"></span>
                                Royal Purple
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Live Preview Sidebar -->
            <div class="live-preview-sidebar">
                <div class="live-preview-sticky">
                    <h3 style="margin: 0 0 20px 0; font-size: 16px;">👁️ Live Preview</h3>
                    <?php
                    $colors = array(
                        'primary' => $options['primary_color'] ?? '#2563eb',
                        'text' => $options['text_color'] ?? '#333333',
                    );
                    ecocommerce_pro_color_preview($colors);
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .live-preview-sidebar {
        position: sticky;
        top: 32px;
    }
    
    .live-preview-sticky {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .preset-button-compact {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        width: 100%;
        text-align: left;
    }
    
    .preset-button-compact:hover {
        border-color: #2563eb;
        transform: translateX(5px);
    }
    
    .preset-swatch {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .media-upload-compact {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
    }
    
    .media-preview-compact {
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        background: #f9fafb;
        border-radius: 8px;
        padding: 10px;
    }
    
    .media-preview-compact img {
        max-width: 100%;
        max-height: 100px;
    }
    
    .preview-placeholder {
        color: #9ca3af;
        font-style: italic;
    }
    
    .media-buttons-compact {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .media-buttons-compact .button {
        flex: 1;
        max-width: 150px;
        font-size: 13px;
        padding: 8px 16px;
    }
    </style>
    <?php
}

// Additional tab rendering functions would continue here...
// (Typography, Header, Footer, Homepage, Shop, Social, Advanced tabs)

