<?php
// --- AUTHENTICATION CHECK ---
session_start();
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: ../server_r/login.php");
    exit;
}
// ----------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Resources</title>
    <style>
        /* --- Base Styles --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        
        body {
            height: 100vh; width: 100vw; display: flex; flex-direction: column; align-items: center; overflow: hidden;
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            background-size: 300% 300%; animation: gradientMove 15s ease infinite;
            color: #1d1d1f;
            padding-top: 80px;
        }
        @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        /* --- Back Button --- */
        .back-btn {
            position: absolute; top: 20px; left: 20px; padding: 10px 20px;
            background: rgba(255,255,255,0.3); backdrop-filter: blur(10px);
            border-radius: 20px; text-decoration: none; color: #333; font-weight: 600;
            border: 1px solid rgba(255,255,255,0.4); transition: 0.2s; z-index: 10;
        }
        .back-btn:hover { background: rgba(255,255,255,0.5); transform: translateY(-2px); }

        /* --- Main Container --- */
        .container {
            width: 90%; max-width: 800px;
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 30px;
            max-height: 80vh; overflow-y: auto;
            display: flex; flex-direction: column; gap: 15px;
        }

        h2 { margin-bottom: 20px; color: #333; font-weight: 700; opacity: 0.8; }

        /* --- File List Item --- */
        .file-item {
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.4);
            padding: 15px 20px;
            border-radius: 16px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .file-item:hover {
            background: rgba(255,255,255,0.7);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .file-info { display: flex; align-items: center; gap: 15px; }
        .file-icon { width: 24px; height: 24px; opacity: 0.7; color: #333; }
        .folder-icon { color: #007AFF; } /* Blue for folders */
        
        .download-icon { 
            background: #007AFF; color: white; border-radius: 50%; 
            padding: 8px; width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;
            font-size: 14px;
        }
        
        /* Different style for folder "enter" arrow */
        .enter-icon { background: rgba(0,0,0,0.1); color: #333; }

        .orb { position: absolute; border-radius: 50%; filter: blur(90px); z-index: -1; opacity: 0.6; }
        .orb-1 { width: 300px; height: 300px; background: #fda085; top: -50px; right: -50px; }
        .orb-2 { width: 300px; height: 300px; background: #8ec5fc; bottom: -50px; left: -50px; }
    </style>
</head>
<body>

    <a href="../index.php" class="back-btn">← Dashboard</a>
    
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <h2>Folder Resources</h2> 

        <?php
        // 1. Get current directory
        $dir = __DIR__;
        
        // 2. Scan for files
        $files = scandir($dir);
        
        // 3. Files to ignore
        $ignore = array('.', '..', 'index.php', '.DS_Store', 'error_log');

        // 4. Loop through files
        foreach ($files as $file) {
            if (!in_array($file, $ignore)) {
                
                // Determine if it's a sub-folder
                $isDir = is_dir($dir . '/' . $file);
                
                // Set Icon Logic
                if ($isDir) {
                    // Folder Icon
                    $iconSvg = '<svg class="file-icon folder-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
                    $actionIcon = '<div class="download-icon enter-icon">→</div>';
                    $downloadAttr = ''; // Folders don't download, they open
                    $link = $file . '/index.php'; // Assuming folders have an index inside, or just link to folder
                } else {
                    // File Icon
                    $iconSvg = '<svg class="file-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                    $actionIcon = '<div class="download-icon">↓</div>';
                    $downloadAttr = 'download';
                    $link = $file;
                }

                echo '
                <a href="'.$link.'" class="file-item" '.$downloadAttr.'>
                    <div class="file-info">
                        '.$iconSvg.'
                        <span>'.$file.'</span>
                    </div>
                    '.$actionIcon.'
                </a>
                ';
            }
        }
        
        // Optional: Message if empty
        if (count($files) <= count($ignore)) {
            echo '<div style="text-align:center; opacity:0.6; padding:20px;">No files found in this folder.</div>';
        }
        ?>

    </div>

    <h1 style="font-size: small; margin-top: 40px;">Designed & Developed By <a href="https://www.instagram.com/alfredjoejr">Alfred Joe Jr</a></h1>

</body>
</html>