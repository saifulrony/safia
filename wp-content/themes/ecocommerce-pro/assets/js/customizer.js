/**
 * EcoCommerce Pro - Customizer JavaScript
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initCustomizerPreview();
    });

    /**
     * Customizer Preview
     */
    function initCustomizerPreview() {
        // Primary Color
        wp.customize('ecocommerce_pro_primary_color', function(value) {
            value.bind(function(newval) {
                $(':root').css('--primary-color', newval);
                $('.btn, .woocommerce .button').css('background-color', newval);
                $('.main-navigation a:hover::after').css('background', newval);
                $('a').css('color', newval);
            });
        });

        // Secondary Color
        wp.customize('ecocommerce_pro_secondary_color', function(value) {
            value.bind(function(newval) {
                $(':root').css('--secondary-color', newval);
                $('.btn-secondary, .woocommerce .button.alt').css('background-color', newval);
            });
        });

        // Header Background Color
        wp.customize('ecocommerce_pro_header_bg', function(value) {
            value.bind(function(newval) {
                $('.site-header').css('background-color', newval);
            });
        });

        // Footer Background Color
        wp.customize('ecocommerce_pro_footer_bg', function(value) {
            value.bind(function(newval) {
                $('.site-footer').css('background-color', newval);
            });
        });

        // Body Font Size
        wp.customize('ecocommerce_pro_body_font_size', function(value) {
            value.bind(function(newval) {
                $('body').css('font-size', newval + 'px');
            });
        });

        // Headings Font Family
        wp.customize('ecocommerce_pro_headings_font', function(value) {
            value.bind(function(newval) {
                const fontFamily = getFontFamily(newval);
                $('h1, h2, h3, h4, h5, h6').css('font-family', fontFamily);
            });
        });

        // Blog Name
        wp.customize('blogname', function(value) {
            blinking value.bind(function(newval) {
                $('.site-title a').text(newval);
            });
        });

        // Blog Description
        wp.customize('blogdescription', function(value) {
            value.bind(function(newval) {
                $('.site-description').text(newval);
            });
        });

        // Custom CSS
        wp.customize('ecocommerce_pro_custom_css', function(value) {
            value.bind(function(newval) {
                // Remove previous custom CSS
                $('#ecocommerce-custom-css').remove();
                
                // Add new custom CSS
                if (newval) {
                    $('head').append('<style id="ecocommerce-custom-css">' + newval + '</style>');
                }
            });
        });
    }

    /**
     * Get Font Family
     */
    function getFontFamily(fontType) {
        const fonts = {
            'default': '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            'serif': 'Georgia, "Times New Roman", serif',
            'sans-serif': 'Arial, Helvetica, sans-serif',
            'monospace': '"Courier New", Courier, monospace'
        };
        
        return fonts[fontType] || fonts['default'];
    }

})(jQuery);
