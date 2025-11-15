export default // Widget renderer for "post-excerpt" (auto-generated)
function renderPostExcerpt(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const excerptLength = settings.length || 55;
  const excerptShowMore = settings.show_more !== false;
  const excerptMoreText = settings.more_text || 'Read More';
  return `<div style="color:#666;line-height:1.6;font-size:16px">
                        <p style="margin:0">This is a sample post excerpt that will display the first ${excerptLength} words of the post content. It provides a preview of the article...</p>
                        ${excerptShowMore ? `<a href="#" style="color:#0073aa;text-decoration:none;font-weight:600;margin-top:10px;display:inline-block">${excerptMoreText} →</a>` : ''}
                    </div>`;
}