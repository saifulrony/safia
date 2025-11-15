export default // Widget renderer for "woo-cart" (auto-generated)
function renderWooCart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const cartShowIcon = settings.show_icon !== 'no';
  const cartIcon = settings.icon || 'fa fa-shopping-cart';
  const cartCount = settings.show_count !== 'no';
  const cartAmount = settings.show_amount !== 'no';
  return `<div style="padding: 15px 25px; background: #92003b; color: #fff; border-radius: 4px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
                        ${cartShowIcon ? `<i class="${cartIcon}" style="font-size: 20px;"></i>` : ''}
                        <span style="font-weight: 600;">Cart</span>
                        ${cartCount ? '<span style="background: #fff; color: #92003b; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">3</span>' : ''}
                        ${cartAmount ? '<span style="margin-left: 5px; font-size: 14px;">$129.00</span>' : ''}
                    </div>`;
}