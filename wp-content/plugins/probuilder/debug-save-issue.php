<?php
/**
 * Debug Save Issue - Deep Diagnostic
 */
require_once('../../../wp-load.php');
if (!current_user_can('manage_options')) die('Access denied');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Save Issue</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e293b; color: #22c55e; }
        .section { background: #0f172a; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #334155; }
        .section h2 { color: #60a5fa; margin-top: 0; }
        button { padding: 10px 20px; background: #22c55e; color: #000; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin: 5px; }
        button:hover { background: #16a34a; }
        #output { white-space: pre-wrap; font-size: 13px; line-height: 1.6; }
        .error { color: #ef4444; }
        .success { color: #22c55e; }
        .warning { color: #f59e0b; }
        .info { color: #60a5fa; }
    </style>
</head>
<body>
    <h1>🔍 ProBuilder Save Issue - Deep Debug</h1>
    
    <div class="section">
        <h2>Step 1: Open ProBuilder Editor</h2>
        <p>Open the editor in another tab:</p>
        <a href="<?php echo home_url('/?page_id=663&probuilder=true'); ?>" target="_blank">
            <button>Open Editor for Page 663</button>
        </a>
    </div>

    <div class="section">
        <h2>Step 2: Run These Commands in Browser Console</h2>
        <p>Press F12 to open console, then copy/paste each command:</p>
        
        <h3>A) Check if ProBuilder object exists:</h3>
        <code style="background: #334155; padding: 10px; display: block; margin: 10px 0;">
typeof ProBuilder
// Should show: "object"
        </code>
        
        <h3>B) Check if elements array exists:</h3>
        <code style="background: #334155; padding: 10px; display: block; margin: 10px 0;">
ProBuilder.elements
// Should show: [] (empty array)
        </code>
        
        <h3>C) Check ProBuilderEditor data:</h3>
        <code style="background: #334155; padding: 10px; display: block; margin: 10px 0;">
console.log('savedElements:', ProBuilderEditor.savedElements);
console.log('post_id:', ProBuilderEditor.post_id);
console.log('widgets count:', ProBuilderEditor.widgets.length);
        </code>
        
        <h3>D) Manually add a test element:</h3>
        <code style="background: #334155; padding: 10px; display: block; margin: 10px 0;">
ProBuilder.addElement('heading', {title: 'Debug Test'});
console.log('After adding, elements:', ProBuilder.elements);
        </code>
        
        <h3>E) Check what gets sent to save:</h3>
        <code style="background: #334155; padding: 10px; display: block; margin: 10px 0;">
// Before clicking save, run this:
console.log('Elements to save:', JSON.stringify(ProBuilder.elements));
// Then click Save button
        </code>
    </div>

    <div class="section">
        <h2>Step 3: Manual Save Test</h2>
        <p>Try saving manually via console:</p>
        <code style="background: #334155; padding: 10px; display: block; margin: 10px 0;">
// First add an element
ProBuilder.addElement('heading', {title: 'Manual Test'});

// Then save manually
jQuery.post(ProBuilderEditor.ajaxurl, {
    action: 'probuilder_save_page',
    nonce: ProBuilderEditor.nonce,
    post_id: ProBuilderEditor.post_id,
    elements: JSON.stringify(ProBuilder.elements)
}, function(response) {
    console.log('Save response:', response);
});
        </code>
    </div>

    <div class="section">
        <h2>Step 4: Check PHP Side</h2>
        <p>Let's see what PHP receives when you save:</p>
        
        <h3>Check saved data in database:</h3>
        <pre><?php
        $page_id = 663;
        $saved = get_post_meta($page_id, '_probuilder_data', true);
        echo "Current saved data for page 663:\n";
        echo "Type: " . gettype($saved) . "\n";
        if (is_array($saved)) {
            echo "Count: " . count($saved) . "\n";
            echo "Data: " . print_r($saved, true);
        } else if (is_string($saved)) {
            echo "String length: " . strlen($saved) . "\n";
            echo "Data: " . substr($saved, 0, 500) . "\n";
        } else {
            echo "Data: " . var_export($saved, true) . "\n";
        }
        ?></pre>
    </div>

    <div class="section">
        <h2>Step 5: Test Save Endpoint Directly</h2>
        <button onclick="testSave()">Test Save Endpoint</button>
        <pre id="output"></pre>
    </div>

    <script>
    function testSave() {
        const output = document.getElementById('output');
        output.innerHTML = 'Testing save endpoint...\n';
        
        // Test data
        const testData = [{
            id: 'test-' + Date.now(),
            widgetType: 'heading',
            settings: {
                title: 'Debug Test Heading',
                html_tag: 'h2'
            }
        }];
        
        output.innerHTML += '\nSending test data:\n' + JSON.stringify(testData, null, 2) + '\n\n';
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'probuilder_save_page',
                nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                post_id: 663,
                elements: JSON.stringify(testData)
            })
        })
        .then(response => response.json())
        .then(data => {
            output.innerHTML += 'Response:\n' + JSON.stringify(data, null, 2) + '\n';
            if (data.success) {
                output.innerHTML += '\n✓ SAVE WORKED!\n';
                output.innerHTML += 'Element count: ' + data.data.element_count + '\n';
            } else {
                output.innerHTML += '\n✗ SAVE FAILED!\n';
                output.innerHTML += 'Error: ' + (data.data ? data.data.message : 'Unknown') + '\n';
            }
        })
        .catch(error => {
            output.innerHTML += '\n✗ ERROR:\n' + error + '\n';
        });
    }
    </script>

    <div class="section">
        <h2>Common Issues & Solutions</h2>
        
        <h3 class="warning">Issue #1: ProBuilder.elements is undefined</h3>
        <p>Solution: ProBuilder object isn't initializing properly</p>
        <p>Fix: Check if jQuery is loaded, check console for errors</p>
        
        <h3 class="warning">Issue #2: Elements array stays empty after adding widgets</h3>
        <p>Solution: addElement() function isn't working</p>
        <p>Fix: Check if widgets are registered, check addElement function</p>
        
        <h3 class="warning">Issue #3: Save sends empty array even when elements exist</h3>
        <p>Solution: Save function reading from wrong variable</p>
        <p>Fix: Check save function implementation</p>
        
        <h3 class="warning">Issue #4: Save endpoint receives data but doesn't save</h3>
        <p>Solution: Database write issue or data validation failing</p>
        <p>Fix: Check PHP error logs, check database permissions</p>
    </div>

    <div class="section">
        <h2>Quick Actions</h2>
        <a href="<?php echo home_url('/?page_id=663&probuilder=true'); ?>" target="_blank">
            <button>Open Editor</button>
        </a>
        <a href="<?php echo home_url('/wp-content/plugins/probuilder/fix-content-mismatch.php?page_id=663'); ?>" target="_blank">
            <button>Check Page Status</button>
        </a>
        <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" target="_blank">
            <button>All Pages</button>
        </a>
    </div>
</body>
</html>

