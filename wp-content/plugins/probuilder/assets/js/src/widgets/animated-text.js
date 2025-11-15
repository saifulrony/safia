export default // Widget renderer for "animated-text" (auto-generated)
function renderAnimatedText(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const animText = settings.text || 'Animated Text';
  const animType = settings.animation || 'typing';
  const animTextColor = settings.color || '#0073aa';
  const animTextSize = settings.size || 36;
  let animPreview = '';
  if (animType === 'typing') {
    animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;border-right:3px solid ${animTextColor}">${animText}</h2>`;
  } else if (animType === 'wave') {
    animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700">${animText}</h2>`;
  } else if (animType === 'glitch') {
    animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;text-shadow:2px 2px ${animTextColor}">${animText}</h2>`;
  } else if (animType === 'neon') {
    animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;text-shadow:0 0 10px ${animTextColor},0 0 20px ${animTextColor}">${animText}</h2>`;
  }
  return `<div style="background:#2d2d2d;padding:40px;border-radius:8px;text-align:center">
                        ${animPreview}
                        <p style="margin:15px 0 0;color:#999;font-size:12px">Animation: ${animType}</p>
                    </div>`;
}