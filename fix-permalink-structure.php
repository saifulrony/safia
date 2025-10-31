<?php
/**
 * Fix Permalink Structure - Remove index.php from URLs
 */

require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Update permalink structure to Post name (removes index.php)
if (isset($_GET['action']) && $_GET['action'] === 'fix') {
    // Set to Post name structure (pretty permalinks without index.php)
    update_option('permalink_structure', '/%postname%/');
    
    // Flush rewrite rules
    flush_rewrite_rules(true);
    
    // Clear all caches
    wp_cache_flush();
    
    $success = true;
}

$current_structure = get_option('permalink_structure');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Permalink Structure</title>
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
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #22c55e;
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
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        code {
            background: #1e293b;
            color: #22c55e;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
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
        .status-good {
            color: #22c55e;
            font-weight: 600;
        }
        .status-bad {
            color: #ef4444;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Permalink Structure - Remove index.php</h1>

        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">
                <strong>✅ Fixed!</strong><br>
                Permalink structure changed to Post name (pretty URLs without index.php)
                <br><br>
                <strong>Your URLs will now be:</strong><br>
                <code>http://192.168.10.203:7000/asdf21/</code><br>
                <strong>Instead of:</strong><br>
                <code>http://192.168.10.203:7000/index.php/asdf21/</code>
            </div>
        <?php endif; ?>

        <div class="section">
            <h3>📊 Current Status</h3>
            
            <div class="check-item">
                <strong>Current Permalink Structure:</strong>
                <code><?php echo $current_structure ?: 'Plain (not set)'; ?></code>
            </div>
            
            <?php if (strpos($current_structure, 'index.php') !== false || empty($current_structure)): ?>
                <div class="alert alert-error">
                    <strong>🚨 Problem Found!</strong><br>
                    Your permalink structure includes <code>index.php</code> or is set to Plain.
                    This makes URLs look like: <code>/index.php/asdf21/</code>
                    <br><br>
                    <strong>Solution:</strong> Change to Post name structure (removes index.php)
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    <strong>✓ Permalink Structure: OK</strong><br>
                    Your structure is: <code><?php echo esc_html($current_structure); ?></code>
                    <br><br>
                    If you're still seeing index.php in URLs, try flushing permalinks.
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>🔧 Quick Fix</h3>
            <p>Click this button to set permalink structure to "Post name" (removes index.php):</p>
            <a href="?action=fix" class="btn btn-danger">
                🔧 Fix Permalink Structure Now
            </a>
            
            <p style="margin-top: 20px; color: #6b7280;">
                This will:
                <ul style="margin-left: 20px; line-height: 1.8;">
                    <li>Change structure to: <code>/%postname%/</code></li>
                    <li>Flush rewrite rules</li>
                    <li>Clear all caches</li>
                    <li>Make URLs work without index.php</li>
                </ul>
            </p>
        </div>

        <?php
        // Check .htaccess
        $htaccess_exists = file_exists(ABSPATH . '.htaccess');
        $htaccess_readable = file_exists(ABSPATH . '.htaccess') && is_readable(ABSPATH . '.htaccess');
        ?>

        <div class="section">
            <h3>📝 .htaccess Status</h3>
            
            <div class="check-item">
                <strong>.htaccess exists:</strong>
                <span class="<?php echo $htaccess_exists ? 'status-good' : 'status-bad'; ?>">
                    <?php echo $htaccess_exists ? '✓ Yes' : '✗ No'; ?>
                </span>
            </div>
            
            <?php if ($htaccess_readable): ?>
                <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 8px;">
                    <strong>.htaccess contents:</strong>
                    <pre style="background: #1e293b; color: #22c55e; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 12px; margin-top: 10px;"><?php echo esc_html(file_get_contents(ABSPATH . '.htaccess')); ?></pre>
                </div>
            <?php elseif (!$htaccess_exists): ?>
                <div class="alert alert-error">
                    <strong>⚠️ .htaccess missing!</strong><br>
                    I'll create it automatically when you click "Fix Permalink Structure Now"
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>🧪 Test URLs</h3>
            <p>After fixing, test these URLs:</p>
            
            <div style="margin: 15px 0;">
                <strong>With page ID:</strong><br>
                <a href="http://192.168.10.203:7000/?page_id=663" target="_blank" class="btn">
                    Test: /?page_id=663
                </a>
            </div>
            
            <div style="margin: 15px 0;">
                <strong>With slug (should NOT have index.php):</strong><br>
                <a href="http://192.168.10.203:7000/asdf21/" target="_blank" class="btn">
                    Test: /asdf21/
                </a>
            </div>
            
            <div class="alert alert-info" style="margin-top: 20px;">
                <strong>✅ After fix:</strong><br>
                <code>/asdf21/</code> should work WITHOUT <code>index.php</code> in the URL
            </div>
        </div>

        <div class="section">
            <h3>🔗 Alternative Fix (Manual)</h3>
            <p>Or fix it manually in WordPress admin:</p>
            <ol style="line-height: 1.8;">
                <li>Go to <strong>Settings → Permalinks</strong></li>
                <li>Select <strong>"Post name"</strong> option</li>
                <li>Click <strong>"Save Changes"</strong></li>
                <li>URLs will work without index.php</li>
            </ol>
            
            <a href="<?php echo admin_url('options-permalink.php'); ?>" class="btn">
                Go to Permalink Settings
            </a>
        </div>
    </div>
</body>
</html>

