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
            
            // Make preview area droppable
            const $previewArea = $('#probuilder-preview-area');
            console.log('Preview area found:', $previewArea.length > 0 ? 'YES' : 'NO');
            
            $previewArea.droppable({
                accept: '.probuilder-widget, .probuilder-element',
                tolerance: 'pointer',
                greedy: true,
                drop: function(event, ui) {
                    const widgetName = ui.draggable.data('widget');
                    console.log('Dropped on preview area! Widget:', widgetName);
                    if (widgetName) {
                        self.addElement(widgetName);
                    } else {
                        console.error('No widget name found on dropped element');
                    }
                },
                over: function() {
                    console.log('Widget dragged over preview area');
                    $(this).addClass('probuilder-drop-active');
                },
                out: function() {
                    console.log('Widget dragged out of preview area');
                    $(this).removeClass('probuilder-drop-active');
                }
            });
            
            console.log('✅ Preview area is now droppable');
            
            // Make preview area sortable
            $previewArea.sortable({
                items: '> .probuilder-element',
                handle: '.probuilder-element-drag',
                placeholder: 'probuilder-element-placeholder',
                tolerance: 'pointer',
                update: function() {
                    console.log('Elements reordered');
                    self.updateElementsOrder();
                }
            });
            
            console.log('✅ Preview area is now sortable');
            
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
            $('.probuilder-settings-tab').on('click', function() {
                const tab = $(this).data('tab');
                $('.probuilder-settings-tab').removeClass('active');
                $(this).addClass('active');
                // Would load different settings based on tab
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
            const widget = this.widgets.find(w => w.name === widgetName);
            if (!widget) {
                console.error('Widget not found:', widgetName);
                return;
            }
            
            const containerElement = this.elements.find(e => e.id === containerId);
            if (!containerElement) {
                console.error('Container not found:', containerId);
                return;
            }
            
            const newElement = {
                id: 'element-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                widgetType: widgetName,
                settings: this.getDefaultSettings(widget),
                children: []
            };
            
            // Add to container's children
            if (!containerElement.children) {
                containerElement.children = [];
            }
            containerElement.children.push(newElement);
            
            console.log('Element added to container:', widgetName, 'into', containerId);
            
            // Re-render the container
            this.updateElementPreview(containerElement);
            this.makeContainersDroppable(); // Re-initialize drop zones
            
            return newElement;
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
                
                const $element = $(`
                    <div class="probuilder-element" data-id="${element.id}" data-widget="${element.widgetType}">
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
                $element.find('.probuilder-element-edit').on('click', function() {
                    self.openSettings(element);
                });
                
                // Duplicate button
                $element.find('.probuilder-element-duplicate').on('click', function() {
                    self.duplicateElement(element);
                });
                
                // Delete button
                $element.find('.probuilder-element-delete').on('click', function() {
                    if (confirm('Are you sure you want to delete this element?')) {
                        self.deleteElement(element);
                    }
                });
                
                // Click to select
                $element.on('click', function(e) {
                    if (!$(e.target).closest('.probuilder-element-actions').length) {
                        self.selectElement(element);
                    }
                });
                
                if (insertBefore) {
                    $(insertBefore).before($element);
                } else {
                    $('#probuilder-preview-area').append($element);
                }
                
                console.log('✅ Element appended to DOM');
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
                    const columnGap = settings.column_gap || 20;
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
                    
                    // Check if container has children
                    const hasChildren = element.children && element.children.length > 0;
                    
                    if (hasChildren) {
                        let columnsHTML = '<div style="' + columnStyle + '">';
                        for (let i = 0; i < element.children.length; i++) {
                            columnsHTML += '<div class="probuilder-column" style="min-height: 50px; border: 1px dashed #e6e9ec; padding: 10px;">';
                            columnsHTML += this.generatePreview(element.children[i], depth + 1);
                            columnsHTML += '</div>';
                        }
                        columnsHTML += '</div>';
                        return `<div style="${containerStyle}">${columnsHTML}</div>`;
                    } else {
                        // Empty container - show drop zones
                        let columnsHTML = '<div style="' + columnStyle + '">';
                        for (let i = 0; i < columns; i++) {
                            columnsHTML += `<div class="probuilder-column probuilder-drop-zone" style="min-height: 80px; border: 2px dashed #d5dadf; padding: 20px; text-align: center; color: #a4afb7; border-radius: 3px; background: #fafafa;">
                                <i class="dashicons dashicons-plus" style="font-size: 20px; opacity: 0.3;"></i><br>
                                <small>Drop here</small>
                            </div>`;
                        }
                        columnsHTML += '</div>';
                        return `<div style="${containerStyle}">${columnsHTML}</div>`;
                    }
                    
                case 'icon-box':
                    return `
                        <div style="text-align: ${settings.text_align || 'center'}; padding: 20px;">
                            <i class="${settings.icon || 'fa fa-star'}" style="font-size: ${settings.icon_size || 48}px; color: ${settings.icon_color || '#93003c'}; margin-bottom: 15px;"></i>
                            <h3 style="margin: 0 0 10px 0; font-size: 20px;">${settings.title || 'Icon Box Title'}</h3>
                            <p style="margin: 0; color: #666; font-size: 14px;">${settings.description || 'Description goes here'}</p>
                        </div>
                    `;
                    
                case 'progress-bar':
                    return `
                        <div style="margin: 15px 0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span style="font-weight: 600;">${settings.title || 'My Skill'}</span>
                                <span style="font-weight: 600;">${settings.percentage || 75}%</span>
                            </div>
                            <div style="background: ${settings.bg_color || '#e0e0e0'}; height: ${settings.height || 25}px; border-radius: ${(settings.height || 25) / 2}px; overflow: hidden;">
                                <div style="background: ${settings.bar_color || '#93003c'}; height: 100%; width: ${settings.percentage || 75}%;"></div>
                            </div>
                        </div>
                    `;
                    
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
            console.log('Opening settings for element:', element.id, element.widgetType);
            
            this.selectElement(element);
            const widget = this.widgets.find(w => w.name === element.widgetType);
            
            if (!widget) {
                console.error('Widget not found for:', element.widgetType);
                return;
            }
            
            console.log('Widget found:', widget.title, 'Controls:', widget.controls ? Object.keys(widget.controls).length : 0);
            
            $('#probuilder-settings-title').text(widget.title);
            
            // Hide placeholder
            $('.probuilder-settings-placeholder').hide();
            
            // Panel is always visible now - just update content
            this.renderSettings(element, widget);
            
            console.log('Settings rendered for:', widget.title);
        },
        
        /**
         * Render settings
         */
        renderSettings: function(element, widget) {
            const $content = $('#probuilder-settings-content');
            $content.empty();
            
            console.log('Rendering settings for:', widget.name);
            
            if (!widget.controls) {
                console.warn('No controls defined for widget:', widget.name);
                $content.html('<div style="padding: 20px; text-align: center; color: #999;">No settings available for this widget</div>');
                return;
            }
            
            console.log('Controls to render:', Object.keys(widget.controls).length);
            
            const self = this;
            
            Object.keys(widget.controls).forEach(key => {
                const control = widget.controls[key];
                
                if (control.type === 'section_start') {
                    $content.append(`<div class="probuilder-control-section"><h4>${control.label}</h4></div>`);
                    return;
                }
                
                const value = element.settings[key] !== undefined ? element.settings[key] : control.default;
                
                const $control = this.renderControl(key, control, value, element);
                
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
                } else {
                    // All other controls
                    $control.find('input, select, textarea').on('input change', function() {
                        element.settings[key] = $(this).val();
                        console.log('Control updated:', key, $(this).val());
                        self.updateElementPreview(element);
                    });
                }
                
                $content.append($control);
            });
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
                    html += `<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="top" value="${dims.top || 0}" placeholder="Top">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="right" value="${dims.right || 0}" placeholder="Right">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="bottom" value="${dims.bottom || 0}" placeholder="Bottom">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="left" value="${dims.left || 0}" placeholder="Left">
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
                this.makeContainersDroppable();
            }
            
            console.log('Preview updated successfully');
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

