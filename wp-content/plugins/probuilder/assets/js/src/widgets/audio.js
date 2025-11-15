export default // Widget renderer for "audio" (auto-generated)
function renderAudio(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const audioUrl = settings.audio_url || '';
  const audioTitle = settings.title || 'Audio Player';
  return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <h4 style="margin: 0 0 15px; color: #333;">${audioTitle}</h4>
                        <div style="background: #92003b; color: #fff; padding: 15px; border-radius: 4px; display: flex; align-items: center; gap: 15px;">
                            <i class="fa fa-play-circle" style="font-size: 32px;"></i>
                            <div style="flex: 1;">
                                <div style="height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px; margin-bottom: 5px;">
                                    <div style="height: 100%; width: 30%; background: #fff; border-radius: 2px;"></div>
                                </div>
                                <div style="font-size: 11px; opacity: 0.9;">0:45 / 3:24</div>
                            </div>
                            <i class="fa fa-volume-up" style="font-size: 20px;"></i>
                        </div>
                    </div>`;
}