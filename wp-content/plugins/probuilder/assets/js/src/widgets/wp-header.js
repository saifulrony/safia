export default // Widget renderer for "wp-header" (auto-generated)
function renderWpHeader(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const headerMenuId = settings.menu_id || '';
  const headerType = settings.header_type || 'horizontal';
  const showLogo = settings.show_logo !== 'no';
  const customLogo = settings.custom_logo?.url || '';
  const logoWidth = settings.logo_width || 150;
  const headerBgColor = settings.bg_color || '#ffffff';
  const headerMenuColor = settings.menu_color || '#333333';
  const headerPadding = settings.padding || {
    top: 20,
    right: 30,
    bottom: 20,
    left: 30
  };
  const headerShadow = settings.box_shadow !== 'no';
  const headerStyle = `
                        background: ${headerBgColor};
                        padding: ${headerPadding.top}px ${headerPadding.right}px ${headerPadding.bottom}px ${headerPadding.left}px;
                        display: flex;
                        align-items: center;
                        ${headerType === 'horizontal' ? 'justify-content: space-between; flex-direction: row;' : 'flex-direction: column; gap: 20px;'}
                        ${headerShadow ? 'box-shadow: 0 2px 10px rgba(0,0,0,0.1);' : ''}
                    `;
  let headerHTML = `<div style="${headerStyle}">`;

  // Logo
  // Logo
  if (showLogo) {
    const logoSrc = customLogo || 'https://via.placeholder.com/150x50/92003b/ffffff?text=LOGO';
    headerHTML += `<div style="flex-shrink: 0;">
                            <img src="${logoSrc}" alt="Logo" style="max-width: ${logoWidth}px; height: auto; display: block;">
                        </div>`;
  }

  // Menu
  // Menu
  headerHTML += `<nav style="flex-grow: 1;">
                        <ul style="list-style: none; margin: 0; padding: 0; display: flex; ${headerType === 'horizontal' ? 'flex-direction: row; gap: 30px; justify-content: flex-end;' : 'flex-direction: column; gap: 15px; align-items: center;'}">
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Home</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">About</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Services</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Contact</a></li>
                        </ul>
                    </nav>`;
  headerHTML += `</div>`;
  return headerHTML;
}