export default // Widget renderer for "share-buttons" (auto-generated)
function renderShareButtons(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const networks = {
    facebook: {
      color: '#1877f2',
      icon: 'fa fa-facebook-f'
    },
    twitter: {
      color: '#1da1f2',
      icon: 'fa fa-twitter'
    },
    linkedin: {
      color: '#0077b5',
      icon: 'fa fa-linkedin-in'
    },
    pinterest: {
      color: '#bd081c',
      icon: 'fa fa-pinterest-p'
    }
  };
  let shareHTML = '<div style="display:flex;gap:10px">';
  Object.keys(networks).forEach(network => {
    shareHTML += `<div style="width:40px;height:40px;background:${networks[network].color};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center"><i class="${networks[network].icon}"></i></div>`;
  });
  shareHTML += '</div>';
  return shareHTML;
}