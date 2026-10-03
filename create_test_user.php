<?php
require 'db.php';  // 引入資料庫連線

$username = "testuser";
$password = "123456";  // 測試密碼
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // 密碼加密
$email = "test@example.com";

try {
    $stmt = $conn->prepare("INSERT INTO user (username, password, email) VALUES (:username, :password, :email)");
    $stmt->execute([
        "username" => $username,
        "password" => $hashedPassword,
        "email" => $email
    ]);
    echo "測試帳號建立成功！";
} catch (PDOException $e) {
    die("帳號建立失敗：" . $e->getMessage());
}
?>
