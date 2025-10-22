<?php
/**
 * Template for displaying search forms
 *
 * @package EcoCommerce_Pro
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x('Search for:', 'label', 'ecocommerce-pro'); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'ecocommerce-pro'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <input type="submit" class="search-submit" value="<?php echo esc_attr_x('Search', 'submit button', 'ecocommerce-pro'); ?>" />
</form>
