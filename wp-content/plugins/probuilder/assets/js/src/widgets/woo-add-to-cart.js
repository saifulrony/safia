export default // Widget renderer for "woo-add-to-cart" (auto-generated)
function renderWooAddToCart(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const wcBtnText = settings.button_text || 'Add to Cart';
  const wcBtnColor = settings.button_color || '#92003b';
  const wcShowQty = settings.show_quantity !== 'no';
  return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            ${wcShowQty ? '<input type="number" value="1" min="1" style="width: 60px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">' : ''}
                            <button style="background: ${wcBtnColor}; color: #fff; border: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; cursor: pointer; flex: 1; font-size: 15px;">
                                <i class="fa fa-cart-plus"></i> ${wcBtnText}
                            </button>
                        </div>
                        <p style="margin-top: 10px; color: #666; font-size: 12px; text-align: center;">
                            <i class="fa fa-shopping-cart"></i> WooCommerce Add to Cart Button
                        </p>
                    </div>`;
}