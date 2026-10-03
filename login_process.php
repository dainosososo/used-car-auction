<?php
session_start();
require 'db.php';   // 引入資料庫連線

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(["username" => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // **成功登入後直接跳轉到車輛資訊頁面**
            header("Location: car_list.php");
            exit();
        } else {
            echo "<script>alert('帳號或密碼錯誤！'); window.location.href='login.php';</script>";
        }
    } catch (PDOException $e) {
        die("登入失敗：" . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}
?>
