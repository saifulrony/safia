/**
 * ProBuilder History Panel
 */

(function($) {
    'use strict';
    
    window.ProBuilderHistory = {
        
        panel: null,
        timeline: null,
        history: [],
        currentIndex: -1,
        maxSize: 50,
        
        /**
         * Initialize
         */
        init: function() {
            this.createPanel();
            this.bindEvents();
            this.loadHistory();
        },
        
        /**
         * Create history panel
         */
        createPanel: function() {
            const html = `
                <div class="probuilder-history-panel" id="pb-history">
                    <div class="probuilder-history-header">
                        <h3><i class="fas fa-history"></i> History</h3>
                        <div class="probuilder-history-actions">
                            <button class="probuilder-history-action-btn" id="pb-hist-undo" title="Undo (Ctrl+Z)" disabled>
                                <i class="fas fa-undo"></i>
                            </button>
                            <button class="probuilder-history-action-btn" id="pb-hist-redo" title="Redo (Ctrl+Y)" disabled>
                                <i class="fas fa-redo"></i>
                            </button>
                            <button class="probuilder-history-action-btn" id="pb-hist-close" title="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="probuilder-history-body">
                        <ul class="probuilder-history-timeline" id="pb-hist-timeline"></ul>
                        <div class="probuilder-history-empty" id="pb-hist-empty">
                            <i class="fas fa-history" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                            No history yet<br>
                            <small>Your changes will appear here</small>
                        </div>
                    </div>
                    <div class="probuilder-history-footer">
                        <button class="probuilder-history-clear" id="pb-hist-clear">Clear History</button>
                        <div class="probuilder-history-shortcuts">
                            <kbd>Ctrl</kbd>+<kbd>Z</kbd> Undo &nbsp; 
                            <kbd>Ctrl</kbd>+<kbd>Y</kbd> Redo
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(html);
            this.panel = $('#pb-history');
            this.timeline = $('#pb-hist-timeline');
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            const self = this;
            
            // Toggle history
            $(document).on('click', '#pb-toggle-history', function() {
                self.toggle();
            });
            
            // Close history
            $(document).on('click', '#pb-hist-close', function() {
                self.hide();
            });
            
            // Undo
            $(document).on('click', '#pb-hist-undo', function() {
                self.undo();
            });
            
            // Redo
            $(document).on('click', '#pb-hist-redo', function() {
                self.redo();
            });
            
            // Clear history
            $(document).on('click', '#pb-hist-clear', function() {
                if (confirm('Clear all history?')) {
                    self.clear();
                }
            });
            
            // Jump to state
            $(document).on('click', '.probuilder-history-item', function() {
                const index = $(this).data('index');
                self.jumpToState(index);
            });
        },
        
        /**
         * Load history from server
         */
        loadHistory: function() {
            const self = this;
            
            if (!ProBuilderEditor || !ProBuilderEditor.post_id) {
                return;
            }
            
            $.post(ProBuilderEditor.ajaxurl, {
                action: 'probuilder_get_history',
                nonce: ProBuilderEditor.nonce,
                post_id: ProBuilderEditor.post_id
            }, function(response) {
                if (response.success && response.data) {
                    self.history = response.data.states || [];
                    self.currentIndex = response.data.current_index || -1;
                    self.render();
                    self.updateButtons();
                }
            });
        },
        
        /**
         * Add state to history
         */
        addState: function(state, label) {
            // Remove states after current index
            if (this.currentIndex < this.history.length - 1) {
                this.history = this.history.slice(0, this.currentIndex + 1);
            }
            
            // Add new state
            this.history.push({
                data: state,
                action: label,
                timestamp: Date.now(),
                preview: this.generatePreview(state)
            });
            
            this.currentIndex++;
            
            // Limit history size
            if (this.history.length > this.maxSize) {
                this.history.shift();
                this.currentIndex--;
            }
            
            this.render();
            this.updateButtons();
            this.saveToServer();
        },
        
        /**
         * Generate preview text
         */
        generatePreview: function(state) {
            if (!state || !Array.isArray(state)) {
                return '';
            }
            
            const types = state.map(el => el.type);
            return types.slice(0, 3).join(', ') + (types.length > 3 ? '...' : '');
        },
        
        /**
         * Undo
         */
        undo: function() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.restoreState(this.currentIndex);
            }
        },
        
        /**
         * Redo
         */
        redo: function() {
            if (this.currentIndex < this.history.length - 1) {
                this.currentIndex++;
                this.restoreState(this.currentIndex);
            }
        },
        
        /**
         * Jump to specific state
         */
        jumpToState: function(index) {
            if (index >= 0 && index < this.history.length) {
                this.currentIndex = index;
                this.restoreState(index);
            }
        },
        
        /**
         * Restore state
         */
        restoreState: function(index) {
            const state = this.history[index];
            
            if (!state || !window.ProBuilderApp) {
                return;
            }
            
            // Restore state in main editor
            window.ProBuilderApp.elements = state.data;
            window.ProBuilderApp.renderCanvas();
            
            this.render();
            this.updateButtons();
            
            // Refresh navigator if available
            if (window.ProBuilderNavigator) {
                window.ProBuilderNavigator.refresh();
            }
        },
        
        /**
         * Clear history
         */
        clear: function() {
            this.history = [];
            this.currentIndex = -1;
            this.render();
            this.updateButtons();
            
            // Clear on server
            if (ProBuilderEditor && ProBuilderEditor.post_id) {
                $.post(ProBuilderEditor.ajaxurl, {
                    action: 'probuilder_clear_history',
                    nonce: ProBuilderEditor.nonce,
                    post_id: ProBuilderEditor.post_id
                });
            }
        },
        
        /**
         * Render timeline
         */
        render: function() {
            if (this.history.length === 0) {
                this.timeline.hide();
                $('#pb-hist-empty').show();
                return;
            }
            
            this.timeline.show();
            $('#pb-hist-empty').hide();
            
            let html = '';
            
            this.history.forEach((state, index) => {
                const isCurrent = index === this.currentIndex;
                const time = this.formatTime(state.timestamp);
                const icon = this.getActionIcon(state.action);
                
                html += `
                    <li class="probuilder-history-item ${isCurrent ? 'current' : ''}" data-index="${index}">
                        <div class="probuilder-history-item-marker"></div>
                        <div class="probuilder-history-item-content">
                            <div class="probuilder-history-item-action">
                                <i class="fas ${icon} probuilder-history-item-icon"></i>
                                <span>${state.action || 'Change'}</span>
                            </div>
                            <div class="probuilder-history-item-time">${time}</div>
                            ${state.preview ? `<div class="probuilder-history-item-preview">${state.preview}</div>` : ''}
                        </div>
                    </li>
                `;
            });
            
            this.timeline.html(html);
            
            // Scroll to current
            setTimeout(() => {
                const current = this.timeline.find('.current');
                if (current.length) {
                    current[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 100);
        },
        
        /**
         * Get icon for action
         */
        getActionIcon: function(action) {
            const icons = {
                'Add': 'fa-plus',
                'Edit': 'fa-edit',
                'Delete': 'fa-trash',
                'Move': 'fa-arrows-alt',
                'Duplicate': 'fa-copy',
                'Style': 'fa-paint-brush'
            };
            
            for (const key in icons) {
                if (action.includes(key)) {
                    return icons[key];
                }
            }
            
            return 'fa-edit';
        },
        
        /**
         * Format timestamp
         */
        formatTime: function(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) {
                return 'Just now';
            } else if (diff < 3600000) {
                return Math.floor(diff / 60000) + ' min ago';
            } else if (diff < 86400000) {
                return Math.floor(diff / 3600000) + ' hours ago';
            } else {
                return date.toLocaleDateString();
            }
        },
        
        /**
         * Update undo/redo buttons
         */
        updateButtons: function() {
            $('#pb-hist-undo').prop('disabled', this.currentIndex <= 0);
            $('#pb-hist-redo').prop('disabled', this.currentIndex >= this.history.length - 1);
        },
        
        /**
         * Save to server
         */
        saveToServer: function() {
            if (!ProBuilderEditor || !ProBuilderEditor.post_id) {
                return;
            }
            
            const currentState = this.history[this.currentIndex];
            
            $.post(ProBuilderEditor.ajaxurl, {
                action: 'probuilder_save_history_state',
                nonce: ProBuilderEditor.nonce,
                post_id: ProBuilderEditor.post_id,
                state: JSON.stringify(currentState.data),
                action_label: currentState.action
            });
        },
        
        /**
         * Show history panel
         */
        show: function() {
            this.panel.addClass('visible');
            this.render();
        },
        
        /**
         * Hide history panel
         */
        hide: function() {
            this.panel.removeClass('visible');
        },
        
        /**
         * Toggle history panel
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
            ProBuilderHistory.init();
        }
    });
    
})(jQuery);

