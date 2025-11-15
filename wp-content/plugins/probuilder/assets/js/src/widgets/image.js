// Widget renderer for "image" (auto-generated)
export default function renderImage(context) {
    const { settings } = context;
    
    const defaultPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600"%3E%3Crect fill="%23d1d5db" width="800" height="600"/%3E%3Cg fill="white" opacity="0.5"%3E%3Crect x="250" y="180" width="300" height="240" rx="8" fill="none" stroke="white" stroke-width="3"/%3E%3Ccircle cx="320" cy="250" r="25"/%3E%3Cpath d="M250 380 L350 300 L450 350 L550 280 L550 420 L250 420 Z"/%3E%3C/g%3E%3C/svg%3E';
    const imgUrl = settings.image?.url || defaultPlaceholder;
    const imgAlign = settings.align || 'center';
    const imgWidth = settings.width || 100;
    const imgMaxWidth = settings.max_width || '';
    const imgHeight = settings.height || '';
    const imgObjectFit = settings.object_fit || 'cover';
    const imgBorderRadius = settings.border_radius || 0;
    const imgBorderWidth = settings.border_width || 0;
    const imgBorderColor = settings.border_color || '#000000';
    
    let imgStyle = `width: ${imgWidth}%; max-width: 100%;`;
    if (imgHeight) {
        imgStyle += ` height: ${imgHeight}px; object-fit: ${imgObjectFit};`;
    } else {
        imgStyle += ` height: 100%; max-height: 100%; object-fit: ${imgObjectFit};`;
    }
    if (imgMaxWidth) imgStyle += ` max-width: ${imgMaxWidth}px;`;
    if (imgBorderRadius > 0) imgStyle += ` border-radius: ${imgBorderRadius}px;`;
    if (imgBorderWidth > 0) imgStyle += ` border: ${imgBorderWidth}px solid ${imgBorderColor};`;
    imgStyle += ' display: block;';
    
    return `<div style="text-align: ${imgAlign}; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden;"><img src="${imgUrl}" alt="" style="${imgStyle}"></div>`;
}

