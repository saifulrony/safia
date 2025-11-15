export default // Widget renderer for "woo-rating" (auto-generated)
function renderWooRating(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const ratingValue = settings.rating || 4.5;
  const ratingCount = settings.show_count !== 'no';
  return `<div style="padding: 15px 20px; background: #f5f5f5; border-radius: 8px; text-align: center;">
                        <div style="color: #f90; font-size: 20px; margin-bottom: 5px;">
                            ${'★'.repeat(Math.floor(ratingValue))}${'☆'.repeat(5 - Math.floor(ratingValue))}
                        </div>
                        ${ratingCount ? '<div style="font-size: 13px; color: #666;">(24 customer reviews)</div>' : ''}
                        <p style="margin-top: 10px; color: #999; font-size: 11px;">
                            <i class="fa fa-star"></i> Product Rating Widget
                        </p>
                    </div>`;
}