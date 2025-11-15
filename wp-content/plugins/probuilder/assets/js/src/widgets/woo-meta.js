export default // Widget renderer for "woo-meta" (auto-generated)
function renderWooMeta(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const metaShowSku = settings.show_sku !== 'no';
  const metaShowCategory = settings.show_category !== 'no';
  const metaShowTags = settings.show_tags !== 'no';
  return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        ${metaShowSku ? '<div style="margin-bottom: 10px; font-size: 14px;"><strong>SKU:</strong> <span style="color: #666;">WP-001</span></div>' : ''}
                        ${metaShowCategory ? '<div style="margin-bottom: 10px; font-size: 14px;"><strong>Category:</strong> <a href="#" style="color: #0073aa;">Electronics</a></div>' : ''}
                        ${metaShowTags ? '<div style="font-size: 14px;"><strong>Tags:</strong> <a href="#" style="color: #0073aa;">Featured</a>, <a href="#" style="color: #0073aa;">New</a></div>' : ''}
                        <p style="margin-top: 15px; color: #999; font-size: 11px; text-align: center;">
                            <i class="fa fa-info-circle"></i> Product Meta Information
                        </p>
                    </div>`;
}