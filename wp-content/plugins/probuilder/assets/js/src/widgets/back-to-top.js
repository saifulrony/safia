export default // Widget renderer for "back-to-top" (auto-generated)
function renderBackToTop(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const backPosition = settings.position || 'bottom-right';
  const backSize = settings.size || 50;
  const backButtonColor = settings.button_color || '#0073aa';
  const backIconColor = settings.icon_color || '#ffffff';
  const posStyle = backPosition === 'bottom-right' ? 'bottom:20px;right:20px' : backPosition === 'bottom-left' ? 'bottom:20px;left:20px' : backPosition === 'top-right' ? 'top:20px;right:20px' : 'top:20px;left:20px';
  return `<div style="background:#f5f5f5;padding:40px;border-radius:8px;position:relative;min-height:200px">
                        <p style="margin:0 0 100px;color:#666;text-align:center">Scroll down to see button...</p>
                        <div style="position:absolute;${posStyle};width:${backSize}px;height:${backSize}px;background:${backButtonColor};color:${backIconColor};border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,0.2);cursor:pointer;font-size:24px;font-weight:bold">↑</div>
                        <p style="margin:0;color:#999;font-size:12px;text-align:center">Back to Top Button (${backPosition})</p>
                    </div>`;
}