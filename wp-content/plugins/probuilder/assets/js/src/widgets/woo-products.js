export default // Widget renderer for "woo-products" (auto-generated)
function renderWooProducts(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const wooColumns = parseInt(settings.columns || 4);
  const wooGap = parseInt(settings.column_gap || 20);
  const wooRowGap = parseInt(settings.row_gap || 30);
  const wooBorderRadius = parseInt(settings.product_border_radius || 8);
  const wooCardBg = settings.card_bg_color || '#ffffff';
  const wooTitleColor = settings.title_color || '#344047';
  const wooPriceColor = settings.price_color || '#92003b';
  const wooBtnBg = settings.button_bg_color || '#92003b';
  const wooBtnText = settings.button_text_color || '#ffffff';
  const wooPerPage = parseInt(settings.products_per_page || 8);
  const wooShowImage = settings.show_image !== 'no';
  const wooShowTitle = settings.show_title !== 'no';
  const wooShowPrice = settings.show_price !== 'no';
  const wooShowRating = settings.show_rating !== 'no';
  const wooShowCart = settings.show_cart_button !== 'no';
  const wooShowBadge = settings.show_badge !== 'no';
  const wooQueryType = settings.query_type || 'recent';
  const wooOrderBy = settings.orderby || 'date';
  const wooOrder = settings.order || 'DESC';

  // Image settings
  // Image settings
  const wooImageRatio = settings.image_ratio || '1:1';
  const wooImageHeight = settings.image_height || 300;
  const wooImageFit = settings.image_fit || 'cover';

  // Calculate padding-top based on aspect ratio
  // Calculate padding-top based on aspect ratio
  const ratioMap = {
    '1:1': '100%',
    '4:3': '75%',
    '3:4': '133.33%',
    '16:9': '56.25%',
    'custom': wooImageHeight + 'px'
  };
  const wooPaddingTop = ratioMap[wooImageRatio] || '100%';

  // Create container that will be populated with real products
  // Create container that will be populated with real products
  const wooContainerId = 'woo-products-' + element.id;
  let wooHTML = `<div id="${wooContainerId}" style="box-sizing: border-box;">
                        <div style="text-align: center; padding: 30px; color: #92003b;">
                            <div style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top-color: #92003b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Loading products...</p>
                        </div>
                    </div>
                    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>`;

  // Load real products immediately
  // Load real products immediately
  setTimeout(() => {
    $.ajax({
      url: ProBuilderEditor.ajaxurl,
      type: 'POST',
      data: {
        action: 'probuilder_get_woo_products',
        nonce: ProBuilderEditor.nonce,
        query_type: wooQueryType,
        per_page: wooPerPage,
        orderby: wooOrderBy,
        order: wooOrder
      },
      success: function (response) {
        if (response.success && response.data.products && response.data.products.length > 0) {
          const products = response.data.products;
          // Grid container - NO padding here, only grid properties
          let productsHTML = `<div style="display: grid; grid-template-columns: repeat(${wooColumns}, 1fr); gap: ${wooRowGap}px ${wooGap}px; width: 100%; box-sizing: border-box;">`;
          products.forEach(product => {
            productsHTML += `<div class="probuilder-product-card" style="border-radius: ${wooBorderRadius}px; overflow: hidden; background: ${wooCardBg}; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">`;
            if (wooShowImage) {
              // Use aspect ratio technique for consistent heights
              const imageContainerStyle = wooImageRatio !== 'custom' ? `position: relative; background: #f8f9fa; overflow: hidden; padding-top: ${wooPaddingTop};` : `position: relative; background: #f8f9fa; overflow: hidden; height: ${wooImageHeight}px;`;
              const imageStyle = wooImageRatio !== 'custom' ? `position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: ${wooImageFit}; display: block;` : `width: 100%; height: 100%; object-fit: ${wooImageFit}; display: block;`;
              productsHTML += `<div class="product-image" style="${imageContainerStyle}">`;
              productsHTML += `<img src="${product.image}" style="${imageStyle}" alt="${product.title}">`;
              if (wooShowBadge && product.sale) {
                productsHTML += `<span class="sale-badge" style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; z-index: 10;">Sale</span>`;
              }
              productsHTML += `</div>`;
            }
            productsHTML += `<div class="product-content" style="padding: 20px;">`;
            if (wooShowTitle) {
              productsHTML += `<h3 class="product-title" style="margin: 0 0 10px; font-size: 16px; font-weight: 600; line-height: 1.4; color: ${wooTitleColor};"><a href="${product.permalink}" style="color: inherit; text-decoration: none;">${product.title}</a></h3>`;
            }
            if (wooShowRating && product.rating > 0) {
              productsHTML += `<div class="product-rating" style="margin-bottom: 10px; color: #fbbf24; font-size: 14px;">`;
              for (let i = 0; i < 5; i++) {
                productsHTML += i < product.rating ? '★' : '☆';
              }
              productsHTML += `</div>`;
            }
            if (wooShowPrice) {
              productsHTML += `<div class="product-price" style="margin-bottom: 15px; font-size: 18px; font-weight: 700; color: ${wooPriceColor}; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">${product.price}</div>`;
            }
            if (wooShowCart) {
              productsHTML += `<a href="${product.permalink}" class="button product_type_simple add_to_cart_button ajax_add_to_cart" style="background: ${wooBtnBg}; color: ${wooBtnText}; padding: 12px 24px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">Add to Cart</a>`;
            }
            productsHTML += `</div></div>`;
          });
          productsHTML += `</div>`;
          $('#' + wooContainerId).html(productsHTML);
          console.log('✅ Loaded', products.length, 'real products:', products.map(p => p.title).join(', '));
        } else {
          // No products found
          $('#' + wooContainerId).html(`<div style="padding: 40px; text-align: center; background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; color: #78350f;">
                                        <i class="dashicons dashicons-cart" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p style="margin: 10px 0 0; font-weight: 600;">No products found</p>
                                        <p style="margin: 5px 0 0; font-size: 13px;">Query: ${wooQueryType} | Add products to your WooCommerce store</p>
                                    </div>`);
          console.warn('No products returned from AJAX');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX Error loading products:', status, error);
        $('#' + wooContainerId).html(`<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">
                                    <i class="dashicons dashicons-warning" style="font-size: 48px;"></i>
                                    <p style="margin: 10px 0 0; font-weight: 600;">Error loading products</p>
                                    <p style="margin: 5px 0 0; font-size: 13px;">Check browser console for details</p>
                                </div>`);
      }
    });
  }, 50);
  return wooHTML;
}