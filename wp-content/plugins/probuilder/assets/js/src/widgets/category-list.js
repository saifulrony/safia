export default // Widget renderer for "category-list" (auto-generated)
function renderCategoryList(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const showCount = settings.show_count !== false;
  return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Categories</h4>
                        <ul style="list-style:none;padding:0;margin:0">
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Design</a>${showCount ? ' <span style="color:#999">(12)</span>' : ''}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Development</a>${showCount ? ' <span style="color:#999">(8)</span>' : ''}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Marketing</a>${showCount ? ' <span style="color:#999">(5)</span>' : ''}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Business</a>${showCount ? ' <span style="color:#999">(15)</span>' : ''}</li>
                        </ul>
                    </div>`;
}