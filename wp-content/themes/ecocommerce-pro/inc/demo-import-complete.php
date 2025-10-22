<?php
/**
 * Complete Demo Import with Products, Images, and Elementor Pages
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import complete demo content via AJAX
 */
function ecocommerce_complete_demo_import_ajax() {
    check_ajax_referer('ecocommerce_demo_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }
    
    $step = isset($_POST['step']) ? sanitize_text_field($_POST['step']) : 'start';
    
    switch ($step) {
        case 'categories':
            $result = ecocommerce_import_categories_with_images();
            wp_send_json_success($result);
            break;
            
        case 'products':
            $result = ecocommerce_import_products_with_images();
            wp_send_json_success($result);
            break;
            
        case 'pages':
            $result = ecocommerce_import_elementor_pages();
            wp_send_json_success($result);
            break;
            
        case 'settings':
            $result = ecocommerce_configure_settings();
            wp_send_json_success($result);
            break;
            
        default:
            wp_send_json_error('Invalid step');
    }
}
add_action('wp_ajax_ecocommerce_complete_demo_import', 'ecocommerce_complete_demo_import_ajax');

/**
 * Import categories with images
 */
function ecocommerce_import_categories_with_images() {
    $categories = array(
        array(
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Latest gadgets and electronic devices',
            'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800&q=80'
        ),
        array(
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Fashion and apparel for everyone',
            'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=800&q=80'
        ),
        array(
            'name' => 'Home & Garden',
            'slug' => 'home-garden',
            'description' => 'Beautiful items for your home',
            'image' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800&q=80'
        ),
        array(
            'name' => 'Sports & Fitness',
            'slug' => 'sports-fitness',
            'description' => 'Fitness equipment and sportswear',
            'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&q=80'
        ),
        array(
            'name' => 'Beauty & Health',
            'slug' => 'beauty-health',
            'description' => 'Beauty products and wellness items',
            'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&q=80'
        )
    );
    
    $created = array();
    
    foreach ($categories as $cat_data) {
        // Check if category exists
        $existing = term_exists($cat_data['slug'], 'product_cat');
        
        if (!$existing) {
            $term = wp_insert_term(
                $cat_data['name'],
                'product_cat',
                array(
                    'slug' => $cat_data['slug'],
                    'description' => $cat_data['description']
                )
            );
            
            if (!is_wp_error($term)) {
                $term_id = $term['term_id'];
                
                // Download and set category image
                $image_id = ecocommerce_download_image($cat_data['image'], $cat_data['name'] . ' Category');
                if ($image_id) {
                    update_term_meta($term_id, 'thumbnail_id', $image_id);
                }
                
                $created[] = $cat_data['name'];
            }
        }
    }
    
    return array(
        'message' => 'Categories created: ' . count($created),
        'categories' => $created
    );
}

/**
 * Import products with images
 */
function ecocommerce_import_products_with_images() {
    $products = array(
        // Electronics
        array(
            'title' => 'Wireless Bluetooth Headphones',
            'category' => 'electronics',
            'price' => '79.99',
            'sale_price' => '59.99',
            'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.',
            'images' => array(
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
                'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&q=80'
            )
        ),
        array(
            'title' => 'Smart Watch Pro',
            'category' => 'electronics',
            'price' => '299.99',
            'sale_price' => '249.99',
            'description' => 'Advanced smartwatch with fitness tracking, heart rate monitor, and GPS.',
            'images' => array(
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80'
            )
        ),
        array(
            'title' => '4K Digital Camera',
            'category' => 'electronics',
            'price' => '899.99',
            'description' => 'Professional 4K camera with 24MP sensor and interchangeable lenses.',
            'images' => array(
                'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&q=80'
            )
        ),
        // Clothing
        array(
            'title' => 'Premium Cotton T-Shirt',
            'category' => 'clothing',
            'price' => '29.99',
            'sale_price' => '19.99',
            'description' => 'Soft, breathable cotton t-shirt available in multiple colors.',
            'images' => array(
                'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80'
            )
        ),
        array(
            'title' => 'Designer Denim Jeans',
            'category' => 'clothing',
            'price' => '89.99',
            'description' => 'Comfortable stretch denim with modern fit and premium quality.',
            'images' => array(
                'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=80'
            )
        ),
        array(
            'title' => 'Winter Jacket',
            'category' => 'clothing',
            'price' => '149.99',
            'sale_price' => '99.99',
            'description' => 'Warm, insulated jacket perfect for cold weather.',
            'images' => array(
                'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&q=80'
            )
        ),
        // Home & Garden
        array(
            'title' => 'Modern LED Lamp',
            'category' => 'home-garden',
            'price' => '49.99',
            'description' => 'Elegant table lamp with adjustable brightness and modern design.',
            'images' => array(
                'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&q=80'
            )
        ),
        array(
            'title' => 'Organic Bamboo Bedding Set',
            'category' => 'home-garden',
            'price' => '129.99',
            'sale_price' => '99.99',
            'description' => 'Eco-friendly bamboo sheets that are soft and breathable.',
            'images' => array(
                'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80'
            )
        ),
        // Sports & Fitness
        array(
            'title' => 'Premium Yoga Mat',
            'category' => 'sports-fitness',
            'price' => '39.99',
            'description' => 'Non-slip yoga mat with extra cushioning for comfort.',
            'images' => array(
                'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&q=80'
            )
        ),
        array(
            'title' => 'Adjustable Dumbbells Set',
            'category' => 'sports-fitness',
            'price' => '199.99',
            'sale_price' => '159.99',
            'description' => 'Space-saving adjustable dumbbells from 5-52.5 lbs.',
            'images' => array(
                'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=800&q=80'
            )
        ),
        // Beauty & Health
        array(
            'title' => 'Organic Facial Serum',
            'category' => 'beauty-health',
            'price' => '49.99',
            'description' => 'Natural anti-aging serum with vitamin C and hyaluronic acid.',
            'images' => array(
                'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&q=80'
            )
        ),
        array(
            'title' => 'Essential Oils Gift Set',
            'category' => 'beauty-health',
            'price' => '59.99',
            'sale_price' => '44.99',
            'description' => 'Collection of 10 pure essential oils for aromatherapy.',
            'images' => array(
                'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&q=80'
            )
        )
    );
    
    $created = 0;
    
    foreach ($products as $product_data) {
        // Create product
        $product = new WC_Product_Simple();
        $product->set_name($product_data['title']);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_description($product_data['description']);
        $product->set_regular_price($product_data['price']);
        
        if (isset($product_data['sale_price'])) {
            $product->set_sale_price($product_data['sale_price']);
        }
        
        $product->set_manage_stock(true);
        $product->set_stock_quantity(100);
        $product->set_stock_status('instock');
        
        $product_id = $product->save();
        
        if ($product_id) {
            // Set category
            $term = get_term_by('slug', $product_data['category'], 'product_cat');
            if ($term) {
                wp_set_object_terms($product_id, $term->term_id, 'product_cat');
            }
            
            // Download and set product images
            if (!empty($product_data['images'])) {
                $image_ids = array();
                
                foreach ($product_data['images'] as $index => $image_url) {
                    $image_id = ecocommerce_download_image($image_url, $product_data['title'] . ' ' . ($index + 1));
                    
                    if ($image_id) {
                        $image_ids[] = $image_id;
                        
                        // Set first image as featured
                        if ($index === 0) {
                            set_post_thumbnail($product_id, $image_id);
                        }
                    }
                }
                
                // Set gallery images
                if (count($image_ids) > 1) {
                    $product->set_gallery_image_ids(array_slice($image_ids, 1));
                    $product->save();
                }
            }
            
            $created++;
        }
    }
    
    return array(
        'message' => 'Products created: ' . $created,
        'count' => $created
    );
}

/**
 * Import Elementor-ready pages
 */
function ecocommerce_import_elementor_pages() {
    $pages_created = array();
    
    // Check if Elementor is active
    if (!did_action('elementor/loaded')) {
        return array(
            'message' => 'Elementor not active. Installing basic pages.',
            'pages' => array('Please activate Elementor plugin first')
        );
    }
    
    // Create REAL Elementor JSON structure with actual widgets
    $elementor_data = array(
        // Hero Section with Image and Text
        array(
            'id' => wp_generate_uuid4(),
            'elType' => 'section',
            'settings' => array(
                'background_background' => 'gradient',
                'background_color' => '#1e293b',
                'background_color_b' => '#334155',
                'background_gradient_angle' => array('size' => 135),
                'padding' => array('top' => '100', 'bottom' => '100', 'unit' => 'px'),
            ),
            'elements' => array(
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 50),
                    'elements' => array(
                        // Badge
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => array(
                                'title' => '🔥 HUGE SALE – 70% OFF',
                                'header_size' => 'h6',
                                'color' => '#f59e0b',
                                'typography_font_weight' => '700',
                                'typography_text_transform' => 'uppercase',
                                'typography_letter_spacing' => array('size' => 2),
                            ),
                        ),
                        // Main Heading
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => array(
                                'title' => 'Find the Boundaries.<br>Push Through!',
                                'header_size' => 'h1',
                                'color' => '#ffffff',
                                'typography_font_size' => array('size' => 60, 'unit' => 'px'),
                                'typography_font_weight' => '900',
                                'typography_line_height' => array('size' => 1.1),
                            ),
                        ),
                        // Description
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'text-editor',
                            'settings' => array(
                                'editor' => 'Discover our exclusive collection. Premium quality meets unbeatable prices.',
                                'text_color' => '#ffffff',
                                'typography_font_size' => array('size' => 18, 'unit' => 'px'),
                            ),
                        ),
                        // Button
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'button',
                            'settings' => array(
                                'text' => 'SHOP NOW →',
                                'link' => array('url' => '/shop'),
                                'button_background_color' => '#6366f1',
                                'button_text_color' => '#ffffff',
                                'typography_font_weight' => '700',
                                'button_border_radius' => array('size' => 50),
                                'button_padding' => array('top' => 18, 'right' => 48, 'bottom' => 18, 'left' => 48),
                            ),
                        ),
                    ),
                ),
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 50),
                    'elements' => array(
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'image',
                            'settings' => array(
                                'image' => array('url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80'),
                                'image_size' => 'full',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        
        // Promo Banners Section
        array(
            'id' => wp_generate_uuid4(),
            'elType' => 'section',
            'settings' => array(
                'background_color' => '#ffffff',
                'padding' => array('top' => '60', 'bottom' => '60', 'unit' => 'px'),
            ),
            'elements' => array(
                // Column 1
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 33),
                    'elements' => array(
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'image-box',
                            'settings' => array(
                                'image' => array('url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80'),
                                'title_text' => 'Summer Sale',
                                'description_text' => 'Up to 30% OFF',
                                'link' => array('url' => '/shop'),
                                'title_color' => '#1e293b',
                                'description_color' => '#6366f1',
                            ),
                        ),
                    ),
                ),
                // Column 2
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 33),
                    'elements' => array(
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'image-box',
                            'settings' => array(
                                'image' => array('url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80'),
                                'title_text' => 'New Arrivals',
                                'description_text' => 'Fresh Styles Daily',
                                'link' => array('url' => '/shop'),
                                'title_color' => '#1e293b',
                                'description_color' => '#8b5cf6',
                            ),
                        ),
                    ),
                ),
                // Column 3
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 33),
                    'elements' => array(
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'image-box',
                            'settings' => array(
                                'image' => array('url' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&q=80'),
                                'title_text' => 'Best Deals',
                                'description_text' => 'Save Big Today',
                                'link' => array('url' => '/shop'),
                                'title_color' => '#1e293b',
                                'description_color' => '#10b981',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        
        // Featured Products Section
        array(
            'id' => wp_generate_uuid4(),
            'elType' => 'section',
            'settings' => array(
                'background_color' => '#f8fafc',
                'padding' => array('top' => '80', 'bottom' => '80', 'unit' => 'px'),
            ),
            'elements' => array(
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 100),
                    'elements' => array(
                        // Section Title
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => array(
                                'title' => 'Featured Products',
                                'header_size' => 'h2',
                                'align' => 'center',
                                'color' => '#1e293b',
                                'typography_font_size' => array('size' => 42, 'unit' => 'px'),
                                'typography_font_weight' => '800',
                            ),
                        ),
                        // Products Shortcode
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'shortcode',
                            'settings' => array(
                                'shortcode' => '[products limit="8" columns="4" orderby="popularity"]',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        
        // Categories Section
        array(
            'id' => wp_generate_uuid4(),
            'elType' => 'section',
            'settings' => array(
                'background_color' => '#ffffff',
                'padding' => array('top' => '80', 'bottom' => '80', 'unit' => 'px'),
            ),
            'elements' => array(
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 100),
                    'elements' => array(
                        // Section Title
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => array(
                                'title' => 'Shop by Category',
                                'header_size' => 'h2',
                                'align' => 'center',
                                'color' => '#1e293b',
                                'typography_font_size' => array('size' => 42, 'unit' => 'px'),
                                'typography_font_weight' => '800',
                            ),
                        ),
                        // Categories Shortcode
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'shortcode',
                            'settings' => array(
                                'shortcode' => '[product_categories limit="6" columns="3"]',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        
        // CTA Section
        array(
            'id' => wp_generate_uuid4(),
            'elType' => 'section',
            'settings' => array(
                'background_background' => 'gradient',
                'background_color' => '#6366f1',
                'background_color_b' => '#8b5cf6',
                'background_gradient_angle' => array('size' => 135),
                'padding' => array('top' => '100', 'bottom' => '100', 'unit' => 'px'),
            ),
            'elements' => array(
                array(
                    'id' => wp_generate_uuid4(),
                    'elType' => 'column',
                    'settings' => array('_column_size' => 100),
                    'elements' => array(
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => array(
                                'title' => 'Ready to Start Shopping?',
                                'header_size' => 'h2',
                                'align' => 'center',
                                'color' => '#ffffff',
                                'typography_font_size' => array('size' => 48, 'unit' => 'px'),
                                'typography_font_weight' => '900',
                            ),
                        ),
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'text-editor',
                            'settings' => array(
                                'editor' => 'Join thousands of happy customers. Free shipping on orders over $50!',
                                'align' => 'center',
                                'text_color' => '#ffffff',
                                'typography_font_size' => array('size' => 20, 'unit' => 'px'),
                            ),
                        ),
                        array(
                            'id' => wp_generate_uuid4(),
                            'elType' => 'widget',
                            'widgetType' => 'button',
                            'settings' => array(
                                'text' => 'BROWSE ALL PRODUCTS',
                                'link' => array('url' => '/shop'),
                                'align' => 'center',
                                'button_background_color' => '#ffffff',
                                'button_text_color' => '#6366f1',
                                'typography_font_weight' => '700',
                                'button_border_radius' => array('size' => 50),
                                'button_padding' => array('top' => 20, 'right' => 50, 'bottom' => 20, 'left' => 50),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );
    
    // Keep the premium CSS-styled content as fallback (hidden by default)
    $fallback_html_content = '
<div class="premium-homepage">

<!-- TOP PROMO BAR -->
<section class="promo-bar">
    🎉 Get Up to <strong style="font-size: 20px; margin: 0 8px;">40% OFF</strong> New-Season Styles - Limited Time Only! 🎉
</section>

<!-- HERO BANNERS SECTION - 3 COLUMNS -->
<section class="hero-banners">
    <div class="premium-container">
        <div class="hero-grid-three">
            
            <!-- COLUMN 1: CATEGORY MENU -->
            <div class="hero-category-menu">
                <div class="category-menu-header">
                    <span class="menu-icon">☰</span>
                    <span class="menu-title">All Categories</span>
                </div>
                <ul class="category-menu-list">
                    <li class="menu-item has-submenu">
                        <a href="/product-category/electronics/">
                            <span class="item-icon">📱</span>
                            <span class="item-text">Electronics</span>
                            <span class="item-arrow">→</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/product-category/electronics/phones/">Smartphones</a></li>
                            <li><a href="/product-category/electronics/laptops/">Laptops</a></li>
                            <li><a href="/product-category/electronics/tablets/">Tablets</a></li>
                            <li><a href="/product-category/electronics/accessories/">Accessories</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="/product-category/fashion/">
                            <span class="item-icon">👕</span>
                            <span class="item-text">Fashion</span>
                            <span class="item-arrow">→</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/product-category/fashion/mens/">Men\'s Clothing</a></li>
                            <li><a href="/product-category/fashion/womens/">Women\'s Clothing</a></li>
                            <li><a href="/product-category/fashion/kids/">Kids Wear</a></li>
                            <li><a href="/product-category/fashion/shoes/">Shoes</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="/product-category/home-garden/">
                            <span class="item-icon">🏠</span>
                            <span class="item-text">Home & Garden</span>
                        </a>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="/product-category/sports/">
                            <span class="item-icon">⚽</span>
                            <span class="item-text">Sports & Outdoors</span>
                            <span class="item-arrow">→</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/product-category/sports/fitness/">Fitness</a></li>
                            <li><a href="/product-category/sports/camping/">Camping</a></li>
                            <li><a href="/product-category/sports/cycling/">Cycling</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="/product-category/beauty/">
                            <span class="item-icon">💄</span>
                            <span class="item-text">Beauty & Health</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/product-category/toys/">
                            <span class="item-icon">🧸</span>
                            <span class="item-text">Toys & Games</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/product-category/books/">
                            <span class="item-icon">📚</span>
                            <span class="item-text">Books & Media</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/product-category/automotive/">
                            <span class="item-icon">🚗</span>
                            <span class="item-text">Automotive</span>
                        </a>
                    </li>
                    <li class="menu-item menu-view-all">
                        <a href="/shop/">
                            <span class="item-icon">🔍</span>
                            <span class="item-text">View All Categories</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- COLUMN 2: HERO SLIDER -->
            <div class="hero-slider-container">
                <div class="hero-slider">
                    <div class="slide slide-1 active">
                        <div class="slide-content">
                            <div class="slide-badge">🔥 HOT DEAL</div>
                            <h2 class="slide-title">HUGE SALE<br/><span class="highlight">70% OFF</span></h2>
                            <p class="slide-text">Find the Boundaries. Push Through!</p>
                            <a href="/shop" class="slide-button">SHOP NOW →</a>
                        </div>
                    </div>
                    <div class="slide slide-2">
                        <div class="slide-content">
                            <div class="slide-badge">✨ NEW ARRIVALS</div>
                            <h2 class="slide-title">Latest Collection<br/><span class="highlight">50% OFF</span></h2>
                            <p class="slide-text">Discover trending products now!</p>
                            <a href="/shop?orderby=date" class="slide-button">DISCOVER →</a>
                        </div>
                    </div>
                    <div class="slide slide-3">
                        <div class="slide-content">
                            <div class="slide-badge">🎁 EXCLUSIVE OFFER</div>
                            <h2 class="slide-title">Premium Quality<br/><span class="highlight">Best Prices</span></h2>
                            <p class="slide-text">Shop with confidence & style!</p>
                            <a href="/shop" class="slide-button">SHOP NOW →</a>
                        </div>
                    </div>
                </div>
                <div class="slider-controls">
                    <button class="slider-prev">‹</button>
                    <button class="slider-next">›</button>
                </div>
                <div class="slider-dots">
                        <span class="dot active" data-slide="0"></span>
                        <span class="dot" data-slide="1"></span>
                        <span class="dot" data-slide="2"></span>
                    </div>
                </div>
            </div>
            
            <!-- COLUMN 3: PROMO CARDS (BANNER) -->
            <div class="hero-side">
                <div class="promo-card promo-card-pink">
                    <div class="promo-label">SUMMER SALE</div>
                    <div class="promo-discount">30% OFF</div>
                    <div class="promo-starting">Starting At</div>
                    <div class="promo-price">$19<sup>.99</sup></div>
                    <a href="/shop" class="promo-btn" style="color: #f5576c;">Get Yours!</a>
                </div>
                
                <div class="promo-card promo-card-cyan">
                    <div class="promo-label">NEW ARRIVALS</div>
                    <div class="promo-discount">50% OFF</div>
                    <div class="promo-starting">Starting At</div>
                    <div class="promo-price">$29<sup>.99</sup></div>
                    <a href="/shop" class="promo-btn" style="color: #0891b2;">Shop Now!</a>
                </div>
            </div>
            
        </div>
        
        <!-- THREE MINI PROMOS -->
        <div class="promo-three-grid">
            <div class="promo-mini promo-mini-green">
                <div class="promo-mini-label">OVER 200 PRODUCTS</div>
                <div class="promo-mini-title">GREAT<br/>DEALS</div>
                <div class="promo-starting">Starting At</div>
                <div class="promo-mini-price">$29<sup>.99</sup></div>
            </div>
            
            <div class="promo-mini promo-mini-purple">
                <div class="promo-mini-label">TRENDING</div>
                <div class="promo-mini-title">Fashion<br/>Sales</div>
                <div class="promo-starting">Starting At</div>
                <div class="promo-mini-price">$99<sup>.00</sup></div>
            </div>
            
            <div class="promo-mini promo-mini-red">
                <div class="promo-mini-label">EXCLUSIVE</div>
                <div class="promo-mini-title">Shoes<br/>50% OFF</div>
                <div class="promo-starting">Starting At</div>
                <div class="promo-mini-price">$99<sup>.00</sup></div>
            </div>
        </div>
    </div>
</section>

<!-- SHOP BY CATEGORY -->
<section style="padding: 80px 0; background: white;">
    <div class="premium-container">
        <div class="section-header">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Explore our diverse collection</p>
        </div>
        ' . do_shortcode('[product_categories limit="5" columns="5"]') . '
    </div>
</section>

<!-- FEATURES TRUST BAR -->
<section class="features-bar">
    <div class="premium-container-narrow">
        <div class="features-grid">
            <div>
                <div class="feature-icon">🚚</div>
                <h3 class="feature-title">FREE SHIPPING & RETURN</h3>
                <p class="feature-text">Free shipping on all orders over $99</p>
            </div>
            
            <div>
                <div class="feature-icon">💰</div>
                <h3 class="feature-title">MONEY BACK GUARANTEE</h3>
                <p class="feature-text">100% money back guarantee</p>
            </div>
            
            <div>
                <div class="feature-icon">💬</div>
                <h3 class="feature-title">ONLINE SUPPORT 24/7</h3>
                <p class="feature-text">Always dedicated team</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section style="padding: 100px 0 80px; background: #fff;">
    <div class="premium-container">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Discover our most popular items</p>
        </div>
        ' . do_shortcode('[products limit="8" columns="4" orderby="popularity"]') . '
    </div>
</section>

<!-- SPECIAL OFFER BANNERS -->
<section class="offer-banners">
    <div class="premium-container">
        <div class="offer-grid">
            <div class="offer-card offer-card-teal">
                <div class="offer-badge">EXCLUSIVE COUPON</div>
                <div class="offer-label">ELECTRONIC DEALS</div>
                <div class="offer-title">
                    UP TO<br/><span class="offer-title-large">$100</span> OFF
                </div>
                <a href="/shop" class="offer-btn" style="color: #0d9488;">Get Yours!</a>
            </div>
            
            <div class="offer-card offer-card-pink">
                <div class="offer-badge-flash">⚡ FLASH SALE</div>
                <div class="offer-label">TOP BRANDS</div>
                <div class="offer-title">Summer<br/>Sunglasses</div>
                <div class="offer-subtitle">STARTING AT</div>
                <div class="offer-price">$19<sup>.99</sup></div>
                <a href="/shop" class="offer-btn" style="color: #d946ef;">View Sale</a>
            </div>
        </div>
    </div>
</section>

<!-- NEW ARRIVALS SECTION -->
<section style="padding: 40px 0 100px; background: #f8fafc;">
    <div class="premium-container">
        <div class="section-header">
            <div class="section-badge">🆕 NEW ARRIVALS</div>
            <h2 class="section-title">Latest Products</h2>
            <p class="section-subtitle">Check out our newest additions</p>
        </div>
        ' . do_shortcode('[products limit="8" columns="4" orderby="date"]') . '
    </div>
</section>

<!-- MEGA CTA SECTION -->
<section class="mega-cta">
    <div class="cta-content">
        <div class="cta-badge">🎁 SPECIAL OFFER - LIMITED TIME</div>
        <h2 class="cta-title">Ready to Start<br/>Shopping?</h2>
        <p class="cta-text">Join <strong>10,000+ happy customers</strong> worldwide</p>
        <p class="cta-promo">
            Get <strong class="cta-highlight">15% OFF</strong> your first order with code: <strong>WELCOME15</strong>
        </p>
        <div class="cta-buttons">
            <a href="/shop" class="cta-btn-primary">🛍️ BROWSE ALL PRODUCTS</a>
            <a href="/shop?orderby=date" class="cta-btn-secondary">✨ NEW ARRIVALS</a>
        </div>
    </div>
</section>

</div>
';
    
    $homepage_id = wp_insert_post(array(
        'post_title' => 'Home',
        'post_content' => '', // Empty - Elementor will render the content
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => get_current_user_id(),
    ));
    
    if ($homepage_id && !is_wp_error($homepage_id)) {
        // Mark as Elementor page with REAL Elementor data
        update_post_meta($homepage_id, '_elementor_edit_mode', 'builder');
        update_post_meta($homepage_id, '_elementor_template_type', 'wp-page');
        update_post_meta($homepage_id, '_elementor_version', '3.16.0');
        update_post_meta($homepage_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
        
        // Set as front page
        update_option('page_on_front', $homepage_id);
        update_option('show_on_front', 'page');
        
        $pages_created[] = 'Homepage (ID: ' . $homepage_id . ') - REAL Elementor Widgets!';
    }
    
    // About Us Page
    $about_id = wp_insert_post(array(
        'post_title' => 'About Us',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'page',
    ));
    
    if ($about_id && !is_wp_error($about_id)) {
        update_post_meta($about_id, '_elementor_edit_mode', 'builder');
        update_post_meta($about_id, '_elementor_template_type', 'wp-page');
        update_post_meta($about_id, '_elementor_version', '3.16.0');
        
        $about_data = array(
            array(
                'id' => wp_generate_uuid4(),
                'elType' => 'section',
                'elements' => array(
                    array(
                        'id' => wp_generate_uuid4(),
                        'elType' => 'column',
                        'settings' => array('_column_size' => 100),
                        'elements' => array(
                            array(
                                'id' => wp_generate_uuid4(),
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => array(
                                    'title' => 'About Our Store',
                                    'header_size' => 'h1'
                                )
                            ),
                            array(
                                'id' => wp_generate_uuid4(),
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => array(
                                    'editor' => '<p>Welcome to our amazing e-commerce store! We offer high-quality products at competitive prices.</p><p>Edit this page with Elementor to create your perfect about page.</p>'
                                )
                            )
                        )
                    )
                )
            )
        );
        
        update_post_meta($about_id, '_elementor_data', wp_slash(wp_json_encode($about_data)));
        $pages_created[] = 'About Us (ID: ' . $about_id . ')';
    }
    
    // Contact Page
    $contact_id = wp_insert_post(array(
        'post_title' => 'Contact',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'page',
    ));
    
    if ($contact_id && !is_wp_error($contact_id)) {
        update_post_meta($contact_id, '_elementor_edit_mode', 'builder');
        update_post_meta($contact_id, '_elementor_template_type', 'wp-page');
        update_post_meta($contact_id, '_elementor_version', '3.16.0');
        
        $contact_data = array(
            array(
                'id' => wp_generate_uuid4(),
                'elType' => 'section',
                'elements' => array(
                    array(
                        'id' => wp_generate_uuid4(),
                        'elType' => 'column',
                        'settings' => array('_column_size' => 100),
                        'elements' => array(
                            array(
                                'id' => wp_generate_uuid4(),
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => array(
                                    'title' => 'Contact Us',
                                    'header_size' => 'h1'
                                )
                            ),
                            array(
                                'id' => wp_generate_uuid4(),
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => array(
                                    'editor' => '<p>Get in touch with us! We\'d love to hear from you.</p><p>Edit with Elementor to add contact forms and more.</p>'
                                )
                            )
                        )
                    )
                )
            )
        );
        
        update_post_meta($contact_id, '_elementor_data', wp_slash(wp_json_encode($contact_data)));
        $pages_created[] = 'Contact (ID: ' . $contact_id . ')';
    }
    
    // Shop Page (if doesn't exist)
    if (!wc_get_page_id('shop')) {
        $shop_id = wp_insert_post(array(
            'post_title' => 'Shop',
            'post_content' => '[products]',
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        
        if ($shop_id && !is_wp_error($shop_id)) {
            update_option('woocommerce_shop_page_id', $shop_id);
            $pages_created[] = 'Shop Page (ID: ' . $shop_id . ')';
        }
    }
    
    // Clear Elementor cache
    if (class_exists('\Elementor\Plugin')) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }
    
    return array(
        'message' => 'Created ' . count($pages_created) . ' Elementor-compatible pages',
        'pages' => $pages_created,
        'note' => 'All pages are now fully editable with Elementor!'
    );
}

/**
 * Configure WooCommerce and theme settings
 */
function ecocommerce_configure_settings() {
    // WooCommerce basic settings
    update_option('woocommerce_shop_page_display', 'both');
    update_option('woocommerce_category_archive_display', 'both');
    update_option('woocommerce_default_catalog_orderby', 'menu_order');
    update_option('woocommerce_currency', 'USD');
    update_option('woocommerce_price_display_suffix', '');
    update_option('woocommerce_tax_display_shop', 'excl');
    update_option('woocommerce_tax_display_cart', 'excl');
    
    // Enable product gallery features
    update_option('woocommerce_enable_gallery_zoom', 'yes');
    update_option('woocommerce_enable_gallery_lightbox', 'yes');
    update_option('woocommerce_enable_gallery_slider', 'yes');
    
    return array(
        'message' => 'Settings configured successfully',
        'status' => 'complete'
    );
}

/**
 * Helper: Download image from URL
 */
function ecocommerce_download_image($image_url, $title) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $tmp = download_url($image_url);
    
    if (is_wp_error($tmp)) {
        return false;
    }
    
    $file_array = array(
        'name' => basename($image_url) . '.jpg',
        'tmp_name' => $tmp
    );
    
    $id = media_handle_sideload($file_array, 0, $title);
    
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }
    
    return $id;
}

