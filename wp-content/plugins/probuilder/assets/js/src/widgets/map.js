export default // Widget renderer for "map" (auto-generated)
function renderMap(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const mapAddress = settings.address || 'Times Square, New York, NY, USA';
  const mapLat = settings.latitude || '';
  const mapLon = settings.longitude || '';
  const mapZoom = settings.zoom || 12;
  const mapHeight = settings.height || 400;
  const mapRadius = settings.border_radius || 8;
  const mapMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };

  // Smart detection: Use address OR coordinates
  // Smart detection: Use address OR coordinates
  let googleMapsUrl;
  const hasCoords = mapLat && mapLon;
  if (hasCoords) {
    // Use coordinates for precision
    googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(mapLat + ',' + mapLon)}&t=m&z=${mapZoom}&output=embed&iwloc=near`;
  } else {
    // Use address - Google Maps will geocode it
    googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(mapAddress)}&t=m&z=${mapZoom}&output=embed&iwloc=near`;
  }
  let mapHTML = `
                        <div class="probuilder-map-preview" style="
                            width: 100%;
                            height: ${mapHeight}px;
                            border-radius: ${mapRadius}px;
                            overflow: hidden;
                            margin: ${mapMargin.top}px ${mapMargin.right}px ${mapMargin.bottom}px ${mapMargin.left}px;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            position: relative;
                            background: #e5e7eb;
                        ">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0; display: block; pointer-events: none;" 
                                src="${googleMapsUrl}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                        <div style="
                            font-size: 12px; 
                            color: #64748b; 
                            margin-top: 8px; 
                            display: flex; 
                            align-items: center; 
                            gap: 5px;
                            margin-left: ${mapMargin.left}px;
                        ">
                            <i class="fa fa-map-marker" style="color: #92003b;"></i>
                            <span>${mapAddress}</span>
                        </div>
                    `;
  return mapHTML;
}