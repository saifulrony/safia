<?php
/**
 * Template Name: Blank Template (Builders)
 * Template Post Type: page
 * 
 * Minimal blank template for any page builder
 * 
 * @package EcoCommerce_Pro
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('blank-template'); ?>>
    <div id="page" class="site">
        <div id="content" class="site-content">
            <main id="primary" class="site-main">
                <?php
                while (have_posts()) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </main>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>

