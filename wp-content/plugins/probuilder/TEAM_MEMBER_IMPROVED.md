# Team Member Widget - Improved! ✅

## What's New

The Team Member widget has been completely redesigned with flexible layout options and better styling controls.

---

## 🎨 **New Features**

### 1. **Layout Options** (Image Position)
Choose how to display your team member:

- **Image Left** (Default)
  - Photo on the left side
  - Content on the right
  - Perfect for horizontal cards

- **Image Top (Centered)**
  - Photo at the top center
  - Content below
  - Classic centered layout

- **Image Right**
  - Photo on the right side
  - Content on the left
  - Great for alternating layouts

### 2. **Content Alignment**
Control how text is aligned:

- **Left** - Text aligned to the left
- **Center** - Text centered
- **Right** - Text aligned to the right

Works with all layout options!

### 3. **Customizable Image Size**
- Slider control: 80px to 300px
- Default: 150px
- Perfect circular images

### 4. **Color Controls**
- **Image Border Color** - Customize the ring around the photo
- **Name Color** - Change the name text color
- **Position Color** - Change the position/title color

### 5. **Instagram Support**
Added Instagram to social media options:
- Facebook
- Twitter
- LinkedIn
- **Instagram** (NEW!)

---

## 📋 **How to Use**

### Step 1: Add Content
1. Drag **Team Member** widget to your page
2. **Content Tab:**
   - Upload photo
   - Enter name
   - Enter position/title
   - Add bio (optional)
   - Add email and phone (optional)
   
3. **Social Links Tab:**
   - Add Facebook URL
   - Add Twitter URL
   - Add LinkedIn URL
   - Add Instagram URL

### Step 2: Choose Layout
1. Go to **Style Tab**
2. **Layout:** Select image position
   - Image Left
   - Image Top (Centered)
   - Image Right

### Step 3: Customize Style
1. **Content Alignment:** Choose text alignment
2. **Image Size:** Adjust with slider (80-300px)
3. **Image Border Color:** Pick a color for the photo border
4. **Name Color:** Customize name text color
5. **Position Color:** Customize position/title color

---

## 💡 **Usage Examples**

### Example 1: Simple Team Grid
```
Create a 3-column container
Add 3 Team Member widgets
Layout: Image Top (Centered)
Content Alignment: Center
```

Result: Classic team grid with centered photos and text

### Example 2: Detailed Team List
```
Add Team Member widgets vertically
Layout: Image Left
Content Alignment: Left
Add full bio for each member
```

Result: Detailed team list with photos on the left

### Example 3: Alternating Layout
```
Add multiple Team Member widgets
Alternate between:
- Widget 1: Image Left
- Widget 2: Image Right
- Widget 3: Image Left
etc.
```

Result: Dynamic zigzag layout

### Example 4: Executive Board
```
Layout: Image Left
Image Size: 200px
Content Alignment: Left
Border Color: Gold (#FFD700)
```

Result: Premium look for C-level executives

---

## 🎯 **Visual Examples**

### Layout: Image Left
```
┌─────────────────────────────────────┐
│  [Photo]  John Doe                  │
│           CEO & Founder             │
│           Bio text here...          │
│           📧 email@example.com      │
│           📞 +1 234 567 890         │
│           [f] [t] [in] [ig]         │
└─────────────────────────────────────┘
```

### Layout: Image Top (Centered)
```
┌─────────────────────────────────────┐
│            [Photo]                  │
│                                     │
│           John Doe                  │
│        CEO & Founder                │
│      Bio text here...               │
│   📧 email@example.com              │
│   📞 +1 234 567 890                 │
│     [f] [t] [in] [ig]               │
└─────────────────────────────────────┘
```

### Layout: Image Right
```
┌─────────────────────────────────────┐
│                 John Doe   [Photo]  │
│              CEO & Founder          │
│           ...Bio text here          │
│     email@example.com 📧            │
│      +1 234 567 890 📞              │
│        [f] [t] [in] [ig]            │
└─────────────────────────────────────┘
```

---

## 🎨 **Style Details**

### Image
- Circular shape (border-radius: 50%)
- Customizable size (80-300px)
- 3px solid border
- Customizable border color
- Object-fit: cover (no distortion)

### Name
- Font size: 22px
- Font weight: 600 (semi-bold)
- Customizable color
- Margin: 0 0 5px 0

### Position
- Font size: 14px
- Font weight: 600 (semi-bold)
- Customizable color
- Margin: 0 0 15px 0

### Bio
- Font size: 14px
- Line height: 1.6
- Color: #666
- Margin: 0 0 15px 0

### Contact Info
- Font size: 13px
- Icons colored with position color
- Proper alignment based on content alignment
- Icons with 8px gap from text

### Social Icons
- Size: 35px × 35px
- Circular buttons
- Brand colors:
  - Facebook: #3b5998
  - Twitter: #1da1f2
  - LinkedIn: #0077b5
  - Instagram: Gradient
- 10px gap between icons
- Hover effects included

---

## 📱 **Responsive Behavior**

The widget is fully responsive and adapts to different screen sizes:

- **Desktop:** Full layout as configured
- **Tablet:** Maintains layout
- **Mobile:** Automatically adjusts spacing

---

## 🔧 **Technical Details**

### Files Modified
1. `/wp-content/plugins/probuilder/widgets/team-member.php`
   - Added layout control
   - Added content alignment control
   - Added image size control
   - Added color controls
   - Added Instagram support
   - Improved rendering logic

2. `/wp-content/plugins/probuilder/assets/js/editor.js`
   - Updated preview template
   - Added layout support
   - Added all new style options
   - Improved preview rendering

### New Controls
- `layout` - Select (left/center/right)
- `text_align` - Select (left/center/right)
- `image_size` - Slider (80-300px)
- `border_color` - Color picker
- `name_color` - Color picker
- `position_color` - Color picker
- `instagram` - URL input

---

## ✨ **Benefits**

✅ **More Flexible** - 3 layout options instead of just centered
✅ **Better Control** - Customize colors, sizes, and alignment
✅ **Professional Look** - Side-by-side layouts for modern designs
✅ **Instagram Support** - Complete social media integration
✅ **Easy to Use** - Intuitive controls in Style tab
✅ **Responsive** - Works great on all devices
✅ **Real-time Preview** - See changes immediately in editor

---

## 🚀 **Quick Start**

1. **Clear browser cache** (Ctrl + Shift + R)
2. Open ProBuilder Editor
3. Drag **Team Member** widget
4. Add content
5. Go to **Style Tab**
6. Choose **Layout:** Image Left
7. Choose **Content Alignment:** Left
8. Adjust **Image Size** if needed
9. Customize colors
10. Done!

---

## 💬 **Tips**

**For Team Grids:**
- Use Image Top (Centered) layout
- Center all content
- Keep bio short
- Add all social links

**For Team Lists:**
- Use Image Left or Right layout
- Left-align content for better readability
- Include detailed bios
- Alternate layouts for visual interest

**For Executive Sections:**
- Use larger image size (200px+)
- Custom border color (gold/brand color)
- Include full contact information
- Professional headshots

**For Footer Team Section:**
- Use Image Top (Centered)
- Smaller image size (100px)
- Minimal bio
- Social links only

---

## 📊 **Comparison**

### Before
- ✗ Only centered layout
- ✗ Fixed image size (150px)
- ✗ No color controls
- ✗ No content alignment options
- ✗ No Instagram support

### After
- ✅ 3 layout options (left/center/right)
- ✅ Adjustable image size (80-300px)
- ✅ Full color customization
- ✅ Content alignment control
- ✅ Instagram support included
- ✅ Better spacing and alignment
- ✅ More professional appearance

---

## ✅ **Status**

**All improvements complete and tested!**

- ✅ Layout options working
- ✅ Content alignment working
- ✅ Color controls working
- ✅ Image size control working
- ✅ Instagram support added
- ✅ Preview template updated
- ✅ No JavaScript errors
- ✅ No PHP errors
- ✅ Responsive design
- ✅ Production ready

---

*Last Updated: October 24, 2025*
*ProBuilder Plugin - Team Member Widget v2.0*

