export default // Widget renderer for "woo-categories" (auto-generated)
function renderWooCategories(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const catColumns = parseInt(settings.columns || 4);
  const catColumnGap = parseInt(settings.column_gap || 20);
  const catRowGap = parseInt(settings.row_gap || 20);
  const catBorderRadius = parseInt(settings.border_radius || 8);
  const catCardBg = settings.card_bg_color || '#ffffff';
  const catTitleColor = settings.title_color || '#344047';
  const catCountColor = settings.count_color || '#6b7280';
  const catImageHeight = parseInt(settings.image_height || 200);
  const catShowImage = settings.show_image !== 'no';
  const catShowCount = settings.show_count !== 'no';
  const catHideEmpty = settings.hide_empty !== 'no';

  // Create container that will be populated with real categories
  // Create container that will be populated with real categories
  const catContainerId = 'woo-categories-' + element.id;
  let catHTML = `<div id="${catContainerId}" style="min-height: 100px;">
                        <div style="text-align: center; padding: 30px; color: #92003b;">
                            <div style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top-color: #92003b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Loading categories...</p>
                        </div>
                    </div>`;

  // Load real categories immediately
  // Load real categories immediately
  setTimeout(() => {
    $.ajax({
      url: ProBuilderEditor.ajaxurl,
      type: 'POST',
      data: {
        action: 'probuilder_get_woo_categories',
        nonce: ProBuilderEditor.nonce,
        hide_empty: catHideEmpty
      },
      success: function (response) {
        if (response.success && response.data.categories && response.data.categories.length > 0) {
          const categories = response.data.categories;
          const colors = ['#92003b', '#667eea', '#4facfe', '#764ba2', '#f093fb', '#00f2fe', '#c44569', '#22c55e'];
          let categoriesHTML = `<div style="display: grid; grid-template-columns: repeat(${catColumns}, 1fr); gap: ${catRowGap}px ${catColumnGap}px;">`;
          categories.forEach((category, idx) => {
            categoriesHTML += `<div class="category-item" style="text-align: center; padding: 20px; background: ${catCardBg}; border-radius: ${catBorderRadius}px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">`;
            if (catShowImage) {
              categoriesHTML += `<a href="${category.link}" style="text-decoration: none;">`;
              if (category.image) {
                categoriesHTML += `<img src="${category.image}" alt="${category.name}" style="width: 100%; height: ${catImageHeight}px; object-fit: cover; border-radius: 4px; margin-bottom: 15px; display: block;">`;
              } else {
                const initial = category.name.charAt(0).toUpperCase();
                const color = colors[idx % colors.length];
                categoriesHTML += `<div style="width: 100%; height: ${catImageHeight}px; background: ${color}; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; font-weight: 700;">${initial}</div>`;
              }
              categoriesHTML += `</a>`;
            }
            categoriesHTML += `<h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 600; line-height: 1.4;"><a href="${category.link}" style="color: ${catTitleColor}; text-decoration: none;">${category.name}</a></h3>`;
            if (catShowCount) {
              categoriesHTML += `<p style="margin: 0; font-size: 14px; color: ${catCountColor};">${category.count} product${category.count !== 1 ? 's' : ''}</p>`;
            }
            categoriesHTML += `</div>`;
          });
          categoriesHTML += `</div>`;
          $('#' + catContainerId).html(categoriesHTML);
          console.log('✅ Loaded', categories.length, 'real categories:', categories.map(c => c.name).join(', '));
        } else {
          $('#' + catContainerId).html(`<div style="padding: 40px; text-align: center; background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; color: #78350f;">
                                        <i class="dashicons dashicons-category" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p style="margin: 10px 0 0; font-weight: 600;">No categories found</p>
                                        <p style="margin: 5px 0 0; font-size: 13px;">Add product categories to your WooCommerce store</p>
                                    </div>`);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX Error loading categories:', status, error);
        $('#' + catContainerId).html(`<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">
                                    <i class="dashicons dashicons-warning" style="font-size: 48px;"></i>
                                    <p style="margin: 10px 0 0; font-weight: 600;">Error loading categories</p>
                                </div>`);
      }
    });
  }, 50);
  return catHTML;
}