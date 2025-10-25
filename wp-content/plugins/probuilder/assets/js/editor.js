/**
 * ProBuilder Editor JavaScript
 */

(function($) {
    'use strict';
    
    const ProBuilder = {
        
        widgets: [],
        elements: [],
        selectedElement: null,
        
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
            this.showSettingsPlaceholder(); // Show initial placeholder
            
            console.log('ProBuilder initialized successfully!');
            console.log('- Widgets:', this.widgets.length);
            console.log('- Elements:', this.elements.length);
            
            // Make globally accessible for templates
            window.ProBuilderApp = this;
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
            
            // Make preview area droppable (only for NEW widgets from sidebar)
            const $previewArea = $('#probuilder-preview-area');
            console.log('Preview area found:', $previewArea.length > 0 ? 'YES' : 'NO');
            
            $previewArea.droppable({
                accept: '.probuilder-widget', // Only accept widgets from sidebar, NOT existing elements
                tolerance: 'pointer',
                greedy: true,
                drop: function(event, ui) {
                    // Check if this is a widget from sidebar (not an existing element)
                    if (!ui.draggable.hasClass('probuilder-element')) {
                        const widgetName = ui.draggable.data('widget');
                        console.log('🎯 Dropped NEW widget on preview area:', widgetName);
                        if (widgetName) {
                            self.addElement(widgetName);
                        }
                    } else {
                        console.log('🔄 Existing element being reordered (handled by sortable)');
                    }
                },
                over: function(event, ui) {
                    if (!ui.draggable.hasClass('probuilder-element')) {
                        console.log('Widget dragged over preview area');
                        $(this).addClass('probuilder-drop-active');
                    }
                },
                out: function() {
                    console.log('Widget dragged out of preview area');
                    $(this).removeClass('probuilder-drop-active');
                }
            });
            
            console.log('✅ Preview area is now droppable (for NEW widgets only)');
            
            // Make preview area sortable
            $previewArea.sortable({
                items: '> .probuilder-element',
                // No handle - can drag from anywhere!
                placeholder: 'probuilder-element-placeholder',
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.7,
                distance: 15,
                delay: 200,
                revert: 150,
                cancel: 'input, textarea, select, button, a, .probuilder-element-actions',
                start: function(event, ui) {
                    console.log('🎯 Started dragging element from anywhere');
                    ui.item.addClass('dragging-element');
                    ui.placeholder.height(ui.item.outerHeight());
                    // Change cursor to grabbing
                    $('body').css('cursor', 'grabbing');
                },
                stop: function(event, ui) {
                    console.log('🎯 Stopped dragging element');
                    ui.item.removeClass('dragging-element');
                    // Reset cursor
                    $('body').css('cursor', '');
                },
                update: function() {
                    console.log('🎯 Elements reordered - updating data');
                    self.updateElementsOrder();
                }
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
            
            $('.probuilder-element[data-widget="container"] .probuilder-element-preview').each(function() {
                $(this).droppable({
                    accept: '.probuilder-widget',
                    tolerance: 'pointer',
                    greedy: true,
                    drop: function(event, ui) {
                        console.log('Dropped into container');
                        const widgetName = ui.draggable.data('widget');
                        const containerId = $(this).closest('.probuilder-element').data('id');
                        
                        if (widgetName && containerId) {
                            self.addElementToContainer(widgetName, containerId);
                        }
                    },
                    over: function() {
                        $(this).css('background', '#fef1f6');
                    },
                    out: function() {
                        $(this).css('background', '');
                    }
                });
                
                // Make sortable within container
                $(this).sortable({
                    items: '> .probuilder-element',
                    handle: '.probuilder-element-drag',
                    placeholder: 'probuilder-element-placeholder',
                    tolerance: 'pointer'
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
         * Add element to container
         */
        addElementToContainer: function(widgetName, containerId) {
            try {
                console.log('Adding element to container:', widgetName, 'into', containerId);
                
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
                containerElement.children.push(newElement);
                
                console.log('Element added to container children array');
                console.log('Container now has', containerElement.children.length, 'children');
                
                // Re-render the entire container
                this.updateContainerWithChildren(containerElement);
                
                console.log('✅ Element successfully added to container');
                
                return newElement;
            } catch (error) {
                console.error('❌ Error adding element to container:', error);
                alert('Error: ' + error.message);
                return null;
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
                
                // Click to select
                $element.on('click', function(e) {
                    if (!$(e.target).closest('.probuilder-element-actions').length && 
                        !$(e.target).closest('.probuilder-column-btn').length &&
                        !$(e.target).closest('.probuilder-drop-zone').length) {
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
                    const tag = settings.html_tag || 'h2';
                    const headingStyle = `
                        color: ${settings.color || '#333'};
                        font-size: ${settings.font_size || 32}px;
                        font-weight: ${settings.font_weight || 600};
                        text-align: ${settings.align || 'left'};
                        margin: 0;
                        line-height: 1.3;
                    `;
                    return `<${tag} style="${headingStyle}">${settings.title || 'This is a heading'}</${tag}>`;
                    
                case 'text':
                    const textStyle = `
                        color: ${settings.text_color || '#666'};
                        font-size: ${settings.font_size || 16}px;
                        line-height: ${settings.line_height || 1.6};
                        margin: 0;
                    `;
                    const textContent = (settings.text || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.').substring(0, 200);
                    return `<div style="${textStyle}">${textContent}</div>`;
                    
                case 'button':
                    const btnStyle = `
                        padding: ${settings.padding?.top || 12}px ${settings.padding?.right || 24}px ${settings.padding?.bottom || 12}px ${settings.padding?.left || 24}px;
                        background: ${settings.bg_color || '#93003c'};
                        color: ${settings.text_color || '#fff'};
                        border: none;
                        border-radius: ${settings.border_radius || 3}px;
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
                    
                    const columnStyle = `
                        display: grid;
                        grid-template-columns: repeat(${columns}, 1fr);
                        gap: ${columnGap}px;
                    `;
                    
                    // Generate responsive CSS
                    const responsiveCSS = `
                        <style>
                            #${containerId} .probuilder-container-columns {
                                display: grid;
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
                        </style>
                    `;
                    
                    // Check if container has children
                    const hasChildren = element.children && element.children.length > 0;
                    
                    if (hasChildren) {
                        let columnsHTML = '<div class="probuilder-container-columns">';
                        for (let i = 0; i < element.children.length; i++) {
                            const child = element.children[i];
                            const childWidget = this.widgets.find(w => w.name === child.widgetType);
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
                        }
                        columnsHTML += '</div>';
                        return `${responsiveCSS}<div id="${containerId}" style="${containerStyle}">${columnsHTML}</div>`;
                    } else {
                        // Empty container - show drop zones
                        let columnsHTML = '<div class="probuilder-container-columns">';
                        for (let i = 0; i < columns; i++) {
                            columnsHTML += `<div class="probuilder-column probuilder-drop-zone" data-container-id="${element.id}" data-column-index="${i}" style="min-height: 100px; border: 1px dashed #d5dadf; padding: 20px; text-align: center; color: #a4afb7; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <i class="dashicons dashicons-plus" style="font-size: 24px; opacity: 0.4; margin-bottom: 5px;"></i>
                                <span style="font-size: 13px; opacity: 0.7;">Click to add widget</span>
                            </div>`;
                        }
                        columnsHTML += '</div>';
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
         * Select element
         */
        selectElement: function(element) {
            this.selectedElement = element;
            $('.probuilder-element').removeClass('selected');
            $(`.probuilder-element[data-id="${element.id}"]`).addClass('selected');
        },
        
        /**
         * Open settings panel
         */
        openSettings: function(element) {
            console.log('==================== OPENING SETTINGS ====================');
            console.log('Element:', element.id, element.widgetType);
            
            this.selectElement(element);
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
            
            console.log('Controls to render:', Object.keys(widget.controls).length);
            
            const self = this;
            let controlsRendered = 0;
            
            Object.keys(widget.controls).forEach(key => {
                const control = widget.controls[key];
                
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
                        element.settings[key] = $(this).val();
                        console.log('Slider updated:', key, $(this).val());
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
                        console.log('Switcher updated:', key, element.settings[key]);
                        self.updateElementPreview(element);
                    });
                    
                    // All other controls
                    $control.find('input, select, textarea').not('.probuilder-switcher-input').on('input change', function() {
                        element.settings[key] = $(this).val();
                        console.log('Control updated:', key, $(this).val());
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
                
                // Re-initialize jQuery UI drop zones
                self.makeContainersDroppable();
                
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
                                <i class="dashicons dashicons-plus-alt" style="color: #92003b;"></i>
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
                console.log('Widget selected:', widgetName, 'for container:', containerId);
                
                if (containerId) {
                    // Add to specific container
                    self.addElementToContainer(widgetName, containerId);
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

