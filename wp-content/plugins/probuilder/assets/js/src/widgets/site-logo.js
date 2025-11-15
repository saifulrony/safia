export default // Widget renderer for "site-logo" (auto-generated)
function renderSiteLogo(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const siteLogoWidth = settings.width || 150;
  const siteLogoImage = settings.logo || 'https://via.placeholder.com/300x100/93003c/ffffff?text=Site+Logo';
  const siteLogoLink = settings.link !== false;
  const siteLogoHTML = `<img src="${siteLogoImage}" alt="Site Logo" style="width:${siteLogoWidth}px;height:auto;display:block">`;
  return siteLogoLink ? `<a href="#">${siteLogoHTML}</a>` : siteLogoHTML;
}