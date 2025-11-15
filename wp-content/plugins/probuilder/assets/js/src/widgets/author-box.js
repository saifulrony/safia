export default // Widget renderer for "author-box" (auto-generated)
function renderAuthorBox(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="background:#f9f9f9;border:1px solid #eee;padding:30px;border-radius:8px;display:flex;gap:20px;align-items:center">
                        <div style="width:80px;height:80px;border-radius:50%;background:#ddd;flex-shrink:0"></div>
                        <div style="flex:1">
                            <h3 style="margin:0 0 10px;font-size:24px">Author Name</h3>
                            <p style="margin:0 0 15px;color:#666">Author biography will appear here...</p>
                            <a href="#" style="color:#0073aa;text-decoration:none;font-weight:600">View All Posts →</a>
                        </div>
                    </div>`;
}