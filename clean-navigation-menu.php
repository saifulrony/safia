<?php
/**
 * Clean Navigation Menu - Remove Headers/Footers/Sliders
 * 
 * This script removes all headers, footers, sliders, and sidebars from navigation menus
 */

// Load WordPress
require_once('wp-load.php');

// Check permissions
if (!current_user_can('edit_theme_options')) {
    die('❌ You must be logged in as administrator to run this script.');
}

echo '<style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; background: #f9fafb; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
    h1 { color: #1f2937; margin-bottom: 10px; }
    .success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
    .warning { padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; color: #92400e; border-radius: 6px; margin: 15px 0; }
    .info { padding: 15px; background: #dbeafe; border-left: 4px solid #3b82f6; color: #1e40af; border-radius: 6px; margin: 15px 0; }
    .error { padding: 15px; background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; border-radius: 6px; margin: 15px 0; }
    .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
    .button:hover { background: #5568d3; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
    table th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
    table td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
    code { background: #f3f4f6; padding: 2px 6px; border-radius: 3px; font-size: 14px; color: #dc2626; }
</style>';

echo '<div class="container">';
echo '<h1>🧹 Clean Navigation Menu</h1>';
echo '<p>Removing headers, footers, sliders, and sidebars from all navigation menus...</p>';

// Get all navigation menus
$menus = wp_get_nav_menus();

if (empty($menus)) {
    echo '<div class="warning"><strong>⚠️ No Menus Found</strong><br>You don\'t have any navigation menus yet.</div>';
    echo '</div>';
    exit;
}

$total_removed = 0;
$custom_part_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];

echo '<h2 style="margin-top: 30px; color: #1f2937;">Processing Menus...</h2>';

foreach ($menus as $menu) {
    echo '<div class="info">';
    echo '<strong>📋 Menu: ' . esc_html($menu->name) . '</strong> (ID: ' . $menu->term_id . ')<br>';
    
    // Get all menu items
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    
    if (empty($menu_items)) {
        echo '<em style="color: #6b7280;">No items in this menu</em>';
        echo '</div>';
        continue;
    }
    
    $removed_count = 0;
    $items_to_remove = [];
    
    // Find menu items that are custom parts
    foreach ($menu_items as $item) {
        // Check if this menu item is a custom part
        if (in_array($item->object, $custom_part_types)) {
            $items_to_remove[] = [
                'id' => $item->ID,
                'title' => $item->title,
                'type' => $item->object
            ];
        }
    }
    
    // Remove the items
    if (!empty($items_to_remove)) {
        echo '<ul style="margin: 10px 0 0 20px; line-height: 1.8;">';
        foreach ($items_to_remove as $item) {
            $result = wp_delete_post($item['id'], true);
            if ($result) {
                $type_name = str_replace('pb_', '', $item['type']);
                echo '<li>❌ Removed: <strong>' . esc_html($item['title']) . '</strong> (' . ucfirst($type_name) . ')</li>';
                $removed_count++;
                $total_removed++;
            }
        }
        echo '</ul>';
    } else {
        echo '<em style="color: #10b981;">✅ No custom parts found in this menu</em>';
    }
    
    echo '</div>';
}

// Summary
if ($total_removed > 0) {
    echo '<div class="success">';
    echo '<strong>✅ Cleanup Complete!</strong><br>';
    echo 'Removed <strong>' . $total_removed . '</strong> header/footer/slider/sidebar item(s) from navigation menus.';
    echo '</div>';
} else {
    echo '<div class="success">';
    echo '<strong>✅ All Clean!</strong><br>';
    echo 'No headers, footers, sliders, or sidebars found in navigation menus.';
    echo '</div>';
}

// Next steps
echo '<div class="info">';
echo '<strong>📍 Next Steps:</strong><br>';
echo '<ol style="margin: 10px 0 0 20px; line-height: 1.8;">';
echo '<li>Go to <strong>Appearance → Menus</strong> to verify</li>';
echo '<li>Your navigation menus should now be clean</li>';
echo '<li>Headers/footers/sliders won\'t appear in "Add menu items" panel anymore</li>';
echo '</ol>';
echo '</div>';

// Flush rewrite rules
flush_rewrite_rules();
delete_option('probuilder_parts_flushed');

echo '<div class="info" style="background: #f3f4f6; border-left-color: #667eea;">';
echo '<strong>🔄 Permalinks Flushed</strong><br>';
echo 'WordPress rewrite rules have been updated.';
echo '</div>';

// Show where to manage menus
echo '<h2 style="margin-top: 30px; color: #1f2937;">Manage Your Menus</h2>';
echo '<div style="text-align: center; margin: 30px 0;">';
echo '<a href="' . admin_url('nav-menus.php') . '" class="button">Go to Appearance → Menus</a>';
echo '<a href="' . admin_url('admin.php?page=probuilder-parts') . '" class="button" style="background: #6b7280;">ProBuilder Dashboard</a>';
echo '</div>';

// Explanation
echo '<div style="background: #fef3c7; padding: 20px; border-radius: 6px; border-left: 4px solid #f59e0b; margin-top: 30px;">';
echo '<h3 style="color: #92400e; margin-top: 0;">💡 Why Were These Removed?</h3>';
echo '<p style="color: #92400e; line-height: 1.6;">';
echo 'Headers, footers, sliders, and sidebars are <strong>reusable elements</strong>, not pages. ';
echo 'They should be inserted into pages using shortcodes like <code>[header id="123"]</code>, ';
echo 'not added to navigation menus like regular pages.';
echo '</p>';
echo '</div>';

// How to use them correctly
echo '<h2 style="margin-top: 30px; color: #1f2937;">✅ Correct Usage</h2>';

echo '<div style="background: #f9fafb; padding: 20px; border-radius: 6px; border: 1px solid #e5e7eb;">';

echo '<h3 style="color: #667eea; margin-top: 0;">Headers</h3>';
echo '<p><strong>In content:</strong> <code>[header id="123"]</code></p>';
echo '<p><strong>In theme:</strong> <code>&lt;?php echo do_shortcode(\'[header id="123"]\'); ?&gt;</code></p>';

echo '<h3 style="color: #667eea; margin-top: 20px;">Footers</h3>';
echo '<p><strong>In content:</strong> <code>[footer id="456"]</code></p>';
echo '<p><strong>In theme:</strong> <code>&lt;?php echo do_shortcode(\'[footer id="456"]\'); ?&gt;</code></p>';

echo '<h3 style="color: #667eea; margin-top: 20px;">Sliders</h3>';
echo '<p><strong>In content:</strong> <code>[slider id="789"]</code></p>';
echo '<p><strong>In ProBuilder:</strong> Use the Saved Part widget</p>';

echo '<h3 style="color: #667eea; margin-top: 20px;">Sidebars</h3>';
echo '<p><strong>In content:</strong> <code>[sidebar id="101"]</code></p>';
echo '<p><strong>In theme:</strong> <code>&lt;?php echo do_shortcode(\'[sidebar id="101"]\'); ?&gt;</code></p>';

echo '</div>';

echo '</div>';

