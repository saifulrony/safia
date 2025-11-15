export default // Widget renderer for "facebook-embed" (auto-generated)
function renderFacebookEmbed(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const facebookType = settings.type || 'post';
  const facebookUrl = settings.url || 'https://www.facebook.com/...';
  return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;max-width:500px;margin:0 auto">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
                                <i class="fa fa-facebook" style="font-size:32px;color:#1877f2"></i>
                                <div style="text-align:left">
                                    <strong style="color:#333;display:block">Facebook ${facebookType === 'post' ? 'Post' : facebookType === 'page' ? 'Page' : 'Video'}</strong>
                                    <small style="color:#999">Embedded content</small>
                                </div>
                            </div>
                            <div style="background:#f0f2f5;height:200px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999">
                                <div>
                                    <i class="fa fa-facebook-f" style="font-size:48px;margin-bottom:10px;display:block"></i>
                                    Facebook ${facebookType.charAt(0).toUpperCase() + facebookType.slice(1)} Preview
                                </div>
                            </div>
                            <div style="margin-top:15px;padding:10px;background:#f0f2f5;border-radius:4px;font-size:12px;color:#666">
                                <i class="fa fa-link" style="margin-right:5px"></i>
                                Embed URL configured
                            </div>
                        </div>
                    </div>`;
}