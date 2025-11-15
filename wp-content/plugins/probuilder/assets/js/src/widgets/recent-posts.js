export default // Widget renderer for "recent-posts" (auto-generated)
function renderRecentPosts(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const recentLimit = settings.limit || 5;
  const recentShowImage = settings.show_image !== false;
  const recentShowDate = settings.show_date !== false;
  return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Recent Posts</h4>
                        ${[1, 2, 3].map(i => `
                            <div style="display:flex;gap:10px;margin-bottom:15px;align-items:center">
                                ${recentShowImage ? '<div style="width:60px;height:60px;background:#ddd;border-radius:4px;flex-shrink:0"></div>' : ''}
                                <div>
                                    <a href="#" style="color:#333;text-decoration:none;font-weight:600;display:block;margin-bottom:4px">Recent Post ${i}</a>
                                    ${recentShowDate ? '<div style="font-size:12px;color:#999">October 25, 2025</div>' : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>`;
}