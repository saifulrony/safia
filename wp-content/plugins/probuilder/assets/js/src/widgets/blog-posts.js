export default // Widget renderer for "blog-posts" (auto-generated)
function renderBlogPosts(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const blogLayout = settings.post_layout || 'grid';
  const blogColumns = parseInt(settings.columns || 3);
  const blogCardBg = settings.card_bg_color || '#ffffff';
  const blogBorderRadius = parseInt(settings.card_border_radius || 8);
  const blogBoxShadow = settings.card_box_shadow !== 'no';
  const blogTitleColor = settings.title_color || '#1e293b';
  const blogExcerptColor = settings.excerpt_color || '#64748b';
  const blogMetaColor = settings.meta_color || '#94a3b8';
  const blogReadMoreBg = settings.read_more_bg_color || '#92003b';
  const blogReadMoreText = settings.read_more_text_color || '#ffffff';
  const blogShowImage = settings.show_image !== 'no';
  const blogShowTitle = settings.show_title !== 'no';
  const blogShowExcerpt = settings.show_excerpt !== 'no';
  const blogShowMeta = settings.show_meta !== 'no';
  const blogShowReadMore = settings.show_read_more !== 'no';
  const blogReadMoreLabel = settings.read_more_text || 'Read More';
  const blogPerPage = parseInt(settings.posts_per_page || 6);
  const blogCategory = settings.category_filter || 0;

  // Create container that will be populated with real posts
  // Create container that will be populated with real posts
  const blogContainerId = 'blog-posts-' + element.id;
  let blogHTML = `<div id="${blogContainerId}" style="min-height: 100px;">
                        <div style="text-align: center; padding: 30px; color: #92003b;">
                            <div style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top-color: #92003b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Loading posts...</p>
                        </div>
                    </div>`;

  // Load real blog posts immediately
  // Load real blog posts immediately
  setTimeout(() => {
    $.ajax({
      url: ProBuilderEditor.ajaxurl,
      type: 'POST',
      data: {
        action: 'probuilder_get_blog_posts',
        nonce: ProBuilderEditor.nonce,
        per_page: blogPerPage,
        category: blogCategory
      },
      success: function (response) {
        if (response.success && response.data.posts && response.data.posts.length > 0) {
          const posts = response.data.posts;
          const boxShadow = blogBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);' : '';
          let postsHTML = `<div style="display: grid; grid-template-columns: repeat(${blogColumns}, 1fr); gap: 30px;">`;
          posts.forEach(post => {
            postsHTML += `<article class="probuilder-blog-post" style="background-color: ${blogCardBg}; border-radius: ${blogBorderRadius}px; overflow: hidden; ${boxShadow}">`;
            if (blogShowImage && post.image) {
              postsHTML += `<div style="position: relative; height: 200px; background-image: url(${post.image}); background-size: cover; background-position: center;">
                                                <a href="${post.permalink}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: block;"></a>
                                            </div>`;
            }
            postsHTML += `<div style="padding: 25px;">`;
            if (blogShowMeta) {
              postsHTML += `<div style="margin-bottom: 15px; font-size: 14px; color: ${blogMetaColor};">
                                                <span>${post.date}</span> • <span>${post.author}</span>
                                            </div>`;
            }
            if (blogShowTitle) {
              postsHTML += `<h3 style="margin: 0 0 15px 0; font-size: 20px; line-height: 1.4;">
                                                <a href="${post.permalink}" style="color: ${blogTitleColor}; text-decoration: none;">${post.title}</a>
                                            </h3>`;
            }
            if (blogShowExcerpt) {
              postsHTML += `<div style="color: ${blogExcerptColor}; line-height: 1.6; margin-bottom: 20px;">${post.excerpt}</div>`;
            }
            if (blogShowReadMore) {
              postsHTML += `<a href="${post.permalink}" style="display: inline-block; background-color: ${blogReadMoreBg}; color: ${blogReadMoreText}; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">${blogReadMoreLabel}</a>`;
            }
            postsHTML += `</div></article>`;
          });
          postsHTML += `</div>`;
          $('#' + blogContainerId).html(postsHTML);
          console.log('✅ Loaded', posts.length, 'real blog posts:', posts.map(p => p.title).join(', '));
        } else {
          $('#' + blogContainerId).html(`<div style="padding: 40px; text-align: center; background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; color: #78350f;">
                                        <i class="dashicons dashicons-admin-post" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p style="margin: 10px 0 0; font-weight: 600;">No blog posts found</p>
                                        <p style="margin: 5px 0 0; font-size: 13px;">Create some blog posts in WordPress</p>
                                    </div>`);
        }
      },
      error: function () {
        console.error('Error loading blog posts');
        $('#' + blogContainerId).html(`<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">
                                    <i class="dashicons dashicons-warning" style="font-size: 48px;"></i>
                                    <p style="margin: 10px 0 0; font-weight: 600;">Error loading posts</p>
                                </div>`);
      }
    });
  }, 50);
  return blogHTML;
}