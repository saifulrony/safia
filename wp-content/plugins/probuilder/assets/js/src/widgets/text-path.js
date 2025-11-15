export default // Widget renderer for "text-path" (auto-generated)
function renderTextPath(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const curvedText = settings.text || 'Beautiful Curved Text';
  const curvePathType = settings.path_type || 'arc';
  const curveAmount = settings.curve_amount || 30;
  const curveWidth = settings.path_width || 500;
  const curveHeight = settings.path_height || 200;
  const textPathColor = settings.text_color || '#1a1a1a';
  const textPathSize = settings.font_size || 32;
  const textPathWeight = settings.font_weight || '600';
  const textPathSpacing = settings.letter_spacing || 0;
  const startOff = settings.start_offset || 0;
  const strokeW = settings.text_stroke || 0;
  const strokeCol = settings.stroke_color || '#000000';

  // Generate path based on type
  // Generate path based on type
  let curvePath = '';
  const cX = curveWidth / 2;
  const cY = curveHeight / 2;
  const sX = 50;
  const eX = curveWidth - 50;
  switch (curvePathType) {
    case 'arc':
      curvePath = `M ${sX},${cY} Q ${cX},${cY - curveAmount} ${eX},${cY}`;
      break;
    case 'arc-down':
      curvePath = `M ${sX},${cY} Q ${cX},${cY + curveAmount} ${eX},${cY}`;
      break;
    case 'wave':
      const wH = Math.abs(curveAmount);
      const step = curveWidth / 4;
      curvePath = `M ${sX},${cY} Q ${sX + step},${cY - wH} ${sX + step * 2},${cY} Q ${sX + step * 3},${cY + wH} ${eX},${cY}`;
      break;
    case 'circle':
      const rad = Math.min(curveWidth, curveHeight) / 2 - 50;
      curvePath = `M ${cX - rad},${cY} A ${rad},${rad} 0 1,1 ${cX + rad},${cY}`;
      break;
    case 's-curve':
      const c1Y = cY - curveAmount;
      const c2Y = cY + curveAmount;
      curvePath = `M ${sX},${cY} C ${curveWidth * 0.3},${c1Y} ${curveWidth * 0.7},${c2Y} ${eX},${cY}`;
      break;
    default:
      curvePath = `M ${sX},${cY} Q ${cX},${cY - curveAmount} ${eX},${cY}`;
  }
  const pathId = 'curve-' + Math.random().toString(36).substr(2, 9);
  const strokeAttr = strokeW > 0 ? `stroke="${strokeCol}" stroke-width="${strokeW}"` : '';
  return `<div style="text-align:center;padding:30px;background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);border-radius:12px">
                        <svg viewBox="0 0 ${curveWidth} ${curveHeight}" style="width:100%;max-width:${curveWidth}px;height:auto">
                            <path id="${pathId}" d="${curvePath}" fill="transparent" stroke="rgba(0,0,0,0.1)" stroke-width="1" stroke-dashautor="5,5"/>
                            <text fill="${textPathColor}" font-size="${textPathSize}" font-weight="${textPathWeight}" letter-spacing="${textPathSpacing}" ${strokeAttr} text-anchor="middle">
                                <textPath href="#${pathId}" startOffset="${startOff}%">
                                    ${curvedText}
                                </textPath>
                            </text>
                        </svg>
                        <div style="margin-top:20px;padding:15px;background:rgba(255,255,255,0.9);border-radius:8px">
                            <p style="margin:0;color:#667eea;font-size:13px;font-weight:600">
                                📐 ${curvePathType.toUpperCase()} Path | 
                                Curve: ${curveAmount > 0 ? '+' : ''}${curveAmount} | 
                                Size: ${textPathSize}px
                            </p>
                        </div>
                    </div>`;
}