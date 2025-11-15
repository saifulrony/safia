export default // Widget renderer for "social-icons" (auto-generated)
function renderSocialIcons(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const socialItems = settings.social_items || [{
    platform: 'facebook',
    url: '#',
    icon: 'fab fa-facebook-f',
    color: '#3b5998'
  }, {
    platform: 'twitter',
    url: '#',
    icon: 'fab fa-twitter',
    color: '#1da1f2'
  }, {
    platform: 'instagram',
    url: '#',
    icon: 'fab fa-instagram',
    color: '#E4405F'
  }, {
    platform: 'linkedin',
    url: '#',
    icon: 'fab fa-linkedin-in',
    color: '#0077b5'
  }];
  const socialAlign = settings.align || 'center';
  const socialIconSize = settings.icon_size || 20;
  const socialBoxSize = settings.icon_box_size || 45;
  const socialSpacing = settings.icon_spacing || 10;
  const socialBgColor = settings.icon_bg_color || '#333333';
  const socialIconColor = settings.icon_color || '#ffffff';
  let socialIconsHTML = '';
  socialItems.forEach(item => {
    socialIconsHTML += `<a href="${item.url || '#'}" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: ${socialBoxSize}px; height: ${socialBoxSize}px; background: ${socialBgColor}; color: ${socialIconColor}; border-radius: 50%; margin: 0 ${socialSpacing / 2}px; text-decoration: none; transition: all 0.3s; font-size: ${socialIconSize}px;">
                            <i class="${item.icon}"></i>
                        </a>`;
  });
  return `<div style="text-align: ${socialAlign}; padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <div style="display: inline-block;">${socialIconsHTML}</div>
                        <p style="margin-top: 15px; color: #666; font-size: 12px; text-align: center;">
                            <i class="fa fa-share-nodes"></i> Social Media Links
                        </p>
                    </div>`;
}