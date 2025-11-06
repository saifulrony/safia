<?php
/**
 * Check if ProBuilder CSS is Loaded with Latest Changes
 */

require_once('wp-load.php');

$css_file = '/home/saiful/wordpress/wp-content/plugins/probuilder/assets/css/editor.css';
$css_url = site_url('/wp-content/plugins/probuilder/assets/css/editor.css');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CSS Diagnostic</title>
    <link rel="stylesheet" href="<?php echo $css_url; ?>?v=<?php echo time(); ?>">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 40px;
            background: #f9fafb;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        h1 { color: #1f2937; }
        .success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
        .error { padding: 15px; background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; border-radius: 6px; margin: 15px 0; }
        .info { padding: 15px; background: #dbeafe; border-left: 4px solid #3b82f6; color: #1e40af; border-radius: 6px; margin: 15px 0; }
        .test-cell {
            position: relative;
            width: 300px;
            height: 200px;
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            margin: 30px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        code { background: #f3f4f6; padding: 4px 8px; border-radius: 4px; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; margin: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 CSS Diagnostic</h1>
        
        <div class="info">
            <strong>ProBuilder CSS File:</strong><br>
            <?php echo $css_file; ?><br><br>
            <strong>Last Modified:</strong><br>
            <?php echo file_exists($css_file) ? date('Y-m-d H:i:s', filemtime($css_file)) : 'File not found!'; ?>
        </div>
        
        <h2>Test Cell (Should Have Resize Handles):</h2>
        <p>This cell should show purple resize handles:</p>
        
        <div class="grid-cell test-cell">
            <div class="resize-handle resize-handle-right"></div>
            <div class="resize-handle resize-handle-bottom"></div>
            <div class="resize-handle resize-handle-corner"></div>
            <div style="text-align: center;">
                <strong>Look for purple handles!</strong><br>
                <small>Right edge | Bottom edge | Corner</small>
            </div>
        </div>
        
        <div id="test-results"></div>
        
        <script>
        // Test if CSS is loaded
        window.addEventListener('load', function() {
            const cell = document.querySelector('.test-cell');
            const rightHandle = document.querySelector('.resize-handle-right');
            const bottomHandle = document.querySelector('.resize-handle-bottom');
            const cornerHandle = document.querySelector('.resize-handle-corner');
            
            const results = document.getElementById('test-results');
            let html = '<h2>🔍 Test Results:</h2>';
            
            // Check if handles exist
            if (rightHandle && bottomHandle && cornerHandle) {
                html += '<div class="success"><strong>✅ Handle elements found in DOM!</strong></div>';
                
                // Check styles
                const rightStyles = window.getComputedStyle(rightHandle);
                const bottomStyles = window.getComputedStyle(bottomHandle);
                const cornerStyles = window.getComputedStyle(cornerHandle);
                
                html += '<div class="info"><strong>Right Handle CSS:</strong><ul>';
                html += '<li>Width: ' + rightStyles.width + (rightStyles.width === '10px' ? ' ✅' : ' ❌ Should be 10px') + '</li>';
                html += '<li>Height: ' + rightStyles.height + (rightStyles.height === '80px' ? ' ✅' : ' ❌ Should be 80px') + '</li>';
                html += '<li>Background: ' + rightStyles.backgroundColor + '</li>';
                html += '<li>Opacity: ' + rightStyles.opacity + (rightStyles.opacity === '0.8' ? ' ✅' : ' ❌ Should be 0.8') + '</li>';
                html += '<li>Position: ' + rightStyles.position + '</li>';
                html += '<li>Cursor: ' + rightStyles.cursor + (rightStyles.cursor === 'ew-resize' ? ' ✅' : ' ❌') + '</li>';
                html += '</ul></div>';
                
                html += '<div class="info"><strong>Bottom Handle CSS:</strong><ul>';
                html += '<li>Width: ' + bottomStyles.width + (bottomStyles.width === '80px' ? ' ✅' : ' ❌ Should be 80px') + '</li>';
                html += '<li>Height: ' + bottomStyles.height + (bottomStyles.height === '10px' ? ' ✅' : ' ❌ Should be 10px') + '</li>';
                html += '<li>Opacity: ' + bottomStyles.opacity + (bottomStyles.opacity === '0.8' ? ' ✅' : ' ❌ Should be 0.8') + '</li>';
                html += '<li>Cursor: ' + bottomStyles.cursor + (bottomStyles.cursor === 'ns-resize' ? ' ✅' : ' ❌') + '</li>';
                html += '</ul></div>';
                
                html += '<div class="info"><strong>Corner Handle CSS:</strong><ul>';
                html += '<li>Width: ' + cornerStyles.width + (cornerStyles.width === '20px' ? ' ✅' : ' ❌ Should be 20px') + '</li>';
                html += '<li>Height: ' + cornerStyles.height + (cornerStyles.height === '20px' ? ' ✅' : ' ❌ Should be 20px') + '</li>';
                html += '<li>Opacity: ' + cornerStyles.opacity + (cornerStyles.opacity === '0.8' ? ' ✅' : ' ❌ Should be 0.8') + '</li>';
                html += '<li>Border Radius: ' + cornerStyles.borderRadius + '</li>';
                html += '<li>Cursor: ' + cornerStyles.cursor + (cornerStyles.cursor === 'nwse-resize' ? ' ✅' : ' ❌') + '</li>';
                html += '</ul></div>';
                
                // Check if visible
                if (rightStyles.opacity === '0.8' && rightStyles.width === '10px') {
                    html += '<div class="success"><h3>✅ CSS IS LOADED CORRECTLY!</h3>';
                    html += '<p>The resize handles CSS is loaded. Handles should be visible (80% opacity purple bars).</p>';
                    html += '<p>If you can\'t see them, check if they\'re behind other elements or scroll the page.</p></div>';
                } else {
                    html += '<div class="error"><h3>❌ CSS NOT LOADED OR OLD VERSION!</h3>';
                    html += '<p><strong>Your browser is using OLD/CACHED CSS!</strong></p>';
                    html += '<p>Clear cache: <code>Ctrl + Shift + R</code></p></div>';
                }
                
            } else {
                html += '<div class="error"><strong>❌ Handle elements NOT found in DOM!</strong></div>';
                html += '<p>The HTML structure is missing handles.</p>';
            }
            
            results.innerHTML = html;
        });
        </script>
        
        <div class="info" style="margin-top: 40px;">
            <h3>💡 Can you see the purple handles on the test cell above?</h3>
            <p><strong>If YES:</strong> CSS is working! Go to ProBuilder and test there.</p>
            <p><strong>If NO:</strong> Your browser is still using cached CSS.</p>
            
            <h4>How to Force Reload CSS:</h4>
            <ol>
                <li>Press <code>Ctrl + Shift + Delete</code></li>
                <li>Select "Cached images and files"</li>
                <li>Clear data</li>
                <li>Reload this page</li>
                <li>Handles should appear!</li>
            </ol>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo admin_url('?p=803&probuilder=true&post_type=pb_header'); ?>" class="button">
                Test in ProBuilder Editor
            </a>
            <a href="javascript:location.reload(true)" class="button" style="background: #10b981;">
                Force Reload This Page
            </a>
        </div>
        
        <div class="info" style="margin-top: 40px; background: #fef3c7; border-left-color: #f59e0b;">
            <h3 style="color: #92400e; margin-top: 0;">📌 New Handle Design:</h3>
            <ul style="color: #92400e;">
                <li><strong>Right Handle:</strong> 10px wide × 80px tall purple bar (centered on right edge)</li>
                <li><strong>Bottom Handle:</strong> 80px wide × 10px tall purple bar (centered on bottom edge)</li>
                <li><strong>Corner Handle:</strong> 20px circle (bottom-right corner)</li>
                <li><strong>Visibility:</strong> 80% opacity ALWAYS (not hidden)</li>
                <li><strong>On Hover:</strong> 100% opacity + grows bigger</li>
                <li><strong>Cell Border:</strong> Purple dashed outline on hover</li>
            </ul>
        </div>
    </div>
</body>
</html>

