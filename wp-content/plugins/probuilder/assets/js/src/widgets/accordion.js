export default // Widget renderer for "accordion" (auto-generated)
function renderAccordion(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const accordionItems = settings.items || [{
    title: 'What is ProBuilder?',
    content: 'ProBuilder is a powerful page builder that allows you to create stunning websites with drag-and-drop functionality.'
  }, {
    title: 'How do I use it?',
    content: 'Simply drag widgets from the left panel onto your canvas and customize them using the settings panel on the right.'
  }, {
    title: 'Is it responsive?',
    content: 'Yes! ProBuilder creates fully responsive designs that work perfectly on all devices.'
  }];
  const allowMultiple = settings.allow_multiple || 'no';
  const defaultOpen = parseInt(settings.default_open) || 1;
  const accordionTitleBg = settings.title_bg_color || '#f8f9fa';
  const accordionTitleText = settings.title_text_color || '#333333';
  const accordionActiveBg = settings.active_bg_color || '#92003b';
  const accordionActiveText = settings.active_text_color || '#ffffff';
  const accordionContentBg = settings.content_bg_color || '#ffffff';
  const accordionContentText = settings.content_text_color || '#666666';
  const accordionBorderColor = settings.border_color || '#e6e9ec';
  const accordionBorderRadius = settings.border_radius || 4;
  const accordionPadding = settings.padding || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const accordionMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const accordionId = 'accordion-' + element.id;
  const accordionContainerStyle = `margin: ${accordionMargin.top}px ${accordionMargin.right}px ${accordionMargin.bottom}px ${accordionMargin.left}px;`;
  let accordionHTML = `<div class="probuilder-accordion-preview" data-accordion-id="${accordionId}" data-allow-multiple="${allowMultiple}" style="width: 100%; ${accordionContainerStyle}">`;
  accordionItems.forEach((item, index) => {
    const isOpen = defaultOpen > 0 && index + 1 === defaultOpen;
    accordionHTML += `
                            <div class="probuilder-accordion-item" data-item-index="${index}" style="margin-bottom: 10px; border: 1px solid ${accordionBorderColor}; border-radius: ${accordionBorderRadius}px; overflow: hidden;">
                                <div class="probuilder-accordion-header" style="
                                    padding: 15px 20px;
                                    background: ${isOpen ? accordionActiveBg : accordionTitleBg};
                                    color: ${isOpen ? accordionActiveText : accordionTitleText};
                                    font-weight: 600;
                                    cursor: pointer;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    transition: all 0.3s ease;
                                    border-radius: ${accordionBorderRadius}px;
                                " data-active-bg="${accordionActiveBg}" data-active-color="${accordionActiveText}" data-inactive-bg="${accordionTitleBg}" data-inactive-color="${accordionTitleText}">
                                    <span>${item.title || `Item ${index + 1}`}</span>
                                    <span class="probuilder-accordion-icon" style="font-size: 18px; transition: all 0.3s;">${isOpen ? '−' : '+'}</span>
                                </div>
                                <div class="probuilder-accordion-content" style="
                                    padding: ${isOpen ? '15px 20px' : '0 20px'};
                                    max-height: ${isOpen ? '500px' : '0'};
                                    background: ${accordionContentBg};
                                    color: ${accordionContentText};
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    opacity: ${isOpen ? '1' : '0'};
                                    border-top: none;
                                ">
                                    ${item.content || 'Content for accordion item'}
                                </div>
                            </div>
                        `;
  });
  accordionHTML += '</div>';

  // Re-attach accordion interactions after render
  setTimeout(function () {
    const $global = window.jQuery || window.$;
    if (!$global) {
      console.warn('Accordion renderer: jQuery not available');
      return;
    }
    const $accordionContainer = $global(`[data-accordion-id="${accordionId}"]`);
    if ($accordionContainer.length === 0) {
      return;
    }
    const allowMultipleOpen = $accordionContainer.data('allow-multiple') === 'yes';

    // Ensure initial open states use natural height (prevents shaky animation)
    $accordionContainer.find('.probuilder-accordion-item').each(function () {
      const $item = $global(this);
      const $header = $item.find('.probuilder-accordion-header');
      const $content = $item.find('.probuilder-accordion-content');
      const isOpen = $header.find('.probuilder-accordion-icon').text() === '−';
      if (isOpen) {
        const naturalHeight = $content.prop('scrollHeight');
        $content.css({
          'max-height': naturalHeight + 'px',
          'padding': '15px 20px',
          'opacity': '1'
        });
      }
    });

    $accordionContainer.find('.probuilder-accordion-header')
      .off('click.probuilderAccordion')
      .on('click.probuilderAccordion', function (e) {
        e.stopPropagation();
        const $header = $global(this);
        const $item = $header.closest('.probuilder-accordion-item');
        const $content = $item.find('.probuilder-accordion-content');
        const $icon = $header.find('.probuilder-accordion-icon');
        const activeBg = $header.data('active-bg');
        const activeColor = $header.data('active-color');
        const inactiveBg = $header.data('inactive-bg');
        const inactiveColor = $header.data('inactive-color');
        const isCurrentlyOpen = $content.hasClass('probuilder-accordion-open');

        if (!allowMultipleOpen && !isCurrentlyOpen) {
          $accordionContainer.find('.probuilder-accordion-content').each(function () {
            const $otherContent = $global(this);
            $otherContent
              .removeClass('probuilder-accordion-open')
              .css({
                'max-height': '0',
                'padding': '0 20px',
                'opacity': '0'
              });
          });
          $accordionContainer.find('.probuilder-accordion-icon').text('+');
          $accordionContainer.find('.probuilder-accordion-header').css({
            'background': inactiveBg,
            'color': inactiveColor
          });
        }

        if (isCurrentlyOpen) {
          $content
            .removeClass('probuilder-accordion-open')
            .css({
              'max-height': '0',
              'padding': '0 20px',
              'opacity': '0'
            });
          $icon.text('+');
          $header.css({
            'background': inactiveBg,
            'color': inactiveColor
          });
        } else {
          const targetHeight = $content.prop('scrollHeight');
          $content
            .addClass('probuilder-accordion-open')
            .css({
              'max-height': targetHeight + 'px',
              'padding': '15px 20px',
              'opacity': '1'
            });
          $icon.text('−');
          $header.css({
            'background': activeBg,
            'color': activeColor
          });
        }
      });
  }, 100);
  return accordionHTML;
}