<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package EcoCommerce_Pro
 */

get_header();

// Check if this is an Elementor Canvas page
$is_elementor_canvas = get_post_meta(get_the_ID(), '_wp_page_template', true) === 'elementor_canvas';

if ($is_elementor_canvas) {
    // For Elementor Canvas, just show content without container
    ?>
    <main id="primary" class="site-main">
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    </main>
    <?php
    get_footer();
    return;
}
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="content-area col-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'ecocommerce-pro'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <?php if (get_edit_post_link()) : ?>
                            <footer class="entry-footer">
                                <?php
                                edit_post_link(
                                    sprintf(
                                        wp_kses(
                                            __('Edit <span class="screen-reader-text">%s</span>', 'ecocommerce-pro'),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        get_the_title()
                                    ),
                                    '<span class="edit-link">',
                                    '</span>'
                                );
                                ?>
                            </footer>
                        <?php endif; ?>
                    </article>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; ?>
            </div>
            
            <aside class="site-sidebar col-4">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</main>

<?php
get_footer();
