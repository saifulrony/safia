export default // Widget renderer for "post-navigation" (auto-generated)
function renderPostNavigation(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="display:flex;gap:20px">
                        <div style="flex:1;background:#f9f9f9;padding:20px;border-radius:8px">
                            <div style="color:#0073aa;font-size:12px;margin-bottom:5px">← Previous</div>
                            <h4 style="margin:0;font-size:16px">Previous Post Title</h4>
                        </div>
                        <div style="flex:1;background:#f9f9f9;padding:20px;border-radius:8px;text-align:right">
                            <div style="color:#0073aa;font-size:12px;margin-bottom:5px">Next →</div>
                            <h4 style="margin:0;font-size:16px">Next Post Title</h4>
                        </div>
                    </div>`;
}