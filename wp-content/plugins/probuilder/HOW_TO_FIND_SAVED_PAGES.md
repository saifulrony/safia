# How to Find Your Saved ProBuilder Pages

## 🔍 Quick Diagnosis Tool

I've created a debug script to help you find all pages saved with ProBuilder.

### Access the Debug Tool:

**URL:** `http://your-site.com/wp-content/plugins/probuilder/debug-saved-pages.php`

Or if local: `http://localhost:7000/wp-content/plugins/probuilder/debug-saved-pages.php`

This will show you:
- ✅ All pages with ProBuilder data
- ✅ Number of elements in each page
- ✅ Data format and structure
- ✅ Links to view/edit the pages
- ✅ Troubleshooting info

---

## 📍 Where to Find Your Pages

### Method 1: WordPress Dashboard

1. Go to **Dashboard → Pages → All Pages**
2. Look for your page in the list
3. Click **"View"** to see the frontend
4. Or click **"Edit with ProBuilder"** to continue editing

### Method 2: Direct URLs

**View Page:**
```
http://your-site.com/your-page-slug/
```

**Edit in ProBuilder:**
```
http://your-site.com/?p=POST_ID&probuilder=true
```

Replace `POST_ID` with your actual page ID (shown in the debug tool).

### Method 3: WordPress Admin

1. Go to **Pages → All Pages**
2. Find your page
3. Hover over it
4. Click **"Edit with ProBuilder"**

---

## 🐛 Troubleshooting

### Issue 1: "No pages found with ProBuilder data"

**Cause:** Data isn't being saved to the database.

**Solutions:**
1. Check database permissions
2. Verify the save function in `class-ajax.php`
3. Check browser console for JavaScript errors
4. Try saving again

### Issue 2: "Page exists but shows no content"

**Cause:** Frontend rendering isn't working.

**Check:**
1. Is `ProBuilder_Frontend::instance()` being called? (It is ✅)
2. View page source - do you see `<div class="probuilder-content">`?
3. Check if there are CSS issues hiding content

**Fix:** Add this to your theme's `functions.php`:
```php
add_filter('the_content', function($content) {
    global $post;
    if ($post) {
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        if (!empty($probuilder_data)) {
            error_log('ProBuilder data exists for post ' . $post->ID);
            error_log('Data: ' . print_r($probuilder_data, true));
        }
    }
    return $content;
}, 1);
```

### Issue 3: "Blank page or white screen"

**Causes:**
- PHP errors
- Missing widgets
- Corrupt data

**Solutions:**
1. Enable WordPress debug mode in `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

2. Check `wp-content/debug.log` for errors

3. Try viewing the page while logged in vs logged out (cache might be the issue)

### Issue 4: "Content appears in editor but not on frontend"

**Cause:** Data format mismatch.

**Check:**
The data should be saved as either:
- JSON string: `'[{"widgetType":"heading",...}]'`
- PHP array: `[["widgetType" => "heading", ...]]`

The frontend class handles both formats, but verify in the debug tool.

---

## 🧪 Test the Setup

### Create a Test Page:

1. **Create new page:**
   - Dashboard → Pages → Add New
   - Title: "ProBuilder Test Page"
   - Click "Edit with ProBuilder"

2. **Add content:**
   - Add a Heading widget
   - Change text to "Hello World"
   - Click **Save**

3. **Verify save:**
   - Access debug tool
   - Should see "ProBuilder Test Page" with 1 element

4. **View frontend:**
   - Click "View Page" in debug tool
   - Should see "Hello World" heading

---

## 📋 Data Structure

ProBuilder saves data in this format:

```json
[
    {
        "id": "element-12345",
        "widgetType": "heading",
        "settings": {
            "text": "Hello World",
            "tag": "h2",
            "color": "#333333"
        },
        "children": []
    },
    {
        "id": "element-67890",
        "widgetType": "text",
        "settings": {
            "content": "<p>Some text here</p>"
        },
        "children": []
    }
]
```

**Meta keys:**
- `_probuilder_data` - Contains all elements
- `_probuilder_edit_mode` - Set to "probuilder"

---

## 🔄 Frontend Rendering Flow

```
1. User visits page
   ↓
2. WordPress loads page
   ↓
3. the_content filter fires
   ↓
4. ProBuilder_Frontend::render_content() runs
   ↓
5. Gets _probuilder_data from post meta
   ↓
6. Loops through elements
   ↓
7. Renders each widget
   ↓
8. Outputs HTML wrapped in <div class="probuilder-content">
```

---

## 🎯 Common Solutions

### Solution 1: Clear Cache

```php
// Add to functions.php temporarily
add_action('init', function() {
    if (isset($_GET['clear_pb_cache']) && current_user_can('manage_options')) {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_probuilder%'");
        echo 'Cache cleared!';
        exit;
    }
});
```

Then visit: `http://your-site.com/?clear_pb_cache=1`

### Solution 2: Re-save the Page

1. Open page in ProBuilder editor
2. Make a tiny change (add a space, remove it)
3. Click Save
4. Verify in debug tool

### Solution 3: Check CSS

Add to your theme's CSS:
```css
.probuilder-content {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.probuilder-element {
    display: block !important;
}
```

---

## 📞 Still Having Issues?

### Check These:

1. **WordPress Version:** 5.8+
2. **PHP Version:** 7.4+
3. **Theme Conflicts:** Try Twenty Twenty-Five theme
4. **Plugin Conflicts:** Disable other plugins temporarily
5. **Permalinks:** Re-save (Settings → Permalinks)

### Get Detailed Info:

```php
// Add to functions.php
add_action('wp_footer', function() {
    if (current_user_can('manage_options')) {
        global $post;
        if ($post) {
            $data = get_post_meta($post->ID, '_probuilder_data', true);
            echo '<!-- ProBuilder Debug -->';
            echo '<!-- Post ID: ' . $post->ID . ' -->';
            echo '<!-- Has Data: ' . (empty($data) ? 'NO' : 'YES') . ' -->';
            if (!empty($data)) {
                echo '<!-- Element Count: ' . (is_array($data) ? count($data) : 'Not array') . ' -->';
            }
            echo '<!-- /ProBuilder Debug -->';
        }
    }
});
```

Then view page source and search for "ProBuilder Debug".

---

## ✅ Success Checklist

- [ ] Debug tool shows my page
- [ ] Element count is correct
- [ ] Data format looks valid
- [ ] Can view page via "View Page" link
- [ ] Content displays on frontend
- [ ] Can edit in ProBuilder
- [ ] No PHP errors in debug.log
- [ ] No JavaScript errors in console

---

## 🚀 Quick Fix Script

If nothing works, try this reset:

```php
// save-fix.php - Place in WordPress root, access once, then delete
<?php
require_once('wp-load.php');

if (!current_user_can('manage_options')) die('Access denied');

// Find pages with ProBuilder data
$pages = get_posts([
    'post_type' => ['page', 'post'],
    'posts_per_page' => -1,
    'meta_key' => '_probuilder_data'
]);

foreach ($pages as $page) {
    $data = get_post_meta($page->ID, '_probuilder_data', true);
    
    // Ensure it's in the right format
    if (is_string($data)) {
        $data = json_decode($data, true);
    }
    
    if (is_array($data)) {
        // Re-save in correct format
        update_post_meta($page->ID, '_probuilder_data', $data);
        update_post_meta($page->ID, '_probuilder_edit_mode', 'probuilder');
        echo "Fixed: {$page->post_title}<br>";
    }
}

echo "Done! Delete this file now.";
```

---

## 📝 Summary

**Your page IS saved** if:
1. It appears in the debug tool ✅
2. It has elements listed ✅
3. The data format looks valid ✅

**To view it:**
1. Use the debug tool links
2. Or go to Dashboard → Pages → Your Page → View

**If content doesn't show:**
- Check frontend rendering
- Clear cache
- Check theme compatibility
- Enable debug mode

---

**Need the debug tool again?**
`http://your-site.com/wp-content/plugins/probuilder/debug-saved-pages.php`

