<?php
/**
 * Count all registered ProBuilder widgets
 * Access via: /wp-content/plugins/probuilder/COUNT_WIDGETS.php
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<!DOCTYPE html><html><head><title>ProBuilder Widget Count</title>";
echo "<style>body{font-family:Arial;padding:40px;max-width:1200px;margin:0 auto}h1{color:#0073aa}table{width:100%;border-collapse:collapse;margin-top:20px}th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd}th{background:#f5f5f5;font-weight:600}.count{font-size:48px;color:#4caf50;font-weight:bold}.category{background:#0073aa;color:white;padding:8px 16px;border-radius:4px;display:inline-block;margin:5px}</style>";
echo "</head><body>";

echo "<h1>🎯 ProBuilder Widget Counter</h1>";

// Initialize ProBuilder
if (class_exists('ProBuilder_Widgets_Manager')) {
    $manager = ProBuilder_Widgets_Manager::instance();
    $widgets = $manager->get_registered_widgets();
    
    echo "<div class='count'>" . count($widgets) . " WIDGETS</div>";
    echo "<h2>Registered Widgets:</h2>";
    
    // Group by category
    $by_category = [];
    foreach ($widgets as $widget) {
        $categories = $widget->get_categories();
        $cat = is_array($categories) ? $categories[0] : 'other';
        if (!isset($by_category[$cat])) {
            $by_category[$cat] = [];
        }
        $by_category[$cat][] = $widget;
    }
    
    echo "<div style='margin:20px 0'>";
    foreach ($by_category as $cat => $w) {
        echo "<span class='category'>" . ucfirst($cat) . ": " . count($w) . "</span> ";
    }
    echo "</div>";
    
    echo "<table>";
    echo "<thead><tr><th>#</th><th>Widget Name</th><th>Title</th><th>Category</th></tr></thead><tbody>";
    
    $i = 1;
    foreach ($widgets as $widget) {
        $categories = $widget->get_categories();
        $cat = is_array($categories) ? $categories[0] : 'other';
        
        echo "<tr>";
        echo "<td>" . $i++ . "</td>";
        echo "<td><code>" . $widget->get_name() . "</code></td>";
        echo "<td>" . $widget->get_title() . "</td>";
        echo "<td>" . ucfirst($cat) . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    
    echo "<h2 style='margin-top:40px'>✅ Summary</h2>";
    echo "<ul style='font-size:18px;line-height:2'>";
    echo "<li>Total Widgets: <strong>" . count($widgets) . "</strong></li>";
    foreach ($by_category as $cat => $w) {
        echo "<li>" . ucfirst($cat) . ": <strong>" . count($w) . "</strong></li>";
    }
    echo "</ul>";
    
    echo "<p style='margin-top:40px;padding:20px;background:#d4edda;border-left:4px solid #28a745;font-size:16px'><strong>✅ ProBuilder has " . count($widgets) . " widgets registered and working!</strong></p>";
    
} else {
    echo "<p style='color:red;font-size:20px'>❌ ProBuilder_Widgets_Manager class not found!</p>";
    echo "<p>This means ProBuilder hasn't been initialized properly.</p>";
}

echo "<p style='margin-top:40px;color:#666;font-size:14px'>Generated: " . date('Y-m-d H:i:s') . "</p>";
echo "<p><a href='/wp-admin/' style='color:#0073aa'>← Back to WordPress Admin</a></p>";

echo "</body></html>";

