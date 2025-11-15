export default // Widget renderer for "calendly" (auto-generated)
function renderCalendly(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const calendlyUrl = settings.url || 'your-calendly-link';
  const calendlyType = settings.type || 'inline';
  const calendlyButtonText = settings.button_text || 'Schedule a Meeting';
  if (calendlyType === 'popup') {
    return `<div style="text-align:center;padding:40px;background:#f5f8fa;border-radius:8px">
                            <div style="margin-bottom:20px">
                                <i class="fa fa-calendar-alt" style="font-size:48px;color:#006bff"></i>
                            </div>
                            <h3 style="margin:0 0 10px;font-size:22px;color:#333">Book a Time</h3>
                            <p style="margin:0 0 20px;color:#666;font-size:14px">Click button to open scheduling popup</p>
                            <button style="background:#006bff;color:#fff;border:none;padding:15px 40px;border-radius:25px;font-size:16px;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(0,107,255,0.3)">
                                <i class="fa fa-calendar-check" style="margin-right:8px"></i>
                                ${calendlyButtonText}
                            </button>
                            <p style="margin:15px 0 0;font-size:11px;color:#999">
                                <i class="fa fa-info-circle"></i> Calendly popup mode
                            </p>
                        </div>`;
  } else {
    // Inline calendar view
    const timeSlots = ['9:00 AM', '10:00 AM', '11:00 AM', '1:00 PM', '2:00 PM', '3:00 PM'];
    return `<div style="background:#fff;border:1px solid #e1e4e8;border-radius:8px;overflow:hidden;max-width:800px;margin:0 auto">
                            <div style="background:#006bff;color:#fff;padding:20px;text-align:center">
                                <i class="fa fa-calendar-alt" style="font-size:32px;margin-bottom:10px"></i>
                                <h3 style="margin:0 0 5px;font-size:22px">Schedule a Meeting</h3>
                                <p style="margin:0;font-size:14px;opacity:0.9">Select a convenient time</p>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                                <div style="padding:20px;border-right:1px solid #e1e4e8">
                                    <h4 style="margin:0 0 15px;font-size:16px;color:#333">
                                        <i class="fa fa-calendar" style="margin-right:8px;color:#006bff"></i>
                                        Select Date
                                    </h4>
                                    <div style="background:#f5f8fa;padding:15px;border-radius:6px;text-align:center">
                                        <div style="font-size:12px;color:#666;font-weight:600;margin-bottom:10px">OCTOBER 2025</div>
                                        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:5px;font-size:12px">
                                            ${[25, 26, 27, 28, 29, 30, 31].map(day => `<div style="padding:8px;background:${day === 25 ? '#006bff' : '#fff'};color:${day === 25 ? '#fff' : '#333'};border-radius:4px;cursor:pointer;font-weight:${day === 25 ? '700' : '400'}">${day}</div>`).join('')}
                                        </div>
                                    </div>
                                </div>
                                <div style="padding:20px">
                                    <h4 style="margin:0 0 15px;font-size:16px;color:#333">
                                        <i class="fa fa-clock" style="margin-right:8px;color:#006bff"></i>
                                        Available Times
                                    </h4>
                                    <div style="display:flex;flex-direction:column;gap:8px">
                                        ${timeSlots.map((time, idx) => `
                                            <div style="padding:12px 15px;background:${idx === 1 ? '#e8f3ff' : '#f5f8fa'};border:1px solid ${idx === 1 ? '#006bff' : 'transparent'};border-radius:6px;cursor:pointer;text-align:center;font-size:14px;font-weight:${idx === 1 ? '600' : '400'};color:#333">
                                                ${time}
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                            <div style="padding:15px 20px;background:#f5f8fa;border-top:1px solid #e1e4e8;text-align:center;font-size:12px;color:#666">
                                <i class="fa fa-globe" style="margin-right:5px"></i>
                                Powered by Calendly · ${calendlyUrl}
                            </div>
                        </div>`;
  }
}