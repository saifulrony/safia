<?php
/**
 * Add Category Images to WooCommerce Product Categories
 */

// Load WordPress
require_once(__DIR__ . '/wp-load.php');

if (!class_exists('WooCommerce')) {
    die("WooCommerce is not installed or activated.\n");
}

echo "========================================\n";
echo "Adding Category Images\n";
echo "========================================\n\n";

// Category image mappings
$category_images = [
    'Electronics' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800&q=80',
    'Clothing' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=800&q=80',
    'Home & Garden' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800&q=80',
    'Sports' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&q=80',
    'Beauty' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&q=80',
    'Sports & Outdoors' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=800&q=80',
    'Beauty & Health' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&q=80'
];

// Function to download and attach image
function download_category_image($image_url, $category_name) {
    $upload_dir = wp_upload_dir();
    
    $filename = sanitize_file_name('category-' . $category_name . '-' . time() . '.jpg');
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    // Download image
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);
    
    $image_data = @file_get_contents($image_url, false, $context);
    
    if ($image_data === false) {
        echo "  ✗ Failed to download image\n";
        return false;
    }
    
    if (@file_put_contents($file_path, $image_data) === false) {
        echo "  ✗ Failed to save image\n";
        return false;
    }
    
    // Create attachment
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
    
    // Generate metadata
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    echo "  ✓ Image downloaded (ID: $attach_id)\n";
    return $attach_id;
}

// Get all product categories
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'exclude' => [15] // Exclude 'Uncategorized'
]);

if (empty($categories) || is_wp_error($categories)) {
    echo "No categories found.\n";
    exit;
}

echo "Found " . count($categories) . " categories.\n\n";

$success_count = 0;
$skip_count = 0;

foreach ($categories as $category) {
    $category_name = $category->name;
    $category_id = $category->term_id;
    
    // Skip numeric category names
    if (is_numeric($category_name)) {
        echo "[$category_id] Skipping invalid category: $category_name\n\n";
        $skip_count++;
        continue;
    }
    
    echo "[$category_id] $category_name\n";
    
    // Check if category already has an image
    $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
    if ($thumbnail_id) {
        echo "  ✓ Already has image\n\n";
        $skip_count++;
        continue;
    }
    
    // Get image URL for this category
    if (!isset($category_images[$category_name])) {
        echo "  ⚠ No image configured\n\n";
        $skip_count++;
        continue;
    }
    
    $image_url = $category_images[$category_name];
    
    // Download and attach image
    $image_id = download_category_image($image_url, $category_name);
    
    if ($image_id) {
        // Set as category thumbnail
        update_term_meta($category_id, 'thumbnail_id', $image_id);
        echo "  ✓ Set as category image\n";
        echo "  ✓ Complete!\n\n";
        $success_count++;
    } else {
        echo "  ✗ Failed\n\n";
        $skip_count++;
    }
    
    usleep(500000); // 0.5 second delay
}

echo "========================================\n";
echo "COMPLETE!\n";
echo "========================================\n";
echo "✓ Categories with images: $success_count\n";
echo "⚠ Categories skipped: $skip_count\n";
echo "✓ Total categories: " . count($categories) . "\n\n";
echo "View your categories:\n";
echo "http://192.168.10.203:7000/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product\n";

