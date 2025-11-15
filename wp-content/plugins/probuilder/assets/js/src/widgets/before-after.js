export default // Widget renderer for "before-after" (auto-generated)
function renderBeforeAfter(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const baBeforeImageUrl = settings.before_image?.url || 'https://via.placeholder.com/800x600/999/fff?text=Before';
  const baAfterImageUrl = settings.after_image?.url || 'https://via.placeholder.com/800x600/92003b/fff?text=After';
  const baBeforeLabel = settings.before_label || 'Before';
  const baAfterLabel = settings.after_label || 'After';
  const baPosition = 50; // Default position

  // Default position

  return `<div style="position: relative; overflow: hidden; border-radius: 8px; max-width: 100%; background: #f5f5f5;">
                        <div style="position: relative; height: 400px; background: #f0f0f0;">
                            <!-- After Image (bottom layer) -->
                            <img src="${baAfterImageUrl}" alt="After" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            
                            <!-- Before Image (top layer with clip) -->
                            <div style="position: absolute; top: 0; left: 0; width: ${baPosition}%; height: 100%; overflow: hidden;">
                                <img src="${baBeforeImageUrl}" alt="Before" style="width: 200%; height: 100%; max-width: none; object-fit: cover;">
                            </div>
                            
                            <!-- Slider -->
                            <div style="position: absolute; top: 0; left: ${baPosition}%; width: 4px; height: 100%; background: #92003b; transform: translateX(-50%); z-index: 2;">
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 40px; background: #92003b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                    <i class="fa fa-arrows-left-right"></i>
                                </div>
                            </div>
                            
                            <!-- Labels -->
                            <div style="position: absolute; top: 20px; left: 20px; background: rgba(0,0,0,0.7); color: #fff; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 600; z-index: 3;">
                                ${baBeforeLabel}
                            </div>
                            <div style="position: absolute; top: 20px; right: 20px; background: rgba(146,0,59,0.9); color: #fff; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 600; z-index: 3;">
                                ${baAfterLabel}
                            </div>
                        </div>
                        <p style="text-align: center; margin: 15px 0 0; color: #666; font-size: 12px;">
                            <i class="fa fa-arrows-left-right"></i> Drag slider to compare before & after
                        </p>
                    </div>`;
}