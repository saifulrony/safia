export default // Widget renderer for "flexbox" (auto-generated)
function renderFlexbox(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const flexDirection = settings.direction || 'row';
  const flexJustify = settings.justify_content || 'flex-start';
  const flexAlign = settings.align_items || 'stretch';
  const flexWrap = settings.wrap || 'wrap';
  const flexGap = settings.gap || 20;
  const flexMinHeight = settings.min_height || 100;
  const flexPadding = settings.padding || {
    top: 20,
    right: 20,
    bottom: 20,
    left: 20
  };
  const flexMargin = settings.margin || {
    top: 0,
    right: 0,
    bottom: 20,
    left: 0
  };
  const flexBgType = settings.background_type || 'color';
  const flexBgColor = settings.background_color || '#f8f9fa';
  const flexBgGradient = settings.background_gradient || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
  const flexBgImage = settings.background_image?.url || '';
  const flexBorder = settings.border || {
    width: 0,
    style: 'solid',
    color: '#000000'
  };
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
}