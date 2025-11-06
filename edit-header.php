<?php
/**
 * Quick Header/Footer/Slider Editor
 * Redirects to correct ProBuilder editor URL
 */

require_once('wp-load.php');

// Get parameters
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';

if (!$post_id) {
    echo '<style>
    body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; padding: 40px; background: #f9fafb; text-align: center; }
    .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
    .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px; }
    </style>';
    
    echo '<div class="container">';
    echo '<h1 style="color: #1f2937;">📌 Quick Editor</h1>';
    echo '<p style="color: #6b7280;">Enter the ID of the header/footer/slider you want to edit:</p>';
    
    echo '<form method="get" style="margin: 30px 0;">';
    echo '<select name="type" style="padding: 10px; margin: 10px; border: 1px solid #e5e7eb; border-radius: 6px;">';
    echo '<option value="pb_header">Header</option>';
    echo '<option value="pb_footer">Footer</option>';
    echo '<option value="pb_slider">Slider</option>';
    echo '<option value="pb_sidebar">Sidebar</option>';
    echo '</select>';
    echo '<input type="number" name="id" placeholder="Post ID" style="padding: 10px; margin: 10px; border: 1px solid #e5e7eb; border-radius: 6px; width: 120px;">';
    echo '<button type="submit" class="button">Open in ProBuilder</button>';
    echo '</form>';
    
    echo '<hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">';
    echo '<p style="color: #6b7280;">Or browse:</p>';
    echo '<a href="' . admin_url('edit.php?post_type=pb_header') . '" class="button">View Headers</a>';
    echo '<a href="' . admin_url('edit.php?post_type=pb_footer') . '" class="button">View Footers</a>';
    echo '<a href="' . admin_url('edit.php?post_type=pb_slider') . '" class="button">View Sliders</a>';
    
    echo '</div>';
    exit;
}

// Build correct editor URL
$editor_url = add_query_arg([
    'p' => $post_id,
    'probuilder' => 'true',
    'post_type' => $type ? $type : 'pb_header',
], home_url('/'));

// Redirect
wp_redirect($editor_url);
exit;

