// Widget renderer for "divider" (auto-generated)
export default function renderDivider(context) {
    const { settings } = context;
    
    const divHeight = settings.height || 1;
    const divStyle = settings.style || 'solid';
    const divColor = settings.color || '#ddd';
    const divWidth = settings.width || 100;
    const divAlign = settings.align || 'center';
    const divGap = settings.gap || 15;
    
    let divMargin = `${divGap}px auto`;
    if (divAlign === 'left') divMargin = `${divGap}px auto ${divGap}px 0`;
    if (divAlign === 'right') divMargin = `${divGap}px 0 ${divGap}px auto`;
    
    return `<div style="width: 100%; display: block; line-height: 0; margin: ${divMargin};"><hr style="border: none; border-top: ${divHeight}px ${divStyle} ${divColor}; width: ${divWidth}%; margin: 0; display: inline-block;"></div>`;
}

