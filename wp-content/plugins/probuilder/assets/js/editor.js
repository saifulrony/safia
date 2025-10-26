/**
 * ProBuilder Editor JavaScript
 */

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
                                <input type="color" class="probuilder-btn-bgcolor" value="${btn.bgColor}" data-type="${type}">
                            </label>
                            <label>
                                <span>Text:</span>
                                <input type="color" class="probuilder-btn-textcolor" value="${btn.textColor}" data-type="${type}">
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
         * Import template data
         */
        importTemplate: function(templateData) {
            console.log('Importing template:', templateData);
            
            if (!templateData || !Array.isArray(templateData)) {
                console.error('Invalid template data');
                return;
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
         * Load saved data
         */
        loadSavedData: function() {
            const data = $('#probuilder-data').val();
            if (data && data !== '[]' && data !== '') {
                try {
                    this.elements = JSON.parse(data);
                    this.renderElements();
                } catch (e) {
                    console.error('Failed to parse saved data:', e);
                }
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
                    console.log('Template data:', template.data);
                    
                    // Clear existing canvas first
                    self.clearCanvas();
                    
                    // Import template data
                    if (Array.isArray(template.data)) {
                        template.data.forEach(function(element) {
                            console.log('Adding element:', element);
                            if (element.widgetType) {
                                self.addElement(element.widgetType, element.settings || {});
                            }
                        });
                    } else {
                        if (template.data.widgetType) {
                            self.addElement(template.data.widgetType, template.data.settings || {});
                        }
                    }
                    
                    // Show success message
                    self.showToast('✓ Template inserted successfully!');
                    
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
                start: function() {
                    const widgetName = $(this).data('widget');
                    console.log('Started dragging widget:', widgetName);
                }
            });
            
            console.log('✅ Widgets are now draggable:', $('.probuilder-widget').length);
            
            // Make preview area sortable (for reordering existing elements)
            const $previewArea = $('#probuilder-preview-area');
            console.log('Preview area found:', $previewArea.length > 0 ? 'YES' : 'NO');
            
            $previewArea.sortable({
                items: '> .probuilder-element',
                placeholder: 'probuilder-element-placeholder',
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.7,
                distance: 15,
                delay: 200,
                revert: 150,
                cancel: 'input, textarea, select, button, a, .probuilder-element-actions, .probuilder-add-below-btn',
                start: function(event, ui) {
                    console.log('🎯 Started dragging element');
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
                    // Check if we're actually over a column (don't add if so)
                    const $target = $(event.target);
                    if ($target.hasClass('probuilder-column') || $target.closest('.probuilder-column').length > 0) {
                        console.log('🎯 Drop intercepted by column, skipping preview area handler');
                        return; // Column will handle it
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
                $('.probuilder-element[data-widget="container"]').each(function() {
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
                            const widgetName = ui.draggable.data('widget');
                            
                            // Remove overlay
                            $(this).find('.probuilder-drop-overlay').remove();
                            
                            console.log('✅ Dropped widget in container (will add to end):', widgetName);
                            
                            if (widgetName && containerId) {
                                // Always add to end of container to create new row
                                self.addElementToContainer(widgetName, containerId, null);
                            }
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
                        greedy: false, // Allow container to also handle drops
                        drop: function(event, ui) {
                            // Only handle if this is an empty drop zone
                            if (!$column.hasClass('probuilder-drop-zone')) {
                                console.log('Column has content, letting container handle drop');
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
                const containerId = $zone.data('container-id');
                const columnIndex = $zone.data('column-index');
                
                $zone.off('click').on('click', function(e) {
                    e.stopPropagation();
                    console.log('Drop zone clicked:', containerId, 'column:', columnIndex);
                    self.showWidgetTemplateSelector(containerId, columnIndex);
                });
            });
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            const self = this;
            
            // Save button
            $('#probuilder-save').on('click', function() {
                self.savePage();
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
                const url = '<?php echo home_url(); ?>/?p=' + ProBuilderEditor.post_id + '&preview=true';
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
                        start: function() {
                            console.log('Started dragging widget:', widgetName);
                            $('.probuilder-element-placeholder').show();
                            $('.probuilder-column').css('outline', '2px dashed #344047');
                            $('#probuilder-preview-area, .probuilder-column').addClass('drop-ready');
                        },
                        stop: function() {
                            $('.probuilder-column').css('outline', '');
                            $('#probuilder-preview-area, .probuilder-column').removeClass('drop-ready');
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
                        <div class="probuilder-element-add-below">
                            <button class="probuilder-add-below-btn" title="Add Element Below">
                                <i class="dashicons dashicons-plus-alt2"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                console.log('Element HTML created');
                
                const self = this;
                
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
                
                // Add below button
                $element.find('.probuilder-add-below-btn').on('click', function(e) {
                    e.stopPropagation();
                    console.log('Add below button clicked for:', element.id);
                    self.showAddElementModal(element);
                });
                
                // Click to select
                $element.on('click', function(e) {
                    if (!$(e.target).closest('.probuilder-element-actions').length && 
                        !$(e.target).closest('.probuilder-column-btn').length &&
                        !$(e.target).closest('.probuilder-drop-zone').length &&
                        !$(e.target).closest('.probuilder-add-below-btn').length) {
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
                                console.log('✅ Initial drop zone clicked:', containerId, 'column:', columnIndex);
                                self.showWidgetTemplateSelector(containerId, columnIndex);
                                return false;
                            });
                        });
                        
                        console.log('✅ Initial drop zone handlers attached');
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
                
                // Make element sortable/movable
                $('#probuilder-preview-area').sortable('refresh');
                
                console.log('✅ Element fully rendered and interactive:', element.id);
            } catch (error) {
                console.error('❌ Error rendering element:', error);
                alert('Error rendering element: ' + error.message);
                return;
            }
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
                
                // Enhanced preview based on widget type
                switch (element.widgetType) {
                    case 'heading':
                        const headingTag = settings.html_tag || 'h2';
                        const headingColor = settings.color || this.getGlobalColor('primary') || '#344047';
                        const headingStyle = `
                            color: ${headingColor};
                            font-size: ${settings.font_size || 32}px;
                            font-weight: ${settings.font_weight || 600};
                            text-align: ${settings.align || 'left'};
                            margin: 0;
                            line-height: 1.3;
                        `;
                        return `<${headingTag} style="${headingStyle}">${settings.title || 'This is a heading'}</${headingTag}>`;
                    
                case 'text':
                    const textColor = settings.text_color || this.getGlobalColor('text') || '#495157';
                    const textStyle = `
                        color: ${textColor};
                        font-size: ${settings.font_size || 16}px;
                        line-height: ${settings.line_height || 1.6};
                        margin: 0;
                    `;
                    const textContent = (settings.text || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.').substring(0, 200);
                    return `<div style="${textStyle}">${textContent}</div>`;
                    
                case 'button':
                    const btnBgColor = settings.bg_color || this.getGlobalColor('primary') || '#344047';
                    const btnTextColor = settings.text_color || '#ffffff';
                    const btnStyle = `
                        padding: ${settings.padding?.top || 12}px ${settings.padding?.right || 24}px ${settings.padding?.bottom || 12}px ${settings.padding?.left || 24}px;
                        background: ${btnBgColor};
                        color: ${btnTextColor};
                        border: none;
                        border-radius: ${settings.border_radius || 4}px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 500;
                        display: inline-block;
                    `;
                    const btnIcon = settings.icon ? `<i class="${settings.icon}"></i> ` : '';
                    return `<div style="text-align: ${settings.align || 'left'}"><button style="${btnStyle}">${btnIcon}${settings.text || 'Click Here'}</button></div>`;
                    
                case 'image':
                    const imgUrl = settings.image?.url || 'https://via.placeholder.com/800x600/e6e9ec/93003c?text=Image';
                    return `<div style="text-align: ${settings.align || 'center'}"><img src="${imgUrl}" style="max-width: ${settings.width || 100}%; height: auto; border-radius: 3px;"></div>`;
                    
                case 'divider':
                    return `<hr style="border: none; border-top: ${settings.height || 1}px ${settings.style || 'solid'} ${settings.color || '#ddd'}; width: ${settings.width || 100}%; margin: 15px auto;">`;
                    
                case 'spacer':
                    return `<div style="height: ${settings.height || 50}px;"></div>`;
                    
                case 'container':
                    const columns = settings.columns || '1';
                    const columnsTablet = settings.columns_tablet || '2';
                    const columnsMobile = settings.columns_mobile || '1';
                    const columnGap = settings.column_gap || 20;
                    const enableRows = settings.enable_rows || 'no';
                    const rows = settings.rows || [];
                    const containerId = 'container-preview-' + element.id;
                    const bgType = settings.background_type || 'color';
                    let background = '';
                    
                    if (bgType === 'color') {
                        background = `background-color: ${settings.background_color || '#f8f9fa'};`;
                    } else if (bgType === 'gradient') {
                        background = `background: ${settings.background_gradient || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'};`;
                    } else if (bgType === 'image' && settings.background_image?.url) {
                        background = `background-image: url(${settings.background_image.url}); background-size: cover; background-position: center;`;
                    }
                    
                    const containerStyle = `
                        ${background}
                        min-height: ${settings.min_height || 100}px;
                        padding: ${settings.padding?.top || 20}px ${settings.padding?.right || 20}px ${settings.padding?.bottom || 20}px ${settings.padding?.left || 20}px;
                        border: ${settings.border?.width || 0}px ${settings.border?.style || 'solid'} ${settings.border?.color || '#000'};
                        border-radius: ${settings.border_radius || 0}px;
                    `;
                    
                    // Generate responsive CSS
                    let responsiveCSS = `
                        <style>
                            #${containerId} .probuilder-container-row {
                                display: block;
                                width: 100%;
                                margin-bottom: 20px;
                            }
                            #${containerId} .probuilder-container-row:last-child {
                                margin-bottom: 0;
                            }
                            #${containerId} .probuilder-container-columns {
                                display: grid;
                                width: 100%;
                            }
                    `;
                    
                    if (enableRows === 'yes' && rows.length > 0) {
                        // Multiple rows CSS
                        rows.forEach((row, index) => {
                            responsiveCSS += `
                                #${containerId} .probuilder-row-${index} .probuilder-container-columns {
                                    grid-template-columns: repeat(${row.row_columns || 2}, 1fr);
                                    gap: ${row.row_gap || 20}px;
                                }
                                @media (max-width: 1024px) {
                                    #${containerId} .probuilder-row-${index} .probuilder-container-columns {
                                        grid-template-columns: repeat(${row.row_columns_tablet || 2}, 1fr);
                                    }
                                }
                                @media (max-width: 767px) {
                                    #${containerId} .probuilder-row-${index} .probuilder-container-columns {
                                        grid-template-columns: repeat(${row.row_columns_mobile || 1}, 1fr);
                                    }
                                }
                            `;
                        });
                    } else {
                        // Single row CSS
                        responsiveCSS += `
                            #${containerId} .probuilder-container-columns {
                                grid-template-columns: repeat(${columns}, 1fr);
                                gap: ${columnGap}px;
                            }
                            @media (max-width: 1024px) {
                                #${containerId} .probuilder-container-columns {
                                    grid-template-columns: repeat(${columnsTablet}, 1fr);
                                }
                            }
                            @media (max-width: 767px) {
                                #${containerId} .probuilder-container-columns {
                                    grid-template-columns: repeat(${columnsMobile}, 1fr);
                                }
                            }
                        `;
                    }
                    
                    responsiveCSS += `</style>`;
                    
                    // Check if container has children
                    const hasChildren = element.children && element.children.length > 0;
                    
                    if (enableRows === 'yes' && rows.length > 0) {
                        // Multiple rows layout
                        let rowsHTML = '';
                        let childIndex = 0;
                        
                        rows.forEach((row, rowIndex) => {
                            rowsHTML += `<div class="probuilder-container-row probuilder-row-${rowIndex}">`;
                            rowsHTML += '<div class="probuilder-container-columns">';
                            
                            const numColumns = parseInt(row.row_columns || 2);
                            
                            // Show columns for this row
                            for (let colIndex = 0; colIndex < numColumns; colIndex++) {
                                const child = element.children && element.children[childIndex];
                                
                                if (child) {
                                    // Render child element
                                    const childPreview = this.generatePreview(child, depth + 1);
                                    rowsHTML += `
                                        <div class="probuilder-column" style="min-height: 50px; padding: 5px; position: relative; z-index: 1;">
                                            <div class="probuilder-nested-element" data-id="${child.id}" data-widget="${child.widgetType}" style="position: relative; z-index: 1;">
                                                <div class="probuilder-nested-controls" style="
                                                    position: absolute;
                                                    top: 0;
                                                    right: 0;
                                                    display: none;
                                                    gap: 4px;
                                                    z-index: 100;
                                                    background: rgba(255, 255, 255, 0.95);
                                                    padding: 4px;
                                                    border-radius: 3px;
                                                    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                                ">
                                                    <button class="probuilder-nested-drag" title="Move" style="
                                                        background: #71717a;
                                                        border: none;
                                                        color: #ffffff;
                                                        width: 24px;
                                                        height: 24px;
                                                        border-radius: 2px;
                                                        cursor: move;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        font-size: 12px;
                                                    ">
                                                        <i class="dashicons dashicons-move" style="font-size: 12px;"></i>
                                                    </button>
                                                    <button class="probuilder-nested-edit" title="Edit" style="
                                                        background: #92003b;
                                                        border: none;
                                                        color: #ffffff;
                                                        width: 24px;
                                                        height: 24px;
                                                        border-radius: 2px;
                                                        cursor: pointer;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        font-size: 12px;
                                                    ">
                                                        <i class="dashicons dashicons-edit" style="font-size: 12px;"></i>
                                                    </button>
                                                    <button class="probuilder-nested-delete" title="Delete" style="
                                                        background: #dc2626;
                                                        border: none;
                                                        color: #ffffff;
                                                        width: 24px;
                                                        height: 24px;
                                                        border-radius: 2px;
                                                        cursor: pointer;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        font-size: 12px;
                                                    ">
                                                        <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                                    </button>
                                                </div>
                                                <div class="probuilder-nested-preview">
                                                    ${childPreview}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    childIndex++;
                                } else {
                                    // Show empty drop zone
                                    rowsHTML += `<div class="probuilder-column probuilder-drop-zone" data-container-id="${element.id}" data-column-index="${childIndex}" style="min-height: 100px; border: 1px dashed #d5dadf; padding: 20px; text-align: center; color: #a4afb7; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                        <i class="dashicons dashicons-plus" style="font-size: 24px; opacity: 0.4; margin-bottom: 5px;"></i>
                                        <span style="font-size: 13px; opacity: 0.7;">Row ${rowIndex + 1} - Col ${colIndex + 1}</span>
                                    </div>`;
                                    childIndex++;
                                }
                            }
                            
                            rowsHTML += '</div>';
                            rowsHTML += '</div>';
                        });
                        
                        return `${responsiveCSS}<div id="${containerId}" style="${containerStyle}">${rowsHTML}</div>`;
                    } else {
                        // Single row layout (but grid can create multiple rows automatically)
                        let columnsHTML = '<div class="probuilder-container-row probuilder-row-0"><div class="probuilder-container-columns">';
                        
                        // Show ALL children - grid will automatically wrap to multiple rows
                        const numChildren = element.children ? element.children.length : 0;
                        const totalSlots = Math.max(columns, numChildren);
                        
                        for (let i = 0; i < totalSlots; i++) {
                            const child = element.children && element.children[i];
                            
                            if (child) {
                                // Render child element
                                const childPreview = this.generatePreview(child, depth + 1);
                                
                                columnsHTML += `
                                    <div class="probuilder-column" style="min-height: 50px; padding: 5px; position: relative; z-index: 1;">
                                        <div class="probuilder-nested-element" data-id="${child.id}" data-widget="${child.widgetType}" style="position: relative; z-index: 1;">
                                            <div class="probuilder-nested-controls" style="
                                                position: absolute;
                                                top: 0;
                                                right: 0;
                                                display: none;
                                                gap: 4px;
                                                z-index: 100;
                                                background: rgba(255, 255, 255, 0.95);
                                                padding: 4px;
                                                border-radius: 3px;
                                                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                            ">
                                                <button class="probuilder-nested-drag" title="Move" style="
                                                    background: #71717a;
                                                    border: none;
                                                    color: #ffffff;
                                                    width: 24px;
                                                    height: 24px;
                                                    border-radius: 2px;
                                                    cursor: move;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    font-size: 12px;
                                                ">
                                                    <i class="dashicons dashicons-move" style="font-size: 12px;"></i>
                                                </button>
                                                <button class="probuilder-nested-edit" title="Edit" style="
                                                    background: #92003b;
                                                    border: none;
                                                    color: #ffffff;
                                                    width: 24px;
                                                    height: 24px;
                                                    border-radius: 2px;
                                                    cursor: pointer;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    font-size: 12px;
                                                ">
                                                    <i class="dashicons dashicons-edit" style="font-size: 12px;"></i>
                                                </button>
                                                <button class="probuilder-nested-delete" title="Delete" style="
                                                    background: #dc2626;
                                                    border: none;
                                                    color: #ffffff;
                                                    width: 24px;
                                                    height: 24px;
                                                    border-radius: 2px;
                                                    cursor: pointer;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    font-size: 12px;
                                                ">
                                                    <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                                </button>
                                            </div>
                                            <div class="probuilder-nested-preview">
                                                ${childPreview}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            } else {
                                // Show empty drop zone for this column
                                columnsHTML += `<div class="probuilder-column probuilder-drop-zone" data-container-id="${element.id}" data-column-index="${i}" style="min-height: 100px; border: 1px dashed #d5dadf; padding: 20px; text-align: center; color: #a4afb7; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <i class="dashicons dashicons-plus" style="font-size: 24px; opacity: 0.4; margin-bottom: 5px;"></i>
                                    <span style="font-size: 13px; opacity: 0.7;">Click to add widget</span>
                                </div>`;
                            }
                        }
                        
                        columnsHTML += '</div></div>';
                        return `${responsiveCSS}<div id="${containerId}" style="${containerStyle}">${columnsHTML}</div>`;
                    }
                    
                case 'flexbox':
                    const flexDirection = settings.direction || 'row';
                    const flexJustify = settings.justify_content || 'flex-start';
                    const flexAlign = settings.align_items || 'stretch';
                    const flexWrap = settings.wrap || 'wrap';
                    const flexGap = settings.gap || 20;
                    const flexMinHeight = settings.min_height || 100;
                    const flexPadding = settings.padding || {top: 20, right: 20, bottom: 20, left: 20};
                    const flexMargin = settings.margin || {top: 0, right: 0, bottom: 20, left: 0};
                    const flexBgType = settings.background_type || 'color';
                    const flexBgColor = settings.background_color || '#f8f9fa';
                    const flexBgGradient = settings.background_gradient || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                    const flexBgImage = settings.background_image?.url || '';
                    const flexBorder = settings.border || {width: 0, style: 'solid', color: '#000000'};
                    const flexBorderRadius = settings.border_radius || 0;
                    const flexBoxShadow = settings.box_shadow === 'yes';
                    
                    let flexBg = '';
                    if (flexBgType === 'color') {
                        flexBg = `background-color: ${flexBgColor};`;
                    } else if (flexBgType === 'gradient') {
                        flexBg = `background: ${flexBgGradient};`;
                    } else if (flexBgType === 'image' && flexBgImage) {
                        flexBg = `background-image: url(${flexBgImage}); background-size: cover; background-position: center;`;
                    }
                    
                    const flexboxStyle = `
                        display: flex;
                        flex-direction: ${flexDirection};
                        justify-content: ${flexJustify};
                        align-items: ${flexAlign};
                        flex-wrap: ${flexWrap};
                        gap: ${flexGap}px;
                        min-height: ${flexMinHeight}px;
                        padding: ${flexPadding.top}px ${flexPadding.right}px ${flexPadding.bottom}px ${flexPadding.left}px;
                        margin: ${flexMargin.top}px ${flexMargin.right}px ${flexMargin.bottom}px ${flexMargin.left}px;
                        ${flexBg}
                        ${flexBorder.width > 0 ? `border: ${flexBorder.width}px ${flexBorder.style} ${flexBorder.color};` : ''}
                        ${flexBorderRadius > 0 ? `border-radius: ${flexBorderRadius}px;` : ''}
                        ${flexBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);' : ''}
                    `;
                    
                    let flexboxHTML = `<div style="${flexboxStyle}">`;
                    flexboxHTML += `
                        <div style="padding: 30px; background: rgba(255,255,255,0.9); border: 2px dashed #cbd5e1; border-radius: 8px; text-align: center; color: #64748b; flex: 1;">
                            <i class="dashicons dashicons-plus" style="font-size: 48px; opacity: 0.4; margin-bottom: 10px;"></i>
                            <div style="font-size: 16px; font-weight: 600;">Flexbox Container</div>
                            <div style="font-size: 13px; margin-top: 5px; opacity: 0.7;">Add widgets inside this flexible layout</div>
                            <div style="font-size: 12px; margin-top: 10px; opacity: 0.6;">
                                Direction: ${flexDirection} | Justify: ${flexJustify.replace('flex-', '').replace('-', ' ')}
                            </div>
                        </div>
                    `;
                    flexboxHTML += `</div>`;
                    
                    return flexboxHTML;
                    
                case 'icon-box':
                    return `
                        <div style="text-align: ${settings.text_align || 'center'}; padding: 20px;">
                            <i class="${settings.icon || 'fa fa-star'}" style="font-size: ${settings.icon_size || 48}px; color: ${settings.icon_color || '#93003c'}; margin-bottom: 15px;"></i>
                            <h3 style="margin: 0 0 10px 0; font-size: 20px;">${settings.title || 'Icon Box Title'}</h3>
                            <p style="margin: 0; color: #666; font-size: 14px;">${settings.description || 'Description goes here'}</p>
                        </div>
                    `;
                    
                case 'info-box':
                    const infoIconType = settings.icon_type || 'number';
                    const infoNumber = settings.number || '01';
                    const infoIcon = settings.icon || 'fa fa-check-circle';
                    const infoTitle = settings.title || 'Step One';
                    const infoDescription = settings.description || 'This is a description of the first step.';
                    const infoButtonText = settings.button_text || '';
                    const infoLayout = settings.layout || 'horizontal';
                    const infoIconStyle = settings.icon_style || 'circle';
                    const infoIconSize = settings.icon_size || 70;
                    const infoAccentColor = settings.accent_color || '#92003b';
                    const infoBgColor = settings.bg_color || '#ffffff';
                    const infoTitleColor = settings.title_color || '#333333';
                    const infoDescColor = settings.description_color || '#666666';
                    const infoBorderColor = settings.border_color || '#e5e5e5';
                    const infoBorderRadius = settings.border_radius || 8;
                    const infoBoxShadow = settings.box_shadow === 'yes';
                    
                    // Icon border radius based on style
                    let infoIconBorderRadius = '50%'; // circle
                    if (infoIconStyle === 'square') infoIconBorderRadius = '0';
                    else if (infoIconStyle === 'rounded') infoIconBorderRadius = '12px';
                    
                    const infoContainerStyle = `
                        padding: 25px;
                        background: ${infoBgColor};
                        border: 1px solid ${infoBorderColor};
                        border-radius: ${infoBorderRadius}px;
                        ${infoLayout === 'horizontal' ? 'display: flex; gap: 20px; align-items: flex-start;' : 'display: flex; flex-direction: column; align-items: center; text-align: center; gap: 20px;'}
                        ${infoBoxShadow ? 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);' : ''}
                    `;
                    
                    const infoIconContainerStyle = `
                        flex-shrink: 0;
                        width: ${infoIconSize}px;
                        height: ${infoIconSize}px;
                        background: ${infoAccentColor};
                        color: #fff;
                        border-radius: ${infoIconBorderRadius};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: ${infoIconSize * 0.4}px;
                        font-weight: bold;
                    `;
                    
                    let infoBoxHTML = `<div style="${infoContainerStyle}">`;
                    
                    // Icon/Number
                    infoBoxHTML += `<div style="${infoIconContainerStyle}">`;
                    if (infoIconType === 'icon') {
                        infoBoxHTML += `<i class="${infoIcon}"></i>`;
                    } else {
                        infoBoxHTML += infoNumber;
                    }
                    infoBoxHTML += `</div>`;
                    
                    // Content
                    const infoContentStyle = `flex: 1; ${infoLayout === 'vertical' ? 'text-align: center;' : ''}`;
                    infoBoxHTML += `<div style="${infoContentStyle}">`;
                    infoBoxHTML += `<h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: ${infoTitleColor};">${infoTitle}</h3>`;
                    
                    if (infoDescription) {
                        infoBoxHTML += `<p style="margin: 0 0 15px 0; color: ${infoDescColor}; line-height: 1.6; font-size: 14px;">${infoDescription}</p>`;
                    }
                    
                    // Button
                    if (infoButtonText) {
                        infoBoxHTML += `<a href="#" style="background: ${infoAccentColor}; color: #fff; padding: 10px 24px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: 600; font-size: 14px;">${infoButtonText}</a>`;
                    }
                    
                    infoBoxHTML += `</div>`;
                    infoBoxHTML += `</div>`;
                    
                    return infoBoxHTML;
                    
                case 'progress-bar':
                    const progTitle = settings.title || 'My Skill';
                    const progPercentage = settings.percentage || 75;
                    const progShowPercentage = settings.show_percentage !== 'no';
                    const progInnerText = settings.inner_text || '';
                    const progBarStyle = settings.bar_style || 'solid';
                    const progBarColor = settings.bar_color || '#92003b';
                    const progBarGradient = settings.bar_gradient || 'linear-gradient(90deg, #92003b 0%, #c44569 100%)';
                    const progBgColor = settings.bg_color || '#e5e7eb';
                    const progHeight = settings.height || 30;
                    const progBorderRadius = settings.border_radius || 15;
                    const progTitleColor = settings.title_color || '#333333';
                    const progPercentageColor = settings.percentage_color || '#333333';
                    const progInnerTextColor = settings.inner_text_color || '#ffffff';
                    
                    let progressHTML = `<div style="margin: 15px 0;">`;
                    
                    // Title row
                    progressHTML += `<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">`;
                    progressHTML += `<span style="font-weight: 600; font-size: 15px; color: ${progTitleColor};">${progTitle}</span>`;
                    if (progShowPercentage) {
                        progressHTML += `<span style="font-weight: 700; font-size: 15px; color: ${progPercentageColor};">${progPercentage}%</span>`;
                    }
                    progressHTML += `</div>`;
                    
                    // Progress bar
                    progressHTML += `<div style="position: relative; background: ${progBgColor}; height: ${progHeight}px; border-radius: ${progBorderRadius}px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">`;
                    
                    let barFillStyle = `height: 100%; width: ${progPercentage}%; display: flex; align-items: center; padding: 0 15px; box-sizing: border-box;`;
                    
                    if (progBarStyle === 'solid') {
                        barFillStyle += ` background: ${progBarColor};`;
                    } else if (progBarStyle === 'gradient') {
                        barFillStyle += ` background: ${progBarGradient};`;
                    } else if (progBarStyle === 'striped' || progBarStyle === 'animated') {
                        barFillStyle += ` background: ${progBarColor};`;
                        barFillStyle += ` background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);`;
                        barFillStyle += ` background-size: 20px 20px;`;
                    }
                    
                    progressHTML += `<div style="${barFillStyle}">`;
                    if (progInnerText) {
                        progressHTML += `<span style="font-size: 13px; font-weight: 600; color: ${progInnerTextColor}; white-space: nowrap;">${progInnerText}</span>`;
                    }
                    progressHTML += `</div>`;
                    
                    progressHTML += `</div>`;
                    progressHTML += `</div>`;
                    
                    return progressHTML;
                    
                case 'tabs':
                    const tabItems = Array.isArray(settings.tabs) ? settings.tabs : [
                        { tab_title: 'Tab 1', children: [] },
                        { tab_title: 'Tab 2', children: [] },
                        { tab_title: 'Tab 3', children: [] }
                    ];
                    const tabsStyle = settings.style || 'horizontal';
                    const activeTabBg = settings.active_bg_color || '#92003b';
                    const activeTabColor = settings.active_text_color || '#ffffff';
                    const inactiveTabBg = settings.inactive_bg_color || '#f3f4f6';
                    const inactiveTabColor = settings.inactive_text_color || '#333333';
                    const contentBg = settings.content_bg_color || '#ffffff';
                    const contentColor = settings.content_text_color || '#666666';
                    
                    // Ensure element has tabChildren array
                    if (!element.tabChildren) {
                        element.tabChildren = tabItems.map(() => []);
                    }
                    
                    const uniqueId = 'tabs-' + element.id;
                    let tabsHTML = `<div class="probuilder-tabs-preview" data-tabs-id="${uniqueId}" data-element-id="${element.id}" style="width: 100%;">`;
                    
                    // Tab headers
                    tabsHTML += '<div class="probuilder-tabs-header" style="display: flex; gap: 5px; border-bottom: 2px solid #e6e9ec; margin-bottom: 0;">';
                    tabItems.forEach((tab, index) => {
                        tabsHTML += `
                            <div class="probuilder-tab-header" data-tab-index="${index}" style="
                                padding: 12px 24px;
                                background: ${index === 0 ? activeTabBg : inactiveTabBg};
                                color: ${index === 0 ? activeTabColor : inactiveTabColor};
                                cursor: pointer;
                                border-radius: 3px 3px 0 0;
                                font-weight: ${index === 0 ? '600' : '400'};
                                transition: all 0.3s;
                                margin-bottom: -2px;
                                border-bottom: ${index === 0 ? `2px solid ${activeTabBg}` : 'none'};
                            " data-active-bg="${activeTabBg}" data-active-color="${activeTabColor}" data-inactive-bg="${inactiveTabBg}" data-inactive-color="${inactiveTabColor}">
                                ${tab.tab_title || tab.title || `Tab ${index + 1}`}
                            </div>
                        `;
                    });
                    tabsHTML += '</div>';
                    
                    // Tab contents (all tabs, hidden except first)
                    tabsHTML += '<div class="probuilder-tabs-contents">';
                    tabItems.forEach((tab, index) => {
                        const tabChildren = element.tabChildren[index] || [];
                        const hasChildren = tabChildren.length > 0;
                        
                        tabsHTML += `
                            <div class="probuilder-tabs-content probuilder-tab-drop-zone" data-tab-content="${index}" data-tab-index="${index}" data-element-id="${element.id}" style="
                                padding: 20px;
                                background: ${contentBg};
                                color: ${contentColor};
                                border: 1px solid #e6e9ec;
                                border-top: none;
                                border-radius: 0 3px 3px 3px;
                                min-height: 150px;
                                display: ${index === 0 ? 'block' : 'none'};
                            ">`;
                        
                        if (hasChildren) {
                            // Render nested elements
                            tabChildren.forEach(child => {
                                const childWidget = this.widgets.find(w => w.name === child.widgetType);
                                const childPreview = this.generatePreview(child, depth + 1);
                                
                                tabsHTML += `
                                    <div class="probuilder-nested-element" data-id="${child.id}" data-widget="${child.widgetType}" style="position: relative; z-index: 1; margin-bottom: 10px;">
                                        <div class="probuilder-nested-controls" style="
                                            position: absolute;
                                            top: 0;
                                            right: 0;
                                            display: none;
                                            gap: 4px;
                                            z-index: 100;
                                            background: rgba(255, 255, 255, 0.95);
                                            padding: 4px;
                                            border-radius: 3px;
                                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                        ">
                                            <button class="probuilder-nested-drag" title="Move" style="background: #71717a; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: move; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-move" style="font-size: 12px;"></i>
                                            </button>
                                            <button class="probuilder-nested-edit" title="Edit" style="background: #92003b; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-edit" style="font-size: 12px;"></i>
                                            </button>
                                            <button class="probuilder-nested-delete" title="Delete" style="background: #dc2626; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                        <div class="probuilder-nested-preview">
                                            ${childPreview}
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            // Show drop zone
                            tabsHTML += `
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 130px; color: #a4afb7; cursor: pointer;" class="probuilder-tab-empty-zone">
                                    <i class="dashicons dashicons-plus" style="font-size: 32px; opacity: 0.4; margin-bottom: 8px;"></i>
                                    <span style="font-size: 14px; opacity: 0.7;">Click to add widget or drag & drop here</span>
                                </div>
                            `;
                        }
                        
                        tabsHTML += `</div>`;
                    });
                    tabsHTML += '</div>';
                    
                    tabsHTML += '</div>';
                    
                    // Add interactive script after a short delay to ensure DOM is ready
                    setTimeout(function() {
                        const $tabsContainer = $(`[data-tabs-id="${uniqueId}"]`);
                        if ($tabsContainer.length === 0) return;
                        
                        // Tab switching
                        $tabsContainer.find('.probuilder-tab-header').off('click').on('click', function(e) {
                            e.stopPropagation();
                            const $header = $(this);
                            const tabIndex = $header.data('tab-index');
                            const activeBg = $header.data('active-bg');
                            const activeColor = $header.data('active-color');
                            const inactiveBg = $header.data('inactive-bg');
                            const inactiveColor = $header.data('inactive-color');
                            
                            // Update all tab headers
                            $tabsContainer.find('.probuilder-tab-header').each(function() {
                                const $tab = $(this);
                                const isActive = $tab.data('tab-index') === tabIndex;
                                
                                $tab.css({
                                    'background': isActive ? activeBg : inactiveBg,
                                    'color': isActive ? activeColor : inactiveColor,
                                    'font-weight': isActive ? '600' : '400',
                                    'border-bottom': isActive ? `2px solid ${activeBg}` : 'none'
                                });
                            });
                            
                            // Show active content, hide others
                            $tabsContainer.find('.probuilder-tabs-content').each(function() {
                                const $content = $(this);
                                const contentIndex = $content.data('tab-content');
                                $content.css('display', contentIndex === tabIndex ? 'block' : 'none');
                            });
                        });
                        
                        // Make tab content droppable
                        ProBuilder.makeTabsDroppable(element);
                        
                        // Attach handlers to nested elements
                        ProBuilder.attachTabNestedHandlers(element, $tabsContainer);
                        
                        // Click empty zone to add widget
                        $tabsContainer.find('.probuilder-tab-empty-zone').off('click').on('click', function(e) {
                            e.stopPropagation();
                            const tabIndex = $(this).closest('.probuilder-tabs-content').data('tab-index');
                            console.log('Tab empty zone clicked:', tabIndex);
                            ProBuilder.showWidgetPickerForTab(element.id, tabIndex);
                        });
                    }, 100);
                    
                    return tabsHTML;
                    
                case 'carousel':
                    const carouselImages = Array.isArray(settings.images) ? settings.images : [
                        { image_url: 'https://via.placeholder.com/1200x600/92003b/ffffff?text=Slide+1', caption: 'First Slide' },
                        { image_url: 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Slide+2', caption: 'Second Slide' },
                        { image_url: 'https://via.placeholder.com/1200x600/4facfe/ffffff?text=Slide+3', caption: 'Third Slide' }
                    ];
                    const carouselHeight = settings.height || 400;
                    const showArrows = settings.show_arrows !== 'no';
                    const showDots = settings.show_dots !== 'no';
                    const arrowsColor = settings.arrows_color || '#ffffff';
                    const dotsColor = settings.dots_color || '#92003b';
                    const autoplay = settings.autoplay !== 'no';
                    const autoplaySpeed = settings.autoplay_speed || 3000;
                    
                    const carouselId = 'carousel-' + element.id;
                    let carouselHTML = `<div class="probuilder-carousel-preview" data-carousel-id="${carouselId}" data-autoplay="${autoplay}" data-speed="${autoplaySpeed}" style="position: relative; overflow: hidden; height: ${carouselHeight}px; background: #f8f9fa; border-radius: 4px;">`;
                    
                    // Slides container
                    carouselHTML += '<div class="probuilder-carousel-slides" style="display: flex; height: 100%; transition: transform 0.5s ease; position: relative;">';
                    carouselImages.forEach((img, index) => {
                        carouselHTML += `
                            <div class="probuilder-carousel-slide" data-slide="${index}" style="
                                flex: 0 0 100%;
                                width: 100%;
                                height: 100%;
                                position: relative;
                                display: ${index === 0 ? 'flex' : 'none'};
                                align-items: center;
                                justify-content: center;
                                background: #000;
                            ">
                                <img src="${img.image_url || 'https://via.placeholder.com/1200x600'}" style="
                                    max-width: 100%;
                                    max-height: 100%;
                                    object-fit: contain;
                                    display: block;
                                " alt="${img.caption || 'Slide ' + (index + 1)}">
                                ${img.caption ? `
                                    <div style="
                                        position: absolute;
                                        bottom: 20px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        background: rgba(0,0,0,0.7);
                                        color: #ffffff;
                                        padding: 10px 20px;
                                        border-radius: 4px;
                                        font-size: 16px;
                                    ">${img.caption}</div>
                                ` : ''}
                            </div>
                        `;
                    });
                    carouselHTML += '</div>';
                    
                    // Navigation arrows
                    if (showArrows) {
                        carouselHTML += `
                            <button class="probuilder-carousel-prev" style="
                                position: absolute;
                                top: 50%;
                                left: 15px;
                                transform: translateY(-50%);
                                background: rgba(0,0,0,0.6);
                                color: ${arrowsColor};
                                border: none;
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                z-index: 10;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                ‹
                            </button>
                            <button class="probuilder-carousel-next" style="
                                position: absolute;
                                top: 50%;
                                right: 15px;
                                transform: translateY(-50%);
                                background: rgba(0,0,0,0.6);
                                color: ${arrowsColor};
                                border: none;
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                z-index: 10;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                ›
                            </button>
                        `;
                    }
                    
                    // Dots indicator
                    if (showDots) {
                        carouselHTML += '<div class="probuilder-carousel-dots" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10;">';
                        carouselImages.forEach((img, index) => {
                            carouselHTML += `
                                <button class="probuilder-carousel-dot ${index === 0 ? 'active' : ''}" data-slide="${index}" style="
                                    width: ${index === 0 ? '24px' : '12px'};
                                    height: 12px;
                                    border-radius: 6px;
                                    border: 2px solid ${dotsColor};
                                    background: ${index === 0 ? dotsColor : 'transparent'};
                                    cursor: pointer;
                                    transition: all 0.3s;
                                    padding: 0;
                                "></button>
                            `;
                        });
                        carouselHTML += '</div>';
                    }
                    
                    carouselHTML += '</div>';
                    
                    // Initialize carousel after rendering
                    const self = this;
                    setTimeout(function() {
                        self.initializeCarousel(element, null);
                    }, 100);
                    
                    return carouselHTML;
                    
                case 'alert':
                    const alertType = settings.alert_type || 'info';
                    const alertTitle = settings.title || 'Information';
                    const alertMessage = settings.message || 'This is an alert message.';
                    const isDismissible = settings.dismissible !== 'no';
                    const showIcon = settings.show_icon !== 'no';
                    const accentColor = settings.accent_color || '#92003b';
                    const quoteColor = settings.quote_color || '#333333';
                    const quoteSize = settings.quote_size || 20;
                    const bgColor = settings.background_color || 'transparent';
                    const alertPadding = settings.padding || {top: 20, right: 30, bottom: 20, left: 30};
                    const alertMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    // Alert type color schemes
                    const alertColors = {
                        'info': { bg: '#e3f2fd', border: '#2196f3', text: '#0d47a1', icon: 'fa-circle-info' },
                        'success': { bg: '#e8f5e9', border: '#4caf50', text: '#1b5e20', icon: 'fa-circle-check' },
                        'warning': { bg: '#fff3e0', border: '#ff9800', text: '#e65100', icon: 'fa-triangle-exclamation' },
                        'error': { bg: '#ffebee', border: '#f44336', text: '#b71c1c', icon: 'fa-circle-xmark' }
                    };
                    
                    const colorScheme = alertColors[alertType] || alertColors['info'];
                    const finalBg = bgColor !== 'transparent' ? bgColor : colorScheme.bg;
                    const finalBorder = accentColor || colorScheme.border;
                    const finalText = quoteColor || colorScheme.text;
                    
                    let alertHTML = `
                        <div class="probuilder-alert probuilder-alert-${alertType}" style="
                            background: ${finalBg};
                            border-left: 4px solid ${finalBorder};
                            color: ${finalText};
                            padding: ${alertPadding.top}px ${alertPadding.right}px ${alertPadding.bottom}px ${alertPadding.left}px;
                            margin: ${alertMargin.top}px ${alertMargin.right}px ${alertMargin.bottom}px ${alertMargin.left}px;
                            border-radius: 4px;
                            position: relative;
                        ">
                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                    `;
                    
                    // Icon
                    if (showIcon) {
                        alertHTML += `<div style="font-size: 24px; color: ${finalBorder};">
                            <i class="fa ${colorScheme.icon}"></i>
                        </div>`;
                    }
                    
                    // Content
                    alertHTML += `
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; font-size: ${quoteSize}px; font-weight: 600; color: ${finalText};">
                                ${alertTitle}
                            </h4>
                    `;
                    
                    if (alertMessage) {
                        alertHTML += `<p style="margin: 0; font-size: 14px; line-height: 1.6; color: ${finalText}; opacity: 0.9;">
                            ${alertMessage}
                        </p>`;
                    }
                    
                    alertHTML += '</div>';
                    
                    // Close button
                    if (isDismissible) {
                        alertHTML += `
                            <button class="probuilder-alert-close" style="
                                background: transparent;
                                border: none;
                                color: ${finalText};
                                cursor: pointer;
                                font-size: 20px;
                                padding: 0;
                                opacity: 0.6;
                                transition: opacity 0.2s;
                            " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">
                                <i class="fa fa-times"></i>
                            </button>
                        `;
                    }
                    
                    alertHTML += '</div></div>';
                    
                    return alertHTML;
                    
                case 'image-box':
                    const imageBoxUrl = settings.image_url || 'https://via.placeholder.com/600x400';
                    const imageBoxPosition = settings.image_position || 'top';
                    const imageBoxTitle = settings.title || 'Beautiful Image Box';
                    const imageBoxDesc = settings.description || 'Add a stunning image with text';
                    const imageBoxShowBtn = settings.show_button !== 'no';
                    const imageBoxBtnText = settings.button_text || 'Learn More';
                    const imageBoxAlign = settings.text_align || 'left';
                    const imageBoxTitleColor = settings.title_color || '#333333';
                    const imageBoxTitleSize = settings.title_size || 24;
                    const imageBoxDescColor = settings.description_color || '#666666';
                    const imageBoxBgColor = settings.bg_color || '#ffffff';
                    const imageBoxRadius = settings.border_radius || 8;
                    const imageBoxMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    const imageBoxPadding = settings.padding || {top: 25, right: 25, bottom: 25, left: 25};
                    
                    let imageBoxHTML = `
                        <div class="probuilder-image-box-preview" style="
                            background: ${imageBoxBgColor};
                            border-radius: ${imageBoxRadius}px;
                            overflow: hidden;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            margin: ${imageBoxMargin.top}px ${imageBoxMargin.right}px ${imageBoxMargin.bottom}px ${imageBoxMargin.left}px;
                            ${imageBoxPosition !== 'top' ? 'display: flex; align-items: center;' : ''}
                            ${imageBoxPosition === 'right' ? 'flex-direction: row-reverse;' : ''}
                        ">
                            <div class="image-box-image" style="margin: 0; line-height: 0; ${imageBoxPosition !== 'top' ? 'flex-shrink: 0; width: 50%;' : ''}">
                                <img src="${imageBoxUrl}" alt="${imageBoxTitle}" style="width: 100%; height: auto; display: block;">
                            </div>
                            <div class="image-box-content" style="
                                padding: ${imageBoxPadding.top}px ${imageBoxPadding.right}px ${imageBoxPadding.bottom}px ${imageBoxPadding.left}px;
                                text-align: ${imageBoxAlign};
                                ${imageBoxPosition !== 'top' ? 'flex: 1;' : ''}
                            ">
                                <h3 style="margin: 0 0 12px 0; font-size: ${imageBoxTitleSize}px; color: ${imageBoxTitleColor}; font-weight: 600;">
                                    ${imageBoxTitle}
                                </h3>
                                ${imageBoxDesc ? `<p style="margin: 0 0 20px 0; font-size: 16px; color: ${imageBoxDescColor}; line-height: 1.6;">
                                    ${imageBoxDesc}
                                </p>` : ''}
                                ${imageBoxShowBtn ? `<a href="#" style="display: inline-block; background: #92003b; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: 600;">
                                    ${imageBoxBtnText}
                                </a>` : ''}
                            </div>
                        </div>
                    `;
                    return imageBoxHTML;
                    
                case 'icon-list':
                    const iconListItems = settings.items || [
                        {text: 'Professional Design', icon: 'fa fa-check-circle'},
                        {text: 'Fast Performance', icon: 'fa fa-check-circle'},
                        {text: 'Responsive Layout', icon: 'fa fa-check-circle'}
                    ];
                    const iconListLayout = settings.layout || 'vertical';
                    const iconListColumns = settings.columns || '2';
                    const iconListIconColor = settings.icon_color || '#92003b';
                    const iconListIconSize = settings.icon_size || 20;
                    const iconListTextColor = settings.text_color || '#333333';
                    const iconListTextSize = settings.text_size || 16;
                    const iconListMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    let iconListContainerStyle = `list-style: none; margin: ${iconListMargin.top}px ${iconListMargin.right}px ${iconListMargin.bottom}px ${iconListMargin.left}px; padding: 0; `;
                    if (iconListLayout === 'grid') {
                        iconListContainerStyle += `display: grid; grid-template-columns: repeat(${iconListColumns}, 1fr); gap: 15px;`;
                    } else if (iconListLayout === 'horizontal') {
                        iconListContainerStyle += 'display: flex; flex-wrap: wrap; gap: 15px 30px;';
                    } else {
                        iconListContainerStyle += 'display: flex; flex-direction: column; gap: 15px;';
                    }
                    
                    let iconListHTML = `<ul class="probuilder-icon-list-preview" style="${iconListContainerStyle}">`;
                    iconListItems.forEach(item => {
                        iconListHTML += `
                            <li style="display: flex; align-items: center;">
                                <span style="color: ${iconListIconColor}; margin-right: 12px; font-size: ${iconListIconSize}px;">
                                    <i class="${item.icon}"></i>
                                </span>
                                <span style="color: ${iconListTextColor}; font-size: ${iconListTextSize}px;">
                                    ${item.text}
                                </span>
                            </li>
                        `;
                    });
                    iconListHTML += '</ul>';
                    return iconListHTML;
                    
                case 'feature-list':
                    const featureListItems = settings.items || [
                        {title: '24/7 Support', description: 'Get help whenever you need it', icon: 'fa fa-headset'},
                        {title: 'Free Updates', description: 'Always get the latest features', icon: 'fa fa-rocket'},
                        {title: 'Money Back', description: '30-day refund policy', icon: 'fa fa-shield-halved'}
                    ];
                    const featureListLayout = settings.layout || 'grid';
                    const featureListColumns = settings.columns || '3';
                    const featureListIconColor = settings.icon_color || '#92003b';
                    const featureListIconSize = settings.icon_size || 40;
                    const featureListIconBg = settings.icon_bg_color || '#f8f9fa';
                    const featureListTitleColor = settings.title_color || '#333333';
                    const featureListDescColor = settings.description_color || '#666666';
                    const featureListShowCard = settings.show_card !== 'no';
                    const featureListCardBg = settings.card_bg_color || '#ffffff';
                    const featureListMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    const featureListPadding = settings.padding || {top: 25, right: 25, bottom: 25, left: 25};
                    
                    let featureListContainerStyle = `margin: ${featureListMargin.top}px ${featureListMargin.right}px ${featureListMargin.bottom}px ${featureListMargin.left}px; `;
                    if (featureListLayout === 'grid') {
                        featureListContainerStyle += `display: grid; grid-template-columns: repeat(${featureListColumns}, 1fr); gap: 20px;`;
                    } else {
                        featureListContainerStyle += 'display: flex; flex-direction: column; gap: 20px;';
                    }
                    
                    let featureListHTML = `<div class="probuilder-feature-list-preview" style="${featureListContainerStyle}">`;
                    featureListItems.forEach(item => {
                        featureListHTML += `
                            <div class="feature-item" style="
                                ${featureListShowCard ? `background: ${featureListCardBg}; border-radius: 8px; padding: ${featureListPadding.top}px ${featureListPadding.right}px ${featureListPadding.bottom}px ${featureListPadding.left}px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);` : ''}
                                display: flex; flex-direction: column; align-items: flex-start;
                            ">
                                <div style="
                                    display: flex; align-items: center; justify-content: center;
                                    width: ${featureListIconSize + 30}px; height: ${featureListIconSize + 30}px;
                                    background: ${featureListIconBg}; border-radius: 50%;
                                    color: ${featureListIconColor}; font-size: ${featureListIconSize}px;
                                    margin-bottom: 15px;
                                ">
                                    <i class="${item.icon}"></i>
                                </div>
                                <h4 style="margin: 0 0 8px 0; font-size: 18px; color: ${featureListTitleColor}; font-weight: 600;">
                                    ${item.title}
                                </h4>
                                ${item.description ? `<p style="margin: 0; font-size: 14px; color: ${featureListDescColor}; line-height: 1.6;">
                                    ${item.description}
                                </p>` : ''}
                            </div>
                        `;
                    });
                    featureListHTML += '</div>';
                    return featureListHTML;
                    
                case 'star-rating':
                    const starRating = parseFloat(settings.rating) || 5;
                    const starMaxStars = parseInt(settings.max_stars) || 5;
                    const starShowTitle = settings.show_title !== 'no';
                    const starTitle = settings.title || 'Excellent Service!';
                    const starShowNumber = settings.show_number !== 'no';
                    const starFilledColor = settings.filled_color || '#ffa500';
                    const starEmptyColor = settings.empty_color || '#d4d4d4';
                    const starSize = settings.star_size || 24;
                    const starTitleColor = settings.title_color || '#333333';
                    const starAlign = settings.align || 'left';
                    const starMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    let starHTML = `
                        <div class="probuilder-star-rating-preview" style="
                            text-align: ${starAlign};
                            margin: ${starMargin.top}px ${starMargin.right}px ${starMargin.bottom}px ${starMargin.left}px;
                        ">
                            ${starShowTitle ? `<div style="font-size: 18px; font-weight: 600; margin-bottom: 10px; color: ${starTitleColor};">
                                ${starTitle}
                            </div>` : ''}
                            <div style="font-size: ${starSize}px; margin-bottom: 8px;">
                    `;
                    
                    for (let i = 1; i <= starMaxStars; i++) {
                        let starClass = 'fa fa-star';
                        let starColor = starEmptyColor;
                        
                        if (i <= Math.floor(starRating)) {
                            starColor = starFilledColor;
                        } else if (i - 0.5 <= starRating) {
                            starClass = 'fa fa-star-half-stroke';
                            starColor = starFilledColor;
                        }
                        
                        starHTML += `<i class="${starClass}" style="color: ${starColor}; margin-right: 3px;"></i>`;
                    }
                    
                    starHTML += `</div>`;
                    
                    if (starShowNumber) {
                        starHTML += `<div style="font-size: 14px; color: #666;">
                            ${starRating.toFixed(1)} / ${starMaxStars}
                        </div>`;
                    }
                    
                    starHTML += '</div>';
                    return starHTML;
                    
                case 'flip-box':
                    const flipFrontIconType = settings.front_icon_type || 'icon';
                    const flipFrontIcon = settings.front_icon || 'fa fa-star';
                    const flipFrontImage = settings.front_image || '';
                    const flipFrontTitle = settings.front_title || 'Amazing Feature';
                    const flipFrontDesc = settings.front_description || 'Hover to see more';
                    
                    const flipBackIconType = settings.back_icon_type || 'none';
                    const flipBackIcon = settings.back_icon || 'fa fa-check-circle';
                    const flipBackImage = settings.back_image || '';
                    const flipBackTitle = settings.back_title || 'Discover More';
                    const flipBackDesc = settings.back_description || 'This is an amazing feature';
                    const flipShowButton = settings.show_button !== 'no';
                    const flipButtonText = settings.button_text || 'Learn More';
                    
                    const flipEffect = settings.flip_effect || 'flip-horizontal';
                    const flipHeight = settings.box_height || 300;
                    
                    const flipFrontBgType = settings.front_bg_type || 'color';
                    const flipFrontBgColor = settings.front_bg_color || '#92003b';
                    const flipFrontBgGrad = settings.front_bg_gradient || '';
                    const flipFrontTextColor = settings.front_text_color || '#ffffff';
                    const flipFrontIconColor = settings.front_icon_color || '#ffffff';
                    const flipFrontIconSize = settings.front_icon_size || 60;
                    
                    const flipBackBgType = settings.back_bg_type || 'color';
                    const flipBackBgColor = settings.back_bg_color || '#333333';
                    const flipBackBgGrad = settings.back_bg_gradient || '';
                    const flipBackTextColor = settings.back_text_color || '#ffffff';
                    const flipButtonBg = settings.back_button_bg || '#ffffff';
                    const flipButtonColor = settings.back_button_color || '#333333';
                    
                    const flipBorderRadius = settings.border_radius || 8;
                    const flipMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    const flipPadding = settings.padding || {top: 30, right: 30, bottom: 30, left: 30};
                    
                    // Front background
                    let flipFrontBg = '';
                    if (flipFrontBgType === 'gradient' && flipFrontBgGrad) {
                        flipFrontBg = `background: ${flipFrontBgGrad};`;
                    } else {
                        flipFrontBg = `background: ${flipFrontBgColor};`;
                    }
                    
                    // Back background
                    let flipBackBg = '';
                    if (flipBackBgType === 'gradient' && flipBackBgGrad) {
                        flipBackBg = `background: ${flipBackBgGrad};`;
                    } else {
                        flipBackBg = `background: ${flipBackBgColor};`;
                    }
                    
                    const flipBoxId = 'flipbox-' + element.id;
                    
                    let flipBoxHTML = `
                        <div class="probuilder-flip-box-preview" id="${flipBoxId}" data-effect="${flipEffect}" style="
                            perspective: 1000px;
                            height: ${flipHeight}px;
                            margin: ${flipMargin.top}px ${flipMargin.right}px ${flipMargin.bottom}px ${flipMargin.left}px;
                            cursor: pointer;
                        ">
                            <div class="flip-box-inner" style="
                                position: relative;
                                width: 100%;
                                height: 100%;
                                transition: transform 0.6s;
                                transform-style: preserve-3d;
                            ">
                                <div class="flip-box-front" style="
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    backface-visibility: hidden;
                                    ${flipFrontBg}
                                    color: ${flipFrontTextColor};
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: ${flipBorderRadius}px;
                                    padding: ${flipPadding.top}px ${flipPadding.right}px ${flipPadding.bottom}px ${flipPadding.left}px;
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                ">
                                    ${flipFrontIconType === 'icon' && flipFrontIcon ? `
                                        <i class="${flipFrontIcon}" style="font-size: ${flipFrontIconSize}px; color: ${flipFrontIconColor}; margin-bottom: 20px;"></i>
                                    ` : ''}
                                    ${flipFrontIconType === 'image' && flipFrontImage ? `
                                        <img src="${flipFrontImage}" alt="${flipFrontTitle}" style="width: ${flipFrontIconSize}px; height: ${flipFrontIconSize}px; margin-bottom: 20px; border-radius: 50%;">
                                    ` : ''}
                                    <h3 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 600; color: ${flipFrontTextColor};">
                                        ${flipFrontTitle}
                                    </h3>
                                    ${flipFrontDesc ? `<p style="margin: 0; font-size: 14px; opacity: 0.9; color: ${flipFrontTextColor};">
                                        ${flipFrontDesc}
                                    </p>` : ''}
                                </div>
                                
                                <div class="flip-box-back" style="
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    backface-visibility: hidden;
                                    ${flipBackBg}
                                    color: ${flipBackTextColor};
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: ${flipBorderRadius}px;
                                    padding: ${flipPadding.top}px ${flipPadding.right}px ${flipPadding.bottom}px ${flipPadding.left}px;
                                    transform: rotateY(180deg);
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                ">
                                    ${flipBackIconType === 'icon' && flipBackIcon ? `
                                        <i class="${flipBackIcon}" style="font-size: 40px; margin-bottom: 15px; color: ${flipBackTextColor};"></i>
                                    ` : ''}
                                    ${flipBackIconType === 'image' && flipBackImage ? `
                                        <img src="${flipBackImage}" alt="${flipBackTitle}" style="width: 60px; height: 60px; margin-bottom: 15px; border-radius: 50%;">
                                    ` : ''}
                                    <h3 style="margin: 0 0 15px 0; font-size: 22px; font-weight: 600; color: ${flipBackTextColor};">
                                        ${flipBackTitle}
                                    </h3>
                                    ${flipBackDesc ? `<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; text-align: center; color: ${flipBackTextColor};">
                                        ${flipBackDesc}
                                    </p>` : ''}
                                    ${flipShowButton ? `<a href="#" style="background: ${flipButtonBg}; color: ${flipButtonColor}; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: 600;">
                                        ${flipButtonText}
                                    </a>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add hover effect
                    setTimeout(function() {
                        const $flipBox = jQuery('#' + flipBoxId);
                        $flipBox.on('mouseenter', function() {
                            const effect = jQuery(this).data('effect');
                            const $inner = jQuery(this).find('.flip-box-inner');
                            
                            switch(effect) {
                                case 'flip-horizontal':
                                    $inner.css('transform', 'rotateY(180deg)');
                                    break;
                                case 'flip-vertical':
                                    $inner.css('transform', 'rotateX(180deg)');
                                    break;
                                case 'zoom-in':
                                    $inner.find('.flip-box-front').css({'opacity': '0', 'transform': 'scale(1.2)'});
                                    $inner.find('.flip-box-back').css({'opacity': '1', 'transform': 'scale(1) rotateY(0deg)'});
                                    break;
                                case 'zoom-out':
                                    $inner.find('.flip-box-front').css({'opacity': '0', 'transform': 'scale(0.8)'});
                                    $inner.find('.flip-box-back').css({'opacity': '1', 'transform': 'scale(1) rotateY(0deg)'});
                                    break;
                                case 'fade':
                                    $inner.find('.flip-box-front').css('opacity', '0');
                                    $inner.find('.flip-box-back').css({'opacity': '1', 'transform': 'rotateY(0deg)'});
                                    break;
                            }
                        }).on('mouseleave', function() {
                            const $inner = jQuery(this).find('.flip-box-inner');
                            $inner.css('transform', '');
                            $inner.find('.flip-box-front').css({'opacity': '1', 'transform': ''});
                            $inner.find('.flip-box-back').css({'opacity': '0', 'transform': 'rotateY(180deg)'});
                        });
                    }, 100);
                    
                    return flipBoxHTML;
                    
                case 'gallery':
                    const galleryImages = settings.images || [
                        {image_url: 'https://via.placeholder.com/600x400/FF6B6B/ffffff?text=1', caption: 'Beautiful Image 1'},
                        {image_url: 'https://via.placeholder.com/600x400/4ECDC4/ffffff?text=2', caption: 'Beautiful Image 2'},
                        {image_url: 'https://via.placeholder.com/600x400/45B7D1/ffffff?text=3', caption: 'Beautiful Image 3'},
                        {image_url: 'https://via.placeholder.com/600x400/96CEB4/ffffff?text=4', caption: 'Beautiful Image 4'},
                        {image_url: 'https://via.placeholder.com/600x400/FFEAA7/ffffff?text=5', caption: 'Beautiful Image 5'},
                        {image_url: 'https://via.placeholder.com/600x400/6C5CE7/ffffff?text=6', caption: 'Beautiful Image 6'}
                    ];
                    const galleryColumns = settings.columns || '3';
                    const galleryGap = settings.gap || 15;
                    const galleryRadius = settings.border_radius || 8;
                    const galleryMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    const galleryShowCaption = settings.show_caption !== 'no';
                    
                    let galleryHTML = `<div class="probuilder-gallery-preview" style="
                        display: grid;
                        grid-template-columns: repeat(${galleryColumns}, 1fr);
                        gap: ${galleryGap}px;
                        margin: ${galleryMargin.top}px ${galleryMargin.right}px ${galleryMargin.bottom}px ${galleryMargin.left}px;
                    ">`;
                    
                    galleryImages.forEach((img, idx) => {
                        galleryHTML += `
                            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: ${galleryRadius}px; line-height: 0; cursor: pointer;">
                                <img src="${img.image_url}" alt="${img.caption || ''}" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;">
                                ${galleryShowCaption && img.caption ? `
                                    <div class="gallery-caption" style="
                                        position: absolute;
                                        bottom: 0;
                                        left: 0;
                                        right: 0;
                                        background: rgba(0,0,0,0.7);
                                        color: #ffffff;
                                        padding: 10px 15px;
                                        font-size: 14px;
                                        transform: translateY(100%);
                                        transition: transform 0.3s ease;
                                    ">${img.caption}</div>
                                ` : ''}
                            </div>
                        `;
                    });
                    
                    galleryHTML += '</div>';
                    
                    setTimeout(function() {
                        jQuery('.probuilder-gallery-preview .gallery-item').on('mouseenter', function() {
                            jQuery(this).find('img').css('transform', 'scale(1.1)');
                            jQuery(this).find('.gallery-caption').css('transform', 'translateY(0)');
                        }).on('mouseleave', function() {
                            jQuery(this).find('img').css('transform', 'scale(1)');
                            jQuery(this).find('.gallery-caption').css('transform', 'translateY(100%)');
                        });
                    }, 100);
                    
                    return galleryHTML;
                    
                case 'logo-grid':
                    const logoGridLogos = settings.logos || [
                        {logo_url: 'https://logo.clearbit.com/google.com', name: 'Google', link: 'https://google.com'},
                        {logo_url: 'https://logo.clearbit.com/microsoft.com', name: 'Microsoft', link: 'https://microsoft.com'},
                        {logo_url: 'https://logo.clearbit.com/apple.com', name: 'Apple', link: 'https://apple.com'},
                        {logo_url: 'https://logo.clearbit.com/amazon.com', name: 'Amazon', link: 'https://amazon.com'},
                        {logo_url: 'https://logo.clearbit.com/facebook.com', name: 'Meta', link: 'https://facebook.com'},
                        {logo_url: 'https://logo.clearbit.com/netflix.com', name: 'Netflix', link: 'https://netflix.com'}
                    ];
                    const logoColumns = settings.columns || '4';
                    const logoGap = settings.gap || 30;
                    const logoGrayscale = settings.grayscale !== 'no';
                    const logoOpacity = settings.opacity || 0.7;
                    const logoBg = settings.bg_color || 'transparent';
                    const logoPadding = settings.padding || 20;
                    const logoShowBorder = settings.border === 'yes';
                    const logoBorderColor = settings.border_color || '#e5e5e5';
                    const logoMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    let logoGridHTML = `<div class="probuilder-logo-grid-preview" style="
                        display: grid;
                        grid-template-columns: repeat(${logoColumns}, 1fr);
                        gap: ${logoGap}px;
                        margin: ${logoMargin.top}px ${logoMargin.right}px ${logoMargin.bottom}px ${logoMargin.left}px;
                    ">`;
                    
                    logoGridLogos.forEach(logo => {
                        logoGridHTML += `
                            <div class="logo-item" style="
                                text-align: center;
                                padding: ${logoPadding}px;
                                background: ${logoBg};
                                ${logoShowBorder ? `border: 1px solid ${logoBorderColor}; border-radius: 8px;` : ''}
                                transition: all 0.3s ease;
                            ">
                                <img src="${logo.logo_url}" alt="${logo.name}" title="${logo.name}" style="
                                    max-width: 100%;
                                    height: auto;
                                    display: block;
                                    margin: 0 auto;
                                    ${logoGrayscale ? 'filter: grayscale(100%);' : ''}
                                    opacity: ${logoOpacity};
                                    transition: all 0.3s ease;
                                ">
                            </div>
                        `;
                    });
                    
                    logoGridHTML += '</div>';
                    
                    setTimeout(function() {
                        jQuery('.probuilder-logo-grid-preview .logo-item').on('mouseenter', function() {
                            jQuery(this).find('img').css({
                                'filter': 'grayscale(0%)',
                                'opacity': '1',
                                'transform': 'scale(1.05)'
                            });
                        }).on('mouseleave', function() {
                            jQuery(this).find('img').css({
                                'filter': logoGrayscale ? 'grayscale(100%)' : 'grayscale(0%)',
                                'opacity': logoOpacity,
                                'transform': 'scale(1)'
                            });
                        });
                    }, 100);
                    
                    return logoGridHTML;
                    
                case 'map':
                    const mapAddress = settings.address || 'Times Square, New York, NY, USA';
                    const mapLat = settings.latitude || '';
                    const mapLon = settings.longitude || '';
                    const mapZoom = settings.zoom || 12;
                    const mapHeight = settings.height || 400;
                    const mapRadius = settings.border_radius || 8;
                    const mapMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    // Smart detection: Use address OR coordinates
                    let googleMapsUrl;
                    const hasCoords = mapLat && mapLon;
                    
                    if (hasCoords) {
                        // Use coordinates for precision
                        googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(mapLat + ',' + mapLon)}&t=m&z=${mapZoom}&output=embed&iwloc=near`;
                    } else {
                        // Use address - Google Maps will geocode it
                        googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(mapAddress)}&t=m&z=${mapZoom}&output=embed&iwloc=near`;
                    }
                    
                    let mapHTML = `
                        <div class="probuilder-map-preview" style="
                            width: 100%;
                            height: ${mapHeight}px;
                            border-radius: ${mapRadius}px;
                            overflow: hidden;
                            margin: ${mapMargin.top}px ${mapMargin.right}px ${mapMargin.bottom}px ${mapMargin.left}px;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            position: relative;
                            background: #e5e7eb;
                        ">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0; display: block; pointer-events: none;" 
                                src="${googleMapsUrl}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                        <div style="
                            font-size: 12px; 
                            color: #64748b; 
                            margin-top: 8px; 
                            display: flex; 
                            align-items: center; 
                            gap: 5px;
                            margin-left: ${mapMargin.left}px;
                        ">
                            <i class="fa fa-map-marker" style="color: #92003b;"></i>
                            <span>${mapAddress}</span>
                        </div>
                    `;
                    
                    return mapHTML;
                    
                case 'toggle':
                    const toggleItems = settings.items || [
                        {title: 'What are the system requirements?', content: 'Our system works on all modern browsers', default_open: 'no'},
                        {title: 'How do I get started?', content: 'Simply sign up and follow our guide', default_open: 'no'}
                    ];
                    const toggleStyle = settings.toggle_style || 'switch';
                    const toggleTitleBg = settings.title_bg_color || '#f8f9fa';
                    const toggleTitleColor = settings.title_text_color || '#333333';
                    const toggleIconColor = settings.toggle_icon_color || '#92003b';
                    const toggleTitleSize = settings.title_font_size || 16;
                    const toggleContentBg = settings.content_bg_color || '#f9f9f9';
                    const toggleContentColor = settings.content_text_color || '#666666';
                    const toggleRadius = settings.border_radius || 4;
                    const toggleSpacing = settings.item_spacing || 10;
                    const toggleMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    const toggleId = 'toggle-' + element.id;
                    
                    let toggleHTML = `<div class="probuilder-toggle-preview" data-toggle-id="${toggleId}" style="
                        margin: ${toggleMargin.top}px ${toggleMargin.right}px ${toggleMargin.bottom}px ${toggleMargin.left}px;
                    ">`;
                    
                    toggleItems.forEach((item, index) => {
                        const isOpen = item.default_open === 'yes';
                        
                        let titleExtraStyle = '';
                        if (toggleStyle === 'bordered') {
                            titleExtraStyle = `border: 2px solid ${toggleIconColor};`;
                        } else if (toggleStyle === 'simple') {
                            titleExtraStyle = `border-bottom: 2px solid #e5e5e5; background: transparent; border-radius: 0;`;
                        }
                        
                        toggleHTML += `
                            <div class="toggle-item" data-index="${index}" style="margin-bottom: ${toggleSpacing}px;">
                                <div class="toggle-title" style="
                                    background: ${toggleTitleBg};
                                    color: ${toggleTitleColor};
                                    font-size: ${toggleTitleSize}px;
                                    padding: 15px 20px;
                                    cursor: pointer;
                                    border-radius: ${toggleRadius}px;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    font-weight: 600;
                                    transition: all 0.3s ease;
                                    ${titleExtraStyle}
                                ">
                                    <span style="flex: 1;">${item.title}</span>
                                    
                                    ${toggleStyle === 'switch' ? `
                                        <span class="switch-toggle" style="
                                            position: relative;
                                            width: 44px;
                                            height: 24px;
                                            background: ${isOpen ? toggleIconColor : '#cbd5e1'};
                                            border-radius: 12px;
                                            transition: background 0.3s;
                                            display: inline-block;
                                            margin-left: 15px;
                                        ">
                                            <span class="switch-thumb" style="
                                                position: absolute;
                                                top: 2px;
                                                left: ${isOpen ? '22px' : '2px'};
                                                width: 20px;
                                                height: 20px;
                                                background: #ffffff;
                                                border-radius: 10px;
                                                transition: left 0.3s;
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                            "></span>
                                        </span>
                                    ` : `
                                        <span class="toggle-icon" style="transition: transform 0.3s; font-size: 18px; color: ${toggleIconColor}; transform: rotate(${isOpen ? '180deg' : '0deg'});">▼</span>
                                    `}
                                </div>
                                <div class="toggle-content" style="
                                    display: ${isOpen ? 'block' : 'none'};
                                    background: ${toggleContentBg};
                                    color: ${toggleContentColor};
                                    padding: ${isOpen ? '15px 20px' : '0 20px'};
                                    margin-top: 5px;
                                    border-radius: ${toggleRadius}px;
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    max-height: ${isOpen ? '1000px' : '0'};
                                ">
                                    <p style="margin: 0; line-height: 1.6;">${item.content}</p>
                                </div>
                            </div>
                        `;
                    });
                    
                    toggleHTML += '</div>';
                    
                    setTimeout(function() {
                        const $toggleContainer = jQuery(`[data-toggle-id="${toggleId}"]`);
                        $toggleContainer.find('.toggle-title').off('click').on('click', function(e) {
                            e.stopPropagation();
                            const $title = jQuery(this);
                            const $content = $title.next('.toggle-content');
                            const $icon = $title.find('.toggle-icon');
                            const $switchToggle = $title.find('.switch-toggle');
                            const $switchThumb = $switchToggle.find('.switch-thumb');
                            
                            const isOpen = $content.css('display') !== 'none';
                            
                            if (isOpen) {
                                $content.css({'max-height': '0', 'padding': '0 20px'});
                                setTimeout(function() { $content.css('display', 'none'); }, 300);
                                $icon.css('transform', 'rotate(0deg)');
                                $switchToggle.css('background', '#cbd5e1');
                                $switchThumb.css('left', '2px');
                            } else {
                                $content.css({'display': 'block', 'max-height': '1000px', 'padding': '15px 20px'});
                                $icon.css('transform', 'rotate(180deg)');
                                $switchToggle.css('background', toggleIconColor);
                                $switchThumb.css('left', '22px');
                            }
                        });
                    }, 100);
                    
                    return toggleHTML;
                    
                case 'html-code':
                    const htmlCode = settings.html_code || '<div class="custom-element"><h3>Custom HTML</h3><p>Add your code here</p></div>';
                    const cssCode = settings.css_code || '.custom-element { padding: 20px; background: #f8f9fa; }';
                    const jsCode = settings.js_code || '';
                    const htmlMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    const htmlCodeId = 'html-code-' + element.id;
                    
                    let htmlCodeHTML = `
                        <div class="probuilder-html-code-preview" id="${htmlCodeId}" style="
                            margin: ${htmlMargin.top}px ${htmlMargin.right}px ${htmlMargin.bottom}px ${htmlMargin.left}px;
                            position: relative;
                        ">
                            <div class="code-label" style="
                                position: absolute;
                                top: 8px;
                                right: 8px;
                                background: #1e293b;
                                color: #fff;
                                padding: 4px 10px;
                                border-radius: 3px;
                                font-size: 10px;
                                font-family: monospace;
                                z-index: 10;
                            ">HTML/CSS/JS</div>
                            <div class="html-output">
                                ${htmlCode}
                            </div>
                            ${cssCode ? `<style>${cssCode}</style>` : ''}
                        </div>
                    `;
                    
                    if (jsCode) {
                        setTimeout(function() {
                            try {
                                eval(jsCode);
                            } catch(e) {
                                console.log('JS code error (expected in preview):', e);
                            }
                        }, 100);
                    }
                    
                    return htmlCodeHTML;
                    
                case 'blockquote':
                    const quoteText = settings.quote_text || 'The only way to do great work is to love what you do.';
                    const author = settings.author || 'Steve Jobs';
                    const authorTitle = settings.author_title || 'Apple Co-founder';
                    const quoteStyle = settings.quote_style || 'border';
                    const accentColorQuote = settings.accent_color || '#92003b';
                    const quoteTextColor = settings.quote_color || '#333333';
                    const quoteFontSize = settings.quote_size || 20;
                    const quoteBgColor = settings.background_color || 'transparent';
                    const showQuoteIcon = settings.show_icon !== 'no';
                    const quotePadding = settings.padding || {top: 20, right: 30, bottom: 20, left: 30};
                    const quoteMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    let blockquoteStyle = '';
                    if (quoteStyle === 'border') {
                        blockquoteStyle = `border-left: 4px solid ${accentColorQuote}; padding-left: 30px; font-style: italic;`;
                    } else if (quoteStyle === 'box') {
                        blockquoteStyle = `border: 2px solid ${accentColorQuote}; padding: 30px; background: ${quoteBgColor !== 'transparent' ? quoteBgColor : '#f9f9f9'}; border-radius: 8px;`;
                    } else {
                        blockquoteStyle = `font-style: italic; padding: 20px 0;`;
                    }
                    
                    let blockquoteHTML = `
                        <blockquote class="probuilder-blockquote" style="
                            ${blockquoteStyle}
                            margin: ${quoteMargin.top}px ${quoteMargin.right}px ${quoteMargin.bottom}px ${quoteMargin.left}px;
                            padding: ${quotePadding.top}px ${quotePadding.right}px ${quotePadding.bottom}px ${quotePadding.left}px;
                            background: ${quoteBgColor !== 'transparent' ? quoteBgColor : (quoteStyle === 'box' ? '#f9f9f9' : 'transparent')};
                        ">
                    `;
                    
                    // Quote icon
                    if (showQuoteIcon) {
                        blockquoteHTML += `
                            <div style="font-size: 48px; color: ${accentColorQuote}; opacity: 0.3; margin-bottom: 15px;">
                                <i class="fa fa-quote-left"></i>
                            </div>
                        `;
                    }
                    
                    // Quote text
                    blockquoteHTML += `
                        <p style="
                            font-size: ${quoteFontSize}px;
                            line-height: 1.6;
                            margin: 0 0 20px 0;
                            color: ${quoteTextColor};
                            font-style: italic;
                        ">${quoteText}</p>
                    `;
                    
                    // Author
                    if (author) {
                        blockquoteHTML += `
                            <footer style="font-style: normal;">
                                <cite style="font-weight: 600; color: ${accentColorQuote}; font-style: normal;">
                                    ${author}
                                </cite>
                        `;
                        
                        if (authorTitle) {
                            blockquoteHTML += `<span style="color: #999; font-size: 14px;"> — ${authorTitle}</span>`;
                        }
                        
                        blockquoteHTML += '</footer>';
                    }
                    
                    blockquoteHTML += '</blockquote>';
                    
                    return blockquoteHTML;
                    
                case 'accordion':
                    const accordionItems = settings.items || [
                        { title: 'What is ProBuilder?', content: 'ProBuilder is a powerful page builder that allows you to create stunning websites with drag-and-drop functionality.' },
                        { title: 'How do I use it?', content: 'Simply drag widgets from the left panel onto your canvas and customize them using the settings panel on the right.' },
                        { title: 'Is it responsive?', content: 'Yes! ProBuilder creates fully responsive designs that work perfectly on all devices.' }
                    ];
                    const allowMultiple = settings.allow_multiple || 'no';
                    const defaultOpen = parseInt(settings.default_open) || 1;
                    const accordionTitleBg = settings.title_bg_color || '#f8f9fa';
                    const accordionTitleText = settings.title_text_color || '#333333';
                    const accordionActiveBg = settings.active_bg_color || '#92003b';
                    const accordionActiveText = settings.active_text_color || '#ffffff';
                    const accordionContentBg = settings.content_bg_color || '#ffffff';
                    const accordionContentText = settings.content_text_color || '#666666';
                    const accordionBorderColor = settings.border_color || '#e6e9ec';
                    const accordionBorderRadius = settings.border_radius || 4;
                    const accordionPadding = settings.padding || {top: 20, right: 0, bottom: 20, left: 0};
                    const accordionMargin = settings.margin || {top: 20, right: 0, bottom: 20, left: 0};
                    
                    const accordionId = 'accordion-' + element.id;
                    const accordionContainerStyle = `margin: ${accordionMargin.top}px ${accordionMargin.right}px ${accordionMargin.bottom}px ${accordionMargin.left}px;`;
                    
                    let accordionHTML = `<div class="probuilder-accordion-preview" data-accordion-id="${accordionId}" data-allow-multiple="${allowMultiple}" style="width: 100%; ${accordionContainerStyle}">`;
                    
                    accordionItems.forEach((item, index) => {
                        const isOpen = (defaultOpen > 0 && index + 1 === defaultOpen);
                        
                        accordionHTML += `
                            <div class="probuilder-accordion-item" data-item-index="${index}" style="margin-bottom: 10px; border: 1px solid ${accordionBorderColor}; border-radius: ${accordionBorderRadius}px; overflow: hidden;">
                                <div class="probuilder-accordion-header" style="
                                    padding: 15px 20px;
                                    background: ${isOpen ? accordionActiveBg : accordionTitleBg};
                                    color: ${isOpen ? accordionActiveText : accordionTitleText};
                                    font-weight: 600;
                                    cursor: pointer;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    transition: all 0.3s ease;
                                    border-radius: ${accordionBorderRadius}px;
                                " data-active-bg="${accordionActiveBg}" data-active-color="${accordionActiveText}" data-inactive-bg="${accordionTitleBg}" data-inactive-color="${accordionTitleText}">
                                    <span>${item.title || `Item ${index + 1}`}</span>
                                    <span class="probuilder-accordion-icon" style="font-size: 18px; transition: all 0.3s;">${isOpen ? '−' : '+'}</span>
                                </div>
                                <div class="probuilder-accordion-content" style="
                                    padding: ${isOpen ? '15px 20px' : '0 20px'};
                                    max-height: ${isOpen ? '500px' : '0'};
                                    background: ${accordionContentBg};
                                    color: ${accordionContentText};
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    opacity: ${isOpen ? '1' : '0'};
                                    border-top: none;
                                ">
                                    ${item.content || 'Content for accordion item'}
                                </div>
                            </div>
                        `;
                    });
                    
                    accordionHTML += '</div>';
                    
                    // Add interactive script
                    setTimeout(function() {
                        const $accordionContainer = $(`[data-accordion-id="${accordionId}"]`);
                        if ($accordionContainer.length === 0) return;
                        
                        $accordionContainer.find('.probuilder-accordion-header').off('click').on('click', function(e) {
                            e.stopPropagation();
                            const $header = $(this);
                            const $item = $header.closest('.probuilder-accordion-item');
                            const $content = $item.find('.probuilder-accordion-content');
                            const $icon = $header.find('.probuilder-accordion-icon');
                            const $accordion = $accordionContainer;
                            const allowMultipleOpen = $accordion.data('allow-multiple') === 'yes';
                            
                            const activeBg = $header.data('active-bg');
                            const activeColor = $header.data('active-color');
                            const inactiveBg = $header.data('inactive-bg');
                            const inactiveColor = $header.data('inactive-color');
                            
                            const isCurrentlyOpen = $content.css('max-height') !== '0px';
                            
                            if (!allowMultipleOpen && !isCurrentlyOpen) {
                                // Close all other items
                                $accordion.find('.probuilder-accordion-content').css({
                                    'max-height': '0',
                                    'padding': '0 20px',
                                    'opacity': '0'
                                });
                                $accordion.find('.probuilder-accordion-icon').text('+');
                                $accordion.find('.probuilder-accordion-header').css({
                                    'background': inactiveBg,
                                    'color': inactiveColor
                                });
                            }
                            
                            if (isCurrentlyOpen) {
                                // Close this item
                                $content.css({
                                    'max-height': '0',
                                    'padding': '0 20px',
                                    'opacity': '0'
                                });
                                $icon.text('+');
                                $header.css({
                                    'background': inactiveBg,
                                    'color': inactiveColor
                                });
                            } else {
                                // Open this item
                                $content.css({
                                    'max-height': '500px',
                                    'padding': '15px 20px',
                                    'opacity': '1'
                                });
                                $icon.text('−');
                                $header.css({
                                    'background': activeBg,
                                    'color': activeColor
                                });
                            }
                        });
                    }, 100);
                    
                    return accordionHTML;
                    
                case 'pricing-table':
                    const priceTitle = settings.title || 'Basic Plan';
                    const priceCurrency = settings.currency || '$';
                    const priceAmount = settings.price || '29';
                    const pricePeriod = settings.period || 'per month';
                    const priceFeatures = Array.isArray(settings.features) ? settings.features : [
                        { text: 'Feature 1' },
                        { text: 'Feature 2' },
                        { text: 'Feature 3' }
                    ];
                    const priceButtonText = settings.button_text || 'Get Started';
                    const priceFeatured = settings.featured === 'yes';
                    
                    const pricingBoxStyle = `
                        border: 2px solid ${priceFeatured ? '#0073aa' : '#e5e5e5'};
                        padding: 40px 30px;
                        text-align: center;
                        background: #ffffff;
                        position: relative;
                        border-radius: 8px;
                        transition: all 0.3s;
                        min-height: 400px;
                    `;
                    
                    let pricingHTML = `<div style="${pricingBoxStyle}">`;
                    
                    // Featured badge
                    if (priceFeatured) {
                        pricingHTML += `
                            <div style="
                                position: absolute;
                                top: 20px;
                                right: 20px;
                                background: #0073aa;
                                color: white;
                                padding: 5px 15px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: bold;
                            ">POPULAR</div>
                        `;
                    }
                    
                    // Title
                    pricingHTML += `<h3 style="font-size: 24px; margin: 0 0 20px 0; font-weight: 600;">${priceTitle}</h3>`;
                    
                    // Price
                    pricingHTML += `
                        <div style="margin-bottom: 30px;">
                            <span style="font-size: 24px; vertical-align: top; font-weight: 600;">${priceCurrency}</span>
                            <span style="font-size: 60px; font-weight: bold; line-height: 1; color: #333;">${priceAmount}</span>
                            <div style="color: #666; font-size: 14px; margin-top: 5px;">${pricePeriod}</div>
                        </div>
                    `;
                    
                    // Features
                    pricingHTML += '<ul style="list-style: none; margin: 0 0 30px 0; padding: 0; text-align: left;">';
                    priceFeatures.forEach(feature => {
                        pricingHTML += `
                            <li style="
                                padding: 10px 0;
                                border-bottom: 1px solid #f0f0f0;
                                color: #555;
                                position: relative;
                                padding-left: 25px;
                            ">
                                <i class="dashicons dashicons-yes" style="
                                    position: absolute;
                                    left: 0;
                                    top: 10px;
                                    color: #0073aa;
                                    font-size: 18px;
                                "></i>
                                ${feature.text || 'Feature'}
                            </li>
                        `;
                    });
                    pricingHTML += '</ul>';
                    
                    // Button
                    pricingHTML += `
                        <a href="#" style="
                            background: ${priceFeatured ? '#0073aa' : '#333333'};
                            color: white;
                            padding: 15px 40px;
                            text-decoration: none;
                            display: inline-block;
                            border-radius: 5px;
                            font-weight: bold;
                            transition: all 0.3s;
                        " onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                            ${priceButtonText}
                        </a>
                    `;
                    
                    pricingHTML += '</div>';
                    
                    return pricingHTML;
                    
                case 'team-member':
                    const teamImage = settings.image?.url || 'https://i.pravatar.cc/300?img=12';
                    const teamName = settings.name || 'John Doe';
                    const teamPosition = settings.position || 'CEO & Founder';
                    const teamBio = settings.bio || 'Passionate about creating amazing products.';
                    const teamEmail = settings.email || '';
                    const teamPhone = settings.phone || '';
                    const teamFacebook = settings.facebook || '';
                    const teamTwitter = settings.twitter || '';
                    const teamLinkedin = settings.linkedin || '';
                    const teamInstagram = settings.instagram || '';
                    
                    // Style settings
                    const teamLayout = settings.layout || 'left';
                    const teamTextAlign = settings.text_align || 'left';
                    const teamImageSize = settings.image_size || 150;
                    const teamBorderColor = settings.border_color || '#92003b';
                    const teamNameColor = settings.name_color || '#333333';
                    const teamPositionColor = settings.position_color || '#92003b';
                    
                    let teamHTML = '';
                    let teamContainerStyle = 'padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background: #fff;';
                    
                    if (teamLayout === 'center') {
                        // Centered layout (image on top)
                        teamContainerStyle += ` text-align: ${teamTextAlign}; display: flex; flex-direction: column; align-items: center;`;
                        teamHTML = `<div style="${teamContainerStyle}">`;
                        
                        // Photo - centered
                        teamHTML += `<div style="margin-bottom: 20px; display: inline-block;">
                            <img src="${teamImage}" alt="${teamName}" style="width: ${teamImageSize}px; height: ${teamImageSize}px; border-radius: 50%; object-fit: cover; border: 3px solid ${teamBorderColor}; display: block;">
                        </div>`;
                        
                    } else {
                        // Left or Right layout
                        const flexDirection = teamLayout === 'left' ? 'row' : 'row-reverse';
                        teamContainerStyle += ` display: flex; flex-direction: ${flexDirection}; gap: 25px; align-items: flex-start;`;
                        teamHTML = `<div style="${teamContainerStyle}">`;
                        
                        // Photo
                        teamHTML += `<div style="flex-shrink: 0;">
                            <img src="${teamImage}" alt="${teamName}" style="width: ${teamImageSize}px; height: ${teamImageSize}px; border-radius: 50%; object-fit: cover; border: 3px solid ${teamBorderColor};">
                        </div>`;
                        
                        // Content wrapper
                        teamHTML += `<div style="flex: 1; text-align: ${teamTextAlign};">`;
                    }
                    
                    // Name
                    teamHTML += `<h3 style="margin: 0 0 5px 0; font-size: 22px; font-weight: 600; color: ${teamNameColor};">${teamName}</h3>`;
                    
                    // Position
                    teamHTML += `<div style="color: ${teamPositionColor}; font-size: 14px; font-weight: 600; margin-bottom: 15px;">${teamPosition}</div>`;
                    
                    // Bio
                    if (teamBio) {
                        teamHTML += `<p style="color: #666; font-size: 14px; line-height: 1.6; margin: 0 0 15px 0;">${teamBio}</p>`;
                    }
                    
                    // Contact
                    if (teamEmail || teamPhone) {
                        const contactAlign = teamTextAlign === 'center' ? 'center' : (teamTextAlign === 'right' ? 'flex-end' : 'flex-start');
                        teamHTML += `<div style="font-size: 13px; color: #666; margin-bottom: 15px; display: flex; flex-direction: column; align-items: ${contactAlign}; gap: 5px;">`;
                        if (teamEmail) teamHTML += `<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-envelope" style="color: ${teamPositionColor};"></i> <span>${teamEmail}</span></div>`;
                        if (teamPhone) teamHTML += `<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-phone" style="color: ${teamPositionColor};"></i> <span>${teamPhone}</span></div>`;
                        teamHTML += `</div>`;
                    }
                    
                    // Social links
                    const hasSocial = teamFacebook || teamTwitter || teamLinkedin || teamInstagram;
                    if (hasSocial) {
                        const socialJustify = teamTextAlign === 'center' ? 'center' : (teamTextAlign === 'right' ? 'flex-end' : 'flex-start');
                        teamHTML += `<div style="display: flex; justify-content: ${socialJustify}; gap: 10px; margin-top: 15px;">`;
                        
                        if (teamFacebook || !hasSocial) {
                            teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #3b5998; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>`;
                        }
                        if (teamTwitter || !hasSocial) {
                            teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #1da1f2; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-twitter"></i></a>`;
                        }
                        if (teamLinkedin || !hasSocial) {
                            teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #0077b5; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-linkedin-in"></i></a>`;
                        }
                        if (teamInstagram) {
                            teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-instagram"></i></a>`;
                        }
                        
                        teamHTML += `</div>`;
                    }
                    
                    // Close content wrapper for left/right layouts
                    if (teamLayout !== 'center') {
                        teamHTML += `</div>`;
                    }
                    
                    teamHTML += `</div>`;
                    return teamHTML;
                    
                case 'testimonial':
                    const testContent = settings.content || 'This is an amazing product! I highly recommend it to everyone.';
                    const testImage = settings.image?.url || 'https://i.pravatar.cc/100?img=8';
                    const testName = settings.name || 'Jane Smith';
                    const testTitle = settings.title || 'Marketing Director';
                    const testRating = settings.rating || 5;
                    const testAlign = settings.align || 'center';
                    
                    let testHTML = `<div style="text-align: ${testAlign}; padding: 30px; border: 1px solid #e5e5e5; background: #f9f9f9; border-radius: 8px;">`;
                    
                    // Quote icon
                    testHTML += `<div style="font-size: 48px; color: #0073aa; opacity: 0.3; margin-bottom: 20px;"><i class="fa fa-quote-left"></i></div>`;
                    
                    // Content
                    testHTML += `<div style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; font-style: italic;"><p style="margin: 0;">${testContent}</p></div>`;
                    
                    // Rating
                    if (testRating > 0) {
                        testHTML += `<div style="margin-bottom: 20px; color: #ffa500;">`;
                        for (let i = 1; i <= 5; i++) {
                            testHTML += `<i class="fa fa-star" style="opacity: ${i <= testRating ? '1' : '0.3'};"></i> `;
                        }
                        testHTML += `</div>`;
                    }
                    
                    // Author
                    testHTML += `<div style="display: flex; align-items: center; justify-content: ${testAlign}; gap: 15px;">
                        <img src="${testImage}" alt="${testName}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                        <div style="text-align: left;">
                            <div style="font-weight: 600; margin-bottom: 5px;">${testName}</div>
                            <div style="color: #666; font-size: 14px;">${testTitle}</div>
                        </div>
                    </div>`;
                    
                    testHTML += `</div>`;
                    return testHTML;
                    
                case 'call-to-action':
                    const ctaTitle = settings.title || 'Ready to Get Started?';
                    const ctaDescription = settings.description || 'Join thousands of satisfied customers today!';
                    const ctaButtonText = settings.button_text || 'Get Started Now';
                    const ctaBgColor = settings.bg_color || '#92003b';
                    const ctaTextColor = settings.text_color || '#ffffff';
                    
                    let ctaHTML = `<div style="background: ${ctaBgColor}; color: ${ctaTextColor}; padding: 60px 40px; text-align: center; border-radius: 8px; position: relative; overflow: hidden;">`;
                    
                    // Background pattern
                    ctaHTML += `<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background: repeating-linear-gradient(45deg, transparent, transparent 10px, currentColor 10px, currentColor 20px);"></div>`;
                    
                    // Content
                    ctaHTML += `<div style="position: relative; z-index: 1;">`;
                    ctaHTML += `<h2 style="margin: 0 0 15px 0; font-size: 36px; color: inherit; font-weight: 700;">${ctaTitle}</h2>`;
                    if (ctaDescription) {
                        ctaHTML += `<p style="margin: 0 0 30px 0; font-size: 18px; opacity: 0.9;">${ctaDescription}</p>`;
                    }
                    ctaHTML += `<a href="#" style="background: #ffffff; color: ${ctaBgColor}; padding: 15px 40px; text-decoration: none; display: inline-block; border-radius: 4px; font-weight: 600; font-size: 16px; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">${ctaButtonText}</a>`;
                    ctaHTML += `</div>`;
                    
                    ctaHTML += `</div>`;
                    return ctaHTML;
                    
                case 'counter':
                    const counterEnd = settings.ending_number || 1000;
                    const counterPrefix = settings.prefix || '';
                    const counterSuffix = settings.suffix || '+';
                    const counterTitle = settings.title || 'Happy Clients';
                    const counterNumberColor = settings.number_color || '#0073aa';
                    const counterTitleColor = settings.title_color || '#333333';
                    const counterAlign = settings.text_align || 'center';
                    
                    let counterHTML = `<div style="text-align: ${counterAlign}; padding: 20px;">`;
                    counterHTML += `<div style="font-size: 48px; font-weight: bold; color: ${counterNumberColor}; margin-bottom: 10px;">`;
                    counterHTML += `${counterPrefix}${counterEnd}${counterSuffix}`;
                    counterHTML += `</div>`;
                    counterHTML += `<div style="font-size: 18px; color: ${counterTitleColor};">${counterTitle}</div>`;
                    counterHTML += `</div>`;
                    return counterHTML;
                    
                case 'contact-form':
                    const formTitle = settings.form_title || 'Get in Touch';
                    const formButtonText = settings.button_text || 'Send Message';
                    const formButtonColor = settings.button_color || '#92003b';
                    
                    let formHTML = `<div style="padding: 30px; background: #fff; border: 1px solid #e5e5e5; border-radius: 8px;">`;
                    
                    if (formTitle) {
                        formHTML += `<h3 style="margin: 0 0 25px 0; font-size: 24px; color: #333; font-weight: 600;">${formTitle}</h3>`;
                    }
                    
                    formHTML += `<div style="display: flex; flex-direction: column; gap: 15px;">`;
                    formHTML += `<input type="text" placeholder="Your Name" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
                    formHTML += `<input type="email" placeholder="Your Email" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
                    formHTML += `<input type="text" placeholder="Subject" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
                    formHTML += `<textarea placeholder="Your Message" rows="4" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;" disabled></textarea>`;
                    formHTML += `<button type="button" style="background: ${formButtonColor}; color: #fff; padding: 14px 30px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 15px;">${formButtonText}</button>`;
                    formHTML += `</div>`;
                    
                    formHTML += `</div>`;
                    return formHTML;
                    
                case 'newsletter':
                    const newsTitle = settings.title || 'Subscribe to Our Newsletter';
                    const newsDescription = settings.description || 'Get the latest updates and offers.';
                    const newsPlaceholder = settings.placeholder || 'Enter your email';
                    const newsButtonText = settings.button_text || 'Subscribe';
                    const newsLayout = settings.layout || 'inline';
                    const newsButtonColor = settings.button_color || '#92003b';
                    
                    let newsHTML = `<div style="padding: 40px; background: #f9f9f9; border-radius: 8px; text-align: center;">`;
                    
                    // Icon
                    newsHTML += `<div style="font-size: 48px; color: ${newsButtonColor}; margin-bottom: 20px; opacity: 0.8;"><i class="fa fa-envelope-open-text"></i></div>`;
                    
                    // Title
                    newsHTML += `<h3 style="margin: 0 0 10px 0; font-size: 24px; color: #333; font-weight: 600;">${newsTitle}</h3>`;
                    
                    // Description
                    if (newsDescription) {
                        newsHTML += `<p style="margin: 0 0 25px 0; color: #666; font-size: 15px;">${newsDescription}</p>`;
                    }
                    
                    // Form
                    const formStyle = newsLayout === 'inline' ? 'display: flex; gap: 10px;' : 'display: flex; flex-direction: column; gap: 10px;';
                    newsHTML += `<div style="${formStyle} max-width: 500px; margin: 0 auto;">`;
                    newsHTML += `<input type="email" placeholder="${newsPlaceholder}" style="flex: 1; padding: 14px 20px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
                    newsHTML += `<button type="button" style="background: ${newsButtonColor}; color: #fff; padding: 14px 35px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; white-space: nowrap;">${newsButtonText}</button>`;
                    newsHTML += `</div>`;
                    
                    newsHTML += `</div>`;
                    return newsHTML;
                    
                case 'countdown':
                    const countdownTarget = settings.target_date || '';
                    const showDays = settings.show_days !== 'no';
                    const showHours = settings.show_hours !== 'no';
                    const showMinutes = settings.show_minutes !== 'no';
                    const showSeconds = settings.show_seconds !== 'no';
                    const showLabels = settings.show_labels !== 'no';
                    const countdownLayout = settings.layout || 'boxes';
                    const countdownAlign = settings.align || 'center';
                    const digitSize = settings.digit_size || 48;
                    const labelSize = settings.label_size || 14;
                    const digitColor = settings.digit_color || '#ffffff';
                    const labelColor = settings.label_color || '#ffffff';
                    const boxBgColor = settings.box_bg_color || '#92003b';
                    const borderRadius = settings.box_border_radius || 8;
                    const showSeparator = settings.separator_show === 'yes';
                    const separatorText = settings.separator_text || ':';
                    
                    const justifyMap = {
                        'left': 'flex-start',
                        'center': 'center',
                        'right': 'flex-end'
                    };
                    
                    let countdownHTML = `<div style="display: flex; justify-content: ${justifyMap[countdownAlign]}; align-items: center; gap: 15px; flex-wrap: wrap;">`;
                    
                    // Box styles based on layout
                    let boxStyle = '';
                    if (countdownLayout === 'boxes') {
                        boxStyle = `background: ${boxBgColor}; padding: 20px 15px; text-align: center; min-width: 90px; border-radius: ${borderRadius}px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);`;
                    } else if (countdownLayout === 'circles') {
                        const circleSize = Math.max(digitSize + 40, 100);
                        boxStyle = `background: ${boxBgColor}; width: ${circleSize}px; height: ${circleSize}px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1);`;
                    } else {
                        boxStyle = `text-align: center;`;
                    }
                    
                    const digitStyle = `font-size: ${digitSize}px; font-weight: bold; color: ${digitColor}; line-height: 1;`;
                    const labelStyle = `font-size: ${labelSize}px; color: ${labelColor}; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;`;
                    const separatorStyle = `font-size: ${digitSize}px; font-weight: bold; color: ${digitColor};`;
                    
                    let firstItem = true;
                    
                    // Days
                    if (showDays) {
                        if (!firstItem && showSeparator && countdownLayout === 'inline') {
                            countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
                        }
                        countdownHTML += `<div style="${boxStyle}">`;
                        countdownHTML += `<div style="${digitStyle}">05</div>`;
                        if (showLabels) countdownHTML += `<div style="${labelStyle}">DAYS</div>`;
                        countdownHTML += `</div>`;
                        firstItem = false;
                    }
                    
                    // Hours
                    if (showHours) {
                        if (!firstItem && showSeparator && countdownLayout === 'inline') {
                            countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
                        }
                        countdownHTML += `<div style="${boxStyle}">`;
                        countdownHTML += `<div style="${digitStyle}">12</div>`;
                        if (showLabels) countdownHTML += `<div style="${labelStyle}">HOURS</div>`;
                        countdownHTML += `</div>`;
                        firstItem = false;
                    }
                    
                    // Minutes
                    if (showMinutes) {
                        if (!firstItem && showSeparator && countdownLayout === 'inline') {
                            countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
                        }
                        countdownHTML += `<div style="${boxStyle}">`;
                        countdownHTML += `<div style="${digitStyle}">34</div>`;
                        if (showLabels) countdownHTML += `<div style="${labelStyle}">MINUTES</div>`;
                        countdownHTML += `</div>`;
                        firstItem = false;
                    }
                    
                    // Seconds
                    if (showSeconds) {
                        if (!firstItem && showSeparator && countdownLayout === 'inline') {
                            countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
                        }
                        countdownHTML += `<div style="${boxStyle}">`;
                        countdownHTML += `<div style="${digitStyle}">56</div>`;
                        if (showLabels) countdownHTML += `<div style="${labelStyle}">SECONDS</div>`;
                        countdownHTML += `</div>`;
                    }
                    
                    countdownHTML += `</div>`;
                    return countdownHTML;
                    
                case 'video':
                    const videoType = settings.video_type || 'youtube';
                    const youtubeUrl = settings.youtube_url || 'https://www.youtube.com/watch?v=ScMzIvxBSi4';
                    const vimeoUrl = settings.vimeo_url || '';
                    const selfUrl = settings.self_url || '';
                    const aspectRatio = settings.aspect_ratio || '16:9';
                    const videoBorderRadius = settings.border_radius || 8;
                    const videoBoxShadow = settings.box_shadow !== 'no';
                    
                    // Calculate padding based on aspect ratio
                    const paddingMap = {
                        '16:9': '56.25%',
                        '4:3': '75%',
                        '21:9': '42.85%',
                        '1:1': '100%',
                        '9:16': '177.78%'
                    };
                    const videoPadding = paddingMap[aspectRatio] || '56.25%';
                    
                    let videoHTML = `<div style="position: relative; padding-bottom: ${videoPadding}; height: 0; overflow: hidden; border-radius: ${videoBorderRadius}px; ${videoBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.15);' : ''}">`;
                    
                    if (videoType === 'youtube' && youtubeUrl) {
                        // Extract YouTube video ID
                        let videoId = '';
                        const youtubeMatch1 = youtubeUrl.match(/[?&]v=([^&]+)/);
                        const youtubeMatch2 = youtubeUrl.match(/youtu\.be\/([^?]+)/);
                        const youtubeMatch3 = youtubeUrl.match(/embed\/([^?]+)/);
                        
                        if (youtubeMatch1) videoId = youtubeMatch1[1];
                        else if (youtubeMatch2) videoId = youtubeMatch2[1];
                        else if (youtubeMatch3) videoId = youtubeMatch3[1];
                        
                        if (videoId) {
                            videoHTML += `<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
                        } else {
                            videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>`;
                        }
                    } else if (videoType === 'vimeo' && vimeoUrl) {
                        // Extract Vimeo video ID
                        const vimeoMatch = vimeoUrl.match(/vimeo\.com\/(\d+)/);
                        const videoId = vimeoMatch ? vimeoMatch[1] : '';
                        
                        if (videoId) {
                            videoHTML += `<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;" src="https://player.vimeo.com/video/${videoId}" frameborder="0" allowfullscreen></iframe>`;
                        } else {
                            videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>`;
                        }
                    } else if (videoType === 'self' && selfUrl) {
                        videoHTML += `<video style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" controls><source src="${selfUrl}" type="video/mp4"></video>`;
                    } else {
                        // Placeholder
                        videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="fa fa-play-circle" style="font-size: 72px; margin-bottom: 15px; opacity: 0.9;"></i>
                            <div style="font-size: 18px; font-weight: 600;">Video Placeholder</div>
                            <div style="font-size: 13px; margin-top: 8px; opacity: 0.8;">Enter a video URL to display</div>
                        </div>`;
                    }
                    
                    videoHTML += `</div>`;
                    return videoHTML;
                    
                case 'shortcode':
                    const shortcodeText = settings.shortcode || '';
                    const shortcodeBgColor = settings.bg_color || '';
                    const shortcodePadding = settings.padding || {top: 20, right: 20, bottom: 20, left: 20};
                    const shortcodeTextAlign = settings.text_align || 'left';
                    
                    let shortcodeHTML = `<div style="
                        ${shortcodeBgColor ? `background: ${shortcodeBgColor};` : ''}
                        padding: ${shortcodePadding.top}px ${shortcodePadding.right}px ${shortcodePadding.bottom}px ${shortcodePadding.left}px;
                        text-align: ${shortcodeTextAlign};
                    ">`;
                    
                    if (!shortcodeText || shortcodeText.trim() === '' || shortcodeText === '[contact-form-7 id="123"]') {
                        // Show placeholder
                        shortcodeHTML += `
                            <div style="padding: 30px; background: #fff3cd; border: 2px dashed #ffc107; border-radius: 8px; text-align: center; color: #856404;">
                                <i class="fa fa-code" style="font-size: 48px; margin-bottom: 15px; opacity: 0.6;"></i>
                                <div style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">Shortcode Widget</div>
                                <div style="font-size: 14px;">Enter a shortcode in the settings to display content</div>
                                <div style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.5); border-radius: 4px; font-size: 13px;">
                                    <strong>Examples:</strong><br>
                                    [gallery]<br>
                                    [contact-form-7 id="123"]<br>
                                    [woocommerce_cart]
                                </div>
                            </div>
                        `;
                    } else {
                        // Show shortcode preview with actual code
                        shortcodeHTML += `
                            <div style="padding: 25px; background: #f0f7ff; border: 2px solid #2196f3; border-radius: 8px; text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px;">
                                    <i class="fa fa-code" style="font-size: 32px; color: #2196f3;"></i>
                                    <div style="font-size: 18px; font-weight: 600; color: #1976d2;">Shortcode Will Execute Here</div>
                                </div>
                                <div style="background: white; padding: 12px 20px; border-radius: 6px; border: 1px solid #bbdefb; margin-top: 15px;">
                                    <code style="color: #d32f2f; font-size: 14px; font-family: 'Courier New', monospace;">${shortcodeText}</code>
                                </div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 12px; opacity: 0.8;">
                                    Preview not available in editor - actual output will show on frontend
                                </div>
                            </div>
                        `;
                    }
                    
                    shortcodeHTML += `</div>`;
                    return shortcodeHTML;
                    
                case 'wp-header':
                    const headerMenuId = settings.menu_id || '';
                    const headerType = settings.header_type || 'horizontal';
                    const showLogo = settings.show_logo !== 'no';
                    const customLogo = settings.custom_logo?.url || '';
                    const logoWidth = settings.logo_width || 150;
                    const headerBgColor = settings.bg_color || '#ffffff';
                    const headerMenuColor = settings.menu_color || '#333333';
                    const headerPadding = settings.padding || {top: 20, right: 30, bottom: 20, left: 30};
                    const headerShadow = settings.box_shadow !== 'no';
                    
                    const headerStyle = `
                        background: ${headerBgColor};
                        padding: ${headerPadding.top}px ${headerPadding.right}px ${headerPadding.bottom}px ${headerPadding.left}px;
                        display: flex;
                        align-items: center;
                        ${headerType === 'horizontal' ? 'justify-content: space-between; flex-direction: row;' : 'flex-direction: column; gap: 20px;'}
                        ${headerShadow ? 'box-shadow: 0 2px 10px rgba(0,0,0,0.1);' : ''}
                    `;
                    
                    let headerHTML = `<div style="${headerStyle}">`;
                    
                    // Logo
                    if (showLogo) {
                        const logoSrc = customLogo || 'https://via.placeholder.com/150x50/92003b/ffffff?text=LOGO';
                        headerHTML += `<div style="flex-shrink: 0;">
                            <img src="${logoSrc}" alt="Logo" style="max-width: ${logoWidth}px; height: auto; display: block;">
                        </div>`;
                    }
                    
                    // Menu
                    headerHTML += `<nav style="flex-grow: 1;">
                        <ul style="list-style: none; margin: 0; padding: 0; display: flex; ${headerType === 'horizontal' ? 'flex-direction: row; gap: 30px; justify-content: flex-end;' : 'flex-direction: column; gap: 15px; align-items: center;'}">
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Home</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">About</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Services</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Contact</a></li>
                        </ul>
                    </nav>`;
                    
                    headerHTML += `</div>`;
                    return headerHTML;
                    
                case 'wp-sidebar':
                    const sidebarId = settings.sidebar_id || '';
                    const sidebarBgColor = settings.bg_color || '';
                    const sidebarPadding = settings.padding || {top: 20, right: 20, bottom: 20, left: 20};
                    const sidebarMargin = settings.margin || {top: 0, right: 0, bottom: 20, left: 0};
                    const sidebarRadius = settings.border_radius || 0;
                    const sidebarShadow = settings.box_shadow === 'yes';
                    
                    const sidebarWrapperStyle = `
                        ${sidebarBgColor ? `background: ${sidebarBgColor};` : ''}
                        padding: ${sidebarPadding.top}px ${sidebarPadding.right}px ${sidebarPadding.bottom}px ${sidebarPadding.left}px;
                        margin: ${sidebarMargin.top}px ${sidebarMargin.right}px ${sidebarMargin.bottom}px ${sidebarMargin.left}px;
                        ${sidebarRadius > 0 ? `border-radius: ${sidebarRadius}px;` : ''}
                        ${sidebarShadow ? 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);' : ''}
                    `;
                    
                    let sidebarHTML = `<div style="${sidebarWrapperStyle}">`;
                    
                    if (!sidebarId) {
                        sidebarHTML += `
                            <div style="padding: 30px; background: #e3f2fd; border: 2px dashed #2196f3; border-radius: 8px; text-align: center; color: #1976d2;">
                                <i class="fa fa-sidebar" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>
                                <div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">WordPress Sidebar</div>
                                <div style="font-size: 13px;">Select a sidebar from the settings</div>
                            </div>
                        `;
                    } else {
                        sidebarHTML += `
                            <div style="padding: 20px; background: #f8f9fa; border-radius: 6px;">
                                <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #333;">Sidebar Widget Area</h3>
                                <div style="font-size: 13px; color: #666; line-height: 1.6;">
                                    <p style="margin: 0 0 10px 0;">• Sidebar widgets will appear here</p>
                                    <p style="margin: 0 0 10px 0;">• Configured in Appearance → Widgets</p>
                                    <p style="margin: 0;">• Preview shows on frontend</p>
                                </div>
                            </div>
                        `;
                    }
                    
                    sidebarHTML += `</div>`;
                    return sidebarHTML;
                    
                case 'wp-footer':
                    const footerId = settings.footer_id || '';
                    const footerLayout = settings.footer_layout || 'columns';
                    const footerColumns = settings.columns || '3';
                    const showCopyright = settings.show_copyright !== 'no';
                    const copyrightText = settings.copyright_text || '© 2025 Your Site. All rights reserved.';
                    const footerBgColor = settings.bg_color || '#1f2937';
                    const footerTextColor = settings.text_color || '#e5e7eb';
                    const footerLinkColor = settings.link_color || '#93c5fd';
                    const footerPadding = settings.padding || {top: 60, right: 30, bottom: 30, left: 30};
                    const copyrightBg = settings.copyright_bg || '#111827';
                    
                    const footerStyle = `
                        background: ${footerBgColor};
                        color: ${footerTextColor};
                        padding: ${footerPadding.top}px ${footerPadding.right}px ${footerPadding.bottom}px ${footerPadding.left}px;
                    `;
                    
                    let footerHTML = `<div style="${footerStyle}">`;
                    
                    if (!footerId) {
                        footerHTML += `
                            <div style="padding: 30px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3); border-radius: 8px; text-align: center;">
                                <i class="fa fa-window-minimize" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>
                                <div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">WordPress Footer</div>
                                <div style="font-size: 13px; opacity: 0.9;">Select a footer widget area from the settings</div>
                            </div>
                        `;
                    } else {
                        const contentStyle = footerLayout === 'columns' ? 
                            `display: grid; grid-template-columns: repeat(${footerColumns}, 1fr); gap: 30px;` : 
                            `display: flex; flex-direction: column; gap: 20px;`;
                        
                        footerHTML += `<div style="${contentStyle}">`;
                        
                        for (let i = 0; i < parseInt(footerColumns); i++) {
                            footerHTML += `
                                <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 6px;">
                                    <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">Footer Widget ${i + 1}</h3>
                                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; line-height: 2;">
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 1}</a></li>
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 2}</a></li>
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 3}</a></li>
                                    </ul>
                                </div>
                            `;
                        }
                        
                        footerHTML += `</div>`;
                    }
                    
                    // Copyright
                    if (showCopyright) {
                        footerHTML += `
                            <div style="background: ${copyrightBg}; text-align: center; padding: 20px; margin-top: 30px; font-size: 14px;">
                                ${copyrightText}
                            </div>
                        `;
                    }
                    
                    footerHTML += `</div>`;
                    return footerHTML;
                    
                case 'form-builder':
                    const fbFormTitle = settings.form_title || 'Contact Us';
                    const fbFormDescription = settings.form_description || 'Send us a message and we\'ll get back to you as soon as possible.';
                    const fbFormButtonText = settings.submit_button_text || 'Send Message';
                    const fbFormBgColor = settings.form_bg_color || '#ffffff';
                    const fbFormPadding = settings.form_padding || {top: 30, right: 30, bottom: 30, left: 30};
                    const fbFormBorderRadius = settings.form_border_radius || {size: 8};
                    const fbFormBoxShadow = settings.form_box_shadow !== 'no';
                    const fbFieldBgColor = settings.field_bg_color || '#ffffff';
                    const fbFieldBorderColor = settings.field_border_color || '#e1e5e9';
                    const fbFieldFocusColor = settings.field_focus_color || '#92003b';
                    const fbButtonBgColor = settings.button_bg_color || '#92003b';
                    const fbButtonTextColor = settings.button_text_color || '#ffffff';
                    
                    const fbFormStyle = `
                        background-color: ${fbFormBgColor};
                        padding: ${fbFormPadding.top}px ${fbFormPadding.right}px ${fbFormPadding.bottom}px ${fbFormPadding.left}px;
                        border-radius: ${fbFormBorderRadius.size}px;
                        ${fbFormBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);' : ''}
                    `;
                    
                    let fbFormHTML = `<div style="${fbFormStyle}">`;
                    
                    if (fbFormTitle) {
                        fbFormHTML += `<h3 style="margin-top: 0; margin-bottom: 15px; color: #1e293b; font-size: 24px;">${fbFormTitle}</h3>`;
                    }
                    
                    if (fbFormDescription) {
                        fbFormHTML += `<p style="margin-bottom: 25px; color: #64748b; font-size: 14px;">${fbFormDescription}</p>`;
                    }
                    
                    fbFormHTML += `<form style="display: flex; flex-direction: column; gap: 20px;">`;
                    
                    // Sample form fields
                    fbFormHTML += `<div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b; font-size: 14px;">Name *</label>
                        <input type="text" placeholder="Your Name" style="width: 100%; padding: 12px; border: 1px solid ${fbFieldBorderColor}; border-radius: 4px; font-family: inherit; box-sizing: border-box;" disabled>
                    </div>`;
                    
                    fbFormHTML += `<div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b; font-size: 14px;">Email *</label>
                        <input type="email" placeholder="your@email.com" style="width: 100%; padding: 12px; border: 1px solid ${fbFieldBorderColor}; border-radius: 4px; font-family: inherit; box-sizing: border-box;" disabled>
                    </div>`;
                    
                    fbFormHTML += `<div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b; font-size: 14px;">Message *</label>
                        <textarea placeholder="Your message..." style="width: 100%; padding: 12px; border: 1px solid ${fbFieldBorderColor}; border-radius: 4px; font-family: inherit; min-height: 100px; resize: vertical; box-sizing: border-box;" disabled></textarea>
                    </div>`;
                    
                    fbFormHTML += `<button type="submit" style="background-color: ${fbButtonBgColor}; color: ${fbButtonTextColor}; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; font-weight: 600; cursor: pointer; align-self: flex-start;">${fbFormButtonText}</button>`;
                    
                    fbFormHTML += `</form></div>`;
                    return fbFormHTML;
                    
                case 'slider':
                    const slSliderHeight = settings.slider_height || {size: 500};
                    const slShowArrows = settings.show_arrows !== 'no';
                    const slShowDots = settings.show_dots !== 'no';
                    const slOverlayColor = settings.overlay_color || 'rgba(0,0,0,0.4)';
                    const slTitleColor = settings.title_color || '#ffffff';
                    const slDescriptionColor = settings.description_color || '#ffffff';
                    const slButtonBgColor = settings.button_bg_color || '#92003b';
                    const slButtonTextColor = settings.button_text_color || '#ffffff';
                    const slArrowColor = settings.arrow_color || '#ffffff';
                    const slDotColor = settings.dot_color || '#ffffff';
                    const slActiveDotColor = settings.active_dot_color || '#92003b';
                    
                    let slSliderHTML = `<div style="position: relative; height: ${slSliderHeight.size}px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">`;
                    
                    // Overlay
                    slSliderHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: ${slOverlayColor};"></div>`;
                    
                    // Content
                    slSliderHTML += `<div style="position: relative; z-index: 2; text-align: center; color: white; padding: 40px;">
                        <h2 style="color: ${slTitleColor}; font-size: 36px; font-weight: 700; margin: 0 0 20px 0; line-height: 1.2;">Welcome to Our Website</h2>
                        <p style="color: ${slDescriptionColor}; font-size: 16px; margin: 0 0 30px 0; line-height: 1.6;">Discover amazing products and services that will transform your business.</p>
                        <a href="#" style="display: inline-block; background-color: ${slButtonBgColor}; color: ${slButtonTextColor}; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: 600; font-size: 16px;">Get Started</a>
                    </div>`;
                    
                    // Navigation Arrows
                    if (slShowArrows) {
                        slSliderHTML += `<button style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); border: none; color: ${slArrowColor}; font-size: 24px; padding: 15px; border-radius: 50%; cursor: pointer; z-index: 3;">‹</button>`;
                        slSliderHTML += `<button style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); border: none; color: ${slArrowColor}; font-size: 24px; padding: 15px; border-radius: 50%; cursor: pointer; z-index: 3;">›</button>`;
                    }
                    
                    // Dots Navigation
                    if (slShowDots) {
                        slSliderHTML += `<div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 3;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background-color: ${slActiveDotColor}; cursor: pointer;"></div>
                            <div style="width: 12px; height: 12px; border-radius: 50%; background-color: ${slDotColor}; opacity: 0.5; cursor: pointer;"></div>
                            <div style="width: 12px; height: 12px; border-radius: 50%; background-color: ${slDotColor}; opacity: 0.5; cursor: pointer;"></div>
                        </div>`;
                    }
                    
                    slSliderHTML += `</div>`;
                    return slSliderHTML;
                    
                case 'blog-posts':
                    const bpPostsPerPage = settings.posts_per_page || {size: 6};
                    const bpPostLayout = settings.post_layout || 'grid';
                    const bpColumns = settings.columns || '3';
                    const bpShowImage = settings.show_image !== 'no';
                    const bpShowTitle = settings.show_title !== 'no';
                    const bpShowExcerpt = settings.show_excerpt !== 'no';
                    const bpShowMeta = settings.show_meta !== 'no';
                    const bpShowReadMore = settings.show_read_more !== 'no';
                    const bpReadMoreText = settings.read_more_text || 'Read More';
                    const bpCardBgColor = settings.card_bg_color || '#ffffff';
                    const bpCardBorderRadius = settings.card_border_radius || {size: 8};
                    const bpCardBoxShadow = settings.card_box_shadow !== 'no';
                    const bpTitleColor = settings.title_color || '#1e293b';
                    const bpExcerptColor = settings.excerpt_color || '#64748b';
                    const bpMetaColor = settings.meta_color || '#94a3b8';
                    const bpReadMoreBgColor = settings.read_more_bg_color || '#92003b';
                    const bpReadMoreTextColor = settings.read_more_text_color || '#ffffff';
                    
                    const bpGridColumns = bpPostLayout === 'grid' ? bpColumns : '1';
                    const bpCardStyle = `
                        background-color: ${bpCardBgColor};
                        border-radius: ${bpCardBorderRadius.size}px;
                        overflow: hidden;
                        ${bpCardBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);' : ''}
                    `;
                    
                    let bpBlogHTML = `<div style="display: grid; grid-template-columns: repeat(${bpGridColumns}, 1fr); gap: 30px;">`;
                    
                    // Sample blog posts
                    const samplePosts = [
                        {title: 'Getting Started with ProBuilder', excerpt: 'A complete guide for beginners who want to create stunning websites without coding knowledge.', category: 'Design', date: 'March 15, 2024', author: 'John Doe'},
                        {title: 'Advanced Design Techniques', excerpt: 'Pro tips and tricks for creating professional-looking websites with advanced features.', category: 'Development', date: 'March 12, 2024', author: 'Jane Smith'},
                        {title: 'Performance Optimization', excerpt: 'Learn how to speed up your website and improve user experience with these optimization tips.', category: 'Marketing', date: 'March 10, 2024', author: 'Mike Johnson'}
                    ];
                    
                    samplePosts.forEach((post, index) => {
                        bpBlogHTML += `<article style="${bpCardStyle}">`;
                        
                        if (bpShowImage) {
                            const gradients = ['linear-gradient(135deg, #667eea 0%, #764ba2 100%)', 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'];
                            bpBlogHTML += `<div style="height: 200px; background: ${gradients[index]}; position: relative;">
                                <div style="position: absolute; top: 15px; left: 15px; background: rgba(0,0,0,0.7); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px;">${post.category}</div>
                            </div>`;
                        }
                        
                        bpBlogHTML += `<div style="padding: 25px;">`;
                        
                        if (bpShowMeta) {
                            bpBlogHTML += `<div style="margin-bottom: 15px; font-size: 14px; color: ${bpMetaColor};">${post.date} • ${post.author}</div>`;
                        }
                        
                        if (bpShowTitle) {
                            bpBlogHTML += `<h3 style="margin: 0 0 15px 0; font-size: 18px; line-height: 1.4;">
                                <a href="#" style="color: ${bpTitleColor}; text-decoration: none;">${post.title}</a>
                            </h3>`;
                        }
                        
                        if (bpShowExcerpt) {
                            bpBlogHTML += `<p style="color: ${bpExcerptColor}; line-height: 1.6; margin: 0 0 20px 0; font-size: 14px;">${post.excerpt}</p>`;
                        }
                        
                        if (bpShowReadMore) {
                            bpBlogHTML += `<a href="#" style="display: inline-block; background-color: ${bpReadMoreBgColor}; color: ${bpReadMoreTextColor}; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">${bpReadMoreText}</a>`;
                        }
                        
                        bpBlogHTML += `</div></article>`;
                    });
                    
                    bpBlogHTML += `</div>`;
                    return bpBlogHTML;
                    
                case 'faq':
                    const fqFaqTitle = settings.faq_title || 'Frequently Asked Questions';
                    const fqFaqDescription = settings.faq_description || 'Find answers to the most common questions about our products and services.';
                    const fqFaqLayout = settings.layout || 'accordion';
                    const fqAllowMultiple = settings.allow_multiple !== 'yes';
                    const fqItemBgColor = settings.item_bg_color || '#ffffff';
                    const fqItemBorderColor = settings.item_border_color || '#e1e5e9';
                    const fqQuestionColor = settings.question_color || '#1e293b';
                    const fqAnswerColor = settings.answer_color || '#64748b';
                    const fqIconColor = settings.icon_color || '#92003b';
                    const fqActiveColor = settings.active_color || '#92003b';
                    const fqBorderRadius = settings.border_radius || {size: 8};
                    
                    let fqFaqHTML = `<div style="max-width: 800px; margin: 0 auto;">`;
                    
                    if (fqFaqTitle) {
                        fqFaqHTML += `<h2 style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">${fqFaqTitle}</h2>`;
                    }
                    
                    if (fqFaqDescription) {
                        fqFaqHTML += `<p style="color: #64748b; font-size: 16px; text-align: center; margin: 0 0 40px 0;">${fqFaqDescription}</p>`;
                    }
                    
                    fqFaqHTML += `<div style="display: flex; flex-direction: column; gap: 15px;">`;
                    
                    // Sample FAQ items
                    const sampleFAQs = [
                        {question: 'What is your return policy?', answer: 'We offer a 30-day return policy for all products in original condition. Simply contact our customer service team to initiate a return.', icon: 'fa fa-undo'},
                        {question: 'How long does shipping take?', answer: 'Standard shipping takes 3-5 business days. Express shipping is available for next-day delivery in most areas.', icon: 'fa fa-shipping-fast'},
                        {question: 'Do you offer customer support?', answer: 'Yes! Our customer support team is available 24/7 via live chat, email, and phone to help with any questions or issues.', icon: 'fa fa-headset'}
                    ];
                    
                    sampleFAQs.forEach((faq, index) => {
                        const fqItemStyle = `
                            background-color: ${fqItemBgColor};
                            border: 1px solid ${fqItemBorderColor};
                            border-radius: ${fqBorderRadius.size}px;
                            overflow: hidden;
                        `;
                        
                        fqFaqHTML += `<div style="${fqItemStyle}">`;
                        
                        const fqHeaderStyle = `padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; ${index === 0 ? `background: ${fqActiveColor};` : ''}`;
                        
                        fqFaqHTML += `<div style="${fqHeaderStyle}">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="${faq.icon}" style="color: ${fqIconColor}; font-size: 18px; width: 20px; text-align: center;"></i>
                                <h3 style="margin: 0; color: ${fqQuestionColor}; font-size: 18px; font-weight: 600;">${faq.question}</h3>
                            </div>
                            <i class="fa fa-chevron-down" style="color: ${fqIconColor}; font-size: 14px; transform: ${index === 0 ? 'rotate(180deg)' : 'rotate(0deg)'};"></i>
                        </div>`;
                        
                        if (index === 0) {
                            fqFaqHTML += `<div style="padding: 0 20px 20px 20px; color: ${fqAnswerColor}; line-height: 1.6;">
                                <p style="margin: 0;">${faq.answer}</p>
                            </div>`;
                        }
                        
                        fqFaqHTML += `</div>`;
                    });
                    
                    fqFaqHTML += `</div></div>`;
                    return fqFaqHTML;
                    
                case 'timeline':
                    const tlTimelineTitle = settings.timeline_title || 'Our Journey';
                    const tlTimelineDescription = settings.timeline_description || 'Follow our journey from the beginning to where we are today.';
                    const tlTimelineLayout = settings.layout || 'vertical';
                    const tlShowConnector = settings.show_connector !== 'no';
                    const tlItemBgColor = settings.item_bg_color || '#ffffff';
                    const tlItemBorderColor = settings.item_border_color || '#e1e5e9';
                    const tlDateColor = settings.date_color || '#92003b';
                    const tlIconBgColor = settings.icon_bg_color || '#92003b';
                    const tlIconColor = settings.icon_color || '#ffffff';
                    const tlConnectorColor = settings.connector_color || '#e1e5e9';
                    const tlBorderRadius = settings.border_radius || {size: 8};
                    
                    let tlTimelineHTML = `<div>`;
                    
                    if (tlTimelineTitle) {
                        tlTimelineHTML += `<h2 style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">${tlTimelineTitle}</h2>`;
                    }
                    
                    if (tlTimelineDescription) {
                        tlTimelineHTML += `<p style="color: #64748b; font-size: 16px; text-align: center; margin: 0 0 50px 0;">${tlTimelineDescription}</p>`;
                    }
                    
                    if (tlTimelineLayout === 'vertical') {
                        tlTimelineHTML += `<div style="position: relative; max-width: 800px; margin: 0 auto;">`;
                        
                        if (tlShowConnector) {
                            tlTimelineHTML += `<div style="position: absolute; left: 30px; top: 0; bottom: 0; width: 2px; background-color: ${tlConnectorColor};"></div>`;
                        }
                        
                        // Sample timeline items
                        const sampleItems = [
                            {date: '2020', title: 'Company Founded', description: 'We started our journey with a vision to revolutionize the industry and provide innovative solutions.', icon: 'fa fa-rocket'},
                            {date: '2021', title: 'First Product Launch', description: 'Launched our flagship product that quickly gained recognition in the market.', icon: 'fa fa-star'},
                            {date: '2022', title: 'International Expansion', description: 'Expanded our operations to serve customers across multiple countries.', icon: 'fa fa-globe'}
                        ];
                        
                        sampleItems.forEach((item, index) => {
                            const tlItemStyle = `
                                background-color: ${tlItemBgColor};
                                border: 1px solid ${tlItemBorderColor};
                                border-radius: ${tlBorderRadius.size}px;
                                padding: 25px;
                                margin-left: 80px;
                                margin-bottom: 30px;
                                position: relative;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            `;
                            
                            tlTimelineHTML += `<div style="${tlItemStyle}">
                                <div style="position: absolute; left: -60px; top: 25px; width: 40px; height: 40px; background-color: ${tlIconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;">
                                    <i class="${item.icon}" style="color: ${tlIconColor}; font-size: 16px;"></i>
                                </div>
                                <div style="color: ${tlDateColor}; font-size: 14px; font-weight: 600; margin-bottom: 10px;">${item.date}</div>
                                <h3 style="color: #1e293b; font-size: 20px; font-weight: 600; margin: 0 0 15px 0;">${item.title}</h3>
                                <p style="color: #64748b; margin: 0; line-height: 1.6;">${item.description}</p>
                            </div>`;
                        });
                        
                        tlTimelineHTML += `</div>`;
                    } else {
                        tlTimelineHTML += `<div style="display: flex; overflow-x: auto; gap: 30px; padding: 20px 0;">`;
                        
                        const sampleItems = [
                            {date: '2020', title: 'Company Founded', description: 'We started our journey with a vision to revolutionize the industry.', icon: 'fa fa-rocket'},
                            {date: '2021', title: 'First Product Launch', description: 'Launched our flagship product that quickly gained recognition.', icon: 'fa fa-star'},
                            {date: '2022', title: 'International Expansion', description: 'Expanded our operations to serve customers worldwide.', icon: 'fa fa-globe'}
                        ];
                        
                        sampleItems.forEach((item, index) => {
                            const tlItemStyle = `
                                background-color: ${tlItemBgColor};
                                border: 1px solid ${tlItemBorderColor};
                                border-radius: ${tlBorderRadius.size}px;
                                padding: 25px;
                                min-width: 300px;
                                flex-shrink: 0;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            `;
                            
                            tlTimelineHTML += `<div style="${tlItemStyle}">
                                <div style="width: 50px; height: 50px; background-color: ${tlIconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                                    <i class="${item.icon}" style="color: ${tlIconColor}; font-size: 20px;"></i>
                                </div>
                                <div style="color: ${tlDateColor}; font-size: 14px; font-weight: 600; margin-bottom: 10px; text-align: center;">${item.date}</div>
                                <h3 style="color: #1e293b; font-size: 18px; font-weight: 600; margin: 0 0 15px 0; text-align: center;">${item.title}</h3>
                                <p style="color: #64748b; margin: 0; line-height: 1.6; text-align: center;">${item.description}</p>
                            </div>`;
                        });
                        
                        tlTimelineHTML += `</div>`;
                    }
                    
                    tlTimelineHTML += `</div>`;
                    return tlTimelineHTML;
                    
                // NEW WIDGETS
                case 'portfolio':
                    const portfolioItems = settings.items || [
                        {title: 'Project 1', image: 'https://via.placeholder.com/400x300/93003c/ffffff?text=Project+1', category: 'Design'},
                        {title: 'Project 2', image: 'https://via.placeholder.com/400x300/0073aa/ffffff?text=Project+2', category: 'Development'},
                        {title: 'Project 3', image: 'https://via.placeholder.com/400x300/4caf50/ffffff?text=Project+3', category: 'Branding'}
                    ];
                    const portfolioColumns = settings.columns || '3';
                    let portfolioHTML = `<div style="display:grid;grid-template-columns:repeat(${portfolioColumns},1fr);gap:20px">`;
                    portfolioItems.forEach(item => {
                        portfolioHTML += `
                            <div style="position:relative;overflow:hidden;border-radius:8px;cursor:pointer">
                                <img src="${item.image || 'https://via.placeholder.com/400x300'}" style="width:100%;height:200px;object-fit:cover;display:block">
                                <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;flex-direction:column;color:#fff;opacity:0;transition:opacity 0.3s" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    <h3 style="margin:0 0 8px;font-size:20px;color:#fff">${item.title || 'Project'}</h3>
                                    <p style="margin:0;color:#ccc;font-size:14px">${item.category || 'Category'}</p>
                                </div>
                            </div>
                        `;
                    });
                    portfolioHTML += '</div>';
                    return portfolioHTML;
                
                case 'reviews':
                    const reviewItems = settings.reviews || [
                        {name: 'John Doe', rating: 5, review: 'Excellent service!'},
                        {name: 'Jane Smith', rating: 5, review: 'Highly recommended!'}
                    ];
                    const reviewColumns = settings.columns || '2';
                    let reviewsHTML = `<div style="display:grid;grid-template-columns:repeat(${reviewColumns},1fr);gap:20px">`;
                    reviewItems.forEach(review => {
                        const stars = '★'.repeat(review.rating || 5) + '☆'.repeat(5 - (review.rating || 5));
                        reviewsHTML += `
                            <div style="background:#f9f9f9;padding:20px;border-radius:8px">
                                <div style="color:#ffc107;font-size:18px;margin-bottom:10px">${stars}</div>
                                <h4 style="margin:0 0 10px;font-size:18px">${review.name || 'Customer'}</h4>
                                <p style="margin:0;color:#666;line-height:1.6">${review.review || 'Great experience!'}</p>
                            </div>
                        `;
                    });
                    reviewsHTML += '</div>';
                    return reviewsHTML;
                
                case 'hotspot':
                    const hotspotImage = settings.image || 'https://via.placeholder.com/800x600/93003c/ffffff?text=Hotspot+Image';
                    const hotspots = settings.hotspots || [{x_position: 30, y_position: 30, title: 'Hotspot 1', content: 'Info'}];
                    let hotspotHTML = `<div style="position:relative;display:inline-block;max-width:100%">
                        <img src="${hotspotImage}" style="width:100%;height:auto;display:block">`;
                    hotspots.forEach(spot => {
                        hotspotHTML += `<div style="position:absolute;left:${spot.x_position || 50}%;top:${spot.y_position || 50}%;transform:translate(-50%,-50%)">
                            <span style="display:block;width:20px;height:20px;background:#0073aa;border-radius:50%;animation:pulse 2s infinite"></span>
                        </div>`;
                    });
                    hotspotHTML += '</div><style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}</style>';
                    return hotspotHTML;
                
                case 'loop-builder':
                    return `<div style="display:grid;grid-template-columns:repeat(${settings.columns || 3},1fr);gap:20px">
                        ${[1,2,3].map(i => `
                            <div style="border:1px solid #eee;border-radius:8px;overflow:hidden">
                                <div style="background:#f0f0f0;height:150px;display:flex;align-items:center;justify-content:center;color:#999">Post ${i}</div>
                                <div style="padding:15px">
                                    <h3 style="margin:0 0 10px;font-size:18px">Dynamic Post ${i}</h3>
                                    <p style="margin:0;color:#666;font-size:14px">Post excerpt will appear here...</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>`;
                
                case 'lottie':
                    const lottieWidth = settings.width || 300;
                    const lottieLoop = settings.loop !== false;
                    const lottieAutoplay = settings.autoplay !== false;
                    const lottieUrl = settings.animation_url || 'https://assets3.lottiefiles.com/packages/lf20_UJNc2t.json';
                    
                    return `<div style="text-align:center;padding:40px;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:12px">
                        <div style="width:${lottieWidth}px;max-width:100%;margin:0 auto;background:#ffffff;border-radius:8px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,0.2)">
                            <div style="position:relative;width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;overflow:hidden">
                                <svg width="100%" height="100%" viewBox="0 0 200 200" style="animation:lottieRotate 3s linear infinite">
                                    <circle cx="100" cy="100" r="80" fill="none" stroke="#667eea" stroke-width="8" stroke-dasharray="50 30" style="animation:lottieDash 2s linear infinite"/>
                                    <circle cx="100" cy="100" r="60" fill="none" stroke="#fa709a" stroke-width="6" stroke-dasharray="40 20" style="animation:lottieDash 2.5s linear infinite reverse"/>
                                    <circle cx="100" cy="100" r="40" fill="none" stroke="#4facfe" stroke-width="4" stroke-dasharray="30 10" style="animation:lottieDash 3s linear infinite"/>
                                    <path d="M 100 60 L 120 100 L 100 140 L 80 100 Z" fill="#fee140" style="animation:lottieScale 2s ease-in-out infinite"/>
                                </svg>
                                <style>
                                    @keyframes lottieRotate {
                                        from { transform: rotate(0deg); }
                                        to { transform: rotate(360deg); }
                                    }
                                    @keyframes lottieDash {
                                        from { stroke-dashoffset: 0; }
                                        to { stroke-dashoffset: 100; }
                                    }
                                    @keyframes lottieScale {
                                        0%, 100% { transform: scale(1); opacity: 1; }
                                        50% { transform: scale(1.2); opacity: 0.8; }
                                    }
                                </style>
                            </div>
                            <div style="margin-top:20px;padding-top:20px;border-top:2px solid #f0f0f0">
                                <div style="display:flex;justify-content:center;gap:15px;margin-bottom:10px">
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:${lottieLoop ? '#10b981' : '#e5e7eb'};color:${lottieLoop ? '#fff' : '#666'};border-radius:20px;font-size:12px;font-weight:600">
                                        <i class="fa fa-repeat"></i> ${lottieLoop ? 'Loop ON' : 'Loop OFF'}
                                    </span>
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:${lottieAutoplay ? '#3b82f6' : '#e5e7eb'};color:${lottieAutoplay ? '#fff' : '#666'};border-radius:20px;font-size:12px;font-weight:600">
                                        <i class="fa fa-play"></i> ${lottieAutoplay ? 'Autoplay ON' : 'Autoplay OFF'}
                                    </span>
                                </div>
                                <p style="margin:10px 0 0;color:#666;font-size:13px;font-weight:500">
                                    <i class="fa fa-film" style="color:#667eea;margin-right:5px"></i>
                                    Lottie Animation
                                </p>
                                <p style="margin:5px 0 0;color:#999;font-size:11px">
                                    ${lottieWidth}px × ${lottieLoop ? 'Infinite' : 'Once'} × ${lottieAutoplay ? 'Auto' : 'Manual'}
                                </p>
                            </div>
                        </div>
                    </div>`;
                
                case 'mega-menu':
                case 'menu':
                    const menuLayout = settings.layout || 'horizontal';
                    const menuItems = ['Home', 'About', 'Services', 'Contact'];
                    const menuStyle = menuLayout === 'horizontal' ? 'flex-direction:row' : 'flex-direction:column';
                    return `<nav style="display:flex;${menuStyle};gap:20px;list-style:none;padding:0;margin:0">
                        ${menuItems.map(item => `<div style="padding:10px 15px;color:#333;cursor:pointer">${item}</div>`).join('')}
                    </nav>`;
                
                case 'search-form':
                    return `<div style="display:flex;gap:0;max-width:600px">
                        <input type="text" placeholder="${settings.placeholder || 'Search...'}" style="flex:1;padding:12px 15px;border:1px solid #ddd;border-radius:4px 0 0 4px;font-size:16px">
                        <button style="background:${settings.button_color || '#0073aa'};color:#fff;border:none;padding:12px 24px;border-radius:0 4px 4px 0;cursor:pointer;font-weight:600">${settings.button_text || 'Search'}</button>
                    </div>`;
                
                case 'breadcrumbs':
                    return `<nav style="font-size:14px;color:#666">
                        <a href="#" style="color:#0073aa;text-decoration:none">Home</a>
                        <span style="color:#999;margin:0 8px">${settings.separator || '/'}</span>
                        <a href="#" style="color:#0073aa;text-decoration:none">Category</a>
                        <span style="color:#999;margin:0 8px">${settings.separator || '/'}</span>
                        <span style="color:#666">Current Page</span>
                    </nav>`;
                
                case 'author-box':
                    return `<div style="background:#f9f9f9;border:1px solid #eee;padding:30px;border-radius:8px;display:flex;gap:20px;align-items:center">
                        <div style="width:80px;height:80px;border-radius:50%;background:#ddd;flex-shrink:0"></div>
                        <div style="flex:1">
                            <h3 style="margin:0 0 10px;font-size:24px">Author Name</h3>
                            <p style="margin:0 0 15px;color:#666">Author biography will appear here...</p>
                            <a href="#" style="color:#0073aa;text-decoration:none;font-weight:600">View All Posts →</a>
                        </div>
                    </div>`;
                
                case 'post-navigation':
                    return `<div style="display:flex;gap:20px">
                        <div style="flex:1;background:#f9f9f9;padding:20px;border-radius:8px">
                            <div style="color:#0073aa;font-size:12px;margin-bottom:5px">← Previous</div>
                            <h4 style="margin:0;font-size:16px">Previous Post Title</h4>
                        </div>
                        <div style="flex:1;background:#f9f9f9;padding:20px;border-radius:8px;text-align:right">
                            <div style="color:#0073aa;font-size:12px;margin-bottom:5px">Next →</div>
                            <h4 style="margin:0;font-size:16px">Next Post Title</h4>
                        </div>
                    </div>`;
                
                case 'share-buttons':
                    const networks = {
                        facebook: {color: '#1877f2', icon: 'fa fa-facebook-f'},
                        twitter: {color: '#1da1f2', icon: 'fa fa-twitter'},
                        linkedin: {color: '#0077b5', icon: 'fa fa-linkedin-in'},
                        pinterest: {color: '#bd081c', icon: 'fa fa-pinterest-p'}
                    };
                    let shareHTML = '<div style="display:flex;gap:10px">';
                    Object.keys(networks).forEach(network => {
                        shareHTML += `<div style="width:40px;height:40px;background:${networks[network].color};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center"><i class="${networks[network].icon}"></i></div>`;
                    });
                    shareHTML += '</div>';
                    return shareHTML;
                
                case 'price-list':
                    const priceItems = settings.items || [{title: 'Service 1', price: '$50', description: 'Description'}];
                    return priceItems.map(item => `
                        <div style="display:flex;justify-content:space-between;padding:20px 0;border-bottom:1px solid #eee">
                            <div><h4 style="margin:0 0 8px;font-size:18px">${item.title || 'Service'}</h4>
                            <p style="margin:0;color:#666;font-size:14px">${item.description || ''}</p></div>
                            <div style="font-size:20px;font-weight:700;color:#0073aa">${item.price || '$50'}</div>
                        </div>
                    `).join('');
                
                case 'login':
                    return `<div style="max-width:400px;padding:30px;background:#f9f9f9;border-radius:8px">
                        <h3 style="margin:0 0 20px">Login</h3>
                        <input type="text" placeholder="Username" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-bottom:15px">
                        <input type="password" placeholder="Password" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-bottom:15px">
                        <button style="width:100%;background:#0073aa;color:#fff;border:none;padding:12px;border-radius:4px;cursor:pointer;font-weight:600">Login</button>
                    </div>`;
                
                case 'sitemap':
                    return `<div style="display:grid;grid-template-columns:repeat(${settings.columns || 3},1fr);gap:30px">
                        <div><h3 style="margin:0 0 15px">Pages</h3><ul style="list-style:none;padding:0;line-height:2"><li><a href="#" style="color:#0073aa">Home</a></li><li><a href="#" style="color:#0073aa">About</a></li><li><a href="#" style="color:#0073aa">Contact</a></li></ul></div>
                        <div><h3 style="margin:0 0 15px">Posts</h3><ul style="list-style:none;padding:0;line-height:2"><li><a href="#" style="color:#0073aa">Recent Post 1</a></li><li><a href="#" style="color:#0073aa">Recent Post 2</a></li><li><a href="#" style="color:#0073aa">Recent Post 3</a></li></ul></div>
                    </div>`;
                
                case 'table-of-contents':
                    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px">${settings.title || 'Table of Contents'}</h4>
                        <ol style="margin:0;padding-left:25px;line-height:2">
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 1</a></li>
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 2</a></li>
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 3</a></li>
                        </ol>
                    </div>`;
                
                case 'icon':
                    const iconSize = settings.size || 50;
                    const iconColor = settings.color || '#0073aa';
                    const iconAlign = settings.align || 'center';
                    const iconClass = settings.icon || 'fa fa-star';
                    return `<div style="text-align:${iconAlign}"><i class="${iconClass}" style="font-size:${iconSize}px;color:${iconColor}"></i></div>`;
                
                case 'category-list':
                    const showCount = settings.show_count !== false;
                    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Categories</h4>
                        <ul style="list-style:none;padding:0;margin:0">
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Design</a>${showCount ? ' <span style="color:#999">(12)</span>' : ''}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Development</a>${showCount ? ' <span style="color:#999">(8)</span>' : ''}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Marketing</a>${showCount ? ' <span style="color:#999">(5)</span>' : ''}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Business</a>${showCount ? ' <span style="color:#999">(15)</span>' : ''}</li>
                        </ul>
                    </div>`;
                
                case 'tag-cloud':
                    const tagColor = settings.color || '#0073aa';
                    const tags = [
                        {name: 'WordPress', size: 20}, {name: 'Design', size: 16}, {name: 'Development', size: 18},
                        {name: 'Tutorial', size: 14}, {name: 'Guide', size: 16}, {name: 'Tips', size: 15},
                        {name: 'SEO', size: 17}, {name: 'Marketing', size: 14}, {name: 'Business', size: 19}
                    ];
                    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Popular Tags</h4>
                        <div style="display:flex;flex-wrap:wrap;gap:10px">
                            ${tags.map(tag => `<a href="#" style="color:${tagColor};font-size:${tag.size}px;text-decoration:none;transition:opacity 0.3s" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">${tag.name}</a>`).join('')}
                        </div>
                    </div>`;
                
                case 'archive-title':
                    const archiveTag = settings.tag || 'h1';
                    const archiveColor = settings.color || '#333';
                    return `<${archiveTag} style="color:${archiveColor};margin:0;font-size:36px;font-weight:700">Archive: Category Name</${archiveTag}>`;
                
                case 'site-logo':
                    const siteLogoWidth = settings.width || 150;
                    const siteLogoImage = settings.logo || 'https://via.placeholder.com/300x100/93003c/ffffff?text=Site+Logo';
                    const siteLogoLink = settings.link !== false;
                    const siteLogoHTML = `<img src="${siteLogoImage}" alt="Site Logo" style="width:${siteLogoWidth}px;height:auto;display:block">`;
                    return siteLogoLink ? `<a href="#">${siteLogoHTML}</a>` : siteLogoHTML;
                
                case 'recent-posts':
                    const recentLimit = settings.limit || 5;
                    const recentShowImage = settings.show_image !== false;
                    const recentShowDate = settings.show_date !== false;
                    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Recent Posts</h4>
                        ${[1,2,3].map(i => `
                            <div style="display:flex;gap:10px;margin-bottom:15px;align-items:center">
                                ${recentShowImage ? '<div style="width:60px;height:60px;background:#ddd;border-radius:4px;flex-shrink:0"></div>' : ''}
                                <div>
                                    <a href="#" style="color:#333;text-decoration:none;font-weight:600;display:block;margin-bottom:4px">Recent Post ${i}</a>
                                    ${recentShowDate ? '<div style="font-size:12px;color:#999">October 25, 2025</div>' : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>`;
                
                case 'post-excerpt':
                    const excerptLength = settings.length || 55;
                    const excerptShowMore = settings.show_more !== false;
                    const excerptMoreText = settings.more_text || 'Read More';
                    return `<div style="color:#666;line-height:1.6;font-size:16px">
                        <p style="margin:0">This is a sample post excerpt that will display the first ${excerptLength} words of the post content. It provides a preview of the article...</p>
                        ${excerptShowMore ? `<a href="#" style="color:#0073aa;text-decoration:none;font-weight:600;margin-top:10px;display:inline-block">${excerptMoreText} →</a>` : ''}
                    </div>`;
                
                case 'post-title':
                    const postTitleTag = settings.tag || 'h1';
                    const postTitleColor = settings.color || '#333';
                    const postTitleSize = settings.size || 36;
                    return `<${postTitleTag} style="color:${postTitleColor};font-size:${postTitleSize}px;margin:0;font-weight:700">Post Title Will Appear Here</${postTitleTag}>`;
                
                case 'post-featured-image':
                    const featuredImageSize = settings.size || 'full';
                    const featuredBorderRadius = settings.border_radius || 0;
                    const featuredLink = settings.link !== false;
                    const featuredImageHTML = `<img src="https://via.placeholder.com/800x600/93003c/ffffff?text=Featured+Image" alt="Featured Image" style="width:100%;height:auto;border-radius:${featuredBorderRadius}px;display:block">`;
                    return featuredLink ? `<a href="#">${featuredImageHTML}</a>` : featuredImageHTML;
                
                case 'post-date':
                    const dateFormat = settings.format || 'F j, Y';
                    const dateShowIcon = settings.show_icon !== false;
                    const dateColor = settings.color || '#666';
                    return `<div style="color:${dateColor};font-size:14px;display:flex;align-items:center;gap:8px">
                        ${dateShowIcon ? '<i class="fa fa-calendar-alt"></i>' : ''}
                        <span>October 25, 2025</span>
                    </div>`;
                
                case 'post-author':
                    const authorShowAvatar = settings.show_avatar !== false;
                    const authorAvatarSize = settings.avatar_size || 32;
                    const authorLink = settings.link !== false;
                    return `<div style="display:flex;align-items:center;gap:10px">
                        ${authorShowAvatar ? `<div style="width:${authorAvatarSize}px;height:${authorAvatarSize}px;background:#ddd;border-radius:50%"></div>` : ''}
                        ${authorLink ? '<a href="#" style="color:#333;text-decoration:none;font-weight:500">Author Name</a>' : '<span style="color:#333;font-weight:500">Author Name</span>'}
                    </div>`;
                
                case 'post-comments':
                    const commentsShowCount = settings.show_count !== false;
                    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        ${commentsShowCount ? '<h3 style="margin:0 0 20px;font-size:24px;color:#333">5 Comments</h3>' : ''}
                        <div style="border-left:3px solid #0073aa;padding-left:15px;margin-bottom:20px">
                            <div style="display:flex;gap:10px;margin-bottom:10px">
                                <div style="width:40px;height:40px;background:#ddd;border-radius:50%;flex-shrink:0"></div>
                                <div>
                                    <strong style="color:#333">John Doe</strong>
                                    <p style="margin:5px 0 0;color:#666;font-size:14px">This is a sample comment on the post.</p>
                                </div>
                            </div>
                        </div>
                        <div style="border:1px solid #ddd;border-radius:4px;padding:15px">
                            <textarea placeholder="Leave a comment..." style="width:100%;border:1px solid #ddd;border-radius:4px;padding:10px;font-size:14px;min-height:80px"></textarea>
                            <button style="background:#0073aa;color:#fff;border:none;padding:10px 20px;border-radius:4px;cursor:pointer;margin-top:10px">Post Comment</button>
                        </div>
                    </div>`;
                
                case 'progress-tracker':
                    const trackerSteps = settings.steps || [
                        {title: 'Step 1', complete: true},
                        {title: 'Step 2', complete: true},
                        {title: 'Step 3', complete: false}
                    ];
                    const trackerOrientation = settings.orientation || 'horizontal';
                    const trackerActiveColor = settings.active_color || '#4caf50';
                    const trackerInactiveColor = settings.inactive_color || '#cccccc';
                    
                    if (trackerOrientation === 'horizontal') {
                        return `<div style="display:flex;align-items:center;gap:10px">
                            ${trackerSteps.map((step, i) => `
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div style="display:flex;flex-direction:column;align-items:center">
                                        <div style="width:40px;height:40px;border-radius:50%;background:${step.complete ? trackerActiveColor : trackerInactiveColor};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">${i+1}</div>
                                        <span style="margin-top:8px;font-size:14px;color:#333">${step.title || 'Step ' + (i+1)}</span>
                                    </div>
                                    ${i < trackerSteps.length - 1 ? `<div style="flex:1;min-width:50px;height:2px;background:${step.complete ? trackerActiveColor : trackerInactiveColor}"></div>` : ''}
                                </div>
                            `).join('')}
                        </div>`;
                    } else {
                        return `<div style="display:flex;flex-direction:column;gap:10px">
                            ${trackerSteps.map((step, i) => `
                                <div style="display:flex;flex-direction:column;gap:10px">
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <div style="width:40px;height:40px;border-radius:50%;background:${step.complete ? trackerActiveColor : trackerInactiveColor};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">${i+1}</div>
                                        <span style="font-size:14px;color:#333">${step.title || 'Step ' + (i+1)}</span>
                                    </div>
                                    ${i < trackerSteps.length - 1 ? `<div style="width:2px;height:30px;background:${step.complete ? trackerActiveColor : trackerInactiveColor};margin-left:19px"></div>` : ''}
                                </div>
                            `).join('')}
                        </div>`;
                    }
                
                case 'reading-progress':
                    const progressPosition = settings.position || 'top';
                    const progressHeight = settings.height || 4;
                    const progressColor = settings.color || '#0073aa';
                    const progressBg = settings.background || '#eeeeee';
                    return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:${progressBg};height:${progressHeight}px;border-radius:${progressHeight}px;overflow:hidden;margin-bottom:15px">
                            <div style="width:60%;height:100%;background:${progressColor};transition:width 0.3s"></div>
                        </div>
                        <p style="margin:0;color:#666;font-size:14px">
                            <i class="fa fa-scroll" style="color:${progressColor};margin-right:8px"></i>
                            Reading Progress Bar (Fixed ${progressPosition === 'top' ? 'Top' : 'Top'})
                        </p>
                        <small style="color:#999">Scrolls with page progress</small>
                    </div>`;
                
                case 'code-highlight':
                    const codeLanguage = settings.language || 'javascript';
                    const codeTheme = settings.theme || 'dark';
                    const codeShowNumbers = settings.show_line_numbers !== false;
                    const codeSample = settings.code || 'function hello() {\n  console.log("Hello World");\n}';
                    const codeLines = codeSample.split('\\n');
                    const codeBg = codeTheme === 'dark' ? '#2d2d2d' : '#f5f5f5';
                    const codeColor = codeTheme === 'dark' ? '#f8f8f2' : '#333';
                    return `<div style="background:${codeBg};color:${codeColor};padding:20px;border-radius:8px;font-family:monospace;font-size:14px;overflow-x:auto">
                        <div style="margin-bottom:8px;color:#999;font-size:12px;text-transform:uppercase">${codeLanguage}</div>
                        ${codeShowNumbers ? codeLines.map((line, i) => `<div><span style="color:#6c6c6c;margin-right:15px;user-select:none">${i+1}</span>${line}</div>`).join('') : codeSample}
                    </div>`;
                
                case 'back-to-top':
                    const backPosition = settings.position || 'bottom-right';
                    const backSize = settings.size || 50;
                    const backButtonColor = settings.button_color || '#0073aa';
                    const backIconColor = settings.icon_color || '#ffffff';
                    const posStyle = backPosition === 'bottom-right' ? 'bottom:20px;right:20px' : 
                                    backPosition === 'bottom-left' ? 'bottom:20px;left:20px' : 
                                    backPosition === 'top-right' ? 'top:20px;right:20px' : 'top:20px;left:20px';
                    return `<div style="background:#f5f5f5;padding:40px;border-radius:8px;position:relative;min-height:200px">
                        <p style="margin:0 0 100px;color:#666;text-align:center">Scroll down to see button...</p>
                        <div style="position:absolute;${posStyle};width:${backSize}px;height:${backSize}px;background:${backButtonColor};color:${backIconColor};border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,0.2);cursor:pointer;font-size:24px;font-weight:bold">↑</div>
                        <p style="margin:0;color:#999;font-size:12px;text-align:center">Back to Top Button (${backPosition})</p>
                    </div>`;
                
                case 'text-path':
                    const pathText = settings.text || 'Curved Text Path';
                    const pathType = settings.path || 'curve';
                    const pathColor = settings.color || '#333';
                    const pathSize = settings.size || 24;
                    return `<div style="text-align:center;padding:30px;background:#f9f9f9;border-radius:8px">
                        <svg viewBox="0 0 500 150" style="width:100%;max-width:500px;height:auto">
                            <defs>
                                <path id="curve-path" d="M 50,100 Q 250,50 450,100" fill="transparent"/>
                            </defs>
                            <text fill="${pathColor}" font-size="${pathSize}" font-weight="600">
                                <textPath href="#curve-path" startOffset="50%" text-anchor="middle">
                                    ${pathText}
                                </textPath>
                            </text>
                        </svg>
                        <p style="margin:10px 0 0;color:#999;font-size:12px">Text follows a curved path</p>
                    </div>`;
                
                case 'scroll-snap':
                    const snapSections = settings.sections || [
                        {title: 'Section 1', content: 'Content 1', bg: '#f5f5f5'},
                        {title: 'Section 2', content: 'Content 2', bg: '#e5e5e5'}
                    ];
                    return `<div style="border:2px dashed #ddd;border-radius:8px;padding:20px;background:#fafafa">
                        <div style="text-align:center;margin-bottom:20px">
                            <i class="fa fa-layer-group" style="font-size:32px;color:#93003c;margin-bottom:10px"></i>
                            <h4 style="margin:0 0 5px;font-size:18px">Scroll Snap Container</h4>
                            <small style="color:#999">${snapSections.length} full-height sections</small>
                        </div>
                        ${snapSections.map((section, i) => `
                            <div style="background:${section.bg || '#f5f5f5'};padding:20px;border-radius:6px;margin-bottom:10px;border-left:4px solid #0073aa">
                                <h5 style="margin:0 0 8px;font-size:16px">${section.title || 'Section ' + (i+1)}</h5>
                                <p style="margin:0;color:#666;font-size:14px">${section.content || 'Section content'}</p>
                            </div>
                        `).join('')}
                    </div>`;
                
                case 'sticky-video':
                    const videoUrl = settings.video_url || 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
                    const stickyVideoPosition = settings.sticky_position || 'bottom-right';
                    return `<div style="background:#f5f5f5;padding:40px;border-radius:8px;position:relative;min-height:250px">
                        <p style="margin:0 0 150px;color:#666;text-align:center">
                            <i class="fa fa-video" style="font-size:48px;color:#93003c;display:block;margin-bottom:15px"></i>
                            Sticky Video Player
                        </p>
                        <div style="position:absolute;bottom:20px;right:20px;width:300px;background:#000;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.3)">
                            <div style="position:relative;padding-bottom:56.25%;background:#333">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:48px">▶</div>
                            </div>
                            <div style="padding:8px;background:#222;color:#fff;font-size:11px;text-align:center">Video will stick on scroll (${stickyVideoPosition})</div>
                        </div>
                    </div>`;
                
                case 'offcanvas':
                    const offcanvasPosition = settings.position || 'right';
                    const offcanvasTrigger = settings.trigger_text || '☰ Menu';
                    const offcanvasWidth = settings.panel_width || 300;
                    const offcanvasBg = settings.panel_bg || '#ffffff';
                    return `<div style="padding:30px;background:#f5f5f5;border-radius:8px">
                        <button style="background:#0073aa;color:#fff;border:none;padding:12px 24px;border-radius:4px;cursor:pointer;font-weight:600;font-size:16px">
                            ${offcanvasTrigger}
                        </button>
                        <div style="margin-top:20px;padding:20px;background:${offcanvasBg};border:2px solid #ddd;border-radius:8px;position:relative">
                            <button style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:24px;cursor:pointer;color:#999">×</button>
                            <h4 style="margin:0 0 15px;font-size:18px">Offcanvas Panel Preview</h4>
                            <p style="margin:0;color:#666;font-size:14px;line-height:1.6">Panel slides in from ${offcanvasPosition}</p>
                            <p style="margin:10px 0 0;color:#999;font-size:12px">Width: ${offcanvasWidth}px</p>
                        </div>
                    </div>`;
                
                case 'paypal-button':
                    const paypalAmount = settings.amount || 10;
                    const paypalCurrency = settings.currency || 'USD';
                    const paypalButtonText = settings.button_text || 'Buy Now';
                    return `<div style="text-align:center;padding:30px;background:#f9f9f9;border-radius:8px">
                        <div style="margin-bottom:20px">
                            <i class="fa fa-paypal" style="font-size:48px;color:#0070ba"></i>
                        </div>
                        <button style="background:#0070ba;color:#fff;border:none;padding:12px 30px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 2px 5px rgba(0,112,186,0.3)">
                            <i class="fa fa-paypal" style="margin-right:8px"></i>
                            ${paypalButtonText}
                        </button>
                        <p style="margin:15px 0 0;color:#666;font-size:14px">Amount: $${paypalAmount} ${paypalCurrency}</p>
                    </div>`;
                
                case 'stripe-button':
                    const stripeAmount = settings.amount || 1000;
                    const stripeCurrency = settings.currency || 'usd';
                    const stripeButtonText = settings.button_text || 'Pay with Stripe';
                    const stripeDisplayAmount = (stripeAmount / 100).toFixed(2);
                    return `<div style="text-align:center;padding:30px;background:#f9f9f9;border-radius:8px">
                        <div style="margin-bottom:20px">
                            <i class="fa fa-stripe-s" style="font-size:48px;color:#635bff"></i>
                        </div>
                        <button style="background:#635bff;color:#fff;border:none;padding:12px 30px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 2px 5px rgba(99,91,255,0.3)">
                            <i class="fa fa-lock" style="margin-right:8px"></i>
                            ${stripeButtonText}
                        </button>
                        <p style="margin:15px 0 0;color:#666;font-size:14px">Amount: $${stripeDisplayAmount} ${stripeCurrency.toUpperCase()}</p>
                        <small style="color:#999;font-size:12px">Secure payment processing</small>
                    </div>`;
                
                case 'custom-css':
                    const customCssCode = settings.css_code || '.my-class { color: red; }';
                    return `<div style="background:#2d2d2d;color:#f8f8f2;padding:20px;border-radius:8px;font-family:monospace;font-size:14px">
                        <div style="margin-bottom:10px;color:#6c92c7;font-weight:600">
                            <i class="fa fa-css3-alt" style="margin-right:8px;color:#2965f1"></i>
                            Custom CSS
                        </div>
                        <pre style="margin:0;color:#98c379;line-height:1.6">${customCssCode.substring(0, 200)}</pre>
                        <div style="margin-top:10px;padding:10px;background:rgba(255,255,255,0.1);border-radius:4px;font-size:12px;color:#abb2bf">
                            <i class="fa fa-info-circle" style="margin-right:5px"></i>
                            CSS will be applied to the page
                        </div>
                    </div>`;
                
                case 'facebook-embed':
                    const facebookType = settings.type || 'post';
                    const facebookUrl = settings.url || 'https://www.facebook.com/...';
                    return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;max-width:500px;margin:0 auto">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
                                <i class="fa fa-facebook" style="font-size:32px;color:#1877f2"></i>
                                <div style="text-align:left">
                                    <strong style="color:#333;display:block">Facebook ${facebookType === 'post' ? 'Post' : facebookType === 'page' ? 'Page' : 'Video'}</strong>
                                    <small style="color:#999">Embedded content</small>
                                </div>
                            </div>
                            <div style="background:#f0f2f5;height:200px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999">
                                <div>
                                    <i class="fa fa-facebook-f" style="font-size:48px;margin-bottom:10px;display:block"></i>
                                    Facebook ${facebookType.charAt(0).toUpperCase() + facebookType.slice(1)} Preview
                                </div>
                            </div>
                            <div style="margin-top:15px;padding:10px;background:#f0f2f5;border-radius:4px;font-size:12px;color:#666">
                                <i class="fa fa-link" style="margin-right:5px"></i>
                                Embed URL configured
                            </div>
                        </div>
                    </div>`;
                
                case 'twitter-embed':
                    const twitterTheme = settings.theme || 'light';
                    const twitterBg = twitterTheme === 'dark' ? '#15202b' : '#ffffff';
                    const twitterTextColor = twitterTheme === 'dark' ? '#ffffff' : '#14171a';
                    return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:${twitterBg};border:1px solid ${twitterTheme === 'dark' ? '#38444d' : '#e1e8ed'};border-radius:12px;padding:20px;max-width:550px;margin:0 auto;text-align:left">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
                                <div style="width:48px;height:48px;background:#1da1f2;border-radius:50%;display:flex;align-items:center;justify-content:center">
                                    <i class="fa fa-twitter" style="font-size:24px;color:#fff"></i>
                                </div>
                                <div>
                                    <strong style="color:${twitterTextColor};display:block">Twitter User</strong>
                                    <small style="color:#8899a6">@username</small>
                                </div>
                            </div>
                            <p style="margin:0 0 15px;color:${twitterTextColor};line-height:1.5">This is a sample tweet that will be embedded. Twitter/X content will display here with the ${twitterTheme} theme. 🐦</p>
                            <div style="color:#8899a6;font-size:13px">
                                <i class="fa fa-clock" style="margin-right:5px"></i>
                                Oct 25, 2025
                            </div>
                        </div>
                    </div>`;
                
                case 'instagram-feed':
                    const instaColumns = settings.columns || 3;
                    const instaLimit = settings.limit || 6;
                    return `<div style="padding:20px;background:#f9f9f9;border-radius:8px">
                        <div style="text-align:center;margin-bottom:20px">
                            <i class="fa fa-instagram" style="font-size:32px;color:#e4405f;margin-bottom:10px"></i>
                            <h4 style="margin:0;font-size:18px;color:#333">Instagram Feed</h4>
                            <small style="color:#999">@${settings.username || 'username'}</small>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(${instaColumns},1fr);gap:10px">
                            ${Array(Math.min(instaLimit, 6)).fill(0).map((_, i) => `
                                <div style="aspect-ratio:1;background:linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px">
                                    <i class="fa fa-camera"></i>
                                </div>
                            `).join('')}
                        </div>
                        <p style="text-align:center;margin-top:15px;font-size:12px;color:#999">
                            <i class="fa fa-info-circle"></i> Connect Instagram API to display photos
                        </p>
                    </div>`;
                
                case 'animated-text':
                    const animText = settings.text || 'Animated Text';
                    const animType = settings.animation || 'typing';
                    const animTextColor = settings.color || '#0073aa';
                    const animTextSize = settings.size || 36;
                    let animPreview = '';
                    
                    if (animType === 'typing') {
                        animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;border-right:3px solid ${animTextColor}">${animText}</h2>`;
                    } else if (animType === 'wave') {
                        animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700">${animText}</h2>`;
                    } else if (animType === 'glitch') {
                        animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;text-shadow:2px 2px ${animTextColor}">${animText}</h2>`;
                    } else if (animType === 'neon') {
                        animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;text-shadow:0 0 10px ${animTextColor},0 0 20px ${animTextColor}">${animText}</h2>`;
                    }
                    
                    return `<div style="background:#2d2d2d;padding:40px;border-radius:8px;text-align:center">
                        ${animPreview}
                        <p style="margin:15px 0 0;color:#999;font-size:12px">Animation: ${animType}</p>
                    </div>`;
                
                case 'notification':
                    const notifMessage = settings.message || 'Important announcement!';
                    const notifType = settings.type || 'info';
                    const notifDismissible = settings.dismissible !== false;
                    const notifColors = {
                        info: {bg: '#2196f3', icon: 'fa fa-info-circle'},
                        success: {bg: '#4caf50', icon: 'fa fa-check-circle'},
                        warning: {bg: '#ff9800', icon: 'fa fa-exclamation-triangle'},
                        error: {bg: '#f44336', icon: 'fa fa-times-circle'}
                    };
                    const notifColor = notifColors[notifType];
                    return `<div style="background:${notifColor.bg};color:#fff;padding:15px 20px;border-radius:8px;position:relative;text-align:center">
                        <i class="${notifColor.icon}" style="margin-right:10px;font-size:18px"></i>
                        <strong>${notifMessage}</strong>
                        ${notifDismissible ? '<button style="position:absolute;right:15px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:24px;cursor:pointer;line-height:1">×</button>' : ''}
                        <div style="margin-top:10px;font-size:11px;opacity:0.8">
                            <i class="fa fa-info-circle"></i> Fixed ${settings.position || 'top'} notification bar
                        </div>
                    </div>`;
                
                case 'image-comparison':
                    const beforeImage = settings.before_image || 'https://via.placeholder.com/800x600/666/fff?text=Before';
                    const afterImage = settings.after_image || 'https://via.placeholder.com/800x600/0073aa/fff?text=After';
                    const comparisonPosition = settings.position || 50;
                    return `<div style="position:relative;overflow:hidden;border-radius:8px;background:#f5f5f5">
                        <div style="position:relative;height:400px;background:url('${beforeImage}') center/cover">
                            <div style="position:absolute;top:0;left:0;height:100%;width:${comparisonPosition}%;overflow:hidden;background:url('${afterImage}') center/cover"></div>
                            <div style="position:absolute;top:0;left:${comparisonPosition}%;width:4px;height:100%;background:#fff;box-shadow:0 0 10px rgba(0,0,0,0.5)">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:40px;height:40px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,0.3)">
                                    <i class="fa fa-arrows-alt-h" style="color:#333"></i>
                                </div>
                            </div>
                            <div style="position:absolute;top:20px;left:20px;background:rgba(0,0,0,0.7);color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;font-weight:600">BEFORE</div>
                            <div style="position:absolute;top:20px;right:20px;background:rgba(0,115,170,0.9);color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;font-weight:600">AFTER</div>
                        </div>
                        <p style="text-align:center;margin:15px 0 0;color:#666;font-size:12px">
                            <i class="fa fa-arrows-alt-h"></i> Drag slider to compare
                        </p>
                    </div>`;
                
                case 'parallax-image':
                    const parallaxImage = settings.image || 'https://via.placeholder.com/1200x800/93003c/ffffff?text=Parallax+Background';
                    const parallaxHeight = settings.height || 400;
                    const parallaxSpeed = settings.speed || 0.5;
                    return `<div style="position:relative;overflow:hidden;height:${parallaxHeight}px;border-radius:8px">
                        <div style="background:url('${parallaxImage}') center/cover;height:150%;width:100%;position:absolute;top:-25%"></div>
                        <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:center;height:100%;color:#fff;text-shadow:0 2px 10px rgba(0,0,0,0.5)">
                            <div style="text-align:center">
                                <i class="fa fa-mountain" style="font-size:48px;margin-bottom:15px;display:block"></i>
                                <h3 style="margin:0 0 10px;font-size:28px;font-weight:700">Parallax Image</h3>
                                <p style="margin:0;font-size:14px">Speed: ${parallaxSpeed}x · Height: ${parallaxHeight}px</p>
                            </div>
                        </div>
                    </div>`;
                
                case 'calendly':
                    const calendlyUrl = settings.url || 'your-calendly-link';
                    const calendlyType = settings.type || 'inline';
                    const calendlyButtonText = settings.button_text || 'Schedule a Meeting';
                    
                    if (calendlyType === 'popup') {
                        return `<div style="text-align:center;padding:40px;background:#f5f8fa;border-radius:8px">
                            <div style="margin-bottom:20px">
                                <i class="fa fa-calendar-alt" style="font-size:48px;color:#006bff"></i>
                            </div>
                            <h3 style="margin:0 0 10px;font-size:22px;color:#333">Book a Time</h3>
                            <p style="margin:0 0 20px;color:#666;font-size:14px">Click button to open scheduling popup</p>
                            <button style="background:#006bff;color:#fff;border:none;padding:15px 40px;border-radius:25px;font-size:16px;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(0,107,255,0.3)">
                                <i class="fa fa-calendar-check" style="margin-right:8px"></i>
                                ${calendlyButtonText}
                            </button>
                            <p style="margin:15px 0 0;font-size:11px;color:#999">
                                <i class="fa fa-info-circle"></i> Calendly popup mode
                            </p>
                        </div>`;
                    } else {
                        // Inline calendar view
                        const timeSlots = ['9:00 AM', '10:00 AM', '11:00 AM', '1:00 PM', '2:00 PM', '3:00 PM'];
                        return `<div style="background:#fff;border:1px solid #e1e4e8;border-radius:8px;overflow:hidden;max-width:800px;margin:0 auto">
                            <div style="background:#006bff;color:#fff;padding:20px;text-align:center">
                                <i class="fa fa-calendar-alt" style="font-size:32px;margin-bottom:10px"></i>
                                <h3 style="margin:0 0 5px;font-size:22px">Schedule a Meeting</h3>
                                <p style="margin:0;font-size:14px;opacity:0.9">Select a convenient time</p>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                                <div style="padding:20px;border-right:1px solid #e1e4e8">
                                    <h4 style="margin:0 0 15px;font-size:16px;color:#333">
                                        <i class="fa fa-calendar" style="margin-right:8px;color:#006bff"></i>
                                        Select Date
                                    </h4>
                                    <div style="background:#f5f8fa;padding:15px;border-radius:6px;text-align:center">
                                        <div style="font-size:12px;color:#666;font-weight:600;margin-bottom:10px">OCTOBER 2025</div>
                                        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:5px;font-size:12px">
                                            ${[25,26,27,28,29,30,31].map(day => 
                                                `<div style="padding:8px;background:${day === 25 ? '#006bff' : '#fff'};color:${day === 25 ? '#fff' : '#333'};border-radius:4px;cursor:pointer;font-weight:${day === 25 ? '700' : '400'}">${day}</div>`
                                            ).join('')}
                                        </div>
                                    </div>
                                </div>
                                <div style="padding:20px">
                                    <h4 style="margin:0 0 15px;font-size:16px;color:#333">
                                        <i class="fa fa-clock" style="margin-right:8px;color:#006bff"></i>
                                        Available Times
                                    </h4>
                                    <div style="display:flex;flex-direction:column;gap:8px">
                                        ${timeSlots.map((time, idx) => `
                                            <div style="padding:12px 15px;background:${idx === 1 ? '#e8f3ff' : '#f5f8fa'};border:1px solid ${idx === 1 ? '#006bff' : 'transparent'};border-radius:6px;cursor:pointer;text-align:center;font-size:14px;font-weight:${idx === 1 ? '600' : '400'};color:#333">
                                                ${time}
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                            <div style="padding:15px 20px;background:#f5f8fa;border-top:1px solid #e1e4e8;text-align:center;font-size:12px;color:#666">
                                <i class="fa fa-globe" style="margin-right:5px"></i>
                                Powered by Calendly · ${calendlyUrl}
                            </div>
                        </div>`;
                    }
                
                default:
                    return `<div style="padding: 25px; background: #f8f9fa; text-align: center; border: 1px solid #e6e9ec; border-radius: 3px; color: #6d7882;"><i class="${widget.icon}" style="font-size: 32px; color: #93003c; margin-bottom: 10px;"></i><br><strong>${widget.title}</strong><br><small style="color: #a4afb7;">Click edit to customize</small></div>`;
            }
            } catch (error) {
                console.error('Error generating preview:', error);
                return '<div style="padding: 20px; color: #f00; text-align: center;">Error generating preview: ' + error.message + '</div>';
            }
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
                    // Slider control
                    $control.find('.probuilder-slider').on('input change', function() {
                        const newValue = $(this).val();
                        element.settings[key] = newValue;
                        console.log('✅ Slider updated:', key, '=', newValue);
                        
                        // Update value display
                        $(this).next('.probuilder-slider-value').text(newValue + (control.unit || ''));
                        
                        // If it's a motion control, apply animation immediately
                        if (key.startsWith('_motion_')) {
                            console.log('🎬 Motion slider changed! Applying animation...');
                            self.applyMotionStyles(element);
                        }
                        
                        self.updateElementPreview(element);
                    });
                } else if (control.type === 'color') {
                    // Color control
                    $control.find('input[type="color"]').on('input change', function() {
                        element.settings[key] = $(this).val();
                        console.log('Color updated:', key, $(this).val());
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
                    
                case 'select':
                    html += `<select class="probuilder-select" data-setting="${key}">`;
                    Object.keys(control.options).forEach(optKey => {
                        html += `<option value="${optKey}" ${value === optKey ? 'selected' : ''}>${control.options[optKey]}</option>`;
                    });
                    html += `</select>`;
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
                    html += `<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="x" value="${shadow.x || 0}" placeholder="X Offset">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="y" value="${shadow.y || 0}" placeholder="Y Offset">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="blur" value="${shadow.blur || 10}" placeholder="Blur">
                        <input type="color" class="probuilder-color" data-setting="${key}" data-shadow="color" value="${shadow.color || '#000000'}">
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
                            
                            // Update element preview
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
            
            // Apply motion/animation styles if set
            this.applyMotionStyles(element, $element);
            
            // Save to history with debounce
            clearTimeout(this.historyDebounceTimer);
            this.historyDebounceTimer = setTimeout(() => {
                this.saveHistory();
            }, 1000); // Save 1 second after last change
            
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
                            console.log('✅ Drop zone clicked in container:', containerId, 'column:', columnIndex);
                            self.showWidgetTemplateSelector(containerId, columnIndex);
                            return false;
                        });
                    });
                    
                    console.log('✅ Container click handlers attached');
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
            console.log('Re-rendering container with children:', containerElement.id);
            
            // Find the container in DOM
            const $container = $(`.probuilder-element[data-id="${containerElement.id}"]`);
            if ($container.length === 0) {
                console.error('Container not found in DOM');
                return;
            }
            
            // Generate new preview with children
            const preview = this.generatePreview(containerElement);
            $container.find('.probuilder-element-preview').html(preview);
            
            console.log('Container preview updated with', containerElement.children.length, 'children');
            
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
                    drop: function(event, ui) {
                        const widgetName = ui.draggable.data('widget');
                        console.log('Widget dropped into tab:', widgetName, 'Tab index:', tabIndex);
                        
                        const widget = self.widgets.find(w => w.name === widgetName);
                        if (!widget) return;
                        
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
                    background: rgba(0, 0, 0, 0.85);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
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
                    background: rgba(0, 0, 0, 0.85);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
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
                    background: rgba(0, 0, 0, 0.85);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
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
                            
                            // Close modal
                            $('.probuilder-templates-modal-overlay').remove();
                            
                            // Clear existing canvas first
                            console.log('Clearing canvas before inserting template...');
                            self.clearCanvas();
                            
                            // Insert template elements
                            if (Array.isArray(templateData)) {
                                console.log('Inserting', templateData.length, 'elements...');
                                templateData.forEach(function(element, index) {
                                    console.log(`Inserting element ${index + 1}:`, element.widgetType);
                                    if (element.widgetType) {
                                        self.addElement(element.widgetType, element.settings || {});
                                    }
                                });
                            } else {
                                console.log('Inserting single element...');
                                if (templateData.widgetType) {
                                    self.addElement(templateData.widgetType, templateData.settings || {});
                                }
                            }
                            
                            // Show success message
                            self.showToast('✓ Template inserted: ' + template.name);
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
            
            // Create simple widget picker overlay
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
                        padding: 30px;
                        max-width: 600px;
                        width: 90%;
                        max-height: 80vh;
                        overflow-y: auto;
                        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                    ">
                        <h3 style="margin: 0 0 20px 0; font-size: 20px; color: #27272a;">Select a Widget</h3>
                        <div class="probuilder-picker-grid" style="
                            display: grid;
                            grid-template-columns: repeat(3, 1fr);
                            gap: 15px;
                        "></div>
                        <button class="probuilder-picker-close" style="
                            margin-top: 20px;
                            padding: 10px 25px;
                            background: #f4f4f5;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            font-weight: 600;
                        ">Cancel</button>
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
            
            $('body').append($overlay);
        },
        
        /**
         * Insert element at specific index
         */
        insertElementAt: function(widgetName, index) {
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
         * Save page
         */
        savePage: function() {
            $('#probuilder-loading').show();
            
            $.ajax({
                url: ProBuilderEditor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'probuilder_save_page',
                    nonce: ProBuilderEditor.nonce,
                    post_id: ProBuilderEditor.post_id,
                    elements: JSON.stringify(this.elements)
                },
                success: function(response) {
                    $('#probuilder-loading').hide();
                    
                    if (response.success) {
                        // Show success message
                        const $message = $('<div class="probuilder-notice probuilder-notice-success">Page saved successfully!</div>');
                        $('.probuilder-editor-header').after($message);
                        setTimeout(() => $message.fadeOut(() => $message.remove()), 3000);
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

