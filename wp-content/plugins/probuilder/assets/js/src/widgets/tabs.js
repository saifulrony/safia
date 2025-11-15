export default // Widget renderer for "tabs" (auto-generated)
function renderTabs(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const tabItems = Array.isArray(settings.tabs) ? settings.tabs : [{
    tab_title: 'Tab 1',
    children: []
  }, {
    tab_title: 'Tab 2',
    children: []
  }, {
    tab_title: 'Tab 3',
    children: []
  }];
  const tabsStyle = settings.style || 'horizontal';
  const activeTabBg = settings.active_bg_color || '#92003b';
  const activeTabColor = settings.active_text_color || '#ffffff';
  const inactiveTabBg = settings.inactive_bg_color || '#f3f4f6';
  const inactiveTabColor = settings.inactive_text_color || '#333333';
  const contentBg = settings.content_bg_color || '#ffffff';
  const contentColor = settings.content_text_color || '#666666';

  // Ensure element has tabChildren array
  // Ensure element has tabChildren array
  if (!element.tabChildren) {
    element.tabChildren = tabItems.map(() => []);
  }
  const uniqueId = 'tabs-' + element.id;
  let tabsHTML = `<div class="probuilder-tabs-preview" data-tabs-id="${uniqueId}" data-element-id="${element.id}" style="width: 100%;">`;

  // Tab headers
  // Tab headers
  tabsHTML += '<div class="probuilder-tabs-header" style="display: flex; gap: 5px; border-bottom: 2px solid #e6e9ec; margin-bottom: 0;">';
  tabItems.forEach((tab, index) => {
    tabsHTML += `
                            <div class="probuilder-tab-header" data-tab-index="${index}" style="
                                padding: 12px 24px;
                                background: ${index === 0 ? activeTabBg : inactiveTabBg};
                                color: ${index === 0 ? activeTabColor : inactiveTabColor};
                                cursor: pointer;
                                border-radius: 3px 3px 0 0;
                                font-weight: ${index === 0 ? '600' : '400'};
                                transition: all 0.3s;
                                margin-bottom: -2px;
                                border-bottom: ${index === 0 ? `2px solid ${activeTabBg}` : 'none'};
                            " data-active-bg="${activeTabBg}" data-active-color="${activeTabColor}" data-inactive-bg="${inactiveTabBg}" data-inactive-color="${inactiveTabColor}">
                                ${tab.tab_title || tab.title || `Tab ${index + 1}`}
                            </div>
                        `;
  });
  tabsHTML += '</div>';

  // Tab contents (all tabs, hidden except first)
  // Tab contents (all tabs, hidden except first)
  tabsHTML += '<div class="probuilder-tabs-contents">';
  tabItems.forEach((tab, index) => {
    const tabChildren = element.tabChildren[index] || [];
    const hasChildren = tabChildren.length > 0;
    tabsHTML += `
                            <div class="probuilder-tabs-content probuilder-tab-drop-zone" data-tab-content="${index}" data-tab-index="${index}" data-element-id="${element.id}" style="
                                padding: 20px;
                                background: ${contentBg};
                                color: ${contentColor};
                                border: 1px solid #e6e9ec;
                                border-top: none;
                                border-radius: 0 3px 3px 3px;
                                min-height: 150px;
                                display: ${index === 0 ? 'block' : 'none'};
                            ">`;
    if (hasChildren) {
      // Render nested elements
      tabChildren.forEach(child => {
        const childWidget = app.widgets.find(w => w.name === child.widgetType);
        const childPreview = app.generatePreview(child, depth + 1);
        tabsHTML += `
                                    <div class="probuilder-nested-element" data-id="${child.id}" data-widget="${child.widgetType}" style="position: relative; z-index: 1; margin-bottom: 10px;">
                                        <div class="probuilder-nested-controls" style="
                                            position: absolute;
                                            top: 0;
                                            right: 0;
                                            display: none;
                                            gap: 4px;
                                            z-index: 100;
                                            background: rgba(255, 255, 255, 0.95);
                                            padding: 4px;
                                            border-radius: 3px;
                                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                        ">
                                            <button class="probuilder-nested-drag" title="Move" style="background: #71717a; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: move; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-move" style="font-size: 12px;"></i>
                                            </button>
                                            <button class="probuilder-nested-edit" title="Edit" style="background: #92003b; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-edit" style="font-size: 12px;"></i>
                                            </button>
                                            <button class="probuilder-nested-delete" title="Delete" style="background: #dc2626; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                        <div class="probuilder-nested-preview">
                                            ${childPreview}
                                        </div>
                                    </div>
                                `;
      });
    } else {
      // Show drop zone
      tabsHTML += `
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 130px; color: #a4afb7; cursor: pointer;" class="probuilder-tab-empty-zone">
                                    <i class="dashicons dashicons-plus" style="font-size: 32px; opacity: 0.4; margin-bottom: 8px;"></i>
                                    <span style="font-size: 14px; opacity: 0.7;">Click to add widget or drag & drop here</span>
                                </div>
                            `;
    }
    tabsHTML += `</div>`;
  });
  tabsHTML += '</div>';
  tabsHTML += '</div>';

  // Add interactive script after a short delay to ensure DOM is ready
  // Add interactive script after a short delay to ensure DOM is ready
  setTimeout(function () {
    const $tabsContainer = $(`[data-tabs-id="${uniqueId}"]`);
    if ($tabsContainer.length === 0) {
      console.warn('Tabs container not found:', uniqueId);
      return;
    }
    console.log('✅ Attaching tab switching handlers for:', uniqueId);

    // Tab switching - Use event delegation for reliability
    $tabsContainer.off('click.tabSwitch').on('click.tabSwitch', '.probuilder-tab-header', function (e) {
      e.stopPropagation();
      e.preventDefault();
      const $header = $(app);
      const tabIndex = $header.data('tab-index');
      const activeBg = $header.data('active-bg');
      const activeColor = $header.data('active-color');
      const inactiveBg = $header.data('inactive-bg');
      const inactiveColor = $header.data('inactive-color');
      console.log('🔄 Switching to tab:', tabIndex);

      // Update all tab headers
      $tabsContainer.find('.probuilder-tab-header').each(function () {
        const $tab = $(app);
        const isActive = $tab.data('tab-index') === tabIndex;
        $tab.css({
          'background': isActive ? activeBg : inactiveBg,
          'color': isActive ? activeColor : inactiveColor,
          'font-weight': isActive ? '600' : '400',
          'border-bottom': isActive ? `2px solid ${activeBg}` : 'none'
        });
      });

      // Show active content, hide others
      $tabsContainer.find('.probuilder-tabs-content').each(function () {
        const $content = $(app);
        const contentIndex = $content.data('tab-content');
        const shouldShow = contentIndex === tabIndex;
        $content.css('display', shouldShow ? 'block' : 'none');
        console.log(`  Tab ${contentIndex}: ${shouldShow ? 'SHOW' : 'hide'}`);
      });
    });

    // Make tab content droppable
    ProBuilder.makeTabsDroppable(element);

    // Attach handlers to nested elements
    ProBuilder.attachTabNestedHandlers(element, $tabsContainer);

    // Click empty zone to add widget
    $tabsContainer.find('.probuilder-tab-empty-zone').off('click').on('click', function (e) {
      e.stopPropagation();
      const tabIndex = $(app).closest('.probuilder-tabs-content').data('tab-index');
      console.log('Tab empty zone clicked:', tabIndex);
      ProBuilder.showWidgetPickerForTab(element.id, tabIndex);
    });
  }, 100);
  return tabsHTML;
  const tabsItems = settings.tabs || [{
    tab_title: 'Tab #1',
    tab_icon: 'fa fa-home',
    tab_content: 'Tab content goes here.'
  }, {
    tab_title: 'Tab #2',
    tab_icon: 'fa fa-star',
    tab_content: 'Tab content goes here.'
  }, {
    tab_title: 'Tab #3',
    tab_icon: 'fa fa-heart',
    tab_content: 'Tab content goes here.'
  }];
  const tabOrientation = settings.tab_orientation || 'horizontal';
  const tabAlignment = settings.tab_alignment || 'left';
  const verticalTabWidth = settings.vertical_tab_width || 25;
  const tabBg = settings.tab_bg_color || '#f5f5f5';
  const tabActiveBg = settings.tab_active_bg_color || '#ffffff';
  const tabText = settings.tab_text_color || '#333333';
  const tabActiveText = settings.tab_active_text_color || '#007cba';
  const tabBorderColor = settings.tab_border_color || '#ddd';
  const tabBorderWidth = settings.tab_border_width || 1;
  const tabBorderRadius = settings.tab_border_radius || 4;
  const tabPadding = settings.tab_padding || 15;
  const contentPadding = settings.content_padding || 20;
  let tabsPreviewHTML = `
                        <div style="
                            display: ${tabOrientation === 'vertical' ? 'flex' : 'block'};
                            border: ${tabBorderWidth}px solid ${tabBorderColor};
                            border-radius: ${tabBorderRadius}px;
                            overflow: hidden;
                        ">
                    `;

  // Tab navigation
  // Tab navigation
  tabsPreviewHTML += `
                        <div style="
                            ${tabOrientation === 'vertical' ? `
                                width: ${verticalTabWidth}%;
                                flex-shrink: 0;
                                border-right: ${tabBorderWidth}px solid ${tabBorderColor};
                            ` : `
                                display: flex;
                                ${tabAlignment === 'center' ? 'justify-content: center;' : ''}
                                ${tabAlignment === 'right' ? 'justify-content: flex-end;' : ''}
                                ${tabAlignment === 'justified' ? 'justify-content: space-between;' : ''}
                                border-bottom: ${tabBorderWidth}px solid ${tabBorderColor};
                            `}
                        ">
                    `;
  tabsItems.forEach((tab, index) => {
    const isActive = index === 0;
    const isLast = index === tabsItems.length - 1;
    tabsPreviewHTML += `
                            <div style="
                                padding: ${tabPadding}px ${tabPadding * 1.5}px;
                                background: ${isActive ? tabActiveBg : tabBg};
                                color: ${isActive ? tabActiveText : tabText};
                                font-weight: ${isActive ? '600' : '400'};
                                cursor: pointer;
                                ${tabOrientation === 'horizontal' ? `
                                    display: inline-block;
                                    border-top-left-radius: ${tabBorderRadius}px;
                                    border-top-right-radius: ${tabBorderRadius}px;
                                    ${tabAlignment === 'justified' ? 'flex: 1; text-align: center;' : ''}
                                ` : ''}
                                ${tabOrientation === 'vertical' ? `
                                    display: block;
                                    width: 100%;
                                    ${index === 0 ? `border-top-left-radius: ${tabBorderRadius}px;` : ''}
                                    ${isLast ? `border-bottom-left-radius: ${tabBorderRadius}px;` : ''}
                                    ${!isLast ? `border-bottom: ${tabBorderWidth}px solid ${tabBorderColor};` : ''}
                                    text-align: left;
                                ` : ''}
                            ">
                                ${tab.tab_icon ? `<i class="${tab.tab_icon}" style="margin-right: 8px;"></i>` : ''}
                                ${tab.tab_title}
                            </div>
                        `;
  });
  tabsPreviewHTML += '</div>';

  // Tab content
  // Tab content
  tabsPreviewHTML += `
                        <div style="
                            ${tabOrientation === 'vertical' ? 'flex: 1;' : ''}
                            padding: ${contentPadding}px;
                            background: ${tabActiveBg};
                            min-height: 150px;
                            ${tabOrientation === 'vertical' ? `
                                border-radius: 0 ${tabBorderRadius}px ${tabBorderRadius}px 0;
                            ` : `
                                border-radius: 0 0 ${tabBorderRadius}px ${tabBorderRadius}px;
                            `}
                        ">
                            <div style="color: #666; line-height: 1.6;">
                                ${tabsItems[0].tab_content}
                            </div>
                        </div>
                    `;
  tabsPreviewHTML += '</div>';
  return tabsPreviewHTML;
}