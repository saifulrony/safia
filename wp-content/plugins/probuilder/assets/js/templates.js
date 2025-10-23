/**
 * ProBuilder Templates
 */
(function($) {
    'use strict';
    
    const ProBuilderTemplates = {
        
        templates: [],
        
        /**
         * Initialize
         */
        init: function() {
            console.log('ProBuilder Templates initializing...');
            this.loadTemplates();
            this.bindEvents();
        },
        
        /**
         * Load templates via AJAX
         */
        loadTemplates: function() {
            const self = this;
            
            console.log('Loading templates via AJAX...');
            console.log('AJAX URL:', ProBuilderEditor.ajaxurl);
            console.log('Nonce:', ProBuilderEditor.nonce);
            
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_get_templates',
                    nonce: ProBuilderEditor.nonce
                },
                success: function(response) {
                    console.log('AJAX response:', response);
                    
                    if (response.success) {
                        self.templates = response.data;
                        console.log('Templates loaded successfully:');
                        console.log('- Prebuilt:', self.templates.prebuilt ? self.templates.prebuilt.length : 0);
                        console.log('- User:', self.templates.user ? self.templates.user.length : 0);
                        self.renderTemplates();
                    } else {
                        console.error('Template loading failed:', response);
                        self.showError('Failed to load templates');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    self.showError('Network error while loading templates');
                }
            });
        },
        
        /**
         * Show error message
         */
        showError: function(message) {
            const html = `
                <div class="probuilder-templates-error" style="text-align: center; padding: 60px 20px; color: #dc2626;">
                    <i class="dashicons dashicons-warning" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <h3 style="font-size: 16px; margin: 0 0 10px 0;">${message}</h3>
                    <button class="probuilder-btn probuilder-btn-secondary" onclick="window.ProBuilderTemplates.loadTemplates()">
                        <i class="dashicons dashicons-update"></i> Retry
                    </button>
                </div>
            `;
            
            $('.probuilder-tab-content[data-tab="templates"]').html(html);
        },
        
        /**
         * Render templates in the tab
         */
        renderTemplates: function() {
            console.log('Rendering templates...');
            
            const categories = {
                'all': 'All Templates',
                'hero': 'Hero Sections',
                'features': 'Features',
                'pricing': 'Pricing Tables',
                'team': 'Team',
                'testimonials': 'Testimonials',
                'cta': 'Call to Action',
                'gallery': 'Galleries',
                'stats': 'Stats',
                'services': 'Services',
                'contact': 'Contact',
                'newsletter': 'Newsletter'
            };
            
            let html = '<div class="probuilder-templates-container">';
            
            // Template categories filter
            html += '<div class="probuilder-template-filters">';
            Object.keys(categories).forEach(cat => {
                html += `<button class="probuilder-template-filter ${cat === 'all' ? 'active' : ''}" data-category="${cat}">${categories[cat]}</button>`;
            });
            html += '</div>';
            
            // Save current design button
            html += '<div class="probuilder-template-actions">';
            html += '<button class="probuilder-btn probuilder-btn-primary" id="probuilder-save-as-template">';
            html += '<i class="dashicons dashicons-saved"></i> Save Current Design as Template';
            html += '</button>';
            html += '</div>';
            
            // Templates grid
            html += '<div class="probuilder-templates-grid">';
            
            // Pre-built templates
            if (this.templates.prebuilt && this.templates.prebuilt.length > 0) {
                html += '<div class="probuilder-templates-section">';
                html += '<h3 class="probuilder-templates-section-title">Pre-built Templates</h3>';
                html += '<div class="probuilder-templates-list">';
                
                this.templates.prebuilt.forEach(template => {
                    html += `
                        <div class="probuilder-template-card" data-category="${template.category}" data-id="${template.id}" data-type="prebuilt">
                            <div class="probuilder-template-thumbnail">
                                <img src="${template.thumbnail}" alt="${template.name}">
                                <div class="probuilder-template-overlay">
                                    <button class="probuilder-template-insert" title="Insert Template">
                                        <i class="dashicons dashicons-plus"></i> Insert
                                    </button>
                                </div>
                            </div>
                            <div class="probuilder-template-info">
                                <h4>${template.name}</h4>
                                <span class="probuilder-template-category">${categories[template.category] || template.category}</span>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div></div>';
            }
            
            // User templates
            if (this.templates.user && this.templates.user.length > 0) {
                html += '<div class="probuilder-templates-section">';
                html += '<h3 class="probuilder-templates-section-title">My Templates</h3>';
                html += '<div class="probuilder-templates-list">';
                
                this.templates.user.forEach(template => {
                    html += `
                        <div class="probuilder-template-card" data-category="${template.category}" data-id="${template.id}" data-type="user">
                            <div class="probuilder-template-thumbnail">
                                <div class="probuilder-template-placeholder">
                                    <i class="dashicons dashicons-admin-page"></i>
                                </div>
                                <div class="probuilder-template-overlay">
                                    <button class="probuilder-template-insert" title="Insert Template">
                                        <i class="dashicons dashicons-plus"></i> Insert
                                    </button>
                                    <button class="probuilder-template-delete" title="Delete Template">
                                        <i class="dashicons dashicons-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="probuilder-template-info">
                                <h4>${template.name}</h4>
                                <span class="probuilder-template-category">${template.category}</span>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div></div>';
            }
            
            html += '</div></div>';
            
            const $templateTab = $('.probuilder-tab-content[data-tab="templates"]');
            console.log('Template tab found:', $templateTab.length > 0);
            
            if ($templateTab.length === 0) {
                console.error('Templates tab not found!');
                return;
            }
            
            $templateTab.html(html);
            console.log('✅ Templates rendered successfully!');
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            const self = this;
            
            // Filter templates by category
            $(document).on('click', '.probuilder-template-filter', function() {
                $('.probuilder-template-filter').removeClass('active');
                $(this).addClass('active');
                
                const category = $(this).data('category');
                
                if (category === 'all') {
                    $('.probuilder-template-card').show();
                } else {
                    $('.probuilder-template-card').hide();
                    $(`.probuilder-template-card[data-category="${category}"]`).show();
                }
            });
            
            // Insert template
            $(document).on('click', '.probuilder-template-insert', function() {
                const $card = $(this).closest('.probuilder-template-card');
                const templateId = $card.data('id');
                const type = $card.data('type');
                
                self.insertTemplate(templateId, type);
            });
            
            // Delete user template
            $(document).on('click', '.probuilder-template-delete', function(e) {
                e.stopPropagation();
                
                if (!confirm('Are you sure you want to delete this template?')) {
                    return;
                }
                
                const $card = $(this).closest('.probuilder-template-card');
                const templateId = $card.data('id');
                
                self.deleteTemplate(templateId, $card);
            });
            
            // Save current design as template
            $(document).on('click', '#probuilder-save-as-template', function() {
                self.showSaveTemplateDialog();
            });
        },
        
        /**
         * Insert template
         */
        insertTemplate: function(templateId, type) {
            console.log('Inserting template:', templateId, type);
            
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_import_template',
                    template_id: templateId,
                    type: type,
                    nonce: ProBuilderEditor.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        // Import template data into editor
                        if (window.ProBuilderApp) {
                            window.ProBuilderApp.importTemplate(response.data);
                        }
                    }
                },
                error: function() {
                    alert('Failed to load template');
                }
            });
        },
        
        /**
         * Delete template
         */
        deleteTemplate: function(templateId, $card) {
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_delete_template',
                    template_id: templateId,
                    nonce: ProBuilderEditor.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $card.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function() {
                    alert('Failed to delete template');
                }
            });
        },
        
        /**
         * Show save template dialog
         */
        showSaveTemplateDialog: function() {
            const html = `
                <div class="probuilder-modal-overlay" id="probuilder-save-template-modal">
                    <div class="probuilder-modal">
                        <div class="probuilder-modal-header">
                            <h3>Save as Template</h3>
                            <button class="probuilder-modal-close">&times;</button>
                        </div>
                        <div class="probuilder-modal-content">
                            <div class="probuilder-control">
                                <label>Template Name</label>
                                <input type="text" id="template-name" class="probuilder-input" placeholder="My Awesome Template">
                            </div>
                            <div class="probuilder-control">
                                <label>Category</label>
                                <select id="template-category" class="probuilder-select">
                                    <option value="hero">Hero Section</option>
                                    <option value="features">Features</option>
                                    <option value="pricing">Pricing</option>
                                    <option value="team">Team</option>
                                    <option value="testimonials">Testimonials</option>
                                    <option value="cta">Call to Action</option>
                                    <option value="gallery">Gallery</option>
                                    <option value="stats">Stats</option>
                                    <option value="services">Services</option>
                                    <option value="contact">Contact</option>
                                    <option value="newsletter">Newsletter</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                        <div class="probuilder-modal-footer">
                            <button class="probuilder-btn probuilder-btn-secondary probuilder-modal-close">Cancel</button>
                            <button class="probuilder-btn probuilder-btn-primary" id="confirm-save-template">Save Template</button>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(html);
            
            // Close modal
            $('.probuilder-modal-close').on('click', function() {
                $('#probuilder-save-template-modal').fadeOut(200, function() {
                    $(this).remove();
                });
            });
            
            // Confirm save
            $('#confirm-save-template').on('click', function() {
                const name = $('#template-name').val();
                const category = $('#template-category').val();
                
                if (!name) {
                    alert('Please enter a template name');
                    return;
                }
                
                ProBuilderTemplates.saveCurrentDesign(name, category);
                $('#probuilder-save-template-modal').fadeOut(200, function() {
                    $(this).remove();
                });
            });
        },
        
        /**
         * Save current design as template
         */
        saveCurrentDesign: function(name, category) {
            const currentData = window.ProBuilderApp ? JSON.stringify(window.ProBuilderApp.elements) : '[]';
            
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_save_template',
                    name: name,
                    category: category,
                    data: currentData,
                    nonce: ProBuilderEditor.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Template saved successfully!');
                        // Reload templates
                        ProBuilderTemplates.loadTemplates();
                    }
                },
                error: function() {
                    alert('Failed to save template');
                }
            });
        }
    };
    
    // Initialize when Templates tab is clicked
    $(document).on('click', '.probuilder-tab-btn[data-tab="templates"]', function() {
        console.log('Templates tab clicked');
        
        // Show templates content
        $('.probuilder-tab-content').removeClass('active');
        $('.probuilder-tab-content[data-tab="templates"]').addClass('active');
        
        // Update tab button state
        $('.probuilder-tab-btn').removeClass('active');
        $(this).addClass('active');
        
        // Load templates if not loaded
        if (!ProBuilderTemplates.templates.prebuilt && !ProBuilderTemplates.templates.user) {
            console.log('Loading templates for first time...');
            ProBuilderTemplates.init();
        }
    });
    
    // Initialize immediately when document is ready
    $(document).ready(function() {
        console.log('ProBuilder Templates ready');
        
        // Load templates immediately
        setTimeout(function() {
            ProBuilderTemplates.init();
        }, 500);
    });
    
    // Make it globally accessible
    window.ProBuilderTemplates = ProBuilderTemplates;
    
})(jQuery);

