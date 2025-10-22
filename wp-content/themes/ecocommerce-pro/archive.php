<?php
/**
 * The template for displaying archive pages
 *
 * @package EcoCommerce_Pro
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="content-area col-8">
                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                    ?>
                </header>

                <?php if (have_posts()) : ?>
                    <div class="posts-container">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('ecocommerce-featured'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="post-content">
                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>
                                        
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
                                    
                                    <div class="entry-summary">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <footer class="entry-footer">
                                        <a href="<?php the_permalink(); ?>" class="read-more btn">
                                            <?php esc_html_e('Read More', 'ecocommerce-pro'); ?>
                                        </a>
                                        
                                        <?php if (has_tag()) : ?>
                                            <div class="tag-links">
                                                <?php the_tags('', ', '); ?>
                                            </div>
                                        <?php endif; ?>
                                    </footer>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php
                    // Pagination
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => __('Previous', 'ecocommerce-pro'),
                        'next_text' => __('Next', 'ecocommerce-pro'),
                    ));
                    ?>
                    
                <?php else : ?>
                    <div class="no-posts">
                        <h2><?php esc_html_e('Nothing Found', 'ecocommerce-pro'); ?></h2>
                        <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'ecocommerce-pro'); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <aside class="site-sidebar col-4">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</main>

<?php
get_footer();
