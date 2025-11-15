export default // Widget renderer for "grid-layout" (auto-generated)
function renderGridLayout(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const gridPattern = settings.grid_pattern || 'pattern-1';
  const gridGap = settings.gap || 20;
  const gridMinHeight = settings.min_height || 30;
  const gridBgColor = settings.background_color || '#f8f9fa';
  const gridBorderColor = settings.border_color || '#ddd';
  const gridBorderWidth = settings.border_width || 1;
  const gridBorderRadius = settings.border_radius || 8;
  const enableResize = settings.enable_resize !== false;
  const gridPadding = settings.padding || {
    top: 20,
    right: 20,
    bottom: 20,
    left: 20
  };
  const gridMargin = settings.margin || {
    top: 0,
    right: 0,
    bottom: 0,
    left: 0
  };

  // Initialize children array if not exists
  // Initialize children array if not exists
  if (!element.children) {
    element.children = [];
  }
  console.log('🔍 Grid-layout rendering:', {
    widgetType: element.widgetType,
    id: element.id,
    childrenCount: element.children.length,
    children: element.children,
    settings: settings
  });

  // Check if template uses simple columns setting (for templates)
  // Check if template uses simple columns setting (for templates)
  const columnsCount = parseInt(settings.columns) || null;
  let gridTemplateData;
  let columnsTemplate;
  let rowsTemplate;
  if (columnsCount && columnsCount > 0) {
    // Generate dynamic grid based on columns setting
    const childrenCount = element.children.length || columnsCount;
    // Ensure we have at least enough cells for all children
    const numRows = Math.ceil(Math.max(childrenCount, columnsCount) / columnsCount);
    columnsTemplate = `repeat(${columnsCount}, 1fr)`;
    rowsTemplate = `repeat(${numRows}, auto)`;

    // Generate grid areas dynamically - create cells for all children
    const areas = [];
    for (let row = 1; row <= numRows; row++) {
      for (let col = 1; col <= columnsCount; col++) {
        const cellIndex = (row - 1) * columnsCount + (col - 1);
        // Create cell for each child or fill empty cells
        areas.push(`${row} / ${col} / ${row + 1} / ${col + 1}`);
      }
    }
    gridTemplateData = {
      columns: columnsTemplate,
      rows: rowsTemplate,
      areas: areas.slice()
    };
    console.log('✅ Using dynamic grid template:', {
      columns: columnsCount,
      rows: numRows,
      areas: areas.length,
      children: childrenCount
    });
  } else {
    // Use pattern-based grid
    const pattern = app.getGridPatterns().find(p => p.id === gridPattern) || app.getGridPatterns()[0];
    const baseTemplate = app.getGridTemplateData(gridPattern);
    gridTemplateData = {
      columns: baseTemplate.columns,
      rows: baseTemplate.rows,
      areas: Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : []
    };
    columnsTemplate = gridTemplateData.columns;
    rowsTemplate = gridTemplateData.rows;

    // Use custom template if available (from resize operations or deletions)
    if (element.settings.custom_template) {
      if (element.settings.custom_template.columns) {
        columnsTemplate = element.settings.custom_template.columns;
      }
      if (element.settings.custom_template.rows) {
        rowsTemplate = element.settings.custom_template.rows;
      }
      if (Array.isArray(element.settings.custom_template.areas) && element.settings.custom_template.areas.length > 0) {
        gridTemplateData.areas = element.settings.custom_template.areas.slice();
      }
      console.log('Using custom template:', {
        columns: columnsTemplate,
        rows: rowsTemplate,
        areas: gridTemplateData.areas.length
      });
    }
  }
  if (element.settings.custom_template) {
    const customTemplate = element.settings.custom_template;
    if (customTemplate.columns) {
      columnsTemplate = customTemplate.columns;
    }
    if (customTemplate.rows) {
      rowsTemplate = customTemplate.rows;
    }
    if (Array.isArray(customTemplate.areas) && customTemplate.areas.length > 0) {
      gridTemplateData.areas = customTemplate.areas.slice();
    }
  }

  // Generate grid HTML with actual cells
  // Generate grid HTML with actual cells
  const gridId = 'grid-' + element.id;
  let gridHTML = `
                        <style>
                            #${gridId} {
                                display: grid;
                                grid-template-columns: ${columnsTemplate};
                                grid-template-rows: ${rowsTemplate};
                                gap: ${gridGap}px;
                                width: 100%;
                                position: relative;
                                padding: ${gridPadding.top || 20}px ${gridPadding.right || 20}px ${gridPadding.bottom || 20}px ${gridPadding.left || 20}px;
                                margin: ${gridMargin.top || 0}px ${gridMargin.right || 0}px ${gridMargin.bottom || 0}px ${gridMargin.left || 0}px;
                            }
                            #${gridId} .grid-cell {
                                min-height: 0 !important;
                                background: ${gridBgColor};
                                border: ${gridBorderWidth}px solid ${gridBorderColor};
                                border-radius: ${gridBorderRadius}px;
                                padding: 40px 16px 16px;
                                position: relative;
                                overflow: visible !important;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: flex-start;
                                gap: 12px;
                                transition: all 0.3s;
                            }
                            #${gridId} .grid-cell.has-content {
                                padding-top: 40px;
                            }
                            #${gridId} .grid-cell.empty-cell {
                                display: flex;
                                align-items: center;
                                justify-content: flex-start;
                                transition: all 0.3s;
                            }
                            #${gridId} .grid-cell.empty-cell:hover {
                                background: rgba(0,124,186,0.05);
                                border-color: #007cba;
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                            }
                            
                            /* Cell Drag Handle - Shows on hover for reordering */
                            #${gridId} .grid-cell-drag-handle {
                                position: absolute;
                                top: 5px;
                                left: 5px;
                                width: 32px;
                                height: 32px;
                                background: rgba(0, 124, 186, 0.9);
                                border: none;
                                border-radius: 4px;
                                color: white;
                                cursor: grab;
                                display: none;
                                align-items: center;
                                justify-content: center;
                                z-index: 999;
                                transition: all 0.2s ease;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            }
                            
                            #${gridId} .grid-cell:hover .grid-cell-drag-handle {
                                display: flex;
                            }
                            
                            #${gridId} .grid-cell-drag-handle:hover {
                                background: rgba(0, 92, 135, 1);
                                transform: scale(1.1);
                                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                            }
                            
                            #${gridId} .grid-cell-drag-handle:active {
                                cursor: grabbing;
                            }
                            
                            /* Cell Delete Button - Now part of toolbar, styled above */
                            
                            /* Dragging state for grid cells */
                            #${gridId} .grid-cell.ui-sortable-helper {
                                opacity: 0.7;
                                box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
                                transform: rotate(2deg);
                                z-index: 10000 !important;
                                cursor: grabbing !important;
                            }
                            
                            #${gridId} .grid-cell.ui-sortable-placeholder {
                                border: 2px dashed #007cba !important;
                                background: rgba(0, 124, 186, 0.1) !important;
                                opacity: 1 !important;
                            }
                            #${gridId} .grid-cell-empty-content {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                                padding: 16px 20px;
                                border: 1px dashed rgba(0, 124, 186, 0.4);
                                border-radius: 10px;
                                max-width: 220px;
                                pointer-events: auto;
                                cursor: pointer;
                                background: rgba(0, 124, 186, 0.05);
                                transition: border-color 0.2s ease, background 0.2s ease;
                                margin: 0 auto;
                                gap: 6px;
                            }
                            
                            #${gridId} .grid-cell-empty-content:hover {
                                border-color: rgba(0, 124, 186, 0.8);
                                background: rgba(0, 124, 186, 0.1);
                            }
                            
                            #${gridId} .grid-cell-empty-content .dashicons {
                                opacity: 0.4 !important;
                            }
                            
                            #${gridId} .grid-cell-add-btn {
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                padding: 10px 16px;
                                border-radius: 20px;
                                border: none;
                                background: #007cba;
                                color: #fff;
                                font-size: 12px;
                                cursor: pointer;
                                transition: background 0.2s ease, transform 0.2s ease;
                                pointer-events: auto;
                            }
                            
                            #${gridId} .grid-cell-add-btn:hover {
                                background: #005a87;
                                transform: translateY(-1px);
                            }
                            
                            /* Ensure toolbar buttons are always on top and clickable */
                            #${gridId} .grid-cell-toolbar,
                            #${gridId} .grid-cell-toolbar * {
                                pointer-events: auto !important;
                                position: relative;
                                z-index: 1000 !important;
                            }
                            #${gridId} .grid-cell-toolbar {
                                position: absolute;
                                top: 8px;
                                right: 8px;
                                opacity: 0;
                                transition: opacity 0.2s;
                                z-index: 1000;
                                pointer-events: auto;
                                display: flex;
                                gap: 4px;
                            }
                            #${gridId} .grid-cell:hover .grid-cell-toolbar {
                                opacity: 1;
                            }
                            #${gridId} .grid-cell-toolbar button {
                                background: #007cba;
                                color: white;
                                border: none;
                                border-radius: 3px;
                                padding: 4px 8px;
                                cursor: pointer;
                                font-size: 11px;
                                margin-left: 4px;
                                pointer-events: auto;
                                position: relative;
                                z-index: 1001;
                            }
                            #${gridId} .grid-cell-toolbar .grid-cell-delete-btn {
                                background: rgba(220, 38, 38, 0.9);
                            }
                            #${gridId} .grid-cell-toolbar .grid-cell-delete-btn:hover {
                                background: rgba(220, 38, 38, 1);
                            }
                            /* Resize handles - Show on hover only */
                            #${gridId} .grid-resize-handle {
                                position: absolute;
                                background: #007cba;
                                opacity: 0;
                                transition: all 0.2s;
                                z-index: 999;
                                pointer-events: auto;
                            }
                            #${gridId} .grid-cell:hover .grid-resize-handle {
                                opacity: 0.7;
                            }
                            #${gridId} .grid-resize-handle:hover {
                                opacity: 1 !important;
                                background: #005a87;
                                transform: scale(1.2);
                            }
                            #${gridId} .grid-resize-handle-top {
                                top: -${Math.floor(gridGap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 6px;
                                cursor: row-resize;
                            }
                            #${gridId} .grid-resize-handle-left {
                                top: 0;
                                left: -${Math.floor(gridGap / 2)}px;
                                width: 6px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${gridId} .grid-resize-handle-right {
                                top: 0;
                                right: -${Math.floor(gridGap / 2)}px;
                                width: 6px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${gridId} .grid-resize-handle-bottom {
                                bottom: -${Math.floor(gridGap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 6px;
                                cursor: row-resize;
                            }
                            #${gridId} .grid-resize-handle-corner {
                                bottom: -${Math.floor(gridGap / 2)}px;
                                right: -${Math.floor(gridGap / 2)}px;
                                width: 16px;
                                height: 16px;
                                cursor: nwse-resize;
                                border-radius: 3px;
                                background: #ffffff !important;
                                border: 2px solid #007cba;
                            }
                            
                            /* Drag and Drop Styles for Grid Cells */
                            #${gridId} .probuilder-drop-target {
                                outline: 2px dashed #007cba !important;
                                background: rgba(0, 124, 186, 0.1) !important;
                                transition: all 0.2s ease;
                            }
                            
                            #${gridId} .probuilder-drop-hover {
                                outline: 3px solid #007cba !important;
                                background: rgba(0, 124, 186, 0.2) !important;
                                transform: scale(1.02);
                                box-shadow: 0 4px 12px rgba(0, 124, 186, 0.3);
                            }
                            
                            /* Nested Element Drag Styles */
                            #${gridId} .probuilder-nested-element {
                                cursor: grab;
                                transition: all 0.2s ease;
                            }
                            
                            #${gridId} .probuilder-nested-element:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            }
                            
                            #${gridId} .probuilder-nested-element:active {
                                cursor: grabbing;
                            }
                            
                            /* Toolbar Styles */
                            #${gridId} .probuilder-nested-toolbar {
                                background: rgba(0, 0, 0, 0.8);
                                border-radius: 4px;
                                padding: 4px;
                                backdrop-filter: blur(10px);
                            }
                            
                            #${gridId} .probuilder-nested-toolbar button {
                                background: transparent;
                                border: none;
                                color: white;
                                padding: 6px;
                                margin: 0 2px;
                                border-radius: 3px;
                                cursor: pointer;
                                transition: background 0.2s ease;
                            }
                            
                            #${gridId} .probuilder-resizable-widget:hover .probuilder-nested-toolbar,
                            #${gridId} .probuilder-nested-element:hover .probuilder-nested-toolbar {
                                display: flex !important;
                            }
                            
                            #${gridId} .probuilder-nested-toolbar button:hover {
                                background: rgba(255, 255, 255, 0.2);
                            }
                            
                            #${gridId} .probuilder-nested-toolbar .probuilder-nested-delete:hover {
                                background: rgba(244, 67, 54, 0.8);
                            }
                            
                            /* Widget Resize Dots - SIMPLE ALWAYS VISIBLE CORNERS */
                            #${gridId} .widget-resize-dot {
                                position: absolute;
                                width: 16px;
                                height: 16px;
                                z-index: 9999;
                                opacity: 1 !important;
                                cursor: pointer;
                                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                                border: 2px solid white;
                            }
                            
                            #${gridId} .widget-resize-dot:hover {
                                transform: scale(1.3);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.5);
                            }
                            
                            /* 4 Corner Dots - OUTSIDE the widget for visibility */
                            #${gridId} .widget-resize-dot-tl {
                                top: -8px;
                                left: -8px;
                                cursor: nwse-resize;
                            }
                            
                            #${gridId} .widget-resize-dot-tr {
                                top: -8px;
                                right: -8px;
                                cursor: nesw-resize;
                            }
                            
                            #${gridId} .widget-resize-dot-br {
                                bottom: -8px;
                                right: -8px;
                                cursor: nwse-resize;
                            }
                            
                            #${gridId} .widget-resize-dot-bl {
                                bottom: -8px;
                                left: -8px;
                                cursor: nesw-resize;
                            }
                            
                            #${gridId} .probuilder-resizable-widget {
                                min-width: 50px;
                                min-height: 50px;
                                border: 1px dashed transparent;
                                transition: border-color 0.2s ease;
                                padding: 8px; /* Add padding so handles don't get cut off */
                                margin: 8px; /* Add margin for space */
                                overflow: visible !important; /* Ensure handles are visible */
                            }
                            
                            #${gridId} .probuilder-resizable-widget:hover {
                                border-color: #007cba;
                            }
                        </style>
                    `;
  const layoutMode = element.settings.custom_template?.layout_mode || 'grid';
  const containerHeight = element.settings.custom_template?.container_height;
  const containerWidth = element.settings.custom_template?.container_width;
  const containerStyleParts = ['position: relative'];
  if (layoutMode === 'absolute') {
    containerStyleParts.push('display: block');
    if (containerWidth) {
      containerStyleParts.push(`min-width: ${containerWidth}px`);
    }
    if (containerHeight) {
      containerStyleParts.push(`min-height: ${containerHeight}px`);
      containerStyleParts.push(`height: ${containerHeight}px`);
    }
  }
  const containerStyleAttr = containerStyleParts.length ? ` style="${containerStyleParts.join('; ')}"` : '';
  gridHTML += `
                        <div id="${gridId}" class="probuilder-grid-layout" data-element-id="${element.id}" data-grid-element-id="${element.id}" data-grid-pattern="${gridPattern}"${containerStyleAttr}>
                    `;

  // Generate cells based on pattern
  // Generate cells based on pattern
  gridTemplateData.areas.forEach((area, index) => {
    const child = element.children && element.children[index];
    const hasContent = !!child;
    console.log(`Grid cell ${index}:`, {
      hasContent,
      child: child ? child.widgetType : 'none'
    });
    const cellOverrides = element.settings.custom_template?.cell_overrides || [];
    const override = cellOverrides[index];
    const useAbsolute = override && typeof override === 'object' && override.position === 'absolute';
    const overrideStyles = [];
    if (useAbsolute) {
      const resolveValue = (percent, px) => {
        if (typeof percent === 'number' && !Number.isNaN(percent)) {
          return `${percent}%`;
        }
        if (typeof px === 'number' && !Number.isNaN(px)) {
          return `${px}px`;
        }
        return null;
      };
      overrideStyles.push('position: absolute');
      overrideStyles.push('grid-area: unset');
      const leftValue = resolveValue(override.leftPercent, override.left);
      const topValue = resolveValue(override.topPercent, override.top);
      const widthValue = resolveValue(override.widthPercent, override.width);
      const heightValue = resolveValue(override.heightPercent, override.height);
      if (leftValue !== null) {
        overrideStyles.push(`left: ${leftValue}`);
      }
      if (topValue !== null) {
        overrideStyles.push(`top: ${topValue}`);
      }
      if (widthValue !== null) {
        overrideStyles.push(`width: ${widthValue}`);
      }
      if (heightValue !== null) {
        overrideStyles.push(`height: ${heightValue}`);
      }
      if (typeof override.zIndex !== 'undefined' && override.zIndex !== null && override.zIndex !== '') {
        overrideStyles.push(`z-index: ${override.zIndex}`);
      }
    } else {
      overrideStyles.push(`grid-area: ${area}`);
      overrideStyles.push('position: relative');
    }
    const overrideStyle = overrideStyles.length ? `style="${overrideStyles.join('; ')}"` : '';
    gridHTML += `
                            <div class="grid-cell ${hasContent ? 'has-content' : 'empty-cell'} ${hasContent ? '' : 'probuilder-drop-zone'}" 
                                 ${overrideStyle}
                                 data-cell-index="${index}"
                                 data-grid-id="${element.id}"
                                 data-original-area="${area}">
                                <div class="grid-cell-drag-handle" data-cell-index="${index}" title="Drag to reorder">
                                    <i class="dashicons dashicons-move" style="font-size: 16px;"></i>
                                </div>
                                <div class="grid-cell-toolbar" data-grid-id="${element.id}">
                                    <button type="button" class="add-content-btn" title="Add Content" data-grid-id="${element.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-plus" style="font-size: 12px; width: 12px; height: 12px;"></i>
                                    </button>
                                    <button type="button" class="settings-btn" title="Cell Settings" data-grid-id="${element.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-admin-generic" style="font-size: 12px; width: 12px; height: 12px;"></i>
                                    </button>
                                    <button type="button" class="grid-cell-delete-btn" title="Delete Cell" data-grid-id="${element.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-trash" style="font-size: 12px; width: 12px; height: 12px;"></i>
                                    </button>
                                </div>
                        `;
    if (hasContent) {
      // Render child widget with resize handles
      const childPreview = app.generatePreview(child, depth + 1);

      // Get widget dimensions and position (if stored)
      const widgetWidth = child.settings?.widget_width || '100%';
      const widgetHeight = child.settings?.widget_height || 'auto';
      const widgetMarginLeft = child.settings?.widget_margin_left || '0px';
      const widgetMarginTop = child.settings?.widget_margin_top || '0px';
      gridHTML += `
                                <div class="probuilder-nested-element probuilder-resizable-widget" 
                                     data-id="${child.id}" 
                                     data-widget="${child.widgetType}"
                                     data-cell-index="${index}"
                                     style="width: ${widgetWidth}; height: ${widgetHeight}; margin-left: ${widgetMarginLeft}; margin-top: ${widgetMarginTop}; position: relative;">
                                    <div class="probuilder-nested-toolbar" style="
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        z-index: 1000;
                                        display: none;
                                        gap: 4px;
                                        background: rgba(255,255,255,0.95);
                                        padding: 4px;
                                        border-radius: 3px;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                        pointer-events: auto;
                                    ">
                                        <button class="probuilder-nested-edit" title="Edit" style="
                                            background: #007cba;
                                            border: none;
                                            color: #ffffff;
                                            width: 24px;
                                            height: 24px;
                                            border-radius: 2px;
                                            cursor: pointer;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            pointer-events: auto;
                                        ">
                                            <i class="dashicons dashicons-edit" style="font-size: 12px; pointer-events: none;"></i>
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
                                            pointer-events: auto;
                                        ">
                                            <i class="dashicons dashicons-trash" style="font-size: 12px; pointer-events: none;"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Widget Resize Handles - 4 VISIBLE corner dots -->
                                    <div class="widget-resize-dot widget-resize-dot-tl" data-direction="top-left" title="Resize from top-left">
                                        <span style="background: #ff0000; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    <div class="widget-resize-dot widget-resize-dot-tr" data-direction="top-right" title="Resize from top-right">
                                        <span style="background: #00ff00; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    <div class="widget-resize-dot widget-resize-dot-br" data-direction="bottom-right" title="Resize from bottom-right">
                                        <span style="background: #0000ff; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    <div class="widget-resize-dot widget-resize-dot-bl" data-direction="bottom-left" title="Resize from bottom-left">
                                        <span style="background: #ffff00; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    
                                    <div class="probuilder-nested-preview">
                                        ${childPreview}
                                    </div>
                                </div>
                            `;
    } else {
      // Empty drop zone - compact add button
      gridHTML += `
                                <div class="grid-cell-empty-content">
                                    <button type="button" class="grid-cell-add-btn" data-grid-id="${element.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-plus-alt2" style="font-size: 16px;"></i>
                                        <span>Add Widget</span>
                                    </button>
                                </div>
                            `;
    }

    // Add resize handles if enabled
    if (enableResize) {
      gridHTML += `
                                <div class="grid-resize-handle grid-resize-handle-top" data-cell-index="${index}" data-direction="top"></div>
                                <div class="grid-resize-handle grid-resize-handle-left" data-cell-index="${index}" data-direction="left"></div>
                                <div class="grid-resize-handle grid-resize-handle-right" data-cell-index="${index}" data-direction="right"></div>
                                <div class="grid-resize-handle grid-resize-handle-bottom" data-cell-index="${index}" data-direction="bottom"></div>
                                <div class="grid-resize-handle grid-resize-handle-corner" data-cell-index="${index}" data-direction="both"></div>
                            `;
    }
    gridHTML += `</div>`;
  });
  gridHTML += `</div>`;
  return gridHTML;
}