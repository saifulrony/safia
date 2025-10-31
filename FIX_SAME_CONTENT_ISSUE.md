# 🔥 FIX: All Pages Showing Same Demo Content

## Your Issue

You're building pages with ProBuilder, but all different URLs show the same content:
```
🔥 HUGE SALE – 70% OFF
Find the Boundaries. Push Through!
Shop by Category...
```

This is the **HOMEPAGE/DEMO CONTENT** showing on ALL pages instead of your custom ProBuilder content.

---

## 🎯 Root Causes (3 Possible Issues)

### Issue #1: **Duplicate URL Slugs** (Most Common)
Multiple pages using the same URL slug → WordPress shows only one page for all URLs

### Issue #2: **Pages Don't Actually Exist**
The URLs you're visiting don't exist → WordPress shows homepage as fallback

### Issue #3: **ProBuilder Data Not Saved**
Pages exist but have no ProBuilder content → Theme shows demo content

---

## ✅ SOLUTION: Follow These Steps

### STEP 1: Check Which URLs Actually Exist

**Visit this diagnostic tool:**
```
http://192.168.10.203:7000/check-what-page-is-loading.php
```

**Enter your page URL** (example: `/my-page/`) and click "Test URL"

**This will tell you:**
- ✅ Does the page exist?
- ✅ What Post ID is it?
- ✅ Does it have ProBuilder data?
- 🚨 Are there duplicate slugs?

---

### STEP 2: Fix Duplicate Slugs (If Found)

If diagnostic shows **"DUPLICATE SLUG FOUND"**:

**Quick Fix - Run this script:**
```
http://192.168.10.203:7000/wp-content/plugins/probuilder/fix-duplicate-slugs.php
```

**Click "Fix All Duplicate Slugs Now"**

This will:
- Keep first page with original URL
- Rename others: `page-2`, `page-3`, etc.
- Flush WordPress rewrite rules
- Each page gets a unique URL

---

### STEP 3: Check All Your ProBuilder Pages

**Visit full diagnostic:**
```
http://192.168.10.203:7000/wp-content/plugins/probuilder/diagnose-url-issue.php
```

**This shows:**
- All ProBuilder pages
- Their URL slugs
- Which ones are duplicates (highlighted in red)
- Element count for each page
- Full URLs to visit

---

### STEP 4: Create Pages Correctly

When creating new pages with ProBuilder:

1. **Go to Pages → Add New**
   ```
   http://192.168.10.203:7000/wp-admin/post-new.php?post_type=page
   ```

2. **Give it a UNIQUE title:**
   - ✅ Good: "About Us", "Contact", "Services"
   - ❌ Bad: Using same title for multiple pages

3. **Check the permalink (URL):**
   - Under the title, you'll see: `Permalink: http://yoursite.com/about-us/`
   - Make sure it's unique!
   - Click "Edit" to change it if needed

4. **Publish the page**

5. **Then click "Edit with ProBuilder"** to add content

---

### STEP 5: Clear Cache

After fixing, clear all caches:

**1. Clear WordPress cache:**
```
http://192.168.10.203:7000/wp-content/plugins/probuilder/clear-cache.php
```

**2. Clear browser cache:**
- Press `Ctrl + Shift + Delete` (Windows) or `Cmd + Shift + Delete` (Mac)
- Select "Cached images and files"
- Click "Clear data"

**OR** use Incognito/Private browsing mode

**3. Flush permalinks:**
- Go to: **Settings → Permalinks**
- Just click "Save Changes" (don't change anything)

---

## 🧪 How to Test

### Test #1: Check Specific Page

Visit:
```
http://192.168.10.203:7000/check-what-page-is-loading.php?url=/your-page-slug/
```

Should show:
- ✅ Page Found!
- ✅ ProBuilder Data: Active
- ✅ Elements: X

### Test #2: Visit Actual Page

Go to the page URL directly:
```
http://192.168.10.203:7000/your-page-slug/
```

**What you should see:**
- ✅ Your ProBuilder content (headings, text, etc.)
- ✅ Debug panel (if logged in) showing correct Post ID
- ❌ NOT the demo store content

### Test #3: Check All Pages

Visit:
```
http://192.168.10.203:7000/wp-content/plugins/probuilder/diagnose-url-issue.php
```

Should show:
- ✅ No duplicate slugs
- ✅ All pages have unique URLs
- ✅ All pages have ProBuilder data

---

## 🚨 Common Mistakes

### Mistake #1: Creating Pages with Same Title

```
❌ WRONG:
- Page 1: Title = "My Page"  → URL: /my-page/
- Page 2: Title = "My Page"  → URL: /my-page/ (DUPLICATE!)
- Page 3: Title = "My Page"  → URL: /my-page/ (DUPLICATE!)

✅ CORRECT:
- Page 1: Title = "Home"      → URL: /home/
- Page 2: Title = "About Us"  → URL: /about-us/
- Page 3: Title = "Contact"   → URL: /contact/
```

### Mistake #2: Not Publishing Pages

Pages must be **Published** (not Draft) to show content.

Check status:
```
http://192.168.10.203:7000/wp-admin/edit.php?post_type=page
```

If status is "Draft", click "Quick Edit" and set Status to "Published"

### Mistake #3: Not Saving ProBuilder Content

After building with ProBuilder:
1. Click the **💾 Save** button (top right)
2. Wait for "Page Saved Successfully!" message
3. Click "🔗 View Page" to verify

---

## 📋 Quick Reference URLs

| Tool | URL | Purpose |
|------|-----|---------|
| **Check What's Loading** | `/check-what-page-is-loading.php` | See which page is loading for a URL |
| **Full Diagnostic** | `/wp-content/plugins/probuilder/diagnose-url-issue.php` | See all pages and duplicates |
| **Fix Duplicates** | `/wp-content/plugins/probuilder/fix-duplicate-slugs.php` | Auto-fix duplicate slugs |
| **Clear Cache** | `/wp-content/plugins/probuilder/clear-cache.php` | Clear WordPress cache |
| **All Pages** | `/wp-admin/edit.php?post_type=page` | View all pages in admin |
| **Permalinks** | `/wp-admin/options-permalink.php` | Flush rewrite rules |

---

## 🎬 Step-by-Step Video Guide

1. **Check for duplicates:**
   - Visit: `/wp-content/plugins/probuilder/diagnose-url-issue.php`
   - Look for red-highlighted rows

2. **If duplicates found:**
   - Click "Auto-Fix All Duplicate Slugs"
   - Wait for success message
   - Pages are automatically renamed

3. **Clear cache:**
   - Visit: `/wp-content/plugins/probuilder/clear-cache.php`
   - Go to Settings → Permalinks → Save Changes

4. **Test your pages:**
   - Visit each page URL
   - Verify correct content shows
   - Check debug panel (if logged in)

5. **Create new pages correctly:**
   - Always use unique titles
   - Check permalink before publishing
   - Save ProBuilder content after building

---

## 💡 Understanding the Issue

**Why does this happen?**

WordPress finds pages by their **URL slug** (the part after the domain):
```
http://example.com/my-page/
                   ^^^^^^^^ This is the slug
```

If two pages have the same slug, WordPress can only show ONE:

```
Database:
- Page #10: slug = "contact" → Content A
- Page #20: slug = "contact" → Content B
- Page #30: slug = "contact" → Content C

When you visit /contact/:
WordPress shows Page #10 (first one found)

All three URLs show the SAME content!
```

**The fix:** Rename duplicates so each has a unique slug:
```
After Fix:
- Page #10: slug = "contact"   → Shows Content A
- Page #20: slug = "contact-2" → Shows Content B
- Page #30: slug = "contact-3" → Shows Content C

Now each URL shows its OWN content!
```

---

## ✅ Verification Checklist

After applying fixes, verify:

- [ ] Run diagnostic tool - shows no duplicates
- [ ] Each page has unique URL slug
- [ ] Each page has ProBuilder data saved
- [ ] Each page is Published (not Draft)
- [ ] Visited each page URL - shows correct content
- [ ] Cleared WordPress cache
- [ ] Cleared browser cache
- [ ] Flushed permalinks (Settings → Permalinks → Save)

---

## 🆘 Still Not Working?

If pages still show same content after all fixes:

### Check #1: Is it the HOMEPAGE?

Your homepage might be set to a specific page:
- Go to: **Settings → Reading**
- Check "Your homepage displays"
- If set to "A static page", note which page
- This page will show at the root URL

### Check #2: Are you visiting the correct URL?

Get the correct URL from WordPress admin:
- Go to: **Pages → All Pages**
- Hover over your page
- Click "View" to see the real URL
- Use that URL for testing

### Check #3: Is WordPress configured correctly?

Check permalink structure:
- Go to: **Settings → Permalinks**
- Should NOT be "Plain"
- Recommended: "Post name"
- Click "Save Changes"

### Check #4: Check error logs

If logged in as admin, check the debug panel on the page:
- Shows Post ID, Title, Slug
- Shows element count
- Shows if ProBuilder is active

---

## 📞 Support Files Created

I've created these diagnostic/fix tools for you:

1. ✅ `check-what-page-is-loading.php` - Quick URL checker
2. ✅ `diagnose-url-issue.php` - Full page diagnostic
3. ✅ `fix-duplicate-slugs.php` - Auto-fix duplicates
4. ✅ Enhanced `class-ajax.php` - Prevents future duplicates
5. ✅ `DUPLICATE_URL_FIX.md` - Complete documentation

---

## 🎉 Prevention (Automatic)

Good news! The ProBuilder plugin now automatically:

✅ Checks for duplicate slugs when saving
✅ Makes slugs unique automatically
✅ Notifies you if URL was changed
✅ Flushes rewrite rules after save
✅ Prevents future duplicate issues

You just need to fix existing duplicates, then new pages will be protected!

---

**Start Here:** Run the diagnostic tool first:
```
http://192.168.10.203:7000/wp-content/plugins/probuilder/diagnose-url-issue.php
```

