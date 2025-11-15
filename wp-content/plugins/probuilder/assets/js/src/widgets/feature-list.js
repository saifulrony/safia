export default // Widget renderer for "feature-list" (auto-generated)
function renderFeatureList(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const featureListItems = settings.items || [{
    title: '24/7 Support',
    description: 'Get help whenever you need it',
    icon: 'fa fa-headset'
  }, {
    title: 'Free Updates',
    description: 'Always get the latest features',
    icon: 'fa fa-rocket'
  }, {
    title: 'Money Back',
    description: '30-day refund policy',
    icon: 'fa fa-shield-halved'
  }];
  const featureListLayout = settings.layout || 'grid';
  const featureListColumns = settings.columns || '3';
  const featureListIconColor = settings.icon_color || '#92003b';
  const featureListIconSize = settings.icon_size || 40;
  const featureListIconBg = settings.icon_bg_color || '#f8f9fa';
  const featureListTitleColor = settings.title_color || '#333333';
  const featureListDescColor = settings.description_color || '#666666';
  const featureListShowCard = settings.show_card !== 'no';
  const featureListCardBg = settings.card_bg_color || '#ffffff';
  const featureListMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const featureListPadding = settings.padding || {
    top: 25,
    right: 25,
    bottom: 25,
    left: 25
  };
  let featureListContainerStyle = `margin: ${featureListMargin.top}px ${featureListMargin.right}px ${featureListMargin.bottom}px ${featureListMargin.left}px; `;
  if (featureListLayout === 'grid') {
    featureListContainerStyle += `display: grid; grid-template-columns: repeat(${featureListColumns}, 1fr); gap: 20px;`;
  } else {
    featureListContainerStyle += 'display: flex; flex-direction: column; gap: 20px;';
  }
  let featureListHTML = `<div class="probuilder-feature-list-preview" style="${featureListContainerStyle}">`;
  featureListItems.forEach(item => {
    featureListHTML += `
                            <div class="feature-item" style="
                                ${featureListShowCard ? `background: ${featureListCardBg}; border-radius: 8px; padding: ${featureListPadding.top}px ${featureListPadding.right}px ${featureListPadding.bottom}px ${featureListPadding.left}px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);` : ''}
                                display: flex; flex-direction: column; align-items: flex-start;
                            ">
                                <div style="
                                    display: flex; align-items: center; justify-content: center;
                                    width: ${featureListIconSize + 30}px; height: ${featureListIconSize + 30}px;
                                    background: ${featureListIconBg}; border-radius: 50%;
                                    color: ${featureListIconColor}; font-size: ${featureListIconSize}px;
                                    margin-bottom: 15px;
                                ">
                                    <i class="${item.icon}"></i>
                                </div>
                                <h4 style="margin: 0 0 8px 0; font-size: 18px; color: ${featureListTitleColor}; font-weight: 600;">
                                    ${item.title}
                                </h4>
                                ${item.description ? `<p style="margin: 0; font-size: 14px; color: ${featureListDescColor}; line-height: 1.6;">
                                    ${item.description}
                                </p>` : ''}
                            </div>
                        `;
  });
  featureListHTML += '</div>';
  return featureListHTML;
}