<?php
/**
 * Test Theme Options - Check if options are being saved and retrieved
 * 
 * Run this from: http://localhost:7000/test-theme-options.php
 */

// Load WordPress
require_once('wp-load.php');

// Get all options
$header_options = get_option('ecocommerce_pro_header_options', array());
$general_options = get_option('ecocommerce_pro_general_options', array());
$styling_options = get_option('ecocommerce_pro_styling_options', array());

?>
<!DOCTYPE html>
<html>
<head>
    <title>Theme Options Test</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f9fafb;
        }
        .test-section {
            background: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1f2937;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        h2 {
            color: #2563eb;
            margin-top: 30px;
        }
        .option-item {
            padding: 12px;
            margin: 8px 0;
            background: #f3f4f6;
            border-left: 4px solid #2563eb;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
        }
        .option-key {
            font-weight: 600;
            color: #374151;
        }
        .option-value {
            color: #6b7280;
            font-family: monospace;
        }
        .status-good {
            color: #10b981;
            font-weight: bold;
        }
        .status-missing {
            color: #ef4444;
            font-weight: bold;
        }
        .test-result {
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            font-weight: 600;
        }
        .test-pass {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }
        .test-fail {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
        .back-link {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .back-link:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <h1>🔧 EcoCommerce Pro - Theme Options Test</h1>
    
    <div class="test-section">
        <h2>📍 Header Options</h2>
        <?php if (empty($header_options)) : ?>
            <div class="test-result test-fail">
                ❌ No header options found! Please save options in Theme Options → Header
            </div>
        <?php else : ?>
            <div class="test-result test-pass">
                ✅ Header options exist! (<?php echo count($header_options); ?> settings)
            </div>
            
            <?php foreach ($header_options as $key => $value) : ?>
                <div class="option-item">
                    <span class="option-key"><?php echo esc_html($key); ?></span>
                    <span class="option-value">
                        <?php 
                        if (is_bool($value)) {
                            echo $value ? '<span class="status-good">✓ Enabled</span>' : '<span class="status-missing">✗ Disabled</span>';
                        } else {
                            echo esc_html($value);
                        }
                        ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="test-section">
        <h2>⚙️ General Options</h2>
        <?php if (empty($general_options)) : ?>
            <div class="test-result test-fail">
                ❌ No general options found!
            </div>
        <?php else : ?>
            <div class="test-result test-pass">
                ✅ General options exist! (<?php echo count($general_options); ?> settings)
            </div>
            
            <?php foreach ($general_options as $key => $value) : ?>
                <div class="option-item">
                    <span class="option-key"><?php echo esc_html($key); ?></span>
                    <span class="option-value">
                        <?php 
                        if (is_bool($value)) {
                            echo $value ? '<span class="status-good">✓ Enabled</span>' : '<span class="status-missing">✗ Disabled</span>';
                        } elseif (is_array($value)) {
                            echo 'Array (' . count($value) . ' items)';
                        } else {
                            echo esc_html(substr($value, 0, 100));
                        }
                        ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="test-section">
        <h2>🎨 Styling Options</h2>
        <?php if (empty($styling_options)) : ?>
            <div class="test-result test-fail">
                ❌ No styling options found!
            </div>
        <?php else : ?>
            <div class="test-result test-pass">
                ✅ Styling options exist! (<?php echo count($styling_options); ?> settings)
            </div>
            
            <?php foreach ($styling_options as $key => $value) : ?>
                <div class="option-item">
                    <span class="option-key"><?php echo esc_html($key); ?></span>
                    <span class="option-value">
                        <?php 
                        if (strpos($key, 'color') !== false) {
                            echo '<span style="display:inline-block;width:20px;height:20px;background:' . esc_attr($value) . ';border:1px solid #ccc;margin-right:8px;vertical-align:middle;"></span>';
                        }
                        echo esc_html($value);
                        ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="test-section">
        <h2>🧪 Functionality Tests</h2>
        
        <h3>Test 1: Cart Icon Toggle</h3>
        <?php
        $show_cart = !isset($header_options['show_cart']) || !empty($header_options['show_cart']);
        ?>
        <div class="test-result <?php echo $show_cart ? 'test-pass' : 'test-fail'; ?>">
            <?php if ($show_cart) : ?>
                ✅ Cart icon should be VISIBLE
            <?php else : ?>
                ⚠️ Cart icon should be HIDDEN
            <?php endif; ?>
        </div>
        
        <h3>Test 2: Search Bar Toggle</h3>
        <?php
        $show_search = !isset($header_options['show_search']) || !empty($header_options['show_search']);
        ?>
        <div class="test-result <?php echo $show_search ? 'test-pass' : 'test-fail'; ?>">
            <?php if ($show_search) : ?>
                ✅ Search bar should be VISIBLE
            <?php else : ?>
                ⚠️ Search bar should be HIDDEN
            <?php endif; ?>
        </div>
        
        <h3>Test 3: Sticky Header</h3>
        <?php
        $sticky_enabled = !empty($header_options['sticky']);
        ?>
        <div class="test-result <?php echo $sticky_enabled ? 'test-pass' : 'test-fail'; ?>">
            <?php if ($sticky_enabled) : ?>
                ✅ Sticky header is ENABLED (scroll to test)
            <?php else : ?>
                ⚠️ Sticky header is DISABLED
            <?php endif; ?>
        </div>
        
        <h3>Test 4: Header Template</h3>
        <?php
        $header_template = isset($header_options['template']) ? $header_options['template'] : 'default';
        ?>
        <div class="test-result test-pass">
            ✅ Current template: <strong><?php echo esc_html($header_template); ?></strong>
        </div>
        
        <h3>Test 5: Top Bar</h3>
        <?php
        $topbar_enabled = !empty($header_options['topbar_enable']);
        ?>
        <div class="test-result <?php echo $topbar_enabled ? 'test-pass' : 'test-fail'; ?>">
            <?php if ($topbar_enabled) : ?>
                ✅ Top bar should be VISIBLE: "<?php echo esc_html($header_options['topbar_text'] ?? ''); ?>"
            <?php else : ?>
                ⚠️ Top bar is DISABLED
            <?php endif; ?>
        </div>
    </div>
    
    <div class="test-section">
        <h2>📝 Instructions</h2>
        <ol style="line-height: 2;">
            <li>Go to <a href="/wp-admin/admin.php?page=ecocommerce-pro-options&tab=header" target="_blank">Theme Options → Header</a></li>
            <li>Change some settings (toggle cart, search, sticky header)</li>
            <li>Click "Save Changes"</li>
            <li>Refresh this page to see updated values</li>
            <li>Visit your <a href="/" target="_blank">homepage</a> to see the actual changes</li>
        </ol>
        
        <a href="/wp-admin/admin.php?page=ecocommerce-pro-options&tab=header" class="back-link">
            ⚙️ Go to Theme Options
        </a>
        
        <a href="/" class="back-link" style="margin-left: 15px; background: #10b981;">
            🏠 View Homepage
        </a>
    </div>
</body>
</html>

