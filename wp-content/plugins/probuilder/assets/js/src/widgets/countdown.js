export default // Widget renderer for "countdown" (auto-generated)
function renderCountdown(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const countdownTarget = settings.target_date || '';
  const showDays = settings.show_days !== 'no';
  const showHours = settings.show_hours !== 'no';
  const showMinutes = settings.show_minutes !== 'no';
  const showSeconds = settings.show_seconds !== 'no';
  const showLabels = settings.show_labels !== 'no';
  const countdownLayout = settings.layout || 'boxes';
  const countdownAlign = settings.align || 'center';
  const digitSize = settings.digit_size || 48;
  const labelSize = settings.label_size || 14;
  const digitColor = settings.digit_color || '#ffffff';
  const labelColor = settings.label_color || '#ffffff';
  const boxBgColor = settings.box_bg_color || '#92003b';
  const borderRadius = settings.box_border_radius || 8;
  const showSeparator = settings.separator_show === 'yes';
  const separatorText = settings.separator_text || ':';
  const justifyMap = {
    'left': 'flex-start',
    'center': 'center',
    'right': 'flex-end'
  };
  let countdownHTML = `<div style="display: flex; justify-content: ${justifyMap[countdownAlign]}; align-items: center; gap: 15px; flex-wrap: wrap;">`;

  // Box styles based on layout
  // Box styles based on layout
  let boxStyle = '';
  if (countdownLayout === 'boxes') {
    boxStyle = `background: ${boxBgColor}; padding: 20px 15px; text-align: center; min-width: 90px; border-radius: ${borderRadius}px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);`;
  } else if (countdownLayout === 'circles') {
    const circleSize = Math.max(digitSize + 40, 100);
    boxStyle = `background: ${boxBgColor}; width: ${circleSize}px; height: ${circleSize}px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1);`;
  } else {
    boxStyle = `text-align: center;`;
  }
  const digitStyle = `font-size: ${digitSize}px; font-weight: bold; color: ${digitColor}; line-height: 1;`;
  const labelStyle = `font-size: ${labelSize}px; color: ${labelColor}; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;`;
  const separatorStyle = `font-size: ${digitSize}px; font-weight: bold; color: ${digitColor};`;
  let firstItem = true;

  // Days
  // Days
  if (showDays) {
    if (!firstItem && showSeparator && countdownLayout === 'inline') {
      countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
    }
    countdownHTML += `<div style="${boxStyle}">`;
    countdownHTML += `<div style="${digitStyle}">05</div>`;
    if (showLabels) countdownHTML += `<div style="${labelStyle}">DAYS</div>`;
    countdownHTML += `</div>`;
    firstItem = false;
  }

  // Hours
  // Hours
  if (showHours) {
    if (!firstItem && showSeparator && countdownLayout === 'inline') {
      countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
    }
    countdownHTML += `<div style="${boxStyle}">`;
    countdownHTML += `<div style="${digitStyle}">12</div>`;
    if (showLabels) countdownHTML += `<div style="${labelStyle}">HOURS</div>`;
    countdownHTML += `</div>`;
    firstItem = false;
  }

  // Minutes
  // Minutes
  if (showMinutes) {
    if (!firstItem && showSeparator && countdownLayout === 'inline') {
      countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
    }
    countdownHTML += `<div style="${boxStyle}">`;
    countdownHTML += `<div style="${digitStyle}">34</div>`;
    if (showLabels) countdownHTML += `<div style="${labelStyle}">MINUTES</div>`;
    countdownHTML += `</div>`;
    firstItem = false;
  }

  // Seconds
  // Seconds
  if (showSeconds) {
    if (!firstItem && showSeparator && countdownLayout === 'inline') {
      countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
    }
    countdownHTML += `<div style="${boxStyle}">`;
    countdownHTML += `<div style="${digitStyle}">56</div>`;
    if (showLabels) countdownHTML += `<div style="${labelStyle}">SECONDS</div>`;
    countdownHTML += `</div>`;
  }
  countdownHTML += `</div>`;
  return countdownHTML;
}