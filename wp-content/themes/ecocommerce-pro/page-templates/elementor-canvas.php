<?php
/**
 * Template Name: Elementor Canvas
 * Template Post Type: page
 * 
 * Blank canvas template for Elementor (no header/footer)
 * 
 * @package EcoCommerce_Pro
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!current_user_can('manage_options')) : ?>
        <meta name="robots" content="noindex,nofollow">
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class('elementor-canvas'); ?>>
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
    <?php wp_footer(); ?>
</body>
</html>

