<?php
session_start();
require_once 'db_connect.php';

// If already logged in, go straight to files
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify password
        // Note: For this example, since we manually inserted a hash, 
        // ensure the hash in DB matches password_hash('password123', PASSWORD_DEFAULT)
        // If you just want to test easily without hashes first, use: if ($pass == $hashed_password)
        if (password_verify($pass, $hashed_password)) { 
            $_SESSION['user_id'] = $id;
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Semester 2</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        
        body {
            height: 100vh; width: 100vw; display: flex; flex-direction: column; 
            justify-content: center; align-items: center; overflow: hidden;
            background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 20%, #a1c4fd 40%, #c2e9fb 60%, #e0c3fc 80%, #8ec5fc 100%);
            background-size: 300% 300%; animation: gradientMove 12s ease infinite;
        }
        @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        .login-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            width: 350px;
            text-align: center;
            z-index: 2;
        }

        h2 { margin-bottom: 20px; color: #1d1d1f; font-weight: 600; }

        .input-group { margin-bottom: 15px; text-align: left; }
        
        input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.5);
            outline: none;
            transition: 0.3s;
            color: #333;
        }
        input:focus { background: rgba(255,255,255,0.8); box-shadow: 0 0 10px rgba(255,255,255,0.5); }

        .login-btn {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: #007AFF;
            color: white;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }
        .login-btn:hover { background: #005bb5; }

        .back-link {
            display: block; margin-top: 15px; color: #555; text-decoration: none; font-size: 0.9rem;
        }
        .back-link:hover { text-decoration: underline; }

        .error-msg { color: #d32f2f; font-size: 0.9rem; margin-bottom: 10px; }

        .orb { position: absolute; border-radius: 50%; filter: blur(80px); z-index: 0; opacity: 0.8; }
        .orb-1 { width: 300px; height: 300px; background: #ff9a9e; top: 10%; left: 20%; animation: float 6s ease-in-out infinite; }
        .orb-2 { width: 400px; height: 400px; background: #a1c4fd; bottom: 10%; right: 20%; animation: float 8s ease-in-out infinite reverse; }
        @keyframes float { 0% { transform: translate(0, 0); } 50% { transform: translate(20px, 40px); } 100% { transform: translate(0, 0); } }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <form class="login-card" method="POST" action="">
        <h2>Semester 2 Access</h2>
        
        <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        
        <button type="submit" class="login-btn">Login</button>
        <a href="../../index.html" class="back-link">← Back to Dashboard</a>
    </form>
</body>
</html>