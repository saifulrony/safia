# 🎯 Alignment Guide Directions - Verified & Explained

## How It Works (Correct Logic)

### When Dragging TOP or BOTTOM (Height Resize):
**Shows**: HORIZONTAL lines (━━━)
**Why**: Comparing Y-axis positions (heights)
**Guides shown**:
- Top guide (horizontal line at top position)
- Bottom guide (horizontal line at bottom position)
- Center-H guide (horizontal line at vertical center)

**Visual**:
```
     Cell Being Resized    Other Cells
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ← TOP guide (when tops align)
     ┌─────────────┐      ┌─────────┐
     │      ↕      │      │         │
━━━━━┿━━━━━━━━━━━━━┿━━━━━━┿━━━━━━━━━┿  ← CENTER-H guide (when vertical centers align)
     │             │      │         │
     └─────────────┘      └─────────┘
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ← BOTTOM guide (when bottoms align)
```

### When Dragging LEFT or RIGHT (Width Resize):
**Shows**: VERTICAL lines (┃)
**Why**: Comparing X-axis positions (widths)
**Guides shown**:
- Left guide (vertical line at left position)
- Right guide (vertical line at right position)
- Center-V guide (vertical line at horizontal center)

**Visual**:
```
    ┃           ┃           ┃
    ┃  Cell     ┃  Other    ┃
    ┃ ┌───┐    ┃ ┌───────┐ ┃
    ┃ │←→ │    ┃ │       │ ┃
    ┃ └───┘    ┃ └───────┘ ┃
    ┃           ┃           ┃
    ↑           ↑           ↑
   LEFT      CENTER       RIGHT
   guide      guide       guide
```

### When Dragging CORNER (Both Dimensions):
**Shows**: BOTH horizontal and vertical lines
**Why**: Comparing both width and height
**Guides shown**: All 6 guides

## Code Logic Explanation

### Variable Names:
```javascript
const showVerticalGuides = (direction === 'left' || direction === 'right' || direction === 'both');
const showHorizontalGuides = (direction === 'top' || direction === 'bottom' || direction === 'both');
```

- `showVerticalGuides` = true when resizing WIDTH (need vertical lines for X comparison)
- `showHorizontalGuides` = true when resizing HEIGHT (need horizontal lines for Y comparison)

### Guide Types:
```javascript
// Vertical guides (for width/X-axis):
$guides.left      // vertical line at left edge
$guides.right     // vertical line at right edge
$guides.centerV   // vertical line at horizontal center

// Horizontal guides (for height/Y-axis):
$guides.top       // horizontal line at top edge
$guides.bottom    // horizontal line at bottom edge  
$guides.centerH   // horizontal line at vertical center
```

## Quick Reference

| Handle | Direction | Shows | Line Type | Compares |
|--------|-----------|-------|-----------|----------|
| **Top** | top | Top, Bottom, Center-H | Horizontal (━) | Heights (Y) |
| **Bottom** | bottom | Top, Bottom, Center-H | Horizontal (━) | Heights (Y) |
| **Left** | left | Left, Right, Center-V | Vertical (┃) | Widths (X) |
| **Right** | right | Left, Right, Center-V | Vertical (┃) | Widths (X) |
| **Corners** | both | All 6 guides | Both | Both |

## Why This Is Correct

### Height Resize Needs Horizontal Lines:
When you're changing height, you want to know "Is my TOP at the same Y position as another cell's top?"
- Answer: Horizontal line showing where the other top is
- If your top touches the line, they're aligned vertically (same Y)

### Width Resize Needs Vertical Lines:
When you're changing width, you want to know "Is my LEFT at the same X position as another cell's left?"
- Answer: Vertical line showing where the other left is
- If your left touches the line, they're aligned horizontally (same X)

## Test Instructions

### To verify:
1. **Drag TOP handle** → Should see ONLY horizontal lines (━━━)
2. **Drag BOTTOM handle** → Should see ONLY horizontal lines (━━━)
3. **Drag LEFT handle** → Should see ONLY vertical lines (┃)
4. **Drag RIGHT handle** → Should see ONLY vertical lines (┃)
5. **Drag CORNER** → Should see BOTH types

If you're seeing different behavior, please let me know which handle shows the wrong type of guide!
