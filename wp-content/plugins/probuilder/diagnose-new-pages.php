<?php
/**
 * Diagnose New Pages Issue
 * Check why new pages aren't working with ProBuilder
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Handle test page creation
if (isset($_POST['action']) && $_POST['action'] === 'create_test_page') {
    $test_title = 'ProBuilder Test Page ' . time();
    
    $post_id = wp_insert_post([
        'post_title' => $test_title,
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_content' => ''
    ]);
    
    if (!is_wp_error($post_id)) {
        // Set ProBuilder as editor
        update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
        
        $test_page_created = $post_id;
        $test_page_url = get_permalink($post_id);
        $test_page_edit_url = add_query_arg(['page_id' => $post_id, 'probuilder' => 'true'], home_url('/'));
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnose New Pages - ProBuilder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        h1 {
            color: #344047;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #667eea;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .section h3 {
            color: #344047;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .alert {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #22c55e;
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin-right: 10px;
            margin-top: 10px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .btn-success {
            background: #22c55e;
        }
        .btn-success:hover {
            background: #16a34a;
        }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .check-item {
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .check-item strong {
            color: #344047;
        }
        .status-good {
            color: #22c55e;
            font-weight: 600;
        }
        .status-bad {
            color: #ef4444;
            font-weight: 600;
        }
        code {
            background: #1e293b;
            color: #22c55e;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
        }
        ul {
            margin-left: 20px;
            line-height: 1.8;
        }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
        }
        .stat-card .label {
            font-size: 13px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnose New Pages Issue</h1>
        <p class="subtitle">Check why new pages aren't working with ProBuilder</p>

        <?php if (isset($test_page_created)): ?>
            <div class="alert alert-success">
                <strong>✓ Test page created successfully!</strong><br>
                Page ID: #<?php echo $test_page_created; ?><br>
                <a href="<?php echo $test_page_edit_url; ?>" target="_blank" style="color: #065f46; text-decoration: underline;">
                    Click here to edit with ProBuilder
                </a>
            </div>
        <?php endif; ?>

        <!-- System Checks -->
        <div class="section">
            <h3>🔧 System Status</h3>
            
            <?php
            // Check if ProBuilder is active
            $probuilder_active = class_exists('ProBuilder');
            
            // Check if Elementor is still active
            $elementor_active = defined('ELEMENTOR_VERSION') || class_exists('\Elementor\Plugin');
            
            // Check ProBuilder files
            $probuilder_files_exist = file_exists(PROBUILDER_PATH . 'includes/class-editor.php');
            
            // Check if post types are enabled
            $enabled_post_types = get_option('probuilder_post_types', ['page', 'post']);
            
            // Check recent pages
            $recent_pages = get_posts([
                'post_type' => 'page',
                'posts_per_page' => 5,
                'orderby' => 'date',
                'order' => 'DESC'
            ]);
            ?>
            
            <div class="check-item">
                <strong>ProBuilder Plugin Active:</strong>
                <span class="<?php echo $probuilder_active ? 'status-good' : 'status-bad'; ?>">
                    <?php echo $probuilder_active ? '✓ Yes' : '✗ No'; ?>
                </span>
            </div>
            
            <div class="check-item">
                <strong>Elementor Still Active:</strong>
                <span class="<?php echo $elementor_active ? 'status-bad' : 'status-good'; ?>">
                    <?php echo $elementor_active ? '✗ Yes (Should be uninstalled!)' : '✓ No (Good!)'; ?>
                </span>
            </div>
            
            <div class="check-item">
                <strong>ProBuilder Files Present:</strong>
                <span class="<?php echo $probuilder_files_exist ? 'status-good' : 'status-bad'; ?>">
                    <?php echo $probuilder_files_exist ? '✓ Yes' : '✗ Missing'; ?>
                </span>
            </div>
            
            <div class="check-item">
                <strong>Pages Enabled for ProBuilder:</strong>
                <span class="<?php echo in_array('page', $enabled_post_types) ? 'status-good' : 'status-bad'; ?>">
                    <?php echo in_array('page', $enabled_post_types) ? '✓ Yes' : '✗ No'; ?>
                </span>
            </div>
            
            <?php if ($elementor_active): ?>
                <div class="alert alert-error" style="margin-top: 15px;">
                    <strong>⚠️ Problem Found: Elementor is still active!</strong><br>
                    You said you uninstalled Elementor, but it's still detected. Please:
                    <ul style="margin-top: 10px;">
                        <li>Go to Plugins → Installed Plugins</li>
                        <li>Make sure Elementor is deactivated</li>
                        <li>Delete it completely</li>
                        <li>Then refresh this page</li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (!in_array('page', $enabled_post_types)): ?>
                <div class="alert alert-error" style="margin-top: 15px;">
                    <strong>⚠️ Problem Found: Pages are not enabled for ProBuilder!</strong><br>
                    <form method="post" style="margin-top: 15px;">
                        <input type="hidden" name="action" value="enable_pages">
                        <button type="submit" class="btn btn-danger">Enable Pages for ProBuilder</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Pages -->
        <div class="section">
            <h3>📄 Recent Pages (Last 5)</h3>
            
            <?php if (empty($recent_pages)): ?>
                <div class="alert alert-info">
                    No pages found. Create one to test!
                </div>
            <?php else: ?>
                <div class="stat-grid">
                    <?php foreach ($recent_pages as $page): ?>
                        <?php
                        $has_probuilder = !empty(get_post_meta($page->ID, '_probuilder_data', true));
                        $has_elementor = !empty(get_post_meta($page->ID, '_elementor_data', true));
                        $edit_mode = get_post_meta($page->ID, '_probuilder_edit_mode', true);
                        ?>
                        <div class="stat-card">
                            <strong style="font-size: 14px; display: block; margin-bottom: 10px;">
                                <?php echo esc_html($page->post_title); ?>
                            </strong>
                            <div class="label">ID: #<?php echo $page->ID; ?></div>
                            <div class="label">
                                <?php if ($has_probuilder): ?>
                                    <span style="color: #22c55e;">✓ ProBuilder</span>
                                <?php elseif ($has_elementor): ?>
                                    <span style="color: #ef4444;">Elementor</span>
                                <?php else: ?>
                                    <span style="color: #9ca3af;">No builder</span>
                                <?php endif; ?>
                            </div>
                            <div style="margin-top: 10px;">
                                <a href="<?php echo add_query_arg(['page_id' => $page->ID, 'probuilder' => 'true'], home_url('/')); ?>" 
                                   class="btn" style="font-size: 11px; padding: 6px 12px;">
                                    Edit
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Create Test Page -->
        <div class="section">
            <h3>🧪 Test Creating a New Page</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">
                Create a test page to verify ProBuilder works on new pages
            </p>
            
            <form method="post">
                <input type="hidden" name="action" value="create_test_page">
                <button type="submit" class="btn btn-success">
                    ➕ Create Test Page with ProBuilder
                </button>
            </form>
        </div>

        <!-- ProBuilder Settings -->
        <div class="section">
            <h3>⚙️ ProBuilder Settings</h3>
            
            <?php
            $settings = [
                'Enabled Post Types' => implode(', ', $enabled_post_types),
                'ProBuilder Version' => defined('PROBUILDER_VERSION') ? PROBUILDER_VERSION : 'Unknown',
                'WordPress Version' => get_bloginfo('version'),
                'PHP Version' => PHP_VERSION,
                'Theme' => wp_get_theme()->get('Name')
            ];
            ?>
            
            <?php foreach ($settings as $label => $value): ?>
                <div class="check-item">
                    <strong><?php echo $label; ?>:</strong>
                    <code><?php echo esc_html($value); ?></code>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- How to Create Pages with ProBuilder -->
        <div class="section">
            <h3>📚 How to Create New Pages with ProBuilder</h3>
            
            <div class="alert alert-info">
                <strong>Correct Workflow:</strong>
                <ol style="margin-left: 20px; margin-top: 10px; line-height: 1.8;">
                    <li>Go to <strong>Pages → Add New</strong></li>
                    <li>Enter a page title (e.g., "Contact Us")</li>
                    <li>Click <strong>"Publish"</strong> (don't add content)</li>
                    <li>In the Pages list, find your new page</li>
                    <li>Click <strong>"Edit with ProBuilder"</strong> link</li>
                    <li>Build your content in ProBuilder</li>
                    <li>Click <strong>💾 Save</strong> button</li>
                    <li>View your page - should show ProBuilder content!</li>
                </ol>
            </div>
        </div>

        <!-- Common Issues -->
        <div class="section">
            <h3>🔍 Common Issues & Solutions</h3>
            
            <details style="margin-bottom: 15px;">
                <summary style="cursor: pointer; padding: 10px; background: white; border-radius: 6px; font-weight: 600;">
                    Issue #1: "Edit with ProBuilder" button doesn't appear
                </summary>
                <div style="padding: 15px; background: white; margin-top: 10px; border-radius: 6px;">
                    <p><strong>Solution:</strong></p>
                    <ul>
                        <li>Make sure pages are enabled in ProBuilder settings</li>
                        <li>Check if you're an admin user</li>
                        <li>Clear WordPress cache</li>
                        <li>Try deactivating and reactivating ProBuilder</li>
                    </ul>
                </div>
            </details>
            
            <details style="margin-bottom: 15px;">
                <summary style="cursor: pointer; padding: 10px; background: white; border-radius: 6px; font-weight: 600;">
                    Issue #2: ProBuilder editor doesn't load
                </summary>
                <div style="padding: 15px; background: white; margin-top: 10px; border-radius: 6px;">
                    <p><strong>Solution:</strong></p>
                    <ul>
                        <li>Check browser console for JavaScript errors</li>
                        <li>Make sure jQuery is loaded</li>
                        <li>Disable other plugins temporarily</li>
                        <li>Try a different browser</li>
                        <li>Clear browser cache</li>
                    </ul>
                </div>
            </details>
            
            <details style="margin-bottom: 15px;">
                <summary style="cursor: pointer; padding: 10px; background: white; border-radius: 6px; font-weight: 600;">
                    Issue #3: Page shows blank content
                </summary>
                <div style="padding: 15px; background: white; margin-top: 10px; border-radius: 6px;">
                    <p><strong>Solution:</strong></p>
                    <ul>
                        <li>This is normal if you haven't built anything yet</li>
                        <li>Edit the page with ProBuilder</li>
                        <li>Add some widgets (heading, text, etc.)</li>
                        <li>Click Save</li>
                        <li>View the page again</li>
                    </ul>
                </div>
            </details>
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h3>🔗 Quick Actions</h3>
            
            <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="btn">
                ➕ Create New Page
            </a>
            
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="btn">
                📄 View All Pages
            </a>
            
            <a href="<?php echo admin_url('admin.php?page=probuilder-settings'); ?>" class="btn">
                ⚙️ ProBuilder Settings
            </a>
            
            <a href="<?php echo home_url('/wp-content/plugins/probuilder/clear-cache.php'); ?>" class="btn">
                🗑️ Clear Cache
            </a>
        </div>

        <?php
        // Handle enabling pages
        if (isset($_POST['action']) && $_POST['action'] === 'enable_pages') {
            $current_types = get_option('probuilder_post_types', []);
            if (!in_array('page', $current_types)) {
                $current_types[] = 'page';
                update_option('probuilder_post_types', $current_types);
                
                echo '<div class="alert alert-success">';
                echo '<strong>✓ Pages have been enabled for ProBuilder!</strong><br>';
                echo 'Refresh this page to see the changes.';
                echo '</div>';
                
                echo '<script>setTimeout(function() { window.location.reload(); }, 2000);</script>';
            }
        }
        ?>
    </div>
</body>
</html>

