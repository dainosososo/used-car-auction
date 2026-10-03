<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>註冊 - 二手車拍賣</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f8f8; text-align: center; }
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
        .container { width: 350px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; cursor: pointer; }
        button:hover { background: #2980b9; }
        .error { color: red; }
    </style>
</head>
<body>
<!-- 頂部標題 -->
<div class="header">邠邠邠二手車拍賣網</div>

<div class="container">
    <h2>註冊</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="register_process.php" method="post">
        <input type="text" name="username" placeholder="帳號" required>
        <input type="email" name="email" placeholder="Email(可選)">
        <input type="password" name="password" placeholder="密碼" required>
        <input type="password" name="confirm_password" placeholder="確認密碼" required>
        <button type="submit">註冊</button>
    </form>
    <p>已有帳號？ <a href="login.php">登入</a></p>
</div>

</body>
</html>
