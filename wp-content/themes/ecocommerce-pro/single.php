<?php
/**
 * The template for displaying all single posts
 *
 * @package EcoCommerce_Pro
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="content-area col-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </span>
                                
                                <span class="byline">
                                    <?php esc_html_e('by', 'ecocommerce-pro'); ?>
                                    <span class="author vcard">
                                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo esc_html(get_the_author()); ?>
                                        </a>
                                    </span>
                                </span>
                                
                                <?php if (has_category()) : ?>
                                    <span class="cat-links">
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="entry-thumbnail">
                                <?php the_post_thumbnail('ecocommerce-featured'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            the_content(sprintf(
                                wp_kses(
                                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'ecocommerce-pro'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                get_the_title()
                            ));

                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'ecocommerce-pro'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <footer class="entry-footer">
                            <?php if (has_tag()) : ?>
                                <div class="tag-links">
                                    <?php the_tags('', ', '); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (get_edit_post_link()) : ?>
                                <div class="edit-link">
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
                                </div>
                            <?php endif; ?>
                        </footer>
                    </article>

                    <?php
                    // Post navigation
                    the_post_navigation(array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'ecocommerce-pro') . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'ecocommerce-pro') . '</span> <span class="nav-title">%title</span>',
                    ));
                    ?>

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
