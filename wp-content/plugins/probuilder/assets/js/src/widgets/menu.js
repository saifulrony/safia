export default // Widget renderer for "menu" (auto-generated)
function renderMenu(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const menuLayout = settings.layout || 'horizontal';
  const menuItems = ['Home', 'About', 'Services', 'Contact'];
  const menuStyle = menuLayout === 'horizontal' ? 'flex-direction:row' : 'flex-direction:column';
  return `<nav style="display:flex;${menuStyle};gap:20px;list-style:none;padding:0;margin:0">
                        ${menuItems.map(item => `<div style="padding:10px 15px;color:#333;cursor:pointer">${item}</div>`).join('')}
                    </nav>`;
}