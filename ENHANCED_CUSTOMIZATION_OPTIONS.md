# 🎨 Enhanced Customization Options - Complete Guide

## ✅ New Features Added

### 1. **Global Layout Settings** (Full-width / Boxed)

Located in: `wp-content/plugins/probuilder/includes/class-global-styles.php`

**Options:**
- **Content Width**: Choose between `full` or `boxed` layout
- **Boxed Width**: Set max-width for boxed layout (default: 1200px)
- **Boxed Padding**: Set side padding for boxed layout (default: 15px)

**CSS Variables:**
- `--pb-layout-content_width`
- `--pb-layout-boxed_width`
- `--pb-layout-boxed_padding`

**Applied automatically** to `.probuilder-page-wrapper` class.

---

## 2. **Slider Widget - MASSIVE Enhancement** 🚀

### New Advanced Effects Section
- **Ken Burns Zoom Level** (1 - 1.5x) - Control zoom effect intensity
- **Parallax Speed** (0.1 - 1.0) - Adjust parallax scrolling speed
- **Background Blur** (0 - 20px) - Apply blur filter to background
- **Background Brightness** (0 - 200%) - Adjust image brightness
- **Background Contrast** (0 - 200%) - Adjust image contrast
- **Content Text Shadow** - Add shadow for better readability
- **Content Backdrop Blur** - Glassmorphism effect behind content

### New Responsive Settings Section
- **Mobile Height** - Custom slider height for mobile devices
- **Tablet Height** - Custom slider height for tablets
- **Hide Content on Mobile** - Toggle title/description visibility
- **Hide Arrows on Mobile** - Toggle navigation arrows on mobile
- **Enable Touch Swipe** - Allow swiping on touch devices
- **Keyboard Navigation** - Enable arrow keys for navigation

### New Performance & Accessibility Section
- **Lazy Load Images** - Load images only when needed
- **Preload Next Slide** - Preload next image for smooth transitions
- **Accessibility Labels** - Add ARIA labels for screen readers
- **Respect Reduced Motion** - Honor user's motion preferences

**Total Slider Options:** 40+ customization controls!

---

## 3. **WooCommerce Products Widget - Professional Grade** 🛒

### New Image & Hover Effects Section
- **Image Aspect Ratio**: Square (1:1), Standard (4:3), Wide (16:9), Portrait (3:4), Custom
- **Custom Image Height**: Set exact height in pixels
- **Image Fit**: Cover, Contain, Fill
- **Hover Effect**: 8 options
  - None
  - Zoom In
  - Zoom Out
  - Slide
  - Rotate
  - Blur
  - Grayscale to Color
  - Fade (Opacity)
- **Show Second Image on Hover** - Display gallery second image
- **Image Overlay on Hover** - Add color overlay
- **Overlay Color** - Customize overlay color
- **Card Shadow**: None, Small, Medium, Large
- **Hover Shadow**: None, Small, Medium, Large, XL
- **Lift Card on Hover** - Slight elevation effect

### New Badge & Labels Section
- **Badge Style**: Modern Rounded, Minimal, Bold, Outline
- **Badge Position**: Top Left, Top Right, Bottom Left, Bottom Right
- **Sale Badge Color** - Customize sale badge
- **Featured Badge Color** - Customize featured badge
- **Show "New" Badge** - Auto-show for new products
- **New Badge Duration** - Days to show "new" badge (default: 30)
- **Out of Stock Badge** - Show out of stock indicator

### New Quick Actions Section
- **Show Quick View** - Quick view button on hover
- **Show Wishlist** - Add to wishlist button
- **Show Compare** - Add to compare button
- **Quick Actions Style**: 
  - Always Visible
  - Show on Hover
  - Icons Only
  - Text Only

### New Typography Section
- **Title Font Size** - Custom title size (px)
- **Title Font Weight**: Normal, Medium, Semi Bold, Bold
- **Price Font Size** - Custom price size (px)
- **Price Font Weight**: Normal, Medium, Semi Bold, Bold

### New Pagination Section
- **Enable Pagination** - Toggle pagination
- **Pagination Type**:
  - Numbers
  - Prev/Next Only
  - Load More Button
  - Infinite Scroll

**Total Products Widget Options:** 50+ customization controls!

---

## 📊 Summary Statistics

| Widget | Total Controls | New Controls Added |
|--------|---------------|-------------------|
| Slider | 40+ | 23 new controls |
| Products | 50+ | 33 new controls |
| **Global Styles** | **Layout: 3 new options** | **Full/Boxed width control** |

---

## 🎯 How to Use

### Setting Global Layout (Full-width / Boxed)

1. Open ProBuilder editor
2. Click Settings icon (⚙️)
3. Navigate to "Global Styles"
4. Find "Layout" section
5. Choose "Full Width" or "Boxed"
6. If Boxed, set max-width and padding
7. Save settings

### Customizing Slider

1. Add/Select Slider widget
2. Open widget settings panel
3. **Content Tab**:
   - Basic slides configuration
   - Performance & Accessibility options
4. **Style Tab**:
   - Advanced Effects (blur, brightness, parallax)
   - Responsive Settings (mobile/tablet heights)
   - Navigation styles
   - Colors

### Customizing Products Widget

1. Add/Select Products widget
2. Open widget settings panel
3. **Content Tab**:
   - Query Type (featured, sale, best selling)
   - Quick Actions (quick view, wishlist)
   - Pagination options
4. **Style Tab**:
   - Image & Hover Effects
   - Badge & Labels styling
   - Typography settings
   - Colors

---

## 🔥 Best Practices

### For Sliders
- Use **Ken Burns** effect sparingly on hero sections
- Enable **Lazy Load** for better performance
- Set **Mobile Height** to 400-500px for optimal mobile experience
- Use **Content Backdrop Blur** for text on busy backgrounds
- Enable **Accessibility Labels** for better SEO

### For Products
- Use **Hover Effect: Zoom** for modern stores
- Enable **Show Second Image on Hover** for fashion/clothing stores
- Use **Card Hover Lift** for premium feel
- Set **Image Ratio to 1:1** for consistent grid
- Enable **Quick View** for better UX
- Use **Load More** pagination for modern scroll experience

### For Layout
- Use **Boxed Layout (1200px)** for better readability on large screens
- Use **Full Width** for modern, edge-to-edge designs
- Set **Boxed Padding to 15-20px** for mobile breathing room

---

## 🚀 What's Next?

Your ProBuilder now has **professional-grade customization** options comparable to:
- **Elementor Pro**
- **WPBakery Page Builder**
- **Beaver Builder**

You can now create:
- ✅ Beautiful hero sliders with advanced effects
- ✅ Professional product grids with hover effects
- ✅ Full-width or boxed layouts
- ✅ Mobile-optimized experiences
- ✅ Accessible, SEO-friendly pages

**All without touching a single line of code!** 🎉

---

## 📝 Files Modified

1. `wp-content/plugins/probuilder/includes/class-global-styles.php` - Added layout options
2. `wp-content/plugins/probuilder/widgets/slider.php` - Added 23 new controls
3. `wp-content/plugins/probuilder/widgets/woo-products.php` - Added 33 new controls

---

**Created:** November 4, 2025  
**ProBuilder Version:** 2.0 (Enhanced)  
**Total New Controls:** 59 customization options added!

