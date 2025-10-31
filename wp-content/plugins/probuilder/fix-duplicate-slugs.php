<?php
/**
 * Quick Fix: Remove Duplicate URL Slugs
 * Run this script once to fix all existing duplicate slugs
 */

// Load WordPress
require_once('../../../wp-load.php');

// Must be admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be logged in as administrator.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Duplicate URL Slugs - ProBuilder</title>
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
            max-width: 900px;
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
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .result {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .result h3 {
            color: #344047;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .result ul {
            margin-left: 20px;
            line-height: 1.8;
        }
        .result li {
            margin-bottom: 10px;
        }
        .code {
            background: #1e293b;
            color: #22c55e;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin-top: 10px;
        }
        .progress {
            margin: 20px 0;
        }
        .progress-bar {
            height: 30px;
            background: #e5e7eb;
            border-radius: 15px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Duplicate URL Slugs</h1>
        <p class="subtitle">Automatically fix pages with duplicate URLs</p>

        <?php
        if (!isset($_GET['action']) || $_GET['action'] !== 'fix') {
            // Show scan results
            echo '<div class="alert alert-info">';
            echo '<strong>🔍 Scanning for duplicate URL slugs...</strong>';
            echo '</div>';

            // Get all published pages
            $args = [
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'ID',
                'order' => 'ASC'
            ];
            
            $all_pages = get_posts($args);
            
            // Find duplicates
            $slugs = [];
            $duplicates = [];
            
            foreach ($all_pages as $page) {
                $slug = $page->post_name;
                if (isset($slugs[$slug])) {
                    if (!isset($duplicates[$slug])) {
                        $duplicates[$slug] = [$slugs[$slug]];
                    }
                    $duplicates[$slug][] = $page->ID;
                } else {
                    $slugs[$slug] = $page->ID;
                }
            }
            
            if (empty($duplicates)) {
                echo '<div class="alert alert-success">';
                echo '<strong>✅ No duplicate URL slugs found!</strong><br>';
                echo 'All your pages have unique URLs. No action needed.';
                echo '</div>';
                
                echo '<a href="' . admin_url('edit.php?post_type=page') . '" class="btn">View All Pages</a>';
            } else {
                echo '<div class="alert alert-error">';
                echo '<strong>🚨 Found ' . count($duplicates) . ' duplicate URL slug(s)!</strong><br>';
                echo 'These pages are using the same URL, causing different URLs to show the same content.';
                echo '</div>';
                
                echo '<div class="result">';
                echo '<h3>Duplicate Slugs Found:</h3>';
                echo '<ul>';
                
                $total_affected = 0;
                foreach ($duplicates as $slug => $post_ids) {
                    $affected = count($post_ids);
                    $total_affected += $affected;
                    
                    echo '<li>';
                    echo '<strong>"' . esc_html($slug) . '"</strong> is used by <strong>' . $affected . ' pages</strong>:';
                    echo '<ul style="margin-top: 5px;">';
                    
                    foreach ($post_ids as $pid) {
                        $p = get_post($pid);
                        echo '<li>';
                        echo 'Post #' . $pid . ' - "' . esc_html($p->post_title) . '" ';
                        echo '<a href="' . get_permalink($pid) . '" target="_blank" style="color: #667eea;">[View]</a> ';
                        echo '<a href="' . admin_url('post.php?post=' . $pid . '&action=edit') . '" style="color: #667eea;">[Edit]</a>';
                        echo '</li>';
                    }
                    
                    echo '</ul>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
                
                echo '<div class="alert alert-warning">';
                echo '<strong>⚠️ What will happen when you click "Fix Now":</strong><br>';
                echo '<ul style="margin-top: 10px; margin-left: 20px;">';
                echo '<li>First page with each slug will keep its original URL</li>';
                echo '<li>Other pages will get numbered URLs (e.g., page-2, page-3)</li>';
                echo '<li>WordPress rewrite rules will be flushed</li>';
                echo '<li>All caches will be cleared</li>';
                echo '<li>' . ($total_affected - count($duplicates)) . ' page(s) will be renamed</li>';
                echo '</ul>';
                echo '</div>';
                
                echo '<form method="get" style="margin-top: 20px;">';
                echo '<input type="hidden" name="action" value="fix">';
                echo '<button type="submit" class="btn btn-danger">🔧 Fix All Duplicate Slugs Now</button>';
                echo '</form>';
                
                echo '<div style="margin-top: 20px;">';
                echo '<a href="diagnose-url-issue.php" class="btn">View Detailed Diagnostic</a>';
                echo '</div>';
            }
        } else {
            // Perform the fix
            echo '<div class="alert alert-info">';
            echo '<strong>🔧 Fixing duplicate slugs...</strong>';
            echo '</div>';
            
            // Get all published pages
            $args = [
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'ID',
                'order' => 'ASC'
            ];
            
            $all_pages = get_posts($args);
            
            // Find duplicates
            $slugs = [];
            $duplicates = [];
            
            foreach ($all_pages as $page) {
                $slug = $page->post_name;
                if (isset($slugs[$slug])) {
                    if (!isset($duplicates[$slug])) {
                        $duplicates[$slug] = [$slugs[$slug]];
                    }
                    $duplicates[$slug][] = $page->ID;
                } else {
                    $slugs[$slug] = $page->ID;
                }
            }
            
            $fixed_count = 0;
            $error_count = 0;
            
            echo '<div class="result">';
            echo '<h3>Fix Results:</h3>';
            
            foreach ($duplicates as $slug => $post_ids) {
                echo '<div style="margin-bottom: 20px;">';
                echo '<strong>Fixing slug "' . esc_html($slug) . '":</strong>';
                echo '<ul style="margin-top: 10px;">';
                
                // Keep first page, rename others
                $first_id = array_shift($post_ids);
                $first_post = get_post($first_id);
                
                echo '<li style="color: #22c55e;">';
                echo '✓ Post #' . $first_id . ' ("' . esc_html($first_post->post_title) . '") keeps original slug: <code>' . $slug . '</code>';
                echo '</li>';
                
                // Rename duplicates
                $counter = 2;
                foreach ($post_ids as $pid) {
                    $new_slug = $slug . '-' . $counter;
                    
                    // Ensure unique
                    while (get_page_by_path($new_slug)) {
                        $counter++;
                        $new_slug = $slug . '-' . $counter;
                    }
                    
                    $result = wp_update_post([
                        'ID' => $pid,
                        'post_name' => $new_slug
                    ]);
                    
                    $post = get_post($pid);
                    
                    if (!is_wp_error($result)) {
                        echo '<li style="color: #22c55e;">';
                        echo '✓ Post #' . $pid . ' ("' . esc_html($post->post_title) . '") renamed to: <code>' . $new_slug . '</code><br>';
                        echo '<small>New URL: <a href="' . get_permalink($pid) . '" target="_blank" style="color: #667eea;">' . get_permalink($pid) . '</a></small>';
                        echo '</li>';
                        $fixed_count++;
                    } else {
                        echo '<li style="color: #ef4444;">';
                        echo '✗ Failed to fix Post #' . $pid . ': ' . $result->get_error_message();
                        echo '</li>';
                        $error_count++;
                    }
                    
                    $counter++;
                }
                
                echo '</ul>';
                echo '</div>';
            }
            
            echo '</div>';
            
            // Flush rewrite rules
            flush_rewrite_rules(true);
            echo '<div class="alert alert-success">';
            echo '✓ Flushed WordPress rewrite rules';
            echo '</div>';
            
            // Clear object cache
            wp_cache_flush();
            echo '<div class="alert alert-success">';
            echo '✓ Cleared WordPress cache';
            echo '</div>';
            
            // Summary
            if ($error_count === 0) {
                echo '<div class="alert alert-success">';
                echo '<strong>🎉 All Done! Fixed ' . $fixed_count . ' duplicate slug(s)!</strong><br>';
                echo 'All your pages now have unique URLs. The issue is resolved!';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning">';
                echo '<strong>⚠️ Partially Complete</strong><br>';
                echo 'Fixed: ' . $fixed_count . ' page(s)<br>';
                echo 'Errors: ' . $error_count . ' page(s)<br>';
                echo 'Please check the pages with errors manually.';
                echo '</div>';
            }
            
            echo '<div style="margin-top: 20px;">';
            echo '<a href="?" class="btn">↻ Scan Again</a> ';
            echo '<a href="diagnose-url-issue.php" class="btn">View Diagnostic</a> ';
            echo '<a href="' . admin_url('edit.php?post_type=page') . '" class="btn">View All Pages</a>';
            echo '</div>';
        }
        ?>

        <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb;">
            <h3 style="color: #344047; margin-bottom: 15px;">📚 What Was Fixed?</h3>
            <p style="line-height: 1.8; color: #64748b;">
                When multiple pages have the same URL slug (e.g., "my-page"), WordPress can only show one of them. 
                This causes all URLs with that slug to display the same content. This script renames duplicate slugs 
                by adding numbers (my-page-2, my-page-3, etc.) so each page has a unique URL.
            </p>
            
            <div style="margin-top: 20px;">
                <strong>📖 Read More:</strong>
                <a href="DUPLICATE_URL_FIX.md" style="color: #667eea; margin-left: 10px;">Complete Fix Documentation</a>
            </div>
        </div>
    </div>
</body>
</html>

