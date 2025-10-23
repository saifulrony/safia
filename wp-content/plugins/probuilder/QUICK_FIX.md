# 🔧 ProBuilder Quick Fix Guide

## Your Issue: Can't Edit with ProBuilder

### ✅ Quick Solution (Try This First!)

**Step 1: Deactivate & Reactivate Plugin**
```
1. Go to: http://localhost:7000/wp-admin/plugins.php
2. Find "ProBuilder - Elementor-Style Page Builder"
3. Click "Deactivate"
4. Wait 2 seconds
5. Click "Activate"
```

**Step 2: Hard Refresh Browser**
```
Press: Ctrl + Shift + R (or Cmd + Shift + R on Mac)
```

**Step 3: Try Edit Link Again**
```
1. Go to: Pages → All Pages
2. Hover over any page
3. Click "Edit with ProBuilder"
```

### If That Doesn't Work...

**Try Direct URL Access:**
```
1. Go to Pages → All Pages
2. Note a page ID (number in URL when you hover)
3. Open this URL:

http://localhost:7000/?p=PAGE_ID&probuilder=true

Example:
http://localhost:7000/?p=2&probuilder=true
http://localhost:7000/?p=5&probuilder=true
```

### Check if Plugin is Active

Run in browser:
```
http://localhost:7000/wp-admin/plugins.php
```

Look for: **"ProBuilder - Elementor-Style Page Builder"**
Status should be: **Active** (in blue)

### Test Command (Run in Terminal)

```bash
# Check plugin is recognized
cd /home/saiful/wordpress
wp plugin list | grep probuilder
```

Should show:
```
probuilder    active    1.2.0
```

### Enable Debug Mode

Edit `wp-config.php`:
```bash
cd /home/saiful/wordpress
nano wp-config.php
```

Add these lines BEFORE `/* That's all, stop editing! */`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
```

Save and check `wp-content/debug.log` for errors.

### Manual Activation

If auto-activation doesn't work:
```bash
cd /home/saiful/wordpress
wp plugin activate probuilder
```

### Check Browser Console

1. Press F12 to open Developer Tools
2. Go to "Console" tab  
3. Look for RED errors
4. Send screenshot if you see errors

### Quick Test URL

Try this exact URL (replace 2 with your page ID):
```
http://localhost:7000/wp-admin/post.php?post=2&action=edit&probuilder=true
```

---

## 🎯 What Should Happen

When you click "Edit with ProBuilder", you should see:

✅ White header (not WordPress admin)
✅ "ProBuilder" logo on left
✅ Desktop/Tablet/Mobile buttons
✅ Left sidebar with widgets (320px)
✅ Center canvas (gray background)
✅ SAVE button (pink/magenta color)

---

## 🐛 Common Issues

### Issue: "Nothing happens when I click"
**Try:**
- Check browser console for JS errors
- Try different browser
- Clear all browser cache
- Disable browser extensions

### Issue: "Shows old WordPress editor"
**Try:**
- Click "Edit with ProBuilder" (not just "Edit")
- Make sure plugin is ACTIVE
- Check URL has `?probuilder=true`

### Issue: "404 Not Found"
**Try:**
- Go to Settings → Permalinks
- Click "Save Changes" (refresh permalinks)
- Try again

### Issue: "Blank white screen"
**Try:**
- Check wp-content/debug.log
- Check browser console
- Check PHP error log
- Increase PHP memory: add to wp-config.php
  ```php
  define('WP_MEMORY_LIMIT', '256M');
  ```

---

## 📞 Still Not Working?

Send me:
1. Screenshot of browser console (F12)
2. Screenshot of plugin page (showing ProBuilder is active)
3. Content of wp-content/debug.log (last 50 lines)
4. Exact URL you're trying to access

---

**Most likely fix:** Just deactivate → activate plugin, then hard refresh!

