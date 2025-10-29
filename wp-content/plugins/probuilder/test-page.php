<?php
/**
 * Test what content is being loaded
 * Usage: /wp-content/plugins/probuilder/test-page.php?slug=rony7
 */

require_once('../../../wp-load.php');

$slug = isset($_GET['slug']) ? sanitize_text_field($_GET['slug']) : '';
if (empty($slug)) {
    die('Usage: test-page.php?slug=rony7');
}

$page = get_page_by_path($slug);
if (!$page) {
    die('Page not found: ' . $slug);
}

$post_id = $page->ID;

// Get ProBuilder data
$probuilder_data = get_post_meta($post_id, '_probuilder_data', true);

// Get raw content
$raw_content = $page->post_content;

// Get filtered content (through the_content filter)
$filtered_content = apply_filters('the_content', $raw_content);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Content Test: <?php echo $slug; ?></title>
    <style>
        body {
            font-family: monospace;
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 2px solid #344047;
        }
        h2 {
            color: #344047;
            margin-bottom: 15px;
            border-bottom: 2px solid #e6e9ec;
            padding-bottom: 10px;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
            font-size: 11px;
            line-height: 1.4;
        }
        .good {
            color: #22c55e;
            font-weight: bold;
        }
        .bad {
            color: #dc2626;
            font-weight: bold;
        }
        .info {
            background: #e6f3ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #ffc107;
        }
        .success {
            background: #d4edda;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #22c55e;
        }
    </style>
</head>
<body>
    <h1>🧪 Content Test for: <?php echo esc_html($slug); ?></h1>
    
    <div class="section">
        <h2>Page Info</h2>
        <div class="info">
            <strong>Post ID:</strong> <?php echo $post_id; ?><br>
            <strong>Title:</strong> <?php echo get_the_title($post_id); ?><br>
            <strong>Slug:</strong> <?php echo $page->post_name; ?><br>
            <strong>Status:</strong> <?php echo get_post_status($post_id); ?><br>
            <strong>URL:</strong> <a href="<?php echo get_permalink($post_id); ?>" target="_blank"><?php echo get_permalink($post_id); ?></a>
        </div>
    </div>

    <div class="section">
        <h2>ProBuilder Data Check</h2>
        <?php if (empty($probuilder_data)): ?>
            <div class="warning">
                <strong class="bad">❌ NO PROBUILDER DATA!</strong><br>
                This page doesn't have any ProBuilder data saved.<br>
                This means: Either nothing was saved, or save failed.
            </div>
        <?php else: ?>
            <div class="success">
                <strong class="good">✅ PROBUILDER DATA EXISTS</strong>
            </div>
            
            <?php
            // Parse if string
            if (is_string($probuilder_data)) {
                $decoded = json_decode($probuilder_data, true);
                $probuilder_data = $decoded ?: $probuilder_data;
            }
            
            if (is_array($probuilder_data)):
            ?>
                <div class="info">
                    <strong>Element Count:</strong> <?php echo count($probuilder_data); ?><br>
                    <?php if (count($probuilder_data) === 1): ?>
                        <strong class="good">✓ Correct!</strong> Should have 1 element (your heading)
                    <?php else: ?>
                        <strong class="bad">✗ Wrong!</strong> Should have 1 element, but has <?php echo count($probuilder_data); ?>
                    <?php endif; ?>
                </div>
                
                <h3 style="margin-top: 20px;">Elements:</h3>
                <?php foreach ($probuilder_data as $index => $element): ?>
                    <div class="info">
                        <strong>Element <?php echo $index + 1; ?>:</strong> 
                        <?php echo isset($element['widgetType']) ? $element['widgetType'] : 'unknown'; ?><br>
                        
                        <?php if ($element['widgetType'] === 'heading' && isset($element['settings']['title'])): ?>
                            <strong style="color: #22c55e;">Heading Text:</strong> "<?php echo esc_html($element['settings']['title']); ?>"<br>
                            <?php if ($element['settings']['title'] === 'This is a heading'): ?>
                                <strong class="good">✓ CORRECT!</strong> This is your heading!
                            <?php else: ?>
                                <strong class="bad">✗ WRONG!</strong> Should be "This is a heading"
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <h3 style="margin-top: 20px;">Full Data:</h3>
                <pre><?php print_r($probuilder_data); ?></pre>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>WordPress Post Content (Raw)</h2>
        <?php if (empty(trim($raw_content))): ?>
            <div class="info">
                <strong>Empty</strong> - This is NORMAL for ProBuilder pages!<br>
                ProBuilder stores data in post_meta, not post_content.
            </div>
        <?php else: ?>
            <pre><?php echo esc_html($raw_content); ?></pre>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Filtered Content (After the_content filter)</h2>
        <div class="info">
            <strong>This is what WordPress will output on the page.</strong><br>
            Length: <?php echo strlen($filtered_content); ?> characters
        </div>
        
        <?php if (strpos($filtered_content, 'ProBuilder Content Start') !== false): ?>
            <div class="success">
                <strong class="good">✅ ProBuilder filter is working!</strong><br>
                Content contains ProBuilder HTML comments.
            </div>
        <?php else: ?>
            <div class="warning">
                <strong class="bad">❌ ProBuilder filter NOT working!</strong><br>
                Content doesn't contain ProBuilder markers.
            </div>
        <?php endif; ?>
        
        <h3>HTML Preview:</h3>
        <div style="border: 2px solid #e6e9ec; padding: 20px; background: white; min-height: 200px;">
            <?php echo $filtered_content; ?>
        </div>
        
        <h3 style="margin-top: 20px;">HTML Source:</h3>
        <pre><?php echo esc_html($filtered_content); ?></pre>
    </div>

    <div class="section">
        <h2>🎯 Action Plan</h2>
        
        <?php if (empty($probuilder_data)): ?>
            <div class="warning">
                <strong>❌ No ProBuilder data saved!</strong><br><br>
                <strong>What to do:</strong><br>
                1. Open this page in ProBuilder<br>
                2. Add your heading widget<br>
                3. Save it<br>
                4. Come back here and refresh<br>
            </div>
        <?php elseif (is_array($probuilder_data) && count($probuilder_data) === 1 && $probuilder_data[0]['widgetType'] === 'heading'): ?>
            <div class="success">
                <strong class="good">✅ Data looks correct!</strong><br><br>
                Heading text: "<?php echo esc_html($probuilder_data[0]['settings']['title'] ?? ''); ?>"<br><br>
                <strong>If page still shows wrong content:</strong><br>
                1. Clear cache<br>
                2. Hard refresh browser (Ctrl+Shift+R)<br>
                3. Try incognito mode<br>
            </div>
        <?php else: ?>
            <div class="warning">
                <strong class="bad">❌ Wrong data saved!</strong><br><br>
                Expected: 1 element (heading)<br>
                Actual: <?php echo is_array($probuilder_data) ? count($probuilder_data) : 0; ?> element(s)<br><br>
                <strong>What to do:</strong><br>
                1. Open page in ProBuilder<br>
                2. DELETE ALL elements<br>
                3. Add ONE heading widget<br>
                4. Type "This is a heading"<br>
                5. Save<br>
                6. Come back here and refresh<br>
            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="<?php echo get_permalink($post_id); ?>" target="_blank" style="display: inline-block; padding: 12px 24px; background: #22c55e; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 5px;">
            👁️ View Page
        </a>
        <a href="<?php echo add_query_arg(['p' => $post_id, 'probuilder' => 'true'], home_url('/')); ?>" style="display: inline-block; padding: 12px 24px; background: #344047; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 5px;">
            ✏️ Edit in ProBuilder
        </a>
        <a href="../../../wp-content/plugins/probuilder/clear-cache.php" style="display: inline-block; padding: 12px 24px; background: #dc2626; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 5px;">
            🗑️ Clear Cache
        </a>
    </div>
</body>
</html>

