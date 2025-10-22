<?php
/**
 * Demo Store Creator
 * Visit this file in your browser to create demo products
 * URL: http://localhost/create-demo-products.php
 */

require_once('wp-load.php');

// Security check
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(home_url('/create-demo-products.php')));
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this page.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Demo Store</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .card { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
        .btn:hover { background: #0056b3; }
        h1 { color: #333; }
        .status { padding: 10px; margin: 5px 0; background: #e9ecef; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🛍️ EcoCommerce Pro - Demo Store Creator</h1>
    
    <?php
    if (isset($_GET['create'])) {
        echo '<div class="card">';
        
        // Check if WooCommerce is installed
        if (!class_exists('WooCommerce')) {
            echo '<div class="error"><strong>❌ WooCommerce is not installed!</strong><br>';
            echo 'Please install WooCommerce first: <a href="' . admin_url('plugin-install.php?s=woocommerce&tab=search&type=term') . '">Install WooCommerce</a></div>';
        } else {
            echo '<h2>Creating Demo Store...</h2>';
            
            // Create categories
            echo '<div class="status">📁 Creating product categories...</div>';
            $categories = array(
                'Electronics' => 'Gadgets and electronic devices',
                'Clothing' => 'Fashion and apparel',
                'Home & Garden' => 'Home improvement and garden supplies',
                'Sports' => 'Sports and fitness equipment',
                'Beauty' => 'Beauty and personal care products',
            );
            
            $cat_ids = array();
            foreach ($categories as $name => $desc) {
                $term = term_exists($name, 'product_cat');
                if (!$term) {
                    $term = wp_insert_term($name, 'product_cat', array('description' => $desc));
                }
                if (!is_wp_error($term)) {
                    $cat_ids[$name] = is_array($term) ? $term['term_id'] : $term;
                }
            }
            echo '<div class="success">✓ Created ' . count($categories) . ' categories</div>';
            
            // Create products
            echo '<div class="status">🛒 Creating demo products...</div>';
            
            $products = array(
                array('name' => 'Premium Wireless Headphones', 'price' => 149.99, 'sale' => 119.99, 'cat' => 'Electronics', 'desc' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life.'),
                array('name' => 'Smart Watch Pro', 'price' => 299.99, 'sale' => null, 'cat' => 'Electronics', 'desc' => 'Advanced smartwatch with fitness tracking and heart rate monitor.'),
                array('name' => 'Ultra HD 4K Camera', 'price' => 899.99, 'sale' => 799.99, 'cat' => 'Electronics', 'desc' => 'Professional 4K camera with 24MP sensor and WiFi.'),
                array('name' => 'Wireless Gaming Mouse', 'price' => 79.99, 'sale' => 59.99, 'cat' => 'Electronics', 'desc' => 'RGB gaming mouse with 16000 DPI and programmable buttons.'),
                array('name' => 'Bluetooth Speaker', 'price' => 89.99, 'sale' => null, 'cat' => 'Electronics', 'desc' => 'Waterproof portable speaker with 360° sound.'),
                
                array('name' => 'Classic Cotton T-Shirt', 'price' => 24.99, 'sale' => 19.99, 'cat' => 'Clothing', 'desc' => '100% organic cotton t-shirt, soft and comfortable.'),
                array('name' => 'Premium Denim Jeans', 'price' => 89.99, 'sale' => null, 'cat' => 'Clothing', 'desc' => 'High-quality denim jeans with stretch fabric.'),
                array('name' => 'Winter Warm Jacket', 'price' => 149.99, 'sale' => 119.99, 'cat' => 'Clothing', 'desc' => 'Insulated winter jacket with water-resistant fabric.'),
                array('name' => 'Running Sneakers', 'price' => 119.99, 'sale' => 99.99, 'cat' => 'Clothing', 'desc' => 'Lightweight running shoes with cushioned sole.'),
                array('name' => 'Leather Wallet', 'price' => 49.99, 'sale' => null, 'cat' => 'Clothing', 'desc' => 'Genuine leather wallet with RFID protection.'),
                
                array('name' => 'Modern Table Lamp', 'price' => 59.99, 'sale' => null, 'cat' => 'Home & Garden', 'desc' => 'Contemporary table lamp with touch control and LED bulb.'),
                array('name' => 'Bamboo Bed Sheets', 'price' => 129.99, 'sale' => 99.99, 'cat' => 'Home & Garden', 'desc' => 'Soft organic bamboo sheets, hypoallergenic and breathable.'),
                array('name' => 'Indoor Plant Set', 'price' => 49.99, 'sale' => null, 'cat' => 'Home & Garden', 'desc' => 'Set of 3 air-purifying plants with decorative pots.'),
                array('name' => 'Kitchen Knife Set', 'price' => 79.99, 'sale' => 69.99, 'cat' => 'Home & Garden', 'desc' => 'Professional 8-piece kitchen knife set with block.'),
                
                array('name' => 'Yoga Mat Premium', 'price' => 49.99, 'sale' => 39.99, 'cat' => 'Sports', 'desc' => 'Non-slip yoga mat with extra cushioning, eco-friendly.'),
                array('name' => 'Adjustable Dumbbells', 'price' => 149.99, 'sale' => null, 'cat' => 'Sports', 'desc' => 'Adjustable dumbbells 5-52 lbs for home gym.'),
                array('name' => 'Camping Tent 4-Person', 'price' => 199.99, 'sale' => 169.99, 'cat' => 'Sports', 'desc' => 'Waterproof 4-person tent with easy setup.'),
                array('name' => 'Resistance Bands Set', 'price' => 29.99, 'sale' => null, 'cat' => 'Sports', 'desc' => 'Set of 5 resistance bands for strength training.'),
                
                array('name' => 'Organic Facial Serum', 'price' => 49.99, 'sale' => 39.99, 'cat' => 'Beauty', 'desc' => 'Anti-aging serum with vitamin C and hyaluronic acid.'),
                array('name' => 'Essential Oils Set', 'price' => 39.99, 'sale' => null, 'cat' => 'Beauty', 'desc' => 'Set of 6 pure essential oils for aromatherapy.'),
                array('name' => 'Moisturizing Face Cream', 'price' => 34.99, 'sale' => 29.99, 'cat' => 'Beauty', 'desc' => 'Daily moisturizer with SPF 30 and natural ingredients.'),
                array('name' => 'Hair Care Gift Set', 'price' => 59.99, 'sale' => null, 'cat' => 'Beauty', 'desc' => 'Shampoo, conditioner, and hair mask set for all hair types.'),
            );
            
            $created = 0;
            $skipped = 0;
            
            foreach ($products as $p) {
                // Check if product already exists
                $existing = get_page_by_title($p['name'], OBJECT, 'product');
                if ($existing) {
                    $skipped++;
                    continue;
                }
                
                $product = new WC_Product_Simple();
                $product->set_name($p['name']);
                $product->set_regular_price($p['price']);
                if ($p['sale']) {
                    $product->set_sale_price($p['sale']);
                }
                $product->set_description($p['desc']);
                $product->set_short_description(substr($p['desc'], 0, 100) . '...');
                $product->set_status('publish');
                $product->set_catalog_visibility('visible');
                $product->set_stock_status('instock');
                $product->set_manage_stock(true);
                $product->set_stock_quantity(rand(10, 100));
                
                $product_id = $product->save();
                
                if ($product_id && isset($cat_ids[$p['cat']])) {
                    wp_set_object_terms($product_id, $cat_ids[$p['cat']], 'product_cat');
                }
                
                $created++;
            }
            
            echo '<div class="success">✓ Created ' . $created . ' new products';
            if ($skipped > 0) {
                echo ' (skipped ' . $skipped . ' existing)';
            }
            echo '</div>';
            
            // Create sample pages
            echo '<div class="status">📄 Creating sample pages...</div>';
            $pages_created = 0;
            
            $about_page = get_page_by_title('About Us');
            if (!$about_page) {
                wp_insert_post(array(
                    'post_title' => 'About Us',
                    'post_content' => '<h2>Welcome to EcoCommerce Pro</h2><p>We offer high-quality, eco-friendly products for modern living.</p>',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ));
                $pages_created++;
            }
            
            $contact_page = get_page_by_title('Contact');
            if (!$contact_page) {
                wp_insert_post(array(
                    'post_title' => 'Contact',
                    'post_content' => '<h2>Get In Touch</h2><p>Email: info@ecocommerce.com<br>Phone: +1 (555) 123-4567</p>',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ));
                $pages_created++;
            }
            
            if ($pages_created > 0) {
                echo '<div class="success">✓ Created ' . $pages_created . ' sample pages</div>';
            }
            
            echo '<h2 style="color: #28a745; margin-top: 30px;">✓✓✓ Demo Store Complete! ✓✓✓</h2>';
            echo '<div class="success">';
            echo '<strong>Summary:</strong><br>';
            echo '• Categories: ' . count($categories) . '<br>';
            echo '• Products: ' . $created . '<br>';
            echo '• Pages: ' . $pages_created . '<br>';
            echo '</div>';
            
            echo '<p><a href="' . home_url('/shop') . '" class="btn">View Shop →</a> ';
            echo '<a href="' . admin_url('edit.php?post_type=product') . '" class="btn">Manage Products →</a></p>';
        }
        
        echo '</div>';
    } else {
        ?>
        <div class="card">
            <h2>Ready to Create Your Demo Store?</h2>
            <p>This will create:</p>
            <ul>
                <li>✅ <strong>5 Product Categories</strong> (Electronics, Clothing, Home, Sports, Beauty)</li>
                <li>✅ <strong>22 Demo Products</strong> with names, descriptions, and prices</li>
                <li>✅ <strong>Sample Pages</strong> (About Us, Contact)</li>
                <li>✅ <strong>Product variations</strong> (regular and sale prices)</li>
            </ul>
            
            <?php if (!class_exists('WooCommerce')): ?>
                <div class="error">
                    <strong>⚠️ WooCommerce is not installed!</strong><br>
                    Please install WooCommerce first: 
                    <a href="<?php echo admin_url('plugin-install.php?s=woocommerce&tab=search&type=term'); ?>">Install WooCommerce →</a>
                </div>
            <?php else: ?>
                <p><a href="?create=true" class="btn">Create Demo Store Now →</a></p>
            <?php endif; ?>
            
            <p><small>Note: This is safe to run. Existing products won't be duplicated.</small></p>
        </div>
        
        <div class="card">
            <h3>Alternative Method:</h3>
            <p>You can also install the <strong>"WooCommerce Smooth Generator"</strong> plugin:</p>
            <ol>
                <li>Go to: <a href="<?php echo admin_url('plugin-install.php?s=smooth+generator&tab=search'); ?>">Plugins → Add New</a></li>
                <li>Search for: "WooCommerce Smooth Generator"</li>
                <li>Install and Activate</li>
                <li>Go to: Tools → WC Smooth Generator</li>
                <li>Generate products automatically</li>
            </ol>
        </div>
        <?php
    }
    ?>
    
    <p style="text-align: center; margin-top: 40px;">
        <a href="<?php echo admin_url(); ?>">← Back to Dashboard</a> | 
        <a href="<?php echo home_url(); ?>">View Site</a>
    </p>
</body>
</html>

