<?php
/**
 * Force Reload ProBuilder Widgets
 * Visit: http://your-site.com/wp-content/plugins/probuilder/reload-widgets.php
 */

require_once('../../../../wp-load.php');

if (!is_admin() && !current_user_can('manage_options')) {
    die('Access denied');
}

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔄 ProBuilder Widgets Reload</h1>";
echo "<pre>";

echo "1. Checking if ProBuilder is active...\n";
if (class_exists('ProBuilder')) {
    echo "   ✓ ProBuilder active\n\n";
} else {
    echo "   ✗ ProBuilder NOT active!\n";
    die();
}

echo "2. Checking Widgets Manager...\n";
if (class_exists('ProBuilder_Widgets_Manager')) {
    echo "   ✓ Widgets Manager exists\n\n";
} else {
    echo "   ✗ Widgets Manager NOT found!\n";
    die();
}

echo "3. Checking WooCommerce widget classes...\n";
$widgets_to_check = [
    'ProBuilder_Widget_Woo_Products',
    'ProBuilder_Widget_Woo_Categories',
    'ProBuilder_Widget_Woo_Cart',
];

foreach ($widgets_to_check as $widget) {
    if (class_exists($widget)) {
        echo "   ✓ {$widget} exists\n";
    } else {
        echo "   ✗ {$widget} NOT FOUND!\n";
    }
}

echo "\n4. Getting registered widgets...\n";
$manager = ProBuilder_Widgets_Manager::instance();
$widgets = $manager->get_widgets();

echo "   Total registered: " . count($widgets) . " widgets\n\n";

echo "5. Checking if WooCommerce widgets are registered...\n";
$woo_widgets = ['woo-products', 'woo-categories', 'woo-cart'];
foreach ($woo_widgets as $widget_name) {
    $found = false;
    foreach ($widgets as $widget) {
        if ($widget->get_name() === $widget_name) {
            $found = true;
            break;
        }
    }
    if ($found) {
        echo "   ✓ '{$widget_name}' is registered\n";
    } else {
        echo "   ✗ '{$widget_name}' NOT registered\n";
    }
}

echo "\n</pre>";

echo "<hr>";
echo "<h2>📝 What To Do:</h2>";
echo "<ul>";
echo "<li><strong>If all widgets show ✓</strong> → Clear browser cache and hard refresh (Ctrl+Shift+F5)</li>";
echo "<li><strong>If any show ✗</strong> → Deactivate and Reactivate ProBuilder plugin</li>";
echo "</ul>";

echo "<hr>";
echo "<p><a href='/wp-admin/plugins.php' style='padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 3px;'>Go to Plugins Page</a></p>";

