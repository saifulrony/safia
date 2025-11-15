export default // Widget renderer for "star-rating" (auto-generated)
function renderStarRating(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const starRating = parseFloat(settings.rating) || 5;
  const starMaxStars = parseInt(settings.max_stars) || 5;
  const starShowTitle = settings.show_title !== 'no';
  const starTitle = settings.title || 'Excellent Service!';
  const starShowNumber = settings.show_number !== 'no';
  const starFilledColor = settings.filled_color || '#ffa500';
  const starEmptyColor = settings.empty_color || '#d4d4d4';
  const starSize = settings.star_size || 24;
  const starTitleColor = settings.title_color || '#333333';
  const starAlign = settings.align || 'left';
  const starMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  let starHTML = `
                        <div class="probuilder-star-rating-preview" style="
                            text-align: ${starAlign};
                            margin: ${starMargin.top}px ${starMargin.right}px ${starMargin.bottom}px ${starMargin.left}px;
                        ">
                            ${starShowTitle ? `<div style="font-size: 18px; font-weight: 600; margin-bottom: 10px; color: ${starTitleColor};">
                                ${starTitle}
                            </div>` : ''}
                            <div style="font-size: ${starSize}px; margin-bottom: 8px;">
                    `;
  for (let i = 1; i <= starMaxStars; i++) {
    let starClass = 'fa fa-star';
    let starColor = starEmptyColor;
    if (i <= Math.floor(starRating)) {
      starColor = starFilledColor;
    } else if (i - 0.5 <= starRating) {
      starClass = 'fa fa-star-half-stroke';
      starColor = starFilledColor;
    }
    starHTML += `<i class="${starClass}" style="color: ${starColor}; margin-right: 3px;"></i>`;
  }
  starHTML += `</div>`;
  if (starShowNumber) {
    starHTML += `<div style="font-size: 14px; color: #666;">
                            ${starRating.toFixed(1)} / ${starMaxStars}
                        </div>`;
  }
  starHTML += '</div>';
  return starHTML;
}