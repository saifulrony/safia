<?php
/**
 * Quick Diagnostic: What Page Is Actually Loading?
 * Put this in your WordPress root and visit: http://yoursite.com/check-what-page-is-loading.php?url=/your-page-url/
 */

// Load WordPress
require_once('wp-load.php');

// Get the URL to test
$test_url = isset($_GET['url']) ? $_GET['url'] : '';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Loading Diagnostic</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #344047;
            margin-bottom: 30px;
        }
        .test-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .test-form input {
            width: 70%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
        }
        .test-form button {
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        .result {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        .result h3 {
            color: #344047;
            margin-top: 0;
        }
        .result code {
            background: #1e293b;
            color: #22c55e;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: monospace;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: #ef4444;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left-color: #22c55e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 What Page Is Actually Loading?</h1>
        
        <div class="test-form">
            <form method="get">
                <input type="text" name="url" placeholder="/your-page-slug/" value="<?php echo esc_attr($test_url); ?>" required />
                <button type="submit">Test URL</button>
            </form>
            <p style="margin-top: 10px; color: #64748b; font-size: 13px;">
                Enter a page URL like: /my-page/ or /rony7/
            </p>
        </div>

        <?php if ($test_url): ?>
            <?php
            // Parse the URL
            $full_url = home_url($test_url);
            $url_parts = parse_url($full_url);
            $path = $url_parts['path'] ?? '/';
            
            // Try to find the page by path
            $slug = trim($path, '/');
            $slug = str_replace(trim(parse_url(home_url(), PHP_URL_PATH), '/'), '', $slug);
            $slug = trim($slug, '/');
            
            echo '<div class="result">';
            echo '<h3>🔍 URL Analysis</h3>';
            echo '<p><strong>Testing URL:</strong> <code>' . esc_html($test_url) . '</code></p>';
            echo '<p><strong>Full URL:</strong> <code>' . esc_html($full_url) . '</code></p>';
            echo '<p><strong>Extracted slug:</strong> <code>' . esc_html($slug) . '</code></p>';
            echo '</div>';
            
            // Find page by slug
            $page = get_page_by_path($slug, OBJECT, 'page');
            
            if (!$page) {
                // Try without any URL prefix
                $parts = explode('/', $slug);
                $page = get_page_by_path(end($parts), OBJECT, 'page');
            }
            
            if ($page) {
                echo '<div class="result alert-success">';
                echo '<h3>✅ Page Found!</h3>';
                echo '<p><strong>Post ID:</strong> <code>' . $page->ID . '</code></p>';
                echo '<p><strong>Title:</strong> ' . esc_html($page->post_title) . '</p>';
                echo '<p><strong>Slug:</strong> <code>' . esc_html($page->post_name) . '</code></p>';
                echo '<p><strong>Status:</strong> ' . esc_html($page->post_status) . '</p>';
                echo '<p><strong>Actual URL:</strong> <a href="' . get_permalink($page->ID) . '" target="_blank" style="color: #667eea;">' . get_permalink($page->ID) . '</a></p>';
                echo '</div>';
                
                // Check for ProBuilder data
                $probuilder_data = get_post_meta($page->ID, '_probuilder_data', true);
                
                if (!empty($probuilder_data)) {
                    $element_count = is_array($probuilder_data) ? count($probuilder_data) : 0;
                    
                    echo '<div class="result alert-success">';
                    echo '<h3>🎨 ProBuilder Data</h3>';
                    echo '<p><strong>Status:</strong> <span style="color: #22c55e;">✓ ProBuilder Active</span></p>';
                    echo '<p><strong>Elements:</strong> ' . $element_count . '</p>';
                    echo '<p><strong>Data Type:</strong> ' . gettype($probuilder_data) . '</p>';
                    
                    if ($element_count > 0 && is_array($probuilder_data)) {
                        echo '<p><strong>First Element:</strong> ' . (isset($probuilder_data[0]['widgetType']) ? esc_html($probuilder_data[0]['widgetType']) : 'unknown') . '</p>';
                    }
                    
                    echo '</div>';
                } else {
                    echo '<div class="result alert-error">';
                    echo '<h3>⚠️ No ProBuilder Data</h3>';
                    echo '<p>This page has no ProBuilder content saved. It will show the default theme template.</p>';
                    echo '</div>';
                }
                
                // Check for duplicate slugs
                global $wpdb;
                $duplicates = $wpdb->get_results($wpdb->prepare(
                    "SELECT ID, post_title FROM $wpdb->posts WHERE post_name = %s AND post_type = 'page' AND post_status = 'publish' AND ID != %d",
                    $page->post_name,
                    $page->ID
                ));
                
                if (!empty($duplicates)) {
                    echo '<div class="result alert-error">';
                    echo '<h3>🚨 DUPLICATE SLUG FOUND!</h3>';
                    echo '<p>Other pages are using the same URL slug "<strong>' . esc_html($page->post_name) . '</strong>". This causes all these URLs to show the same content!</p>';
                    echo '<ul style="margin-left: 20px; line-height: 1.8;">';
                    foreach ($duplicates as $dup) {
                        echo '<li>Post #' . $dup->ID . ' - "' . esc_html($dup->post_title) . '"</li>';
                    }
                    echo '</ul>';
                    echo '<p><strong>Solution:</strong> <a href="' . home_url('/wp-content/plugins/probuilder/fix-duplicate-slugs.php') . '" style="color: #ef4444; font-weight: 600;">Fix Duplicate Slugs →</a></p>';
                    echo '</div>';
                }
                
            } else {
                echo '<div class="result alert-error">';
                echo '<h3>❌ Page Not Found</h3>';
                echo '<p>No page exists with this URL. WordPress will show:</p>';
                echo '<ul style="margin-left: 20px; line-height: 1.8;">';
                echo '<li>The homepage (if configured)</li>';
                echo '<li>OR a 404 error page</li>';
                echo '</ul>';
                echo '</div>';
                
                // Show all pages
                echo '<div class="result">';
                echo '<h3>📄 Available Pages:</h3>';
                $all_pages = get_posts([
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ]);
                
                if (!empty($all_pages)) {
                    echo '<ul style="margin-left: 20px; line-height: 1.8;">';
                    foreach ($all_pages as $p) {
                        $has_pb = !empty(get_post_meta($p->ID, '_probuilder_data', true));
                        echo '<li>';
                        echo '<strong>' . esc_html($p->post_title) . '</strong> ';
                        echo '<code>' . esc_html($p->post_name) . '</code> ';
                        if ($has_pb) {
                            echo '<span style="color: #22c55e;">[ProBuilder]</span> ';
                        }
                        echo '<a href="' . get_permalink($p->ID) . '" target="_blank" style="color: #667eea;">[View]</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No published pages found.</p>';
                }
                echo '</div>';
            }
            ?>
            
            <div class="result">
                <h3>🔧 Quick Actions</h3>
                <p>
                    <a href="<?php echo home_url('/wp-content/plugins/probuilder/diagnose-url-issue.php'); ?>" style="color: #667eea; font-weight: 600;">Run Full Diagnostic →</a><br>
                    <a href="<?php echo home_url('/wp-content/plugins/probuilder/fix-duplicate-slugs.php'); ?>" style="color: #667eea; font-weight: 600;">Fix Duplicate Slugs →</a><br>
                    <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" style="color: #667eea; font-weight: 600;">View All Pages →</a>
                </p>
            </div>
            
        <?php else: ?>
            <div class="result">
                <h3>ℹ️ How to Use This Tool</h3>
                <p>Enter a page URL in the form above to see:</p>
                <ul style="margin-left: 20px; line-height: 1.8;">
                    <li>Which page WordPress is actually loading for that URL</li>
                    <li>If the page has ProBuilder data</li>
                    <li>If there are duplicate slugs causing conflicts</li>
                    <li>What content should be displayed</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

