export default // Widget renderer for "pie-chart" (auto-generated)
function renderPieChart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const pieTitle = settings.chart_title || 'Sales Distribution';
  const pieShowTitle = settings.show_title !== 'no';
  const pieHeight = settings.chart_height || 400;
  const pieData = settings.chart_data || "Product A, 30\nProduct B, 25\nProduct C, 20\nProduct D, 15\nProduct E, 10";
  const pieColorScheme = settings.colors_scheme || 'vibrant';
  const pieCustomColors = settings.custom_colors || '#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF';
  const pieShowLegend = settings.show_legend !== 'no';
  const pieLegendPos = settings.legend_position || 'bottom';

  // Parse data
  // Parse data
  const pieLines = pieData.split('\n').filter(line => line.trim());
  const pieLabels = [];
  const pieValues = [];
  pieLines.forEach(line => {
    const parts = line.split(',').map(s => s.trim());
    if (parts.length >= 2) {
      pieLabels.push(parts[0]);
      pieValues.push(parseFloat(parts[1]) || 0);
    }
  });

  // Get colors
  // Get colors
  const pieColorSchemes = {
    'vibrant': ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
    'pastel': ['#FFB3BA', '#BAFFC9', '#BAE1FF', '#FFFFBA', '#FFD4BA', '#E0BBE4'],
    'monochrome': ['#1a1a1a', '#333333', '#4d4d4d', '#666666', '#808080', '#999999']
  };
  const pieColors = pieColorScheme === 'custom' ? pieCustomColors.split(',').map(c => c.trim()) : pieColorSchemes[pieColorScheme] || pieColorSchemes['vibrant'];

  // Calculate total and percentages
  // Calculate total and percentages
  const pieTotal = pieValues.reduce((sum, val) => sum + val, 0);

  // Generate SVG pie slices
  // Generate SVG pie slices
  let pieCurrentAngle = -90; // Start at top
  // Start at top
  let pieSVGPaths = '';
  pieValues.forEach((value, i) => {
    const percentage = value / pieTotal * 100;
    const angle = value / pieTotal * 360;
    const endAngle = pieCurrentAngle + angle;
    const startX = 100 + 80 * Math.cos(pieCurrentAngle * Math.PI / 180);
    const startY = 100 + 80 * Math.sin(pieCurrentAngle * Math.PI / 180);
    const endX = 100 + 80 * Math.cos(endAngle * Math.PI / 180);
    const endY = 100 + 80 * Math.sin(endAngle * Math.PI / 180);
    const largeArc = angle > 180 ? 1 : 0;
    const color = pieColors[i % pieColors.length];
    pieSVGPaths += `<path d="M 100 100 L ${startX} ${startY} A 80 80 0 ${largeArc} 1 ${endX} ${endY} Z" fill="${color}" stroke="#fff" stroke-width="2"/>`;
    pieCurrentAngle = endAngle;
  });
  let pieHTML = `<div style="padding: 20px; text-align: center;">`;
  if (pieShowTitle && pieTitle) {
    pieHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${pieTitle}</h3>`;
  }
  const legendHTML = pieShowLegend ? `
                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin: 15px 0;">
                            ${pieLabels.map((label, i) => `
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 12px; height: 12px; background: ${pieColors[i % pieColors.length]}; border-radius: 2px;"></div>
                                    <span style="font-size: 13px; color: #666;">${label}</span>
                                </div>
                            `).join('')}
                        </div>
                    ` : '';
  if (pieLegendPos === 'top') pieHTML += legendHTML;
  pieHTML += `<div style="display: flex; justify-content: center; align-items: center; height: ${pieHeight}px; background: #f9f9f9; border-radius: 8px;">`;
  pieHTML += `<svg width="250" height="250" viewBox="0 0 200 200">${pieSVGPaths}</svg>`;
  pieHTML += `</div>`;
  if (pieLegendPos === 'bottom') pieHTML += legendHTML;
  pieHTML += `</div>`;
  return pieHTML;
}