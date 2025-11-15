export default // Widget renderer for "newsletter" (auto-generated)
function renderNewsletter(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const newsTitle = settings.title || 'Subscribe to Our Newsletter';
  const newsDescription = settings.description || 'Get the latest updates and offers.';
  const newsPlaceholder = settings.placeholder || 'Enter your email';
  const newsButtonText = settings.button_text || 'Subscribe';
  const newsLayout = settings.layout || 'inline';
  const newsButtonColor = settings.button_color || '#92003b';
  let newsHTML = `<div style="padding: 40px; background: #f9f9f9; border-radius: 8px; text-align: center;">`;

  // Icon
  // Icon
  newsHTML += `<div style="font-size: 48px; color: ${newsButtonColor}; margin-bottom: 20px; opacity: 0.8;"><i class="fa fa-envelope-open-text"></i></div>`;

  // Title
  // Title
  newsHTML += `<h3 style="margin: 0 0 10px 0; font-size: 24px; color: #333; font-weight: 600;">${newsTitle}</h3>`;

  // Description
  // Description
  if (newsDescription) {
    newsHTML += `<p style="margin: 0 0 25px 0; color: #666; font-size: 15px;">${newsDescription}</p>`;
  }

  // Form
  // Form
  const formStyle = newsLayout === 'inline' ? 'display: flex; gap: 10px;' : 'display: flex; flex-direction: column; gap: 10px;';
  newsHTML += `<div style="${formStyle} max-width: 500px; margin: 0 auto;">`;
  newsHTML += `<input type="email" placeholder="${newsPlaceholder}" style="flex: 1; padding: 14px 20px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
  newsHTML += `<button type="button" style="background: ${newsButtonColor}; color: #fff; padding: 14px 35px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; white-space: nowrap;">${newsButtonText}</button>`;
  newsHTML += `</div>`;
  newsHTML += `</div>`;
  return newsHTML;
}