?php
session_start();
require 'db.php'; // 連線資料庫

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('請先登入'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// 查詢當前用戶的密碼（已加密）
$stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 驗證舊密碼是否正確
    if (!password_verify($old_password, $user['password'])) {
        echo "<script>alert('舊密碼錯誤'); window.location.href='change_password.php';</script>";
        exit();
    }

    // 檢查新密碼與確認密碼是否相同
    if ($new_password !== $confirm_password) {
        echo "<script>alert('新密碼與確認密碼不匹配'); window.location.href='change_password.php';</script>";
        exit();
    }

    // 加密新密碼
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 更新密碼到資料庫
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $user_id]);

    echo "<script>alert('密碼變更成功，請重新登入'); window.location.href='logout.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>變更密碼</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 1px 1px 10px rgba(0,0,0,0.1); }
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
    </style>
</head>
<body>
<!-- 頂部標題 -->
<div class="header">邠邠邠二手車拍賣網</div>
<div class="container">
    <h2>變更密碼</h2>
    <form action="change_password.php" method="post">
        <div class="mb-3">
            <label for="old_password" class="form-label">舊密碼：</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">新密碼：</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">確認新密碼：</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">變更密碼</button>
    </form>
</div>

</body>
</html>
