export default // Widget renderer for "carousel" (auto-generated)
function renderCarousel(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const carouselImages = Array.isArray(settings.images) ? settings.images : [{
    image_url: 'https://via.placeholder.com/1200x600/92003b/ffffff?text=Slide+1',
    caption: 'First Slide'
  }, {
    image_url: 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Slide+2',
    caption: 'Second Slide'
  }, {
    image_url: 'https://via.placeholder.com/1200x600/4facfe/ffffff?text=Slide+3',
    caption: 'Third Slide'
  }];
  const carouselHeight = settings.height || 400;
  const showArrows = settings.show_arrows !== 'no';
  const showDots = settings.show_dots !== 'no';
  const arrowsColor = settings.arrows_color || '#ffffff';
  const dotsColor = settings.dots_color || '#92003b';
  const autoplay = settings.autoplay !== 'no';
  const autoplaySpeed = settings.autoplay_speed || 3000;
  const carouselId = 'carousel-' + element.id;
  let carouselHTML = `<div class="probuilder-carousel-preview" data-carousel-id="${carouselId}" data-autoplay="${autoplay}" data-speed="${autoplaySpeed}" style="position: relative; overflow: hidden; height: ${carouselHeight}px; background: #f8f9fa; border-radius: 4px;">`;

  // Slides container
  // Slides container
  carouselHTML += '<div class="probuilder-carousel-slides" style="display: flex; height: 100%; transition: transform 0.5s ease; position: relative;">';
  carouselImages.forEach((img, index) => {
    carouselHTML += `
                            <div class="probuilder-carousel-slide" data-slide="${index}" style="
                                flex: 0 0 100%;
                                width: 100%;
                                height: 100%;
                                position: relative;
                                display: ${index === 0 ? 'flex' : 'none'};
                                align-items: center;
                                justify-content: center;
                                background: #000;
                            ">
                                <img src="${img.image_url || 'https://via.placeholder.com/1200x600'}" style="
                                    max-width: 100%;
                                    max-height: 100%;
                                    object-fit: contain;
                                    display: block;
                                " alt="${img.caption || 'Slide ' + (index + 1)}">
                                ${img.caption ? `
                                    <div style="
                                        position: absolute;
                                        bottom: 20px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        background: rgba(0,0,0,0.7);
                                        color: #ffffff;
                                        padding: 10px 20px;
                                        border-radius: 4px;
                                        font-size: 16px;
                                    ">${img.caption}</div>
                                ` : ''}
                            </div>
                        `;
  });
  carouselHTML += '</div>';

  // Navigation arrows
  // Navigation arrows
  if (showArrows) {
    carouselHTML += `
                            <button class="probuilder-carousel-prev" style="
                                position: absolute;
                                top: 50%;
                                left: 15px;
                                transform: translateY(-50%);
                                background: rgba(0,0,0,0.6);
                                color: ${arrowsColor};
                                border: none;
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                z-index: 10;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                ‹
                            </button>
                            <button class="probuilder-carousel-next" style="
                                position: absolute;
                                top: 50%;
                                right: 15px;
                                transform: translateY(-50%);
                                background: rgba(0,0,0,0.6);
                                color: ${arrowsColor};
                                border: none;
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                z-index: 10;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                ›
                            </button>
                        `;
  }

  // Dots indicator
  // Dots indicator
  if (showDots) {
    carouselHTML += '<div class="probuilder-carousel-dots" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10;">';
    carouselImages.forEach((img, index) => {
      carouselHTML += `
                                <button class="probuilder-carousel-dot ${index === 0 ? 'active' : ''}" data-slide="${index}" style="
                                    width: ${index === 0 ? '24px' : '12px'};
                                    height: 12px;
                                    border-radius: 6px;
                                    border: 2px solid ${dotsColor};
                                    background: ${index === 0 ? dotsColor : 'transparent'};
                                    cursor: pointer;
                                    transition: all 0.3s;
                                    padding: 0;
                                "></button>
                            `;
    });
    carouselHTML += '</div>';
  }
  carouselHTML += '</div>';

  // Initialize carousel after rendering
  // Initialize carousel after rendering
  const self = app;
  setTimeout(function () {
    self.initializeCarousel(element, null);
  }, 100);
  return carouselHTML;
}