export default // Widget renderer for "sitemap" (auto-generated)
function renderSitemap(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="display:grid;grid-template-columns:repeat(${settings.columns || 3},1fr);gap:30px">
                        <div><h3 style="margin:0 0 15px">Pages</h3><ul style="list-style:none;padding:0;line-height:2"><li><a href="#" style="color:#0073aa">Home</a></li><li><a href="#" style="color:#0073aa">About</a></li><li><a href="#" style="color:#0073aa">Contact</a></li></ul></div>
                        <div><h3 style="margin:0 0 15px">Posts</h3><ul style="list-style:none;padding:0;line-height:2"><li><a href="#" style="color:#0073aa">Recent Post 1</a></li><li><a href="#" style="color:#0073aa">Recent Post 2</a></li><li><a href="#" style="color:#0073aa">Recent Post 3</a></li></ul></div>
                    </div>`;
}