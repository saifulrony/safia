/**
 * Backup & Restore System for Theme Settings
 * Export, Import, and Reset all theme options
 */

(function($) {
    'use strict';
    
    /**
     * Show notification message
     */
    function showMessage(type, text) {
        const icon = type === 'success' ? '✅' : '❌';
        const $message = $(`
            <div class="backup-message ${type}">
                <div class="backup-message-icon">${icon}</div>
                <div class="backup-message-text">${text}</div>
            </div>
        `);
        
        $('body').append($message);
        
        setTimeout(() => {
            $message.addClass('show');
        }, 100);
        
        setTimeout(() => {
            $message.removeClass('show');
            setTimeout(() => {
                $message.remove();
            }, 300);
        }, 3000);
    }
    
    /**
     * Show confirmation modal
     */
    function showConfirmModal(options) {
        const modal = $(`
            <div class="backup-modal-overlay">
                <div class="backup-modal">
                    <div class="modal-icon">${options.icon}</div>
                    <h2 class="modal-title">${options.title}</h2>
                    <p class="modal-message">${options.message}</p>
                    <div class="modal-actions">
                        ${options.showCancel ? '<button class="modal-btn modal-btn-secondary modal-cancel">Cancel</button>' : ''}
                        <button class="modal-btn ${options.dangerMode ? 'modal-btn-danger' : 'modal-btn-primary'} modal-confirm">${options.confirmText}</button>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(modal);
        
        setTimeout(() => {
            modal.addClass('active');
        }, 10);
        
        return new Promise((resolve) => {
            modal.find('.modal-confirm').on('click', function() {
                modal.removeClass('active');
                setTimeout(() => {
                    modal.remove();
                    resolve(true);
                }, 300);
            });
            
            modal.find('.modal-cancel, .backup-modal-overlay').on('click', function(e) {
                if (e.target === this) {
                    modal.removeClass('active');
                    setTimeout(() => {
                        modal.remove();
                        resolve(false);
                    }, 300);
                }
            });
        });
    }
    
    /**
     * Backup all settings
     */
    $('#backup-all-settings').on('click', async function() {
        const confirmed = await showConfirmModal({
            icon: '💾',
            title: 'Backup Theme Settings',
            message: 'This will download all your theme settings as a JSON file. You can use this file to restore your settings later.',
            confirmText: 'Download Backup',
            showCancel: true,
            dangerMode: false
        });
        
        if (!confirmed) return;
        
        const button = $(this);
        const originalHtml = button.html();
        button.html('<span class="backup-loading"></span> Creating Backup...');
        button.prop('disabled', true);
        
        try {
            // Get all theme options
            const response = await $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'ecocommerce_backup_settings',
                    nonce: ecocommerceBackup.nonce
                }
            });
            
            if (response.success) {
                // Create download
                const dataStr = JSON.stringify(response.data, null, 2);
                const dataBlob = new Blob([dataStr], { type: 'application/json' });
                const url = URL.createObjectURL(dataBlob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `ecocommerce-pro-backup-${Date.now()}.json`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                
                showMessage('success', 'Backup downloaded successfully!');
            } else {
                showMessage('error', 'Failed to create backup');
            }
        } catch (error) {
            showMessage('error', 'Error creating backup');
            console.error(error);
        }
        
        button.html(originalHtml);
        button.prop('disabled', false);
    });
    
    /**
     * Restore settings from backup
     */
    $('#restore-settings').on('click', async function() {
        const confirmed = await showConfirmModal({
            icon: '📤',
            title: 'Restore Theme Settings',
            message: 'Select a backup file to restore your theme settings. This will overwrite your current settings.',
            confirmText: 'Choose File',
            showCancel: true,
            dangerMode: false
        });
        
        if (!confirmed) return;
        
        $('#import-file-input').trigger('click');
    });
    
    /**
     * Handle file selection
     */
    $('#import-file-input').on('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        
        reader.onload = async function(event) {
            try {
                const settings = JSON.parse(event.target.result);
                
                const confirmed = await showConfirmModal({
                    icon: '⚠️',
                    title: 'Confirm Restore',
                    message: 'This will replace all your current theme settings with the backup. This action cannot be undone. Are you sure?',
                    confirmText: 'Yes, Restore Settings',
                    showCancel: true,
                    dangerMode: true
                });
                
                if (!confirmed) {
                    $('#import-file-input').val('');
                    return;
                }
                
                // Show loading
                const $overlay = $('<div class="backup-modal-overlay active"><div class="backup-modal"><div class="modal-icon">⏳</div><h2 class="modal-title">Restoring Settings...</h2><p class="modal-message">Please wait while we restore your theme settings.</p></div></div>');
                $('body').append($overlay);
                
                // Restore settings
                const response = await $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'ecocommerce_restore_settings',
                        nonce: ecocommerceBackup.nonce,
                        settings: JSON.stringify(settings)
                    }
                });
                
                $overlay.remove();
                
                if (response.success) {
                    showMessage('success', 'Settings restored successfully! Reloading page...');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showMessage('error', response.data || 'Failed to restore settings');
                }
            } catch (error) {
                showMessage('error', 'Invalid backup file');
                console.error(error);
            }
            
            $('#import-file-input').val('');
        };
        
        reader.readAsText(file);
    });
    
    /**
     * Reset all settings to defaults
     */
    $('#reset-all-settings').on('click', async function() {
        const confirmed = await showConfirmModal({
            icon: '⚠️',
            title: 'Reset to Defaults',
            message: 'This will reset ALL theme settings to their default values. All your customizations will be lost. This action cannot be undone!',
            confirmText: 'Yes, Reset Everything',
            showCancel: true,
            dangerMode: true
        });
        
        if (!confirmed) return;
        
        // Double confirmation for reset
        const doubleConfirm = await showConfirmModal({
            icon: '🚨',
            title: 'Are You Absolutely Sure?',
            message: 'This is your last chance! Resetting will permanently delete all your theme customizations. Consider backing up first.',
            confirmText: 'Yes, I Am Sure',
            showCancel: true,
            dangerMode: true
        });
        
        if (!doubleConfirm) return;
        
        // Show loading
        const $overlay = $('<div class="backup-modal-overlay active"><div class="backup-modal"><div class="modal-icon">⏳</div><h2 class="modal-title">Resetting Settings...</h2><p class="modal-message">Please wait while we reset all theme settings to defaults.</p></div></div>');
        $('body').append($overlay);
        
        try {
            const response = await $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'ecocommerce_reset_settings',
                    nonce: ecocommerceBackup.nonce
                }
            });
            
            $overlay.remove();
            
            if (response.success) {
                showMessage('success', 'Settings reset successfully! Reloading page...');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showMessage('error', 'Failed to reset settings');
            }
        } catch (error) {
            $overlay.remove();
            showMessage('error', 'Error resetting settings');
            console.error(error);
        }
    });
    
})(jQuery);

