<?php
/**
 * Check what's on Page 489
 */
require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

$post_id = 489;
$post = get_post($post_id);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Page 489 Diagnostic</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #344047; margin-bottom: 30px; }
        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .section h3 { color: #344047; margin-top: 0; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #22c55e; }
        .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }
        code {
            background: #1e293b;
            color: #22c55e;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
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
        }
        .btn:hover { background: #5568d3; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        pre {
            background: #1e293b;
            color: #22c55e;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Page 489 Diagnostic & Fix</h1>

        <?php if (!$post): ?>
            <div class="alert alert-error">
                <strong>❌ Page Not Found!</strong><br>
                Page ID 489 doesn't exist in the database.
            </div>
        <?php else: ?>
            
            <div class="section">
                <h3>📄 Page Information</h3>
                <p><strong>Post ID:</strong> <?php echo $post->ID; ?></p>
                <p><strong>Title:</strong> <?php echo esc_html($post->post_title); ?></p>
                <p><strong>Slug:</strong> <code><?php echo esc_html($post->post_name); ?></code></p>
                <p><strong>Status:</strong> <?php echo $post->post_status; ?></p>
                <p><strong>URL:</strong> <a href="<?php echo get_permalink($post_id); ?>" target="_blank" style="color: #667eea;"><?php echo get_permalink($post_id); ?></a></p>
            </div>

            <?php
            // Check post content
            $has_content = !empty(trim($post->post_content));
            ?>
            
            <div class="section">
                <h3>📝 Post Content (in database)</h3>
                <?php if ($has_content): ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Page has existing content!</strong><br>
                        This page contains <?php echo strlen($post->post_content); ?> characters of content. This might be Elementor or demo content that's showing instead of ProBuilder.
                    </div>
                    <details>
                        <summary style="cursor: pointer; padding: 10px; background: #e5e7eb; border-radius: 6px; margin-top: 10px;">View Raw Content</summary>
                        <pre><?php echo esc_html(substr($post->post_content, 0, 500)) . (strlen($post->post_content) > 500 ? '...' : ''); ?></pre>
                    </details>
                <?php else: ?>
                    <div class="alert alert-success">
                        <strong>✓ Post content is empty</strong><br>
                        Good! The page has no content in the database, which means it should rely on ProBuilder.
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Check ProBuilder data
            $probuilder_data = get_post_meta($post_id, '_probuilder_data', true);
            $has_probuilder = !empty($probuilder_data);
            ?>

            <div class="section">
                <h3>🎨 ProBuilder Data</h3>
                <?php if ($has_probuilder): ?>
                    <?php
                    $element_count = is_array($probuilder_data) ? count($probuilder_data) : 0;
                    ?>
                    <div class="alert alert-success">
                        <strong>✓ ProBuilder data found!</strong><br>
                        Elements: <?php echo $element_count; ?><br>
                        Data type: <?php echo gettype($probuilder_data); ?>
                    </div>
                    
                    <?php if ($element_count > 0 && is_array($probuilder_data)): ?>
                        <p><strong>Elements in this page:</strong></p>
                        <ul>
                            <?php foreach ($probuilder_data as $index => $element): ?>
                                <li>
                                    Element <?php echo $index + 1; ?>: 
                                    <code><?php echo isset($element['widgetType']) ? esc_html($element['widgetType']) : 'unknown'; ?></code>
                                    <?php if (isset($element['settings']['title'])): ?>
                                        - "<?php echo esc_html(substr($element['settings']['title'], 0, 50)); ?>"
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-error">
                        <strong>❌ No ProBuilder data!</strong><br>
                        This page has no ProBuilder content saved. Have you saved your work in ProBuilder editor?
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Check Elementor data
            $elementor_data = get_post_meta($post_id, '_elementor_data', true);
            $elementor_edit_mode = get_post_meta($post_id, '_elementor_edit_mode', true);
            $has_elementor = !empty($elementor_data) || $elementor_edit_mode === 'builder';
            ?>

            <div class="section">
                <h3>🔷 Elementor Data</h3>
                <?php if ($has_elementor): ?>
                    <div class="alert alert-error">
                        <strong>🚨 PROBLEM FOUND: Page has Elementor data!</strong><br>
                        This page was built with Elementor. Elementor content might be showing instead of ProBuilder content.
                    </div>
                    <p><strong>Elementor metadata:</strong></p>
                    <ul>
                        <li>_elementor_edit_mode: <code><?php echo esc_html($elementor_edit_mode ?: 'not set'); ?></code></li>
                        <li>_elementor_data length: <?php echo !empty($elementor_data) ? strlen($elementor_data) . ' characters' : 'empty'; ?></li>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-success">
                        <strong>✓ No Elementor data</strong><br>
                        Good! This page is not using Elementor.
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Get all meta
            $all_meta = get_post_meta($post_id);
            ?>

            <div class="section">
                <h3>🔧 All Page Metadata</h3>
                <details>
                    <summary style="cursor: pointer; padding: 10px; background: #e5e7eb; border-radius: 6px;">Show All Meta Keys (<?php echo count($all_meta); ?>)</summary>
                    <ul style="margin-top: 10px; font-family: monospace; font-size: 12px;">
                        <?php foreach ($all_meta as $key => $value): ?>
                            <li>
                                <code><?php echo esc_html($key); ?></code>
                                <?php if (is_array($value) && count($value) === 1): ?>
                                    = <?php echo esc_html(substr(print_r($value[0], true), 0, 100)); ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            </div>

            <div class="section">
                <h3>💊 Quick Fixes</h3>
                
                <?php if ($has_elementor && $has_probuilder): ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Both Elementor AND ProBuilder data exist!</strong><br>
                        The page is confused about which builder to use. Let's fix this by:
                        <ol style="margin-top: 10px; margin-left: 20px;">
                            <li>Remove Elementor data</li>
                            <li>Clear page content</li>
                            <li>Keep only ProBuilder data</li>
                        </ol>
                    </div>
                    
                    <form method="post" style="margin-top: 20px;">
                        <input type="hidden" name="action" value="clear_elementor">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <button type="submit" class="btn btn-danger">🗑️ Remove Elementor Data & Use ProBuilder Only</button>
                    </form>
                <?php elseif ($has_content && $has_probuilder): ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Page has both content AND ProBuilder data!</strong><br>
                        The page content might be showing instead of ProBuilder. Let's clear it.
                    </div>
                    
                    <form method="post" style="margin-top: 20px;">
                        <input type="hidden" name="action" value="clear_content">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <button type="submit" class="btn btn-danger">🗑️ Clear Page Content & Use ProBuilder Only</button>
                    </form>
                <?php elseif (!$has_probuilder): ?>
                    <div class="alert alert-error">
                        <strong>❌ No ProBuilder data saved!</strong><br>
                        You need to edit and save the page in ProBuilder first.
                    </div>
                    
                    <a href="<?php echo add_query_arg(['page_id' => $post_id, 'probuilder' => 'true'], home_url('/')); ?>" class="btn">
                        ✏️ Edit with ProBuilder
                    </a>
                <?php else: ?>
                    <div class="alert alert-success">
                        <strong>✓ Page looks good!</strong><br>
                        Should be working correctly. Try viewing it:
                    </div>
                    
                    <a href="<?php echo get_permalink($post_id); ?>" target="_blank" class="btn">
                        👁️ View Page
                    </a>
                <?php endif; ?>

                <a href="<?php echo home_url('/wp-content/plugins/probuilder/clear-cache.php'); ?>" class="btn">
                    🗑️ Clear Cache
                </a>
            </div>

            <?php
            // Handle form submissions
            if (isset($_POST['action']) && isset($_POST['post_id'])) {
                $action = $_POST['action'];
                $pid = intval($_POST['post_id']);
                
                if ($action === 'clear_elementor' && $pid === $post_id) {
                    // Remove Elementor data
                    delete_post_meta($pid, '_elementor_data');
                    delete_post_meta($pid, '_elementor_edit_mode');
                    delete_post_meta($pid, '_elementor_template_type');
                    delete_post_meta($pid, '_elementor_version');
                    delete_post_meta($pid, '_elementor_pro_version');
                    delete_post_meta($pid, '_elementor_css');
                    
                    // Clear post content
                    wp_update_post([
                        'ID' => $pid,
                        'post_content' => ''
                    ]);
                    
                    // Clear cache
                    clean_post_cache($pid);
                    wp_cache_flush();
                    
                    echo '<div class="alert alert-success">';
                    echo '<strong>✓ Done!</strong><br>';
                    echo 'Removed Elementor data and cleared content. Page now uses ProBuilder only!';
                    echo '</div>';
                    
                    echo '<script>setTimeout(function() { window.location.reload(); }, 2000);</script>';
                } elseif ($action === 'clear_content' && $pid === $post_id) {
                    // Clear post content
                    wp_update_post([
                        'ID' => $pid,
                        'post_content' => ''
                    ]);
                    
                    // Clear cache
                    clean_post_cache($pid);
                    wp_cache_flush();
                    
                    echo '<div class="alert alert-success">';
                    echo '<strong>✓ Done!</strong><br>';
                    echo 'Cleared page content. Page now uses ProBuilder only!';
                    echo '</div>';
                    
                    echo '<script>setTimeout(function() { window.location.reload(); }, 2000);</script>';
                }
            }
            ?>

        <?php endif; ?>
    </div>
</body>
</html>

