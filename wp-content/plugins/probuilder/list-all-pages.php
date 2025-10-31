<?php
/**
 * List All Pages - Show which builder is active
 * Bulk convert Elementor pages to ProBuilder
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Handle bulk actions
if (isset($_POST['action']) && $_POST['action'] === 'bulk_remove_elementor') {
    $page_ids = isset($_POST['page_ids']) ? array_map('intval', $_POST['page_ids']) : [];
    
    $fixed_count = 0;
    $errors = [];
    
    foreach ($page_ids as $pid) {
        // Remove Elementor data
        $elementor_keys = ['_elementor_data', '_elementor_edit_mode', '_elementor_template_type', '_elementor_version', '_elementor_pro_version', '_elementor_css'];
        foreach ($elementor_keys as $key) {
            delete_post_meta($pid, $key);
        }
        
        // Clear post content
        wp_update_post([
            'ID' => $pid,
            'post_content' => ''
        ]);
        
        // Ensure ProBuilder mode
        update_post_meta($pid, '_probuilder_edit_mode', 'probuilder');
        
        // Clear cache
        clean_post_cache($pid);
        
        $fixed_count++;
    }
    
    // Clear all caches
    wp_cache_flush();
    flush_rewrite_rules(false);
    
    $success_message = "✓ Fixed $fixed_count page(s)! Removed Elementor data and cleared demo content.";
}

// Get all pages
$all_pages = get_posts([
    'post_type' => 'page',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'ID',
    'order' => 'DESC'
]);

?>
<!DOCTYPE html>
<html>
<head>
    <title>All Pages - Builder Status</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        h1 {
            color: #344047;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #667eea;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .alert {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #22c55e;
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .stat-card h3 {
            color: #344047;
            font-size: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
        }
        .stat-card.elementor { border-left-color: #ef4444; }
        .stat-card.elementor .number { color: #ef4444; }
        .stat-card.probuilder { border-left-color: #22c55e; }
        .stat-card.probuilder .number { color: #22c55e; }
        .stat-card.neither { border-left-color: #9ca3af; }
        .stat-card.neither .number { color: #9ca3af; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #344047;
            color: white;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:hover {
            background: #f1f5f9;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-elementor {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-probuilder {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-both {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-none {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-1px);
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 11px;
        }
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-success {
            background: #22c55e;
        }
        .btn-success:hover {
            background: #16a34a;
        }
        
        .bulk-actions {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .bulk-actions strong {
            color: #344047;
        }
        
        .checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #e0e7ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            color: #344047;
            margin-bottom: 10px;
        }
        .info-box ul {
            margin-left: 20px;
            line-height: 1.8;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📄 All Pages - Builder Status</h1>
        <p class="subtitle">View and manage which builder is active on each page</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <strong><?php echo $success_message; ?></strong>
            </div>
        <?php endif; ?>

        <?php
        // Calculate statistics
        $total_pages = count($all_pages);
        $elementor_only = 0;
        $probuilder_only = 0;
        $both_builders = 0;
        $no_builder = 0;
        $elementor_pages = [];
        
        foreach ($all_pages as $page) {
            $has_elementor = !empty(get_post_meta($page->ID, '_elementor_data', true)) || get_post_meta($page->ID, '_elementor_edit_mode', true) === 'builder';
            $has_probuilder = !empty(get_post_meta($page->ID, '_probuilder_data', true));
            
            if ($has_elementor && $has_probuilder) {
                $both_builders++;
                $elementor_pages[] = $page->ID;
            } elseif ($has_elementor) {
                $elementor_only++;
                $elementor_pages[] = $page->ID;
            } elseif ($has_probuilder) {
                $probuilder_only++;
            } else {
                $no_builder++;
            }
        }
        ?>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Pages</h3>
                <div class="number"><?php echo $total_pages; ?></div>
            </div>
            <div class="stat-card elementor">
                <h3>Elementor Only</h3>
                <div class="number"><?php echo $elementor_only; ?></div>
            </div>
            <div class="stat-card probuilder">
                <h3>ProBuilder Only</h3>
                <div class="number"><?php echo $probuilder_only; ?></div>
            </div>
            <div class="stat-card">
                <h3>Both Builders</h3>
                <div class="number"><?php echo $both_builders; ?></div>
            </div>
            <div class="stat-card neither">
                <h3>No Builder</h3>
                <div class="number"><?php echo $no_builder; ?></div>
            </div>
        </div>

        <?php if (count($elementor_pages) > 0): ?>
            <div class="alert alert-error">
                <strong>🚨 Problem Found: <?php echo count($elementor_pages); ?> page(s) have Elementor data!</strong><br>
                These pages will show Elementor/demo content instead of ProBuilder content. Use the bulk action below to fix all at once.
            </div>
        <?php endif; ?>

        <!-- Info Box -->
        <div class="info-box">
            <h3>🔍 Understanding Builder Status</h3>
            <ul>
                <li><strong>Elementor Only:</strong> Old demo pages - will show demo content</li>
                <li><strong>ProBuilder Only:</strong> Clean ProBuilder pages - will show your content ✓</li>
                <li><strong>Both Builders:</strong> Conflicting! Will show Elementor content instead of ProBuilder</li>
                <li><strong>No Builder:</strong> Regular WordPress pages (no page builder)</li>
            </ul>
        </div>

        <!-- Bulk Actions Form -->
        <form method="post" id="bulk-form">
            <input type="hidden" name="action" value="bulk_remove_elementor">
            
            <?php if (count($elementor_pages) > 0): ?>
                <div class="bulk-actions">
                    <strong>Bulk Actions:</strong>
                    <button type="button" class="btn btn-sm" onclick="selectAllElementor()">
                        ✓ Select All Elementor Pages
                    </button>
                    <button type="button" class="btn btn-sm" onclick="deselectAll()">
                        ✗ Deselect All
                    </button>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Remove Elementor from selected pages? This will:\n\n1. Delete all Elementor data\n2. Clear page content\n3. Keep ProBuilder data (if any)\n4. Pages will use ProBuilder only\n\nContinue?')">
                        🗑️ Remove Elementor from Selected
                    </button>
                    <span id="selected-count" style="color: #667eea; font-weight: 600;"></span>
                </div>
            <?php endif; ?>

            <!-- Pages Table -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="checkbox" id="select-all" onchange="toggleAll(this)">
                        </th>
                        <th>ID</th>
                        <th>Page Title</th>
                        <th>URL Slug</th>
                        <th>Builder Status</th>
                        <th>PB Elements</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_pages as $page): ?>
                        <?php
                        $has_elementor = !empty(get_post_meta($page->ID, '_elementor_data', true)) || get_post_meta($page->ID, '_elementor_edit_mode', true) === 'builder';
                        $has_probuilder = !empty(get_post_meta($page->ID, '_probuilder_data', true));
                        $probuilder_data = get_post_meta($page->ID, '_probuilder_data', true);
                        $element_count = is_array($probuilder_data) ? count($probuilder_data) : 0;
                        
                        // Determine badge
                        if ($has_elementor && $has_probuilder) {
                            $badge_class = 'badge-both';
                            $badge_text = '⚠️ Both';
                        } elseif ($has_elementor) {
                            $badge_class = 'badge-elementor';
                            $badge_text = 'Elementor';
                        } elseif ($has_probuilder) {
                            $badge_class = 'badge-probuilder';
                            $badge_text = '✓ ProBuilder';
                        } else {
                            $badge_class = 'badge-none';
                            $badge_text = 'None';
                        }
                        
                        $show_checkbox = $has_elementor;
                        ?>
                        <tr>
                            <td>
                                <?php if ($show_checkbox): ?>
                                    <input type="checkbox" name="page_ids[]" value="<?php echo $page->ID; ?>" class="checkbox page-checkbox" onchange="updateCount()">
                                <?php endif; ?>
                            </td>
                            <td><strong>#<?php echo $page->ID; ?></strong></td>
                            <td><?php echo esc_html($page->post_title); ?></td>
                            <td><code><?php echo esc_html($page->post_name); ?></code></td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $badge_text; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($has_probuilder): ?>
                                    <strong><?php echo $element_count; ?></strong>
                                <?php else: ?>
                                    <span style="color: #9ca3af;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo get_permalink($page->ID); ?>" target="_blank" class="btn btn-sm">View</a>
                                <a href="<?php echo add_query_arg(['page_id' => $page->ID, 'probuilder' => 'true'], home_url('/')); ?>" class="btn btn-sm btn-success">Edit</a>
                                <?php if ($has_elementor): ?>
                                    <a href="fix-page-489.php?post_id=<?php echo $page->ID; ?>" class="btn btn-sm btn-danger">Fix</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
            <h3 style="color: #344047; margin-bottom: 15px;">🔗 Quick Links</h3>
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="btn">View All Pages in Admin</a>
            <a href="diagnose-url-issue.php" class="btn">Full Diagnostic</a>
            <a href="clear-cache.php" class="btn">Clear Cache</a>
        </div>
    </div>

    <script>
        function toggleAll(checkbox) {
            const checkboxes = document.querySelectorAll('.page-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateCount();
        }
        
        function selectAllElementor() {
            document.querySelectorAll('.page-checkbox').forEach(cb => cb.checked = true);
            document.getElementById('select-all').checked = true;
            updateCount();
        }
        
        function deselectAll() {
            document.querySelectorAll('.page-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('select-all').checked = false;
            updateCount();
        }
        
        function updateCount() {
            const checked = document.querySelectorAll('.page-checkbox:checked').length;
            const counter = document.getElementById('selected-count');
            if (counter) {
                counter.textContent = checked > 0 ? `(${checked} selected)` : '';
            }
        }
        
        // Initialize count
        updateCount();
    </script>
</body>
</html>
