export default // Widget renderer for "shortcode" (auto-generated)
function renderShortcode(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const shortcodeText = settings.shortcode || '';
  const shortcodeBgColor = settings.bg_color || '';
  const shortcodePadding = settings.padding || {
    top: 20,
    right: 20,
    bottom: 20,
    left: 20
  };
  const shortcodeTextAlign = settings.text_align || 'left';
  let shortcodeHTML = `<div style="
                        ${shortcodeBgColor ? `background: ${shortcodeBgColor};` : ''}
                        padding: ${shortcodePadding.top}px ${shortcodePadding.right}px ${shortcodePadding.bottom}px ${shortcodePadding.left}px;
                        text-align: ${shortcodeTextAlign};
                    ">`;
  if (!shortcodeText || shortcodeText.trim() === '' || shortcodeText === '[contact-form-7 id="123"]') {
    // Show placeholder
    shortcodeHTML += `
                            <div style="padding: 30px; background: #fff3cd; border: 2px dashed #ffc107; border-radius: 8px; text-align: center; color: #856404;">
                                <i class="fa fa-code" style="font-size: 48px; margin-bottom: 15px; opacity: 0.6;"></i>
                                <div style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">Shortcode Widget</div>
                                <div style="font-size: 14px;">Enter a shortcode in the settings to display content</div>
                                <div style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.5); border-radius: 4px; font-size: 13px;">
                                    <strong>Examples:</strong><br>
                                    [gallery]<br>
                                    [contact-form-7 id="123"]<br>
                                    [woocommerce_cart]
                                </div>
                            </div>
                        `;
  } else {
    // Show shortcode preview with actual code
    shortcodeHTML += `
                            <div style="padding: 25px; background: #f0f7ff; border: 2px solid #2196f3; border-radius: 8px; text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px;">
                                    <i class="fa fa-code" style="font-size: 32px; color: #2196f3;"></i>
                                    <div style="font-size: 18px; font-weight: 600; color: #1976d2;">Shortcode Will Execute Here</div>
                                </div>
                                <div style="background: white; padding: 12px 20px; border-radius: 6px; border: 1px solid #bbdefb; margin-top: 15px;">
                                    <code style="color: #d32f2f; font-size: 14px; font-family: 'Courier New', monospace;">${shortcodeText}</code>
                                </div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 12px; opacity: 0.8;">
                                    Preview not available in editor - actual output will show on frontend
                                </div>
                            </div>
                        `;
  }
  shortcodeHTML += `</div>`;
  return shortcodeHTML;
}