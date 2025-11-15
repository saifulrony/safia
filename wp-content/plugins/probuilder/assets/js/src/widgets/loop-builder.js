export default // Widget renderer for "loop-builder" (auto-generated)
function renderLoopBuilder(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="display:grid;grid-template-columns:repeat(${settings.columns || 3},1fr);gap:20px">
                        ${[1, 2, 3].map(i => `
                            <div style="border:1px solid #eee;border-radius:8px;overflow:hidden">
                                <div style="background:#f0f0f0;height:150px;display:flex;align-items:center;justify-content:center;color:#999">Post ${i}</div>
                                <div style="padding:15px">
                                    <h3 style="margin:0 0 10px;font-size:18px">Dynamic Post ${i}</h3>
                                    <p style="margin:0;color:#666;font-size:14px">Post excerpt will appear here...</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>`;
}