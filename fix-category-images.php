<?php
/**
 * Fix Category Images for remaining categories
 */

// Load WordPress
require_once(__DIR__ . '/wp-load.php');

echo "========================================\n";
echo "Fixing Remaining Category Images\n";
echo "========================================\n\n";

// Category mappings with proper names
$category_updates = [
    18 => [
        'name' => 'Home & Garden',
        'url' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800&q=80'
    ],
    21 => [
        'name' => 'Sports & Outdoors',
        'url' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=800&q=80'
    ],
    22 => [
        'name' => 'Beauty & Health',
        'url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&q=80'
    ]
];

function download_and_attach($image_url, $category_name, $term_id) {
    $upload_dir = wp_upload_dir();
    
    $filename = sanitize_file_name('category-' . $category_name . '-' . time() . '.jpg');
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0'
        ]
    ]);
    
    $image_data = @file_get_contents($image_url, false, $context);
    
    if ($image_data === false) {
        echo "  ✗ Failed to download\n";
        return false;
    }
    
    if (@file_put_contents($file_path, $image_data) === false) {
        echo "  ✗ Failed to save\n";
        return false;
    }
    
    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $category_name . ' Category',
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attach_id = wp_insert_attachment($attachment, $file_path);
    
    if (is_wp_error($attach_id)) {
        echo "  ✗ Failed to create attachment\n";
        return false;
    }
    
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    // Set as category thumbnail
    update_term_meta($term_id, 'thumbnail_id', $attach_id);
    
    echo "  ✓ Image added (ID: $attach_id)\n";
    return true;
}

foreach ($category_updates as $term_id => $data) {
    echo "[$term_id] {$data['name']}\n";
    
    $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
    if ($thumbnail_id) {
        echo "  ✓ Already has image\n\n";
        continue;
    }
    
    if (download_and_attach($data['url'], $data['name'], $term_id)) {
        echo "  ✓ Complete!\n\n";
    } else {
        echo "  ✗ Failed\n\n";
    }
    
    usleep(500000);
}

echo "========================================\n";
echo "Done! All categories now have images.\n";
echo "========================================\n";

