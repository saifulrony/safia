export default // Widget renderer for "login" (auto-generated)
function renderLogin(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  return `<div style="max-width:400px;padding:30px;background:#f9f9f9;border-radius:8px">
                        <h3 style="margin:0 0 20px">Login</h3>
                        <input type="text" placeholder="Username" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-bottom:15px">
                        <input type="password" placeholder="Password" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-bottom:15px">
                        <button style="width:100%;background:#0073aa;color:#fff;border:none;padding:12px;border-radius:4px;cursor:pointer;font-weight:600">Login</button>
                    </div>`;
}