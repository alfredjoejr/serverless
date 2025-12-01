<?php
session_start();

// --- CONFIGURATION ---
// Your Drive Folder Link
$drive_link = "https://drive.google.com/drive/folders/19rsgOE0XpOYyPr20_w9KqAlZqDgMt14X"; 
// ---------------------

// Authentication Check
if (!isset($_SESSION['sem1_user_id'])) {
    header("Location: server_r/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester 1 Resources</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        
        body {
            height: 100vh; 
            width: 100vw; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; /* Center content vertically */
            overflow: hidden; 
            
            /* Theme Background */
            background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 20%, #a1c4fd 40%, #c2e9fb 60%, #e0c3fc 80%, #8ec5fc 100%);
            background-size: 300% 300%; 
            animation: gradientMove 15s ease infinite;
            
            color: #1d1d1f;
        }
        
        @keyframes gradientMove { 
            0% { background-position: 0% 50%; } 
            50% { background-position: 100% 50%; } 
            100% { background-position: 0% 50%; } 
        }

        /* --- Buttons --- */
        .btn-nav {
            position: absolute;
            top: 20px;
            padding: 10px 20px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px; 
            text-decoration: none; 
            font-weight: 600;
            transition: 0.2s;
            font-size: 0.9rem;
            z-index: 20;
        }

        .back-btn {
            left: 20px;
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #333;
        }
        .back-btn:hover { background: rgba(255, 255, 255, 0.5); transform: translateY(-2px); }

        .logout-btn {
            right: 20px;
            background: rgba(255, 80, 80, 0.2);
            border: 1px solid rgba(255, 80, 80, 0.3);
            color: #8b0000;
        }
        .logout-btn:hover { background: rgba(255, 80, 80, 0.4); transform: translateY(-2px); }

        /* --- Center Card --- */
        .glass-card {
            width: 90%; 
            max-width: 500px;
            padding: 50px 30px;
            
            /* Glass Effect */
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 10;
        }

        .glass-card h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .glass-card p {
            margin-bottom: 30px;
            color: #555;
            font-size: 1.1rem;
        }

        .drive-btn {
            background: #007AFF;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 15px 40px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 122, 255, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .drive-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 122, 255, 0.4);
            background: #0066d6;
        }

        /* Decorative Orbs */
        .orb { position: absolute; border-radius: 50%; filter: blur(90px); z-index: 0; opacity: 0.6; pointer-events: none; }
        .orb-1 { width: 300px; height: 300px; background: #fda085; top: 10%; right: 20%; animation: float 6s ease-in-out infinite; }
        .orb-2 { width: 300px; height: 300px; background: #8ec5fc; bottom: 10%; left: 20%; animation: float 8s ease-in-out infinite reverse; }

        @keyframes float { 
            0% { transform: translate(0, 0); } 
            50% { transform: translate(20px, 40px); } 
            100% { transform: translate(0, 0); } 
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <a href="../index.html" class="btn-nav back-btn">← Dashboard</a>
    <a href="server_r/logout.php" class="btn-nav logout-btn">Logout</a>

    <div class="glass-card">
        <h1>Semester 1 Resources</h1>
        <p>Access files securely via Google Drive.</p>
        
        <a href="<?php echo $drive_link; ?>" target="_blank" class="drive-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                <path d="M12 18v-6"></path>
                <path d="m9 15 3 3 3-3"></path>
            </svg>
            Click here for Drive
        </a>
    </div>

</body>
</html>