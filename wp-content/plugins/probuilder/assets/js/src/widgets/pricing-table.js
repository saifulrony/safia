export default // Widget renderer for "pricing-table" (auto-generated)
function renderPricingTable(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const priceTitle = settings.title || 'Basic Plan';
  const priceCurrency = settings.currency || '$';
  const priceAmount = settings.price || '29';
  const pricePeriod = settings.period || 'per month';
  const priceFeatures = Array.isArray(settings.features) ? settings.features : [{
    text: 'Feature 1'
  }, {
    text: 'Feature 2'
  }, {
    text: 'Feature 3'
  }];
  const priceButtonText = settings.button_text || 'Get Started';
  const priceFeatured = settings.featured === 'yes';
  const pricingBoxStyle = `
                        border: 2px solid ${priceFeatured ? '#0073aa' : '#e5e5e5'};
                        padding: 40px 30px;
                        text-align: center;
                        background: #ffffff;
                        position: relative;
                        border-radius: 8px;
                        transition: all 0.3s;
                        min-height: 400px;
                    `;
  let pricingHTML = `<div style="${pricingBoxStyle}">`;

  // Featured badge
  // Featured badge
  if (priceFeatured) {
    pricingHTML += `
                            <div style="
                                position: absolute;
                                top: 20px;
                                right: 20px;
                                background: #0073aa;
                                color: white;
                                padding: 5px 15px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: bold;
                            ">POPULAR</div>
                        `;
  }

  // Title
  // Title
  pricingHTML += `<h3 style="font-size: 24px; margin: 0 0 20px 0; font-weight: 600;">${priceTitle}</h3>`;

  // Price
  // Price
  pricingHTML += `
                        <div style="margin-bottom: 30px;">
                            <span style="font-size: 24px; vertical-align: top; font-weight: 600;">${priceCurrency}</span>
                            <span style="font-size: 60px; font-weight: bold; line-height: 1; color: #333;">${priceAmount}</span>
                            <div style="color: #666; font-size: 14px; margin-top: 5px;">${pricePeriod}</div>
                        </div>
                    `;

  // Features
  // Features
  pricingHTML += '<ul style="list-style: none; margin: 0 0 30px 0; padding: 0; text-align: left;">';
  priceFeatures.forEach(feature => {
    pricingHTML += `
                            <li style="
                                padding: 10px 0;
                                border-bottom: 1px solid #f0f0f0;
                                color: #555;
                                position: relative;
                                padding-left: 25px;
                            ">
                                <i class="dashicons dashicons-yes" style="
                                    position: absolute;
                                    left: 0;
                                    top: 10px;
                                    color: #0073aa;
                                    font-size: 18px;
                                "></i>
                                ${feature.text || 'Feature'}
                            </li>
                        `;
  });
  pricingHTML += '</ul>';

  // Button
  // Button
  pricingHTML += `
                        <a href="#" style="
                            background: ${priceFeatured ? '#0073aa' : '#333333'};
                            color: white;
                            padding: 15px 40px;
                            text-decoration: none;
                            display: inline-block;
                            border-radius: 5px;
                            font-weight: bold;
                            transition: all 0.3s;
                        " onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                            ${priceButtonText}
                        </a>
                    `;
  pricingHTML += '</div>';
  return pricingHTML;
}