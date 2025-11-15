export default // Widget renderer for "wp-footer" (auto-generated)
function renderWpFooter(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const footerId = settings.footer_id || '';
  const footerLayout = settings.footer_layout || 'columns';
  const footerColumns = settings.columns || '3';
  const showCopyright = settings.show_copyright !== 'no';
  const copyrightText = settings.copyright_text || '© 2025 Your Site. All rights reserved.';
  const footerBgColor = settings.bg_color || '#1f2937';
  const footerTextColor = settings.text_color || '#e5e7eb';
  const footerLinkColor = settings.link_color || '#93c5fd';
  const footerPadding = settings.padding || {
    top: 60,
    right: 30,
    bottom: 30,
    left: 30
  };
  const copyrightBg = settings.copyright_bg || '#111827';
  const footerStyle = `
                        background: ${footerBgColor};
                        color: ${footerTextColor};
                        padding: ${footerPadding.top}px ${footerPadding.right}px ${footerPadding.bottom}px ${footerPadding.left}px;
                    `;
  let footerHTML = `<div style="${footerStyle}">`;
  if (!footerId) {
    footerHTML += `
                            <div style="padding: 30px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3); border-radius: 8px; text-align: center;">
                                <i class="fa fa-window-minimize" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>
                                <div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">WordPress Footer</div>
                                <div style="font-size: 13px; opacity: 0.9;">Select a footer widget area from the settings</div>
                            </div>
                        `;
  } else {
    const contentStyle = footerLayout === 'columns' ? `display: grid; grid-template-columns: repeat(${footerColumns}, 1fr); gap: 30px;` : `display: flex; flex-direction: column; gap: 20px;`;
    footerHTML += `<div style="${contentStyle}">`;
    for (let i = 0; i < parseInt(footerColumns); i++) {
      footerHTML += `
                                <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 6px;">
                                    <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">Footer Widget ${i + 1}</h3>
                                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; line-height: 2;">
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 1}</a></li>
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 2}</a></li>
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 3}</a></li>
                                    </ul>
                                </div>
                            `;
    }
    footerHTML += `</div>`;
  }

  // Copyright
  // Copyright
  if (showCopyright) {
    footerHTML += `
                            <div style="background: ${copyrightBg}; text-align: center; padding: 20px; margin-top: 30px; font-size: 14px;">
                                ${copyrightText}
                            </div>
                        `;
  }
  footerHTML += `</div>`;
  return footerHTML;
}