<?php
/**
 * Fix Content Mismatch Issue
 * When you save in ProBuilder but page shows different content
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Get a specific page to test
$test_page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;

if ($test_page_id) {
    $test_page = get_post($test_page_id);
    $probuilder_data = get_post_meta($test_page_id, '_probuilder_data', true);
    $post_content = $test_page->post_content;
}

// Handle fix action
if (isset($_POST['action']) && $_POST['action'] === 'clear_gutenberg_content') {
    $page_id = intval($_POST['page_id']);
    
    // Clear the post content (Gutenberg/default content)
    wp_update_post([
        'ID' => $page_id,
        'post_content' => ''
    ]);
    
    // Ensure ProBuilder mode is set
    update_post_meta($page_id, '_probuilder_edit_mode', 'probuilder');
    
    // Clear caches
    clean_post_cache($page_id);
    wp_cache_delete($page_id, 'post_meta');
    wp_cache_flush();
    
    $success_message = "✓ Fixed page #$page_id - Cleared Gutenberg content. ProBuilder content will now show!";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Content Mismatch - ProBuilder</title>
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
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
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
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .section h3 {
            color: #344047;
            margin-bottom: 15px;
            font-size: 18px;
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
        .btn:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .code-block {
            background: #1e293b;
            color: #22c55e;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 15px 0;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #344047;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-error {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Content Mismatch Issue</h1>
        <p class="subtitle">When ProBuilder shows different content than what you built</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <strong><?php echo $success_message; ?></strong><br>
                <a href="?page_id=<?php echo $page_id; ?>" class="btn" style="margin-top: 15px;">Check Page Again</a>
                <a href="<?php echo get_permalink($page_id); ?>" target="_blank" class="btn">View Page</a>
            </div>
        <?php endif; ?>

        <div class="alert alert-error">
            <strong>🐛 The Problem You're Experiencing:</strong>
            <ol style="margin: 15px 0 0 20px; line-height: 1.8;">
                <li>You edit a page with ProBuilder</li>
                <li>You add widgets (heading, text, etc.)</li>
                <li>You click Save</li>
                <li>You view the page...</li>
                <li><strong>BUT it shows DIFFERENT content!</strong> (Gutenberg or old content)</li>
            </ol>
        </div>

        <div class="section">
            <h3>🔍 Why This Happens</h3>
            <p style="line-height: 1.8; color: #6b7280;">
                When you create a page in WordPress, it stores content in the <code>post_content</code> field (Gutenberg).
                When you edit with ProBuilder, it saves data in <code>_probuilder_data</code> meta field (separate!).
                <br><br>
                If <strong>both exist</strong>, your theme might show Gutenberg content instead of ProBuilder content.
            </p>
            
            <div class="code-block">
                <strong style="color: #f59e0b;">CONFLICT:</strong><br>
                Page has BOTH:<br>
                - post_content: "Gutenberg/default content" ← Shows this<br>
                - _probuilder_data: [Your ProBuilder widgets] ← Should show this<br>
                <br>
                <strong style="color: #22c55e;">SOLUTION:</strong><br>
                Clear post_content → Only ProBuilder data remains → Shows correctly!
            </div>
        </div>

        <?php if ($test_page_id && $test_page): ?>
            <div class="section">
                <h3>📄 Checking Page #<?php echo $test_page_id; ?>: <?php echo esc_html($test_page->post_title); ?></h3>
                
                <?php
                $has_post_content = !empty(trim($post_content));
                $has_probuilder = !empty($probuilder_data);
                $pb_element_count = is_array($probuilder_data) ? count($probuilder_data) : 0;
                
                $is_conflict = $has_post_content && $has_probuilder;
                ?>
                
                <table>
                    <tr>
                        <th style="width: 300px;">Check</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                    <tr>
                        <td><strong>Gutenberg Content (post_content)</strong></td>
                        <td>
                            <?php if ($has_post_content): ?>
                                <span class="badge badge-error">⚠️ Has Content</span>
                            <?php else: ?>
                                <span class="badge badge-success">✓ Empty</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($has_post_content): ?>
                                <?php echo strlen($post_content); ?> characters
                            <?php else: ?>
                                No content (good!)
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>ProBuilder Data (_probuilder_data)</strong></td>
                        <td>
                            <?php if ($has_probuilder): ?>
                                <span class="badge badge-success">✓ Has Data</span>
                            <?php else: ?>
                                <span class="badge badge-error">✗ No Data</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($has_probuilder): ?>
                                <?php echo $pb_element_count; ?> element(s)
                            <?php else: ?>
                                Not built yet
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Conflict Detection</strong></td>
                        <td>
                            <?php if ($is_conflict): ?>
                                <span class="badge badge-error">🚨 CONFLICT!</span>
                            <?php else: ?>
                                <span class="badge badge-success">✓ No Conflict</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($is_conflict): ?>
                                Both Gutenberg AND ProBuilder data exist
                            <?php else: ?>
                                Only one content source
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <?php if ($is_conflict): ?>
                    <div class="alert alert-error" style="margin-top: 20px;">
                        <strong>🚨 CONFLICT FOUND!</strong><br>
                        This page has BOTH Gutenberg content AND ProBuilder data. 
                        The Gutenberg content is probably showing instead of your ProBuilder design.
                        <br><br>
                        <strong>Fix:</strong> Clear the Gutenberg content so only ProBuilder shows.
                    </div>
                    
                    <form method="post">
                        <input type="hidden" name="action" value="clear_gutenberg_content">
                        <input type="hidden" name="page_id" value="<?php echo $test_page_id; ?>">
                        <button type="submit" class="btn btn-danger">
                            🔧 Fix This Page (Clear Gutenberg Content)
                        </button>
                    </form>
                <?php elseif ($has_probuilder && !$has_post_content): ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        <strong>✓ This page is configured correctly!</strong><br>
                        It has ProBuilder data and no conflicting Gutenberg content.
                        <br><br>
                        If it's still showing wrong content, clear your cache.
                    </div>
                    
                    <a href="<?php echo home_url('/wp-content/plugins/probuilder/clear-cache.php'); ?>" class="btn">
                        🗑️ Clear Cache
                    </a>
                    <a href="<?php echo get_permalink($test_page_id); ?>" target="_blank" class="btn">
                        👁️ View Page
                    </a>
                <?php elseif (!$has_probuilder): ?>
                    <div class="alert alert-warning" style="margin-top: 20px;">
                        <strong>⚠️ No ProBuilder data found!</strong><br>
                        This page hasn't been built with ProBuilder yet, or the data wasn't saved.
                        <br><br>
                        <strong>Solution:</strong> Edit with ProBuilder and save.
                    </div>
                    
                    <a href="<?php echo add_query_arg(['page_id' => $test_page_id, 'probuilder' => 'true'], home_url('/')); ?>" class="btn">
                        ✏️ Edit with ProBuilder
                    </a>
                <?php endif; ?>

                <?php if ($has_post_content): ?>
                    <details style="margin-top: 20px;">
                        <summary style="cursor: pointer; padding: 10px; background: #f8f9fa; border-radius: 6px; font-weight: 600;">
                            Show Gutenberg Content (<?php echo strlen($post_content); ?> chars)
                        </summary>
                        <div class="code-block" style="margin-top: 10px;">
                            <?php echo esc_html(substr($post_content, 0, 500)) . (strlen($post_content) > 500 ? '...' : ''); ?>
                        </div>
                    </details>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="section">
            <h3>🔍 Check Any Page</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">
                Enter a page ID to check if it has content conflicts:
            </p>
            
            <form method="get" style="display: flex; gap: 10px; align-items: center;">
                <input type="number" name="page_id" placeholder="Page ID (e.g., 489)" 
                       style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; width: 200px;"
                       value="<?php echo $test_page_id ?: ''; ?>" required>
                <button type="submit" class="btn">Check Page</button>
            </form>
        </div>

        <div class="section">
            <h3>📚 How to Prevent This in Future</h3>
            
            <div class="alert alert-info">
                <strong>Correct Workflow for New Pages:</strong>
                <ol style="margin: 15px 0 0 20px; line-height: 1.8;">
                    <li><strong>Create page in WordPress:</strong>
                        <ul style="margin-left: 20px;">
                            <li>Go to Pages → Add New</li>
                            <li>Enter only the TITLE</li>
                            <li>DON'T add any content in Gutenberg</li>
                            <li>Click "Publish"</li>
                        </ul>
                    </li>
                    <li><strong>Edit with ProBuilder immediately:</strong>
                        <ul style="margin-left: 20px;">
                            <li>In Pages list, click "Edit with ProBuilder"</li>
                            <li>Build your content</li>
                            <li>Click Save</li>
                        </ul>
                    </li>
                    <li><strong>Never use Gutenberg editor for ProBuilder pages!</strong></li>
                </ol>
            </div>

            <div class="alert alert-warning" style="margin-top: 15px;">
                <strong>⚠️ If you accidentally edit in Gutenberg:</strong>
                <ul style="margin: 10px 0 0 20px; line-height: 1.8;">
                    <li>Your page will have BOTH Gutenberg and ProBuilder content</li>
                    <li>This causes the conflict</li>
                    <li>Use this tool to clear Gutenberg content</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h3>🔗 Quick Actions</h3>
            
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="btn">
                📄 View All Pages
            </a>
            
            <a href="<?php echo home_url('/wp-content/plugins/probuilder/list-all-pages.php'); ?>" class="btn">
                📊 Check All Pages for Conflicts
            </a>
            
            <a href="<?php echo home_url('/wp-content/plugins/probuilder/clear-cache.php'); ?>" class="btn">
                🗑️ Clear Cache
            </a>
        </div>
    </div>
</body>
</html>

