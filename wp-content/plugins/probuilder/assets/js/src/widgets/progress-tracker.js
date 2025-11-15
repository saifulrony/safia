export default // Widget renderer for "progress-tracker" (auto-generated)
function renderProgressTracker(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const trackerSteps = settings.steps || [{
    title: 'Step 1',
    complete: true
  }, {
    title: 'Step 2',
    complete: true
  }, {
    title: 'Step 3',
    complete: false
  }];
  const trackerOrientation = settings.orientation || 'horizontal';
  const trackerActiveColor = settings.active_color || '#4caf50';
  const trackerInactiveColor = settings.inactive_color || '#cccccc';
  if (trackerOrientation === 'horizontal') {
    return `<div style="display:flex;align-items:center;gap:10px">
                            ${trackerSteps.map((step, i) => `
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div style="display:flex;flex-direction:column;align-items:center">
                                        <div style="width:40px;height:40px;border-radius:50%;background:${step.complete ? trackerActiveColor : trackerInactiveColor};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">${i + 1}</div>
                                        <span style="margin-top:8px;font-size:14px;color:#333">${step.title || 'Step ' + (i + 1)}</span>
                                    </div>
                                    ${i < trackerSteps.length - 1 ? `<div style="flex:1;min-width:50px;height:2px;background:${step.complete ? trackerActiveColor : trackerInactiveColor}"></div>` : ''}
                                </div>
                            `).join('')}
                        </div>`;
  } else {
    return `<div style="display:flex;flex-direction:column;gap:10px">
                            ${trackerSteps.map((step, i) => `
                                <div style="display:flex;flex-direction:column;gap:10px">
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <div style="width:40px;height:40px;border-radius:50%;background:${step.complete ? trackerActiveColor : trackerInactiveColor};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">${i + 1}</div>
                                        <span style="font-size:14px;color:#333">${step.title || 'Step ' + (i + 1)}</span>
                                    </div>
                                    ${i < trackerSteps.length - 1 ? `<div style="width:2px;height:30px;background:${step.complete ? trackerActiveColor : trackerInactiveColor};margin-left:19px"></div>` : ''}
                                </div>
                            `).join('')}
                        </div>`;
  }
}