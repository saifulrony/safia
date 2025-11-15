export default // Widget renderer for "twitter-embed" (auto-generated)
function renderTwitterEmbed(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const twitterTheme = settings.theme || 'light';
  const twitterBg = twitterTheme === 'dark' ? '#15202b' : '#ffffff';
  const twitterTextColor = twitterTheme === 'dark' ? '#ffffff' : '#14171a';
  return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:${twitterBg};border:1px solid ${twitterTheme === 'dark' ? '#38444d' : '#e1e8ed'};border-radius:12px;padding:20px;max-width:550px;margin:0 auto;text-align:left">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
                                <div style="width:48px;height:48px;background:#1da1f2;border-radius:50%;display:flex;align-items:center;justify-content:center">
                                    <i class="fa fa-twitter" style="font-size:24px;color:#fff"></i>
                                </div>
                                <div>
                                    <strong style="color:${twitterTextColor};display:block">Twitter User</strong>
                                    <small style="color:#8899a6">@username</small>
                                </div>
                            </div>
                            <p style="margin:0 0 15px;color:${twitterTextColor};line-height:1.5">This is a sample tweet that will be embedded. Twitter/X content will display here with the ${twitterTheme} theme. 🐦</p>
                            <div style="color:#8899a6;font-size:13px">
                                <i class="fa fa-clock" style="margin-right:5px"></i>
                                Oct 25, 2025
                            </div>
                        </div>
                    </div>`;
}