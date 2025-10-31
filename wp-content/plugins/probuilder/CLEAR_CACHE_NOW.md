# Clear Cache to See 12 Column Limit

## The Issue

The file shows `max => 12` correctly, but the browser/WordPress is showing cached data with the old limit.

## Solution: Clear All Caches

### Step 1: Hard Refresh Browser
```
Chrome/Firefox/Edge:
- Windows: Ctrl + Shift + R
- Mac: Cmd + Shift + R

Or:
- Open DevTools (F12)
- Right-click refresh button
- Select "Empty Cache and Hard Reload"
```

### Step 2: Clear WordPress Object Cache
```bash
cd /home/saiful/wordpress
wp cache flush
```

### Step 3: Restart Apache (Clears PHP cache)
```bash
sudo systemctl restart apache2
```

### Step 4: Clear Browser Storage
1. Open browser DevTools (F12)
2. Go to "Application" tab (Chrome) or "Storage" tab (Firefox)
3. Clear "Local Storage"
4. Clear "Session Storage"
5. Close and reopen browser

### Step 5: Re-save Widget
Sometimes WordPress caches widget definitions. Try:
1. Go to WordPress Admin
2. Navigate to ProBuilder editor
3. Close and reopen the page
4. Try the widget again

## Quick Test

After clearing cache, check if slider shows:
- Min: 1
- Max: **12** (not 100)
- Default: 2

## Alternative: Force Cache Bust

If cache persists, add a version bump to force reload:

```php
// In container-2.php, change the widget name temporarily:
$this->name = 'container-2-v2';  // Add version suffix
```

Then change it back after cache clears.

## Current File Content (Confirmed Correct)

```php
$this->add_control('columns', [
    'label' => __('Number of Columns', 'probuilder'),
    'type' => 'slider',
    'default' => 2,
    'range' => [
        'min' => 1,
        'max' => 12,  // ✅ CORRECT
    ],
]);
```

The issue is definitely caching, not the code!

