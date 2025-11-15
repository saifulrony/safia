export default // Widget renderer for "testimonial" (auto-generated)
function renderTestimonial(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const testContent = settings.testimonial || settings.content || 'This is an amazing product! I highly recommend it to everyone.';
  const testImage = settings.author_image || settings.image?.url || 'https://i.pravatar.cc/100?img=8';
  const testName = settings.author_name || settings.name || 'Jane Smith';
  const testTitle = settings.author_title || settings.title || 'Marketing Director';
  const testRating = parseInt(settings.rating) || 5;
  const testAlign = settings.alignment || settings.align || 'center';
  let testHTML = `<div style="text-align: ${testAlign}; padding: 40px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: relative; transition: all 0.3s;">`;

  // Quote icon with better styling
  // Quote icon with better styling
  testHTML += `<div style="font-size: 60px; color: #92003b; opacity: 0.15; margin-bottom: 20px; line-height: 1;"><i class="fa fa-quote-left"></i></div>`;

  // Content
  // Content
  testHTML += `<div style="font-size: 16px; line-height: 1.8; margin-bottom: 25px; font-style: italic; color: #4b5563;"><p style="margin: 0;">${testContent}</p></div>`;

  // Rating with stars
  // Rating with stars
  if (testRating > 0) {
    testHTML += `<div style="margin-bottom: 20px; color: #fbbf24; font-size: 18px;">`;
    for (let i = 1; i <= 5; i++) {
      testHTML += i <= testRating ? '★' : '☆';
    }
    testHTML += `</div>`;
  }

  // Author with image
  // Author with image
  testHTML += `<div style="display: flex; align-items: center; justify-content: ${testAlign}; gap: 15px; margin-top: 20px;">
                        <img src="${testImage}" alt="${testName}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid #f3f4f6;">
                        <div style="text-align: left;">
                            <div style="font-weight: 700; margin-bottom: 3px; font-size: 16px; color: #1f2937;">${testName}</div>
                            <div style="color: #92003b; font-size: 14px; font-weight: 500;">${testTitle}</div>
                        </div>
                    </div>`;
  testHTML += `</div>`;
  return testHTML;
}