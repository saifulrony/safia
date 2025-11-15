// Widget renderer for "button" (auto-generated)
export default function renderButton(context) {
    const { settings, app } = context;
    
    const btnType = settings.button_type || 'solid';
    const btnSizePreset = settings.size_preset || 'medium';
    const btnWidthType = settings.width_type || 'auto';
    const btnCustomWidth = settings.custom_width || 200;
    const btnAlign = settings.align || 'left';
    
    const btnBgColor = settings.bg_color || app.getGlobalColor('primary') || '#0073aa';
    const btnTextColor = settings.text_color || '#ffffff';
    const btnGradientColor = settings.gradient_color || '#005a87';
    const btnGradientAngle = settings.gradient_angle || 135;
    
    let btnFontSize = settings.font_size || 16;
    const btnFontWeight = settings.font_weight || '500';
    const btnTextTransform = settings.text_transform || 'none';
    const btnLetterSpacing = settings.letter_spacing || 0;
    
    const btnBorderWidth = settings.border_width || 0;
    const btnBorderColor = settings.border_color || '#0073aa';
    const btnBorderRadius = settings.border_radius || 3;
    
    const btnShadow = settings.box_shadow || { x: 0, y: 2, blur: 8, color: 'rgba(0,0,0,0.1)' };
    
    const btnIcon = settings.icon || '';
    const btnIconPosition = settings.icon_position || 'left';
    const btnIconSpacing = settings.icon_spacing || 8;
    
    let btnPadding = settings.padding || { top: 12, right: 24, bottom: 12, left: 24 };
    const btnMargin = settings.margin || { top: 0, right: 0, bottom: 0, left: 0 };
    
    if (btnSizePreset !== 'custom') {
        const presets = {
            small: { font: 14, padding: { top: 8, right: 16, bottom: 8, left: 16 } },
            medium: { font: 16, padding: { top: 12, right: 24, bottom: 12, left: 24 } },
            large: { font: 18, padding: { top: 16, right: 32, bottom: 16, left: 32 } },
            xl: { font: 22, padding: { top: 20, right: 40, bottom: 20, left: 40 } }
        };
        if (presets[btnSizePreset]) {
            btnFontSize = presets[btnSizePreset].font;
            btnPadding = presets[btnSizePreset].padding;
        }
    }
    
    let btnBackground = '';
    if (btnType === 'gradient') {
        btnBackground = `background: linear-gradient(${btnGradientAngle}deg, ${btnBgColor}, ${btnGradientColor});`;
    } else if (btnType === 'outline' || btnType === 'ghost') {
        btnBackground = 'background: transparent;';
    } else {
        btnBackground = `background: ${btnBgColor};`;
    }
    
    const btnBorder = (btnBorderWidth > 0 || btnType === 'outline')
        ? `border: ${btnType === 'outline' ? Math.max(2, btnBorderWidth) : btnBorderWidth}px solid ${btnBorderColor};`
        : 'border: none;';
    
    const btnBoxShadow = btnShadow.blur > 0
        ? `box-shadow: ${btnShadow.x}px ${btnShadow.y}px ${btnShadow.blur}px ${btnShadow.color};`
        : '';
    
    let btnWidth = '';
    if (btnWidthType === 'full' || btnAlign === 'justify') {
        btnWidth = 'width: 100%; display: block; text-align: center;';
    } else if (btnWidthType === 'custom') {
        btnWidth = `width: ${btnCustomWidth}px; display: inline-block; text-align: center;`;
    } else {
        btnWidth = 'display: inline-block;';
    }
    
    const btnStyle = `
        ${btnBackground}
        color: ${btnTextColor};
        font-size: ${btnFontSize}px;
        font-weight: ${btnFontWeight};
        text-transform: ${btnTextTransform};
        letter-spacing: ${btnLetterSpacing}px;
        padding: ${btnPadding.top}px ${btnPadding.right}px ${btnPadding.bottom}px ${btnPadding.left}px;
        margin: ${btnMargin.top}px ${btnMargin.right}px ${btnMargin.bottom}px ${btnMargin.left}px;
        border-radius: ${btnBorderRadius}px;
        ${btnBoxShadow}
        ${btnWidth}
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        ${btnBorder}
    `;
    
    const btnIconHtml = btnIcon ?
        (btnIconPosition === 'left'
            ? `<i class="${btnIcon}" style="margin-right: ${btnIconSpacing}px;"></i> `
            : ` <i class="${btnIcon}" style="margin-left: ${btnIconSpacing}px;"></i>`)
        : '';
    
    const btnContent = btnIconPosition === 'left'
        ? btnIconHtml + (settings.text || 'Click Here')
        : (settings.text || 'Click Here') + btnIconHtml;
    
    return `<div style="text-align: ${btnAlign === 'justify' ? 'center' : btnAlign};"><a href="#" class="probuilder-button" style="${btnStyle}">${btnContent}</a></div>`;
}

