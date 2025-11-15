/**
 * ProBuilder Editor JavaScript
 */

import { widgetRenderers } from './widgets/index.js';

(function($) {
    'use strict';
    
    const ProBuilder = {
        
        widgets: [],
        elements: [],
        selectedElement: null,
        history: [],
        historyIndex: -1,
        maxHistorySize: 50,
        isPerformingHistoryAction: false,
        isGridCellResizing: false,
        isNestedDropInProgress: false,
        globalStyles: {
            colors: [
                { id: 'primary', name: 'Primary', color: '#344047' },
                { id: 'secondary', name: 'Secondary', color: '#2c3e50' },
                { id: 'accent', name: 'Accent', color: '#4a5a6b' },
                { id: 'text', name: 'Text', color: '#495157' }
            ],
            typography: {
                h1: { fontSize: 48, fontWeight: 700, lineHeight: 1.2 },
                h2: { fontSize: 36, fontWeight: 600, lineHeight: 1.3 },
                h3: { fontSize: 28, fontWeight: 600, lineHeight: 1.4 },
                body: { fontSize: 16, fontWeight: 400, lineHeight: 1.6 }
            },
            buttons: {
                primary: { bgColor: '#344047', textColor: '#ffffff', borderRadius: 4 },
                secondary: { bgColor: '#2c3e50', textColor: '#ffffff', borderRadius: 4 },
                outline: { bgColor: 'transparent', textColor: '#344047', borderRadius: 4, border: '2px solid #344047' }
            }
        },
    /**
     * Ensure color inputs receive valid hex values
     */
    sanitizeColorValue: function(value, fallback = '#000000') {
        if (typeof value !== 'string') {
            return fallback;
        }
        const trimmed = value.trim();
        if (trimmed.toLowerCase() === 'transparent') {
            return fallback;
        }
        const shortHexMatch = trimmed.match(/^#([0-9a-fA-F]{3})$/);
        if (shortHexMatch) {
            const [r, g, b] = shortHexMatch[1].split('');
            return `#${r}${r}${g}${g}${b}${b}`.toLowerCase();
        }
        if (/^#([0-9a-fA-F]{6})$/.test(trimmed)) {
            return trimmed.toLowerCase();
        }
        return fallback;
    },
        
        /**
         * Initialize
         */
        init: function() {
            console.log('ProBuilder initializing...');
            console.log('ProBuilderEditor object:', typeof ProBuilderEditor !== 'undefined' ? 'EXISTS' : 'MISSING');
            
            if (typeof ProBuilderEditor === 'undefined') {
                console.error('ProBuilderEditor is not defined! Check if scripts are loading.');
                alert('ProBuilder Error: Editor scripts not loaded. Please refresh the page.');
                return;
            }
            
            this.widgets = ProBuilderEditor.widgets || [];
            console.log('Widgets loaded:', this.widgets.length);
            
            if (this.widgets.length === 0) {
                console.error('No widgets found! Check widget registration.');
            }
            
            // Debug: Log heading widget's controls and tabs
            if (this.widgets.length > 0) {
                const headingWidget = this.widgets.find(w => w.name === 'heading');
                if (headingWidget) {
                    console.log('==================== HEADING WIDGET DEBUG ====================');
                    console.log('Total controls:', Object.keys(headingWidget.controls).length);
                    console.log('Controls object:', headingWidget.controls);
                    
                    let contentCount = 0, styleCount = 0, advancedCount = 0;
                    
                    Object.keys(headingWidget.controls).forEach(key => {
                        const control = headingWidget.controls[key];
                        const tab = control.tab || 'NO_TAB';
                        const type = control.type || 'NO_TYPE';
                        
                        console.log(`  ${key}: tab="${tab}" type="${type}"`);
                        
                        if (tab === 'content') contentCount++;
                        if (tab === 'style') styleCount++;
                        if (tab === 'advanced') advancedCount++;
                    });
                    
                    console.log('Tab distribution:');
                    console.log(`  - Content: ${contentCount} controls`);
                    console.log(`  - Style: ${styleCount} controls`);
                    console.log(`  - Advanced: ${advancedCount} controls`);
                    console.log('==============================================================');
                }
            }
            
            this.loadSavedData();
            
            // CRITICAL: Ensure elements is always an array after loading
            if (!Array.isArray(this.elements)) {
                console.error('❌ CRITICAL: this.elements is not an array after loadSavedData!');
                console.error('Type:', typeof this.elements);
                console.error('Value:', this.elements);
                this.elements = [];
                console.log('✅ Reset to empty array');
            }
            
            this.renderWidgets();
            
            // Small delay to ensure widgets are in DOM before making them draggable
            setTimeout(() => {
                this.initDragDrop();
                console.log('Drag and drop initialized');
            }, 100);
            
            this.bindEvents();
            this.initResizablePanels();
            this.initSidebarToggles();
            this.updateEmptyState();
            this.updatePanelResponsiveness();
            this.loadTemplates();
            this.showSettingsPlaceholder(); // Show initial placeholder
            
            console.log('ProBuilder initialized successfully!');
            console.log('- Widgets:', this.widgets.length);
            console.log('- Elements:', this.elements.length);
            
            // Make globally accessible for templates
            window.ProBuilderApp = this;
            
            // Initialize keyboard shortcuts
            this.initKeyboardShortcuts();
            
            // Initialize global styles
            this.initGlobalStyles();
            
            // Save initial state
            this.saveHistory();
        },
        
        /**
         * Initialize keyboard shortcuts
         */
        initKeyboardShortcuts: function() {
            const self = this;
            
            $(document).on('keydown', function(e) {
                // Ctrl/Cmd + Z = Undo
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    self.undo();
                    return false;
                }
                
                // Ctrl/Cmd + Shift + Z or Ctrl/Cmd + Y = Redo
                if ((e.ctrlKey || e.metaKey) && (e.shiftKey && e.key === 'z' || e.key === 'y')) {
                    e.preventDefault();
                    self.redo();
                    return false;
                }
                
                // Ctrl/Cmd + S = Save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    self.saveData();
                    return false;
                }
                
                // Ctrl/Cmd + C = Copy
                if ((e.ctrlKey || e.metaKey) && e.key === 'c' && self.selectedElement) {
                    e.preventDefault();
                    self.copyElement();
                    return false;
                }
                
                // Ctrl/Cmd + V = Paste
                if ((e.ctrlKey || e.metaKey) && e.key === 'v' && self.copiedElement) {
                    e.preventDefault();
                    self.pasteElement();
                    return false;
                }
                
                // Ctrl/Cmd + D = Duplicate
                if ((e.ctrlKey || e.metaKey) && e.key === 'd' && self.selectedElement) {
                    e.preventDefault();
                    self.duplicateElement();
                    return false;
                }
                
                // Delete = Delete selected element
                if (e.key === 'Delete' && self.selectedElement) {
                    e.preventDefault();
                    self.deleteSelectedElement();
                    return false;
                }
            });
            
            console.log('✅ Keyboard shortcuts initialized');
        },
        
        /**
         * Initialize global styles
         */
        initGlobalStyles: function() {
            const self = this;
            
            // Load saved global styles
            const savedStyles = localStorage.getItem('probuilder_global_styles');
            if (savedStyles) {
                try {
                    this.globalStyles = JSON.parse(savedStyles);
                } catch (e) {
                    console.error('Error loading global styles:', e);
                }
            }
            
            // Render global styles
            this.renderGlobalColors();
            this.renderGlobalTypography();
            this.renderGlobalButtons();
            
            // Bind events
            $('#add-global-color').on('click', function() {
                self.addGlobalColor();
            });
            
            // Layout settings
            $('#probuilder-layout-width').on('change', function() {
                const value = $(this).val();
                if (value === 'boxed') {
                    $('#probuilder-boxed-width-control').show();
                } else {
                    $('#probuilder-boxed-width-control').hide();
                }
            });
            
            $('#apply-layout-settings').on('click', function() {
                self.applyLayoutSettings();
            });
            
            // Load saved layout settings
            self.loadLayoutSettings();
            
            console.log('✅ Global styles initialized');
        },
        
        /**
         * Render global colors
         */
        renderGlobalColors: function() {
            const $container = $('#probuilder-color-palette');
            $container.empty();
            
            this.globalStyles.colors.forEach((color, index) => {
                const $colorItem = $(`
                    <div class="probuilder-global-color-item" data-index="${index}">
                        <div class="probuilder-color-preview" style="background: ${color.color};">
                            <input type="color" class="probuilder-color-picker-input" value="${color.color}" data-index="${index}">
                        </div>
                        <div class="probuilder-color-info">
                            <input type="text" class="probuilder-color-name" value="${color.name}" data-index="${index}" placeholder="Color name">
                            <div class="probuilder-color-value">${color.color}</div>
                        </div>
                        <button class="probuilder-color-delete" data-index="${index}" title="Delete">
                            <i class="dashicons dashicons-trash"></i>
                        </button>
                    </div>
                `);
                
                $container.append($colorItem);
            });
            
            // Bind color picker change
            const self = this;
            $container.find('.probuilder-color-picker-input').on('change', function() {
                const index = $(this).data('index');
                const newColor = $(this).val();
                self.updateGlobalColor(index, 'color', newColor);
            });
            
            // Bind name change
            $container.find('.probuilder-color-name').on('change', function() {
                const index = $(this).data('index');
                const newName = $(this).val();
                self.updateGlobalColor(index, 'name', newName);
            });
            
            // Bind delete
            $container.find('.probuilder-color-delete').on('click', function() {
                const index = $(this).data('index');
                self.deleteGlobalColor(index);
            });
        },
        
        /**
         * Render global typography
         */
        renderGlobalTypography: function() {
            const $container = $('#probuilder-typography-presets');
            $container.empty();
            
            const types = ['h1', 'h2', 'h3', 'body'];
            const labels = { h1: 'Heading 1', h2: 'Heading 2', h3: 'Heading 3', body: 'Body Text' };
            
            types.forEach(type => {
                const typo = this.globalStyles.typography[type];
                const $typoItem = $(`
                    <div class="probuilder-typo-preset" data-type="${type}">
                        <div class="probuilder-typo-preview">
                            <span style="font-size: ${typo.fontSize}px; font-weight: ${typo.fontWeight}; line-height: ${typo.lineHeight};">
                                ${labels[type]}
                            </span>
                        </div>
                        <div class="probuilder-typo-controls">
                            <label>
                                <span>Size:</span>
                                <input type="number" class="probuilder-typo-fontsize" value="${typo.fontSize}" data-type="${type}" min="10" max="100">
                            </label>
                            <label>
                                <span>Weight:</span>
                                <select class="probuilder-typo-weight" data-type="${type}">
                                    <option value="300" ${typo.fontWeight == 300 ? 'selected' : ''}>Light</option>
                                    <option value="400" ${typo.fontWeight == 400 ? 'selected' : ''}>Normal</option>
                                    <option value="600" ${typo.fontWeight == 600 ? 'selected' : ''}>Semi Bold</option>
                                    <option value="700" ${typo.fontWeight == 700 ? 'selected' : ''}>Bold</option>
                                </select>
                            </label>
                            <label>
                                <span>Line Height:</span>
                                <input type="number" class="probuilder-typo-lineheight" value="${typo.lineHeight}" data-type="${type}" min="0.5" max="3" step="0.1">
                            </label>
                        </div>
                    </div>
                `);
                
                $container.append($typoItem);
            });
            
            // Bind events
            const self = this;
            $container.find('.probuilder-typo-fontsize, .probuilder-typo-weight, .probuilder-typo-lineheight').on('change', function() {
                const type = $(this).data('type');
                self.updateTypography(type);
            });
        },
        
        /**
         * Render global buttons
         */
        renderGlobalButtons: function() {
            const $container = $('#probuilder-button-presets');
            $container.empty();
            
            const buttonTypes = ['primary', 'secondary', 'outline'];
            const labels = { primary: 'Primary', secondary: 'Secondary', outline: 'Outline' };
            
            Object.keys(this.globalStyles.buttons).forEach(type => {
                const btn = this.globalStyles.buttons[type];
                const bgColorInputValue = this.sanitizeColorValue(btn.bgColor, '#000000');
                const textColorInputValue = this.sanitizeColorValue(btn.textColor, '#000000');
                const $btnItem = $(`
                    <div class="probuilder-button-preset" data-type="${type}">
                        <div class="probuilder-button-preview">
                            <button style="
                                background: ${btn.bgColor};
                                color: ${btn.textColor};
                                border-radius: ${btn.borderRadius}px;
                                border: ${btn.border || 'none'};
                                padding: 12px 24px;
                                cursor: default;
                            ">
                                ${labels[type]} Button
                            </button>
                        </div>
                        <div class="probuilder-button-controls">
                            <label>
                                <span>Background:</span>
                                <input type="color" class="probuilder-btn-bgcolor" value="${bgColorInputValue}" data-type="${type}">
                            </label>
                            <label>
                                <span>Text:</span>
                                <input type="color" class="probuilder-btn-textcolor" value="${textColorInputValue}" data-type="${type}">
                            </label>
                            <label>
                                <span>Border Radius:</span>
                                <input type="number" class="probuilder-btn-radius" value="${btn.borderRadius}" data-type="${type}" min="0" max="50">
                            </label>
                        </div>
                    </div>
                `);
                
                $container.append($btnItem);
            });
            
            // Bind events
            const self = this;
            $container.find('.probuilder-btn-bgcolor, .probuilder-btn-textcolor, .probuilder-btn-radius').on('change', function() {
                const type = $(this).data('type');
                self.updateButtonStyle(type);
            });
        },
        
        /**
         * Add global color
         */
        addGlobalColor: function() {
            const newColor = {
                id: 'color-' + Date.now(),
                name: 'New Color',
                color: '#' + Math.floor(Math.random()*16777215).toString(16)
            };
            
            this.globalStyles.colors.push(newColor);
            this.renderGlobalColors();
            this.saveGlobalStyles();
            this.showToast('Color added!');
        },
        
        /**
         * Update global color
         */
        updateGlobalColor: function(index, property, value) {
            if (this.globalStyles.colors[index]) {
                this.globalStyles.colors[index][property] = value;
                
                // Update preview
                if (property === 'color') {
                    $(`.probuilder-color-preview`).eq(index).css('background', value);
                    $(`.probuilder-color-value`).eq(index).text(value);
                }
                
                this.saveGlobalStyles();
                
                // Apply to canvas elements
                this.applyGlobalStyles();
            }
        },
        
        /**
         * Delete global color
         */
        deleteGlobalColor: function(index) {
            if (confirm('Delete this color?')) {
                this.globalStyles.colors.splice(index, 1);
                this.renderGlobalColors();
                this.saveGlobalStyles();
                this.showToast('Color deleted!');
            }
        },
        
        /**
         * Update typography
         */
        updateTypography: function(type) {
            const $container = $(`.probuilder-typo-preset[data-type="${type}"]`);
            const fontSize = $container.find('.probuilder-typo-fontsize').val();
            const fontWeight = $container.find('.probuilder-typo-weight').val();
            const lineHeight = $container.find('.probuilder-typo-lineheight').val();
            
            this.globalStyles.typography[type] = {
                fontSize: parseInt(fontSize),
                fontWeight: parseInt(fontWeight),
                lineHeight: parseFloat(lineHeight)
            };
            
            // Update preview
            $container.find('.probuilder-typo-preview span').css({
                'font-size': fontSize + 'px',
                'font-weight': fontWeight,
                'line-height': lineHeight
            });
            
            this.saveGlobalStyles();
            
            // Apply to canvas elements
            this.applyGlobalStyles();
        },
        
        /**
         * Update button style
         */
        updateButtonStyle: function(type) {
            const $container = $(`.probuilder-button-preset[data-type="${type}"]`);
            const bgColor = $container.find('.probuilder-btn-bgcolor').val();
            const textColor = $container.find('.probuilder-btn-textcolor').val();
            const borderRadius = $container.find('.probuilder-btn-radius').val();
            
            this.globalStyles.buttons[type].bgColor = bgColor;
            this.globalStyles.buttons[type].textColor = textColor;
            this.globalStyles.buttons[type].borderRadius = parseInt(borderRadius);
            
            // Update preview
            $container.find('.probuilder-button-preview button').css({
                'background': bgColor,
                'color': textColor,
                'border-radius': borderRadius + 'px'
            });
            
            this.saveGlobalStyles();
            
            // Apply to canvas elements
            this.applyGlobalStyles();
        },
        
        /**
         * Save global styles
         */
        saveGlobalStyles: function() {
            localStorage.setItem('probuilder_global_styles', JSON.stringify(this.globalStyles));
            console.log('✅ Global styles saved');
        },
        
        /**
         * Load layout settings from server
         */
        loadLayoutSettings: function() {
            const self = this;
            $.ajax({
                url: ProBuilderEditor.ajax_url,
                type: 'POST',
                data: {
                    action: 'probuilder_get_global_styles',
                    nonce: ProBuilderEditor.nonce
                },
                success: function(response) {
                    // Use defaults if no settings exist
                    const layout = (response.success && response.data && response.data.layout) 
                        ? response.data.layout 
                        : {content_width: 'boxed', boxed_width: '1400px', boxed_padding: '15px'};
                    
                    const contentWidth = layout.content_width || 'boxed';
                    const boxedWidth = layout.boxed_width || '1400px';
                    const boxedPadding = layout.boxed_padding || '15px';
                    
                    $('#probuilder-layout-width').val(contentWidth);
                    $('#probuilder-boxed-width').val(boxedWidth);
                    $('#probuilder-layout-padding').val(boxedPadding);
                    
                    // Show/hide boxed width control
                    if (contentWidth === 'full') {
                        $('#probuilder-boxed-width-control').hide();
                    } else {
                        $('#probuilder-boxed-width-control').show();
                    }
                    
                    // Apply to preview area immediately
                    const $previewArea = $('#probuilder-preview-area');
                    if (contentWidth === 'boxed') {
                        $previewArea.css({
                            'max-width': boxedWidth,
                            'margin': '0 auto',
                            'padding': '0 ' + boxedPadding,
                            'box-sizing': 'border-box'
                        });
                    } else {
                        $previewArea.css({
                            'max-width': '100%',
                            'margin': '0',
                            'padding': '0'
                        });
                    }
                    
                    console.log('✅ Layout settings loaded and applied:', layout);
                },
                error: function() {
                    console.error('Failed to load layout settings');
                }
            });
        },
        
        /**
         * Apply layout settings
         */
        applyLayoutSettings: function() {
            const self = this;
            const contentWidth = $('#probuilder-layout-width').val();
            const boxedWidth = $('#probuilder-boxed-width').val();
            const boxedPadding = $('#probuilder-layout-padding').val();
            
            // Save via AJAX
            $.ajax({
                url: ProBuilderEditor.ajax_url,
                type: 'POST',
                data: {
                    action: 'probuilder_save_global_styles',
                    nonce: ProBuilderEditor.nonce,
                    styles: JSON.stringify({
                        layout: {
                            content_width: contentWidth,
                            boxed_width: boxedWidth,
                            boxed_padding: boxedPadding
                        }
                    })
                },
                success: function(response) {
                    console.log('Layout save response:', response);
                    if (response.success) {
                        // Apply instantly to preview area
                        const $previewArea = $('#probuilder-preview-area');
                        if (contentWidth === 'boxed') {
                            $previewArea.css({
                                'max-width': boxedWidth,
                                'margin': '0 auto',
                                'padding': '0 ' + boxedPadding,
                                'box-sizing': 'border-box'
                            });
                        } else {
                            $previewArea.css({
                                'max-width': '100%',
                                'margin': '0',
                                'padding': '0'
                            });
                        }
                        
                        self.showNotification('Layout settings applied!', 'success');
                        console.log('✅ Layout settings saved and applied to preview');
                    } else {
                        const errorMsg = response.data && response.data.message ? response.data.message : 'Unknown error';
                        console.error('❌ Layout save failed:', errorMsg, response);
                        self.showNotification('Failed to save: ' + errorMsg, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX error:', {xhr: xhr, status: status, error: error});
                    console.error('Response text:', xhr.responseText);
                    self.showNotification('AJAX Error: ' + (error || status), 'error');
                }
            });
        },
        
        /**
         * Get global color by ID
         */
        getGlobalColor: function(colorId) {
            const color = this.globalStyles.colors.find(c => c.id === colorId);
            return color ? color.color : null;
        },
        
        /**
         * Apply global styles to all elements
         */
        applyGlobalStyles: function() {
            console.log('🎨 Applying global styles to all elements...');
            
            // Re-render all elements to apply new global styles
            this.elements.forEach(element => {
                this.updateElementPreview(element);
            });
            
            console.log('✅ Global styles applied to', this.elements.length, 'elements');
        },
        
        /**
         * Save current state to history
         */
        saveHistory: function() {
            if (this.isPerformingHistoryAction) {
                return; // Don't save during undo/redo
            }
            
            // Clone current elements state
            const state = JSON.parse(JSON.stringify(this.elements));
            
            // Remove future history if we're not at the end
            if (this.historyIndex < this.history.length - 1) {
                this.history = this.history.slice(0, this.historyIndex + 1);
            }
            
            // Add new state
            this.history.push(state);
            
            // Limit history size
            if (this.history.length > this.maxHistorySize) {
                this.history.shift();
            } else {
                this.historyIndex++;
            }
            
            this.updateHistoryButtons();
            console.log('📝 History saved. Index:', this.historyIndex, 'Total:', this.history.length);
        },
        
        /**
         * Undo
         */
        undo: function() {
            if (this.historyIndex <= 0) {
                console.log('⚠️ Nothing to undo');
                return;
            }
            
            this.historyIndex--;
            this.restoreHistory();
            console.log('↩️ Undo. Index:', this.historyIndex);
        },
        
        /**
         * Redo
         */
        redo: function() {
            if (this.historyIndex >= this.history.length - 1) {
                console.log('⚠️ Nothing to redo');
                return;
            }
            
            this.historyIndex++;
            this.restoreHistory();
            console.log('↪️ Redo. Index:', this.historyIndex);
        },
        
        /**
         * Restore state from history
         */
        restoreHistory: function() {
            this.isPerformingHistoryAction = true;
            
            const state = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
            this.elements = state;
            
            console.log('🔄 Restoring history state with', this.elements.length, 'elements');
            
            // Re-render canvas
            $('#probuilder-preview-area').empty();
            this.elements.forEach(element => {
                this.renderElement(element);
            });
            
            this.updateEmptyState();
            
            // Reinitialize drag & drop system after history restore
            setTimeout(() => {
                this.makeContainersDroppable();
                this.reinitializeSidebarWidgets();
                this.reinitializePreviewArea();
                console.log('✅ History restored and drag & drop reinitialized');
            }, 100);
            
            this.updateHistoryButtons();
            
            this.isPerformingHistoryAction = false;
        },
        
        /**
         * Update history button states
         */
        updateHistoryButtons: function() {
            const canUndo = this.historyIndex > 0;
            const canRedo = this.historyIndex < this.history.length - 1;
            
            $('#probuilder-undo').prop('disabled', !canUndo).toggleClass('disabled', !canUndo);
            $('#probuilder-redo').prop('disabled', !canRedo).toggleClass('disabled', !canRedo);
        },
        
        /**
         * Copy element
         */
        copyElement: function() {
            if (!this.selectedElement) return;
            
            const element = this.elements.find(el => el.id === this.selectedElement);
            if (element) {
                this.copiedElement = JSON.parse(JSON.stringify(element));
                console.log('📋 Element copied:', element.widgetType);
                
                // Show toast notification
                this.showToast('Element copied! Press Ctrl+V to paste');
            }
        },
        
        /**
         * Paste element
         */
        pasteElement: function() {
            if (!this.copiedElement) return;
            
            // Ensure elements is always an array
            if (!Array.isArray(this.elements)) {
                console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                this.elements = [];
            }
            
            // Clone with new ID
            const newElement = this.cloneElementData(this.copiedElement);
            this.elements.push(newElement);
            this.renderElement(newElement);
            this.updateEmptyState();
            this.saveHistory();
            
            console.log('📌 Element pasted:', newElement.widgetType);
            this.showToast('Element pasted!');
        },
        
        /**
         * Duplicate element
         */
        duplicateElement: function() {
            if (!this.selectedElement) return;
            
            // Ensure elements is always an array
            if (!Array.isArray(this.elements)) {
                console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                this.elements = [];
                return;
            }
            
            const element = this.elements.find(el => el.id === this.selectedElement);
            if (element) {
                const newElement = this.cloneElementData(element);
                
                // Insert after original
                const index = this.elements.findIndex(el => el.id === this.selectedElement);
                this.elements.splice(index + 1, 0, newElement);
                
                this.renderElement(newElement);
                this.updateEmptyState();
                this.saveHistory();
                
                console.log('🔄 Element duplicated:', newElement.widgetType);
                this.showToast('Element duplicated!');
            }
        },
        
        /**
         * Delete selected element
         */
        deleteSelectedElement: function() {
            if (!this.selectedElement) return;
            
            this.deleteElement(this.selectedElement);
            this.showToast('Element deleted!');
        },
        
        /**
         * Show toast notification
         */
        showToast: function(message) {
            const toast = $(`
                <div class="probuilder-toast" style="
                    position: fixed;
                    bottom: 30px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #333;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 6px;
                    z-index: 99999;
                    font-size: 14px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    animation: slideUp 0.3s ease;
                ">
                    ${message}
                </div>
            `);
            
            $('body').append(toast);
            
            setTimeout(() => {
                toast.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 2000);
        },
        
        /**
         * Show notification with type (success/error/info)
         */
        showNotification: function(message, type = 'info') {
            const colors = {
                'success': '#10b981',
                'error': '#ef4444',
                'info': '#3b82f6',
                'warning': '#f59e0b'
            };
            
            const icons = {
                'success': '✓',
                'error': '✕',
                'info': 'ℹ',
                'warning': '⚠'
            };
            
            const bgColor = colors[type] || colors.info;
            const icon = icons[type] || icons.info;
            
            const notification = $(`
                <div class="probuilder-notification" style="
                    position: fixed;
                    bottom: 30px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: ${bgColor};
                    color: white;
                    padding: 14px 24px;
                    border-radius: 8px;
                    z-index: 99999;
                    font-size: 14px;
                    font-weight: 500;
                    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
                    animation: slideUp 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <span style="font-size: 18px; line-height: 1;">${icon}</span>
                    <span>${message}</span>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(() => {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        },
        
        /**
         * Import template data
         */
        importTemplate: function(templateData) {
            console.log('Importing template:', templateData);
            
            if (!templateData || !Array.isArray(templateData)) {
                console.error('Invalid template data');
                return;
            }
            
            // Ensure elements is always an array
            if (!Array.isArray(this.elements)) {
                console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                this.elements = [];
            }
            
            // Add template elements to current design
            templateData.forEach(elementData => {
                // Generate new IDs to avoid conflicts
                const newElement = this.cloneElementData(elementData);
                this.elements.push(newElement);
                this.renderElement(newElement);
            });
            
            this.updateEmptyState();
            this.makeContainersDroppable();
            
            console.log('✅ Template imported successfully! Elements added:', templateData.length);
        },
        
        /**
         * Clone element data with new IDs
         */
        cloneElementData: function(elementData) {
            const newElement = {
                id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                widgetType: elementData.widgetType,
                settings: JSON.parse(JSON.stringify(elementData.settings || {})),
                children: []
            };
            
            if (elementData.children && elementData.children.length > 0) {
                newElement.children = elementData.children.map(child => this.cloneElementData(child));
            }
            
            return newElement;
        },

        /**
         * Normalize nested structure to array (handles objects with numeric keys)
         */
        normalizeStructureToArray: function(value) {
            if (Array.isArray(value)) {
                return value.slice();
            }
            if (value && typeof value === 'object') {
                const arr = [];
                Object.keys(value).forEach(key => {
                    const index = parseInt(key, 10);
                    if (!Number.isNaN(index)) {
                        arr[index] = value[key];
                    }
                });
                return arr;
            }
            return [];
        },

        /**
         * Ensure grid layout elements have proper children/custom template populated
         */
        ensureGridElementStructure: function(element) {
            if (!element || element.widgetType !== 'grid-layout') {
                return;
            }

            if (!element.settings || typeof element.settings !== 'object') {
                element.settings = {};
            }

            // Determine pattern and base template
            const pattern = element.settings.grid_pattern || 'pattern-1';
            const baseTemplate = this.getGridTemplateData(pattern) || {};

            // Ensure custom template exists and has required fields
            if (!element.settings.custom_template || typeof element.settings.custom_template !== 'object') {
                element.settings.custom_template = {};
            }

            const customTemplate = element.settings.custom_template;
            if (!customTemplate.columns && baseTemplate.columns) {
                customTemplate.columns = baseTemplate.columns;
            }
            if (!customTemplate.rows && baseTemplate.rows) {
                customTemplate.rows = baseTemplate.rows;
            }
            if (!Array.isArray(customTemplate.cell_overrides)) {
                customTemplate.cell_overrides = [];
            }

            const baseAreas = Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : [];
            let templateAreas = Array.isArray(customTemplate.areas) && customTemplate.areas.length > 0
                ? customTemplate.areas.filter(area => area)
                : baseAreas;

            if (!Array.isArray(templateAreas)) {
                templateAreas = [];
            }

            customTemplate.areas = templateAreas.slice();

            // Normalize existing children and fallbacks stored in settings
            const directChildren = this.normalizeStructureToArray(element.children);
            const storedChildren = this.normalizeStructureToArray(element.settings._children);
            const totalCells = templateAreas.length;
            const finalChildren = new Array(totalCells);

            for (let index = 0; index < totalCells; index++) {
                let child = directChildren[index];

                if (!child && storedChildren[index]) {
                    // Deep clone to avoid mutating original stored structure
                    try {
                        child = JSON.parse(JSON.stringify(storedChildren[index]));
                    } catch (error) {
                        console.warn('⚠️ Failed to clone stored child structure', storedChildren[index], error);
                        child = storedChildren[index];
                    }
                }

                if (child && typeof child === 'object') {
                    if (!child.id) {
                        child.id = 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                    }
                    if (!child.settings || typeof child.settings !== 'object') {
                        child.settings = {};
                    }
                } else {
                    child = null;
                }

                finalChildren[index] = child;

            if (typeof customTemplate.cell_overrides[index] === 'undefined') {
                customTemplate.cell_overrides[index] = null;
            }
            }

        element.children = finalChildren;
        try {
            element.settings._children = JSON.parse(JSON.stringify(finalChildren));
        } catch (error) {
            console.warn('⚠️ Failed to serialize grid children for settings._children', error);
            element.settings._children = finalChildren;
        }
        },

        /**
         * Prepare elements for saving (deep clone + normalize)
         */
        prepareElementsForSave: function(elements) {
            const normalize = (element) => {
                if (!element || typeof element !== 'object') {
                    return null;
                }

                // Deep clone without references
                const cloned = JSON.parse(JSON.stringify(element));

                if (!cloned.settings || typeof cloned.settings !== 'object') {
                    cloned.settings = {};
                }

                if (cloned.widgetType === 'grid-layout') {
                    const pattern = cloned.settings.grid_pattern || 'pattern-1';
                    const baseTemplate = this.getGridTemplateData(pattern) || {};

                    if (!cloned.settings.custom_template || typeof cloned.settings.custom_template !== 'object') {
                        cloned.settings.custom_template = {};
                    }

                    if (!cloned.settings.custom_template.columns && baseTemplate.columns) {
                        cloned.settings.custom_template.columns = baseTemplate.columns;
                    }

                    if (!cloned.settings.custom_template.rows && baseTemplate.rows) {
                        cloned.settings.custom_template.rows = baseTemplate.rows;
                    }

                    if (!Array.isArray(cloned.settings.custom_template.areas) || cloned.settings.custom_template.areas.length === 0) {
                        cloned.settings.custom_template.areas = Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : [];
                    } else {
                        cloned.settings.custom_template.areas = cloned.settings.custom_template.areas.filter(area => area);
                    }

                    if (!cloned.settings.custom_template.columns && baseTemplate.columns) {
                        cloned.settings.custom_template.columns = baseTemplate.columns;
                    }

                    if (!cloned.settings.custom_template.rows && baseTemplate.rows) {
                        cloned.settings.custom_template.rows = baseTemplate.rows;
                    }

                    if (!Array.isArray(cloned.settings.custom_template.cell_overrides)) {
                        cloned.settings.custom_template.cell_overrides = [];
                    } else {
                        cloned.settings.custom_template.cell_overrides = cloned.settings.custom_template.cell_overrides.map(override => {
                            if (!override) {
                                return null;
                            }
                            return Object.assign({}, override);
                        });
                    }

                    if (!Array.isArray(cloned.children)) {
                        cloned.children = [];
                    }

                    cloned.children = cloned.children.map(child => normalize(child));

                    try {
                        cloned.settings._children = JSON.parse(JSON.stringify(cloned.children));
                    } catch (error) {
                        console.warn('⚠️ Failed to clone children for _children during save prep', error);
                        cloned.settings._children = cloned.children;
                    }
                } else if (Array.isArray(cloned.children) && cloned.children.length > 0) {
                    cloned.children = cloned.children.map(child => normalize(child));
                }

                return cloned;
            };

            return (elements || []).map(el => normalize(el)).filter(el => el);
        },
        
        /**
         * Load saved data
         */
        loadSavedData: function() {
            // FIRST: Try to load from PHP localized data (ProBuilderEditor.savedElements)
            if (typeof ProBuilderEditor !== 'undefined' && ProBuilderEditor.savedElements) {
                console.log('📥 Loading saved elements from PHP...', ProBuilderEditor.savedElements);
                
                if (Array.isArray(ProBuilderEditor.savedElements)) {
                    this.elements = ProBuilderEditor.savedElements;
                    console.log('✅ Loaded', this.elements.length, 'elements from ProBuilderEditor.savedElements');
                    this.renderElements();
                    return; // Exit early, we have the data!
                }
            }
            
            // FALLBACK: Try to load from hidden input field (legacy method)
            const data = $('#probuilder-data').val();
            if (data && data !== '[]' && data !== '') {
                try {
                    const parsed = JSON.parse(data);
                    // Ensure it's an array
                    if (Array.isArray(parsed)) {
                        this.elements = parsed;
                    } else if (typeof parsed === 'object' && parsed !== null) {
                        // If it's an object, try to convert to array
                        this.elements = Object.values(parsed);
                        console.warn('Converted object to array:', this.elements);
                    } else {
                        console.error('Invalid data format, expected array but got:', typeof parsed);
                        this.elements = [];
                    }
                    
                    console.log('✅ Loaded', this.elements.length, 'elements from hidden input (fallback)');
                    this.renderElements();
                } catch (e) {
                    console.error('Failed to parse saved data:', e);
                    this.elements = [];
                }
            } else {
                console.log('ℹ️ No saved data found, starting with empty canvas');
                this.elements = [];
            }
        },
        
        /**
         * Load Templates Library
         */
        loadTemplates: function() {
            const self = this;
            const $templatesContainer = $('.probuilder-tab-content[data-tab="templates"]');
            
            console.log('Loading templates from library...');
            
            $.ajax({
                url: ProBuilderEditor.ajaxurl || ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_get_templates'
                },
                success: function(response) {
                    console.log('Templates response:', response);
                    
                    if (response.success && response.data) {
                        self.renderTemplates(response.data.prebuilt || []);
                    } else {
                        $templatesContainer.html(`
                            <div style="text-align: center; padding: 60px 20px; color: #dc2626;">
                                <i class="dashicons dashicons-warning" style="font-size: 48px; margin-bottom: 20px;"></i>
                                <h3 style="font-size: 16px; margin: 0 0 10px 0;">Failed to Load Templates</h3>
                                <p style="font-size: 13px; margin: 0;">Please refresh the page and try again</p>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Templates load error:', error);
                    $templatesContainer.html(`
                        <div style="text-align: center; padding: 60px 20px; color: #dc2626;">
                            <i class="dashicons dashicons-warning" style="font-size: 48px; margin-bottom: 20px;"></i>
                            <h3 style="font-size: 16px; margin: 0 0 10px 0;">Error Loading Templates</h3>
                            <p style="font-size: 13px; margin: 0;">${error}</p>
                        </div>
                    `);
                }
            });
        },
        
        /**
         * Clear Canvas - Remove all elements
         */
        clearCanvas: function() {
            console.log('🗑️ Clearing canvas elements...');
            
            // Clear elements array
            this.elements = [];
            
            // Clear selected element
            this.selectedElement = null;
            
            // Re-render canvas (which will show empty state)
            this.renderElements();
            
            // Clear properties panel
            $('.probuilder-properties-content').html(`
                <div style="text-align: center; padding: 60px 20px; color: #a1a1aa;">
                    <i class="dashicons dashicons-admin-page" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                    <h3 style="font-size: 16px; color: #71717a; margin: 0 0 10px 0;">No Element Selected</h3>
                    <p style="font-size: 13px; margin: 0;">Select an element to edit its properties</p>
                </div>
            `);
            
            // Save history state
            this.saveHistory();
            
            console.log('✅ Canvas cleared, ready for template');
        },
        
        /**
         * Render Templates in UI
         */
        renderTemplates: function(templates) {
            const $templatesContainer = $('.probuilder-tab-content[data-tab="templates"]');
            const self = this;
            
            console.log('=== TEMPLATES DEBUG ===');
            console.log('Total templates received:', templates ? templates.length : 0);
            console.log('Templates array:', templates);
            
            if (!templates || templates.length === 0) {
                $templatesContainer.html(`
                    <div style="text-align: center; padding: 60px 20px; color: #a1a1aa;">
                        <i class="dashicons dashicons-admin-page" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                        <h3 style="font-size: 16px; color: #71717a; margin: 0 0 10px 0;">No Templates Found</h3>
                        <p style="font-size: 13px; margin: 0;">Templates will appear here once created</p>
                    </div>
                `);
                return;
            }
            
            console.log('Rendering', templates.length, 'templates');
            
            // Log each template
            templates.forEach((template, index) => {
                console.log(`Template ${index + 1}:`, template.name, '(ID:', template.id + ')');
            });
            
            let templatesHTML = `
                <div style="padding: 15px 20px; background: #f0f9ff; border-left: 4px solid #0284c7; margin-bottom: 20px;">
                    <strong style="color: #0c4a6e;">📋 ${templates.length} Templates Available</strong>
                    <p style="margin: 5px 0 0; font-size: 12px; color: #475569;">Showing all full page and section templates</p>
                </div>
                <div class="probuilder-templates-grid" style="padding: 0 20px 20px;">
            `;
            
            templates.forEach(function(template) {
                const thumbnail = template.thumbnail || 'data:image/svg+xml;base64,' + btoa('<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="#9ca3af" font-size="16">Template</text></svg>');
                
                templatesHTML += `
                    <div class="probuilder-template-item" data-template-id="${template.id}" style="
                        background: #fff;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        overflow: hidden;
                        cursor: pointer;
                        transition: all 0.2s;
                        margin-bottom: 15px;
                    " onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">
                        <div class="probuilder-template-thumbnail" style="
                            width: 100%;
                            height: 150px;
                            background: url('${thumbnail}') center/cover;
                            border-bottom: 1px solid #e5e7eb;
                        "></div>
                        <div class="probuilder-template-info" style="padding: 12px 15px;">
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #1f2937;">${template.name}</h4>
                            <p style="margin: 0; font-size: 12px; color: #6b7280;">${template.category || 'Full Page'}</p>
                        </div>
                        <div class="probuilder-template-actions" style="padding: 0 15px 12px; display: flex; gap: 8px;">
                            <button class="probuilder-template-insert" data-template-id="${template.id}" style="
                                flex: 1;
                                background: #0073aa;
                                color: #fff;
                                border: none;
                                padding: 8px 12px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-size: 12px;
                                font-weight: 600;
                                transition: background 0.2s;
                            " onmouseover="this.style.background='#005a87'" onmouseout="this.style.background='#0073aa'">
                                <i class="dashicons dashicons-plus-alt2" style="font-size: 14px; vertical-align: middle;"></i>
                                Insert
                            </button>
                            <button class="probuilder-template-preview" data-template-id="${template.id}" style="
                                background: #f3f4f6;
                                color: #374151;
                                border: 1px solid #d1d5db;
                                padding: 8px 12px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                                <i class="dashicons dashicons-visibility" style="font-size: 14px; vertical-align: middle;"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            templatesHTML += '</div></div>';
            
            console.log('Templates HTML generated, inserting into DOM...');
            $templatesContainer.html(templatesHTML);
            console.log('Templates inserted successfully!');
            
            // Bind insert handlers
            $('.probuilder-template-insert').on('click', function(e) {
                e.stopPropagation();
                const templateId = $(this).data('template-id');
                const template = templates.find(t => t.id === templateId);
                
                if (template && template.data) {
                    console.log('Inserting template:', template.name);
                    console.log('Template type:', template.type);
                    console.log('Template data:', template.data);
                    
                    // Clear canvas ONLY for full-page templates
                    if (template.type === 'page') {
                        console.log('🗑️ Full page template - clearing canvas first');
                        self.clearCanvas();
                    } else {
                        console.log('➕ Section template - adding to existing content');
                    }
                    
                    // Import template data WITH CHILDREN
                    if (Array.isArray(template.data)) {
                        template.data.forEach(function(elementData) {
                            console.log('📦 Template element:', elementData.widgetType);
                            console.log('   Children count:', elementData.children ? elementData.children.length : 0);
                            if (elementData.children && elementData.children.length > 0) {
                                console.log('   First child:', elementData.children[0]);
                            }
                            
                            if (elementData.widgetType) {
                                // Clone element data to preserve children
                                const newElement = self.cloneElementData(elementData);
                                console.log('✅ Cloned element:', newElement.widgetType, 'with', newElement.children.length, 'children');
                                self.elements.push(newElement);
                                self.renderElement(newElement);
                            }
                        });
                    } else {
                        if (template.data.widgetType) {
                            const newElement = self.cloneElementData(template.data);
                            self.elements.push(newElement);
                            self.renderElement(newElement);
                        }
                    }
                    
                    // Update UI
                    self.updateEmptyState();
                    self.saveHistory();
                    
                    // Show success message
                    const action = template.type === 'page' ? 'inserted' : 'added';
                    self.showToast('✓ Template ' + action + ' successfully!');
                    
                    // Switch back to widgets tab
                    $('.probuilder-tab-btn[data-tab="widgets"]').click();
                } else {
                    console.error('Template not found or no data:', templateId);
                    self.showToast('❌ Error: Template not found');
                }
            });
            
            // Bind preview handlers
            $('.probuilder-template-preview').on('click', function(e) {
                e.stopPropagation();
                const templateId = $(this).data('template-id');
                const template = templates.find(t => t.id === templateId);
                
                if (template) {
                    console.log('Previewing template:', template.name);
                    self.showToast('👁 Preview: ' + template.name);
                    
                    // You can add a modal preview here later
                    alert('Preview: ' + template.name + '\n\nThis template contains ' + 
                          (Array.isArray(template.data) ? template.data.length : 1) + ' elements.');
                }
            });
            
            console.log('Templates rendered successfully');
        },
        
        /**
         * Render widgets in sidebar
         */
        renderWidgets: function() {
            console.log('Rendering widgets...');
            
            const categories = {
                'layout': [],
                'basic': [],
                'advanced': [],
                'content': []
            };
            
            // Group widgets by category
            this.widgets.forEach(widget => {
                console.log('Processing widget:', widget.name, 'Category:', widget.category);
                if (categories[widget.category]) {
                    categories[widget.category].push(widget);
                } else {
                    console.warn('Unknown category:', widget.category, 'for widget:', widget.name);
                }
            });
            
            // Render each category
            Object.keys(categories).forEach(category => {
                const $container = $(`.probuilder-widgets-grid[data-category="${category}"]`);
                console.log(`Rendering ${category} category:`, categories[category].length, 'widgets');
                console.log('Container found:', $container.length > 0 ? 'YES' : 'NO');
                
                if ($container.length === 0) {
                    console.error(`Container for category "${category}" not found!`);
                    return;
                }
                
                // Clear existing content
                $container.empty();
                
                if (categories[category].length === 0) {
                    console.warn(`No widgets in category: ${category}`);
                    return;
                }
                
                categories[category].forEach(widget => {
                    const $widget = $(`
                        <div class="probuilder-widget" data-widget="${widget.name}">
                            <div class="probuilder-widget-icon">
                                <i class="${widget.icon}"></i>
                            </div>
                            <div class="probuilder-widget-title">${widget.title}</div>
                        </div>
                    `);
                    
                    $container.append($widget);
                    console.log('Added widget:', widget.name);
                });
            });
            
            console.log('Widgets rendering complete!');
        },
        
        /**
         * Initialize drag and drop
         */
        initDragDrop: function() {
            const self = this;
            
            // Check if jQuery UI is loaded
            if (typeof $.fn.draggable === 'undefined') {
                console.error('jQuery UI Draggable not loaded!');
                alert('ProBuilder Error: jQuery UI is not loaded. Drag and drop will not work.');
                return;
            }
            
            console.log('Initializing drag and drop...');
            console.log('Widgets found:', $('.probuilder-widget').length);
            
            // Make widgets draggable
            $('.probuilder-widget').draggable({
                helper: 'clone',
                appendTo: 'body',
                zIndex: 10000,
                cursor: 'move',
                revert: 'invalid',
                revertDuration: 200,
                start: function(event, ui) {
                    const widgetName = $(this).data('widget');
                    console.log('Started dragging widget:', widgetName);
                    $(ui.helper).css('opacity', '0.8');
                },
                stop: function(event, ui) {
                    console.log('Stopped dragging widget');
                    // Remove the helper clone to prevent it from sticking
                    $(ui.helper).remove();
                    $('body').css('cursor', '');
                }
            });
            
            console.log('✅ Widgets are now draggable:', $('.probuilder-widget').length);
            
            // Make preview area sortable (for reordering existing elements)
            const $previewArea = $('#probuilder-preview-area');
            console.log('Preview area found:', $previewArea.length > 0 ? 'YES' : 'NO');
            
            $previewArea.sortable({
                items: '> .probuilder-element',
                handle: '.probuilder-element-drag',
                placeholder: 'probuilder-element-placeholder',
                tolerance: 'pointer',
                cursor: 'grabbing',
                opacity: 0.8,
                distance: 10,
                delay: 100,
                revert: 150,
                cancel: 'input, textarea, select, .probuilder-element-actions button, .probuilder-add-below-btn',
                start: function(event, ui) {
                    console.log('🎯 Started dragging element via handle');
                    ui.item.addClass('dragging-element');
                    ui.placeholder.height(ui.item.outerHeight());
                    $('body').css('cursor', 'grabbing');
                },
                stop: function(event, ui) {
                    console.log('🎯 Stopped dragging element');
                    ui.item.removeClass('dragging-element');
                    $('body').css('cursor', '');
                },
                update: function() {
                    console.log('🎯 Elements reordered - updating data');
                    self.updateElementsOrder();
                }
            });
            
            // Make preview area droppable for NEW widgets from sidebar
            $previewArea.droppable({
                accept: '.probuilder-widget',
                tolerance: 'pointer',
                greedy: false, // Allow both preview area and columns to receive events
                drop: function(event, ui) {
                    if (self.isNestedDropInProgress) {
                        console.log('🎯 Nested drop in progress - skipping canvas drop handler');
                        return;
                    }
                    // Check if we're actually over a column (don't add if so)
                    const $target = $(event.target);
                    if ($target.hasClass('probuilder-column') || $target.closest('.probuilder-column').length > 0) {
                        console.log('🎯 Drop intercepted by column, skipping preview area handler');
                        return; // Column will handle it
                    }

                    // Detect actual element under pointer to avoid duplicates when dropping into nested zones
                    const clientX = event.clientX || (event.originalEvent && event.originalEvent.clientX);
                    const clientY = event.clientY || (event.originalEvent && event.originalEvent.clientY);
                    if (typeof clientX === 'number' && typeof clientY === 'number') {
                        const elementUnderPointer = document.elementFromPoint(clientX, clientY);
                        if (elementUnderPointer) {
                            const $realTarget = $(elementUnderPointer);
                            if (
                                $realTarget.closest('.grid-cell').length > 0 ||
                                $realTarget.closest('.probuilder-grid-layout').length > 0 ||
                                $realTarget.closest('.probuilder-tab-drop-zone').length > 0 ||
                                $realTarget.closest('.probuilder-drop-zone').length > 0
                            ) {
                                console.log('🎯 Drop handled by nested zone, skipping canvas handler');
                                return;
                            }
                        }
                    }
                    
                    const widgetName = ui.draggable.data('widget');
                    console.log('🎯 Dropped NEW widget on canvas:', widgetName);
                    
                    if (widgetName) {
                        // Get drop position by checking Y coordinate
                        const dropY = event.pageY;
                        const $elements = $previewArea.children('.probuilder-element');
                        let insertIndex = $elements.length; // Default to end
                        
                        // Find where to insert based on Y position
                        $elements.each(function(index) {
                            const $el = $(this);
                            const elTop = $el.offset().top;
                            const elMiddle = elTop + ($el.outerHeight() / 2);
                            
                            if (dropY < elMiddle && insertIndex === $elements.length) {
                                insertIndex = index;
                                return false; // Break loop
                            }
                        });
                        
                        console.log('Inserting at index:', insertIndex);
                        self.addElementAtPosition(widgetName, insertIndex);
                    }
                },
                over: function(event, ui) {
                    if (ui.draggable.hasClass('probuilder-widget')) {
                        $(this).addClass('probuilder-drop-active');
                    }
                },
                out: function() {
                    $(this).removeClass('probuilder-drop-active');
                }
            });
            
            // Make sidebar widgets draggable
            $('.probuilder-widget').each(function() {
                const $widget = $(this);
                const widgetName = $widget.data('widget');
                
                $widget.draggable({
                    helper: function() {
                        // Create a clone that looks like the widget
                        return $(this).clone().css({
                            'width': $(this).width(),
                            'opacity': 0.8,
                            'z-index': 10000
                        });
                    },
                    appendTo: 'body',
                    zIndex: 10000,
                    cursor: 'move',
                    revert: 'invalid',
                    revertDuration: 200,
                    start: function() {
                        console.log('Started dragging widget:', widgetName);
                        $('.probuilder-element-placeholder').show();
                        $('.probuilder-column').css('outline', '2px dashed #92003b');
                        // Make canvas areas droppable-ready
                        $('#probuilder-preview-area, .probuilder-column').addClass('drop-ready');
                    },
                    stop: function() {
                        $('.probuilder-column').css('outline', '');
                        $('#probuilder-preview-area, .probuilder-column').removeClass('drop-ready');
                    }
                });
            });
            
            console.log('✅ Preview area is now sortable - drag from anywhere on element!');
            
            // Make containers droppable for nested elements
            this.makeContainersDroppable();
            
            console.log('✅ Drag and drop fully initialized!');
        },
        
        /**
         * Make containers droppable
         */
        makeContainersDroppable: function() {
            const self = this;
            
            try {
                console.log('🔧 Reinitializing container drop zones...');
                
                // First, make the entire container droppable to catch any drops
                $('.probuilder-element[data-widget="container"], .probuilder-element[data-widget="container-2"]').each(function() {
                    const $container = $(this);
                    const containerId = $container.data('id');
                    
                    if (!containerId) return;
                    
                    // Destroy existing droppable if exists
                    if ($container.data('ui-droppable')) {
                        try {
                            $container.droppable('destroy');
                        } catch (e) {
                            console.warn('Error destroying container droppable:', e);
                        }
                    }
                    
                    // Make entire container droppable as fallback
                    $container.droppable({
                        accept: '.probuilder-widget',
                        tolerance: 'pointer',
                        greedy: true, // Capture all drops inside container bounds
                        drop: function(event, ui) {
                            self.isNestedDropInProgress = true;
                            const finishNestedDrop = () => {
                                setTimeout(() => {
                                    self.isNestedDropInProgress = false;
                                }, 0);
                            };
                            const widgetName = ui.draggable.data('widget');
                            
                            // Remove overlay
                            $(this).find('.probuilder-drop-overlay').remove();
                            
                            console.log('✅ Dropped widget in container (will add to end):', widgetName);
                            
                            if (widgetName && containerId) {
                                // Always add to end of container to create new row
                                self.addElementToContainer(widgetName, containerId, null);
                            }
                            finishNestedDrop();
                        },
                        over: function(event, ui) {
                            if (ui.draggable.hasClass('probuilder-widget')) {
                                const $containerElement = $(this);
                                
                                // Check if overlay already exists
                                if ($containerElement.find('.probuilder-drop-overlay').length === 0) {
                                    // Add overlay indicator that doesn't affect layout
                                    const overlay = `
                                        <div class="probuilder-drop-overlay" style="
                                            position: absolute;
                                            top: 0;
                                            left: 0;
                                            right: 0;
                                            bottom: 0;
                                            background: rgba(254, 241, 246, 0.9);
                                            border: 3px dashed #92003b;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            z-index: 5;
                                            pointer-events: none;
                                            animation: pulse 1.5s ease-in-out infinite;
                                        ">
                                            <div style="
                                                background: white;
                                                padding: 20px 30px;
                                                border-radius: 8px;
                                                box-shadow: 0 4px 12px rgba(146, 0, 59, 0.2);
                                                text-align: center;
                                            ">
                                                <i class="dashicons dashicons-plus-alt2" style="font-size: 48px; color: #92003b; margin-bottom: 10px; display: block;"></i>
                                                <span style="font-size: 16px; font-weight: 600; color: #92003b; display: block;">Drop inside container</span>
                                                <span style="font-size: 13px; color: #666; margin-top: 5px; display: block;">Element will be added to the grid</span>
                                            </div>
                                        </div>
                                    `;
                                    $containerElement.append(overlay);
                                    console.log('✨ Drop overlay created');
                                }
                            }
                        },
                        out: function(event, ui) {
                            // Remove overlay
                            $(this).find('.probuilder-drop-overlay').remove();
                            console.log('🧹 Drop overlay removed');
                        }
                    });
                });
                
                // Make container columns droppable for widgets from sidebar
                const $columns = $('.probuilder-element[data-widget="container"] .probuilder-column');
                console.log('Found', $columns.length, 'container columns');
                
                $columns.each(function() {
                    const $column = $(this);
                    const containerId = $column.data('container-id');
                    const columnIndex = $column.data('column-index');
                    
                    // Destroy existing droppable/sortable if they exist
                    try {
                        if ($column.data('ui-droppable')) {
                            $column.droppable('destroy');
                        }
                        if ($column.data('ui-sortable')) {
                            $column.sortable('destroy');
                        }
                    } catch (e) {
                        console.warn('Error destroying old handlers:', e);
                    }
                    
                    // Make column droppable for new widgets
                    $column.droppable({
                        accept: '.probuilder-widget',
                        tolerance: 'pointer',
                        greedy: true, // Prevent parent elements from also handling the drop
                        drop: function(event, ui) {
                            event.preventDefault();
                            self.isNestedDropInProgress = true;
                            const finishNestedDrop = () => {
                                setTimeout(() => {
                                    self.isNestedDropInProgress = false;
                                }, 0);
                            };
                            // Only handle if this is an empty drop zone
                            if (!$column.hasClass('probuilder-drop-zone')) {
                                console.log('Column has content, letting container handle drop');
                                finishNestedDrop();
                                return;
                            }
                            
                            const widgetName = ui.draggable.data('widget');
                            console.log('🎯 Dropped widget in empty column:', widgetName, 'column:', columnIndex);
                            
                            if (widgetName && containerId) {
                                self.addElementToContainer(widgetName, containerId, columnIndex);
                                event.stopPropagation(); // Prevent container from also handling
                            }
                            
                            // Reset background
                            $(this).css('background', '');
                            finishNestedDrop();
                        },
                        over: function(event, ui) {
                            if (ui.draggable.hasClass('probuilder-widget')) {
                                $(this).css('background', '#fef1f6');
                            }
                        },
                        out: function() {
                            $(this).css('background', '');
                        }
                    });
                    
                    // Make column sortable for reordering nested elements
                    $column.sortable({
                        items: '> .probuilder-nested-element',
                        placeholder: 'probuilder-element-placeholder',
                        tolerance: 'pointer',
                        cursor: 'move',
                        connectWith: '.probuilder-column',
                        opacity: 0.7,
                        distance: 10,
                        handle: '.probuilder-nested-drag'
                    });
                });
                
                console.log('✅ Container drop zones initialized successfully');
            } catch (error) {
                console.error('❌ Error in makeContainersDroppable:', error);
            }
            
            // Make container drop zones clickable
            $('.probuilder-drop-zone').each(function() {
                const $zone = $(this);
                const containerId = $zone.data('container-id') || $zone.data('grid-id'); // Support both container and container-2
                const columnIndex = $zone.data('column-index') ?? parseInt($zone.attr('data-cell-index'), 10); // Support both naming conventions
                
                $zone.off('click').on('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    const $target = $(e.target);
                    const isToolbar = $target.closest('.grid-cell-toolbar').length > 0;
                    const isDelete = $target.closest('.grid-cell-delete-btn').length > 0;
                    const isSettings = $target.closest('.settings-btn').length > 0;
                    const isAddContent = $target.closest('.add-content-btn').length > 0;
                    const isAddButton = $target.closest('.grid-cell-add-btn').length > 0;

                    if (isToolbar || isDelete || isSettings || isAddContent) {
                        console.log('⏭️ Drop zone click ignored - toolbar interaction');
                        return false;
                    }

                    if ($zone.hasClass('has-content')) {
                        console.log('⏭️ Drop zone click ignored - cell already has content');
                        return false;
                    }

                    if (!isAddButton) {
                        console.log('⏭️ Drop zone click ignored - outside add button');
                        return false;
                    }

                    if (self.isGridCellDeleting) {
                        console.log('⏸️ Drop zone click ignored - grid cell delete in progress');
                        return false;
                    }

                    if (self.isGridCellResizing) {
                        console.log('⏸️ Drop zone click ignored - grid cell resizing');
                        return false;
                    }

                    console.log('Drop zone clicked:', containerId, 'column/cell:', columnIndex);
                    self.showWidgetTemplateSelector(containerId, columnIndex);
                });
            });
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            const self = this;
            
            // Global event delegation for element controls (always work, even after updates)
            $('#probuilder-preview-area').on('click', '.probuilder-element-delete', function(e) {
                e.stopPropagation();
                e.preventDefault();
                const elementId = $(this).closest('.probuilder-element').data('id');
                const element = self.elements.find(el => el.id === elementId);
                if (element) {
                    console.log('🗑️ Delete button clicked for:', elementId);
                    self.deleteElement(element);
                }
                return false;
            });
            
            $('#probuilder-preview-area').on('click', '.probuilder-element-duplicate', function(e) {
                e.stopPropagation();
                e.preventDefault();
                const elementId = $(this).closest('.probuilder-element').data('id');
                const element = self.elements.find(el => el.id === elementId);
                if (element) {
                    console.log('📋 Duplicate button clicked for:', elementId);
                    self.duplicateElement(element);
                }
                return false;
            });
            
            $('#probuilder-preview-area').on('click', '.probuilder-element-edit', function(e) {
                e.stopPropagation();
                e.preventDefault();
                const elementId = $(this).closest('.probuilder-element').data('id');
                const element = self.elements.find(el => el.id === elementId);
                if (element) {
                    console.log('✏️ Edit button clicked for:', elementId);
                    self.openSettings(element);
                }
                return false;
            });

            const resolveGridId = ($el) => {
                return $el.attr('data-grid-id')
                    || $el.data('grid-id')
                    || $el.closest('.probuilder-grid-layout').attr('data-element-id')
                    || $el.closest('.probuilder-grid-layout').data('element-id')
                    || $el.closest('.probuilder-element').attr('data-id')
                    || $el.closest('.probuilder-element').data('id')
                    || null;
            };

            $('#probuilder-preview-area').on('click', '.grid-cell-add-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const $btn = $(this);
                const gridId = resolveGridId($btn);
                const cellIndexAttr = $btn.attr('data-cell-index');
                const cellIndex = parseInt(cellIndexAttr, 10);

                if (!gridId || Number.isNaN(cellIndex)) {
                    console.error('❌ Add button: missing grid ID or invalid cell index', {gridId, cellIndexAttr});
                    return false;
                }

                if (self.isGridCellDeleting || self.isGridCellResizing) {
                    console.log('⏸️ Add button click ignored - grid busy', {
                        deleting: self.isGridCellDeleting,
                        resizing: self.isGridCellResizing
                    });
                    return false;
                }

                console.log('➕ Add button clicked for grid cell', {gridId, cellIndex});
                self.showWidgetTemplateSelector(gridId, cellIndex, true);
                return false;
            });

            // Global handler for grid cell delete buttons (safety net)
            $('#probuilder-preview-area').on('click', '.grid-cell-delete-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                const $button = $(this);
                const cellIndexAttr = $button.attr('data-cell-index');
                const cellIndex = parseInt(cellIndexAttr, 10);
                const gridIdAttr = resolveGridId($button);

                if (Number.isNaN(cellIndex)) {
                    console.error('❌ Global handler: invalid cell index on delete button:', cellIndexAttr);
                    return false;
                }

                if (!gridIdAttr) {
                    console.error('❌ Global handler: grid ID not found for delete button', {cellIndexAttr});
                    return false;
                }

                const gridElement = self.elements.find(el => el && el.id === gridIdAttr);
                if (!gridElement) {
                    console.error('❌ Global handler: grid element not found', {gridIdAttr, cellIndex});
                    return false;
                }

                console.log('🗑️ Global grid cell delete handler triggered', {gridIdAttr, cellIndex});

                const $gridLayout = $button.closest('.probuilder-grid-layout');
                const domPattern = $gridLayout.attr('data-grid-pattern') || null;
                const domAreas = $gridLayout.find('.grid-cell').map(function() {
                    return $(this).attr('data-original-area') || null;
                }).get();

                const deleted = self.handleGridCellDelete(gridElement, cellIndex, {
                    triggerSource: 'global-handler',
                    skipConfirm: false,
                    domPattern,
                    domAreas
                });

                if (!deleted) {
                    console.warn('⚠️ Global handler: grid cell delete helper returned false', {gridIdAttr, cellIndex});
                }

                return false;
            });

            // Ultimate fallback to catch clicks even if element lives outside preview area container
            $(document).off('click.probuilderGridDeleteFallback', '.grid-cell-delete-btn')
                .on('click.probuilderGridDeleteFallback', '.grid-cell-delete-btn', function(e) {
                console.log('🛑 Document-level delete handler invoked');
                // Let the preview-area handler process it if we're inside
                if ($(this).closest('#probuilder-preview-area').length > 0) {
                    return; // already handled above
                }

                e.preventDefault();
                e.stopPropagation();

                const $button = $(this);
                const cellIndexAttr = $button.attr('data-cell-index');
                const cellIndex = parseInt(cellIndexAttr, 10);
                const gridIdAttr = resolveGridId($button);

                console.log('🔍 Document fallback handler details', {cellIndexAttr, gridIdAttr});

                if (Number.isNaN(cellIndex) || !gridIdAttr) {
                    console.error('❌ Document-level handler: missing cell index or grid id', {cellIndexAttr, gridIdAttr});
                    return false;
                }

                const gridElement = self.elements.find(el => el && el.id === gridIdAttr);
                if (!gridElement) {
                    console.error('❌ Document-level handler: grid element not found', {gridIdAttr, cellIndex});
                    return false;
                }

                const $gridLayout = $button.closest('.probuilder-grid-layout');
                const domPattern = $gridLayout.attr('data-grid-pattern') || null;
                const domAreas = $gridLayout.find('.grid-cell').map(function() {
                    return $(this).attr('data-original-area') || null;
                }).get();

                const deleted = self.handleGridCellDelete(gridElement, cellIndex, {
                    triggerSource: 'document-fallback',
                    skipConfirm: false,
                    domPattern,
                    domAreas
                });

                if (!deleted) {
                    console.warn('⚠️ Document-level handler: delete helper returned false', {gridIdAttr, cellIndex});
                }

                return false;
            });
            
            // Add below button - use widget picker
            $('#probuilder-preview-area').on('click', '.probuilder-add-below-btn', function(e) {
                e.stopPropagation();
                e.preventDefault();
                const elementId = $(this).closest('.probuilder-element').data('id');
                const element = self.elements.find(el => el.id === elementId);
                if (element) {
                    console.log('➕ Add below button clicked for:', elementId);
                    self.showWidgetPicker(element);
                }
                return false;
            });
            
            // New Page button
            $('#probuilder-new-page').on('click', function() {
                self.createNewPage();
            });
            
            // Clear Page button
            $('#probuilder-clear-page').on('click', function() {
                self.clearPage();
            });
            
            // Save button
            $('#probuilder-save').on('click', function() {
                self.savePage();
            });
            
            // Page Settings button
            $('#probuilder-page-settings').on('click', function() {
                self.showPageSettings();
            });
            
            // Undo button
            $('#probuilder-undo').on('click', function() {
                self.undo();
            });
            
            // Redo button
            $('#probuilder-redo').on('click', function() {
                self.redo();
            });
            
            // Preview button
            $('#probuilder-preview').on('click', function() {
                const url = ProBuilderEditor.home_url + '/?p=' + ProBuilderEditor.post_id + '&preview=true';
                window.open(url, '_blank');
            });
            
            // Responsive device buttons
            $('.probuilder-device-btn').on('click', function() {
                const device = $(this).data('device');
                console.log('Switching to device:', device);
                
                // Update active button
                $('.probuilder-device-btn').removeClass('active');
                $(this).addClass('active');
                
                // Update canvas data-device attribute
                $('.probuilder-canvas').attr('data-device', device);
                
                console.log('Canvas device set to:', device);
            });
            
            // Add first element button
            $('#probuilder-add-first-element').on('click', function() {
                self.showWidgetPicker(null);
            });
            
            // Add element button at bottom
            $(document).on('click', '.probuilder-add-element-btn', function() {
                self.showWidgetPicker(null);
            });
            
            // Container column resize handles (vertical dividers between columns)
            $(document).on('mousedown', '.probuilder-resize-handle', function(e) {
                e.preventDefault();
                self.startColumnResize($(this), e);
            });
            
            // Responsive visibility: re-apply on resize (compact and efficient)
            let respTimer = null;
            $(window).on('resize', function() {
                clearTimeout(respTimer);
                respTimer = setTimeout(function() {
                    self.applyResponsiveVisibilityToAll();
                }, 100);
            });
            
            // Column resize handles (resize individual columns)
            $(document).on('mousedown', '.column-resize-handle', function(e) {
                e.stopPropagation();
                e.preventDefault();
                e.stopImmediatePropagation();
                
                const $handle = $(this);
                const elementId = $handle.data('element-id');
                const columnIndex = parseInt($handle.data('column-index'));
                const direction = $handle.data('direction');
                
                console.log('🎯🎯🎯 COLUMN RESIZE HANDLE CLICKED! 🎯🎯🎯', {
                    elementId, 
                    columnIndex, 
                    direction,
                    handleClass: $handle.attr('class'),
                    handleElement: $handle[0]
                });
                
                // Find the container element in our data
                const containerElement = self.elements.find(el => el.id === elementId);
                if (!containerElement) {
                    console.error('❌ Container element not found:', elementId);
                    return;
                }
                
                if (isNaN(columnIndex)) {
                    console.error('❌ Invalid column index:', columnIndex);
                    return;
                }
                
                // Prevent any click events from bubbling
                $(document).on('click.columnResizePrevent', function(clickEvent) {
                    clickEvent.preventDefault();
                    clickEvent.stopPropagation();
                    $(document).off('click.columnResizePrevent');
                });
                
                self.startColumnDimensionResize(containerElement, columnIndex, direction, e);
                return false;
            });
            
            // Global grid cell resize handler (more reliable)
            $(document).on('mousedown', '.grid-resize-handle', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                const $handle = $(this);
                const cellIndex = parseInt($handle.attr('data-cell-index'), 10);
                const direction = $handle.data('direction');
                
                // Try multiple ways to find the grid element ID
                const $gridContainer = $handle.closest('.probuilder-grid-layout');
                let gridId = null;
                
                if ($gridContainer.length) {
                    // Get element ID from the grid container
                    gridId = $gridContainer.data('element-id') || $gridContainer.data('grid-element-id');
                }
                
                // Fallback: try to find via parent .probuilder-element
                if (!gridId) {
                    const $gridElement = $handle.closest('.probuilder-element');
                    gridId = $gridElement.data('id');
                }
                
                console.log('🎯 Global grid resize handler:', {gridId, cellIndex, direction, found: !!gridId});
                
                if (!gridId) {
                    console.error('❌ Could not find grid element ID');
                    return;
                }
                
                // Find the grid element in our data
                const gridElement = self.elements.find(e => e.id === gridId);
                if (!gridElement) {
                    console.error('❌ Grid element not found in elements array:', gridId);
                    console.log('Available elements:', self.elements.map(e => ({id: e.id, type: e.widgetType})));
                    return;
                }
                
                // Prevent any click events from bubbling
                $(document).on('click.gridResizePrevent', function(clickEvent) {
                    clickEvent.preventDefault();
                    clickEvent.stopPropagation();
                    $(document).off('click.gridResizePrevent');
                });
                
                self.startGridCellResize(gridElement, cellIndex, direction, e);
            });
            
            // Global container column resize handler (more reliable)
            $(document).on('mousedown', '.column-resize-handle', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                const $handle = $(this);
                const columnIndex = $handle.data('column-index');
                const direction = $handle.data('direction');
                const $containerElement = $handle.closest('.probuilder-element');
                const containerId = $containerElement.data('id');
                
                console.log('🎯 Global container column resize handler:', {containerId, columnIndex, direction});
                
                // Find the container element in our data
                const containerElement = self.elements.find(e => e.id === containerId);
                if (!containerElement) {
                    console.error('❌ Container element not found:', containerId);
                    return;
                }
                
                // Prevent any click events from bubbling
                $(document).on('click.columnResizePrevent', function(clickEvent) {
                    clickEvent.preventDefault();
                    clickEvent.stopPropagation();
                    $(document).off('click.columnResizePrevent');
                });
                
                self.startContainerColumnResize(containerElement, columnIndex, direction, e);
            });
            
            // Global delete handler for nested elements (more reliable)
            $(document).on('click', '.probuilder-nested-delete', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                const $button = $(this);
                const $nestedEl = $button.closest('.probuilder-nested-element');
                const childId = $nestedEl.data('id');
                const $gridCell = $nestedEl.closest('.grid-cell');
                const cellIndex = parseInt($gridCell.attr('data-cell-index'), 10);
                const $gridElement = $nestedEl.closest('.probuilder-element');
                const gridId = $gridElement.data('id');
                
                console.log('🗑️ Global delete handler triggered:', {childId, cellIndex, gridId});
                
                // Find the grid element in our data
                const gridElement = self.elements.find(e => e.id === gridId);
                if (!gridElement) {
                    console.error('❌ Grid element not found:', gridId);
                    return;
                }
                
                // Initialize children array if not exists
                if (!gridElement.children) {
                    gridElement.children = [];
                }
                
                if (gridElement.children[cellIndex]) {
                    console.log('🗑️ Removing widget from cell:', cellIndex);
                    gridElement.children[cellIndex] = null;
                    
                    // Re-render the entire grid
                    const $oldElement = $(`.probuilder-element[data-id="${gridId}"]`);
                    const $parent = $oldElement.parent();
                    const insertBefore = $oldElement.next()[0];
                    
                    $oldElement.remove();
                    self.renderElement(gridElement, insertBefore);
                    self.saveHistory();
                    
                    console.log('✅ Widget deleted from grid cell', cellIndex);
                } else {
                    console.warn('⚠️ No widget found in cell:', cellIndex);
                }
            });
            
            // Grid preset selection
            $(document).on('click', '.probuilder-grid-preset-item', function() {
                const $item = $(this);
                const setting = $item.data('setting');
                const pattern = $item.data('pattern');
                
                // Update selected state
                $item.siblings('.probuilder-grid-preset-item').removeClass('selected');
                $item.siblings('.probuilder-grid-preset-item').css({
                    'border-color': '#ddd',
                    'background': '#fff'
                }).find('div:last-child').css({
                    'color': '#666',
                    'font-weight': '400'
                });
                
                $item.addClass('selected');
                $item.css({
                    'border-color': '#007cba',
                    'background': 'rgba(0,124,186,0.05)'
                }).find('div:last-child').css({
                    'color': '#007cba',
                    'font-weight': '600'
                });
                
                // Update the setting
                if (self.selectedElement) {
                    const elementToUpdate = self.selectedElement;
                    elementToUpdate.settings[setting] = pattern;
                    
                    // For grid layouts, fully re-render to reattach all handlers
                    if (elementToUpdate.widgetType === 'grid-layout') {
                        const $oldElement = $(`.probuilder-element[data-id="${elementToUpdate.id}"]`);
                        const insertBefore = $oldElement.next()[0];
                        
                        // Remove old element
                        $oldElement.remove();
                        
                        // Render new element with all handlers
                        self.renderElement(elementToUpdate, insertBefore);
                        
                        // Re-select the element to keep settings panel open
                        setTimeout(function() {
                            self.selectElement(elementToUpdate);
                        }, 100);
                        
                        console.log('Grid pattern applied and element re-rendered:', pattern);
                    } else {
                        self.updateContainerWithChildren(elementToUpdate);
                    }
                    
                    self.saveHistory();
                }
            });
            
            // Add element between sections
            $(document).on('click', '.probuilder-add-section-between button', function() {
                const index = $(this).closest('.probuilder-add-section-between').data('index');
                self.showWidgetPicker(index);
            });
            
            // Tab switching
            $('.probuilder-tab-btn').on('click', function() {
                const tab = $(this).data('tab');
                
                // If Templates tab clicked, show modal instead
                if (tab === 'templates') {
                    self.showTemplatesModal();
                    return;
                }
                
                $('.probuilder-tab-btn').removeClass('active');
                $(this).addClass('active');
                $('.probuilder-tab-content').removeClass('active');
                $(`.probuilder-tab-content[data-tab="${tab}"]`).addClass('active');
            });
            
            // Settings tabs
            $(document).on('click', '.probuilder-settings-tab', function() {
                const tab = $(this).data('tab');
                console.log('Settings tab clicked:', tab);
                
                $('.probuilder-settings-tab').removeClass('active');
                $(this).addClass('active');
                
                // Re-render settings with tab filter
                if (self.selectedElement) {
                    const widget = self.widgets.find(w => w.name === self.selectedElement.widgetType);
                    if (widget) {
                        self.renderSettings(self.selectedElement, widget, tab);
                    }
                }
            });
            
            // Close settings panel
            $('.probuilder-close-settings').on('click', function() {
                self.closeSettings();
            });
            
            // Widget search
            $('#probuilder-widget-search').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.probuilder-widget').each(function() {
                    const title = $(this).find('.probuilder-widget-title').text().toLowerCase();
                    if (title.indexOf(search) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    self.savePage();
                }
                
                // Delete key to remove element
                if (e.key === 'Delete' && self.selectedElement) {
                    self.deleteElement(self.selectedElement);
                }
            });
            
            // Responsive device controls
            $('.probuilder-device-btn').on('click', function() {
                const device = $(this).data('device');
                $('.probuilder-device-btn').removeClass('active');
                $(this).addClass('active');
                
                $('.probuilder-canvas-inner').removeClass('device-desktop device-tablet device-mobile');
                $('.probuilder-canvas-inner').addClass('device-' + device);
            });
        },
        
        /**
         * Add element
         */
        addElement: function(widgetName, settings = {}) {
            try {
                console.log('Adding element:', widgetName);
                
                // Ensure elements is always an array
                if (!Array.isArray(this.elements)) {
                    console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                    this.elements = [];
                }
                
                const widget = this.widgets.find(w => w.name === widgetName);
                if (!widget) {
                    console.error('Widget not found:', widgetName);
                    alert('Error: Widget "' + widgetName + '" not found!');
                    return;
                }
                
                const element = {
                    id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                    widgetType: widgetName,
                    settings: Object.assign({}, this.getDefaultSettings(widget), settings),
                    children: []
                };
                
                console.log('Element created:', element);
                
                this.elements.push(element);
                console.log('Element pushed to array');
                
                this.renderElement(element);
                console.log('Element rendered');
                
                this.updateEmptyState();
                console.log('Empty state updated');
                
                // Save to history
                this.saveHistory();
                
                // Automatically select and open settings for the new element
                setTimeout(() => {
                    this.selectElement(element);
                    console.log('Element auto-selected:', element.id);
                }, 100);
                
                // Re-initialize drop zones with slight delay
                setTimeout(() => {
                    this.makeContainersDroppable();
                }, 50);
                
                console.log('✅ Element added successfully:', widgetName, element.id);
                
                return element;
            } catch (error) {
                console.error('❌ Error adding element:', error);
                alert('Error adding element: ' + error.message);
                return null;
            }
        },
        
        /**
         * Add element at specific position
         */
        addElementAtPosition: function(widgetName, insertIndex, settings = {}) {
            try {
                console.log('Adding element at position:', widgetName, 'at index:', insertIndex);
                
                // Ensure elements is always an array
                if (!Array.isArray(this.elements)) {
                    console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                    this.elements = [];
                }
                
                const widget = this.widgets.find(w => w.name === widgetName);
                if (!widget) {
                    console.error('Widget not found:', widgetName);
                    alert('Error: Widget "' + widgetName + '" not found!');
                    return;
                }
                
                const element = {
                    id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                    widgetType: widgetName,
                    settings: Object.assign({}, this.getDefaultSettings(widget), settings),
                    children: []
                };
                
                console.log('Element created:', element);
                
                // Insert at specific position
                this.elements.splice(insertIndex, 0, element);
                console.log('Element inserted at index:', insertIndex);
                
                // Re-render all elements to reflect new order
                this.renderElements();
                console.log('All elements re-rendered');
                
                this.updateEmptyState();
                
                // Automatically select and open settings for the new element
                setTimeout(() => {
                    this.selectElement(element);
                    console.log('Element auto-selected:', element.id);
                }, 100);
                
                // Re-initialize drop zones with slight delay
                setTimeout(() => {
                    this.makeContainersDroppable();
                }, 50);
                
                console.log('✅ Element added at position successfully:', widgetName, element.id);
                
                return element;
            } catch (error) {
                console.error('❌ Error adding element at position:', error);
                alert('Error adding element: ' + error.message);
                return null;
            }
        },
        
        /**
         * Add element to container
         */
        addElementToContainer: function(widgetName, containerId, columnIndex = null) {
            try {
                console.log('Adding element to container:', widgetName, 'into', containerId, 'at column:', columnIndex);
                
                const widget = this.widgets.find(w => w.name === widgetName);
                if (!widget) {
                    console.error('Widget not found:', widgetName);
                    alert('Error: Widget not found');
                    return;
                }
                
                const containerElement = this.elements.find(e => e.id === containerId);
                if (!containerElement) {
                    console.error('Container not found:', containerId);
                    alert('Error: Container not found');
                    return;
                }
                
                const newElement = {
                    id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                    widgetType: widgetName,
                    settings: Object.assign({}, this.getDefaultSettings(widget)),
                    children: []
                };
                
                // Add to container's children
                if (!containerElement.children) {
                    containerElement.children = [];
                }
                
                // Check if specified column position is already filled
                const columns = parseInt(containerElement.settings.columns || 1);
                const filledColumns = containerElement.children.length;
                
                if (columnIndex !== null && columnIndex >= 0) {
                    // Check if dropping into an existing filled column
                    if (columnIndex < filledColumns) {
                        // Column already has an element - just append to end
                        containerElement.children.push(newElement);
                        console.log('Column', columnIndex, 'already filled. Element appended as position:', containerElement.children.length - 1);
                    } else {
                        // Empty column - insert at that position
                        const insertIndex = Math.min(columnIndex, containerElement.children.length);
                        containerElement.children.splice(insertIndex, 0, newElement);
                        console.log('Element inserted at empty column:', insertIndex);
                    }
                } else {
                    // Default behavior: append to end
                    containerElement.children.push(newElement);
                    console.log('Element appended to end');
                }
                
                // Log the grid layout
                console.log(`Container layout: ${containerElement.children.length} elements in ${columns}-column grid`);
                const rows = Math.ceil(containerElement.children.length / columns);
                console.log(`This creates ${rows} row(s)`);
                
                // Force full canvas refresh for vertical containers to show all children
                if (containerElement.settings && containerElement.settings.direction === 'vertical') {
                    console.log('🔵 Forcing canvas refresh for VERTICAL container with', containerElement.children.length, 'children');
                    this.renderCanvas();
                }
                
                // Re-render the entire container
                this.updateContainerWithChildren(containerElement);
                
                // Reinitialize entire drag & drop system
                setTimeout(() => {
                    console.log('🔄 Full drag & drop system reinitialization...');
                    
                    // Reinitialize all components
                    this.reinitializeSidebarWidgets();
                    this.reinitializePreviewArea();
                    
                    // Reinitialize containers with a delay
                    setTimeout(() => {
                        this.makeContainersDroppable();
                    }, 150);
                    
                    console.log('✅ Drag & drop system fully reinitialized');
                }, 100);
                
                // Automatically select and open settings for the new element
                setTimeout(() => {
                    this.selectElement(newElement);
                    console.log('Element auto-selected:', newElement.id);
                }, 100);
                
                console.log('✅ Element successfully added to container');
                
                return newElement;
            } catch (error) {
                console.error('❌ Error adding element to container:', error);
                alert('Error: ' + error.message);
                return null;
            }
        },
        
        /**
         * Get grid patterns for visual selection
         */
        /**
         * Get grid template data (columns, rows, areas)
         */
        getGridTemplateData: function(pattern) {
            const templates = {
                'pattern-1': {
                    columns: 'repeat(4, 1fr)',
                    rows: 'repeat(4, 150px)',
                    areas: [
                        '1 / 1 / 3 / 3',  // Large left
                        '1 / 3 / 2 / 5',  // Top right
                        '2 / 3 / 3 / 4',  // Mid right 1
                        '2 / 4 / 3 / 5',  // Mid right 2
                        '3 / 1 / 5 / 2',  // Bottom left 1
                        '3 / 2 / 5 / 3',  // Bottom left 2
                        '3 / 3 / 5 / 5',  // Bottom right
                    ]
                },
                'pattern-2': {
                    columns: 'repeat(6, 1fr)',
                    rows: 'repeat(3, 200px)',
                    areas: [
                        '1 / 1 / 3 / 4',  // Large featured
                        '1 / 4 / 2 / 7',  // Top right
                        '2 / 4 / 3 / 5',  // Bottom right 1
                        '2 / 5 / 3 / 6',  // Bottom right 2
                        '2 / 6 / 3 / 7',  // Bottom right 3
                        '3 / 1 / 4 / 3',  // Bottom left
                        '3 / 3 / 4 / 5',  // Bottom center
                        '3 / 5 / 4 / 7',  // Bottom right
                    ]
                },
                'pattern-3': {
                    columns: 'repeat(4, 1fr)',
                    rows: 'repeat(5, 120px)',
                    areas: [
                        '1 / 1 / 3 / 2',  '1 / 2 / 2 / 3',  '1 / 3 / 3 / 4',  '1 / 4 / 2 / 5',
                        '2 / 2 / 4 / 3',  '2 / 4 / 3 / 5',  '3 / 1 / 4 / 2',  '3 / 3 / 5 / 4',
                        '3 / 4 / 5 / 5',  '4 / 1 / 6 / 2',  '4 / 2 / 5 / 3',  '5 / 3 / 6 / 5'
                    ]
                },
                'pattern-4': {
                    columns: 'repeat(12, 1fr)',
                    rows: 'repeat(4, 150px)',
                    areas: [
                        '1 / 1 / 2 / 4',  '1 / 4 / 2 / 7',  '1 / 7 / 2 / 10', '1 / 10 / 2 / 13',
                        '2 / 1 / 4 / 9',  '2 / 9 / 4 / 13', '4 / 1 / 5 / 7',  '4 / 7 / 5 / 13'
                    ]
                },
                'pattern-5': {
                    columns: 'repeat(5, 1fr)',
                    rows: 'repeat(3, 180px)',
                    areas: [
                        '1 / 1 / 3 / 3',  '1 / 3 / 2 / 4',  '1 / 4 / 2 / 5',  '1 / 5 / 2 / 6',
                        '2 / 3 / 3 / 6',  '3 / 1 / 4 / 2',  '3 / 2 / 4 / 3',  '3 / 3 / 4 / 4',
                        '3 / 4 / 4 / 5',  '3 / 5 / 4 / 6'
                    ]
                },
                'pattern-6': {
                    columns: 'repeat(4, 1fr)',
                    rows: 'repeat(4, 180px)',
                    areas: [
                        '1 / 1 / 3 / 3',  '1 / 3 / 2 / 4',  '1 / 4 / 2 / 5',  '2 / 3 / 3 / 4',
                        '2 / 4 / 3 / 5',  '3 / 1 / 5 / 2',  '3 / 2 / 4 / 3',  '3 / 3 / 4 / 4',
                        '3 / 4 / 4 / 5',  '4 / 2 / 5 / 5'
                    ]
                },
                'pattern-7': {
                    columns: 'repeat(6, 1fr)',
                    rows: 'repeat(4, 150px)',
                    areas: [
                        '1 / 1 / 2 / 3',  '1 / 3 / 3 / 5',  '1 / 5 / 2 / 7',  '2 / 1 / 3 / 2',
                        '2 / 2 / 3 / 3',  '2 / 5 / 4 / 7',  '3 / 1 / 5 / 3',  '3 / 3 / 4 / 5',
                        '4 / 3 / 5 / 7'
                    ]
                },
                'pattern-8': {
                    columns: 'repeat(2, 1fr)',
                    rows: 'repeat(6, 120px)',
                    areas: [
                        '1 / 1 / 4 / 2',  '1 / 2 / 2 / 3',  '2 / 2 / 3 / 3',  '3 / 2 / 4 / 3',
                        '4 / 1 / 5 / 2',  '4 / 2 / 5 / 3',  '5 / 1 / 7 / 2',  '5 / 2 / 7 / 3'
                    ]
                },
                'pattern-9': {
                    columns: 'repeat(8, 1fr)',
                    rows: 'repeat(5, 140px)',
                    areas: [
                        '1 / 1 / 3 / 5',  '1 / 5 / 2 / 9',  '2 / 5 / 3 / 7',  '2 / 7 / 3 / 9',
                        '3 / 1 / 4 / 3',  '3 / 3 / 4 / 5',  '3 / 5 / 4 / 7',  '3 / 7 / 4 / 9',
                        '4 / 1 / 6 / 4',  '4 / 4 / 5 / 6',  '4 / 6 / 5 / 8',  '4 / 8 / 5 / 9',
                        '5 / 4 / 6 / 9'
                    ]
                },
                'pattern-10': {
                    columns: 'repeat(10, 1fr)',
                    rows: 'repeat(6, 120px)',
                    areas: [
                        '1 / 1 / 3 / 4',   '1 / 4 / 2 / 6',   '1 / 6 / 3 / 8',   '1 / 8 / 2 / 11',
                        '2 / 4 / 3 / 6',   '2 / 8 / 4 / 11',  '3 / 1 / 5 / 3',   '3 / 3 / 4 / 5',
                        '3 / 5 / 5 / 8',   '4 / 3 / 5 / 5',   '4 / 8 / 6 / 10',  '5 / 1 / 7 / 3',
                        '5 / 3 / 6 / 6',   '5 / 10 / 7 / 11', '6 / 3 / 7 / 5',   '6 / 5 / 7 / 8',
                        '6 / 8 / 7 / 10'
                    ]
                }
            };
            
            return templates[pattern] || templates['pattern-1'];
        },
        
        getGridPatterns: function() {
            return [
                {
                    id: 'pattern-1',
                    name: 'Magazine Hero',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="46" height="46" fill="#007cba" opacity="0.7"/><rect x="52" y="2" width="46" height="21" fill="#007cba" opacity="0.5"/><rect x="52" y="27" width="21" height="21" fill="#007cba" opacity="0.5"/><rect x="77" y="27" width="21" height="21" fill="#007cba" opacity="0.5"/><rect x="2" y="52" width="21" height="46" fill="#007cba" opacity="0.5"/><rect x="27" y="52" width="21" height="46" fill="#007cba" opacity="0.5"/><rect x="52" y="52" width="46" height="46" fill="#007cba" opacity="0.6"/></svg>`
                },
                {
                    id: 'pattern-2',
                    name: 'Featured Post',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="44" height="63" fill="#007cba" opacity="0.7"/><rect x="50" y="2" width="48" height="28" fill="#007cba" opacity="0.5"/><rect x="50" y="34" width="14" height="31" fill="#007cba" opacity="0.5"/><rect x="68" y="34" width="14" height="31" fill="#007cba" opacity="0.5"/><rect x="86" y="34" width="12" height="31" fill="#007cba" opacity="0.5"/><rect x="2" y="69" width="30" height="29" fill="#007cba" opacity="0.5"/><rect x="36" y="69" width="30" height="29" fill="#007cba" opacity="0.6"/><rect x="70" y="69" width="28" height="29" fill="#007cba" opacity="0.5"/></svg>`
                },
                {
                    id: 'pattern-3',
                    name: 'Pinterest Masonry',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="21" height="40" fill="#007cba" opacity="0.6"/><rect x="27" y="2" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="52" y="2" width="21" height="40" fill="#007cba" opacity="0.6"/><rect x="77" y="2" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="27" y="26" width="21" height="40" fill="#007cba" opacity="0.7"/><rect x="77" y="26" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="2" y="46" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="52" y="46" width="21" height="52" fill="#007cba" opacity="0.6"/><rect x="77" y="50" width="21" height="48" fill="#007cba" opacity="0.6"/><rect x="2" y="70" width="21" height="28" fill="#007cba" opacity="0.6"/><rect x="27" y="70" width="21" height="28" fill="#007cba" opacity="0.5"/></svg>`
                },
                {
                    id: 'pattern-4',
                    name: 'Dashboard',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="27" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="52" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="77" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="2" y="20" width="60" height="44" fill="#007cba" opacity="0.7"/><rect x="66" y="20" width="32" height="44" fill="#007cba" opacity="0.6"/><rect x="2" y="68" width="46" height="30" fill="#007cba" opacity="0.5"/><rect x="52" y="68" width="46" height="30" fill="#007cba" opacity="0.5"/></svg>`
                },
                {
                    id: 'pattern-5',
                    name: 'Portfolio Showcase',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="36" height="60" fill="#007cba" opacity="0.7"/><rect x="42" y="2" width="16" height="28" fill="#007cba" opacity="0.5"/><rect x="62" y="2" width="16" height="28" fill="#007cba" opacity="0.5"/><rect x="82" y="2" width="16" height="28" fill="#007cba" opacity="0.5"/><rect x="42" y="34" width="56" height="28" fill="#007cba" opacity="0.6"/><rect x="2" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="22" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="42" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="62" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="82" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/></svg>`
                },
                {
                    id: 'pattern-6',
                    name: 'Product Grid',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="44" height="44" fill="#007cba" opacity="0.7"/><rect x="50" y="2" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="75" y="2" width="23" height="20" fill="#007cba" opacity="0.5"/><rect x="50" y="26" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="75" y="26" width="23" height="20" fill="#007cba" opacity="0.5"/><rect x="2" y="50" width="21" height="48" fill="#007cba" opacity="0.6"/><rect x="27" y="50" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="52" y="50" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="77" y="50" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="27" y="74" width="71" height="24" fill="#007cba" opacity="0.6"/></svg>`
                },
                {
                    id: 'pattern-7',
                    name: 'Asymmetric Modern',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="28" height="20" fill="#007cba" opacity="0.5"/><rect x="34" y="2" width="28" height="46" fill="#007cba" opacity="0.7"/><rect x="66" y="2" width="32" height="20" fill="#007cba" opacity="0.5"/><rect x="2" y="26" width="13" height="22" fill="#007cba" opacity="0.5"/><rect x="19" y="26" width="13" height="22" fill="#007cba" opacity="0.5"/><rect x="66" y="26" width="32" height="44" fill="#007cba" opacity="0.6"/><rect x="2" y="52" width="30" height="46" fill="#007cba" opacity="0.6"/><rect x="36" y="52" width="26" height="20" fill="#007cba" opacity="0.5"/><rect x="36" y="76" width="60" height="22" fill="#007cba" opacity="0.6"/></svg>`
                },
                {
                    id: 'pattern-8',
                    name: 'Split Screen',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="46" height="56" fill="#007cba" opacity="0.7"/><rect x="52" y="2" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="52" y="22" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="52" y="42" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="62" width="46" height="16" fill="#007cba" opacity="0.6"/><rect x="52" y="62" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="82" width="46" height="16" fill="#007cba" opacity="0.6"/><rect x="52" y="82" width="46" height="16" fill="#007cba" opacity="0.6"/></svg>`
                },
                {
                    id: 'pattern-9',
                    name: 'Blog Magazine',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="42" height="36" fill="#007cba" opacity="0.7"/><rect x="48" y="2" width="50" height="16" fill="#007cba" opacity="0.6"/><rect x="48" y="22" width="23" height="16" fill="#007cba" opacity="0.5"/><rect x="75" y="22" width="23" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="27" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="52" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="77" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="62" width="35" height="36" fill="#007cba" opacity="0.6"/><rect x="41" y="62" width="25" height="16" fill="#007cba" opacity="0.5"/><rect x="70" y="62" width="28" height="16" fill="#007cba" opacity="0.5"/><rect x="41" y="82" width="57" height="16" fill="#007cba" opacity="0.6"/></svg>`
                },
                {
                    id: 'pattern-10',
                    name: 'Creative Complex',
                    svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="26" height="34" fill="#007cba" opacity="0.6"/><rect x="32" y="2" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="54" y="2" width="18" height="34" fill="#007cba" opacity="0.6"/><rect x="76" y="2" width="22" height="16" fill="#007cba" opacity="0.5"/><rect x="32" y="22" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="76" y="22" width="22" height="34" fill="#007cba" opacity="0.6"/><rect x="2" y="40" width="18" height="36" fill="#007cba" opacity="0.6"/><rect x="24" y="40" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="46" y="40" width="26" height="36" fill="#007cba" opacity="0.7"/><rect x="24" y="60" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="80" width="18" height="18" fill="#007cba" opacity="0.5"/><rect x="24" y="80" width="24" height="18" fill="#007cba" opacity="0.5"/><rect x="76" y="60" width="22" height="18" fill="#007cba" opacity="0.5"/><rect x="52" y="80" width="46" height="18" fill="#007cba" opacity="0.6"/></svg>`
                }
            ];
        },
        
        /**
         * Start column resize
         */
        startColumnResize: function(handle, e) {
            const self = this;
            const elementId = handle.data('element-id');
            const columnIndex = parseInt(handle.data('column'));
            
            console.log('🎯 Starting column resize for element:', elementId, 'column:', columnIndex);
            
            // Get container element
            const containerElement = this.elements.find(el => el.id === elementId);
            if (!containerElement) {
                console.error('Container element not found:', elementId);
                return;
            }
            
            // Get current column widths
            const currentWidths = (containerElement.settings.column_widths || '50,50').split(',').map(w => parseFloat(w.trim()));
            const startX = e.clientX;
            const startWidths = [...currentWidths];
            
            console.log('Start widths:', startWidths);
            
            // Get container DOM element for width calculation
            const $containerColumns = handle.closest('.probuilder-container-columns');
            const containerWidth = $containerColumns.width();
            
            // Show visual feedback
            handle.css('opacity', '1');
            $('body').css('cursor', 'ew-resize');
            
            // Add global mouse events
            $(document).on('mousemove.columnResize', function(moveEvent) {
                moveEvent.preventDefault();
                
                const deltaX = moveEvent.clientX - startX;
                const deltaPercent = (deltaX / containerWidth) * 100;
                
                console.log('Delta:', deltaPercent.toFixed(2) + '%');
                
                // Calculate new widths
                const newWidths = [...startWidths];
                newWidths[columnIndex] = Math.max(5, Math.min(95, startWidths[columnIndex] + deltaPercent));
                newWidths[columnIndex + 1] = Math.max(5, Math.min(95, startWidths[columnIndex + 1] - deltaPercent));
                
                // Update settings
                containerElement.settings.column_widths = newWidths.map(w => w.toFixed(2)).join(',');
                
                console.log('New widths:', containerElement.settings.column_widths);
                
                // Re-render container
                self.updateContainerWithChildren(containerElement);
            });
            
            $(document).on('mouseup.columnResize', function() {
                $(document).off('.columnResize');
                
                // Reset cursor
                $('body').css('cursor', '');
                
                // Save to history
                self.saveHistory();
                
                console.log('✅ Column resize completed. Final widths:', containerElement.settings.column_widths);
            });
        },
        
        /**
         * Start column dimension resize - ABSOLUTE POSITIONING for anchored edges
         * Resizes individual column's height and width with opposite edge fixed
         */
        startColumnDimensionResize: function(containerElement, columnIndex, direction, e) {
            const self = this;
            
            console.log('🎯 Starting column resize (ANCHORED):', containerElement.id, 'column:', columnIndex, 'direction:', direction);
            
            const $containerElement = $(`.probuilder-element[data-id="${containerElement.id}"]`);
            const $column = $containerElement.find(`.probuilder-column[data-column-index="${columnIndex}"]`);
            const $containerColumns = $column.closest('.probuilder-container-columns');
            
            if (!$column.length) {
                console.error('Column not found:', columnIndex);
                return;
            }
            
            // Get column position and dimensions
            const columnRect = $column[0].getBoundingClientRect();
            const containerRect = $containerColumns[0].getBoundingClientRect();
            
            const startWidth = columnRect.width;
            const startHeight = columnRect.height;
            const startTop = columnRect.top - containerRect.top;
            const startLeft = columnRect.left - containerRect.left;
            
            const startX = e.clientX;
            const startY = e.clientY;
            const containerWidth = $containerColumns.width();
            
            // Get or initialize column heights and paddings
            if (!containerElement.settings.column_heights) {
                containerElement.settings.column_heights = [];
            }
            if (!containerElement.settings.column_paddings) {
                containerElement.settings.column_paddings = [];
            }
            
            const startColumnHeight = containerElement.settings.column_heights[columnIndex] || 'auto';
            const startHeightValue = startColumnHeight === 'auto' ? startHeight : parseInt(startColumnHeight);
            
            // Get current paddings for this column
            const columnPadding = containerElement.settings.column_paddings[columnIndex] || {};
            const startPaddingTop = columnPadding.top || 0;
            const startPaddingLeft = columnPadding.left || 0;
            const startPaddingRight = columnPadding.right || 0;
            
            // Get current column widths
            const currentWidths = (containerElement.settings.column_widths || '50,50').split(',').map(w => parseFloat(w.trim()));
            const startWidths = [...currentWidths];
            const columnsCount = parseInt(containerElement.settings.columns_count || '2');
            
            // Track final position during drag
            let finalLeft = startLeft;
            let finalTop = startTop;
            let finalWidth = startWidth;
            let finalHeight = startHeight;
            
            console.log('Start:', {columnIndex, width: startWidth, height: startHeight, top: startTop, left: startLeft});
            
            // Convert to absolute positioning for anchored edge resize
            $column.css({
                'position': 'absolute',
                'top': startTop + 'px',
                'left': startLeft + 'px',
                'width': startWidth + 'px',
                'height': startHeight + 'px',
                'z-index': '1000',
                'box-shadow': '0 0 20px rgba(0,124,186,0.4)',
                'border-color': '#007cba',
                'transition': 'none'
            });
            
            // Set cursor based on direction
            const cursorMap = {
                'top': 'row-resize',
                'left': 'col-resize',
                'right': 'col-resize',
                'bottom': 'row-resize',
                'both': 'nwse-resize'
            };
            $('body').css('cursor', cursorMap[direction] || 'default');
            
            // Add size indicator
            const $indicator = $('<div class="column-resize-indicator" style="position: fixed; top: 10px; right: 10px; background: rgba(0,124,186,0.9); color: white; padding: 10px 15px; border-radius: 4px; font-size: 12px; z-index: 99999; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>');
            $('body').append($indicator);
            
            // Mouse move - resize with ANCHORED edges
            $(document).on('mousemove.columnDimensionResize', function(moveEvent) {
                moveEvent.preventDefault();
                moveEvent.stopPropagation();
                
                const deltaX = moveEvent.clientX - startX;
                const deltaY = moveEvent.clientY - startY;
                
                let newWidth = startWidth;
                let newHeight = startHeight;
                let newLeft = startLeft;
                let newTop = startTop;
                let newWidths = [...startWidths];
                
                // Handle VERTICAL resizing with anchored edges
                if (direction === 'top') {
                    // Top edge moves, bottom edge FIXED
                    newHeight = Math.max(50, startHeight - deltaY);
                    newTop = startTop + (startHeight - newHeight);
                    
                    $column.css({
                        'height': newHeight + 'px',
                        'top': newTop + 'px'
                    });
                    
                    finalHeight = newHeight;
                    finalTop = newTop;
                    
                    $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Height: ${Math.round(newHeight)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Bottom edge FIXED</div>
                    `);
                    
                } else if (direction === 'bottom') {
                    // Bottom edge moves, top edge FIXED
                    newHeight = Math.max(50, startHeight + deltaY);
                    
                    $column.css({
                        'height': newHeight + 'px'
                    });
                    
                    finalHeight = newHeight;
                    
                    $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Height: ${Math.round(newHeight)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Top edge FIXED</div>
                    `);
                }
                
                // Handle HORIZONTAL resizing with anchored edges
                if (direction === 'left') {
                    // Left edge moves, right edge FIXED
                    newWidth = Math.max(50, startWidth - deltaX);
                    newLeft = startLeft + (startWidth - newWidth);
                    
                    $column.css({
                        'width': newWidth + 'px',
                        'left': newLeft + 'px'
                    });
                    
                    finalWidth = newWidth;
                    finalLeft = newLeft;
                    
                    $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Width: ${Math.round(newWidth)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Right edge FIXED</div>
                    `);
                    
                } else if (direction === 'right') {
                    // Right edge moves, left edge FIXED
                    newWidth = Math.max(50, startWidth + deltaX);
                    
                    $column.css({
                        'width': newWidth + 'px'
                    });
                    
                    finalWidth = newWidth;
                    
                    $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Width: ${Math.round(newWidth)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Left edge FIXED</div>
                    `);

                    
                } else if (direction === 'both') {
                    // Corner handle - right and bottom edges move, top and left FIXED
                    newHeight = Math.max(50, startHeight + deltaY);
                    newWidth = Math.max(50, startWidth + deltaX);
                    
                    $column.css({
                        'height': newHeight + 'px',
                        'width': newWidth + 'px'
                    });
                    
                    finalHeight = newHeight;
                    finalWidth = newWidth;
                    
                    $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Width: ${Math.round(newWidth)}px</div>
                        <div>Height: ${Math.round(newHeight)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Top-left FIXED</div>
                    `);
                }
            });
            
            // Mouse up - Convert back to grid positioning
            $(document).on('mouseup.columnDimensionResize', function(upEvent) {
                upEvent.preventDefault();
                upEvent.stopPropagation();
                
                $(document).off('.columnDimensionResize');
                $indicator.remove();
                $('body').css('cursor', '');
                
                // Save final dimensions
                const exactWidth = Math.round(finalWidth);
                const exactHeight = Math.round(finalHeight);
                const widthPercent = (exactWidth / containerRect.width) * 100;
                
                console.log('Final dimensions:', {
                    width: exactWidth,
                    height: exactHeight,
                    widthPercent: widthPercent.toFixed(2)
                });
                
                // Save height if changed
                if (direction === 'top' || direction === 'bottom' || direction === 'both') {
                    containerElement.settings.column_heights[columnIndex] = exactHeight;
                    console.log('✅ Saved column height:', exactHeight);
                }
                
                // Save width if changed (convert to percentage for grid)
                if (direction === 'left' || direction === 'right' || direction === 'both') {
                    const newWidthPercent = (exactWidth / containerWidth) * 100;
                    const newWidths = [...startWidths]; // Use startWidths to preserve original values
                    
                    console.log('💾 Calculating new widths:', {
                        exactWidth,
                        containerWidth,
                        newWidthPercent: newWidthPercent.toFixed(2),
                        startWidths,
                        columnIndex,
                        direction
                    });
                    
                    if (columnIndex > 0 && direction === 'left') {
                        // Left edge moved - adjust this and previous column
                        const startWidthPx = containerWidth * (startWidths[columnIndex] / 100);
                        const prevWidthPx = containerWidth * (startWidths[columnIndex - 1] / 100);
                        const widthChange = exactWidth - startWidthPx;
                        
                        newWidths[columnIndex] = newWidthPercent;
                        newWidths[columnIndex - 1] = ((prevWidthPx - widthChange) / containerWidth) * 100;
                        
                        console.log('Left edge resize:', {
                            thisColumn: newWidths[columnIndex].toFixed(2),
                            prevColumn: newWidths[columnIndex - 1].toFixed(2)
                        });
                    } else if (columnIndex < columnsCount - 1 && direction === 'right') {
                        // Right edge moved - adjust this and next column
                        const startWidthPx = containerWidth * (startWidths[columnIndex] / 100);
                        const nextWidthPx = containerWidth * (startWidths[columnIndex + 1] / 100);
                        const widthChange = exactWidth - startWidthPx;
                        
                        newWidths[columnIndex] = newWidthPercent;
                        newWidths[columnIndex + 1] = ((nextWidthPx - widthChange) / containerWidth) * 100;
                        
                        console.log('Right edge resize:', {
                            thisColumn: newWidths[columnIndex].toFixed(2),
                            nextColumn: newWidths[columnIndex + 1].toFixed(2)
                        });
                    } else {
                        // Edge column (first left or last right) - redistribute to all others
                        const startWidthPx = containerWidth * (startWidths[columnIndex] / 100);
                        const widthChange = exactWidth - startWidthPx;
                        
                        newWidths[columnIndex] = newWidthPercent;
                        
                        // Calculate total of other columns
                        const otherIndices = [];
                        for (let j = 0; j < columnsCount; j++) {
                            if (j !== columnIndex) otherIndices.push(j);
                        }
                        
                        if (otherIndices.length > 0) {
                            const totalOthers = otherIndices.reduce((sum, j) => sum + startWidths[j], 0);
                            const totalOthersPx = containerWidth * (totalOthers / 100);
                            const newTotalOthersPx = totalOthersPx - widthChange;
                            
                            // Redistribute proportionally
                            otherIndices.forEach(j => {
                                const proportion = startWidths[j] / totalOthers;
                                newWidths[j] = (newTotalOthersPx / containerWidth) * 100 * proportion;
                            });
                        }
                        
                        console.log('Edge column resize:', {
                            thisColumn: newWidths[columnIndex].toFixed(2),
                            allWidths: newWidths.map(w => w.toFixed(2))
                        });
                    }
                    
                    containerElement.settings.column_widths = newWidths.map(w => w.toFixed(2)).join(',');
                    console.log('✅✅✅ SAVED column widths:', containerElement.settings.column_widths);
                }
                
                console.log('🔄 Final saved widths:', containerElement.settings.column_widths);
                
                // For width changes, apply grid template and SKIP preview update
                if (direction === 'left' || direction === 'right' || direction === 'both') {
                    const savedWidths = containerElement.settings.column_widths.split(',').map(w => parseFloat(w));
                    const total = savedWidths.reduce((sum, w) => sum + w, 0);
                    const gridTemplate = savedWidths.map(w => (w / total) + 'fr').join(' ');
                    
                    console.log('🎯 Applying grid template directly:', {
                        gridTemplate,
                        savedWidths: containerElement.settings.column_widths
                    });
                    
                    // Reset column to relative positioning FIRST
                    $column.css({
                        'position': '',
                        'top': '',
                        'left': '',
                        'width': '',
                        'height': '',
                        'z-index': '',
                        'box-shadow': '',
                        'border-color': '',
                        'transition': ''
                    });
                    
                    // THEN apply the grid template
                    $containerColumns.css('grid-template-columns', gridTemplate);
                    
                    console.log('✅ Grid template applied, widths saved. NOT calling updateElementPreview.');
                    
                    // DON'T call updateElementPreview - it would reset everything!
                    // Just save to history
                    self.saveHistory();
                    
                    return; // Exit early, don't update preview
                }
                
                // For height-only changes, update preview normally
                // Reset column to relative positioning
                $column.css({
                    'position': '',
                    'top': '',
                    'left': '',
                    'width': '',
                    'height': '',
                    'z-index': '',
                    'box-shadow': '',
                    'border-color': '',
                    'transition': ''
                });
                
                // Update the container preview to apply saved dimensions
                self.updateElementPreview(containerElement);
                
                // Save to history
                self.saveHistory();
            });
        },
        
        /**
         * Show alignment guides (Illustrator-style)
         */
        showAlignmentGuides: function($currentCell, $gridContainer, currentIndex, bounds, $guides, direction) {
            const tolerance = 3; // pixels tolerance for alignment
            
            // Hide all guides first
            Object.values($guides).forEach($guide => $guide.hide());
            
            // Get all other cells
            const $allCells = $gridContainer.find('.grid-cell').not(`[data-cell-index="${currentIndex}"]`);
            
            if ($allCells.length === 0) return;
            
            // Determine which guides to check based on resize direction
            // When resizing WIDTH (left/right), show VERTICAL lines (for X-axis alignment)
            // When resizing HEIGHT (top/bottom), show HORIZONTAL lines (for Y-axis alignment)
            const showVerticalGuides = (direction === 'left' || direction === 'right' || direction === 'both');
            const showHorizontalGuides = (direction === 'top' || direction === 'bottom' || direction === 'both');
            
            console.log('🎯 Alignment guides for direction:', direction, {
                showVerticalGuides,
                showHorizontalGuides
            });
            
            // Check alignment with each other cell
            $allCells.each(function() {
                const otherRect = this.getBoundingClientRect();
                
                // When resizing WIDTH (left/right handles), show VERTICAL guides (comparing X positions)
                if (showVerticalGuides) {
                    // Left edge alignment - vertical line at left position
                    if (Math.abs(bounds.left - otherRect.left) < tolerance) {
                        $guides.left.css('left', otherRect.left + 'px').show();
                        console.log('  ┃ Showing LEFT guide (vertical line)');
                    }
                    // Right edge alignment - vertical line at right position
                    if (Math.abs(bounds.right - otherRect.right) < tolerance) {
                        $guides.right.css('left', otherRect.right + 'px').show();
                        console.log('  ┃ Showing RIGHT guide (vertical line)');
                    }
                    // Center vertical alignment - vertical line at center X
                    if (Math.abs(bounds.centerX - (otherRect.left + otherRect.width / 2)) < tolerance) {
                        $guides.centerV.css('left', (otherRect.left + otherRect.width / 2) + 'px').show();
                        console.log('  ┃ Showing CENTER-V guide (vertical line)');
                    }
                }
                
                // When resizing HEIGHT (top/bottom handles), show HORIZONTAL guides (comparing Y positions)
                if (showHorizontalGuides) {
                    // Top edge alignment - horizontal line at top position
                    if (Math.abs(bounds.top - otherRect.top) < tolerance) {
                        $guides.top.css('top', otherRect.top + 'px').show();
                        console.log('  ━ Showing TOP guide (horizontal line)');
                    }
                    // Bottom edge alignment - horizontal line at bottom position
                    if (Math.abs(bounds.bottom - otherRect.bottom) < tolerance) {
                        $guides.bottom.css('top', otherRect.bottom + 'px').show();
                        console.log('  ━ Showing BOTTOM guide (horizontal line)');
                    }
                    // Center horizontal alignment - horizontal line at center Y
                    if (Math.abs(bounds.centerY - (otherRect.top + otherRect.height / 2)) < tolerance) {
                        $guides.centerH.css('top', (otherRect.top + otherRect.height / 2) + 'px').show();
                        console.log('  ━ Showing CENTER-H guide (horizontal line)');
                    }
                }
            });
        },
        
        /**
         * Start grid cell resize - ABSOLUTE POSITIONING METHOD
         * VERSION: 3.0.0-responsive-2024-10-26
         */
        startGridCellResize: function(gridElement, cellIndex, direction, e) {
            const self = this;
            
            console.log('🎯 Starting absolute resize VERSION 3.0.0:', gridElement.id, 'cell:', cellIndex, 'direction:', direction);
            console.log('📌 CODE VERSION: 3.0.0-responsive-2024-10-26 - WITH RESPONSIVE NEIGHBORS');

            // Flag so we can ignore click events triggered at end of resize
            this.isGridCellResizing = true;
            
            const $gridContainer = $(`.probuilder-element[data-id="${gridElement.id}"] .probuilder-grid-layout`);
            const $gridCell = $gridContainer.find(`.grid-cell[data-cell-index="${cellIndex}"]`);
            
            // Get cell position and dimensions
            const cellRect = $gridCell[0].getBoundingClientRect();
            const containerRect = $gridContainer[0].getBoundingClientRect();
            
            const startWidth = cellRect.width;
            const startHeight = cellRect.height;
            const startTop = cellRect.top - containerRect.top;
            const startLeft = cellRect.left - containerRect.left;
            
            const startX = e.clientX;
            const startY = e.clientY;
            
            // Store original area and styles
            const originalArea = $gridCell.data('original-area');
            const originalPosition = $gridCell.css('position');
            const originalZIndex = $gridCell.css('z-index');
            
            // Track final position during drag
            let finalLeft = startLeft;
            let finalTop = startTop;
            let finalWidth = startWidth;
            let finalHeight = startHeight;
            let isResizing = false; // Track if user actually resized (moved mouse)
            
            console.log('Start:', {width: startWidth, height: startHeight, area: originalArea});
            
            // Convert to absolute positioning for smooth resize
            $gridCell.css({
                'position': 'absolute',
                'top': startTop + 'px',
                'left': startLeft + 'px',
                'width': startWidth + 'px',
                'height': startHeight + 'px',
                'grid-area': 'unset',
                'z-index': '1000',
                'box-shadow': '0 0 20px rgba(0,124,186,0.4)',
                'border-color': '#007cba',
                'transition': 'none'
            });
            
            const cursorMap = {
                'top': 'row-resize',
                'left': 'col-resize',
                'right': 'col-resize',
                'bottom': 'row-resize',
                'both': 'nwse-resize'
            };
            $('body').css('cursor', cursorMap[direction] || 'default');
            
            // Add size indicator
            const $indicator = $('<div class="grid-resize-indicator" style="position: fixed; top: 10px; right: 10px; background: rgba(0,124,186,0.9); color: white; padding: 10px 15px; border-radius: 4px; font-size: 12px; z-index: 99999; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>');
            $('body').append($indicator);
            
            // Create alignment guide containers
            const $guides = {
                left: $('<div class="probuilder-alignment-guide vertical"></div>').appendTo('body'),
                right: $('<div class="probuilder-alignment-guide vertical"></div>').appendTo('body'),
                top: $('<div class="probuilder-alignment-guide horizontal"></div>').appendTo('body'),
                bottom: $('<div class="probuilder-alignment-guide horizontal"></div>').appendTo('body'),
                centerV: $('<div class="probuilder-alignment-guide vertical"></div>').appendTo('body'),
                centerH: $('<div class="probuilder-alignment-guide horizontal"></div>').appendTo('body')
            };
            
            // Hide all guides initially
            Object.values($guides).forEach($guide => $guide.hide());
            
            // Mouse move - SMOOTH pixel-by-pixel resize
            $(document).on('mousemove.gridResize', function(moveEvent) {
                moveEvent.preventDefault();
                moveEvent.stopPropagation();
                
                const deltaX = moveEvent.clientX - startX;
                const deltaY = moveEvent.clientY - startY;
                
                // Mark that we're actually resizing (mouse moved)
                if (Math.abs(deltaX) > 2 || Math.abs(deltaY) > 2) {
                    isResizing = true;
                }
                
                let newWidth = startWidth;
                let newHeight = startHeight;
                let newLeft = startLeft;
                let newTop = startTop;
                
                if (direction === 'top') {
                    // Resize from top side - bottom edge stays fixed
                    newHeight = Math.max(20, startHeight - deltaY);
                    newTop = startTop + (startHeight - newHeight);
                    $gridCell.css({
                        'height': newHeight + 'px',
                        'top': newTop + 'px'
                    });
                    // Track final values
                    finalHeight = newHeight;
                    finalTop = newTop;
                } else if (direction === 'bottom' || direction === 'both') {
                    // Resize from bottom side - top edge stays fixed
                    newHeight = Math.max(20, startHeight + deltaY);
                    $gridCell.css('height', newHeight + 'px');
                    // Track final values
                    finalHeight = newHeight;
                    finalTop = startTop; // Top doesn't move
                }
                
                if (direction === 'left') {
                    // Resize from left side - right edge stays fixed
                    newWidth = Math.max(20, startWidth - deltaX);
                    newLeft = startLeft + (startWidth - newWidth);
                    $gridCell.css({
                        'width': newWidth + 'px',
                        'left': newLeft + 'px'
                    });
                    // Track final values
                    finalWidth = newWidth;
                    finalLeft = newLeft;
                } else if (direction === 'right' || direction === 'both') {
                    // Resize from right side - left edge stays fixed
                    newWidth = Math.max(20, startWidth + deltaX);
                    $gridCell.css('width', newWidth + 'px');
                    // Track final values
                    finalWidth = newWidth;
                    finalLeft = startLeft; // Left doesn't move
                }
                
                // Update indicator
                const widthPercent = Math.round((newWidth / containerRect.width) * 100);
                const heightPercent = Math.round((newHeight / containerRect.height) * 100);
                
                $indicator.html(`
                    <div style="font-weight: bold; margin-bottom: 5px;">Resizing Cell ${cellIndex + 1}</div>
                    <div>Width: ${Math.round(newWidth)}px (${widthPercent}%)</div>
                    <div>Height: ${Math.round(newHeight)}px (${heightPercent}%)</div>
                    <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Release to apply</div>
                `);
                
                // Show alignment guides (Illustrator-style) - only show relevant direction
                self.showAlignmentGuides($gridCell, $gridContainer, cellIndex, {
                    left: newLeft + containerRect.left,
                    top: newTop + containerRect.top,
                    right: newLeft + newWidth + containerRect.left,
                    bottom: newTop + newHeight + containerRect.top,
                    centerX: newLeft + (newWidth / 2) + containerRect.left,
                    centerY: newTop + (newHeight / 2) + containerRect.top,
                    width: newWidth,
                    height: newHeight
                }, $guides, direction);
            });
            
            // Mouse up - Convert back to grid with RESPONSIVE behavior
            $(document).on('mouseup.gridResize', function(upEvent) {
                upEvent.preventDefault();
                upEvent.stopPropagation();
                
                $(document).off('.gridResize');
                $indicator.remove();
                
                // Remove alignment guides
                Object.values($guides).forEach($guide => $guide.remove());
                
                // Use the tracked final dimensions from mousemove (already updated during drag)
                // No need to recalculate - we have the exact values from the drag operation
                
                // Calculate scale factors for grid-area adjustments
                const scaleX = finalWidth / startWidth;
                const scaleY = finalHeight / startHeight;
                
                // Round to exact pixels for final application
                const exactWidth = Math.round(finalWidth);
                const exactHeight = Math.round(finalHeight);
                
                console.log('Using tracked dimensions from drag:', {
                    finalLeft,
                    finalTop,
                    exactWidth,
                    exactHeight,
                    direction
                });
                
                // Parse original grid-area
                const parts = originalArea.split('/').map(p => p.trim());
                let [rowStart, colStart, rowEnd, colEnd] = parts.map(p => parseInt(p));
                
                // Calculate new spans based on scale - FLEXIBLE (no rounding for precise control)
                const originalColSpan = colEnd - colStart;
                const originalRowSpan = rowEnd - rowStart;
                
                if (direction === 'right' || direction === 'both') {
                    // Resize from right: adjust colEnd
                    const newColSpan = Math.max(1, originalColSpan * scaleX);
                    colEnd = colStart + newColSpan;
                } else if (direction === 'left') {
                    // Resize from left: adjust colStart
                    const newColSpan = Math.max(1, originalColSpan * scaleX);
                    colStart = colEnd - newColSpan;
                }
                
                if (direction === 'bottom' || direction === 'both') {
                    // Resize from bottom: adjust rowEnd
                    const newRowSpan = Math.max(1, originalRowSpan * scaleY);
                    rowEnd = rowStart + newRowSpan;
                } else if (direction === 'top') {
                    // Resize from top: adjust rowStart
                    const newRowSpan = Math.max(1, originalRowSpan * scaleY);
                    rowStart = rowEnd - newRowSpan;
                }
                
                // Keep original grid-area for reference
                const finalArea = originalArea;
                
                console.log('Finalizing resize with tracked values:', {
                    direction,
                    finalLeft,
                    finalTop,
                    exactWidth,
                    exactHeight,
                    startLeft,
                    startTop
                });
                
                // Persist custom grid template details so reload uses the resized dimensions
                if (!gridElement.settings) {
                    gridElement.settings = {};
                }
                if (!gridElement.settings.custom_template || typeof gridElement.settings.custom_template !== 'object') {
                    gridElement.settings.custom_template = {};
                }
                gridElement.settings.custom_template.areas = gridElement.settings.custom_template.areas || [];
                gridElement.settings.custom_template.areas[cellIndex] = finalArea;

                if ($gridContainer.length) {
                    const computedStyles = window.getComputedStyle($gridContainer[0]);
                    gridElement.settings.custom_template.columns = (computedStyles.getPropertyValue('grid-template-columns') || '').trim();
                    gridElement.settings.custom_template.rows = (computedStyles.getPropertyValue('grid-template-rows') || '').trim();
                }

                if (!Array.isArray(gridElement.settings.custom_template.cell_overrides)) {
                    gridElement.settings.custom_template.cell_overrides = [];
                }

                const containerWidth = containerRect.width || 1;
                const containerHeight = containerRect.height || 1;

                const overrides = gridElement.settings.custom_template.cell_overrides;
                const $allCells = $gridContainer.find('.grid-cell');
                let maxRight = 0;
                let maxBottom = 0;

                $gridContainer.css({
                    'position': 'relative'
                });

                $allCells.each(function(idx) {
                    const $cell = $(this);
                    const cellEl = $cell[0];
                    const cellRect = (idx === cellIndex)
                        ? {
                            left: containerRect.left + finalLeft,
                            top: containerRect.top + finalTop,
                            width: finalWidth,
                            height: finalHeight
                        }
                        : cellEl.getBoundingClientRect();

                    const relativeLeft = Math.round(cellRect.left - containerRect.left);
                    const relativeTop = Math.round(cellRect.top - containerRect.top);
                    const exactCellWidth = Math.round(cellRect.width);
                    const exactCellHeight = Math.round(cellRect.height);

                    const leftPercent = parseFloat(((relativeLeft / containerWidth) * 100).toFixed(4));
                    const topPercent = parseFloat(((relativeTop / containerHeight) * 100).toFixed(4));
                    const widthPercent = parseFloat(((exactCellWidth / containerWidth) * 100).toFixed(4));
                    const heightPercent = parseFloat(((exactCellHeight / containerHeight) * 100).toFixed(4));

                    const existingOverride = overrides[idx] || {};
                    const computedStyle = window.getComputedStyle(cellEl);
                    let zIndex = existingOverride.zIndex;
                    if (typeof zIndex === 'undefined' || zIndex === null) {
                        const computedZ = parseInt(computedStyle.zIndex, 10);
                        zIndex = Number.isFinite(computedZ) ? computedZ : (idx === cellIndex ? 2 : 1);
                    }

                    overrides[idx] = {
                        left: relativeLeft,
                        top: relativeTop,
                        width: exactCellWidth,
                        height: exactCellHeight,
                        leftPercent,
                        topPercent,
                        widthPercent,
                        heightPercent,
                        position: 'absolute',
                        zIndex
                    };

                    $cell.css({
                        'position': 'absolute',
                        'left': `${relativeLeft}px`,
                        'top': `${relativeTop}px`,
                        'width': `${exactCellWidth}px`,
                        'height': `${exactCellHeight}px`,
                        'grid-area': 'unset',
                        'z-index': zIndex || '',
                        'box-shadow': '',
                        'border-color': '',
                        'transition': ''
                    });

                    const cellBottom = relativeTop + exactCellHeight;
                    const cellRight = relativeLeft + exactCellWidth;
                    if (cellBottom > maxBottom) {
                        maxBottom = cellBottom;
                    }
                    if (cellRight > maxRight) {
                        maxRight = cellRight;
                    }
                });

                const adjustedHeight = Math.max(containerRect.height, maxBottom);
                gridElement.settings.custom_template.container_height = Math.round(adjustedHeight);
                gridElement.settings.custom_template.container_width = Math.round(Math.max(containerRect.width, maxRight));
                gridElement.settings.custom_template.layout_mode = 'absolute';

                $gridContainer.css({
                    'height': `${Math.round(adjustedHeight)}px`,
                    'min-height': `${Math.round(adjustedHeight)}px`
                });

                $('body').css('cursor', '');
                
                // Update stored area
                $gridCell.data('original-area', finalArea);
                $gridCell.attr('data-original-area', finalArea);
                
                // Prevent click event from firing if we actually resized
                if (isResizing) {
                    setTimeout(function() {
                        $(document).one('click.preventAfterResize', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            return false;
                        });
                    }, 10);
                }
                
                // Save to history
                self.saveHistory();
                
                console.log('✅ Resize complete:', {
                    original: originalArea,
                    final: finalArea,
                    scaleX: scaleX.toFixed(2),
                    scaleY: scaleY.toFixed(2),
                    finalWidth: Math.round(finalWidth),
                    finalHeight: Math.round(finalHeight),
                    isResizing: isResizing
                });

                // Allow other interactions again (next tick to avoid current click)
                setTimeout(function() {
                    self.isGridCellResizing = false;
                    console.log('🟢 Grid cell resize completed - clicks enabled again');
                }, 150);
            });
        },
        
    handleGridCellDelete: function(gridElement, cellIndex, options = {}) {
        const self = this;
        const settings = Object.assign({
            skipConfirm: false,
            triggerSource: 'unknown',
            removeCell: true,
            confirmMessage: null,
            confirmCallback: null
        }, options || {});

        const normalizeToArray = (value) => self.normalizeStructureToArray(value);

        if (!gridElement || !gridElement.id) {
            console.error('❌ Cannot modify grid cell - grid element missing', {gridElement, cellIndex, settings});
            return false;
        }

        if (!Number.isInteger(cellIndex) || cellIndex < 0) {
            console.error('❌ Cannot modify grid cell - invalid cell index', {cellIndex, gridElement, settings});
            return false;
        }

        gridElement.children = normalizeToArray(gridElement.children);

        if (gridElement.children.length === 0 && gridElement.settings) {
            const fallbackChildren = normalizeToArray(gridElement.settings._children);
            if (fallbackChildren.length > 0) {
                gridElement.children = fallbackChildren;
            }
        }

        if (!gridElement.settings || typeof gridElement.settings !== 'object') {
            gridElement.settings = {};
        }

        const patternHint = settings.domPattern || null;
        const patternKey = patternHint || gridElement.settings.grid_pattern || 'pattern-1';
        gridElement.settings.grid_pattern = patternKey;

        if (!gridElement.settings.custom_template || typeof gridElement.settings.custom_template !== 'object') {
            gridElement.settings.custom_template = {};
        }

        const baseTemplate = self.getGridTemplateData(patternKey) || {};

        if (!gridElement.settings.custom_template.columns && baseTemplate.columns) {
            gridElement.settings.custom_template.columns = baseTemplate.columns;
        }

        if (!gridElement.settings.custom_template.rows && baseTemplate.rows) {
            gridElement.settings.custom_template.rows = baseTemplate.rows;
        }

        const domAreas = Array.isArray(settings.domAreas) && settings.domAreas.length > 0
            ? settings.domAreas.filter(area => area)
            : null;

        if (domAreas && domAreas.length > 0) {
            gridElement.settings.custom_template.areas = domAreas.slice();
        } else if (!Array.isArray(gridElement.settings.custom_template.areas) || gridElement.settings.custom_template.areas.length === 0) {
            gridElement.settings.custom_template.areas = Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : [];
        } else {
            gridElement.settings.custom_template.areas = gridElement.settings.custom_template.areas.slice();
        }

        const targetAreasLength = gridElement.settings.custom_template.areas.length;
        for (let i = 0; i < targetAreasLength; i++) {
            if (typeof gridElement.children[i] === 'undefined') {
                gridElement.children[i] = null;
            }
        }

        const confirmMessage = settings.confirmMessage || (settings.removeCell
            ? `Delete Cell ${cellIndex + 1} (including its content)?`
            : `Delete widget from Cell ${cellIndex + 1}?`);

        if (!settings.skipConfirm) {
            const confirmed = window.confirm(confirmMessage);
            if (!confirmed) {
                console.log('❌ Deletion cancelled by user');
                return false;
            }
        }

        if (typeof settings.confirmCallback === 'function') {
            try {
                settings.confirmCallback({
                    gridId: gridElement.id,
                    cellIndex,
                    triggerSource: settings.triggerSource,
                    mode: settings.removeCell ? 'remove-cell' : 'clear-content'
                });
            } catch (callbackError) {
                console.warn('⚠️ Confirm callback threw error:', callbackError);
            }
        }

        self.isGridCellDeleting = true;

        const updateStructure = (key) => {
            if (!gridElement.settings || typeof gridElement.settings !== 'object') {
                return;
            }
            if (!gridElement.settings[key]) {
                return;
            }
            const structureArray = normalizeToArray(gridElement.settings[key]);
            if (settings.removeCell) {
                if (cellIndex < structureArray.length) {
                    structureArray.splice(cellIndex, 1);
                }
            } else {
                structureArray[cellIndex] = null;
            }
            gridElement.settings[key] = structureArray;
        };

        const logContext = {
            cellIndex,
            triggerSource: settings.triggerSource,
            mode: settings.removeCell ? 'remove-cell' : 'clear-content'
        };

        console.log('🗑️ Grid cell delete handler started', logContext);

        if (settings.removeCell) {
            if (cellIndex < gridElement.children.length) {
                console.log('🔍 Before delete (children):', gridElement.children);
                gridElement.children.splice(cellIndex, 1);
                console.log('🔍 After delete (children):', gridElement.children);
            }
        } else {
            if (gridElement.children[cellIndex]) {
                console.log('🔍 Clearing widget only for cell', cellIndex);
                gridElement.children[cellIndex] = null;
            } else {
                console.log('ℹ️ Grid cell already empty', logContext);
            }
        }

        updateStructure('_children');
        updateStructure('children');

        if (settings.removeCell) {
            let currentAreas = [];
            if (Array.isArray(gridElement.settings.custom_template.areas) && gridElement.settings.custom_template.areas.length > 0) {
                currentAreas = gridElement.settings.custom_template.areas.slice();
            } else {
                currentAreas = [];
            }

            if (cellIndex < currentAreas.length) {
                currentAreas.splice(cellIndex, 1);
                gridElement.settings.custom_template.areas = currentAreas;
                console.log('🧩 Updated custom areas after delete:', currentAreas);
            } else {
                console.warn('⚠️ Cell index exceeds current areas length', {cellIndex, areasLength: currentAreas.length});
            }
        }

        const $oldElement = $(`.probuilder-element[data-id="${gridElement.id}"]`);
        const insertBefore = $oldElement.next()[0];
        $oldElement.remove();

        self.renderElement(gridElement, insertBefore);
        self.saveHistory();

        setTimeout(() => {
            self.isGridCellDeleting = false;
        }, 0);

        console.log('✅ Grid cell delete handler finished', logContext);
        return true;
    },

    /**
         * Start container column resize - similar to grid cell resize
         * Allows resizing from all directions: top, left, right, bottom, and corners
         */
        startContainerColumnResize: function(containerElement, columnIndex, direction, e) {
            const self = this;
            
            console.log('🎯 Starting container column resize:', containerElement.id, 'column:', columnIndex, 'direction:', direction);
            
            const $container = $(`.probuilder-element[data-id="${containerElement.id}"]`);
            const $column = $container.find(`.probuilder-column[data-column-index="${columnIndex}"]`);
            
            if ($column.length === 0) {
                console.error('Column not found:', columnIndex);
                return;
            }
            
            // Get column and container dimensions
            const columnRect = $column[0].getBoundingClientRect();
            const containerRect = $container[0].getBoundingClientRect();
            
            const startWidth = columnRect.width;
            const startHeight = columnRect.height;
            const startTop = columnRect.top - containerRect.top;
            const startLeft = columnRect.left - containerRect.left;
            
            const startX = e.clientX;
            const startY = e.clientY;
            
            // Store original styles
            const originalPosition = $column.css('position');
            const originalZIndex = $column.css('z-index');
            const originalWidth = $column.css('width');
            const originalHeight = $column.css('height');
            
            // Get container settings
            const settings = containerElement.settings;
            const columnHeights = settings.column_heights || [];
            const columnPaddings = settings.column_paddings || [];
            
            // Track final dimensions
            let finalWidth = startWidth;
            let finalHeight = startHeight;
            let finalTop = startTop;
            let finalLeft = startLeft;
            let isResizing = false;
            
            console.log('Start:', {width: startWidth, height: startHeight});
            
            // Convert to absolute positioning for smooth resize
            $column.css({
                'position': 'absolute',
                'top': startTop + 'px',
                'left': startLeft + 'px',
                'width': startWidth + 'px',
                'height': startHeight + 'px',
                'z-index': '1000',
                'box-shadow': '0 0 20px rgba(0,124,186,0.4)',
                'border-color': '#007cba',
                'transition': 'none'
            });
            
            const cursorMap = {
                'top': 'row-resize',
                'left': 'col-resize',
                'right': 'col-resize',
                'bottom': 'row-resize',
                'both': 'nwse-resize'
            };
            $('body').css('cursor', cursorMap[direction] || 'default');
            
            // Add size indicator
            const $indicator = $('<div class="column-resize-indicator" style="position: fixed; top: 10px; right: 10px; background: rgba(0,124,186,0.9); color: white; padding: 10px 15px; border-radius: 4px; font-size: 12px; z-index: 99999; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>');
            $('body').append($indicator);
            
            // Mouse move - SMOOTH pixel-by-pixel resize
            $(document).on('mousemove.columnResize', function(moveEvent) {
                moveEvent.preventDefault();
                moveEvent.stopPropagation();
                
                const deltaX = moveEvent.clientX - startX;
                const deltaY = moveEvent.clientY - startY;
                
                // Mark that we're actually resizing (mouse moved)
                if (Math.abs(deltaX) > 2 || Math.abs(deltaY) > 2) {
                    isResizing = true;
                }
                
                let newWidth = startWidth;
                let newHeight = startHeight;
                let newLeft = startLeft;
                let newTop = startTop;
                
                if (direction === 'top') {
                    // Resize from top side - bottom edge stays fixed
                    newHeight = Math.max(50, startHeight - deltaY);
                    newTop = startTop + (startHeight - newHeight);
                    $column.css({
                        'height': newHeight + 'px',
                        'top': newTop + 'px'
                    });
                    finalHeight = newHeight;
                    finalTop = newTop;
                } else if (direction === 'bottom' || direction === 'both') {
                    // Resize from bottom side - top edge stays fixed
                    newHeight = Math.max(50, startHeight + deltaY);
                    $column.css('height', newHeight + 'px');
                    finalHeight = newHeight;
                    finalTop = startTop;
                }
                
                if (direction === 'left') {
                    // Resize from left side - right edge stays fixed
                    newWidth = Math.max(50, startWidth - deltaX);
                    newLeft = startLeft + (startWidth - newWidth);
                    $column.css({
                        'width': newWidth + 'px',
                        'left': newLeft + 'px'
                    });
                    finalWidth = newWidth;
                    finalLeft = newLeft;
                } else if (direction === 'right' || direction === 'both') {
                    // Resize from right side - left edge stays fixed
                    newWidth = Math.max(50, startWidth + deltaX);
                    $column.css('width', newWidth + 'px');
                    finalWidth = newWidth;
                    finalLeft = startLeft;
                }
                
                // Update indicator
                const widthPercent = Math.round((newWidth / containerRect.width) * 100);
                
                $indicator.html(`
                    <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                    <div>Width: ${Math.round(newWidth)}px (${widthPercent}%)</div>
                    <div>Height: ${Math.round(newHeight)}px</div>
                    <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Release to apply</div>
                `);
            });
            
            // Mouse up - Finalize resize and update settings
            $(document).on('mouseup.columnResize', function(upEvent) {
                upEvent.preventDefault();
                upEvent.stopPropagation();
                
                $(document).off('.columnResize');
                $indicator.remove();
                $('body').css('cursor', '');
                
                console.log('Finalizing column resize:', {
                    finalWidth,
                    finalHeight,
                    direction,
                    isResizing
                });
                
                // Calculate scale factors for column width adjustments
                const scaleX = finalWidth / startWidth;
                const scaleY = finalHeight / startHeight;
                
                // Update column heights in settings if height changed
                if ((direction === 'top' || direction === 'bottom' || direction === 'both') && isResizing) {
                    if (!settings.column_heights) {
                        settings.column_heights = [];
                    }
                    settings.column_heights[columnIndex] = Math.round(finalHeight);
                    console.log('Updated column height:', columnIndex, '=', Math.round(finalHeight));
                }
                
                // Update column widths if width changed
                if ((direction === 'left' || direction === 'right' || direction === 'both') && isResizing) {
                    const columnsCount = parseInt(settings.columns || '1');
                    const columnWidths = settings.column_widths ? settings.column_widths.split(',').map(w => parseFloat(w.trim())) : [];
                    
                    // Ensure we have width values for all columns
                    while (columnWidths.length < columnsCount) {
                        columnWidths.push(100 / columnsCount);
                    }
                    
                    // Calculate new width percentage
                    const oldWidthPercent = columnWidths[columnIndex];
                    const newWidthPercent = (finalWidth / containerRect.width) * 100;
                    const widthDifference = newWidthPercent - oldWidthPercent;
                    
                    console.log('Width adjustment:', {
                        oldWidthPercent,
                        newWidthPercent,
                        widthDifference
                    });
                    
                    // Update the resized column width
                    columnWidths[columnIndex] = newWidthPercent;
                    
                    // Distribute the difference among other columns
                    const otherColumns = [];
                    for (let i = 0; i < columnsCount; i++) {
                        if (i !== columnIndex) {
                            otherColumns.push(i);
                        }
                    }
                    
                    if (otherColumns.length > 0 && widthDifference !== 0) {
                        const adjustmentPerColumn = -widthDifference / otherColumns.length;
                        otherColumns.forEach(idx => {
                            columnWidths[idx] = Math.max(5, columnWidths[idx] + adjustmentPerColumn);
                        });
                    }
                    
                    // Normalize to ensure total is 100%
                    const total = columnWidths.reduce((sum, w) => sum + w, 0);
                    const normalized = columnWidths.map(w => (w / total) * 100);
                    
                    settings.column_widths = normalized.map(w => w.toFixed(2)).join(',');
                    console.log('Updated column widths:', settings.column_widths);
                }
                
                // Reset column to use grid layout
                $column.css({
                    'position': '',
                    'top': '',
                    'left': '',
                    'width': '',
                    'height': '',
                    'z-index': '',
                    'box-shadow': '',
                    'transition': ''
                });
                
                // Re-render the container to apply changes
                self.updateElementPreview(containerElement);
                
                // Save changes
                self.saveData();
                
                console.log('✅ Container column resize complete');
            });
        },
        
        /**
         * Start widget resize - for all regular widgets
         */
        startWidgetResize: function(element, $element, direction, e) {
            const self = this;
            
            console.log('🎯 Starting widget resize:', element.id, 'direction:', direction);
            
            const $preview = $element.find('.probuilder-element-preview');
            const startWidth = $preview.outerWidth();
            const startHeight = $preview.outerHeight();
            const startX = e.clientX;
            const startY = e.clientY;
            
            console.log('Start dimensions:', {width: startWidth, height: startHeight});
            
            // Add visual feedback
            $element.addClass('is-resizing');
            
            // Set cursor based on direction
            const cursorMap = {
                'top': 'ns-resize',
                'bottom': 'ns-resize',
                'left': 'ew-resize',
                'right': 'ew-resize',
                'both': 'nwse-resize'
            };
            $('body').css('cursor', cursorMap[direction] || 'default');
            
            // Add size indicator - Elementor style
            const $indicator = $('<div class="probuilder-resize-indicator" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.8); color: white; padding: 8px 16px; border-radius: 3px; font-size: 13px; z-index: 99999; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif; font-weight: 500; pointer-events: none;"></div>');
            $('body').append($indicator);
            
            // Store original dimensions in settings if not already set
            if (!element.settings._width) {
                element.settings._width = '100%';
            }
            if (!element.settings._height) {
                element.settings._height = 'auto';
            }
            
            // Mouse move - live resizing
            $(document).on('mousemove.widgetResize', function(moveEvent) {
                moveEvent.preventDefault();
                moveEvent.stopPropagation();
                
                const deltaX = moveEvent.clientX - startX;
                const deltaY = moveEvent.clientY - startY;
                
                let newWidth = startWidth;
                let newHeight = startHeight;
                
                // Calculate new dimensions based on direction
                if (direction === 'right' || direction === 'both') {
                    newWidth = Math.max(20, startWidth + deltaX);
                } else if (direction === 'left') {
                    newWidth = Math.max(20, startWidth - deltaX);
                }
                
                if (direction === 'bottom' || direction === 'both') {
                    newHeight = Math.max(20, startHeight + deltaY);
                } else if (direction === 'top') {
                    newHeight = Math.max(20, startHeight - deltaY);
                }
                
                // Apply the new dimensions to both element and preview
                if (direction === 'left' || direction === 'right' || direction === 'both') {
                    $element.css('width', newWidth + 'px');
                    $preview.css('width', newWidth + 'px');
                }
                
                if (direction === 'top' || direction === 'bottom' || direction === 'both') {
                    $element.css('height', newHeight + 'px');
                    $preview.css('height', newHeight + 'px');
                }
                
                // Update indicator - Elementor style (simple W × H)
                $indicator.text(`${Math.round(newWidth)} × ${Math.round(newHeight)}`);
            });
            
            // Mouse up - save dimensions
            $(document).on('mouseup.widgetResize', function(upEvent) {
                upEvent.preventDefault();
                upEvent.stopPropagation();
                
                $(document).off('.widgetResize');
                $indicator.remove();
                $('body').css('cursor', '');
                $element.removeClass('is-resizing');
                
                // Calculate final dimensions
                const deltaX = upEvent.clientX - startX;
                const deltaY = upEvent.clientY - startY;
                
                let finalWidth = startWidth;
                let finalHeight = startHeight;
                
                if (direction === 'right' || direction === 'both') {
                    finalWidth = Math.max(20, startWidth + deltaX);
                } else if (direction === 'left') {
                    finalWidth = Math.max(20, startWidth - deltaX);
                }
                
                if (direction === 'bottom' || direction === 'both') {
                    finalHeight = Math.max(20, startHeight + deltaY);
                } else if (direction === 'top') {
                    finalHeight = Math.max(20, startHeight - deltaY);
                }
                
                // Save to element settings and apply to both element and preview
                if (direction === 'left' || direction === 'right' || direction === 'both') {
                    element.settings._width = Math.round(finalWidth) + 'px';
                    $element.css('width', element.settings._width);
                    $preview.css('width', element.settings._width);
                }
                
                if (direction === 'top' || direction === 'bottom' || direction === 'both') {
                    element.settings._height = Math.round(finalHeight) + 'px';
                    $element.css('height', element.settings._height);
                    $preview.css('height', element.settings._height);
                }
                
                // Save to history
                self.saveHistory();
                
                console.log('✅ Widget resize complete:', {
                    width: element.settings._width,
                    height: element.settings._height
                });
            });
        },
        
        /**
         * Add new row to container
         */
        addRowToContainer: function(containerId) {
            try {
                console.log('Adding new row to container:', containerId);
                
                const containerElement = this.elements.find(e => e.id === containerId);
                if (!containerElement) {
                    console.error('Container not found:', containerId);
                    alert('Error: Container not found');
                    return;
                }
                
                // Enable rows if not already enabled
                if (!containerElement.settings.enable_rows) {
                    containerElement.settings.enable_rows = 'yes';
                }
                
                // Initialize rows array if not exists
                if (!containerElement.settings.rows) {
                    containerElement.settings.rows = [];
                }
                
                // Add new row with default settings
                const newRow = {
                    row_columns: '2',
                    row_columns_tablet: '2',
                    row_columns_mobile: '1',
                    row_gap: 20
                };
                
                containerElement.settings.rows.push(newRow);
                
                console.log('New row added. Total rows:', containerElement.settings.rows.length);
                
                // Re-render the container with proper handlers
                this.updateContainerWithChildren(containerElement);
                
                // Show success message
                this.showNotification('New row added to container!', 'success');
                
                return true;
            } catch (error) {
                console.error('❌ Error adding row to container:', error);
                alert('Error: ' + error.message);
                return false;
            }
        },
        
        /**
         * Reinitialize sidebar widgets draggable
         */
        reinitializeSidebarWidgets: function() {
            const self = this;
            
            try {
                console.log('🔧 Reinitializing sidebar widgets...');
                
                // Make sidebar widgets draggable
                $('.probuilder-widget').each(function() {
                    const $widget = $(this);
                    const widgetName = $widget.data('widget');
                    
                    // Destroy existing draggable if it exists
                    if ($widget.data('ui-draggable')) {
                        $widget.draggable('destroy');
                    }
                    
                    // Recreate draggable
                    $widget.draggable({
                        helper: function() {
                            return $(this).clone().css({
                                'width': $(this).width(),
                                'opacity': 0.8,
                                'z-index': 10000
                            });
                        },
                        appendTo: 'body',
                        zIndex: 10000,
                        cursor: 'move',
                        revert: 'invalid',
                        revertDuration: 200,
                        start: function(event, ui) {
                            console.log('Started dragging widget:', widgetName);
                            $('.probuilder-element-placeholder').show();
                            $('.probuilder-column').css('outline', '2px dashed #344047');
                            $('#probuilder-preview-area, .probuilder-column').addClass('drop-ready');
                        },
                        stop: function(event, ui) {
                            console.log('Stopped dragging widget');
                            $('.probuilder-column').css('outline', '');
                            $('#probuilder-preview-area, .probuilder-column').removeClass('drop-ready');
                            // Remove the helper clone to prevent it from sticking
                            $(ui.helper).remove();
                            $('body').css('cursor', '');
                        }
                    });
                });
                
                console.log('✅ Sidebar widgets reinitialized successfully');
            } catch (error) {
                console.error('❌ Error reinitializing sidebar widgets:', error);
            }
        },
        
        /**
         * Reinitialize preview area droppable
         */
        reinitializePreviewArea: function() {
            const self = this;
            
            try {
                console.log('🔧 Reinitializing preview area...');
                
                const $previewArea = $('#probuilder-preview-area');
                
                // Destroy existing droppable if it exists
                if ($previewArea.data('ui-droppable')) {
                    $previewArea.droppable('destroy');
                }
                
                // Recreate preview area droppable
                $previewArea.droppable({
                    accept: '.probuilder-widget',
                    tolerance: 'pointer',
                    greedy: false,
                    drop: function(event, ui) {
                        const $target = $(event.target);
                        if ($target.hasClass('probuilder-column') || $target.closest('.probuilder-column').length > 0) {
                            console.log('🎯 Drop intercepted by column, skipping preview area handler');
                            return;
                        }
                        
                        const widgetName = ui.draggable.data('widget');
                        console.log('🎯 Dropped NEW widget on canvas:', widgetName);
                        
                        if (widgetName) {
                            const dropY = event.pageY;
                            const $elements = $previewArea.children('.probuilder-element');
                            let insertIndex = $elements.length;
                            
                            $elements.each(function(index) {
                                const $el = $(this);
                                const elTop = $el.offset().top;
                                const elMiddle = elTop + ($el.outerHeight() / 2);
                                
                                if (dropY < elMiddle && insertIndex === $elements.length) {
                                    insertIndex = index;
                                    return false;
                                }
                            });
                            
                            console.log('Inserting at index:', insertIndex);
                            self.addElementAtPosition(widgetName, insertIndex);
                        }
                    },
                    over: function(event, ui) {
                        if (ui.draggable.hasClass('probuilder-widget')) {
                            $(this).addClass('probuilder-drop-active');
                        }
                    },
                    out: function() {
                        $(this).removeClass('probuilder-drop-active');
                    }
                });
                
                console.log('✅ Preview area reinitialized successfully');
            } catch (error) {
                console.error('❌ Error reinitializing preview area:', error);
            }
        },
        
        /**
         * Get default settings for widget
         */
        getDefaultSettings: function(widget) {
            const settings = {};
            
            if (widget.controls) {
                Object.keys(widget.controls).forEach(key => {
                    const control = widget.controls[key];
                    if (control.default !== undefined) {
                        settings[key] = control.default;
                    }
                });
            }
            
            return settings;
        },
        
        /**
         * Render single element
         */
        renderElement: function(element, insertBefore) {
            try {
                console.log('Rendering element:', element.id, element.widgetType);
                
                const widget = this.widgets.find(w => w.name === element.widgetType);
                if (!widget) {
                    console.error('Widget not found for element:', element.widgetType);
                    return;
                }
                
                if (element.widgetType === 'grid-layout') {
                    this.ensureGridElementStructure(element);
                }
                
                const preview = this.generatePreview(element);
                console.log('Preview generated for:', element.id);
                
                // Add column selector for containers
                const columnSelector = element.widgetType === 'container' ? `
                    <div class="probuilder-container-controls">
                        <span class="probuilder-container-controls-label">Columns:</span>
                        <div class="probuilder-column-selector">
                            ${[1,2,3,4,5,6,7,8,9,10,11,12].map(num => 
                                `<button class="probuilder-column-btn ${(element.settings.columns || '1') == num ? 'active' : ''}" data-columns="${num}">${num}</button>`
                            ).join('')}
                        </div>
                        <div class="probuilder-row-controls" style="margin-top: 10px;">
                            <button class="probuilder-add-row-btn" data-element-id="${element.id}" style="
                                background: #007cba;
                                color: white;
                                border: none;
                                padding: 5px 10px;
                                border-radius: 3px;
                                font-size: 12px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 5px;
                            ">
                                <i class="dashicons dashicons-plus-alt2" style="font-size: 14px;"></i>
                                Add Row
                            </button>
                        </div>
                    </div>
                ` : '';
                
                        const $element = $(`
                    <div class="probuilder-element" data-id="${element.id}" data-widget="${element.widgetType}">
                        ${columnSelector}
                        <div class="probuilder-element-controls">
                            <span class="probuilder-element-drag">
                                <i class="dashicons dashicons-move"></i>
                            </span>
                            <span class="probuilder-element-name">${widget.title}</span>
                            <div class="probuilder-element-actions">
                                <button class="probuilder-element-edit" title="Edit">
                                    <i class="dashicons dashicons-edit"></i>
                                </button>
                                <button class="probuilder-element-duplicate" title="Duplicate">
                                    <i class="dashicons dashicons-admin-page"></i>
                                </button>
                                <button class="probuilder-element-delete" title="Delete">
                                    <i class="dashicons dashicons-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="probuilder-element-preview">
                            ${preview}
                        </div>
                        <div class="probuilder-element-resize-handles">
                            <div class="probuilder-widget-resize-handle probuilder-resize-n" data-direction="top" title="Resize height"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-e" data-direction="right" title="Resize width"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-s" data-direction="bottom" title="Resize height"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-w" data-direction="left" title="Resize width"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-ne" data-direction="both" title="Resize both"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-se" data-direction="both" title="Resize both"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-sw" data-direction="both" title="Resize both"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-nw" data-direction="both" title="Resize both"></div>
                        </div>
                        <div class="probuilder-element-add-below">
                            <button class="probuilder-add-below-btn" title="Add Element Below">
                                <i class="dashicons dashicons-plus-alt2"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                console.log('Element HTML created');
                
                const self = this;
                
                // Apply saved dimensions if they exist
                if (element.settings._width && element.settings._width !== '100%') {
                    $element.css('width', element.settings._width);
                    $element.find('.probuilder-element-preview').css('width', element.settings._width);
                }
                // Only apply height for non-container widgets
                if (element.settings._height && element.settings._height !== 'auto' && 
                    element.widgetType !== 'container' && element.widgetType !== 'grid-layout') {
                    $element.css('height', element.settings._height);
                    $element.find('.probuilder-element-preview').css('height', element.settings._height);
                }
                
                // Apply spacing and common styles to preview container
                const spacingStyles = this.getSpacingStyles(element.settings);
                const commonStyles = this.getCommonInlineStyles(element.settings);
                const combinedInitStyles = [spacingStyles, commonStyles].filter(Boolean).join('; ');
                if (combinedInitStyles) {
                    const $previewInit = $element.find('.probuilder-element-preview');
                    $previewInit.attr('style', (($previewInit.attr('style') || '') + '; ' + combinedInitStyles).trim());
                }

                // Apply responsive visibility initially
                this.applyResponsiveVisibility(element, $element);
                
                // Edit button
                $element.find('.probuilder-element-edit').on('click', function(e) {
                    e.stopPropagation();
                    console.log('Edit button clicked for:', element.id);
                    self.openSettings(element);
                });
                
                // Duplicate button
                $element.find('.probuilder-element-duplicate').on('click', function(e) {
                    e.stopPropagation();
                    console.log('Duplicate button clicked for:', element.id);
                    self.duplicateElement(element);
                });
                
                // Delete button - no confirmation
                $element.find('.probuilder-element-delete').on('click', function(e) {
                    e.stopPropagation();
                    console.log('Delete button clicked for:', element.id);
                    self.deleteElement(element);
                });
                
                // Add below button - use widget picker
                $element.find('.probuilder-add-below-btn').on('click', function(e) {
                    e.stopPropagation();
                    console.log('Add below button clicked for:', element.id);
                    self.showWidgetPicker(element);
                });
                
                // Widget resize handles
                $element.find('.probuilder-widget-resize-handle').on('mousedown', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const direction = $(this).data('direction');
                    console.log('🎯 Starting widget resize:', element.id, 'direction:', direction);
                    self.startWidgetResize(element, $element, direction, e);
                });
                
                // Click to select
                $element.on('click', function(e) {
                    if (!$(e.target).closest('.probuilder-element-actions').length && 
                        !$(e.target).closest('.probuilder-column-btn').length &&
                        !$(e.target).closest('.probuilder-drop-zone').length &&
                        !$(e.target).closest('.probuilder-add-below-btn').length &&
                        !$(e.target).closest('.probuilder-widget-resize-handle').length) {
                        console.log('Element clicked, selecting:', element.id);
                        self.selectElement(element);
                    }
                });
                
                // Container column selector and drop zones
                if (element.widgetType === 'container') {
                    $element.find('.probuilder-column-btn').on('click', function(e) {
                        e.stopPropagation();
                        const columns = $(this).data('columns');
                        console.log('Changing container columns to:', columns);
                        
                        // Update element settings
                        element.settings.columns = columns.toString();
                        
                        // Update active state
                        $(this).siblings().removeClass('active');
                        $(this).addClass('active');
                        
                        // Re-render the container
                        self.updateElementPreview(element);
                    });
                    
                    // Add row button handler
                    $element.find('.probuilder-add-row-btn').on('click', function(e) {
                        e.stopPropagation();
                        console.log('Adding new row to container:', element.id);
                        self.addRowToContainer(element.id);
                    });
                    
                    // Attach click handlers to drop zones immediately
                    setTimeout(function() {
                        console.log('🔧 Attaching initial drop zone handlers for container:', element.id);
                        
                        const $dropZones = $element.find('.probuilder-drop-zone');
                        console.log('Found', $dropZones.length, 'drop zones');
                        
                        $dropZones.each(function() {
                            const $zone = $(this);
                            const containerId = $zone.data('container-id');
                            const columnIndex = $zone.data('column-index');
                            
                            $zone.off('click').on('click', function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                if (self.isGridCellResizing) {
                                    console.log('⏸️ Initial drop zone click ignored - grid cell resizing');
                                    return false;
                                }
                                console.log('✅ Initial drop zone clicked:', containerId, 'column:', columnIndex);
                                self.showWidgetTemplateSelector(containerId, columnIndex);
                                return false;
                            });
                        });
                        
                        console.log('✅ Initial drop zone handlers attached');
                    }, 100);
                    
                    // Container column resize handles - similar to grid layout
                    setTimeout(function() {
                        console.log('🔧 Attaching container column resize handlers for:', element.id);
                        
                        const $resizeHandles = $element.find('.column-resize-handle');
                        console.log('Found', $resizeHandles.length, 'resize handles');
                        
                        // Remove any existing handlers first
                        $element.off('mousedown.columnResize', '.column-resize-handle');
                        
                        // Attach using delegation so it survives re-renders
                        $element.on('mousedown.columnResize', '.column-resize-handle', function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            const columnIndex = $(this).data('column-index');
                            const direction = $(this).data('direction');
                            console.log('🎯 Container column resize started:', columnIndex, direction);
                            
                            // Get fresh reference to container element
                            const containerElement = self.elements.find(el => el.id === element.id);
                            if (!containerElement) {
                                console.error('Container element not found:', element.id);
                                return;
                            }
                            
                            // Prevent any click events from bubbling
                            $(document).on('click.columnResizePrevent', function(clickEvent) {
                                clickEvent.preventDefault();
                                clickEvent.stopPropagation();
                                $(document).off('click.columnResizePrevent');
                            });
                            
                            self.startContainerColumnResize(containerElement, columnIndex, direction, e);
                        });
                        
                        console.log('✅ Container column resize handlers attached');
                    }, 100);
                }
                
                // Grid Layout drop zones and resize handles
                if (element.widgetType === 'grid-layout') {
                    setTimeout(function() {
                        console.log('🎨 Attaching grid drop zone handlers for:', element.id);
                        
                        const $gridDropZones = $element.find('.probuilder-drop-zone');
                        console.log('Found', $gridDropZones.length, 'grid cells');
                        
                        $gridDropZones.each(function() {
                                const $zone = $(this);
                                const gridId = $zone.data('grid-id');
                                const cellIndex = parseInt($zone.attr('data-cell-index'), 10);
                            const $gridCell = $zone.closest('.grid-cell');
                            const toolbarHeight = $gridCell.find('.grid-cell-toolbar').outerHeight(true) || 0;
                            $gridCell.css('padding-top', toolbarHeight + 8);
                            
                            // Make grid cells droppable
                            $zone.droppable({
                                accept: '.probuilder-widget',
                                tolerance: 'pointer',
                                hoverClass: 'probuilder-drop-hover',
                                greedy: true, // Prevent parent elements from also handling the drop
                                drop: function(event, ui) {
                                    event.stopPropagation();
                                    event.preventDefault();
                                    self.isNestedDropInProgress = true;
                                    const finishNestedDrop = () => {
                                        setTimeout(() => {
                                            self.isNestedDropInProgress = false;
                                        }, 0);
                                    };
                                    const widgetName = ui.draggable.data('widget');
                                    console.log('✅ Widget dropped in grid cell:', widgetName, 'grid:', gridId, 'cell:', cellIndex);
                                    
                                    // Find the grid element
                                    const gridElement = self.elements.find(e => e.id === gridId);
                                    if (!gridElement) {
                                        console.error('Grid element not found:', gridId);
                                        finishNestedDrop();
                                        return;
                                    }
                                    
                                    // Initialize children array if needed
                                    if (!gridElement.children) {
                                        gridElement.children = [];
                                    }
                                    
                                    // Create new element
                                    const newElement = {
                                        id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                                        widgetType: widgetName,
                                        settings: {},
                                        children: []
                                    };
                                    
                                    // Add to the specific cell
                                    gridElement.children[cellIndex] = newElement;
                                    
                                    console.log('Grid children updated:', gridElement.children);
                                    
                                    // Re-render the grid
                                    self.updateElementPreview(gridElement);
                                    
                                    // Open settings for the new element
                                    setTimeout(() => {
                                        self.openSettings(newElement);
                                    }, 100);
                                    
                                    // Save to history
                                    self.saveHistory();
                                    finishNestedDrop();
                                }
                            });
                            
                            // Click handler for empty cells - ONLY trigger on "Drop widgets here" text
                            // VERSION: 6.0.0 - Restricted click area
                            if (!$zone.hasClass('has-content')) {
                                // Remove click from entire cell
                                $zone.off('click');
                                
                                // Add click ONLY to the empty content area
                                const $emptyContent = $zone.find('.grid-cell-empty-content');
                                const placeholderRect = this.getBoundingClientRect ? this.getBoundingClientRect() : null;

                                if (placeholderRect) {
                                    const toolbarEl = $zone.find('.grid-cell-toolbar')[0];
                                    const toolbarRect = toolbarEl ? toolbarEl.getBoundingClientRect() : null;
                                    if (toolbarRect) {
                                        const overlapBuffer = 8;
                                        const newWidth = Math.max(placeholderRect.width - (toolbarRect.width + overlapBuffer), 0);
                                        const newHeight = Math.max(placeholderRect.height - (toolbarRect.height + overlapBuffer), 0);

                                        $emptyContent.css({
                                            maxWidth: newWidth + 'px',
                                            maxHeight: newHeight + 'px',
                                            overflow: 'hidden',
                                            display: 'inline-flex',
                                            alignItems: 'center',
                                            justifyContent: 'center'
                                        });
                                    }
                                }

                                $emptyContent.off('click').on('click', function(e) {
                                    e.stopPropagation();
                                    e.preventDefault();

                                    if (self.isGridCellDeleting) {
                                        console.log('⏸️ Skipping widget selector - grid cell delete in progress');
                                        return false;
                                    }

                                    if (self.isGridCellResizing) {
                                        console.log('⏸️ Skipping widget selector - grid cell resizing in progress');
                                        return false;
                                    }

                                    console.log('✅ VERSION 6.0.0 - Empty content area clicked:', gridId, 'cell:', cellIndex);
                                    self.showWidgetTemplateSelector(gridId, cellIndex, true); // true = isGrid
                                    return false;
                                });
                            }
                        });
                        
                        // Resize handles for grid cells - using event delegation
                        const $resizeHandles = $element.find('.grid-resize-handle');
                        console.log('Found', $resizeHandles.length, 'resize handles');
                        
                        // Remove any existing handlers first
                        $element.off('mousedown.gridResize', '.grid-resize-handle');
                        
                        // Attach using delegation so it survives re-renders
                        $element.on('mousedown.gridResize', '.grid-resize-handle', function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            const cellIndex = parseInt($(this).attr('data-cell-index'), 10);
                            const direction = $(this).data('direction');
                            console.log('🎯 Grid resize started:', cellIndex, direction);
                            
                            // Get fresh reference to grid element
                            const gridElement = self.elements.find(el => el.id === element.id);
                            if (!gridElement) {
                                console.error('Grid element not found!');
                                return;
                            }
                            
                            // Prevent any click events from bubbling
                            $(document).on('click.gridResizePrevent', function(clickEvent) {
                                clickEvent.preventDefault();
                                clickEvent.stopPropagation();
                                $(document).off('click.gridResizePrevent');
                            });
                            
                            self.startGridCellResize(gridElement, cellIndex, direction, e);
                        });
                        
                        // Use event delegation for better reliability
                        // VERSION: 5.0.0 - Delegated Event Handlers + Cell Delete
                        console.log('🔧 VERSION 5.0.0 - Setting up delegated event handlers for grid:', element.id);
                        
                        const gridElement = self.elements.find(e => e.id === element.id);
                        
                        // Grid Cell Toolbar Buttons - Handle all toolbar button clicks
                        // Stop all toolbar button clicks from bubbling to cell
                        $element.off('click.toolbarBtn', '.grid-cell-toolbar button')
                            .on('click.toolbarBtn', '.grid-cell-toolbar button', function(e) {
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                            });
                        
                        // Add Content Button - Open widget selector
                        $element.off('click.addContent', '.grid-cell-toolbar .add-content-btn')
                            .on('click.addContent', '.grid-cell-toolbar .add-content-btn', function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                e.stopImmediatePropagation();
                                
                                const cellIndexAttr = $(this).attr('data-cell-index');
                                const cellIndex = parseInt(cellIndexAttr, 10);
                                if (Number.isNaN(cellIndex)) {
                                    console.error('❌ Invalid cell index on add button:', cellIndexAttr);
                                    return false;
                                }
                                
                                console.log('➕ Add content button clicked for cell:', cellIndex);
                                self.showWidgetTemplateSelector(element.id, cellIndex, true); // true = isGrid
                                return false;
                            });
                        
                        // Grid Cell Delete Button - Delete entire cell
                        // VERSION: 7.0.0 - Enhanced debugging
                        console.log('🔧 Setting up cell delete button handlers for grid:', element.id);
                        
                        $element.off('click.cellDelete', '.grid-cell-delete-btn')
                            .on('click.cellDelete', '.grid-cell-delete-btn', function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                e.stopImmediatePropagation();

                                const cellIndexAttr = $(this).attr('data-cell-index');
                                const cellIndex = parseInt(cellIndexAttr, 10);
                                if (Number.isNaN(cellIndex)) {
                                    console.error('❌ Invalid cell index on delete button:', cellIndexAttr);
                                    return false;
                                }

                                const $gridLayout = $(this).closest('.probuilder-grid-layout');
                                const gridIdAttr = $(this).attr('data-grid-id') || $(this).closest('.grid-cell-toolbar').attr('data-grid-id') || element.id;
                                const domPattern = $gridLayout.attr('data-grid-pattern') || null;
                                const domAreas = $gridLayout.find('.grid-cell').map(function() {
                                    return $(this).attr('data-original-area') || null;
                                }).get();
                                const activeGridElement = self.elements.find(el => el && el.id === gridIdAttr) || gridElement;

                                console.log('🗑️ Grid cell delete requested', {cellIndex, gridIdAttr, triggerSource: 'toolbar-button'});

                                const deleted = self.handleGridCellDelete(activeGridElement, cellIndex, {
                                    triggerSource: 'toolbar-button',
                                    skipConfirm: false,
                                    domPattern,
                                    domAreas
                                });

                                if (!deleted) {
                                    console.warn('⚠️ Grid cell delete helper returned false', {cellIndex, gridIdAttr});
                                }

                                return false;
                            });
                        
                        // Debug: Check if delete buttons exist
                        const $deleteButtons = $element.find('.grid-cell-delete-btn');
                        console.log('🔍 Found', $deleteButtons.length, 'cell delete buttons');
                        $deleteButtons.each(function(i) {
                            console.log('🔍 Delete button', i, ':', $(this).attr('data-cell-index'));
                        });
                        
                        // Make grid cells sortable - DRAG AND REORDER LIKE PUZZLE
                        console.log('🧩 Initializing grid cell drag-and-drop reordering');
                        const $gridContainer = $element.find('.probuilder-grid-layout');
                        
                        if ($gridContainer.length > 0) {
                            $gridContainer.sortable({
                                items: '> .grid-cell',
                                handle: '.grid-cell-drag-handle',
                                placeholder: 'ui-sortable-placeholder grid-cell',
                                tolerance: 'pointer',
                                cursor: 'grabbing',
                                opacity: 0.7,
                                distance: 10,
                                delay: 100,
                                start: function(event, ui) {
                                    console.log('🎯 Started dragging grid cell');
                                    ui.item.addClass('ui-sortable-helper');
                                    $('body').css('cursor', 'grabbing');
                                },
                                stop: function(event, ui) {
                                    console.log('🎯 Stopped dragging grid cell');
                                    ui.item.removeClass('ui-sortable-helper');
                                    $('body').css('cursor', '');
                                },
                                update: function(event, ui) {
                                    console.log('🔄 Grid cells reordered - auto-adjusting layout');
                                    
                                    // Get new order of cells
                                    const newChildren = [];
                                    $gridContainer.find('.grid-cell').each(function(index) {
                                        const oldIndex = parseInt($(this).attr('data-cell-index'), 10);
                                        const oldChild = gridElement.children ? gridElement.children[oldIndex] : null;
                                        newChildren.push(oldChild); // Can be null for empty cells
                                        
                                        // Update cell index
                                        $(this).attr('data-cell-index', index);
                                        $(this).find('.grid-cell-drag-handle').attr('data-cell-index', index);
                                        $(this).find('.grid-cell-delete-btn').attr('data-cell-index', index);
                                    });
                                    
                                    // Update the grid element's children array
                                    gridElement.children = newChildren;
                                    
                                    // Re-render the grid with new order
                                    console.log('🔄 Re-rendering grid with new cell order');
                                    const $oldElement = $(`.probuilder-element[data-id="${gridElement.id}"]`);
                                    const insertBefore = $oldElement.next()[0];
                                    $oldElement.remove();
                                    
                                    self.renderElement(gridElement, insertBefore);
                                    self.saveHistory();
                                    
                                    self.showToast('✅ Grid cells reordered!');
                                    console.log('✅ Grid layout auto-adjusted like flexbox');
                                }
                            });
                            
                            console.log('✅ Grid cell sortable initialized');
                        }
                        
                        // Hover to show/hide toolbar - using delegation
                        $element.off('mouseenter.nestedHover mouseleave.nestedHover', '.probuilder-nested-element')
                            .on('mouseenter.nestedHover', '.probuilder-nested-element', function() {
                                $(this).find('.probuilder-nested-toolbar').show();
                            })
                            .on('mouseleave.nestedHover', '.probuilder-nested-element', function() {
                                $(this).find('.probuilder-nested-toolbar').hide();
                            });
                        
                        // Edit button click - using delegation
                        $element.off('click.nestedEdit', '.probuilder-nested-edit')
                            .on('click.nestedEdit', '.probuilder-nested-edit', function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                const $nestedEl = $(this).closest('.probuilder-nested-element');
                                const childId = $nestedEl.data('id');
                                const childElement = gridElement.children ? gridElement.children.find(c => c && c.id === childId) : null;
                                
                                if (childElement) {
                                    console.log('✏️ Edit nested element:', childId);
                                    self.openSettings(childElement);
                                }
                            });
                        
                        // Delete button click - using delegation
                        // VERSION: 6.0.0 - Enhanced debugging for widget delete
                        console.log('🔧 Setting up widget delete button handlers for grid:', element.id);
                        
                        // Use setTimeout to ensure DOM is ready
                        setTimeout(function() {
                            $element.off('click.nestedDelete', '.probuilder-nested-delete')
                                .on('click.nestedDelete', '.probuilder-nested-delete', function(e) {
                                    e.stopPropagation();
                                    e.preventDefault();

                                    const $nestedEl = $(this).closest('.probuilder-nested-element');
                                    const cellIndex = parseInt($nestedEl.closest('.grid-cell').attr('data-cell-index'), 10);
                                    const $gridLayout = $nestedEl.closest('.probuilder-grid-layout');
                                    const triggerOptions = {
                                        triggerSource: 'nested-widget-delete',
                                        skipConfirm: true,
                                        removeCell: false,
                                        domPattern: $gridLayout.attr('data-grid-pattern') || null,
                                        domAreas: $gridLayout.find('.grid-cell').map(function() {
                                            return $(this).attr('data-original-area') || null;
                                        }).get()
                                    };

                                    if (!gridElement) {
                                        console.error('❌ Grid element not found!');
                                        return;
                                    }

                                    const deleted = self.handleGridCellDelete(gridElement, cellIndex, triggerOptions);

                                    if (!deleted) {
                                        console.warn('⚠️ Nested widget delete failed', {cellIndex, triggerOptions});
                                    } else {
                                        console.log('✅ Widget deleted from grid cell', cellIndex);
                                    }
                                });
                        
                        // Debug: Check if widget delete buttons exist
                        const $widgetDeleteButtons = $element.find('.probuilder-nested-delete');
                        console.log('🔍 Found', $widgetDeleteButtons.length, 'widget delete buttons');
                        $widgetDeleteButtons.each(function(i) {
                            const $btn = $(this);
                            const $nestedEl = $btn.closest('.probuilder-nested-element');
                            console.log('🔍 Widget delete button', i, ':', $nestedEl.data('id'));
                        });
                        
                        }, 100); // End setTimeout for delete handlers
                        
                        // Widget Resize Handles - using delegation
                        // VERSION: 12.0.0 - Widget resize functionality with INSIDE positioning
                        console.log('🔧 VERSION 12.0.0 - Setting up widget resize handlers for grid:', element.id);
                        
                        // Debug: Check if resize dots exist in DOM
                        setTimeout(function() {
                            const dotCount = $element.find('.widget-resize-dot').length;
                            const tlCount = $element.find('.widget-resize-dot-tl').length;
                            const trCount = $element.find('.widget-resize-dot-tr').length;
                            const brCount = $element.find('.widget-resize-dot-br').length;
                            const blCount = $element.find('.widget-resize-dot-bl').length;
                            
                            console.log('🔍 VERSION 14.0.0 - Resize Dot Debug:', {
                                total: dotCount,
                                topLeft: tlCount,
                                topRight: trCount,
                                bottomRight: brCount,
                                bottomLeft: blCount
                            });
                            
                            if (dotCount > 0) {
                                console.log('✅ Resize dots found! They should be VISIBLE as colored circles on widget corners.');
                                console.log('🔴 RED = Top-Left, 🟢 GREEN = Top-Right, 🔵 BLUE = Bottom-Right, 🟡 YELLOW = Bottom-Left');
                            } else {
                                console.error('❌ No resize dots found in DOM!');
                            }
                        }, 200);
                        
                        $element.off('mousedown.widgetResize', '.widget-resize-dot')
                            .on('mousedown.widgetResize', '.widget-resize-dot', function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                
                                const $handle = $(this);
                                const direction = $handle.data('direction');
                                const $widget = $handle.closest('.probuilder-resizable-widget');
                                const widgetId = $widget.data('id');
                                const cellIndex = parseInt($widget.attr('data-cell-index'), 10);
                                
                                console.log('🎯 VERSION 9.0.0 - Widget resize start:', widgetId, 'direction:', direction);
                                
                                // Get widget element from data
                                const widgetElement = gridElement.children[cellIndex];
                                if (!widgetElement) return;
                                
                                const startX = e.clientX;
                                const startY = e.clientY;
                                const startWidth = $widget.outerWidth();
                                const startHeight = $widget.outerHeight();
                                const startLeft = parseFloat($widget.css('marginLeft')) || 0;
                                const startTop = parseFloat($widget.css('marginTop')) || 0;
                                
                                console.log('🔍 Start dimensions:', startWidth, 'x', startHeight, 'position:', startLeft, startTop);
                                
                                // Create size indicator
                                const $indicator = $('<div>').css({
                                    position: 'fixed',
                                    top: e.clientY + 20,
                                    left: e.clientX + 20,
                                    background: 'rgba(0,0,0,0.8)',
                                    color: 'white',
                                    padding: '8px 12px',
                                    borderRadius: '4px',
                                    fontSize: '12px',
                                    zIndex: 10000,
                                    pointerEvents: 'none'
                                }).text(`${Math.round(startWidth)}px × ${Math.round(startHeight)}px`);
                                
                                $('body').append($indicator);
                                
                                // Mouse move
                                $(document).on('mousemove.widgetResize', function(moveEvent) {
                                    const deltaX = moveEvent.clientX - startX;
                                    const deltaY = moveEvent.clientY - startY;
                                    
                                    let newWidth = startWidth;
                                    let newHeight = startHeight;
                                    let newLeft = startLeft;
                                    let newTop = startTop;
                                    
                                    // Handle different resize directions
                                    switch(direction) {
                                        case 'right':
                                            newWidth = Math.max(20, startWidth + deltaX);
                                            break;
                                        case 'left':
                                            newWidth = Math.max(20, startWidth - deltaX);
                                            newLeft = startLeft + (startWidth - newWidth);
                                            break;
                                        case 'bottom':
                                            newHeight = Math.max(20, startHeight + deltaY);
                                            break;
                                        case 'top':
                                            newHeight = Math.max(20, startHeight - deltaY);
                                            newTop = startTop + (startHeight - newHeight);
                                            break;
                                        case 'top-left':
                                            newWidth = Math.max(20, startWidth - deltaX);
                                            newHeight = Math.max(20, startHeight - deltaY);
                                            newLeft = startLeft + (startWidth - newWidth);
                                            newTop = startTop + (startHeight - newHeight);
                                            break;
                                        case 'top-right':
                                            newWidth = Math.max(20, startWidth + deltaX);
                                            newHeight = Math.max(20, startHeight - deltaY);
                                            newTop = startTop + (startHeight - newHeight);
                                            break;
                                        case 'bottom-right':
                                            newWidth = Math.max(20, startWidth + deltaX);
                                            newHeight = Math.max(20, startHeight + deltaY);
                                            break;
                                        case 'bottom-left':
                                            newWidth = Math.max(20, startWidth - deltaX);
                                            newHeight = Math.max(20, startHeight + deltaY);
                                            newLeft = startLeft + (startWidth - newWidth);
                                            break;
                                    }
                                    
                                    // Apply new size and position
                                    $widget.css({
                                        width: newWidth + 'px',
                                        height: newHeight + 'px',
                                        marginLeft: newLeft + 'px',
                                        marginTop: newTop + 'px'
                                    });
                                    
                                    // Update indicator
                                    $indicator.text(`${Math.round(newWidth)}px × ${Math.round(newHeight)}px`);
                                    $indicator.css({
                                        top: moveEvent.clientY + 20,
                                        left: moveEvent.clientX + 20
                                    });
                                });
                                
                                // Mouse up
                                $(document).on('mouseup.widgetResize', function(upEvent) {
                                    $(document).off('.widgetResize');
                                    $indicator.remove();
                                    
                                    const finalWidth = $widget.outerWidth();
                                    const finalHeight = $widget.outerHeight();
                                    const finalLeft = parseFloat($widget.css('marginLeft')) || 0;
                                    const finalTop = parseFloat($widget.css('marginTop')) || 0;
                                    
                                    console.log('✅ Final dimensions:', finalWidth, 'x', finalHeight, 'position:', finalLeft, finalTop);
                                    
                                    // Save dimensions and position to widget settings
                                    if (!widgetElement.settings) {
                                        widgetElement.settings = {};
                                    }
                                    widgetElement.settings.widget_width = finalWidth + 'px';
                                    widgetElement.settings.widget_height = finalHeight + 'px';
                                    widgetElement.settings.widget_margin_left = finalLeft + 'px';
                                    widgetElement.settings.widget_margin_top = finalTop + 'px';
                                    
                                    self.saveHistory();
                                    
                                    console.log('✅ Widget resized and saved');
                                });
                            });
                        
                        // Drag to move - using delegation
                        // VERSION: 15.0.0 - Fixed drag with resize dots
                        $element.off('mousedown.nestedDrag', '.probuilder-nested-element')
                            .on('mousedown.nestedDrag', '.probuilder-nested-element', function(e) {
                                // Don't drag if clicking toolbar buttons or resize dots
                                if ($(e.target).closest('.probuilder-nested-toolbar').length > 0 ||
                                    $(e.target).closest('.widget-resize-dot').length > 0) {
                                    console.log('❌ Click on toolbar or resize dot - not dragging');
                                    return;
                                }
                                
                                console.log('✅ Click on widget content - can drag');
                                
                                const $nestedEl = $(this);
                                const childId = $nestedEl.data('id');
                                const sourceCellIndex = parseInt($nestedEl.closest('.grid-cell').attr('data-cell-index'), 10);
                                const $gridContainer = $nestedEl.closest('.probuilder-grid-layout');
                                
                                console.log('🎯 VERSION 4.0.0 - Drag start:', childId, 'from cell:', sourceCellIndex);
                                
                                // Get the widget data
                                const widgetData = gridElement.children[sourceCellIndex];
                                if (!widgetData) return;
                                
                                // Create a visual clone for dragging
                                const $clone = $nestedEl.clone().css({
                                    position: 'absolute',
                                    zIndex: 9999,
                                    pointerEvents: 'none',
                                    opacity: 0.7,
                                    transform: 'rotate(5deg)',
                                    boxShadow: '0 5px 20px rgba(0,0,0,0.3)',
                                    width: $nestedEl.outerWidth(),
                                    height: $nestedEl.outerHeight()
                                });
                                
                                $('body').append($clone);
                                
                                // Highlight all cells as drop targets except source
                                $gridContainer.find('.grid-cell').each(function() {
                                    const cellIndex = parseInt($(this).attr('data-cell-index'), 10);
                                    if (cellIndex !== sourceCellIndex) {
                                        $(this).addClass('probuilder-drop-target');
                                    }
                                });
                                
                                // Track mouse movement
                                let isDragging = false;
                                const startX = e.clientX;
                                const startY = e.clientY;
                                
                                $(document).on('mousemove.nestedDrag', function(moveEvent) {
                                    if (!isDragging) {
                                        const deltaX = Math.abs(moveEvent.clientX - startX);
                                        const deltaY = Math.abs(moveEvent.clientY - startY);
                                        if (deltaX > 5 || deltaY > 5) {
                                            isDragging = true;
                                            $('body').css('cursor', 'grabbing');
                                            console.log('🎯 Drag activated');
                                        }
                                    }
                                    
                                    if (isDragging) {
                                        $clone.css({
                                            left: moveEvent.clientX - 50,
                                            top: moveEvent.clientY - 25
                                        });
                                        
                                        // Highlight hovered cell
                                        const $hoveredCell = $(moveEvent.target).closest('.grid-cell');
                                        if ($hoveredCell.length && $hoveredCell.hasClass('probuilder-drop-target')) {
                                            $gridContainer.find('.grid-cell').removeClass('probuilder-drop-hover');
                                            $hoveredCell.addClass('probuilder-drop-hover');
                                        } else {
                                            $gridContainer.find('.grid-cell').removeClass('probuilder-drop-hover');
                                        }
                                    }
                                });
                                
                                $(document).on('mouseup.nestedDrag', function(upEvent) {
                                    $(document).off('.nestedDrag');
                                    $clone.remove();
                                    $('body').css('cursor', '');
                                    
                                    // Remove all drop target highlights
                                    $gridContainer.find('.grid-cell').removeClass('probuilder-drop-target probuilder-drop-hover');
                                    
                                    if (isDragging) {
                                        // Check if dropped on a valid cell
                                        const $droppedCell = $(upEvent.target).closest('.grid-cell');
                                        if ($droppedCell.length && parseInt($droppedCell.attr('data-cell-index'), 10) !== sourceCellIndex) {
                                            const targetCellIndex = parseInt($droppedCell.attr('data-cell-index'), 10);
                                            
                                            console.log('🎯 Moving widget from cell', sourceCellIndex, 'to cell', targetCellIndex);
                                            
                                            // Move the widget in the data structure
                                            const widgetToMove = gridElement.children[sourceCellIndex];
                                            
                                            // Remove from source
                                            gridElement.children.splice(sourceCellIndex, 1);
                                            
                                            // Insert at target (adjust index if needed)
                                            let insertIndex = targetCellIndex;
                                            if (targetCellIndex > sourceCellIndex) {
                                                insertIndex = targetCellIndex - 1; // Adjust for removed item
                                            }
                                            
                                            // Ensure we don't exceed array bounds
                                            insertIndex = Math.max(0, Math.min(insertIndex, gridElement.children.length));
                                            
                                            gridElement.children.splice(insertIndex, 0, widgetToMove);
                                            
                                            // Re-render the grid
                                            self.renderElement(gridElement);
                                            
                                            // Save to history
                                            self.saveHistory();
                                            
                                            console.log('✅ Widget moved successfully');
                                        }
                                    }
                                });
                            });
                        
                        console.log('✅ Grid drop zone and resize handlers attached');
                    }, 100);
                }
                
                // Container 2 - uses exact same logic as Grid Layout
                if (element.widgetType === 'container-2') {
                    setTimeout(function() {
                        console.log('🎨 Attaching Container 2 drop zone handlers for:', element.id);
                        
                        const $gridDropZones = $element.find('.probuilder-drop-zone');
                        console.log('Found', $gridDropZones.length, 'Container 2 cells');
                        
                        $gridDropZones.each(function() {
                            const $zone = $(this);
                            const gridId = $zone.data('grid-id');
                            const cellIndex = parseInt($zone.attr('data-cell-index'), 10);
                            
                            // Make cells droppable
                            $zone.droppable({
                                accept: '.probuilder-widget',
                                tolerance: 'pointer',
                                hoverClass: 'probuilder-drop-hover',
                                greedy: true, // Prevent parent elements from also handling the drop
                                drop: function(event, ui) {
                                    event.stopPropagation();
                                    event.preventDefault();
                                    self.isNestedDropInProgress = true;
                                    const finishNestedDrop = () => {
                                        setTimeout(() => {
                                            self.isNestedDropInProgress = false;
                                        }, 0);
                                    };
                                    const widgetName = ui.draggable.data('widget');
                                    console.log('✅ Widget dropped in Container 2 cell:', widgetName, 'container:', gridId, 'cell:', cellIndex);
                                    
                                    // Find the container element
                                    const containerElement = self.elements.find(e => e.id === gridId);
                                    if (!containerElement) {
                                        console.error('Container 2 element not found:', gridId);
                                        finishNestedDrop();
                                        return;
                                    }
                                    
                                    // Initialize children array if needed
                                    if (!containerElement.children) {
                                        containerElement.children = [];
                                    }
                                    
                                    // Create new element
                                    const newElement = {
                                        id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                                        widgetType: widgetName,
                                        settings: {},
                                        children: []
                                    };
                                    
                                    // Add to the specific cell
                                    containerElement.children[cellIndex] = newElement;
                                    
                                    console.log('Container 2 children updated:', containerElement.children);
                                    
                                    // Re-render the container
                                    self.updateElementPreview(containerElement);
                                    self.saveData();
                                    finishNestedDrop();
                                }
                            });
                            
                            // Click handler for empty cells
                            $zone.off('click').on('click', function(e) {
                                e.stopPropagation();
                                console.log('Container 2 cell clicked:', gridId, 'cell:', cellIndex);
                                self.showWidgetPicker(null);
                            });
                        });
                        
                        // Resize handles for Container 2 cells - using event delegation
                        const $resizeHandles = $element.find('.grid-resize-handle');
                        console.log('Found', $resizeHandles.length, 'resize handles in Container 2');
                        
                        // Remove any existing handlers first
                        $element.off('mousedown.gridResize', '.grid-resize-handle');
                        
                        // Attach using delegation so it survives re-renders
                        $element.on('mousedown.gridResize', '.grid-resize-handle', function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            const cellIndex = parseInt($(this).attr('data-cell-index'), 10);
                            const direction = $(this).data('direction');
                            console.log('🎯 Container 2 resize started:', cellIndex, direction);
                            
                            // Get fresh reference to container element
                            const containerElement = self.elements.find(el => el.id === element.id);
                            if (!containerElement) {
                                console.error('Container 2 element not found:', element.id);
                                return;
                            }
                            
                            // Prevent any click events from bubbling
                            $(document).on('click.gridResizePrevent', function(clickEvent) {
                                clickEvent.preventDefault();
                                clickEvent.stopPropagation();
                                $(document).off('click.gridResizePrevent');
                            });
                            
                            // Use the same grid cell resize function (it works perfectly!)
                            self.startGridCellResize(containerElement, cellIndex, direction, e);
                        });
                        
                        // Use event delegation for better reliability
                        $(document).off('mousedown.gridResizeGlobal-' + element.id);
                        $(document).on('mousedown.gridResizeGlobal-' + element.id, '.grid-resize-handle', function(e) {
                            const $handle = $(this);
                            const $container2Element = $handle.closest('.probuilder-element[data-id="' + element.id + '"]');
                            
                            if ($container2Element.length) {
                                e.stopPropagation();
                                e.preventDefault();
                                const cellIndex = parseInt($handle.attr('data-cell-index'), 10);
                                const direction = $handle.data('direction');
                                console.log('🎯 Global Container 2 resize handler:', element.id, 'cell:', cellIndex, 'direction:', direction);
                                
                                const containerElement = self.elements.find(el => el.id === element.id);
                                if (containerElement) {
                                    $(document).on('click.gridResizePrevent', function(clickEvent) {
                                        clickEvent.preventDefault();
                                        clickEvent.stopPropagation();
                                        $(document).off('click.gridResizePrevent');
                                    });
                                    
                                    self.startGridCellResize(containerElement, cellIndex, direction, e);
                                }
                            }
                        });
                        
                        console.log('✅ Container 2 drop zone and resize handlers attached');
                    }, 100);
                }
                
                if (insertBefore) {
                    $(insertBefore).before($element);
                    console.log('✅ Element inserted before another element');
                } else {
                    $('#probuilder-preview-area').append($element);
                    console.log('✅ Element appended to preview area');
                }
                
                // Apply motion/animation styles immediately after adding to DOM
                this.applyMotionStyles(element, $element);
                
                // Make element sortable/movable (only if sortable is initialized)
                if ($('#probuilder-preview-area').hasClass('ui-sortable')) {
                    $('#probuilder-preview-area').sortable('refresh');
                }
                
                console.log('✅ Element fully rendered and interactive:', element.id);
            } catch (error) {
                console.error('❌ Error rendering element:', error);
                alert('Error rendering element: ' + error.message);
                return;
            }
        },
        
        /**
         * Get spacing styles (margin & padding) from settings
         * Supports dimensions type (grouped: {top, right, bottom, left})
         */
        getSpacingStyles: function(settings) {
            const spacing = [];
            
            // Padding - dimensions format (grouped)
            if (settings.padding && typeof settings.padding === 'object') {
                const p = settings.padding;
                if (p.top || p.right || p.bottom || p.left) {
                    const pTop = p.top || '0';
                    const pRight = p.right || '0';
                    const pBottom = p.bottom || '0';
                    const pLeft = p.left || '0';
                    spacing.push(`padding: ${pTop}px ${pRight}px ${pBottom}px ${pLeft}px`);
                }
            }
            
            // Margin - dimensions format (grouped)
            if (settings.margin && typeof settings.margin === 'object') {
                const m = settings.margin;
                if (m.top || m.right || m.bottom || m.left) {
                    const mTop = m.top || '0';
                    const mRight = m.right || '0';
                    const mBottom = m.bottom || '0';
                    const mLeft = m.left || '0';
                    spacing.push(`margin: ${mTop}px ${mRight}px ${mBottom}px ${mLeft}px`);
                }
            }
            
            return spacing.join('; ');
        },

        /**
         * Build common inline styles from settings
         * Mirrors PHP get_inline_styles: background, border, radius, shadow, transform, opacity, z-index
         */
        getCommonInlineStyles: function(settings) {
            const styles = [];
            const s = settings || {};
            
            // Background
            const bgType = s.background_type || 'none';
            if (bgType !== 'none') {
                if (bgType === 'color') {
                    if (s.background_color) styles.push(`background-color: ${s.background_color}`);
                } else if (bgType === 'gradient') {
                    const start = s.background_gradient_start || '#667eea';
                    const end = s.background_gradient_end || '#764ba2';
                    const angle = (typeof s.background_gradient_angle !== 'undefined') ? s.background_gradient_angle : 135;
                    styles.push(`background: linear-gradient(${parseInt(angle, 10)}deg, ${start}, ${end})`);
                } else if (bgType === 'image') {
                    const bg = s.background_image || {};
                    if (bg.url) {
                        styles.push(`background-image: url(${bg.url})`);
                        styles.push(`background-size: ${s.background_size || 'cover'}`);
                        styles.push(`background-position: ${s.background_position || 'center center'}`);
                        styles.push(`background-repeat: ${s.background_repeat || 'no-repeat'}`);
                    }
                }
            }
            
            // Border
            const borderStyle = s.border_style || 'none';
            if (borderStyle !== 'none') {
                const bw = s.border_width || {top: '1', right: '1', bottom: '1', left: '1'};
                const bc = s.border_color || '#000000';
                styles.push(`border-style: ${borderStyle}`);
                styles.push(`border-width: ${bw.top || 0}px ${bw.right || 0}px ${bw.bottom || 0}px ${bw.left || 0}px`);
                styles.push(`border-color: ${bc}`);
            }
            
            // Border radius
            const br = s.border_radius;
            if (br && typeof br === 'object') {
                const any = (val) => val !== undefined && val !== '' && val !== '0';
                if (any(br.top) || any(br.right) || any(br.bottom) || any(br.left)) {
                    styles.push(`border-radius: ${br.top || 0}px ${br.right || 0}px ${br.bottom || 0}px ${br.left || 0}px`);
                }
            }
            
            // Box shadow
            if (s.box_shadow_enable === 'yes') {
                const h = parseInt(s.box_shadow_h || 0, 10);
                const v = parseInt(s.box_shadow_v || 5, 10);
                const blur = parseInt(s.box_shadow_blur || 15, 10);
                const spread = parseInt(s.box_shadow_spread || 0, 10);
                const color = s.box_shadow_color || 'rgba(0,0,0,0.2)';
                styles.push(`box-shadow: ${h}px ${v}px ${blur}px ${spread}px ${color}`);
            }
            
            // Transform
            const t = [];
            if (typeof s.rotate !== 'undefined' && s.rotate != 0) t.push(`rotate(${s.rotate}deg)`);
            if (typeof s.scale !== 'undefined' && s.scale != 100) {
                const scaleDecimal = parseFloat(s.scale) / 100;
                t.push(`scale(${scaleDecimal})`);
            }
            if ((typeof s.skew_x !== 'undefined' && s.skew_x != 0) || (typeof s.skew_y !== 'undefined' && s.skew_y != 0)) {
                const sx = s.skew_x || 0; const sy = s.skew_y || 0;
                t.push(`skew(${sx}deg, ${sy}deg)`);
            }
            if (t.length) styles.push(`transform: ${t.join(' ')}`);
            
            // Opacity - convert percentage (0-100) to decimal (0-1)
            if (typeof s.opacity !== 'undefined' && s.opacity !== '' && s.opacity != 100) {
                const opacityDecimal = parseFloat(s.opacity) / 100;
                styles.push(`opacity: ${opacityDecimal}`);
            }
            
            // Z-index
            if (typeof s.z_index !== 'undefined' && s.z_index !== '') styles.push(`z-index: ${parseInt(s.z_index, 10)}`);
            
            return styles.join('; ');
        },

        /**
         * Responsive visibility handling (compact, no extra CSS files)
         */
        applyResponsiveVisibility: function(element, $element) {
            try {
                const s = element.settings || {};
                const ww = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                
                let shouldHide = false;
                if (ww <= 767 && s.hide_mobile === 'yes') shouldHide = true;
                if (ww >= 768 && ww <= 1024 && s.hide_tablet === 'yes') shouldHide = true;
                if (ww >= 1025 && s.hide_desktop === 'yes') shouldHide = true;
                
                const $target = $element && $element.length ? $element : $(`.probuilder-element[data-id="${element.id}"]`);
                if ($target.length) {
                    $target.toggle(shouldHide ? false : true);
                }
            } catch (e) {
                console.error('applyResponsiveVisibility error:', e);
            }
        },
        
        applyResponsiveVisibilityToAll: function() {
            if (!Array.isArray(this.elements)) return;
            const self = this;
            this.elements.forEach(function(el) {
                self.applyResponsiveVisibility(el);
            });
        },
        
        /**
         * Generate preview HTML
         */
        generatePreview: function(element, depth = 0) {
            try {
                // Prevent infinite recursion
                if (depth > 10) {
                    console.error('Max recursion depth reached for element:', element.id);
                    return '<div style="padding: 20px; color: #f00;">Max nesting depth reached</div>';
                }
                
                const widget = this.widgets.find(w => w.name === element.widgetType);
                if (!widget) {
                    console.error('Widget not found:', element.widgetType);
                    return '<div style="padding: 20px; color: #999; text-align: center;">Widget not found: ' + element.widgetType + '</div>';
                }
                
                const settings = element.settings || {};
                
                // Get spacing styles for this widget
                const spacingStyles = this.getSpacingStyles(settings);
                
                const renderer = widgetRenderers[element.widgetType];
                if (renderer) {
                    return renderer({ element, settings, spacingStyles, app: this, depth, widget });
                }
                
                    return `<div style="padding: 25px; background: #f8f9fa; text-align: center; border: 1px solid #e6e9ec; border-radius: 3px; color: #6d7882;"><i class="${widget.icon}" style="font-size: 32px; color: #93003c; margin-bottom: 10px;"></i><br><strong>${widget.title}</strong><br><small style="color: #a4afb7;">Click edit to customize</small></div>`;

            } catch (error) {
                console.error('Error generating preview:', error);
                return '<div style="padding: 20px; color: #f00; text-align: center;">Error generating preview: ' + error.message + '</div>';
            }
        },
        
        /**
         * Render WooCommerce products fallback (sample products)
         */
        renderWooProductsFallback: function(containerId, opts) {
            const sampleProducts = [
                {title: 'Sample Product 1', price: '$29.99', image: 'https://via.placeholder.com/300x300/92003b/ffffff?text=Product+1', sale: true, rating: 5},
                {title: 'Sample Product 2', price: '$39.99', image: 'https://via.placeholder.com/300x300/667eea/ffffff?text=Product+2', sale: false, rating: 4},
                {title: 'Sample Product 3', price: '$49.99', image: 'https://via.placeholder.com/300x300/4facfe/ffffff?text=Product+3', sale: false, rating: 5},
                {title: 'Sample Product 4', price: '$19.99', image: 'https://via.placeholder.com/300x300/764ba2/ffffff?text=Product+4', sale: true, rating: 4},
            ].slice(0, opts.wooPerPage);
            
            let html = `
                <div style="background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; padding: 12px; margin-bottom: 15px; text-align: center;">
                    <i class="dashicons dashicons-info" style="color: #f59e0b;"></i>
                    <strong style="color: #92400e; font-size: 13px;">Preview Mode</strong>
                    <p style="margin: 5px 0 0; font-size: 12px; color: #78350f;">Real products will show on frontend after save</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(${opts.wooColumns}, 1fr); gap: ${opts.wooRowGap}px ${opts.wooGap}px;">
            `;
            
            sampleProducts.forEach(p => {
                html += `<div style="border-radius: ${opts.wooBorderRadius}px; overflow: hidden; background: ${opts.wooCardBg}; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">`;
                if (opts.wooShowImage) {
                    html += `<div style="position: relative; background: #f8f9fa;"><img src="${p.image}" style="width: 100%; height: auto; display: block;">`;
                    if (opts.wooShowBadge && p.sale) html += `<span style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Sale</span>`;
                    html += `</div>`;
                }
                html += `<div style="padding: 20px;">`;
                if (opts.wooShowTitle) html += `<h3 style="margin: 0 0 10px; font-size: 18px; color: ${opts.wooTitleColor};">${p.title}</h3>`;
                if (opts.wooShowRating) {
                    html += `<div style="margin-bottom: 10px; color: #fbbf24;">`;
                    for (let i = 0; i < 5; i++) html += i < p.rating ? '★' : '☆';
                    html += `</div>`;
                }
                if (opts.wooShowPrice) html += `<div style="margin-bottom: 15px; font-size: 20px; font-weight: 600; color: ${opts.wooPriceColor};">${p.price}</div>`;
                if (opts.wooShowCart) html += `<a href="#" style="background: ${opts.wooBtnBg}; color: ${opts.wooBtnText}; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px; font-weight: 600;">Add to Cart</a>`;
                html += `</div></div>`;
            });
            
            html += `</div>`;
            $('#' + containerId).html(html);
        },
        
        /**
         * Render all elements
         */
        renderElements: function() {
            $('#probuilder-preview-area').empty();
            this.elements.forEach(element => {
                this.renderElement(element);
            });
        },
        
        /**
         * Select element (automatically opens settings)
         */
        selectElement: function(element) {
            this.selectedElement = element;
            $('.probuilder-element').removeClass('selected');
            $(`.probuilder-element[data-id="${element.id}"]`).addClass('selected');
            
            // Automatically open settings when element is selected
            this.openSettings(element);
        },
        
        /**
         * Open settings panel
         */
        openSettings: function(element) {
            console.log('==================== OPENING SETTINGS ====================');
            console.log('Element:', element.id, element.widgetType);
            
            // Just update the selected element visually (don't call selectElement to avoid circular call)
            this.selectedElement = element;
            $('.probuilder-element').removeClass('selected');
            $(`.probuilder-element[data-id="${element.id}"]`).addClass('selected');
            
            const widget = this.widgets.find(w => w.name === element.widgetType);
            
            if (!widget) {
                console.error('Widget not found for:', element.widgetType);
                return;
            }
            
            console.log('Widget found:', widget.title);
            console.log('Controls count:', widget.controls ? Object.keys(widget.controls).length : 0);
            console.log('Controls object:', widget.controls);
            
            // Log each control's tab
            if (widget.controls) {
                Object.keys(widget.controls).forEach(key => {
                    const control = widget.controls[key];
                    console.log(`  ${key}: tab="${control.tab || 'UNDEFINED'}" type="${control.type}"`);
                });
            }
            
            $('#probuilder-settings-title').text(widget.title);
            
            // Hide placeholder
            $('.probuilder-settings-placeholder').hide();
            
            // Show tabs
            $('#probuilder-settings-tabs').show();
            
            // Reset to content tab
            $('.probuilder-settings-tab').removeClass('active');
            $('.probuilder-settings-tab[data-tab="content"]').addClass('active');
            
            // Panel is always visible now - just update content
            this.renderSettings(element, widget, 'content');
            
            console.log('Settings rendered for:', widget.title);
        },
        
        /**
         * Get motion controls for all widgets - Enhanced with comprehensive animations
         */
        getMotionControls: function() {
            return {
                // Entrance Animation
                '_motion_animation': {
                    label: 'Entrance Animation',
                    type: 'select',
                    default: 'none',
                    options: {
                        'none': 'None',
                        // Fade Animations
                        'fadeIn': 'Fade In',
                        'fadeInUp': 'Fade In Up',
                        'fadeInDown': 'Fade In Down',
                        'fadeInLeft': 'Fade In Left',
                        'fadeInRight': 'Fade In Right',
                        // Zoom Animations
                        'zoomIn': 'Zoom In',
                        'zoomInUp': 'Zoom In Up',
                        'zoomInDown': 'Zoom In Down',
                        'zoomInLeft': 'Zoom In Left',
                        'zoomInRight': 'Zoom In Right',
                        // Slide Animations
                        'slideInUp': 'Slide In Up',
                        'slideInDown': 'Slide In Down',
                        'slideInLeft': 'Slide In Left',
                        'slideInRight': 'Slide In Right',
                        // Bounce Animations
                        'bounceIn': 'Bounce In',
                        'bounceInUp': 'Bounce In Up',
                        'bounceInDown': 'Bounce In Down',
                        'bounceInLeft': 'Bounce In Left',
                        'bounceInRight': 'Bounce In Right',
                        // Flip & Rotate
                        'flipInX': 'Flip In X',
                        'flipInY': 'Flip In Y',
                        'rotateIn': 'Rotate In',
                        'rotateInUpLeft': 'Rotate In Up Left',
                        'rotateInUpRight': 'Rotate In Up Right',
                        'rotateInDownLeft': 'Rotate In Down Left',
                        'rotateInDownRight': 'Rotate In Down Right',
                        // Special Effects
                        'lightSpeedInRight': 'Light Speed In Right',
                        'lightSpeedInLeft': 'Light Speed In Left',
                        'rollIn': 'Roll In',
                        'jackInTheBox': 'Jack In The Box',
                        'backInUp': 'Back In Up',
                        'backInDown': 'Back In Down',
                        'backInLeft': 'Back In Left',
                        'backInRight': 'Back In Right'
                    },
                    tab: 'motion'
                },
                '_motion_duration': {
                    label: 'Animation Duration (ms)',
                    type: 'slider',
                    default: 1000,
                    range: {
                        px: { min: 200, max: 3000, step: 100 }
                    },
                    unit: 'ms',
                    tab: 'motion'
                },
                '_motion_delay': {
                    label: 'Animation Delay (ms)',
                    type: 'slider',
                    default: 0,
                    range: {
                        px: { min: 0, max: 5000, step: 100 }
                    },
                    unit: 'ms',
                    tab: 'motion'
                },
                '_motion_easing': {
                    label: 'Easing Function',
                    type: 'select',
                    default: 'ease-in-out',
                    options: {
                        'linear': 'Linear',
                        'ease': 'Ease',
                        'ease-in': 'Ease In',
                        'ease-out': 'Ease Out',
                        'ease-in-out': 'Ease In Out',
                        'ease-in-back': 'Ease In Back',
                        'ease-out-back': 'Ease Out Back',
                        'ease-in-out-back': 'Ease In Out Back'
                    },
                    tab: 'motion'
                },
                // Hover Animation
                '_motion_hover': {
                    label: 'Hover Animation',
                    type: 'select',
                    default: 'none',
                    options: {
                        'none': 'None',
                        'grow': 'Grow',
                        'shrink': 'Shrink',
                        'pulse': 'Pulse',
                        'push': 'Push',
                        'pop': 'Pop',
                        'bounce': 'Bounce',
                        'rotate': 'Rotate',
                        'grow-rotate': 'Grow & Rotate',
                        'float': 'Float',
                        'sink': 'Sink',
                        'wobble': 'Wobble',
                        'skew': 'Skew',
                        'buzz': 'Buzz'
                    },
                    tab: 'motion'
                },
                // Advanced Options
                '_motion_repeat': {
                    label: 'Repeat Animation',
                    type: 'select',
                    default: '1',
                    options: {
                        '1': 'Once',
                        '2': 'Twice',
                        '3': 'Three Times',
                        'infinite': 'Infinite Loop'
                    },
                    tab: 'motion'
                },
                '_motion_viewport': {
                    label: 'Animate on Scroll',
                    type: 'switcher',
                    default: 'yes',
                    tab: 'motion'
                },
                '_motion_viewport_offset': {
                    label: 'Viewport Offset (%)',
                    type: 'slider',
                    default: 20,
                    range: {
                        px: { min: 0, max: 100, step: 5 }
                    },
                    unit: '%',
                    tab: 'motion'
                },
                '_motion_preview_btn': {
                    label: 'Preview Animation',
                    type: 'raw_html',
                    html: '<button type="button" class="probuilder-button probuilder-preview-animation" style="width: 100%; margin-top: 10px;">▶️ Preview Animation</button>',
                    tab: 'motion'
                }
            };
        },
        
        /**
         * Render settings
         */
        renderSettings: function(element, widget, activeTab = 'content') {
            const $content = $('#probuilder-settings-content');
            $content.empty();
            
            console.log('Rendering settings for:', widget.name, 'Tab:', activeTab);
            
            if (!widget.controls) {
                console.warn('No controls defined for widget:', widget.name);
                $content.html('<div style="padding: 20px; text-align: center; color: #999;">No settings available for this widget</div>');
                return;
            }
            
            // Merge motion controls with widget controls for the motion tab
            const allControls = activeTab === 'motion' ? this.getMotionControls() : widget.controls;
            
            console.log('Controls to render:', Object.keys(allControls).length);
            
            const self = this;
			let controlsRendered = 0;
			let responsiveRowRendered = false;
            
			Object.keys(allControls).forEach(key => {
                const control = allControls[key];
                
                // Filter by tab - only show controls for active tab
                const controlTab = control.tab || 'content';
                
                const willShow = controlTab === activeTab;
                console.log(`  Processing: ${key} | Tab: ${controlTab} | Active: ${activeTab} | Show: ${willShow}`);
                
                if (controlTab !== activeTab) {
                    console.log(`    ↳ Skipped (tab mismatch)`);
                    return; // Skip controls not in active tab
                }
                
				if (control.type === 'section_start') {
                    console.log(`    ↳ Adding section header: ${control.label}`);
                    $content.append(`<div class="probuilder-control-section"><h4>${control.label}</h4></div>`);
                    return;
                }

				// Compact Responsive Row: group Desktop / Tablet / Mobile in one row with toggle switches
				if (activeTab === 'style' && !responsiveRowRendered && key === 'hide_desktop') {
					const vDesktop = (element.settings['hide_desktop'] === 'yes');
					const vTablet = (element.settings['hide_tablet'] === 'yes');
					const vMobile = (element.settings['hide_mobile'] === 'yes');
					
					const rowHtml = `
						<div class="probuilder-control">
							<label>Responsive</label>
							<div class="probuilder-responsive-row" style="display: flex; gap: 10px; align-items: center; flex-wrap: nowrap;">
								<label class="probuilder-toggle-switch" style="display:flex; align-items:center; gap:6px; cursor:pointer;">
									<input type="checkbox" class="probuilder-responsive-toggle" data-device="desktop" ${vDesktop ? 'checked' : ''} style="display:none;">
									<span class="probuilder-switcher-track" style="position:relative; width:44px; height:24px; background:${vDesktop?'#92003b':'#cbd5e1'}; border-radius:12px; transition:background 0.3s; display:inline-block;">
										<span class="probuilder-switcher-thumb" style="position:absolute; left:${vDesktop?'22px':'2px'}; top:2px; width:20px; height:20px; background:#fff; border-radius:50%; transition:left 0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
									</span>
									<i class="dashicons dashicons-desktop" style="opacity:.7"></i>
									<span style="font-size:12px; color:#374151;">Hide Desktop</span>
								</label>
								<label class="probuilder-toggle-switch" style="display:flex; align-items:center; gap:6px; cursor:pointer;">
									<input type="checkbox" class="probuilder-responsive-toggle" data-device="tablet" ${vTablet ? 'checked' : ''} style="display:none;">
									<span class="probuilder-switcher-track" style="position:relative; width:44px; height:24px; background:${vTablet?'#92003b':'#cbd5e1'}; border-radius:12px; transition:background 0.3s; display:inline-block;">
										<span class="probuilder-switcher-thumb" style="position:absolute; left:${vTablet?'22px':'2px'}; top:2px; width:20px; height:20px; background:#fff; border-radius:50%; transition:left 0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
									</span>
									<i class="dashicons dashicons-tablet" style="opacity:.7"></i>
									<span style="font-size:12px; color:#374151;">Hide Tablet</span>
								</label>
								<label class="probuilder-toggle-switch" style="display:flex; align-items:center; gap:6px; cursor:pointer;">
									<input type="checkbox" class="probuilder-responsive-toggle" data-device="mobile" ${vMobile ? 'checked' : ''} style="display:none;">
									<span class="probuilder-switcher-track" style="position:relative; width:44px; height:24px; background:${vMobile?'#92003b':'#cbd5e1'}; border-radius:12px; transition:background 0.3s; display:inline-block;">
										<span class="probuilder-switcher-thumb" style="position:absolute; left:${vMobile?'22px':'2px'}; top:2px; width:20px; height:20px; background:#fff; border-radius:50%; transition:left 0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
									</span>
									<i class="dashicons dashicons-smartphone" style="opacity:.7"></i>
									<span style="font-size:12px; color:#374151;">Hide Mobile</span>
								</label>
							</div>
						</div>`;
					
					const $row = $(rowHtml);
					const keyMap = { desktop: 'hide_desktop', tablet: 'hide_tablet', mobile: 'hide_mobile' };
					$row.find('.probuilder-responsive-toggle').on('change', function(e) {
						const device = $(e.currentTarget).data('device');
						const settingKey = keyMap[device];
						const checked = $(e.currentTarget).is(':checked');
						element.settings[settingKey] = checked ? 'yes' : 'no';
						const $track = $(e.currentTarget).siblings('.probuilder-switcher-track');
						const $thumb = $track.find('.probuilder-switcher-thumb');
						$track.css('background', checked ? '#92003b' : '#cbd5e1');
						$thumb.css('left', checked ? '22px' : '2px');
						console.log('✅ Responsive toggled:', settingKey, element.settings[settingKey]);
						ProBuilder.applyResponsiveVisibility(element);
					});
					
					$content.append($row);
					controlsRendered++;
					responsiveRowRendered = true;
					return; // skip default rendering for hide_desktop (we handled the row)
				}
				// Skip individual responsive controls if compact row already rendered
				if (responsiveRowRendered && (key === 'hide_tablet' || key === 'hide_mobile')) {
					return;
				}
                
                const value = element.settings[key] !== undefined ? element.settings[key] : control.default;
                
                const $control = this.renderControl(key, control, value, element);
                controlsRendered++;
                
                // Handle different control types
                if (control.type === 'dimensions') {
                    // Dimensions control - update on each input change
                    $control.find('input').on('input change', function() {
                        const dimension = $(this).data('dimension');
                        if (!element.settings[key]) {
                            element.settings[key] = {};
                        }
                        element.settings[key][dimension] = $(this).val();
                        console.log('Dimension updated:', key, dimension, $(this).val());
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'border') {
                    // Border control
                    $control.find('input, select').on('input change', function() {
                        const borderProp = $(this).data('border');
                        if (!element.settings[key]) {
                            element.settings[key] = {};
                        }
                        element.settings[key][borderProp] = $(this).val();
                        console.log('Border updated:', key, borderProp, $(this).val());
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'box-shadow') {
                    // Box shadow control
                    $control.find('input').on('input change', function() {
                        const shadowProp = $(this).data('shadow');
                        if (!element.settings[key]) {
                            element.settings[key] = {};
                        }
                        element.settings[key][shadowProp] = $(this).val();
                        console.log('Shadow updated:', key, shadowProp, $(this).val());
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'angle') {
                    // Circular angle picker control
                    const $angleInput = $control.find('.probuilder-angle-input');
                    const $angleHandle = $control.find('.angle-handle');
                    const $angleArc = $control.find(`#${$angleInput.attr('id').replace('-input', '-arc')}`);
                    const $angleLine = $control.find(`#${$angleInput.attr('id').replace('-input', '-line')}`);
                    const $angleWrapper = $control.find('.angle-circle-wrapper');
                    const $angleDisplay = $angleWrapper.find('div').last();
                    
                    // Function to update all angle UI elements
                    function updateAngleUI(newAngle) {
                        newAngle = Math.max(0, Math.min(360, parseInt(newAngle) || 0));
                        
                        // Update input
                        $angleInput.val(newAngle);
                        
                        // Calculate handle position (SVG coordinates)
                        const angleRad = (newAngle - 90) * Math.PI / 180;
                        const handleX = 50 + 45 * Math.cos(angleRad);
                        const handleY = 50 + 45 * Math.sin(angleRad);
                        
                        // Update handle position (absolute positioning)
                        $angleHandle.css({
                            'top': handleY + 'px',
                            'left': handleX + 'px'
                        });
                        
                        // Update line
                        $angleLine.attr({
                            'x2': handleX,
                            'y2': handleY
                        });
                        
                        // Update arc
                        const arcLength = (newAngle / 360) * 283;
                        $angleArc.attr('stroke-dasharray', `${arcLength} 283`);
                        
                        // Update center display
                        $angleDisplay.text(Math.round(newAngle) + '°');
                        
                        // Update element setting
                        element.settings[key] = newAngle;
                        self.updateElementPreview(element);
                    }
                    
                    // Handle manual input
                    $angleInput.on('input change', function() {
                        updateAngleUI($(this).val());
                    });
                    
                    // Handle preset buttons
                    $control.find('.angle-preset-btn').on('click', function(e) {
                        e.preventDefault();
                        const presetAngle = $(this).data('angle');
                        updateAngleUI(presetAngle);
                        
                        // Visual feedback
                        $(this).css({
                            'background': '#92003b',
                            'color': 'white',
                            'border-color': '#92003b'
                        });
                        setTimeout(() => {
                            $(this).css({
                                'background': 'white',
                                'color': 'inherit',
                                'border-color': '#d1d5db'
                            });
                        }, 200);
                    });
                    
                    // Handle circular drag - MAKE ENTIRE CIRCLE CLICKABLE
                    let isDragging = false;
                    
                    // Click anywhere in circle to set angle
                    $angleWrapper.on('mousedown', function(e) {
                        e.preventDefault();
                        isDragging = true;
                        $angleHandle.css('cursor', 'grabbing');
                        $angleWrapper.css('cursor', 'grabbing');
                        
                        // Immediately update angle on click
                        const wrapperOffset = $angleWrapper.offset();
                        const centerX = wrapperOffset.left + 50;
                        const centerY = wrapperOffset.top + 50;
                        const mouseX = e.pageX;
                        const mouseY = e.pageY;
                        
                        // Calculate angle from center
                        const deltaX = mouseX - centerX;
                        const deltaY = mouseY - centerY;
                        let angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI);
                        angle = (angle + 90) % 360; // Normalize to 0-360, adjusted for -90 rotation
                        if (angle < 0) angle += 360;
                        
                        updateAngleUI(Math.round(angle));
                    });
                    
                    $(document).on('mousemove', function(e) {
                        if (!isDragging) return;
                        
                        const wrapperOffset = $angleWrapper.offset();
                        const centerX = wrapperOffset.left + 50;
                        const centerY = wrapperOffset.top + 50;
                        const mouseX = e.pageX;
                        const mouseY = e.pageY;
                        
                        // Calculate angle from center
                        const deltaX = mouseX - centerX;
                        const deltaY = mouseY - centerY;
                        let angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI);
                        angle = (angle + 90) % 360; // Normalize to 0-360, adjusted for -90 rotation
                        if (angle < 0) angle += 360;
                        
                        updateAngleUI(Math.round(angle));
                    });
                    
                    $(document).on('mouseup', function() {
                        if (isDragging) {
                            isDragging = false;
                            $angleHandle.css('cursor', 'grab');
                            $angleWrapper.css('cursor', 'pointer');
                        }
                    });
                    
                    // Hover effect
                    $angleWrapper.on('mouseenter', function() {
                        $angleHandle.css('transform', 'translate(-50%, -50%) scale(1.2)');
                    }).on('mouseleave', function() {
                        if (!isDragging) {
                            $angleHandle.css('transform', 'translate(-50%, -50%) scale(1)');
                        }
                    });
                    
                } else if (control.type === 'typography') {
                    // Typography control
                    $control.find('input, select').on('input change', function() {
                        const typoProp = $(this).data('typo');
                        if (!element.settings[key]) {
                            element.settings[key] = {};
                        }
                        element.settings[key][typoProp] = $(this).val();
                        console.log('Typography updated:', key, typoProp, $(this).val());
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'switcher') {
                    // Switcher control
                    $control.find('input[type="checkbox"]').on('change', function() {
                        element.settings[key] = $(this).is(':checked') ? 'yes' : 'no';
                        console.log('Switcher updated:', key, element.settings[key]);
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'slider') {
                    // Slider control - ensure opacity and all sliders update immediately
                    $control.find('.probuilder-slider').on('input change', function() {
                        const newValue = parseFloat($(this).val()) || 0;
                        element.settings[key] = newValue;
                        console.log('✅ Slider updated:', key, '=', newValue);
                        
                        // Update value display
                        $(this).next('.probuilder-slider-value').text(newValue + (control.unit || ''));
                        
                        // If it's a motion control, apply animation immediately
                        if (key.startsWith('_motion_')) {
                            console.log('🎬 Motion slider changed! Applying animation...');
                            self.applyMotionStyles(element);
                        }
                        
                        // Force immediate preview update (critical for opacity)
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'color') {
                    // Color control - handle background_color and other colors
                    $control.find('input[type="color"]').on('input change', function() {
                        element.settings[key] = $(this).val();
                        console.log('✅ Color updated:', key, $(this).val());
                        // Force immediate preview update (critical for background_color)
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'select') {
                    // Select control - handle background_type changes
                    $control.find('select').on('change', function() {
                        const newValue = $(this).val();
                        element.settings[key] = newValue;
                        console.log('✅ Select updated:', key, '=', newValue);
                        
                        // If background_type changed, force preview update
                        if (key === 'background_type') {
                            self.updateElementPreview(element);
                        } else {
                            self.updateElementPreview(element);
                        }
                    });
                    
                    // Circular gradient angle slider handler
                    $control.find('.probuilder-gradient-angle-slider').on('input change', function() {
                        const newAngle = parseFloat($(this).val()) || 135;
                        element.settings[key] = newAngle;
                        console.log('✅ Gradient angle updated:', newAngle);
                        
                        // Update visual circle
                        const $container = $(this).closest('.probuilder-gradient-angle-wrapper');
                        const circleSize = 120;
                        const center = circleSize / 2;
                        const radius = 40;
                        const angleRad = (newAngle - 90) * Math.PI / 180;
                        const dotX = center + radius * Math.cos(angleRad);
                        const dotY = center + radius * Math.sin(angleRad);
                        
                        const $svg = $container.find('svg');
                        $svg.find('line').attr({ x2: dotX, y2: dotY });
                        $svg.find('circle:last-child').attr({ cx: dotX, cy: dotY });
                        $container.find('.probuilder-angle-value').text(Math.round(newAngle) + '°');
                        
                        // Force immediate preview update
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'repeater') {
                    // Repeater control handlers
                    const $repeater = $control.find('.probuilder-repeater');
                    const fields = control.fields || [];
                    
                    // Toggle item expand/collapse
                    $repeater.find('.probuilder-repeater-toggle').off('click').on('click', function(e) {
                        e.stopPropagation();
                        const $item = $(this).closest('.probuilder-repeater-item');
                        const $fields = $item.find('.probuilder-repeater-item-fields');
                        const $icon = $(this).find('.dashicons');
                        
                        $fields.slideToggle(200);
                        $icon.toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');
                    });
                    
                    // Delete item
                    $repeater.find('.probuilder-repeater-delete').off('click').on('click', function(e) {
                        e.stopPropagation();
                        
                        const $item = $(this).closest('.probuilder-repeater-item');
                        const index = parseInt($item.data('index'));
                        
                        if (!Array.isArray(element.settings[key])) {
                            element.settings[key] = [];
                        }
                        
                        element.settings[key].splice(index, 1);
                        console.log('Repeater item deleted:', key, index);
                        
                        // Re-render settings and update preview
                        self.renderSettings(element, widget, activeTab);
                        self.updateElementPreview(element);
                    });
                    
                    // Update field values
                    $repeater.find('.probuilder-repeater-item').each(function() {
                        const $item = $(this);
                        const index = parseInt($item.data('index'));
                        
                        $item.find('input, textarea').off('input change').on('input change', function() {
                            const fieldName = $(this).data('field');
                            const fieldValue = $(this).val();
                            
                            if (!Array.isArray(element.settings[key])) {
                                element.settings[key] = [];
                            }
                            
                            if (!element.settings[key][index]) {
                                element.settings[key][index] = {};
                            }
                            
                            element.settings[key][index][fieldName] = fieldValue;
                            console.log('Repeater field updated:', key, index, fieldName, fieldValue);
                            self.updateElementPreview(element);
                        });
                    });
                    
                    // Add new item
                    $repeater.find('.probuilder-repeater-add').off('click').on('click', function(e) {
                        e.stopPropagation();
                        
                        if (!Array.isArray(element.settings[key])) {
                            element.settings[key] = [];
                        }
                        
                        // Create new item with default values
                        const newItem = {};
                        fields.forEach(field => {
                            newItem[field.name] = field.default || '';
                        });
                        
                        element.settings[key].push(newItem);
                        console.log('Repeater item added:', key);
                        
                        // Re-render settings and update preview
                        self.renderSettings(element, widget, activeTab);
                        self.updateElementPreview(element);
                    });
                    
                    // Make items sortable
                    $repeater.find('.probuilder-repeater-items').sortable({
                        handle: '.probuilder-repeater-handle',
                        axis: 'y',
                        placeholder: 'probuilder-repeater-placeholder',
                        update: function(event, ui) {
                            const newOrder = [];
                            $repeater.find('.probuilder-repeater-item').each(function() {
                                const index = parseInt($(this).data('index'));
                                newOrder.push(element.settings[key][index]);
                            });
                            element.settings[key] = newOrder;
                            console.log('Repeater items reordered:', key);
                            
                            // Re-render settings and update preview
                            self.renderSettings(element, widget, activeTab);
                            self.updateElementPreview(element);
                        }
                    });
                } else {
                    // Switcher control
                    $control.find('.probuilder-switcher-input').on('change', function() {
                        element.settings[key] = $(this).is(':checked') ? 'yes' : 'no';
                        console.log('✅ Switcher updated:', key, element.settings[key]);
                        self.updateElementPreview(element);
                    });
                    
                    // All other controls
                    $control.find('input, select, textarea').not('.probuilder-switcher-input').on('input change', function() {
                        const newValue = $(this).val();
                        element.settings[key] = newValue;
                        console.log('✅ Control updated:', key, '=', newValue);
                        
                        // Update slider value display
                        if ($(this).hasClass('probuilder-slider')) {
                            $(this).next('.probuilder-slider-value').text(newValue + (control.unit || ''));
                        }
                        
                        // If it's a motion control, apply animation immediately
                        if (key.startsWith('_motion_')) {
                            console.log('🎬 Motion control changed! Applying animation...');
                            self.applyMotionStyles(element);
                        }
                        
                        self.updateElementPreview(element);
                    });
                }
                
                $content.append($control);
            });
            
            console.log('Controls rendered in', activeTab, 'tab:', controlsRendered);
            
            // Show message if no controls in this tab
            if (controlsRendered === 0) {
                $content.html(`
                    <div style="text-align: center; padding: 40px 20px; color: #a1a1aa;">
                        <i class="dashicons dashicons-info" style="font-size: 32px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <h4 style="font-size: 14px; color: #71717a; margin: 0 0 8px 0;">No ${activeTab.charAt(0).toUpperCase() + activeTab.slice(1)} Settings</h4>
                        <p style="font-size: 12px; margin: 0;">This widget has no settings in this tab. Try other tabs.</p>
                    </div>
                `);
            }
            
            // Add preview animation button handler (for motion tab)
            if (activeTab === 'motion') {
                $content.find('.probuilder-preview-animation').off('click').on('click', function() {
                    self.previewAnimation(element);
                });
            }
        },
        
        /**
         * Preview animation on canvas element - Replays the animation
         */
        previewAnimation: function(element) {
            const animation = element.settings._motion_animation || 'fadeIn';
            const duration = element.settings._motion_duration || 1000;
            const easing = element.settings._motion_easing || 'ease-in-out';
            
            console.log('🎬 Preview animation clicked for:', element.id);
            console.log('Animation:', animation, 'Duration:', duration);
            
            if (!animation || animation === 'none') {
                alert('Please select an animation first!');
                return;
            }
            
            // Find the element in the canvas
            const $canvasElement = $(`.probuilder-element[data-id="${element.id}"]`);
            
            if ($canvasElement.length === 0) {
                console.error('❌ Canvas element not found:', element.id);
                return;
            }
            
            // Get the preview area
            const $preview = $canvasElement.find('.probuilder-element-preview');
            
            if ($preview.length === 0) {
                console.error('❌ Preview area not found');
                return;
            }
            
            console.log('✅ Element found, replaying animation...');
            
            // Remove existing animation to reset
            $preview.css('animation', 'none');
            
            // Force reflow to reset
            void $preview[0].offsetWidth;
            
            // Apply animation
            $preview.css({
                'animation-name': animation,
                'animation-duration': duration + 'ms',
                'animation-timing-function': easing,
                'animation-fill-mode': 'both',
                'opacity': '1'
            });
            
            console.log('✅ Animation replayed!');
            
            // Don't reset - let it stay animated
        },
        
        /**
         * Apply motion/animation styles to canvas element
         */
        applyMotionStyles: function(element, $element) {
            // Get motion settings
            const animation = element.settings._motion_animation || 'none';
            const duration = element.settings._motion_duration || 1000;
            const delay = element.settings._motion_delay || 0;
            const easing = element.settings._motion_easing || 'ease-in-out';
            const hoverAnimation = element.settings._motion_hover || 'none';
            const repeat = element.settings._motion_repeat || '1';
            
            console.log('🎬 applyMotionStyles called for element:', element.id);
            console.log('Animation settings:', { animation, duration, delay, easing, hoverAnimation, repeat });
            
            // If no $element passed, find it
            if (!$element || $element.length === 0) {
                $element = $(`.probuilder-element[data-id="${element.id}"]`);
                console.log('Found element:', $element.length);
            }
            
            if ($element.length === 0) {
                console.error('❌ Element not found in DOM:', element.id);
                return;
            }
            
            // Apply to the preview area inside the element
            const $preview = $element.find('.probuilder-element-preview');
            
            if ($preview.length === 0) {
                console.error('❌ Preview area not found for:', element.id);
                return;
            }
            
            // Remove previous animation classes
            $preview.removeClass(function(index, className) {
                return (className.match(/(^|\s)probuilder-animate-\S+/g) || []).join(' ');
            });
            
            // Clear any existing animation first
            $preview.css('animation', 'none');
            
            // Force reflow to reset animation
            void $preview[0].offsetWidth;
            
            // Apply entrance animation if set
            if (animation && animation !== 'none') {
                console.log('✅ Applying animation:', animation);
                
                // Apply animation to preview area
                $preview.css({
                    'animation-name': animation,
                    'animation-duration': duration + 'ms',
                    'animation-delay': delay + 'ms',
                    'animation-timing-function': easing,
                    'animation-fill-mode': 'both',
                    'animation-iteration-count': repeat === 'infinite' ? 'infinite' : repeat
                });
                
                $preview.addClass('probuilder-animate-' + animation);
                
                console.log('✅ Animation applied to preview area');
            } else {
                console.log('ℹ️ Animation set to none, clearing...');
                // Clear animation if set to none
                $preview.css('animation', 'none');
            }
            
            // Apply hover animation if set
            if (hoverAnimation && hoverAnimation !== 'none') {
                $preview.addClass('probuilder-hover-' + hoverAnimation);
                
                // Add hover animation CSS dynamically
                const hoverStyle = `
                    .probuilder-hover-${hoverAnimation}:hover {
                        animation: ${hoverAnimation} 0.5s ease-in-out !important;
                    }
                `;
                
                // Check if style already exists
                if (!$('#probuilder-hover-styles').length) {
                    $('head').append('<style id="probuilder-hover-styles"></style>');
                }
                
                const $styleTag = $('#probuilder-hover-styles');
                if ($styleTag.text().indexOf(hoverAnimation) === -1) {
                    $styleTag.append(hoverStyle);
                }
                
                console.log('✅ Hover animation added:', hoverAnimation);
            }
            
            console.log('✅ Motion styles applied successfully');
        },
        
        /**
         * Render control
         */
        renderControl: function(key, control, value, element) {
            let html = `<div class="probuilder-control">
                <label>${control.label}</label>`;
            
            switch (control.type) {
                case 'text':
                    html += `<input type="text" class="probuilder-input" data-setting="${key}" value="${value || ''}" placeholder="${control.placeholder || ''}">`;
                    break;
                    
                case 'textarea':
                    html += `<textarea class="probuilder-textarea" data-setting="${key}" rows="5" placeholder="${control.placeholder || ''}">${value || ''}</textarea>`;
                    break;
                    
                case 'editor':
                case 'wysiwyg':
                    const editorId = 'probuilder-editor-' + key + '-' + element.id;
                    html += `<div class="probuilder-wysiwyg-wrapper" style="margin-top: 8px;">
                        <textarea id="${editorId}" class="probuilder-editor-textarea" data-setting="${key}" style="width: 100%; min-height: 200px; display: none;">${value || ''}</textarea>
                        
                        <!-- MS Word/Excel Style Ribbon -->
                        <div class="probuilder-editor-ribbon" style="
                            background: #f8f9fa;
                            border: 1px solid #d1d5db;
                            border-bottom: none;
                            border-radius: 4px 4px 0 0;
                        ">
                            <!-- Font Group -->
                            <div style="
                                padding: 6px 12px;
                                display: flex;
                                gap: 8px;
                                align-items: center;
                                flex-wrap: wrap;
                                border-bottom: 1px solid #e5e7eb;
                            ">
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    <select class="probuilder-editor-format" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 8px;
                                        font-size: 12px;
                                        border-radius: 3px;
                                        cursor: pointer;
                                        min-width: 110px;
                                    ">
                                        <option value="p">¶ Normal</option>
                                        <option value="h1">Heading 1</option>
                                        <option value="h2">Heading 2</option>
                                        <option value="h3">Heading 3</option>
                                        <option value="h4">Heading 4</option>
                                        <option value="h5">Heading 5</option>
                                        <option value="h6">Heading 6</option>
                                        <option value="blockquote">Quote</option>
                                        <option value="pre">Code</option>
                                    </select>
                                    
                                    <select class="probuilder-editor-font" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 8px;
                                        font-size: 12px;
                                        border-radius: 3px;
                                        cursor: pointer;
                                        min-width: 130px;
                                    ">
                                        <option value="inherit">Calibri</option>
                                        <option value="Arial, sans-serif">Arial</option>
                                        <option value="'Times New Roman', serif">Times New Roman</option>
                                        <option value="'Georgia', serif">Georgia</option>
                                        <option value="'Courier New', monospace">Courier New</option>
                                        <option value="'Verdana', sans-serif">Verdana</option>
                                        <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
                                        <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
                                        <option value="'Roboto', sans-serif">Roboto</option>
                                        <option value="'Open Sans', sans-serif">Open Sans</option>
                                        <option value="'Lato', sans-serif">Lato</option>
                                        <option value="'Montserrat', sans-serif">Montserrat</option>
                                        <option value="'Poppins', sans-serif">Poppins</option>
                                    </select>
                                    
                                    <select class="probuilder-editor-size" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 8px;
                                        font-size: 12px;
                                        border-radius: 3px;
                                        cursor: pointer;
                                        width: 65px;
                                    ">
                                        <option value="8px">8</option>
                                        <option value="9px">9</option>
                                        <option value="10px">10</option>
                                        <option value="11px">11</option>
                                        <option value="12px">12</option>
                                        <option value="14px">14</option>
                                        <option value="16px" selected>16</option>
                                        <option value="18px">18</option>
                                        <option value="20px">20</option>
                                        <option value="24px">24</option>
                                        <option value="28px">28</option>
                                        <option value="32px">32</option>
                                        <option value="36px">36</option>
                                        <option value="48px">48</option>
                                    </select>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Text Style Buttons -->
                                <div style="display: flex; gap: 2px; background: #fff; border: 1px solid #d1d5db; border-radius: 3px; padding: 2px;">
                                    <button type="button" class="probuilder-editor-btn" data-cmd="bold" title="Bold (Ctrl+B)" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        font-weight: 700;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">B</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="italic" title="Italic (Ctrl+I)" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        font-style: italic;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">I</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="underline" title="Underline (Ctrl+U)" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        text-decoration: underline;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">U</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="strikethrough" title="Strikethrough" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        text-decoration: line-through;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">abc</button>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Color Pickers - MS Word Style -->
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    <div style="position: relative;">
                                        <button type="button" class="probuilder-editor-color-btn" data-type="foreColor" style="
                                            background: #fff;
                                            border: 1px solid #d1d5db;
                                            width: 32px;
                                            height: 24px;
                                            cursor: pointer;
                                            border-radius: 3px;
                                            position: relative;
                                            padding: 0;
                                        ">
                                            <span style="
                                                font-size: 14px;
                                                font-weight: 700;
                                                color: #000;
                                                line-height: 22px;
                                            ">A</span>
                                            <div class="probuilder-color-indicator" style="
                                                position: absolute;
                                                bottom: 2px;
                                                left: 2px;
                                                right: 2px;
                                                height: 3px;
                                                background: #000;
                                                border-radius: 1px;
                                            "></div>
                                        </button>
                                        <input type="color" class="probuilder-editor-color-input" data-type="foreColor" style="
                                            position: absolute;
                                            opacity: 0;
                                            width: 100%;
                                            height: 100%;
                                            cursor: pointer;
                                            top: 0;
                                            left: 0;
                                        ">
                                    </div>
                                    
                                    <div style="position: relative;">
                                        <button type="button" class="probuilder-editor-color-btn" data-type="backColor" style="
                                            background: #fff;
                                            border: 1px solid #d1d5db;
                                            width: 32px;
                                            height: 24px;
                                            cursor: pointer;
                                            border-radius: 3px;
                                            position: relative;
                                            padding: 0;
                                        ">
                                            <span style="
                                                font-size: 10px;
                                                line-height: 22px;
                                            ">A</span>
                                            <div class="probuilder-bg-indicator" style="
                                                position: absolute;
                                                bottom: 2px;
                                                left: 2px;
                                                right: 2px;
                                                height: 6px;
                                                background: #ffff00;
                                                border-radius: 1px;
                                            "></div>
                                        </button>
                                        <input type="color" class="probuilder-editor-color-input" data-type="backColor" value="#ffff00" style="
                                            position: absolute;
                                            opacity: 0;
                                            width: 100%;
                                            height: 100%;
                                            cursor: pointer;
                                            top: 0;
                                            left: 0;
                                        ">
                                    </div>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Simplified Alignment -->
                                <div style="display: flex; gap: 2px; background: #fff; border: 1px solid #d1d5db; border-radius: 3px; padding: 2px;">
                                    <button type="button" class="probuilder-editor-btn" data-cmd="justifyLeft" title="Align Left" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 8px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        font-size: 14px;
                                    ">☰</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="justifyCenter" title="Center" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 8px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        font-size: 14px;
                                    ">☷</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="justifyRight" title="Align Right" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 8px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        font-size: 14px;
                                    ">☱</button>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Lists & Indent -->
                                <div style="display: flex; gap: 2px;">
                                    <button type="button" class="probuilder-editor-btn" data-cmd="insertUnorderedList" title="Bullet List" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 14px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">•</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="insertOrderedList" title="Numbered List" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 14px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">1.</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="outdent" title="Decrease Indent" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 12px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">←</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="indent" title="Increase Indent" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 12px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">→</button>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Link & Image -->
                                <button type="button" class="probuilder-editor-btn" data-cmd="createLink" title="Insert Link" style="
                                    background: #fff;
                                    border: 1px solid #d1d5db;
                                    padding: 4px 10px;
                                    font-size: 12px;
                                    cursor: pointer;
                                    border-radius: 3px;
                                ">🔗</button>
                                <button type="button" class="probuilder-editor-btn" data-cmd="insertImage" title="Insert Image" style="
                                    background: #fff;
                                    border: 1px solid #d1d5db;
                                    padding: 4px 10px;
                                    font-size: 12px;
                                    cursor: pointer;
                                    border-radius: 3px;
                                ">🖼️</button>
                                
                                <select class="probuilder-editor-more" style="
                                    background: #fff;
                                    border: 1px solid #d1d5db;
                                    padding: 4px 8px;
                                    font-size: 12px;
                                    border-radius: 3px;
                                    cursor: pointer;
                                    min-width: 70px;
                                    margin-left: 4px;
                                ">
                                    <option value="">More ▼</option>
                                    <option value="undo">↶ Undo</option>
                                    <option value="redo">↷ Redo</option>
                                    <option value="unlink">🔗✗ Unlink</option>
                                    <option value="hr">─ Horizontal Line</option>
                                    <option value="table">▦ Insert Table</option>
                                    <option value="subscript">X₂ Subscript</option>
                                    <option value="superscript">X² Superscript</option>
                                    <option value="uppercase">ABC UPPERCASE</option>
                                    <option value="lowercase">abc lowercase</option>
                                    <option value="removeFormat">✗ Clear Formatting</option>
                                    <option value="selectAll">⊞ Select All</option>
                                </select>
                            </div>
                        </div>
                        <div id="${editorId}-editor" contenteditable="true" class="probuilder-editor-content" style="
                            min-height: 200px;
                            padding: 12px;
                            border: 1px solid #dee2e6;
                            background: #fff;
                            border-radius: 0 0 4px 4px;
                            outline: none;
                            font-family: inherit;
                            font-size: 14px;
                            line-height: 1.6;
                        ">${value || ''}</div>
                        <style>
                            /* MS Word/Excel Style Button Hover Effects */
                            .probuilder-editor-btn:hover {
                                background: #e3f2fd !important;
                            }
                            .probuilder-editor-btn:active {
                                background: #bbdefb !important;
                            }
                            
                            /* Color button hover */
                            .probuilder-editor-color-btn:hover {
                                background: #f5f5f5 !important;
                                border-color: #1976d2 !important;
                            }
                            .probuilder-editor-color-btn:active {
                                background: #e0e0e0 !important;
                            }
                            
                            /* Dropdown hover */
                            .probuilder-editor-format:hover,
                            .probuilder-editor-font:hover,
                            .probuilder-editor-size:hover,
                            .probuilder-editor-more:hover {
                                border-color: #1976d2 !important;
                            }
                            
                            /* Editor focus */
                            #${editorId}-editor:focus {
                                border-color: #1976d2;
                                box-shadow: 0 0 0 2px rgba(25,118,210,0.2);
                            }
                            
                            /* Grouped button container */
                            .probuilder-editor-ribbon > div > div[style*="background: #fff"] > button:hover {
                                background: #f5f5f5 !important;
                            }
                            
                            .probuilder-editor-ribbon > div > div[style*="background: #fff"] > button:active {
                                background: #e0e0e0 !important;
                            }
                            
                            #${editorId}-editor blockquote {
                                border-left: 4px solid #92003b;
                                margin: 16px 0;
                                padding: 12px 20px;
                                background: #f9f9f9;
                                font-style: italic;
                                color: #555;
                            }
                            #${editorId}-editor pre {
                                background: #2d2d2d;
                                color: #f8f8f2;
                                padding: 16px;
                                border-radius: 4px;
                                overflow-x: auto;
                                font-family: 'Courier New', monospace;
                                font-size: 13px;
                                line-height: 1.5;
                            }
                            #${editorId}-editor code {
                                background: #f4f4f4;
                                padding: 2px 6px;
                                border-radius: 3px;
                                font-family: 'Courier New', monospace;
                                font-size: 13px;
                                color: #c7254e;
                            }
                            #${editorId}-editor pre code {
                                background: transparent;
                                padding: 0;
                                color: #f8f8f2;
                            }
                            #${editorId}-editor h1, 
                            #${editorId}-editor h2, 
                            #${editorId}-editor h3, 
                            #${editorId}-editor h4, 
                            #${editorId}-editor h5, 
                            #${editorId}-editor h6 {
                                margin-top: 16px;
                                margin-bottom: 8px;
                                font-weight: 600;
                                line-height: 1.3;
                            }
                            #${editorId}-editor h1 { font-size: 32px; }
                            #${editorId}-editor h2 { font-size: 28px; }
                            #${editorId}-editor h3 { font-size: 24px; }
                            #${editorId}-editor h4 { font-size: 20px; }
                            #${editorId}-editor h5 { font-size: 18px; }
                            #${editorId}-editor h6 { font-size: 16px; }
                            #${editorId}-editor ul, 
                            #${editorId}-editor ol {
                                margin: 12px 0;
                                padding-left: 30px;
                            }
                            #${editorId}-editor li {
                                margin: 6px 0;
                            }
                            #${editorId}-editor a {
                                color: #92003b;
                                text-decoration: underline;
                            }
                            #${editorId}-editor img {
                                max-width: 100%;
                                height: auto;
                                border-radius: 4px;
                                margin: 8px 0;
                            }
                            #${editorId}-editor hr {
                                border: none;
                                border-top: 2px solid #ddd;
                                margin: 20px 0;
                            }
                        </style>
                    </div>`;
                    // Initialize editor after DOM insertion
                    setTimeout(() => {
                        const $editor = $(`#${editorId}-editor`);
                        const $textarea = $(`#${editorId}`);
                        
                        // Sync content from contenteditable to textarea
                        const syncContent = () => {
                            $textarea.val($editor.html());
                            element.settings[key] = $editor.html();
                            self.updateElementPreview(element);
                            self.saveData();
                        };
                        
                        // Toolbar buttons
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-btn').on('click', function(e) {
                            e.preventDefault();
                            const cmd = $(this).data('cmd');
                            const value = $(this).data('value') || null;
                            
                            if (cmd === 'createLink') {
                                const url = prompt('Enter URL:', 'https://');
                                if (url) {
                                    document.execCommand(cmd, false, url);
                                }
                            } else if (cmd === 'insertImage') {
                                const url = prompt('Enter image URL:', 'https://');
                                if (url) {
                                    document.execCommand(cmd, false, url);
                                }
                            } else {
                                document.execCommand(cmd, false, value);
                            }
                            $editor.focus();
                            syncContent();
                        });
                        
                        // Format selector (paragraph, headings, etc)
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-format').on('change', function() {
                            const format = $(this).val();
                            document.execCommand('formatBlock', false, format);
                            $editor.focus();
                            syncContent();
                        });
                        
                        // Font family selector
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-font').on('change', function() {
                            const font = $(this).val();
                            document.execCommand('fontName', false, font);
                            $editor.focus();
                            syncContent();
                        });
                        
                        // Font size selector
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-size').on('change', function() {
                            const size = $(this).val();
                            if (!size) return;
                            
                            // Wrap selection in span with font-size
                            if (window.getSelection && window.getSelection().rangeCount > 0) {
                                const selection = window.getSelection();
                                const range = selection.getRangeAt(0);
                                if (!range.collapsed) {
                                    const span = document.createElement('span');
                                    span.style.fontSize = size;
                                    try {
                                        range.surroundContents(span);
                                    } catch(e) {
                                        // If surroundContents fails, use execCommand
                                        document.execCommand('fontSize', false, '3');
                                        $editor.find('font[size="3"]').css('font-size', size);
                                    }
                                } else {
                                    // No selection, apply to next typed text
                                    document.execCommand('fontSize', false, '3');
                                    $editor.find('font[size="3"]').css('font-size', size);
                                }
                            }
                            $editor.focus();
                            syncContent();
                        });
                        
                        // Color pickers - MS Word style
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-color-input').on('change', function() {
                            const color = $(this).val();
                            const type = $(this).data('type'); // foreColor or backColor
                            document.execCommand(type, false, color);
                            
                            // Update indicator color
                            if (type === 'foreColor') {
                                $(this).siblings('.probuilder-color-indicator').css('background', color);
                            } else {
                                $(this).siblings('.probuilder-bg-indicator').css('background', color);
                            }
                            
                            $editor.focus();
                            syncContent();
                        });
                        
                        // Color button click opens color picker
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-color-btn').on('click', function() {
                            $(this).siblings('.probuilder-editor-color-input').click();
                        });
                        
                        // More dropdown
                        $editor.closest('.probuilder-wysiwyg-wrapper').find('.probuilder-editor-more').on('change', function() {
                            const action = $(this).val();
                            if (!action) return;
                            
                            switch (action) {
                                case 'undo':
                                    document.execCommand('undo');
                                    break;
                                case 'redo':
                                    document.execCommand('redo');
                                    break;
                                case 'unlink':
                                    document.execCommand('unlink');
                                    break;
                                case 'hr':
                                    document.execCommand('insertHorizontalRule');
                                    break;
                                case 'table':
                                    const table = '<table border="1" style="border-collapse: collapse; width: 100%;"><tr><td style="padding: 8px;">Cell 1</td><td style="padding: 8px;">Cell 2</td></tr><tr><td style="padding: 8px;">Cell 3</td><td style="padding: 8px;">Cell 4</td></tr></table>';
                                    document.execCommand('insertHTML', false, table);
                                    break;
                                case 'subscript':
                                    document.execCommand('subscript');
                                    break;
                                case 'superscript':
                                    document.execCommand('superscript');
                                    break;
                                case 'uppercase':
                                    const upperSel = window.getSelection();
                                    if (upperSel.rangeCount > 0) {
                                        const upperRange = upperSel.getRangeAt(0);
                                        const upperText = upperRange.toString().toUpperCase();
                                        upperRange.deleteContents();
                                        upperRange.insertNode(document.createTextNode(upperText));
                                    }
                                    break;
                                case 'lowercase':
                                    const lowerSel = window.getSelection();
                                    if (lowerSel.rangeCount > 0) {
                                        const lowerRange = lowerSel.getRangeAt(0);
                                        const lowerText = lowerRange.toString().toLowerCase();
                                        lowerRange.deleteContents();
                                        lowerRange.insertNode(document.createTextNode(lowerText));
                                    }
                                    break;
                                case 'removeFormat':
                                    document.execCommand('removeFormat');
                                    break;
                                case 'selectAll':
                                    document.execCommand('selectAll');
                                    break;
                            }
                            
                            $(this).val(''); // Reset dropdown
                            $editor.focus();
                            syncContent();
                        });
                        
                        // Keyboard shortcuts
                        $editor.on('keydown', function(e) {
                            // Ctrl+B for bold
                            if (e.ctrlKey && e.key === 'b') {
                                e.preventDefault();
                                document.execCommand('bold');
                                syncContent();
                            }
                            // Ctrl+I for italic
                            if (e.ctrlKey && e.key === 'i') {
                                e.preventDefault();
                                document.execCommand('italic');
                                syncContent();
                            }
                            // Ctrl+U for underline
                            if (e.ctrlKey && e.key === 'u') {
                                e.preventDefault();
                                document.execCommand('underline');
                                syncContent();
                            }
                            // Ctrl+Z for undo
                            if (e.ctrlKey && e.key === 'z' && !e.shiftKey) {
                                e.preventDefault();
                                document.execCommand('undo');
                                syncContent();
                            }
                            // Ctrl+Y or Ctrl+Shift+Z for redo
                            if ((e.ctrlKey && e.key === 'y') || (e.ctrlKey && e.shiftKey && e.key === 'z')) {
                                e.preventDefault();
                                document.execCommand('redo');
                                syncContent();
                            }
                        });
                        
                        // Sync on input
                        $editor.on('input paste keyup', function() {
                            syncContent();
                        });
                    }, 100);
                    break;
                    
                case 'select':
                    // Special: Circular gradient angle control
                    if (key === 'background_gradient_angle') {
                        const angleValue = parseFloat(value) || 135;
                        const circleSize = 120;
                        const center = circleSize / 2;
                        const radius = 40;
                        const angleRad = (angleValue - 90) * Math.PI / 180;
                        const dotX = center + radius * Math.cos(angleRad);
                        const dotY = center + radius * Math.sin(angleRad);
                        
                        html += `
                            <div class="probuilder-gradient-angle-wrapper" style="position: relative; width: ${circleSize}px; height: ${circleSize + 30}px; margin: 10px auto;">
                                <svg width="${circleSize}" height="${circleSize}" style="position: absolute; top: 0; left: 0;">
                                    <circle cx="${center}" cy="${center}" r="${radius}" fill="none" stroke="#d1d5db" stroke-width="2"/>
                                    <line x1="${center}" y1="${center}" x2="${dotX}" y2="${dotY}" stroke="#92003b" stroke-width="2"/>
                                    <circle cx="${dotX}" cy="${dotY}" r="8" fill="#92003b" cursor="move"/>
                                </svg>
                                <input type="range" class="probuilder-gradient-angle-slider" data-setting="${key}" min="0" max="360" step="1" value="${angleValue}" style="
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: ${circleSize}px;
                                    height: ${circleSize}px;
                                    opacity: 0;
                                    cursor: pointer;
                                    z-index: 10;
                                " title="Drag to rotate gradient angle">
                                <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); font-size: 11px; color: #71717a; white-space: nowrap;">
                                    <span class="probuilder-angle-value">${Math.round(angleValue)}°</span>
                                </div>
                            </div>
                        `;
                    } else {
                        html += `<select class="probuilder-select" data-setting="${key}">`;
                        Object.keys(control.options).forEach(optKey => {
                            html += `<option value="${optKey}" ${value === optKey ? 'selected' : ''}>${control.options[optKey]}</option>`;
                        });
                        html += `</select>`;
                    }
                    break;
                    
                case 'grid_preset':
                    const gridPatterns = this.getGridPatterns();
                    html += `<div class="probuilder-grid-presets" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 12px;">`;
                    
                    gridPatterns.forEach(pattern => {
                        const isSelected = value === pattern.id || (!value && pattern.id === 'pattern-1');
                        html += `
                            <div class="probuilder-grid-preset-item ${isSelected ? 'selected' : ''}" 
                                 data-setting="${key}" 
                                 data-pattern="${pattern.id}"
                                 style="
                                     cursor: pointer;
                                     padding: 12px;
                                     border: 2px solid ${isSelected ? '#007cba' : '#ddd'};
                                     border-radius: 8px;
                                     background: ${isSelected ? 'rgba(0,124,186,0.05)' : '#fff'};
                                     transition: all 0.2s;
                                 "
                                 title="${pattern.name}">
                                <div style="
                                    width: 100%;
                                    height: 80px;
                                    background: #f0f0f1;
                                    border-radius: 4px;
                                    padding: 4px;
                                    margin-bottom: 8px;
                                ">
                                    ${pattern.svg}
                                </div>
                                <div style="
                                    text-align: center;
                                    font-size: 10px;
                                    color: ${isSelected ? '#007cba' : '#666'};
                                    font-weight: ${isSelected ? '600' : '400'};
                                ">${pattern.name}</div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                    break;
                    
                case 'color':
                    html += `<div style="display: flex; gap: 10px; align-items: center;">
                        <input type="color" class="probuilder-color" data-setting="${key}" value="${value || '#000000'}" style="width: 60px;">
                        <input type="text" class="probuilder-input" value="${value || '#000000'}" style="flex: 1; font-family: monospace;" readonly>
                    </div>`;
                    break;
                    
                case 'slider':
                    const unit = control.unit || 'px';
                    html += `<div style="display: flex; align-items: center; gap: 8px;">
                        <input type="range" class="probuilder-slider" data-setting="${key}" min="${control.range?.px?.min || 0}" max="${control.range?.px?.max || 100}" step="${control.range?.px?.step || 1}" value="${value || control.default}">
                        <span class="probuilder-slider-value">${value || control.default}${unit}</span>
                    </div>`;
                    break;
                    
                case 'dimensions':
                    const dims = value || control.default || {top: 0, right: 0, bottom: 0, left: 0};
                    html += `<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Top</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="top" value="${dims.top || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Right</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="right" value="${dims.right || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Bottom</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="bottom" value="${dims.bottom || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Left</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="left" value="${dims.left || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                    </div>`;
                    break;
                    
                case 'border':
                    const border = value || control.default || {width: 1, style: 'solid', color: '#000000'};
                    html += `<div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-border="width" value="${border.width || 1}" placeholder="Width" style="width: 70px;">
                        <select class="probuilder-select" data-setting="${key}" data-border="style" style="flex: 1;">
                            <option value="solid" ${border.style === 'solid' ? 'selected' : ''}>Solid</option>
                            <option value="dashed" ${border.style === 'dashed' ? 'selected' : ''}>Dashed</option>
                            <option value="dotted" ${border.style === 'dotted' ? 'selected' : ''}>Dotted</option>
                            <option value="double" ${border.style === 'double' ? 'selected' : ''}>Double</option>
                        </select>
                        <input type="color" class="probuilder-color" data-setting="${key}" data-border="color" value="${border.color || '#000000'}" style="width: 60px;">
                    </div>`;
                    break;
                    
                case 'box-shadow':
                    const shadow = value || control.default || {x: 0, y: 4, blur: 10, color: 'rgba(0,0,0,0.1)'};
                    html += `<div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="x" value="${shadow.x || 0}" placeholder="X" style="width: 60px; padding: 6px 8px; font-size: 11px;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="y" value="${shadow.y || 0}" placeholder="Y" style="width: 60px; padding: 6px 8px; font-size: 11px;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="blur" value="${shadow.blur || 10}" placeholder="Blur" style="width: 60px; padding: 6px 8px; font-size: 11px;">
                        <input type="color" class="probuilder-color" data-setting="${key}" data-shadow="color" value="${shadow.color || '#000000'}" style="width: 40px; height: 32px; padding: 0; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer;">
                    </div>`;
                    break;
                    
                case 'switcher':
                    const switcherValue = value || control.default || 'no';
                    const isOn = (switcherValue === 'yes' || switcherValue === true || switcherValue === 'on');
                    html += `<label class="probuilder-switcher" style="
                        display: inline-flex;
                        align-items: center;
                        cursor: pointer;
                        user-select: none;
                    ">
                        <input type="checkbox" class="probuilder-switcher-input" data-setting="${key}" ${isOn ? 'checked' : ''} style="display: none;">
                        <span class="probuilder-switcher-track" style="
                            position: relative;
                            width: 44px;
                            height: 24px;
                            background: ${isOn ? '#92003b' : '#cbd5e1'};
                            border-radius: 12px;
                            transition: background 0.3s;
                            display: inline-block;
                        ">
                            <span class="probuilder-switcher-thumb" style="
                                position: absolute;
                                top: 2px;
                                left: ${isOn ? '22px' : '2px'};
                                width: 20px;
                                height: 20px;
                                background: #ffffff;
                                border-radius: 10px;
                                transition: left 0.3s;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            "></span>
                        </span>
                    </label>`;
                    break;
                    
                case 'typography':
                    const typo = value || control.default || {family: 'inherit', size: 16, weight: 400, lineHeight: 1.5};
                    html += `<div style="display: flex; flex-direction: column; gap: 12px;">
                        <select class="probuilder-select" data-setting="${key}" data-typo="family">
                            <option value="inherit" ${typo.family === 'inherit' ? 'selected' : ''}>Default</option>
                            <option value="Arial, sans-serif" ${typo.family === 'Arial, sans-serif' ? 'selected' : ''}>Arial</option>
                            <option value="'Georgia', serif" ${typo.family === 'Georgia' ? 'selected' : ''}>Georgia</option>
                            <option value="'Times New Roman', serif" ${typo.family === 'Times New Roman' ? 'selected' : ''}>Times New Roman</option>
                            <option value="'Roboto', sans-serif" ${typo.family === 'Roboto' ? 'selected' : ''}>Roboto</option>
                        </select>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                            <input type="number" class="probuilder-input" data-setting="${key}" data-typo="size" value="${typo.size || 16}" placeholder="Size">
                            <select class="probuilder-select" data-setting="${key}" data-typo="weight">
                                <option value="300" ${typo.weight == 300 ? 'selected' : ''}>Light</option>
                                <option value="400" ${typo.weight == 400 ? 'selected' : ''}>Normal</option>
                                <option value="600" ${typo.weight == 600 ? 'selected' : ''}>Semi Bold</option>
                                <option value="700" ${typo.weight == 700 ? 'selected' : ''}>Bold</option>
                            </select>
                        </div>
                        <input type="number" class="probuilder-input" data-setting="${key}" data-typo="lineHeight" value="${typo.lineHeight || 1.5}" step="0.1" placeholder="Line Height">
                    </div>`;
                    break;
                    
                case 'url':
                    html += `<input type="url" class="probuilder-input" data-setting="${key}" value="${value || ''}" placeholder="${control.placeholder || 'https://'}">`;
                    break;
                    
                case 'number':
                    html += `<input type="number" class="probuilder-input" data-setting="${key}" value="${value || ''}" placeholder="${control.placeholder || ''}">`;
                    break;
                    
                case 'angle':
                    const angleValue = value || control.default || 0;
                    const angleId = 'angle-' + key + '-' + Date.now();
                    
                    // Calculate handle position ON THE BORDER (radius = 45px on 100px circle)
                    const angleRad = (angleValue - 90) * Math.PI / 180;
                    const handleX = 50 + 45 * Math.cos(angleRad);  // 50 = center, 45 = radius
                    const handleY = 50 + 45 * Math.sin(angleRad);
                    
                    html += `
                        <div class="probuilder-angle-picker" style="display: flex; align-items: center; gap: 15px;">
                            <div class="angle-circle-wrapper" id="${angleId}-wrapper" 
                                 style="position: relative; 
                                        width: 100px; 
                                        height: 100px; 
                                        cursor: pointer;
                                        user-select: none;">
                                <svg width="100" height="100" viewBox="0 0 100 100" style="display: block;">
                                    <!-- Background circle -->
                                    <circle cx="50" cy="50" r="45" fill="white" stroke="#d1d5db" stroke-width="2"/>
                                    
                                    <!-- Angle indicator line (from center to edge) -->
                                    <line x1="50" y1="50" x2="${handleX}" y2="${handleY}" 
                                          stroke="#92003b" stroke-width="2" 
                                          id="${angleId}-line"
                                          style="transition: all 0.1s ease;"/>
                                    
                                    <!-- Gradient arc (shows angle) -->
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#92003b" stroke-width="4" 
                                            stroke-dasharray="${(angleValue / 360) * 283} 283" 
                                            transform="rotate(-90 50 50)"
                                            id="${angleId}-arc"
                                            style="transition: stroke-dasharray 0.2s ease; opacity: 0.3;"/>
                                    
                                    <!-- Center dot -->
                                    <circle cx="50" cy="50" r="4" fill="#92003b"/>
                                </svg>
                                
                                <!-- Draggable handle (BIGGER and more visible) -->
                                <div class="angle-handle" id="${angleId}-handle" 
                                     style="position: absolute; 
                                            width: 24px; 
                                            height: 24px; 
                                            background: #92003b; 
                                            border: 4px solid white; 
                                            border-radius: 50%; 
                                            cursor: grab;
                                            box-shadow: 0 3px 12px rgba(146, 0, 59, 0.4);
                                            top: ${handleY}px; 
                                            left: ${handleX}px;
                                            transform: translate(-50%, -50%);
                                            transition: all 0.1s ease;
                                            z-index: 10;">
                                </div>
                                
                                <!-- Angle display in center -->
                                <div style="position: absolute; 
                                            top: 50%; 
                                            left: 50%; 
                                            transform: translate(-50%, -50%);
                                            font-size: 14px;
                                            font-weight: 700;
                                            color: #92003b;
                                            pointer-events: none;
                                            text-shadow: 0 0 3px white, 0 0 5px white;">
                                    ${Math.round(angleValue)}°
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <input type="number" class="probuilder-input probuilder-angle-input" 
                                       id="${angleId}-input"
                                       data-setting="${key}" 
                                       value="${angleValue}" 
                                       min="0" 
                                       max="360" 
                                       style="width: 100%; text-align: center; font-weight: 600; font-size: 16px; padding: 8px;">
                                <div style="margin-top: 10px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;">
                                    <button type="button" class="angle-preset-btn" data-angle="0" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">0°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="45" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">45°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="90" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">90°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="135" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">135°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="180" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">180°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="225" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">225°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="270" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">270°</button>
                                    <button type="button" class="angle-preset-btn" data-angle="315" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">315°</button>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'code':
                    html += `<textarea class="probuilder-textarea probuilder-code-editor" data-setting="${key}" rows="8" placeholder="${control.placeholder || ''}" style="font-family: 'Courier New', monospace; font-size: 12px; background: #f8f9fa; border: 1px solid #d1d5db; padding: 10px;">${value || ''}</textarea>`;
                    if (control.description) {
                        html += `<small style="display: block; margin-top: 5px; color: #71717a; font-size: 11px;">${control.description}</small>`;
                    }
                    break;
                    
                case 'switcher':
                    html += `<label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" data-setting="${key}" ${value === 'yes' ? 'checked' : ''} style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="font-size: 12px; color: #71717a;">Enable</span>
                    </label>`;
                    break;
                    
                case 'icon':
                    html += `<input type="text" class="probuilder-input" data-setting="${key}" value="${value || ''}" placeholder="fa fa-star">
                    <small style="display: block; margin-top: 8px; color: #71717a; font-size: 11px;">Font Awesome icon class (e.g., fa fa-star)</small>`;
                    break;
                    
                case 'media':
                    html += `<button type="button" class="probuilder-media-btn probuilder-btn probuilder-btn-secondary" data-setting="${key}" style="width: 100%;">
                        <i class="dashicons dashicons-format-image"></i> Choose Image
                    </button>
                    <div class="probuilder-media-preview">
                        ${value?.url ? `<img src="${value.url}" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px; border: 1px solid #e6e9ec;">` : ''}
                    </div>`;
                    break;
                    
                case 'repeater':
                    const items = Array.isArray(value) ? value : (control.default || []);
                    const fields = control.fields || [];
                    
                    html += `<div class="probuilder-repeater" data-setting="${key}" style="border: 1px solid #e6e9ec; border-radius: 4px; padding: 10px; background: #fafbfc;">`;
                    
                    // Items list
                    html += `<div class="probuilder-repeater-items">`;
                    items.forEach((item, index) => {
                        html += `<div class="probuilder-repeater-item" data-index="${index}" style="background: #ffffff; border: 1px solid #e6e9ec; border-radius: 4px; padding: 12px; margin-bottom: 8px; position: relative;">`;
                        
                        // Item header with title and controls
                        const firstField = fields[0];
                        const itemTitle = item[firstField?.name] || `Item #${index + 1}`;
                        html += `<div class="probuilder-repeater-item-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #e6e9ec;">`;
                        html += `<div style="display: flex; align-items: center; gap: 8px;">`;
                        html += `<span class="probuilder-repeater-handle" style="cursor: move; color: #71717a;"><i class="dashicons dashicons-menu"></i></span>`;
                        html += `<strong style="font-size: 13px;">${itemTitle}</strong>`;
                        html += `</div>`;
                        html += `<div style="display: flex; gap: 4px;">`;
                        html += `<button class="probuilder-repeater-toggle" type="button" style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: #92003b;"><i class="dashicons dashicons-arrow-down-alt2"></i></button>`;
                        html += `<button class="probuilder-repeater-delete" type="button" style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: #dc2626;"><i class="dashicons dashicons-trash"></i></button>`;
                        html += `</div>`;
                        html += `</div>`;
                        
                        // Item fields (collapsed by default)
                        html += `<div class="probuilder-repeater-item-fields" style="display: none;">`;
                        fields.forEach(field => {
                            const fieldValue = item[field.name] || field.default || '';
                            html += `<div style="margin-bottom: 10px;">`;
                            html += `<label style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 500;">${field.label}</label>`;
                            
                            if (field.type === 'textarea') {
                                html += `<textarea class="probuilder-input" data-field="${field.name}" rows="3" style="width: 100%; padding: 8px; font-size: 12px;">${fieldValue}</textarea>`;
                            } else {
                                html += `<input type="text" class="probuilder-input" data-field="${field.name}" value="${fieldValue}" style="width: 100%; padding: 8px; font-size: 12px;">`;
                            }
                            html += `</div>`;
                        });
                        html += `</div>`;
                        
                        html += `</div>`;
                    });
                    html += `</div>`;
                    
                    // Add new item button
                    html += `<button type="button" class="probuilder-repeater-add" style="width: 100%; padding: 10px; background: #92003b; color: #ffffff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="dashicons dashicons-plus-alt2" style="font-size: 16px;"></i>
                        <span>Add Item</span>
                    </button>`;
                    
                    html += `</div>`;
                    break;
                    
                default:
                    html += `<input type="text" class="probuilder-input" data-setting="${key}" value="${value || ''}">`;
            }
            
            html += `</div>`;
            
            const $control = $(html);
            
            // Slider value update (visual only)
            $control.find('.probuilder-slider').on('input', function() {
                const unit = control.unit || 'px';
                $control.find('.probuilder-slider-value').text($(this).val() + unit);
            });
            
            // Color picker text sync (visual only)
            $control.find('input[type="color"]').on('input', function() {
                $(this).siblings('input[type="text"]').val($(this).val());
            });
            
            // Switcher toggle (visual only)
            $control.find('.probuilder-switcher-input').on('change', function() {
                const isChecked = $(this).is(':checked');
                const $track = $(this).siblings('.probuilder-switcher-track');
                const $thumb = $track.find('.probuilder-switcher-thumb');
                
                $track.css('background', isChecked ? '#92003b' : '#cbd5e1');
                $thumb.css('left', isChecked ? '22px' : '2px');
            });
            
            // Media uploader button
            $control.find('.probuilder-media-btn').on('click', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const settingKey = $btn.data('setting');
                
                // WordPress media uploader
                if (typeof wp !== 'undefined' && wp.media) {
                    const mediaUploader = wp.media({
                        title: 'Choose Image',
                        button: { text: 'Select Image' },
                        multiple: false
                    });
                    
                    mediaUploader.on('select', function() {
                        const attachment = mediaUploader.state().get('selection').first().toJSON();
                        
                        // Update element settings
                        if (element) {
                            element.settings[settingKey] = {
                                id: attachment.id,
                                url: attachment.url
                            };
                            
                            // Update preview
                            $btn.next('.probuilder-media-preview').html(
                                `<img src="${attachment.url}" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px; border: 1px solid #e6e9ec;">`
                            );
                            
                            // Force immediate element preview update (critical for background_image)
                            console.log('✅ Media updated:', settingKey, attachment.url);
                            ProBuilder.updateElementPreview(element);
                        }
                    });
                    
                    mediaUploader.open();
                } else {
                    // Fallback: prompt for URL
                    const url = prompt('Enter image URL:');
                    if (url && element) {
                        element.settings[settingKey] = { url: url };
                        $btn.next('.probuilder-media-preview').html(
                            `<img src="${url}" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px; border: 1px solid #e6e9ec;">`
                        );
                        console.log('✅ Media URL set:', settingKey, url);
                        ProBuilder.updateElementPreview(element);
                    }
                }
            });
            
            return $control;
        },
        
        /**
         * Close settings panel
         */
        closeSettings: function() {
            // Panel stays visible - just clear selection and show placeholder
            $('.probuilder-element').removeClass('selected');
            this.selectedElement = null;
            this.showSettingsPlaceholder();
        },
        
        /**
         * Show settings placeholder
         */
        showSettingsPlaceholder: function() {
            console.log('Showing settings placeholder');
            $('#probuilder-settings-title').text('Settings');
            $('#probuilder-settings-content').html(`
                <div class="probuilder-settings-placeholder" style="display: block;">
                    <i class="dashicons dashicons-admin-settings"></i>
                    <h4>Element Settings</h4>
                    <p>Click on any element in the canvas to edit its settings</p>
                    <div style="margin-top: 30px; padding: 20px; background: #f4f4f5; border-radius: 4px; text-align: left;">
                        <p style="font-size: 12px; color: #71717a; margin: 0 0 10px 0;"><strong>Quick Tips:</strong></p>
                        <ul style="font-size: 11px; color: #71717a; margin: 0; padding-left: 20px; line-height: 1.8;">
                            <li>Click "Edit" button (pencil icon) on elements</li>
                            <li>Use Content/Style/Advanced tabs</li>
                            <li>Changes update instantly</li>
                            <li>Resize this panel by dragging left edge</li>
                        </ul>
                    </div>
                </div>
            `);
        },
        
        /**
         * Update element preview
         */
        updateElementPreview: function(element) {
            console.log('Updating preview for:', element.widgetType, element.id);
            console.log('Current settings:', element.settings);
            
            const $element = $(`.probuilder-element[data-id="${element.id}"]`);
            
            if ($element.length === 0) {
                console.error('Element not found in DOM:', element.id);
                return;
            }
            
            const newPreview = this.generatePreview(element);
            $element.find('.probuilder-element-preview').html(newPreview);
            
            // Re-apply spacing and all common styles on preview container
            const spacingAfter = this.getSpacingStyles(element.settings || {});
            const commonAfter = this.getCommonInlineStyles(element.settings || {});
            const combinedStyleStr = [spacingAfter, commonAfter].filter(Boolean).join('; ');
            if (combinedStyleStr) {
                const $preview = $element.find('.probuilder-element-preview');
                $preview.attr('style', (($preview.attr('style') || '') + '; ' + combinedStyleStr).trim());
            }
            
            // Apply responsive visibility after preview update
            this.applyResponsiveVisibility(element, $element);
            
            // Apply motion/animation styles if set
            this.applyMotionStyles(element, $element);
            
            // Save to history with debounce
            clearTimeout(this.historyDebounceTimer);
            this.historyDebounceTimer = setTimeout(() => {
                this.saveHistory();
            }, 1000); // Save 1 second after last change
            
            // Grid-based elements need a full re-render to restore drag/drop bindings
            if (element.widgetType === 'grid-layout' || element.widgetType === 'container-2') {
                console.log('🔁 Re-rendering grid-based element to refresh interactions:', element.id);
                const insertBefore = $element.next()[0];
                $element.remove();
                this.renderElement(element, insertBefore);
                return;
            }
            
            // Re-initialize drop zones if it's a container
            if (element.widgetType === 'container') {
                const self = this;
                
                setTimeout(function() {
                    console.log('🔧 Re-initializing container interactivity');
                    
                    // Make droppable
                    self.makeContainersDroppable();
                    
                    // Find and attach click handlers to drop zones
                    const $dropZones = $element.find('.probuilder-drop-zone');
                    console.log('Found', $dropZones.length, 'drop zones in container');
                    
                    $dropZones.each(function() {
                        const $zone = $(this);
                        const containerId = $zone.data('container-id');
                        const columnIndex = $zone.data('column-index');
                        
                        console.log('Attaching click to drop zone:', containerId, 'column:', columnIndex);
                        
                        $zone.off('click').on('click', function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            if (self.isGridCellResizing) {
                                console.log('⏸️ Reinitialized drop zone click ignored - grid cell resizing');
                                return false;
                            }
                            console.log('✅ Drop zone clicked in container:', containerId, 'column:', columnIndex);
                            self.showWidgetTemplateSelector(containerId, columnIndex);
                            return false;
                        });
                    });
                    
                    // Re-attach resize handlers for container columns
                    const $resizeHandles = $element.find('.column-resize-handle');
                    console.log('Found', $resizeHandles.length, 'resize handles for re-initialization');
                    
                    $element.off('mousedown.columnResize', '.column-resize-handle');
                    $element.on('mousedown.columnResize', '.column-resize-handle', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        const columnIndex = $(this).data('column-index');
                        const direction = $(this).data('direction');
                        console.log('🎯 Container column resize started (re-initialized):', columnIndex, direction);
                        
                        const containerElement = self.elements.find(el => el.id === element.id);
                        if (!containerElement) {
                            console.error('Container element not found:', element.id);
                            return;
                        }
                        
                        $(document).on('click.columnResizePrevent', function(clickEvent) {
                            clickEvent.preventDefault();
                            clickEvent.stopPropagation();
                            $(document).off('click.columnResizePrevent');
                        });
                        
                        self.startContainerColumnResize(containerElement, columnIndex, direction, e);
                    });
                    
                    console.log('✅ Container click handlers and resize handlers attached');
                }, 50);
            }
            
            // Re-initialize tabs if it's a tabs widget
            if (element.widgetType === 'tabs') {
                const self = this;
                setTimeout(function() {
                    self.makeTabsDroppable(element);
                    self.attachTabNestedHandlers(element, $element);
                }, 50);
            }
            
            // Re-initialize carousel if it's a carousel widget
            if (element.widgetType === 'carousel') {
                const self = this;
                setTimeout(function() {
                    console.log('🎠 Re-initializing carousel:', element.id);
                    self.initializeCarousel(element, $element);
                }, 50);
            }
            
            console.log('Preview updated successfully');
        },
        
        /**
         * Update container with children (re-render with full interactivity)
         */
        updateContainerWithChildren: function(containerElement) {
            console.log('Re-rendering container with children:', containerElement.id, 'Children count:', containerElement.children ? containerElement.children.length : 0);
            
            // Find the container in DOM
            const $container = $(`.probuilder-element[data-id="${containerElement.id}"]`);
            if ($container.length === 0) {
                console.error('Container not found in DOM');
                return;
            }
            
            // FORCE full regeneration with depth 0 to ensure all children are rendered
            const preview = this.generatePreview(containerElement, 0);
            
            // Replace entire preview content
            const $preview = $container.find('.probuilder-element-preview');
            if ($preview.length > 0) {
                $preview.html(preview);
                console.log('✅ Container preview updated with', containerElement.children.length, 'children');
            } else {
                console.error('❌ Preview element not found for container');
            }
            
            // Small delay to ensure DOM is fully updated
            const self = this;
            setTimeout(function() {
                // Attach event handlers to nested elements
                self.attachNestedElementHandlers(containerElement, $container);
                
                // Attach event handlers to drop zones
                $container.find('.probuilder-drop-zone').off('click').on('click', function(e) {
                    e.stopPropagation();
                    const containerId = $(this).data('container-id');
                    const columnIndex = $(this).data('column-index');
                    if (self.isGridCellResizing) {
                        console.log('⏸️ Container drop zone click ignored - grid cell resizing');
                        return false;
                    }
                    console.log('Drop zone clicked in container:', containerId, 'column:', columnIndex);
                    self.showWidgetTemplateSelector(containerId, columnIndex);
                });
                
                // Re-initialize jQuery UI drop zones with additional delay
                setTimeout(function() {
                    // Reinitialize container drop zones
                    self.makeContainersDroppable();
                    
                    // Check and reinitialize sidebar widgets if needed
                    const $widgets = $('.probuilder-widget');
                    let needsReinit = false;
                    $widgets.each(function() {
                        if (!$(this).data('ui-draggable')) {
                            needsReinit = true;
                            return false;
                        }
                    });
                    
                    if (needsReinit) {
                        console.log('⚠️ Sidebar widgets lost, reinitializing...');
                        self.reinitializeSidebarWidgets();
                    }
                    
                    // Check and reinitialize preview area if needed
                    const $previewArea = $('#probuilder-preview-area');
                    if (!$previewArea.data('ui-droppable')) {
                        console.log('⚠️ Preview area droppable lost, reinitializing...');
                        self.reinitializePreviewArea();
                    }
                    
                    console.log('✅ All drag & drop components verified and reinitialized');
                }, 100);
                
                console.log('✅ Container fully updated with interactive children and drop zones');
            }, 50);
        },
        
        /**
         * Attach event handlers to nested elements in containers
         */
        attachNestedElementHandlers: function(containerElement, $containerDOM) {
            const self = this;
            
            if (!containerElement.children || containerElement.children.length === 0) {
                return;
            }
            
            console.log('🔧 Attaching handlers to', containerElement.children.length, 'nested elements');
            
            // First, clean up any existing handlers and jQuery UI instances
            $containerDOM.find('.probuilder-nested-element').each(function() {
                if ($(this).data('ui-draggable')) {
                    $(this).draggable('destroy');
                }
            });
            
            $containerDOM.find('.probuilder-column').each(function() {
                if ($(this).data('ui-droppable')) {
                    $(this).droppable('destroy');
                }
            });
            
            // Attach click handlers to each child element
            containerElement.children.forEach(childElement => {
                const $childPreview = $containerDOM.find(`.probuilder-nested-element[data-id="${childElement.id}"]`);
                
                if ($childPreview.length === 0) {
                    console.warn('⚠️ Child element not found in DOM:', childElement.id);
                    return;
                }
                
                console.log('✅ Found nested element:', childElement.id);
                
                // Clear all previous handlers
                $childPreview.find('.probuilder-nested-edit').off();
                $childPreview.find('.probuilder-nested-delete').off();
                $childPreview.off('click');
                
                // Edit handler - use both mouseup and click for reliability
                $childPreview.find('.probuilder-nested-edit').on('mouseup click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    console.log('✏️ Edit button clicked for:', childElement.id);
                    self.openSettings(childElement);
                    return false;
                });
                
                // Delete handler - use both mouseup and click for reliability
                $childPreview.find('.probuilder-nested-delete').on('mouseup click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    console.log('🗑️ Delete button clicked for:', childElement.id);
                    self.deleteNestedElement(containerElement, childElement.id);
                    return false;
                });
                
                // Click to select (but not on buttons)
                $childPreview.on('click', function(e) {
                    if (!$(e.target).closest('.probuilder-nested-controls').length) {
                        e.stopPropagation();
                        console.log('👆 Nested element clicked:', childElement.id);
                        self.selectElement(childElement);
                    }
                });
            });
            
            // Small delay before setting up draggable to ensure button handlers are fully attached
            setTimeout(function() {
                console.log('🎯 Setting up draggable for nested elements');
                
                // Make nested elements draggable
                $containerDOM.find('.probuilder-nested-element').each(function() {
                    const $nestedEl = $(this);
                    const childId = $nestedEl.data('id');
                    const childElement = containerElement.children.find(c => c.id === childId);
                    
                    if (!childElement) return;
                    
                    // Make draggable via handle
                    $nestedEl.draggable({
                        handle: '.probuilder-nested-drag',
                        helper: 'clone',
                        cursor: 'move',
                        revert: 'invalid',
                        zIndex: 10000,
                        delay: 100, // Delay to distinguish from clicks
                        start: function(event, ui) {
                            console.log('🎯 Started dragging:', childId);
                            $(this).css('opacity', '0.5');
                            ui.helper.css({
                                'width': $(this).width(),
                                'background': '#ffffff',
                                'border': '2px solid #92003b',
                                'box-shadow': '0 5px 15px rgba(0,0,0,0.3)'
                            });
                        },
                        stop: function(event, ui) {
                            console.log('🎯 Stopped dragging:', childId);
                            $(this).css('opacity', '1');
                        }
                    });
                });
                
                // Make columns droppable
                $containerDOM.find('.probuilder-column').each(function(colIndex) {
                    $(this).droppable({
                        accept: '.probuilder-nested-element',
                        tolerance: 'pointer',
                        hoverClass: 'probuilder-drop-hover',
                        drop: function(event, ui) {
                            const droppedId = ui.draggable.data('id');
                            console.log('🎯 Dropped:', droppedId, 'into column:', colIndex);
                            
                            const childIndex = containerElement.children.findIndex(c => c.id === droppedId);
                            if (childIndex === -1) return;
                            
                            const movedElement = containerElement.children.splice(childIndex, 1)[0];
                            containerElement.children.splice(colIndex, 0, movedElement);
                            
                            console.log('✅ Moved to column', colIndex);
                            self.updateContainerWithChildren(containerElement);
                        }
                    });
                });
                
                // Make canvas droppable for moving elements out
                if (!$('#probuilder-preview-area').data('nested-droppable-init')) {
                    $('#probuilder-preview-area').droppable({
                        accept: '.probuilder-nested-element',
                        tolerance: 'pointer',
                        drop: function(event, ui) {
                            const droppedId = ui.draggable.data('id');
                            console.log('🎯 Dropped onto canvas:', droppedId);
                            
                            const childIndex = containerElement.children.findIndex(c => c.id === droppedId);
                            if (childIndex === -1) return;
                            
                            const movedElement = containerElement.children.splice(childIndex, 1)[0];
                            self.elements.push(movedElement);
                            
                            console.log('✅ Moved out of container');
                            self.updateContainerWithChildren(containerElement);
                            self.render();
                        }
                    });
                    $('#probuilder-preview-area').data('nested-droppable-init', true);
                }
                
                console.log('✅ All nested elements are now fully interactive');
            }, 100);
        },
        
        /**
         * Delete nested element from container
         */
        deleteNestedElement: function(containerElement, childId) {
            console.log('Deleting nested element:', childId, 'from container:', containerElement.id);
            
            if (!containerElement.children) return;
            
            containerElement.children = containerElement.children.filter(child => child.id !== childId);
            
            console.log('Container now has', containerElement.children.length, 'children');
            
            // Re-render container
            this.updateContainerWithChildren(containerElement);
        },
        
        /**
         * Make tabs droppable
         */
        makeTabsDroppable: function(tabsElement) {
            const self = this;
            const $tabsContainer = $(`[data-element-id="${tabsElement.id}"][data-tabs-id]`);
            
            // Make each tab content area droppable
            $tabsContainer.find('.probuilder-tab-drop-zone').each(function() {
                const $zone = $(this);
                const tabIndex = parseInt($zone.data('tab-index'));
                
                // Make droppable
                $zone.droppable({
                    accept: '.probuilder-widget',
                    tolerance: 'pointer',
                    hoverClass: 'probuilder-drop-hover',
                    greedy: true, // Prevent parent elements from also handling the drop
                    drop: function(event, ui) {
                        event.stopPropagation();
                        event.preventDefault();
                        self.isNestedDropInProgress = true;
                        const finishNestedDrop = () => {
                            setTimeout(() => {
                                self.isNestedDropInProgress = false;
                            }, 0);
                        };
                        const widgetName = ui.draggable.data('widget');
                        console.log('Widget dropped into tab:', widgetName, 'Tab index:', tabIndex);
                        
                        const widget = self.widgets.find(w => w.name === widgetName);
                        if (!widget) {
                            finishNestedDrop();
                            return;
                        }
                        
                        // Create new element
                        const newElement = {
                            id: 'element-' + Date.now(),
                            widgetType: widgetName,
                            settings: {}
                        };
                        
                        // Initialize with default settings
                        if (widget.controls) {
                            Object.keys(widget.controls).forEach(key => {
                                const control = widget.controls[key];
                                if (control.default !== undefined) {
                                    newElement.settings[key] = control.default;
                                }
                            });
                        }
                        
                        // Add to tab children
                        if (!tabsElement.tabChildren) {
                            tabsElement.tabChildren = [];
                        }
                        if (!tabsElement.tabChildren[tabIndex]) {
                            tabsElement.tabChildren[tabIndex] = [];
                        }
                        tabsElement.tabChildren[tabIndex].push(newElement);
                        
                        console.log('Element added to tab', tabIndex);
                        
                        // Re-render tabs
                        self.updateElementPreview(tabsElement);
                        finishNestedDrop();
                    }
                });
                
                // Also make sortable within tab
                $zone.sortable({
                    items: '.probuilder-nested-element',
                    handle: '.probuilder-nested-drag',
                    placeholder: 'probuilder-nested-placeholder',
                    tolerance: 'pointer',
                    update: function(event, ui) {
                        console.log('Tab elements reordered');
                        
                        const newOrder = [];
                        $zone.find('.probuilder-nested-element').each(function() {
                            const childId = $(this).data('id');
                            const child = tabsElement.tabChildren[tabIndex].find(c => c.id === childId);
                            if (child) {
                                newOrder.push(child);
                            }
                        });
                        tabsElement.tabChildren[tabIndex] = newOrder;
                    }
                });
            });
        },
        
        /**
         * Attach handlers to nested elements in tabs
         */
        attachTabNestedHandlers: function(tabsElement, $tabsContainer) {
            const self = this;
            
            if (!tabsElement.tabChildren) return;
            
            tabsElement.tabChildren.forEach((tabChildren, tabIndex) => {
                if (!tabChildren || tabChildren.length === 0) return;
                
                tabChildren.forEach(childElement => {
                    const $childPreview = $tabsContainer.find(`.probuilder-nested-element[data-id="${childElement.id}"]`);
                    
                    if ($childPreview.length === 0) return;
                    
                    // Edit handler
                    $childPreview.find('.probuilder-nested-edit').off('mouseup click').on('mouseup click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        console.log('✏️ Tab nested element edit:', childElement.id);
                        self.openSettings(childElement);
                        return false;
                    });
                    
                    // Delete handler
                    $childPreview.find('.probuilder-nested-delete').off('mouseup click').on('mouseup click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        console.log('🗑️ Tab nested element delete:', childElement.id);
                        tabsElement.tabChildren[tabIndex] = tabsElement.tabChildren[tabIndex].filter(c => c.id !== childElement.id);
                        self.updateElementPreview(tabsElement);
                        return false;
                    });
                    
                    // Click to select
                    $childPreview.on('click', function(e) {
                        if (!$(e.target).closest('.probuilder-nested-controls').length) {
                            e.stopPropagation();
                            console.log('👆 Tab nested element clicked:', childElement.id);
                            self.selectElement(childElement);
                        }
                    });
                });
            });
        },
        
        /**
         * Initialize carousel functionality
         */
        initializeCarousel: function(element, $elementDOM) {
            const settings = element.settings || {};
            const carouselId = 'carousel-' + element.id;
            const $carousel = $elementDOM ? $elementDOM.find(`[data-carousel-id="${carouselId}"]`) : $(`[data-carousel-id="${carouselId}"]`);
            
            if ($carousel.length === 0) {
                console.warn('Carousel not found:', carouselId);
                return;
            }
            
            console.log('🎠 Initializing carousel:', carouselId);
            
            const carouselImages = Array.isArray(settings.images) ? settings.images : [
                { image_url: 'https://via.placeholder.com/1200x600/92003b/ffffff?text=Slide+1', caption: 'First Slide' },
                { image_url: 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Slide+2', caption: 'Second Slide' },
                { image_url: 'https://via.placeholder.com/1200x600/4facfe/ffffff?text=Slide+3', caption: 'Third Slide' }
            ];
            const dotsColor = settings.dots_color || '#92003b';
            const autoplay = settings.autoplay !== 'no';
            const autoplaySpeed = parseInt(settings.autoplay_speed) || 3000;
            
            let currentSlide = 0;
            const totalSlides = carouselImages.length;
            let autoplayInterval = null;
            
            console.log('Carousel settings:', {
                slides: totalSlides,
                autoplay: autoplay,
                speed: autoplaySpeed,
                dotsColor: dotsColor
            });
            
            function showSlide(index) {
                // Ensure index is within bounds
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                currentSlide = index;
                
                console.log('Showing slide:', currentSlide);
                
                // Hide all slides
                $carousel.find('.probuilder-carousel-slide').css('display', 'none');
                
                // Show current slide
                $carousel.find(`.probuilder-carousel-slide[data-slide="${currentSlide}"]`).css('display', 'flex');
                
                // Update dots
                $carousel.find('.probuilder-carousel-dot').each(function() {
                    const dotIndex = parseInt($(this).data('slide'));
                    const isActive = dotIndex === currentSlide;
                    $(this).css({
                        'width': isActive ? '24px' : '12px',
                        'background': isActive ? dotsColor : 'transparent'
                    }).toggleClass('active', isActive);
                });
            }
            
            function nextSlide() {
                showSlide(currentSlide + 1);
            }
            
            function prevSlide() {
                showSlide(currentSlide - 1);
            }
            
            // Arrow navigation
            $carousel.find('.probuilder-carousel-prev').off('click').on('click', function(e) {
                e.stopPropagation();
                console.log('← Previous clicked');
                prevSlide();
                
                // Reset autoplay
                if (autoplay && autoplayInterval) {
                    clearInterval(autoplayInterval);
                    startAutoplay();
                }
            });
            
            $carousel.find('.probuilder-carousel-next').off('click').on('click', function(e) {
                e.stopPropagation();
                console.log('→ Next clicked');
                nextSlide();
                
                // Reset autoplay
                if (autoplay && autoplayInterval) {
                    clearInterval(autoplayInterval);
                    startAutoplay();
                }
            });
            
            // Dot navigation
            $carousel.find('.probuilder-carousel-dot').off('click').on('click', function(e) {
                e.stopPropagation();
                const slideIndex = parseInt($(this).data('slide'));
                console.log('● Dot clicked:', slideIndex);
                showSlide(slideIndex);
                
                // Reset autoplay
                if (autoplay && autoplayInterval) {
                    clearInterval(autoplayInterval);
                    startAutoplay();
                }
            });
            
            // Autoplay
            function startAutoplay() {
                if (autoplayInterval) {
                    clearInterval(autoplayInterval);
                }
                console.log('▶ Starting autoplay with speed:', autoplaySpeed);
                autoplayInterval = setInterval(nextSlide, autoplaySpeed);
            }
            
            if (autoplay) {
                startAutoplay();
            }
            
            // Stop autoplay on hover
            $carousel.on('mouseenter', function() {
                if (autoplayInterval) {
                    console.log('⏸ Pausing autoplay (hover)');
                    clearInterval(autoplayInterval);
                }
            }).on('mouseleave', function() {
                if (autoplay) {
                    console.log('▶ Resuming autoplay');
                    startAutoplay();
                }
            });
            
            console.log('✅ Carousel initialized successfully');
        },
        
        /**
         * Show widget picker for tab
         */
        showWidgetPickerForTab: function(tabsElementId, tabIndex) {
            const self = this;
            const tabsElement = this.elements.find(e => e.id === tabsElementId);
            
            if (!tabsElement) return;
            
            // Create modal overlay
            const $overlay = $('<div class="probuilder-widget-picker-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 100000; display: flex; align-items: center; justify-content: center;"></div>');
            
            // Create modal content
            const $modal = $('<div class="probuilder-widget-picker-modal" style="background: #ffffff; border-radius: 8px; max-width: 900px; width: 90%; max-height: 80vh; overflow: auto; padding: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);"></div>');
            
            $modal.append('<h2 style="margin: 0 0 20px 0; font-size: 24px; color: #1e293b;">Add Widget to Tab ' + (tabIndex + 1) + '</h2>');
            
            // Create widgets grid
            const $grid = $('<div class="probuilder-widget-picker-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px;"></div>');
            
            this.widgets.forEach(widget => {
                const $widgetCard = $(`
                    <div class="probuilder-widget-picker-card" data-widget="${widget.name}" style="
                        padding: 20px;
                        border: 2px solid #e2e8f0;
                        border-radius: 8px;
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.2s;
                        background: #ffffff;
                    ">
                        <i class="${widget.icon}" style="font-size: 32px; color: #92003b; margin-bottom: 10px; display: block;"></i>
                        <div style="font-size: 13px; font-weight: 500; color: #334155;">${widget.title}</div>
                    </div>
                `);
                
                $widgetCard.on('mouseenter', function() {
                    $(this).css({'border-color': '#92003b', 'transform': 'translateY(-2px)', 'box-shadow': '0 4px 12px rgba(146,0,59,0.15)'});
                }).on('mouseleave', function() {
                    $(this).css({'border-color': '#e2e8f0', 'transform': 'translateY(0)', 'box-shadow': 'none'});
                });
                
                $widgetCard.on('click', function() {
                    const widgetName = $(this).data('widget');
                    const selectedWidget = self.widgets.find(w => w.name === widgetName);
                    
                    if (!selectedWidget) return;
                    
                    // Create new element
                    const newElement = {
                        id: 'element-' + Date.now(),
                        widgetType: widgetName,
                        settings: {}
                    };
                    
                    // Initialize with default settings
                    if (selectedWidget.controls) {
                        Object.keys(selectedWidget.controls).forEach(key => {
                            const control = selectedWidget.controls[key];
                            if (control.default !== undefined) {
                                newElement.settings[key] = control.default;
                            }
                        });
                    }
                    
                    // Add to tab children
                    if (!tabsElement.tabChildren) {
                        tabsElement.tabChildren = [];
                    }
                    if (!tabsElement.tabChildren[tabIndex]) {
                        tabsElement.tabChildren[tabIndex] = [];
                    }
                    tabsElement.tabChildren[tabIndex].push(newElement);
                    
                    console.log('Widget added to tab', tabIndex, ':', widgetName);
                    
                    // Re-render tabs
                    self.updateElementPreview(tabsElement);
                    
                    // Close modal
                    $overlay.remove();
                });
                
                $grid.append($widgetCard);
            });
            
            $modal.append($grid);
            
            // Close button
            const $closeBtn = $('<button style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b; padding: 5px 10px;">×</button>');
            $closeBtn.on('click', function() {
                $overlay.remove();
            });
            $modal.css('position', 'relative').prepend($closeBtn);
            
            // Close on overlay click
            $overlay.on('click', function(e) {
                if (e.target === this) {
                    $(this).remove();
                }
            });
            
            $overlay.append($modal);
            $('body').append($overlay);
        },
        
        /**
         * Duplicate element
         */
        duplicateElement: function(element) {
            const newElement = JSON.parse(JSON.stringify(element));
            newElement.id = 'element-' + Date.now();
            this.elements.push(newElement);
            this.renderElement(newElement);
        },
        
        /**
         * Delete element
         */
        deleteElement: function(element) {
            // Ensure elements is always an array
            if (!Array.isArray(this.elements)) {
                console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                this.elements = [];
                return;
            }
            
            const index = this.elements.findIndex(e => e.id === element.id);
            if (index > -1) {
                this.elements.splice(index, 1);
            }
            
            $(`.probuilder-element[data-id="${element.id}"]`).remove();
            this.closeSettings();
            this.updateEmptyState();
            
            // Save to history
            this.saveHistory();
        },
        
        /**
         * Update elements order
         */
        updateElementsOrder: function() {
            const newOrder = [];
            $('#probuilder-preview-area .probuilder-element').each(function() {
                const id = $(this).data('id');
                const element = ProBuilder.elements.find(e => e.id === id);
                if (element) {
                    newOrder.push(element);
                }
            });
            this.elements = newOrder;
        },
        
        /**
         * Update empty state
         */
        updateEmptyState: function() {
            if (this.elements.length === 0) {
                $('#probuilder-empty-state').show();
                $('#probuilder-add-bottom').hide();
            } else {
                $('#probuilder-empty-state').hide();
                $('#probuilder-add-bottom').show();
            }
        },
        
        /**
         * Initialize resizable panels
         */
        initResizablePanels: function() {
            const self = this;
            let isResizing = false;
            let currentPanel = null;
            let startX = 0;
            let startWidth = 0;
            
            // Left sidebar resize - use a larger hit zone
            $('.probuilder-sidebar').on('mousedown', function(e) {
                // Get click position relative to the panel
                const rect = this.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const width = rect.width;
                
                // Only start resize if clicking on right edge (last 15px)
                if (clickX > width - 15) {
                    isResizing = true;
                    currentPanel = 'left';
                    startX = e.pageX;
                    startWidth = $(this).width();
                    $(this).addClass('resizing');
                    $('body').css('cursor', 'ew-resize').css('user-select', 'none');
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
            
            // Right sidebar resize - use a larger hit zone
            $('.probuilder-settings-panel').on('mousedown', function(e) {
                // Get click position relative to the panel
                const rect = this.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                
                // Only start resize if clicking on left edge (first 15px)
                if (clickX < 15) {
                    isResizing = true;
                    currentPanel = 'right';
                    startX = e.pageX;
                    startWidth = $(this).width();
                    $(this).addClass('resizing');
                    $('body').css('cursor', 'ew-resize').css('user-select', 'none');
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
            
            // Mouse move
            $(document).on('mousemove', function(e) {
                if (!isResizing) return;
                
                const dx = e.pageX - startX;
                
                if (currentPanel === 'left') {
                    const newWidth = Math.max(200, Math.min(500, startWidth + dx));
                    $('.probuilder-sidebar').width(newWidth);
                    self.updatePanelResponsiveness();
                    
                    // Save to localStorage
                    localStorage.setItem('probuilder_sidebar_width', newWidth);
                } else if (currentPanel === 'right') {
                    const newWidth = Math.max(250, Math.min(600, startWidth - dx));
                    $('.probuilder-settings-panel').width(newWidth);
                    self.updatePanelResponsiveness();
                    
                    // Save to localStorage
                    localStorage.setItem('probuilder_settings_width', newWidth);
                }
                
                e.preventDefault();
            });
            
            // Mouse up
            $(document).on('mouseup', function() {
                if (isResizing) {
                    isResizing = false;
                    currentPanel = null;
                    $('.probuilder-sidebar, .probuilder-settings-panel').removeClass('resizing');
                    $('body').css('cursor', '').css('user-select', '');
                }
            });
            
            // Load saved widths from localStorage
            const savedSidebarWidth = localStorage.getItem('probuilder_sidebar_width');
            const savedSettingsWidth = localStorage.getItem('probuilder_settings_width');
            
            if (savedSidebarWidth) {
                $('.probuilder-sidebar').width(parseInt(savedSidebarWidth));
            }
            
            if (savedSettingsWidth) {
                $('.probuilder-settings-panel').width(parseInt(savedSettingsWidth));
            }
            
            // Initial responsiveness update
            self.updatePanelResponsiveness();
            
            console.log('✅ Resizable panels initialized');
        },
        
        /**
         * Initialize sidebar collapse/expand toggles
         */
        initSidebarToggles: function() {
            const self = this;
            
            // Left sidebar toggle
            $('#probuilder-left-sidebar-toggle').on('click', function() {
                $('.probuilder-sidebar').toggleClass('collapsed');
                const isCollapsed = $('.probuilder-sidebar').hasClass('collapsed');
                console.log('Left sidebar', isCollapsed ? 'collapsed' : 'expanded');
                
                // Update responsiveness after toggle
                setTimeout(() => {
                    self.updatePanelResponsiveness();
                }, 300);
            });
            
            // Right sidebar toggle
            $('#probuilder-right-sidebar-toggle').on('click', function() {
                $('.probuilder-settings-panel').toggleClass('collapsed');
                const isCollapsed = $('.probuilder-settings-panel').hasClass('collapsed');
                console.log('Right sidebar', isCollapsed ? 'collapsed' : 'expanded');
                
                // Update responsiveness after toggle
                setTimeout(() => {
                    self.updatePanelResponsiveness();
                }, 300);
            });
            
            console.log('✅ Sidebar toggles initialized');
        },
        
        /**
         * Update panel responsiveness based on width
         */
        updatePanelResponsiveness: function() {
            // Left sidebar responsiveness
            const sidebarWidth = $('.probuilder-sidebar').width();
            
            if (sidebarWidth < 250) {
                $('.probuilder-sidebar').attr('data-width', 'narrow');
            } else if (sidebarWidth > 380) {
                $('.probuilder-sidebar').attr('data-width', 'wide');
            } else {
                $('.probuilder-sidebar').attr('data-width', 'medium');
            }
            
            // Right settings panel responsiveness
            const settingsWidth = $('.probuilder-settings-panel').width();
            
            if (settingsWidth < 300) {
                $('.probuilder-settings-panel').attr('data-width', 'narrow');
            } else if (settingsWidth > 420) {
                $('.probuilder-settings-panel').attr('data-width', 'wide');
            } else {
                $('.probuilder-settings-panel').attr('data-width', 'medium');
            }
            
            console.log('Panel widths updated - Sidebar:', sidebarWidth, 'Settings:', settingsWidth);
        },
        
        /**
         * Show widget/template selector modal for drop zones
         */
        showWidgetTemplateSelector: function(containerId = null, columnIndex = null) {
            const self = this;
            
            const modalHTML = `
                <div class="probuilder-selector-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); z-index: 999999; display: flex; align-items: center; justify-content: center;">
                    <div class="probuilder-selector-modal" style="background: #ffffff; border-radius: 8px; width: 90%; max-width: 900px; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
                        <div style="padding: 20px 25px; border-bottom: 1px solid #e6e9ec; display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #495157;">
                                <i class="dashicons dashicons-plus-alt2" style="color: #92003b;"></i>
                                Add Element
                            </h3>
                            <button class="probuilder-selector-close" style="background: transparent; border: none; font-size: 32px; cursor: pointer; color: #a1a1aa; line-height: 1; width: 32px; height: 32px; padding: 0;">&times;</button>
                        </div>
                        <div style="padding: 25px; overflow-y: auto; flex: 1;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px;">
                                ${this.widgets.map(widget => `
                                    <div class="probuilder-selector-widget" data-widget="${widget.name}" style="background: #ffffff; border: 1px solid #e6e9ec; border-radius: 6px; padding: 15px 10px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                        <i class="${widget.icon}" style="font-size: 28px; color: #92003b; margin-bottom: 8px; display: block;"></i>
                                        <div style="font-size: 11px; font-weight: 600; color: #495157; line-height: 1.3;">${widget.title}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(modalHTML);
            
            // Close modal
            $('.probuilder-selector-close, .probuilder-selector-overlay').on('click', function(e) {
                if (e.target === this) {
                    $('.probuilder-selector-overlay').fadeOut(200, function() {
                        $(this).remove();
                    });
                }
            });
            
            // Widget hover effect
            $('.probuilder-selector-widget').hover(
                function() {
                    $(this).css({
                        'border-color': '#92003b',
                        'transform': 'translateY(-4px)',
                        'box-shadow': '0 8px 16px rgba(146, 0, 59, 0.15)'
                    });
                },
                function() {
                    $(this).css({
                        'border-color': '#e6e9ec',
                        'transform': 'translateY(0)',
                        'box-shadow': 'none'
                    });
                }
            );
            
            // Select widget
            $('.probuilder-selector-widget').on('click', function() {
                const widgetName = $(this).data('widget');
                console.log('Widget selected:', widgetName, 'for container:', containerId, 'column:', columnIndex);
                
                if (containerId) {
                    // Add to specific container at specified column
                    self.addElementToContainer(widgetName, containerId, columnIndex);
                } else {
                    // Add to main canvas
                    self.addElement(widgetName);
                }
                
                // Close modal
                $('.probuilder-selector-overlay').fadeOut(200, function() {
                    $(this).remove();
                });
            });
        },
        
        /**
         * Show add element modal (for adding below an element)
         */
        showAddElementModal: function(afterElement) {
            const self = this;
            
            const modalHTML = `
                <div class="probuilder-selector-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); z-index: 999999; display: flex; align-items: center; justify-content: center;">
                    <div class="probuilder-selector-modal" style="background: #ffffff; border-radius: 8px; width: 90%; max-width: 900px; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
                        <div style="padding: 20px 25px; border-bottom: 1px solid #e6e9ec; display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #495157;">
                                <i class="dashicons dashicons-plus-alt2" style="color: #92003b;"></i>
                                Add Element Below
                            </h3>
                            <button class="probuilder-selector-close" style="background: transparent; border: none; font-size: 32px; cursor: pointer; color: #a1a1aa; line-height: 1; width: 32px; height: 32px; padding: 0;">&times;</button>
                        </div>
                        <div style="padding: 25px; overflow-y: auto; flex: 1;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px;">
                                ${this.widgets.map(widget => `
                                    <div class="probuilder-selector-widget" data-widget="${widget.name}" style="background: #ffffff; border: 1px solid #e6e9ec; border-radius: 6px; padding: 15px 10px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                        <i class="${widget.icon}" style="font-size: 28px; color: #92003b; margin-bottom: 8px; display: block;"></i>
                                        <div style="font-size: 11px; font-weight: 600; color: #495157; line-height: 1.3;">${widget.title}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(modalHTML);
            
            // Close modal
            $('.probuilder-selector-close, .probuilder-selector-overlay').on('click', function(e) {
                if (e.target === this) {
                    $('.probuilder-selector-overlay').fadeOut(200, function() {
                        $(this).remove();
                    });
                }
            });
            
            // Widget hover effect
            $('.probuilder-selector-widget').hover(
                function() {
                    $(this).css({
                        'border-color': '#92003b',
                        'transform': 'translateY(-4px)',
                        'box-shadow': '0 8px 16px rgba(146, 0, 59, 0.15)'
                    });
                },
                function() {
                    $(this).css({
                        'border-color': '#e6e9ec',
                        'transform': 'translateY(0)',
                        'box-shadow': 'none'
                    });
                }
            );
            
            // Select widget
            $('.probuilder-selector-widget').on('click', function() {
                const widgetName = $(this).data('widget');
                console.log('Widget selected to add after:', afterElement.id);
                
                // Find the index of the current element
                const currentIndex = self.elements.findIndex(e => e.id === afterElement.id);
                
                if (currentIndex !== -1) {
                    // Create new element
                    const widget = self.widgets.find(w => w.name === widgetName);
                    if (widget) {
                        const newElement = {
                            id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                            widgetType: widgetName,
                            settings: Object.assign({}, self.getDefaultSettings(widget)),
                            children: []
                        };
                        
                        // Insert after the current element
                        self.elements.splice(currentIndex + 1, 0, newElement);
                        
                        // Re-render all elements
                        self.renderElements();
                        
                        // Auto-select the new element
                        setTimeout(() => {
                            self.selectElement(newElement);
                        }, 100);
                        
                        console.log('Element added after:', afterElement.id);
                    }
                }
                
                // Close modal
                $('.probuilder-selector-overlay').fadeOut(200, function() {
                    $(this).remove();
                });
            });
        },
        
        /**
         * Generate template thumbnail
         */
        generateTemplateThumbnail: function(templateId) {
            const thumbnails = {
                // Full Page Templates
                'page-landing': `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <!-- Header -->
                        <div style="background: #1e293b; height: 18px; display: flex; align-items: center; padding: 0 8px; gap: 6px;">
                            <div style="width: 30px; height: 6px; background: #92003b; border-radius: 2px;"></div>
                            <div style="margin-left: auto; display: flex; gap: 3px;">
                                ${[1,2,3].map(() => `<div style="width: 12px; height: 4px; background: rgba(255,255,255,0.5); border-radius: 1px;"></div>`).join('')}
                            </div>
                        </div>
                        <!-- Hero -->
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 45px; display: flex; align-items: center; justify-content: center;">
                            <div style="text-align: center;">
                                <div style="width: 60px; height: 5px; background: rgba(255,255,255,0.9); margin: 0 auto 3px; border-radius: 2px;"></div>
                                <div style="width: 40px; height: 3px; background: rgba(255,255,255,0.7); margin: 0 auto 5px; border-radius: 2px;"></div>
                                <div style="width: 20px; height: 8px; background: white; margin: 0 auto; border-radius: 2px;"></div>
                            </div>
                        </div>
                        <!-- Features -->
                        <div style="padding: 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                            ${[1,2,3].map(() => `
                                <div style="text-align: center;">
                                    <div style="width: 12px; height: 12px; background: #92003b; border-radius: 50%; margin: 0 auto 3px;"></div>
                                    <div style="width: 80%; height: 2px; background: #e2e8f0; margin: 0 auto 2px; border-radius: 1px;"></div>
                                    <div style="width: 100%; height: 2px; background: #f1f5f9; margin: 0 auto; border-radius: 1px;"></div>
                                </div>
                            `).join('')}
                        </div>
                        <!-- Pricing -->
                        <div style="background: #f8f9fa; padding: 6px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 3px;">
                            ${[1,2,3].map((i) => `<div style="background: white; border: 1px solid ${i === 2 ? '#92003b' : '#e6e9ec'}; height: 20px; border-radius: 3px;"></div>`).join('')}
                        </div>
                        <!-- CTA -->
                        <div style="background: #92003b; height: 20px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 40px; height: 6px; background: rgba(255,255,255,0.9); border-radius: 2px;"></div>
                        </div>
                        <!-- Footer -->
                        <div style="background: #1e293b; height: 15px;"></div>
                    </div>
                `,
                'page-about': `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 15px;"></div>
                        <div style="background: #f8f9fa; height: 25px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 50px; height: 4px; background: #92003b; border-radius: 2px;"></div>
                        </div>
                        <div style="padding: 8px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                            <div style="background: #e6e9ec; height: 40px; border-radius: 4px;"></div>
                            <div style="display: flex; flex-direction: column; gap: 3px; justify-content: center;">
                                ${[1,2,3,4].map(() => `<div style="width: 100%; height: 3px; background: #e2e8f0; border-radius: 1px;"></div>`).join('')}
                            </div>
                        </div>
                        <div style="background: #f8f9fa; padding: 6px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 3px; margin: 4px 0;">
                            ${[1,2,3,4].map(() => `
                                <div style="text-align: center;">
                                    <div style="width: 15px; height: 15px; background: #cbd5e1; border-radius: 50%; margin: 0 auto 2px;"></div>
                                    <div style="width: 80%; height: 2px; background: #e2e8f0; margin: 0 auto; border-radius: 1px;"></div>
                                </div>
                            `).join('')}
                        </div>
                        <div style="background: #1e293b; height: 15px;"></div>
                    </div>
                `,
                'page-services': `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 15px;"></div>
                        <div style="background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); height: 30px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 45px; height: 5px; background: rgba(255,255,255,0.9); border-radius: 2px;"></div>
                        </div>
                        <div style="padding: 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                            ${[1,2,3].map(() => `
                                <div style="border: 1px solid #e6e9ec; padding: 6px; border-radius: 4px; text-align: center;">
                                    <div style="width: 12px; height: 12px; background: #92003b; border-radius: 50%; margin: 0 auto 3px;"></div>
                                    <div style="width: 80%; height: 3px; background: #e2e8f0; margin: 0 auto 2px; border-radius: 1px;"></div>
                                    <div style="width: 100%; height: 2px; background: #f1f5f9; margin: 0 auto 3px; border-radius: 1px;"></div>
                                    <div style="width: 50%; height: 6px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                                </div>
                            `).join('')}
                        </div>
                        <div style="padding: 8px;">
                            <div style="background: #f8f9fa; padding: 8px; border-radius: 4px;">
                                ${[1,2,3].map(() => `<div style="width: 100%; height: 6px; background: white; margin-bottom: 3px; border-radius: 2px; border: 1px solid #e2e8f0;"></div>`).join('')}
                                <div style="width: 40%; height: 10px; background: #92003b; border-radius: 2px;"></div>
                            </div>
                        </div>
                    </div>
                `,
                'page-portfolio': `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #0f172a; height: 15px;"></div>
                        <div style="padding: 6px 8px; text-align: center;">
                            <div style="width: 40px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 60px; height: 3px; background: #cbd5e1; margin: 0 auto; border-radius: 1px;"></div>
                        </div>
                        <div style="padding: 0 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                            ${[1,2,3,4,5,6].map((i) => `
                                <div style="aspect-ratio: 1; background: linear-gradient(135deg, ${['#667eea', '#f59e0b', '#ec4899', '#10b981', '#3b82f6', '#8b5cf6'][i-1]} 0%, ${['#764ba2', '#d97706', '#db2777', '#059669', '#2563eb', '#7c3aed'][i-1]} 100%); border-radius: 4px;"></div>
                            `).join('')}
                        </div>
                    </div>
                `,
                'page-shop': `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 12px; display: flex; align-items: center; padding: 0 6px; justify-content: space-between;">
                            <div style="width: 20px; height: 4px; background: #92003b; border-radius: 1px;"></div>
                            <div style="display: flex; gap: 2px;">
                                ${[1,2,3].map(() => `<div style="width: 10px; height: 3px; background: rgba(255,255,255,0.5); border-radius: 1px;"></div>`).join('')}
                            </div>
                        </div>
                        <div style="background: #92003b; height: 25px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 50px; height: 6px; background: rgba(255,255,255,0.9); border-radius: 2px;"></div>
                        </div>
                        <div style="padding: 8px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;">
                            ${[1,2,3,4,5,6,7,8].map(() => `
                                <div style="border: 1px solid #e6e9ec; border-radius: 4px; overflow: hidden;">
                                    <div style="background: #f1f5f9; height: 25px;"></div>
                                    <div style="padding: 3px; text-align: center;">
                                        <div style="width: 80%; height: 2px; background: #cbd5e1; margin: 0 auto 2px; border-radius: 1px;"></div>
                                        <div style="width: 40%; height: 4px; background: #92003b; margin: 0 auto; border-radius: 1px;"></div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
                'page-blog': `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 15px;"></div>
                        <div style="padding: 8px; display: grid; grid-template-columns: 2fr 1fr; gap: 8px;">
                            <div>
                                ${[1,2].map(() => `
                                    <div style="background: white; border: 1px solid #e6e9ec; margin-bottom: 6px; border-radius: 4px; overflow: hidden;">
                                        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); height: 25px;"></div>
                                        <div style="padding: 5px;">
                                            <div style="width: 80%; height: 3px; background: #1e293b; margin-bottom: 2px; border-radius: 1px;"></div>
                                            <div style="width: 100%; height: 2px; background: #e2e8f0; margin-bottom: 2px; border-radius: 1px;"></div>
                                            <div style="width: 90%; height: 2px; background: #e2e8f0; border-radius: 1px;"></div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                            <div style="background: #f8f9fa; padding: 6px; border-radius: 4px;">
                                ${[1,2,3,4].map(() => `<div style="width: 100%; height: 3px; background: #cbd5e1; margin-bottom: 3px; border-radius: 1px;"></div>`).join('')}
                            </div>
                        </div>
                    </div>
                `,
                
                // Hero Sections
                'hero-1': `
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative;">
                        <div style="position: absolute; top: 8px; right: 8px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; opacity: 0.3;"></div>
                        <div style="position: absolute; bottom: 8px; left: 8px; width: 30px; height: 30px; background: rgba(255,255,255,0.15); border-radius: 50%; opacity: 0.3;"></div>
                        <div style="text-align: center; color: white; max-width: 80%;">
                            <div style="width: 100%; height: 10px; background: rgba(255,255,255,0.95); margin: 0 auto 6px; border-radius: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);"></div>
                            <div style="width: 75%; height: 8px; background: rgba(255,255,255,0.8); margin: 0 auto 4px; border-radius: 3px;"></div>
                            <div style="width: 85%; height: 6px; background: rgba(255,255,255,0.6); margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 70%; height: 6px; background: rgba(255,255,255,0.5); margin: 0 auto 12px; border-radius: 2px;"></div>
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <div style="width: 35px; height: 18px; background: rgba(255,255,255,0.95); border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);"></div>
                                <div style="width: 35px; height: 18px; background: rgba(255,255,255,0.25); border: 1px solid rgba(255,255,255,0.6); border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                `,
                'hero-2': `
                    <div style="background: #1e293b; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; position: relative;">
                        <div style="width: 80%; max-width: 200px; text-align: center;">
                            <div style="width: 100%; height: 12px; background: rgba(255,255,255,0.95); margin: 0 auto 6px; border-radius: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>
                            <div style="width: 75%; height: 8px; background: rgba(255,255,255,0.75); margin: 0 auto 4px; border-radius: 3px;"></div>
                            <div style="width: 85%; height: 6px; background: rgba(255,255,255,0.6); margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 70%; height: 6px; background: rgba(255,255,255,0.5); margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 90%; height: 6px; background: rgba(255,255,255,0.4); margin: 0 auto 14px; border-radius: 2px;"></div>
                            <div style="width: 45%; height: 20px; background: #92003b; margin: 0 auto; border-radius: 5px; box-shadow: 0 4px 12px rgba(146, 0, 59, 0.4);"></div>
                        </div>
                        <div style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: flex; gap: 4px;">
                            ${[1,2,3].map((i) => `<div style="width: 6px; height: 6px; background: ${i === 2 ? '#92003b' : 'rgba(255,255,255,0.3)'}; border-radius: 50%;"></div>`).join('')}
                        </div>
                    </div>
                `,
                'hero-3': `
                    <div style="background: #f8f9fa; height: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 0;">
                        <div style="background: linear-gradient(135deg, #e6e9ec 0%, #cbd5e1 100%); display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="width: 70px; height: 70px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
                            <div style="position: absolute; top: 8px; left: 8px; width: 20px; height: 20px; background: rgba(255,255,255,0.4); border-radius: 50%;"></div>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; padding: 15px; background: white;">
                            <div style="width: 85%; height: 9px; background: #1e293b; margin-bottom: 6px; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"></div>
                            <div style="width: 95%; height: 5px; background: #cbd5e1; margin-bottom: 4px; border-radius: 2px;"></div>
                            <div style="width: 75%; height: 5px; background: #e2e8f0; margin-bottom: 4px; border-radius: 2px;"></div>
                            <div style="width: 90%; height: 5px; background: #e2e8f0; margin-bottom: 12px; border-radius: 2px;"></div>
                            <div style="width: 45%; height: 16px; background: #92003b; border-radius: 4px; box-shadow: 0 2px 6px rgba(146, 0, 59, 0.3);"></div>
                        </div>
                    </div>
                `,
                
                // Features
                'features-1': `
                    <div style="background: white; height: 100%; padding: 15px;">
                        <div style="text-align: center; margin-bottom: 12px;">
                            <div style="width: 50px; height: 6px; background: #92003b; margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 70px; height: 4px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                            ${[1,2,3].map(() => `
                                <div style="text-align: center; background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e6e9ec;">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); border-radius: 50%; margin: 0 auto 8px; box-shadow: 0 2px 8px rgba(146, 0, 59, 0.3);"></div>
                                    <div style="width: 90%; height: 6px; background: #1e293b; margin: 0 auto 4px; border-radius: 2px;"></div>
                                    <div style="width: 100%; height: 4px; background: #cbd5e1; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 95%; height: 4px; background: #e2e8f0; margin: 0 auto; border-radius: 2px;"></div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
                'features-2': `
                    <div style="background: #f8f9fa; height: 100%; padding: 15px;">
                        <div style="text-align: center; margin-bottom: 12px;">
                            <div style="width: 50px; height: 6px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 65px; height: 4px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        ${[1,2].map(() => `
                            <div style="display: flex; gap: 12px; align-items: flex-start; background: white; padding: 12px; border-radius: 8px; margin-bottom: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border: 1px solid #e6e9ec;">
                                <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); border-radius: 6px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(146, 0, 59, 0.3); display: flex; align-items: center; justify-content: center;">
                                    <div style="width: 10px; height: 10px; border: 2px solid white; border-radius: 50%;"></div>
                                </div>
                                <div style="flex: 1;">
                                    <div style="width: 75%; height: 6px; background: #1e293b; margin-bottom: 5px; border-radius: 2px;"></div>
                                    <div style="width: 95%; height: 4px; background: #cbd5e1; margin-bottom: 3px; border-radius: 2px;"></div>
                                    <div style="width: 88%; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `,
                'features-3': `
                    <div style="background: white; height: 100%; padding: 15px;">
                        ${[1,2,3,4].map(() => `
                            <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                                <div style="width: 12px; height: 12px; background: #92003b; border-radius: 50%; flex-shrink: 0;"></div>
                                <div style="flex: 1; height: 5px; background: #e2e8f0; border-radius: 2px;"></div>
                            </div>
                        `).join('')}
                    </div>
                `,
                
                // Pricing
                'pricing-1': `
                    <div style="background: #f8f9fa; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 50px; height: 6px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 70px; height: 4px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                            ${[1,2,3].map((i) => `
                                <div style="background: white; border: 2px solid ${i === 2 ? '#92003b' : '#e6e9ec'}; border-radius: 8px; padding: 12px; text-align: center; box-shadow: ${i === 2 ? '0 8px 20px rgba(146, 0, 59, 0.2)' : '0 2px 8px rgba(0,0,0,0.05)'}; transform: ${i === 2 ? 'scale(1.05)' : 'scale(1)'};">
                                    ${i === 2 ? '<div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: #92003b; color: white; padding: 2px 8px; border-radius: 10px; font-size: 8px;">POPULAR</div>' : ''}
                                    <div style="width: 70%; height: 6px; background: #1e293b; margin: 0 auto 6px; border-radius: 2px;"></div>
                                    <div style="width: 50%; height: 14px; background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); margin: 0 auto 8px; border-radius: 3px; box-shadow: 0 2px 6px rgba(146, 0, 59, 0.3);"></div>
                                    <div style="width: 85%; height: 3px; background: #e2e8f0; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 85%; height: 3px; background: #e2e8f0; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 85%; height: 3px; background: #e2e8f0; margin: 0 auto 8px; border-radius: 2px;"></div>
                                    <div style="width: 60%; height: 12px; background: ${i === 2 ? 'linear-gradient(135deg, #92003b 0%, #d5006d 100%)' : '#cbd5e1'}; margin: 0 auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
                'pricing-2': `
                    <div style="background: white; height: 100%; padding: 15px;">
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-bottom: 6px;">
                            ${[1,2,3,4].map(() => `<div style="height: 12px; background: #92003b; border-radius: 2px;"></div>`).join('')}
                        </div>
                        ${[1,2,3].map(() => `
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-bottom: 4px;">
                                ${[1,2,3,4].map(() => `<div style="height: 8px; background: #f1f5f9; border-radius: 2px;"></div>`).join('')}
                            </div>
                        `).join('')}
                    </div>
                `,
                
                // Testimonials
                'testimonial-1': `
                    <div style="background: linear-gradient(to bottom, #f8f9fa 0%, white 100%); height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <div style="text-align: center; max-width: 75%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e6e9ec;">
                            <div style="font-size: 24px; color: #92003b; margin-bottom: 10px;">"</div>
                            <div style="width: 100%; height: 5px; background: #1e293b; margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 90%; height: 4px; background: #cbd5e1; margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 85%; height: 4px; background: #e2e8f0; margin: 0 auto 10px; border-radius: 2px;"></div>
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; margin: 0 auto 10px; border: 3px solid white; box-shadow: 0 3px 12px rgba(59, 130, 246, 0.3);"></div>
                            <div style="width: 60%; height: 5px; background: #1e293b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 45%; height: 3px; background: #cbd5e1; margin: 0 auto 6px; border-radius: 2px;"></div>
                            <div style="display: flex; gap: 3px; justify-content: center;">
                                ${[1,2,3,4,5].map(() => `<div style="width: 8px; height: 8px; background: #fbbf24; border-radius: 1px;"></div>`).join('')}
                            </div>
                        </div>
                    </div>
                `,
                'testimonial-2': `
                    <div style="background: #f8f9fa; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 55px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            ${[1,2].map((i) => `
                                <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #e6e9ec; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
                                    <div style="color: #92003b; font-size: 20px; margin-bottom: 6px;">"</div>
                                    <div style="width: 100%; height: 4px; background: #cbd5e1; margin-bottom: 3px; border-radius: 2px;"></div>
                                    <div style="width: 90%; height: 4px; background: #e2e8f0; margin-bottom: 3px; border-radius: 2px;"></div>
                                    <div style="width: 75%; height: 4px; background: #e2e8f0; margin-bottom: 8px; border-radius: 2px;"></div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <div style="width: 26px; height: 26px; background: linear-gradient(135deg, ${i === 1 ? '#10b981' : '#f59e0b'} 0%, ${i === 1 ? '#059669' : '#d97706'} 100%); border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"></div>
                                        <div style="flex: 1;">
                                            <div style="width: 80%; height: 4px; background: #1e293b; margin-bottom: 2px; border-radius: 1px;"></div>
                                            <div style="width: 60%; height: 3px; background: #cbd5e1; border-radius: 1px;"></div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
                
                // CTA
                'cta-1': `
                    <div style="background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
                        <div style="width: 70%; height: 10px; background: rgba(255,255,255,0.9); margin-bottom: 6px; border-radius: 3px;"></div>
                        <div style="width: 50%; height: 6px; background: rgba(255,255,255,0.6); margin-bottom: 12px; border-radius: 3px;"></div>
                        <div style="width: 35%; height: 18px; background: white; border-radius: 4px;"></div>
                    </div>
                `,
                'cta-2': `
                    <div style="background: #1e293b; height: 100%; padding: 15px; display: flex; flex-direction: column; justify-content: center;">
                        <div style="width: 60%; height: 8px; background: rgba(255,255,255,0.8); margin-bottom: 6px; border-radius: 3px;"></div>
                        <div style="width: 40%; height: 6px; background: rgba(255,255,255,0.5); margin-bottom: 12px; border-radius: 3px;"></div>
                        <div style="display: flex; gap: 6px;">
                            <div style="flex: 1; height: 16px; background: rgba(255,255,255,0.3); border-radius: 3px;"></div>
                            <div style="width: 30%; height: 16px; background: #92003b; border-radius: 3px;"></div>
                        </div>
                    </div>
                `,
                
                // Team
                'team-1': `
                    <div style="background: white; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 45px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 60px; height: 3px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                            ${[1,2,3,4].map((i) => `
                                <div style="text-align: center; background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px solid #e6e9ec;">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, ${['#3b82f6', '#10b981', '#f59e0b', '#ec4899'][i-1]} 0%, ${['#2563eb', '#059669', '#d97706', '#db2777'][i-1]} 100%); border-radius: 50%; margin: 0 auto 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); border: 2px solid white;"></div>
                                    <div style="width: 85%; height: 5px; background: #1e293b; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 65%; height: 3px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
                'team-2': `
                    <div style="background: #f8f9fa; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 45px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            ${[1,2].map((i) => `
                                <div style="background: white; padding: 12px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e6e9ec;">
                                    <div style="width: 44px; height: 44px; background: linear-gradient(135deg, ${i === 1 ? '#3b82f6' : '#ec4899'} 0%, ${i === 1 ? '#2563eb' : '#db2777'} 100%); border-radius: 50%; margin: 0 auto 8px; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.2);"></div>
                                    <div style="width: 75%; height: 6px; background: #1e293b; margin: 0 auto 4px; border-radius: 2px;"></div>
                                    <div style="width: 55%; height: 4px; background: #92003b; margin: 0 auto 6px; border-radius: 2px;"></div>
                                    <div style="width: 95%; height: 3px; background: #e2e8f0; margin: 0 auto 2px; border-radius: 1px;"></div>
                                    <div style="width: 90%; height: 3px; background: #e2e8f0; margin: 0 auto; border-radius: 1px;"></div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `,
                
                // Gallery
                'gallery-1': `
                    <div style="background: white; height: 100%; display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 1fr); gap: 4px; padding: 10px;">
                        ${[1,2,3,4,5,6].map((i) => `
                            <div style="background: linear-gradient(135deg, ${i % 2 === 0 ? '#fbbf24' : '#ec4899'} 0%, ${i % 2 === 0 ? '#f59e0b' : '#db2777'} 100%); border-radius: 4px;"></div>
                        `).join('')}
                    </div>
                `,
                'gallery-2': `
                    <div style="background: #1e293b; height: 100%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 3px; padding: 10px;">
                        ${[1,2,3,4,5,6,7,8].map(() => `
                            <div style="background: #334155; border-radius: 3px; aspect-ratio: 1;"></div>
                        `).join('')}
                    </div>
                `,
                
                // Contact
                'contact-1': `
                    <div style="background: white; height: 100%; padding: 15px;">
                        <div style="width: 60%; height: 8px; background: #92003b; margin-bottom: 10px; border-radius: 3px;"></div>
                        ${[1,2,3].map(() => `
                            <div style="width: 100%; height: 12px; background: #f1f5f9; margin-bottom: 6px; border-radius: 3px; border: 1px solid #e2e8f0;"></div>
                        `).join('')}
                        <div style="width: 40%; height: 16px; background: #92003b; margin-top: 8px; border-radius: 3px;"></div>
                    </div>
                `,
                'contact-2': `
                    <div style="background: white; height: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 12px;">
                        <div>
                            ${[1,2].map(() => `
                                <div style="width: 100%; height: 10px; background: #f1f5f9; margin-bottom: 6px; border-radius: 3px; border: 1px solid #e2e8f0;"></div>
                            `).join('')}
                            <div style="width: 50%; height: 14px; background: #92003b; margin-top: 6px; border-radius: 3px;"></div>
                        </div>
                        <div style="background: #e6e9ec; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 20px; height: 20px; background: #92003b; border-radius: 50%;"></div>
                        </div>
                    </div>
                `,
                
                // Footer
                'footer-1': `
                    <div style="background: #1e293b; height: 100%; padding: 15px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                            ${[1,2,3].map(() => `
                                <div style="width: 100%; height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px;"></div>
                            `).join('')}
                        </div>
                        <div style="width: 60%; height: 4px; background: rgba(255,255,255,0.2); margin: 0 auto; border-radius: 2px;"></div>
                    </div>
                `,
                'footer-2': `
                    <div style="background: #0f172a; height: 100%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; padding: 12px;">
                        ${[1,2,3,4].map(() => `
                            <div>
                                <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.6); margin-bottom: 4px; border-radius: 2px;"></div>
                                ${[1,2,3].map(() => `
                                    <div style="width: 80%; height: 3px; background: rgba(255,255,255,0.3); margin-bottom: 3px; border-radius: 2px;"></div>
                                `).join('')}
                            </div>
                        `).join('')}
                    </div>
                `
            };
            
            return thumbnails[templateId] || `
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; display: flex; align-items: center; justify-content: center;">
                    <i class="dashicons dashicons-welcome-widgets-menus" style="font-size: 48px; color: rgba(255, 255, 255, 0.3);"></i>
                </div>
            `;
        },
        
        /**
         * Show templates modal
         */
        showTemplatesModal: function() {
            const self = this;
            
            console.log('=== LOADING TEMPLATES MODAL ===');
            
            // Show loading state
            const loadingHTML = `
                <div class="probuilder-templates-modal-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="background: white; padding: 40px; border-radius: 12px; text-align: center;">
                        <div style="font-size: 48px; color: #92003b; margin-bottom: 20px;">
                            <i class="dashicons dashicons-update" style="animation: spin 1s linear infinite;"></i>
                        </div>
                        <h3 style="margin: 0; color: #1e293b;">Loading Templates...</h3>
                        <p style="margin: 10px 0 0; color: #64748b; font-size: 14px;">Please wait</p>
                    </div>
                </div>
                <style>
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                </style>
            `;
            
            $('body').append(loadingHTML);
            
            // Load templates from backend
            $.ajax({
                url: ProBuilderEditor.ajaxurl || ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_get_templates'
                },
                timeout: 15000, // 15 second timeout
                success: function(response) {
                    console.log('✓ Templates loaded successfully');
                    console.log('Response:', response);
                    
                    // Remove loading
                    $('.probuilder-templates-modal-overlay').remove();
                    
                    if (response.success && response.data) {
                        const allTemplates = response.data.prebuilt || [];
                        console.log('✓ Total templates:', allTemplates.length);
                        
                        // Group templates by category
                        const templatesByCategory = {};
                        
                        allTemplates.forEach(template => {
                            const cat = template.category || 'other';
                            if (!templatesByCategory[cat]) {
                                templatesByCategory[cat] = [];
                            }
                            templatesByCategory[cat].push(template);
                        });
                        
                        console.log('✓ Templates grouped by category:', Object.keys(templatesByCategory));
                        
                        // Build modal with real templates
                        self.buildTemplatesModal(templatesByCategory, allTemplates);
                    } else {
                        console.error('❌ Failed to load templates');
                        self.showErrorModal('Failed to load templates. Please refresh and try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX Error:', status, error);
                    console.error('Response:', xhr.responseText);
                    $('.probuilder-templates-modal-overlay').remove();
                    
                    let errorMsg = 'Error loading templates';
                    if (status === 'timeout') {
                        errorMsg = 'Templates loading timed out. Please try again.';
                    } else if (xhr.responseText) {
                        errorMsg = 'Server error: ' + xhr.responseText.substring(0, 100);
                    }
                    
                    self.showErrorModal(errorMsg);
                }
            });
        },
        
        /**
         * Show error modal
         */
        showErrorModal: function(errorMessage) {
            const errorHTML = `
                <div class="probuilder-templates-modal-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="
                        background: white;
                        padding: 40px;
                        border-radius: 12px;
                        text-align: center;
                        max-width: 500px;
                        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                    ">
                        <div style="font-size: 64px; color: #dc2626; margin-bottom: 20px;">
                            <i class="dashicons dashicons-warning"></i>
                        </div>
                        <h3 style="margin: 0 0 15px; color: #1e293b; font-size: 20px;">Error Loading Templates</h3>
                        <p style="margin: 0 0 25px; color: #64748b; font-size: 14px; line-height: 1.6;">${errorMessage}</p>
                        <button onclick="jQuery('.probuilder-templates-modal-overlay').remove();" style="
                            background: #92003b;
                            color: white;
                            border: none;
                            padding: 12px 30px;
                            border-radius: 6px;
                            font-size: 14px;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.2s;
                        " onmouseover="this.style.background='#d5006d'" onmouseout="this.style.background='#92003b'">
                            Close
                        </button>
                    </div>
                </div>
            `;
            
            $('body').append(errorHTML);
        },
        
        /**
         * Build templates modal UI
         */
        buildTemplatesModal: function(templatesByCategory, allTemplates) {
            const self = this;
            
            // Category metadata
            const categoryMeta = {
                'pages': { title: 'Full Page Templates', icon: 'dashicons-welcome-widgets-menus' },
                'hero': { title: 'Hero Sections', icon: 'dashicons-welcome-view-site' },
                'features': { title: 'Features', icon: 'dashicons-star-filled' },
                'pricing': { title: 'Pricing Tables', icon: 'dashicons-cart' },
                'testimonials': { title: 'Testimonials', icon: 'dashicons-format-quote' },
                'cta': { title: 'Call to Action', icon: 'dashicons-megaphone' },
                'team': { title: 'Team Sections', icon: 'dashicons-groups' },
                'gallery': { title: 'Galleries', icon: 'dashicons-format-gallery' },
                'contact': { title: 'Contact Sections', icon: 'dashicons-email' },
                'footer': { title: 'Footers', icon: 'dashicons-align-full-width' },
                'other': { title: 'Other Templates', icon: 'dashicons-admin-page' }
            };
            
            // Build modal HTML
            let modalHTML = `
                <div class="probuilder-templates-modal-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div class="probuilder-templates-modal" style="
                        background: #ffffff;
                        border-radius: 12px;
                        width: 95%;
                        max-width: 1200px;
                        max-height: 90vh;
                        display: flex;
                        flex-direction: column;
                        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                        overflow: hidden;
                    ">
                        <!-- Header -->
                        <div style="
                            padding: 25px 30px;
                            border-bottom: 1px solid #e6e9ec;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            background: linear-gradient(135deg, #92003b 0%, #d5006d 100%);
                        ">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="dashicons dashicons-layout" style="color: #ffffff; font-size: 32px;"></i>
                                <div>
                                    <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff;">Template Library</h2>
                                    <p style="margin: 5px 0 0 0; font-size: 13px; color: rgba(255, 255, 255, 0.8);">✨ ${allTemplates.length} Professional Templates Available</p>
                                </div>
                            </div>
                            <button class="probuilder-templates-close" style="
                                background: rgba(255, 255, 255, 0.2);
                                border: none;
                                color: #ffffff;
                                width: 36px;
                                height: 36px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.2s;
                            " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
                        </div>
                        
                        <!-- Search Box -->
                        <div style="padding: 20px 30px; border-bottom: 1px solid #e6e9ec; background: white;">
                            <div style="position: relative;">
                                <i class="dashicons dashicons-search" style="
                                    position: absolute;
                                    left: 12px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #71717a;
                                    font-size: 18px;
                                "></i>
                                <input type="text" id="template-search" placeholder="Search templates..." style="
                                    width: 100%;
                                    padding: 12px 12px 12px 40px;
                                    border: 2px solid #e6e9ec;
                                    border-radius: 6px;
                                    font-size: 14px;
                                    color: #1e293b;
                                    transition: all 0.2s;
                                    outline: none;
                                " onfocus="this.style.borderColor='#92003b'; this.style.boxShadow='0 0 0 3px rgba(146, 0, 59, 0.1)'" onblur="this.style.borderColor='#e6e9ec'; this.style.boxShadow='none'">
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="templates-content" style="
                            flex: 1;
                            overflow-y: auto;
                            padding: 30px;
                            background: #f8f9fa;
                        ">
            `;
            
            // Add each category
            Object.keys(templatesByCategory).forEach(categoryKey => {
                const categoryTemplates = templatesByCategory[categoryKey];
                const catMeta = categoryMeta[categoryKey] || { title: categoryKey, icon: 'dashicons-admin-page' };
                
                modalHTML += `
                    <div class="template-category" style="margin-bottom: 40px;">
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            margin-bottom: 20px;
                            padding-bottom: 12px;
                            border-bottom: 2px solid #92003b;
                        ">
                            <i class="dashicons ${catMeta.icon}" style="font-size: 24px; color: #92003b;"></i>
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">${catMeta.title}</h3>
                            <span style="
                                background: #92003b;
                                color: #ffffff;
                                padding: 2px 10px;
                                border-radius: 12px;
                                font-size: 11px;
                                font-weight: 600;
                            ">${categoryTemplates.length}</span>
                        </div>
                        
                        <div style="
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                            gap: 20px;
                        ">
                `;
                
                // Add templates
                categoryTemplates.forEach(template => {
                    const thumbnail = template.thumbnail || 'data:image/svg+xml;base64,' + btoa(`<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="#9ca3af" font-size="16">Template</text></svg>`);
                    
                    modalHTML += `
                        <div class="template-card" data-template-id="${template.id}" data-template-name="${template.name.toLowerCase()}" data-template-category="${catMeta.title.toLowerCase()}" style="
                            background: #ffffff;
                            border: 2px solid #e6e9ec;
                            border-radius: 8px;
                            overflow: hidden;
                            cursor: pointer;
                            transition: all 0.2s;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                        " onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='#92003b'; this.style.boxShadow='0 8px 20px rgba(146, 0, 59, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#e6e9ec'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.05)'">
                            <!-- Preview Thumbnail -->
                            <div style="
                                height: 160px;
                                position: relative;
                                overflow: hidden;
                                border-bottom: 1px solid #e6e9ec;
                            ">
                                <img src="${thumbnail}" style="width: 100%; height: 100%; object-fit: cover;" />
                                <div style="
                                    position: absolute;
                                    top: 10px;
                                    right: 10px;
                                    background: rgba(146, 0, 59, 0.95);
                                    color: #ffffff;
                                    padding: 4px 10px;
                                    border-radius: 4px;
                                    font-size: 10px;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                ">NEW</div>
                            </div>
                            
                            <!-- Content -->
                            <div style="padding: 15px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 15px; font-weight: 700; color: #1e293b;">${template.name}</h4>
                                <button class="template-insert-btn" data-template-id="${template.id}" style="
                                    width: 100%;
                                    background: #92003b;
                                    color: #ffffff;
                                    border: none;
                                    padding: 8px 16px;
                                    border-radius: 4px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    cursor: pointer;
                                    transition: all 0.2s;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 6px;
                                " onmouseover="this.style.background='#d5006d'" onmouseout="this.style.background='#92003b'">
                                    <i class="dashicons dashicons-download" style="font-size: 16px;"></i>
                                    Insert Template
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                modalHTML += `
                        </div>
                    </div>
                `;
            });
            
            modalHTML += `
                        </div>
                    </div>
                </div>
            `;
            
            // Add to body
            $('body').append(modalHTML);
            
            // Bind close button
            $('.probuilder-templates-close, .probuilder-templates-modal-overlay').on('click', function(e) {
                if (e.target === this) {
                    $('.probuilder-templates-modal-overlay').remove();
                }
            });
            
            // Bind search functionality
            $('#template-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                
                if (searchTerm === '') {
                    // Show all templates and categories
                    $('.template-card').show();
                    $('.template-category').show();
                } else {
                    // Hide all first
                    $('.template-card').hide();
                    
                    // Show matching templates
                    $('.template-card').each(function() {
                        const $card = $(this);
                        const name = $card.data('template-name') || '';
                        const category = $card.data('template-category') || '';
                        const preview = $card.data('template-preview') || '';
                        
                        if (name.includes(searchTerm) || category.includes(searchTerm) || preview.includes(searchTerm)) {
                            $card.show();
                        }
                    });
                    
                    // Hide categories with no visible templates
                    $('.template-category').each(function() {
                        const $category = $(this);
                        const visibleCards = $category.find('.template-card:visible').length;
                        
                        if (visibleCards > 0) {
                            $category.show();
                        } else {
                            $category.hide();
                        }
                    });
                }
            });
            
            // Bind template insert button clicks
            $('.template-insert-btn').on('click', function(e) {
                e.stopPropagation();
                const $btn = $(this);
                const templateId = $btn.data('template-id');
                const template = allTemplates.find(t => t.id === templateId);
                
                console.log('=== INSERTING TEMPLATE ===');
                console.log('Template ID:', templateId);
                
                if (!template) {
                    console.error('❌ Template not found:', templateId);
                    alert('Error: Template not found');
                    return;
                }
                
                // Show loading on button
                const originalHTML = $btn.html();
                $btn.html('<i class="dashicons dashicons-update" style="font-size: 16px; animation: spin 1s linear infinite;"></i> Loading...').prop('disabled', true);
                
                // Load template data from backend
                $.ajax({
                    url: ProBuilderEditor.ajaxurl || ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'probuilder_get_template_data',
                        template_id: templateId
                    },
                    timeout: 30000, // 30 second timeout for large templates
                    success: function(response) {
                        console.log('✓ Template data loaded');
                        
                        if (response.success && response.data && response.data.data) {
                            const templateData = response.data.data;
                            console.log('Template elements:', Array.isArray(templateData) ? templateData.length : 1);
                            console.log('Template type:', template.type);
                            
                            // Close modal
                            $('.probuilder-templates-modal-overlay').remove();
                            
                            // Clear canvas ONLY for full-page templates
                            if (template.type === 'page') {
                                console.log('🗑️ Full page template - clearing canvas first');
                                self.clearCanvas();
                            } else {
                                console.log('➕ Section template - adding to existing content');
                            }
                            
                            // Insert template elements WITH CHILDREN
                            if (Array.isArray(templateData)) {
                                console.log('Inserting', templateData.length, 'elements...');
                                templateData.forEach(function(elementData, index) {
                                    console.log(`Inserting element ${index + 1}:`, elementData.widgetType);
                                    console.log('   Children count:', elementData.children ? elementData.children.length : 0);
                                    if (elementData.children && elementData.children.length > 0) {
                                        console.log('   First child:', elementData.children[0]);
                                    }
                                    
                                    if (elementData.widgetType) {
                                        // Clone element data to preserve children
                                        const newElement = self.cloneElementData(elementData);
                                        console.log('✅ Cloned element:', newElement.widgetType, 'with', newElement.children.length, 'children');
                                        self.elements.push(newElement);
                                        self.renderElement(newElement);
                                    }
                                });
                            } else {
                                console.log('Inserting single element...');
                                if (templateData.widgetType) {
                                    const newElement = self.cloneElementData(templateData);
                                    self.elements.push(newElement);
                                    self.renderElement(newElement);
                                }
                            }
                            
                            // Update UI
                            self.updateEmptyState();
                            self.makeContainersDroppable();
                            self.saveHistory();
                            
                            // Show success message
                            const action = template.type === 'page' ? 'inserted' : 'added';
                            self.showToast('✓ Template ' + action + ': ' + template.name);
                            
                            // Switch back to widgets tab
                            $('.probuilder-tab-btn[data-tab="widgets"]').click();
                        } else {
                            console.error('❌ Invalid template data response');
                            alert('Error: Could not load template data');
                            $btn.html(originalHTML).prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ AJAX Error loading template data:', status, error);
                        
                        let errorMsg = 'Error loading template';
                        if (status === 'timeout') {
                            errorMsg = 'Template loading timed out. This template might be too large.';
                        }
                        
                        alert(errorMsg);
                        $btn.html(originalHTML).prop('disabled', false);
                    }
                });
            });
        },
        
        /**
         * Import template data
         */
        importTemplate: function(templateId) {
            const templateData = this.getTemplateData(templateId);
            
            if (!templateData || templateData.length === 0) {
                alert('Template data not found for: ' + templateId);
                return;
            }
            
            console.log('Importing template:', templateId, 'with', templateData.length, 'elements');
            
            // Add each element from template
            templateData.forEach((elementData, index) => {
                setTimeout(() => {
                    const widget = this.widgets.find(w => w.name === elementData.widgetType);
                    if (widget) {
                        const element = {
                            id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                            widgetType: elementData.widgetType,
                            settings: Object.assign({}, this.getDefaultSettings(widget), elementData.settings || {}),
                            children: elementData.children || []
                        };
                        
                        this.elements.push(element);
                        this.renderElement(element);
                    }
                }, index * 50); // Slight delay for smooth rendering
            });
            
            // Update empty state after all elements added
            setTimeout(() => {
                this.updateEmptyState();
                this.makeContainersDroppable();
                
                // Select first element
                if (this.elements.length > 0) {
                    setTimeout(() => {
                        this.selectElement(this.elements[this.elements.length - templateData.length]);
                    }, 100);
                }
                
                console.log('✅ Template imported successfully!');
            }, templateData.length * 50 + 100);
        },
        
        /**
         * Get template data
         */
        getTemplateData: function(templateId) {
            const templates = {
                // Hero Templates
                'hero-1': [
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_type: 'gradient',
                            background_gradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            min_height: 500,
                            padding: { top: 80, right: 40, bottom: 80, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Build Amazing Websites',
                                    html_tag: 'h1',
                                    font_size: 48,
                                    color: '#ffffff',
                                    align: 'center',
                                    font_weight: 700
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Create beautiful, professional websites with our powerful page builder. No coding required.',
                                    color: '#ffffff',
                                    font_size: 18,
                                    text_align: 'center'
                                }
                            },
                            {
                                widgetType: 'button',
                                settings: {
                                    text: 'Get Started',
                                    align: 'center',
                                    size: 'large',
                                    bg_color: '#ffffff',
                                    text_color: '#667eea'
                                }
                            }
                        ]
                    }
                ],
                
                'hero-2': [
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_type: 'color',
                            background_color: '#1e293b',
                            min_height: 450,
                            padding: { top: 60, right: 40, bottom: 60, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Welcome to Our Platform',
                                    html_tag: 'h1',
                                    font_size: 42,
                                    color: '#ffffff',
                                    align: 'center'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Discover the best solution for your business needs.',
                                    color: '#cbd5e1',
                                    font_size: 16,
                                    text_align: 'center'
                                }
                            },
                            {
                                widgetType: 'button',
                                settings: {
                                    text: 'Learn More',
                                    align: 'center',
                                    bg_color: '#92003b'
                                }
                            }
                        ]
                    }
                ],
                
                'features-1': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Our Features',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 40, right: 20, bottom: 40, left: 20 }
                        },
                        children: [
                            {
                                widgetType: 'icon-box',
                                settings: {
                                    icon: 'fa fa-rocket',
                                    title: 'Fast Performance',
                                    description: 'Lightning-fast load times and optimized performance.'
                                }
                            },
                            {
                                widgetType: 'icon-box',
                                settings: {
                                    icon: 'fa fa-shield',
                                    title: 'Secure & Safe',
                                    description: 'Enterprise-level security to protect your data.'
                                }
                            },
                            {
                                widgetType: 'icon-box',
                                settings: {
                                    icon: 'fa fa-heart',
                                    title: 'Easy to Use',
                                    description: 'Intuitive interface that anyone can master.'
                                }
                            }
                        ]
                    }
                ],
                
                'pricing-1': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Choose Your Plan',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 25,
                            background_color: '#f8f9fa',
                            padding: { top: 50, right: 30, bottom: 50, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'pricing-table',
                                settings: {
                                    title: 'Basic',
                                    price: '9',
                                    period: 'per month',
                                    features: [
                                        { text: '10 GB Storage' },
                                        { text: '5 Users' },
                                        { text: 'Email Support' }
                                    ],
                                    button_text: 'Get Started'
                                }
                            },
                            {
                                widgetType: 'pricing-table',
                                settings: {
                                    title: 'Pro',
                                    price: '29',
                                    period: 'per month',
                                    featured: 'yes',
                                    features: [
                                        { text: '100 GB Storage' },
                                        { text: 'Unlimited Users' },
                                        { text: 'Priority Support' },
                                        { text: 'Advanced Features' }
                                    ],
                                    button_text: 'Get Started'
                                }
                            },
                            {
                                widgetType: 'pricing-table',
                                settings: {
                                    title: 'Enterprise',
                                    price: '99',
                                    period: 'per month',
                                    features: [
                                        { text: 'Unlimited Storage' },
                                        { text: 'Unlimited Users' },
                                        { text: '24/7 Support' },
                                        { text: 'Custom Solutions' }
                                    ],
                                    button_text: 'Contact Sales'
                                }
                            }
                        ]
                    }
                ],
                
                'page-landing': [
                    // Hero Section
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_type: 'gradient',
                            background_gradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            min_height: 600,
                            padding: { top: 100, right: 40, bottom: 100, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Build Your Dream Website Today',
                                    html_tag: 'h1',
                                    font_size: 56,
                                    color: '#ffffff',
                                    align: 'center',
                                    font_weight: 700
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'The easiest way to create professional, stunning websites without any coding knowledge. Start building in minutes!',
                                    color: '#ffffff',
                                    font_size: 20,
                                    text_align: 'center'
                                }
                            },
                            {
                                widgetType: 'spacer',
                                settings: { height: 30 }
                            },
                            {
                                widgetType: 'button',
                                settings: {
                                    text: 'Start Free Trial',
                                    align: 'center',
                                    size: 'large',
                                    bg_color: '#ffffff',
                                    text_color: '#667eea'
                                }
                            }
                        ]
                    },
                    // Features Section
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Powerful Features',
                            html_tag: 'h2',
                            font_size: 42,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: 'Everything you need to build amazing websites',
                            font_size: 18,
                            text_align: 'center',
                            color: '#64748b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'icon-box',
                                settings: {
                                    icon: 'fa fa-rocket',
                                    title: 'Lightning Fast',
                                    description: 'Build pages at incredible speed with our intuitive drag-and-drop interface',
                                    icon_size: 48,
                                    icon_color: '#667eea'
                                }
                            },
                            {
                                widgetType: 'icon-box',
                                settings: {
                                    icon: 'fa fa-paint-brush',
                                    title: 'Beautiful Designs',
                                    description: 'Access hundreds of pre-made templates and design elements',
                                    icon_size: 48,
                                    icon_color: '#667eea'
                                }
                            },
                            {
                                widgetType: 'icon-box',
                                settings: {
                                    icon: 'fa fa-mobile',
                                    title: 'Mobile Ready',
                                    description: 'Fully responsive designs that look perfect on all devices',
                                    icon_size: 48,
                                    icon_color: '#667eea'
                                }
                            }
                        ]
                    },
                    // Stats Counter
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 30,
                            background_color: '#f8f9fa',
                            padding: { top: 60, right: 30, bottom: 60, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '10000',
                                    title: 'Happy Customers',
                                    suffix: '+'
                                }
                            },
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '50000',
                                    title: 'Websites Created',
                                    suffix: '+'
                                }
                            },
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '99',
                                    title: 'Satisfaction Rate',
                                    suffix: '%'
                                }
                            },
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '24',
                                    title: 'Support Available',
                                    suffix: '/7'
                                }
                            }
                        ]
                    },
                    // Pricing Section
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Choose Your Plan',
                            html_tag: 'h2',
                            font_size: 42,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: 'Select the perfect plan for your needs',
                            font_size: 18,
                            text_align: 'center',
                            color: '#64748b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'pricing-table',
                                settings: {
                                    title: 'Starter',
                                    price: '0',
                                    period: 'forever',
                                    features: [
                                        { text: '5 Pages' },
                                        { text: 'Basic Templates' },
                                        { text: 'Email Support' }
                                    ],
                                    button_text: 'Get Started Free'
                                }
                            },
                            {
                                widgetType: 'pricing-table',
                                settings: {
                                    title: 'Professional',
                                    price: '29',
                                    period: 'per month',
                                    featured: 'yes',
                                    features: [
                                        { text: 'Unlimited Pages' },
                                        { text: 'All Templates' },
                                        { text: 'Priority Support' },
                                        { text: 'Advanced Widgets' }
                                    ],
                                    button_text: 'Start Free Trial'
                                }
                            },
                            {
                                widgetType: 'pricing-table',
                                settings: {
                                    title: 'Agency',
                                    price: '99',
                                    period: 'per month',
                                    features: [
                                        { text: 'Everything in Pro' },
                                        { text: 'White Label' },
                                        { text: '24/7 Support' },
                                        { text: 'Client Management' }
                                    ],
                                    button_text: 'Contact Sales'
                                }
                            }
                        ]
                    },
                    // Testimonials
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'What Our Customers Say',
                            html_tag: 'h2',
                            font_size: 42,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'testimonial',
                                settings: {
                                    name: 'Sarah Johnson',
                                    position: 'CEO, TechCorp',
                                    content: 'This page builder has completely transformed our workflow. We can now create stunning websites in a fraction of the time!',
                                    rating: 5
                                }
                            },
                            {
                                widgetType: 'testimonial',
                                settings: {
                                    name: 'Michael Chen',
                                    position: 'Designer',
                                    content: 'The design flexibility is incredible. I can bring any vision to life without touching code.',
                                    rating: 5
                                }
                            },
                            {
                                widgetType: 'testimonial',
                                settings: {
                                    name: 'Emily Davis',
                                    position: 'Marketing Director',
                                    content: 'Our conversion rates increased by 40% after redesigning our landing pages with this tool!',
                                    rating: 5
                                }
                            }
                        ]
                    },
                    // Final CTA
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'call-to-action',
                        settings: {
                            title: 'Ready to Transform Your Website?',
                            description: 'Join over 10,000 businesses creating stunning websites with our page builder',
                            button_text: 'Start Building Now',
                            bg_color: '#92003b'
                        }
                    }
                ],
                
                'page-about': [
                    // Hero/Title
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_color: '#f8f9fa',
                            min_height: 250,
                            padding: { top: 60, right: 40, bottom: 60, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'About Our Company',
                                    html_tag: 'h1',
                                    font_size: 48,
                                    align: 'center',
                                    color: '#1e293b'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Building the future of web design, one page at a time',
                                    font_size: 18,
                                    text_align: 'center',
                                    color: '#64748b'
                                }
                            }
                        ]
                    },
                    // Story Section
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '2',
                            column_gap: 50,
                            padding: { top: 20, right: 40, bottom: 20, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'image',
                                settings: {
                                    url: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600',
                                    align: 'center'
                                }
                            },
                            {
                                widgetType: 'container',
                                settings: {
                                    columns: '1'
                                },
                                children: [
                                    {
                                        widgetType: 'heading',
                                        settings: {
                                            title: 'Our Story',
                                            html_tag: 'h2',
                                            font_size: 36,
                                            color: '#1e293b'
                                        }
                                    },
                                    {
                                        widgetType: 'text',
                                        settings: {
                                            content: '<p>Founded in 2020, we started with a simple mission: make professional web design accessible to everyone.</p><p>Today, we serve over 10,000 customers worldwide, helping them create stunning websites without writing a single line of code.</p><p>Our team is passionate about innovation, design excellence, and customer success.</p>'
                                        }
                                    },
                                    {
                                        widgetType: 'button',
                                        settings: {
                                            text: 'Learn More',
                                            bg_color: '#92003b'
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    // Values Section
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Our Core Values',
                            html_tag: 'h2',
                            font_size: 38,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-lightbulb',
                                    title: 'Innovation',
                                    description: 'We constantly push boundaries to deliver cutting-edge solutions',
                                    layout: 'vertical',
                                    icon_size: 80
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-users',
                                    title: 'Customer First',
                                    description: 'Your success is our success. We put customers at the heart of everything',
                                    layout: 'vertical',
                                    icon_size: 80
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-star',
                                    title: 'Excellence',
                                    description: 'We strive for perfection in every detail of our products',
                                    layout: 'vertical',
                                    icon_size: 80
                                }
                            }
                        ]
                    },
                    // Team Section
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Meet Our Team',
                            html_tag: 'h2',
                            font_size: 38,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: 'The talented people behind our success',
                            font_size: 16,
                            text_align: 'center',
                            color: '#64748b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 25,
                            padding: { top: 20, right: 20, bottom: 20, left: 20 }
                        },
                        children: [
                            {
                                widgetType: 'team-member',
                                settings: {
                                    name: 'John Smith',
                                    position: 'CEO & Founder',
                                    layout: 'center'
                                }
                            },
                            {
                                widgetType: 'team-member',
                                settings: {
                                    name: 'Sarah Johnson',
                                    position: 'CTO',
                                    layout: 'center'
                                }
                            },
                            {
                                widgetType: 'team-member',
                                settings: {
                                    name: 'Mike Davis',
                                    position: 'Head of Design',
                                    layout: 'center'
                                }
                            },
                            {
                                widgetType: 'team-member',
                                settings: {
                                    name: 'Lisa Chen',
                                    position: 'Lead Developer',
                                    layout: 'center'
                                }
                            }
                        ]
                    },
                    // Stats
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 30,
                            background_color: '#1e293b',
                            padding: { top: 60, right: 30, bottom: 60, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '5',
                                    title: 'Years in Business',
                                    suffix: '+',
                                    number_color: '#ffffff',
                                    title_color: '#cbd5e1'
                                }
                            },
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '150',
                                    title: 'Team Members',
                                    suffix: '+',
                                    number_color: '#ffffff',
                                    title_color: '#cbd5e1'
                                }
                            },
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '1000',
                                    title: 'Projects Completed',
                                    suffix: '+',
                                    number_color: '#ffffff',
                                    title_color: '#cbd5e1'
                                }
                            },
                            {
                                widgetType: 'counter',
                                settings: {
                                    number: '50',
                                    title: 'Countries',
                                    suffix: '+',
                                    number_color: '#ffffff',
                                    title_color: '#cbd5e1'
                                }
                            }
                        ]
                    }
                ],
                
                'page-services': [
                    // Hero
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_type: 'gradient',
                            background_gradient: 'linear-gradient(135deg, #92003b 0%, #d5006d 100%)',
                            min_height: 400,
                            padding: { top: 80, right: 40, bottom: 80, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Our Services',
                                    html_tag: 'h1',
                                    font_size: 52,
                                    color: '#ffffff',
                                    align: 'center',
                                    font_weight: 700
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Professional solutions tailored to your business needs',
                                    color: '#ffffff',
                                    font_size: 20,
                                    text_align: 'center'
                                }
                            }
                        ]
                    },
                    // Services Grid
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'What We Offer',
                            html_tag: 'h2',
                            font_size: 38,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-code',
                                    title: 'Web Development',
                                    description: 'Custom website development using the latest technologies and best practices',
                                    button_text: 'Learn More',
                                    layout: 'vertical',
                                    icon_size: 70
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-paint-brush',
                                    title: 'UI/UX Design',
                                    description: 'Beautiful, user-friendly interface designs that convert visitors',
                                    button_text: 'Learn More',
                                    layout: 'vertical',
                                    icon_size: 70
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-chart-line',
                                    title: 'Digital Marketing',
                                    description: 'Grow your business with data-driven marketing strategies',
                                    button_text: 'Learn More',
                                    layout: 'vertical',
                                    icon_size: 70
                                }
                            }
                        ]
                    },
                    // Second Row of Services
                    {
                        widgetType: 'spacer',
                        settings: { height: 30 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-search',
                                    title: 'SEO Optimization',
                                    description: 'Rank higher on search engines and drive organic traffic',
                                    button_text: 'Learn More',
                                    layout: 'vertical',
                                    icon_size: 70
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-shield',
                                    title: 'Security & Maintenance',
                                    description: 'Keep your website secure and running smoothly 24/7',
                                    button_text: 'Learn More',
                                    layout: 'vertical',
                                    icon_size: 70
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'icon',
                                    icon: 'fa fa-headset',
                                    title: 'Support & Training',
                                    description: 'Expert support and comprehensive training for your team',
                                    button_text: 'Learn More',
                                    layout: 'vertical',
                                    icon_size: 70
                                }
                            }
                        ]
                    },
                    // Process Section
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_color: '#f8f9fa',
                            padding: { top: 60, right: 40, bottom: 60, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Our Process',
                                    html_tag: 'h2',
                                    font_size: 38,
                                    align: 'center',
                                    color: '#1e293b'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Simple, streamlined, and effective',
                                    font_size: 16,
                                    text_align: 'center',
                                    color: '#64748b'
                                }
                            }
                        ]
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 25,
                            background_color: '#f8f9fa',
                            padding: { top: 20, right: 30, bottom: 40, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'number',
                                    number: '01',
                                    title: 'Consultation',
                                    description: 'We discuss your goals and requirements',
                                    layout: 'vertical'
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'number',
                                    number: '02',
                                    title: 'Planning',
                                    description: 'We create a detailed project roadmap',
                                    layout: 'vertical'
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'number',
                                    number: '03',
                                    title: 'Development',
                                    description: 'We bring your vision to life',
                                    layout: 'vertical'
                                }
                            },
                            {
                                widgetType: 'info-box',
                                settings: {
                                    icon_type: 'number',
                                    number: '04',
                                    title: 'Launch',
                                    description: 'We deploy and support your project',
                                    layout: 'vertical'
                                }
                            }
                        ]
                    },
                    // CTA
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'call-to-action',
                        settings: {
                            title: 'Ready to Start Your Project?',
                            description: 'Contact us today for a free consultation',
                            button_text: 'Get in Touch',
                            bg_color: '#667eea'
                        }
                    }
                ],
                
                'page-portfolio': [
                    // Hero
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_color: '#0f172a',
                            min_height: 300,
                            padding: { top: 70, right: 40, bottom: 70, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Our Portfolio',
                                    html_tag: 'h1',
                                    font_size: 52,
                                    align: 'center',
                                    color: '#ffffff',
                                    font_weight: 700
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Explore our latest creative projects and success stories',
                                    font_size: 18,
                                    text_align: 'center',
                                    color: '#cbd5e1'
                                }
                            }
                        ]
                    },
                    // Categories
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: '<p style="text-align: center; font-size: 14px; color: #64748b; margin: 0;"><strong style="color: #92003b;">Filter:</strong> <a href="#" style="margin: 0 10px;">All</a> | <a href="#" style="margin: 0 10px;">Web Design</a> | <a href="#" style="margin: 0 10px;">Branding</a> | <a href="#" style="margin: 0 10px;">Mobile Apps</a></p>'
                        }
                    },
                    // Gallery
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'gallery',
                        settings: {
                            columns: '3',
                            gap: 20
                        }
                    },
                    // Client Logos
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Trusted By Leading Brands',
                            html_tag: 'h2',
                            font_size: 32,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 30 }
                    },
                    {
                        widgetType: 'logo-grid',
                        settings: {
                            columns: '6'
                        }
                    },
                    // CTA
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'call-to-action',
                        settings: {
                            title: 'Have a Project in Mind?',
                            description: 'Let\'s work together to bring your vision to life',
                            button_text: 'Start a Project',
                            bg_color: '#1e293b'
                        }
                    }
                ],
                
                'page-shop': [
                    // Hero Banner
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_type: 'gradient',
                            background_gradient: 'linear-gradient(135deg, #92003b 0%, #d5006d 100%)',
                            min_height: 350,
                            padding: { top: 70, right: 40, bottom: 70, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Shop Our Collection',
                                    html_tag: 'h1',
                                    font_size: 52,
                                    color: '#ffffff',
                                    align: 'center',
                                    font_weight: 700
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Premium quality products at amazing prices',
                                    color: '#ffffff',
                                    font_size: 20,
                                    text_align: 'center'
                                }
                            },
                            {
                                widgetType: 'spacer',
                                settings: { height: 20 }
                            },
                            {
                                widgetType: 'button',
                                settings: {
                                    text: 'Shop Now',
                                    align: 'center',
                                    size: 'large',
                                    bg_color: '#ffffff',
                                    text_color: '#92003b'
                                }
                            }
                        ]
                    },
                    // Featured Categories
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Shop by Category',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 30 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 20,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Electronics',
                                    description: 'Latest gadgets',
                                    button_text: 'Browse'
                                }
                            },
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Fashion',
                                    description: 'Trending styles',
                                    button_text: 'Browse'
                                }
                            },
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Home & Living',
                                    description: 'Decor items',
                                    button_text: 'Browse'
                                }
                            },
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Sports',
                                    description: 'Fitness gear',
                                    button_text: 'Browse'
                                }
                            }
                        ]
                    },
                    // Featured Products
                    {
                        widgetType: 'spacer',
                        settings: { height: 60 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Featured Products',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 30 }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: '<p style="text-align: center; color: #64748b;">This section would display WooCommerce products. Install WooCommerce to show your products here.</p>'
                        }
                    },
                    // Newsletter
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_color: '#f8f9fa',
                            padding: { top: 50, right: 40, bottom: 50, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Get Exclusive Deals',
                                    html_tag: 'h2',
                                    font_size: 32,
                                    align: 'center',
                                    color: '#1e293b'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Subscribe to our newsletter for special offers and updates',
                                    font_size: 16,
                                    text_align: 'center',
                                    color: '#64748b'
                                }
                            },
                            {
                                widgetType: 'spacer',
                                settings: { height: 20 }
                            },
                            {
                                widgetType: 'newsletter',
                                settings: {
                                    placeholder: 'Enter your email address',
                                    button_text: 'Subscribe'
                                }
                            }
                        ]
                    }
                ],
                
                'page-blog': [
                    // Hero
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_color: '#f8f9fa',
                            min_height: 250,
                            padding: { top: 60, right: 40, bottom: 60, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Our Blog',
                                    html_tag: 'h1',
                                    font_size: 52,
                                    align: 'center',
                                    color: '#1e293b',
                                    font_weight: 700
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Insights, tips, and stories from our team',
                                    font_size: 18,
                                    text_align: 'center',
                                    color: '#64748b'
                                }
                            }
                        ]
                    },
                    // Categories
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: '<p style="text-align: center; font-size: 14px; color: #64748b;"><strong style="color: #92003b;">Categories:</strong> <a href="#" style="margin: 0 8px;">All</a> | <a href="#" style="margin: 0 8px;">Design</a> | <a href="#" style="margin: 0 8px;">Development</a> | <a href="#" style="margin: 0 8px;">Marketing</a> | <a href="#" style="margin: 0 8px;">Business</a></p>'
                        }
                    },
                    // Featured Post
                    {
                        widgetType: 'spacer',
                        settings: { height: 50 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Featured Article',
                            html_tag: 'h2',
                            font_size: 28,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 20 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            padding: { top: 20, right: 40, bottom: 20, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'image',
                                settings: {
                                    url: 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200',
                                    align: 'center'
                                }
                            },
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: '10 Tips for Better Web Design',
                                    html_tag: 'h3',
                                    font_size: 32,
                                    color: '#1e293b'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<p>Discover the essential principles and best practices that will take your web design skills to the next level. From typography to color theory, we cover everything you need to know.</p>'
                                }
                            },
                            {
                                widgetType: 'button',
                                settings: {
                                    text: 'Read More',
                                    bg_color: '#92003b'
                                }
                            }
                        ]
                    },
                    // Recent Posts
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Recent Posts',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center',
                            color: '#1e293b'
                        }
                    },
                    {
                        widgetType: 'spacer',
                        settings: { height: 40 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            padding: { top: 20, right: 30, bottom: 20, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Getting Started with ProBuilder',
                                    description: 'A complete guide for beginners',
                                    button_text: 'Read Article'
                                }
                            },
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Advanced Design Techniques',
                                    description: 'Pro tips and tricks',
                                    button_text: 'Read Article'
                                }
                            },
                            {
                                widgetType: 'image-box',
                                settings: {
                                    title: 'Performance Optimization',
                                    description: 'Speed up your website',
                                    button_text: 'Read Article'
                                }
                            }
                        ]
                    },
                    // Newsletter
                    {
                        widgetType: 'spacer',
                        settings: { height: 80 }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_color: '#92003b',
                            padding: { top: 60, right: 40, bottom: 60, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Never Miss an Update',
                                    html_tag: 'h2',
                                    font_size: 36,
                                    align: 'center',
                                    color: '#ffffff'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: 'Get the latest articles delivered to your inbox',
                                    font_size: 16,
                                    text_align: 'center',
                                    color: '#ffffff'
                                }
                            },
                            {
                                widgetType: 'spacer',
                                settings: { height: 20 }
                            },
                            {
                                widgetType: 'newsletter',
                                settings: {
                                    placeholder: 'Your email address',
                                    button_text: 'Subscribe Now'
                                }
                            }
                        ]
                    }
                ],
                
                'hero-3': [
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '2',
                            column_gap: 40,
                            padding: { top: 60, right: 40, bottom: 60, left: 40 },
                            min_height: 450
                        },
                        children: [
                            {
                                widgetType: 'image',
                                settings: {
                                    url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600',
                                    align: 'center'
                                }
                            },
                            {
                                widgetType: 'container',
                                settings: {
                                    columns: '1'
                                },
                                children: [
                                    {
                                        widgetType: 'heading',
                                        settings: {
                                            title: 'Innovative Solutions',
                                            html_tag: 'h2',
                                            font_size: 38
                                        }
                                    },
                                    {
                                        widgetType: 'text',
                                        settings: {
                                            content: 'We provide cutting-edge technology solutions that help your business grow and succeed in the digital age.'
                                        }
                                    },
                                    {
                                        widgetType: 'button',
                                        settings: {
                                            text: 'Learn More',
                                            bg_color: '#92003b'
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ],
                
                'features-2': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Why Choose Us',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'feature-list',
                        settings: {
                            features: [
                                { icon: 'fa fa-check-circle', title: 'Premium Quality', description: 'Top-notch products and services' },
                                { icon: 'fa fa-check-circle', title: '24/7 Support', description: 'Always here when you need us' },
                                { icon: 'fa fa-check-circle', title: 'Money Back Guarantee', description: '30-day refund policy' }
                            ]
                        }
                    }
                ],
                
                'features-3': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Key Features',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'icon-list',
                        settings: {
                            items: [
                                { text: 'Drag & Drop Builder' },
                                { text: 'Mobile Responsive' },
                                { text: 'SEO Optimized' },
                                { text: 'Fast Loading' },
                                { text: 'Regular Updates' }
                            ]
                        }
                    }
                ],
                
                'testimonial-1': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'What Our Customers Say',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'testimonial',
                        settings: {
                            name: 'Jane Cooper',
                            position: 'CEO, TechCorp',
                            content: 'This page builder has transformed how we create websites. It\'s intuitive, powerful, and saves us countless hours.',
                            rating: 5
                        }
                    }
                ],
                
                'testimonial-2': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Client Testimonials',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '2',
                            column_gap: 30,
                            padding: { top: 40, right: 20, bottom: 40, left: 20 }
                        },
                        children: [
                            {
                                widgetType: 'testimonial',
                                settings: {
                                    name: 'Robert Fox',
                                    position: 'Marketing Director',
                                    content: 'Outstanding tool! Easy to use and produces amazing results.',
                                    rating: 5
                                }
                            },
                            {
                                widgetType: 'testimonial',
                                settings: {
                                    name: 'Emily Watson',
                                    position: 'Designer',
                                    content: 'Love the flexibility and design options. Highly recommended!',
                                    rating: 5
                                }
                            }
                        ]
                    }
                ],
                
                'cta-1': [
                    {
                        widgetType: 'call-to-action',
                        settings: {
                            title: 'Start Building Today',
                            description: 'Join over 10,000 users creating amazing websites',
                            button_text: 'Get Started Now',
                            bg_color: '#92003b'
                        }
                    }
                ],
                
                'cta-2': [
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '1',
                            background_type: 'color',
                            background_color: '#1e293b',
                            padding: { top: 50, right: 40, bottom: 50, left: 40 }
                        },
                        children: [
                            {
                                widgetType: 'heading',
                                settings: {
                                    title: 'Subscribe to Our Newsletter',
                                    html_tag: 'h2',
                                    font_size: 32,
                                    color: '#ffffff',
                                    align: 'center'
                                }
                            },
                            {
                                widgetType: 'newsletter',
                                settings: {
                                    placeholder: 'Enter your email',
                                    button_text: 'Subscribe'
                                }
                            }
                        ]
                    }
                ],
                
                'team-1': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Our Team',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 25,
                            padding: { top: 40, right: 20, bottom: 40, left: 20 }
                        },
                        children: [
                            { widgetType: 'team-member', settings: { name: 'Alex Morgan', position: 'CEO', layout: 'center' } },
                            { widgetType: 'team-member', settings: { name: 'Sam Wilson', position: 'Designer', layout: 'center' } },
                            { widgetType: 'team-member', settings: { name: 'Chris Lee', position: 'Developer', layout: 'center' } },
                            { widgetType: 'team-member', settings: { name: 'Jordan Taylor', position: 'Marketer', layout: 'center' } }
                        ]
                    }
                ],
                
                'team-2': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Meet the Team',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '2',
                            column_gap: 30,
                            padding: { top: 40, right: 30, bottom: 40, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'team-member',
                                settings: {
                                    name: 'Jessica Brown',
                                    position: 'Lead Designer',
                                    bio: 'Creative designer with 10+ years of experience',
                                    layout: 'left'
                                }
                            },
                            {
                                widgetType: 'team-member',
                                settings: {
                                    name: 'David Miller',
                                    position: 'Senior Developer',
                                    bio: 'Full-stack developer specializing in modern web apps',
                                    layout: 'left'
                                }
                            }
                        ]
                    }
                ],
                
                'gallery-1': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Photo Gallery',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'gallery',
                        settings: {
                            columns: '3',
                            gap: 15
                        }
                    }
                ],
                
                'gallery-2': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Our Work',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'gallery',
                        settings: {
                            columns: '4',
                            gap: 10
                        }
                    }
                ],
                
                'contact-1': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Get in Touch',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'contact-form',
                        settings: {
                            show_labels: 'yes'
                        }
                    }
                ],
                
                'contact-2': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Contact Us',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '2',
                            column_gap: 40,
                            padding: { top: 40, right: 30, bottom: 40, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'contact-form',
                                settings: {
                                    show_labels: 'yes'
                                }
                            },
                            {
                                widgetType: 'map',
                                settings: {
                                    address: 'New York, NY, USA',
                                    height: 400
                                }
                            }
                        ]
                    }
                ],
                
                'pricing-2': [
                    {
                        widgetType: 'heading',
                        settings: {
                            title: 'Pricing Comparison',
                            html_tag: 'h2',
                            font_size: 36,
                            align: 'center'
                        }
                    },
                    {
                        widgetType: 'text',
                        settings: {
                            content: '<p style="text-align: center;">Detailed comparison of all our plans and features</p>'
                        }
                    }
                ],
                
                'footer-1': [
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '3',
                            column_gap: 30,
                            background_color: '#1e293b',
                            padding: { top: 50, right: 30, bottom: 30, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">About</h4><p style="color: #cbd5e1; font-size: 14px;">We create amazing websites.</p>',
                                    color: '#ffffff'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">Quick Links</h4><ul style="color: #cbd5e1; font-size: 14px;"><li>Home</li><li>Services</li><li>Contact</li></ul>',
                                    color: '#ffffff'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">Contact</h4><p style="color: #cbd5e1; font-size: 14px;">Email: info@example.com</p>',
                                    color: '#ffffff'
                                }
                            }
                        ]
                    }
                ],
                
                'footer-2': [
                    {
                        widgetType: 'container',
                        settings: {
                            columns: '4',
                            column_gap: 25,
                            background_color: '#0f172a',
                            padding: { top: 50, right: 30, bottom: 30, left: 30 }
                        },
                        children: [
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">Company</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>About</li><li>Team</li><li>Careers</li></ul>'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">Products</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>Features</li><li>Pricing</li><li>FAQ</li></ul>'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">Resources</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>Blog</li><li>Docs</li><li>Support</li></ul>'
                                }
                            },
                            {
                                widgetType: 'text',
                                settings: {
                                    content: '<h4 style="color: white;">Legal</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>Privacy</li><li>Terms</li><li>Cookies</li></ul>'
                                }
                            }
                        ]
                    }
                ]
            };
            
            return templates[templateId] || [];
        },
        
        /**
         * Show widget picker modal
         */
        showWidgetPicker: function(insertIndex) {
            const self = this;
            
            // Create widget picker overlay with tabs and search
            const $overlay = $(`
                <div class="probuilder-widget-picker-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.7);
                    z-index: 10000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
                ">
                    <div class="probuilder-widget-picker" style="
                        background: #ffffff;
                        border-radius: 8px;
                        padding: 0;
                        max-width: 700px;
                        width: 90%;
                        max-height: 85vh;
                        overflow: hidden;
                        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                        display: flex;
                        flex-direction: column;
                    ">
                        <!-- Header -->
                        <div style="padding: 25px 30px 20px; border-bottom: 1px solid #e6e9ec;">
                            <h3 style="margin: 0 0 15px 0; font-size: 22px; color: #27272a; font-weight: 600;">Select a Widget</h3>
                            
                            <!-- Search Input -->
                            <input type="text" 
                                   class="probuilder-picker-search" 
                                   placeholder="Search widgets..." 
                                   style="
                                       width: 100%;
                                       padding: 12px 15px 12px 40px;
                                       border: 2px solid #e6e9ec;
                                       border-radius: 6px;
                                       font-size: 14px;
                                       outline: none;
                                       transition: all 0.2s;
                                       background: #fafafa url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOCIgaGVpZ2h0PSIxOCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM3MTcxN2EiIHN0cm9rZS13aWR0aD0iMiI+PGNpcmNsZSBjeD0iMTEiIGN5PSIxMSIgcj0iOCIvPjxwYXRoIGQ9Im0yMSAyMS00LjM1LTQuMzUiLz48L3N2Zz4=') no-repeat 12px center;
                                   ">
                        </div>
                        
                        <!-- Tabs -->
                        <div class="probuilder-picker-tabs" style="
                            display: flex;
                            border-bottom: 1px solid #e6e9ec;
                            background: #fafafa;
                        ">
                            <button class="probuilder-picker-tab active" data-tab="widgets" style="
                                flex: 1;
                                padding: 15px 20px;
                                border: none;
                                background: #ffffff;
                                cursor: pointer;
                                font-size: 14px;
                                font-weight: 600;
                                color: #344047;
                                border-bottom: 3px solid #344047;
                                transition: all 0.2s;
                            ">
                                <i class="dashicons dashicons-screenoptions" style="font-size: 18px; margin-right: 6px;"></i>
                                Widgets
                            </button>
                            <button class="probuilder-picker-tab" data-tab="templates" style="
                                flex: 1;
                                padding: 15px 20px;
                                border: none;
                                background: transparent;
                                cursor: pointer;
                                font-size: 14px;
                                font-weight: 600;
                                color: #71717a;
                                border-bottom: 3px solid transparent;
                                transition: all 0.2s;
                            ">
                                <i class="dashicons dashicons-layout" style="font-size: 18px; margin-right: 6px;"></i>
                                Templates
                            </button>
                        </div>
                        
                        <!-- Content Area -->
                        <div class="probuilder-picker-content" style="
                            flex: 1;
                            overflow-y: auto;
                            padding: 25px 30px;
                        ">
                            <!-- Widgets Tab Content -->
                            <div class="probuilder-picker-tab-content active" data-tab="widgets">
                                <div class="probuilder-picker-grid" style="
                                    display: grid;
                                    grid-template-columns: repeat(3, 1fr);
                                    gap: 15px;
                                "></div>
                            </div>
                            
                            <!-- Templates Tab Content -->
                            <div class="probuilder-picker-tab-content" data-tab="templates" style="display: none;">
                                <div style="text-align: center; padding: 40px 20px; color: #71717a;">
                                    <i class="dashicons dashicons-layout" style="font-size: 64px; opacity: 0.2; margin-bottom: 15px;"></i>
                                    <h4 style="margin: 0 0 10px; font-size: 16px; color: #344047;">Templates Coming Soon</h4>
                                    <p style="margin: 0; font-size: 13px;">Pre-made templates will be available here for quick page building.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div style="padding: 15px 30px; border-top: 1px solid #e6e9ec; background: #fafafa; text-align: right;">
                            <button class="probuilder-picker-close" style="
                                padding: 10px 25px;
                                background: #344047;
                                color: #ffffff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 13px;
                                transition: all 0.2s;
                            ">Close</button>
                        </div>
                    </div>
                </div>
            `);
            
            // Add widgets to picker
            const $grid = $overlay.find('.probuilder-picker-grid');
            this.widgets.forEach(widget => {
                const $card = $(`
                    <div class="probuilder-picker-widget" data-widget="${widget.name}" style="
                        background: #fafafa;
                        border: 2px solid #e6e9ec;
                        border-radius: 4px;
                        padding: 20px 15px;
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">
                        <i class="${widget.icon}" style="font-size: 32px; color: #92003b; margin-bottom: 10px;"></i>
                        <div style="font-size: 12px; font-weight: 600; color: #27272a;">${widget.title}</div>
                    </div>
                `);
                
                $card.on('click', function() {
                    const widgetName = $(this).data('widget');
                    if (insertIndex !== null) {
                        // Insert at specific index
                        self.insertElementAt(widgetName, insertIndex);
                    } else {
                        // Add to end
                        self.addElement(widgetName);
                    }
                    $overlay.remove();
                });
                
                $card.on('mouseenter', function() {
                    $(this).css({
                        'background': '#ffffff',
                        'border-color': '#92003b',
                        'transform': 'translateY(-3px)',
                        'box-shadow': '0 4px 16px rgba(147, 0, 60, 0.15)'
                    });
                }).on('mouseleave', function() {
                    $(this).css({
                        'background': '#fafafa',
                        'border-color': '#e6e9ec',
                        'transform': 'translateY(0)',
                        'box-shadow': 'none'
                    });
                });
                
                $grid.append($card);
            });
            
            // Tab switching
            $overlay.find('.probuilder-picker-tab').on('click', function() {
                const tab = $(this).data('tab');
                
                // Update active tab
                $overlay.find('.probuilder-picker-tab').removeClass('active').css({
                    'background': 'transparent',
                    'color': '#71717a',
                    'border-bottom-color': 'transparent'
                });
                
                $(this).addClass('active').css({
                    'background': '#ffffff',
                    'color': '#344047',
                    'border-bottom-color': '#344047'
                });
                
                // Show corresponding content
                $overlay.find('.probuilder-picker-tab-content').hide();
                $overlay.find(`.probuilder-picker-tab-content[data-tab="${tab}"]`).show();
                
                console.log('Switched to tab:', tab);
            });
            
            // Search functionality
            $overlay.find('.probuilder-picker-search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                
                if (searchTerm === '') {
                    // Show all widgets
                    $overlay.find('.probuilder-picker-widget').show();
                } else {
                    // Filter widgets
                    $overlay.find('.probuilder-picker-widget').each(function() {
                        const widgetTitle = $(this).find('div').text().toLowerCase();
                        const widgetName = $(this).data('widget').toLowerCase();
                        
                        if (widgetTitle.includes(searchTerm) || widgetName.includes(searchTerm)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
            
            // Search input focus effect
            $overlay.find('.probuilder-picker-search').on('focus', function() {
                $(this).css({
                    'border-color': '#344047',
                    'background': '#ffffff'
                });
            }).on('blur', function() {
                $(this).css({
                    'border-color': '#e6e9ec',
                    'background': '#fafafa'
                });
            });
            
            // Close button
            $overlay.find('.probuilder-picker-close').on('click', function() {
                $overlay.remove();
            });
            
            // Click outside to close
            $overlay.on('click', function(e) {
                if ($(e.target).hasClass('probuilder-widget-picker-overlay')) {
                    $overlay.remove();
                }
            });
            
            // ESC key to close
            $(document).on('keydown.widgetPicker', function(e) {
                if (e.key === 'Escape') {
                    $overlay.remove();
                    $(document).off('keydown.widgetPicker');
                }
            });
            
            $('body').append($overlay);
            
            // Focus search input
            setTimeout(() => {
                $overlay.find('.probuilder-picker-search').focus();
            }, 100);
        },
        
        /**
         * Insert element at specific index
         */
        insertElementAt: function(widgetName, index) {
            // Ensure elements is always an array
            if (!Array.isArray(this.elements)) {
                console.warn('⚠️ this.elements was not an array! Initializing as empty array.');
                this.elements = [];
            }
            
            const widget = this.widgets.find(w => w.name === widgetName);
            if (!widget) {
                console.error('Widget not found:', widgetName);
                return;
            }
            
            const element = {
                id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                widgetType: widgetName,
                settings: this.getDefaultSettings(widget),
                children: []
            };
            
            this.elements.splice(index, 0, element);
            this.renderElements();
            this.updateEmptyState();
            this.makeContainersDroppable();
            
            console.log('Element inserted at index:', index, widgetName);
            
            return element;
        },
        
        /**
         * Create New Page
         */
        createNewPage: function() {
            const self = this;
            
            if (!confirm('Create a new blank page? Current unsaved changes will be lost.')) {
                return;
            }
            
            $('#probuilder-loading').show();
            
            // Get current post type
            const urlParams = new URLSearchParams(window.location.search);
            const postType = urlParams.get('post_type') || 'page';
            
            // Create new page via AJAX
            $.ajax({
                url: ProBuilderEditor.ajax_url,
                type: 'POST',
                data: {
                    action: 'probuilder_create_new_page',
                    nonce: ProBuilderEditor.nonce,
                    post_type: postType
                },
                success: function(response) {
                    if (response.success) {
                        // Redirect to new page editor
                        const newUrl = response.data.editor_url;
                        window.location.href = newUrl;
                    } else {
                        alert('Failed to create new page: ' + (response.data.message || 'Unknown error'));
                        $('#probuilder-loading').hide();
                    }
                },
                error: function() {
                    alert('Failed to create new page. Please try again.');
                    $('#probuilder-loading').hide();
                }
            });
        },
        
        /**
         * Clear Page
         */
        clearPage: function() {
            const self = this;
            
            if (!confirm('Clear all content from this page? This action cannot be undone!')) {
                return;
            }
            
            // Clear all elements
            self.elements = [];
            
            // Clear the canvas
            $('#probuilder-preview-area').empty();
            
            // Save history
            self.saveHistory();
            
            // Show success message
            self.showNotification('Page cleared! Add widgets to start building.', 'success');
            
            console.log('✅ Page cleared');
        },
        
        /**
         * Show page settings modal
         */
        showPageSettings: function() {
            const self = this;
            const postId = ProBuilderEditor.post_id;
            
            // Get current page data
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_get_page_settings',
                    nonce: ProBuilderEditor.nonce,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        self.renderPageSettingsModal(data);
                    } else {
                        alert('Error loading page settings');
                    }
                },
                error: function() {
                    alert('Error loading page settings');
                }
            });
        },
        
        /**
         * Render page settings modal
         */
        renderPageSettingsModal: function(data) {
            const self = this;
            
            const $modal = $(`
                <div class="probuilder-page-settings-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.7);
                    z-index: 100000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
                ">
                    <div class="probuilder-page-settings-modal" style="
                        background: #ffffff;
                        border-radius: 8px;
                        width: 600px;
                        max-width: 90%;
                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                        overflow: hidden;
                    ">
                        <div style="padding: 25px 30px; border-bottom: 1px solid #e6e9ec; background: #fafafa;">
                            <h3 style="margin: 0; font-size: 20px; color: #344047; font-weight: 600;">
                                <i class="dashicons dashicons-admin-generic" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
                                Page Settings
                            </h3>
                        </div>
                        
                        <div style="padding: 30px;">
                            <!-- Page Title -->
                            <div style="margin-bottom: 25px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #344047; font-size: 13px;">
                                    <i class="dashicons dashicons-edit" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                                    Page Title
                                </label>
                                <input type="text" 
                                       id="probuilder-page-title-input" 
                                       value="${data.title || ''}"
                                       placeholder="Enter page title..."
                                       style="
                                           width: 100%;
                                           padding: 12px 15px;
                                           border: 2px solid #e6e9ec;
                                           border-radius: 6px;
                                           font-size: 14px;
                                           outline: none;
                                           transition: all 0.2s;
                                       ">
                            </div>
                            
                            <!-- Page URL/Slug -->
                            <div style="margin-bottom: 25px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #344047; font-size: 13px;">
                                    <i class="dashicons dashicons-admin-links" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                                    Page URL (Slug)
                                </label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #71717a; font-size: 13px; white-space: nowrap;">${data.site_url}/</span>
                                    <input type="text" 
                                           id="probuilder-page-slug-input" 
                                           value="${data.slug || ''}"
                                           placeholder="page-url"
                                           style="
                                               flex: 1;
                                               padding: 12px 15px;
                                               border: 2px solid #e6e9ec;
                                               border-radius: 6px;
                                               font-size: 14px;
                                               outline: none;
                                               transition: all 0.2s;
                                           ">
                                </div>
                                <p style="margin: 8px 0 0; font-size: 12px; color: #71717a;">
                                    <i class="dashicons dashicons-info" style="font-size: 14px; vertical-align: middle;"></i>
                                    URL-friendly characters only (lowercase, numbers, hyphens)
                                </p>
                            </div>
                            
                            <!-- Current URL Display -->
                            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; margin-bottom: 20px;">
                                <p style="margin: 0 0 5px; font-size: 11px; font-weight: 600; color: #71717a; text-transform: uppercase;">Current URL:</p>
                                <p id="probuilder-current-url" style="margin: 0; font-size: 13px; color: #344047; word-break: break-all;">
                                    ${data.permalink || ''}
                                </p>
                            </div>
                        </div>
                        
                        <div style="padding: 20px 30px; border-top: 1px solid #e6e9ec; background: #fafafa; display: flex; justify-content: flex-end; gap: 10px;">
                            <button class="probuilder-page-settings-cancel" style="
                                padding: 10px 25px;
                                background: #f4f4f5;
                                color: #344047;
                                border: 2px solid #e6e9ec;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 13px;
                                transition: all 0.2s;
                            ">Cancel</button>
                            <button class="probuilder-page-settings-save" style="
                                padding: 10px 25px;
                                background: #344047;
                                color: #ffffff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 13px;
                                transition: all 0.2s;
                            ">
                                <i class="dashicons dashicons-saved" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            // Auto-generate slug from title
            $modal.find('#probuilder-page-title-input').on('input', function() {
                const title = $(this).val();
                const currentSlug = $('#probuilder-page-slug-input').val();
                
                // Only auto-generate if slug is empty
                if (!currentSlug || currentSlug === '') {
                    const slug = title.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');
                    $('#probuilder-page-slug-input').val(slug);
                    $('#probuilder-current-url').text(data.site_url + '/' + slug + '/');
                }
            });
            
            // Update URL preview on slug change
            $modal.find('#probuilder-page-slug-input').on('input', function() {
                let slug = $(this).val();
                // Sanitize slug in real-time
                slug = slug.toLowerCase()
                    .replace(/[^a-z0-9-]/g, '')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                $(this).val(slug);
                $('#probuilder-current-url').text(data.site_url + '/' + slug + '/');
            });
            
            // Focus styles
            $modal.find('input').on('focus', function() {
                $(this).css('border-color', '#344047');
            }).on('blur', function() {
                $(this).css('border-color', '#e6e9ec');
            });
            
            // Hover effects
            $modal.find('.probuilder-page-settings-cancel').hover(
                function() { $(this).css({'background': '#e6e9ec', 'transform': 'translateY(-1px)'}); },
                function() { $(this).css({'background': '#f4f4f5', 'transform': 'translateY(0)'}); }
            );
            
            $modal.find('.probuilder-page-settings-save').hover(
                function() { $(this).css({'background': '#2c3540', 'transform': 'translateY(-1px)'}); },
                function() { $(this).css({'background': '#344047', 'transform': 'translateY(0)'}); }
            );
            
            // Cancel button
            $modal.find('.probuilder-page-settings-cancel').on('click', function() {
                $modal.remove();
            });
            
            // Close on overlay click
            $modal.on('click', function(e) {
                if ($(e.target).hasClass('probuilder-page-settings-overlay')) {
                    $modal.remove();
                }
            });
            
            // Save button
            $modal.find('.probuilder-page-settings-save').on('click', function() {
                const newTitle = $('#probuilder-page-title-input').val().trim();
                const newSlug = $('#probuilder-page-slug-input').val().trim();
                
                if (!newTitle) {
                    alert('Please enter a page title');
                    return;
                }
                
                if (!newSlug) {
                    alert('Please enter a page URL');
                    return;
                }
                
                // Save via AJAX
                $(this).prop('disabled', true).text('Saving...');
                
                $.ajax({
                    url: ProBuilderEditor.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'probuilder_save_page_settings',
                        nonce: ProBuilderEditor.nonce,
                        post_id: ProBuilderEditor.post_id,
                        title: newTitle,
                        slug: newSlug
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update page title in header
                            $('.probuilder-page-title').text(newTitle);
                            
                            // Show success message
                            self.showToast('✅ Page settings updated!');
                            
                            // Close modal
                            $modal.remove();
                            
                            // Update the current URL in browser if needed
                            console.log('✅ Page settings saved:', {title: newTitle, slug: newSlug});
                        } else {
                            alert('Error saving page settings: ' + (response.data?.message || 'Unknown error'));
                            $(this).prop('disabled', false).html('<i class="dashicons dashicons-saved"></i> Save Changes');
                        }
                    },
                    error: function() {
                        alert('Error saving page settings. Please try again.');
                        $(this).prop('disabled', false).html('<i class="dashicons dashicons-saved"></i> Save Changes');
                    }
                });
            });
            
            // Add to DOM
            $('body').append($modal);
            
            // Focus on title input
            setTimeout(() => {
                $('#probuilder-page-title-input').focus().select();
            }, 100);
        },
        
        /**
         * Save page
         */
        savePage: function() {
            $('#probuilder-loading').show();
            
            // Debug: Log what we're about to save
            console.log('=== SAVING PAGE ===');
            console.log('Post ID:', ProBuilderEditor.post_id);
            console.log('Elements count:', this.elements.length);
            console.log('Elements array:', this.elements);
            
            if (this.elements.length > 0) {
                console.log('First element:', this.elements[0]);
                if (this.elements[0].widgetType === 'heading') {
                    console.log('Heading text:', this.elements[0].settings?.title);
                }
            }
            
            // Ensure elements is an array
            if (!Array.isArray(this.elements)) {
                console.error('❌ this.elements is not an array!');
                alert('Error: Cannot save - data is corrupted. Please refresh the page.');
                $('#probuilder-loading').hide();
                return;
            }
            
            const elementsForSave = this.prepareElementsForSave(this.elements);

            const elementsJSON = JSON.stringify(elementsForSave);
            console.log('JSON length:', elementsJSON.length);
            console.log('JSON preview:', elementsJSON.substring(0, 200));
            
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_save_page',
                    nonce: ProBuilderEditor.nonce,
                    post_id: ProBuilderEditor.post_id,
                    elements: elementsJSON
                },
                success: function(response) {
                    $('#probuilder-loading').hide();
                    
                    if (response.success) {
                        const permalink = response.data?.permalink || '';
                        const elementCount = response.data?.element_count || 0;
                        
                        // Show success message with permalink
                        const listAllPagesUrl = ProBuilderEditor.home_url + '/wp-content/plugins/probuilder/list-all-pages.php';
                        
                        const $message = $(`
                            <div class="probuilder-notice probuilder-notice-success" style="
                                position: fixed;
                                top: 80px;
                                left: 50%;
                                transform: translateX(-50%);
                                background: #ffffff;
                                border-left: 4px solid #22c55e;
                                padding: 25px 30px;
                                border-radius: 12px;
                                box-shadow: 0 10px 40px rgba(0,0,0,0.25);
                                z-index: 99999;
                                min-width: 450px;
                                max-width: 650px;
                            ">
                                <div style="font-size: 18px; font-weight: 700; color: #16a34a; margin-bottom: 8px;">
                                    <i class="dashicons dashicons-yes-alt" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
                                    Page Saved Successfully!
                                </div>
                                <div style="font-size: 14px; color: #71717a; margin-bottom: 18px;">
                                    <strong>${elementCount}</strong> element(s) saved to this page
                                </div>
                                ${permalink ? `
                                    <div style="background: #f8f9fa; padding: 12px 15px; border-radius: 6px; margin-bottom: 15px;">
                                        <div style="font-size: 11px; color: #71717a; margin-bottom: 5px; font-weight: 600; text-transform: uppercase;">Page URL:</div>
                                        <div style="font-size: 13px; color: #344047; word-break: break-all; font-family: monospace;">
                                            ${permalink}
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="${permalink}" target="_blank" style="
                                            flex: 1;
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            background: #22c55e;
                                            color: #ffffff;
                                            padding: 12px 20px;
                                            border-radius: 6px;
                                            text-decoration: none;
                                            font-size: 14px;
                                            font-weight: 600;
                                            transition: all 0.2s;
                                            gap: 8px;
                                        " onmouseover="this.style.background='#16a34a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(34,197,94,0.3)'" onmouseout="this.style.background='#22c55e'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                            <i class="dashicons dashicons-external" style="font-size: 18px;"></i>
                                            View This Page
                                        </a>
                                        <a href="${listAllPagesUrl}" target="_blank" style="
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            background: #344047;
                                            color: #ffffff;
                                            padding: 12px 20px;
                                            border-radius: 6px;
                                            text-decoration: none;
                                            font-size: 14px;
                                            font-weight: 600;
                                            transition: all 0.2s;
                                            gap: 8px;
                                        " onmouseover="this.style.background='#2c3540'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#344047'; this.style.transform='translateY(0)'">
                                            <i class="dashicons dashicons-list-view" style="font-size: 18px;"></i>
                                            All Pages
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        `);
                        
                        $('body').append($message);
                        setTimeout(() => $message.fadeOut(() => $message.remove()), 5000);
                        
                        console.log('✅ Page saved with', elementCount, 'elements');
                        console.log('📍 View at:', permalink);
                    } else {
                        alert('Error saving page: ' + (response.data?.message || 'Unknown error'));
                    }
                },
                error: function() {
                    $('#probuilder-loading').hide();
                    alert('Error saving page. Please try again.');
                }
            });
        }
        
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        console.log('Document ready, initializing ProBuilder...');
        console.log('jQuery version:', $.fn.jquery);
        
        // Check if containers exist
        console.log('Sidebar exists:', $('.probuilder-sidebar').length > 0);
        console.log('Canvas exists:', $('.probuilder-canvas').length > 0);
        console.log('Preview area exists:', $('#probuilder-preview-area').length > 0);
        
        // Small delay to ensure everything is loaded
        setTimeout(function() {
            ProBuilder.init();
        }, 100);
    });
    
    // Make ProBuilder globally available
    window.ProBuilder = ProBuilder;
    
    // Log when script loads
    console.log('ProBuilder Editor JavaScript loaded successfully!');
    
})(jQuery);

