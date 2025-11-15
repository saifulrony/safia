export default // Widget renderer for "price-list" (auto-generated)
function renderPriceList(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const priceItems = settings.items || [{
    title: 'Service 1',
    price: '$50',
    description: 'Description'
  }];
  return priceItems.map(item => `
                        <div style="display:flex;justify-content:space-between;padding:20px 0;border-bottom:1px solid #eee">
                            <div><h4 style="margin:0 0 8px;font-size:18px">${item.title || 'Service'}</h4>
                            <p style="margin:0;color:#666;font-size:14px">${item.description || ''}</p></div>
                            <div style="font-size:20px;font-weight:700;color:#0073aa">${item.price || '$50'}</div>
                        </div>
                    `).join('');
}