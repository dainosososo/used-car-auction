<?php
session_start();
require 'db.php'; // 確保連線資料庫

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 驗證密碼是否一致
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "密碼不一致！";
        header("Location: register.php");
        exit();
    }

    // 檢查帳號是否已存在
    $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "帳號已被註冊！";
        header("Location: register.php");
        exit();
    }

    // 加密密碼
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 新增使用者到資料庫
    $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $hashed_password])) {
        $_SESSION['success'] = "註冊成功，請登入！";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "註冊失敗，請重試！";
        header("Location: register.php");
        exit();
    }
}
?>
