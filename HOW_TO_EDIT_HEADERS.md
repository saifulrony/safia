# 📌 How to Edit Headers/Footers/Sliders

## ✅ Your Headers ARE Saved Correctly!

They're saved as `pb_header` post type (not pages). The 404 is expected when you use the wrong URL format.

---

## 🔧 How to Edit Header 812

### ✅ Correct URL Format:
```
http://192.168.10.203:7000/?p=812&probuilder=true&post_type=pb_header
```

### ❌ Wrong URL (404):
```
http://192.168.10.203:7000/?post_type=pb_header&p=812
```

**The order and parameters matter!**

---

## 🚀 Easiest Way to Edit Headers

### Method 1: From Headers List (RECOMMENDED)

1. Go to: http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
2. See all your headers in the list
3. Hover over any header
4. Click **"Edit with ProBuilder"** link
5. Done! Editor opens automatically

### Method 2: Quick Editor Tool

Use this helper:
```
http://192.168.10.203:7000/edit-header.php?type=pb_header&id=812
```

This auto-redirects to the correct ProBuilder editor URL!

### Method 3: WordPress Admin Edit

1. Go to headers list
2. Click "Edit" (not "Edit with ProBuilder")
3. In WordPress editor, click **"Edit with ProBuilder"** button
4. Editor opens

---

## 📋 All Your Headers

Visit this to see all headers:
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
```

You'll see:
- List of all headers
- Status column (Active/Inactive)
- Shortcode for each
- **"Edit with ProBuilder"** link ← Use this!

---

## ✅ How Activation Works

### Create & Activate:

1. **Create Header**
   ```
   ProBuilder → Headers → Add New
   Edit with ProBuilder
   Build your navigation
   Save
   ```

2. **Activate Site-Wide**
   ```
   Check ✅ "Set as Active Header" (right sidebar)
   Click Update
   ```

3. **Header Appears Everywhere!**
   ```
   Visit any page on your site
   Your header shows automatically
   No shortcode needed!
   ```

---

## 🎯 Quick Access URLs

### Edit Header 812:
```
http://192.168.10.203:7000/?p=812&probuilder=true&post_type=pb_header
```

### View All Headers:
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
```

### Create New Header:
```
http://192.168.10.203:7000/wp-admin/post-new.php?post_type=pb_header
```

### ProBuilder Dashboard:
```
http://192.168.10.203:7000/wp-admin/admin.php?page=probuilder-parts
```

### Quick Editor (Any ID):
```
http://192.168.10.203:7000/edit-header.php?type=pb_header&id=YOUR_ID
```

---

## 💡 Understanding the 404

### Why You Got 404:

Headers are **elements**, not public pages. They can't be accessed like:
```
❌ http://192.168.10.203:7000/?post_type=pb_header&p=812
❌ http://192.168.10.203:7000/header-name/
```

**This is intentional!** Headers shouldn't be viewable as standalone pages.

### How to Use Headers:

**Option 1: Activate Site-Wide (Recommended)**
- Check ✅ "Set as Active Header"
- Shows automatically on all pages
- Like Elementor Pro!

**Option 2: Use Shortcode**
- `[header id="812"]` in page content
- For specific pages only

---

## 🎊 Summary

**Your headers ARE working correctly!**

✅ Saved as `pb_header` (not pages)
✅ Edit via ProBuilder editor  
✅ Activate to show site-wide
✅ Same builder for everything
✅ Professional implementation

**Just use the correct edit URL or click "Edit with ProBuilder" from the headers list!**

---

## 📍 Next Steps

1. **View all headers**: http://192.168.10.203:7000/wp-admin/edit.php?post_type=pb_header
2. **Click "Edit with ProBuilder"** on any header
3. **Check ✅ "Set as Active Header"** to make it site-wide
4. **Visit your site** - header appears automatically!

🎉 **No separate builder needed - it works perfectly!**

