<?php
/**
 * Create Beautiful Prebuilt Headers Using WP Header Widget
 * Professional, ready-to-use headers with logo and menus
 */

require_once('wp-load.php');

if (!current_user_can('edit_posts')) {
    die('❌ Permission denied');
}

function gen_id() {
    return 'el_' . uniqid();
}

// Get primary menu ID
$menus = wp_get_nav_menus();
$primary_menu_id = !empty($menus) ? $menus[0]->term_id : '';

// Beautiful prebuilt headers using WP Header widget
$headers = [
    // 1. Classic White Header
    [
        'title' => 'Classic White Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'wp-header',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_margin' => '0 auto',
                    'menu_id' => $primary_menu_id,
                    'header_type' => 'horizontal',
                    'show_logo' => 'yes',
                    'logo_width' => 150,
                    'bg_color' => '#ffffff',
                    'menu_color' => '#333333',
                    'menu_hover_color' => '#667eea',
                    'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40],
                    'sticky' => 'no',
                    'box_shadow' => 'yes',
                ],
                'children' => []
            ],
        ],
    ],
    
    // 2. Dark Mode Header
    [
        'title' => 'Dark Modern Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'wp-header',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_margin' => '0 auto',
                    'menu_id' => $primary_menu_id,
                    'header_type' => 'horizontal',
                    'show_logo' => 'yes',
                    'logo_width' => 140,
                    'bg_color' => '#1f2937',
                    'menu_color' => '#ffffff',
                    'menu_hover_color' => '#667eea',
                    'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40],
                    'sticky' => 'yes',
                    'box_shadow' => 'yes',
                ],
                'children' => []
            ],
        ],
    ],
    
    // 3. Sticky Header
    [
        'title' => 'Sticky Professional Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'wp-header',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_margin' => '0 auto',
                    'menu_id' => $primary_menu_id,
                    'header_type' => 'horizontal',
                    'show_logo' => 'yes',
                    'logo_width' => 160,
                    'bg_color' => '#ffffff',
                    'menu_color' => '#1f2937',
                    'menu_hover_color' => '#92003b',
                    'padding' => ['top' => 25, 'right' => 40, 'bottom' => 25, 'left' => 40],
                    'sticky' => 'yes',
                    'box_shadow' => 'yes',
                ],
                'children' => []
            ],
        ],
    ],
    
    // 4. Minimal Clean Header
    [
        'title' => 'Minimal Clean Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'wp-header',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1200px',
                    '_margin' => '0 auto',
                    'menu_id' => $primary_menu_id,
                    'header_type' => 'horizontal',
                    'show_logo' => 'yes',
                    'logo_width' => 120,
                    'bg_color' => '#ffffff',
                    'menu_color' => '#6b7280',
                    'menu_hover_color' => '#1f2937',
                    'padding' => ['top' => 15, 'right' => 30, 'bottom' => 15, 'left' => 30],
                    'sticky' => 'no',
                    'box_shadow' => 'no',
                ],
                'children' => []
            ],
        ],
    ],
    
    // 5. Purple Gradient Header
    [
        'title' => 'Purple Gradient Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_background_type' => 'gradient',
                    '_background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    '_padding' => '0',
                ],
                'children' => [
                    [
                        'id' => gen_id(),
                        'widgetType' => 'wp-header',
                        'settings' => [
                            '_width' => '100%',
                            '_max_width' => '1400px',
                            '_margin' => '0 auto',
                            'menu_id' => $primary_menu_id,
                            'header_type' => 'horizontal',
                            'show_logo' => 'yes',
                            'logo_width' => 150,
                            'bg_color' => 'transparent',
                            'menu_color' => '#ffffff',
                            'menu_hover_color' => '#fbbf24',
                            'padding' => ['top' => 20, 'right' => 40, 'bottom' => 20, 'left' => 40],
                            'sticky' => 'yes',
                            'box_shadow' => 'no',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
];

echo '<!DOCTYPE html><html><head><meta charset="utf-8">';
echo '<style>
body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; padding: 40px; background: #f9fafb; }
.container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
h1 { color: #1f2937; margin-bottom: 10px; }
.success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
.header-card { padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; margin: 20px 0; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); }
.header-card h3 { margin-top: 0; color: white; }
.button { display: inline-block; background: white; color: #667eea; padding: 10px 20px; border-radius: 6px; text-decoration: none; margin: 5px 5px 0 0; font-weight: 600; }
code { background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 4px; font-size: 13px; }
</style></head><body>';

echo '<div class="container">';
echo '<h1>🎨 Creating Beautiful Headers...</h1>';

$created = 0;

foreach ($headers as $header) {
    // Check if exists
    $existing = get_posts([
        'post_type' => 'pb_header',
        'title' => $header['title'],
        'posts_per_page' => 1,
        'post_status' => 'any',
    ]);
    
    if (!empty($existing)) {
        continue;
    }
    
    // Create header
    $post_id = wp_insert_post([
        'post_title' => $header['title'],
        'post_type' => 'pb_header',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    ], true);
    
    if (is_wp_error($post_id)) {
        continue;
    }
    
    // Save data
    update_post_meta($post_id, '_probuilder_data', $header['data']);
    update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
    
    echo '<div class="header-card">';
    echo '<h3>✅ ' . esc_html($header['title']) . '</h3>';
    echo '<p style="opacity: 0.9; margin: 10px 0;">ID: ' . $post_id . '</p>';
    echo '<p style="margin: 15px 0;"><code>[header id="' . $post_id . '"]</code></p>';
    echo '<a href="' . admin_url('?p=' . $post_id . '&probuilder=true&post_type=pb_header') . '" class="button">Edit in ProBuilder</a>';
    echo '</div>';
    
    $created++;
}

if ($created > 0) {
    echo '<div class="success">';
    echo '<h2 style="margin-top: 0;">🎉 Success!</h2>';
    echo '<p><strong>' . $created . ' professional header(s) created!</strong></p>';
    echo '<p>These headers use the <strong>WP Header widget</strong> which includes:</p>';
    echo '<ul>';
    echo '<li>✅ Site logo (automatic)</li>';
    echo '<li>✅ WordPress navigation menu</li>';
    echo '<li>✅ Professional styling</li>';
    echo '<li>✅ Sticky header option</li>';
    echo '<li>✅ Customizable colors</li>';
    echo '</ul>';
    echo '</div>';
} else {
    echo '<div class="success">✅ All headers already exist!</div>';
}

echo '<h2 style="margin-top: 40px;">📍 Next Steps:</h2>';
echo '<ol style="line-height: 2;">';
echo '<li>Go to <strong><a href="' . admin_url('edit.php?post_type=pb_header') . '" style="color: #667eea;">ProBuilder → Headers</a></strong></li>';
echo '<li>You\'ll see your beautiful prebuilt headers</li>';
echo '<li>Click <strong>"Edit with ProBuilder"</strong> on any one</li>';
echo '<li>Check ✅ <strong>"Set as Active Header"</strong> (right sidebar)</li>';
echo '<li>Click <strong>Update</strong></li>';
echo '<li>Visit your site - the header appears on all pages!</li>';
echo '</ol>';

echo '<div style="background: #fef3c7; padding: 20px; border-radius: 8px; margin-top: 30px; border-left: 4px solid #f59e0b;">';
echo '<h3 style="color: #92400e; margin-top: 0;">💡 About WP Header Widget</h3>';
echo '<p style="color: #92400e; line-height: 1.6; margin: 0;">';
echo 'The WP Header widget automatically displays your WordPress logo and navigation menu with professional styling. ';
echo 'It\'s a complete header solution - just select your menu and customize the colors!';
echo '</p>';
echo '</div>';

echo '<div style="text-align: center; margin-top: 40px;">';
echo '<a href="' . admin_url('edit.php?post_type=pb_header') . '" class="button" style="font-size: 16px; padding: 15px 40px; background: #667eea; color: white;">View All Headers</a>';
echo '</div>';

echo '</div></body></html>';

