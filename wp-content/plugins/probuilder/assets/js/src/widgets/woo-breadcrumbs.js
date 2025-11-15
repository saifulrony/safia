export default // Widget renderer for "woo-breadcrumbs" (auto-generated)
function renderWooBreadcrumbs(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const bcSeparator = settings.separator || '/';
  return `<div style="padding: 15px 20px; background: #f5f5f5; border-radius: 4px;">
                        <div style="font-size: 14px; color: #666;">
                            <a href="#" style="color: #0073aa; text-decoration: none;">Home</a>
                            <span style="margin: 0 8px; color: #999;">${bcSeparator}</span>
                            <a href="#" style="color: #0073aa; text-decoration: none;">Shop</a>
                            <span style="margin: 0 8px; color: #999;">${bcSeparator}</span>
                            <span style="color: #333;">Product Name</span>
                        </div>
                        <p style="margin: 10px 0 0; color: #999; font-size: 11px; text-align: center;">
                            <i class="fa fa-route"></i> WooCommerce Breadcrumbs
                        </p>
                    </div>`;
}