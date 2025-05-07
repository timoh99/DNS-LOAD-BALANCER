<?php
session_start();

// Database connection (create includes/db.php)
require 'db.php';

// Handle form submissions
$login_error = '';
$register_error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['login'])) {
        // Login logic
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $db->prepare("SELECT id, username FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, md5($password)]); // In production, use password_hash()
        
        if($user = $stmt->fetch()) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $login_error = "Invalid username or password";
        }
    } 
    elseif(isset($_POST['register'])) {
        // Registration logic
        $username = $_POST['regUsername'];
        $email = $_POST['regEmail'];
        $password = md5($_POST['regPassword']); // In production, use password_hash()
        
        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
            
            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } catch(PDOException $e) {
            $register_error = "Username or email already exists";
        }
    }
}


// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password']; // In production, this should be securely hashed
    
    // Validate credentials (replace with your authentication logic)
    if (validateCredentials($username, $password)) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit; // Important: stop script execution after redirect
    } else {
        $error = "Invalid username or password";
    }
}

// Your authentication function (replace with database check)
function validateCredentials($username, $password) {
    // Replace with actual database check
    // Example: return checkUserInDatabase($username, $password);
    return true; // Placeholder for example
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS Login Page</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container <?= isset($_POST['register']) ? 'active' : '' ?>">
        <!-- Login Form -->
        <div class="form-box login">
            <form method="POST" action="login.php">
                <h1>Login</h1>
                <?php if($login_error): ?>
                <div class="error-message"><?= htmlspecialchars($login_error) ?></div>
                <?php endif; ?>
                
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock'></i>
                </div>
                <div class="forget-link">
                    <a href="#">Forget Password?</a>
                </div>
                
                <button type="submit" name="login" class="btn">Login</button> 
                <p>or login with social media platform</p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="social-icon"><i class='bx bxl-twitter'></i></a>
                    <a href="#" class="social-icon"><i class='bx bxl-google'></i></a>
                    <a href="#" class="social-icon"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box register">
            <form method="POST" action="login.php">
                <h1>Register</h1>
                <?php if($register_error): ?>
                <div class="error-message"><?= htmlspecialchars($register_error) ?></div>
                <?php endif; ?>
                
                <div class="input-box">
                    <input type="text" name="regUsername" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="regEmail" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="regPassword" placeholder="Password" required>
                    <i class='bx bxs-lock'></i>
                </div>
                
                <button type="submit" name="register" class="btn">Register</button> 
                <p>or register with social media platform</p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="social-icon"><i class='bx bxl-twitter'></i></a>
                    <a href="#" class="social-icon"><i class='bx bxl-google'></i></a>
                    <a href="#" class="social-icon"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <!-- Toggle Box (unchanged) -->
        <div class="toggle-box"> 
            <div class="toggle-panel toggle-left">
                <h1>WELCOME TO DNS SOLUTIONS</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>
            
            <div class="toggle-panel toggle-right">
                <h1>WELCOME BACK</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>