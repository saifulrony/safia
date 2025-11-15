export default // Widget renderer for "alert" (auto-generated)
function renderAlert(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const alertType = settings.alert_type || 'info';
  const alertTitle = settings.title || 'Information';
  const alertMessage = settings.message || 'This is an alert message.';
  const isDismissible = settings.dismissible !== 'no';
  const showIcon = settings.show_icon !== 'no';
  const accentColor = settings.accent_color || '#92003b';
  const quoteColor = settings.quote_color || '#333333';
  const quoteSize = settings.quote_size || 20;
  const bgColor = settings.background_color || 'transparent';
  const alertPadding = settings.padding || {
    top: 20,
    right: 30,
    bottom: 20,
    left: 30
  };
  const alertMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };

  // Alert type color schemes
  // Alert type color schemes
  const alertColors = {
    'info': {
      bg: '#e3f2fd',
      border: '#2196f3',
      text: '#0d47a1',
      icon: 'fa-circle-info'
    },
    'success': {
      bg: '#e8f5e9',
      border: '#4caf50',
      text: '#1b5e20',
      icon: 'fa-circle-check'
    },
    'warning': {
      bg: '#fff3e0',
      border: '#ff9800',
      text: '#e65100',
      icon: 'fa-triangle-exclamation'
    },
    'error': {
      bg: '#ffebee',
      border: '#f44336',
      text: '#b71c1c',
      icon: 'fa-circle-xmark'
    }
  };
  const colorScheme = alertColors[alertType] || alertColors['info'];
  const finalBg = bgColor !== 'transparent' ? bgColor : colorScheme.bg;
  const finalBorder = accentColor || colorScheme.border;
  const finalText = quoteColor || colorScheme.text;
  let alertHTML = `
                        <div class="probuilder-alert probuilder-alert-${alertType}" style="
                            background: ${finalBg};
                            border-left: 4px solid ${finalBorder};
                            color: ${finalText};
                            padding: ${alertPadding.top}px ${alertPadding.right}px ${alertPadding.bottom}px ${alertPadding.left}px;
                            margin: ${alertMargin.top}px ${alertMargin.right}px ${alertMargin.bottom}px ${alertMargin.left}px;
                            border-radius: 4px;
                            position: relative;
                        ">
                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                    `;

  // Icon
  // Icon
  if (showIcon) {
    alertHTML += `<div style="font-size: 24px; color: ${finalBorder};">
                            <i class="fa ${colorScheme.icon}"></i>
                        </div>`;
  }

  // Content
  // Content
  alertHTML += `
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; font-size: ${quoteSize}px; font-weight: 600; color: ${finalText};">
                                ${alertTitle}
                            </h4>
                    `;
  if (alertMessage) {
    alertHTML += `<p style="margin: 0; font-size: 14px; line-height: 1.6; color: ${finalText}; opacity: 0.9;">
                            ${alertMessage}
                        </p>`;
  }
  alertHTML += '</div>';

  // Close button
  // Close button
  if (isDismissible) {
    alertHTML += `
                            <button class="probuilder-alert-close" style="
                                background: transparent;
                                border: none;
                                color: ${finalText};
                                cursor: pointer;
                                font-size: 20px;
                                padding: 0;
                                opacity: 0.6;
                                transition: opacity 0.2s;
                            " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">
                                <i class="fa fa-times"></i>
                            </button>
                        `;
  }
  alertHTML += '</div></div>';
  return alertHTML;
}