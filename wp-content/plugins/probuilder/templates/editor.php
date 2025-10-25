<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo get_the_title() . ' - ProBuilder Editor'; ?></title>
    <?php 
    // Remove actions that might interfere
    remove_all_actions('wp_head');
    remove_all_actions('wp_print_styles');
    remove_all_actions('wp_print_scripts');
    
    // Add back only what we need
    add_action('wp_head', 'wp_enqueue_scripts', 1);
    add_action('wp_head', 'wp_print_styles', 8);
    add_action('wp_head', 'wp_print_head_scripts', 9);
    
    wp_head(); 
    ?>
    <style>
        /* CRITICAL: Force positioning with inline styles */
        body.admin-bar #wpadminbar {
            z-index: 99999 !important;
            position: fixed !important;
            top: 0 !important;
        }
        
        body.admin-bar .probuilder-editor-header {
            position: fixed !important;
            top: 60px !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 99998 !important;
            height: 45px !important;
            background: #ffffff !important;
        }
        
        body.admin-bar .probuilder-editor-main {
            margin-top: 105px !important;
            height: calc(100vh - 105px) !important;
        }
        
        /* Fallback styles if main CSS fails */
        body.probuilder-editor-body {
            margin: 0;
            padding: 0 !important;
            font-family: -apple-system, sans-serif;
        }
        .probuilder-editor-header {
            background: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }
        .probuilder-sidebar {
            width: 320px;
            background: #fff;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }
        .probuilder-canvas {
            flex: 1;
            padding: 30px;
            background: #f0f0f0;
            overflow-y: auto;
        }
    </style>
</head>
<body class="probuilder-editor-body">
    
    <!-- Debug Info -->
    <script>
        console.log('=== ProBuilder Editor Template Loaded ===');
        console.log('Post ID: <?php echo get_the_ID(); ?>');
        console.log('Template file: editor.php');
        console.log('Cache buster: <?php echo time(); ?>');
        
        // Check positioning after DOM loads
        jQuery(document).ready(function($) {
            setTimeout(function() {
                const $header = $('.probuilder-editor-header');
                const $main = $('.probuilder-editor-main');
                const $adminBar = $('#wpadminbar');
                
                console.log('=== POSITIONING CHECK ===');
                console.log('Admin bar top:', $adminBar.css('top'), 'height:', $adminBar.height());
                console.log('ProBuilder header top:', $header.css('top'), 'height:', $header.height());
                console.log('ProBuilder main margin-top:', $main.css('margin-top'));
                console.log('Body has admin-bar class:', $('body').hasClass('admin-bar'));
            }, 500);
        });
    </script>
    
    <!-- Editor Header -->
    <div class="probuilder-editor-header">
        <div class="probuilder-header-left">
            <div class="probuilder-logo">
                <i class="dashicons dashicons-layout"></i>
                <span>ProBuilder</span>
            </div>
            <div class="probuilder-page-title">
                <?php echo esc_html(get_the_title()); ?>
            </div>
            <div class="probuilder-responsive-controls">
                <button class="probuilder-device-btn active" data-device="desktop" title="Desktop View">
                    <i class="dashicons dashicons-desktop"></i>
                </button>
                <button class="probuilder-device-btn" data-device="tablet" title="Tablet View">
                    <i class="dashicons dashicons-tablet"></i>
                </button>
                <button class="probuilder-device-btn" data-device="mobile" title="Mobile View">
                    <i class="dashicons dashicons-smartphone"></i>
                </button>
            </div>
            
            <div class="probuilder-history-controls">
                <button id="probuilder-undo" class="probuilder-btn probuilder-btn-icon disabled" title="Undo (Ctrl+Z)">
                    <i class="dashicons dashicons-undo"></i>
                </button>
                <button id="probuilder-redo" class="probuilder-btn probuilder-btn-icon disabled" title="Redo (Ctrl+Shift+Z)">
                    <i class="dashicons dashicons-redo"></i>
                </button>
            </div>
        </div>
        
        <div class="probuilder-header-right">
            <button id="probuilder-preview" class="probuilder-btn probuilder-btn-secondary">
                <i class="dashicons dashicons-visibility"></i>
                <?php esc_html_e('Preview', 'probuilder'); ?>
            </button>
            <button id="probuilder-save" class="probuilder-btn probuilder-btn-primary">
                <i class="dashicons dashicons-saved"></i>
                <?php esc_html_e('Save', 'probuilder'); ?>
            </button>
            <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="probuilder-btn probuilder-btn-link">
                <i class="dashicons dashicons-no-alt"></i>
                <?php esc_html_e('Exit', 'probuilder'); ?>
            </a>
        </div>
    </div>
    
    <!-- Editor Main -->
    <div class="probuilder-editor-main">
        
        <!-- Sidebar - Widgets Panel -->
        <div class="probuilder-sidebar">
            <div class="probuilder-sidebar-header">
                <input type="text" id="probuilder-widget-search" class="probuilder-search-input" placeholder="<?php esc_attr_e('Search widgets...', 'probuilder'); ?>">
                <button class="probuilder-sidebar-toggle" id="probuilder-left-sidebar-toggle" title="Collapse Sidebar">
                    <i class="dashicons dashicons-arrow-left-alt2"></i>
                </button>
            </div>
            
            <div class="probuilder-sidebar-tabs">
                <button class="probuilder-tab-btn active" data-tab="widgets">
                    <i class="dashicons dashicons-screenoptions"></i>
                    <?php esc_html_e('Widgets', 'probuilder'); ?>
                </button>
                <button class="probuilder-tab-btn" data-tab="templates">
                    <i class="dashicons dashicons-layout"></i>
                    <?php esc_html_e('Templates', 'probuilder'); ?>
                </button>
                <button class="probuilder-tab-btn" data-tab="globalstyles">
                    <i class="dashicons dashicons-admin-appearance"></i>
                    <?php esc_html_e('Global Styles', 'probuilder'); ?>
                </button>
            </div>
            
            <div class="probuilder-sidebar-content">
                
                <!-- Widgets Tab -->
                <div class="probuilder-tab-content active" data-tab="widgets">
                    
                    <!-- Layout Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-editor-table"></i>
                            <?php esc_html_e('Layout', 'probuilder'); ?>
                        </div>
                        <div class="probuilder-widgets-grid" data-category="layout"></div>
                    </div>
                    
                    <!-- Basic Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-editor-paragraph"></i>
                            <?php esc_html_e('Basic', 'probuilder'); ?>
                        </div>
                        <div class="probuilder-widgets-grid" data-category="basic"></div>
                    </div>
                    
                    <!-- Advanced Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-admin-settings"></i>
                            <?php esc_html_e('Advanced', 'probuilder'); ?>
                        </div>
                        <div class="probuilder-widgets-grid" data-category="advanced"></div>
                    </div>
                    
                    <!-- Content Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-admin-page"></i>
                            <?php esc_html_e('Content', 'probuilder'); ?>
                        </div>
                        <div class="probuilder-widgets-grid" data-category="content"></div>
                    </div>
                    
                </div>
                
                <!-- Templates Tab -->
                <div class="probuilder-tab-content" data-tab="templates">
                    <div class="probuilder-templates-loading" style="text-align: center; padding: 60px 20px; color: #a1a1aa;">
                        <i class="dashicons dashicons-admin-page" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                        <h3 style="font-size: 16px; color: #71717a; margin: 0 0 10px 0;">Loading Templates...</h3>
                        <p style="font-size: 13px; margin: 0;">Please wait while we load the template library</p>
                    </div>
                </div>
                
                <!-- Global Styles Tab -->
                <div class="probuilder-tab-content" data-tab="globalstyles">
                    <div class="probuilder-global-styles-container">
                        
                        <!-- Color Palette Section -->
                        <div class="probuilder-global-section">
                            <div class="probuilder-global-section-header">
                                <i class="dashicons dashicons-art"></i>
                                <h3><?php esc_html_e('Color Palette', 'probuilder'); ?></h3>
                            </div>
                            <div class="probuilder-global-section-content">
                                <div class="probuilder-color-palette" id="probuilder-color-palette">
                                    <!-- Colors will be added by JavaScript -->
                                </div>
                                <button class="probuilder-btn probuilder-btn-secondary probuilder-btn-sm" id="add-global-color">
                                    <i class="dashicons dashicons-plus-alt2"></i>
                                    <?php esc_html_e('Add Color', 'probuilder'); ?>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Typography Section -->
                        <div class="probuilder-global-section">
                            <div class="probuilder-global-section-header">
                                <i class="dashicons dashicons-editor-textcolor"></i>
                                <h3><?php esc_html_e('Typography', 'probuilder'); ?></h3>
                            </div>
                            <div class="probuilder-global-section-content">
                                <div class="probuilder-typography-presets" id="probuilder-typography-presets">
                                    <!-- Typography presets will be added by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Button Styles Section -->
                        <div class="probuilder-global-section">
                            <div class="probuilder-global-section-header">
                                <i class="dashicons dashicons-button"></i>
                                <h3><?php esc_html_e('Button Styles', 'probuilder'); ?></h3>
                            </div>
                            <div class="probuilder-global-section-content">
                                <div class="probuilder-button-presets" id="probuilder-button-presets">
                                    <!-- Button presets will be added by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Canvas - Preview Area -->
        <div class="probuilder-canvas" data-device="desktop">
            <div class="probuilder-canvas-inner">
                <div id="probuilder-preview-area" class="probuilder-preview-area">
                    <!-- Elements will be added here -->
                </div>
                
                <!-- Add Element Button - Always visible between elements -->
                <div class="probuilder-add-element-section" id="probuilder-add-bottom">
                    <button class="probuilder-add-element-btn" title="Add New Element">
                        <i class="dashicons dashicons-plus-alt2"></i>
                        <span>Add Element</span>
                    </button>
                </div>
            </div>
            
            <!-- Empty State (shown when no elements) -->
            <div id="probuilder-empty-state" class="probuilder-empty-state">
                <i class="dashicons dashicons-welcome-add-page"></i>
                <h3><?php esc_html_e('Start Building Your Page', 'probuilder'); ?></h3>
                <p><?php esc_html_e('Drag widgets from the left panel or click below to add your first element', 'probuilder'); ?></p>
                <button class="probuilder-btn probuilder-btn-primary" id="probuilder-add-first-element">
                    <i class="dashicons dashicons-plus-alt2"></i>
                    <?php esc_html_e('Add Element', 'probuilder'); ?>
                </button>
            </div>
        </div>
        
        <!-- Settings Panel - Always Visible -->
        <div class="probuilder-settings-panel" id="probuilder-settings-panel">
            <div class="probuilder-settings-header">
                <button class="probuilder-sidebar-toggle" id="probuilder-right-sidebar-toggle" title="Collapse Settings">
                    <i class="dashicons dashicons-arrow-right-alt2"></i>
                </button>
                <h3 id="probuilder-settings-title"><?php esc_html_e('Settings', 'probuilder'); ?></h3>
            </div>
            
            <div class="probuilder-settings-tabs">
                <button class="probuilder-settings-tab active" data-tab="content">
                    <i class="dashicons dashicons-edit"></i>
                    <?php esc_html_e('Content', 'probuilder'); ?>
                </button>
                <button class="probuilder-settings-tab" data-tab="style">
                    <i class="dashicons dashicons-admin-appearance"></i>
                    <?php esc_html_e('Style', 'probuilder'); ?>
                </button>
                <button class="probuilder-settings-tab" data-tab="advanced">
                    <i class="dashicons dashicons-admin-settings"></i>
                    <?php esc_html_e('Advanced', 'probuilder'); ?>
                </button>
                <button class="probuilder-settings-tab" data-tab="motion">
                    <i class="dashicons dashicons-controls-play"></i>
                    <?php esc_html_e('Motion', 'probuilder'); ?>
                </button>
            </div>
            
            <div class="probuilder-settings-content" id="probuilder-settings-content">
                <div class="probuilder-settings-placeholder">
                    <i class="dashicons dashicons-admin-settings"></i>
                    <h4><?php esc_html_e('Element Settings', 'probuilder'); ?></h4>
                    <p><?php esc_html_e('Click on any element in the canvas to edit its settings', 'probuilder'); ?></p>
                    <div style="margin-top: 30px; padding: 20px; background: #f4f4f5; border-radius: 4px; text-align: left;">
                        <p style="font-size: 12px; color: #71717a; margin: 0 0 10px 0;"><strong><?php esc_html_e('Quick Tips:', 'probuilder'); ?></strong></p>
                        <ul style="font-size: 11px; color: #71717a; margin: 0; padding-left: 20px; line-height: 1.8;">
                            <li><?php esc_html_e('Click "Edit" button on elements', 'probuilder'); ?></li>
                            <li><?php esc_html_e('Use Content/Style/Advanced tabs', 'probuilder'); ?></li>
                            <li><?php esc_html_e('Changes update instantly', 'probuilder'); ?></li>
                            <li><?php esc_html_e('Resize this panel by dragging left edge', 'probuilder'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Loading Overlay -->
    <div id="probuilder-loading" class="probuilder-loading" style="display: none;">
        <div class="probuilder-spinner"></div>
        <p><?php esc_html_e('Saving...', 'probuilder'); ?></p>
    </div>
    
    <!-- Hidden data -->
    <?php 
    $current_post_id = get_the_ID();
    if (!$current_post_id && isset($_GET['post'])) {
        $current_post_id = intval($_GET['post']);
    }
    ?>
    <input type="hidden" id="probuilder-post-id" value="<?php echo esc_attr($current_post_id); ?>">
    <input type="hidden" id="probuilder-data" value='<?php echo esc_attr(get_post_meta($current_post_id, '_probuilder_data', true) ? json_encode(get_post_meta($current_post_id, '_probuilder_data', true)) : '[]'); ?>'>
    
    <!-- Ensure jQuery UI is loaded -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <?php wp_footer(); ?>
    
    <script>
        // Final check
        jQuery(document).ready(function($) {
            console.log('=== Final Template Check ===');
            console.log('Sidebar found:', $('.probuilder-sidebar').length);
            console.log('Widget grids found:', $('.probuilder-widgets-grid').length);
            console.log('ProBuilderEditor defined:', typeof ProBuilderEditor !== 'undefined');
            
            if (typeof ProBuilderEditor !== 'undefined') {
                console.log('Widgets in ProBuilderEditor:', ProBuilderEditor.widgets ? ProBuilderEditor.widgets.length : 'undefined');
            }
        });
    </script>
</body>
</html>


