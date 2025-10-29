/**
 * ProBuilder License Management
 */

(function($) {
    'use strict';
    
    // Activate License
    $('#probuilder-license-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const $message = $('#license-message');
        const licenseKey = $('#license_key').val().trim();
        
        if (!licenseKey) {
            showMessage('error', 'Please enter a license key.');
            return;
        }
        
        // Show loading state
        $button.addClass('loading').prop('disabled', true);
        $message.removeClass('success error').html('');
        
        $.ajax({
            url: probuilderLicense.ajax_url,
            type: 'POST',
            data: {
                action: 'probuilder_activate_license',
                nonce: probuilderLicense.nonce,
                license_key: licenseKey
            },
            success: function(response) {
                $button.removeClass('loading').prop('disabled', false);
                
                if (response.success) {
                    showMessage('success', response.data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showMessage('error', response.data.message);
                }
            },
            error: function() {
                $button.removeClass('loading').prop('disabled', false);
                showMessage('error', 'An error occurred. Please try again.');
            }
        });
    });
    
    // Deactivate License
    $('#probuilder-deactivate-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to deactivate this license?')) {
            return;
        }
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const $message = $('#license-message');
        
        $button.addClass('loading').prop('disabled', true);
        $message.removeClass('success error').html('');
        
        $.ajax({
            url: probuilderLicense.ajax_url,
            type: 'POST',
            data: {
                action: 'probuilder_deactivate_license',
                nonce: probuilderLicense.nonce
            },
            success: function(response) {
                $button.removeClass('loading').prop('disabled', false);
                
                if (response.success) {
                    showMessage('success', response.data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showMessage('error', response.data.message || 'Deactivation failed.');
                }
            },
            error: function() {
                $button.removeClass('loading').prop('disabled', false);
                showMessage('error', 'An error occurred. Please try again.');
            }
        });
    });
    
    function showMessage(type, message) {
        const $message = $('#license-message');
        $message.removeClass('success error').addClass(type).html(message).fadeIn();
    }
    
})(jQuery);

