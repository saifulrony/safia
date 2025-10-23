<?php
/**
 * ProBuilder Standalone Editor - GUARANTEED TO WORK
 * Access: http://localhost:7000/wp-content/plugins/probuilder/editor-standalone.php?post_id=489
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is logged in
if (!is_user_logged_in()) {
    wp_die('Please <a href="' . wp_login_url() . '">log in</a> to WordPress first.');
}

// Get post ID
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if (!$post_id) {
    wp_die('Post ID required. Add ?post_id=YOUR_PAGE_ID to the URL');
}

$post = get_post($post_id);
if (!$post) {
    wp_die('Post not found. Post ID: ' . $post_id);
}

// Get widgets data
$widgets_data = [];
if (class_exists('ProBuilder_Widgets_Manager')) {
    $widgets_data = ProBuilder_Widgets_Manager::instance()->get_widgets_config();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($post->post_title); ?> - ProBuilder Editor</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- ProBuilder CSS -->
    <link rel="stylesheet" href="assets/css/editor.css?v=<?php echo time(); ?>">
    
    <!-- WordPress Dashicons -->
    <link rel="stylesheet" href="<?php echo includes_url('css/dashicons.min.css'); ?>">
    
    <style>
        /* Ensure base layout works */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: #e6e9ec;
        }
        .probuilder-editor-main {
            display: flex !important;
            height: calc(100vh - 60px) !important;
        }
    </style>
</head>
<body class="probuilder-editor-body">
    
    <!-- Editor Header -->
    <div class="probuilder-editor-header">
        <div class="probuilder-header-left">
            <div class="probuilder-logo">
                <i class="dashicons dashicons-layout"></i>
                <span>ProBuilder</span>
            </div>
            <div class="probuilder-page-title">
                <?php echo esc_html($post->post_title); ?>
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
        </div>
        
        <div class="probuilder-header-right">
            <button id="probuilder-preview" class="probuilder-btn probuilder-btn-secondary">
                <i class="dashicons dashicons-visibility"></i>
                Preview
            </button>
            <button id="probuilder-save" class="probuilder-btn probuilder-btn-primary">
                <i class="dashicons dashicons-saved"></i>
                Save
            </button>
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="probuilder-btn probuilder-btn-link">
                <i class="dashicons dashicons-no-alt"></i>
                Exit
            </a>
        </div>
    </div>
    
    <!-- Editor Main -->
    <div class="probuilder-editor-main">
        
        <!-- Sidebar - Widgets Panel -->
        <div class="probuilder-sidebar">
            <div class="probuilder-sidebar-header">
                <input type="text" id="probuilder-widget-search" class="probuilder-search-input" placeholder="Search widgets...">
            </div>
            
            <div class="probuilder-sidebar-tabs">
                <button class="probuilder-tab-btn active" data-tab="widgets">
                    <i class="dashicons dashicons-screenoptions"></i>
                    Widgets
                </button>
                <button class="probuilder-tab-btn" data-tab="templates">
                    <i class="dashicons dashicons-layout"></i>
                    Templates
                </button>
            </div>
            
            <div class="probuilder-sidebar-content">
                
                <!-- Widgets Tab -->
                <div class="probuilder-tab-content active" data-tab="widgets">
                    
                    <!-- Layout Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-editor-table"></i>
                            LAYOUT
                        </div>
                        <div class="probuilder-widgets-grid" data-category="layout">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Basic Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-editor-paragraph"></i>
                            BASIC
                        </div>
                        <div class="probuilder-widgets-grid" data-category="basic">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Advanced Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-admin-settings"></i>
                            ADVANCED
                        </div>
                        <div class="probuilder-widgets-grid" data-category="advanced">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Content Widgets -->
                    <div class="probuilder-widget-category">
                        <div class="probuilder-category-title">
                            <i class="dashicons dashicons-admin-page"></i>
                            CONTENT
                        </div>
                        <div class="probuilder-widgets-grid" data-category="content">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    
                </div>
                
                <!-- Templates Tab -->
                <div class="probuilder-tab-content" data-tab="templates">
                    <div class="probuilder-templates-list" id="probuilder-templates-list">
                        <p style="padding: 20px; text-align: center; color: #999;">No templates yet</p>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Canvas - Preview Area -->
        <div class="probuilder-canvas">
            <div class="probuilder-canvas-inner">
                <div id="probuilder-preview-area" class="probuilder-preview-area">
                    <!-- Elements will be added here -->
                </div>
            </div>
            
            <!-- Add Element Button (when empty) -->
            <div id="probuilder-empty-state" class="probuilder-empty-state" style="display: block;">
                <i class="dashicons dashicons-plus-alt"></i>
                <h3>Start Building Your Page</h3>
                <p>Drag widgets from the left panel or click below to add your first element</p>
                <button class="probuilder-btn probuilder-btn-primary" id="probuilder-add-first-element">
                    <i class="dashicons dashicons-plus"></i>
                    Add Element
                </button>
            </div>
        </div>
        
        <!-- Settings Panel -->
        <div class="probuilder-settings-panel" id="probuilder-settings-panel">
            <div class="probuilder-settings-header">
                <h3 id="probuilder-settings-title">Element Settings</h3>
                <button class="probuilder-close-settings">
                    <i class="dashicons dashicons-no-alt"></i>
                </button>
            </div>
            
            <div class="probuilder-settings-tabs">
                <button class="probuilder-settings-tab active" data-tab="content">
                    <i class="dashicons dashicons-edit"></i>
                    Content
                </button>
                <button class="probuilder-settings-tab" data-tab="style">
                    <i class="dashicons dashicons-admin-appearance"></i>
                    Style
                </button>
                <button class="probuilder-settings-tab" data-tab="advanced">
                    <i class="dashicons dashicons-admin-settings"></i>
                    Advanced
                </button>
            </div>
            
            <div class="probuilder-settings-content" id="probuilder-settings-content">
                <p style="padding: 20px; text-align: center; color: #999;">Select an element to edit its settings</p>
            </div>
        </div>
        
    </div>
    
    <!-- Loading Overlay -->
    <div id="probuilder-loading" class="probuilder-loading" style="display: none;">
        <div class="probuilder-spinner"></div>
        <p>Saving...</p>
    </div>
    
    <!-- Hidden data -->
    <input type="hidden" id="probuilder-post-id" value="<?php echo esc_attr($post_id); ?>">
    <input type="hidden" id="probuilder-data" value='<?php echo esc_attr(get_post_meta($post_id, '_probuilder_data', true) ? json_encode(get_post_meta($post_id, '_probuilder_data', true)) : '[]'); ?>'>
    
    <!-- jQuery & jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- ProBuilder Editor Data -->
    <script>
        var ProBuilderEditor = {
            ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('probuilder_editor'); ?>',
            post_id: <?php echo $post_id; ?>,
            widgets: <?php echo json_encode($widgets_data); ?>,
            templates: [],
            i18n: {
                save: 'Save',
                preview: 'Preview',
                exit: 'Exit',
                add_element: 'Add Element',
                settings: 'Settings',
                style: 'Style',
                advanced: 'Advanced'
            },
            debug: true
        };
        
        console.log('=== ProBuilder Standalone Editor ===');
        console.log('Post ID:', ProBuilderEditor.post_id);
        console.log('Widgets loaded:', ProBuilderEditor.widgets.length);
        console.log('AJAX URL:', ProBuilderEditor.ajaxurl);
    </script>
    
    <!-- ProBuilder Editor JavaScript -->
    <script src="assets/js/editor.js?v=<?php echo time(); ?>"></script>
    
    <script>
        // Additional initialization
        jQuery(document).ready(function($) {
            console.log('Document ready!');
            console.log('Sidebar exists:', $('.probuilder-sidebar').length);
            console.log('Canvas exists:', $('.probuilder-canvas').length);
            console.log('Widget containers:', $('.probuilder-widgets-grid').length);
        });
    </script>
    
</body>
</html>

