<?php
/**
 * WooCommerce template wrapper
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div id="primary" class="content-area" style="padding: 40px 0;">
    <main id="main" class="site-main">
        <div class="container">
            <?php woocommerce_content(); ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>

