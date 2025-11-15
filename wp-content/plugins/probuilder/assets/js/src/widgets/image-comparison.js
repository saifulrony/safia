export default // Widget renderer for "image-comparison" (auto-generated)
function renderImageComparison(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const beforeImage = settings.before_image || 'https://via.placeholder.com/800x600/666/fff?text=Before';
  const afterImage = settings.after_image || 'https://via.placeholder.com/800x600/0073aa/fff?text=After';
  const comparisonPosition = settings.position || 50;
  return `<div style="position:relative;overflow:hidden;border-radius:8px;background:#f5f5f5">
                        <div style="position:relative;height:400px;background:url('${beforeImage}') center/cover">
                            <div style="position:absolute;top:0;left:0;height:100%;width:${comparisonPosition}%;overflow:hidden;background:url('${afterImage}') center/cover"></div>
                            <div style="position:absolute;top:0;left:${comparisonPosition}%;width:4px;height:100%;background:#fff;box-shadow:0 0 10px rgba(0,0,0,0.5)">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:40px;height:40px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,0.3)">
                                    <i class="fa fa-arrows-alt-h" style="color:#333"></i>
                                </div>
                            </div>
                            <div style="position:absolute;top:20px;left:20px;background:rgba(0,0,0,0.7);color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;font-weight:600">BEFORE</div>
                            <div style="position:absolute;top:20px;right:20px;background:rgba(0,115,170,0.9);color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;font-weight:600">AFTER</div>
                        </div>
                        <p style="text-align:center;margin:15px 0 0;color:#666;font-size:12px">
                            <i class="fa fa-arrows-alt-h"></i> Drag slider to compare
                        </p>
                    </div>`;
}