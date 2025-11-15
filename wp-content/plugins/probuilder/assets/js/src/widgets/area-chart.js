export default // Widget renderer for "area-chart" (auto-generated)
function renderAreaChart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const areaTitle = settings.chart_title || 'Website Traffic';
  const areaShowTitle = settings.show_title !== 'no';
  const areaHeight = settings.chart_height || 400;
  const areaLineColor = settings.line_color || '#4BC0C0';
  const areaData = settings.chart_data || "Week 1, 2400\nWeek 2, 3200\nWeek 3, 2800\nWeek 4, 4100\nWeek 5, 3900\nWeek 6, 5200";
  const areaLineWidth = settings.line_width || 3;
  const areaShowPoints = settings.show_points !== 'no';
  const areaFillOpacity = (settings.fill_opacity || 40) / 100;

  // Parse data
  // Parse data
  const areaLines = areaData.split('\n').filter(line => line.trim());
  const areaLabels = [];
  const areaValues = [];
  areaLines.forEach(line => {
    const parts = line.split(',').map(s => s.trim());
    if (parts.length >= 2) {
      areaLabels.push(parts[0]);
      areaValues.push(parseFloat(parts[1]) || 0);
    }
  });

  // Calculate points
  // Calculate points
  const areaMaxValue = Math.max(...areaValues);
  const areaMinValue = Math.min(...areaValues);
  const areaRange = areaMaxValue - areaMinValue || 1;
  const areaSvgWidth = 400;
  const areaSvgHeight = 200;
  const areaPadding = 30;
  const areaPlotWidth = areaSvgWidth - 2 * areaPadding;
  const areaPlotHeight = areaSvgHeight - 2 * areaPadding;
  let areaPoints = areaValues.map((value, i) => {
    const x = areaPadding + i / (areaValues.length - 1 || 1) * areaPlotWidth;
    const y = areaPadding + areaPlotHeight - (value - areaMinValue) / areaRange * areaPlotHeight;
    return {
      x,
      y
    };
  });
  const areaPolylinePoints = areaPoints.map(p => `${p.x},${p.y}`).join(' ');
  const areaPolygonPoints = `${areaPadding},${areaPadding + areaPlotHeight} ${areaPolylinePoints} ${areaPoints[areaPoints.length - 1].x},${areaPadding + areaPlotHeight}`;
  const gradId = 'areaGrad' + Date.now();
  let areaHTML = `<div style="padding: 20px; text-align: center;">`;
  if (areaShowTitle && areaTitle) {
    areaHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${areaTitle}</h3>`;
  }
  areaHTML += `<div style="display: flex; justify-content: center; align-items: center; height: ${areaHeight}px; background: #f9f9f9; border-radius: 8px; padding: 20px;">`;
  areaHTML += `<svg width="100%" height="100%" viewBox="0 0 ${areaSvgWidth} ${areaSvgHeight + 40}">`;

  // Gradient definition
  // Gradient definition
  areaHTML += `<defs><linearGradient id="${gradId}" x1="0%" y1="0%" x2="0%" y2="100%">`;
  areaHTML += `<stop offset="0%" style="stop-color:${areaLineColor};stop-opacity:${areaFillOpacity}" />`;
  areaHTML += `<stop offset="100%" style="stop-color:${areaLineColor};stop-opacity:0.05" />`;
  areaHTML += `</linearGradient></defs>`;

  // Fill area
  // Fill area
  areaHTML += `<polygon points="${areaPolygonPoints}" fill="url(#${gradId})"/>`;

  // Draw line
  // Draw line
  areaHTML += `<polyline points="${areaPolylinePoints}" fill="none" stroke="${areaLineColor}" stroke-width="${areaLineWidth}"/>`;

  // Draw points if enabled
  // Draw points if enabled
  if (areaShowPoints) {
    areaPoints.forEach(p => {
      areaHTML += `<circle cx="${p.x}" cy="${p.y}" r="5" fill="${areaLineColor}"/>`;
    });
  }

  // Draw labels
  // Draw labels
  areaLabels.forEach((label, i) => {
    const x = areaPadding + i / (areaLabels.length - 1 || 1) * areaPlotWidth;
    areaHTML += `<text x="${x}" y="${areaSvgHeight}" text-anchor="middle" font-size="11" fill="#666">${label}</text>`;
  });
  areaHTML += `</svg>`;
  areaHTML += `</div></div>`;
  return areaHTML;
}