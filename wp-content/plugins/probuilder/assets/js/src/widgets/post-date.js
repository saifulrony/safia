export default // Widget renderer for "post-date" (auto-generated)
function renderPostDate(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const dateFormat = settings.format || 'F j, Y';
  const dateShowIcon = settings.show_icon !== false;
  const dateColor = settings.color || '#666';
  return `<div style="color:${dateColor};font-size:14px;display:flex;align-items:center;gap:8px">
                        ${dateShowIcon ? '<i class="fa fa-calendar-alt"></i>' : ''}
                        <span>October 25, 2025</span>
                    </div>`;
}