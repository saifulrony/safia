export default // Widget renderer for "breadcrumbs" (auto-generated)
function renderBreadcrumbs(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<nav style="font-size:14px;color:#666">
                        <a href="#" style="color:#0073aa;text-decoration:none">Home</a>
                        <span style="color:#999;margin:0 8px">${settings.separator || '/'}</span>
                        <a href="#" style="color:#0073aa;text-decoration:none">Category</a>
                        <span style="color:#999;margin:0 8px">${settings.separator || '/'}</span>
                        <span style="color:#666">Current Page</span>
                    </nav>`;
}