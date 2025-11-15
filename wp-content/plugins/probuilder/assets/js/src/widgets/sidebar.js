export default // Widget renderer for "sidebar" (auto-generated)
function renderSidebar(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const sidebarName = settings.sidebar || 'sidebar-1';
  return `<div style="padding: 20px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px;">
                        <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-left: 4px solid #92003b;">
                            <h4 style="margin: 0 0 5px; font-size: 16px;">Widget Area</h4>
                            <p style="margin: 0; font-size: 12px; color: #666;">Search Widget</p>
                        </div>
                        <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-left: 4px solid #0073aa;">
                            <h4 style="margin: 0 0 5px; font-size: 16px;">Recent Posts</h4>
                            <p style="margin: 0; font-size: 12px; color: #666;">Latest blog entries</p>
                        </div>
                        <div style="padding: 15px; background: #fff; border-left: 4px solid #46b450;">
                            <h4 style="margin: 0 0 5px; font-size: 16px;">Categories</h4>
                            <p style="margin: 0; font-size: 12px; color: #666;">Post categories</p>
                        </div>
                        <p style="margin-top: 15px; text-align: center; color: #666; font-size: 11px;">
                            <i class="fa fa-columns"></i> WordPress Sidebar: ${sidebarName}
                        </p>
                    </div>`;
}