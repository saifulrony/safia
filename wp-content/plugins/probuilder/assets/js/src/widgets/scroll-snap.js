export default // Widget renderer for "scroll-snap" (auto-generated)
function renderScrollSnap(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const snapSections = settings.sections || [{
    title: 'Section 1',
    content: 'Content 1',
    bg: '#f5f5f5'
  }, {
    title: 'Section 2',
    content: 'Content 2',
    bg: '#e5e5e5'
  }];
  return `<div style="border:2px dashed #ddd;border-radius:8px;padding:20px;background:#fafafa">
                        <div style="text-align:center;margin-bottom:20px">
                            <i class="fa fa-layer-group" style="font-size:32px;color:#93003c;margin-bottom:10px"></i>
                            <h4 style="margin:0 0 5px;font-size:18px">Scroll Snap Container</h4>
                            <small style="color:#999">${snapSections.length} full-height sections</small>
                        </div>
                        ${snapSections.map((section, i) => `
                            <div style="background:${section.bg || '#f5f5f5'};padding:20px;border-radius:6px;margin-bottom:10px;border-left:4px solid #0073aa">
                                <h5 style="margin:0 0 8px;font-size:16px">${section.title || 'Section ' + (i + 1)}</h5>
                                <p style="margin:0;color:#666;font-size:14px">${section.content || 'Section content'}</p>
                            </div>
                        `).join('')}
                    </div>`;
}