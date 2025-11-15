export default // Widget renderer for "image-box" (auto-generated)
function renderImageBox(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const imageBoxUrl = settings.image_url || 'https://via.placeholder.com/600x400';
  const imageBoxPosition = settings.image_position || 'top';
  const imageBoxTitle = settings.title || 'Beautiful Image Box';
  const imageBoxDesc = settings.description || 'Add a stunning image with text';
  const imageBoxShowBtn = settings.show_button !== 'no';
  const imageBoxBtnText = settings.button_text || 'Learn More';
  const imageBoxAlign = settings.text_align || 'left';
  const imageBoxTitleColor = settings.title_color || '#333333';
  const imageBoxTitleSize = settings.title_size || 24;
  const imageBoxDescColor = settings.description_color || '#666666';
  const imageBoxBgColor = settings.bg_color || '#ffffff';
  const imageBoxRadius = settings.border_radius || 8;
  const imageBoxMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const imageBoxPadding = settings.padding || {
    top: 25,
    right: 25,
    bottom: 25,
    left: 25
  };
  let imageBoxHTML = `
                        <div class="probuilder-image-box-preview" style="
                            background: ${imageBoxBgColor};
                            border-radius: ${imageBoxRadius}px;
                            overflow: hidden;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            margin: ${imageBoxMargin.top}px ${imageBoxMargin.right}px ${imageBoxMargin.bottom}px ${imageBoxMargin.left}px;
                            ${imageBoxPosition !== 'top' ? 'display: flex; align-items: center;' : ''}
                            ${imageBoxPosition === 'right' ? 'flex-direction: row-reverse;' : ''}
                        ">
                            <div class="image-box-image" style="margin: 0; line-height: 0; ${imageBoxPosition !== 'top' ? 'flex-shrink: 0; width: 50%;' : ''}">
                                <img src="${imageBoxUrl}" alt="${imageBoxTitle}" style="width: 100%; height: auto; display: block;">
                            </div>
                            <div class="image-box-content" style="
                                padding: ${imageBoxPadding.top}px ${imageBoxPadding.right}px ${imageBoxPadding.bottom}px ${imageBoxPadding.left}px;
                                text-align: ${imageBoxAlign};
                                ${imageBoxPosition !== 'top' ? 'flex: 1;' : ''}
                            ">
                                <h3 style="margin: 0 0 12px 0; font-size: ${imageBoxTitleSize}px; color: ${imageBoxTitleColor}; font-weight: 600;">
                                    ${imageBoxTitle}
                                </h3>
                                ${imageBoxDesc ? `<p style="margin: 0 0 20px 0; font-size: 16px; color: ${imageBoxDescColor}; line-height: 1.6;">
                                    ${imageBoxDesc}
                                </p>` : ''}
                                ${imageBoxShowBtn ? `<a href="#" style="display: inline-block; background: #92003b; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: 600;">
                                    ${imageBoxBtnText}
                                </a>` : ''}
                            </div>
                        </div>
                    `;
  return imageBoxHTML;
}