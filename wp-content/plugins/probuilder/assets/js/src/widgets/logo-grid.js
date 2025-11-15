export default // Widget renderer for "logo-grid" (auto-generated)
function renderLogoGrid(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const logoGridLogos = settings.logos || [{
    logo_url: 'https://logo.clearbit.com/google.com',
    name: 'Google',
    link: 'https://google.com'
  }, {
    logo_url: 'https://logo.clearbit.com/microsoft.com',
    name: 'Microsoft',
    link: 'https://microsoft.com'
  }, {
    logo_url: 'https://logo.clearbit.com/apple.com',
    name: 'Apple',
    link: 'https://apple.com'
  }, {
    logo_url: 'https://logo.clearbit.com/amazon.com',
    name: 'Amazon',
    link: 'https://amazon.com'
  }, {
    logo_url: 'https://logo.clearbit.com/facebook.com',
    name: 'Meta',
    link: 'https://facebook.com'
  }, {
    logo_url: 'https://logo.clearbit.com/netflix.com',
    name: 'Netflix',
    link: 'https://netflix.com'
  }];
  const logoColumns = settings.columns || '4';
  const logoGap = settings.gap || 30;
  const logoGrayscale = settings.grayscale !== 'no';
  const logoOpacity = settings.opacity || 0.7;
  const logoBg = settings.bg_color || 'transparent';
  const logoPadding = settings.padding || 20;
  const logoShowBorder = settings.border === 'yes';
  const logoBorderColor = settings.border_color || '#e5e5e5';
  const logoMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  let logoGridHTML = `<div class="probuilder-logo-grid-preview" style="
                        display: grid;
                        grid-template-columns: repeat(${logoColumns}, 1fr);
                        gap: ${logoGap}px;
                        margin: ${logoMargin.top}px ${logoMargin.right}px ${logoMargin.bottom}px ${logoMargin.left}px;
                    ">`;
  logoGridLogos.forEach(logo => {
    logoGridHTML += `
                            <div class="logo-item" style="
                                text-align: center;
                                padding: ${logoPadding}px;
                                background: ${logoBg};
                                ${logoShowBorder ? `border: 1px solid ${logoBorderColor}; border-radius: 8px;` : ''}
                                transition: all 0.3s ease;
                            ">
                                <img src="${logo.logo_url}" alt="${logo.name}" title="${logo.name}" style="
                                    max-width: 100%;
                                    height: auto;
                                    display: block;
                                    margin: 0 auto;
                                    ${logoGrayscale ? 'filter: grayscale(100%);' : ''}
                                    opacity: ${logoOpacity};
                                    transition: all 0.3s ease;
                                ">
                            </div>
                        `;
  });
  logoGridHTML += '</div>';
  setTimeout(function () {
    jQuery('.probuilder-logo-grid-preview .logo-item').on('mouseenter', function () {
      jQuery(app).find('img').css({
        'filter': 'grayscale(0%)',
        'opacity': '1',
        'transform': 'scale(1.05)'
      });
    }).on('mouseleave', function () {
      jQuery(app).find('img').css({
        'filter': logoGrayscale ? 'grayscale(100%)' : 'grayscale(0%)',
        'opacity': logoOpacity,
        'transform': 'scale(1)'
      });
    });
  }, 100);
  return logoGridHTML;
}