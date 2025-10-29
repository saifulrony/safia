<?php
/**
 * Quick file verification script
 * Access at: http://your-site.com/wp-content/plugins/probuilder/check-file.php
 */

header('Content-Type: text/html; charset=utf-8');

$file = __DIR__ . '/assets/js/editor.js';

?>
<!DOCTYPE html>
<html>
<head>
    <title>ProBuilder File Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .ok { color: #4caf50; font-size: 48px; }
        .error { color: #f44336; font-size: 48px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .instruction {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-top: 20px;
        }
        .highlight {
            background: #fff9c4;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>📁 ProBuilder editor.js File Check</h1>
        
        <?php if (file_exists($file)): ?>
            <div class="ok">✓</div>
            <h2>File Found and Updated!</h2>
            
            <table>
                <tr>
                    <td>File Path:</td>
                    <td><code><?php echo $file; ?></code></td>
                </tr>
                <tr>
                    <td>File Size:</td>
                    <td><strong><?php echo number_format(filesize($file) / 1024, 2); ?> KB</strong></td>
                </tr>
                <tr>
                    <td>Last Modified:</td>
                    <td class="highlight"><strong><?php echo date('F d, Y H:i:s', filemtime($file)); ?></strong></td>
                </tr>
                <tr>
                    <td>Modified (Ago):</td>
                    <td><strong><?php 
                        $seconds = time() - filemtime($file);
                        if ($seconds < 60) {
                            echo $seconds . ' seconds ago';
                        } elseif ($seconds < 3600) {
                            echo round($seconds / 60) . ' minutes ago';
                        } elseif ($seconds < 86400) {
                            echo round($seconds / 3600) . ' hours ago';
                        } else {
                            echo round($seconds / 86400) . ' days ago';
                        }
                    ?></strong></td>
                </tr>
                <tr>
                    <td>Readable:</td>
                    <td><?php echo is_readable($file) ? '✓ Yes' : '✗ No'; ?></td>
                </tr>
                <tr>
                    <td>Writable:</td>
                    <td><?php echo is_writable($file) ? '✓ Yes' : '✗ No'; ?></td>
                </tr>
            </table>

            <div class="instruction">
                <h3>✅ File is Up-to-Date!</h3>
                <p>The file shows <strong><?php echo date('F d, Y H:i:s', filemtime($file)); ?></strong></p>
                <p>If this is recent (today), the changes are saved on the server.</p>
                <br>
                <h4>Next Step: Clear Your Browser Cache</h4>
                <ol>
                    <li><strong>Press Ctrl+Shift+R</strong> (Windows/Linux) or <strong>Cmd+Shift+R</strong> (Mac)</li>
                    <li>Go to ProBuilder editor</li>
                    <li>Try resizing a grid cell</li>
                    <li>It should resize smoothly now!</li>
                </ol>
            </div>

            <?php
            // Read first 500 chars to verify content
            $content = file_get_contents($file, false, null, 0, 500);
            ?>
            
            <h3 style="margin-top: 30px;">File Preview (First 500 characters):</h3>
            <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px;"><?php echo htmlspecialchars($content); ?>...</pre>

        <?php else: ?>
            <div class="error">✗</div>
            <h2>File Not Found!</h2>
            <p>The file <code><?php echo $file; ?></code> does not exist.</p>
            
            <div class="instruction">
                <h3>Possible Issues:</h3>
                <ul>
                    <li>File was not saved</li>
                    <li>Incorrect file path</li>
                    <li>Permission issues</li>
                </ul>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; text-align: center;">
            <a href="javascript:location.reload(true)" style="background: #2196f3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Refresh This Page</a>
        </div>
    </div>
</body>
</html>

