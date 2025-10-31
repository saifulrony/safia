# ✅ Alignment Guides - Direction-Aware!

## Problem Fixed

**Issue**: When resizing top/bottom (height), all guides appeared including vertical lines (left/right/center). This was confusing because vertical lines are for width comparison, not height.

**Solution**: Guides now only show for the relevant direction being resized.

## Smart Direction Detection

### When Resizing HEIGHT (top/bottom):
Shows ONLY **horizontal lines** (for comparing heights):
```
         Cell 1                Cell 2
     ┌─────────────┐      ┌─────────────┐
     │             │      │             │
     │   Resize    │      │   Compare   │
━━━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━━━━━━━━━━━┿━━━━  ← Top alignment guide
     │      ↕      │      │             │
     │             │      │             │
━━━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━━━━━━━━━━━┿━━━━  ← Center alignment guide
     │             │      │             │
     └─────────────┘      └─────────────┘
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ← Bottom alignment guide

Dragging TOP handle → Shows horizontal lines when heights match
Dragging BOTTOM handle → Shows horizontal lines when heights match
```

### When Resizing WIDTH (left/right):
Shows ONLY **vertical lines** (for comparing widths):
```
    ┃           ┃           ┃
    ┃  Cell 1   ┃  Cell 2   ┃
    ┃ ┌───────┐ ┃ ┌───────┐ ┃
    ┃ │Resize │ ┃ │Compare│ ┃
    ┃ │  ←→   │ ┃ │       │ ┃
    ┃ └───────┘ ┃ └───────┘ ┃
    ┃           ┃           ┃
    ┃           ┃           ┃
    ↑           ↑           ↑
   Left      Center       Right
  Guide      Guide        Guide

Dragging LEFT handle → Shows vertical lines when widths match
Dragging RIGHT handle → Shows vertical lines when widths match
```

### When Resizing BOTH (corner handles):
Shows **both horizontal AND vertical lines**:
```
    ┃           ┃           ┃
━━━━┿━━━━━━━━━━━┿━━━━━━━━━━━┿━━━━  ← Horizontal (height)
    ┃ ┌───────┐ ┃ ┌───────┐ ┃
    ┃ │Resize │ ┃ │Compare│ ┃
    ┃ │   ↔   │ ┃ │       │ ┃
━━━━┿━┿━━━━━━━┿━┿━┿━━━━━━━┿━┿━━━━  ← Horizontal (center)
    ┃ │   ↕   │ ┃ │       │ ┃
    ┃ └───────┘ ┃ └───────┘ ┃
━━━━┿━━━━━━━━━━━┿━━━━━━━━━━━┿━━━━  ← Horizontal (bottom)
    ↑           ↑           ↑
  Vertical guides (width)

Dragging CORNER handle → Shows all relevant guides
```

## Code Logic

### Direction Parameter Added:
```javascript
showAlignmentGuides($cell, $container, index, bounds, $guides, direction)
//                                                              ↑ NEW!
```

### Smart Filtering:
```javascript
// Determine which guides to check
const checkVertical = (direction === 'left' || direction === 'right' || direction === 'both');
const checkHorizontal = (direction === 'top' || direction === 'bottom' || direction === 'both');

// Only show relevant guides
if (checkVertical) {
    // Show left, right, centerV guides (vertical lines)
}

if (checkHorizontal) {
    // Show top, bottom, centerH guides (horizontal lines)
}
```

## Guide Mapping

### Resize Direction → Guide Type:

| Handle Direction | Guides Shown | Line Type | Purpose |
|-----------------|--------------|-----------|---------|
| **Top** | top, bottom, centerH | Horizontal | Compare heights |
| **Bottom** | top, bottom, centerH | Horizontal | Compare heights |
| **Left** | left, right, centerV | Vertical | Compare widths |
| **Right** | left, right, centerV | Vertical | Compare widths |
| **Both** (Corner) | All 6 guides | Both | Compare both |

## Visual Clarity

### Before (Wrong):
```
Dragging TOP (height) → Shows BOTH vertical AND horizontal lines ❌
  Result: Confusing - vertical lines are irrelevant to height
```

### After (Correct):
```
Dragging TOP (height) → Shows ONLY horizontal lines ✅
  Result: Clear - only shows height comparison guides

Dragging LEFT (width) → Shows ONLY vertical lines ✅
  Result: Clear - only shows width comparison guides
```

## Benefits

✅ **Less Clutter**: Only relevant guides appear
✅ **Clear Intent**: Direction matches guide type
✅ **Better UX**: No confusion about what's being compared
✅ **Faster Work**: Focus on relevant alignments only
✅ **Professional**: Matches Illustrator behavior
✅ **Smart**: Automatically filters based on handle

## Example Scenarios

### Scenario 1: Making Same Height
1. Drag **top** or **bottom** handle
2. Only **horizontal lines** appear
3. When tops align → Top guide shows
4. When bottoms align → Bottom guide shows
5. When centers align → Center horizontal guide shows

### Scenario 2: Making Same Width
1. Drag **left** or **right** handle
2. Only **vertical lines** appear
3. When left edges align → Left guide shows
4. When right edges align → Right guide shows
5. When centers align → Center vertical guide shows

### Scenario 3: Matching Both Dimensions
1. Drag **corner** handle
2. **Both** horizontal and vertical lines appear
3. All alignment types shown
4. Perfect for matching exact size and position

## Result

Alignment guides are now **direction-aware** and only show lines relevant to what you're resizing:
- **Height resize** → Horizontal guides only
- **Width resize** → Vertical guides only
- **Both resize** → All guides

**Refresh your browser and test!** Guides now match your resize direction perfectly! 🎯
