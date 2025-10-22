/**
 * EcoCommerce Pro Admin JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize color pickers - both old and new classes
    if ($.fn.wpColorPicker) {
        $('.color-picker, .wp-color-picker-field').wpColorPicker({
            change: function(event, ui) {
                // Update live preview if it exists
                updateColorPreview();
            }
        });
    }
    
    // Visual Radio Buttons - Handle selection
    $('.visual-radio-option').on('click', function() {
        var $this = $(this);
        var $group = $this.closest('.visual-radio-group');
        
        // Remove selected class from all options in this group
        $group.find('.visual-radio-option').removeClass('selected');
        
        // Add selected class to clicked option
        $this.addClass('selected');
        
        // Check the radio button
        $this.find('input[type="radio"]').prop('checked', true);
    });
    
    // Icon Selector - Handle selection
    $('.icon-option').on('click', function() {
        var $this = $(this);
        var $group = $this.closest('.icon-selector-group');
        
        // Remove selected class from all options
        $group.find('.icon-option').removeClass('selected');
        
        // Add selected class to clicked option
        $this.addClass('selected');
        
        // Check the radio button
        $this.find('input[type="radio"]').prop('checked', true);
    });
    
    // Toggle Switch - Visual feedback
    $('.toggle-switch input').on('change', function() {
        var $wrapper = $(this).closest('.toggle-switch-wrapper');
        if ($(this).is(':checked')) {
            $wrapper.addClass('toggle-active');
        } else {
            $wrapper.removeClass('toggle-active');
        }
    });
    
    // Update color preview function
    function updateColorPreview() {
        var primaryColor = $('input[name*="primary_color"]').val() || '#2563eb';
        var textColor = $('input[name*="text_color"]').val() || '#333333';
        
        $('.preview-header').css('background', primaryColor);
        $('.preview-button').css('background', primaryColor);
        $('.preview-text').css('color', textColor);
        $('.preview-link').css('color', primaryColor);
    }
    
    // Logo Upload
    $('.upload-logo-button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var customUploader = wp.media({
            title: 'Select Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        });
        
        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#site_logo').val(attachment.url);
            $('.logo-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto;" />');
        });
        
        customUploader.open();
    });
    
    // Remove Logo
    $('.remove-logo-button').on('click', function(e) {
        e.preventDefault();
        $('#site_logo').val('');
        $('.logo-preview').html('');
    });
    
    // Favicon Upload
    $('.upload-favicon-button').on('click', function(e) {
        e.preventDefault();
        
        var customUploader = wp.media({
            title: 'Select Favicon',
            button: {
                text: 'Use this favicon'
            },
            multiple: false
        });
        
        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#site_favicon').val(attachment.url);
            $('.favicon-preview').html('<img src="' + attachment.url + '" style="max-width: 64px; height: auto;" />');
        });
        
        customUploader.open();
    });
    
    // Remove Favicon
    $('.remove-favicon-button').on('click', function(e) {
        e.preventDefault();
        $('#site_favicon').val('');
        $('.favicon-preview').html('');
    });
    
    // Hero Background Upload
    $('.upload-hero-bg-button').on('click', function(e) {
        e.preventDefault();
        
        var customUploader = wp.media({
            title: 'Select Hero Background Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#hero_bg').val(attachment.url);
            $('.hero-bg-preview').html('<img src="' + attachment.url + '" style="max-width: 400px; height: auto;" />');
        });
        
        customUploader.open();
    });
    
    // Remove Hero Background
    $('.remove-hero-bg-button').on('click', function(e) {
        e.preventDefault();
        $('#hero_bg').val('');
        $('.hero-bg-preview').html('');
    });
    
    // Color Presets
    var colorPresets = {
        'eco-green': {
            primary_color: '#4CAF50',
            secondary_color: '#8BC34A',
            accent_color: '#FF9800',
            text_color: '#333333',
            heading_color: '#111111',
            link_color: '#4CAF50',
            link_hover_color: '#388E3C',
            background_color: '#FFFFFF'
        },
        'ocean-blue': {
            primary_color: '#2196F3',
            secondary_color: '#03A9F4',
            accent_color: '#00BCD4',
            text_color: '#333333',
            heading_color: '#111111',
            link_color: '#2196F3',
            link_hover_color: '#1976D2',
            background_color: '#FFFFFF'
        },
        'sunset-orange': {
            primary_color: '#FF9800',
            secondary_color: '#FF5722',
            accent_color: '#FFC107',
            text_color: '#333333',
            heading_color: '#111111',
            link_color: '#FF9800',
            link_hover_color: '#F57C00',
            background_color: '#FFFFFF'
        },
        'royal-purple': {
            primary_color: '#9C27B0',
            secondary_color: '#E91E63',
            accent_color: '#FF4081',
            text_color: '#333333',
            heading_color: '#111111',
            link_color: '#9C27B0',
            link_hover_color: '#7B1FA2',
            background_color: '#FFFFFF'
        }
    };
    
    $('.preset-button').on('click', function(e) {
        e.preventDefault();
        
        var preset = $(this).data('preset');
        var colors = colorPresets[preset];
        
        if (colors) {
            $.each(colors, function(key, value) {
                var input = $('input[name="ecocommerce_pro_styling_options[' + key + ']"]');
                if (input.length) {
                    input.val(value);
                    if (input.hasClass('color-picker')) {
                        input.wpColorPicker('color', value);
                    }
                }
            });
            
            alert('Color preset "' + preset + '" applied! Don\'t forget to save your changes.');
        }
    });
    
    // Reset Styles
    $('.reset-styles-button').on('click', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to reset all styling options to their default values?')) {
            var defaults = {
                primary_color: '#4CAF50',
                secondary_color: '#2196F3',
                accent_color: '#FF9800',
                text_color: '#333333',
                heading_color: '#111111',
                link_color: '#4CAF50',
                link_hover_color: '#388E3C',
                background_color: '#FFFFFF',
                body_font_size: '16',
                line_height: '1.6'
            };
            
            $.each(defaults, function(key, value) {
                var input = $('input[name="ecocommerce_pro_styling_options[' + key + ']"]');
                if (input.length) {
                    input.val(value);
                    if (input.hasClass('color-picker')) {
                        input.wpColorPicker('color', value);
                    }
                }
            });
            
            // Reset select fields
            $('select[name="ecocommerce_pro_styling_options[body_font]"]').val('Inter');
            $('select[name="ecocommerce_pro_styling_options[heading_font]"]').val('Inter');
            $('select[name="ecocommerce_pro_styling_options[button_style]"]').val('default');
            $('select[name="ecocommerce_pro_styling_options[button_hover]"]').val('darken');
            
            // Clear custom CSS
            $('textarea[name="ecocommerce_pro_styling_options[custom_css]"]').val('');
            
            alert('Styling options reset to defaults! Don\'t forget to save your changes.');
        }
    });
    
    // Form validation
    $('form').on('submit', function() {
        // Validate URLs
        $(this).find('input[type="url"]').each(function() {
            var url = $(this).val();
            if (url && !url.match(/^https?:\/\//)) {
                alert('Please enter a valid URL starting with http:// or https://');
                $(this).focus();
                return false;
            }
        });
        
        // Validate email
        $(this).find('input[type="email"]').each(function() {
            var email = $(this).val();
            if (email && !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                alert('Please enter a valid email address');
                $(this).focus();
                return false;
            }
        });
    });
    
    // Auto-save notification
    var hasUnsavedChanges = false;
    
    $('input, textarea, select').on('change', function() {
        hasUnsavedChanges = true;
    });
    
    $(window).on('beforeunload', function() {
        if (hasUnsavedChanges) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
    
    $('form').on('submit', function() {
        hasUnsavedChanges = false;
    });
    
    // Add helpful tooltips
    $('[data-tooltip]').hover(
        function() {
            var tooltip = $('<div class="ecocommerce-tooltip">' + $(this).data('tooltip') + '</div>');
            $('body').append(tooltip);
            
            var pos = $(this).offset();
            tooltip.css({
                position: 'absolute',
                top: pos.top - tooltip.height() - 10,
                left: pos.left + ($(this).width() / 2) - (tooltip.width() / 2),
                background: '#1d2327',
                color: '#fff',
                padding: '8px 12px',
                borderRadius: '4px',
                fontSize: '12px',
                zIndex: 10000
            });
        },
        function() {
            $('.ecocommerce-tooltip').remove();
        }
    );
});

