export default // Widget renderer for "html-code" (auto-generated)
function renderHtmlCode(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const htmlCode = settings.html_code || '<div class="custom-element"><h3>Custom HTML</h3><p>Add your code here</p></div>';
  const cssCode = settings.css_code || '.custom-element { padding: 20px; background: #f8f9fa; }';
  const jsCode = settings.js_code || '';
  const htmlMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  const htmlCodeId = 'html-code-' + element.id;
  let htmlCodeHTML = `
                        <div class="probuilder-html-code-preview" id="${htmlCodeId}" style="
                            margin: ${htmlMargin.top}px ${htmlMargin.right}px ${htmlMargin.bottom}px ${htmlMargin.left}px;
                            position: relative;
                        ">
                            <div class="code-label" style="
                                position: absolute;
                                top: 8px;
                                right: 8px;
                                background: #1e293b;
                                color: #fff;
                                padding: 4px 10px;
                                border-radius: 3px;
                                font-size: 10px;
                                font-family: monospace;
                                z-index: 10;
                            ">HTML/CSS/JS</div>
                            <div class="html-output">
                                ${htmlCode}
                            </div>
                            ${cssCode ? `<style>${cssCode}</style>` : ''}
                        </div>
                    `;
  if (jsCode) {
    setTimeout(function () {
      try {
        eval(jsCode);
      } catch (e) {
        console.log('JS code error (expected in preview):', e);
      }
    }, 100);
  }
  return htmlCodeHTML;
}