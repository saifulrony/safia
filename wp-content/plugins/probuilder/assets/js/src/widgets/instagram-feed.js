export default // Widget renderer for "instagram-feed" (auto-generated)
function renderInstagramFeed(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const instaColumns = settings.columns || 3;
  const instaLimit = settings.limit || 6;
  return `<div style="padding:20px;background:#f9f9f9;border-radius:8px">
                        <div style="text-align:center;margin-bottom:20px">
                            <i class="fa fa-instagram" style="font-size:32px;color:#e4405f;margin-bottom:10px"></i>
                            <h4 style="margin:0;font-size:18px;color:#333">Instagram Feed</h4>
                            <small style="color:#999">@${settings.username || 'username'}</small>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(${instaColumns},1fr);gap:10px">
                            ${Array(Math.min(instaLimit, 6)).fill(0).map((_, i) => `
                                <div style="aspect-ratio:1;background:linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px">
                                    <i class="fa fa-camera"></i>
                                </div>
                            `).join('')}
                        </div>
                        <p style="text-align:center;margin-top:15px;font-size:12px;color:#999">
                            <i class="fa fa-info-circle"></i> Connect Instagram API to display photos
                        </p>
                    </div>`;
}