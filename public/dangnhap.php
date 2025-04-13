<?php
// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin đăng nhập
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sử dụng prepared statements để tránh SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if ($password === $row['password']) { // So sánh trực tiếp mật khẩu
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: home.php"); // Điều hướng về trang chủ
            exit(); // Đảm bảo không tiếp tục thực thi mã phía sau
        } else {
            echo "<p style='color:red;'>Mật khẩu không đúng!</p>";
        }
    } else {
        echo "<p style='color:red;'>Tài khoản không tồn tại. Vui lòng thử lại!</p>";
    }

    $stmt->close(); // Đóng prepared statement
}

$conn->close(); // Đóng kết nối MySQL
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký / Đăng Nhập</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #141E30, #243B55);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: white;
            margin-bottom: 30px;
        }

        label {
            color: white;
            text-align: left;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            color: #333;
            font-size: 16px;
        }

        button {
            background-color: #FF6347;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #FF4500;
        }

        .link {
            color: #FF6347;
            text-decoration: none;
            font-size: 14px;
        }

        .link:hover {
            text-decoration: underline;
        }

        footer {
            font-size: 14px;
            color: white;
            margin-top: 20px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Đăng Nhập</h2>
        <form action="dangnhap.php" method="POST">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Đăng nhập</button>
        </form>

        <p>Chưa có tài khoản? <a href="dangky.php" class="link">Đăng ký ngay</a></p>
    </div>

</body>
</html>
