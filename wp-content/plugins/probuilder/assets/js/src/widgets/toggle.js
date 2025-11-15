export default // Widget renderer for "toggle" (auto-generated)
function renderToggle(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const toggleItems = settings.items || [{
    title: 'What are the system requirements?',
    content: 'Our system works on all modern browsers',
    default_open: 'no'
  }, {
    title: 'How do I get started?',
    content: 'Simply sign up and follow our guide',
    default_open: 'no'
  }];
  const toggleStyle = settings.toggle_style || 'switch';
  const toggleTitleBg = settings.title_bg_color || '#f8f9fa';
  const toggleTitleColor = settings.title_text_color || '#333333';
  const toggleIconColor = settings.toggle_icon_color || '#92003b';
  const toggleTitleSize = settings.title_font_size || 16;
  const toggleContentBg = settings.content_bg_color || '#f9f9f9';
  const toggleContentColor = settings.content_text_color || '#666666';
  const toggleRadius = settings.border_radius || 4;
  const toggleSpacing = settings.item_spacing || 10;
  const toggleMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const toggleId = 'toggle-' + element.id;
  let toggleHTML = `<div class="probuilder-toggle-preview" data-toggle-id="${toggleId}" style="
                        margin: ${toggleMargin.top}px ${toggleMargin.right}px ${toggleMargin.bottom}px ${toggleMargin.left}px;
                    ">`;
  toggleItems.forEach((item, index) => {
    const isOpen = item.default_open === 'yes';
    let titleExtraStyle = '';
    if (toggleStyle === 'bordered') {
      titleExtraStyle = `border: 2px solid ${toggleIconColor};`;
    } else if (toggleStyle === 'simple') {
      titleExtraStyle = `border-bottom: 2px solid #e5e5e5; background: transparent; border-radius: 0;`;
    }
    toggleHTML += `
                            <div class="toggle-item" data-index="${index}" style="margin-bottom: ${toggleSpacing}px;">
                                <div class="toggle-title" style="
                                    background: ${toggleTitleBg};
                                    color: ${toggleTitleColor};
                                    font-size: ${toggleTitleSize}px;
                                    padding: 15px 20px;
                                    cursor: pointer;
                                    border-radius: ${toggleRadius}px;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    font-weight: 600;
                                    transition: all 0.3s ease;
                                    ${titleExtraStyle}
                                ">
                                    <span style="flex: 1;">${item.title}</span>
                                    
                                    ${toggleStyle === 'switch' ? `
                                        <span class="switch-toggle" style="
                                            position: relative;
                                            width: 44px;
                                            height: 24px;
                                            background: ${isOpen ? toggleIconColor : '#cbd5e1'};
                                            border-radius: 12px;
                                            transition: background 0.3s;
                                            display: inline-block;
                                            margin-left: 15px;
                                        ">
                                            <span class="switch-thumb" style="
                                                position: absolute;
                                                top: 2px;
                                                left: ${isOpen ? '22px' : '2px'};
                                                width: 20px;
                                                height: 20px;
                                                background: #ffffff;
                                                border-radius: 10px;
                                                transition: left 0.3s;
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                            "></span>
                                        </span>
                                    ` : `
                                        <span class="toggle-icon" style="transition: transform 0.3s; font-size: 18px; color: ${toggleIconColor}; transform: rotate(${isOpen ? '180deg' : '0deg'});">▼</span>
                                    `}
                                </div>
                                <div class="toggle-content" style="
                                    display: ${isOpen ? 'block' : 'none'};
                                    background: ${toggleContentBg};
                                    color: ${toggleContentColor};
                                    padding: ${isOpen ? '15px 20px' : '0 20px'};
                                    margin-top: 5px;
                                    border-radius: ${toggleRadius}px;
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    max-height: ${isOpen ? '1000px' : '0'};
                                ">
                                    <p style="margin: 0; line-height: 1.6;">${item.content}</p>
                                </div>
                            </div>
                        `;
  });
  toggleHTML += '</div>';
  setTimeout(function () {
    const $toggleContainer = jQuery(`[data-toggle-id="${toggleId}"]`);
    $toggleContainer.find('.toggle-title').off('click').on('click', function (e) {
      e.stopPropagation();
      const $title = jQuery(app);
      const $content = $title.next('.toggle-content');
      const $icon = $title.find('.toggle-icon');
      const $switchToggle = $title.find('.switch-toggle');
      const $switchThumb = $switchToggle.find('.switch-thumb');
      const isOpen = $content.css('display') !== 'none';
      if (isOpen) {
        $content.css({
          'max-height': '0',
          'padding': '0 20px'
        });
        setTimeout(function () {
          $content.css('display', 'none');
        }, 300);
        $icon.css('transform', 'rotate(0deg)');
        $switchToggle.css('background', '#cbd5e1');
        $switchThumb.css('left', '2px');
      } else {
        $content.css({
          'display': 'block',
          'max-height': '1000px',
          'padding': '15px 20px'
        });
        $icon.css('transform', 'rotate(180deg)');
        $switchToggle.css('background', toggleIconColor);
        $switchThumb.css('left', '22px');
      }
    });
  }, 100);
  return toggleHTML;
}