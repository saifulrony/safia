<?php
/**
 * Final Widget Fixer - PHP Based, No Syntax Errors
 * Systematically fixes all ProBuilder widgets
 */

$widgets_dir = __DIR__ . '/wp-content/plugins/probuilder/widgets/';
$widgets = glob($widgets_dir . '*.php');

$stats = ['fixed' => 0, 'already_good' => 0, 'manual_needed' => []];

foreach ($widgets as $widget_file) {
    $filename = basename($widget_file);
    
    if ($filename === 'index.php') continue;
    
    $content = file_get_contents($widget_file);
    $original = $content;
    
    // Check if already properly implemented
    $has_wrapper_classes_in_output = (
        preg_match('/esc_attr\(\$wrapper_classes\)/', $content) &&
        preg_match('/class=["\'][^"\']*esc_attr\(\$wrapper_classes\)/', $content)
    );
    
    $has_wrapper_attrs_in_output = preg_match('/\$wrapper_attributes[\'"\s]*\./', $content) ||
                                   preg_match('/\.\s*\$wrapper_attributes/', $content) ||
                                   preg_match('/echo\s+\$wrapper_attributes/', $content);
    
    if ($has_wrapper_classes_in_output && $has_wrapper_attrs_in_output) {
        echo "✓ GOOD: $filename\n";
        $stats['already_good']++;
        continue;
    }
    
    // Find the main wrapper div in render method
    // Strategy: Look for first echo '<div class="probuilder-XXX" after render() function
    
    $render_start = strpos($content, 'protected function render()');
    if ($render_start === false) {
        echo "⚠ NO RENDER: $filename\n";
        $stats['manual_needed'][] = $filename;
        continue;
    }
    
    $render_section = substr($content, $render_start, 5000);
    
    // Pattern: echo '<div class="probuilder-WIDGET_NAME"
    if (preg_match('/(echo\s+[\'"]<div\s+class=[\'"])probuilder-([a-z0-9-]+)([\'"])/', $render_section, $m, PREG_OFFSET_CAPTURE)) {
        $match_pos = $render_start + $m[0][1];
        $match_len = strlen($m[0][0]);
        
        // Extract the full echo statement
        $after_match = substr($content, $match_pos);
        if (preg_match('/echo\s+[\'"][^;]+;/', $after_match, $full_statement)) {
            $statement = $full_statement[0];
            
            // Build replacement
            $new_statement = $statement;
            
            // Replace class="probuilder-XXX" with class="' . esc_attr($wrapper_classes) . ' probuilder-XXX"
            $new_statement = preg_replace(
                '/class=([\'"])probuilder-/',
                'class=$1\' . esc_attr(\$wrapper_classes) . \' probuilder-',
                $new_statement,
                1
            );
            
            // Add wrapper_attributes after class if not present
            if (strpos($new_statement, '$wrapper_attributes') === false) {
                $new_statement = preg_replace(
                    '/(class=[\'"][^\'"]* [\'"])/',
                    '$1 \' . \$wrapper_attributes . \' ',
                    $new_statement,
                    1
                );
            }
            
            // Add/merge inline_styles with style attribute
            if (preg_match('/(style=[\'"])([^\'"]*)([\'"])/', $new_statement, $style_match)) {
                $style_content = $style_match[2];
                $new_style = $style_match[1] . $style_content . '\' . (\$inline_styles ? \' ; \' . \$inline_styles : \'\') . \'' . $style_match[3];
                $new_statement = str_replace($style_match[0], $new_style, $new_statement);
            } else {
                // No style attribute, add one before closing >
                $new_statement = preg_replace(
                    '/(>)([\'"];)$/',
                    ' style="\' . esc_attr(\$inline_styles) . \'"$1$2',
                    $new_statement
                );
            }
            
            // Replace in content
            $content = str_replace($statement, $new_statement, $content);
            
            // Save
            if (!file_exists($widget_file . '.backup_final')) {
                file_put_contents($widget_file . '.backup_final', $original);
            }
            file_put_contents($widget_file, $content);
            
            echo "✓ FIXED: $filename\n";
            $stats['fixed']++;
        } else {
            echo "⚠ COMPLEX: $filename\n";
            $stats['manual_needed'][] = $filename;
        }
    } else {
        echo "⚠ NO PATTERN: $filename\n";
        $stats['manual_needed'][] = $filename;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "SUMMARY:\n";
echo "Already Good: {$stats['already_good']} widgets\n";
echo "Fixed: {$stats['fixed']} widgets\n";
echo "Manual Needed: " . count($stats['manual_needed']) . " widgets\n";
echo "\n";

if (!empty($stats['manual_needed'])) {
    echo "Widgets needing manual review (" . count($stats['manual_needed']) . "):\n";
    foreach ($stats['manual_needed'] as $w) {
        echo "  - $w\n";
    }
}

echo "\nBackups saved with .backup_final extension\n";
echo "Done!\n";

