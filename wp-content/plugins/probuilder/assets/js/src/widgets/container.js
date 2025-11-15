export default // Widget renderer for "container" (auto-generated)
function renderContainer(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  // Container - Simple row with adjustable columns
  const c2Columns = parseInt(settings.columns) || 2;
  const c2Gap = settings.gap || 20;
  const c2MinHeight = settings.min_height || 30;
  const c2BgColor = settings.background_color || '#f8f9fa';
  const c2BorderColor = settings.border_color || '#ddd';
  const c2BorderWidth = settings.border_width || 1;
  const c2BorderRadius = settings.border_radius || 8;
  const c2EnableResize = settings.enable_resize !== false;

  // Initialize children array if not exists
  // Initialize children array if not exists
  if (!element.children) {
    element.children = [];
  }
  console.log('🔍 Container rendering:', {
    widgetType: element.widgetType,
    id: element.id,
    childrenCount: element.children.length,
    children: element.children,
    columns: c2Columns
  });

  // Calculate how many cells we need
  // Calculate how many cells we need
  const childrenCount = element.children.length;
  const minCells = Math.max(c2Columns, childrenCount || c2Columns);

  // Generate dynamic template based on columns and children
  // Generate dynamic template based on columns and children
  const c2TemplateData = {
    columns: `repeat(${c2Columns}, 1fr)`,
    rows: 'auto',
    areas: []
  };

  // Generate grid areas - create enough for all children
  // If columns=1 and children=5, create 5 rows (vertical stack)
  // If columns=2 and children=5, create 3 rows (2+2+1)
  // Generate grid areas - create enough for all children
  // If columns=1 and children=5, create 5 rows (vertical stack)
  // If columns=2 and children=5, create 3 rows (2+2+1)
  const numRows = Math.ceil(minCells / c2Columns);
  let cellIndex = 0;
  for (let row = 1; row <= numRows; row++) {
    for (let col = 1; col <= c2Columns && cellIndex < minCells; col++) {
      c2TemplateData.areas.push(`${row} / ${col} / ${row + 1} / ${col + 1}`);
      cellIndex++;
    }
  }

  // Update rows template to accommodate multiple rows
  // Update rows template to accommodate multiple rows
  if (numRows > 1) {
    c2TemplateData.rows = `repeat(${numRows}, auto)`;
  }
  console.log('Container layout:', {
    columns: c2Columns,
    children: childrenCount,
    minCells: minCells,
    rows: numRows,
    areasCreated: c2TemplateData.areas.length
  });

  // Use custom template if available (from resize operations)
  // Use custom template if available (from resize operations)
  let c2ColumnsTemplate = c2TemplateData.columns;
  let c2RowsTemplate = c2TemplateData.rows;
  if (element.settings.custom_template) {
    c2ColumnsTemplate = element.settings.custom_template.columns || c2ColumnsTemplate;
    c2RowsTemplate = element.settings.custom_template.rows || c2RowsTemplate;
    console.log('Container using custom template:', {
      columns: c2ColumnsTemplate,
      rows: c2RowsTemplate
    });
  }

  // Get margin and padding
  // Get margin and padding
  const c2Margin = settings.margin || {
    top: '0',
    right: '0',
    bottom: '0',
    left: '0'
  };
  const c2Padding = settings.padding || {
    top: '0',
    right: '0',
    bottom: '0',
    left: '0'
  };

  // Build wrapper style with margin and padding
  // Build wrapper style with margin and padding
  const c2WrapperStyle = `
                        margin: ${c2Margin.top}px ${c2Margin.right}px ${c2Margin.bottom}px ${c2Margin.left}px;
                        padding: ${c2Padding.top}px ${c2Padding.right}px ${c2Padding.bottom}px ${c2Padding.left}px;
                    `;

  // Generate grid HTML with actual cells
  // Generate grid HTML with actual cells
  const c2Id = 'container-' + element.id;
  let c2HTML = `
                        <style>
                            #${c2Id} {
                                display: grid;
                                grid-template-columns: ${c2ColumnsTemplate};
                                grid-template-rows: ${c2RowsTemplate};
                                gap: ${c2Gap}px;
                                width: 100%;
                                position: relative;
                            }
                            #${c2Id} .container-cell {
                                min-height: 0 !important;
                                background: ${c2BgColor};
                                border: ${c2BorderWidth}px solid ${c2BorderColor};
                                border-radius: ${c2BorderRadius}px;
                                padding: 10px;
                                position: relative;
                                overflow: visible !important;
                            }
                            #${c2Id} .container-cell.has-content {
                                padding: 0;
                                overflow: visible !important;
                            }
                            #${c2Id} .container-cell.empty-cell {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            }
                            #${c2Id} .container-cell.empty-cell:hover {
                                background: rgba(0,124,186,0.05);
                                border-color: #007cba;
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                            }
                            /* Resize handles */
                            #${c2Id} .container-resize-handle {
                                position: absolute;
                                background: #007cba;
                                opacity: 0;
                                transition: opacity 0.2s;
                                z-index: 50;
                            }
                            #${c2Id} .container-cell:hover .container-resize-handle {
                                opacity: 0.6;
                            }
                            #${c2Id} .container-resize-handle:hover {
                                opacity: 1 !important;
                                background: #005a87;
                            }
                            #${c2Id} .container-resize-handle-top {
                                top: -${Math.floor(c2Gap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 4px;
                                cursor: row-resize;
                            }
                            #${c2Id} .container-resize-handle-left {
                                top: 0;
                                left: -${Math.floor(c2Gap / 2)}px;
                                width: 4px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${c2Id} .container-resize-handle-right {
                                top: 0;
                                right: -${Math.floor(c2Gap / 2)}px;
                                width: 4px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${c2Id} .container-resize-handle-bottom {
                                bottom: -${Math.floor(c2Gap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 4px;
                                cursor: row-resize;
                            }
                            #${c2Id} .container-resize-handle-corner {
                                bottom: -${Math.floor(c2Gap / 2)}px;
                                right: -${Math.floor(c2Gap / 2)}px;
                                width: 12px;
                                height: 12px;
                                cursor: nwse-resize;
                                border-radius: 2px;
                            }
                        </style>
                        <div id="${c2Id}" class="probuilder-container-widget" data-element-id="${element.id}" style="${c2WrapperStyle}">
                    `;

  // Generate cells based on pattern
  // Generate cells based on pattern
  c2TemplateData.areas.forEach((area, index) => {
    const child = element.children && element.children[index];
    const hasContent = !!child;
    console.log(`Container cell ${index}:`, {
      hasContent,
      child: child ? child.widgetType : 'none'
    });
    c2HTML += `
                            <div class="container-cell ${hasContent ? 'has-content' : 'empty-cell'} probuilder-drop-zone" 
                                 style="grid-area: ${area};" 
                                 data-cell-index="${index}"
                                 data-container-id="${element.id}"
                                 data-original-area="${area}">
                        `;

    // Add resize handles
    if (c2EnableResize) {
      c2HTML += `
                                <div class="container-resize-handle container-resize-handle-top" data-cell-index="${index}" data-direction="top"></div>
                                <div class="container-resize-handle container-resize-handle-left" data-cell-index="${index}" data-direction="left"></div>
                                <div class="container-resize-handle container-resize-handle-right" data-cell-index="${index}" data-direction="right"></div>
                                <div class="container-resize-handle container-resize-handle-bottom" data-cell-index="${index}" data-direction="bottom"></div>
                                <div class="container-resize-handle container-resize-handle-corner" data-cell-index="${index}" data-direction="both"></div>
                            `;
    }
    if (hasContent) {
      // Render child widget
      const childPreview = app.generatePreview(child, depth + 1);
      c2HTML += `
                                <div class="probuilder-nested-element" 
                                     data-id="${child.id}" 
                                     data-widget="${child.widgetType}"
                                     data-cell-index="${index}"
                                     style="position: relative;">
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
                                    ">
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
                                        ">
                                            <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    <div class="probuilder-nested-preview">
                                        ${childPreview}
                                    </div>
                                </div>
                            `;
    } else {
      // Empty cell with drop zone indicator
      c2HTML += `
                                <div class="container-cell-empty-content" style="pointer-events: auto; padding: 30px;">
                                    <i class="dashicons dashicons-welcome-add-page" style="font-size: 32px; opacity: 0.3; color: #999;"></i>
                                    <div style="font-size: 12px; margin-top: 8px; color: #999;">Column ${index + 1}</div>
                                    <div style="font-size: 11px; margin-top: 4px; color: #bbb;">Drop widgets here</div>
                                </div>
                            `;
    }
    c2HTML += `</div>`;
  });
  c2HTML += `</div>`;
  return c2HTML;
}