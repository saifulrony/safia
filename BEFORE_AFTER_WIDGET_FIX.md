# ✅ Before/After Widget - Canvas Preview Fixed

## 🐛 Issue
The Before/After widget was not showing any preview on the ProBuilder canvas when dragged from the widget panel.

## 🔍 Root Cause
The widget was missing:
1. **JavaScript preview case** in `editor.js` - No preview generation code existed
2. **Undefined PHP variables** in the widget render method - `$wrapper_classes` and `$wrapper_attributes` were not defined

## ✅ Solution Applied

### Fix #1: Added Canvas Preview (JavaScript)
**File**: `wp-content/plugins/probuilder/assets/js/editor.js` (Lines 9816-9851)

Added a complete preview case for the `before-after` widget:

```javascript
case 'before-after':
    const baBeforeImageUrl = settings.before_image?.url || 'https://via.placeholder.com/800x600/999/fff?text=Before';
    const baAfterImageUrl = settings.after_image?.url || 'https://via.placeholder.com/800x600/92003b/fff?text=After';
    const baBeforeLabel = settings.before_label || 'Before';
    const baAfterLabel = settings.after_label || 'After';
    const baPosition = 50; // Default position
    
    return `<div style="position: relative; overflow: hidden; border-radius: 8px;">
        <div style="position: relative; height: 400px;">
            <!-- After Image (bottom layer) -->
            <img src="${baAfterImageUrl}" style="width: 100%; height: 100%; object-fit: cover;">
            
            <!-- Before Image (top layer with 50% clip) -->
            <div style="position: absolute; top: 0; left: 0; width: 50%; height: 100%; overflow: hidden;">
                <img src="${baBeforeImageUrl}" style="width: 200%; height: 100%; object-fit: cover;">
            </div>
            
            <!-- Slider Handle -->
            <div style="position: absolute; left: 50%; width: 4px; height: 100%; background: #92003b;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                     width: 40px; height: 40px; background: #92003b; border-radius: 50%; 
                     display: flex; align-items: center; justify-content: center; color: #fff;">
                    <i class="fa fa-arrows-left-right"></i>
                </div>
            </div>
            
            <!-- Labels -->
            <div style="position: absolute; top: 20px; left: 20px; 
                 background: rgba(0,0,0,0.7); color: #fff; padding: 8px 15px; 
                 border-radius: 4px; font-size: 14px; font-weight: 600;">
                ${baBeforeLabel}
            </div>
            <div style="position: absolute; top: 20px; right: 20px; 
                 background: rgba(146,0,59,0.9); color: #fff; padding: 8px 15px; 
                 border-radius: 4px; font-size: 14px; font-weight: 600;">
                ${baAfterLabel}
            </div>
        </div>
        <p style="text-align: center; margin: 15px 0 0; color: #666; font-size: 12px;">
            <i class="fa fa-arrows-left-right"></i> Drag slider to compare before & after
        </p>
    </div>`;
```

### Fix #2: Fixed PHP Variables
**File**: `wp-content/plugins/probuilder/widgets/before-after.php` (Lines 52-62)

**Before:**
```php
protected function render() {
    $before = $this->get_settings('before_image', [...]);
    $after = $this->get_settings('after_image', [...]);
    // ...
    
    // ❌ $wrapper_classes and $wrapper_attributes are UNDEFINED
    ?>
    <div class="<?php echo esc_attr($wrapper_classes); ?>" ...>
```

**After:**
```php
protected function render() {
    // Render custom CSS if any
    $this->render_custom_css();
    
    $before = $this->get_settings('before_image', [...]);
    $after = $this->get_settings('after_image', [...]);
    // ...
    
    // ✅ Get wrapper classes and attributes from base class
    $wrapper_classes = $this->get_wrapper_classes();
    $wrapper_attributes = $this->get_wrapper_attributes();
    
    ?>
    <div class="<?php echo esc_attr($wrapper_classes); ?>" ...>
```

## 🎨 Preview Features

The canvas preview now shows:
- ✅ **Before image** (left 50%, gray placeholder by default)
- ✅ **After image** (bottom layer, magenta placeholder by default)
- ✅ **Slider handle** (center, magenta circular button with arrows icon)
- ✅ **Before label** (top-left, dark background)
- ✅ **After label** (top-right, magenta background)
- ✅ **Help text** below the comparison ("Drag slider to compare")
- ✅ **Proper layout** (400px height, responsive width)
- ✅ **Rounded corners** (8px border-radius)

### Preview Layout:
```
┌─────────────────────────────────────┐
│ [Before]         [After]            │
│                                     │
│  BEFORE  │  AFTER                   │
│  IMAGE   │  IMAGE                   │
│  (50%)   │  (100%)                  │
│         (●)  ← Slider Handle        │
│                                     │
└─────────────────────────────────────┘
  Drag slider to compare before & after
```

## 🧪 How to Test

### Step 1: Clear Cache
```
Press: Ctrl + Shift + R (or Cmd + Shift + R on Mac)
```

### Step 2: Add Widget to Canvas
1. Open ProBuilder editor on any page
2. Find "Before/After" widget in **Advanced** category
3. Drag it to the canvas
4. **You should now see the preview!** ✅

### Step 3: Verify Preview Content
- ✅ Two images visible (before on left, after on right)
- ✅ Slider handle in the center (magenta circle with arrows)
- ✅ "Before" label (top-left, dark background)
- ✅ "After" label (top-right, magenta background)
- ✅ Help text below ("Drag slider to compare before & after")

### Step 4: Configure Settings
1. Click on the widget to select it
2. Settings panel opens on the right
3. Change images using the "Before Image" and "After Image" controls
4. Change labels using "Before Label" and "After Label" fields
5. Preview updates automatically ✅

### Step 5: Test on Frontend
1. Save the page
2. View on frontend
3. The interactive slider should work (drag to compare images)

## ✨ What Works Now

### In Editor (Canvas):
- ✅ Static preview showing before/after comparison
- ✅ Default placeholder images (gray "Before", magenta "After")
- ✅ Custom images appear when configured
- ✅ Custom labels appear when configured
- ✅ Professional, realistic preview
- ✅ Matches frontend design

### On Frontend (Published Page):
- ✅ Interactive slider (drag to compare)
- ✅ Smooth animation
- ✅ Touch support (mobile devices)
- ✅ Keyboard accessible
- ✅ Custom images and labels
- ✅ Responsive design

## 📁 Files Changed

1. **wp-content/plugins/probuilder/assets/js/editor.js**
   - Lines 9816-9851: Added `before-after` preview case

2. **wp-content/plugins/probuilder/widgets/before-after.php**
   - Lines 52-62: Fixed undefined variables (`$wrapper_classes`, `$wrapper_attributes`)
   - Added `render_custom_css()` call
   - Added calls to `get_wrapper_classes()` and `get_wrapper_attributes()`

## 🎉 Result

**The Before/After widget now shows a beautiful preview on the canvas!**

✅ Preview appears immediately when added  
✅ Shows before/after images with slider  
✅ Displays custom labels  
✅ Realistic, professional appearance  
✅ No PHP errors or undefined variables

**Clear your browser cache and try it!** 🚀

