<?php
/**
 * EcoCommerce Pro - Compact All-in-One Options Page
 * 100+ Options with Tabs, Accordions, and Search
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Compact All Options Page
 */
function ecocommerce_pro_render_all_options_compact() {
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
    
    // Get all options
    $general_options = get_option('ecocommerce_pro_general_options', ecocommerce_pro_get_default_general_options());
    $header_options = get_option('ecocommerce_pro_header_options', ecocommerce_pro_get_default_header_options());
    $homepage_options = get_option('ecocommerce_pro_homepage_options', ecocommerce_pro_get_default_homepage_options());
    $footer_options = get_option('ecocommerce_pro_footer_options', ecocommerce_pro_get_default_footer_options());
    $styling_options = get_option('ecocommerce_pro_styling_options', ecocommerce_pro_get_default_styling_options());
    
    ?>
    <div class="wrap ecocommerce-all-options-compact">
        <div class="options-header-compact">
            <h1>⚙️ Theme Options</h1>
            <div class="header-actions">
                <input type="text" id="options-search" placeholder="🔍 Search options..." class="search-options-input" />
                <a href="<?php echo home_url(); ?>" target="_blank" class="btn-preview">👁️ Preview Site</a>
            </div>
        </div>
        
        <!-- Vertical Tab Navigation -->
        <div class="vertical-layout">
            <nav class="vertical-tab-nav">
                <a href="?page=ecocommerce-pro-options&tab=general" class="vertical-tab <?php echo $active_tab === 'general' ? 'active' : ''; ?>">
                    <span class="tab-icon">⚙️</span>
                    <span class="tab-label">General</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=colors" class="vertical-tab <?php echo $active_tab === 'colors' ? 'active' : ''; ?>">
                    <span class="tab-icon">🎨</span>
                    <span class="tab-label">Colors</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=typography" class="vertical-tab <?php echo $active_tab === 'typography' ? 'active' : ''; ?>">
                    <span class="tab-icon">✍️</span>
                    <span class="tab-label">Typography</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=layout" class="vertical-tab <?php echo $active_tab === 'layout' ? 'active' : ''; ?>">
                    <span class="tab-icon">📐</span>
                    <span class="tab-label">Layout</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=header" class="vertical-tab <?php echo $active_tab === 'header' ? 'active' : ''; ?>">
                    <span class="tab-icon">📍</span>
                    <span class="tab-label">Header</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=footer" class="vertical-tab <?php echo $active_tab === 'footer' ? 'active' : ''; ?>">
                    <span class="tab-icon">🦶</span>
                    <span class="tab-label">Footer</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=homepage" class="vertical-tab <?php echo $active_tab === 'homepage' ? 'active' : ''; ?>">
                    <span class="tab-icon">🏠</span>
                    <span class="tab-label">Homepage</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=shop" class="vertical-tab <?php echo $active_tab === 'shop' ? 'active' : ''; ?>">
                    <span class="tab-icon">🛍️</span>
                    <span class="tab-label">Shop</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=cart" class="vertical-tab <?php echo $active_tab === 'cart' ? 'active' : ''; ?>">
                    <span class="tab-icon">🛒</span>
                    <span class="tab-label">Cart Page</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=performance" class="vertical-tab <?php echo $active_tab === 'performance' ? 'active' : ''; ?>">
                    <span class="tab-icon">⚡</span>
                    <span class="tab-label">Performance</span>
                </a>
                <a href="?page=ecocommerce-pro-options&tab=seo" class="vertical-tab <?php echo $active_tab === 'seo' ? 'active' : ''; ?>">
                    <span class="tab-icon">🔍</span>
                    <span class="tab-label">SEO</span>
                </a>
            </nav>
            
            <div class="vertical-content-wrapper">
                <div class="compact-content-wrapper">
                    <div class="compact-content-main">
                        <form method="post" action="options.php" id="theme-options-form">
                    <?php
                    // Render tab content
                    switch ($active_tab) {
                        case 'general':
                            settings_fields('ecocommerce_pro_general');
                            ecocommerce_compact_general_tab($general_options);
                            break;
                        case 'colors':
                            settings_fields('ecocommerce_pro_styling');
                            ecocommerce_compact_colors_tab($styling_options);
                            break;
                        case 'typography':
                            settings_fields('ecocommerce_pro_styling');
                            ecocommerce_compact_typography_tab($styling_options);
                            break;
                        case 'layout':
                            settings_fields('ecocommerce_pro_general');
                            ecocommerce_compact_layout_tab($general_options);
                            break;
                        case 'header':
                            settings_fields('ecocommerce_pro_header');
                            ecocommerce_compact_header_tab($header_options);
                            break;
                        case 'footer':
                            settings_fields('ecocommerce_pro_footer');
                            ecocommerce_compact_footer_tab($footer_options);
                            break;
                        case 'homepage':
                            settings_fields('ecocommerce_pro_homepage');
                            ecocommerce_compact_homepage_tab($homepage_options);
                            break;
                        case 'shop':
                            settings_fields('ecocommerce_pro_general');
                            ecocommerce_compact_shop_tab($general_options);
                            break;
                        case 'cart':
                            settings_fields('ecocommerce_pro_cart');
                            ecocommerce_compact_cart_tab($general_options);
                            break;
                        case 'performance':
                            settings_fields('ecocommerce_pro_general');
                            ecocommerce_compact_performance_tab($general_options);
                            break;
                        case 'seo':
                            settings_fields('ecocommerce_pro_general');
                            ecocommerce_compact_seo_tab($general_options);
                            break;
                    }
                    ?>
                    
                            <div class="compact-footer-actions">
                                <?php submit_button('💾 Save Changes', 'primary large', 'submit', false); ?>
                                <button type="button" class="button button-secondary reset-tab-button">🔄 Reset Tab</button>
                                <button type="button" class="button button-secondary export-settings-button">📥 Export Settings</button>
                            </div>
                        </form>
                    </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Search functionality
        $('#options-search').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            
            $('.accordion-section').each(function() {
                var $section = $(this);
                var sectionText = $section.text().toLowerCase();
                
                if (sectionText.indexOf(searchTerm) > -1 || searchTerm === '') {
                    $section.show();
                    if (searchTerm !== '') {
                        $section.find('.accordion-header').addClass('active');
                        $section.find('.accordion-content').addClass('active');
                    }
                } else {
                    $section.hide();
                }
            });
            
            // Show message if no results
            var visibleSections = $('.accordion-section:visible').length;
            $('#search-results-info').remove();
            
            if (searchTerm !== '' && visibleSections === 0) {
                $('.compact-content-main').prepend('<div id="search-results-info" class="no-results">No options found for "' + searchTerm + '"</div>');
            } else if (searchTerm !== '') {
                $('.compact-content-main').prepend('<div id="search-results-info" class="search-info">Found ' + visibleSections + ' matching section(s)</div>');
            }
        });
        
        // Accordion functionality
        $(document).on('click', '.accordion-header', function() {
            var $header = $(this);
            var $content = $header.next('.accordion-content');
            
            $header.toggleClass('active');
            $content.toggleClass('active');
        });
        
        // Reset tab
        $('.reset-tab-button').on('click', function() {
            if (confirm('Reset all options in this tab to defaults?')) {
                $(this).closest('form').find('input[type="text"], input[type="url"], input[type="email"], input[type="number"], textarea').val('');
                $(this).closest('form').find('input[type="checkbox"]').prop('checked', false);
                $(this).closest('form').find('select').prop('selectedIndex', 0);
            }
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('#theme-options-form').submit();
            }
        });
    });
    </script>
    <?php
}

/**
 * Render General Tab with Accordions
 */
function ecocommerce_compact_general_tab($options) {
    ?>
    <!-- Preloader Settings Accordion -->
    <div class="accordion-section active" data-keywords="preloader loading spinner animation custom image gif">
        <div class="accordion-header">
            <span><span class="accordion-emoji">⏳</span> Preloader Settings</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="field-compact full-width">
                <?php ecocommerce_pro_toggle_switch(array(
                    'name' => 'ecocommerce_pro_general_options[preloader_enable]',
                    'checked' => !empty($options['preloader_enable']),
                    'label' => 'Enable Preloader',
                    'description' => 'Show loading animation'
                )); ?>
            </div>
            
            <div class="field-compact full-width">
                <label class="field-label-compact">Preloader Type</label>
                <div class="preloader-type-grid">
                    <?php
                    $preloader_type = $options['preloader_type'] ?? 'spinner';
                    $types = array(
                        'spinner' => array('name' => 'Spinner', 'icon' => '🔄'),
                        'dots' => array('name' => 'Dots', 'icon' => '⚫'),
                        'bars' => array('name' => 'Bars', 'icon' => '📊'),
                        'circle' => array('name' => 'Circle', 'icon' => '⭕'),
                        'pulse' => array('name' => 'Pulse', 'icon' => '💗'),
                        'wave' => array('name' => 'Wave', 'icon' => '🌊'),
                        'bounce' => array('name' => 'Bounce', 'icon' => '⚽'),
                        'flip' => array('name' => 'Flip', 'icon' => '🔃'),
                        'custom' => array('name' => 'Custom Image', 'icon' => '🖼️')
                    );
                    
                    foreach ($types as $key => $type) :
                    ?>
                        <label class="preloader-option <?php echo $preloader_type === $key ? 'selected' : ''; ?>">
                            <input type="radio" name="ecocommerce_pro_general_options[preloader_type]" value="<?php echo esc_attr($key); ?>" <?php checked($preloader_type, $key); ?> />
                            <div class="preloader-preview">
                                <span class="preloader-icon"><?php echo $type['icon']; ?></span>
                                <span class="preloader-name"><?php echo $type['name']; ?></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="field-compact full-width custom-preloader-section" style="<?php echo $preloader_type !== 'custom' ? 'display:none;' : ''; ?>">
                <label class="field-label-compact">Custom Preloader Image/GIF</label>
                <div class="image-upload-wrapper">
                    <input type="text" name="ecocommerce_pro_general_options[preloader_custom_image]" value="<?php echo esc_attr($options['preloader_custom_image'] ?? ''); ?>" class="input-compact preloader-custom-url" readonly />
                    <button type="button" class="button button-secondary upload-preloader-btn">Upload Image/GIF</button>
                    <?php if (!empty($options['preloader_custom_image'])) : ?>
                        <button type="button" class="button button-secondary remove-preloader-btn">Remove</button>
                    <?php endif; ?>
                </div>
                <?php if (!empty($options['preloader_custom_image'])) : ?>
                    <div class="preloader-preview-image">
                        <img src="<?php echo esc_url($options['preloader_custom_image']); ?>" style="max-width: 150px; max-height: 150px; margin-top: 10px;" />
                    </div>
                <?php endif; ?>
                <small class="field-description">Upload a PNG, GIF, or SVG (max 200KB recommended)</small>
            </div>
            
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Preloader Color</label>
                    <input type="text" name="ecocommerce_pro_general_options[preloader_color]" value="<?php echo esc_attr($options['preloader_color'] ?? '#667eea'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Background Color</label>
                    <input type="text" name="ecocommerce_pro_general_options[preloader_bg]" value="<?php echo esc_attr($options['preloader_bg'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Size</label>
                    <input type="number" name="ecocommerce_pro_general_options[preloader_size]" value="<?php echo esc_attr($options['preloader_size'] ?? '60'); ?>" min="30" max="150" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Animation Speed</label>
                    <select name="ecocommerce_pro_general_options[preloader_speed]" class="select-compact">
                        <option value="slow" <?php selected($options['preloader_speed'] ?? 'normal', 'slow'); ?>>Slow (2s)</option>
                        <option value="normal" <?php selected($options['preloader_speed'] ?? 'normal', 'normal'); ?>>Normal (1s)</option>
                        <option value="fast" <?php selected($options['preloader_speed'] ?? 'normal', 'fast'); ?>>Fast (0.5s)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Site Settings Accordion -->
    <div class="accordion-section" data-keywords="site back top smooth scroll animation">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🌐</span> Site Settings</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[back_to_top]',
                        'checked' => !empty($options['back_to_top']),
                        'label' => 'Back to Top',
                        'description' => 'Scroll button'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[smooth_scroll]',
                        'checked' => !empty($options['smooth_scroll']),
                        'label' => 'Smooth Scroll',
                        'description' => 'Smooth animation'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[lazy_load]',
                        'checked' => !empty($options['lazy_load']),
                        'label' => 'Lazy Load',
                        'description' => 'Load images on scroll'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[animations]',
                        'checked' => !empty($options['animations']),
                        'label' => 'Animations',
                        'description' => 'CSS animations'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[boxed_layout]',
                        'checked' => !empty($options['boxed_layout']),
                        'label' => 'Boxed Layout',
                        'description' => 'Centered container'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Branding Accordion -->
    <div class="accordion-section" data-keywords="logo favicon brand icon site identity">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎨</span> Branding & Logo</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Site Logo</label>
                    <div class="media-compact">
                        <input type="hidden" name="ecocommerce_pro_general_options[logo]" id="site_logo" value="<?php echo esc_attr($options['logo'] ?? ''); ?>" />
                        <div class="media-preview-mini">
                            <?php if (!empty($options['logo'])): ?>
                                <img src="<?php echo esc_url($options['logo']); ?>" alt="Logo" />
                            <?php else: ?>
                                <span class="no-media">📷</span>
                            <?php endif; ?>
                        </div>
                        <div class="media-actions-compact">
                            <button type="button" class="btn-compact btn-primary upload-logo-button">Upload</button>
                            <button type="button" class="btn-compact btn-danger remove-logo-button">Remove</button>
                        </div>
                    </div>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Favicon</label>
                    <div class="media-compact">
                        <input type="hidden" name="ecocommerce_pro_general_options[favicon]" id="site_favicon" value="<?php echo esc_attr($options['favicon'] ?? ''); ?>" />
                        <div class="media-preview-mini">
                            <?php if (!empty($options['favicon'])): ?>
                                <img src="<?php echo esc_url($options['favicon']); ?>" alt="Favicon" style="max-width: 32px;" />
                            <?php else: ?>
                                <span class="no-media">🔖</span>
                            <?php endif; ?>
                        </div>
                        <div class="media-actions-compact">
                            <button type="button" class="btn-compact btn-primary upload-favicon-button">Upload</button>
                            <button type="button" class="btn-compact btn-danger remove-favicon-button">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="compact-grid-3">
                <div class="field-compact">
                    <label class="field-label-compact">Logo Width (px)</label>
                    <input type="number" name="ecocommerce_pro_general_options[logo_width]" value="<?php echo esc_attr($options['logo_width'] ?? '150'); ?>" min="50" max="400" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Logo Position</label>
                    <select name="ecocommerce_pro_general_options[logo_position]" class="select-compact">
                        <option value="left" <?php selected($options['logo_position'] ?? 'left', 'left'); ?>>Left</option>
                        <option value="center" <?php selected($options['logo_position'] ?? 'left', 'center'); ?>>Center</option>
                        <option value="right" <?php selected($options['logo_position'] ?? 'left', 'right'); ?>>Right</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[retina_logo]',
                        'checked' => !empty($options['retina_logo']),
                        'label' => 'Retina Logo',
                        'description' => '2x resolution'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Information -->
    <div class="accordion-section" data-keywords="contact email phone address location">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📞</span> Contact Information</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Email Address</label>
                    <input type="email" name="ecocommerce_pro_general_options[contact_email]" value="<?php echo esc_attr($options['contact_email'] ?? ''); ?>" placeholder="info@example.com" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Phone Number</label>
                    <input type="text" name="ecocommerce_pro_general_options[contact_phone]" value="<?php echo esc_attr($options['contact_phone'] ?? ''); ?>" placeholder="+1 234 567 8900" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Address Line 1</label>
                    <input type="text" name="ecocommerce_pro_general_options[address_1]" value="<?php echo esc_attr($options['address_1'] ?? ''); ?>" placeholder="123 Main Street" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Address Line 2</label>
                    <input type="text" name="ecocommerce_pro_general_options[address_2]" value="<?php echo esc_attr($options['address_2'] ?? ''); ?>" placeholder="Suite 100" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">City</label>
                    <input type="text" name="ecocommerce_pro_general_options[city]" value="<?php echo esc_attr($options['city'] ?? ''); ?>" placeholder="New York" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Country</label>
                    <input type="text" name="ecocommerce_pro_general_options[country]" value="<?php echo esc_attr($options['country'] ?? ''); ?>" placeholder="USA" class="input-compact" />
                </div>
            </div>
        </div>
    </div>
    
    <!-- Social Media Links -->
    <div class="accordion-section" data-keywords="social media facebook twitter instagram linkedin youtube pinterest">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📱</span> Social Media (10 Networks)</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">🔵 Facebook</label>
                    <input type="url" name="ecocommerce_pro_general_options[facebook]" value="<?php echo esc_url($options['facebook'] ?? ''); ?>" placeholder="https://facebook.com/yourpage" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">🐦 Twitter/X</label>
                    <input type="url" name="ecocommerce_pro_general_options[twitter]" value="<?php echo esc_url($options['twitter'] ?? ''); ?>" placeholder="https://twitter.com/username" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">📸 Instagram</label>
                    <input type="url" name="ecocommerce_pro_general_options[instagram]" value="<?php echo esc_url($options['instagram'] ?? ''); ?>" placeholder="https://instagram.com/username" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">💼 LinkedIn</label>
                    <input type="url" name="ecocommerce_pro_general_options[linkedin]" value="<?php echo esc_url($options['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/company/name" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">▶️ YouTube</label>
                    <input type="url" name="ecocommerce_pro_general_options[youtube]" value="<?php echo esc_url($options['youtube'] ?? ''); ?>" placeholder="https://youtube.com/c/channel" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">📌 Pinterest</label>
                    <input type="url" name="ecocommerce_pro_general_options[pinterest]" value="<?php echo esc_url($options['pinterest'] ?? ''); ?>" placeholder="https://pinterest.com/username" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">💬 TikTok</label>
                    <input type="url" name="ecocommerce_pro_general_options[tiktok]" value="<?php echo esc_url($options['tiktok'] ?? ''); ?>" placeholder="https://tiktok.com/@username" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">👻 Snapchat</label>
                    <input type="url" name="ecocommerce_pro_general_options[snapchat]" value="<?php echo esc_url($options['snapchat'] ?? ''); ?>" placeholder="https://snapchat.com/add/username" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">📺 Vimeo</label>
                    <input type="url" name="ecocommerce_pro_general_options[vimeo]" value="<?php echo esc_url($options['vimeo'] ?? ''); ?>" placeholder="https://vimeo.com/username" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">💬 WhatsApp</label>
                    <input type="text" name="ecocommerce_pro_general_options[whatsapp]" value="<?php echo esc_attr($options['whatsapp'] ?? ''); ?>" placeholder="+1234567890" class="input-compact" />
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Colors Tab - Ultra Compact
 */
function ecocommerce_compact_colors_tab($options) {
    ?>
    <div class="colors-tab-layout">
        <div class="colors-main">
            <!-- Primary Colors -->
            <div class="accordion-section" data-keywords="primary secondary accent brand colors">
                <div class="accordion-header">
                    <span><span class="accordion-emoji">🎨</span> Brand Colors</span>
                    <span class="accordion-toggle">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="color-grid-compact">
                        <div class="color-field-compact">
                            <label>Primary</label>
                            <input type="text" name="ecocommerce_pro_styling_options[primary_color]" value="<?php echo esc_attr($options['primary_color'] ?? '#2563eb'); ?>" class="color-picker-compact" data-default="#2563eb" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Secondary</label>
                            <input type="text" name="ecocommerce_pro_styling_options[secondary_color]" value="<?php echo esc_attr($options['secondary_color'] ?? '#10b981'); ?>" class="color-picker-compact" data-default="#10b981" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Accent</label>
                            <input type="text" name="ecocommerce_pro_styling_options[accent_color]" value="<?php echo esc_attr($options['accent_color'] ?? '#f59e0b'); ?>" class="color-picker-compact" data-default="#f59e0b" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Success</label>
                            <input type="text" name="ecocommerce_pro_styling_options[success_color]" value="<?php echo esc_attr($options['success_color'] ?? '#10b981'); ?>" class="color-picker-compact" data-default="#10b981" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Warning</label>
                            <input type="text" name="ecocommerce_pro_styling_options[warning_color]" value="<?php echo esc_attr($options['warning_color'] ?? '#f59e0b'); ?>" class="color-picker-compact" data-default="#f59e0b" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Danger</label>
                            <input type="text" name="ecocommerce_pro_styling_options[danger_color]" value="<?php echo esc_attr($options['danger_color'] ?? '#ef4444'); ?>" class="color-picker-compact" data-default="#ef4444" />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Text Colors -->
            <div class="accordion-section" data-keywords="text heading link paragraph body font color">
                <div class="accordion-header">
                    <span><span class="accordion-emoji">📝</span> Text & Links</span>
                    <span class="accordion-toggle">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="color-grid-compact">
                        <div class="color-field-compact">
                            <label>Body Text</label>
                            <input type="text" name="ecocommerce_pro_styling_options[text_color]" value="<?php echo esc_attr($options['text_color'] ?? '#333333'); ?>" class="wp-color-picker-field" data-default-color="#333333" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Headings</label>
                            <input type="text" name="ecocommerce_pro_styling_options[heading_color]" value="<?php echo esc_attr($options['heading_color'] ?? '#111111'); ?>" class="wp-color-picker-field" data-default-color="#111111" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Links</label>
                            <input type="text" name="ecocommerce_pro_styling_options[link_color]" value="<?php echo esc_attr($options['link_color'] ?? '#2563eb'); ?>" class="wp-color-picker-field" data-default-color="#2563eb" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Link Hover</label>
                            <input type="text" name="ecocommerce_pro_styling_options[link_hover_color]" value="<?php echo esc_attr($options['link_hover_color'] ?? '#1e40af'); ?>" class="wp-color-picker-field" data-default-color="#1e40af" />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Background Colors -->
            <div class="accordion-section" data-keywords="background body header footer section">
                <div class="accordion-header">
                    <span><span class="accordion-emoji">🖼️</span> Backgrounds</span>
                    <span class="accordion-toggle">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="color-grid-compact">
                        <div class="color-field-compact">
                            <label>Body Background</label>
                            <input type="text" name="ecocommerce_pro_styling_options[background_color]" value="<?php echo esc_attr($options['background_color'] ?? '#ffffff'); ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Header Background</label>
                            <input type="text" name="ecocommerce_pro_styling_options[header_bg_color]" value="<?php echo esc_attr($options['header_bg_color'] ?? '#ffffff'); ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Footer Background</label>
                            <input type="text" name="ecocommerce_pro_styling_options[footer_bg_color]" value="<?php echo esc_attr($options['footer_bg_color'] ?? '#1f2937'); ?>" class="wp-color-picker-field" data-default-color="#1f2937" />
                        </div>
                        
                        <div class="color-field-compact">
                            <label>Section Alt</label>
                            <input type="text" name="ecocommerce_pro_styling_options[alt_bg_color]" value="<?php echo esc_attr($options['alt_bg_color'] ?? '#f9fafb'); ?>" class="wp-color-picker-field" data-default-color="#f9fafb" />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Color Presets -->
            <div class="accordion-section">
                <div class="accordion-header">
                    <span><span class="accordion-emoji">✨</span> Color Presets (One-Click)</span>
                    <span class="accordion-toggle">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="preset-grid-compact">
                        <button type="button" class="preset-card-compact" data-preset="eco-green">
                            <div class="preset-colors">
                                <span style="background: #4CAF50;"></span>
                                <span style="background: #8BC34A;"></span>
                                <span style="background: #FF9800;"></span>
                            </div>
                            <span class="preset-name">Eco Green</span>
                        </button>
                        
                        <button type="button" class="preset-card-compact" data-preset="ocean-blue">
                            <div class="preset-colors">
                                <span style="background: #2196F3;"></span>
                                <span style="background: #03A9F4;"></span>
                                <span style="background: #00BCD4;"></span>
                            </div>
                            <span class="preset-name">Ocean Blue</span>
                        </button>
                        
                        <button type="button" class="preset-card-compact" data-preset="sunset-orange">
                            <div class="preset-colors">
                                <span style="background: #FF9800;"></span>
                                <span style="background: #FF5722;"></span>
                                <span style="background: #FFC107;"></span>
                            </div>
                            <span class="preset-name">Sunset Orange</span>
                        </button>
                        
                        <button type="button" class="preset-card-compact" data-preset="royal-purple">
                            <div class="preset-colors">
                                <span style="background: #9C27B0;"></span>
                                <span style="background: #E91E63;"></span>
                                <span style="background: #FF4081;"></span>
                            </div>
                            <span class="preset-name">Royal Purple</span>
                        </button>
                        
                        <button type="button" class="preset-card-compact" data-preset="dark-mode">
                            <div class="preset-colors">
                                <span style="background: #1f2937;"></span>
                                <span style="background: #374151;"></span>
                                <span style="background: #6b7280;"></span>
                            </div>
                            <span class="preset-name">Dark Mode</span>
                        </button>
                        
                        <button type="button" class="preset-card-compact" data-preset="minimalist">
                            <div class="preset-colors">
                                <span style="background: #000000;"></span>
                                <span style="background: #ffffff;"></span>
                                <span style="background: #f3f4f6;"></span>
                            </div>
                            <span class="preset-name">Minimalist</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sticky Live Preview -->
        <div class="colors-preview-sidebar">
            <div class="preview-sticky-wrapper">
                <h4 class="preview-title">👁️ Live Preview</h4>
                <?php
                $colors = array(
                    'primary' => $options['primary_color'] ?? '#2563eb',
                    'text' => $options['text_color'] ?? '#333333',
                );
                ecocommerce_pro_color_preview($colors);
                ?>
                
                <div class="preview-info">
                    <small>Colors update in real-time as you pick them</small>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Typography Tab
 */
function ecocommerce_compact_typography_tab($options) {
    ?>
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">✍️</span> Font Families</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Body Font</label>
                    <select name="ecocommerce_pro_styling_options[body_font]" class="select-compact">
                        <option value="Inter" <?php selected($options['body_font'] ?? 'Inter', 'Inter'); ?>>Inter</option>
                        <option value="Roboto" <?php selected($options['body_font'] ?? 'Inter', 'Roboto'); ?>>Roboto</option>
                        <option value="Open Sans" <?php selected($options['body_font'] ?? 'Inter', 'Open Sans'); ?>>Open Sans</option>
                        <option value="Lato" <?php selected($options['body_font'] ?? 'Inter', 'Lato'); ?>>Lato</option>
                        <option value="Poppins" <?php selected($options['body_font'] ?? 'Inter', 'Poppins'); ?>>Poppins</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Heading Font</label>
                    <select name="ecocommerce_pro_styling_options[heading_font]" class="select-compact">
                        <option value="Inter" <?php selected($options['heading_font'] ?? 'Inter', 'Inter'); ?>>Inter</option>
                        <option value="Roboto" <?php selected($options['heading_font'] ?? 'Inter', 'Roboto'); ?>>Roboto</option>
                        <option value="Montserrat" <?php selected($options['heading_font'] ?? 'Inter', 'Montserrat'); ?>>Montserrat</option>
                        <option value="Playfair Display" <?php selected($options['heading_font'] ?? 'Inter', 'Playfair Display'); ?>>Playfair Display</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📏</span> Font Sizes</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <label class="field-label-compact">Body Font Size</label>
                    <input type="number" name="ecocommerce_pro_styling_options[body_font_size]" value="<?php echo esc_attr($options['body_font_size'] ?? '16'); ?>" min="12" max="24" class="input-compact" /> px
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Line Height</label>
                    <input type="number" name="ecocommerce_pro_styling_options[line_height]" value="<?php echo esc_attr($options['line_height'] ?? '1.6'); ?>" min="1" max="3" step="0.1" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Letter Spacing</label>
                    <input type="number" name="ecocommerce_pro_styling_options[letter_spacing]" value="<?php echo esc_attr($options['letter_spacing'] ?? '0'); ?>" min="-2" max="5" step="0.1" class="input-compact" /> px
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Layout Tab
 */
function ecocommerce_compact_layout_tab($options) {
    ?>
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📐</span> Site Layout</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <?php
            ecocommerce_pro_visual_radio(array(
                'name' => 'ecocommerce_pro_general_options[site_layout]',
                'value' => $options['site_layout'] ?? 'full-width',
                'options' => array(
                    'full-width' => array(
                        'label' => 'Full Width',
                        'description' => 'Content spans full width',
                        'image' => '',
                    ),
                    'boxed' => array(
                        'label' => 'Boxed',
                        'description' => 'Content in container',
                        'image' => '',
                    ),
                ),
            ));
            ?>
        </div>
    </div>
    
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📦</span> Container Widths</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Site Max Width</label>
                    <input type="number" name="ecocommerce_pro_general_options[site_width]" value="<?php echo esc_attr($options['site_width'] ?? '1280'); ?>" min="960" max="1920" class="input-compact" /> px
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Content Width</label>
                    <input type="number" name="ecocommerce_pro_general_options[content_width]" value="<?php echo esc_attr($options['content_width'] ?? '800'); ?>" min="600" max="1200" class="input-compact" /> px
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Header Tab
 */
function ecocommerce_compact_header_tab($options) {
    $current_template = $options['template'] ?? 'default';
    ?>
    
    <!-- Header Template Selection -->
    <div class="template-selection-section">
        <h3 class="section-title-compact">
            <span class="section-icon">🎨</span>
            Choose Your Header Design
        </h3>
        <p class="section-description">Select from 8 professionally designed header templates</p>
        
        <?php ecocommerce_pro_header_template_selector($current_template); ?>
    </div>
    
    <!-- Header Customization Options -->
    <div class="accordion-section" style="margin-top: 30px;">
        <div class="accordion-header">
            <span><span class="accordion-emoji">⚙️</span> Header Settings</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_header_options[sticky]',
                        'checked' => !empty($options['sticky']),
                        'label' => 'Sticky Header',
                        'description' => 'Header stays fixed on scroll'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_header_options[show_search]',
                        'checked' => !empty($options['show_search']),
                        'label' => 'Search Bar',
                        'description' => 'Show search in header'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_header_options[show_cart]',
                        'checked' => !empty($options['show_cart']),
                        'label' => 'Cart Icon',
                        'description' => 'WooCommerce cart'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_header_options[show_social]',
                        'checked' => !empty($options['show_social']),
                        'label' => 'Social Icons',
                        'description' => 'Display social links'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_header_options[transparent_home]',
                        'checked' => !empty($options['transparent_home']),
                        'label' => 'Transparent on Home',
                        'description' => 'Overlay on homepage'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Header Height</label>
                    <input type="number" name="ecocommerce_pro_header_options[height]" value="<?php echo esc_attr($options['height'] ?? '80'); ?>" min="60" max="150" class="input-compact" /> px
                    <small>Height in pixels (60-150)</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Bar Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📢</span> Top Bar (Above Header)</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="field-compact">
                <?php ecocommerce_pro_toggle_switch(array(
                    'name' => 'ecocommerce_pro_header_options[topbar_enable]',
                    'checked' => !empty($options['topbar_enable']),
                    'label' => 'Enable Top Bar',
                    'description' => 'Show promotional bar above header'
                )); ?>
            </div>
            
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Top Bar Text</label>
                    <input type="text" name="ecocommerce_pro_header_options[topbar_text]" value="<?php echo esc_attr($options['topbar_text'] ?? ''); ?>" placeholder="Free shipping on orders over $50!" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Top Bar Background</label>
                    <input type="text" name="ecocommerce_pro_header_options[topbar_bg]" value="<?php echo esc_attr($options['topbar_bg'] ?? '#1f2937'); ?>" class="wp-color-picker-field" data-default-color="#1f2937" />
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📱</span> Mobile Menu</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <label class="field-label-compact">Mobile Breakpoint</label>
                    <input type="number" name="ecocommerce_pro_header_options[mobile_breakpoint]" value="<?php echo esc_attr($options['mobile_breakpoint'] ?? '768'); ?>" min="480" max="1024" class="input-compact" /> px
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Menu Icon Style</label>
                    <select name="ecocommerce_pro_header_options[menu_icon]" class="select-compact">
                        <option value="hamburger" <?php selected($options['menu_icon'] ?? 'hamburger', 'hamburger'); ?>>☰ Hamburger</option>
                        <option value="dots" <?php selected($options['menu_icon'] ?? 'hamburger', 'dots'); ?>>⋮ Dots</option>
                        <option value="menu-text" <?php selected($options['menu_icon'] ?? 'hamburger', 'menu-text'); ?>>📝 Text</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Menu Position</label>
                    <select name="ecocommerce_pro_header_options[mobile_menu_position]" class="select-compact">
                        <option value="left" <?php selected($options['mobile_menu_position'] ?? 'left', 'left'); ?>>← Left Slide</option>
                        <option value="right" <?php selected($options['mobile_menu_position'] ?? 'left', 'right'); ?>>Right Slide →</option>
                        <option value="fullscreen" <?php selected($options['mobile_menu_position'] ?? 'left', 'fullscreen'); ?>>⛶ Full Screen</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .section-title-compact {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-icon {
        font-size: 24px;
    }
    
    .section-description {
        margin: 0 0 24px 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    .template-selection-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 2px solid #e5e7eb;
    }
    </style>
    <?php
}

/**
 * Render Footer Tab
 */
function ecocommerce_compact_footer_tab($options) {
    ?>
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🦶</span> Footer Layout</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Footer Columns</label>
                    <select name="ecocommerce_pro_footer_options[columns]" class="select-compact">
                        <option value="1" <?php selected($options['columns'] ?? '4', '1'); ?>>1 Column</option>
                        <option value="2" <?php selected($options['columns'] ?? '4', '2'); ?>>2 Columns</option>
                        <option value="3" <?php selected($options['columns'] ?? '4', '3'); ?>>3 Columns</option>
                        <option value="4" <?php selected($options['columns'] ?? '4', '4'); ?>>4 Columns</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_footer_options[show_widgets]',
                        'checked' => !empty($options['show_widgets']),
                        'label' => 'Show Widgets',
                        'description' => 'Footer widget areas'
                    )); ?>
                </div>
            </div>
            
            <div class="field-compact">
                <label class="field-label-compact">Copyright Text</label>
                <input type="text" name="ecocommerce_pro_footer_options[copyright]" value="<?php echo esc_attr($options['copyright'] ?? ''); ?>" placeholder="© [year] [site_title]" class="input-compact" />
                <small>Use [year] and [site_title] as placeholders</small>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Homepage Tab
 */
function ecocommerce_compact_homepage_tab($options) {
    ?>
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎯</span> Hero Section</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="field-compact">
                <?php ecocommerce_pro_toggle_switch(array(
                    'name' => 'ecocommerce_pro_homepage_options[hero_enable]',
                    'checked' => !empty($options['hero_enable']),
                    'label' => 'Enable Hero Section',
                    'description' => 'Show hero banner'
                )); ?>
            </div>
            
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Hero Title</label>
                    <input type="text" name="ecocommerce_pro_homepage_options[hero_title]" value="<?php echo esc_attr($options['hero_title'] ?? 'Welcome to Our Store'); ?>" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Hero Button Text</label>
                    <input type="text" name="ecocommerce_pro_homepage_options[hero_button_text]" value="<?php echo esc_attr($options['hero_button_text'] ?? 'Shop Now'); ?>" class="input-compact" />
                </div>
            </div>
            
            <div class="field-compact">
                <label class="field-label-compact">Hero Subtitle</label>
                <textarea name="ecocommerce_pro_homepage_options[hero_subtitle]" rows="2" class="input-compact"><?php echo esc_textarea($options['hero_subtitle'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">⭐</span> Featured Products</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_homepage_options[featured_enable]',
                        'checked' => !empty($options['featured_enable']),
                        'label' => 'Show Featured',
                        'description' => 'Display section'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Section Title</label>
                    <input type="text" name="ecocommerce_pro_homepage_options[featured_title]" value="<?php echo esc_attr($options['featured_title'] ?? 'Featured Products'); ?>" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Products Count</label>
                    <input type="number" name="ecocommerce_pro_homepage_options[featured_count]" value="<?php echo esc_attr($options['featured_count'] ?? '8'); ?>" min="4" max="20" class="input-compact" />
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Shop Tab
 */
function ecocommerce_compact_shop_tab($options) {
    ?>
    <!-- Shop Layout Design -->
    <div class="accordion-section active">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📐</span> Shop Layout Design</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="field-compact full-width">
                <label class="field-label-compact">Container Width</label>
                <div class="layout-selector-grid">
                    <?php
                    $shop_layout = $options['shop_layout'] ?? 'boxed';
                    $layouts = array(
                        'boxed' => array('name' => 'Boxed', 'icon' => '📦', 'desc' => 'Contained width (1200px)'),
                        'full-width' => array('name' => 'Full Width', 'icon' => '↔️', 'desc' => 'Edge to edge'),
                        'wide' => array('name' => 'Wide', 'icon' => '📏', 'desc' => 'Wide container (1400px)')
                    );
                    
                    foreach ($layouts as $key => $layout) :
                    ?>
                        <label class="layout-option <?php echo $shop_layout === $key ? 'selected' : ''; ?>">
                            <input type="radio" name="ecocommerce_pro_general_options[shop_layout]" value="<?php echo esc_attr($key); ?>" <?php checked($shop_layout, $key); ?> />
                            <div class="layout-preview">
                                <span class="layout-icon"><?php echo $layout['icon']; ?></span>
                                <strong><?php echo $layout['name']; ?></strong>
                                <small><?php echo $layout['desc']; ?></small>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Container Max Width</label>
                    <input type="number" name="ecocommerce_pro_general_options[shop_container_width]" value="<?php echo esc_attr($options['shop_container_width'] ?? '1200'); ?>" min="960" max="1920" step="20" class="input-compact" />
                    <small class="field-description">In pixels (for boxed layout)</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Content Padding</label>
                    <input type="number" name="ecocommerce_pro_general_options[shop_content_padding]" value="<?php echo esc_attr($options['shop_content_padding'] ?? '40'); ?>" min="0" max="100" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Grid Spacing -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📏</span> Product Grid Spacing</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Grid Gap (Space Between Products)</label>
                    <input type="number" name="ecocommerce_pro_general_options[product_grid_gap]" value="<?php echo esc_attr($options['product_grid_gap'] ?? '24'); ?>" min="0" max="60" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Section Margin Top</label>
                    <input type="number" name="ecocommerce_pro_general_options[shop_section_margin_top]" value="<?php echo esc_attr($options['shop_section_margin_top'] ?? '40'); ?>" min="0" max="100" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Section Margin Bottom</label>
                    <input type="number" name="ecocommerce_pro_general_options[shop_section_margin_bottom]" value="<?php echo esc_attr($options['shop_section_margin_bottom'] ?? '40'); ?>" min="0" max="100" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Section Padding</label>
                    <input type="number" name="ecocommerce_pro_general_options[shop_section_padding]" value="<?php echo esc_attr($options['shop_section_padding'] ?? '60'); ?>" min="0" max="120" class="input-compact" />
                    <small class="field-description">In pixels (top & bottom)</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Card Design -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎴</span> Product Card Design</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Card Border Radius</label>
                    <input type="number" name="ecocommerce_pro_general_options[product_border_radius]" value="<?php echo esc_attr($options['product_border_radius'] ?? '12'); ?>" min="0" max="30" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Card Padding</label>
                    <input type="number" name="ecocommerce_pro_general_options[product_card_padding]" value="<?php echo esc_attr($options['product_card_padding'] ?? '16'); ?>" min="0" max="40" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Card Background</label>
                    <input type="text" name="ecocommerce_pro_general_options[product_card_bg]" value="<?php echo esc_attr($options['product_card_bg'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Card Border Color</label>
                    <input type="text" name="ecocommerce_pro_general_options[product_card_border]" value="<?php echo esc_attr($options['product_card_border'] ?? '#e5e7eb'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Card Shadow Intensity</label>
                    <select name="ecocommerce_pro_general_options[product_card_shadow]" class="select-compact">
                        <option value="none" <?php selected($options['product_card_shadow'] ?? 'medium', 'none'); ?>>None</option>
                        <option value="light" <?php selected($options['product_card_shadow'] ?? 'medium', 'light'); ?>>Light</option>
                        <option value="medium" <?php selected($options['product_card_shadow'] ?? 'medium', 'medium'); ?>>Medium</option>
                        <option value="heavy" <?php selected($options['product_card_shadow'] ?? 'medium', 'heavy'); ?>>Heavy</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[product_hover_effect]',
                        'checked' => !empty($options['product_hover_effect']),
                        'label' => 'Hover Lift Effect',
                        'description' => 'Elevate on hover'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🛍️</span> Shop Settings</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <label class="field-label-compact">Products Per Page</label>
                    <input type="number" name="ecocommerce_pro_general_options[products_per_page]" value="<?php echo esc_attr($options['products_per_page'] ?? '12'); ?>" min="4" max="50" class="input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Products Per Row</label>
                    <select name="ecocommerce_pro_general_options[products_per_row]" class="select-compact">
                        <option value="2" <?php selected($options['products_per_row'] ?? '4', '2'); ?>>2</option>
                        <option value="3" <?php selected($options['products_per_row'] ?? '4', '3'); ?>>3</option>
                        <option value="4" <?php selected($options['products_per_row'] ?? '4', '4'); ?>>4</option>
                        <option value="5" <?php selected($options['products_per_row'] ?? '4', '5'); ?>>5</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_ratings]',
                        'checked' => !empty($options['show_ratings']),
                        'label' => 'Show Ratings',
                        'description' => 'Star ratings'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar & Filters -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🔍</span> Sidebar & Filters</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="field-compact full-width">
                <label class="field-label-compact">Sidebar Position</label>
                <div class="layout-selector-grid">
                    <?php
                    $sidebar_position = $options['shop_sidebar_position'] ?? 'left';
                    $positions = array(
                        'left' => array('name' => 'Left Sidebar', 'icon' => '◀️'),
                        'right' => array('name' => 'Right Sidebar', 'icon' => '▶️'),
                        'none' => array('name' => 'No Sidebar', 'icon' => '📱')
                    );
                    
                    foreach ($positions as $key => $position) :
                    ?>
                        <label class="layout-option <?php echo $sidebar_position === $key ? 'selected' : ''; ?>">
                            <input type="radio" name="ecocommerce_pro_general_options[shop_sidebar_position]" value="<?php echo esc_attr($key); ?>" <?php checked($sidebar_position, $key); ?> />
                            <div class="layout-preview">
                                <span class="layout-icon"><?php echo $position['icon']; ?></span>
                                <strong><?php echo $position['name']; ?></strong>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Sidebar Width</label>
                    <input type="number" name="ecocommerce_pro_general_options[shop_sidebar_width]" value="<?php echo esc_attr($options['shop_sidebar_width'] ?? '25'); ?>" min="20" max="35" class="input-compact" />
                    <small class="field-description">Percentage (%)</small>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[sticky_sidebar]',
                        'checked' => !empty($options['sticky_sidebar']),
                        'label' => 'Sticky Sidebar',
                        'description' => 'Fixed on scroll'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎛️</span> Product Filters</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_category_filter]',
                        'checked' => !empty($options['show_category_filter']),
                        'label' => 'Category Filter',
                        'description' => 'Filter by category'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_price_filter]',
                        'checked' => !empty($options['show_price_filter']),
                        'label' => 'Price Filter',
                        'description' => 'Filter by price range'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_rating_filter]',
                        'checked' => !empty($options['show_rating_filter']),
                        'label' => 'Rating Filter',
                        'description' => 'Filter by stars'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_color_filter]',
                        'checked' => !empty($options['show_color_filter']),
                        'label' => 'Color Filter',
                        'description' => 'Filter by color'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_size_filter]',
                        'checked' => !empty($options['show_size_filter']),
                        'label' => 'Size Filter',
                        'description' => 'Filter by size'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_brand_filter]',
                        'checked' => !empty($options['show_brand_filter']),
                        'label' => 'Brand Filter',
                        'description' => 'Filter by brand'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sorting & View Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🔄</span> Sorting & View Options</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_sorting]',
                        'checked' => !empty($options['show_sorting']),
                        'label' => 'Product Sorting',
                        'description' => 'Sort dropdown'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_result_count]',
                        'checked' => !empty($options['show_result_count']),
                        'label' => 'Result Count',
                        'description' => 'Show product count'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_grid_list_toggle]',
                        'checked' => !empty($options['show_grid_list_toggle']),
                        'label' => 'Grid/List Toggle',
                        'description' => 'View switcher'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Default Sorting</label>
                    <select name="ecocommerce_pro_general_options[default_sorting]" class="select-compact">
                        <option value="menu_order" <?php selected($options['default_sorting'] ?? 'menu_order', 'menu_order'); ?>>Default</option>
                        <option value="popularity" <?php selected($options['default_sorting'] ?? 'menu_order', 'popularity'); ?>>Popularity</option>
                        <option value="rating" <?php selected($options['default_sorting'] ?? 'menu_order', 'rating'); ?>>Average Rating</option>
                        <option value="date" <?php selected($options['default_sorting'] ?? 'menu_order', 'date'); ?>>Latest</option>
                        <option value="price" <?php selected($options['default_sorting'] ?? 'menu_order', 'price'); ?>>Price: Low to High</option>
                        <option value="price-desc" <?php selected($options['default_sorting'] ?? 'menu_order', 'price-desc'); ?>>Price: High to Low</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Products Per Page Default</label>
                    <select name="ecocommerce_pro_general_options[products_per_page_options]" class="select-compact">
                        <option value="show_all" <?php selected($options['products_per_page_options'] ?? 'fixed', 'show_all'); ?>>Show All Options</option>
                        <option value="fixed" <?php selected($options['products_per_page_options'] ?? 'fixed', 'fixed'); ?>>Fixed Count Only</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[ajax_filtering]',
                        'checked' => !empty($options['ajax_filtering']),
                        'label' => 'AJAX Filtering',
                        'description' => 'No page reload'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Card Display -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🏷️</span> Product Card Display</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_sale_badge]',
                        'checked' => !empty($options['show_sale_badge']),
                        'label' => 'Sale Badge',
                        'description' => 'Show sale tags'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_new_badge]',
                        'checked' => !empty($options['show_new_badge']),
                        'label' => 'New Badge',
                        'description' => 'Show new products'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_stock_status]',
                        'checked' => !empty($options['show_stock_status']),
                        'label' => 'Stock Status',
                        'description' => 'In stock/Out of stock'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_quick_view]',
                        'checked' => !empty($options['show_quick_view']),
                        'label' => 'Quick View',
                        'description' => 'Quick view button'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_wishlist]',
                        'checked' => !empty($options['show_wishlist']),
                        'label' => 'Wishlist',
                        'description' => 'Wishlist icon'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_compare]',
                        'checked' => !empty($options['show_compare']),
                        'label' => 'Compare',
                        'description' => 'Compare products'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toolbar Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎚️</span> Shop Toolbar</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Toolbar Position</label>
                    <select name="ecocommerce_pro_general_options[toolbar_position]" class="select-compact">
                        <option value="top" <?php selected($options['toolbar_position'] ?? 'top', 'top'); ?>>Top Only</option>
                        <option value="bottom" <?php selected($options['toolbar_position'] ?? 'top', 'bottom'); ?>>Bottom Only</option>
                        <option value="both" <?php selected($options['toolbar_position'] ?? 'top', 'both'); ?>>Top & Bottom</option>
                        <option value="none" <?php selected($options['toolbar_position'] ?? 'top', 'none'); ?>>Hidden</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Toolbar Background</label>
                    <input type="text" name="ecocommerce_pro_general_options[toolbar_bg]" value="<?php echo esc_attr($options['toolbar_bg'] ?? '#f9fafb'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Toolbar Padding</label>
                    <input type="number" name="ecocommerce_pro_general_options[toolbar_padding]" value="<?php echo esc_attr($options['toolbar_padding'] ?? '20'); ?>" min="10" max="50" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Toolbar Border Radius</label>
                    <input type="number" name="ecocommerce_pro_general_options[toolbar_radius]" value="<?php echo esc_attr($options['toolbar_radius'] ?? '8'); ?>" min="0" max="20" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pagination Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📄</span> Pagination</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Pagination Style</label>
                    <select name="ecocommerce_pro_general_options[pagination_style]" class="select-compact">
                        <option value="numbers" <?php selected($options['pagination_style'] ?? 'numbers', 'numbers'); ?>>Page Numbers</option>
                        <option value="load_more" <?php selected($options['pagination_style'] ?? 'numbers', 'load_more'); ?>>Load More Button</option>
                        <option value="infinite" <?php selected($options['pagination_style'] ?? 'numbers', 'infinite'); ?>>Infinite Scroll</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Pagination Alignment</label>
                    <select name="ecocommerce_pro_general_options[pagination_align]" class="select-compact">
                        <option value="left" <?php selected($options['pagination_align'] ?? 'center', 'left'); ?>>Left</option>
                        <option value="center" <?php selected($options['pagination_align'] ?? 'center', 'center'); ?>>Center</option>
                        <option value="right" <?php selected($options['pagination_align'] ?? 'center', 'right'); ?>>Right</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Button Color</label>
                    <input type="text" name="ecocommerce_pro_general_options[pagination_color]" value="<?php echo esc_attr($options['pagination_color'] ?? '#667eea'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_page_count]',
                        'checked' => !empty($options['show_page_count']),
                        'label' => 'Show Page Count',
                        'description' => 'E.g. "Page 1 of 5"'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Breadcrumbs -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🗺️</span> Breadcrumbs</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_breadcrumbs]',
                        'checked' => !empty($options['show_breadcrumbs']),
                        'label' => 'Show Breadcrumbs',
                        'description' => 'Navigation path'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Breadcrumb Separator</label>
                    <select name="ecocommerce_pro_general_options[breadcrumb_separator]" class="select-compact">
                        <option value="/" <?php selected($options['breadcrumb_separator'] ?? '/', '/'); ?>>/</option>
                        <option value=">" <?php selected($options['breadcrumb_separator'] ?? '/', '>'); ?>>></option>
                        <option value="→" <?php selected($options['breadcrumb_separator'] ?? '/', '→'); ?>>→</option>
                        <option value="·" <?php selected($options['breadcrumb_separator'] ?? '/', '·'); ?>>·</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Background Color</label>
                    <input type="text" name="ecocommerce_pro_general_options[breadcrumb_bg]" value="<?php echo esc_attr($options['breadcrumb_bg'] ?? '#f9fafb'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Text Color</label>
                    <input type="text" name="ecocommerce_pro_general_options[breadcrumb_color]" value="<?php echo esc_attr($options['breadcrumb_color'] ?? '#6b7280'); ?>" class="color-picker-field input-compact" />
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Card Display Options -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎴</span> Product Information Display</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_product_category]',
                        'checked' => !empty($options['show_product_category']),
                        'label' => 'Product Category',
                        'description' => 'Show category label'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_product_excerpt]',
                        'checked' => !empty($options['show_product_excerpt']),
                        'label' => 'Product Excerpt',
                        'description' => 'Short description'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[show_add_to_cart]',
                        'checked' => !empty($options['show_add_to_cart']),
                        'label' => 'Add to Cart Button',
                        'description' => 'Show on grid'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Cart Page Tab - Comprehensive Cart Customization
 */
function ecocommerce_compact_cart_tab($options) {
    $cart_options = get_option('ecocommerce_pro_cart_options', ecocommerce_pro_get_default_cart_options());
    ?>
    
    <!-- Cart Header Styling -->
    <div class="accordion-section active">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🎨</span> Cart Table Header</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Header Background</label>
                    <input type="text" name="ecocommerce_pro_cart_options[header_bg]" value="<?php echo esc_attr($cart_options['header_bg'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'); ?>" class="color-picker-field input-compact" />
                    <small class="field-description">Can use gradient or solid color</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Header Text Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[header_text_color]" value="<?php echo esc_attr($cart_options['header_text_color'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Header Font Size</label>
                    <input type="number" name="ecocommerce_pro_cart_options[header_font_size]" value="<?php echo esc_attr($cart_options['header_font_size'] ?? '14'); ?>" min="10" max="24" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Header Padding</label>
                    <input type="number" name="ecocommerce_pro_cart_options[header_padding]" value="<?php echo esc_attr($cart_options['header_padding'] ?? '20'); ?>" min="10" max="50" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cart Table Body -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">📋</span> Cart Table Body</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Row Background</label>
                    <input type="text" name="ecocommerce_pro_cart_options[row_bg]" value="<?php echo esc_attr($cart_options['row_bg'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Row Hover Background</label>
                    <input type="text" name="ecocommerce_pro_cart_options[row_hover_bg]" value="<?php echo esc_attr($cart_options['row_hover_bg'] ?? '#f8f9fa'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[border_color]" value="<?php echo esc_attr($cart_options['border_color'] ?? '#f0f0f0'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Text Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[text_color]" value="<?php echo esc_attr($cart_options['text_color'] ?? '#1f2937'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Cell Padding</label>
                    <input type="number" name="ecocommerce_pro_cart_options[cell_padding]" value="<?php echo esc_attr($cart_options['cell_padding'] ?? '24'); ?>" min="10" max="50" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Radius</label>
                    <input type="number" name="ecocommerce_pro_cart_options[border_radius]" value="<?php echo esc_attr($cart_options['border_radius'] ?? '12'); ?>" min="0" max="30" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Thumbnail -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🖼️</span> Product Thumbnail</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Thumbnail Size</label>
                    <input type="number" name="ecocommerce_pro_cart_options[thumbnail_size]" value="<?php echo esc_attr($cart_options['thumbnail_size'] ?? '100'); ?>" min="50" max="200" class="input-compact" />
                    <small class="field-description">In pixels (square)</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Thumbnail Border Radius</label>
                    <input type="number" name="ecocommerce_pro_cart_options[thumbnail_radius]" value="<?php echo esc_attr($cart_options['thumbnail_radius'] ?? '10'); ?>" min="0" max="50" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_cart_options[thumbnail_shadow]',
                        'checked' => !empty($cart_options['thumbnail_shadow']),
                        'label' => 'Thumbnail Shadow',
                        'description' => 'Add shadow to images'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_cart_options[thumbnail_hover_effect]',
                        'checked' => !empty($cart_options['thumbnail_hover_effect']),
                        'label' => 'Hover Zoom Effect',
                        'description' => 'Scale on hover'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Remove Button -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🗑️</span> Remove Button</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Button Background</label>
                    <input type="text" name="ecocommerce_pro_cart_options[remove_bg]" value="<?php echo esc_attr($cart_options['remove_bg'] ?? '#fee'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Button Text Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[remove_color]" value="<?php echo esc_attr($cart_options['remove_color'] ?? '#e74c3c'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Button Hover Background</label>
                    <input type="text" name="ecocommerce_pro_cart_options[remove_hover_bg]" value="<?php echo esc_attr($cart_options['remove_hover_bg'] ?? '#e74c3c'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Button Hover Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[remove_hover_color]" value="<?php echo esc_attr($cart_options['remove_hover_color'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Button Size</label>
                    <input type="number" name="ecocommerce_pro_cart_options[remove_size]" value="<?php echo esc_attr($cart_options['remove_size'] ?? '36'); ?>" min="24" max="60" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Radius</label>
                    <input type="number" name="ecocommerce_pro_cart_options[remove_radius]" value="<?php echo esc_attr($cart_options['remove_radius'] ?? '8'); ?>" min="0" max="50" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quantity Input -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🔢</span> Quantity Selector</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Input Width</label>
                    <input type="number" name="ecocommerce_pro_cart_options[quantity_width]" value="<?php echo esc_attr($cart_options['quantity_width'] ?? '60'); ?>" min="40" max="100" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[quantity_border]" value="<?php echo esc_attr($cart_options['quantity_border'] ?? '#e0e0e0'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Width</label>
                    <input type="number" name="ecocommerce_pro_cart_options[quantity_border_width]" value="<?php echo esc_attr($cart_options['quantity_border_width'] ?? '2'); ?>" min="1" max="5" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Radius</label>
                    <input type="number" name="ecocommerce_pro_cart_options[quantity_radius]" value="<?php echo esc_attr($cart_options['quantity_radius'] ?? '8'); ?>" min="0" max="30" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cart Totals Sidebar -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">💰</span> Cart Totals Sidebar</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Background Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[totals_bg]" value="<?php echo esc_attr($cart_options['totals_bg'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[totals_border]" value="<?php echo esc_attr($cart_options['totals_border'] ?? '#f0f0f0'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Title Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[totals_title_color]" value="<?php echo esc_attr($cart_options['totals_title_color'] ?? '#1f2937'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Total Amount Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[totals_amount_color]" value="<?php echo esc_attr($cart_options['totals_amount_color'] ?? '#2563eb'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Padding</label>
                    <input type="number" name="ecocommerce_pro_cart_options[totals_padding]" value="<?php echo esc_attr($cart_options['totals_padding'] ?? '32'); ?>" min="16" max="60" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Radius</label>
                    <input type="number" name="ecocommerce_pro_cart_options[totals_radius]" value="<?php echo esc_attr($cart_options['totals_radius'] ?? '12'); ?>" min="0" max="30" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Checkout Button -->
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🚀</span> Checkout Button</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-2">
                <div class="field-compact">
                    <label class="field-label-compact">Button Background</label>
                    <input type="text" name="ecocommerce_pro_cart_options[checkout_bg]" value="<?php echo esc_attr($cart_options['checkout_bg'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'); ?>" class="color-picker-field input-compact" />
                    <small class="field-description">Can use gradient or solid color</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Button Text Color</label>
                    <input type="text" name="ecocommerce_pro_cart_options[checkout_color]" value="<?php echo esc_attr($cart_options['checkout_color'] ?? '#ffffff'); ?>" class="color-picker-field input-compact" />
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Font Size</label>
                    <input type="number" name="ecocommerce_pro_cart_options[checkout_font_size]" value="<?php echo esc_attr($cart_options['checkout_font_size'] ?? '18'); ?>" min="14" max="28" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Font Weight</label>
                    <select name="ecocommerce_pro_cart_options[checkout_font_weight]" class="select-compact">
                        <option value="400" <?php selected($cart_options['checkout_font_weight'] ?? '700', '400'); ?>>Normal</option>
                        <option value="500" <?php selected($cart_options['checkout_font_weight'] ?? '700', '500'); ?>>Medium</option>
                        <option value="600" <?php selected($cart_options['checkout_font_weight'] ?? '700', '600'); ?>>Semi Bold</option>
                        <option value="700" <?php selected($cart_options['checkout_font_weight'] ?? '700', '700'); ?>>Bold</option>
                    </select>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Padding (Top/Bottom)</label>
                    <input type="number" name="ecocommerce_pro_cart_options[checkout_padding]" value="<?php echo esc_attr($cart_options['checkout_padding'] ?? '18'); ?>" min="10" max="30" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <label class="field-label-compact">Border Radius</label>
                    <input type="number" name="ecocommerce_pro_cart_options[checkout_radius]" value="<?php echo esc_attr($cart_options['checkout_radius'] ?? '10'); ?>" min="0" max="30" class="input-compact" />
                    <small class="field-description">In pixels</small>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_cart_options[checkout_uppercase]',
                        'checked' => !empty($cart_options['checkout_uppercase']),
                        'label' => 'Uppercase Text',
                        'description' => 'Transform to uppercase'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_cart_options[checkout_shadow]',
                        'checked' => !empty($cart_options['checkout_shadow']),
                        'label' => 'Button Shadow',
                        'description' => 'Add shadow effect'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}

/**
 * Render Performance Tab
 */
function ecocommerce_compact_performance_tab($options) {
    ?>
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">⚡</span> Speed Optimization</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="compact-grid-3">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[minify_css]',
                        'checked' => !empty($options['minify_css']),
                        'label' => 'Minify CSS',
                        'description' => 'Compress CSS files'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[minify_js]',
                        'checked' => !empty($options['minify_js']),
                        'label' => 'Minify JS',
                        'description' => 'Compress JavaScript'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[defer_js]',
                        'checked' => !empty($options['defer_js']),
                        'label' => 'Defer JS',
                        'description' => 'Load JS async'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render SEO Tab
 */
function ecocommerce_compact_seo_tab($options) {
    ?>
    <div class="accordion-section">
        <div class="accordion-header">
            <span><span class="accordion-emoji">🔍</span> SEO Settings</span>
            <span class="accordion-toggle">▼</span>
        </div>
        <div class="accordion-content">
            <div class="field-compact">
                <label class="field-label-compact">Meta Description</label>
                <textarea name="ecocommerce_pro_general_options[meta_description]" rows="3" class="input-compact" placeholder="Default site meta description"><?php echo esc_textarea($options['meta_description'] ?? ''); ?></textarea>
                <small>Used when page has no specific meta description</small>
            </div>
            
            <div class="field-compact">
                <label class="field-label-compact">Meta Keywords</label>
                <input type="text" name="ecocommerce_pro_general_options[meta_keywords]" value="<?php echo esc_attr($options['meta_keywords'] ?? ''); ?>" class="input-compact" placeholder="keyword1, keyword2, keyword3" />
                <small>Comma-separated keywords</small>
            </div>
            
            <div class="compact-grid-2">
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[og_tags]',
                        'checked' => !empty($options['og_tags']),
                        'label' => 'Open Graph Tags',
                        'description' => 'Social sharing'
                    )); ?>
                </div>
                
                <div class="field-compact">
                    <?php ecocommerce_pro_toggle_switch(array(
                        'name' => 'ecocommerce_pro_general_options[twitter_cards]',
                        'checked' => !empty($options['twitter_cards']),
                        'label' => 'Twitter Cards',
                        'description' => 'Twitter previews'
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

