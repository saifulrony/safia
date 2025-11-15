export default // Widget renderer for "notification" (auto-generated)
function renderNotification(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const notifMessage = settings.message || 'Important announcement!';
  const notifType = settings.type || 'info';
  const notifDismissible = settings.dismissible !== false;
  const notifColors = {
    info: {
      bg: '#2196f3',
      icon: 'fa fa-info-circle'
    },
    success: {
      bg: '#4caf50',
      icon: 'fa fa-check-circle'
    },
    warning: {
      bg: '#ff9800',
      icon: 'fa fa-exclamation-triangle'
    },
    error: {
      bg: '#f44336',
      icon: 'fa fa-times-circle'
    }
  };
  const notifColor = notifColors[notifType];
  return `<div style="background:${notifColor.bg};color:#fff;padding:15px 20px;border-radius:8px;position:relative;text-align:center">
                        <i class="${notifColor.icon}" style="margin-right:10px;font-size:18px"></i>
                        <strong>${notifMessage}</strong>
                        ${notifDismissible ? '<button style="position:absolute;right:15px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:24px;cursor:pointer;line-height:1">×</button>' : ''}
                        <div style="margin-top:10px;font-size:11px;opacity:0.8">
                            <i class="fa fa-info-circle"></i> Fixed ${settings.position || 'top'} notification bar
                        </div>
                    </div>`;
}