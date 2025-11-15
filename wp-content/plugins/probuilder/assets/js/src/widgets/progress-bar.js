export default // Widget renderer for "progress-bar" (auto-generated)
function renderProgressBar(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const progTitle = settings.title || 'My Skill';
  const progPercentage = settings.percentage || 75;
  const progShowPercentage = settings.show_percentage !== 'no';
  const progInnerText = settings.inner_text || '';
  const progBarStyle = settings.bar_style || 'solid';
  const progBarColor = settings.bar_color || '#92003b';
  const progBarGradient = settings.bar_gradient || 'linear-gradient(90deg, #92003b 0%, #c44569 100%)';
  const progBgColor = settings.bg_color || '#e5e7eb';
  const progHeight = settings.height || 30;
  const progBorderRadius = settings.border_radius || 15;
  const progTitleColor = settings.title_color || '#333333';
  const progPercentageColor = settings.percentage_color || '#333333';
  const progInnerTextColor = settings.inner_text_color || '#ffffff';
  let progressHTML = `<div style="margin: 15px 0;">`;

  // Title row
  // Title row
  progressHTML += `<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">`;
  progressHTML += `<span style="font-weight: 600; font-size: 15px; color: ${progTitleColor};">${progTitle}</span>`;
  if (progShowPercentage) {
    progressHTML += `<span style="font-weight: 700; font-size: 15px; color: ${progPercentageColor};">${progPercentage}%</span>`;
  }
  progressHTML += `</div>`;

  // Progress bar
  // Progress bar
  progressHTML += `<div style="position: relative; background: ${progBgColor}; height: ${progHeight}px; border-radius: ${progBorderRadius}px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">`;
  let barFillStyle = `height: 100%; width: ${progPercentage}%; display: flex; align-items: center; padding: 0 15px; box-sizing: border-box;`;
  if (progBarStyle === 'solid') {
    barFillStyle += ` background: ${progBarColor};`;
  } else if (progBarStyle === 'gradient') {
    barFillStyle += ` background: ${progBarGradient};`;
  } else if (progBarStyle === 'striped' || progBarStyle === 'animated') {
    barFillStyle += ` background: ${progBarColor};`;
    barFillStyle += ` background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);`;
    barFillStyle += ` background-size: 20px 20px;`;
  }
  progressHTML += `<div style="${barFillStyle}">`;
  if (progInnerText) {
    progressHTML += `<span style="font-size: 13px; font-weight: 600; color: ${progInnerTextColor}; white-space: nowrap;">${progInnerText}</span>`;
  }
  progressHTML += `</div>`;
  progressHTML += `</div>`;
  progressHTML += `</div>`;
  return progressHTML;
}