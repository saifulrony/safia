export default // Widget renderer for "post-comments" (auto-generated)
function renderPostComments(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const commentsShowCount = settings.show_count !== false;
  return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        ${commentsShowCount ? '<h3 style="margin:0 0 20px;font-size:24px;color:#333">5 Comments</h3>' : ''}
                        <div style="border-left:3px solid #0073aa;padding-left:15px;margin-bottom:20px">
                            <div style="display:flex;gap:10px;margin-bottom:10px">
                                <div style="width:40px;height:40px;background:#ddd;border-radius:50%;flex-shrink:0"></div>
                                <div>
                                    <strong style="color:#333">John Doe</strong>
                                    <p style="margin:5px 0 0;color:#666;font-size:14px">This is a sample comment on the post.</p>
                                </div>
                            </div>
                        </div>
                        <div style="border:1px solid #ddd;border-radius:4px;padding:15px">
                            <textarea placeholder="Leave a comment..." style="width:100%;border:1px solid #ddd;border-radius:4px;padding:10px;font-size:14px;min-height:80px"></textarea>
                            <button style="background:#0073aa;color:#fff;border:none;padding:10px 20px;border-radius:4px;cursor:pointer;margin-top:10px">Post Comment</button>
                        </div>
                    </div>`;
}