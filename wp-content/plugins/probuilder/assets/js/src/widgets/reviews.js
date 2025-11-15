export default // Widget renderer for "reviews" (auto-generated)
function renderReviews(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const reviewItems = settings.reviews || [{
    name: 'John Doe',
    rating: 5,
    review: 'Excellent service!'
  }, {
    name: 'Jane Smith',
    rating: 5,
    review: 'Highly recommended!'
  }];
  const reviewColumns = settings.columns || '2';
  let reviewsHTML = `<div style="display:grid;grid-template-columns:repeat(${reviewColumns},1fr);gap:20px">`;
  reviewItems.forEach(review => {
    const stars = '★'.repeat(review.rating || 5) + '☆'.repeat(5 - (review.rating || 5));
    reviewsHTML += `
                            <div style="background:#f9f9f9;padding:20px;border-radius:8px">
                                <div style="color:#ffc107;font-size:18px;margin-bottom:10px">${stars}</div>
                                <h4 style="margin:0 0 10px;font-size:18px">${review.name || 'Customer'}</h4>
                                <p style="margin:0;color:#666;line-height:1.6">${review.review || 'Great experience!'}</p>
                            </div>
                        `;
  });
  reviewsHTML += '</div>';
  return reviewsHTML;
}