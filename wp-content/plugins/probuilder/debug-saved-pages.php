<?php
/**
 * Debug script to check saved ProBuilder pages
 * Access: /wp-content/plugins/probuilder/debug-saved-pages.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. Admin only.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>ProBuilder - Saved Pages Debug</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: #344047;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .page-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .page-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #344047;
        }
        .meta-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .meta-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 13px;
        }
        .meta-label {
            font-weight: 600;
            color: #666;
        }
        .data-preview {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .btn-primary {
            background: #344047;
            color: white;
        }
        .btn-secondary {
            background: #e6e9ec;
            color: #344047;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-info {
            background: #e6f3ff;
            color: #0066cc;
            border: 1px solid #b3d9ff;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔍 ProBuilder Saved Pages</h1>
        <p>Debug tool to view all pages with ProBuilder data</p>
    </div>

    <?php
    // Get all posts with ProBuilder data
    $args = [
        'post_type' => ['page', 'post'],
        'post_status' => ['publish', 'draft', 'private'],
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_probuilder_data',
                'compare' => 'EXISTS'
            ]
        ]
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="alert alert-success">';
        echo '<strong>✅ Found ' . $query->found_posts . ' page(s) with ProBuilder data</strong>';
        echo '</div>';

        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $probuilder_data = get_post_meta($post_id, '_probuilder_data', true);
            $edit_mode = get_post_meta($post_id, '_probuilder_edit_mode', true);
            
            // Decode if JSON string
            if (is_string($probuilder_data)) {
                $decoded_data = json_decode($probuilder_data, true);
                $is_json = json_last_error() === JSON_ERROR_NONE;
            } else {
                $decoded_data = $probuilder_data;
                $is_json = false;
            }
            
            $element_count = is_array($decoded_data) ? count($decoded_data) : 0;
            
            echo '<div class="page-card">';
            echo '<div class="page-title">' . get_the_title() . '</div>';
            
            echo '<div class="meta-info">';
            echo '<div class="meta-item"><span class="meta-label">Post ID:</span> ' . $post_id . '</div>';
            echo '<div class="meta-item"><span class="meta-label">Post Type:</span> ' . get_post_type() . '</div>';
            echo '<div class="meta-item"><span class="meta-label">Status:</span> ' . get_post_status() . '</div>';
            echo '<div class="meta-item"><span class="meta-label">Edit Mode:</span> ' . ($edit_mode ? $edit_mode : 'Not set') . '</div>';
            echo '<div class="meta-item"><span class="meta-label">Elements:</span> ' . $element_count . '</div>';
            echo '<div class="meta-item"><span class="meta-label">Data Format:</span> ' . ($is_json ? 'JSON String' : (is_array($probuilder_data) ? 'Array' : 'Other')) . '</div>';
            echo '</div>';
            
            echo '<div style="margin-top: 15px;">';
            echo '<strong>ProBuilder Data Preview:</strong>';
            echo '<div class="data-preview">';
            if (is_array($decoded_data) && !empty($decoded_data)) {
                echo 'Elements: ' . $element_count . "\n\n";
                foreach ($decoded_data as $index => $element) {
                    echo "Element " . ($index + 1) . ":\n";
                    echo "  - Type: " . (isset($element['widgetType']) ? $element['widgetType'] : 'Unknown') . "\n";
                    echo "  - ID: " . (isset($element['id']) ? $element['id'] : 'No ID') . "\n";
                    if (isset($element['children']) && is_array($element['children'])) {
                        echo "  - Children: " . count($element['children']) . "\n";
                    }
                    echo "\n";
                }
            } else {
                echo print_r($probuilder_data, true);
            }
            echo '</div>';
            echo '</div>';
            
            echo '<div class="actions">';
            echo '<a href="' . get_permalink() . '" class="btn btn-primary" target="_blank">View Page</a>';
            echo '<a href="' . admin_url('post.php?post=' . $post_id . '&action=edit') . '" class="btn btn-secondary">Edit in WordPress</a>';
            echo '<a href="' . add_query_arg(['p' => $post_id, 'probuilder' => 'true'], home_url('/')) . '" class="btn btn-secondary">Edit in ProBuilder</a>';
            echo '</div>';
            
            echo '</div>';
        }
        wp_reset_postdata();
    } else {
        echo '<div class="alert alert-warning">';
        echo '<strong>⚠️ No pages found with ProBuilder data</strong><br>';
        echo 'This means no pages have been saved using ProBuilder yet, or the data isn\'t being saved properly.';
        echo '</div>';
    }
    ?>

    <div style="margin-top: 40px; padding: 20px; background: white; border-radius: 8px;">
        <h3>🔧 Troubleshooting</h3>
        <ul style="line-height: 1.8;">
            <li><strong>If no pages are found:</strong> ProBuilder data isn't being saved. Check the save function and database permissions.</li>
            <li><strong>If pages exist but don't display content:</strong> Check the frontend rendering in <code>class-frontend.php</code></li>
            <li><strong>If data format is wrong:</strong> Check if data is being saved as JSON string or array.</li>
            <li><strong>To test:</strong> Create a new page, add some widgets in ProBuilder, save, then refresh this page.</li>
        </ul>
    </div>

    <div style="margin-top: 20px; text-align: center; color: #666; font-size: 13px;">
        <p>Debug Script • ProBuilder v<?php echo PROBUILDER_VERSION; ?> • <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><a href="<?php echo admin_url(); ?>">← Back to Dashboard</a></p>
    </div>
</body>
</html>

