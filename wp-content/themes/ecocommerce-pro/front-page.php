<?php
/**
 * The front page template - Complete Modern E-commerce Design
 *
 * @package EcoCommerce_Pro
 */

get_header();

// Get theme options
$homepage_options = get_option('ecocommerce_pro_homepage_options', array());
$general_options = get_option('ecocommerce_pro_general_options', array());

// Check if using Elementor or other page builder
$use_page_builder = false;
if (function_exists('elementor_theme_do_location')) {
    $use_page_builder = get_post_meta(get_the_ID(), '_elementor_edit_mode', true) === 'builder';
} elseif (class_exists('FLBuilderModel') && FLBuilderModel::is_builder_enabled()) {
    $use_page_builder = true;
}
?>

<main id="primary" class="site-main homepage-template">

<?php
// Always process the page content first for Elementor compatibility
if (have_posts()) :
    while (have_posts()) : the_post();
        
        // Check if using Elementor
        $is_elementor = get_post_meta(get_the_ID(), '_elementor_edit_mode', true) === 'builder';
        
        if ($is_elementor) {
            // Elementor page - must call the_content()
            the_content();
            $use_page_builder = true;
        } elseif (trim(get_the_content())) {
            // Page has custom content
            ?>
            <div class="page-content-wrapper">
                <div class="container">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php
            $use_page_builder = true;
        } else {
            // No content, will show default sections below
            $use_page_builder = false;
        }
        
    endwhile;
endif;

// Show default homepage sections if not using page builder
if (!$use_page_builder) :
?>

    <?php
    /* ==========================================================================
       HERO SECTION - Large Banner with CTA
       ========================================================================== */
    
    $hero_enable = $homepage_options['hero_enable'] ?? true;
    
    if ($hero_enable) :
        $hero_title = $homepage_options['hero_title'] ?? 'Welcome to Our Store';
        $hero_subtitle = $homepage_options['hero_subtitle'] ?? 'Discover amazing products at great prices';
        $hero_button_text = $homepage_options['hero_button_text'] ?? 'Shop Now';
        $hero_button_url = $homepage_options['hero_button_url'] ?? (class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#products');
        $hero_bg = $homepage_options['hero_bg'] ?? '';
        ?>
        
        <section class="hero-section-modern" style="<?php echo $hero_bg ? 'background-image: url(' . esc_url($hero_bg) . ');' : ''; ?>">
            <div class="hero-overlay"></div>
            <div class="container">
                <div class="hero-content-wrapper">
                    <div class="hero-text-content">
                        <h1 class="hero-main-title"><?php echo esc_html($hero_title); ?></h1>
                        <p class="hero-main-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                        <div class="hero-buttons">
                            <a href="<?php echo esc_url($hero_button_url); ?>" class="btn btn-primary btn-lg">
                                <?php echo esc_html($hero_button_text); ?> →
                            </a>
                            <a href="#featured-products" class="btn btn-secondary btn-lg">
                                Explore Products
                            </a>
                        </div>
                        
                        <!-- Trust Badges -->
                        <div class="trust-badges">
                            <div class="trust-badge">
                                <span class="trust-icon">🚚</span>
                                <span class="trust-text">Free Shipping</span>
                            </div>
                            <div class="trust-badge">
                                <span class="trust-icon">↩️</span>
                                <span class="trust-text">Easy Returns</span>
                            </div>
                            <div class="trust-badge">
                                <span class="trust-icon">⭐</span>
                                <span class="trust-text">5-Star Rated</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-image-content">
                        <div class="hero-product-showcase">
                            <!-- Decorative element -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    <?php endif; ?>

    <?php
    /* ==========================================================================
       CATEGORY SHOWCASE - Shop by Category
       ========================================================================== */
    
    if (class_exists('WooCommerce')) :
        $product_categories = get_terms('product_cat', array(
            'hide_empty' => false,
            'number' => 6,
            'exclude' => get_option('default_product_cat'),
        ));
        
        if (!empty($product_categories) && !is_wp_error($product_categories)) :
    ?>
        <section class="category-showcase-section">
            <div class="container">
                <div class="section-header-modern">
                    <h2 class="section-title-modern">Shop by Category</h2>
                    <p class="section-subtitle-modern">Explore our wide range of products</p>
                </div>
                
                <div class="category-grid-modern">
                    <?php foreach ($product_categories as $category) : 
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : 'https://via.placeholder.com/400x300/667eea/ffffff?text=' . urlencode($category->name);
                    ?>
                        <a href="<?php echo get_term_link($category); ?>" class="category-card-modern">
                            <div class="category-image-modern">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>" loading="lazy" />
                                <div class="category-overlay"></div>
                            </div>
                            <div class="category-content-modern">
                                <h3 class="category-name"><?php echo esc_html($category->name); ?></h3>
                                <p class="category-count"><?php echo esc_html($category->count); ?> Products</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endif;
    ?>

    <?php
    /* ==========================================================================
       FEATURED PRODUCTS - Highlighted Products
       ========================================================================== */
    
    if (class_exists('WooCommerce')) :
        $featured_enable = $homepage_options['featured_enable'] ?? true;
        
        if ($featured_enable) :
            $featured_title = $homepage_options['featured_title'] ?? 'Featured Products';
            $featured_count = $homepage_options['featured_count'] ?? 8;
    ?>
        <section id="featured-products" class="featured-products-section">
            <div class="container">
                <div class="section-header-modern">
                    <h2 class="section-title-modern"><?php echo esc_html($featured_title); ?></h2>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="view-all-link">
                        View All Products →
                    </a>
                </div>
                
                <div class="products-grid-modern">
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => $featured_count,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'meta_query' => array(
                            array(
                                'key' => '_stock_status',
                                'value' => 'instock',
                                'compare' => '='
                            )
                        )
                    );
                    
                    $products_query = new WP_Query($args);
                    
                    if ($products_query->have_posts()) :
                        while ($products_query->have_posts()) : $products_query->the_post();
                            global $product;
                            ?>
                            
                            <div class="product-card-modern">
                                <div class="product-image-wrapper">
                                    <a href="<?php echo get_permalink(); ?>">
                                        <?php 
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('medium', array('loading' => 'lazy'));
                                        } else {
                                            echo '<img src="https://via.placeholder.com/400x400/f3f4f6/9ca3af?text=Product" alt="Product" loading="lazy" />';
                                        }
                                        ?>
                                    </a>
                                    
                                    <?php if ($product->is_on_sale()) : ?>
                                        <span class="product-badge-sale">Sale</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($product->is_featured()) : ?>
                                        <span class="product-badge-featured">⭐ Featured</span>
                                    <?php endif; ?>
                                    
                                    <div class="product-quick-actions">
                                        <button class="quick-view-btn" title="Quick View">👁️</button>
                                        <button class="wishlist-btn" title="Add to Wishlist">❤️</button>
                                    </div>
                                </div>
                                
                                <div class="product-info-modern">
                                    <?php
                                    $categories = get_the_terms($product->get_id(), 'product_cat');
                                    if ($categories && !is_wp_error($categories)) :
                                        $category = array_shift($categories);
                                        ?>
                                        <span class="product-category-label"><?php echo esc_html($category->name); ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="product-title-modern">
                                        <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <?php if ($product->get_rating_count() > 0) : ?>
                                        <div class="product-rating-modern">
                                            <span class="stars-rating">
                                                <?php
                                                $rating = $product->get_average_rating();
                                                $full_stars = floor($rating);
                                                for ($i = 0; $i < 5; $i++) {
                                                    echo $i < $full_stars ? '⭐' : '☆';
                                                }
                                                ?>
                                            </span>
                                            <span class="rating-count">(<?php echo $product->get_rating_count(); ?>)</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="product-price-modern">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    
                                    <div class="product-actions-modern">
                                        <a href="<?php echo $product->add_to_cart_url(); ?>" class="add-to-cart-modern" data-product_id="<?php echo $product->get_id(); ?>">
                                            🛒 Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <div class="no-products-message">
                            <h3>No products yet</h3>
                            <p>Import demo content to see products here!</p>
                            <a href="<?php echo admin_url('admin.php?page=ecocommerce-pro-import-demo'); ?>" class="btn btn-primary">
                                Import Demo Products
                            </a>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endif;
    ?>

    <?php
    /* ==========================================================================
       PROMOTIONAL BANNER - Call to Action
       ========================================================================== */
    
    $cta_enable = $homepage_options['cta_enable'] ?? true;
    
    if ($cta_enable) :
        $cta_title = $homepage_options['cta_title'] ?? 'Special Offer - Limited Time';
        $cta_description = $homepage_options['cta_description'] ?? 'Get 20% off on your first order. Use code: WELCOME20';
        $cta_button_text = $homepage_options['cta_button_text'] ?? 'Shop Now';
        $cta_button_url = $homepage_options['cta_button_url'] ?? (class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#');
    ?>
        <section class="cta-banner-section">
            <div class="container">
                <div class="cta-banner-modern">
                    <div class="cta-content">
                        <span class="cta-badge">🎉 Limited Offer</span>
                        <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
                        <p class="cta-description"><?php echo esc_html($cta_description); ?></p>
                        <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-primary btn-lg">
                            <?php echo esc_html($cta_button_text); ?>
                        </a>
                    </div>
                    <div class="cta-decoration">
                        <div class="cta-circle cta-circle-1"></div>
                        <div class="cta-circle cta-circle-2"></div>
                        <div class="cta-circle cta-circle-3"></div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    /* ==========================================================================
       WHY CHOOSE US - Features/Benefits
       ========================================================================== */
    ?>
    <section class="features-benefits-section">
        <div class="container">
            <div class="features-grid-modern">
                <div class="feature-card-modern">
                    <div class="feature-icon-modern">🚚</div>
                    <h3 class="feature-title-modern">Free Shipping</h3>
                    <p class="feature-desc-modern">On orders over $50. Fast and reliable delivery to your door.</p>
                </div>
                
                <div class="feature-card-modern">
                    <div class="feature-icon-modern">💳</div>
                    <h3 class="feature-title-modern">Secure Payment</h3>
                    <p class="feature-desc-modern">100% secure transactions. Your data is safe with us.</p>
                </div>
                
                <div class="feature-card-modern">
                    <div class="feature-icon-modern">↩️</div>
                    <h3 class="feature-title-modern">Easy Returns</h3>
                    <p class="feature-desc-modern">30-day money-back guarantee. Shop with confidence.</p>
                </div>
                
                <div class="feature-card-modern">
                    <div class="feature-icon-modern">🎧</div>
                    <h3 class="feature-title-modern">24/7 Support</h3>
                    <p class="feature-desc-modern">Our team is always here to help you with any questions.</p>
                </div>
            </div>
        </div>
    </section>

    <?php
    /* ==========================================================================
       TRENDING/NEW ARRIVALS - Latest Products
       ========================================================================== */
    
    if (class_exists('WooCommerce')) :
    ?>
        <section class="new-arrivals-section">
            <div class="container">
                <div class="section-header-modern">
                    <h2 class="section-title-modern">New Arrivals</h2>
                    <p class="section-subtitle-modern">Check out our latest products</p>
                </div>
                
                <div class="products-grid-modern">
                    <?php
                    $new_products_args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 4,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );
                    
                    $new_products = new WP_Query($new_products_args);
                    
                    if ($new_products->have_posts()) :
                        while ($new_products->have_posts()) : $new_products->the_post();
                            global $product;
                            ?>
                            
                            <div class="product-card-modern">
                                <div class="product-image-wrapper">
                                    <a href="<?php echo get_permalink(); ?>">
                                        <?php 
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('medium', array('loading' => 'lazy'));
                                        } else {
                                            echo '<img src="https://via.placeholder.com/400x400/eff6ff/2563eb?text=' . urlencode(get_the_title()) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy" />';
                                        }
                                        ?>
                                    </a>
                                    
                                    <span class="product-badge-new">✨ New</span>
                                    
                                    <div class="product-quick-actions">
                                        <button class="quick-view-btn" title="Quick View">👁️</button>
                                        <button class="wishlist-btn" title="Add to Wishlist">❤️</button>
                                    </div>
                                </div>
                                
                                <div class="product-info-modern">
                                    <?php
                                    $categories = get_the_terms($product->get_id(), 'product_cat');
                                    if ($categories && !is_wp_error($categories)) :
                                        $category = array_shift($categories);
                                        ?>
                                        <span class="product-category-label"><?php echo esc_html($category->name); ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="product-title-modern">
                                        <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="product-price-modern">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    
                                    <div class="product-actions-modern">
                                        <a href="<?php echo $product->add_to_cart_url(); ?>" class="add-to-cart-modern">
                                            🛒 Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    /* ==========================================================================
       PROMOTIONAL SPLIT BANNER
       ========================================================================== */
    ?>
    <section class="promo-split-section">
        <div class="container">
            <div class="promo-split-grid">
                <div class="promo-card promo-card-1">
                    <div class="promo-content">
                        <span class="promo-label">Summer Sale</span>
                        <h3 class="promo-title">Up to 50% Off</h3>
                        <p class="promo-desc">On selected items</p>
                        <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('shop')) : '#'; ?>" class="btn btn-white">
                            Shop Sale →
                        </a>
                    </div>
                </div>
                
                <div class="promo-card promo-card-2">
                    <div class="promo-content">
                        <span class="promo-label">New Collection</span>
                        <h3 class="promo-title">Fresh Arrivals</h3>
                        <p class="promo-desc">Latest products in stock</p>
                        <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('shop')) : '#'; ?>" class="btn btn-white">
                            Explore Now →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    /* ==========================================================================
       TESTIMONIALS - Customer Reviews
       ========================================================================== */
    ?>
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header-modern">
                <h2 class="section-title-modern">What Our Customers Say</h2>
                <p class="section-subtitle-modern">Join thousands of happy customers</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"Amazing products and fast shipping! I'm very satisfied with my purchase. Highly recommended!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">👤</div>
                        <div class="author-info">
                            <strong>Sarah Johnson</strong>
                            <span>Verified Buyer</span>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"Great quality and excellent customer service. Will definitely shop here again!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">👤</div>
                        <div class="author-info">
                            <strong>Mike Davis</strong>
                            <span>Verified Buyer</span>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"Love the eco-friendly packaging and fast delivery. Products exceeded my expectations!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">👤</div>
                        <div class="author-info">
                            <strong>Emma Wilson</strong>
                            <span>Verified Buyer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    /* ==========================================================================
       NEWSLETTER SIGNUP
       ========================================================================== */
    ?>
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-wrapper">
                <div class="newsletter-content">
                    <span class="newsletter-icon">📧</span>
                    <h2 class="newsletter-title">Subscribe to Our Newsletter</h2>
                    <p class="newsletter-desc">Get special offers and updates delivered to your inbox</p>
                </div>
                <div class="newsletter-form-wrapper">
                    <form class="newsletter-form" method="post">
                        <input type="email" placeholder="Enter your email address" class="newsletter-input" required />
                        <button type="submit" class="newsletter-submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php endif; // End default homepage sections ?>

</main>

<?php
get_footer();
