export default // Widget renderer for "bar-chart" (auto-generated)
function renderBarChart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const barTitle = settings.chart_title || 'Sales by Category';
  const barShowTitle = settings.show_title !== 'no';
  const barHeight = settings.chart_height || 400;
  const barColorMode = settings.color_mode || 'single';
  const barColor = settings.bar_color || '#36A2EB';
  const barGradientColor = settings.gradient_color || '#9966FF';
  const barMultiColors = settings.multi_colors || '#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF, #FF9F40';
  const barData = settings.chart_data || "Electronics, 12500\nClothing, 9800\nHome & Garden, 7600\nSports, 6400\nBooks, 5200";
  const barRadius = settings.border_radius || 4;
  const barOrientation = settings.orientation || 'vertical';
  const barShowValues = settings.show_values_on_bars === 'yes';
  const barValueFormat = settings.value_format || 'number';

  // Parse data
  // Parse data
  const barLines = barData.split('\n').filter(line => line.trim());
  const barLabels = [];
  const barValues = [];
  barLines.forEach(line => {
    const parts = line.split(',').map(s => s.trim());
    if (parts.length >= 2) {
      barLabels.push(parts[0]);
      barValues.push(parseFloat(parts[1]) || 0);
    }
  });
  if (barValues.length === 0) {
    return '<div style="padding: 20px; text-align: center; color: #999;">Enter chart data to see preview</div>';
  }
  const barMaxValue = Math.max(...barValues);

  // Get colors based on mode
  // Get colors based on mode
  let barColors = [];
  if (barColorMode === 'multi') {
    const multiColorArray = barMultiColors.split(',').map(c => c.trim());
    barColors = barLabels.map((_, i) => multiColorArray[i % multiColorArray.length]);
  } else if (barColorMode === 'gradient') {
    // For preview, use gradient on each bar
    barColors = barLabels.map(() => `linear-gradient(180deg, ${barColor}, ${barGradientColor})`);
  } else {
    barColors = barLabels.map(() => barColor);
  }
  let barHTML = `<div style="padding: 20px; text-align: center;">`;
  if (barShowTitle && barTitle) {
    barHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${barTitle}</h3>`;
  }
  if (barOrientation === 'vertical') {
    barHTML += `<div style="display: flex; justify-content: center; align-items: flex-end; gap: 10px; height: ${barHeight}px; background: #f9f9f9; border-radius: 8px; padding: 40px 20px 40px;">`;
    barValues.forEach((value, i) => {
      const heightPercent = value / barMaxValue * 80;
      let formattedValue = value.toLocaleString();
      if (barValueFormat === 'currency') formattedValue = '$' + formattedValue;
      if (barValueFormat === 'percentage') formattedValue = formattedValue + '%';
      barHTML += `<div style="flex: 1; max-width: 80px; display: flex; flex-direction: column; align-items: center; gap: 8px;">`;
      if (barShowValues) {
        barHTML += `<span style="font-size: 11px; color: #666; font-weight: 600;">${formattedValue}</span>`;
      }
      barHTML += `<div style="width: 100%; height: ${heightPercent}%; background: ${barColors[i]}; border-radius: ${barRadius}px ${barRadius}px 0 0;"></div>`;
      barHTML += `<span style="font-size: 11px; color: #666; text-align: center; word-break: break-word;">${barLabels[i]}</span>`;
      barHTML += `</div>`;
    });
    barHTML += `</div>`;
  } else {
    barHTML += `<div style="display: flex; flex-direction: column; justify-content: center; gap: 15px; height: ${barHeight}px; background: #f9f9f9; border-radius: 8px; padding: 20px;">`;
    barValues.forEach((value, i) => {
      const widthPercent = value / barMaxValue * 85;
      let formattedValue = value.toLocaleString();
      if (barValueFormat === 'currency') formattedValue = '$' + formattedValue;
      if (barValueFormat === 'percentage') formattedValue = formattedValue + '%';
      barHTML += `<div style="display: flex; align-items: center; gap: 10px;">`;
      barHTML += `<span style="font-size: 12px; color: #666; min-width: 100px; text-align: right;">${barLabels[i]}</span>`;
      barHTML += `<div style="display: flex; align-items: center; width: ${widthPercent}%; height: 30px; background: ${barColors[i]}; border-radius: 0 ${barRadius}px ${barRadius}px 0; position: relative;">`;
      if (barShowValues) {
        barHTML += `<span style="position: absolute; right: -50px; font-size: 11px; color: #666; font-weight: 600; white-space: nowrap;">${formattedValue}</span>`;
      }
      barHTML += `</div>`;
      barHTML += `</div>`;
    });
    barHTML += `</div>`;
  }
  barHTML += `</div>`;
  return barHTML;
}