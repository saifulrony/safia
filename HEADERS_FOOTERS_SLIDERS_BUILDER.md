# 🎨 ProBuilder - Headers, Footers & Sliders Builder!

## ✅ NEW FEATURE: Dedicated Builders for Headers, Footers & Sliders

You now have **separate admin pages** to create and manage:
- 📌 **Headers** - Custom site headers
- 📎 **Footers** - Custom site footers
- 🎬 **Sliders** - Hero sliders & carousels

Each with **full ProBuilder drag & drop** editing!

---

## 🚀 How to Access

### Method 1: ProBuilder Dashboard
```
WordPress Admin → ProBuilder (new menu!)
```

### Method 2: Direct Links
```
Headers:  /wp-admin/edit.php?post_type=pb_header
Footers:  /wp-admin/edit.php?post_type=pb_footer
Sliders:  /wp-admin/edit.php?post_type=pb_slider
```

---

## 📌 CREATE CUSTOM HEADERS

### What You Can Build:
- ✅ Logo + Navigation Menu
- ✅ Search Bar
- ✅ Shopping Cart Icon
- ✅ User Account Menu
- ✅ Sticky Headers
- ✅ Transparent Headers
- ✅ Multi-row Headers
- ✅ Mega Menus

### How to Create:

**Step 1: Create Header**
```
ProBuilder → Headers → Add New Header
```

**Step 2: Enter Details**
- Title: "Main Header" (or "Shop Header", "Landing Page Header", etc.)
- Click: "Publish"

**Step 3: Design with ProBuilder**
- Click: "Edit with ProBuilder" button
- Drag & drop widgets:
  - Image (for logo)
  - Menu widget
  - Search widget
  - WooCommerce Cart widget
  - Icons, buttons, text, etc.
- Customize colors, spacing, fonts
- Click: Save

**Step 4: Use Your Header**
- Copy shortcode: `[header id="123"]`
- Or assign site-wide in Theme Builder

---

## 📎 CREATE CUSTOM FOOTERS

### What You Can Build:
- ✅ Multi-Column Layouts (1-6 columns)
- ✅ Social Media Icons
- ✅ Newsletter Signup Forms
- ✅ Quick Links
- ✅ Copyright Text
- ✅ Payment Method Icons
- ✅ Contact Information
- ✅ Back to Top Button

### How to Create:

**Step 1: Create Footer**
```
ProBuilder → Footers → Add New Footer
```

**Step 2: Enter Details**
- Title: "Main Footer" (or "Dark Footer", "Minimal Footer", etc.)
- Click: "Publish"

**Step 3: Design with ProBuilder**
- Click: "Edit with ProBuilder"
- Use Grid Layout or Container for columns
- Add widgets:
  - Text Editor (for content)
  - Social Icons
  - Form widgets
  - Menu widget (for links)
  - Image (for logos)
- Customize background, spacing, colors
- Click: Save

**Step 4: Use Your Footer**
- Copy shortcode: `[footer id="456"]`
- Or assign site-wide in Theme Builder

---

## 🎬 CREATE CUSTOM SLIDERS

### What You Can Build:
- ✅ Hero Sliders (homepage banners)
- ✅ Full-Screen Sliders
- ✅ Product Carousels
- ✅ Testimonial Sliders
- ✅ Image Galleries
- ✅ Video Sliders
- ✅ Logo Carousels
- ✅ Content Sliders

### How to Create:

**Step 1: Create Slider**
```
ProBuilder → Sliders → Add New Slider
```

**Step 2: Enter Details**
- Title: "Homepage Hero Slider" (or "Product Carousel", etc.)
- Click: "Publish"

**Step 3: Design with ProBuilder**
- Click: "Edit with ProBuilder"
- Add Slider widget
- Add slides (each slide = container with content):
  - Background images
  - Heading widgets
  - Text widgets
  - Button widgets
  - Animations
- Configure slider settings:
  - Autoplay
  - Transition effects
  - Navigation (arrows, dots)
  - Speed
- Click: Save

**Step 4: Use Your Slider**
- Copy shortcode: `[slider id="789"]`
- Insert in any page/post
- Or use in templates

---

## 🎨 ProBuilder Dashboard

When you visit **ProBuilder** menu, you'll see:

### Beautiful Dashboard with Cards:

**📌 Headers Card** (Purple Gradient)
- Create Header button
- View All button
- Count of headers created

**📎 Footers Card** (Pink Gradient)
- Create Footer button
- View All button
- Count of footers created

**🎬 Sliders Card** (Blue Gradient)
- Create Slider button
- View All button
- Count of sliders created

### Features Overview Section:
Shows what you can build with each type

### Quick Tips Section:
- How to edit with ProBuilder
- How to use shortcodes
- Tips for responsive design

---

## 📋 Admin List Pages

### Each type has a custom list page with columns:

**Standard Columns:**
- ☑️ Checkbox (bulk actions)
- 📝 Title
- 📅 Date
- ✏️ Actions (Edit, Trash, etc.)

**Custom Columns:**
- 📄 **Shortcode** - Copy shortcode button
- 👁️ **Preview** - Preview button

**Actions:**
- Edit
- Edit with ProBuilder (pink color, highlighted)
- Trash
- View

---

## 🎯 Use Cases

### E-commerce Store:

**Headers:**
```
Main Header - Logo, Menu, Search, Cart
Sale Header - Promo banner + navigation
Minimal Header - Logo only (for landing pages)
```

**Footers:**
```
Main Footer - 4 columns (About, Links, Contact, Newsletter)
Minimal Footer - Copyright + social icons only
Shop Footer - Payment icons + quick links
```

**Sliders:**
```
Homepage Hero - 3 slides showcasing products
Category Slider - Products carousel
Testimonials - Customer reviews slider
```

### Landing Pages:

**Headers:**
```
Transparent Header - For hero sections
Fixed Header - Stays on top while scrolling
```

**Footers:**
```
CTA Footer - Large call-to-action + form
Simple Footer - Just copyright
```

**Sliders:**
```
Feature Showcase - 5 slides showing features
Before/After Slider - Product comparisons
```

---

## 📱 Responsive Design

All parts are **fully responsive**:
- ✅ Desktop optimized
- ✅ Tablet friendly
- ✅ Mobile ready
- ✅ Touch gestures (for sliders)

---

## 🔧 Technical Details

### Custom Post Types Created:

1. **`pb_header`** - Headers
2. **`pb_footer`** - Footers
3. **`pb_slider`** - Sliders

### Features:

**ProBuilder Integration:**
- ✅ "Edit with ProBuilder" button
- ✅ Full drag & drop editor
- ✅ All 110+ widgets available
- ✅ Save/Load functionality
- ✅ Revisions support

**Admin Features:**
- ✅ Custom dashboard
- ✅ List pages with custom columns
- ✅ Copy shortcode button
- ✅ Preview button
- ✅ Meta boxes with info
- ✅ Counts in dashboard

**Shortcodes:**
- `[header id="123"]`
- `[footer id="456"]`
- `[slider id="789"]`

---

## 📂 File Structure

```
wp-content/plugins/probuilder/includes/
  └─ class-custom-parts.php (NEW!)
     - Registers 3 custom post types
     - Creates ProBuilder dashboard
     - Adds admin menus
     - Manages list pages
     - Handles shortcodes
```

---

## 🎨 How It Works

### Workflow:

```
1. Create New (Header/Footer/Slider)
   ↓
2. Enter Title & Publish
   ↓
3. Click "Edit with ProBuilder"
   ↓
4. ProBuilder Editor Opens (Full Canvas)
   ↓
5. Drag & Drop Widgets
   ↓
6. Customize Settings
   ↓
7. Save
   ↓
8. Copy Shortcode
   ↓
9. Use Anywhere!
```

### Data Storage:

- Title: Post title
- Content: ProBuilder data (`_probuilder_data`)
- Shortcode: Generated from post ID
- Preview: Uses ProBuilder frontend renderer

---

## ✨ Advantages

### vs Hard-Coded Headers/Footers:
- ✅ No coding required
- ✅ Visual editor
- ✅ Easy to update
- ✅ Multiple variations
- ✅ Reusable

### vs Page Builder Limitations:
- ✅ Dedicated sections
- ✅ Better organization
- ✅ Shortcode support
- ✅ Easy management

### vs Theme Builder Only:
- ✅ More flexible
- ✅ Can be used anywhere
- ✅ Not limited to theme rules
- ✅ Portable between sites

---

## 🚀 Next Steps

### Phase 1 (Complete): ✅
- Custom post types
- Admin dashboard
- List pages
- ProBuilder integration
- Shortcodes

### Phase 2 (Future):
- [ ] Theme Builder integration (assign site-wide)
- [ ] Conditional display rules
- [ ] Import/export
- [ ] Template library
- [ ] Live preview in settings

---

## 📖 Quick Reference

### Menu Location:
```
WordPress Admin → ProBuilder (new top-level menu)
  ├─ Dashboard
  ├─ 📌 Headers
  ├─ 📎 Footers
  ├─ 🎬 Sliders
  └─ 📦 Template Parts
```

### Create New:
```
ProBuilder → [Type] → Add New
```

### Edit with Builder:
```
List Page → Hover → "Edit with ProBuilder" (pink)
```

### Copy Shortcode:
```
List Page → Shortcode Column → "Copy" button
```

### Preview:
```
List Page → Preview Column → "Preview" button
```

---

## ✅ STATUS: COMPLETE & READY!

🎉 **You now have professional Header, Footer & Slider builders with full drag & drop functionality!**

**Perfect for:**
- E-commerce stores
- Corporate websites
- Landing pages
- Portfolio sites
- Blogs
- Any WordPress site!

---

*Last Updated: November 4, 2025*  
*ProBuilder Version: 2.1 - Custom Parts Edition*

