<?php
/**
 * ProBuilder Canvas Template
 * Full-width template without theme header/footer/sidebar
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get the post content
global $post;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!current_theme_supports('title-tag')) : ?>
        <title><?php echo wp_get_document_title(); ?></title>
    <?php endif; ?>
    <?php wp_head(); ?>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .probuilder-canvas-wrapper {
            width: 100%;
            min-height: 100vh;
        }
    </style>
</head>
<body <?php body_class('probuilder-canvas-mode'); ?>>

<div class="probuilder-canvas-wrapper">
    <?php
    while (have_posts()) {
        the_post();
        
        // Get ProBuilder data
        $probuilder_data = get_post_meta(get_the_ID(), '_probuilder_data', true);
        
        if (!empty($probuilder_data)) {
            // Render ProBuilder content
            $frontend = ProBuilder_Frontend::instance();
            echo $frontend->render_elements($probuilder_data);
        } else {
            // Fallback to default content
            the_content();
        }
    }
    ?>
</div>

<?php wp_footer(); ?>

</body>
</html>

