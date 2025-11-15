export default // Widget renderer for "counter" (auto-generated)
function renderCounter(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const counterEnd = settings.ending_number || 1000;
  const counterPrefix = settings.prefix || '';
  const counterSuffix = settings.suffix || '+';
  const counterTitle = settings.title || 'Happy Clients';
  const counterNumberColor = settings.number_color || '#0073aa';
  const counterTitleColor = settings.title_color || '#333333';
  const counterAlign = settings.text_align || 'center';
  let counterHTML = `<div style="text-align: ${counterAlign}; padding: 20px;">`;
  counterHTML += `<div style="font-size: 48px; font-weight: bold; color: ${counterNumberColor}; margin-bottom: 10px;">`;
  counterHTML += `${counterPrefix}${counterEnd}${counterSuffix}`;
  counterHTML += `</div>`;
  counterHTML += `<div style="font-size: 18px; color: ${counterTitleColor};">${counterTitle}</div>`;
  counterHTML += `</div>`;
  return counterHTML;
}