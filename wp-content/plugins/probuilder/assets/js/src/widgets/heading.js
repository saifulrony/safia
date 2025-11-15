// Widget renderer for "heading" (auto-generated)
export default function renderHeading(context) {
    const { element, settings, spacingStyles, app } = context;
    
    const headingTag = settings.html_tag || 'h2';
    const headingColor = settings.color || app.getGlobalColor('primary') || '#344047';
    const enableTextPath = (settings.enable_text_path === 'yes');
    const pathTypeHeading = settings.path_type || 'curve';
    const curveAmountHeading = settings.curve_amount || 50;
    
    const opacityStyle = (typeof settings.opacity !== 'undefined' && settings.opacity !== '' && settings.opacity !== 100)
        ? `opacity: ${parseFloat(settings.opacity) / 100};` : '';
    const transforms = [];
    if (typeof settings.rotate !== 'undefined' && settings.rotate !== 0) transforms.push(`rotate(${settings.rotate}deg)`);
    if (typeof settings.scale !== 'undefined' && settings.scale !== 100) {
        const scaleVal = parseFloat(settings.scale) / 100;
        transforms.push(`scale(${scaleVal})`);
    }
    if ((typeof settings.skew_x !== 'undefined' && settings.skew_x !== 0) || (typeof settings.skew_y !== 'undefined' && settings.skew_y !== 0)) {
        const sx = settings.skew_x || 0; const sy = settings.skew_y || 0;
        transforms.push(`skew(${sx}deg, ${sy}deg)`);
    }
    const transformStyle = transforms.length ? `transform: ${transforms.join(' ')};` : '';
    const combinedSpacing = spacingStyles ? `${spacingStyles};` : '';
    
    if (enableTextPath) {
        const titleText = (settings.title || 'This is a heading').toString();
        const fontSize = settings.font_size || 32;
        const fontWeight = settings.font_weight || 600;
        const textAlign = settings.align || 'left';
        const svgId = 'heading-path-preview-' + element.id;
        const svgHeight = fontSize * 3;
        const textLength = Math.max(10, titleText.length) * fontSize * 0.6;
        let pathD = '';
        if (pathTypeHeading === 'curve') {
            const controlY = (svgHeight / 2) - curveAmountHeading;
            pathD = `M 0,${svgHeight} Q ${textLength / 2},${controlY} ${textLength},${svgHeight}`;
        } else if (pathTypeHeading === 'wave') {
            const waveHeight = Math.abs(curveAmountHeading);
            pathD = `M 0,${svgHeight / 2} Q ${textLength * 0.25},${(svgHeight / 2) - waveHeight} ${textLength * 0.5},${svgHeight / 2} T ${textLength},${svgHeight / 2}`;
        } else {
            const radius = textLength / 2;
            pathD = `M 0,${svgHeight} A ${radius},${radius} 0 0,1 ${textLength},${svgHeight}`;
        }
        return `
            <div style="text-align: ${textAlign}; ${combinedSpacing} ${opacityStyle} ${transformStyle}">
                <svg width="100%" height="${svgHeight}px" viewBox="0 0 ${textLength} ${svgHeight}" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                    <defs>
                        <path id="${svgId}" d="${pathD}" fill="transparent"/>
                    </defs>
                    <text style="fill: ${headingColor}; font-size: ${fontSize}px; font-weight: ${fontWeight};">
                        <textPath href="#${svgId}" startOffset="50%" text-anchor="middle">${titleText}</textPath>
                    </text>
                </svg>
            </div>
        `;
    }
    
    const headingStyle = `
        color: ${headingColor};
        font-size: ${settings.font_size || 32}px;
        font-weight: ${settings.font_weight || 600};
        text-align: ${settings.align || 'left'};
        margin: 0;
        line-height: 1.3;
        ${combinedSpacing}
        ${opacityStyle}
        ${transformStyle}
    `;
    return `<${headingTag} style="${headingStyle}">${settings.title || 'This is a heading'}</${headingTag}>`;
}

