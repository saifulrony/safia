<?php
/**
 * Quick Fix for Page 489
 * Removes Elementor/demo content and uses ProBuilder only
 */

require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

$post_id = 489;
$action = isset($_GET['action']) ? $_GET['action'] : '';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Page 489</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #344047; margin-bottom: 20px; }
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
            padding: 15px 30px;
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
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .steps ol {
            margin-left: 20px;
            line-height: 2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Page 489</h1>

        <?php if ($action === 'fix'): ?>
            <?php
            // Perform the fix
            $post = get_post($post_id);
            
            if (!$post) {
                echo '<div class="alert alert-error">Page 489 not found!</div>';
            } else {
                echo '<div class="alert alert-info"><strong>Fixing page...</strong></div>';
                
                $steps_done = [];
                
                // Step 1: Remove Elementor data
                $elementor_keys = ['_elementor_data', '_elementor_edit_mode', '_elementor_template_type', '_elementor_version', '_elementor_pro_version', '_elementor_css'];
                $elementor_removed = 0;
                foreach ($elementor_keys as $key) {
                    if (delete_post_meta($post_id, $key)) {
                        $elementor_removed++;
                    }
                }
                if ($elementor_removed > 0) {
                    $steps_done[] = "✓ Removed $elementor_removed Elementor meta keys";
                }
                
                // Step 2: Clear post content
                $result = wp_update_post([
                    'ID' => $post_id,
                    'post_content' => ''
                ]);
                
                if (!is_wp_error($result)) {
                    $steps_done[] = "✓ Cleared page content (removed demo text)";
                }
                
                // Step 3: Ensure ProBuilder mode is set
                update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
                $steps_done[] = "✓ Set ProBuilder as the active editor";
                
                // Step 4: Clear all caches
                clean_post_cache($post_id);
                wp_cache_delete($post_id, 'post_meta');
                wp_cache_flush();
                $steps_done[] = "✓ Cleared WordPress cache";
                
                // Step 5: Flush rewrite rules
                flush_rewrite_rules(false);
                $steps_done[] = "✓ Flushed permalink rules";
                
                // Show results
                echo '<div class="alert alert-success">';
                echo '<strong>🎉 Page 489 Fixed Successfully!</strong><br><br>';
                echo '<strong>What was done:</strong><br>';
                echo '<ul style="margin-top: 10px;">';
                foreach ($steps_done as $step) {
                    echo '<li>' . $step . '</li>';
                }
                echo '</ul>';
                echo '</div>';
                
                $probuilder_data = get_post_meta($post_id, '_probuilder_data', true);
                $has_probuilder = !empty($probuilder_data);
                
                if ($has_probuilder) {
                    $element_count = is_array($probuilder_data) ? count($probuilder_data) : 0;
                    echo '<div class="alert alert-success">';
                    echo '<strong>✓ ProBuilder Data Verified</strong><br>';
                    echo "Found $element_count element(s) in this page.";
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-error">';
                    echo '<strong>⚠️ No ProBuilder Data Found</strong><br>';
                    echo 'The page is clean, but has no ProBuilder content yet. You need to edit it with ProBuilder and save.';
                    echo '</div>';
                }
                
                echo '<a href="' . get_permalink($post_id) . '" target="_blank" class="btn">👁️ View Page</a>';
                echo '<a href="' . add_query_arg(['page_id' => $post_id, 'probuilder' => 'true'], home_url('/')) . '" class="btn">✏️ Edit with ProBuilder</a>';
                echo '<a href="check-page-489.php" class="btn">🔍 Check Again</a>';
            }
            ?>
            
        <?php else: ?>
            
            <div class="alert alert-info">
                <strong>🔍 Problem with Page 489:</strong><br>
                When you save content in ProBuilder, you're seeing old demo/Elementor content instead of your ProBuilder content.
            </div>

            <div class="steps">
                <strong>This fix will:</strong>
                <ol>
                    <li>Remove all Elementor metadata from page 489</li>
                    <li>Clear the page content (remove demo text)</li>
                    <li>Set ProBuilder as the active editor</li>
                    <li>Clear all WordPress caches</li>
                    <li>Flush permalink rules</li>
                    <li>Keep your ProBuilder content intact</li>
                </ol>
            </div>

            <?php
            // Check current state
            $post = get_post($post_id);
            if ($post) {
                $has_content = !empty(trim($post->post_content));
                $has_elementor = !empty(get_post_meta($post_id, '_elementor_data', true)) || get_post_meta($post_id, '_elementor_edit_mode', true) === 'builder';
                $has_probuilder = !empty(get_post_meta($post_id, '_probuilder_data', true));
                
                echo '<div class="alert alert-info">';
                echo '<strong>Current State:</strong><br>';
                echo '<ul style="margin-top: 10px; margin-left: 20px;">';
                echo '<li>Page content: ' . ($has_content ? '<span style="color: #ef4444;">Has content (' . strlen($post->post_content) . ' chars)</span>' : '<span style="color: #22c55e;">Empty</span>') . '</li>';
                echo '<li>Elementor data: ' . ($has_elementor ? '<span style="color: #ef4444;">Found (conflicting!)</span>' : '<span style="color: #22c55e;">None</span>') . '</li>';
                echo '<li>ProBuilder data: ' . ($has_probuilder ? '<span style="color: #22c55e;">Found (' . (is_array(get_post_meta($post_id, '_probuilder_data', true)) ? count(get_post_meta($post_id, '_probuilder_data', true)) : '0') . ' elements)</span>' : '<span style="color: #ef4444;">None</span>') . '</li>';
                echo '</ul>';
                echo '</div>';
                
                if ($has_probuilder) {
                    echo '<div class="alert alert-success">';
                    echo '<strong>✓ Good News!</strong><br>';
                    echo 'Your ProBuilder content is saved. This fix will just remove the conflicting content so your ProBuilder content shows.';
                    echo '</div>';
                }
            }
            ?>

            <a href="?action=fix" class="btn btn-danger">🔧 Fix Page 489 Now</a>
            <a href="check-page-489.php" class="btn">🔍 Detailed Diagnostic</a>
            
        <?php endif; ?>
    </div>
</body>
</html>

