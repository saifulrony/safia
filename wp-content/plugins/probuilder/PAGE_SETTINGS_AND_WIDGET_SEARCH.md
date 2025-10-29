# Page Settings & Widget Search Features

## ✨ New Features Added

### 1. 📝 Page Settings (Title & URL)
### 2. 🔍 Widget Search & Tabs

---

## 📝 Feature 1: Page Settings

### Overview
Edit your page title and URL directly from the ProBuilder editor - no need to go back to WordPress admin!

### How to Access

Click the **"Page Settings"** button in the ProBuilder header (next to Preview button).

```
┌────────────────────────────────────────┐
│ ProBuilder  |  [Page Settings] [Preview] [Save] [Exit] │
└────────────────────────────────────────┘
```

### What You Can Edit

#### 1. **Page Title**
- The main heading of your page
- Shows in browser tabs, search results, and page header
- Example: "About Us", "Contact", "Home Page"

#### 2. **Page URL (Slug)**
- The web address of your page
- Auto-generated from title (when empty)
- Sanitized in real-time (lowercase, hyphens only)
- Example: "about-us", "contact", "home-page"

### Modal Interface

```
┌─────────────────────────────────────────┐
│  ⚙️ Page Settings                       │
├─────────────────────────────────────────┤
│                                         │
│  📝 Page Title                          │
│  [About Our Company____________]        │
│                                         │
│  🔗 Page URL (Slug)                     │
│  yoursite.com/[about-our-company]       │
│  ℹ️  URL-friendly characters only       │
│                                         │
│  Current URL:                           │
│  📍 http://yoursite.com/about-our-co... │
│                                         │
├─────────────────────────────────────────┤
│                   [Cancel] [💾 Save]    │
└─────────────────────────────────────────┘
```

### Smart Features

#### Auto-Slug Generation
When you type a title, the URL slug is automatically generated:

```
Title: "My Awesome Page"
      ↓
Slug:  "my-awesome-page"
```

#### Real-Time Sanitization
As you type the URL, invalid characters are removed:

```
Input:  "My Page 123!!!"
        ↓
Output: "my-page-123"
```

#### Duplicate URL Prevention
If URL already exists, a unique number is added:

```
"about-us" exists
      ↓
Saves as: "about-us-2"
```

#### Live URL Preview
See exactly what your page URL will be:

```
Current URL:
📍 http://localhost:7000/my-new-page/
```

### How to Use

1. **Click** "Page Settings" button
2. **Edit** title and/or URL
3. **Review** the current URL preview
4. **Click** "Save Changes"
5. **See** success notification

### Keyboard Shortcuts

- **Tab** - Move between fields
- **Enter** - Save changes (when focused on input)
- **Esc** - Close modal

---

## 🔍 Feature 2: Widget Picker with Tabs & Search

### Overview
Enhanced widget selection modal with organized tabs and powerful search functionality.

### Modal Structure

```
┌─────────────────────────────────────────┐
│  Select a Widget                        │
│  [🔍 Search widgets...]                │
├─────────────────────────────────────────┤
│  [📱 Widgets] │ [📐 Templates]         │
├─────────────────────────────────────────┤
│                                         │
│  [Heading]  [Text]     [Button]        │
│  [Image]    [Video]    [Icon]          │
│  [Gallery]  [Tabs]     [Accordion]     │
│  ...                                    │
│                                         │
├─────────────────────────────────────────┤
│                              [Close]    │
└─────────────────────────────────────────┘
```

### Tab 1: Widgets

**Content:** All available widgets displayed in a grid

**Features:**
- 3-column responsive grid
- Hover effects (border highlight, shadow)
- Click to add widget
- Filtered by search

**Widget Card:**
```
┌──────────┐
│    📝    │  ← Icon (32px)
│ Heading  │  ← Title
└──────────┘
```

### Tab 2: Templates

**Content:** Template library (Coming soon)

**Placeholder:**
```
┌─────────────────────────┐
│         📐              │
│   Templates Coming Soon │
│                         │
│ Pre-made templates will │
│ be available here for   │
│ quick page building.    │
└─────────────────────────┘
```

### Search Functionality

#### How It Works

1. **Type** in search box
2. **Filters** widget grid in real-time
3. **Matches** both widget title and widget name
4. **Case-insensitive** matching

#### Examples

**Search: "text"**
- Shows: Text, Text Editor, Text Path, etc.
- Hides: Button, Image, Heading, etc.

**Search: "head"**
- Shows: Heading widget
- Hides: All others

**Search: ""** (empty)
- Shows: All widgets

#### Visual Feedback

```
Search: "button"

[Heading] ← Hidden
[Text]    ← Hidden
[Button]  ← Visible ✓
[Image]   ← Hidden
```

### Keyboard Shortcuts

- **Type** - Start searching immediately
- **↑↓** - Navigate results (future enhancement)
- **Enter** - Add selected widget (future)
- **Esc** - Close modal

### UX Features

#### 1. Auto-Focus
- Search input auto-focuses when modal opens
- Start typing immediately

#### 2. Smooth Animations
- Fade in/out for widgets
- Smooth tab transitions
- Hover effects

#### 3. Visual Highlights
- Active tab: Blue bottom border
- Focused input: Blue border
- Hovered widget: White background, shadow

#### 4. Accessibility
- Clear labels
- Focus indicators
- Keyboard navigation
- Screen reader friendly

---

## 🎨 Visual Design

### Colors

**Primary:** `#344047` (Dark blue-gray)
**Accent:** `#92003b` (Dark pink/red)
**Text:** `#27272a` (Almost black)
**Muted:** `#71717a` (Gray)
**Border:** `#e6e9ec` (Light gray)
**Background:** `#fafafa` (Off-white)

### Layout

**Modal Width:** 700px (widget picker), 600px (page settings)
**Max Height:** 85vh
**Border Radius:** 8px
**Shadow:** `0 20px 60px rgba(0, 0, 0, 0.3)`
**Backdrop:** Blur effect

---

## 🔧 Technical Implementation

### Page Settings Flow

```
User clicks "Page Settings"
         ↓
AJAX: probuilder_get_page_settings
         ↓
Returns: title, slug, permalink, site_url
         ↓
Display modal with current values
         ↓
User edits and clicks "Save Changes"
         ↓
AJAX: probuilder_save_page_settings
         ↓
Updates: wp_posts.post_title, wp_posts.post_name
         ↓
Returns: success + new permalink
         ↓
Updates header title
         ↓
Shows success toast
```

### Widget Search Algorithm

```javascript
searchTerm = input.toLowerCase().trim()

For each widget:
    widgetTitle = widget.title.toLowerCase()
    widgetName = widget.name.toLowerCase()
    
    if (widgetTitle.includes(searchTerm) || widgetName.includes(searchTerm)):
        SHOW widget
    else:
        HIDE widget
```

### Data Validation

#### Page Title
- Cannot be empty
- Sanitized with `sanitize_text_field()`

#### Page Slug
- Cannot be empty
- Lowercase only
- Only: a-z, 0-9, hyphens
- No spaces (converted to hyphens)
- Sanitized with `sanitize_title()`
- Checked for duplicates
- Made unique if needed

---

## 📋 AJAX Endpoints

### 1. Get Page Settings

**Action:** `probuilder_get_page_settings`

**Request:**
```javascript
{
    action: 'probuilder_get_page_settings',
    nonce: '[nonce]',
    post_id: 123
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "title": "About Us",
        "slug": "about-us",
        "permalink": "http://site.com/about-us/",
        "site_url": "http://site.com"
    }
}
```

### 2. Save Page Settings

**Action:** `probuilder_save_page_settings`

**Request:**
```javascript
{
    action: 'probuilder_save_page_settings',
    nonce: '[nonce]',
    post_id: 123,
    title: "New Title",
    slug: "new-slug"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "message": "Page settings updated successfully!",
        "title": "New Title",
        "slug": "new-slug",
        "permalink": "http://site.com/new-slug/"
    }
}
```

---

## 🎯 Use Cases

### Use Case 1: Rename Page
**Scenario:** You want to change "Page 1" to "About Us"

**Steps:**
1. Click "Page Settings"
2. Change title to "About Us"
3. Slug auto-updates to "about-us"
4. Click "Save Changes"
5. ✅ Page renamed, URL updated

### Use Case 2: Custom URL
**Scenario:** You want a specific URL like "team"

**Steps:**
1. Click "Page Settings"
2. Title: "Our Amazing Team"
3. Manually change slug to "team"
4. Click "Save Changes"
5. ✅ Page accessible at `/team/`

### Use Case 3: Find Specific Widget
**Scenario:** You need the Accordion widget

**Steps:**
1. Click "Add Element"
2. Type "accordion" in search
3. Only Accordion widget shows
4. Click it to add
5. ✅ Widget added

### Use Case 4: Browse Templates
**Scenario:** Want to use a pre-made template

**Steps:**
1. Click "Add Element"
2. Click "Templates" tab
3. (Future: Browse and select template)
4. ✅ Template added to page

---

## 🚀 Benefits

### Page Settings
- ✅ **Fast:** Edit title/URL without leaving editor
- ✅ **Smart:** Auto-generates SEO-friendly slugs
- ✅ **Safe:** Prevents duplicate URLs
- ✅ **Live:** See URL changes in real-time
- ✅ **Validated:** Ensures valid characters only

### Widget Search
- ✅ **Quick:** Find widgets in seconds
- ✅ **Intuitive:** Type-to-filter is familiar
- ✅ **Flexible:** Search by name or title
- ✅ **Organized:** Tabs separate widgets from templates
- ✅ **Responsive:** Works on all screen sizes

---

## 🎓 Best Practices

### Page Titles
1. **Be descriptive:** "Services" vs "What We Do"
2. **Use keywords:** Good for SEO
3. **Keep it concise:** 50-60 characters max
4. **Title case:** "About Us" not "about us"

### Page URLs
1. **Keep short:** "services" vs "our-services-page"
2. **Use hyphens:** "about-us" not "about_us"
3. **No numbers:** "services" vs "services-1"
4. **Lowercase:** "services" not "Services"
5. **Keywords:** Include relevant words

### Widget Search
1. **Type less:** "head" finds "Heading"
2. **Use keywords:** "text" finds multiple text widgets
3. **Clear search:** Delete text to see all widgets
4. **Explore:** Browse categories in Widgets tab

---

## 📱 Responsive Design

### Page Settings Modal
- **Desktop:** 600px wide
- **Tablet:** 90% width
- **Mobile:** 90% width, adjusted padding

### Widget Picker Modal
- **Desktop:** 3-column grid
- **Tablet:** 3-column grid
- **Mobile:** 2-column grid (automatic)

---

## 🔒 Security

### Nonce Verification
- All AJAX requests verified with nonce
- Action: `'probuilder-editor'`

### Capability Checks
- `edit_posts` required for all operations
- Cannot edit others' posts without permission

### Data Sanitization
- Title: `sanitize_text_field()`
- Slug: `sanitize_title()`
- Removes: special characters, scripts, HTML

### Unique Slugs
- Checks for existing slugs
- Auto-generates unique slug if conflict
- Uses WordPress core function: `wp_unique_post_slug()`

---

## 🧪 Testing Checklist

### Page Settings
- [ ] Click "Page Settings" button
- [ ] Modal opens with current title/slug
- [ ] Edit title - slug auto-updates
- [ ] Edit slug - real-time sanitization works
- [ ] URL preview updates
- [ ] Click "Save Changes"
- [ ] Success notification shows
- [ ] Title updates in header
- [ ] Can view page at new URL
- [ ] Click "Cancel" - modal closes without saving

### Widget Search
- [ ] Click "Add Element"
- [ ] Modal opens with search bar
- [ ] Search box auto-focused
- [ ] Type "button" - only button widgets show
- [ ] Clear search - all widgets show
- [ ] Click "Widgets" tab - widgets display
- [ ] Click "Templates" tab - placeholder shows
- [ ] Switch back to "Widgets" tab - widgets display
- [ ] Click widget - adds to canvas, modal closes
- [ ] Press Esc - modal closes

---

## 💻 Code Examples

### Open Page Settings Programmatically

```javascript
// From anywhere in the editor
ProBuilder.showPageSettings();
```

### Open Widget Picker with Search

```javascript
// Open picker
ProBuilder.showWidgetPicker(null);

// Open picker and insert at specific index
ProBuilder.showWidgetPicker(2);
```

### Get Current Page Settings

```javascript
$.ajax({
    url: ProBuilderEditor.ajaxurl,
    type: 'POST',
    data: {
        action: 'probuilder_get_page_settings',
        nonce: ProBuilderEditor.nonce,
        post_id: ProBuilderEditor.post_id
    },
    success: function(response) {
        console.log('Title:', response.data.title);
        console.log('Slug:', response.data.slug);
        console.log('URL:', response.data.permalink);
    }
});
```

---

## 🎨 Customization

### Change Modal Colors

Add to `editor.css`:

```css
/* Page settings modal accent color */
.probuilder-page-settings-save {
    background: #your-color !important;
}

/* Widget picker accent color */
.probuilder-picker-tab.active {
    border-bottom-color: #your-color !important;
}
```

### Change Search Placeholder

In `editor.js`:

```javascript
// Find this line and change text:
placeholder="Search widgets..."
```

### Change Modal Width

```javascript
// In showPageSettings():
width: 800px  // Change from 600px

// In showWidgetPicker():
max-width: 900px  // Change from 700px
```

---

## 📊 Statistics

### Page Settings Modal
- **Size:** ~200 lines of code
- **AJAX Calls:** 2 (get + save)
- **Inputs:** 2 (title + slug)
- **Validation:** Real-time
- **Security:** Nonce + capability checks

### Widget Picker
- **Size:** ~150 lines of code
- **Tabs:** 2 (Widgets + Templates)
- **Search:** Real-time filtering
- **Widgets Shown:** All registered widgets
- **Layout:** Responsive grid

---

## 🐛 Troubleshooting

### Issue: Page Settings Button Not Visible

**Solution:** Clear browser cache (Ctrl+Shift+R)

### Issue: Modal Doesn't Open

**Check:**
1. Browser console for JavaScript errors
2. Ensure jQuery is loaded
3. Check nonce is valid

**Fix:**
```javascript
// In browser console:
console.log(typeof ProBuilderEditor !== 'undefined');  // Should be true
console.log(ProBuilderEditor.nonce);  // Should show nonce value
```

### Issue: Search Doesn't Filter

**Check:**
1. Are widgets loaded?
2. Any console errors?

**Debug:**
```javascript
// In browser console after opening picker:
console.log($('.probuilder-picker-widget').length);  // Should show widget count
```

### Issue: Slug Saves with Unexpected Characters

**Cause:** `sanitize_title()` removes special characters

**Expected:**
- "My Café" → "my-cafe"
- "Service #1" → "service-1"
- "Hello World!" → "hello-world"

### Issue: URL Already Exists Error

**Solution:** System auto-appends number:
- "about" exists → saves as "about-2"
- "about-2" exists → saves as "about-3"

---

## 📦 Files Modified

### 1. `templates/editor.php`
- Added "Page Settings" button to header

### 2. `assets/js/editor.js`
- Added `showPageSettings()` function
- Added `renderPageSettingsModal()` function
- Enhanced `showWidgetPicker()` with tabs and search
- Added tab switching logic
- Added search filtering logic
- Added event handlers

### 3. `includes/class-ajax.php`
- Added `get_page_settings()` function
- Added `save_page_settings()` function
- Registered AJAX actions

---

## 🎯 Future Enhancements

### Page Settings
- [ ] Featured image selector
- [ ] Page template selector
- [ ] SEO meta description
- [ ] Custom CSS for page
- [ ] Page visibility (public/private)
- [ ] Parent page selector

### Widget Picker
- [ ] Widget categories as tabs
- [ ] Recently used widgets section
- [ ] Favorite/pin widgets
- [ ] Widget preview on hover
- [ ] Keyboard navigation (arrow keys)
- [ ] Drag widgets from picker to canvas

### Templates
- [ ] Pre-made page templates
- [ ] Template categories
- [ ] Template preview
- [ ] Template search
- [ ] Import/export templates
- [ ] Community templates

---

## ✅ Summary

### What's New

**Page Settings:**
- 🎯 Edit title and URL from editor
- 🎯 Auto-slug generation
- 🎯 Real-time URL preview
- 🎯 Duplicate prevention

**Widget Picker:**
- 🎯 Two tabs (Widgets & Templates)
- 🎯 Search functionality
- 🎯 Real-time filtering
- 🎯 Keyboard shortcuts

### Benefits

**For Users:**
- ⚡ Faster workflow (no context switching)
- 🎨 Better organization (tabs)
- 🔍 Quick widget discovery (search)
- ✨ Professional UX (smooth animations)

**For Developers:**
- 🔧 Clean code structure
- 🛡️ Proper security
- 📝 Well-documented
- 🧪 Easy to extend

---

## 🚀 Deployment

**Status:** ✅ Complete and Ready

**Action Required:**
1. Clear browser cache
2. Test page settings
3. Test widget search
4. Enjoy! 🎉

---

## Version

**Feature Version:** 1.0.0  
**Date:** October 26, 2025  
**Status:** ✅ ACTIVE  
**Stability:** STABLE  

---

## 📖 Quick Reference

### Open Page Settings
```
Button: Top header → "Page Settings"
Shortcut: (Future) Ctrl+Shift+P
```

### Search Widgets
```
Button: "Add Element" or (+) buttons
Focus: Auto-focuses on search
Type: Start typing to filter
Clear: Delete text to show all
```

### Tab Switch
```
Widgets: Click "Widgets" tab
Templates: Click "Templates" tab
```

---

**You can now manage page settings and find widgets easily!** 🎊

