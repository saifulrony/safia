export default // Widget renderer for "woo-related" (auto-generated)
function renderWooRelated(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const relatedCount = settings.posts_per_page || 4;
  const relatedColumns = settings.columns || 4;
  return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin: 0 0 20px; font-size: 22px; color: #333;">Related Products</h3>
                        <div style="display: grid; grid-template-columns: repeat(${Math.min(relatedColumns, 4)}, 1fr); gap: 15px;">
                            ${Array(Math.min(relatedCount, 4)).fill('').map((_, i) => `
                                <div style="background: #fff; padding: 15px; border-radius: 4px; text-align: center;">
                                    <div style="width: 100%; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 4px; margin-bottom: 10px;"></div>
                                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 5px;">Product ${i + 1}</div>
                                    <div style="color: #92003b; font-weight: 600;">$${(i + 1) * 10}.00</div>
                                </div>
                            `).join('')}
                        </div>
                    </div>`;
}