# 📍 WHERE TO SELECT MENU - SIMPLE GUIDE

## ❌ You're Looking in the WRONG Place!

### This is the Headers LIST page (where you are):
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
```

**What you can do here:**
- ✅ See all headers
- ✅ Delete headers  
- ✅ Click "Edit with ProBuilder"

**What you CANNOT do here:**
- ❌ Select menus
- ❌ Edit header content
- ❌ Customize colors

**This is just a LIST!** You need to EDIT a specific header!

---

## ✅ Where to Select Menu (CORRECT Place)

### This is the ProBuilder EDITOR:
```
http://192.168.10.203:7000/?p=803&probuilder=true&post_type=pb_header
```

**What you can do here:**
- ✅ Add widgets
- ✅ Select menus ← YES!
- ✅ Customize everything
- ✅ Edit header content

**This is the EDITOR!** Here you can select menus!

---

## 🎯 Step-by-Step (Follow Exactly)

### Step 1: From Headers List → Open Editor

You're here:
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
```

**Click "Edit with ProBuilder"** on header 803

### Step 2: Editor Opens

Now you're here:
```
http://192.168.10.203:7000/?p=803&probuilder=true&post_type=pb_header
```

You see:
- **Left Panel**: Widgets
- **Middle**: Canvas (preview)
- **Right Panel**: Settings (appears when you click a widget)

### Step 3: Add Navigation Menu Widget

In **LEFT panel**:
1. Search: Type "**Navigation Menu**" or "**Menu**"
2. Find the widget (icon: list/bullets)
3. **Drag to canvas** or **click** to add

### Step 4: Click the Widget in Canvas

After adding, **click on the Navigation Menu widget** in the middle canvas area

### Step 5: Settings Panel Appears (RIGHT)

The **right panel** shows settings:

```
┌─────────────────────┐
│ Content Tab         │
├─────────────────────┤
│                     │
│ Select Menu:        │
│ [Choose...    ▼]    │ ← CLICK HERE!
│                     │
│ Layout:             │
│ [Horizontal   ▼]    │
│                     │
│ Alignment:          │
│ [Right        ▼]    │
│                     │
└─────────────────────┘
```

**Click the "Select Menu" dropdown!**

### Step 6: Choose Your Menu

Dropdown shows:
```
Select Menu
────────────
Primary Menu     ← Click this
Footer Menu
Social Menu
```

Choose your menu!

### Step 7: Save

- Click **Save** button (top right corner)
- Done! Menu appears in header!

---

## 🖼️ Visual Flow

```
┌────────────────────────────────────────────────┐
│  LIST PAGE (edit.php?post_type=pb_header)     │
│  ┌──────────────────────────────────────┐     │
│  │ Headers List                         │     │
│  │ ─────────────────────────            │     │
│  │ Classic Header    [Edit with PB] ←Click│   │
│  │ Dark Header       [Edit with PB]     │     │
│  └──────────────────────────────────────┘     │
└────────────────────────────────────────────────┘
                     ↓
                  Clicking
                     ↓
┌────────────────────────────────────────────────┐
│  EDITOR (p=803&probuilder=true)                │
│  ┌────────┐  ┌──────────┐  ┌──────────────┐  │
│  │Widgets │  │  Canvas  │  │   Settings   │  │
│  │        │  │          │  │              │  │
│  │Search: │  │          │  │ Select Menu: │  │
│  │Menu    │  │  Widget  │  │ [Primary ▼]  │  │
│  │        │  │    ↑     │  │      ↑       │  │
│  │[Nav    │  │  Click   │  │  SELECT HERE!│  │
│  │ Menu]  │  │   it!    │  │              │  │
│  │        │  │          │  │              │  │
│  └────────┘  └──────────┘  └──────────────┘  │
│   Add this      Then click    Then select    │
└────────────────────────────────────────────────┘
```

---

## 💡 Common Confusion

### "I can't find where to select menu!"

**Problem**: You're on the list page, not in the editor

**Solution**:
1. ❌ NOT here: `edit.php?post_type=pb_header` (list)
2. ✅ HERE: `?p=803&probuilder=true&post_type=pb_header` (editor)
3. Click widget → Settings appear → Select menu

---

## 🎨 Widget Settings Reference

### Content Settings (What to Show):

| Setting | Options | Default | Use |
|---------|---------|---------|-----|
| Select Menu | Your WP menus | None | Choose which menu |
| Layout | Horizontal / Vertical | Horizontal | Row or column |
| Alignment | Left / Center / Right | Left | Position items |
| Spacing | 5-100px | 20px | Gap between items |

### Style Settings (How It Looks):

| Setting | Range | Default | Use |
|---------|-------|---------|-----|
| Text Color | Any color | #333 | Link color |
| Hover Color | Any color | #667eea | Hover effect |
| Active Color | Any color | #667eea | Current page |
| Font Size | 12-32px | 16px | Text size |
| Font Weight | 300-700 | 500 | Text thickness |

---

## ✅ Complete Example

### Creating a Professional Header Menu:

```
1. WordPress → Appearance → Menus
   Create menu: "Main Navigation"
   Add: Home, Shop, About, Blog, Contact
   Save

2. ProBuilder → Headers → Add New
   Title: "Main Header"

3. Edit with ProBuilder

4. Add Container widget (2 columns)

5. Column 1: Add Heading
   - Text: "YOUR BRAND"
   - Size: 28px

6. Column 2: Add Navigation Menu widget ← THIS!
   Click widget → Settings (right):
   - Select Menu: Main Navigation
   - Layout: Horizontal
   - Alignment: Right
   - Text Color: #6b7280
   - Hover Color: #667eea
   - Font Size: 16px

7. Save

8. Check ✅ "Set as Active Header"

9. Update

10. Visit site → Professional header with menu! 🎉
```

---

## 🚀 Quick Links

### Create WordPress Menu:
```
http://192.168.10.203:7000/wp-admin/nav-menus.php
```

### Edit Header 803 in ProBuilder:
```
http://192.168.10.203:7000/?p=803&probuilder=true&post_type=pb_header
```

### View All Headers (List):
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
```

---

## 📋 Checklist

Before you can select a menu, make sure:

- [x] You created a menu in Appearance → Menus
- [x] The menu has items (pages, links, etc.)
- [x] You saved the menu
- [x] You're in ProBuilder EDITOR (not list page)
- [x] You added Navigation Menu widget to canvas
- [x] You clicked on the widget
- [x] Settings panel is showing on right
- [x] You're looking at "Content" tab

**If all checked, you'll see "Select Menu" dropdown!**

---

## 🎯 Final Answer

**The Navigation Menu widget EXISTS and is ENHANCED!**

**You can select custom menus!**

**Just:**
1. ✅ Open header in ProBuilder EDITOR
2. ✅ Add "Navigation Menu" widget
3. ✅ Click it
4. ✅ Right panel shows "Select Menu" dropdown
5. ✅ Choose your WordPress menu
6. ✅ Save!

**No separate builder needed - the widget is there!** 📋✨

---

**Widget Location**: ProBuilder Editor → Left Panel → Search "Navigation Menu" or "Menu"

