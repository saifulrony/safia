<?php
/**
 * Add Sample Headers - SIMPLE VERSION
 * Creates basic header templates immediately
 */

require_once('wp-load.php');

if (!current_user_can('edit_posts')) {
    die('❌ Permission denied');
}

function gen_id() {
    return 'el_' . uniqid();
}

// Simple header data
$headers_to_create = [
    [
        'title' => 'Classic Header - Logo Left Menu Right',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#ffffff',
                    'columns' => '2',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => gen_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'YOUR BRAND',
                            'tag' => 'h1',
                            'size' => '28px',
                            'color' => '#1f2937',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Home | About | Shop | Contact',
                            'size' => '16px',
                            'color' => '#6b7280',
                            'alignment' => 'right',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'E-commerce Header - With Cart',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#ffffff',
                    '_border_bottom_width' => '1px',
                    '_border_bottom_color' => '#e5e7eb',
                    'columns' => '3',
                    'gap' => '20',
                ],
                'children' => [
                    [
                        'id' => gen_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => '🛒 SHOP',
                            'tag' => 'h1',
                            'size' => '28px',
                            'color' => '#667eea',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Men | Women | Kids | Sale',
                            'size' => '16px',
                            'color' => '#6b7280',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => '🔍 Search | 🛒 Cart (0)',
                            'size' => '16px',
                            'color' => '#1f2937',
                            'alignment' => 'right',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'Centered Logo Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '30px 20px',
                    '_background_color' => '#ffffff',
                ],
                'children' => [
                    [
                        'id' => gen_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'YOUR BRAND',
                            'tag' => 'h1',
                            'size' => '36px',
                            'color' => '#1f2937',
                            'weight' => '700',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'spacer',
                        'settings' => ['_height' => '15px'],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Home | Shop | About | Blog | Contact',
                            'size' => '15px',
                            'color' => '#6b7280',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'Dark Mode Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#1f2937',
                    'columns' => '2',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => gen_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'BRAND',
                            'tag' => 'h1',
                            'size' => '28px',
                            'color' => '#ffffff',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Home | About | Services | Contact',
                            'size' => '16px',
                            'color' => '#d1d5db',
                            'alignment' => 'right',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    [
        'title' => 'Minimal Header',
        'data' => [
            [
                'id' => gen_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1200px',
                    '_padding' => '15px 20px',
                    'columns' => '2',
                    'gap' => '30',
                ],
                'children' => [
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Brand',
                            'size' => '22px',
                            'color' => '#1f2937',
                            'weight' => '600',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => gen_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Home | Blog | About',
                            'size' => '14px',
                            'color' => '#6b7280',
                            'alignment' => 'right',
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
.container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
h1 { color: #1f2937; }
.success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
.item { padding: 15px; background: #f9fafb; border-radius: 6px; margin: 15px 0; border-left: 4px solid #667eea; }
.button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
code { background: #f3f4f6; padding: 2px 8px; border-radius: 4px; font-size: 13px; }
</style></head><body>';

echo '<div class="container">';
echo '<h1>🎨 Creating Sample Headers...</h1>';

$created = 0;
$errors = [];

foreach ($headers_to_create as $header) {
    // Check if exists
    $existing = get_posts([
        'post_type' => 'pb_header',
        'title' => $header['title'],
        'posts_per_page' => 1,
        'post_status' => 'any',
    ]);
    
    if (!empty($existing)) {
        echo '<div class="item">ℹ️ <strong>' . esc_html($header['title']) . '</strong> - Already exists (ID: ' . $existing[0]->ID . ')</div>';
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
        $errors[] = $header['title'] . ': ' . $post_id->get_error_message();
        continue;
    }
    
    // Save data
    update_post_meta($post_id, '_probuilder_data', $header['data']);
    update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
    
    echo '<div class="success">✅ <strong>Created: ' . esc_html($header['title']) . '</strong><br>';
    echo '<small>ID: ' . $post_id . ' | Shortcode: <code>[header id="' . $post_id . '"]</code></small></div>';
    
    $created++;
}

// Summary
echo '<hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">';

if ($created > 0) {
    echo '<div class="success">';
    echo '<h2 style="margin-top: 0;">🎉 Success!</h2>';
    echo '<p><strong>' . $created . ' header(s) created!</strong></p>';
    echo '</div>';
}

if (!empty($errors)) {
    echo '<div style="padding: 15px; background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; border-radius: 6px; margin: 15px 0;">';
    echo '<strong>❌ Errors:</strong><ul>';
    foreach ($errors as $error) {
        echo '<li>' . esc_html($error) . '</li>';
    }
    echo '</ul></div>';
}

echo '<h2>📍 Next Steps:</h2>';
echo '<ol>';
echo '<li>Go to <strong>ProBuilder → Headers</strong></li>';
echo '<li>You should see your new headers</li>';
echo '<li>Click "Edit with ProBuilder" to customize them</li>';
echo '<li>Use shortcode to insert into pages</li>';
echo '</ol>';

echo '<div style="text-align: center; margin-top: 40px;">';
echo '<a href="' . admin_url('edit.php?post_type=pb_header') . '" class="button" style="font-size: 16px; padding: 15px 40px;">📌 View Headers Now</a>';
echo '<a href="' . admin_url('admin.php?page=probuilder-parts') . '" class="button" style="background: #6b7280;">ProBuilder Dashboard</a>';
echo '</div>';

echo '<div style="margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b;">';
echo '<h3 style="color: #92400e; margin-top: 0;">💡 How to Use</h3>';
echo '<p style="color: #92400e;"><strong>In theme header.php:</strong></p>';
echo '<code style="display: block; padding: 10px; background: white; color: #dc2626;">&lt;?php echo do_shortcode(\'[header id="123"]\'); ?&gt;</code>';
echo '<p style="color: #92400e; margin-top: 15px;"><strong>In page content:</strong></p>';
echo '<code style="display: block; padding: 10px; background: white; color: #dc2626;">[header id="123"]</code>';
echo '</div>';

echo '</div></body></html>';

