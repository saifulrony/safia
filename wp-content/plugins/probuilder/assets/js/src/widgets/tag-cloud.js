export default // Widget renderer for "tag-cloud" (auto-generated)
function renderTagCloud(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const tagColor = settings.color || '#0073aa';
  const tags = [{
    name: 'WordPress',
    size: 20
  }, {
    name: 'Design',
    size: 16
  }, {
    name: 'Development',
    size: 18
  }, {
    name: 'Tutorial',
    size: 14
  }, {
    name: 'Guide',
    size: 16
  }, {
    name: 'Tips',
    size: 15
  }, {
    name: 'SEO',
    size: 17
  }, {
    name: 'Marketing',
    size: 14
  }, {
    name: 'Business',
    size: 19
  }];
  return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Popular Tags</h4>
                        <div style="display:flex;flex-wrap:wrap;gap:10px">
                            ${tags.map(tag => `<a href="#" style="color:${tagColor};font-size:${tag.size}px;text-decoration:none;transition:opacity 0.3s" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">${tag.name}</a>`).join('')}
                        </div>
                    </div>`;
}