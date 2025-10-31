<?php
/**
 * Diagnostic Tool - URL/Page Issue Checker
 * This helps identify why different URLs show the same content
 */

// Load WordPress
require_once('../../../wp-load.php');

// Must be admin
if (!current_user_can('manage_options')) {
    die('Access denied. Please log in as admin.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>ProBuilder URL Diagnostic Tool</title>
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
            margin-bottom: 40px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .section h2 {
            color: #344047;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-good { color: #22c55e; font-weight: bold; }
        .status-warning { color: #f59e0b; font-weight: bold; }
        .status-error { color: #ef4444; font-weight: bold; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #344047;
            color: white;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:hover {
            background: #f1f5f9;
        }
        .duplicate {
            background: #fee !important;
        }
        .code {
            background: #1e293b;
            color: #22c55e;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            overflow-x: auto;
            margin-top: 15px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
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
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #22c55e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 ProBuilder URL Diagnostic Tool</h1>
        <p class="subtitle">Checking why different URLs show the same content...</p>

        <?php
        // Get all ProBuilder pages
        $args = [
            'post_type' => 'page',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_probuilder_data',
                    'compare' => 'EXISTS'
                ]
            ]
        ];
        
        $probuilder_pages = get_posts($args);
        
        // Check for duplicate slugs
        $slugs = [];
        $duplicates = [];
        
        foreach ($probuilder_pages as $page) {
            $slug = $page->post_name;
            if (isset($slugs[$slug])) {
                $duplicates[$slug][] = $page->ID;
                if (!in_array($slugs[$slug], $duplicates[$slug])) {
                    $duplicates[$slug][] = $slugs[$slug];
                }
            } else {
                $slugs[$slug] = $page->ID;
            }
        }
        
        // Display results
        if (!empty($duplicates)) {
            echo '<div class="alert alert-error">';
            echo '<strong>🚨 PROBLEM FOUND: Duplicate URL Slugs!</strong><br>';
            echo 'Multiple pages are using the same URL slug. This causes different URLs to show the same content!';
            echo '</div>';
        } else {
            echo '<div class="alert alert-success">';
            echo '<strong>✓ No duplicate URL slugs found.</strong>';
            echo '</div>';
        }
        ?>

        <!-- All ProBuilder Pages -->
        <div class="section">
            <h2>📄 All ProBuilder Pages (<?php echo count($probuilder_pages); ?>)</h2>
            
            <?php if (empty($probuilder_pages)): ?>
                <p>No ProBuilder pages found. Create some pages first!</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Post ID</th>
                            <th>Title</th>
                            <th>URL Slug</th>
                            <th>Status</th>
                            <th>Elements</th>
                            <th>Full URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($probuilder_pages as $page): 
                            $elements = get_post_meta($page->ID, '_probuilder_data', true);
                            $element_count = is_array($elements) ? count($elements) : 0;
                            $is_duplicate = false;
                            
                            // Check if this page has a duplicate slug
                            foreach ($duplicates as $dup_slug => $dup_ids) {
                                if ($page->post_name === $dup_slug) {
                                    $is_duplicate = true;
                                    break;
                                }
                            }
                        ?>
                            <tr class="<?php echo $is_duplicate ? 'duplicate' : ''; ?>">
                                <td><strong>#<?php echo $page->ID; ?></strong></td>
                                <td><?php echo esc_html($page->post_title); ?></td>
                                <td>
                                    <code><?php echo esc_html($page->post_name); ?></code>
                                    <?php if ($is_duplicate): ?>
                                        <span class="status-error">⚠️ DUPLICATE!</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($page->post_status === 'publish'): ?>
                                        <span class="status-good">✓ Published</span>
                                    <?php else: ?>
                                        <span class="status-warning"><?php echo $page->post_status; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $element_count; ?> element(s)</td>
                                <td>
                                    <a href="<?php echo get_permalink($page->ID); ?>" target="_blank" style="color: #667eea;">
                                        <?php echo get_permalink($page->ID); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <?php if (!empty($duplicates)): ?>
        <!-- Duplicate Details -->
        <div class="section">
            <h2>🚨 Duplicate URL Slugs Found</h2>
            
            <?php foreach ($duplicates as $slug => $post_ids): ?>
                <div class="alert alert-error" style="margin-bottom: 15px;">
                    <strong>Slug: "<?php echo esc_html($slug); ?>"</strong> is used by <?php echo count($post_ids); ?> pages:<br>
                    <ul style="margin-top: 10px; margin-left: 20px;">
                        <?php foreach ($post_ids as $pid): 
                            $p = get_post($pid);
                        ?>
                            <li>
                                Post ID: <strong>#<?php echo $pid; ?></strong> - 
                                "<?php echo esc_html($p->post_title); ?>" - 
                                <a href="<?php echo get_permalink($pid); ?>" target="_blank" style="color: #667eea;">View</a> | 
                                <a href="<?php echo admin_url('post.php?post=' . $pid . '&action=edit'); ?>" style="color: #667eea;">Edit</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <div class="code">
<strong style="color: #f59e0b;">⚡ SOLUTION:</strong>

WordPress can only show ONE page per URL slug. When you have duplicates:
- WordPress picks one (usually the oldest post)
- All other URLs redirect to that same page
- This is why different URLs show the same content!

<strong style="color: #22c55e;">TO FIX THIS:</strong>

1. Go to each duplicate page in WordPress admin
2. Edit the page and change its "Permalink" (URL slug) to something unique
3. Or use the button below to auto-fix all duplicates

<strong style="color: #667eea;">AUTOMATIC FIX:</strong>
The fix button below will rename duplicate pages by adding numbers:
- rony → rony (keeps original)
- rony → rony-2 (second duplicate)
- rony → rony-3 (third duplicate)
            </div>

            <a href="?action=fix_duplicates&nonce=<?php echo wp_create_nonce('fix_duplicates'); ?>" class="btn">
                🔧 Auto-Fix All Duplicate Slugs
            </a>
        </div>
        <?php endif; ?>

        <?php
        // Handle auto-fix
        if (isset($_GET['action']) && $_GET['action'] === 'fix_duplicates') {
            if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'fix_duplicates')) {
                echo '<div class="alert alert-error">Invalid security token.</div>';
            } else {
                echo '<div class="section">';
                echo '<h2>🔧 Fixing Duplicate Slugs...</h2>';
                
                $fixed = 0;
                foreach ($duplicates as $slug => $post_ids) {
                    // Keep the first one, rename others
                    array_shift($post_ids); // Remove first ID (keep it as is)
                    
                    $counter = 2;
                    foreach ($post_ids as $pid) {
                        $new_slug = $slug . '-' . $counter;
                        
                        // Make sure this slug is unique
                        while (get_page_by_path($new_slug)) {
                            $counter++;
                            $new_slug = $slug . '-' . $counter;
                        }
                        
                        $result = wp_update_post([
                            'ID' => $pid,
                            'post_name' => $new_slug
                        ]);
                        
                        if (!is_wp_error($result)) {
                            echo '<div class="alert alert-success">';
                            echo "✓ Fixed Post #$pid: Changed slug from '<strong>$slug</strong>' to '<strong>$new_slug</strong>'<br>";
                            echo "New URL: <a href='" . get_permalink($pid) . "' target='_blank'>" . get_permalink($pid) . "</a>";
                            echo '</div>';
                            $fixed++;
                        } else {
                            echo '<div class="alert alert-error">';
                            echo "✗ Failed to fix Post #$pid: " . $result->get_error_message();
                            echo '</div>';
                        }
                        
                        $counter++;
                    }
                }
                
                // Flush rewrite rules
                flush_rewrite_rules();
                
                echo '<div class="alert alert-success">';
                echo "<strong>🎉 Done! Fixed $fixed duplicate slug(s).</strong><br>";
                echo 'Permalink rules have been flushed. Your pages should now have unique URLs!';
                echo '</div>';
                
                echo '<a href="?" class="btn">↻ Refresh Page</a>';
                echo '</div>';
            }
        }
        ?>

        <!-- Permalink Settings Check -->
        <div class="section">
            <h2>⚙️ WordPress Permalink Settings</h2>
            <?php
            $permalink_structure = get_option('permalink_structure');
            ?>
            
            <p><strong>Current structure:</strong> 
                <?php if (empty($permalink_structure)): ?>
                    <span class="status-warning">Plain (Not SEO-friendly)</span>
                <?php else: ?>
                    <code><?php echo esc_html($permalink_structure); ?></code>
                    <span class="status-good">✓ Good</span>
                <?php endif; ?>
            </p>

            <?php if (empty($permalink_structure)): ?>
                <div class="alert alert-warning">
                    <strong>⚠️ Warning:</strong> You're using plain permalinks. This can cause URL issues.
                    <br>Recommended: Go to <a href="<?php echo admin_url('options-permalink.php'); ?>" style="color: #667eea;"><strong>Settings → Permalinks</strong></a> 
                    and select "Post name" structure.
                </div>
            <?php endif; ?>

            <a href="<?php echo admin_url('options-permalink.php'); ?>" class="btn">
                Go to Permalink Settings
            </a>
        </div>

        <!-- Actions -->
        <div class="section">
            <h2>🔧 Quick Actions</h2>
            
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="btn">
                📝 View All Pages
            </a>
            
            <a href="<?php echo admin_url('options-permalink.php'); ?>" class="btn">
                ⚙️ Permalink Settings
            </a>
            
            <a href="?" class="btn">
                ↻ Refresh Diagnostic
            </a>
        </div>
    </div>
</body>
</html>

