export default // Widget renderer for "faq" (auto-generated)
function renderFaq(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const fqFaqTitle = settings.faq_title || 'Frequently Asked Questions';
  const fqFaqDescription = settings.faq_description || 'Find answers to the most common questions about our products and services.';
  const fqFaqLayout = settings.layout || 'accordion';
  const fqAllowMultiple = settings.allow_multiple !== 'yes';
  const fqItemBgColor = settings.item_bg_color || '#ffffff';
  const fqItemBorderColor = settings.item_border_color || '#e1e5e9';
  const fqQuestionColor = settings.question_color || '#1e293b';
  const fqAnswerColor = settings.answer_color || '#64748b';
  const fqIconColor = settings.icon_color || '#92003b';
  const fqActiveColor = settings.active_color || '#92003b';
  const fqBorderRadius = settings.border_radius || {
    size: 8
  };
  let fqFaqHTML = `<div style="max-width: 800px; margin: 0 auto;">`;
  if (fqFaqTitle) {
    fqFaqHTML += `<h2 style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">${fqFaqTitle}</h2>`;
  }
  if (fqFaqDescription) {
    fqFaqHTML += `<p style="color: #64748b; font-size: 16px; text-align: center; margin: 0 0 40px 0;">${fqFaqDescription}</p>`;
  }
  fqFaqHTML += `<div style="display: flex; flex-direction: column; gap: 15px;">`;

  // Sample FAQ items
  // Sample FAQ items
  const sampleFAQs = [{
    question: 'What is your return policy?',
    answer: 'We offer a 30-day return policy for all products in original condition. Simply contact our customer service team to initiate a return.',
    icon: 'fa fa-undo'
  }, {
    question: 'How long does shipping take?',
    answer: 'Standard shipping takes 3-5 business days. Express shipping is available for next-day delivery in most areas.',
    icon: 'fa fa-shipping-fast'
  }, {
    question: 'Do you offer customer support?',
    answer: 'Yes! Our customer support team is available 24/7 via live chat, email, and phone to help with any questions or issues.',
    icon: 'fa fa-headset'
  }];
  sampleFAQs.forEach((faq, index) => {
    const fqItemStyle = `
                            background-color: ${fqItemBgColor};
                            border: 1px solid ${fqItemBorderColor};
                            border-radius: ${fqBorderRadius.size}px;
                            overflow: hidden;
                        `;
    fqFaqHTML += `<div style="${fqItemStyle}">`;
    const fqHeaderStyle = `padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; ${index === 0 ? `background: ${fqActiveColor};` : ''}`;
    fqFaqHTML += `<div style="${fqHeaderStyle}">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="${faq.icon}" style="color: ${fqIconColor}; font-size: 18px; width: 20px; text-align: center;"></i>
                                <h3 style="margin: 0; color: ${fqQuestionColor}; font-size: 18px; font-weight: 600;">${faq.question}</h3>
                            </div>
                            <i class="fa fa-chevron-down" style="color: ${fqIconColor}; font-size: 14px; transform: ${index === 0 ? 'rotate(180deg)' : 'rotate(0deg)'};"></i>
                        </div>`;
    if (index === 0) {
      fqFaqHTML += `<div style="padding: 0 20px 20px 20px; color: ${fqAnswerColor}; line-height: 1.6;">
                                <p style="margin: 0;">${faq.answer}</p>
                            </div>`;
    }
    fqFaqHTML += `</div>`;
  });
  fqFaqHTML += `</div></div>`;
  return fqFaqHTML;
}