export default // Widget renderer for "reading-progress" (auto-generated)
function renderReadingProgress(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const progressPosition = settings.position || 'top';
  const progressHeight = settings.height || 4;
  const progressColor = settings.color || '#0073aa';
  const progressBg = settings.background || '#eeeeee';
  return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:${progressBg};height:${progressHeight}px;border-radius:${progressHeight}px;overflow:hidden;margin-bottom:15px">
                            <div style="width:60%;height:100%;background:${progressColor};transition:width 0.3s"></div>
                        </div>
                        <p style="margin:0;color:#666;font-size:14px">
                            <i class="fa fa-scroll" style="color:${progressColor};margin-right:8px"></i>
                            Reading Progress Bar (Fixed ${progressPosition === 'top' ? 'Top' : 'Top'})
                        </p>
                        <small style="color:#999">Scrolls with page progress</small>
                    </div>`;
}