export default // Widget renderer for "hotspot" (auto-generated)
function renderHotspot(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const hotspotImage = settings.image || 'https://via.placeholder.com/800x600/93003c/ffffff?text=Hotspot+Image';
  const hotspots = settings.hotspots || [{
    x_position: 30,
    y_position: 30,
    title: 'Hotspot 1',
    content: 'Info'
  }];
  let hotspotHTML = `<div style="position:relative;display:inline-block;max-width:100%">
                        <img src="${hotspotImage}" style="width:100%;height:auto;display:block">`;
  hotspots.forEach(spot => {
    hotspotHTML += `<div style="position:absolute;left:${spot.x_position || 50}%;top:${spot.y_position || 50}%;transform:translate(-50%,-50%)">
                            <span style="display:block;width:20px;height:20px;background:#0073aa;border-radius:50%;animation:pulse 2s infinite"></span>
                        </div>`;
  });
  hotspotHTML += '</div><style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}</style>';
  return hotspotHTML;
}