export default // Widget renderer for "info-box" (auto-generated)
function renderInfoBox(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const infoIconType = settings.icon_type || 'number';
  const infoNumber = settings.number || '01';
  const infoIcon = settings.icon || 'fa fa-check-circle';
  const infoTitle = settings.title || 'Step One';
  const infoDescription = settings.description || 'This is a description of the first step.';
  const infoButtonText = settings.button_text || '';
  const infoLayout = settings.layout || 'horizontal';
  const infoIconStyle = settings.icon_style || 'circle';
  const infoIconSize = settings.icon_size || 70;
  const infoAccentColor = settings.accent_color || '#92003b';
  const infoBgColor = settings.bg_color || '#ffffff';
  const infoTitleColor = settings.title_color || '#333333';
  const infoDescColor = settings.description_color || '#666666';
  const infoBorderColor = settings.border_color || '#e5e5e5';
  const infoBorderRadius = settings.border_radius || 8;
  const infoBoxShadow = settings.box_shadow === 'yes';

  // Icon border radius based on style
  // Icon border radius based on style
  let infoIconBorderRadius = '50%'; // circle
  // circle
  if (infoIconStyle === 'square') infoIconBorderRadius = '0';else if (infoIconStyle === 'rounded') infoIconBorderRadius = '12px';
  const infoContainerStyle = `
                        padding: 25px;
                        background: ${infoBgColor};
                        border: 1px solid ${infoBorderColor};
                        border-radius: ${infoBorderRadius}px;
                        ${infoLayout === 'horizontal' ? 'display: flex; gap: 20px; align-items: flex-start;' : 'display: flex; flex-direction: column; align-items: center; text-align: center; gap: 20px;'}
                        ${infoBoxShadow ? 'box-shadow: 0 4px 15px rgba(0,0,0,0.1);' : ''}
                    `;
  const infoIconContainerStyle = `
                        flex-shrink: 0;
                        width: ${infoIconSize}px;
                        height: ${infoIconSize}px;
                        background: ${infoAccentColor};
                        color: #fff;
                        border-radius: ${infoIconBorderRadius};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: ${infoIconSize * 0.4}px;
                        font-weight: bold;
                    `;
  let infoBoxHTML = `<div style="${infoContainerStyle}">`;

  // Icon/Number
  // Icon/Number
  infoBoxHTML += `<div style="${infoIconContainerStyle}">`;
  if (infoIconType === 'icon') {
    infoBoxHTML += `<i class="${infoIcon}"></i>`;
  } else {
    infoBoxHTML += infoNumber;
  }
  infoBoxHTML += `</div>`;

  // Content
  // Content
  const infoContentStyle = `flex: 1; ${infoLayout === 'vertical' ? 'text-align: center;' : ''}`;
  infoBoxHTML += `<div style="${infoContentStyle}">`;
  infoBoxHTML += `<h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: ${infoTitleColor};">${infoTitle}</h3>`;
  if (infoDescription) {
    infoBoxHTML += `<p style="margin: 0 0 15px 0; color: ${infoDescColor}; line-height: 1.6; font-size: 14px;">${infoDescription}</p>`;
  }

  // Button
  // Button
  if (infoButtonText) {
    infoBoxHTML += `<a href="#" style="background: ${infoAccentColor}; color: #fff; padding: 10px 24px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: 600; font-size: 14px;">${infoButtonText}</a>`;
  }
  infoBoxHTML += `</div>`;
  infoBoxHTML += `</div>`;
  return infoBoxHTML;
}