# Quick Start Guide - New Widget Options 🚀

## What's New?

All ProBuilder widgets now have **8 powerful new style options** automatically added, plus the **Image widget has been completely overhauled** with 27+ customization options!

---

## 🎨 New Global Style Options (Available on ALL Widgets)

### How to Access:
1. Open any page in ProBuilder editor
2. Add or click any widget
3. Go to the **Style** tab
4. Scroll down to find these new sections:

### Section 1: Advanced Options
- **Opacity** - Make elements transparent (0 = invisible, 1 = solid)
- **Z-Index** - Control layering (higher numbers appear on top)
- **CSS Classes** - Add custom classes (space-separated)
- **CSS ID** - Add a unique ID for the element

### Section 2: Display on Devices
- **Hide on Desktop** - Hide on screens > 1024px
- **Hide on Tablet** - Hide on screens 768px - 1024px  
- **Hide on Mobile** - Hide on screens < 768px

### Section 3: Custom CSS
- **Custom CSS** - Write custom CSS code for this element
- Monospace code editor with syntax highlighting
- Applies only to this specific element

---

## 📸 Image Widget - New Features

The Image widget now has **27+ comprehensive options**!

### Quick Tour:

#### 1. Content Tab - Image Section
- ✅ Beautiful default sample image (no more placeholder.com!)
- ✅ Image size selection (thumbnail, medium, large, full)
- ✅ Alt text for SEO and accessibility
- ✅ Caption with styled display

#### 2. Content Tab - Link Section
- ✅ Link to: None, Custom URL, or Lightbox
- ✅ Custom URL field
- ✅ Open in new tab option

#### 3. Style Tab - Image Section
- ✅ Width (percentage slider)
- ✅ Max width (pixels)
- ✅ Height (pixels)
- ✅ Object fit (cover, contain, fill, etc.)
- ✅ Alignment (left, center, right)

#### 4. Style Tab - Border Section
- ✅ Border radius (0-200px for rounded corners)
- ✅ Border width (0-20px)
- ✅ Border color picker

#### 5. Style Tab - Effects Section
- ✅ Hover animations (zoom in, zoom out, slide, rotate)
- ✅ Brightness (0-200%)
- ✅ Contrast (0-200%)
- ✅ Saturation (0-200%)
- ✅ Blur (0-10px)
- ✅ Hue rotate (0-360 degrees)

#### 6. Style Tab - Box Shadow Section
- ✅ Enable/disable shadow
- ✅ Horizontal offset
- ✅ Vertical offset
- ✅ Blur radius
- ✅ Spread
- ✅ Shadow color with transparency

---

## 💡 Usage Examples

### Example 1: Create a Faded Background Element
1. Add any widget (e.g., Text widget)
2. Go to Style tab
3. Scroll to "Advanced Options"
4. Set **Opacity** to `0.5`
5. Result: Element is 50% transparent

### Example 2: Mobile-Only Button
1. Add a Button widget
2. Go to Style tab
3. Scroll to "Display on Devices"
4. Enable **Hide on Desktop**
5. Enable **Hide on Tablet**
6. Result: Button only shows on mobile devices

### Example 3: Circular Profile Image with Shadow
1. Add Image widget
2. Upload your image
3. Go to Style tab → Border section
4. Set **Border Radius** to `200px` (or 50%)
5. Go to Box Shadow section
6. Enable **Box Shadow**
7. Adjust shadow values as desired
8. Result: Beautiful circular image with shadow

### Example 4: Hover Zoom Image Gallery
1. Add Image widget
2. Upload image
3. Go to Style tab → Effects section
4. Set **Hover Animation** to "Zoom In"
5. Optionally add a **Link** to lightbox
6. Result: Image zooms on hover and opens in lightbox when clicked

### Example 5: Sepia-Toned Vintage Image
1. Add Image widget
2. Upload image
3. Go to Style tab → Effects section
4. Set **Saturation** to `30` (desaturate)
5. Set **Contrast** to `110` (increase slightly)
6. Set **Brightness** to `95` (darken slightly)
7. Result: Vintage sepia-tone effect

---

## 🎯 Pro Tips

### Tip 1: Custom CSS for Advanced Users
Use the Custom CSS option to add complex styling:
```css
/* Example: Add a gradient overlay */
.my-element::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.7));
}
```

### Tip 2: Responsive Design Strategy
- Design desktop first
- Use "Hide on Desktop" for mobile-specific elements
- Use "Hide on Mobile" for desktop-specific elements
- Keep tablet as the default middle ground

### Tip 3: Z-Index Layering
- Navigation: 1000+
- Modals/Popups: 900-999
- Fixed elements: 100-899
- Regular content: 1-99
- Background elements: 0 or negative

### Tip 4: Image Optimization
1. Use appropriate image sizes (don't upload 5MB images!)
2. Set **Max Width** to prevent oversized images
3. Use **Object Fit: Cover** for consistent sizing
4. Add **Alt Text** for every image (SEO!)
5. Use **Lazy Loading** when available

### Tip 5: Hover Effects
Combine multiple options for stunning effects:
- Hover animation: Zoom In
- Box shadow: Increase on hover (use Custom CSS)
- Brightness: Increase slightly
- Result: Professional image gallery effect

---

## 🔥 Advanced Examples

### Example: Frosted Glass Effect
```css
/* Add this to Custom CSS */
.frosted-glass {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
```
Then add `frosted-glass` to CSS Classes field.

### Example: Parallax-Style Z-Index Layering
1. Background image: Z-Index = -1
2. Content: Z-Index = 1
3. Floating elements: Z-Index = 10
4. Navigation: Z-Index = 100

### Example: Animated Gradient Border
```css
/* Add to Custom CSS */
@keyframes gradient-rotate {
    0% { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(360deg); }
}

.animated-border {
    animation: gradient-rotate 3s linear infinite;
    border: 3px solid;
    border-image: linear-gradient(45deg, #f06, #4a90e2, #f06) 1;
}
```

---

## 🐛 Troubleshooting

### Issue: Options not showing in Style tab
**Solution:** 
1. Clear browser cache
2. Hard refresh (Ctrl+Shift+R or Cmd+Shift+R)
3. Check browser console for JavaScript errors

### Issue: Custom CSS not applying
**Solution:**
1. Make sure CSS syntax is correct
2. Use browser dev tools (F12) to inspect element
3. Check if styles are being overridden by more specific selectors
4. Try adding `!important` as last resort

### Issue: Responsive visibility not working
**Solution:**
1. Test on actual devices or browser responsive mode
2. Clear cache
3. Check if conflicting CSS exists
4. Verify media query breakpoints

### Issue: Image not displaying
**Solution:**
1. Check image URL is valid
2. Verify file upload was successful
3. Check file permissions
4. Try a different image source

---

## ✅ Verification Checklist

Test these to ensure everything is working:

- [ ] Open ProBuilder editor
- [ ] Add a Heading widget
- [ ] Go to Style tab
- [ ] Verify "Advanced Options" section exists
- [ ] Verify "Display on Devices" section exists
- [ ] Verify "Custom CSS" section exists
- [ ] Add an Image widget
- [ ] Verify default sample image loads
- [ ] Check all 6 style sections are present
- [ ] Test opacity slider
- [ ] Test responsive visibility toggles
- [ ] Test custom CSS editor
- [ ] Test image border radius
- [ ] Test image hover animation
- [ ] Test image filters (brightness, contrast, etc.)
- [ ] Test box shadow controls
- [ ] View test results: `widget-test-results.html`

---

## 📚 Additional Resources

- **Full Documentation:** `WIDGET_ENHANCEMENTS_COMPLETE.md`
- **Test Results:** `widget-test-results.html`
- **Modified Files:**
  - `wp-content/plugins/probuilder/includes/class-base-widget.php`
  - `wp-content/plugins/probuilder/widgets/image.php`
  - `wp-content/plugins/probuilder/widgets/heading.php`
  - `wp-content/plugins/probuilder/assets/js/editor.js`
  - `wp-content/plugins/probuilder/assets/css/editor.css`

---

## 🚀 Get Started Now!

1. Open WordPress admin: `http://localhost:7000/wp-admin`
2. Edit any page with ProBuilder
3. Add any widget and explore the new Style tab options
4. Add an Image widget and try all 27+ options
5. Create something beautiful! 🎨

---

**Happy Building! 🎉**

*All 100+ ProBuilder widgets now have these options automatically.*
*Zero configuration required - just start using them!*

