export default // Widget renderer for "icon-box" (auto-generated)
function renderIconBox(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const iconAlign = settings.alignment || settings.text_align || 'center';
  const iconSize = settings.icon_size || '48px';
  const iconColor = settings.icon_color || '#92003b';
  const iconTitle = settings.title || 'Icon Box Title';
  const iconTitleSize = settings.title_size || '18px';
  const iconDesc = settings.description || 'Description goes here';
  const iconDescSize = settings.description_size || '14px';
  const iconBg = settings.background_color || 'transparent';
  const iconPadding = settings.padding || '30px';
  const iconBorderRadius = settings.border_radius || '8px';
  const iconBoxShadow = settings.box_shadow === 'yes' ? '0 4px 12px rgba(0,0,0,0.1)' : 'none';
  return `
                        <div style="text-align: ${iconAlign}; padding: ${iconPadding}; background: ${iconBg}; border-radius: ${iconBorderRadius}; box-shadow: ${iconBoxShadow}; transition: all 0.3s;">
                            <div style="width: ${iconSize}; height: ${iconSize}; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: ${iconColor}15; border-radius: 50%;">
                                <i class="${settings.icon || 'fa fa-star'}" style="font-size: ${iconSize}; color: ${iconColor};"></i>
                            </div>
                            <h3 style="margin: 0 0 10px 0; font-size: ${iconTitleSize}; font-weight: 600; color: #1f2937;">${iconTitle}</h3>
                            <p style="margin: 0; color: #6b7280; font-size: ${iconDescSize}; line-height: 1.6;">${iconDesc}</p>
                        </div>
                    `;
}