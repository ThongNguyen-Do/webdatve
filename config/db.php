<?php
$host = 'localhost';
$dbname = 'datve';
$username = 'root';  // Tài khoản mặc định của XAMPP
$password = '';      // Nếu không đặt mật khẩu cho MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
