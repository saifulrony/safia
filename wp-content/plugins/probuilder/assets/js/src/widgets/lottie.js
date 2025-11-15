export default // Widget renderer for "lottie" (auto-generated)
function renderLottie(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const lottieWidth = settings.width || 300;
  const lottieLoop = settings.loop !== false;
  const lottieAutoplay = settings.autoplay !== false;
  const lottieUrl = settings.animation_url || 'https://assets3.lottiefiles.com/packages/lf20_UJNc2t.json';
  return `<div style="text-align:center;padding:40px;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:12px">
                        <div style="width:${lottieWidth}px;max-width:100%;margin:0 auto;background:#ffffff;border-radius:8px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,0.2)">
                            <div style="position:relative;width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;overflow:hidden">
                                <svg width="100%" height="100%" viewBox="0 0 200 200" style="animation:lottieRotate 3s linear infinite">
                                    <circle cx="100" cy="100" r="80" fill="none" stroke="#667eea" stroke-width="8" stroke-dasharray="50 30" style="animation:lottieDash 2s linear infinite"/>
                                    <circle cx="100" cy="100" r="60" fill="none" stroke="#fa709a" stroke-width="6" stroke-dasharray="40 20" style="animation:lottieDash 2.5s linear infinite reverse"/>
                                    <circle cx="100" cy="100" r="40" fill="none" stroke="#4facfe" stroke-width="4" stroke-dasharray="30 10" style="animation:lottieDash 3s linear infinite"/>
                                    <path d="M 100 60 L 120 100 L 100 140 L 80 100 Z" fill="#fee140" style="animation:lottieScale 2s ease-in-out infinite"/>
                                </svg>
                                <style>
                                    @keyframes lottieRotate {
                                        from { transform: rotate(0deg); }
                                        to { transform: rotate(360deg); }
                                    }
                                    @keyframes lottieDash {
                                        from { stroke-dashoffset: 0; }
                                        to { stroke-dashoffset: 100; }
                                    }
                                    @keyframes lottieScale {
                                        0%, 100% { transform: scale(1); opacity: 1; }
                                        50% { transform: scale(1.2); opacity: 0.8; }
                                    }
                                </style>
                            </div>
                            <div style="margin-top:20px;padding-top:20px;border-top:2px solid #f0f0f0">
                                <div style="display:flex;justify-content:center;gap:15px;margin-bottom:10px">
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:${lottieLoop ? '#10b981' : '#e5e7eb'};color:${lottieLoop ? '#fff' : '#666'};border-radius:20px;font-size:12px;font-weight:600">
                                        <i class="fa fa-repeat"></i> ${lottieLoop ? 'Loop ON' : 'Loop OFF'}
                                    </span>
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:${lottieAutoplay ? '#3b82f6' : '#e5e7eb'};color:${lottieAutoplay ? '#fff' : '#666'};border-radius:20px;font-size:12px;font-weight:600">
                                        <i class="fa fa-play"></i> ${lottieAutoplay ? 'Autoplay ON' : 'Autoplay OFF'}
                                    </span>
                                </div>
                                <p style="margin:10px 0 0;color:#666;font-size:13px;font-weight:500">
                                    <i class="fa fa-film" style="color:#667eea;margin-right:5px"></i>
                                    Lottie Animation
                                </p>
                                <p style="margin:5px 0 0;color:#999;font-size:11px">
                                    ${lottieWidth}px × ${lottieLoop ? 'Infinite' : 'Once'} × ${lottieAutoplay ? 'Auto' : 'Manual'}
                                </p>
                            </div>
                        </div>
                    </div>`;
}