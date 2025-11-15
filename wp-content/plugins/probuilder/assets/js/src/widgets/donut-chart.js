export default // Widget renderer for "donut-chart" (auto-generated)
function renderDonutChart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const donutTitle = settings.chart_title || 'Market Share';
  const donutShowTitle = settings.show_title !== 'no';
  const donutHeight = settings.chart_height || 400;
  const donutCenterText = settings.center_text || '';
  const donutData = settings.chart_data || "Product A, 35\nProduct B, 30\nProduct C, 20\nProduct D, 15";
  const donutColorScheme = settings.colors_scheme || 'vibrant';
  const donutCustomColors = settings.custom_colors || '#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF';
  const donutShowLegend = settings.show_legend !== 'no';
  const donutLegendPos = settings.legend_position || 'bottom';
  const donutCutout = settings.cutout_percentage || 50;

  // Parse data
  // Parse data
  const donutLines = donutData.split('\n').filter(line => line.trim());
  const donutLabels = [];
  const donutValues = [];
  donutLines.forEach(line => {
    const parts = line.split(',').map(s => s.trim());
    if (parts.length >= 2) {
      donutLabels.push(parts[0]);
      donutValues.push(parseFloat(parts[1]) || 0);
    }
  });

  // Get colors
  // Get colors
  const donutColorSchemes = {
    'vibrant': ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
    'pastel': ['#FFB3BA', '#BAFFC9', '#BAE1FF', '#FFFFBA', '#FFD4BA', '#E0BBE4'],
    'monochrome': ['#1a1a1a', '#333333', '#4d4d4d', '#666666', '#808080', '#999999']
  };
  const donutColors = donutColorScheme === 'custom' ? donutCustomColors.split(',').map(c => c.trim()) : donutColorSchemes[donutColorScheme] || donutColorSchemes['vibrant'];

  // Calculate total
  // Calculate total
  const donutTotal = donutValues.reduce((sum, val) => sum + val, 0);

  // Generate donut paths
  // Generate donut paths
  const donutStrokeWidth = 80 * (1 - donutCutout / 100);
  let donutCurrentAngle = -90;
  let donutSVGPaths = '';
  donutValues.forEach((value, i) => {
    const angle = value / donutTotal * 360;
    const endAngle = donutCurrentAngle + angle;
    const radius = 80 - donutStrokeWidth / 2;
    const startX = 100 + radius * Math.cos(donutCurrentAngle * Math.PI / 180);
    const startY = 100 + radius * Math.sin(donutCurrentAngle * Math.PI / 180);
    const endX = 100 + radius * Math.cos(endAngle * Math.PI / 180);
    const endY = 100 + radius * Math.sin(endAngle * Math.PI / 180);
    const largeArc = angle > 180 ? 1 : 0;
    const color = donutColors[i % donutColors.length];
    donutSVGPaths += `<path d="M ${startX} ${startY} A ${radius} ${radius} 0 ${largeArc} 1 ${endX} ${endY}" fill="none" stroke="${color}" stroke-width="${donutStrokeWidth}"/>`;
    donutCurrentAngle = endAngle;
  });
  let donutHTML = `<div style="padding: 20px; text-align: center;">`;
  if (donutShowTitle && donutTitle) {
    donutHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${donutTitle}</h3>`;
  }
  const donutLegendHTML = donutShowLegend ? `
                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin: 15px 0;">
                            ${donutLabels.map((label, i) => `
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 12px; height: 12px; background: ${donutColors[i % donutColors.length]}; border-radius: 2px;"></div>
                                    <span style="font-size: 13px; color: #666;">${label}</span>
                                </div>
                            `).join('')}
                        </div>
                    ` : '';
  if (donutLegendPos === 'top') donutHTML += donutLegendHTML;
  donutHTML += `<div style="position: relative; display: flex; justify-content: center; align-items: center; height: ${donutHeight}px; background: #f9f9f9; border-radius: 8px;">`;
  donutHTML += `<svg width="250" height="250" viewBox="0 0 200 200">${donutSVGPaths}</svg>`;
  if (donutCenterText) {
    donutHTML += `<div style="position: absolute; font-size: 24px; font-weight: 600;">${donutCenterText}</div>`;
  }
  donutHTML += `</div>`;
  if (donutLegendPos === 'bottom') donutHTML += donutLegendHTML;
  donutHTML += `</div>`;
  return donutHTML;
}