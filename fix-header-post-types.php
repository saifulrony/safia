<?php
/**
 * Fix Header/Footer/Slider Post Types
 * 
 * Converts incorrectly saved pages back to their proper post types
 */

// Load WordPress
require_once('wp-load.php');

// Check permissions
if (!current_user_can('manage_options')) {
    die('❌ You must be logged in as administrator to run this script.');
}

echo '<style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; background: #f9fafb; }
    .container { max-width: 900px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
    h1 { color: #1f2937; margin-bottom: 10px; }
    .success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
    .warning { padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; color: #92400e; border-radius: 6px; margin: 15px 0; }
    .info { padding: 15px; background: #dbeafe; border-left: 4px solid #3b82f6; color: #1e40af; border-radius: 6px; margin: 15px 0; }
    .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
    table th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
    table td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
    code { background: #f3f4f6; padding: 2px 6px; border-radius: 3px; font-size: 14px; }
</style>';

echo '<div class="container">';
echo '<h1>🔧 Fix Header/Footer/Slider Post Types</h1>';
echo '<p>Checking for headers, footers, and sliders that were incorrectly saved as pages...</p>';

// Find pages that look like they should be custom parts
$pages = get_posts([
    'post_type' => 'page',
    'posts_per_page' => -1,
    'post_status' => ['publish', 'draft', 'pending', 'private'],
]);

$fixes = [
    'headers' => [],
    'footers' => [],
    'sliders' => [],
    'sidebars' => [],
];

// Patterns to detect what type each page should be
foreach ($pages as $page) {
    $title = strtolower($page->post_title);
    $slug = $page->post_name;
    
    // Check if it looks like a header
    if (
        preg_match('/\bheader\b/i', $title) || 
        preg_match('/\bheader\b/i', $slug) ||
        preg_match('/^new-header-[a-f0-9]+$/i', $slug)
    ) {
        $fixes['headers'][] = $page;
    }
    // Check if it looks like a footer
    elseif (
        preg_match('/\bfooter\b/i', $title) || 
        preg_match('/\bfooter\b/i', $slug) ||
        preg_match('/^new-footer-[a-f0-9]+$/i', $slug)
    ) {
        $fixes['footers'][] = $page;
    }
    // Check if it looks like a slider
    elseif (
        preg_match('/\bslider\b/i', $title) || 
        preg_match('/\bslider\b/i', $slug) ||
        preg_match('/^new-slider-[a-f0-9]+$/i', $slug)
    ) {
        $fixes['sliders'][] = $page;
    }
    // Check if it looks like a sidebar
    elseif (
        preg_match('/\bsidebar\b/i', $title) || 
        preg_match('/\bsidebar\b/i', $slug) ||
        preg_match('/^new-sidebar-[a-f0-9]+$/i', $slug)
    ) {
        $fixes['sidebars'][] = $page;
    }
}

$total_fixed = 0;

// Display findings
echo '<h2 style="margin-top: 30px; color: #1f2937;">🔍 Found Issues:</h2>';

if (empty($fixes['headers']) && empty($fixes['footers']) && empty($fixes['sliders']) && empty($fixes['sidebars'])) {
    echo '<div class="success">';
    echo '<strong>✅ All Good!</strong><br>';
    echo 'No pages found that look like they should be headers, footers, or sliders.';
    echo '</div>';
} else {
    // Show what will be fixed
    foreach ($fixes as $type => $items) {
        if (empty($items)) continue;
        
        $singular = rtrim($type, 's');
        $post_type = 'pb_' . $singular;
        
        echo '<div class="warning">';
        echo '<strong>⚠️ Found ' . count($items) . ' ' . ucfirst($type) . ':</strong>';
        echo '<table style="margin-top: 10px;">';
        echo '<tr><th>Title</th><th>Slug</th><th>Status</th><th>Action</th></tr>';
        
        foreach ($items as $item) {
            echo '<tr>';
            echo '<td>' . esc_html($item->post_title) . '</td>';
            echo '<td><code>' . esc_html($item->post_name) . '</code></td>';
            echo '<td>' . ucfirst($item->post_status) . '</td>';
            echo '<td><span style="color: #3b82f6;">Will convert to ' . $post_type . '</span></td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</div>';
    }
    
    // Fix button
    if (!isset($_GET['confirm'])) {
        echo '<div class="info" style="text-align: center; padding: 30px;">';
        echo '<p><strong>Ready to fix these issues?</strong></p>';
        echo '<a href="?confirm=yes" class="button" style="font-size: 16px; padding: 15px 40px;">🔧 Fix All Issues</a>';
        echo '</div>';
        echo '</div>';
        exit;
    }
}

// Perform the fixes
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    echo '<h2 style="margin-top: 30px; color: #1f2937;">🔧 Applying Fixes...</h2>';
    
    foreach ($fixes as $type => $items) {
        if (empty($items)) continue;
        
        $singular = rtrim($type, 's');
        $post_type = 'pb_' . $singular;
        
        echo '<div class="info">';
        echo '<strong>Converting ' . count($items) . ' ' . ucfirst($type) . '...</strong><br>';
        echo '<ul style="margin: 10px 0 0 20px; line-height: 1.8;">';
        
        foreach ($items as $item) {
            $result = wp_update_post([
                'ID' => $item->ID,
                'post_type' => $post_type,
            ], true);
            
            if (!is_wp_error($result)) {
                echo '<li>✅ Converted: <strong>' . esc_html($item->post_title) . '</strong> → ' . $post_type . '</li>';
                $total_fixed++;
            } else {
                echo '<li>❌ Failed: <strong>' . esc_html($item->post_title) . '</strong> - ' . $result->get_error_message() . '</li>';
            }
        }
        
        echo '</ul>';
        echo '</div>';
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Summary
    echo '<div class="success">';
    echo '<strong>🎉 Fix Complete!</strong><br>';
    echo 'Converted <strong>' . $total_fixed . '</strong> page(s) to correct post types.';
    echo '</div>';
    
    // Next steps
    echo '<div class="info">';
    echo '<strong>📍 Next Steps:</strong><br>';
    echo '<ol style="margin: 10px 0 0 20px; line-height: 1.8;">';
    echo '<li>Check <strong>ProBuilder → Headers</strong> to see your headers</li>';
    echo '<li>Check <strong>ProBuilder → Footers</strong> to see your footers</li>';
    echo '<li>Check <strong>ProBuilder → Sliders</strong> to see your sliders</li>';
    echo '<li>These are now proper elements, not pages!</li>';
    echo '<li>They won\'t appear in Pages list anymore</li>';
    echo '</ol>';
    echo '</div>';
}

// Links
echo '<div style="text-align: center; margin: 30px 0;">';
echo '<a href="' . admin_url('edit.php?post_type=pb_header') . '" class="button">View Headers</a>';
echo '<a href="' . admin_url('edit.php?post_type=pb_footer') . '" class="button">View Footers</a>';
echo '<a href="' . admin_url('edit.php?post_type=pb_slider') . '" class="button">View Sliders</a>';
echo '<a href="' . admin_url('admin.php?page=probuilder-parts') . '" class="button" style="background: #6b7280;">ProBuilder Dashboard</a>';
echo '</div>';

echo '</div>';

