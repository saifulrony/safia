export default // Widget renderer for "flip-box" (auto-generated)
function renderFlipBox(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const flipFrontIconType = settings.front_icon_type || 'icon';
  const flipFrontIcon = settings.front_icon || 'fa fa-star';
  const flipFrontImage = settings.front_image || '';
  const flipFrontTitle = settings.front_title || 'Amazing Feature';
  const flipFrontDesc = settings.front_description || 'Hover to see more';
  const flipBackIconType = settings.back_icon_type || 'none';
  const flipBackIcon = settings.back_icon || 'fa fa-check-circle';
  const flipBackImage = settings.back_image || '';
  const flipBackTitle = settings.back_title || 'Discover More';
  const flipBackDesc = settings.back_description || 'This is an amazing feature';
  const flipShowButton = settings.show_button !== 'no';
  const flipButtonText = settings.button_text || 'Learn More';
  const flipEffect = settings.flip_effect || 'flip-horizontal';
  const flipHeight = settings.box_height || 300;
  const flipFrontBgType = settings.front_bg_type || 'color';
  const flipFrontBgColor = settings.front_bg_color || '#92003b';
  const flipFrontBgGrad = settings.front_bg_gradient || '';
  const flipFrontTextColor = settings.front_text_color || '#ffffff';
  const flipFrontIconColor = settings.front_icon_color || '#ffffff';
  const flipFrontIconSize = settings.front_icon_size || 60;
  const flipBackBgType = settings.back_bg_type || 'color';
  const flipBackBgColor = settings.back_bg_color || '#333333';
  const flipBackBgGrad = settings.back_bg_gradient || '';
  const flipBackTextColor = settings.back_text_color || '#ffffff';
  const flipButtonBg = settings.back_button_bg || '#ffffff';
  const flipButtonColor = settings.back_button_color || '#333333';
  const flipBorderRadius = settings.border_radius || 8;
  const flipMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const flipPadding = settings.padding || {
    top: 30,
    right: 30,
    bottom: 30,
    left: 30
  };

  // Front background
  // Front background
  let flipFrontBg = '';
  if (flipFrontBgType === 'gradient' && flipFrontBgGrad) {
    flipFrontBg = `background: ${flipFrontBgGrad};`;
  } else {
    flipFrontBg = `background: ${flipFrontBgColor};`;
  }

  // Back background
  // Back background
  let flipBackBg = '';
  if (flipBackBgType === 'gradient' && flipBackBgGrad) {
    flipBackBg = `background: ${flipBackBgGrad};`;
  } else {
    flipBackBg = `background: ${flipBackBgColor};`;
  }
  const flipBoxId = 'flipbox-' + element.id;
  let flipBoxHTML = `
                        <div class="probuilder-flip-box-preview" id="${flipBoxId}" data-effect="${flipEffect}" style="
                            perspective: 1000px;
                            height: ${flipHeight}px;
                            margin: ${flipMargin.top}px ${flipMargin.right}px ${flipMargin.bottom}px ${flipMargin.left}px;
                            cursor: pointer;
                        ">
                            <div class="flip-box-inner" style="
                                position: relative;
                                width: 100%;
                                height: 100%;
                                transition: transform 0.6s;
                                transform-style: preserve-3d;
                            ">
                                <div class="flip-box-front" style="
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    backface-visibility: hidden;
                                    ${flipFrontBg}
                                    color: ${flipFrontTextColor};
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: ${flipBorderRadius}px;
                                    padding: ${flipPadding.top}px ${flipPadding.right}px ${flipPadding.bottom}px ${flipPadding.left}px;
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                ">
                                    ${flipFrontIconType === 'icon' && flipFrontIcon ? `
                                        <i class="${flipFrontIcon}" style="font-size: ${flipFrontIconSize}px; color: ${flipFrontIconColor}; margin-bottom: 20px;"></i>
                                    ` : ''}
                                    ${flipFrontIconType === 'image' && flipFrontImage ? `
                                        <img src="${flipFrontImage}" alt="${flipFrontTitle}" style="width: ${flipFrontIconSize}px; height: ${flipFrontIconSize}px; margin-bottom: 20px; border-radius: 50%;">
                                    ` : ''}
                                    <h3 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 600; color: ${flipFrontTextColor};">
                                        ${flipFrontTitle}
                                    </h3>
                                    ${flipFrontDesc ? `<p style="margin: 0; font-size: 14px; opacity: 0.9; color: ${flipFrontTextColor};">
                                        ${flipFrontDesc}
                                    </p>` : ''}
                                </div>
                                
                                <div class="flip-box-back" style="
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    backface-visibility: hidden;
                                    ${flipBackBg}
                                    color: ${flipBackTextColor};
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: ${flipBorderRadius}px;
                                    padding: ${flipPadding.top}px ${flipPadding.right}px ${flipPadding.bottom}px ${flipPadding.left}px;
                                    transform: rotateY(180deg);
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                ">
                                    ${flipBackIconType === 'icon' && flipBackIcon ? `
                                        <i class="${flipBackIcon}" style="font-size: 40px; margin-bottom: 15px; color: ${flipBackTextColor};"></i>
                                    ` : ''}
                                    ${flipBackIconType === 'image' && flipBackImage ? `
                                        <img src="${flipBackImage}" alt="${flipBackTitle}" style="width: 60px; height: 60px; margin-bottom: 15px; border-radius: 50%;">
                                    ` : ''}
                                    <h3 style="margin: 0 0 15px 0; font-size: 22px; font-weight: 600; color: ${flipBackTextColor};">
                                        ${flipBackTitle}
                                    </h3>
                                    ${flipBackDesc ? `<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; text-align: center; color: ${flipBackTextColor};">
                                        ${flipBackDesc}
                                    </p>` : ''}
                                    ${flipShowButton ? `<a href="#" style="background: ${flipButtonBg}; color: ${flipButtonColor}; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: 600;">
                                        ${flipButtonText}
                                    </a>` : ''}
                                </div>
                            </div>
                        </div>
                    `;

  // Add hover effect
  // Add hover effect
  setTimeout(function () {
    const $flipBox = jQuery('#' + flipBoxId);
    $flipBox.on('mouseenter', function () {
      const effect = jQuery(app).data('effect');
      const $inner = jQuery(app).find('.flip-box-inner');
      switch (effect) {
        case 'flip-horizontal':
          $inner.css('transform', 'rotateY(180deg)');
          break;
        case 'flip-vertical':
          $inner.css('transform', 'rotateX(180deg)');
          break;
        case 'zoom-in':
          $inner.find('.flip-box-front').css({
            'opacity': '0',
            'transform': 'scale(1.2)'
          });
          $inner.find('.flip-box-back').css({
            'opacity': '1',
            'transform': 'scale(1) rotateY(0deg)'
          });
          break;
        case 'zoom-out':
          $inner.find('.flip-box-front').css({
            'opacity': '0',
            'transform': 'scale(0.8)'
          });
          $inner.find('.flip-box-back').css({
            'opacity': '1',
            'transform': 'scale(1) rotateY(0deg)'
          });
          break;
        case 'fade':
          $inner.find('.flip-box-front').css('opacity', '0');
          $inner.find('.flip-box-back').css({
            'opacity': '1',
            'transform': 'rotateY(0deg)'
          });
          break;
      }
    }).on('mouseleave', function () {
      const $inner = jQuery(app).find('.flip-box-inner');
      $inner.css('transform', '');
      $inner.find('.flip-box-front').css({
        'opacity': '1',
        'transform': ''
      });
      $inner.find('.flip-box-back').css({
        'opacity': '0',
        'transform': 'rotateY(180deg)'
      });
    });
  }, 100);
  return flipBoxHTML;
}