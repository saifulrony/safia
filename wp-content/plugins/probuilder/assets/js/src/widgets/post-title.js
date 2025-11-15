export default // Widget renderer for "post-title" (auto-generated)
function renderPostTitle(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const postTitleTag = settings.tag || 'h1';
  const postTitleColor = settings.color || '#333';
  const postTitleSize = settings.size || 36;
  return `<${postTitleTag} style="color:${postTitleColor};font-size:${postTitleSize}px;margin:0;font-weight:700">Post Title Will Appear Here</${postTitleTag}>`;
}