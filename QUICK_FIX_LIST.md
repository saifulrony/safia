# 🚀 ProBuilder Quick Fix List

## What to Fix and How

### 🔴 CRITICAL: Fix These 5 Widgets First

---

## 1. SOCIAL ICONS Widget (BOTH ISSUES!)

### Issue A: No Canvas Preview
**Fix**: Add to `/assets/js/editor.js` around line 9850:

```javascript
case 'social-icons':
    const socialItems = settings.social_items || [
        {platform: 'facebook', url: '#', icon: 'fab fa-facebook-f', color: '#3b5998'},
        {platform: 'twitter', url: '#', icon: 'fab fa-twitter', color: '#1da1f2'},
        {platform: 'instagram', url: '#', icon: 'fab fa-instagram', color: '#E4405F'}
    ];
    const socialAlign = settings.align || 'center';
    const socialSize = settings.size || 40;
    const socialSpacing = settings.spacing || 10;
    
    let iconsHTML = '';
    socialItems.forEach(item => {
        iconsHTML += `<a href="${item.url}" style="display: inline-flex; align-items: center; justify-content: center; width: ${socialSize}px; height: ${socialSize}px; background: ${item.color}; color: #fff; border-radius: 50%; margin: 0 ${socialSpacing/2}px; text-decoration: none; transition: all 0.3s;">
            <i class="${item.icon}" style="font-size: ${socialSize/2}px;"></i>
        </a>`;
    });
    
    return `<div style="text-align: ${socialAlign}; padding: 20px;">
        ${iconsHTML}
        <p style="margin-top: 15px; color: #666; font-size: 12px;">
            <i class="fa fa-share-nodes"></i> Social media links
        </p>
    </div>`;
```

### Issue B: Undefined Variables
**Fix**: In `/widgets/social-icons.php`, add to start of `render()` method (line ~46):

```php
protected function render() {
    // Render custom CSS if any
    $this->render_custom_css();
    
    // Get wrapper classes and attributes from base class
    $wrapper_classes = $this->get_wrapper_classes();
    $wrapper_attributes = $this->get_wrapper_attributes();
    $inline_styles = $this->get_inline_styles();
    
    // ... rest of existing code
}
```

---

## 2. WOO ADD TO CART Widget

### Issue: No Canvas Preview
**Fix**: Add to `/assets/js/editor.js`:

```javascript
case 'woo-add-to-cart':
    const btnText = settings.button_text || 'Add to Cart';
    const btnColor = settings.button_color || '#92003b';
    const showQty = settings.show_quantity !== 'no';
    
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            ${showQty ? '<input type="number" value="1" min="1" style="width: 60px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">' : ''}
            <button style="background: ${btnColor}; color: #fff; border: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; cursor: pointer; flex: 1;">
                <i class="fa fa-cart-plus"></i> ${btnText}
            </button>
        </div>
        <p style="margin-top: 10px; color: #666; font-size: 12px; text-align: center;">
            <i class="fa fa-shopping-cart"></i> WooCommerce Add to Cart Button
        </p>
    </div>`;
```

---

## 3. WOO CART Widget

### Issue: No Canvas Preview
**Fix**: Add to `/assets/js/editor.js`:

```javascript
case 'woo-cart':
    const showIcon = settings.show_icon !== 'no';
    const cartIcon = settings.icon || 'fa fa-shopping-cart';
    const cartCount = settings.show_count !== 'no';
    
    return `<div style="padding: 15px 25px; background: #92003b; color: #fff; border-radius: 4px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
        ${showIcon ? `<i class="${cartIcon}" style="font-size: 20px;"></i>` : ''}
        <span style="font-weight: 600;">Cart</span>
        ${cartCount ? '<span style="background: #fff; color: #92003b; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">3</span>' : ''}
        <p style="margin: 10px 0 0; color: #666; font-size: 11px; width: 100%; text-align: center;">
            <i class="fa fa-shopping-cart"></i> WooCommerce Cart Widget
        </p>
    </div>`;
```

---

## 4. CONTACT FORM Widget

### Issue: Undefined Variables
**Fix**: In `/widgets/contact-form.php`, line 46, change:

```php
protected function render() {
    // ADD THESE LINES:
    $this->render_custom_css();
    $wrapper_classes = $this->get_wrapper_classes();
    $wrapper_attributes = $this->get_wrapper_attributes();
    $inline_styles = $this->get_inline_styles();
    
    // Then continue with existing code:
    $form_title = $this->get_settings('form_title', 'Get in Touch');
    // ... rest of code
}
```

---

## 5. GALLERY Widget

### Issue: Undefined Variables
**Fix**: In `/widgets/gallery.php`, add to start of `render()` method:

```php
protected function render() {
    // Render custom CSS if any
    $this->render_custom_css();
    
    // Get wrapper classes and attributes from base class
    $wrapper_classes = $this->get_wrapper_classes();
    $wrapper_attributes = $this->get_wrapper_attributes();
    $inline_styles = $this->get_inline_styles();
    
    // ... rest of existing code
}
```

---

## 🔧 Standard Fix Templates

### For ALL Widgets Missing Preview:

Add this pattern to `/assets/js/editor.js`:

```javascript
case 'WIDGET-NAME':
    // Get settings with defaults
    const setting1 = settings.setting1 || 'Default Value';
    
    // Build HTML preview
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
        <h3 style="margin: 0 0 10px; color: #333;">${setting1}</h3>
        <p style="color: #666; margin: 0;">Preview content for this widget</p>
    </div>`;
```

### For ALL Widgets with Undefined Variables:

Add to start of `render()` method in widget PHP file:

```php
protected function render() {
    // Render custom CSS if any
    $this->render_custom_css();
    
    // Get wrapper classes and attributes from base class
    $wrapper_classes = $this->get_wrapper_classes();
    $wrapper_attributes = $this->get_wrapper_attributes();
    $inline_styles = $this->get_inline_styles();
    
    // Rest of your render code...
}
```

---

## 📋 Complete Fix Checklist

### Priority 1 (Today):
- [ ] social-icons (both fixes)
- [ ] woo-add-to-cart (preview)
- [ ] woo-cart (preview)
- [ ] contact-form (undefined vars)
- [ ] gallery (undefined vars)

### Priority 2 (This Week):
- [ ] video (undefined vars)
- [ ] recent-posts (undefined vars)
- [ ] google-maps (preview)
- [ ] audio (preview)
- [ ] woo-breadcrumbs (preview)
- [ ] woo-rating (preview)
- [ ] woo-related (preview)
- [ ] woo-reviews (preview)
- [ ] woo-meta (preview)

### Priority 3 (This Month):
- [ ] All remaining undefined variable fixes (10 widgets)
- [ ] All remaining preview additions (3 widgets)
- [ ] Comprehensive testing

---

## 🧪 Testing After Each Fix

1. **Clear browser cache**: `Ctrl + Shift + R`
2. **Check canvas**: Widget shows preview (not blank)
3. **Check settings**: Settings panel opens correctly
4. **Check frontend**: No PHP errors in console
5. **Test interactions**: Widget works as expected

---

## 💾 Backup Before Fixing

```bash
# Backup entire plugin before making changes
cp -r wp-content/plugins/probuilder wp-content/plugins/probuilder-backup
```

---

**Ready to fix? Start with social-icons - it's the most important!** 🚀

