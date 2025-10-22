<?php
/**
 * The header for our theme - Porto Style
 *
 * @package EcoCommerce_Pro
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site porto-site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'ecocommerce-pro'); ?></a>

    <!-- Porto-Style Top Bar -->
    <div class="porto-top-bar">
        <div class="container">
            <div class="topbar-left">
                <span class="promo-text">🎉 Get Up to <strong>40% OFF</strong> New-Season Styles</span>
            </div>
            <div class="topbar-right">
                <nav class="topbar-menu">
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">My Account</a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">Log In</a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
                    <a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a>
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link-top">
                            Cart (<?php echo WC()->cart->get_cart_contents_count(); ?>)
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Porto-Style Main Header -->
    <header id="masthead" class="site-header porto-header">
        <div class="container">
            <div class="porto-header-layout">
                
                <!-- Logo -->
                <div class="site-branding porto-logo">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <?php
                    }
                    ?>
                </div>

                <!-- Search Bar (Porto Style) -->
                <div class="porto-search-bar">
                    <form role="search" method="get" class="porto-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <select class="search-category" name="product_cat">
                            <option value="">All Categories</option>
                            <?php
                            if (class_exists('WooCommerce')) {
                                $categories = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'hide_empty' => false,
                                    'number' => 10
                                ));
                                foreach ($categories as $category) {
                                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <input type="search" class="search-field" placeholder="Search products..." name="s" />
                        <button type="submit" class="search-submit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Call Us -->
                <div class="porto-call-us">
                    <span class="call-label">CALL US NOW</span>
                    <a href="tel:+1235678890" class="call-number">+123 5678 890</a>
                </div>

                <!-- Cart Icon -->
                <div class="porto-cart-wrapper">
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a class="porto-cart-icon" href="<?php echo esc_url(wc_get_cart_url()); ?>">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <?php 
                            $cart_count = WC()->cart->get_cart_contents_count();
                            if ($cart_count > 0) : 
                            ?>
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Porto-Style Navigation Menu Bar -->
    <nav class="porto-navigation-bar">
        <div class="container">
            <button class="porto-mobile-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
            
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'nav-menu porto-nav-menu',
                'container'      => false,
                'fallback_cb'    => 'ecocommerce_pro_default_menu',
            ));
            ?>
        </div>
    </nav>

    <div id="content" class="site-content">
