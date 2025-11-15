export default // Widget renderer for "line-chart" (auto-generated)
function renderLineChart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const lineTitle = settings.chart_title || 'Monthly Revenue';
  const lineShowTitle = settings.show_title !== 'no';
  const lineHeight = settings.chart_height || 400;
  const lineColor = settings.line_color || '#36A2EB';
  const lineData = settings.chart_data || "Jan, 4500\nFeb, 5200\nMar, 6100\nApr, 5800\nMay, 7200\nJun, 8500";
  const lineWidth = settings.line_width || 3;
  const showPoints = settings.show_points !== 'no';
  const fillArea = settings.fill_area === 'yes';
  const fillColor = settings.fill_color || 'rgba(54, 162, 235, 0.2)';

  // Parse data
  // Parse data
  const lineLines = lineData.split('\n').filter(line => line.trim());
  const lineLabels = [];
  const lineValues = [];
  lineLines.forEach(line => {
    const parts = line.split(',').map(s => s.trim());
    if (parts.length >= 2) {
      lineLabels.push(parts[0]);
      lineValues.push(parseFloat(parts[1]) || 0);
    }
  });

  // Calculate points
  // Calculate points
  const lineMaxValue = Math.max(...lineValues);
  const lineMinValue = Math.min(...lineValues);
  const lineRange = lineMaxValue - lineMinValue || 1;
  const svgWidth = 400;
  const svgHeight = 200;
  const padding = 30;
  const plotWidth = svgWidth - 2 * padding;
  const plotHeight = svgHeight - 2 * padding;
  let linePoints = lineValues.map((value, i) => {
    const x = padding + i / (lineValues.length - 1 || 1) * plotWidth;
    const y = padding + plotHeight - (value - lineMinValue) / lineRange * plotHeight;
    return {
      x,
      y
    };
  });
  const linePolylinePoints = linePoints.map(p => `${p.x},${p.y}`).join(' ');
  let lineHTML = `<div style="padding: 20px; text-align: center;">`;
  if (lineShowTitle && lineTitle) {
    lineHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${lineTitle}</h3>`;
  }
  lineHTML += `<div style="display: flex; justify-content: center; align-items: center; height: ${lineHeight}px; background: #f9f9f9; border-radius: 8px; padding: 20px;">`;
  lineHTML += `<svg width="100%" height="100%" viewBox="0 0 ${svgWidth} ${svgHeight + 40}">`;

  // Fill area if enabled
  // Fill area if enabled
  if (fillArea && linePoints.length > 0) {
    const areaPoints = `${padding},${padding + plotHeight} ${linePolylinePoints} ${linePoints[linePoints.length - 1].x},${padding + plotHeight}`;
    lineHTML += `<polygon points="${areaPoints}" fill="${fillColor}" stroke="none"/>`;
  }

  // Draw line
  // Draw line
  lineHTML += `<polyline points="${linePolylinePoints}" fill="none" stroke="${lineColor}" stroke-width="${lineWidth}"/>`;

  // Draw points if enabled
  // Draw points if enabled
  if (showPoints) {
    linePoints.forEach(p => {
      lineHTML += `<circle cx="${p.x}" cy="${p.y}" r="5" fill="${lineColor}"/>`;
    });
  }

  // Draw labels
  // Draw labels
  lineLabels.forEach((label, i) => {
    const x = padding + i / (lineLabels.length - 1 || 1) * plotWidth;
    lineHTML += `<text x="${x}" y="${svgHeight}" text-anchor="middle" font-size="11" fill="#666">${label}</text>`;
  });
  lineHTML += `</svg>`;
  lineHTML += `</div></div>`;
  return lineHTML;
}