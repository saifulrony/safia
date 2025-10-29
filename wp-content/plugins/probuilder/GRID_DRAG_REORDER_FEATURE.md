# Grid Cell Drag & Reorder - Puzzle-Like Functionality

## 🎉 New Feature: Drag & Drop Grid Cells Like Puzzle Pieces!

You can now **drag and reorder grid cells** just like puzzle pieces, and the grid will **automatically adjust** like flexbox!

---

## ✨ Features

### 1. **Visual Drag Handle**
- Blue drag handle (⬍ move icon) appears on the **top-left** of each cell when you hover
- Instantly recognizable and easy to grab
- Color: Blue (`#007cba`) - matches ProBuilder theme

### 2. **Smooth Drag & Drop**
- Click and hold the drag handle to pick up a cell
- Drag it to any position in the grid
- See a placeholder showing where it will drop
- Release to place it in the new position

### 3. **Auto-Adjusting Layout**
- Grid automatically reflows after reordering
- Works like flexbox - cells flow naturally into their new positions
- No manual resizing needed
- Preserves cell content and settings

### 4. **Visual Feedback**
- **Dragging:** Cell becomes semi-transparent (70% opacity) with shadow
- **Placeholder:** Dashed blue border shows drop location
- **Cursor:** Changes to `grabbing` during drag
- **Toast notification:** "✅ Grid cells reordered!" confirms success

---

## 🎮 How to Use

### Step 1: Add a Grid Layout
1. Open ProBuilder editor
2. Add a **Grid Layout** widget from the sidebar
3. Choose a pattern (2 columns, 3 columns, etc.)

### Step 2: Add Content to Cells
1. Drop widgets into grid cells
2. Or leave some cells empty (you can reorder empty cells too!)

### Step 3: Reorder Cells
1. **Hover** over any grid cell
2. **Look for** the blue drag handle (⬍) on the top-left corner
3. **Click and hold** the drag handle
4. **Drag** to desired position
5. **Release** to drop

### Visual Guide:
```
BEFORE DRAG:
┌─────────┬─────────┬─────────┐
│ ⬍ Cell 1│ ⬍ Cell 2│ ⬍ Cell 3│
│ (Image) │ (Text)  │ (Button)│
└─────────┴─────────┴─────────┘

DRAGGING Cell 2:
┌─────────┬ ╌ ╌ ╌ ╌ ┬─────────┐
│ ⬍ Cell 1│ DROP    │ ⬍ Cell 3│
│ (Image) │ HERE    │ (Button)│
└─────────┴ ╌ ╌ ╌ ╌ ┴─────────┘
         [Cell 2 floating]

AFTER DROP:
┌─────────┬─────────┬─────────┐
│ ⬍ Cell 1│ ⬍ Cell 3│ ⬍ Cell 2│
│ (Image) │ (Button)│ (Text)  │
└─────────┴─────────┴─────────┘
```

---

## 🎨 Visual Elements

### Drag Handle (Top-Left Corner)
```css
Position: Top-left (5px from edges)
Size: 32px × 32px
Color: Blue (#007cba)
Icon: ⬍ (dashicons-move)
Visibility: Shows on hover
Cursor: grab → grabbing
```

### Delete Button (Top-Right Corner)
```css
Position: Top-right (5px from edges)
Size: 28px × 28px
Color: Red (#dc2626)
Icon: 🗑️ (dashicons-trash)
Visibility: Shows on hover
```

### Layout:
```
┌──────────────────────────┐
│ ⬍ [Drag]      [Delete] 🗑│
│                          │
│    CELL CONTENT HERE     │
│                          │
└──────────────────────────┘
```

---

## 🔧 Technical Details

### How It Works

1. **Sortable Initialization:**
   - Uses jQuery UI Sortable
   - Applied to `.probuilder-grid-layout`
   - Handle: `.grid-cell-drag-handle`

2. **Reordering Logic:**
   ```javascript
   // When cells are reordered:
   1. Capture new order from DOM
   2. Update children array in element data
   3. Re-render grid with new order
   4. Save to history (undo/redo support)
   5. Show success notification
   ```

3. **Data Persistence:**
   - Cell order is saved in `gridElement.children` array
   - Automatically saved to database
   - Survives page refresh
   - Supports undo/redo

4. **Flexbox-Like Behavior:**
   - Cells automatically reflow to fill space
   - Maintains grid pattern structure
   - Empty cells are preserved
   - Content stays within cells

---

## 🎯 Use Cases

### 1. **Rearrange Product Grid**
```
Products:      │  After Reordering:
[A] [B] [C]    │  [C] [A] [B]
[D] [E] [F]    │  [F] [D] [E]
```

### 2. **Reorganize Gallery**
```
Images:        │  After Reordering:
[1] [2] [3]    │  [3] [1] [2]
[4] [5] [6]    │  [6] [4] [5]
```

### 3. **Adjust Dashboard Widgets**
```
Dashboard:     │  After Reordering:
[Stats][Chart] │  [Chart][Stats]
[News][Users]  │  [Users][News]
```

---

## ⚙️ Configuration

### Sortable Options:
- **Handle:** `.grid-cell-drag-handle` (only drag from handle)
- **Tolerance:** `pointer` (drop when pointer enters)
- **Opacity:** `0.7` (70% transparent while dragging)
- **Distance:** `10px` (must move 10px before drag starts)
- **Delay:** `100ms` (must hold for 100ms before drag starts)
- **Cursor:** `grabbing` (shows grab cursor during drag)

### Customizable Behaviors:
- **Placeholder:** Dashed blue border
- **Helper:** Semi-transparent with shadow
- **Auto-scroll:** Enabled by default
- **Animation:** Smooth transitions

---

## 🚀 Benefits

1. **✅ Intuitive:** Drag and drop is familiar to everyone
2. **✅ Fast:** Reorder cells in seconds
3. **✅ Visual:** See exactly where cells will go
4. **✅ Safe:** Undo/redo support
5. **✅ Responsive:** Works with any grid size
6. **✅ Flexible:** Reorder with or without content
7. **✅ Smart:** Auto-adjusts layout like flexbox

---

## 🎬 Demo Scenario

**Scenario:** You have a 3-column product grid and want to move the featured product to the first position.

**Steps:**
1. Hover over the featured product cell → See drag handle (⬍)
2. Click and hold drag handle → Cell lifts up
3. Drag to first position → See placeholder
4. Release → Cell drops into new position
5. Grid automatically reflows → Done!

**Result:** Featured product is now first, all other products shifted accordingly!

---

## 🔒 What's Protected

✅ **Content stays safe:** Reordering doesn't affect cell content  
✅ **Settings preserved:** All cell settings remain intact  
✅ **Children intact:** Nested widgets move with their cell  
✅ **Resize handles:** Cell resize handles still work  
✅ **Delete button:** Cell delete button still works  
✅ **Undo/Redo:** Full history support  

---

## 📋 Compatibility

✅ **All grid patterns:** 2-column, 3-column, 4-column, custom  
✅ **All content types:** Text, images, buttons, forms, etc.  
✅ **Empty cells:** Can reorder empty cells  
✅ **Mixed cells:** Mix of empty and filled cells  
✅ **Nested widgets:** Works with any widget inside cells  
✅ **Responsive:** Works on all screen sizes  

---

## 🐛 Troubleshooting

### Issue: Drag handle not appearing
**Solution:** Hover over the cell - it appears on hover

### Issue: Can't drag the cell
**Solution:** Make sure you're clicking the drag handle (⬍), not the content

### Issue: Cell dropped in wrong place
**Solution:** Wait for placeholder to appear, then release

### Issue: Grid looks weird after reorder
**Solution:** Clear browser cache (Ctrl+Shift+R)

---

## 🎓 Best Practices

1. **Plan your layout:** Think about logical order before dragging
2. **Use placeholders:** Wait to see the placeholder before dropping
3. **Save often:** ProBuilder auto-saves, but click Save to be sure
4. **Test on mobile:** Check how reordered grid looks on different screens
5. **Use empty cells strategically:** Empty cells can create visual spacing

---

## 📝 Keyboard Shortcuts

While the feature is mouse-based, here are related shortcuts:

- **Ctrl+Z:** Undo reorder
- **Ctrl+Shift+Z:** Redo reorder
- **Ctrl+S:** Save changes
- **Esc:** Cancel drag (during drag)

---

## 🎨 Styling

### Drag Handle Customization:
```css
/* Change drag handle color */
.grid-cell-drag-handle {
    background: rgba(255, 0, 0, 0.9) !important; /* Red */
}

/* Change drag handle size */
.grid-cell-drag-handle {
    width: 40px !important;
    height: 40px !important;
}

/* Change drag handle icon */
.grid-cell-drag-handle .dashicons::before {
    content: "\f504"; /* Different icon */
}
```

---

## 🏆 Summary

✨ **New Feature:** Drag and reorder grid cells like puzzle pieces  
🎯 **How:** Click drag handle (⬍) → Drag → Drop  
🚀 **Result:** Grid auto-adjusts like flexbox  
💾 **Saved:** Changes persist and support undo/redo  
🎉 **Benefit:** Fast, intuitive content reorganization  

**You now have complete control over your grid layout!** 🎊

---

## Files Modified

- `assets/js/editor.js` - Added sortable functionality and drag handle
- `assets/css/editor.css` - Added drag handle styles and pointer events

---

## Version

**Feature Version:** 1.0.0  
**Date:** October 26, 2025  
**Status:** ✅ Active and Ready to Use!

