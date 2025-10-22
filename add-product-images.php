<?php
/**
 * Add Product Images to WooCommerce Products
 * This script downloads images and assigns them to products
 */

// Load WordPress
require_once(__DIR__ . '/wp-load.php');

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    die("WooCommerce is not installed or activated.\n");
}

echo "========================================\n";
echo "Adding Product Images to WooCommerce\n";
echo "========================================\n\n";

// Product image mappings with real product images
$product_images = [
    'Premium Wireless Headphones' => [
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
        'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&q=80'
    ],
    'Smart Watch Pro' => [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80',
        'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&q=80'
    ],
    'Ultra HD 4K Camera' => [
        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&q=80',
        'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&q=80'
    ],
    'Wireless Gaming Mouse' => [
        'https://images.unsplash.com/photo-1527814050087-3793815479db?w=800&q=80',
        'https://images.unsplash.com/photo-1563297007-0686b7003af7?w=800&q=80'
    ],
    'Bluetooth Speaker' => [
        'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&q=80',
        'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=800&q=80'
    ],
    'Classic Cotton T-Shirt' => [
        'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80',
        'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800&q=80'
    ],
    'Premium Denim Jeans' => [
        'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=80',
        'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?w=800&q=80'
    ],
    'Winter Warm Jacket' => [
        'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&q=80',
        'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&q=80'
    ],
    'Running Sneakers' => [
        'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
        'https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?w=800&q=80'
    ],
    'Leather Wallet' => [
        'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
        'https://images.unsplash.com/photo-1612357223799-7057c0f5b5c7?w=800&q=80'
    ]
];

// Function to download image from URL
function download_image($image_url, $product_title) {
    $upload_dir = wp_upload_dir();
    
    // Create a safe filename
    $filename = sanitize_file_name($product_title . '-' . time() . '-' . rand(1000, 9999) . '.jpg');
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    // Download the image
    $image_data = @file_get_contents($image_url);
    
    if ($image_data === false) {
        echo "  ✗ Failed to download image from: $image_url\n";
        return false;
    }
    
    // Save the image
    if (@file_put_contents($file_path, $image_data) === false) {
        echo "  ✗ Failed to save image to: $file_path\n";
        return false;
    }
    
    // Prepare the attachment data
    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($product_title),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    // Insert the attachment
    $attach_id = wp_insert_attachment($attachment, $file_path);
    
    if (is_wp_error($attach_id)) {
        echo "  ✗ Failed to create attachment\n";
        return false;
    }
    
    // Generate attachment metadata
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    echo "  ✓ Downloaded and saved image (ID: $attach_id)\n";
    return $attach_id;
}

// Get all products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);

$products = get_posts($args);

if (empty($products)) {
    echo "No products found.\n";
    exit;
}

echo "Found " . count($products) . " products.\n\n";

$success_count = 0;
$skip_count = 0;

// Process each product
foreach ($products as $product_post) {
    $product_title = $product_post->post_title;
    $product_id = $product_post->ID;
    
    echo "[$product_id] Processing: $product_title\n";
    
    // Check if product already has an image
    if (has_post_thumbnail($product_id)) {
        echo "  ⚠ Product already has a featured image, skipping...\n\n";
        $skip_count++;
        continue;
    }
    
    // Get images for this product
    if (!isset($product_images[$product_title])) {
        echo "  ⚠ No images configured for this product\n\n";
        $skip_count++;
        continue;
    }
    
    $images = $product_images[$product_title];
    $image_ids = array();
    
    // Download and attach images
    foreach ($images as $index => $image_url) {
        echo "  Downloading image " . ($index + 1) . "...\n";
        $image_id = download_image($image_url, $product_title);
        
        if ($image_id) {
            $image_ids[] = $image_id;
            
            // Set first image as featured image
            if ($index === 0) {
                set_post_thumbnail($product_id, $image_id);
                echo "  ✓ Set as featured image\n";
            }
        }
        
        // Small delay to avoid rate limiting
        sleep(1);
    }
    
    // Add gallery images if we have more than one
    if (count($image_ids) > 1) {
        // Get product object
        $product = wc_get_product($product_id);
        if ($product) {
            // Set gallery images (excluding the first one which is featured)
            $gallery_ids = array_slice($image_ids, 1);
            $product->set_gallery_image_ids($gallery_ids);
            $product->save();
            echo "  ✓ Added " . count($gallery_ids) . " gallery image(s)\n";
        }
    }
    
    echo "  ✓ Product images added successfully!\n\n";
    $success_count++;
}

echo "========================================\n";
echo "Process Complete!\n";
echo "========================================\n";
echo "✓ Products updated: $success_count\n";
echo "⚠ Products skipped: $skip_count\n";
echo "✓ Total products: " . count($products) . "\n";
echo "\nAll product images have been added!\n";
echo "Visit your shop page to see the results.\n";

