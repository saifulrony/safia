export default // Widget renderer for "post-author" (auto-generated)
function renderPostAuthor(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const authorShowAvatar = settings.show_avatar !== false;
  const authorAvatarSize = settings.avatar_size || 32;
  const authorLink = settings.link !== false;
  return `<div style="display:flex;align-items:center;gap:10px">
                        ${authorShowAvatar ? `<div style="width:${authorAvatarSize}px;height:${authorAvatarSize}px;background:#ddd;border-radius:50%"></div>` : ''}
                        ${authorLink ? '<a href="#" style="color:#333;text-decoration:none;font-weight:500">Author Name</a>' : '<span style="color:#333;font-weight:500">Author Name</span>'}
                    </div>`;
}