# 📋 How to Select Menu in Header

## ✅ You're on the WRONG page!

**This page shows the LIST of headers:**
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
```

**You CAN'T select a menu here!** This is just the list view.

---

## 🎯 Correct Workflow

### Step 1: Pick a Header to Edit
From the headers list page, **click "Edit with ProBuilder"** on any header

### Step 2: ProBuilder Editor Opens
Now you're in the editor: `?p=803&probuilder=true&post_type=pb_header`

### Step 3: Click on the WP Header Widget
- If the header already has a WP Header widget, **click on it** in the canvas
- If not, add one from the widgets panel (left side)

### Step 4: Settings Panel Opens (Right Side)
When you click the WP Header widget, the **settings panel appears on the right**

### Step 5: Select Menu
In the settings panel:
- Find **"Select Menu"** dropdown
- Click it
- Choose your WordPress menu (Primary, Footer, etc.)
- The menu will appear in the header!

### Step 6: Save
- Click **Save** button (top right)
- Your menu is now in the header!

---

## 🖼️ Visual Guide

```
┌─────────────────────────────────────────────────────────────┐
│  Headers List Page (edit.php?post_type=pb_header)          │
│  ┌───────────────────────────────────────────────┐         │
│  │ Title              Status    Shortcode         │         │
│  │ Classic Header     Active    [header id="123"] │         │
│  │ Dark Header        Inactive  [header id="456"] │         │
│  └───────────────────────────────────────────────┘         │
│                    ↓ Click "Edit with ProBuilder"           │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│  ProBuilder Editor (?p=803&probuilder=true)                │
│  ┌──────────┐  ┌─────────────────┐  ┌──────────────────┐  │
│  │ Widgets  │  │  Canvas/Preview │  │  Settings Panel  │  │
│  │ Panel    │  │                 │  │                  │  │
│  │          │  │  [WP Header]←Click│  │ ┌────────────┐ │  │
│  │ Search:  │  │  ┌──────────────┐│  │ │Content Tab │ │  │
│  │ WP Header│  │  │Logo | Menu   ││  │ │            │ │  │
│  │          │  │  └──────────────┘│  │ │Select Menu:│ │  │
│  │          │  │                 │  │ │[Primary ▼] │ │  │
│  │          │  │                 │  │ └────────────┘ │  │
│  └──────────┘  └─────────────────┘  └──────────────────┘  │
│                                           ↑                 │
│                              SELECT MENU HERE!              │
└─────────────────────────────────────────────────────────────┘
```

---

## 📍 Step-by-Step Instructions

### 1. Create or Edit a Header

**Option A: Use Prebuilt Header**
```
Run: http://192.168.10.203:7000/create-beautiful-headers.php
Then go to: ProBuilder → Headers
```

**Option B: Edit Existing Header 803**
```
http://192.168.10.203:7000/?p=803&probuilder=true&post_type=pb_header
```

### 2. In ProBuilder Editor

**If header is empty:**
1. Look at left panel (Widgets)
2. Search for "WP Header" or scroll to find it
3. Click or drag it to the canvas

**If header has WP Header widget already:**
1. Click on the header widget in the canvas
2. Settings panel opens on the right

### 3. Configure Menu

In the **right settings panel**:

```
┌──────────────────────┐
│ Content Tab          │
├──────────────────────┤
│ Select Menu:         │
│ [Choose menu... ▼]   │ ← Click here!
│                      │
│ Options:             │
│ - Primary            │
│ - Footer Menu        │
│ - Social Menu        │
└──────────────────────┘
```

Select your menu from dropdown!

### 4. Customize (Optional)

**Style Tab** (right panel):
- Background Color: Click color picker
- Menu Text Color: Choose color
- Menu Hover Color: Choose hover color
- Sticky Header: Toggle on/off
- Box Shadow: Toggle on/off

### 5. Save & Activate

1. Click **Save** button (top right)
2. Find **"Site-Wide Activation"** box (right sidebar, scroll down)
3. Check ✅ **"Set as Active Header"**
4. Click **Update/Publish** button

### 6. View Your Site!

Visit any page - your header with menu appears automatically!

---

## 🛠️ Create WordPress Menu First

If you don't have a menu yet:

### 1. Create Menu in WordPress

```
1. Go to: Appearance → Menus
2. Click: "Create a new menu"
3. Name: "Primary Menu"
4. Add pages: Home, Shop, About, Contact
5. Save Menu
```

### 2. Now Use it in Header

```
1. Edit header in ProBuilder
2. Click WP Header widget
3. Select Menu → Choose "Primary Menu"
4. Save
```

---

## 💡 Common Mistakes

### ❌ WRONG: Trying to select menu from headers list
```
You're at: edit.php?post_type=pb_header
You see: List of all headers
You try: To find menu selector
Result: ❌ Can't find it!
```

### ✅ CORRECT: Select menu in ProBuilder editor
```
You're at: ?p=803&probuilder=true&post_type=pb_header
You see: ProBuilder editor
You click: WP Header widget in canvas
Settings panel opens on right
You select: Menu from dropdown
Result: ✅ Menu added to header!
```

---

## 🎯 Quick Solution

### Can't Find Menu Selector?

**You need to be IN the ProBuilder editor!**

1. **Click "Edit with ProBuilder"** on a header from the list
2. **Wait for editor to load**
3. **Click the WP Header widget** in the canvas
4. **Right panel shows settings** with menu dropdown
5. **Select your menu** there!

---

## 📹 Visual Steps

```
Headers List Page
    ↓ Click "Edit with ProBuilder"
    
ProBuilder Editor Opens
    ↓ Click WP Header widget in canvas
    
Settings Panel Opens (Right Side)
    ↓ Find "Select Menu" dropdown
    
Click Dropdown
    ↓ Choose your WordPress menu
    
Menu Appears in Header
    ↓ Click Save
    
Done!
```

---

## 🚀 Try This Right Now:

### 1. Open Editor for Header 803:
```
http://192.168.10.203:7000/?p=803&probuilder=true&post_type=pb_header
```

### 2. Look at Canvas (Middle)
- See the WP Header widget? Click it!
- Don't see it? Add it from widgets panel (left)

### 3. Look at Right Panel
- Settings panel appears
- Find "Select Menu" dropdown
- Choose your menu!

### 4. Save
- Click Save button
- Check ✅ "Set as Active Header"
- Update

---

## ✅ Summary

**The menu selector is NOT on the list page!**

It's in the **ProBuilder editor → Right settings panel** → **Content tab** → **"Select Menu" dropdown**

**Correct flow:**
```
Headers List → Edit with ProBuilder → Click Widget → Settings Panel → Select Menu
```

---

**Open the editor and you'll find the menu selector!** 📋✨

