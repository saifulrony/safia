export default // Widget renderer for "archive-title" (auto-generated)
function renderArchiveTitle(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const archiveTag = settings.tag || 'h1';
  const archiveColor = settings.color || '#333';
  return `<${archiveTag} style="color:${archiveColor};margin:0;font-size:36px;font-weight:700">Archive: Category Name</${archiveTag}>`;
}