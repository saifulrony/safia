# 🔧 ProBuilder Troubleshooting - "No Widgets Showing"

## Issue: Only Seeing Text Editor, No Widgets

If you see the ProBuilder editor but **NO WIDGETS** in the left sidebar, follow these steps:

---

## ✅ IMMEDIATE FIX

### Step 1: Open Browser Console
```
1. Press F12 (or Right-click → Inspect)
2. Click "Console" tab
3. Look for messages starting with "ProBuilder"
```

### Step 2: Check for These Messages
You should see:
```
✅ "ProBuilder Editor JavaScript loaded successfully!"
✅ "ProBuilder initializing..."
✅ "Widgets loaded: 20"
✅ "ProBuilder initialized successfully!"
```

If you see:
```
❌ "ProBuilderEditor is not defined"
❌ "No widgets found"
❌ JavaScript errors in RED
```
→ Continue to fixes below

---

## 🔍 DIAGNOSTIC STEPS

### Check 1: Is the Editor Actually Loading?

Look at the page. Do you see:
- [ ] White header at top
- [ ] "ProBuilder" logo
- [ ] Left sidebar (even if empty)
- [ ] Gray canvas area
- [ ] SAVE button (pink/magenta)

**If YES** → Scripts are loading but widgets aren't rendering
**If NO** → Editor itself isn't loading (wrong URL?)

### Check 2: Open Console and Run
```javascript
// Paste this in Console and press Enter
console.log('ProBuilderEditor:', typeof ProBuilderEditor);
console.log('Widgets:', ProBuilderEditor?.widgets?.length);
```

Expected output:
```
ProBuilderEditor: object
Widgets: 20
```

If you see `undefined` → Scripts not loaded!

### Check 3: Check Network Tab
```
1. Open F12 → Network tab
2. Refresh page (Ctrl+R)
3. Look for: editor.js
4. Status should be: 200 (green)
5. Size should be: ~33KB
```

If 404 or missing → File path issue!

---

## 🛠️ FIXES

### Fix 1: Hard Refresh (Most Common!)
```
Windows/Linux: Ctrl + Shift + R
Mac: Cmd + Shift + R

Do this 2-3 times!
```

### Fix 2: Clear ALL Browser Cache
```
Chrome:
1. Settings → Privacy and security
2. Clear browsing data
3. Select: Cached images and files
4. Time range: All time
5. Click "Clear data"

Firefox:
1. Options → Privacy & Security
2. Cookies and Site Data
3. Clear Data
4. Check "Cached Web Content"
5. Clear
```

### Fix 3: Try Incognito/Private Window
```
Chrome: Ctrl + Shift + N
Firefox: Ctrl + Shift + P
Edge: Ctrl + Shift + P

Then go to:
http://localhost:7000/?p=2&probuilder=true
```

### Fix 4: Deactivate & Reactivate Plugin
```
1. Go to: Plugins page
2. Deactivate ProBuilder
3. Wait 5 seconds
4. Activate ProBuilder
5. Hard refresh browser
6. Try again
```

### Fix 5: Check File Permissions
```bash
# Run in terminal
cd /home/saiful/wordpress/wp-content/plugins/probuilder
chmod -R 755 .
```

### Fix 6: Verify Files Are Complete
```bash
# Check file sizes
ls -lh assets/js/editor.js
# Should show ~33K

ls -lh assets/css/editor.css
# Should show ~18K

# Check file content
head -20 assets/js/editor.js
# Should start with: "ProBuilder Editor JavaScript"
```

### Fix 7: Disable Caching Plugins
If you have caching plugins:
```
WP Super Cache → Delete Cache
W3 Total Cache → Empty All Caches
WP Rocket → Clear Cache
```

### Fix 8: Increase PHP Memory
Add to `wp-config.php`:
```php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```

### Fix 9: Check jQuery UI
In browser console:
```javascript
console.log('jQuery UI:', typeof jQuery.ui);
```

Should show: `object`
If `undefined` → jQuery UI not loaded!

### Fix 10: Manual Script Loading Test
Create file: `wp-content/plugins/probuilder/test-scripts.php`
```php
<?php
/**
 * Template Name: Test ProBuilder Scripts
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test ProBuilder Scripts</title>
    <?php wp_head(); ?>
</head>
<body>
    <h1>ProBuilder Script Test</h1>
    <p>Check console for errors...</p>
    
    <script>
    jQuery(document).ready(function($) {
        console.log('Test page loaded');
        console.log('jQuery:', typeof $);
        console.log('jQuery UI:', typeof $.ui);
        console.log('ProBuilderEditor:', typeof ProBuilderEditor);
        if (typeof ProBuilderEditor !== 'undefined') {
            console.log('Widgets:', ProBuilderEditor.widgets.length);
        }
    });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>
```

Access: `http://localhost:7000/?probuilder=true&test=1`

---

## 🎯 EXPECTED INTERFACE

When working correctly, you should see:

### LEFT SIDEBAR (320px wide):
```
┌─────────────────────────┐
│ Search: [🔍_____]        │
│                         │
│ WIDGETS | TEMPLATES     │
│ ─────────────────────── │
│                         │
│ LAYOUT ▼                │
│ ┌────────┬────────┐     │
│ │  Icon  │  Icon  │     │
│ │Container│       │     │
│ └────────┴────────┘     │
│                         │
│ BASIC ▼                 │
│ ┌────────┬────────┐     │
│ │  Icon  │  Icon  │     │
│ │Heading │  Text  │     │
│ └────────┴────────┘     │
│ ┌────────┬────────┐     │
│ │  Icon  │  Icon  │     │
│ │ Button │ Image  │     │
│ └────────┴────────┘     │
│                         │
│ ADVANCED ▼              │
│ ┌────────┬────────┐     │
│ │  Icon  │  Icon  │     │
│ │  Tabs  │Accordion│    │
│ └────────┴────────┘     │
│                         │
│ CONTENT ▼               │
│ ┌────────┬────────┐     │
│ │  Icon  │  Icon  │     │
│ │IconBox │ImageBox│     │
│ └────────┴────────┘     │
│  ...more widgets...     │
└─────────────────────────┘
```

---

## 🆘 EMERGENCY RESET

If nothing works, reinstall plugin:

### Method 1: Via Admin
```
1. Plugins → Deactivate ProBuilder
2. Delete ProBuilder
3. Re-upload plugin folder
4. Activate
```

### Method 2: Via Terminal
```bash
cd /home/saiful/wordpress/wp-content/plugins
rm -rf probuilder
# Re-upload or re-create plugin
```

---

## 📊 DEBUG CHECKLIST

Run through this checklist:

```
□ Hard refresh browser (Ctrl+Shift+R)
□ Tried incognito/private window
□ Cleared browser cache completely
□ Checked browser console for errors
□ Verified ProBuilderEditor object exists
□ Checked widgets array has 20 items
□ Verified editor.js file is 33KB
□ Verified editor.css file is 18KB
□ Deactivated and reactivated plugin
□ Checked file permissions (755)
□ Disabled caching plugins
□ Tried different browser
□ Checked jQuery and jQuery UI load
□ Verified URL has ?probuilder=true
```

---

## 🎯 WHAT TO SEND FOR HELP

If still not working, send:

1. **Screenshot of full browser window**
2. **Browser console screenshot** (F12 → Console tab)
3. **Network tab screenshot** (showing editor.js)
4. **Output of this command:**
   ```bash
   ls -lh /home/saiful/wordpress/wp-content/plugins/probuilder/assets/js/editor.js
   head -5 /home/saiful/wordpress/wp-content/plugins/probuilder/assets/js/editor.js
   ```

---

## ✅ SUCCESS INDICATORS

You'll know it's working when you see:

1. **Console shows:**
   - "ProBuilder initialized successfully!"
   - "Widgets loaded: 20"
   - No RED errors

2. **Left sidebar shows:**
   - 4 categories (Layout, Basic, Advanced, Content)
   - Widget cards in 2-column grid
   - Icons for each widget
   - Hover effects work

3. **You can:**
   - Drag widgets to canvas
   - See widget preview
   - Click Edit to open settings
   - Switch device modes
   - Save page

---

**Most common issue:** Browser cache! Clear it completely!

