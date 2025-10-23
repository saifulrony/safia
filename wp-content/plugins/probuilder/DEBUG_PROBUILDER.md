# ProBuilder Debug Guide

## Issue: "Can't Get Option to Edit"

If you clicked "Edit with ProBuilder" but nothing happens, follow these steps:

### Step 1: Verify Plugin is Activated
```
WordPress Admin → Plugins → Find "ProBuilder - Elementor-Style Page Builder"
Status should be: ACTIVE (in blue)
If not: Click "Activate"
```

### Step 2: Check Browser Console for Errors
```
1. Right-click on page → "Inspect" or press F12
2. Go to "Console" tab
3. Look for any RED errors
4. Take a screenshot if you see errors
```

### Step 3: Hard Refresh Browser
```
Windows/Linux: Ctrl + Shift + R
Mac: Cmd + Shift + R

This clears the cached CSS/JS files
```

### Step 4: Test Edit Link Manually
```
1. Go to: Pages → All Pages
2. Hover over any page
3. You should see "Edit with ProBuilder" link
4. Click it

OR manually go to:
http://localhost:7000/wp-admin/post.php?post=YOUR_PAGE_ID&action=edit&probuilder=true

Replace YOUR_PAGE_ID with actual page ID (like 2, 5, etc.)
```

### Step 5: Check PHP Errors
```
Enable WordPress Debug Mode:

1. Open: wp-config.php
2. Find: define('WP_DEBUG', false);
3. Change to: 
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
4. Save file
5. Try again
6. Check: wp-content/debug.log for errors
```

### Step 6: Verify File Permissions
```bash
# Run in terminal:
cd /home/saiful/wordpress/wp-content/plugins/probuilder
ls -la

# All files should be readable
# If not, fix permissions:
sudo chmod -R 755 /home/saiful/wordpress/wp-content/plugins/probuilder
```

### Step 7: Test Direct Access
```
Try accessing editor directly:

1. Create a test page if you don't have one:
   Pages → Add New → Title: "Test Page" → Publish

2. Note the Page ID (in URL after saving)

3. Go to:
   http://localhost:7000/?p=PAGE_ID&probuilder=true
   
   Example:
   http://localhost:7000/?p=2&probuilder=true
```

### Step 8: Check if jQuery UI is Loading
```
1. Open: http://localhost:7000/?p=2&probuilder=true
2. Open Browser Console (F12)
3. Type: jQuery.ui
4. Press Enter
5. Should show: Object {version: "1.13.2", ...}
6. If shows "undefined" → jQuery UI not loaded
```

### Step 9: Disable Other Plugins
```
Sometimes other plugins conflict:

1. Go to: Plugins
2. Deactivate all plugins EXCEPT ProBuilder
3. Try "Edit with ProBuilder" again
4. If it works, reactivate plugins one by one to find conflict
```

### Step 10: Clear WordPress Cache
```
If using cache plugin:
1. WP Super Cache: Settings → Delete Cache
2. W3 Total Cache: Performance → Empty All Caches
3. WP Rocket: Clear Cache button

If no cache plugin:
- Just do hard refresh (Ctrl+Shift+R)
```

## Common Issues & Solutions

### Issue: "Edit with ProBuilder link doesn't show"
**Solution:**
```
1. Go to: ProBuilder → Settings
2. Make sure "Pages" is checked
3. Click "Save Settings"
4. Refresh page list
```

### Issue: "Blank screen after clicking Edit"
**Solution:**
```
1. Check browser console for JavaScript errors
2. Check PHP error log (wp-content/debug.log)
3. Make sure all plugin files are uploaded
4. Re-upload plugin if needed
```

### Issue: "Old Gutenberg-like interface shows"
**Solution:**
```
1. Hard refresh: Ctrl + Shift + R
2. Clear browser cache completely
3. Try incognito/private window
4. Check file modification dates:
   ls -la wp-content/plugins/probuilder/assets/css/
   editor.css should be recent
```

### Issue: "Widgets not showing in sidebar"
**Solution:**
```
Check browser console for:
- "ProBuilderEditor is not defined"
- "widgets is undefined"

If you see these:
1. Clear cache
2. Hard refresh
3. Check if editor.js is loading:
   - Open Console → Network tab
   - Reload page
   - Look for editor.js
   - Should be 200 OK
```

## Manual Debug Commands

### Check Plugin Files Exist
```bash
cd /home/saiful/wordpress/wp-content/plugins/probuilder
ls -la assets/css/editor.css
ls -la assets/js/editor.js
ls -la templates/editor.php

# All should exist and be recent
```

### Check File Sizes
```bash
# CSS should be large (the new Elementor-style one)
ls -lh assets/css/editor.css
# Should be around 20-25KB

# JS should be comprehensive
ls -lh assets/js/editor.js
# Should be around 15-20KB
```

### Verify Plugin Active in Database
```bash
# Connect to MySQL
mysql -u root -p

# Use your database
use wordpress_db_name;

# Check active plugins
SELECT option_value FROM wp_options WHERE option_name = 'active_plugins';

# Should include: probuilder/probuilder.php
```

## Test URLs

Try these URLs directly:

```
# Main site
http://localhost:7000/

# Admin
http://localhost:7000/wp-admin/

# Pages list
http://localhost:7000/wp-admin/edit.php?post_type=page

# Edit page 2 with ProBuilder
http://localhost:7000/wp-admin/post.php?post=2&action=edit&probuilder=true

# Or frontend editor
http://localhost:7000/?p=2&probuilder=true
```

## What Should Happen

When you click "Edit with ProBuilder":

1. URL should change to include `?probuilder=true`
2. Page should reload
3. You should see:
   - White header with ProBuilder logo
   - Left sidebar with widgets (320px wide)
   - Center canvas area (gray background)
   - No WordPress admin menus
   - Pink accent colors
   - Device buttons (Desktop/Tablet/Mobile)

## Still Not Working?

### Get More Info:
```bash
# Check WordPress version
wp core version

# Check PHP version
php -v

# Check if mod_rewrite is enabled
apache2ctl -M | grep rewrite

# Check .htaccess exists
ls -la /home/saiful/wordpress/.htaccess
```

### Create Debug Page

Create file: `wp-content/plugins/probuilder/test-debug.php`
```php
<?php
// Debug ProBuilder
echo "ProBuilder Debug Info:\n\n";
echo "Plugin Path: " . PROBUILDER_PATH . "\n";
echo "Plugin URL: " . PROBUILDER_URL . "\n";
echo "Version: " . PROBUILDER_VERSION . "\n";
echo "Editor Class Exists: " . (class_exists('ProBuilder_Editor') ? 'YES' : 'NO') . "\n";
echo "Editor Active: " . (isset($_GET['probuilder']) ? 'YES' : 'NO') . "\n";
echo "Post ID: " . get_the_ID() . "\n";
```

Access: `http://localhost:7000/?p=2&probuilder=true` and check output.

## Contact Info

If still having issues:
1. Check browser console screenshot
2. Check PHP error log: `wp-content/debug.log`
3. Note exact error messages
4. Note WordPress version
5. Note PHP version

---

**Most Common Fix:** Hard refresh browser with Ctrl+Shift+R!

