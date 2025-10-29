<?php
/**
 * Widget Test Script - Verify all widgets are loading
 * 
 * Run from command line:
 * php test-widgets.php
 * 
 * Or access via browser:
 * http://your-site.com/wp-content/plugins/probuilder/test-widgets.php
 */

// Load WordPress
if (!defined('ABSPATH')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';
}

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>ProBuilder Widget Test</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .widget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        .widget-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .widget-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .widget-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .widget-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        .widget-category {
            display: inline-block;
            padding: 3px 10px;
            background: #f0f0f0;
            border-radius: 12px;
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .category-layout { background: #e3f2fd; color: #1976d2; }
        .category-basic { background: #f3e5f5; color: #7b1fa2; }
        .category-advanced { background: #fff3e0; color: #e65100; }
        .category-content { background: #e8f5e9; color: #2e7d32; }
        .section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .section h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .success { color: #4caf50; }
        .warning { color: #ff9800; }
        .error { color: #f44336; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎨 ProBuilder Widget Test</h1>
        <p>Comprehensive widget loading verification</p>
    </div>

    <?php
    // Get widgets manager
    $widgets_manager = ProBuilder_Widgets_Manager::instance();
    $widgets = $widgets_manager->get_widgets();
    
    // Count widgets by category
    $categories = [
        'layout' => [],
        'basic' => [],
        'advanced' => [],
        'content' => [],
        'other' => []
    ];
    
    foreach ($widgets as $widget) {
        $category = $widget->get_category();
        if (!isset($categories[$category])) {
            $categories['other'][] = $widget;
        } else {
            $categories[$category][] = $widget;
        }
    }
    
    // Check for grid layout specifically
    $grid_widget = $widgets_manager->get_widget('grid-layout');
    ?>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total Widgets</div>
            <div class="stat-number"><?php echo count($widgets); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Layout</div>
            <div class="stat-number"><?php echo count($categories['layout']); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Basic</div>
            <div class="stat-number"><?php echo count($categories['basic']); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Advanced</div>
            <div class="stat-number"><?php echo count($categories['advanced']); ?></div>
        </div>
    </div>

    <div class="section">
        <h2>Grid Layout Widget Status</h2>
        <?php if ($grid_widget): ?>
            <p class="success">✅ <strong>Grid Layout widget is loaded!</strong></p>
            <ul>
                <li><strong>Name:</strong> <?php echo esc_html($grid_widget->get_name()); ?></li>
                <li><strong>Title:</strong> <?php echo esc_html($grid_widget->get_title()); ?></li>
                <li><strong>Icon:</strong> <?php echo esc_html($grid_widget->get_icon()); ?></li>
                <li><strong>Category:</strong> <?php echo esc_html($grid_widget->get_category()); ?></li>
                <li><strong>Class:</strong> <?php echo get_class($grid_widget); ?></li>
            </ul>
            
            <?php 
            $controls = $grid_widget->get_controls();
            ?>
            <p><strong>Controls:</strong> <?php echo count($controls); ?> controls registered</p>
            <details>
                <summary>View all controls</summary>
                <ul>
                    <?php foreach ($controls as $key => $control): ?>
                        <li>
                            <strong><?php echo esc_html($key); ?></strong>
                            <?php if (isset($control['label'])): ?>
                                - <?php echo esc_html($control['label']); ?>
                            <?php endif; ?>
                            <?php if (isset($control['type'])): ?>
                                (<?php echo esc_html($control['type']); ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        <?php else: ?>
            <p class="error">❌ <strong>Grid Layout widget NOT found!</strong></p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>All Widgets (<?php echo count($widgets); ?>)</h2>
        <div class="widget-grid">
            <?php foreach ($widgets as $widget): ?>
                <div class="widget-card">
                    <div class="widget-icon"><?php 
                        $icon = $widget->get_icon();
                        if (strpos($icon, 'fa-') !== false) {
                            echo '<i class="' . esc_attr($icon) . '"></i>';
                        } else {
                            echo '📦';
                        }
                    ?></div>
                    <div class="widget-name"><?php echo esc_html($widget->get_title()); ?></div>
                    <div class="widget-category category-<?php echo esc_attr($widget->get_category()); ?>">
                        <?php echo esc_html($widget->get_category()); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h2>Widget Files Check</h2>
        <?php
        $widgets_dir = PROBUILDER_PATH . 'widgets/';
        $widget_files = glob($widgets_dir . '*.php');
        ?>
        <p>Found <strong><?php echo count($widget_files); ?></strong> widget files in directory</p>
        <p>Loaded <strong><?php echo count($widgets); ?></strong> widget instances</p>
        
        <?php if (count($widget_files) != count($widgets)): ?>
            <p class="warning">⚠️ Warning: File count doesn't match loaded widgets!</p>
        <?php else: ?>
            <p class="success">✅ All widget files successfully loaded!</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>System Information</h2>
        <ul>
            <li><strong>ProBuilder Version:</strong> <?php echo PROBUILDER_VERSION; ?></li>
            <li><strong>ProBuilder Path:</strong> <?php echo PROBUILDER_PATH; ?></li>
            <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
            <li><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></li>
            <li><strong>Test Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
        </ul>
    </div>

</body>
</html>


