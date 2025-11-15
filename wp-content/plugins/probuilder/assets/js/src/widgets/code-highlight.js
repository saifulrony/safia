export default // Widget renderer for "code-highlight" (auto-generated)
function renderCodeHighlight(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const codeLanguage = settings.language || 'javascript';
  const codeTheme = settings.theme || 'dark';
  const codeShowNumbers = settings.show_line_numbers !== false;
  const codeSample = settings.code || 'function hello() {\n  console.log("Hello World");\n}';
  const codeLines = codeSample.split('\\n');
  const codeBg = codeTheme === 'dark' ? '#2d2d2d' : '#f5f5f5';
  const codeColor = codeTheme === 'dark' ? '#f8f8f2' : '#333';
  return `<div style="background:${codeBg};color:${codeColor};padding:20px;border-radius:8px;font-family:monospace;font-size:14px;overflow-x:auto">
                        <div style="margin-bottom:8px;color:#999;font-size:12px;text-transform:uppercase">${codeLanguage}</div>
                        ${codeShowNumbers ? codeLines.map((line, i) => `<div><span style="color:#6c6c6c;margin-right:15px;user-select:none">${i + 1}</span>${line}</div>`).join('') : codeSample}
                    </div>`;
}