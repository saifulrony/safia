<?php
/**
 * Flush WordPress Permalinks
 * Run this to update rewrite rules after changing custom post types
 */

// Load WordPress
require_once('wp-load.php');

// Check permissions
if (!current_user_can('manage_options')) {
    die('❌ You must be logged in as administrator to run this script.');
}

echo '<style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; background: #f9fafb; }
    .container { max-width: 700px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
    h1 { color: #1f2937; margin-bottom: 10px; }
    .success { padding: 20px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 20px 0; }
    .info { padding: 20px; background: #dbeafe; border-left: 4px solid #3b82f6; color: #1e40af; border-radius: 6px; margin: 20px 0; }
    .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
    .button:hover { background: #5568d3; }
    code { background: #f3f4f6; padding: 2px 6px; border-radius: 3px; font-size: 14px; color: #dc2626; }
</style>';

echo '<div class="container">';
echo '<h1>🔄 Flush WordPress Permalinks</h1>';
echo '<p>This will update WordPress rewrite rules to recognize the new custom post type settings.</p>';

// Flush rewrite rules
flush_rewrite_rules();

// Clear the flush option to allow it to run again if needed
delete_option('probuilder_parts_flushed');

echo '<div class="success">';
echo '<strong>✅ Permalinks Flushed Successfully!</strong><br>';
echo 'WordPress rewrite rules have been updated.';
echo '</div>';

echo '<div class="info">';
echo '<strong>📌 What This Did:</strong><br>';
echo '<ul style="margin: 10px 0 0 20px; line-height: 1.8;">';
echo '<li>Updated WordPress rewrite rules</li>';
echo '<li>Headers, Footers, Sliders, and Sidebars are now non-public</li>';
echo '<li>They cannot be accessed directly via URL</li>';
echo '<li>Attempting to visit their URLs will show 404 error</li>';
echo '<li>They can only be used via shortcodes</li>';
echo '</ul>';
echo '</div>';

echo '<h2 style="margin-top: 30px; color: #1f2937;">✅ Test It Now</h2>';
echo '<p>Try visiting your header URL:</p>';

// Get a test header
$test_header = get_posts([
    'post_type' => 'pb_header',
    'posts_per_page' => 1,
    'post_status' => 'publish'
]);

if ($test_header) {
    $header_url = get_permalink($test_header[0]->ID);
    $header_id = $test_header[0]->ID;
    
    echo '<div style="background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0;">';
    echo '<strong>Header URL (should show 404):</strong><br>';
    echo '<a href="' . $header_url . '" target="_blank" style="color: #667eea;">' . $header_url . '</a>';
    echo '</div>';
    
    echo '<div style="background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0;">';
    echo '<strong>Correct way to use it:</strong><br>';
    echo '<code>[header id="' . $header_id . '"]</code>';
    echo '</div>';
} else {
    echo '<p style="color: #6b7280;"><em>No headers found. Create one first to test.</em></p>';
}

echo '<h2 style="margin-top: 30px; color: #1f2937;">📋 How to Use Custom Parts</h2>';

echo '<div style="background: #f9fafb; padding: 20px; border-radius: 6px; border: 1px solid #e5e7eb;">';

echo '<h3 style="color: #667eea; margin-top: 0;">📌 Headers</h3>';
echo '<p>Use in pages/posts: <code>[header id="123"]</code></p>';
echo '<p>Use in theme files: <code>&lt;?php echo do_shortcode(\'[header id="123"]\'); ?&gt;</code></p>';

echo '<h3 style="color: #667eea; margin-top: 20px;">📎 Footers</h3>';
echo '<p>Use in pages/posts: <code>[footer id="456"]</code></p>';
echo '<p>Use in theme files: <code>&lt;?php echo do_shortcode(\'[footer id="456"]\'); ?&gt;</code></p>';

echo '<h3 style="color: #667eea; margin-top: 20px;">🎬 Sliders</h3>';
echo '<p>Use in pages/posts: <code>[slider id="789"]</code></p>';
echo '<p>Use in theme files: <code>&lt;?php echo do_shortcode(\'[slider id="789"]\'); ?&gt;</code></p>';

echo '<h3 style="color: #667eea; margin-top: 20px;">📋 Sidebars</h3>';
echo '<p>Use in pages/posts: <code>[sidebar id="101"]</code></p>';
echo '<p>Use in theme files: <code>&lt;?php echo do_shortcode(\'[sidebar id="101"]\'); ?&gt;</code></p>';

echo '</div>';

echo '<div style="margin-top: 30px; text-align: center;">';
echo '<a href="' . admin_url('admin.php?page=probuilder-parts') . '" class="button">Go to ProBuilder Dashboard</a>';
echo '<a href="' . admin_url('edit.php?post_type=pb_header') . '" class="button" style="background: #6b7280;">View Headers</a>';
echo '<a href="' . admin_url('edit.php?post_type=pb_footer') . '" class="button" style="background: #6b7280;">View Footers</a>';
echo '</div>';

echo '<div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">';
echo '<strong>Note:</strong> If headers/footers are still accessible via URL, try:';
echo '<ol style="margin: 10px 0 0 20px; line-height: 1.8;">';
echo '<li>Go to <strong>Settings → Permalinks</strong></li>';
echo '<li>Click <strong>Save Changes</strong> (don\'t change anything)</li>';
echo '<li>This forces WordPress to regenerate all rewrite rules</li>';
echo '</ol>';
echo '</div>';

echo '</div>';

