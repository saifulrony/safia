export default // Widget renderer for "table-of-contents" (auto-generated)
function renderTableOfContents(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px">${settings.title || 'Table of Contents'}</h4>
                        <ol style="margin:0;padding-left:25px;line-height:2">
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 1</a></li>
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 2</a></li>
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 3</a></li>
                        </ol>
                    </div>`;
}