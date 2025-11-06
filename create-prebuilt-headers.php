<?php
/**
 * Create Prebuilt Headers for ProBuilder
 * 
 * Adds professional, ready-to-use header templates
 */

// Load WordPress
require_once('wp-load.php');

// Check permissions
if (!current_user_can('edit_posts')) {
    die('❌ You must be logged in as an editor or administrator to run this script.');
}

echo '<style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; background: #f9fafb; }
    .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
    h1 { color: #1f2937; margin-bottom: 10px; }
    .success { padding: 15px; background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; border-radius: 6px; margin: 15px 0; }
    .info { padding: 15px; background: #dbeafe; border-left: 4px solid #3b82f6; color: #1e40af; border-radius: 6px; margin: 15px 0; }
    .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
    .header-preview { margin: 20px 0; padding: 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: #f9fafb; }
    .header-preview h3 { color: #667eea; margin-top: 0; }
    code { background: #f3f4f6; padding: 2px 6px; border-radius: 3px; font-size: 14px; }
</style>';

echo '<div class="container">';
echo '<h1>🎨 Create Prebuilt Headers</h1>';
echo '<p>Adding professional, ready-to-use header templates to your ProBuilder...</p>';

// Helper function to generate unique ID
function generate_element_id() {
    return 'el_' . uniqid();
}

// Prebuilt header templates
$headers = [
    // 1. Classic Logo + Menu Header
    [
        'title' => '📌 Classic Logo + Menu',
        'description' => 'Traditional header with logo on left, navigation menu on right',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#ffffff',
                    'columns' => '2',
                    'gap' => '30',
                    'align_items' => 'center',
                ],
                'children' => [
                    // Logo
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Your Brand',
                            'tag' => 'h1',
                            'size' => '28px',
                            'color' => '#1f2937',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    // Menu
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'right',
                            'link_color' => '#6b7280',
                            'link_hover_color' => '#667eea',
                            'font_size' => '16px',
                            'spacing' => '30px',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    
    // 2. Centered Logo Header
    [
        'title' => '📌 Centered Logo',
        'description' => 'Elegant centered header with logo in the middle',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_padding' => '30px 20px',
                    '_background_color' => '#ffffff',
                    '_border_bottom_width' => '1px',
                    '_border_bottom_color' => '#e5e7eb',
                ],
                'children' => [
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Your Brand',
                            'tag' => 'h1',
                            'size' => '32px',
                            'color' => '#1f2937',
                            'weight' => '700',
                            'alignment' => 'center',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'spacer',
                        'settings' => ['_height' => '20px'],
                        'children' => []
                    ],
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'center',
                            'link_color' => '#6b7280',
                            'link_hover_color' => '#667eea',
                            'font_size' => '15px',
                            'spacing' => '25px',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    
    // 3. E-commerce Header with Cart
    [
        'title' => '🛒 E-commerce Header',
        'description' => 'Header with logo, search, and shopping cart icon',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#ffffff',
                    'columns' => '3',
                    'gap' => '30',
                    'align_items' => 'center',
                ],
                'children' => [
                    // Logo
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Shop',
                            'tag' => 'h1',
                            'size' => '28px',
                            'color' => '#667eea',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    // Menu
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'center',
                            'link_color' => '#6b7280',
                            'link_hover_color' => '#667eea',
                            'font_size' => '16px',
                            'spacing' => '25px',
                        ],
                        'children' => []
                    ],
                    // Cart Icon
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'woo-cart',
                        'settings' => [
                            'icon_size' => '24px',
                            'icon_color' => '#1f2937',
                            'show_count' => 'yes',
                            'count_bg_color' => '#667eea',
                            'alignment' => 'right',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    
    // 4. Minimal Header
    [
        'title' => '📌 Minimal & Clean',
        'description' => 'Minimalist header with clean design',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1200px',
                    '_padding' => '15px 20px',
                    '_background_color' => 'transparent',
                    'columns' => '2',
                    'gap' => '30',
                    'align_items' => 'center',
                ],
                'children' => [
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'text',
                        'settings' => [
                            'text' => 'Brand',
                            'size' => '24px',
                            'color' => '#1f2937',
                            'weight' => '600',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'right',
                            'link_color' => '#1f2937',
                            'link_hover_color' => '#667eea',
                            'font_size' => '14px',
                            'spacing' => '30px',
                            'font_weight' => '500',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    
    // 5. Transparent Header
    [
        'title' => '📌 Transparent (For Hero)',
        'description' => 'Transparent header that overlays hero sections',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '25px 20px',
                    '_background_color' => 'rgba(255, 255, 255, 0.95)',
                    '_backdrop_filter' => 'blur(10px)',
                    'columns' => '2',
                    'gap' => '30',
                    'align_items' => 'center',
                ],
                'children' => [
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Brand',
                            'tag' => 'h1',
                            'size' => '26px',
                            'color' => '#1f2937',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'right',
                            'link_color' => '#1f2937',
                            'link_hover_color' => '#667eea',
                            'font_size' => '15px',
                            'spacing' => '30px',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    
    // 6. Full-Width Dark Header
    [
        'title' => '📌 Dark Mode',
        'description' => 'Modern dark header with white text',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#1f2937',
                    'columns' => '2',
                    'gap' => '30',
                    'align_items' => 'center',
                ],
                'children' => [
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Your Brand',
                            'tag' => 'h1',
                            'size' => '28px',
                            'color' => '#ffffff',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'right',
                            'link_color' => '#d1d5db',
                            'link_hover_color' => '#ffffff',
                            'font_size' => '16px',
                            'spacing' => '30px',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
    
    // 7. Three Column Header
    [
        'title' => '📌 Logo + Menu + CTA',
        'description' => 'Header with logo, menu, and call-to-action button',
        'data' => [
            [
                'id' => generate_element_id(),
                'widgetType' => 'container',
                'settings' => [
                    '_width' => '100%',
                    '_max_width' => '1400px',
                    '_padding' => '20px',
                    '_background_color' => '#ffffff',
                    '_border_bottom_width' => '1px',
                    '_border_bottom_color' => '#e5e7eb',
                    'columns' => '3',
                    'gap' => '30',
                    'align_items' => 'center',
                ],
                'children' => [
                    // Logo
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'heading',
                        'settings' => [
                            'text' => 'Brand',
                            'tag' => 'h1',
                            'size' => '26px',
                            'color' => '#667eea',
                            'weight' => '700',
                        ],
                        'children' => []
                    ],
                    // Menu
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'menu',
                        'settings' => [
                            'menu_id' => 'primary',
                            'layout' => 'horizontal',
                            'alignment' => 'center',
                            'link_color' => '#6b7280',
                            'link_hover_color' => '#667eea',
                            'font_size' => '15px',
                            'spacing' => '25px',
                        ],
                        'children' => []
                    ],
                    // CTA Button
                    [
                        'id' => generate_element_id(),
                        'widgetType' => 'button',
                        'settings' => [
                            'text' => 'Get Started',
                            'link' => '#',
                            'bg_color' => '#667eea',
                            'text_color' => '#ffffff',
                            'padding' => '12px 30px',
                            'border_radius' => '6px',
                            'alignment' => 'right',
                            'hover_bg_color' => '#5568d3',
                        ],
                        'children' => []
                    ],
                ],
            ],
        ],
    ],
];

$created = [];
$skipped = [];

echo '<h2 style="margin-top: 30px; color: #1f2937;">Creating Headers...</h2>';

foreach ($headers as $header) {
    // Check if header with this title already exists
    $existing = get_posts([
        'post_type' => 'pb_header',
        'title' => $header['title'],
        'posts_per_page' => 1,
        'post_status' => 'any',
    ]);
    
    if (!empty($existing)) {
        $skipped[] = $header['title'];
        continue;
    }
    
    // Create header
    $post_id = wp_insert_post([
        'post_title' => $header['title'],
        'post_type' => 'pb_header',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    ]);
    
    if (!is_wp_error($post_id)) {
        // Save ProBuilder data
        update_post_meta($post_id, '_probuilder_data', $header['data']);
        update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
        
        $created[] = [
            'id' => $post_id,
            'title' => $header['title'],
            'description' => $header['description'],
        ];
    }
}

// Results
if (!empty($created)) {
    echo '<div class="success">';
    echo '<strong>✅ Created ' . count($created) . ' Prebuilt Headers!</strong>';
    echo '</div>';
    
    echo '<h2 style="margin-top: 30px; color: #1f2937;">📋 Your New Headers:</h2>';
    
    foreach ($created as $header) {
        echo '<div class="header-preview">';
        echo '<h3>' . esc_html($header['title']) . '</h3>';
        echo '<p style="color: #6b7280; margin: 10px 0;">' . esc_html($header['description']) . '</p>';
        echo '<p><strong>Shortcode:</strong> <code>[header id="' . $header['id'] . '"]</code></p>';
        echo '<p><strong>PHP:</strong> <code>&lt;?php echo do_shortcode(\'[header id="' . $header['id'] . '"]\'); ?&gt;</code></p>';
        echo '<div style="margin-top: 15px;">';
        echo '<a href="' . admin_url('?p=' . $header['id'] . '&probuilder=true&post_type=pb_header') . '" class="button" style="font-size: 14px; padding: 8px 20px;">Edit with ProBuilder</a>';
        echo '</div>';
        echo '</div>';
    }
}

if (!empty($skipped)) {
    echo '<div class="info">';
    echo '<strong>ℹ️ Skipped ' . count($skipped) . ' header(s) (already exist):</strong><br>';
    echo '<ul style="margin: 10px 0 0 20px;">';
    foreach ($skipped as $title) {
        echo '<li>' . esc_html($title) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

if (empty($created) && empty($skipped)) {
    echo '<div class="info">';
    echo '<strong>⚠️ No headers were created.</strong><br>';
    echo 'This might be due to a permissions issue or database error.';
    echo '</div>';
}

// Next steps
echo '<h2 style="margin-top: 40px; color: #1f2937;">📍 Next Steps:</h2>';
echo '<div class="info">';
echo '<ol style="margin: 10px 0 0 20px; line-height: 2;">';
echo '<li>Go to <strong>ProBuilder → Headers</strong> to see all headers</li>';
echo '<li>Click "Edit with ProBuilder" on any header to customize it</li>';
echo '<li>Use the shortcode to insert the header into pages</li>';
echo '<li>Or add to your theme\'s <code>header.php</code> file</li>';
echo '</ol>';
echo '</div>';

// Links
echo '<div style="text-align: center; margin: 40px 0;">';
echo '<a href="' . admin_url('edit.php?post_type=pb_header') . '" class="button" style="font-size: 16px; padding: 15px 40px;">View All Headers</a>';
echo '<a href="' . admin_url('admin.php?page=probuilder-parts') . '" class="button" style="background: #6b7280;">ProBuilder Dashboard</a>';
echo '</div>';

// Explanation
echo '<div style="background: #fef3c7; padding: 20px; border-radius: 8px; border-left: 4px solid #f59e0b; margin-top: 30px;">';
echo '<h3 style="color: #92400e; margin-top: 0;">💡 About Prebuilt Headers</h3>';
echo '<p style="color: #92400e; line-height: 1.6;">';
echo 'These are starter templates that you can customize to match your brand. ';
echo 'Edit the logo text, colors, menu styles, and layout using ProBuilder. ';
echo 'Each header is fully editable and ready to use!';
echo '</p>';
echo '</div>';

echo '</div>';

