export default // Widget renderer for "video" (auto-generated)
function renderVideo(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const videoType = settings.video_type || 'youtube';
  const youtubeUrl = settings.youtube_url || 'https://www.youtube.com/watch?v=ScMzIvxBSi4';
  const vimeoUrl = settings.vimeo_url || '';
  const selfUrl = settings.self_url || '';
  const aspectRatio = settings.aspect_ratio || '16:9';
  const videoBorderRadius = settings.border_radius || 8;
  const videoBoxShadow = settings.box_shadow !== 'no';

  // Calculate padding based on aspect ratio
  // Calculate padding based on aspect ratio
  const paddingMap = {
    '16:9': '56.25%',
    '4:3': '75%',
    '21:9': '42.85%',
    '1:1': '100%',
    '9:16': '177.78%'
  };
  const videoPadding = paddingMap[aspectRatio] || '56.25%';
  let videoHTML = `<div style="position: relative; padding-bottom: ${videoPadding}; height: 0; overflow: hidden; border-radius: ${videoBorderRadius}px; ${videoBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.15);' : ''}">`;
  if (videoType === 'youtube' && youtubeUrl) {
    // Extract YouTube video ID
    let videoId = '';
    const youtubeMatch1 = youtubeUrl.match(/[?&]v=([^&]+)/);
    const youtubeMatch2 = youtubeUrl.match(/youtu\.be\/([^?]+)/);
    const youtubeMatch3 = youtubeUrl.match(/embed\/([^?]+)/);
    if (youtubeMatch1) videoId = youtubeMatch1[1];else if (youtubeMatch2) videoId = youtubeMatch2[1];else if (youtubeMatch3) videoId = youtubeMatch3[1];
    if (videoId) {
      videoHTML += `<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
    } else {
      videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>`;
    }
  } else if (videoType === 'vimeo' && vimeoUrl) {
    // Extract Vimeo video ID
    const vimeoMatch = vimeoUrl.match(/vimeo\.com\/(\d+)/);
    const videoId = vimeoMatch ? vimeoMatch[1] : '';
    if (videoId) {
      videoHTML += `<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;" src="https://player.vimeo.com/video/${videoId}" frameborder="0" allowfullscreen></iframe>`;
    } else {
      videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>`;
    }
  } else if (videoType === 'self' && selfUrl) {
    videoHTML += `<video style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" controls><source src="${selfUrl}" type="video/mp4"></video>`;
  } else {
    // Placeholder
    videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="fa fa-play-circle" style="font-size: 72px; margin-bottom: 15px; opacity: 0.9;"></i>
                            <div style="font-size: 18px; font-weight: 600;">Video Placeholder</div>
                            <div style="font-size: 13px; margin-top: 8px; opacity: 0.8;">Enter a video URL to display</div>
                        </div>`;
  }
  videoHTML += `</div>`;
  return videoHTML;
}