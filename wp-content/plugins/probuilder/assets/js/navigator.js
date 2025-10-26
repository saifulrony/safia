/**
 * ProBuilder Navigator Panel
 */

(function($) {
    'use strict';
    
    window.ProBuilderNavigator = {
        
        panel: null,
        tree: null,
        currentElements: [],
        selectedId: null,
        
        /**
         * Initialize
         */
        init: function() {
            this.createPanel();
            this.bindEvents();
            this.refresh();
        },
        
        /**
         * Create navigator panel
         */
        createPanel: function() {
            const html = `
                <div class="probuilder-navigator-panel" id="pb-navigator">
                    <div class="probuilder-navigator-header">
                        <h3><i class="fas fa-sitemap"></i> Navigator</h3>
                        <button class="probuilder-navigator-close" id="pb-nav-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="probuilder-navigator-body">
                        <ul class="probuilder-navigator-tree" id="pb-nav-tree"></ul>
                        <div class="probuilder-navigator-empty" id="pb-nav-empty">
                            <i class="fas fa-cube" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                            No elements yet<br>
                            <small>Start building your page</small>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(html);
            this.panel = $('#pb-navigator');
            this.tree = $('#pb-nav-tree');
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            const self = this;
            
            // Toggle navigator
            $(document).on('click', '#pb-toggle-navigator', function() {
                self.toggle();
            });
            
            // Close navigator
            $(document).on('click', '#pb-nav-close', function() {
                self.hide();
            });
            
            // Select element
            $(document).on('click', '.probuilder-navigator-item', function(e) {
                e.stopPropagation();
                const id = $(this).data('element-id');
                self.selectElement(id);
            });
            
            // Toggle children
            $(document).on('click', '.probuilder-navigator-toggle', function(e) {
                e.stopPropagation();
                $(this).toggleClass('collapsed expanded');
                $(this).closest('.probuilder-navigator-item').next('.probuilder-navigator-children').toggle();
            });
            
            // Hide element
            $(document).on('click', '.pb-nav-hide', function(e) {
                e.stopPropagation();
                const id = $(this).closest('.probuilder-navigator-item').data('element-id');
                self.toggleVisibility(id);
            });
            
            // Lock element
            $(document).on('click', '.pb-nav-lock', function(e) {
                e.stopPropagation();
                const id = $(this).closest('.probuilder-navigator-item').data('element-id');
                self.toggleLock(id);
            });
            
            // Duplicate element
            $(document).on('click', '.pb-nav-duplicate', function(e) {
                e.stopPropagation();
                const id = $(this).closest('.probuilder-navigator-item').data('element-id');
                self.duplicateElement(id);
            });
            
            // Delete element
            $(document).on('click', '.pb-nav-delete', function(e) {
                e.stopPropagation();
                const id = $(this).closest('.probuilder-navigator-item').data('element-id');
                self.deleteElement(id);
            });
            
            // Make items draggable
            this.initDragDrop();
        },
        
        /**
         * Initialize drag and drop
         */
        initDragDrop: function() {
            const self = this;
            
            $(document).on('mousedown', '.probuilder-navigator-item', function(e) {
                if ($(e.target).closest('.probuilder-navigator-item-actions').length) {
                    return;
                }
                
                $(this).addClass('dragging');
                const id = $(this).data('element-id');
                
                e.dataTransfer = e.originalEvent.dataTransfer;
                if (e.dataTransfer) {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', id);
                }
            });
            
            $(document).on('mouseup', '.probuilder-navigator-item', function() {
                $('.probuilder-navigator-item').removeClass('dragging drop-target');
            });
        },
        
        /**
         * Refresh navigator tree
         */
        refresh: function() {
            if (!window.ProBuilderApp) {
                return;
            }
            
            this.currentElements = window.ProBuilderApp.elements || [];
            this.render();
        },
        
        /**
         * Render tree
         */
        render: function() {
            if (this.currentElements.length === 0) {
                this.tree.hide();
                $('#pb-nav-empty').show();
                return;
            }
            
            this.tree.show();
            $('#pb-nav-empty').hide();
            
            this.tree.html(this.renderItems(this.currentElements));
        },
        
        /**
         * Render items recursively
         */
        renderItems: function(items, level = 0) {
            let html = '';
            
            items.forEach(item => {
                const hasChildren = item.children && item.children.length > 0;
                const icon = this.getIcon(item.type);
                const label = this.getLabel(item);
                
                html += `
                    <li>
                        <div class="probuilder-navigator-item ${item.id === this.selectedId ? 'selected' : ''}" 
                             data-element-id="${item.id}"
                             style="padding-left: ${level * 20 + 12}px;">
                            ${hasChildren ? '<button class="probuilder-navigator-toggle expanded"></button>' : '<span style="width:16px;display:inline-block;"></span>'}
                            <div class="probuilder-navigator-item-content">
                                <i class="fas ${icon} probuilder-navigator-item-icon"></i>
                                <span class="probuilder-navigator-item-label">${label}</span>
                            </div>
                            <div class="probuilder-navigator-item-actions">
                                <button class="probuilder-navigator-action pb-nav-hide" title="Hide">
                                    <i class="fas ${item.hidden ? 'fa-eye-slash' : 'fa-eye'}"></i>
                                </button>
                                <button class="probuilder-navigator-action pb-nav-lock" title="Lock">
                                    <i class="fas ${item.locked ? 'fa-lock' : 'fa-unlock'}"></i>
                                </button>
                                <button class="probuilder-navigator-action pb-nav-duplicate" title="Duplicate">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button class="probuilder-navigator-action pb-nav-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        ${hasChildren ? `<ul class="probuilder-navigator-children">${this.renderItems(item.children, level + 1)}</ul>` : ''}
                    </li>
                `;
            });
            
            return html;
        },
        
        /**
         * Get icon for element type
         */
        getIcon: function(type) {
            const icons = {
                container: 'fa-layer-group',
                section: 'fa-square',
                column: 'fa-columns',
                heading: 'fa-heading',
                text: 'fa-paragraph',
                button: 'fa-mouse-pointer',
                image: 'fa-image',
                video: 'fa-video',
                'icon-box': 'fa-star',
                testimonial: 'fa-quote-right',
                slider: 'fa-sliders-h',
                gallery: 'fa-images',
                form: 'fa-wpforms'
            };
            
            return icons[type] || 'fa-cube';
        },
        
        /**
         * Get label for element
         */
        getLabel: function(item) {
            if (item.settings && item.settings.title) {
                return item.settings.title;
            }
            
            return item.type.charAt(0).toUpperCase() + item.type.slice(1).replace(/-/g, ' ');
        },
        
        /**
         * Select element
         */
        selectElement: function(id) {
            this.selectedId = id;
            
            // Update UI
            $('.probuilder-navigator-item').removeClass('selected');
            $(`.probuilder-navigator-item[data-element-id="${id}"]`).addClass('selected');
            
            // Select in main editor
            if (window.ProBuilderApp) {
                window.ProBuilderApp.selectElement(id);
            }
        },
        
        /**
         * Toggle visibility
         */
        toggleVisibility: function(id) {
            if (window.ProBuilderApp) {
                window.ProBuilderApp.toggleElementVisibility(id);
                this.refresh();
            }
        },
        
        /**
         * Toggle lock
         */
        toggleLock: function(id) {
            if (window.ProBuilderApp) {
                window.ProBuilderApp.toggleElementLock(id);
                this.refresh();
            }
        },
        
        /**
         * Duplicate element
         */
        duplicateElement: function(id) {
            if (window.ProBuilderApp) {
                window.ProBuilderApp.duplicateElement(id);
                this.refresh();
            }
        },
        
        /**
         * Delete element
         */
        deleteElement: function(id) {
            if (confirm('Delete this element?')) {
                if (window.ProBuilderApp) {
                    window.ProBuilderApp.deleteElement(id);
                    this.refresh();
                }
            }
        },
        
        /**
         * Show navigator
         */
        show: function() {
            this.panel.addClass('visible');
            this.refresh();
        },
        
        /**
         * Hide navigator
         */
        hide: function() {
            this.panel.removeClass('visible');
        },
        
        /**
         * Toggle navigator
         */
        toggle: function() {
            if (this.panel.hasClass('visible')) {
                this.hide();
            } else {
                this.show();
            }
        }
    };
    
    // Initialize when ProBuilder is ready
    $(document).ready(function() {
        if (typeof ProBuilderEditor !== 'undefined') {
            ProBuilderNavigator.init();
        }
    });
    
})(jQuery);

