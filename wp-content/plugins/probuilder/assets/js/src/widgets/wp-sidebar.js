export default // Widget renderer for "wp-sidebar" (auto-generated)
function renderWpSidebar(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const sidebarId = settings.sidebar_id || '';
  const sidebarBgColor = settings.bg_color || '';
  const sidebarPadding = settings.padding || {
    top: 20,
    right: 20,
    bottom: 20,
    left: 20
  };
  const sidebarMargin = settings.margin || {
    top: 0,
    right: 0,
    bottom: 20,
    left: 0
  };
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
}