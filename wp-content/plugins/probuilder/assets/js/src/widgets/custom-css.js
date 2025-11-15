export default // Widget renderer for "custom-css" (auto-generated)
function renderCustomCss(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const customCssCode = settings.css_code || '.my-class { color: red; }';
  return `<div style="background:#2d2d2d;color:#f8f8f2;padding:20px;border-radius:8px;font-family:monospace;font-size:14px">
                        <div style="margin-bottom:10px;color:#6c92c7;font-weight:600">
                            <i class="fa fa-css3-alt" style="margin-right:8px;color:#2965f1"></i>
                            Custom CSS
                        </div>
                        <pre style="margin:0;color:#98c379;line-height:1.6">${customCssCode.substring(0, 200)}</pre>
                        <div style="margin-top:10px;padding:10px;background:rgba(255,255,255,0.1);border-radius:4px;font-size:12px;color:#abb2bf">
                            <i class="fa fa-info-circle" style="margin-right:5px"></i>
                            CSS will be applied to the page
                        </div>
                    </div>`;
}