export default // Widget renderer for "call-to-action" (auto-generated)
function renderCallToAction(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const ctaTitle = settings.title || 'Ready to Get Started?';
  const ctaDescription = settings.description || 'Join thousands of satisfied customers today!';
  const ctaButtonText = settings.button_text || 'Get Started Now';
  const ctaBgColor = settings.bg_color || '#92003b';
  const ctaTextColor = settings.text_color || '#ffffff';
  const ctaTitleColor = settings.title_color || ctaTextColor;
  const ctaTitleSize = settings.title_size || '36px';
  const ctaDescColor = settings.description_color || ctaTextColor;
  const ctaDescSize = settings.description_size || '18px';
  const ctaBtnBg = settings.button_bg_color || '#ffffff';
  const ctaBtnText = settings.button_text_color || ctaBgColor;
  const ctaAlign = settings.alignment || 'center';
  const ctaMinHeight = settings._min_height || 'auto';
  const ctaPadding = settings._padding || '60px 40px';

  // Background handling
  // Background handling
  const ctaBgType = settings._background_type || 'color';
  const ctaBgImage = settings._background_image || '';
  const ctaBgOverlay = settings._background_overlay || 'rgba(0,0,0,0.3)';
  let ctaBgStyle = '';
  if (ctaBgType === 'image' && ctaBgImage) {
    ctaBgStyle = `background-image: url('${ctaBgImage}'); background-size: cover; background-position: center;`;
  } else {
    ctaBgStyle = `background: ${ctaBgColor};`;
  }
  let ctaHTML = `<div style="${ctaBgStyle} color: ${ctaTextColor}; padding: ${ctaPadding}; text-align: ${ctaAlign}; border-radius: 8px; position: relative; overflow: hidden; min-height: ${ctaMinHeight}; display: flex; align-items: center; justify-content: ${ctaAlign};">`;

  // Background overlay (for images)
  // Background overlay (for images)
  if (ctaBgType === 'image' && ctaBgImage) {
    ctaHTML += `<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: ${ctaBgOverlay}; z-index: 0;"></div>`;
  }

  // Content
  // Content
  ctaHTML += `<div style="position: relative; z-index: 1; max-width: 600px;">`;
  ctaHTML += `<h2 style="margin: 0 0 15px 0; font-size: ${ctaTitleSize}; color: ${ctaTitleColor}; font-weight: 700; line-height: 1.2;">${ctaTitle}</h2>`;
  if (ctaDescription) {
    ctaHTML += `<p style="margin: 0 0 30px 0; font-size: ${ctaDescSize}; color: ${ctaDescColor}; opacity: 0.95; line-height: 1.6;">${ctaDescription}</p>`;
  }
  if (ctaButtonText) {
    ctaHTML += `<a href="#" style="background: ${ctaBtnBg}; color: ${ctaBtnText}; padding: 15px 40px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 16px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.25)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';">${ctaButtonText}</a>`;
  }
  ctaHTML += `</div>`;
  ctaHTML += `</div>`;
  return ctaHTML;
}