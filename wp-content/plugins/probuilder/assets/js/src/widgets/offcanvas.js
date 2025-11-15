export default // Widget renderer for "offcanvas" (auto-generated)
function renderOffcanvas(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const offcanvasPosition = settings.position || 'right';
  const offcanvasTrigger = settings.trigger_text || '☰ Menu';
  const offcanvasWidth = settings.panel_width || 300;
  const offcanvasBg = settings.panel_bg || '#ffffff';
  return `<div style="padding:30px;background:#f5f5f5;border-radius:8px">
                        <button style="background:#0073aa;color:#fff;border:none;padding:12px 24px;border-radius:4px;cursor:pointer;font-weight:600;font-size:16px">
                            ${offcanvasTrigger}
                        </button>
                        <div style="margin-top:20px;padding:20px;background:${offcanvasBg};border:2px solid #ddd;border-radius:8px;position:relative">
                            <button style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:24px;cursor:pointer;color:#999">×</button>
                            <h4 style="margin:0 0 15px;font-size:18px">Offcanvas Panel Preview</h4>
                            <p style="margin:0;color:#666;font-size:14px;line-height:1.6">Panel slides in from ${offcanvasPosition}</p>
                            <p style="margin:10px 0 0;color:#999;font-size:12px">Width: ${offcanvasWidth}px</p>
                        </div>
                    </div>`;
}