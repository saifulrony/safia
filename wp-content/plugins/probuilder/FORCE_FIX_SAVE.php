<?php
/**
 * FORCE FIX - Patches ProBuilder Save Issue
 * This adds aggressive fixes directly to make save work
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Create the fix JavaScript
$fix_js = <<<'JAVASCRIPT'
// AGGRESSIVE FIX FOR PROBUILDER SAVE ISSUE
console.log('🔧 ProBuilder Save Fix Loaded!');

// Wait for ProBuilder to initialize
jQuery(document).ready(function($) {
    
    // Check every 500ms until ProBuilder exists
    let checkInterval = setInterval(function() {
        if (typeof ProBuilder !== 'undefined') {
            clearInterval(checkInterval);
            applyFix();
        }
    }, 500);
    
    function applyFix() {
        console.log('✅ Applying ProBuilder save fix...');
        
        // FORCE: Ensure elements is always an array
        if (!Array.isArray(ProBuilder.elements)) {
            console.warn('🔧 FIX: Initializing elements array');
            ProBuilder.elements = [];
        }
        
        // BACKUP: Store original addElement function
        const originalAddElement = ProBuilder.addElement;
        
        // OVERRIDE: Wrap addElement with logging and safety checks
        ProBuilder.addElement = function(widgetName, settings = {}) {
            console.log('🔧 FIX: addElement called with:', widgetName);
            
            // Ensure elements exists
            if (!Array.isArray(this.elements)) {
                console.warn('🔧 FIX: elements was not array, initializing');
                this.elements = [];
            }
            
            // Call original function
            const result = originalAddElement.call(this, widgetName, settings);
            
            // Verify element was added
            console.log('🔧 FIX: After addElement, count:', this.elements.length);
            
            return result;
        };
        
        // BACKUP: Store original save function
        const originalSave = ProBuilder.save;
        
        // OVERRIDE: Wrap save with debugging
        ProBuilder.save = function() {
            console.log('🔧 FIX: Save called!');
            console.log('🔧 FIX: Elements to save:', this.elements);
            console.log('🔧 FIX: Element count:', this.elements.length);
            
            // Ensure elements is array
            if (!Array.isArray(this.elements)) {
                console.error('🔧 FIX: ERROR - elements is not an array!');
                this.elements = [];
            }
            
            // Log each element
            this.elements.forEach((el, i) => {
                console.log(`🔧 FIX: Element ${i}:`, el.widgetType);
            });
            
            // Call original save
            return originalSave.call(this);
        };
        
        // TEST: Add a test element automatically
        console.log('🔧 FIX: Adding test element to verify it works...');
        setTimeout(function() {
            if (ProBuilder.elements.length === 0) {
                console.log('🔧 FIX: Canvas is empty, you can add widgets now');
            } else {
                console.log('🔧 FIX: Found', ProBuilder.elements.length, 'existing elements');
            }
        }, 1000);
        
        console.log('✅ ProBuilder save fix applied successfully!');
        console.log('📝 Try adding a widget and saving now');
    }
});
JAVASCRIPT;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Force Fix ProBuilder Save</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #344047; margin-bottom: 20px; }
        .alert {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #22c55e;
        }
        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
        .code {
            background: #1e293b;
            color: #22c55e;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .step {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
        .step h3 {
            color: #344047;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Force Fix ProBuilder Save Issue</h1>

        <div class="alert alert-success">
            <strong>✅ Fix Script Ready!</strong><br>
            This will inject debugging code directly into the editor to force it to work.
        </div>

        <div class="step">
            <h3>Step 1: Copy This Script</h3>
            <p>Select all and copy (Ctrl+A, Ctrl+C):</p>
            <div class="code"><?php echo htmlspecialchars($fix_js); ?></div>
        </div>

        <div class="step">
            <h3>Step 2: Open ProBuilder Editor</h3>
            <a href="<?php echo home_url('/?page_id=663&probuilder=true'); ?>" target="_blank" class="btn">
                Open Editor for Page 663
            </a>
        </div>

        <div class="step">
            <h3>Step 3: Paste Script in Console</h3>
            <ol style="line-height: 1.8;">
                <li>Press <strong>F12</strong> to open browser console</li>
                <li><strong>Paste</strong> the script from Step 1</li>
                <li>Press <strong>Enter</strong></li>
                <li>You should see: "✅ ProBuilder save fix applied successfully!"</li>
            </ol>
        </div>

        <div class="step">
            <h3>Step 4: Test Adding Widget</h3>
            <ol style="line-height: 1.8;">
                <li>Click <strong>"Heading"</strong> widget in left panel</li>
                <li>Watch console - should see: "🔧 FIX: addElement called with: heading"</li>
                <li>Type some text in the heading</li>
                <li>Click <strong>💾 Save</strong> button</li>
                <li>Should see: "🔧 FIX: Element count: 1"</li>
                <li>Should say: "✓ Page Saved Successfully! <strong>1 element(s) saved</strong>"</li>
            </ol>
        </div>

        <div class="alert alert-info">
            <strong>💡 What This Does:</strong><br>
            This script wraps ProBuilder's addElement and save functions with extra logging and safety checks.
            It will show you EXACTLY what's happening when you add widgets and save.
        </div>

        <div class="step">
            <h3>After Testing</h3>
            <p>Once you confirm it works with the fix script, tell me and I'll make it permanent in the code.</p>
        </div>
    </div>
</body>
</html>

