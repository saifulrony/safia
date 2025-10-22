<?php
/**
 * Add Product Images to ALL WooCommerce Products
 * This script downloads images and assigns them to products
 */

// Load WordPress
require_once(__DIR__ . '/wp-load.php');

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    die("WooCommerce is not installed or activated.\n");
}

echo "========================================\n";
echo "Adding Images to ALL Products\n";
echo "========================================\n\n";

// Comprehensive product image mappings
$product_images = [
    // Electronics
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
    'Portable Bluetooth Speaker' => [
        'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&q=80',
        'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=800&q=80'
    ],
    
    // Fashion
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
    'Running Sneakers Pro' => [
        'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
        'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&q=80'
    ],
    'Leather Wallet' => [
        'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
        'https://images.unsplash.com/photo-1606919725968-fb009069c53a?w=800&q=80'
    ],
    'Leather Wallet Premium' => [
        'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&q=80',
        'https://images.unsplash.com/photo-1606919725968-fb009069c53a?w=800&q=80'
    ],
    
    // Home & Living
    'Modern Table Lamp' => [
        'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&q=80',
        'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&q=80'
    ],
    'Modern LED Table Lamp' => [
        'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&q=80',
        'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&q=80'
    ],
    'Bamboo Bed Sheets' => [
        'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80',
        'https://images.unsplash.com/photo-1616627977421-1543e6180e4e?w=800&q=80'
    ],
    'Organic Bamboo Bed Sheets' => [
        'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80',
        'https://images.unsplash.com/photo-1616627977421-1543e6180e4e?w=800&q=80'
    ],
    'Indoor Plant Set' => [
        'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=800&q=80',
        'https://images.unsplash.com/photo-1459156212016-c812468e2115?w=800&q=80'
    ],
    'Indoor Plant Collection' => [
        'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=800&q=80',
        'https://images.unsplash.com/photo-1459156212016-c812468e2115?w=800&q=80'
    ],
    
    // Kitchen
    'Kitchen Knife Set' => [
        'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=800&q=80',
        'https://images.unsplash.com/photo-1594591037318-efc6c20ae187?w=800&q=80'
    ],
    'Professional Knife Set' => [
        'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=800&q=80',
        'https://images.unsplash.com/photo-1594591037318-efc6c20ae187?w=800&q=80'
    ],
    
    // Fitness
    'Yoga Mat Premium' => [
        'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&q=80',
        'https://images.unsplash.com/photo-1592432678016-e910b452f9a2?w=800&q=80'
    ],
    'Premium Yoga Mat' => [
        'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&q=80',
        'https://images.unsplash.com/photo-1592432678016-e910b452f9a2?w=800&q=80'
    ],
    'Adjustable Dumbbells' => [
        'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=800&q=80',
        'https://images.unsplash.com/photo-1598266663439-2056e6900339?w=800&q=80'
    ],
    'Adjustable Dumbbells Set' => [
        'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=800&q=80',
        'https://images.unsplash.com/photo-1598266663439-2056e6900339?w=800&q=80'
    ],
    'Resistance Bands Set' => [
        'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=800&q=80',
        'https://images.unsplash.com/photo-1598971639058-fab3c3109a00?w=800&q=80'
    ],
    
    // Outdoor & Camping
    'Camping Tent 4-Person' => [
        'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&q=80',
        'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=800&q=80'
    ],
    'Family Camping Tent' => [
        'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&q=80',
        'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=800&q=80'
    ],
    
    // Beauty & Personal Care
    'Organic Facial Serum' => [
        'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&q=80',
        'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=800&q=80'
    ],
    'Essential Oils Set' => [
        'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&q=80',
        'https://images.unsplash.com/photo-1600428877325-300d0f662c2e?w=800&q=80'
    ],
    'Essential Oils Gift Set' => [
        'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&q=80',
        'https://images.unsplash.com/photo-1600428877325-300d0f662c2e?w=800&q=80'
    ],
    'Moisturizing Face Cream' => [
        'https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?w=800&q=80',
        'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=800&q=80'
    ],
    'Daily Moisturizing Cream' => [
        'https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?w=800&q=80',
        'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=800&q=80'
    ],
    'Hair Care Gift Set' => [
        'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=800&q=80',
        'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=800&q=80'
    ]
];

// Function to download image from URL
function download_image($image_url, $product_title) {
    $upload_dir = wp_upload_dir();
    
    // Create a safe filename
    $filename = sanitize_file_name($product_title . '-' . time() . '-' . rand(1000, 9999) . '.jpg');
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    // Download the image with timeout and error handling
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
    
    // Save the image
    if (@file_put_contents($file_path, $image_data) === false) {
        echo "  ✗ Failed to save image\n";
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
    
    echo "  ✓ Image added (ID: $attach_id)\n";
    return $attach_id;
}

// Get all products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'ID',
    'order' => 'ASC'
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
    
    echo "[$product_id] $product_title\n";
    
    // Check if product already has an image
    if (has_post_thumbnail($product_id)) {
        echo "  ✓ Already has image\n\n";
        $skip_count++;
        continue;
    }
    
    // Get images for this product
    if (!isset($product_images[$product_title])) {
        echo "  ⚠ No images configured\n\n";
        $skip_count++;
        continue;
    }
    
    $images = $product_images[$product_title];
    $image_ids = array();
    
    // Download and attach images
    foreach ($images as $index => $image_url) {
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
        usleep(500000); // 0.5 seconds
    }
    
    // Add gallery images if we have more than one
    if (count($image_ids) > 1) {
        $product = wc_get_product($product_id);
        if ($product) {
            $gallery_ids = array_slice($image_ids, 1);
            $product->set_gallery_image_ids($gallery_ids);
            $product->save();
            echo "  ✓ Added gallery images\n";
        }
    }
    
    if (!empty($image_ids)) {
        echo "  ✓ Complete!\n\n";
        $success_count++;
    } else {
        echo "  ✗ Failed to add images\n\n";
        $skip_count++;
    }
}

echo "========================================\n";
echo "COMPLETE!\n";
echo "========================================\n";
echo "✓ Products with images: $success_count\n";
echo "⚠ Products skipped: $skip_count\n";
echo "✓ Total products: " . count($products) . "\n\n";
echo "Visit your shop to see the results:\n";
echo "http://192.168.10.203:7000/shop/\n";

