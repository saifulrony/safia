<?php
/**
 * Clear ALL ProBuilder Cache
 * Access once, then close
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Clear all ProBuilder cache
global $wpdb;

// Clear post cache
$posts = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_probuilder_data'");
foreach ($posts as $post_id) {
    clean_post_cache($post_id);
    wp_cache_delete($post_id, 'post_meta');
    wp_cache_delete($post_id, 'posts');
    delete_post_meta($post_id, '_probuilder_cache_output');
    delete_post_meta($post_id, '_probuilder_cache_data');
}

// Clear any ProBuilder transients
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_probuilder%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_probuilder%'");

// Clear WordPress object cache
wp_cache_flush();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cache Cleared</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #22c55e;
            font-size: 32px;
            margin-bottom: 20px;
        }
        p {
            color: #71717a;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #344047;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 5px;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #2c3540;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 64, 71, 0.3);
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="success-icon">✅</div>
        <h1>Cache Cleared!</h1>
        <p>
            All ProBuilder cache has been cleared for <strong><?php echo count($posts); ?> page(s)</strong>.<br>
            Your pages should now show the latest content.
        </p>
        <p style="font-size: 14px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
            <strong>Next steps:</strong><br>
            1. Visit your page to see fresh content<br>
            2. If still showing old content, clear browser cache (Ctrl+Shift+R)
        </p>
        <a href="<?php echo admin_url(); ?>" class="btn">Back to Dashboard</a>
        <a href="list-all-pages.php" class="btn" style="background: #667eea;">View All Pages</a>
    </div>
</body>
</html>

