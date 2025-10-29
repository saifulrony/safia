<?php
/**
 * Check homepage and page settings
 */
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Get homepage settings
$show_on_front = get_option('show_on_front');
$page_on_front = get_option('page_on_front');
$page_for_posts = get_option('page_for_posts');

// Check which pages actually exist
$test_slugs = ['rony3', 'rony4', 'rony7', 'rony9', 'rony10', 'rony11', 'rony12', 'rony500'];
$existing_pages = [];
$missing_pages = [];

foreach ($test_slugs as $slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        $existing_pages[] = [
            'slug' => $slug,
            'id' => $page->ID,
            'title' => $page->post_title,
            'status' => $page->post_status,
            'has_probuilder' => !empty(get_post_meta($page->ID, '_probuilder_data', true))
        ];
    } else {
        $missing_pages[] = $slug;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Homepage Settings Check</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #344047;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e6e9ec;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .alert-danger {
            background: #fee;
            border-left: 4px solid #dc2626;
            color: #721c24;
        }
        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }
        .alert-success {
            background: #d4edda;
            border-left: 4px solid #22c55e;
            color: #155724;
        }
        .alert-info {
            background: #e6f3ff;
            border-left: 4px solid #667eea;
            color: #004085;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success {
            background: #22c55e;
            color: white;
        }
        .badge-danger {
            background: #dc2626;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #344047;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="section">
        <h1>🏠 Homepage & Page Settings Diagnosis</h1>
    </div>

    <div class="section">
        <h2>WordPress Homepage Settings</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Value</th>
                <th>What It Means</th>
            </tr>
            <tr>
                <td><strong>show_on_front</strong></td>
                <td><code><?php echo esc_html($show_on_front); ?></code></td>
                <td>
                    <?php if ($show_on_front === 'page'): ?>
                        Static page is set as homepage
                    <?php else: ?>
                        Latest posts shown on homepage
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>page_on_front</strong></td>
                <td><code><?php echo $page_on_front ? $page_on_front : 'None'; ?></code></td>
                <td>
                    <?php if ($page_on_front): ?>
                        Page ID <?php echo $page_on_front; ?> (<?php echo get_the_title($page_on_front); ?>)
                    <?php else: ?>
                        No static homepage set
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <?php if ($show_on_front === 'page' && $page_on_front): ?>
            <div class="alert alert-warning">
                <strong>⚠️ ISSUE FOUND!</strong><br>
                Your homepage is set to page ID <?php echo $page_on_front; ?> (<?php echo get_the_title($page_on_front); ?>).<br>
                This might be causing ALL pages to show homepage content!<br><br>
                <strong>Solution:</strong> Change to "Your latest posts" or set a different homepage.
            </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Pages That Actually Exist</h2>
        <?php if (!empty($existing_pages)): ?>
            <table>
                <tr>
                    <th>Slug</th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>ProBuilder</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($existing_pages as $page): ?>
                    <tr>
                        <td><code><?php echo esc_html($page['slug']); ?></code></td>
                        <td><?php echo $page['id']; ?></td>
                        <td><?php echo esc_html($page['title']); ?></td>
                        <td><?php echo $page['status']; ?></td>
                        <td>
                            <?php if ($page['has_probuilder']): ?>
                                <span class="badge badge-success">YES</span>
                            <?php else: ?>
                                <span class="badge badge-danger">NO</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo home_url('/' . $page['slug'] . '/'); ?>" target="_blank" class="btn" style="padding: 6px 12px; font-size: 12px;">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>❌ No pages found!</strong><br>
                None of the test pages (rony3, rony4, etc.) exist in the database.
            </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Pages That DON'T Exist</h2>
        <?php if (!empty($missing_pages)): ?>
            <div class="alert alert-info">
                These pages don't exist in WordPress. When you visit them, WordPress shows the homepage instead:
            </div>
            <ul>
                <?php foreach ($missing_pages as $slug): ?>
                    <li><code><?php echo esc_html($slug); ?></code> - This page was never created!</li>
                <?php endforeach; ?>
            </ul>
            
            <div class="alert alert-warning">
                <strong>This explains why rony500 shows demo content!</strong><br>
                It doesn't exist, so WordPress defaults to showing the homepage.
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                All tested pages exist!
            </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>🎯 Solution</h2>
        
        <div class="alert alert-success">
            <strong>✅ Fix Applied to Theme</strong><br><br>
            
            I've updated both <code>front-page.php</code> and <code>page.php</code> to properly detect ProBuilder pages.<br><br>
            
            <strong>Next steps:</strong><br>
            1. Clear cache (button below)<br>
            2. Visit your pages<br>
            3. Hard refresh browser (Ctrl+Shift+R)<br>
            4. Should show ProBuilder content now!
        </div>
        
        <?php if ($show_on_front === 'page'): ?>
            <div class="alert alert-warning">
                <strong>⚠️ Additional Issue:</strong><br>
                Your homepage setting might be interfering.<br><br>
                <strong>Recommended:</strong><br>
                Go to: <a href="<?php echo admin_url('options-reading.php'); ?>">Settings → Reading</a><br>
                Change "Your homepage displays" to: <strong>"Your latest posts"</strong><br>
                This prevents homepage from overriding other pages.
            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="clear-cache.php" class="btn" style="background: #dc2626;">🗑️ Clear Cache Now</a>
        <a href="list-all-pages.php" class="btn" style="background: #667eea;">📋 View All Pages</a>
        <a href="<?php echo admin_url('options-reading.php'); ?>" class="btn" style="background: #22c55e;">⚙️ Fix Homepage Settings</a>
    </div>
    
    <div style="margin-top: 40px; padding: 20px; background: white; border-radius: 8px;">
        <h3>🎓 Understanding the Problem</h3>
        <p><strong>Why were all pages showing demo content?</strong></p>
        <ol>
            <li>WordPress is configured to show a static page as homepage</li>
            <li>When you visit /rony7/, WordPress checks if that page exists</li>
            <li>If page exists: Shows that page</li>
            <li>If page doesn't exist: Shows homepage (front-page.php)</li>
            <li>front-page.php was showing demo content for pages without regular content</li>
            <li>ProBuilder pages have no regular content (data is in meta)</li>
            <li>So theme showed demo sections instead!</li>
        </ol>
        
        <p><strong>The fix:</strong></p>
        <ul>
            <li>✅ Theme now checks for ProBuilder data FIRST</li>
            <li>✅ If ProBuilder data exists, shows your content</li>
            <li>✅ Only shows demo if page truly has no content</li>
        </ul>
    </div>
</body>
</html>

