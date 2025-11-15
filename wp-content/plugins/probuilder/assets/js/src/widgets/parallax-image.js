export default // Widget renderer for "parallax-image" (auto-generated)
function renderParallaxImage(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const parallaxImage = settings.image || 'https://via.placeholder.com/1200x800/93003c/ffffff?text=Parallax+Background';
  const parallaxHeight = settings.height || 400;
  const parallaxSpeed = settings.speed || 0.5;
  return `<div style="position:relative;overflow:hidden;height:${parallaxHeight}px;border-radius:8px">
                        <div style="background:url('${parallaxImage}') center/cover;height:150%;width:100%;position:absolute;top:-25%"></div>
                        <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:center;height:100%;color:#fff;text-shadow:0 2px 10px rgba(0,0,0,0.5)">
                            <div style="text-align:center">
                                <i class="fa fa-mountain" style="font-size:48px;margin-bottom:15px;display:block"></i>
                                <h3 style="margin:0 0 10px;font-size:28px;font-weight:700">Parallax Image</h3>
                                <p style="margin:0;font-size:14px">Speed: ${parallaxSpeed}x · Height: ${parallaxHeight}px</p>
                            </div>
                        </div>
                    </div>`;
}