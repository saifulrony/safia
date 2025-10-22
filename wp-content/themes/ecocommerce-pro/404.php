<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package EcoCommerce_Pro
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="content-area col-12">
                <section class="error-404 not-found">
                    <div class="error-content">
                        <div class="error-illustration">
                            <h1 class="error-code">404</h1>
                        </div>
                        
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'ecocommerce-pro'); ?></h1>
                        </header>

                        <div class="page-content">
                            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ecocommerce-pro'); ?></p>

                            <div class="error-actions">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Go Home', 'ecocommerce-pro'); ?>
                                </a>
                                
                                <?php if (class_exists('WooCommerce')) : ?>
                                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-secondary">
                                        <?php esc_html_e('Shop Now', 'ecocommerce-pro'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php get_search_form(); ?>

                            <div class="widget">
                                <h2><?php esc_html_e('Most Used Categories', 'ecocommerce-pro'); ?></h2>
                                <ul>
                                    <?php
                                    wp_list_categories(array(
                                        'orderby'    => 'count',
                                        'order'      => 'DESC',
                                        'show_count' => 1,
                                        'title_li'   => '',
                                        'number'     => 10,
                                    ));
                                    ?>
                                </ul>
                            </div>

                            <div class="widget">
                                <h2><?php esc_html_e('Recent Posts', 'ecocommerce-pro'); ?></h2>
                                <ul>
                                    <?php
                                    $recent_posts = wp_get_recent_posts(array(
                                        'numberposts' => 5,
                                        'post_status' => 'publish'
                                    ));
                                    
                                    foreach ($recent_posts as $post) :
                                        ?>
                                        <li>
                                            <a href="<?php echo get_permalink($post['ID']); ?>">
                                                <?php echo esc_html($post['post_title']); ?>
                                            </a>
                                        </li>
                                        <?php
                                    endforeach;
                                    ?>
                                </ul>
                            </div>

                            <?php if (class_exists('WooCommerce')) : ?>
                                <div class="widget">
                                    <h2><?php esc_html_e('Popular Products', 'ecocommerce-pro'); ?></h2>
                                    <div class="products-grid">
                                        <?php
                                        $products = wc_get_products(array(
                                            'limit' => 4,
                                            'orderby' => 'popularity',
                                            'status' => 'publish'
                                        ));
                                        
                                        foreach ($products as $product) :
                                            ?>
                                            <div class="product-item">
                                                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                                    <?php echo $product->get_image(); ?>
                                                    <h3><?php echo esc_html($product->get_name()); ?></h3>
                                                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                                                </a>
                                            </div>
                                            <?php
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
