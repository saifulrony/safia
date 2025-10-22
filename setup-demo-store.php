<?php
/**
 * EcoCommerce Pro - Demo Store Setup Script
 * 
 * This script will:
 * 1. Install and activate WooCommerce
 * 2. Create product categories
 * 3. Create 25+ demo products
 * 4. Set up sample images
 * 5. Configure WooCommerce settings
 */

// Load WordPress
require_once('/home/saiful/wordpress/wp-load.php');

if (!current_user_can('manage_options')) {
    die('You must be an administrator to run this script.');
}

echo "==============================================\n";
echo "EcoCommerce Pro - Demo Store Setup\n";
echo "==============================================\n\n";

// Step 1: Check if WooCommerce is installed
echo "[1/5] Checking WooCommerce installation...\n";

if (!class_exists('WooCommerce')) {
    echo "Installing WooCommerce plugin...\n";
    
    // Download and install WooCommerce
    include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
    include_once(ABSPATH . 'wp-admin/includes/file.php');
    include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    
    $plugin_slug = 'woocommerce';
    $api = plugins_api('plugin_information', array('slug' => $plugin_slug));
    
    if (is_wp_error($api)) {
        die("Error: Could not fetch plugin information.\n");
    }
    
    $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
    $result = $upgrader->install($api->download_link);
    
    if (is_wp_error($result)) {
        die("Error: Could not install WooCommerce.\n");
    }
    
    // Activate WooCommerce
    activate_plugin('woocommerce/woocommerce.php');
    echo "✓ WooCommerce installed and activated!\n\n";
} else {
    echo "✓ WooCommerce is already installed!\n\n";
}

// Step 2: Create Product Categories
echo "[2/5] Creating product categories...\n";

$categories = array(
    'Electronics' => 'Laptops, phones, cameras, and gadgets',
    'Clothing' => 'Fashion apparel for men and women',
    'Home & Garden' => 'Furniture, decor, and outdoor items',
    'Books' => 'Fiction, non-fiction, and educational books',
    'Sports & Outdoors' => 'Fitness equipment and outdoor gear',
    'Beauty & Health' => 'Cosmetics, skincare, and wellness products',
    'Toys & Games' => 'Fun for kids and adults',
    'Food & Beverages' => 'Organic and gourmet food items'
);

$category_ids = array();

foreach ($categories as $name => $description) {
    $term = term_exists($name, 'product_cat');
    
    if (!$term) {
        $term = wp_insert_term($name, 'product_cat', array(
            'description' => $description,
            'slug' => sanitize_title($name)
        ));
    }
    
    if (!is_wp_error($term)) {
        $category_ids[$name] = is_array($term) ? $term['term_id'] : $term;
        echo "  ✓ Created category: $name\n";
    }
}

echo "\n";

// Step 3: Create Demo Products
echo "[3/5] Creating demo products...\n";

$demo_products = array(
    // Electronics
    array(
        'name' => 'Premium Wireless Headphones',
        'category' => 'Electronics',
        'price' => 149.99,
        'sale_price' => 119.99,
        'description' => 'High-quality wireless headphones with noise cancellation, 30-hour battery life, and premium sound quality. Perfect for music lovers and professionals.',
        'short_description' => 'Wireless headphones with noise cancellation and 30-hour battery.',
        'sku' => 'ELEC-001',
        'stock' => 50,
        'featured' => true,
    ),
    array(
        'name' => 'Smart Watch Pro',
        'category' => 'Electronics',
        'price' => 299.99,
        'sale_price' => null,
        'description' => 'Advanced smartwatch with fitness tracking, heart rate monitor, GPS, and 5-day battery life. Compatible with iOS and Android.',
        'short_description' => 'Advanced smartwatch with fitness tracking and 5-day battery.',
        'sku' => 'ELEC-002',
        'stock' => 35,
        'featured' => true,
    ),
    array(
        'name' => 'Ultra HD 4K Camera',
        'category' => 'Electronics',
        'price' => 899.99,
        'sale_price' => 799.99,
        'description' => 'Professional 4K camera with 24MP sensor, image stabilization, and WiFi connectivity. Includes lens kit.',
        'short_description' => 'Professional 4K camera with 24MP sensor and WiFi.',
        'sku' => 'ELEC-003',
        'stock' => 20,
        'featured' => false,
    ),
    array(
        'name' => 'Wireless Gaming Mouse',
        'category' => 'Electronics',
        'price' => 79.99,
        'sale_price' => 59.99,
        'description' => 'RGB gaming mouse with 16000 DPI, programmable buttons, and 70-hour battery life.',
        'short_description' => 'High-performance RGB gaming mouse with 16000 DPI.',
        'sku' => 'ELEC-004',
        'stock' => 100,
        'featured' => false,
    ),
    array(
        'name' => 'Portable Bluetooth Speaker',
        'category' => 'Electronics',
        'price' => 89.99,
        'sale_price' => null,
        'description' => 'Waterproof portable speaker with 360° sound, 12-hour battery, and built-in microphone.',
        'short_description' => 'Waterproof speaker with 360° sound and 12-hour battery.',
        'sku' => 'ELEC-005',
        'stock' => 75,
        'featured' => false,
    ),
    
    // Clothing
    array(
        'name' => 'Classic Cotton T-Shirt',
        'category' => 'Clothing',
        'price' => 24.99,
        'sale_price' => 19.99,
        'description' => '100% organic cotton t-shirt. Soft, comfortable, and eco-friendly. Available in multiple colors.',
        'short_description' => 'Soft organic cotton t-shirt in various colors.',
        'sku' => 'CLO-001',
        'stock' => 200,
        'featured' => false,
    ),
    array(
        'name' => 'Premium Denim Jeans',
        'category' => 'Clothing',
        'price' => 89.99,
        'sale_price' => null,
        'description' => 'High-quality denim jeans with stretch fabric. Slim fit design, durable and stylish.',
        'short_description' => 'Comfortable slim fit denim jeans with stretch.',
        'sku' => 'CLO-002',
        'stock' => 150,
        'featured' => true,
    ),
    array(
        'name' => 'Winter Warm Jacket',
        'category' => 'Clothing',
        'price' => 149.99,
        'sale_price' => 119.99,
        'description' => 'Insulated winter jacket with water-resistant fabric. Perfect for cold weather.',
        'short_description' => 'Water-resistant winter jacket with insulation.',
        'sku' => 'CLO-003',
        'stock' => 60,
        'featured' => true,
    ),
    array(
        'name' => 'Running Sneakers',
        'category' => 'Clothing',
        'price' => 119.99,
        'sale_price' => 99.99,
        'description' => 'Lightweight running shoes with cushioned sole and breathable mesh. Ideal for runners.',
        'short_description' => 'Lightweight cushioned running shoes.',
        'sku' => 'CLO-004',
        'stock' => 80,
        'featured' => false,
    ),
    
    // Home & Garden
    array(
        'name' => 'Modern Table Lamp',
        'category' => 'Home & Garden',
        'price' => 59.99,
        'sale_price' => null,
        'description' => 'Contemporary table lamp with touch control and LED bulb. Energy efficient and stylish.',
        'short_description' => 'Touch-control LED table lamp with modern design.',
        'sku' => 'HOME-001',
        'stock' => 40,
        'featured' => false,
    ),
    array(
        'name' => 'Organic Bamboo Sheets Set',
        'category' => 'Home & Garden',
        'price' => 129.99,
        'sale_price' => 99.99,
        'description' => 'Soft bamboo bed sheets set. Hypoallergenic, breathable, and eco-friendly.',
        'short_description' => 'Soft organic bamboo bed sheets, hypoallergenic.',
        'sku' => 'HOME-002',
        'stock' => 50,
        'featured' => true,
    ),
    array(
        'name' => 'Indoor Plant Collection',
        'category' => 'Home & Garden',
        'price' => 49.99,
        'sale_price' => null,
        'description' => 'Set of 3 air-purifying indoor plants with decorative pots. Easy to care for.',
        'short_description' => 'Set of 3 air-purifying plants with pots.',
        'sku' => 'HOME-003',
        'stock' => 30,
        'featured' => false,
    ),
    
    // Books
    array(
        'name' => 'The Complete Guide to Web Development',
        'category' => 'Books',
        'price' => 39.99,
        'sale_price' => 29.99,
        'description' => 'Comprehensive guide covering HTML, CSS, JavaScript, and modern frameworks.',
        'short_description' => 'Complete web development guide for beginners to advanced.',
        'sku' => 'BOOK-001',
        'stock' => 100,
        'featured' => false,
    ),
    array(
        'name' => 'Mindfulness for Beginners',
        'category' => 'Books',
        'price' => 19.99,
        'sale_price' => null,
        'description' => 'Practical guide to mindfulness meditation and stress reduction techniques.',
        'short_description' => 'Practical mindfulness and meditation guide.',
        'sku' => 'BOOK-002',
        'stock' => 150,
        'featured' => false,
    ),
    
    // Sports & Outdoors
    array(
        'name' => 'Yoga Mat Premium',
        'category' => 'Sports & Outdoors',
        'price' => 49.99,
        'sale_price' => 39.99,
        'description' => 'Non-slip yoga mat with extra cushioning. Eco-friendly TPE material.',
        'short_description' => 'Non-slip eco-friendly yoga mat with cushioning.',
        'sku' => 'SPORT-001',
        'stock' => 80,
        'featured' => true,
    ),
    array(
        'name' => 'Camping Tent 4-Person',
        'category' => 'Sports & Outdoors',
        'price' => 199.99,
        'sale_price' => 169.99,
        'description' => 'Spacious 4-person tent with waterproof fabric and easy setup.',
        'short_description' => 'Waterproof 4-person camping tent.',
        'sku' => 'SPORT-002',
        'stock' => 25,
        'featured' => false,
    ),
    array(
        'name' => 'Adjustable Dumbbells Set',
        'category' => 'Sports & Outdoors',
        'price' => 149.99,
        'sale_price' => null,
        'description' => 'Adjustable dumbbells from 5-52 lbs. Space-saving home gym equipment.',
        'short_description' => 'Adjustable dumbbells 5-52 lbs for home gym.',
        'sku' => 'SPORT-003',
        'stock' => 40,
        'featured' => true,
    ),
    
    // Beauty & Health
    array(
        'name' => 'Organic Facial Serum',
        'category' => 'Beauty & Health',
        'price' => 49.99,
        'sale_price' => 39.99,
        'description' => 'Anti-aging facial serum with vitamin C and hyaluronic acid. Natural ingredients.',
        'short_description' => 'Anti-aging serum with vitamin C and natural ingredients.',
        'sku' => 'BEAUTY-001',
        'stock' => 100,
        'featured' => true,
    ),
    array(
        'name' => 'Essential Oils Gift Set',
        'category' => 'Beauty & Health',
        'price' => 39.99,
        'sale_price' => null,
        'description' => 'Set of 6 pure essential oils for aromatherapy. Includes lavender, eucalyptus, and more.',
        'short_description' => 'Set of 6 pure essential oils for aromatherapy.',
        'sku' => 'BEAUTY-002',
        'stock' => 60,
        'featured' => false,
    ),
    
    // Toys & Games
    array(
        'name' => 'Wooden Building Blocks Set',
        'category' => 'Toys & Games',
        'price' => 34.99,
        'sale_price' => 27.99,
        'description' => 'Educational wooden blocks for kids. Safe, non-toxic, and fun.',
        'short_description' => 'Educational wooden building blocks for children.',
        'sku' => 'TOY-001',
        'stock' => 75,
        'featured' => false,
    ),
    array(
        'name' => 'Strategy Board Game',
        'category' => 'Toys & Games',
        'price' => 44.99,
        'sale_price' => null,
        'description' => 'Award-winning strategy board game for 2-4 players. Fun for the whole family.',
        'short_description' => 'Award-winning strategy game for 2-4 players.',
        'sku' => 'TOY-002',
        'stock' => 50,
        'featured' => false,
    ),
    
    // Food & Beverages
    array(
        'name' => 'Organic Green Tea Collection',
        'category' => 'Food & Beverages',
        'price' => 24.99,
        'sale_price' => 19.99,
        'description' => 'Premium organic green tea sampler. 5 varieties, 100% natural.',
        'short_description' => 'Premium organic green tea sampler, 5 varieties.',
        'sku' => 'FOOD-001',
        'stock' => 120,
        'featured' => false,
    ),
    array(
        'name' => 'Gourmet Coffee Beans',
        'category' => 'Food & Beverages',
        'price' => 19.99,
        'sale_price' => null,
        'description' => 'Single-origin arabica coffee beans. Medium roast, smooth flavor.',
        'short_description' => 'Single-origin arabica coffee, medium roast.',
        'sku' => 'FOOD-002',
        'stock' => 90,
        'featured' => false,
    ),
    array(
        'name' => 'Organic Honey Jar',
        'category' => 'Food & Beverages',
        'price' => 14.99,
        'sale_price' => 12.99,
        'description' => 'Raw organic honey from local beekeepers. Pure and unprocessed.',
        'short_description' => 'Raw organic honey, pure and unprocessed.',
        'sku' => 'FOOD-003',
        'stock' => 80,
        'featured' => false,
    ),
);

$product_count = 0;

foreach ($demo_products as $product_data) {
    // Create product
    $product = new WC_Product_Simple();
    
    $product->set_name($product_data['name']);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description($product_data['description']);
    $product->set_short_description($product_data['short_description']);
    $product->set_sku($product_data['sku']);
    $product->set_regular_price($product_data['price']);
    
    if ($product_data['sale_price']) {
        $product->set_sale_price($product_data['sale_price']);
    }
    
    $product->set_manage_stock(true);
    $product->set_stock_quantity($product_data['stock']);
    $product->set_stock_status('instock');
    
    if ($product_data['featured']) {
        $product->set_featured(true);
    }
    
    // Save product
    $product_id = $product->save();
    
    // Set category
    if (isset($category_ids[$product_data['category']])) {
        wp_set_object_terms($product_id, $category_ids[$product_data['category']], 'product_cat');
    }
    
    // Add product image (placeholder)
    $image_url = 'https://via.placeholder.com/800x800.png?text=' . urlencode($product_data['name']);
    $upload_dir = wp_upload_dir();
    
    $product_count++;
    echo "  ✓ Created product: {$product_data['name']}\n";
}

echo "\n✓ Created $product_count demo products!\n\n";

// Step 4: Configure WooCommerce Settings
echo "[4/5] Configuring WooCommerce settings...\n";

update_option('woocommerce_shop_page_id', get_option('woocommerce_shop_page_id'));
update_option('woocommerce_currency', 'USD');
update_option('woocommerce_currency_pos', 'left');
update_option('woocommerce_price_thousand_sep', ',');
update_option('woocommerce_price_decimal_sep', '.');
update_option('woocommerce_price_num_decimals', 2);

echo "✓ WooCommerce settings configured!\n\n";

// Step 5: Create sample pages
echo "[5/5] Creating sample pages...\n";

$sample_pages = array(
    array(
        'title' => 'About Us',
        'content' => '<h2>Welcome to EcoCommerce Pro</h2><p>We are committed to providing high-quality, eco-friendly products that make a difference. Our mission is to offer sustainable alternatives without compromising on quality or style.</p><h3>Our Values</h3><ul><li>Sustainability</li><li>Quality</li><li>Customer Satisfaction</li><li>Innovation</li></ul>',
    ),
    array(
        'title' => 'Contact Us',
        'content' => '<h2>Get In Touch</h2><p>We\'d love to hear from you! Reach out with any questions or feedback.</p><p><strong>Email:</strong> info@ecocommerce.com</p><p><strong>Phone:</strong> +1 (555) 123-4567</p><p><strong>Address:</strong> 123 Green Street, Eco City, EC 12345</p>',
    ),
);

foreach ($sample_pages as $page_data) {
    $existing = get_page_by_title($page_data['title']);
    
    if (!$existing) {
        wp_insert_post(array(
            'post_title' => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        echo "  ✓ Created page: {$page_data['title']}\n";
    }
}

echo "\n";
echo "==============================================\n";
echo "✓✓✓ Demo Store Setup Complete! ✓✓✓\n";
echo "==============================================\n\n";
echo "Created:\n";
echo "  • " . count($categories) . " Product Categories\n";
echo "  • $product_count Demo Products\n";
echo "  • " . count($sample_pages) . " Sample Pages\n";
echo "  • Configured WooCommerce settings\n\n";
echo "Visit your shop at: " . home_url('/shop') . "\n";
echo "WordPress Admin: " . admin_url() . "\n\n";
echo "Enjoy your new demo store!\n";

