# 二手汽車拍賣網 (Secondhand Car Auction Website)

一個基於 PHP 與 MySQL 開發的二手汽車線上拍賣與展示平台，提供會員車輛瀏覽、上架拍賣、購物車管理、模擬結帳與即時留言諮詢功能。

---

## 📌 功能特色

- **會員系統**：
  - 使用者註冊、安全登入與登出
  - 個人資料管理與密碼變更
- **車輛拍賣與瀏覽**：
  - 瀏覽車輛拍賣清單，支援搜尋與分類
  - 車輛詳細規格、車況描述與多圖相片輪播展示
  - 會員可自行上架二手車輛（支援上傳多張車輛圖片）
  - 上架車輛管理與刪除下架功能
- **購物與結帳**：
  - 購物車功能（加入購物車、移除與數量調整）
  - 模擬訂單結帳流程
- **即時溝通**：
  - 買賣雙方即時在線留言與訊息諮詢系統

---

## 🛠️ 技術堆疊

- **後端語言**：PHP (使用 PDO 資料庫抽象層，有效防範 SQL Injection)
- **資料庫**：MySQL / MariaDB
- **前端技術**：HTML5, CSS3, JavaScript
- **檔案處理**：PHP 圖片上傳與伺服器端目錄存儲管理

---

## 🚀 快速安裝與運行

### 1. 取得專案原始碼
```bash
git clone https://github.com/dainosososo/used-car-auction.git
```

### 2. 設定資料庫
1. 啟動本機 MySQL 伺服器（例如透過 XAMPP、WampServer 或獨立 MySQL）。
2. 建立名為 `car_repaire_system` 的資料庫。
3. 匯入專案根目錄下的結構檔案 `car_repaire_system.sql`：
   ```sql
   mysql -u root -p car_repaire_system < car_repaire_system.sql
   ```

### 3. 配置連線資訊
複製 `db.example.php` 並命名為 `db.php`，修改為您當地的 MySQL 連線密碼：
```bash
cp db.example.php db.php
```
在 `db.php` 中確認連線參數：
```php
$host = "localhost";
$dbname = "car_repaire_system";
$username = "root";
$password = "your_actual_password"; // 請輸入您的 MySQL 密碼
```

### 4. 啟動伺服器
可直接使用 PHP 內建伺服器進行測試：
```bash
php -S localhost:8000
```
或放置於 Apache / Nginx / IIS 之網頁根目錄中，以瀏覽器訪問 `http://localhost:8000`。

---

## 📂 專案檔案結構

```text
├── uploads/                # 車輛上傳圖片存儲目錄
├── car_repaire_system.sql  # 資料庫資料表定義結構
├── db.example.php          # 資料庫連線配置範本
├── index.php               # 首頁
├── car_list.php            # 車輛清單
├── car_detailcase.php      # 車輛詳細頁面
├── upload_car.php          # 車輛上架表單
├── upload_car_process.php  # 車輛上架後端處理
├── delete_car.php          # 車輛下架刪除
├── cart.php                # 購物車頁面
├── checkout.php            # 結帳頁面
├── login.php               # 會員登入
├── register.php            # 會員註冊
├── user_file.php           # 會員資料中心
├── change_password.php     # 密碼修改
├── chat.php                # 即時聊天訊息頁面
├── fetch_messages.php      # 聊天室訊息非同步更新
└── README.md               # 專案說明文件
```
