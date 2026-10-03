<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <style>
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- 頂部標題 -->
    <div class="header">邠邠邠二手車拍賣網</div>
    <h2>用戶登入</h2>
    <form action="login_process.php" method="post">
        <label for="username">帳號：</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">密碼：</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <button type="submit">登入</button>
    </form>
</body>
</html>
