export default // Widget renderer for "sticky-video" (auto-generated)
function renderStickyVideo(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const videoUrl = settings.video_url || 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
  const stickyVideoPosition = settings.sticky_position || 'bottom-right';
  return `<div style="background:#f5f5f5;padding:40px;border-radius:8px;position:relative;min-height:250px">
                        <p style="margin:0 0 150px;color:#666;text-align:center">
                            <i class="fa fa-video" style="font-size:48px;color:#93003c;display:block;margin-bottom:15px"></i>
                            Sticky Video Player
                        </p>
                        <div style="position:absolute;bottom:20px;right:20px;width:300px;background:#000;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.3)">
                            <div style="position:relative;padding-bottom:56.25%;background:#333">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:48px">▶</div>
                            </div>
                            <div style="padding:8px;background:#222;color:#fff;font-size:11px;text-align:center">Video will stick on scroll (${stickyVideoPosition})</div>
                        </div>
                    </div>`;
}