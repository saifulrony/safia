export default // Widget renderer for "anchor" (auto-generated)
function renderAnchor(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const anchorId = settings.anchor_id || 'section-1';
  return `<div style="padding: 20px; background: #f0f8ff; border: 2px dashed #0073aa; border-radius: 8px; text-align: center;">
                        <i class="fa fa-anchor" style="font-size: 32px; color: #0073aa; margin-bottom: 10px; display: block;"></i>
                        <h4 style="margin: 0 0 5px; color: #0073aa;">Anchor Point</h4>
                        <p style="margin: 0; color: #666; font-size: 13px;">ID: <strong>${anchorId}</strong></p>
                        <p style="margin: 5px 0 0; color: #999; font-size: 11px;">Use this ID in links to scroll to this section</p>
                    </div>`;
}