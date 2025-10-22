<?php
/**
 * EcoCommerce Pro - Demo Import Functionality
 * 
 * @package EcoCommerce_Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Import Demo Page
 */
function ecocommerce_pro_render_import_demo_page() {
    // Use new complete import page
    ecocommerce_pro_render_demo_import_page_new();
    return;
    
    // Handle AJAX import request (old method, kept for compatibility)
    if (isset($_POST['ecocommerce_import_demo']) && check_admin_referer('ecocommerce_import_demo_nonce')) {
        ecocommerce_pro_do_demo_import();
        return;
    }
    
    ?>
    <div class="wrap ecocommerce-pro-options">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="ecocommerce-admin-container">
            <div class="ecocommerce-admin-main">
                <div class="ecocommerce-card demo-import-card">
                    <h2>🎨 Import Demo Content</h2>
                    
                    <?php if (class_exists('WooCommerce')): ?>
                        
                        <div class="demo-preview">
                            <img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="Demo Preview" style="max-width: 100%; height: auto; border-radius: 8px; margin-bottom: 20px;" />
                        </div>
                        
                        <div class="demo-info">
                            <h3>What Will Be Imported?</h3>
                            <ul class="demo-features">
                                <li>✅ <strong>Product Categories</strong> (5 categories: Electronics, Clothing, Home, Sports, Beauty)</li>
                                <li>✅ <strong>Demo Products</strong> (22+ products with descriptions and prices)</li>
                                <li>✅ <strong>Sale Prices</strong> (Products with discounted prices and sale badges)</li>
                                <li>✅ <strong>Sample Pages</strong> (About Us, Contact pages)</li>
                                <li>✅ <strong>Product Attributes</strong> (Stock management, SKUs)</li>
                                <li>✅ <strong>WooCommerce Settings</strong> (Basic shop configuration)</li>
                            </ul>
                            
                            <div class="demo-warning" style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
                                <strong>⚠️ Important Notes:</strong>
                                <ul style="margin: 10px 0 0 20px;">
                                    <li>This will create new products and categories</li>
                                    <li>Existing content will not be affected</li>
                                    <li>You can delete demo content anytime</li>
                                    <li>Import takes 10-15 seconds</li>
                                </ul>
                            </div>
                            
                            <form method="post" id="demo-import-form">
                                <?php wp_nonce_field('ecocommerce_import_demo_nonce'); ?>
                                <input type="hidden" name="ecocommerce_import_demo" value="1" />
                                
                                <button type="submit" class="button button-primary button-hero" id="import-demo-btn" style="padding: 15px 30px; font-size: 16px;">
                                    <span class="dashicons dashicons-download" style="margin-top: 4px;"></span>
                                    Import Demo Content
                                </button>
                                
                                <div id="import-progress" style="display: none; margin-top: 20px;">
                                    <div class="progress-bar" style="background: #f0f0f0; border-radius: 5px; overflow: hidden; height: 30px;">
                                        <div class="progress-fill" style="background: linear-gradient(90deg, #2563eb, #10b981); height: 100%; width: 0%; transition: width 0.3s; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;"></div>
                                    </div>
                                    <p id="import-status" style="margin-top: 10px; font-weight: 500;"></p>
                                </div>
                            </form>
                        </div>
                        
                        <script>
                        jQuery(document).ready(function($) {
                            $('#demo-import-form').on('submit', function() {
                                $('#import-demo-btn').prop('disabled', true).html('<span class="dashicons dashicons-update dashicons-spin"></span> Importing...');
                                $('#import-progress').show();
                                
                                var progress = 0;
                                var interval = setInterval(function() {
                                    progress += 10;
                                    if (progress > 90) progress = 90;
                                    $('.progress-fill').css('width', progress + '%').text(progress + '%');
                                    
                                    if (progress <= 30) {
                                        $('#import-status').text('Creating categories...');
                                    } else if (progress <= 60) {
                                        $('#import-status').text('Importing products...');
                                    } else {
                                        $('#import-status').text('Configuring settings...');
                                    }
                                }, 500);
                                
                                setTimeout(function() {
                                    clearInterval(interval);
                                    $('.progress-fill').css('width', '100%').text('100%');
                                    $('#import-status').text('Complete!');
                                }, 10000);
                            });
                        });
                        </script>
                        
                    <?php else: ?>
                        
                        <div class="notice notice-error" style="padding: 20px; margin: 20px 0;">
                            <h3>❌ WooCommerce Required</h3>
                            <p>The demo import feature requires WooCommerce to be installed and activated.</p>
                            <p>
                                <a href="<?php echo admin_url('plugin-install.php?s=woocommerce&tab=search&type=term'); ?>" class="button button-primary">
                                    Install WooCommerce Now
                                </a>
                            </p>
                        </div>
                        
                    <?php endif; ?>
                </div>
                
                <?php if (class_exists('WooCommerce')): ?>
                <div class="ecocommerce-card">
                    <h3>📦 Imported Content Management</h3>
                    <p>After importing, you can manage your content:</p>
                    <ul>
                        <li><a href="<?php echo admin_url('edit.php?post_type=product'); ?>">Manage Products →</a></li>
                        <li><a href="<?php echo admin_url('edit-tags.php?taxonomy=product_cat&post_type=product'); ?>">Manage Categories →</a></li>
                        <li><a href="<?php echo home_url('/shop'); ?>" target="_blank">View Shop →</a></li>
                    </ul>
                    
                    <h4 style="margin-top: 20px;">🗑️ Remove Demo Content</h4>
                    <p>To remove demo products, go to <strong>Products</strong> and delete them manually, or use this quick action:</p>
                    <button type="button" class="button button-secondary" onclick="if(confirm('Are you sure you want to delete all demo products? This cannot be undone!')) { window.location.href='<?php echo wp_nonce_url(admin_url('admin.php?page=ecocommerce-pro-import-demo&action=delete_demo'), 'delete_demo_nonce'); ?>'; }">
                        Delete All Demo Products
                    </button>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="ecocommerce-admin-sidebar">
                <div class="ecocommerce-card">
                    <h3>💡 Quick Tips</h3>
                    <ul style="line-height: 1.8;">
                        <li>Import demo to see how products look</li>
                        <li>Customize products after import</li>
                        <li>Replace demo images with your own</li>
                        <li>Edit prices and descriptions</li>
                        <li>Delete products you don't need</li>
                    </ul>
                </div>
                
                <div class="ecocommerce-card">
                    <h3>🎨 Next Steps</h3>
                    <ol style="line-height: 1.8;">
                        <li>Import demo content</li>
                        <li>Visit your <a href="<?php echo home_url('/shop'); ?>" target="_blank">shop page</a></li>
                        <li>Customize colors in <a href="<?php echo admin_url('admin.php?page=ecocommerce-pro-styling'); ?>">Styling</a></li>
                        <li>Upload your logo in <a href="<?php echo admin_url('admin.php?page=ecocommerce-pro-options'); ?>">General Settings</a></li>
                        <li>Add your products!</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .demo-features {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    
    .demo-features li {
        padding: 10px;
        margin: 5px 0;
        background: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #2563eb;
    }
    
    .demo-import-card {
        max-width: 900px;
    }
    
    .dashicons-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    </style>
    <?php
}

/**
 * Helper function to check if post exists by title
 */
function ecocommerce_pro_post_exists_by_title($title, $post_type = 'post') {
    global $wpdb;
    $post = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = %s AND post_status != 'trash' LIMIT 1",
        $title,
        $post_type
    ));
    return $post ? true : false;
}

/**
 * Perform Demo Import
 */
function ecocommerce_pro_do_demo_import() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    if (!class_exists('WooCommerce')) {
        wp_die('WooCommerce is required');
    }
    
    $results = array(
        'categories' => 0,
        'products' => 0,
        'pages' => 0,
    );
    
    // Create categories
    $categories = array(
        'Electronics' => 'Latest gadgets and electronic devices',
        'Clothing' => 'Fashionable apparel and accessories',
        'Home & Garden' => 'Everything for your home and garden',
        'Sports & Outdoors' => 'Sports equipment and outdoor gear',
        'Beauty & Health' => 'Beauty products and health essentials',
    );
    
    $cat_ids = array();
    foreach ($categories as $name => $desc) {
        $term = term_exists($name, 'product_cat');
        if (!$term) {
            $term = wp_insert_term($name, 'product_cat', array('description' => $desc));
            $results['categories']++;
        }
        if (!is_wp_error($term)) {
            $cat_ids[$name] = is_array($term) ? $term['term_id'] : $term;
        }
    }
    
    // Demo products data
    $products = array(
        array('name' => 'Premium Wireless Headphones', 'price' => 149.99, 'sale' => 119.99, 'cat' => 'Electronics', 'desc' => 'High-quality wireless headphones with active noise cancellation, 30-hour battery life, and premium sound quality. Perfect for music lovers and professionals who demand the best audio experience.', 'featured' => true),
        array('name' => 'Smart Watch Pro', 'price' => 299.99, 'sale' => null, 'cat' => 'Electronics', 'desc' => 'Advanced smartwatch with comprehensive fitness tracking, heart rate monitor, GPS, and 5-day battery life. Compatible with both iOS and Android devices.', 'featured' => true),
        array('name' => 'Ultra HD 4K Camera', 'price' => 899.99, 'sale' => 799.99, 'cat' => 'Electronics', 'desc' => 'Professional-grade 4K camera with 24MP sensor, advanced image stabilization, and WiFi connectivity. Includes premium lens kit for versatile photography.', 'featured' => false),
        array('name' => 'Wireless Gaming Mouse', 'price' => 79.99, 'sale' => 59.99, 'cat' => 'Electronics', 'desc' => 'High-performance RGB gaming mouse with ultra-precise 16000 DPI sensor, programmable buttons, and exceptional 70-hour battery life.', 'featured' => false),
        array('name' => 'Portable Bluetooth Speaker', 'price' => 89.99, 'sale' => null, 'cat' => 'Electronics', 'desc' => 'Waterproof portable speaker with immersive 360° sound, 12-hour battery, and crystal-clear built-in microphone for hands-free calls.', 'featured' => false),
        
        array('name' => 'Classic Cotton T-Shirt', 'price' => 24.99, 'sale' => 19.99, 'cat' => 'Clothing', 'desc' => '100% organic cotton t-shirt. Incredibly soft, comfortable, and eco-friendly. Available in multiple colors and sizes. Perfect for everyday wear.', 'featured' => false),
        array('name' => 'Premium Denim Jeans', 'price' => 89.99, 'sale' => null, 'cat' => 'Clothing', 'desc' => 'High-quality denim jeans with comfortable stretch fabric. Modern slim fit design that\'s both durable and stylish for any occasion.', 'featured' => true),
        array('name' => 'Winter Warm Jacket', 'price' => 149.99, 'sale' => 119.99, 'cat' => 'Clothing', 'desc' => 'Premium insulated winter jacket with water-resistant fabric and thermal lining. Stay warm and dry in the coldest weather conditions.', 'featured' => true),
        array('name' => 'Running Sneakers Pro', 'price' => 119.99, 'sale' => 99.99, 'cat' => 'Clothing', 'desc' => 'Lightweight running shoes with advanced cushioned sole and breathable mesh upper. Engineered for comfort and performance.', 'featured' => false),
        array('name' => 'Leather Wallet Premium', 'price' => 49.99, 'sale' => null, 'cat' => 'Clothing', 'desc' => 'Genuine leather wallet with RFID protection technology. Sleek design with multiple card slots and bill compartment.', 'featured' => false),
        
        array('name' => 'Modern LED Table Lamp', 'price' => 59.99, 'sale' => null, 'cat' => 'Home & Garden', 'desc' => 'Contemporary table lamp with intuitive touch control and energy-efficient LED bulb. Perfect ambient lighting for any room.', 'featured' => false),
        array('name' => 'Organic Bamboo Bed Sheets', 'price' => 129.99, 'sale' => 99.99, 'cat' => 'Home & Garden', 'desc' => 'Luxuriously soft organic bamboo sheets. Hypoallergenic, breathable, and naturally temperature-regulating for perfect sleep.', 'featured' => true),
        array('name' => 'Indoor Plant Collection', 'price' => 49.99, 'sale' => null, 'cat' => 'Home & Garden', 'desc' => 'Beautiful set of 3 air-purifying indoor plants with stylish decorative pots. Low maintenance and perfect for any space.', 'featured' => false),
        array('name' => 'Professional Knife Set', 'price' => 79.99, 'sale' => 69.99, 'cat' => 'Home & Garden', 'desc' => 'Complete 8-piece professional kitchen knife set with premium steel blades and ergonomic handles. Includes wooden storage block.', 'featured' => false),
        
        array('name' => 'Premium Yoga Mat', 'price' => 49.99, 'sale' => 39.99, 'cat' => 'Sports & Outdoors', 'desc' => 'Non-slip yoga mat with extra cushioning and eco-friendly TPE material. Perfect for yoga, pilates, and floor exercises.', 'featured' => true),
        array('name' => 'Adjustable Dumbbells Set', 'price' => 149.99, 'sale' => null, 'cat' => 'Sports & Outdoors', 'desc' => 'Space-saving adjustable dumbbells ranging from 5-52 lbs. Perfect for complete home gym workouts with minimal equipment.', 'featured' => true),
        array('name' => 'Family Camping Tent', 'price' => 199.99, 'sale' => 169.99, 'cat' => 'Sports & Outdoors', 'desc' => 'Spacious waterproof 4-person tent with easy quick-setup design. Perfect for family camping trips and outdoor adventures.', 'featured' => false),
        array('name' => 'Resistance Bands Set', 'price' => 29.99, 'sale' => null, 'cat' => 'Sports & Outdoors', 'desc' => 'Complete set of 5 resistance bands with varying resistance levels. Perfect for strength training, stretching, and rehabilitation.', 'featured' => false),
        
        array('name' => 'Organic Facial Serum', 'price' => 49.99, 'sale' => 39.99, 'cat' => 'Beauty & Health', 'desc' => 'Anti-aging facial serum enriched with vitamin C and hyaluronic acid. Natural ingredients for radiant, youthful-looking skin.', 'featured' => true),
        array('name' => 'Essential Oils Gift Set', 'price' => 39.99, 'sale' => null, 'cat' => 'Beauty & Health', 'desc' => 'Premium set of 6 pure essential oils for aromatherapy. Includes lavender, eucalyptus, peppermint, tea tree, lemon, and orange.', 'featured' => false),
        array('name' => 'Daily Moisturizing Cream', 'price' => 34.99, 'sale' => 29.99, 'cat' => 'Beauty & Health', 'desc' => 'Hydrating daily moisturizer with SPF 30 protection and natural ingredients. Suitable for all skin types.', 'featured' => false),
        array('name' => 'Hair Care Gift Set', 'price' => 59.99, 'sale' => null, 'cat' => 'Beauty & Health', 'desc' => 'Complete hair care set including shampoo, conditioner, and nourishing hair mask. Suitable for all hair types.', 'featured' => false),
    );
    
    // Create products
    foreach ($products as $p) {
        // Check if product exists
        if (ecocommerce_pro_post_exists_by_title($p['name'], 'product')) {
            continue;
        }
        
        $product = new WC_Product_Simple();
        $product->set_name($p['name']);
        $product->set_regular_price($p['price']);
        if ($p['sale']) {
            $product->set_sale_price($p['sale']);
        }
        $product->set_description($p['desc']);
        $product->set_short_description(wp_trim_words($p['desc'], 20));
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_stock_status('instock');
        $product->set_manage_stock(true);
        $product->set_stock_quantity(rand(20, 100));
        $product->set_sku('DEMO-' . strtoupper(substr(md5($p['name']), 0, 8)));
        
        if ($p['featured']) {
            $product->set_featured(true);
        }
        
        $product_id = $product->save();
        
        if ($product_id && isset($cat_ids[$p['cat']])) {
            wp_set_object_terms($product_id, $cat_ids[$p['cat']], 'product_cat');
        }
        
        $results['products']++;
    }
    
    // Create sample pages
    $pages = array(
        array(
            'title' => 'About Us',
            'content' => '<h2>Welcome to Our Store</h2><p>We are committed to providing high-quality products that make a difference in your life. Our mission is to offer the best selection of products at competitive prices, backed by excellent customer service.</p><h3>Our Values</h3><ul><li><strong>Quality:</strong> We carefully select every product</li><li><strong>Sustainability:</strong> Eco-friendly options whenever possible</li><li><strong>Customer Service:</strong> Your satisfaction is our priority</li><li><strong>Innovation:</strong> Always bringing you the latest products</li></ul>',
        ),
        array(
            'title' => 'Contact Us',
            'content' => '<h2>Get In Touch</h2><p>We\'d love to hear from you! Whether you have a question about products, orders, or anything else, our team is ready to answer all your questions.</p><p><strong>Email:</strong> info@yourstore.com</p><p><strong>Phone:</strong> +1 (555) 123-4567</p><p><strong>Address:</strong> 123 Commerce Street, Business City, BC 12345</p><p><strong>Hours:</strong> Monday - Friday: 9am - 6pm</p>',
        ),
    );
    
    foreach ($pages as $page_data) {
        // Check if page exists
        if (!ecocommerce_pro_post_exists_by_title($page_data['title'], 'page')) {
            wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            $results['pages']++;
        }
    }
    
    // Display success message
    ?>
    <div class="wrap">
        <div class="notice notice-success" style="padding: 20px; margin: 20px 0;">
            <h2>✅ Demo Import Successful!</h2>
            <p><strong>Import Summary:</strong></p>
            <ul>
                <li>📁 Categories Created: <?php echo $results['categories']; ?></li>
                <li>🛍️ Products Imported: <?php echo $results['products']; ?></li>
                <li>📄 Pages Created: <?php echo $results['pages']; ?></li>
            </ul>
            <p>
                <a href="<?php echo home_url('/shop'); ?>" class="button button-primary" target="_blank">View Shop →</a>
                <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="button">Manage Products →</a>
                <a href="<?php echo admin_url('admin.php?page=ecocommerce-pro-import-demo'); ?>" class="button">Back to Import Page</a>
            </p>
        </div>
    </div>
    <?php
}

// Handle delete demo action
add_action('admin_init', 'ecocommerce_pro_handle_delete_demo');
function ecocommerce_pro_handle_delete_demo() {
    if (isset($_GET['action']) && $_GET['action'] === 'delete_demo' && 
        isset($_GET['page']) && $_GET['page'] === 'ecocommerce-pro-import-demo' &&
        check_admin_referer('delete_demo_nonce')) {
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Delete all products with DEMO SKU prefix
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_sku',
                    'value' => 'DEMO-',
                    'compare' => 'LIKE'
                )
            )
        );
        
        $products = get_posts($args);
        foreach ($products as $product) {
            wp_delete_post($product->ID, true);
        }
        
        wp_redirect(admin_url('admin.php?page=ecocommerce-pro-import-demo&deleted=' . count($products)));
        exit;
    }
}

