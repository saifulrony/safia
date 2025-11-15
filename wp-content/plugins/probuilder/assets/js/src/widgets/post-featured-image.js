export default // Widget renderer for "post-featured-image" (auto-generated)
function renderPostFeaturedImage(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const featuredImageSize = settings.size || 'full';
  const featuredBorderRadius = settings.border_radius || 0;
  const featuredLink = settings.link !== false;
  const featuredImageHTML = `<img src="https://via.placeholder.com/800x600/93003c/ffffff?text=Featured+Image" alt="Featured Image" style="width:100%;height:auto;border-radius:${featuredBorderRadius}px;display:block">`;
  return featuredLink ? `<a href="#">${featuredImageHTML}</a>` : featuredImageHTML;
}