# 🎨 How to Use ProBuilder - Elementor-Style Interface

## ✅ You Confirmed It Works!

The standalone editor works perfectly! Now let's use it properly.

---

## 🎯 TWO WAYS TO USE PROBUILDER

### Method 1: "Edit with ProBuilder" Link (Fixed!)

**After reactivating plugin:**

1. Go to **Pages → All Pages**
   ```
   http://192.168.10.203:7000/wp-admin/edit.php?post_type=page
   ```

2. **Hover** over any page (like "About Us")

3. Click **"Edit with ProBuilder"** (pink colored link)

4. It will open:
   ```
   http://192.168.10.203:7000/?p=489&probuilder=true
   ```

5. **You'll see the working interface!**

---

### Method 2: Direct URL (Always Works!)

Just open this URL format:
```
http://192.168.10.203:7000/?p=PAGE_ID&probuilder=true
```

**Examples:**
- Page 489 (About Us): `http://192.168.10.203:7000/?p=489&probuilder=true`
- Page 2: `http://192.168.10.203:7000/?p=2&probuilder=true`
- Page 5: `http://192.168.10.203:7000/?p=5&probuilder=true`

---

### Method 3: Standalone Editor (Guaranteed!)

Use the standalone file:
```
http://192.168.10.203:7000/wp-content/plugins/probuilder/editor-standalone.php?post_id=489
```

Change `post_id=489` to any page ID you want to edit.

---

## 📋 QUICK REFERENCE

### Find Page IDs

**Option A: From Pages List**
1. Go to Pages → All Pages
2. Hover over a page
3. Look at bottom of browser - you'll see: `post=123` (123 is the ID)

**Option B: From URL**
1. Click "Edit" on any page
2. Look at URL: `...post.php?post=489&action=edit`
3. The number after `post=` is the ID (489)

**Option C: Using Standalone**
1. Just try different numbers
2. Start with: `?post_id=2`
3. Then: `?post_id=3`, `?post_id=4`, etc.

---

## 🎨 USING THE INTERFACE

Once you're in the editor:

### LEFT SIDEBAR (320px wide)
- **Search** - Find widgets quickly
- **LAYOUT** - Container widget
- **BASIC** - Heading, Text, Button, Image, Divider, Spacer
- **ADVANCED** - Tabs, Accordion, Carousel, Gallery
- **CONTENT** - Icon Box, Image Box, Progress Bar, Testimonial, Counter, etc.

### CENTER CANVAS
- Drag widgets here
- See real-time preview
- Hover to see element controls
- Click to select element

### RIGHT SETTINGS (slides from right)
- Click "Edit" on any element
- **CONTENT** tab - Edit text, links, etc.
- **STYLE** tab - Colors, fonts, spacing
- **ADVANCED** tab - CSS, custom options

### TOP HEADER
- **Desktop/Tablet/Mobile** - Switch preview modes
- **PREVIEW** - View page in new tab
- **SAVE** - Save your changes (or Ctrl+S)
- **EXIT** - Return to WordPress

---

## 🚀 QUICK START WORKFLOW

### Build a Hero Section (2 minutes):

1. **Open Editor**
   ```
   http://192.168.10.203:7000/?p=489&probuilder=true
   ```

2. **Add Container**
   - Drag "Container" from LAYOUT
   - Click "Edit"
   - Set Background Color: #92003b (pink)
   - Set Padding: 80px all sides
   - Close settings

3. **Add Heading**
   - Drag "Heading" inside container
   - Click "Edit"
   - Change text: "Welcome to Our Website"
   - Set Color: #ffffff (white)
   - Set Font Size: 48px
   - Set Alignment: Center

4. **Add Text**
   - Drag "Text" below heading
   - Click "Edit"
   - Add your description
   - Set Color: #ffffff (white)

5. **Add Button**
   - Drag "Button" below text
   - Click "Edit"
   - Change text: "Get Started"
   - Set Alignment: Center

6. **Save**
   - Click "SAVE" button
   - Or press Ctrl+S

7. **Preview**
   - Click "PREVIEW" to see it live!

---

## 🎯 BOOKMARKS TO SAVE

### Your Main Editor URLs

**About Us Page:**
```
http://192.168.10.203:7000/?p=489&probuilder=true
```

**Home Page (usually ID 2):**
```
http://192.168.10.203:7000/?p=2&probuilder=true
```

**Contact Page (find ID from Pages list):**
```
http://192.168.10.203:7000/?p=YOUR_ID&probuilder=true
```

### Quick Access

**Pages List:**
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=page
```

**ProBuilder Dashboard:**
```
http://192.168.10.203:7000/wp-admin/admin.php?page=probuilder
```

**ProBuilder Settings:**
```
http://192.168.10.203:7000/wp-admin/admin.php?page=probuilder-settings
```

---

## 💡 PRO TIPS

### Efficiency Tips
1. **Bookmark editor URL** for quick access
2. **Use Ctrl+S** to save (faster than clicking)
3. **Duplicate elements** instead of recreating
4. **Search widgets** instead of scrolling
5. **Use keyboard shortcuts**

### Design Tips
1. **Start with containers** for structure
2. **Use consistent colors** throughout
3. **Test all device modes** (Desktop/Tablet/Mobile)
4. **Preview often** to see real appearance
5. **Save frequently** to avoid data loss

### Workflow Tips
1. **Build desktop first**, then check mobile
2. **Use spacer** widget between sections
3. **Group related content** in containers
4. **Use heading hierarchy** (H1 → H2 → H3)
5. **Add icons** to make content engaging

---

## 🔧 TROUBLESHOOTING

### "Edit with ProBuilder" Shows Gutenberg
**Solution:** Deactivate → Reactivate plugin
Then use the new pink link.

### Widget Changes Not Showing
**Solution:** Click the "Edit" button on the element,
make changes, then the preview updates automatically.

### Can't Save
**Solution:** Check browser console (F12) for errors.
Make sure you're logged into WordPress.

### Widgets Not Dragging
**Solution:** Make sure jQuery UI is loaded.
Check console for "jQuery.ui" - should be "object".

---

## 📱 USING RESPONSIVE MODES

### Desktop Mode (Default)
- Canvas width: 1140px
- Design for desktop/laptop
- Full layout visible

### Tablet Mode
- Canvas width: 768px
- Simulates iPad
- Test tablet layouts

### Mobile Mode
- Canvas width: 375px
- Simulates iPhone
- Essential for mobile users

**How to Switch:**
Click the Desktop 🖥️, Tablet 📱, or Mobile 📱 icons in the header!

---

## 🎨 WIDGET QUICK REFERENCE

### Most Used Widgets:

1. **Container** - Section backgrounds
2. **Heading** - Titles (H1-H6)
3. **Text** - Paragraphs
4. **Button** - Call-to-action
5. **Image** - Photos/graphics
6. **Icon Box** - Features with icons
7. **Spacer** - Vertical spacing
8. **Divider** - Horizontal lines

### Advanced Widgets:

9. **Tabs** - Organized content
10. **Accordion** - FAQs
11. **Carousel** - Image slider
12. **Gallery** - Photo grid
13. **Progress Bar** - Skills/stats
14. **Testimonial** - Reviews
15. **Counter** - Animated numbers
16. **Pricing Table** - Plans
17. **Video** - YouTube/Vimeo
18. **Map** - Google Maps

---

## ⌨️ KEYBOARD SHORTCUTS

- `Ctrl+S` or `Cmd+S` - Save page
- `Delete` - Delete selected element
- `Esc` - Close settings panel
- `Ctrl+Z` - Undo (future feature)

---

## 📞 NEED HELP?

Check these files in `/wp-content/plugins/probuilder/`:
- **README.md** - Complete documentation
- **QUICK_START.md** - 5-minute guide
- **WIDGETS_GUIDE.md** - All widgets explained
- **TROUBLESHOOTING.md** - Debug help
- **QUICK_FIX.md** - Common issues

---

## 🎊 YOU'RE ALL SET!

### To Edit Any Page:

**Quick Method:**
```
http://192.168.10.203:7000/?p=PAGE_ID&probuilder=true
```

**Or use:** "Edit with ProBuilder" pink link in Pages list

### What You Get:
✅ 20+ widgets like Elementor
✅ Drag & drop interface
✅ Visual preview
✅ 13 control types
✅ Responsive modes
✅ Professional design
✅ NOT Gutenberg!

---

**Happy Building! 🚀**

Start creating beautiful pages with ProBuilder's Elementor-style interface!

