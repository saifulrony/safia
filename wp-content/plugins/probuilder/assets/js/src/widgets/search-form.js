export default // Widget renderer for "search-form" (auto-generated)
function renderSearchForm(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="display:flex;gap:0;max-width:600px">
                        <input type="text" placeholder="${settings.placeholder || 'Search...'}" style="flex:1;padding:12px 15px;border:1px solid #ddd;border-radius:4px 0 0 4px;font-size:16px">
                        <button style="background:${settings.button_color || '#0073aa'};color:#fff;border:none;padding:12px 24px;border-radius:0 4px 4px 0;cursor:pointer;font-weight:600">${settings.button_text || 'Search'}</button>
                    </div>`;
}