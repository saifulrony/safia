<?php
/**
 * Test WoodMart Template Structure
 * Run this to verify template has children
 */

require_once 'wp-load.php';

// Load templates library
require_once 'wp-content/plugins/probuilder/includes/class-templates-library.php';

$templates = ProBuilder_Templates_Library::instance();
$template_data = $templates->get_template_data('woodmart-home');

echo "=== WOODMART TEMPLATE STRUCTURE ===\n\n";
echo "Total Elements: " . count($template_data) . "\n\n";

foreach ($template_data as $index => $element) {
    echo "[$index] " . $element['widgetType'] . "\n";
    
    if (isset($element['children']) && !empty($element['children'])) {
        echo "  ✅ Has " . count($element['children']) . " children:\n";
        foreach ($element['children'] as $childIndex => $child) {
            echo "    [$childIndex] " . $child['widgetType'] . "\n";
            if (isset($child['settings']['title'])) {
                echo "       Title: " . $child['settings']['title'] . "\n";
            }
        }
    } else {
        echo "  ⚠️ No children\n";
    }
    echo "\n";
}

echo "\n=== CONTAINERS/GRIDS WITH CHILDREN ===\n\n";

foreach ($template_data as $index => $element) {
    if (in_array($element['widgetType'], ['container', 'grid-layout'])) {
        $childCount = isset($element['children']) ? count($element['children']) : 0;
        $status = $childCount > 0 ? '✅' : '❌';
        echo "$status " . $element['widgetType'] . " - " . $childCount . " children\n";
        
        if ($childCount > 0) {
            echo "   Columns: " . ($element['settings']['columns'] ?? 'N/A') . "\n";
            echo "   Children types: ";
            foreach ($element['children'] as $child) {
                echo $child['widgetType'] . ", ";
            }
            echo "\n";
        }
        echo "\n";
    }
}

echo "\n✅ TEST COMPLETE!\n";

