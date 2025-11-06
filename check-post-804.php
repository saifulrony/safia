<?php
/**
 * Check What Post 804 Is
 */

require_once('wp-load.php');

echo '<style>
body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; padding: 40px; background: #f9fafb; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
h1 { color: #1f2937; }
.info { padding: 15px; background: #dbeafe; border-left: 4px solid #3b82f6; color: #1e40af; border-radius: 6px; margin: 15px 0; }
.warning { padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; color: #92400e; border-radius: 6px; margin: 15px 0; }
.success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
.error { padding: 15px; background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; border-radius: 6px; margin: 15px 0; }
.button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
code { background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 14px; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
table th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
table td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
</style>';

echo '<div class="container">';
echo '<h1>🔍 Checking Post ID: 804</h1>';

// Get the post
$post = get_post(804);

if (!$post) {
    echo '<div class="error">';
    echo '<strong>❌ Post Not Found</strong><br>';
    echo 'Post ID 804 does not exist in your database.';
    echo '</div>';
    
    // Show all headers instead
    echo '<h2>📌 Available Headers:</h2>';
    $headers = get_posts([
        'post_type' => 'pb_header',
        'posts_per_page' => -1,
        'post_status' => 'any',
        'orderby' => 'ID',
        'order' => 'DESC'
    ]);
    
    if (empty($headers)) {
        echo '<div class="warning">';
        echo '<strong>⚠️ No Headers Found</strong><br>';
        echo 'You need to create headers first. Run the script:<br>';
        echo '<code>http://192.168.10.203:7000/add-sample-headers-now.php</code>';
        echo '</div>';
    } else {
        echo '<table>';
        echo '<tr><th>ID</th><th>Title</th><th>Status</th><th>Actions</th></tr>';
        foreach ($headers as $header) {
            echo '<tr>';
            echo '<td>' . $header->ID . '</td>';
            echo '<td>' . esc_html($header->post_title) . '</td>';
            echo '<td>' . ucfirst($header->post_status) . '</td>';
            echo '<td>';
            echo '<a href="' . admin_url('?p=' . $header->ID . '&probuilder=true&post_type=pb_header') . '" class="button" style="padding: 6px 15px; font-size: 12px; margin: 0;">Edit</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    
    echo '</div>';
    exit;
}

// Post exists - show info
echo '<div class="success">';
echo '<strong>✅ Post Found!</strong>';
echo '</div>';

echo '<table>';
echo '<tr><th>Property</th><th>Value</th></tr>';
echo '<tr><td><strong>ID</strong></td><td>' . $post->ID . '</td></tr>';
echo '<tr><td><strong>Title</strong></td><td>' . esc_html($post->post_title) . '</td></tr>';
echo '<tr><td><strong>Post Type</strong></td><td><code>' . $post->post_type . '</code></td></tr>';
echo '<tr><td><strong>Status</strong></td><td>' . ucfirst($post->post_status) . '</td></tr>';
echo '<tr><td><strong>Date</strong></td><td>' . $post->post_date . '</td></tr>';
echo '</table>';

// Check if it's a custom part
$custom_part_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar'];

if (in_array($post->post_type, $custom_part_types)) {
    echo '<div class="warning">';
    echo '<h2 style="margin-top: 0;">⚠️ This is a Custom Element!</h2>';
    echo '<p>Post ID 804 is a <strong>' . str_replace('pb_', '', $post->post_type) . '</strong>. ';
    echo 'Headers, footers, sliders, and sidebars <strong>cannot be accessed directly via URL</strong>.</p>';
    echo '<p>They are reusable elements that you insert into pages using shortcodes.</p>';
    echo '</div>';
    
    echo '<div class="info">';
    echo '<h3 style="margin-top: 0;">✅ How to Use This ' . ucfirst(str_replace('pb_', '', $post->post_type)) . ':</h3>';
    
    echo '<p><strong>Method 1: Via Shortcode in Page Content</strong></p>';
    echo '<code>[' . str_replace('pb_', '', $post->post_type) . ' id="' . $post->ID . '"]</code>';
    
    echo '<p style="margin-top: 20px;"><strong>Method 2: Via PHP in Theme Files</strong></p>';
    echo '<code>&lt;?php echo do_shortcode(\'[' . str_replace('pb_', '', $post->post_type) . ' id="' . $post->ID . '"]\'); ?&gt;</code>';
    
    echo '<p style="margin-top: 20px;"><strong>Method 3: Edit with ProBuilder</strong></p>';
    echo '<a href="' . admin_url('?p=' . $post->ID . '&probuilder=true&post_type=' . $post->post_type) . '" class="button">Edit with ProBuilder</a>';
    echo '</div>';
    
    // Show shortcode info
    echo '<div style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 20px;">';
    echo '<h3 style="color: #667eea; margin-top: 0;">📋 Shortcode for Post 804:</h3>';
    echo '<input type="text" value="[' . str_replace('pb_', '', $post->post_type) . ' id=&quot;' . $post->ID . '&quot;]" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: monospace; font-size: 14px;" onclick="this.select();" readonly>';
    echo '<p style="margin-top: 10px; color: #6b7280; font-size: 13px;">Click to select and copy</p>';
    echo '</div>';
    
} else {
    // It's a regular page/post
    echo '<div class="info">';
    echo '<h2 style="margin-top: 0;">ℹ️ This is a Regular ' . ucfirst($post->post_type) . '</h2>';
    echo '<p>You can access it via its permalink.</p>';
    echo '</div>';
    
    echo '<div class="success">';
    echo '<p><strong>Permalink:</strong></p>';
    echo '<a href="' . get_permalink($post->ID) . '" target="_blank" style="color: #667eea; word-break: break-all;">' . get_permalink($post->ID) . '</a>';
    echo '</div>';
    
    echo '<div style="margin-top: 30px;">';
    echo '<a href="' . get_permalink($post->ID) . '" class="button" target="_blank">View ' . ucfirst($post->post_type) . '</a>';
    echo '<a href="' . admin_url('post.php?post=' . $post->ID . '&action=edit') . '" class="button" style="background: #6b7280;">Edit in Admin</a>';
    echo '</div>';
}

// Show all similar items
echo '<hr style="margin: 40px 0; border: none; border-top: 1px solid #e5e7eb;">';

if (in_array($post->post_type, $custom_part_types)) {
    $type_name = str_replace('pb_', '', $post->post_type);
    echo '<h2>📋 All ' . ucfirst($type_name) . 's:</h2>';
    
    $all_items = get_posts([
        'post_type' => $post->post_type,
        'posts_per_page' => -1,
        'post_status' => 'any',
        'orderby' => 'ID',
        'order' => 'DESC'
    ]);
    
    if (!empty($all_items)) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Title</th><th>Status</th><th>Shortcode</th><th>Actions</th></tr>';
        foreach ($all_items as $item) {
            echo '<tr' . ($item->ID == $post->ID ? ' style="background: #fef3c7;"' : '') . '>';
            echo '<td><strong>' . $item->ID . '</strong></td>';
            echo '<td>' . esc_html($item->post_title) . '</td>';
            echo '<td>' . ucfirst($item->post_status) . '</td>';
            echo '<td><code>[' . $type_name . ' id="' . $item->ID . '"]</code></td>';
            echo '<td><a href="' . admin_url('?p=' . $item->ID . '&probuilder=true&post_type=' . $post->post_type) . '" class="button" style="padding: 6px 15px; font-size: 12px; margin: 0;">Edit</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}

// Links
echo '<div style="text-align: center; margin-top: 40px;">';
echo '<a href="' . admin_url('edit.php?post_type=' . $post->post_type) . '" class="button">View All ' . ucfirst(str_replace('pb_', '', $post->post_type)) . 's</a>';
echo '<a href="' . admin_url('admin.php?page=probuilder-parts') . '" class="button" style="background: #6b7280;">ProBuilder Dashboard</a>';
echo '</div>';

echo '</div>';

