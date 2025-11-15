export default // Widget renderer for "woo-reviews" (auto-generated)
function renderWooReviews(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const reviewsCount = settings.reviews_count || 3;
  return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin: 0 0 20px; font-size: 22px; color: #333;">Customer Reviews</h3>
                        ${Array(Math.min(reviewsCount, 3)).fill('').map((_, i) => `
                            <div style="background: #fff; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <strong style="color: #333;">Customer ${i + 1}</strong>
                                    <div style="color: #f90; font-size: 14px;">${'★'.repeat(5 - i)}${'☆'.repeat(i)}</div>
                                </div>
                                <p style="margin: 0; color: #666; font-size: 14px; line-height: 1.6;">Great product! Highly recommended for anyone looking for quality.</p>
                            </div>
                        `).join('')}
                    </div>`;
}