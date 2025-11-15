export default // Widget renderer for "google-maps" (auto-generated)
function renderGoogleMaps(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const gmAddress = settings.address || 'Times Square, New York, NY';
  const gmZoom = settings.zoom || 15;
  const gmHeight = settings.height || 400;
  return `<div style="position: relative; height: ${gmHeight}px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; overflow: hidden;">
                        <div style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' viewBox=\\'0 0 800 600\\'%3E%3Crect fill=\\'%23e0e0e0\\' width=\\'800\\' height=\\'600\\'/%3E%3Cpath fill=\\'%23ccc\\' d=\\'M0 300 Q200 250 400 300 T800 300 V600 H0 Z\\'/%3E%3Cpath fill=\\'%23bbb\\' d=\\'M0 400 Q200 350 400 400 T800 400 V600 H0 Z\\'/%3E%3C/svg%3E') center/cover;"></div>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; z-index: 1;">
                            <div style="width: 40px; height: 60px; margin: 0 auto 15px; background: #92003b; clip-path: polygon(50% 100%, 0% 40%, 0% 0%, 100% 0%, 100% 40%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; padding-bottom: 15px;">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>
                            <div style="background: rgba(255,255,255,0.95); padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                <div style="font-weight: 600; color: #333; margin-bottom: 5px;">${gmAddress}</div>
                                <div style="font-size: 11px; color: #666;">Zoom: ${gmZoom}x · Height: ${gmHeight}px</div>
                            </div>
                        </div>
                    </div>`;
}