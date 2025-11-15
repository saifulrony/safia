export default // Widget renderer for "icon-list" (auto-generated)
function renderIconList(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const iconListItems = settings.items || [{
    text: 'Professional Design',
    icon: 'fa fa-check-circle'
  }, {
    text: 'Fast Performance',
    icon: 'fa fa-check-circle'
  }, {
    text: 'Responsive Layout',
    icon: 'fa fa-check-circle'
  }];
  const iconListLayout = settings.layout || 'vertical';
  const iconListColumns = settings.columns || '2';
  const iconListIconColor = settings.icon_color || '#92003b';
  const iconListIconSize = settings.icon_size || 20;
  const iconListTextColor = settings.text_color || '#333333';
  const iconListTextSize = settings.text_size || 16;
  const iconListMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  let iconListContainerStyle = `list-style: none; margin: ${iconListMargin.top}px ${iconListMargin.right}px ${iconListMargin.bottom}px ${iconListMargin.left}px; padding: 0; `;
  if (iconListLayout === 'grid') {
    iconListContainerStyle += `display: grid; grid-template-columns: repeat(${iconListColumns}, 1fr); gap: 15px;`;
  } else if (iconListLayout === 'horizontal') {
    iconListContainerStyle += 'display: flex; flex-wrap: wrap; gap: 15px 30px;';
  } else {
    iconListContainerStyle += 'display: flex; flex-direction: column; gap: 15px;';
  }
  let iconListHTML = `<ul class="probuilder-icon-list-preview" style="${iconListContainerStyle}">`;
  iconListItems.forEach(item => {
    iconListHTML += `
                            <li style="display: flex; align-items: center;">
                                <span style="color: ${iconListIconColor}; margin-right: 12px; font-size: ${iconListIconSize}px;">
                                    <i class="${item.icon}"></i>
                                </span>
                                <span style="color: ${iconListTextColor}; font-size: ${iconListTextSize}px;">
                                    ${item.text}
                                </span>
                            </li>
                        `;
  });
  iconListHTML += '</ul>';
  return iconListHTML;
}