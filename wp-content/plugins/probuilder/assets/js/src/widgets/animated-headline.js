export default // Widget renderer for "animated-headline" (auto-generated)
function renderAnimatedHeadline(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const ahTitle = settings.before_text || 'We Are';
  const ahWords = settings.animated_text || 'Creative\nAwesome\nProfessional';
  const ahStyle = settings.animation_type || 'typing';
  const firstWord = ahWords.split('\n')[0];
  return `<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; text-align: center;">
                        <h2 style="margin: 0; color: #fff; font-size: 36px; font-weight: 700;">
                            ${ahTitle} <span style="color: #ffd700;">${firstWord}</span>
                        </h2>
                        <p style="margin: 10px 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">
                            <i class="fa fa-magic"></i> Animation: ${ahStyle}
                        </p>
                    </div>`;
}