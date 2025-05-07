<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireAuth() {
    if(!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Simple login for demo
if(isset($_POST['login'])) {
    if($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['user_id'] = 1;
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid credentials";
    }
}
?>