export default // Widget renderer for "gallery" (auto-generated)
function renderGallery(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const galleryImages = settings.images || [{
    image_url: 'https://via.placeholder.com/600x400/FF6B6B/ffffff?text=1',
    caption: 'Beautiful Image 1'
  }, {
    image_url: 'https://via.placeholder.com/600x400/4ECDC4/ffffff?text=2',
    caption: 'Beautiful Image 2'
  }, {
    image_url: 'https://via.placeholder.com/600x400/45B7D1/ffffff?text=3',
    caption: 'Beautiful Image 3'
  }, {
    image_url: 'https://via.placeholder.com/600x400/96CEB4/ffffff?text=4',
    caption: 'Beautiful Image 4'
  }, {
    image_url: 'https://via.placeholder.com/600x400/FFEAA7/ffffff?text=5',
    caption: 'Beautiful Image 5'
  }, {
    image_url: 'https://via.placeholder.com/600x400/6C5CE7/ffffff?text=6',
    caption: 'Beautiful Image 6'
  }];
  const galleryColumns = settings.columns || '3';
  const galleryGap = settings.gap || 15;
  const galleryRadius = settings.border_radius || 8;
  const galleryMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const galleryShowCaption = settings.show_caption !== 'no';
  let galleryHTML = `<div class="probuilder-gallery-preview" style="
                        display: grid;
                        grid-template-columns: repeat(${galleryColumns}, 1fr);
                        gap: ${galleryGap}px;
                        margin: ${galleryMargin.top}px ${galleryMargin.right}px ${galleryMargin.bottom}px ${galleryMargin.left}px;
                    ">`;
  galleryImages.forEach((img, idx) => {
    galleryHTML += `
                            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: ${galleryRadius}px; line-height: 0; cursor: pointer;">
                                <img src="${img.image_url}" alt="${img.caption || ''}" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;">
                                ${galleryShowCaption && img.caption ? `
                                    <div class="gallery-caption" style="
                                        position: absolute;
                                        bottom: 0;
                                        left: 0;
                                        right: 0;
                                        background: rgba(0,0,0,0.7);
                                        color: #ffffff;
                                        padding: 10px 15px;
                                        font-size: 14px;
                                        transform: translateY(100%);
                                        transition: transform 0.3s ease;
                                    ">${img.caption}</div>
                                ` : ''}
                            </div>
                        `;
  });
  galleryHTML += '</div>';
  setTimeout(function () {
    jQuery('.probuilder-gallery-preview .gallery-item').on('mouseenter', function () {
      jQuery(app).find('img').css('transform', 'scale(1.1)');
      jQuery(app).find('.gallery-caption').css('transform', 'translateY(0)');
    }).on('mouseleave', function () {
      jQuery(app).find('img').css('transform', 'scale(1)');
      jQuery(app).find('.gallery-caption').css('transform', 'translateY(100%)');
    });
  }, 100);
  return galleryHTML;
}