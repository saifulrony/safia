export default // Widget renderer for "icon" (auto-generated)
function renderIcon(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const iconWidgetSize = settings.size || 50;
  const iconWidgetColor = settings.color || '#0073aa';
  const iconWidgetAlign = settings.align || 'center';
  const iconWidgetClass = settings.icon || 'fa fa-star';
  return `<div style="text-align:${iconWidgetAlign}"><i class="${iconWidgetClass}" style="font-size:${iconWidgetSize}px;color:${iconWidgetColor}"></i></div>`;
}