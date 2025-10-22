<?php
/**
 * Template Name: Full Width (Page Builders)
 * Template Post Type: page
 * 
 * Full width template for page builders like Elementor, Beaver Builder, etc.
 * 
 * @package EcoCommerce_Pro
 */

get_header(); ?>

<main id="primary" class="site-main full-width-template">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('full-width-content'); ?>>
            <div class="entry-content">
                <?php
                the_content();
                
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'ecocommerce-pro'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();

