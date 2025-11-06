<?php
/**
 * Simple Grid with Resize Handles - Standalone Test
 * This will show if CSS/HTML is working
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grid Resize Test</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 40px;
            background: #f0f0f0;
        }
        .info {
            max-width: 800px;
            margin: 0 auto 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #1f2937; margin-top: 0; }
        
        /* EXACT COPY OF PROBUILDER CSS */
        .resize-handle {
            position: absolute;
            background: #667eea;
            opacity: 0.8;
            transition: all 0.2s ease;
            z-index: 1000;
            pointer-events: auto !important;
            user-select: none !important;
        }
        
        .grid-cell:hover .resize-handle {
            opacity: 1 !important;
        }
        
        .resize-handle:hover {
            background: #5568d3 !important;
            transform: scale(1.2) !important;
        }
        
        .resize-handle-right {
            right: -5px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 80px;
            cursor: ew-resize;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
            border: 2px solid #ffffff;
        }
        
        .resize-handle-bottom {
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 10px;
            cursor: ns-resize;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
            border: 2px solid #ffffff;
        }
        
        .resize-handle-corner {
            right: -8px;
            bottom: -8px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: nwse-resize;
            background: #667eea;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
            border: 3px solid #ffffff;
        }
        
        .grid-cell:hover {
            outline: 2px dashed #667eea;
            outline-offset: -2px;
            background: rgba(102, 126, 234, 0.05);
        }
        
        /* Test grid */
        .test-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .grid-cell {
            position: relative;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="info">
        <h1>🎨 Grid Resize Handles Test</h1>
        <p><strong>This is a standalone test with the EXACT CSS from ProBuilder.</strong></p>
        <p>If you can see purple handles here, then the CSS works. If not, something else is wrong.</p>
    </div>
    
    <div class="test-grid">
        <div class="grid-cell">
            <div class="resize-handle resize-handle-right"></div>
            <div class="resize-handle resize-handle-bottom"></div>
            <div class="resize-handle resize-handle-corner"></div>
            <div style="text-align: center;">
                <strong style="color: #667eea; font-size: 18px;">Cell 1</strong><br>
                <small style="color: #6b7280;">Look for purple handles!</small>
            </div>
        </div>
        
        <div class="grid-cell">
            <div class="resize-handle resize-handle-right"></div>
            <div class="resize-handle resize-handle-bottom"></div>
            <div class="resize-handle resize-handle-corner"></div>
            <div style="text-align: center;">
                <strong style="color: #667eea; font-size: 18px;">Cell 2</strong><br>
                <small style="color: #6b7280;">Handles should be visible!</small>
            </div>
        </div>
    </div>
    
    <div class="info" style="margin-top: 40px;">
        <h2 style="color: #667eea;">✅ What You Should See:</h2>
        <ul style="line-height: 2;">
            <li><strong>Right edge:</strong> Purple vertical bar (10px wide, 80px tall) - centered on right edge</li>
            <li><strong>Bottom edge:</strong> Purple horizontal bar (80px wide, 10px tall) - centered on bottom edge</li>
            <li><strong>Bottom-right corner:</strong> Purple circle (20px diameter)</li>
            <li><strong>On hover:</strong> Purple dashed outline around cell + background tint</li>
            <li><strong>Handles are 80% visible ALWAYS</strong> (not hidden)</li>
        </ul>
        
        <h3 style="color: #dc2626;">❓ Can You See the Purple Handles?</h3>
        
        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <button onclick="document.getElementById('yes-result').style.display='block'; document.getElementById('no-result').style.display='none';" 
                    style="flex: 1; padding: 20px; background: #10b981; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                ✅ YES - I can see purple handles
            </button>
            <button onclick="document.getElementById('no-result').style.display='block'; document.getElementById('yes-result').style.display='none';" 
                    style="flex: 1; padding: 20px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                ❌ NO - I see nothing
            </button>
        </div>
        
        <div id="yes-result" style="display: none; padding: 20px; background: #d1fae5; border-radius: 8px; margin-top: 20px; border-left: 4px solid #10b981;">
            <h3 style="color: #065f46; margin-top: 0;">✅ CSS IS WORKING!</h3>
            <p style="color: #065f46;">The CSS is loaded correctly. The issue is in ProBuilder editor.</p>
            <p style="color: #065f46;"><strong>Next:</strong> Open ProBuilder editor and check browser console (F12) for JavaScript errors.</p>
            <a href="http://192.168.10.203:7000/?p=803&probuilder=true&post_type=pb_header" 
               style="display: inline-block; background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 10px;">
                Open ProBuilder Editor
            </a>
        </div>
        
        <div id="no-result" style="display: none; padding: 20px; background: #fee2e2; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ef4444;">
            <h3 style="color: #991b1b; margin-top: 0;">❌ CSS NOT LOADED!</h3>
            <p style="color: #991b1b;">Your browser is NOT loading the CSS or there's an override.</p>
            <p style="color: #991b1b;"><strong>Solutions:</strong></p>
            <ol style="color: #991b1b; line-height: 1.8;">
                <li>Open browser DevTools (F12)</li>
                <li>Go to "Elements" or "Inspector" tab</li>
                <li>Find one of the cells above</li>
                <li>Look for <code>&lt;div class="resize-handle resize-handle-right"&gt;&lt;/div&gt;</code></li>
                <li>Click on it and check the "Styles" panel</li>
                <li>See if CSS is applied</li>
            </ol>
        </div>
    </div>
    
    <div class="info">
        <h3>🔍 Browser Console Test:</h3>
        <p>Open console (F12) and paste this:</p>
        <code style="display: block; padding: 15px; background: #f3f4f6; border-radius: 6px; overflow-x: auto;">
const h = document.querySelector('.resize-handle-right');<br>
if (h) {<br>
  const s = getComputedStyle(h);<br>
  console.log('Handle found!', {<br>
    width: s.width,<br>
    height: s.height,<br>
    opacity: s.opacity,<br>
    background: s.backgroundColor,<br>
    position: s.position<br>
  });<br>
} else {<br>
  console.log('❌ Handle NOT found in DOM!');<br>
}
        </code>
        
        <p style="margin-top: 15px;"><strong>Expected output:</strong></p>
        <code style="display: block; padding: 15px; background: #d1fae5; border-radius: 6px;">
width: "10px" ✅<br>
height: "80px" ✅<br>
opacity: "0.8" ✅
        </code>
    </div>
</body>
</html>

