<?php
// 資料庫連線配置範本
// 請將此檔案複製為 db.php 並填入您的實際資料庫密碼
$host = "localhost";            // 資料庫主機
$dbname = "car_repaire_system"; // 資料庫名稱
$username = "root";             // MySQL 帳號
$password = "your_password";    // MySQL 密碼 (請替換為您的密碼)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;port=3306", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>
