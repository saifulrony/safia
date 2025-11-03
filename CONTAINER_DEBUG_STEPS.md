# 🔍 Container Vertical Stacking - Debug Steps

## Issue: Canvas still showing only one widget

### Quick Debug Steps:

1. **Clear Browser Cache COMPLETELY**
   - Press: **Ctrl+Shift+Delete** (or Cmd+Shift+Delete on Mac)
   - Select "Cached images and files"
   - Select "All time"
   - Click "Clear data"
   - **OR** Press: **Ctrl+Shift+R** multiple times

2. **Open Browser Console**
   - Press: **F12** (or Cmd+Option+I on Mac)
   - Go to "Console" tab
   - Look for any errors (red text)
   - Share any errors you see

3. **Check Container Settings**
   - Click on the container in the canvas
   - In the settings panel, verify:
     - Direction: Should be "Vertical (Stack)"
     - NOT "Horizontal (Columns)"

4. **Check How Many Widgets Are in Container**
   - Look at the page source (Ctrl+U)
   - Search for your container ID
   - Count how many child widgets are inside
   - Compare to what you see in canvas

5. **Try Adding Widgets Again**
   - Delete the container
   - Add a new Container widget
   - Set Direction: "Vertical (Stack)"
   - Drag ONE widget into it
   - Check if it shows
   - Drag ANOTHER widget into it
   - Check if BOTH show

---

## Expected Behavior:

### When Direction = Vertical:
```
Canvas should show:
┌─────────────────┐
│   Widget 1      │
├─────────────────┤
│   Widget 2      │
├─────────────────┤
│   Widget 3      │
└─────────────────┘
```

### What You Might Be Seeing:
```
Canvas shows:
┌─────────────────┐
│   Widget 1      │
└─────────────────┘
(Widget 2 and 3 missing)
```

But page shows all 3 correctly.

---

## Possible Causes:

1. **Browser Cache** - Old JavaScript still running
2. **JavaScript Error** - Blocking preview generation
3. **Data Not Syncing** - Children not in element.children array
4. **CSS Conflict** - Grid CSS being overridden

---

## Manual Test:

1. Open: http://192.168.10.203:7000/draft-new-page/ (or any page)
2. Click "Edit with ProBuilder"
3. Add Container
4. Click container to select it
5. In settings:
   - Find "Direction"
   - Set to "Vertical (Stack)"
6. Drag a Heading widget into container
7. **Does it show in canvas?** → Yes/No
8. Drag a Text widget into container
9. **Do BOTH show in canvas?** → Yes/No
10. Save page
11. View page (not editor)
12. **Do BOTH show on page?** → Yes/No

---

## If Still Broken:

Please provide:
1. Screenshot of canvas (showing only 1 widget)
2. Screenshot of page (showing all widgets)
3. Any console errors (press F12 → Console tab)
4. Container settings (Direction = ?)

Then I can provide a more specific fix!

---

## Alternative Workaround:

If vertical stacking doesn't work, you can achieve the same result temporarily:

1. Add Container
2. Keep Direction: "Horizontal (Columns)"
3. Set "Number of Columns": **1**
4. Add widgets
5. They will stack vertically (because 1 column = vertical)

This achieves the same visual result while I debug the "Vertical (Stack)" option.

