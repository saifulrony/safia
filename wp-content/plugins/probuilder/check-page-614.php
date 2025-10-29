<?php
/**
 * Quick check for page ID 614
 */
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

$post_id = 614;
$post = get_post($post_id);

if (!$post) {
    die('Page 614 not found!');
}

$probuilder_data = get_post_meta($post_id, '_probuilder_data', true);

// Parse if string
if (is_string($probuilder_data)) {
    $decoded = json_decode($probuilder_data, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $probuilder_data = $decoded;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page 614 Debug</title>
    <style>
        body { font-family: monospace; max-width: 1200px; margin: 40px auto; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 25px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #344047; }
        h2 { color: #344047; margin-bottom: 15px; }
        .alert { padding: 15px; border-radius: 6px; margin: 15px 0; }
        .alert-danger { background: #fee; border-left: 4px solid #dc2626; }
        .alert-warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        .alert-success { background: #d4edda; border-left: 4px solid #22c55e; }
        .alert-info { background: #e6f3ff; border-left: 4px solid #667eea; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow: auto; max-height: 300px; font-size: 11px; }
        .btn { display: inline-block; padding: 12px 24px; background: #344047; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 5px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔍 Diagnostic for Page ID: 614</h1>
        <div class="alert alert-info">
            <strong>Page Title:</strong> <?php echo get_the_title($post_id); ?><br>
            <strong>Slug:</strong> <?php echo $post->post_name; ?><br>
            <strong>Status:</strong> <?php echo $post->post_status; ?><br>
            <strong>URL (page_id):</strong> <a href="http://192.168.10.203:7000/?page_id=614" target="_blank">http://192.168.10.203:7000/?page_id=614</a><br>
            <strong>URL (slug):</strong> <a href="<?php echo get_permalink($post_id); ?>" target="_blank"><?php echo get_permalink($post_id); ?></a>
        </div>
    </div>

    <div class="box">
        <h2>ProBuilder Data Status</h2>
        <?php if (empty($probuilder_data)): ?>
            <div class="alert alert-danger">
                <strong>❌ NO PROBUILDER DATA!</strong><br>
                This page has no ProBuilder data saved. The blank body is because there's no content.
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <strong>✅ ProBuilder data exists!</strong>
            </div>
            
            <div class="alert alert-info">
                <strong>Data Type:</strong> <?php echo gettype($probuilder_data); ?><br>
                <?php if (is_array($probuilder_data)): ?>
                    <strong>Element Count:</strong> <?php echo count($probuilder_data); ?><br><br>
                    
                    <?php if (count($probuilder_data) === 0): ?>
                        <div class="alert alert-warning">
                            <strong>⚠️ Empty Array!</strong><br>
                            ProBuilder data exists but contains 0 elements.<br>
                            This is why the body is blank - there's nothing to render!
                        </div>
                    <?php else: ?>
                        <strong>Elements:</strong><br>
                        <?php foreach ($probuilder_data as $index => $element): ?>
                            <div style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 4px;">
                                <strong>Element <?php echo $index + 1; ?>:</strong> 
                                <?php echo isset($element['widgetType']) ? $element['widgetType'] : 'Unknown'; ?>
                                
                                <?php if ($element['widgetType'] === 'heading'): ?>
                                    <br><strong style="color: #22c55e;">Heading:</strong> 
                                    "<?php echo esc_html($element['settings']['title'] ?? 'No title'); ?>"
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <h3>Full Data:</h3>
            <pre><?php print_r($probuilder_data); ?></pre>
        <?php endif; ?>
    </div>

    <div class="box">
        <h2>🔧 What to Do</h2>
        
        <?php if (empty($probuilder_data)): ?>
            <div class="alert alert-warning">
                <strong>Action Required:</strong><br>
                1. Open page 614 in ProBuilder<br>
                2. Add your heading widget<br>
                3. Save the page<br>
                4. Come back here and refresh
            </div>
        <?php elseif (is_array($probuilder_data) && count($probuilder_data) === 0): ?>
            <div class="alert alert-warning">
                <strong>Action Required:</strong><br>
                ProBuilder data exists but is empty (0 elements).<br><br>
                1. Open page 614 in ProBuilder<br>
                2. Add your widgets<br>
                3. Save the page<br>
                4. The blank body should now have content!
            </div>
        <?php elseif (is_array($probuilder_data) && count($probuilder_data) > 0): ?>
            <div class="alert alert-success">
                <strong>Data looks good!</strong><br>
                <?php echo count($probuilder_data); ?> element(s) saved.<br><br>
                If page still shows blank:<br>
                1. Clear cache (button below)<br>
                2. Check debug.log for PHP errors<br>
                3. Visit page - should show error messages if widgets fail to render
            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="http://192.168.10.203:7000/?page_id=614" target="_blank" class="btn" style="background: #22c55e;">👁️ View Page</a>
        <a href="<?php echo add_query_arg(['p' => $post_id, 'probuilder' => 'true'], home_url('/')); ?>" class="btn">✏️ Edit in ProBuilder</a>
        <a href="clear-cache.php" class="btn" style="background: #dc2626;">🗑️ Clear Cache</a>
        <a href="list-all-pages.php" class="btn" style="background: #667eea;">📋 All Pages</a>
    </div>

    <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 8px;">
        <h3>🎓 Why Blank Body?</h3>
        <p><strong>Common causes:</strong></p>
        <ol>
            <li><strong>No data saved:</strong> ProBuilder data is empty</li>
            <li><strong>Empty array:</strong> Data exists but contains 0 elements</li>
            <li><strong>Widget errors:</strong> Widgets fail to render (check debug.log)</li>
            <li><strong>CSS hiding content:</strong> Content renders but is hidden</li>
        </ol>
        
        <p><strong>With my fix:</strong></p>
        <ul>
            <li>✅ Now shows error messages for failed widgets (for admins)</li>
            <li>✅ Shows warning if widget produces no output</li>
            <li>✅ Logs errors to debug.log</li>
            <li>✅ Shows debug panel with element count</li>
        </ul>
    </div>
</body>
</html>

