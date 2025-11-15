export default // Widget renderer for "timeline" (auto-generated)
function renderTimeline(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const tlTimelineTitle = settings.timeline_title || 'Our Journey';
  const tlTimelineDescription = settings.timeline_description || 'Follow our journey from the beginning to where we are today.';
  const tlTimelineLayout = settings.layout || 'vertical';
  const tlShowConnector = settings.show_connector !== 'no';
  const tlItemBgColor = settings.item_bg_color || '#ffffff';
  const tlItemBorderColor = settings.item_border_color || '#e1e5e9';
  const tlDateColor = settings.date_color || '#92003b';
  const tlIconBgColor = settings.icon_bg_color || '#92003b';
  const tlIconColor = settings.icon_color || '#ffffff';
  const tlConnectorColor = settings.connector_color || '#e1e5e9';
  const tlBorderRadius = settings.border_radius || {
    size: 8
  };
  let tlTimelineHTML = `<div>`;
  if (tlTimelineTitle) {
    tlTimelineHTML += `<h2 style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">${tlTimelineTitle}</h2>`;
  }
  if (tlTimelineDescription) {
    tlTimelineHTML += `<p style="color: #64748b; font-size: 16px; text-align: center; margin: 0 0 50px 0;">${tlTimelineDescription}</p>`;
  }
  if (tlTimelineLayout === 'vertical') {
    tlTimelineHTML += `<div style="position: relative; max-width: 800px; margin: 0 auto;">`;
    if (tlShowConnector) {
      tlTimelineHTML += `<div style="position: absolute; left: 30px; top: 0; bottom: 0; width: 2px; background-color: ${tlConnectorColor};"></div>`;
    }

    // Sample timeline items
    const sampleItems = [{
      date: '2020',
      title: 'Company Founded',
      description: 'We started our journey with a vision to revolutionize the industry and provide innovative solutions.',
      icon: 'fa fa-rocket'
    }, {
      date: '2021',
      title: 'First Product Launch',
      description: 'Launched our flagship product that quickly gained recognition in the market.',
      icon: 'fa fa-star'
    }, {
      date: '2022',
      title: 'International Expansion',
      description: 'Expanded our operations to serve customers across multiple countries.',
      icon: 'fa fa-globe'
    }];
    sampleItems.forEach((item, index) => {
      const tlItemStyle = `
                                background-color: ${tlItemBgColor};
                                border: 1px solid ${tlItemBorderColor};
                                border-radius: ${tlBorderRadius.size}px;
                                padding: 25px;
                                margin-left: 80px;
                                margin-bottom: 30px;
                                position: relative;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            `;
      tlTimelineHTML += `<div style="${tlItemStyle}">
                                <div style="position: absolute; left: -60px; top: 25px; width: 40px; height: 40px; background-color: ${tlIconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;">
                                    <i class="${item.icon}" style="color: ${tlIconColor}; font-size: 16px;"></i>
                                </div>
                                <div style="color: ${tlDateColor}; font-size: 14px; font-weight: 600; margin-bottom: 10px;">${item.date}</div>
                                <h3 style="color: #1e293b; font-size: 20px; font-weight: 600; margin: 0 0 15px 0;">${item.title}</h3>
                                <p style="color: #64748b; margin: 0; line-height: 1.6;">${item.description}</p>
                            </div>`;
    });
    tlTimelineHTML += `</div>`;
  } else {
    tlTimelineHTML += `<div style="display: flex; overflow-x: auto; gap: 30px; padding: 20px 0;">`;
    const sampleItems = [{
      date: '2020',
      title: 'Company Founded',
      description: 'We started our journey with a vision to revolutionize the industry.',
      icon: 'fa fa-rocket'
    }, {
      date: '2021',
      title: 'First Product Launch',
      description: 'Launched our flagship product that quickly gained recognition.',
      icon: 'fa fa-star'
    }, {
      date: '2022',
      title: 'International Expansion',
      description: 'Expanded our operations to serve customers worldwide.',
      icon: 'fa fa-globe'
    }];
    sampleItems.forEach((item, index) => {
      const tlItemStyle = `
                                background-color: ${tlItemBgColor};
                                border: 1px solid ${tlItemBorderColor};
                                border-radius: ${tlBorderRadius.size}px;
                                padding: 25px;
                                min-width: 300px;
                                flex-shrink: 0;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            `;
      tlTimelineHTML += `<div style="${tlItemStyle}">
                                <div style="width: 50px; height: 50px; background-color: ${tlIconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                                    <i class="${item.icon}" style="color: ${tlIconColor}; font-size: 20px;"></i>
                                </div>
                                <div style="color: ${tlDateColor}; font-size: 14px; font-weight: 600; margin-bottom: 10px; text-align: center;">${item.date}</div>
                                <h3 style="color: #1e293b; font-size: 18px; font-weight: 600; margin: 0 0 15px 0; text-align: center;">${item.title}</h3>
                                <p style="color: #64748b; margin: 0; line-height: 1.6; text-align: center;">${item.description}</p>
                            </div>`;
    });
    tlTimelineHTML += `</div>`;
  }
  tlTimelineHTML += `</div>`;
  return tlTimelineHTML;
}