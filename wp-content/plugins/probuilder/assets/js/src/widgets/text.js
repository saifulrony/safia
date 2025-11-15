// Widget renderer for "text" (auto-generated)
export default function renderText(context) {
    const { element, settings, app } = context;
    
    const textColor = settings.text_color || app.getGlobalColor('text') || '#495157';
    const textAlign = settings.text_align || 'left';
    const pathType = settings.path_type || 'none';
    const textCurveAmount = settings.curve_amount || 50;
    
    const textStyle = `
        color: ${textColor};
        font-size: ${settings.font_size || 16}px;
        line-height: ${settings.line_height || 1.6};
        text-align: ${textAlign};
        margin: 0;
    `;
    
    let textContent = settings.text || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    const textContentPlain = textContent.replace(/<[^>]*>/g, '').substring(0, 200);
    
    if (pathType !== 'none') {
        const svgId = 'text-path-preview-' + element.id;
        const svgHeight = (settings.font_size || 16) * 3;
        const textLength = textContentPlain.length * (settings.font_size || 16) * 0.6;
        const midY = svgHeight / 2;
        const intensity = textCurveAmount;
        
        let pathD = '';
        switch (pathType) {
            case 'curve': {
                const controlY = midY - intensity;
                pathD = `M 0,${svgHeight} Q ${textLength / 2},${controlY} ${textLength},${svgHeight}`;
                break;
            }
            case 'wave': {
                const waveHeight = Math.abs(intensity);
                pathD = `M 0,${midY} Q ${textLength * 0.25},${midY - waveHeight} ${textLength * 0.5},${midY} T ${textLength},${midY}`;
                break;
            }
            case 'circle': {
                const radius = textLength / 2;
                pathD = `M 0,${svgHeight} A ${radius},${radius} 0 0,1 ${textLength},${svgHeight}`;
                break;
            }
            case 'zigzag': {
                const points = 8;
                pathD = `M 0,${midY}`;
                for (let i = 1; i <= points; i++) {
                    const x = (textLength / points) * i;
                    const y = midY + ((i % 2 === 0) ? intensity : -intensity);
                    pathD += ` L ${x},${y}`;
                }
                break;
            }
            case 'spiral': {
                const turns = 3;
                pathD = `M 0,${midY}`;
                for (let i = 1; i <= 20; i++) {
                    const x = (textLength / 20) * i;
                    const angle = (i / 20) * turns * 2 * Math.PI;
                    const amplitude = intensity * (i / 20);
                    const y = midY + Math.sin(angle) * amplitude;
                    pathD += ` L ${x},${y}`;
                }
                break;
            }
            case 'sine': {
                const frequency = 2;
                pathD = `M 0,${midY}`;
                for (let i = 1; i <= 30; i++) {
                    const x = (textLength / 30) * i;
                    const angle = (i / 30) * frequency * 2 * Math.PI;
                    const y = midY + Math.sin(angle) * intensity;
                    pathD += ` L ${x},${y}`;
                }
                break;
            }
            case 'bounce': {
                const bounces = 4;
                pathD = `M 0,${midY}`;
                for (let i = 1; i <= bounces; i++) {
                    const x1 = (textLength / bounces) * (i - 0.5);
                    const y1 = midY - Math.abs(intensity);
                    const x2 = (textLength / bounces) * i;
                    const y2 = midY;
                    pathD += ` Q ${x1},${y1} ${x2},${y2}`;
                }
                break;
            }
            case 'infinity': {
                const loopWidth = textLength / 2;
                pathD = `M 0,${midY} `;
                pathD += `Q ${loopWidth * 0.25},${midY - intensity} ${loopWidth * 0.5},${midY} `;
                pathD += `Q ${loopWidth * 0.75},${midY + intensity} ${loopWidth},${midY} `;
                pathD += `Q ${loopWidth * 1.25},${midY - intensity} ${loopWidth * 1.5},${midY} `;
                pathD += `Q ${loopWidth * 1.75},${midY + intensity} ${textLength},${midY}`;
                break;
            }
            default:
                pathD = `M 0,${midY} L ${textLength},${midY}`;
        }
        
        return `
            <div style="text-align: ${textAlign};">
                <svg width="100%" height="${svgHeight}px" viewBox="0 0 ${textLength} ${svgHeight}" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                    <defs>
                        <path id="${svgId}" d="${pathD}" fill="transparent"/>
                    </defs>
                    <text style="fill: ${textColor}; font-size: ${settings.font_size || 16}px;">
                        <textPath href="#${svgId}" startOffset="50%" text-anchor="middle">
                            ${textContentPlain}
                        </textPath>
                    </text>
                </svg>
            </div>
        `;
    }
    
    return `<div class="probuilder-text" style="${textStyle}">${textContent}</div>`;
}

