<?php
/**
 * Elementor Auto-Installer
 * Visit this file to automatically install and activate Elementor
 * URL: http://localhost:7000/install-elementor.php
 */

require_once('wp-load.php');

// Security check
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(home_url('/install-elementor.php')));
    exit;
}

if (!current_user_can('install_plugins')) {
    wp_die('You do not have permission to install plugins.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Install Elementor</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px; 
            margin: 50px auto; 
            padding: 20px;
            background: #f0f0f1;
        }
        .card { 
            background: #fff;
            padding: 30px; 
            border-radius: 8px; 
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .btn { 
            background: #2563eb;
            color: white; 
            padding: 15px 30px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: 600;
            text-decoration: none; 
            display: inline-block;
            transition: all 0.3s;
        }
        .btn:hover { 
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        h1 { color: #1f2937; margin-top: 0; }
        h2 { color: #374151; font-size: 20px; }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .progress {
            background: #f3f4f6;
            border-radius: 8px;
            height: 30px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar {
            background: linear-gradient(90deg, #2563eb, #10b981);
            height: 100%;
            transition: width 0.5s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <h1>🎨 Elementor Installation</h1>
    
    <?php
    if (isset($_GET['install'])) {
        echo '<div class="card">';
        echo '<h2>Installing Elementor...</h2>';
        echo '<div class="spinner"></div>';
        echo '<p id="status" style="text-align: center; color: #6b7280;">Please wait...</p>';
        
        // Flush output
        if (ob_get_level() > 0) {
            ob_flush();
            flush();
        }
        
        // Include required WordPress files
        if (!function_exists('plugins_api')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
        }
        if (!class_exists('Plugin_Upgrader')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
        }
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/misc.php');
        
        echo '<script>document.getElementById("status").textContent = "Downloading Elementor...";</script>';
        if (ob_get_level() > 0) { ob_flush(); flush(); }
        
        // Get plugin information
        $api = plugins_api('plugin_information', array(
            'slug' => 'elementor',
            'fields' => array(
                'short_description' => false,
                'sections' => false,
                'requires' => false,
                'rating' => false,
                'ratings' => false,
                'downloaded' => false,
                'last_updated' => false,
                'added' => false,
                'tags' => false,
                'compatibility' => false,
                'homepage' => false,
                'donate_link' => false,
            ),
        ));
        
        if (is_wp_error($api)) {
            echo '<div class="error"><strong>Error:</strong> Could not fetch Elementor information. ' . $api->get_error_message() . '</div>';
            echo '<p><a href="' . admin_url('plugin-install.php?s=elementor&tab=search') . '" class="btn">Try Manual Installation</a></p>';
        } else {
            echo '<script>document.getElementById("status").textContent = "Installing Elementor...";</script>';
            if (ob_get_level() > 0) { ob_flush(); flush(); }
            
            // Install plugin
            $skin = new WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader($skin);
            $result = $upgrader->install($api->download_link);
            
            if (is_wp_error($result)) {
                echo '<div class="error"><strong>Installation Failed:</strong> ' . $result->get_error_message() . '</div>';
                echo '<p><a href="' . admin_url('plugin-install.php?s=elementor&tab=search') . '" class="btn">Try Manual Installation</a></p>';
            } elseif (is_wp_error($skin->result)) {
                echo '<div class="error"><strong>Installation Failed:</strong> ' . $skin->result->get_error_message() . '</div>';
            } elseif ($skin->get_errors()->has_errors()) {
                echo '<div class="error"><strong>Installation Failed:</strong> ' . $skin->get_error_messages() . '</div>';
            } else {
                echo '<script>document.getElementById("status").textContent = "Activating Elementor...";</script>';
                if (ob_get_level() > 0) { ob_flush(); flush(); }
                
                // Activate plugin
                $activate_result = activate_plugin('elementor/elementor.php');
                
                if (is_wp_error($activate_result)) {
                    echo '<div class="error"><strong>Activation Failed:</strong> ' . $activate_result->get_error_message() . '</div>';
                } else {
                    echo '<script>document.querySelector(".spinner").style.display = "none";</script>';
                    echo '<div class="success">';
                    echo '<h2 style="margin-top: 0;">✅ Elementor Installed Successfully!</h2>';
                    echo '<p><strong>Elementor is now active and ready to use!</strong></p>';
                    echo '</div>';
                    
                    echo '<div class="info">';
                    echo '<h3>🚀 Next Steps:</h3>';
                    echo '<ol style="line-height: 2;">';
                    echo '<li>Create or edit a page</li>';
                    echo '<li>Click the <strong>"Edit with Elementor"</strong> button</li>';
                    echo '<li>Start building with drag & drop!</li>';
                    echo '</ol>';
                    echo '</div>';
                    
                    echo '<p style="margin-top: 30px;">';
                    echo '<a href="' . admin_url('post-new.php?post_type=page') . '" class="btn">Create New Page →</a> ';
                    echo '<a href="' . admin_url('edit.php?post_type=page') . '" class="btn">Edit Existing Page →</a>';
                    echo '</p>';
                }
            }
        }
        
        echo '</div>';
    } else {
        // Check if Elementor is already installed
        if (defined('ELEMENTOR_VERSION')) {
            ?>
            <div class="card">
                <div class="success">
                    <h2 style="margin-top: 0;">✅ Elementor is Already Installed!</h2>
                    <p><strong>Version:</strong> <?php echo ELEMENTOR_VERSION; ?></p>
                    <p>You can start using Elementor to build pages right away!</p>
                </div>
                
                <h3>🎯 How to Use Elementor:</h3>
                <ol style="line-height: 2; font-size: 15px;">
                    <li>Go to <strong>Pages → Add New</strong> or edit an existing page</li>
                    <li>Look for the <strong>"Edit with Elementor"</strong> button (blue button at the top)</li>
                    <li>Click it to open the Elementor editor</li>
                    <li>Drag widgets from the left panel and drop them on the page</li>
                    <li>Customize everything visually</li>
                    <li>Click <strong>"Publish"</strong> or <strong>"Update"</strong> when done</li>
                </ol>
                
                <p style="margin-top: 30px;">
                    <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="btn">Create New Page with Elementor →</a>
                </p>
            </div>
            <?php
        } else {
            ?>
            <div class="card">
                <h2>🎨 Install Elementor Page Builder</h2>
                
                <div class="info">
                    <strong>About Elementor:</strong>
                    <ul style="margin: 10px 0 0 20px; line-height: 1.8;">
                        <li>⭐ <strong>5+ million</strong> active installations</li>
                        <li>🎨 <strong>Drag & drop</strong> visual page builder</li>
                        <li>🆓 <strong>Free version</strong> is very powerful</li>
                        <li>📱 <strong>Responsive</strong> editing for mobile/tablet</li>
                        <li>⚡ <strong>Live preview</strong> as you build</li>
                        <li>🎯 <strong>100+ widgets</strong> included for free</li>
                    </ul>
                </div>
                
                <h3>What This Will Install:</h3>
                <ul style="line-height: 1.8;">
                    <li>✅ Elementor Page Builder (Free Version)</li>
                    <li>✅ All free widgets and features</li>
                    <li>✅ Template library access</li>
                    <li>✅ Visual drag-and-drop editor</li>
                    <li>✅ Mobile responsive tools</li>
                </ul>
                
                <p style="margin-top: 20px;">
                    <a href="?install=true" class="btn" onclick="this.innerHTML='<span style=\'display:inline-block;animation:spin 1s linear infinite\'>⚙️</span> Installing...'; this.style.pointerEvents='none';">
                        Install Elementor Now →
                    </a>
                </p>
                
                <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
                    <strong>Note:</strong> This will download and install Elementor from WordPress.org. 
                    Installation takes about 10-15 seconds.
                </p>
            </div>
            
            <div class="card">
                <h3>📋 Alternative: Manual Installation</h3>
                <p>If automatic installation doesn't work, you can install manually:</p>
                <ol style="line-height: 2;">
                    <li>Go to <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>"><strong>Plugins → Add New</strong></a></li>
                    <li>Search for <strong>"Elementor"</strong></li>
                    <li>Click <strong>"Install Now"</strong> on "Elementor Website Builder"</li>
                    <li>Click <strong>"Activate"</strong> after installation</li>
                    <li>Done! Start creating pages</li>
                </ol>
            </div>
            <?php
        }
    }
    ?>
    
    <p style="text-align: center; margin-top: 40px; color: #6b7280;">
        <a href="<?php echo admin_url(); ?>">← Back to Dashboard</a> | 
        <a href="<?php echo admin_url('admin.php?page=ecocommerce-pro-builders'); ?>">Theme Options - Page Builders</a>
    </p>
    
    <style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    </style>
</body>
</html>
