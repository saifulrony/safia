<?php
/**
 * Check what data is saved for a specific page
 * Usage: /wp-content/plugins/probuilder/check-page-data.php?slug=rony4
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

$slug = isset($_GET['slug']) ? sanitize_text_field($_GET['slug']) : '';

if (empty($slug)) {
    die('Usage: check-page-data.php?slug=your-page-slug');
}

// Find page by slug
$page = get_page_by_path($slug);

if (!$page) {
    die('Page not found with slug: ' . $slug);
}

$post_id = $page->ID;
$probuilder_data = get_post_meta($post_id, '_probuilder_data', true);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Data Check: <?php echo $slug; ?></title>
    <style>
        body {
            font-family: monospace;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #344047;
        }
        h2 {
            color: #344047;
            margin-bottom: 15px;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
        }
        .info {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #344047;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }
        .btn:hover {
            background: #2c3540;
        }
    </style>
</head>
<body>
    <div class="section">
        <h1>🔍 Page Data Check: <?php echo esc_html($slug); ?></h1>
    </div>

    <div class="section">
        <h2>Page Information</h2>
        <div class="info">
            <div class="label">Post ID:</div>
            <div><?php echo $post_id; ?></div>
        </div>
        <div class="info">
            <div class="label">Title:</div>
            <div><?php echo get_the_title($post_id); ?></div>
        </div>
        <div class="info">
            <div class="label">Slug:</div>
            <div><?php echo $page->post_name; ?></div>
        </div>
        <div class="info">
            <div class="label">Status:</div>
            <div><?php echo get_post_status($post_id); ?></div>
        </div>
        <div class="info">
            <div class="label">URL:</div>
            <div><a href="<?php echo get_permalink($post_id); ?>" target="_blank"><?php echo get_permalink($post_id); ?></a></div>
        </div>
        <div class="info">
            <div class="label">Modified:</div>
            <div><?php echo get_the_modified_date('Y-m-d H:i:s', $post_id); ?></div>
        </div>
    </div>

    <?php if (empty($probuilder_data)): ?>
        <div class="alert alert-danger">
            <strong>❌ NO PROBUILDER DATA FOUND!</strong><br>
            This page doesn't have any ProBuilder data saved.
        </div>
    <?php else: ?>
        <div class="alert alert-success">
            <strong>✅ PROBUILDER DATA EXISTS</strong>
        </div>
        
        <div class="section">
            <h2>Data Information</h2>
            <div class="info">
                <div class="label">Data Type:</div>
                <div><?php echo gettype($probuilder_data); ?></div>
            </div>
            
            <?php if (is_string($probuilder_data)): ?>
                <div class="info">
                    <div class="label">String Length:</div>
                    <div><?php echo strlen($probuilder_data); ?> characters</div>
                </div>
                <?php
                $decoded = json_decode($probuilder_data, true);
                if (json_last_error() === JSON_ERROR_NONE):
                ?>
                    <div class="alert alert-success">
                        <strong>✅ Valid JSON String</strong>
                    </div>
                    <div class="info">
                        <div class="label">Element Count:</div>
                        <div><?php echo is_array($decoded) ? count($decoded) : 0; ?></div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <strong>❌ Invalid JSON String</strong><br>
                        Error: <?php echo json_last_error_msg(); ?>
                    </div>
                <?php endif; ?>
            <?php elseif (is_array($probuilder_data)): ?>
                <div class="info">
                    <div class="label">Element Count:</div>
                    <div><?php echo count($probuilder_data); ?></div>
                </div>
                
                <?php if (count($probuilder_data) > 0): ?>
                    <h3 style="margin-top: 20px; margin-bottom: 10px;">Elements:</h3>
                    <?php foreach ($probuilder_data as $index => $element): ?>
                        <div style="background: #f8f9fa; padding: 10px; margin-bottom: 10px; border-radius: 4px;">
                            <strong>Element <?php echo $index + 1; ?>:</strong> 
                            <?php echo isset($element['widgetType']) ? $element['widgetType'] : 'Unknown'; ?>
                            (ID: <?php echo isset($element['id']) ? $element['id'] : 'No ID'; ?>)
                            
                            <?php if (isset($element['settings']) && is_array($element['settings'])): ?>
                                <br><small style="color: #666;">Settings: <?php echo count($element['settings']); ?> properties</small>
                                
                                <?php if ($element['widgetType'] === 'heading' && isset($element['settings']['title'])): ?>
                                    <br><strong style="color: #22c55e;">Heading Text: "<?php echo esc_html($element['settings']['title']); ?>"</strong>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Raw Data</h2>
            <pre><?php print_r($probuilder_data); ?></pre>
        </div>
    <?php endif; ?>

    <div class="section">
        <h2>Actions</h2>
        <a href="<?php echo get_permalink($post_id); ?>" target="_blank" class="btn">👁️ View Page</a>
        <a href="<?php echo add_query_arg(['p' => $post_id, 'probuilder' => 'true'], home_url('/')); ?>" class="btn">✏️ Edit in ProBuilder</a>
        <a href="?slug=<?php echo $slug; ?>&clear_cache=1" class="btn" style="background: #dc2626;">🗑️ Clear Cache for This Page</a>
        <a href="list-all-pages.php" class="btn" style="background: #667eea;">📋 All Pages</a>
    </div>

    <?php
    // Clear cache if requested
    if (isset($_GET['clear_cache'])) {
        clean_post_cache($post_id);
        delete_post_meta($post_id, '_probuilder_cache_output');
        delete_post_meta($post_id, '_probuilder_cache_data');
        
        // Clear WordPress object cache
        wp_cache_delete($post_id, 'posts');
        wp_cache_delete($post_id, 'post_meta');
        
        echo '<div class="alert alert-success">';
        echo '<strong>✅ Cache Cleared!</strong><br>';
        echo 'The cache for this page has been cleared. Try viewing the page again.';
        echo '</div>';
        
        echo '<script>setTimeout(function() { window.location = "?slug=' . $slug . '"; }, 2000);</script>';
    }
    ?>

    <div style="margin-top: 40px; text-align: center; color: #666; font-size: 12px;">
        <p>ProBuilder Diagnostic Tool • <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
</body>
</html>

