/**
 * Preloader Admin Interface
 * Handles image upload and type switching
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        /**
         * Handle preloader type selection
         */
        $('input[name="ecocommerce_pro_general_options[preloader_type]"]').on('change', function() {
            const selectedType = $(this).val();
            
            // Update selected state
            $('.preloader-option').removeClass('selected');
            $(this).closest('.preloader-option').addClass('selected');
            
            // Show/hide custom image section
            if (selectedType === 'custom') {
                $('.custom-preloader-section').slideDown(300);
            } else {
                $('.custom-preloader-section').slideUp(300);
            }
        });
        
        /**
         * Upload custom preloader image
         */
        $('.upload-preloader-btn').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const customFrame = wp.media({
                title: 'Select Preloader Image',
                button: {
                    text: 'Use This Image'
                },
                multiple: false,
                library: {
                    type: ['image/gif', 'image/png', 'image/svg+xml', 'image/jpeg']
                }
            });
            
            customFrame.on('select', function() {
                const attachment = customFrame.state().get('selection').first().toJSON();
                
                // Update input field
                $('.preloader-custom-url').val(attachment.url);
                
                // Show preview if not exists
                if ($('.preloader-preview-image').length === 0) {
                    const preview = $('<div class="preloader-preview-image"><img src="' + attachment.url + '" style="max-width: 150px; max-height: 150px;" /></div>');
                    $('.custom-preloader-section .field-description').before(preview);
                    
                    // Add remove button if not exists
                    if ($('.remove-preloader-btn').length === 0) {
                        $('.image-upload-wrapper').append('<button type="button" class="button button-secondary remove-preloader-btn">Remove</button>');
                    }
                } else {
                    $('.preloader-preview-image img').attr('src', attachment.url);
                }
                
                // Show success message
                showNotice('Preloader image selected!', 'success');
            });
            
            customFrame.open();
        });
        
        /**
         * Remove custom preloader image
         */
        $(document).on('click', '.remove-preloader-btn', function(e) {
            e.preventDefault();
            
            if (confirm('Remove custom preloader image?')) {
                $('.preloader-custom-url').val('');
                $('.preloader-preview-image').fadeOut(300, function() {
                    $(this).remove();
                });
                $(this).remove();
                
                showNotice('Preloader image removed', 'info');
            }
        });
        
        /**
         * Show admin notice
         */
        function showNotice(message, type) {
            const notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            $('.wrap h1').after(notice);
            
            setTimeout(function() {
                notice.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
    });
    
})(jQuery);

