<?php
/**
 * Fix Permalinks - Make slugs work
 */

require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Check page 663
$page = get_post(663);
$permalink_structure = get_option('permalink_structure');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Permalinks</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #344047; margin-bottom: 20px; }
        .alert {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #22c55e; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }
        .alert-info { background: #dbeafe; color: #1e40af; border-left: 4px solid #3b82f6; }
        .section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .section h3 { color: #344047; margin-top: 0; }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin: 10px 5px;
        }
        .btn:hover { background: #5568d3; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        code {
            background: #1e293b;
            color: #22c55e;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: monospace;
        }
        .check-item {
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-good { color: #22c55e; font-weight: 600; }
        .status-bad { color: #ef4444; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Permalinks for Page 663</h1>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'flush'): ?>
            <?php
            // Flush rewrite rules
            flush_rewrite_rules(true);
            
            // Clear object cache
            wp_cache_flush();
            
            echo '<div class="alert alert-success">';
            echo '<strong>✓ Permalinks Flushed!</strong><br>';
            echo 'WordPress rewrite rules have been regenerated.';
            echo '</div>';
            ?>
        <?php endif; ?>

        <div class="section">
            <h3>🔍 Current Status</h3>
            
            <div class="check-item">
                <strong>Page Title:</strong>
                <span><?php echo esc_html($page->post_title); ?></span>
            </div>
            
            <div class="check-item">
                <strong>Page Slug:</strong>
                <code><?php echo esc_html($page->post_name); ?></code>
            </div>
            
            <div class="check-item">
                <strong>Page Status:</strong>
                <span class="<?php echo $page->post_status === 'publish' ? 'status-good' : 'status-bad'; ?>">
                    <?php echo esc_html($page->post_status); ?>
                </span>
            </div>
            
            <div class="check-item">
                <strong>Permalink Structure:</strong>
                <code><?php echo $permalink_structure ?: 'Plain (not SEO-friendly)'; ?></code>
            </div>
            
            <div class="check-item">
                <strong>Expected URL:</strong>
                <code><?php echo get_permalink(663); ?></code>
            </div>
        </div>

        <?php
        // Check if permalink structure is empty
        if (empty($permalink_structure)):
        ?>
            <div class="alert alert-error">
                <strong>🚨 Problem Found: Plain Permalinks!</strong><br>
                Your site is using "Plain" permalinks, which means slugs like <code>/asdf2/</code> won't work.
                You can only use <code>?page_id=663</code> format.
                <br><br>
                <strong>Solution:</strong> Change to pretty permalinks (recommended!)
            </div>
            
            <div class="section">
                <h3>Fix: Enable Pretty Permalinks</h3>
                <ol style="line-height: 1.8;">
                    <li>Go to <strong>Settings → Permalinks</strong></li>
                    <li>Select <strong>"Post name"</strong> option</li>
                    <li>Click <strong>"Save Changes"</strong></li>
                    <li>Come back here and test again</li>
                </ol>
                
                <a href="<?php echo admin_url('options-permalink.php'); ?>" class="btn">
                    Go to Permalink Settings
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <strong>✓ Permalink Structure: OK</strong><br>
                You're using pretty permalinks: <code><?php echo $permalink_structure; ?></code>
            </div>
            
            <div class="alert alert-info">
                <strong>The slug should work, but you may need to flush rewrite rules.</strong>
            </div>
            
            <div class="section">
                <h3>Quick Fix</h3>
                <p>Click this button to flush WordPress rewrite rules:</p>
                <a href="?action=flush" class="btn btn-danger">
                    Flush Permalinks Now
                </a>
            </div>
        <?php endif; ?>

        <div class="section">
            <h3>🧪 Test URLs</h3>
            <p>After fixing, test both URLs:</p>
            
            <div style="margin: 15px 0;">
                <strong>1. Test with page ID:</strong><br>
                <a href="http://192.168.10.203:7000/?page_id=663" target="_blank" class="btn">
                    Test: /?page_id=663
                </a>
            </div>
            
            <div style="margin: 15px 0;">
                <strong>2. Test with slug:</strong><br>
                <a href="http://192.168.10.203:7000/asdf2/" target="_blank" class="btn">
                    Test: /asdf2/
                </a>
            </div>
            
            <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
                Both should show the same content!
            </p>
        </div>

        <?php
        // Check .htaccess
        $htaccess_path = ABSPATH . '.htaccess';
        $htaccess_exists = file_exists($htaccess_path);
        $htaccess_writable = is_writable($htaccess_path) || is_writable(ABSPATH);
        ?>

        <div class="section">
            <h3>🔧 Apache Configuration</h3>
            
            <div class="check-item">
                <strong>.htaccess exists:</strong>
                <span class="<?php echo $htaccess_exists ? 'status-good' : 'status-bad'; ?>">
                    <?php echo $htaccess_exists ? '✓ Yes' : '✗ No'; ?>
                </span>
            </div>
            
            <div class="check-item">
                <strong>.htaccess writable:</strong>
                <span class="<?php echo $htaccess_writable ? 'status-good' : 'status-bad'; ?>">
                    <?php echo $htaccess_writable ? '✓ Yes' : '✗ No'; ?>
                </span>
            </div>
            
            <?php if (!$htaccess_exists || !$htaccess_writable): ?>
                <div class="alert alert-warning">
                    <strong>⚠️ .htaccess Issue</strong><br>
                    WordPress needs to create/update .htaccess file for pretty permalinks.
                    <br><br>
                    <strong>Fix:</strong> Run this command:
                    <code style="display: block; margin-top: 10px;">
                        sudo chmod 666 /home/saiful/wordpress/.htaccess
                    </code>
                    Or create it manually (see below)
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$htaccess_exists): ?>
            <div class="section">
                <h3>📝 Manual .htaccess Fix</h3>
                <p>Create this file: <code>/home/saiful/wordpress/.htaccess</code></p>
                <p>With this content:</p>
                <pre style="background: #1e293b; color: #22c55e; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 12px;">
# BEGIN WordPress
&lt;IfModule mod_rewrite.c&gt;
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
&lt;/IfModule&gt;
# END WordPress
                </pre>
                
                <p>Then run:</p>
                <code style="display: block; background: #1e293b; color: #22c55e; padding: 15px; border-radius: 8px;">
                    sudo chmod 644 /home/saiful/wordpress/.htaccess
                </code>
            </div>
        <?php endif; ?>

        <div class="section">
            <h3>🔗 Quick Actions</h3>
            <a href="<?php echo admin_url('options-permalink.php'); ?>" class="btn">
                Permalink Settings
            </a>
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="btn">
                All Pages
            </a>
            <a href="?" class="btn">
                Refresh This Page
            </a>
        </div>
    </div>
</body>
</html>

